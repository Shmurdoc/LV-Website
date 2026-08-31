<?php
/**
 * Global Helper Functions — Viata Luxe Guesthouse
 */

require_once __DIR__ . '/db.php';

// =====================================================
// SETTINGS
// =====================================================

/**
 * Get a single setting value by key
 */
function setting(string $key, string $default = ''): string
{
    static $cache = [];
    if (isset($cache[$key])) {
        return $cache[$key];
    }
    $db = Database::get();
    try {
        // site_settings is canonical table; global_settings is VIEW alias (kept for B/C)
        $stmt = $db->prepare('SELECT setting_value FROM site_settings WHERE setting_key = :key LIMIT 1');
        $stmt->execute(['key' => $key]);
        $result = $stmt->fetch();
        $cache[$key] = $result ? $result['setting_value'] : $default;
    } catch (Throwable $e) {
        $cache[$key] = $default;
    }
    return $cache[$key];
}

/**
 * Get all settings in a group as key=>value array
 */
function settings_group(string $group): array
{
    static $cache = [];
    if (isset($cache[$group])) {
        return $cache[$group];
    }
    $db = Database::get();
    try {
        // site_settings.setting_group is canonical; no is_active column (removed fallback masking)
        $stmt = $db->prepare('SELECT setting_key, setting_value FROM site_settings WHERE setting_group = :group ORDER BY sort_order ASC');
        $stmt->execute(['group' => $group]);
        $rows = $stmt->fetchAll();
        $cache[$group] = [];
        foreach ($rows as $row) {
            $cache[$group][$row['setting_key']] = $row['setting_value'];
        }
    } catch (Throwable $e) {
        $cache[$group] = [];
    }
    return $cache[$group];
}

// =====================================================
// SOFT-DELETE & VISIBILITY FILTERING
// =====================================================

/**
 * Filter a query result set to exclude soft-deleted rows
 * and respect visibility windows (visible_from / visible_until).
 *
 * Usage in admin:
 *   $results = active_only($results);  // normal list
 *   $results = active_only($results, include_deleted: true);  // trash view
 *   $results = active_only($results, include_scheduled: true); // all including scheduled
 *
 * Usage on frontend: always excludes deleted + future-scheduled + expired
 */
function active_only(
    array $rows,
    bool $include_deleted = false,
    bool $include_scheduled = false
): array {
    $now = date('Y-m-d H:i:s');
    return array_filter($rows, function ($row) use ($now, $include_deleted, $include_scheduled) {
        // Soft-delete filter
        if (!$include_deleted && !empty($row['deleted_at'])) {
            return false;
        }
        // Visibility scheduling filter (only for rows that have the columns)
        if (isset($row['visible_from']) || isset($row['visible_until'])) {
            if (!$include_scheduled) {
                if (!empty($row['visible_from']) && $row['visible_from'] > $now) {
                    return false; // Not yet visible (scheduled)
                }
                if (!empty($row['visible_until']) && $row['visible_until'] < $now) {
                    return false; // Expired
                }
            }
        }
        return true;
    });
}

/**
 * Build WHERE clause for soft-delete + visibility on raw SQL.
 * Appends conditions to $params array by reference.
 *
 * Usage:
 *   $where = active_where($params, 'pages');
 *   $db->prepare("SELECT * FROM pages $where ORDER BY sort_order")->execute($params);
 */
function active_where(
    array &$params,
    string $table = 'p',
    bool $include_deleted = false,
    bool $include_scheduled = false
): string {
    $conditions = [];
    // include_deleted=true = no filter (show both active + trashed); fix: previously added IS NOT NULL (trash-only) — bug
    if (!$include_deleted) {
        $conditions[] = "$table.deleted_at IS NULL";
    }
    if (!$include_scheduled) {
        $now = date('Y-m-d H:i:s');
        $conditions[] = "($table.visible_from IS NULL OR $table.visible_from <= :_vis_from)";
        $conditions[] = "($table.visible_until IS NULL OR $table.visible_until >= :_vis_until)";
        $params['_vis_from'] = $now;
        $params['_vis_until'] = $now;
    }
    return $conditions ? ' WHERE ' . implode(' AND ', $conditions) : '';
}

// =====================================================
// PAGES
// =====================================================

/**
 * Get a page by slug
 */
function get_page(string $slug): ?array
{
    $db = Database::get();
    try {
        $stmt = $db->prepare('SELECT * FROM pages WHERE slug = :slug AND is_published = 1 AND deleted_at IS NULL LIMIT 1');
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch() ?: null;
    } catch (Throwable $e) {
        return null;
    }
}

/**
 * Get all pages ordered by id
 */
function get_all_pages(): array
{
    $db = Database::get();
    $stmt = $db->query('SELECT * FROM pages ORDER BY id ASC');
    return $stmt->fetchAll();
}

/**
 * Get SEO record for a page (schema_json + additional_meta)
 * Separation: keeps DB logic out of templates/header.php
 */
function get_page_seo(int $page_id): ?array
{
    try {
        $db = Database::get();
        $stmt = $db->prepare('SELECT schema_type, schema_json, additional_meta FROM page_seo WHERE page_id = :id LIMIT 1');
        $stmt->execute(['id' => $page_id]);
        return $stmt->fetch() ?: null;
    } catch (Throwable $e) {
        return null;
    }
}

// =====================================================
// SECTIONS
// =====================================================

/**
 * Get all visible sections for a page, with orientation data
 */
function get_sections(int $page_id): array
{
    $db = Database::get();
    try {
        $stmt = $db->prepare('
            SELECT s.id, s.page_id, s.section_type, s.title, s.subtitle, s.content, s.image,
                   s.link_url, s.link_text, s.css_class, s.sort_order,
                   s.is_visible, s.visible_from, s.visible_until, s.deleted_at,
                   so.layout, so.background_color, so.background_image, so.text_color,
                   so.padding_top, so.padding_bottom, so.padding_left, so.padding_right,
                   so.max_width, so.alignment, so.vertical_alignment, so.animation, so.responsive_stack
            FROM sections s
            LEFT JOIN section_orientation so ON so.section_id = s.id
            WHERE s.page_id = :page_id AND s.is_visible = 1
              AND s.deleted_at IS NULL
              AND (s.visible_from IS NULL OR s.visible_from <= NOW())
              AND (s.visible_until IS NULL OR s.visible_until >= NOW())
            ORDER BY s.sort_order ASC
        ');
        $stmt->execute(['page_id' => $page_id]);
        return $stmt->fetchAll();
    } catch (Throwable $e) {
        error_log('get_sections failed: ' . $e->getMessage());
        return [];
    }
}

/**
 * Get a single section by ID
 */
function get_section(int $section_id): ?array
{
    $db = Database::get();
    try {
        $stmt = $db->prepare('
            SELECT s.*, so.layout, so.background_color, so.background_image, so.text_color,
                   so.padding_top, so.padding_bottom, so.padding_left, so.padding_right,
                   so.max_width, so.alignment, so.vertical_alignment, so.animation, so.responsive_stack
            FROM sections s
            LEFT JOIN section_orientation so ON so.section_id = s.id
            WHERE s.id = :id
        ');
        $stmt->execute(['id' => $section_id]);
        return $stmt->fetch() ?: null;
    } catch (Throwable $e) {
        return null;
    }
}

// =====================================================
// NAVIGATION
// =====================================================

/**
 * Get full navigation tree (top-level + children)
 */
function get_navigation(): array
{
    $db = Database::get();
    try {
        $stmt = $db->prepare('
            SELECT n.id, n.label, n.url, n.page_id, n.parent_id, n.sort_order,
                   n.open_in_new_tab, n.css_class, p.slug AS page_slug,
                   n.visible_from, n.visible_until, n.deleted_at
            FROM navigation n
            LEFT JOIN pages p ON p.id = n.page_id
            WHERE n.is_published = 1 AND n.deleted_at IS NULL
              AND (n.visible_from IS NULL OR n.visible_from <= NOW())
              AND (n.visible_until IS NULL OR n.visible_until >= NOW())
            ORDER BY n.sort_order ASC
        ');
        $stmt->execute();
        $rows = $stmt->fetchAll();
    } catch (Throwable $e) {
        // navigation table missing — provide hardcoded fallback (logs warning to error_log)
        error_log('get_navigation fallback: ' . $e->getMessage());
        $rows = [
            ['id' => 1, 'label' => 'Home',          'url' => '',          'page_id' => null, 'parent_id' => null, 'sort_order' => 1, 'open_in_new_tab' => 0, 'css_class' => '', 'page_slug' => 'home'],
            ['id' => 2, 'label' => 'Accommodation',  'url' => null,       'page_id' => null, 'parent_id' => null, 'sort_order' => 2, 'open_in_new_tab' => 0, 'css_class' => '', 'page_slug' => 'accommodation'],
            ['id' => 3, 'label' => 'Safari',          'url' => null,       'page_id' => null, 'parent_id' => null, 'sort_order' => 3, 'open_in_new_tab' => 0, 'css_class' => '', 'page_slug' => 'safari'],
            ['id' => 4, 'label' => 'Gallery',         'url' => null,       'page_id' => null, 'parent_id' => null, 'sort_order' => 4, 'open_in_new_tab' => 0, 'css_class' => '', 'page_slug' => 'gallery'],
            ['id' => 5, 'label' => 'Contact',         'url' => null,       'page_id' => null, 'parent_id' => null, 'sort_order' => 5, 'open_in_new_tab' => 0, 'css_class' => '', 'page_slug' => 'contact'],
        ];
    }

    // Build tree
    $top = [];
    $children = [];
    foreach ($rows as $row) {
        if ($row['parent_id'] === null) {
            $top[] = $row;
        } else {
            $children[$row['parent_id']][] = $row;
        }
    }
    // Attach children to parents
    foreach ($top as &$item) {
        $item['children'] = $children[$item['id']] ?? [];
    }
    return $top;
}

// =====================================================
// APARTMENTS
// =====================================================

/**
 * Get all published apartments — static cache, no N+1, respects soft-delete + visibility
 * Uses single query; callers should reuse result rather than querying per-apartment
 */
function get_apartments(): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }
    $db = Database::get();
    // Explicitly filter deleted_at + is_published, order by sort_order (pricing highlights via is_featured check in template, not ORDER)
    $stmt = $db->query('SELECT * FROM apartments WHERE is_published = 1 AND deleted_at IS NULL AND (visible_from IS NULL OR visible_from <= NOW()) AND (visible_until IS NULL OR visible_until >= NOW()) ORDER BY sort_order ASC');
    $cache = $stmt->fetchAll();
    return $cache;
}

/**
 * Get the featured apartment — is_featured=1, fallback to first by sort_order
 * Wrapper owned by Track A (contract)
 */
function get_featured_apartment(): ?array
{
    $apartments = get_apartments();
    foreach ($apartments as $apt) {
        if (!empty($apt['is_featured'])) {
            return $apt;
        }
    }
    return $apartments[0] ?? null;
}

/**
 * Get a single apartment by slug
 */
function get_apartment(string $slug): ?array
{
    $db = Database::get();
    $stmt = $db->prepare('SELECT * FROM apartments WHERE slug = :slug AND is_published = 1 AND deleted_at IS NULL AND (visible_from IS NULL OR visible_from <= NOW()) AND (visible_until IS NULL OR visible_until >= NOW()) LIMIT 1');
    $stmt->execute(['slug' => $slug]);
    return $stmt->fetch() ?: null;
}

/**
 * Get apartment by ID
 */
function get_apartment_by_id(int $id): ?array
{
    $db = Database::get();
    $stmt = $db->prepare('SELECT * FROM apartments WHERE id = :id AND deleted_at IS NULL LIMIT 1');
    $stmt->execute(['id' => $id]);
    return $stmt->fetch() ?: null;
}

/**
 * Get images for an apartment — static grouped cache, single query, no N+1 (like amenities)
 */
function get_apartment_images(int $apartment_id): array
{
    static $grouped = null;
    if ($grouped === null) {
        $db = Database::get();
        $rows = $db->query('SELECT * FROM apartment_images WHERE deleted_at IS NULL ORDER BY apartment_id ASC, sort_order ASC')->fetchAll();
        $grouped = [];
        foreach ($rows as $r) { $grouped[(int)$r['apartment_id']][] = $r; }
    }
    return $grouped[$apartment_id] ?? [];
}

/**
 * Get testimonials for a specific apartment (DB-driven, no author_name/quote phantom cols)
 */
function get_apartment_testimonials(int $apartment_id): array
{
    $db = Database::get();
    $stmt = $db->prepare('SELECT * FROM testimonials WHERE apartment_id = :id AND is_published = 1 AND deleted_at IS NULL ORDER BY sort_order ASC LIMIT 1');
    $stmt->execute(['id' => $apartment_id]);
    return $stmt->fetchAll();
}

/**
 * Get amenities for an apartment — static grouped cache, single query, no N+1
 */
function get_apartment_amenities(int $apartment_id): array
{
    static $grouped = null;
    if ($grouped === null) {
        $db = Database::get();
        $rows = $db->query('SELECT * FROM apartment_amenities WHERE deleted_at IS NULL ORDER BY apartment_id ASC, sort_order ASC')->fetchAll();
        $grouped = [];
        foreach ($rows as $r) { $grouped[(int)$r['apartment_id']][] = $r; }
    }
    return $grouped[$apartment_id] ?? [];
}

// =====================================================
// TESTIMONIALS
// =====================================================

/**
 * Get featured testimonials — respects deleted_at + visibility windows
 */
function get_featured_testimonials(): array
{
    $db = Database::get();
    $stmt = $db->query('SELECT * FROM testimonials WHERE is_featured = 1 AND is_published = 1 AND deleted_at IS NULL ORDER BY sort_order ASC');
    return $stmt->fetchAll();
}

// =====================================================
// GALLERY
// =====================================================

/**
 * Get gallery categories with image counts
 */
function get_gallery_categories(): array
{
    $db = Database::get();
    try {
        $stmt = $db->query("
            SELECT gc.*, COUNT(gi.id) AS image_count
            FROM public_categories gc
            LEFT JOIN gallery_images gi ON gi.public_category_id = gc.id AND gi.deleted_at IS NULL
            WHERE gc.entity_type = 'gallery' AND gc.is_active = 1
            GROUP BY gc.id
            ORDER BY gc.sort_order ASC
        ");
        return $stmt->fetchAll();
    } catch (Throwable $e) {
        return [];
    }
}

/**
 * Get images for a gallery category (by category_id)
 */
function get_gallery_images(int $category_id): array
{
    $db = Database::get();
    $stmt = $db->prepare('SELECT * FROM gallery_images WHERE public_category_id = :cat AND deleted_at IS NULL ORDER BY sort_order ASC');
    $stmt->execute(['cat' => $category_id]);
    return $stmt->fetchAll();
}

/**
 * Get featured gallery images (for homepage preview) — single JOIN query, no N+1, no fallback masking
 * Filters gi.is_featured=1 (seeded 8 preview images), respects soft-delete + category visibility
 */
function get_featured_gallery(int $limit = 8): array
{
    $db = Database::get();
    $stmt = $db->prepare('
        SELECT gi.image_path, gi.alt_text, gi.caption, gc.name AS category_name
        FROM gallery_images gi
        JOIN public_categories gc ON gi.public_category_id = gc.id
        WHERE gi.is_featured = 1
          AND gi.deleted_at IS NULL
          AND gc.is_active = 1
        ORDER BY gi.sort_order ASC, gi.id ASC
        LIMIT :limit
    ');
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

// =====================================================
// SAFARI
// =====================================================

/**
 * Get all published safari activities
 */
function get_safari_activities(): array
{
    $db = Database::get();
    try {
        $stmt = $db->query('SELECT * FROM safari_activities WHERE is_published = 1 ORDER BY sort_order ASC');
        return $stmt->fetchAll();
    } catch (Throwable $e) {
        return [];
    }
}

// =====================================================
// FAQS
// =====================================================

/**
 * Get FAQs (optionally for a specific page)
 */
function get_faqs(?int $page_id = null): array
{
    $db = Database::get();
    try {
        if ($page_id) {
            $stmt = $db->prepare('SELECT * FROM faqs WHERE page_id = :page_id AND is_published = 1 ORDER BY sort_order ASC');
            $stmt->execute(['page_id' => $page_id]);
        } else {
            $stmt = $db->query('SELECT * FROM faqs WHERE is_published = 1 ORDER BY sort_order ASC');
        }
        return $stmt->fetchAll();
    } catch (Throwable $e) {
        return [];
    }
}

// =====================================================
// TRACK B — HERO / PROMISE / MOMENTS / DINING (Builder B)
// Contract: new tables hero_slides, promise_pillars, moments, dining_items — B owns, A reads only
// Helpers: get_hero_slides(), get_promise_pillars(), get_moments(), get_dining_items()
// =====================================================

/**
 * Get hero slides for a page — respects is_published, soft-delete, visibility window, ORDER BY sort_order
 * Single query, no N+1, no fallback masking
 */
function get_hero_slides(int $page_id = 1): array
{
    $db = Database::get();
    try {
        $now = date('Y-m-d H:i:s');
        $stmt = $db->prepare('SELECT * FROM hero_slides WHERE page_id = :page_id AND is_published = 1 AND deleted_at IS NULL AND (visible_from IS NULL OR visible_from <= :vf) AND (visible_until IS NULL OR visible_until >= :vu) ORDER BY sort_order ASC');
        $stmt->execute(['page_id' => $page_id, 'vf' => $now, 'vu' => $now]);
        return $stmt->fetchAll();
    } catch (Throwable $e) {
        return [];
    }
}

/**
 * Get promise pillars for a page — respects soft-delete + visibility
 */
function get_promise_pillars(int $page_id = 1): array
{
    $db = Database::get();
    try {
        $now = date('Y-m-d H:i:s');
        $stmt = $db->prepare('SELECT * FROM promise_pillars WHERE page_id = :page_id AND is_published = 1 AND deleted_at IS NULL AND (visible_from IS NULL OR visible_from <= :vf) AND (visible_until IS NULL OR visible_until >= :vu) ORDER BY sort_order ASC');
        $stmt->execute(['page_id' => $page_id, 'vf' => $now, 'vu' => $now]);
        return $stmt->fetchAll();
    } catch (Throwable $e) {
        return [];
    }
}

/**
 * Get moments for a page — respects soft-delete + visibility
 */
function get_moments(int $page_id = 1): array
{
    $db = Database::get();
    try {
        $now = date('Y-m-d H:i:s');
        $stmt = $db->prepare('SELECT * FROM moments WHERE page_id = :page_id AND is_published = 1 AND deleted_at IS NULL AND (visible_from IS NULL OR visible_from <= :vf) AND (visible_until IS NULL OR visible_until >= :vu) ORDER BY sort_order ASC');
        $stmt->execute(['page_id' => $page_id, 'vf' => $now, 'vu' => $now]);
        return $stmt->fetchAll();
    } catch (Throwable $e) {
        return [];
    }
}

/**
 * Get dining items for a page — respects soft-delete + visibility
 */
function get_dining_items(int $page_id = 1): array
{
    $db = Database::get();
    try {
        $now = date('Y-m-d H:i:s');
        $stmt = $db->prepare('SELECT * FROM dining_items WHERE page_id = :page_id AND is_published = 1 AND deleted_at IS NULL AND (visible_from IS NULL OR visible_from <= :vf) AND (visible_until IS NULL OR visible_until >= :vu) ORDER BY sort_order ASC');
        $stmt->execute(['page_id' => $page_id, 'vf' => $now, 'vu' => $now]);
        return $stmt->fetchAll();
    } catch (Throwable $e) {
        return [];
    }
}

// =====================================================
// CSRF PROTECTION
// =====================================================

/**
 * Generate or return existing CSRF token
 */
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Render hidden CSRF input field
 */
function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}

/**
 * Verify CSRF token from POST data
 */
function csrf_verify(): bool
{
    $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}

// =====================================================
// OUTPUT ESCAPING
// =====================================================

/**
 * Escape HTML output
 */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Sanitize HTML content — strip dangerous tags/attributes while allowing safe formatting
 * Used for CMS content fields that contain rich HTML (paragraphs, links, etc.)
 */
function sanitize_html(string $html): string
{
    $allowed = '<p><br><strong><em><b><i><a><ul><ol><li><h2><h3><h4><h5><h6><img><blockquote><hr>';
    $html = strip_tags($html, $allowed);
    // Remove event handlers from all tags
    $html = preg_replace('/\s+on\w+\s*=\s*(["\'][^"\']*["\']|\S+)/i', '', $html);
    // Remove javascript: protocol from href/src
    $html = preg_replace('/(href|src)\s*=\s*["\']?\s*javascript\s*:/i', '$1="#"', $html);
    // Remove data: protocol from src
    $html = preg_replace('/src\s*=\s*["\']?\s*data\s*:/i', 'src="#"', $html);
    return $html;
}

/**
 * Escape and echo
 */
function ee(?string $value): void
{
    echo e($value);
}

// =====================================================
// URL HELPERS
// =====================================================

/**
 * Build a URL from a path
 */
function url(string $path = ''): string
{
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? '';

    if ($host) {
        // Extract the subdirectory path from BASE_URL (e.g. /work/final%20website from http://localhost/work/final%20website)
        $baseHost = parse_url(BASE_URL, PHP_URL_HOST) ?? '';
        $basePath = parse_url(BASE_URL, PHP_URL_PATH) ?? '';
        if ($host === $baseHost && $basePath) {
            return rtrim($scheme . '://' . $host . $basePath, '/') . '/' . ltrim($path, '/');
        }
        return $scheme . '://' . $host . '/' . ltrim($path, '/');
    }

    return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
}

/**
 * Validate URL scheme — only allow http, https, mailto, tel
 * Prevents javascript:, data:, vbscript: XSS injection in href attributes
 */
function safe_url(string $url): string
{
    $url = trim($url);
    if ($url === '') return '';
    $scheme = parse_url($url, PHP_URL_SCHEME);
    if ($scheme === null || in_array(strtolower($scheme), ['http', 'https', 'mailto', 'tel', ''], true)) {
        return htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
    }
    return '#';
}

/**
 * Get the current page slug from the request URI
 */
function current_slug(): string
{
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = rtrim($uri, '/');
    if ($uri === '' || $uri === '/') {
        return 'home';
    }
    return ltrim($uri, '/');
}

// =====================================================
// IMAGE HELPERS
// =====================================================

/**
 * Get image URL — handles both relative paths and full URLs
 */
function image_url(string $path): string
{
    if (strpos($path, 'http') === 0) {
        return $path;
    }
    return url($path);
}

/**
 * Format price as ZAR
 */
function format_price(float $amount): string
{
    return 'R' . number_format($amount, 0);
}

// =====================================================
// ADMIN HELPERS
// =====================================================

/**
 * Check if admin is logged in
 */
function is_admin_logged_in(): bool
{
    return !empty($_SESSION['admin_id']);
}

/**
 * Require admin authentication — redirect to login if not
 */
function require_admin(): void
{
    if (!is_admin_logged_in()) {
        header('Location: ' . url('/admin/login'));
        exit;
    }

    // Check session timeout
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > ADMIN_TIMEOUT) {
        session_unset();
        session_destroy();
        header('Location: ' . url('/admin/login'));
        exit;
    }
    $_SESSION['last_activity'] = time();
}

/**
 * Log an activity
 */
function log_activity(string $action, ?string $entity_type = null, ?int $entity_id = null, ?array $details = null): void
{
    if (!is_admin_logged_in()) return;
    $db = Database::get();
    $stmt = $db->prepare('INSERT INTO activity_log (user_id, action, entity_type, entity_id, details, ip_address) VALUES (:user_id, :action, :entity_type, :entity_id, :details, :ip)');
    $stmt->execute([
        'user_id'     => $_SESSION['admin_id'],
        'action'      => $action,
        'entity_type' => $entity_type,
        'entity_id'   => $entity_id,
        'details'     => $details ? json_encode($details) : null,
        'ip'          => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
    ]);
}

/**
 * Get contact submissions count
 */
function get_unread_submissions_count(): int
{
    $db = Database::get();
    $stmt = $db->query('SELECT COUNT(*) AS cnt FROM contact_submissions WHERE is_read = 0');
    return (int) $stmt->fetch()['cnt'];
}
