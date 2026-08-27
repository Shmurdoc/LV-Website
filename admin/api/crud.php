<?php
/**
 * Admin CRUD API — Viata Luxe Guesthouse
 * Handles all admin create/update/delete operations.
 */
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/includes/admin-functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_error('Method not allowed', 405);
}

require_admin();
if (!csrf_verify()) {
    json_error('Invalid CSRF token', 403);
}

$action = $_POST['action'] ?? '';
$entity = $_POST['entity'] ?? '';

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
    case 'setting':
        handleSetting($action);
        break;
    case 'contact_submission':
        handleContactSubmission($action);
        break;
    default:
        json_error('Unknown entity');
}

// ── PAGES ──
function handlePage(string $action): void {
    $db = Database::get();
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        $db->prepare('DELETE FROM pages WHERE id = ?')->execute([$id]);
        log_activity('delete', 'page', $id);
        json_response(['success' => true, 'redirect' => '/admin/pages']);
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
            'is_published' => isset($_POST['is_published']) ? 1 : 0,
            'is_homepage' => isset($_POST['is_homepage']) ? 1 : 0,
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
        ];
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
        json_response(['success' => true, 'redirect' => '/admin/pages']);
    }
    json_error('Invalid action');
}

// ── SECTIONS ──
function handleSection(string $action): void {
    $db = Database::get();
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        $db->prepare('DELETE FROM sections WHERE id = ?')->execute([$id]);
        $db->prepare('DELETE FROM section_orientation WHERE section_id = ?')->execute([$id]);
        log_activity('delete', 'section', $id);
        json_response(['success' => true, 'redirect' => '/admin/sections']);
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
        ];
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
        // Save orientation
        $orient = [
            'section_id' => $id,
            'layout' => trim($_POST['layout'] ?? 'text-left'),
            'background_color' => trim($_POST['background_color'] ?? ''),
            'text_color' => trim($_POST['text_color'] ?? ''),
            'alignment' => trim($_POST['alignment'] ?? 'left'),
            'animation' => trim($_POST['animation'] ?? 'fade-up'),
        ];
        $db->prepare('INSERT INTO section_orientation (section_id, layout, background_color, text_color, alignment, animation) VALUES (:section_id, :layout, :background_color, :text_color, :alignment, :animation) ON DUPLICATE KEY UPDATE layout = :layout2, background_color = :bg2, text_color = :tc2, alignment = :al2, animation = :an2')
            ->execute(array_merge($orient, ['layout2'=>$orient['layout'],'bg2'=>$orient['background_color'],'tc2'=>$orient['text_color'],'al2'=>$orient['alignment'],'an2'=>$orient['animation']]));
        json_response(['success' => true, 'redirect' => '/admin/sections']);
    }
    json_error('Invalid action');
}

// ── APARTMENTS ──
function handleApartment(string $action): void {
    $db = Database::get();
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        $db->prepare('DELETE FROM apartments WHERE id = ?')->execute([$id]);
        log_activity('delete', 'apartment', $id);
        json_response(['success' => true, 'redirect' => '/admin/apartments']);
    }
    if ($action === 'save') {
        $id = (int)($_POST['id'] ?? 0);
        require_fields($_POST, ['name', 'slug', 'page_id', 'price_per_night']);
        $data = [
            'page_id' => (int)$_POST['page_id'],
            'name' => trim($_POST['name']),
            'slug' => trim($_POST['slug']),
            'subtitle' => trim($_POST['subtitle'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'price_per_night' => (float)$_POST['price_per_night'],
            'max_guests' => (int)($_POST['max_guests'] ?? 2),
            'room_size_m2' => (float)($_POST['room_size_m2'] ?? 0),
            'bedrooms' => (int)($_POST['bedrooms'] ?? 1),
            'beds_description' => trim($_POST['beds_description'] ?? ''),
            'hero_image' => trim($_POST['hero_image'] ?? ''),
            'meta_title' => trim($_POST['meta_title'] ?? ''),
            'meta_description' => trim($_POST['meta_description'] ?? ''),
            'is_published' => isset($_POST['is_published']) ? 1 : 0,
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
        ];
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
        json_response(['success' => true, 'redirect' => '/admin/apartments']);
    }
    json_error('Invalid action');
}

// ── FAQS ──
function handleFaq(string $action): void {
    $db = Database::get();
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        $db->prepare('DELETE FROM faqs WHERE id = ?')->execute([$id]);
        log_activity('delete', 'faq', $id);
        json_response(['success' => true, 'redirect' => '/admin/faqs']);
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
        ];
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
        json_response(['success' => true, 'redirect' => '/admin/faqs']);
    }
    json_error('Invalid action');
}

// ── TESTIMONIALS ──
function handleTestimonial(string $action): void {
    $db = Database::get();
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        $db->prepare('DELETE FROM testimonials WHERE id = ?')->execute([$id]);
        log_activity('delete', 'testimonial', $id);
        json_response(['success' => true, 'redirect' => '/admin/testimonials']);
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
        ];
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
        json_response(['success' => true, 'redirect' => '/admin/testimonials']);
    }
    json_error('Invalid action');
}

// ── NAVIGATION ──
function handleNavigation(string $action): void {
    $db = Database::get();
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        $db->prepare('DELETE FROM navigation WHERE id = ?')->execute([$id]);
        log_activity('delete', 'navigation', $id);
        json_response(['success' => true, 'redirect' => '/admin/navigation']);
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
        ];
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
        json_response(['success' => true, 'redirect' => '/admin/navigation']);
    }
    json_error('Invalid action');
}

// ── SAFARI ──
function handleSafari(string $action): void {
    $db = Database::get();
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        $db->prepare('DELETE FROM safari_activities WHERE id = ?')->execute([$id]);
        log_activity('delete', 'safari', $id);
        json_response(['success' => true, 'redirect' => '/admin/safari']);
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
        ];
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
        json_response(['success' => true, 'redirect' => '/admin/safari']);
    }
    json_error('Invalid action');
}

// ── GALLERY CATEGORIES ──
function handleGalleryCategory(string $action): void {
    $db = Database::get();
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) json_error('Missing id');
        $db->prepare('DELETE FROM gallery_categories WHERE id = ?')->execute([$id]);
        log_activity('delete', 'gallery_category', $id);
        json_response(['success' => true, 'redirect' => '/admin/gallery']);
    }
    if ($action === 'save') {
        $id = (int)($_POST['id'] ?? 0);
        require_fields($_POST, ['name', 'slug']);
        $data = [
            'name' => trim($_POST['name']),
            'slug' => trim($_POST['slug']),
            'description' => trim($_POST['description'] ?? ''),
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'is_published' => isset($_POST['is_published']) ? 1 : 0,
        ];
        if ($id) {
            $sets = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($data)));
            $data['id'] = $id;
            $db->prepare("UPDATE gallery_categories SET $sets WHERE id = :id")->execute($data);
            log_activity('update', 'gallery_category', $id);
        } else {
            $cols = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_map(fn($k) => ":$k", array_keys($data)));
            $db->prepare("INSERT INTO gallery_categories ($cols, created_at) VALUES ($placeholders, NOW())")->execute($data);
            $id = (int)$db->lastInsertId();
            log_activity('create', 'gallery_category', $id);
        }
        json_response(['success' => true, 'redirect' => '/admin/gallery']);
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
        $db->prepare('DELETE FROM contact_submissions WHERE id = ?')->execute([$id]);
        log_activity('delete', 'contact_submission', $id);
        json_response(['success' => true, 'redirect' => '/admin/contact']);
    }
    json_error('Invalid action');
}
