<?php
/**
 * Admin Helper Functions — Viata Luxe Guesthouse
 */

/**
 * Inline SVG icon set (stroke-based, Feather-style). Avoids emoji so the
 * admin panel renders consistently on every OS/browser.
 */
function admin_icon(string $name, int $size = 18): string
{
    $paths = [
        'dashboard' => '<rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/>',
        'pages'     => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="13" x2="15" y2="13"/><line x1="9" y1="17" x2="13" y2="17"/>',
        'sections'  => '<line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="13" y2="18"/><circle cx="16" cy="18" r="2"/>',
        'apartments'=> '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
        'gallery'   => '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>',
        'safari'    => '<circle cx="12" cy="8" r="5"/><path d="M12 14v6"/><path d="M8 22h8"/>',
        'testimonials'=> '<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8z"/><path d="M8.5 11h7"/><path d="M8.5 14h4"/>',
        'faqs'      => '<circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12" y2="17"/>',
        'navigation'=> '<line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3" y2="6"/><line x1="3" y1="12" x2="3" y2="12"/><line x1="3" y1="18" x2="3" y2="18"/>',
        'contact'   => '<path d="M22 2 11 13"/><path d="M22 2 15 22l-4-9-9-4z"/>',
        'settings'  => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>',
        'globe'     => '<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>',
        'logout'    => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>',
        'plus'      => '<line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>',
        'edit'      => '<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>',
        'trash'     => '<polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>',
        'restore'   => '<polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>',
        'check'     => '<polyline points="20 6 9 17 4 12"/>',
        'mail'      => '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>',
        'eye'       => '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>',
        'home'      => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
        'bed'       => '<path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/>',
        'star'      => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
        'users'     => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
        'inbox'     => '<polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>',
    ];
    $body = $paths[$name] ?? '<circle cx="12" cy="12" r="10"/>';
    return '<svg xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size
        . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" '
        . 'stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $body . '</svg>';
}

/**
 * Get all admin pages for sidebar navigation (SVG icons, no emoji)
 */
function admin_base(): string
{
    return url('/admin');
}

function get_admin_nav(): array
{
    $base = admin_base();
    return [
        ['label' => 'Dashboard',      'url' => "$base/dashboard",       'path' => '/dashboard',   'icon' => 'dashboard'],
        ['label' => 'Pages',          'url' => "$base/pages",           'path' => '/pages',       'icon' => 'pages'],
        ['label' => 'Sections',       'url' => "$base/sections",        'path' => '/sections',    'icon' => 'sections'],
        ['label' => 'Apartments',     'url' => "$base/apartments",      'path' => '/apartments',  'icon' => 'apartments'],
        ['label' => 'Gallery',        'url' => "$base/gallery",         'path' => '/gallery',     'icon' => 'gallery'],
        ['label' => 'Safari',         'url' => "$base/safari",          'path' => '/safari',      'icon' => 'safari'],
        ['label' => 'Testimonials',   'url' => "$base/testimonials",    'path' => '/testimonials','icon' => 'testimonials'],
        ['label' => 'FAQs',           'url' => "$base/faqs",            'path' => '/faqs',        'icon' => 'faqs'],
        ['label' => 'Navigation',     'url' => "$base/navigation",      'path' => '/navigation',  'icon' => 'navigation'],
        ['label' => 'Contact',        'url' => "$base/contact",         'path' => '/contact',     'icon' => 'contact'],
        ['label' => 'Settings',       'url' => "$base/settings",        'path' => '/settings',    'icon' => 'settings'],
    ];
}

/**
 * Get dashboard stats
 */
function get_admin_stats(): array
{
    $db = Database::get();
    $stats = [];

    $stats['pages'] = (int) $db->query('SELECT COUNT(*) FROM pages WHERE deleted_at IS NULL')->fetchColumn();
    $stats['apartments'] = (int) $db->query('SELECT COUNT(*) FROM apartments WHERE deleted_at IS NULL')->fetchColumn();
    $stats['testimonials'] = (int) $db->query('SELECT COUNT(*) FROM testimonials WHERE deleted_at IS NULL')->fetchColumn();
    $stats['gallery_images'] = (int) $db->query('SELECT COUNT(*) FROM gallery_images WHERE deleted_at IS NULL')->fetchColumn();
    $stats['faqs'] = (int) $db->query('SELECT COUNT(*) FROM faqs WHERE deleted_at IS NULL')->fetchColumn();
    $stats['contact_unread'] = get_unread_submissions_count();
    $stats['sections'] = (int) $db->query('SELECT COUNT(*) FROM sections WHERE deleted_at IS NULL')->fetchColumn();

    $trashTotal = (int) $db->query('SELECT COUNT(*) FROM pages WHERE deleted_at IS NOT NULL')->fetchColumn()
                + (int) $db->query('SELECT COUNT(*) FROM apartments WHERE deleted_at IS NOT NULL')->fetchColumn()
                + (int) $db->query('SELECT COUNT(*) FROM sections WHERE deleted_at IS NOT NULL')->fetchColumn()
                + (int) $db->query('SELECT COUNT(*) FROM faqs WHERE deleted_at IS NOT NULL')->fetchColumn()
                + (int) $db->query('SELECT COUNT(*) FROM testimonials WHERE deleted_at IS NOT NULL')->fetchColumn();
    $stats['trash_count'] = $trashTotal;

    return $stats;
}

/**
 * Get recent activity log
 */
function get_recent_activity(int $limit = 20): array
{
    $db = Database::get();
    $stmt = $db->prepare('
        SELECT al.*, au.full_name, au.username
        FROM activity_log al
        LEFT JOIN admin_users au ON au.id = al.user_id
        ORDER BY al.created_at DESC
        LIMIT :limit
    ');
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Send JSON response
 */
function json_response(array $data, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($data);
    exit;
}

/**
 * Send JSON error
 */
function json_error(string $message, int $status = 400): void
{
    json_response(['error' => $message], $status);
}

/**
 * Get POST body as JSON
 */
function get_json_body(): array
{
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

/**
 * Validate required fields
 */
function require_fields(array $data, array $fields): void
{
    foreach ($fields as $field) {
        if (empty($data[$field]) && $data[$field] !== '0' && $data[$field] !== 0) {
            json_error("Missing required field: {$field}");
        }
    }
}

/**
 * Handle file upload
 */
function handle_upload(string $fieldName, string $subDir = ''): ?string
{
    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $file = $_FILES[$fieldName];

    if ($file['size'] > MAX_UPLOAD_SIZE) {
        json_error('File too large. Max size: ' . (MAX_UPLOAD_SIZE / 1024 / 1024) . 'MB');
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ALLOWED_EXTENSIONS)) {
        json_error('Invalid file type. Allowed: ' . implode(', ', ALLOWED_EXTENSIONS));
    }

    $uploadDir = UPLOAD_DIR;
    if ($subDir) {
        $uploadDir .= '/' . $subDir;
    }

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $filename = uniqid('img_', true) . '.' . $ext;
    $path = $uploadDir . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $path)) {
        json_error('Failed to save uploaded file');
    }

    return 'uploads' . ($subDir ? '/' . $subDir : '') . '/' . $filename;
}

/**
 * Handle simple text/textarea form save (POST to database)
 */
function save_setting(string $key, string $value): void
{
    $db = Database::get();
    $stmt = $db->prepare('
        INSERT INTO global_settings (setting_key, setting_value, updated_at)
        VALUES (:key, :val, NOW())
        ON DUPLICATE KEY UPDATE setting_value = :val2, updated_at = NOW()
    ');
    $stmt->execute(['key' => $key, 'val' => $value, 'val2' => $value]);
}
