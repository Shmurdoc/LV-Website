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
    $stmt = $db->prepare('SELECT setting_value FROM global_settings WHERE setting_key = :key LIMIT 1');
    $stmt->execute(['key' => $key]);
    $result = $stmt->fetch();
    $cache[$key] = $result ? $result['setting_value'] : $default;
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
    $stmt = $db->prepare('SELECT setting_key, setting_value FROM global_settings WHERE setting_group = :group ORDER BY sort_order ASC');
    $stmt->execute(['group' => $group]);
    $rows = $stmt->fetchAll();
    $cache[$group] = [];
    foreach ($rows as $row) {
        $cache[$group][$row['setting_key']] = $row['setting_value'];
    }
    return $cache[$group];
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
    $stmt = $db->prepare('SELECT * FROM pages WHERE slug = :slug AND is_published = 1 LIMIT 1');
    $stmt->execute(['slug' => $slug]);
    return $stmt->fetch() ?: null;
}

/**
 * Get all published pages ordered by sort_order
 */
function get_all_pages(): array
{
    $db = Database::get();
    $stmt = $db->query('SELECT * FROM pages WHERE is_published = 1 ORDER BY sort_order ASC');
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
    $stmt = $db->prepare('
        SELECT s.id, s.section_type, s.title, s.subtitle, s.content, s.image,
               s.link_url, s.link_text, s.css_class,
               so.layout, so.background_color, so.background_image, so.text_color,
               so.padding_top, so.padding_bottom, so.padding_left, so.padding_right,
               so.max_width, so.alignment, so.vertical_alignment, so.animation, so.responsive_stack
        FROM sections s
        LEFT JOIN section_orientation so ON so.section_id = s.id
        WHERE s.page_id = :page_id AND s.is_visible = 1
        ORDER BY s.sort_order ASC
    ');
    $stmt->execute(['page_id' => $page_id]);
    return $stmt->fetchAll();
}

/**
 * Get a single section by ID
 */
function get_section(int $section_id): ?array
{
    $db = Database::get();
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
    $stmt = $db->prepare('
        SELECT n.id, n.label, n.url, n.page_id, n.parent_id, n.sort_order,
               n.open_in_new_tab, n.css_class, p.slug AS page_slug
        FROM navigation n
        LEFT JOIN pages p ON p.id = n.page_id
        WHERE n.is_published = 1
        ORDER BY n.sort_order ASC
    ');
    $stmt->execute();
    $rows = $stmt->fetchAll();

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
 * Get all published apartments
 */
function get_apartments(): array
{
    $db = Database::get();
    $stmt = $db->query('SELECT * FROM apartments WHERE is_published = 1 ORDER BY sort_order ASC');
    return $stmt->fetchAll();
}

/**
 * Get a single apartment by slug
 */
function get_apartment(string $slug): ?array
{
    $db = Database::get();
    $stmt = $db->prepare('SELECT * FROM apartments WHERE slug = :slug AND is_published = 1 LIMIT 1');
    $stmt->execute(['slug' => $slug]);
    return $stmt->fetch() ?: null;
}

/**
 * Get apartment by ID
 */
function get_apartment_by_id(int $id): ?array
{
    $db = Database::get();
    $stmt = $db->prepare('SELECT * FROM apartments WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $id]);
    return $stmt->fetch() ?: null;
}

/**
 * Get images for an apartment
 */
function get_apartment_images(int $apartment_id): array
{
    $db = Database::get();
    $stmt = $db->prepare('SELECT * FROM apartment_images WHERE apartment_id = :id ORDER BY sort_order ASC');
    $stmt->execute(['id' => $apartment_id]);
    return $stmt->fetchAll();
}

/**
 * Get amenities for an apartment
 */
function get_apartment_amenities(int $apartment_id): array
{
    static $grouped = null;
    if ($grouped === null) {
        $db = Database::get();
        $rows = $db->query('SELECT * FROM apartment_amenities ORDER BY apartment_id ASC, sort_order ASC')->fetchAll();
        $grouped = [];
        foreach ($rows as $r) { $grouped[(int)$r['apartment_id']][] = $r; }
    }
    return $grouped[$apartment_id] ?? [];
}

// =====================================================
// TESTIMONIALS
// =====================================================

/**
 * Get featured testimonials
 */
function get_featured_testimonials(): array
{
    $db = Database::get();
    $stmt = $db->query('SELECT * FROM testimonials WHERE is_featured = 1 AND is_published = 1 ORDER BY sort_order ASC');
    return $stmt->fetchAll();
}

/**
 * Get testimonials for a specific apartment
 */
function get_apartment_testimonials(int $apartment_id): array
{
    $db = Database::get();
    $stmt = $db->prepare('SELECT * FROM testimonials WHERE apartment_id = :id AND is_published = 1 ORDER BY sort_order ASC');
    $stmt->execute(['id' => $apartment_id]);
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
    $stmt = $db->query('
        SELECT gc.*, COUNT(gi.id) AS image_count
        FROM gallery_categories gc
        LEFT JOIN gallery_images gi ON gi.category_id = gc.id
        WHERE gc.is_published = 1
        GROUP BY gc.id
        ORDER BY gc.sort_order ASC
    ');
    return $stmt->fetchAll();
}

/**
 * Get images for a gallery category
 */
function get_gallery_images(int $category_id): array
{
    $db = Database::get();
    $stmt = $db->prepare('SELECT * FROM gallery_images WHERE category_id = :id ORDER BY sort_order ASC');
    $stmt->execute(['id' => $category_id]);
    return $stmt->fetchAll();
}

/**
 * Get featured gallery images (for homepage preview)
 */
function get_featured_gallery(int $limit = 6): array
{
    $db = Database::get();
    $stmt = $db->prepare('
        SELECT gi.image_path, gi.alt_text, gc.name AS category_name
        FROM gallery_images gi
        JOIN gallery_categories gc ON gc.id = gi.category_id
        WHERE gc.is_published = 1
        ORDER BY gi.sort_order ASC
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
    $stmt = $db->query('SELECT * FROM safari_activities WHERE is_published = 1 ORDER BY sort_order ASC');
    return $stmt->fetchAll();
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
    if ($page_id) {
        $stmt = $db->prepare('SELECT * FROM faqs WHERE page_id = :page_id AND is_published = 1 ORDER BY sort_order ASC');
        $stmt->execute(['page_id' => $page_id]);
    } else {
        $stmt = $db->query('SELECT * FROM faqs WHERE is_published = 1 ORDER BY sort_order ASC');
    }
    return $stmt->fetchAll();
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
        header('Location: ' . url('/admin/login.php'));
        exit;
    }

    // Check session timeout
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > ADMIN_TIMEOUT) {
        session_unset();
        session_destroy();
        header('Location: ' . url('/admin/login.php'));
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
