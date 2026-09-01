<?php
/**
 * Admin CRUD API — Viata Luxe Guesthouse
 * Handles all admin create/update/delete operations.
 */
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../includes/admin-functions.php';
require_once __DIR__ . '/../includes/rbac.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_error('Method not allowed', 405);
}

require_admin();
if (!csrf_verify()) {
    json_error('Invalid CSRF token', 403);
}

// ── RBAC: entity → required permission ──
$crudPermMap = [
    'page'              => 'pages.write',
    'section'           => 'sections.write',
    'apartment'         => 'apartments.write',
    'faq'               => 'faqs.write',
    'testimonial'       => 'testimonials.write',
    'navigation'        => 'navigation.manage',
    'safari'            => 'safari.write',
    'gallery_category'  => 'gallery.write',
    'gallery_image'     => 'gallery.write',
    'apartment_image'   => 'apartments.write',
    'apartment_amenity' => 'apartments.write',
    'page_seo'          => 'pages.write',
    'setting'           => 'settings.manage',
    'contact_submission'=> 'contact.read',
    'hero_slide'        => 'hero.write',
    'promise_pillar'    => 'promise.write',
    'moment'            => 'moments.write',
    'dining_item'       => 'dining.write',
    'public_category'   => 'categories.write',
];
// Bulk actions that only need read (mark_read is not a write)
$bulkReadActions = ['mark_read'];
$entityForPerm = $_POST['entity'] ?? '';
$actionForPerm = $_POST['action'] ?? '';
if ($entityForPerm && isset($crudPermMap[$entityForPerm])) {
    $needsWrite = !in_array($actionForPerm, $bulkReadActions, true) && $actionForPerm !== 'mark_read';
    if ($needsWrite) {
        require_permission($crudPermMap[$entityForPerm]);
    }
}

$action = $_POST['action'] ?? '';
$entity = $_POST['entity'] ?? '';

// ── BULK ACTIONS — must run BEFORE the switch (switch handlers exit on unknown action) ──
if (str_starts_with($action, 'bulk_')) {
    // RBAC already checked above
    $bulkAction = substr($action, 5);
    $idsRaw = $_POST['ids'] ?? '';
    $ids = array_filter(array_map('intval', explode(',', $idsRaw)));
    if (empty($ids)) json_error('No IDs provided');
    $db = Database::get();
    $entityTableMap = [
        'page' => 'pages', 'section' => 'sections', 'apartment' => 'apartments',
        'faq' => 'faqs', 'testimonial' => 'testimonials', 'navigation' => 'navigation',
        'safari' => 'safari_activities', 'gallery_category' => 'public_categories',
        'gallery_image' => 'gallery_images', 'apartment_image' => 'apartment_images',
        'apartment_amenity' => 'apartment_amenities', 'contact_submission' => 'contact_submissions',
        'hero_slide' => 'hero_slides', 'promise_pillar' => 'promise_pillars',
        'moment' => 'moments', 'dining_item' => 'dining_items',
    ];
    $table = $entityTableMap[$entity] ?? '';
    if (!$table) json_error('Unknown entity for bulk action');
    $hardDeleteTables = ['public_categories'];
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    if ($bulkAction === 'delete') {
        if (in_array($table, $hardDeleteTables, true)) {
            try {
                $db->prepare("DELETE FROM `$table` WHERE id IN ($placeholders)")->execute($ids);
            } catch (PDOException $e) {
                if ($e->getCode() === '23000') {
                    json_error('Cannot delete: category still has images. Remove images first.', 409);
                }
                throw $e;
            }
        } else {
            $db->prepare("UPDATE `$table` SET deleted_at = NOW() WHERE id IN ($placeholders) AND deleted_at IS NULL")->execute($ids);
        }
        log_activity('bulk_delete', $entity, null, ['ids' => $ids]);
    } elseif ($bulkAction === 'unpublish') {
        $activeColumn = null;
        if (in_array($table, ['public_categories'], true)) {
            $activeColumn = 'is_active';
        } elseif (in_array($table, ['pages', 'sections', 'apartments', 'faqs', 'testimonials', 'navigation', 'safari_activities'], true)) {
            $activeColumn = 'is_published';
        }
        if ($activeColumn) {
            $db->prepare("UPDATE `$table` SET `$activeColumn` = 0 WHERE id IN ($placeholders)")->execute($ids);
            log_activity('bulk_unpublish', $entity, null, ['ids' => $ids]);
        } else {
            json_error('Entity does not support unpublish');
        }
    } else {
        json_error('Unknown bulk action: ' . $bulkAction);
    }
    json_response(['success' => true, 'count' => count($ids)]);
}

switch ($entity) {
    case 'page':
        handlePage($action);
        break;
    case 'section':
        handleSection($action);
        break;
    case 'apartment':
        handleApartment($action);
        break;
    case 'faq':
        handleFaq($action);
        break;
    case 'testimonial':
        handleTestimonial($action);
        break;
    case 'navigation':
        handleNavigation($action);
        break;
    case 'safari':
        handleSafari($action);
        break;
    case 'gallery_category':
        handleGalleryCategory($action);
        break;
    case 'gallery_image':
        handleGalleryImage($action);
        break;
    case 'apartment_image':
        handleApartmentImage($action);
        break;
    case 'apartment_amenity':
        handleApartmentAmenity($action);
        break;
    case 'page_seo':
        handlePageSeo($action);
        break;
    case 'setting':
        handleSetting($action);
        break;
    case 'contact_submission':
        handleContactSubmission($action);
        break;
    case 'hero_slide':
        handleHeroSlide($action);
        break;
    case 'promise_pillar':
        handlePromisePillar($action);
        break;
    case 'moment':
        handleMoment($action);
        break;
    case 'dining_item':
        handleDiningItem($action);
        break;
    case 'public_category':
        handlePublicCategory($action);
        break;
    default:
        json_error('Unknown entity');
}

// ── SOFT-DELETE HELPERS ──
function soft_delete(string $table, int $id): void {
    $db = Database::get();
    $allowed = ['pages','sections','apartments','apartment_images','gallery_images','safari_activities','testimonials','navigation','hero_slides','faqs','contact_submissions','dining_items','promise_pillars','moments','page_seo','section_orientation'];
    if (!in_array($table, $allowed, true)) { json_error('Invalid table'); return; }
    $db->prepare("UPDATE `$table` SET deleted_at = NOW() WHERE id = ? AND deleted_at IS NULL")->execute([$id]);
}

function restore_entity(string $table, int $id): void {
    $db = Database::get();
    $allowed = ['pages','sections','apartments','apartment_images','gallery_images','safari_activities','testimonials','navigation','hero_slides','faqs','contact_submissions','dining_items','promise_pillars','moments','page_seo','section_orientation'];
    if (!in_array($table, $allowed, true)) { json_error('Invalid table'); return; }
    $db->prepare("UPDATE `$table` SET deleted_at = NULL WHERE id = ?")->execute([$id]);
}

function permanent_delete(string $table, int $id, string $entity_type): void {
    $db = Database::get();
    $allowed = ['pages','sections','apartments','apartment_images','gallery_images','safari_activities','testimonials','navigation','hero_slides','faqs','contact_submissions','dining_items','promise_pillars','moments','page_seo','section_orientation'];
    if (!in_array($table, $allowed, true)) { json_error('Invalid table'); return; }
    $db->prepare("DELETE FROM `$table` WHERE id = ?")->execute([$id]);
    log_activity('permanent_delete', $entity_type, $id);
}

// Extract visible_from/visible_until from POST data
function visibility_fields(): array {
    return [
        'visible_from' => !empty($_POST['visible_from']) ? trim($_POST['visible_from']) : null,
        'visible_until' => !empty($_POST['visible_until']) ? trim($_POST['visible_until']) : null,
    ];
}

// ── PAGES ──
function handlePage(string $action): void {
    $db = Database::get();
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        soft_delete('pages', $id);
        log_activity('delete', 'page', $id);
        json_response(['success' => true, 'redirect' => url('/admin/pages')]);
    }
    if ($action === 'restore') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        restore_entity('pages', $id);
        log_activity('restore', 'page', $id);
        json_response(['success' => true, 'redirect' => url('/admin/pages')]);
    }
    if ($action === 'permanent_delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        permanent_delete('pages', $id, 'page');
        json_response(['success' => true, 'redirect' => url('/admin/pages?trash=1')]);
    }
    if ($action === 'save') {
        $id = (int)($_POST['id'] ?? 0);
        require_fields($_POST, ['slug', 'title']);
        $data = [
            'slug' => trim($_POST['slug']),
            'title' => trim($_POST['title']),
            'subtitle' => trim($_POST['subtitle'] ?? ''),
            'meta_title' => trim($_POST['meta_title'] ?? ''),
            'meta_description' => trim($_POST['meta_description'] ?? ''),
            'template' => trim($_POST['template'] ?? 'default'),
            'hero_image' => trim($_POST['hero_image'] ?? ''),
            'hero_kicker' => trim($_POST['hero_kicker'] ?? ''),
            'hero_align' => trim($_POST['hero_align'] ?? 'center'),
            'is_published' => isset($_POST['is_published']) ? 1 : 0,
            'is_homepage' => isset($_POST['is_homepage']) ? 1 : 0,
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
        ] + visibility_fields();
        if ($id) {
            $sets = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($data)));
            $data['id'] = $id;
            $db->prepare("UPDATE pages SET $sets, updated_at = NOW() WHERE id = :id")->execute($data);
            log_activity('update', 'page', $id, $data);
        } else {
            $cols = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_map(fn($k) => ":$k", array_keys($data)));
            $db->prepare("INSERT INTO pages ($cols, created_at) VALUES ($placeholders, NOW())")->execute($data);
            $id = (int)$db->lastInsertId();
            log_activity('create', 'page', $id, $data);
        }
        json_response(['success' => true, 'redirect' => url('/admin/pages')]);
    }
    json_error('Invalid action');
}

// ── SECTIONS ──
function handleSection(string $action): void {
    $db = Database::get();
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        soft_delete('sections', $id);
        log_activity('delete', 'section', $id);
        json_response(['success' => true, 'redirect' => url('/admin/sections')]);
    }
    if ($action === 'restore') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        restore_entity('sections', $id);
        log_activity('restore', 'section', $id);
        json_response(['success' => true, 'redirect' => url('/admin/sections')]);
    }
    if ($action === 'permanent_delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        permanent_delete('sections', $id, 'section');
        $db->prepare('DELETE FROM section_orientation WHERE section_id = ?')->execute([$id]);
        json_response(['success' => true, 'redirect' => url('/admin/sections?trash=1')]);
    }
    if ($action === 'save') {
        $id = (int)($_POST['id'] ?? 0);
        require_fields($_POST, ['section_type', 'page_id']);
        $data = [
            'page_id' => (int)$_POST['page_id'],
            'section_type' => trim($_POST['section_type']),
            'title' => trim($_POST['title'] ?? ''),
            'subtitle' => trim($_POST['subtitle'] ?? ''),
            'content' => trim($_POST['content'] ?? ''),
            'image' => trim($_POST['image'] ?? ''),
            'link_url' => trim($_POST['link_url'] ?? ''),
            'link_text' => trim($_POST['link_text'] ?? ''),
            'css_class' => trim($_POST['css_class'] ?? ''),
            'is_visible' => isset($_POST['is_visible']) ? 1 : 0,
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
        ] + visibility_fields();
        if ($id) {
            $sets = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($data)));
            $data['id'] = $id;
            $db->prepare("UPDATE sections SET $sets, updated_at = NOW() WHERE id = :id")->execute($data);
            log_activity('update', 'section', $id);
        } else {
            $cols = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_map(fn($k) => ":$k", array_keys($data)));
            $db->prepare("INSERT INTO sections ($cols, created_at) VALUES ($placeholders, NOW())")->execute($data);
            $id = (int)$db->lastInsertId();
            log_activity('create', 'section', $id);
        }
        // Save orientation — all fields (full DB alignment, no hardcoded omission)
        $orient = [
            'section_id' => $id,
            'layout' => trim($_POST['layout'] ?? 'text-left'),
            'background_color' => trim($_POST['background_color'] ?? '') ?: null,
            'background_image' => trim($_POST['background_image'] ?? '') ?: null,
            'text_color' => trim($_POST['text_color'] ?? '') ?: null,
            'padding_top' => trim($_POST['padding_top'] ?? '4rem'),
            'padding_bottom' => trim($_POST['padding_bottom'] ?? '4rem'),
            'padding_left' => trim($_POST['padding_left'] ?? '2rem'),
            'padding_right' => trim($_POST['padding_right'] ?? '2rem'),
            'max_width' => trim($_POST['max_width'] ?? '1200px'),
            'alignment' => trim($_POST['alignment'] ?? 'left'),
            'vertical_alignment' => trim($_POST['vertical_alignment'] ?? 'center'),
            'animation' => trim($_POST['animation'] ?? 'fade-up'),
            'responsive_stack' => trim($_POST['responsive_stack'] ?? 'stack'),
        ];
        $db->prepare('INSERT INTO section_orientation (section_id, layout, background_color, background_image, text_color, padding_top, padding_bottom, padding_left, padding_right, max_width, alignment, vertical_alignment, animation, responsive_stack)
            VALUES (:section_id, :layout, :background_color, :background_image, :text_color, :padding_top, :padding_bottom, :padding_left, :padding_right, :max_width, :alignment, :vertical_alignment, :animation, :responsive_stack)
            ON DUPLICATE KEY UPDATE layout=VALUES(layout), background_color=VALUES(background_color), background_image=VALUES(background_image), text_color=VALUES(text_color), padding_top=VALUES(padding_top), padding_bottom=VALUES(padding_bottom), padding_left=VALUES(padding_left), padding_right=VALUES(padding_right), max_width=VALUES(max_width), alignment=VALUES(alignment), vertical_alignment=VALUES(vertical_alignment), animation=VALUES(animation), responsive_stack=VALUES(responsive_stack)')
            ->execute($orient);
        json_response(['success' => true, 'redirect' => url('/admin/sections')]);
    }
    json_error('Invalid action');
}

// ── APARTMENTS ──
function handleApartment(string $action): void {
    $db = Database::get();
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        soft_delete('apartments', $id);
        log_activity('delete', 'apartment', $id);
        json_response(['success' => true, 'redirect' => url('/admin/apartments')]);
    }
    if ($action === 'restore') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        restore_entity('apartments', $id);
        log_activity('restore', 'apartment', $id);
        json_response(['success' => true, 'redirect' => url('/admin/apartments')]);
    }
    if ($action === 'permanent_delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        permanent_delete('apartments', $id, 'apartment');
        json_response(['success' => true, 'redirect' => url('/admin/apartments?trash=1')]);
    }
    if ($action === 'save') {
        $id = (int)($_POST['id'] ?? 0);
        require_fields($_POST, ['name', 'slug', 'page_id', 'price_per_night']);
        $price = (float)$_POST['price_per_night'];
        if ($price <= 0) json_error('Price must be greater than 0');
        if ($price > 100000) json_error('Price seems too high — max R100,000 per night');
        // features JSON hygiene — handle both array (from features[] inputs) and raw JSON string
        $featuresPost = $_POST['features'] ?? '';
        if (is_array($featuresPost)) {
            $featuresPost = array_filter(array_map('trim', $featuresPost), 'strlen');
            $features = !empty($featuresPost) ? json_encode($featuresPost) : null;
        } else {
            $featuresRaw = trim($featuresPost);
            $features = null;
            if ($featuresRaw !== '') {
                $decoded = json_decode($featuresRaw, true);
                if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
                    json_error('features must be valid JSON array');
                }
                $features = $featuresRaw;
            }
        }
        $data = [
            'page_id' => (int)$_POST['page_id'],
            'name' => trim($_POST['name']),
            'slug' => trim($_POST['slug']),
            'subtitle' => trim($_POST['subtitle'] ?? ''),
            'tagline' => trim($_POST['tagline'] ?? '') ?: null,
            'description' => trim($_POST['description'] ?? ''),
            'price_per_night' => $price,
            'max_guests' => (int)($_POST['max_guests'] ?? 2),
            'room_size_m2' => (float)($_POST['room_size_m2'] ?? 0),
            'bedrooms' => (int)($_POST['bedrooms'] ?? 1),
            'bathrooms' => (int)($_POST['bathrooms'] ?? 1),
            'beds_description' => trim($_POST['beds_description'] ?? ''),
            'features' => $features,
            'hero_image' => trim($_POST['hero_image'] ?? ''),
            'meta_title' => trim($_POST['meta_title'] ?? ''),
            'meta_description' => trim($_POST['meta_description'] ?? ''),
            'is_published' => isset($_POST['is_published']) ? 1 : 0,
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
        ] + visibility_fields();
        if ($id) {
            $sets = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($data)));
            $data['id'] = $id;
            $db->prepare("UPDATE apartments SET $sets, updated_at = NOW() WHERE id = :id")->execute($data);
            log_activity('update', 'apartment', $id);
        } else {
            $cols = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_map(fn($k) => ":$k", array_keys($data)));
            $db->prepare("INSERT INTO apartments ($cols, created_at) VALUES ($placeholders, NOW())")->execute($data);
            $id = (int)$db->lastInsertId();
            log_activity('create', 'apartment', $id);
        }
        json_response(['success' => true, 'redirect' => url('/admin/apartments')]);
    }
    json_error('Invalid action');
}

// ── FAQS ──
function handleFaq(string $action): void {
    $db = Database::get();
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        soft_delete('faqs', $id);
        log_activity('delete', 'faq', $id);
        json_response(['success' => true, 'redirect' => url('/admin/faqs')]);
    }
    if ($action === 'restore') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        restore_entity('faqs', $id);
        log_activity('restore', 'faq', $id);
        json_response(['success' => true, 'redirect' => url('/admin/faqs')]);
    }
    if ($action === 'permanent_delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        permanent_delete('faqs', $id, 'faq');
        json_response(['success' => true, 'redirect' => url('/admin/faqs?trash=1')]);
    }
    if ($action === 'save') {
        $id = (int)($_POST['id'] ?? 0);
        require_fields($_POST, ['question', 'answer']);
        $data = [
            'page_id' => $_POST['page_id'] ? (int)$_POST['page_id'] : null,
            'question' => trim($_POST['question']),
            'answer' => trim($_POST['answer']),
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'is_published' => isset($_POST['is_published']) ? 1 : 0,
        ] + visibility_fields();
        if ($id) {
            $sets = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($data)));
            $data['id'] = $id;
            $db->prepare("UPDATE faqs SET $sets, updated_at = NOW() WHERE id = :id")->execute($data);
            log_activity('update', 'faq', $id);
        } else {
            $cols = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_map(fn($k) => ":$k", array_keys($data)));
            $db->prepare("INSERT INTO faqs ($cols, created_at) VALUES ($placeholders, NOW())")->execute($data);
            $id = (int)$db->lastInsertId();
            log_activity('create', 'faq', $id);
        }
        json_response(['success' => true, 'redirect' => url('/admin/faqs')]);
    }
    json_error('Invalid action');
}

// ── TESTIMONIALS ──
function handleTestimonial(string $action): void {
    $db = Database::get();
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        soft_delete('testimonials', $id);
        log_activity('delete', 'testimonial', $id);
        json_response(['success' => true, 'redirect' => url('/admin/testimonials')]);
    }
    if ($action === 'restore') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        restore_entity('testimonials', $id);
        log_activity('restore', 'testimonial', $id);
        json_response(['success' => true, 'redirect' => url('/admin/testimonials')]);
    }
    if ($action === 'permanent_delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        permanent_delete('testimonials', $id, 'testimonial');
        json_response(['success' => true, 'redirect' => url('/admin/testimonials?trash=1')]);
    }
    if ($action === 'save') {
        $id = (int)($_POST['id'] ?? 0);
        require_fields($_POST, ['reviewer_name', 'review_text']);
        $data = [
            'apartment_id' => $_POST['apartment_id'] ? (int)$_POST['apartment_id'] : null,
            'reviewer_name' => trim($_POST['reviewer_name']),
            'review_text' => trim($_POST['review_text']),
            'rating' => (int)($_POST['rating'] ?? 5),
            'source' => trim($_POST['source'] ?? 'direct'),
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'is_published' => isset($_POST['is_published']) ? 1 : 0,
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
        ] + visibility_fields();
        if ($id) {
            $sets = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($data)));
            $data['id'] = $id;
            $db->prepare("UPDATE testimonials SET $sets WHERE id = :id")->execute($data);
            log_activity('update', 'testimonial', $id);
        } else {
            $cols = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_map(fn($k) => ":$k", array_keys($data)));
            $db->prepare("INSERT INTO testimonials ($cols, created_at) VALUES ($placeholders, NOW())")->execute($data);
            $id = (int)$db->lastInsertId();
            log_activity('create', 'testimonial', $id);
        }
        json_response(['success' => true, 'id' => $id, 'redirect' => url('/admin/testimonials')]);
    }
    json_error('Invalid action');
}

// ── NAVIGATION ──
function handleNavigation(string $action): void {
    $db = Database::get();
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        soft_delete('navigation', $id);
        log_activity('delete', 'navigation', $id);
        json_response(['success' => true, 'redirect' => url('/admin/navigation')]);
    }
    if ($action === 'restore') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        restore_entity('navigation', $id);
        log_activity('restore', 'navigation', $id);
        json_response(['success' => true, 'redirect' => url('/admin/navigation')]);
    }
    if ($action === 'permanent_delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        permanent_delete('navigation', $id, 'navigation');
        json_response(['success' => true, 'redirect' => url('/admin/navigation?trash=1')]);
    }
    if ($action === 'save') {
        $id = (int)($_POST['id'] ?? 0);
        require_fields($_POST, ['label']);
        $data = [
            'label' => trim($_POST['label']),
            'url' => trim($_POST['url'] ?? ''),
            'page_id' => $_POST['page_id'] ? (int)$_POST['page_id'] : null,
            'parent_id' => $_POST['parent_id'] ? (int)$_POST['parent_id'] : null,
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'is_published' => isset($_POST['is_published']) ? 1 : 0,
            'open_in_new_tab' => isset($_POST['open_in_new_tab']) ? 1 : 0,
            'css_class' => trim($_POST['css_class'] ?? ''),
        ] + visibility_fields();
        if ($id) {
            $sets = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($data)));
            $data['id'] = $id;
            $db->prepare("UPDATE navigation SET $sets WHERE id = :id")->execute($data);
            log_activity('update', 'navigation', $id);
        } else {
            $cols = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_map(fn($k) => ":$k", array_keys($data)));
            $db->prepare("INSERT INTO navigation ($cols, created_at) VALUES ($placeholders, NOW())")->execute($data);
            $id = (int)$db->lastInsertId();
            log_activity('create', 'navigation', $id);
        }
        json_response(['success' => true, 'redirect' => url('/admin/navigation')]);
    }
    json_error('Invalid action');
}

// ── SAFARI ──
function handleSafari(string $action): void {
    $db = Database::get();
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        soft_delete('safari_activities', $id);
        log_activity('delete', 'safari', $id);
        json_response(['success' => true, 'redirect' => url('/admin/safari')]);
    }
    if ($action === 'restore') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        restore_entity('safari_activities', $id);
        log_activity('restore', 'safari', $id);
        json_response(['success' => true, 'redirect' => url('/admin/safari')]);
    }
    if ($action === 'permanent_delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        permanent_delete('safari_activities', $id, 'safari');
        json_response(['success' => true, 'redirect' => url('/admin/safari?trash=1')]);
    }
    if ($action === 'save') {
        $id = (int)($_POST['id'] ?? 0);
        require_fields($_POST, ['title']);
        $data = [
            'title' => trim($_POST['title']),
            'content' => trim($_POST['content'] ?? ''),
            'image' => trim($_POST['image'] ?? ''),
            'link_url' => trim($_POST['link_url'] ?? ''),
            'link_text' => trim($_POST['link_text'] ?? ''),
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'is_published' => isset($_POST['is_published']) ? 1 : 0,
        ] + visibility_fields();
        if ($id) {
            $sets = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($data)));
            $data['id'] = $id;
            $db->prepare("UPDATE safari_activities SET $sets, updated_at = NOW() WHERE id = :id")->execute($data);
            log_activity('update', 'safari', $id);
        } else {
            $cols = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_map(fn($k) => ":$k", array_keys($data)));
            $db->prepare("INSERT INTO safari_activities ($cols, created_at) VALUES ($placeholders, NOW())")->execute($data);
            $id = (int)$db->lastInsertId();
            log_activity('create', 'safari', $id);
        }
        json_response(['success' => true, 'redirect' => url('/admin/safari')]);
    }
    json_error('Invalid action');
}

// ── GALLERY CATEGORIES ──
// NOTE: public_categories has no deleted_at column — use hard delete only.
// The column is is_active (not is_published).
function handleGalleryCategory(string $action): void {
    $db = Database::get();
    if ($action === 'delete' || $action === 'permanent_delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        try {
            $db->prepare('DELETE FROM public_categories WHERE id = ?')->execute([$id]);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                json_error('Cannot delete: category still has images. Move or delete images first.', 409);
            }
            throw $e;
        }
        log_activity('delete', 'gallery_category', $id);
        json_response(['success' => true, 'redirect' => url('/admin/gallery')]);
    }
    if ($action === 'save') {
        $id = (int)($_POST['id'] ?? 0);
        require_fields($_POST, ['name', 'slug']);
        $data = [
            'name' => trim($_POST['name']),
            'slug' => trim($_POST['slug']),
            'description' => trim($_POST['description'] ?? ''),
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ];
        if ($id) {
            $sets = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($data)));
            $data['id'] = $id;
            $db->prepare("UPDATE public_categories SET $sets WHERE id = :id")->execute($data);
            log_activity('update', 'gallery_category', $id);
        } else {
            $cols = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_map(fn($k) => ":$k", array_keys($data)));
            $db->prepare("INSERT INTO public_categories ($cols, entity_type, created_at) VALUES ($placeholders, 'gallery', NOW())")->execute($data);
            $id = (int)$db->lastInsertId();
            log_activity('create', 'gallery_category', $id);
        }
        json_response(['success' => true, 'redirect' => url('/admin/gallery')]);
    }
    json_error('Invalid action');
}

// ── GALLERY IMAGES ──
function handleGalleryImage(string $action): void {
    $db = Database::get();
    $catId = (int)($_POST['category_id'] ?? 0);
    $catParam = $catId ? "?category_id=$catId" : '';
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        soft_delete('gallery_images', $id);
        log_activity('delete', 'gallery_image', $id);
        json_response(['success' => true, 'redirect' => url("/admin/gallery/images$catParam")]);
    }
    if ($action === 'restore') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        restore_entity('gallery_images', $id);
        log_activity('restore', 'gallery_image', $id);
        json_response(['success' => true, 'redirect' => url("/admin/gallery/images$catParam")]);
    }
    if ($action === 'permanent_delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        permanent_delete('gallery_images', $id, 'gallery_image');
        json_response(['success' => true, 'redirect' => url('/admin/gallery?trash=1')]);
    }
    if ($action === 'save') {
        $id = (int)($_POST['id'] ?? 0);
        require_fields($_POST, ['category_id', 'image_path']);
        $data = [
            'public_category_id' => (int)$_POST['category_id'],
            'image_path'  => trim($_POST['image_path']),
            'alt_text'    => trim($_POST['alt_text'] ?? ''),
            'caption'     => trim($_POST['caption'] ?? ''),
            'sort_order'  => (int)($_POST['sort_order'] ?? 0),
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
        ] + visibility_fields();
        if ($id) {
            $sets = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($data)));
            $data['id'] = $id;
            $db->prepare("UPDATE gallery_images SET $sets WHERE id = :id")->execute($data);
            log_activity('update', 'gallery_image', $id);
        } else {
            $cols = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_map(fn($k) => ":$k", array_keys($data)));
            $db->prepare("INSERT INTO gallery_images ($cols) VALUES ($placeholders)")->execute($data);
            $id = (int)$db->lastInsertId();
            log_activity('create', 'gallery_image', $id);
        }
        json_response(['success' => true, 'redirect' => url('/admin/gallery/images?category_id=' . $data['public_category_id'])]);
    }
    json_error('Invalid action');
}

// ── APARTMENT IMAGES ──
function handleApartmentImage(string $action): void {
    $db = Database::get();
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        soft_delete('apartment_images', $id);
        log_activity('delete', 'apartment_image', $id);
        json_response(['success' => true, 'redirect' => url('/admin/apartments')]);
    }
    if ($action === 'restore') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        restore_entity('apartment_images', $id);
        log_activity('restore', 'apartment_image', $id);
        json_response(['success' => true, 'redirect' => url('/admin/apartments')]);
    }
    if ($action === 'permanent_delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        permanent_delete('apartment_images', $id, 'apartment_image');
        json_response(['success' => true, 'redirect' => url('/admin/apartments?trash=1')]);
    }
    if ($action === 'save') {
        $id = (int)($_POST['id'] ?? 0);
        require_fields($_POST, ['apartment_id', 'image_path']);
        $data = [
            'apartment_id' => (int)$_POST['apartment_id'],
            'image_path'   => trim($_POST['image_path']),
            'alt_text'     => trim($_POST['alt_text'] ?? ''),
            'caption'      => trim($_POST['caption'] ?? ''),
            'sort_order'   => (int)($_POST['sort_order'] ?? 0),
            'is_hero'      => isset($_POST['is_hero']) ? 1 : 0,
        ] + visibility_fields();
        if ($id) {
            $sets = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($data)));
            $data['id'] = $id;
            $db->prepare("UPDATE apartment_images SET $sets WHERE id = :id")->execute($data);
            log_activity('update', 'apartment_image', $id);
        } else {
            $cols = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_map(fn($k) => ":$k", array_keys($data)));
            $db->prepare("INSERT INTO apartment_images ($cols) VALUES ($placeholders)")->execute($data);
            $id = (int)$db->lastInsertId();
            log_activity('create', 'apartment_image', $id);
        }
        json_response(['success' => true, 'redirect' => url('/admin/apartments')]);
    }
    json_error('Invalid action');
}

// ── APARTMENT AMENITIES ──
function handleApartmentAmenity(string $action): void {
    $db = Database::get();
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        soft_delete('apartment_amenities', $id);
        log_activity('delete', 'apartment_amenity', $id);
        json_response(['success' => true, 'redirect' => url('/admin/apartments')]);
    }
    if ($action === 'restore') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        restore_entity('apartment_amenities', $id);
        log_activity('restore', 'apartment_amenity', $id);
        json_response(['success' => true, 'redirect' => url('/admin/apartments')]);
    }
    if ($action === 'permanent_delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        permanent_delete('apartment_amenities', $id, 'apartment_amenity');
        json_response(['success' => true, 'redirect' => url('/admin/apartments?trash=1')]);
    }
    if ($action === 'save') {
        $id = (int)($_POST['id'] ?? 0);
        require_fields($_POST, ['apartment_id', 'amenity_name']);
        $data = [
            'apartment_id' => (int)$_POST['apartment_id'],
            'amenity_name' => trim($_POST['amenity_name']),
            'amenity_icon' => trim($_POST['amenity_icon'] ?? ''),
            'sort_order'   => (int)($_POST['sort_order'] ?? 0),
        ];
        if ($id) {
            $sets = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($data)));
            $data['id'] = $id;
            $db->prepare("UPDATE apartment_amenities SET $sets WHERE id = :id")->execute($data);
            log_activity('update', 'apartment_amenity', $id);
        } else {
            $cols = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_map(fn($k) => ":$k", array_keys($data)));
            $db->prepare("INSERT INTO apartment_amenities ($cols) VALUES ($placeholders)")->execute($data);
            $id = (int)$db->lastInsertId();
            log_activity('create', 'apartment_amenity', $id);
        }
        json_response(['success' => true, 'redirect' => url('/admin/apartments')]);
    }
    json_error('Invalid action');
}

// ── PAGE SEO ──
function handlePageSeo(string $action): void {
    $db = Database::get();
    if ($action === 'save') {
        $page_id = (int)($_POST['page_id'] ?? 0);
        if (!$page_id) json_error('Missing page_id');
        $data = [
            'page_id'       => $page_id,
            'schema_type'   => trim($_POST['schema_type'] ?? 'WebPage'),
            'schema_json'   => trim($_POST['schema_json'] ?? ''),
            'additional_meta' => trim($_POST['additional_meta'] ?? ''),
        ];
        // Validate JSON if provided
        if ($data['schema_json'] !== '' && json_decode($data['schema_json']) === null && $data['schema_json'] !== 'null') {
            json_error('schema_json must be valid JSON');
        }
        if ($data['additional_meta'] !== '' && json_decode($data['additional_meta']) === null && $data['additional_meta'] !== 'null') {
            json_error('additional_meta must be valid JSON');
        }
        $db->prepare('INSERT INTO page_seo (page_id, schema_type, schema_json, additional_meta) VALUES (:page_id, :schema_type, :schema_json, :additional_meta) ON DUPLICATE KEY UPDATE schema_type = VALUES(schema_type), schema_json = VALUES(schema_json), additional_meta = VALUES(additional_meta)')
            ->execute($data);
        log_activity('update', 'page_seo', $page_id);
        json_response(['success' => true, 'redirect' => url('/admin/pages')]);
    }
    json_error('Invalid action');
}

// ── SETTINGS ──
function handleSetting(string $action): void {
    if ($action === 'save') {
        $key = trim($_POST['key'] ?? '');
        $value = trim($_POST['value'] ?? '');
        if (!$key) json_error('Missing key');
        save_setting($key, $value);
        log_activity('update', 'setting', null, ['key' => $key]);
        json_response(['success' => true]);
    }
    json_error('Invalid action');
}

// ── TRACK B: HERO SLIDES ──
function handleHeroSlide(string $action): void {
    $db = Database::get();
    if ($action === 'delete') { $id=(int)($_POST['id']??0); if(!$id) json_error('Missing id'); soft_delete('hero_slides',$id); log_activity('delete','hero_slide',$id); json_response(['success'=>true,'redirect'=>url('/admin/hero-slides')]); }
    if ($action === 'restore') { $id=(int)($_POST['id']??0); if(!$id) json_error('Missing id'); restore_entity('hero_slides',$id); log_activity('restore','hero_slide',$id); json_response(['success'=>true,'redirect'=>url('/admin/hero-slides')]); }
    if ($action === 'permanent_delete') { $id=(int)($_POST['id']??0); if(!$id) json_error('Missing id'); permanent_delete('hero_slides',$id,'hero_slide'); json_response(['success'=>true,'redirect'=>url('/admin/hero-slides?trash=1')]); }
    if ($action === 'save') {
        $id=(int)($_POST['id']??0);
        require_fields($_POST,['image_path']);
        $data=['page_id'=>(int)($_POST['page_id']??1),'image_path'=>trim($_POST['image_path']),'alt_text'=>trim($_POST['alt_text']??''),'caption'=>trim($_POST['caption']??''),'link_url'=>trim($_POST['link_url']??''),'sort_order'=>(int)($_POST['sort_order']??0),'is_published'=>isset($_POST['is_published'])?1:0] + visibility_fields();
        if($id){ $sets=implode(', ',array_map(fn($k)=>"$k=:$k",array_keys($data))); $data['id']=$id; $db->prepare("UPDATE hero_slides SET $sets, updated_at=NOW() WHERE id=:id")->execute($data); log_activity('update','hero_slide',$id); }
        else { $cols=implode(', ',array_keys($data)); $ph=implode(', ',array_map(fn($k)=>":$k",array_keys($data))); $db->prepare("INSERT INTO hero_slides ($cols) VALUES ($ph)")->execute($data); $id=(int)$db->lastInsertId(); log_activity('create','hero_slide',$id); }
        json_response(['success'=>true,'redirect'=>url('/admin/hero-slides')]);
    }
    json_error('Invalid action');
}
function handlePromisePillar(string $action): void {
    $db = Database::get();
    if ($action === 'delete') { $id=(int)($_POST['id']??0); if(!$id) json_error('Missing id'); soft_delete('promise_pillars',$id); log_activity('delete','promise_pillar',$id); json_response(['success'=>true,'redirect'=>url('/admin/promise-pillars')]); }
    if ($action === 'restore') { $id=(int)($_POST['id']??0); if(!$id) json_error('Missing id'); restore_entity('promise_pillars',$id); log_activity('restore','promise_pillar',$id); json_response(['success'=>true,'redirect'=>url('/admin/promise-pillars')]); }
    if ($action === 'permanent_delete') { $id=(int)($_POST['id']??0); if(!$id) json_error('Missing id'); permanent_delete('promise_pillars',$id,'promise_pillar'); json_response(['success'=>true,'redirect'=>url('/admin/promise-pillars?trash=1')]); }
    if ($action === 'save') {
        $id=(int)($_POST['id']??0);
        require_fields($_POST,['title','text']);
        $data=['page_id'=>(int)($_POST['page_id']??1),'icon'=>trim($_POST['icon']??''),'title'=>trim($_POST['title']),'text'=>trim($_POST['text']),'link_url'=>trim($_POST['link_url']??''),'sort_order'=>(int)($_POST['sort_order']??0),'is_published'=>isset($_POST['is_published'])?1:0] + visibility_fields();
        if($id){ $sets=implode(', ',array_map(fn($k)=>"$k=:$k",array_keys($data))); $data['id']=$id; $db->prepare("UPDATE promise_pillars SET $sets, updated_at=NOW() WHERE id=:id")->execute($data); log_activity('update','promise_pillar',$id); }
        else { $cols=implode(', ',array_keys($data)); $ph=implode(', ',array_map(fn($k)=>":$k",array_keys($data))); $db->prepare("INSERT INTO promise_pillars ($cols) VALUES ($ph)")->execute($data); $id=(int)$db->lastInsertId(); log_activity('create','promise_pillar',$id); }
        json_response(['success'=>true,'redirect'=>url('/admin/promise-pillars')]);
    }
    json_error('Invalid action');
}
function handleMoment(string $action): void {
    $db = Database::get();
    if ($action === 'delete') { $id=(int)($_POST['id']??0); if(!$id) json_error('Missing id'); soft_delete('moments',$id); log_activity('delete','moment',$id); json_response(['success'=>true,'redirect'=>url('/admin/moments')]); }
    if ($action === 'restore') { $id=(int)($_POST['id']??0); if(!$id) json_error('Missing id'); restore_entity('moments',$id); log_activity('restore','moment',$id); json_response(['success'=>true,'redirect'=>url('/admin/moments')]); }
    if ($action === 'permanent_delete') { $id=(int)($_POST['id']??0); if(!$id) json_error('Missing id'); permanent_delete('moments',$id,'moment'); json_response(['success'=>true,'redirect'=>url('/admin/moments?trash=1')]); }
    if ($action === 'save') {
        $id=(int)($_POST['id']??0);
        require_fields($_POST,['title','image_path']);
        $data=['page_id'=>(int)($_POST['page_id']??1),'kicker'=>trim($_POST['kicker']??''),'title'=>trim($_POST['title']),'text'=>trim($_POST['text']??''),'image_path'=>trim($_POST['image_path']),'alt_text'=>trim($_POST['alt_text']??''),'sort_order'=>(int)($_POST['sort_order']??0),'is_published'=>isset($_POST['is_published'])?1:0] + visibility_fields();
        if($id){ $sets=implode(', ',array_map(fn($k)=>"$k=:$k",array_keys($data))); $data['id']=$id; $db->prepare("UPDATE moments SET $sets, updated_at=NOW() WHERE id=:id")->execute($data); log_activity('update','moment',$id); }
        else { $cols=implode(', ',array_keys($data)); $ph=implode(', ',array_map(fn($k)=>":$k",array_keys($data))); $db->prepare("INSERT INTO moments ($cols) VALUES ($ph)")->execute($data); $id=(int)$db->lastInsertId(); log_activity('create','moment',$id); }
        json_response(['success'=>true,'redirect'=>url('/admin/moments')]);
    }
    json_error('Invalid action');
}
function handleDiningItem(string $action): void {
    $db = Database::get();
    if ($action === 'delete') { $id=(int)($_POST['id']??0); if(!$id) json_error('Missing id'); soft_delete('dining_items',$id); log_activity('delete','dining_item',$id); json_response(['success'=>true,'redirect'=>url('/admin/dining')]); }
    if ($action === 'restore') { $id=(int)($_POST['id']??0); if(!$id) json_error('Missing id'); restore_entity('dining_items',$id); log_activity('restore','dining_item',$id); json_response(['success'=>true,'redirect'=>url('/admin/dining')]); }
    if ($action === 'permanent_delete') { $id=(int)($_POST['id']??0); if(!$id) json_error('Missing id'); permanent_delete('dining_items',$id,'dining_item'); json_response(['success'=>true,'redirect'=>url('/admin/dining?trash=1')]); }
    if ($action === 'save') {
        $id=(int)($_POST['id']??0);
        require_fields($_POST,['title']);
        $data=['page_id'=>(int)($_POST['page_id']??1),'title'=>trim($_POST['title']),'time_label'=>trim($_POST['time_label']??''),'text'=>trim($_POST['text']??''),'icon'=>trim($_POST['icon']??''),'sort_order'=>(int)($_POST['sort_order']??0),'is_published'=>isset($_POST['is_published'])?1:0] + visibility_fields();
        if($id){ $sets=implode(', ',array_map(fn($k)=>"$k=:$k",array_keys($data))); $data['id']=$id; $db->prepare("UPDATE dining_items SET $sets, updated_at=NOW() WHERE id=:id")->execute($data); log_activity('update','dining_item',$id); }
        else { $cols=implode(', ',array_keys($data)); $ph=implode(', ',array_map(fn($k)=>":$k",array_keys($data))); $db->prepare("INSERT INTO dining_items ($cols) VALUES ($ph)")->execute($data); $id=(int)$db->lastInsertId(); log_activity('create','dining_item',$id); }
        json_response(['success'=>true,'redirect'=>url('/admin/dining')]);
    }
    json_error('Invalid action');
}

// ── CONTACT SUBMISSIONS ──
function handleContactSubmission(string $action): void {
    $db = Database::get();
    if ($action === 'mark_read') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        $db->prepare('UPDATE contact_submissions SET is_read = 1 WHERE id = ?')->execute([$id]);
        json_response(['success' => true]);
    }
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        soft_delete('contact_submissions', $id);
        log_activity('delete', 'contact_submission', $id);
        json_response(['success' => true, 'redirect' => url('/admin/contact')]);
    }
    if ($action === 'restore') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        restore_entity('contact_submissions', $id);
        log_activity('restore', 'contact_submission', $id);
        json_response(['success' => true, 'redirect' => url('/admin/contact')]);
    }
    if ($action === 'permanent_delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        permanent_delete('contact_submissions', $id, 'contact_submission');
        json_response(['success' => true, 'redirect' => url('/admin/contact?trash=1')]);
    }
    json_error('Invalid action');
}

// ── PUBLIC CATEGORIES (TAXONOMY) ──
function handlePublicCategory(string $action): void {
    $db = Database::get();
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        try {
            $db->prepare('DELETE FROM public_categories WHERE id = ?')->execute([$id]);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                json_error('Cannot delete: category is still in use. Remove linked images or apartments first.', 409);
            }
            throw $e;
        }
        log_activity('delete', 'public_category', $id);
        json_response(['success' => true, 'redirect' => url('/admin/categories')]);
    }
    if ($action === 'save') {
        $id = (int)($_POST['id'] ?? 0);
        require_fields($_POST, ['entity_type', 'name', 'slug']);
        $data = [
            'entity_type' => trim($_POST['entity_type']),
            'name'        => trim($_POST['name']),
            'slug'        => trim($_POST['slug']),
            'description' => trim($_POST['description'] ?? ''),
            'sort_order'  => (int)($_POST['sort_order'] ?? 0),
            'is_active'   => isset($_POST['is_active']) ? 1 : 0,
        ];
        if ($id) {
            $sets = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($data)));
            $data['id'] = $id;
            $db->prepare("UPDATE public_categories SET $sets WHERE id = :id")->execute($data);
            log_activity('update', 'public_category', $id);
        } else {
            $cols = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_map(fn($k) => ":$k", array_keys($data)));
            $db->prepare("INSERT INTO public_categories ($cols) VALUES ($placeholders)")->execute($data);
            $id = (int)$db->lastInsertId();
            log_activity('create', 'public_category', $id);
        }
        json_response(['success' => true, 'redirect' => url('/admin/categories')]);
    }
    json_error('Invalid action');
}
