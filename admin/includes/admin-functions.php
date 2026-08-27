<?php
/**
 * Admin Helper Functions — Viata Luxe Guesthouse
 */

/**
 * Get all admin pages for sidebar navigation
 */
function get_admin_nav(): array
{
    return [
        ['label' => 'Dashboard',   'url' => '/admin/dashboard',   'icon' => '📊'],
        ['label' => 'Pages',       'url' => '/admin/pages',       'icon' => '📄'],
        ['label' => 'Sections',    'url' => '/admin/sections',    'icon' => '🧩'],
        ['label' => 'Apartments',  'url' => '/admin/apartments',  'icon' => '🏠'],
        ['label' => 'Gallery',     'url' => '/admin/gallery',     'icon' => '🖼️'],
        ['label' => 'Safari',      'url' => '/admin/safari',      'icon' => '🦁'],
        ['label' => 'Testimonials','url' => '/admin/testimonials', 'icon' => '⭐'],
        ['label' => 'FAQs',        'url' => '/admin/faqs',        'icon' => '❓'],
        ['label' => 'Navigation',  'url' => '/admin/navigation',  'icon' => '🧭'],
        ['label' => 'Contact',     'url' => '/admin/contact',     'icon' => '✉️'],
        ['label' => 'Settings',    'url' => '/admin/settings',    'icon' => '⚙️'],
    ];
}

/**
 * Get dashboard stats
 */
function get_admin_stats(): array
{
    $db = Database::get();
    $stats = [];

    $stats['pages'] = (int) $db->query('SELECT COUNT(*) FROM pages')->fetchColumn();
    $stats['apartments'] = (int) $db->query('SELECT COUNT(*) FROM apartments')->fetchColumn();
    $stats['testimonials'] = (int) $db->query('SELECT COUNT(*) FROM testimonials')->fetchColumn();
    $stats['gallery_images'] = (int) $db->query('SELECT COUNT(*) FROM gallery_images')->fetchColumn();
    $stats['faqs'] = (int) $db->query('SELECT COUNT(*) FROM faqs')->fetchColumn();
    $stats['contact_unread'] = get_unread_submissions_count();
    $stats['sections'] = (int) $db->query('SELECT COUNT(*) FROM sections')->fetchColumn();

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
