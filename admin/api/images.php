<?php
/**
 * Image Browser API — Lists available images from uploads/ and Luxury Images/
 * Returns JSON for the admin image browser modal.
 */
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../includes/admin-functions.php';

header('Content-Type: application/json');

// Allow GET for listing, POST for upload
$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'GET' && $method !== 'POST') {
    json_error('Method not allowed', 405);
}

require_admin();

$root = dirname(__DIR__, 2); // project root
$search = trim($_GET['search'] ?? '');
$dir = trim($_GET['dir'] ?? '');

// Scan directories for images
function scan_images(string $baseDir, string $subDir = ''): array
{
    $results = [];
    $path = $baseDir;
    if ($subDir) {
        $path = $baseDir . '/' . $subDir;
    }
    if (!is_dir($path)) {
        return [];
    }
    $prefix = $subDir ? $subDir . '/' : '';
    $items = scandir($path);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        $full = $path . '/' . $item;
        if (is_dir($full)) {
            // Recurse into subdirectories (e.g. Luxury Images/logos/)
            $results = array_merge($results, scan_images($baseDir, $prefix . $item));
        } elseif (preg_match('/\.(jpe?g|png|gif|webp|avif|svg)$/i', $item)) {
            $relPath = 'uploads/' . $prefix . $item;
            $results[] = [
                'path' => $relPath,
                'name' => $item,
                'dir'  => $prefix ?: 'uploads',
                'size' => filesize($full),
            ];
        }
    }
    return $results;
}

if ($method === 'GET') {
    $uploadsDir = $root . '/uploads';
    $luxuryDir = $root . '/Luxury Images';
    $images = [];
    $images = array_merge($images, scan_images($uploadsDir));
    $images = array_merge($images, scan_images($luxuryDir));

    // Filter by search
    if ($search !== '') {
        $q = strtolower($search);
        $images = array_filter($images, function ($img) use ($q) {
            return stripos($img['name'], $q) !== false
                || stripos($img['path'], $q) !== false;
        });
    }

    // Filter by subdirectory
    if ($dir !== '') {
        $images = array_filter($images, function ($img) use ($dir) {
            return stripos($img['dir'], $dir) !== false;
        });
    }

    // Collect unique subdirectories for filter dropdown
    $dirs = [];
    foreach ($images as $img) {
        $top = explode('/', $img['dir'])[0];
        $dirs[$top] = true;
    }

    $images = array_values($images);

    json_response([
        'images' => $images,
        'dirs'   => array_keys($dirs),
        'total'  => count($images),
    ]);
}

// POST: Handle image upload
if ($method === 'POST') {
    $targetDir = 'uploads';
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        json_error('No file uploaded or upload error');
    }

    $file = $_FILES['image'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];
    if (!in_array($ext, $allowed)) {
        json_error('Invalid file type. Allowed: ' . implode(', ', $allowed));
    }
    if ($file['size'] > 5 * 1024 * 1024) {
        json_error('File too large (max 5MB)');
    }

    $subdir = trim($_POST['subdir'] ?? '');
    if ($subdir) {
        $subdir = preg_replace('/[^a-zA-Z0-9_\-\/]/', '', $subdir);
        $targetDir .= '/' . $subdir;
    }

    $destDir = $root . '/' . $targetDir;
    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }

    $safeName = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $file['name']);
    $safeName = preg_replace('/_+/', '_', $safeName);
    $dest = $destDir . '/' . $safeName;

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        json_error('Failed to save file');
    }

    $relPath = $targetDir . '/' . $safeName;
    json_response([
        'success' => true,
        'path'    => $relPath,
        'name'    => $safeName,
    ]);
}
