<?php
/**
 * Admin Panel Entry Point — Viata Luxe Guesthouse
 * Routes all admin requests through this file.
 */

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/includes/admin-functions.php';

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$uri = rtrim($uri, '/');

// Strip base path (for nested directories like /work/final website)
$docRoot = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/\\');
$baseDir = str_replace('\\', '/', dirname(__DIR__));
$basePath = str_replace($docRoot, '', $baseDir);
$basePath = '/' . ltrim($basePath, '/');
if ($basePath !== '/' && strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

$path = preg_replace('#^/admin#', '', $uri);
$path = '/' . ltrim($path, '/');

// Allow login/logout without auth
if ($path === '/' || $path === '/login') {
    if (is_admin_logged_in()) {
        header('Location: /admin/dashboard');
        exit;
    }
    require __DIR__ . '/login.php';
    exit;
}

if ($path === '/logout') {
    session_unset();
    session_destroy();
    header('Location: /admin/login');
    exit;
}

// All other routes require auth — wrap in try-catch for DB fallback
try {
    require_admin();
} catch (\PDOException $e) {
    http_response_code(503);
    require __DIR__ . '/../templates/503.php';
    exit;
}

// API routes
if (strpos($path, '/api/') === 0) {
    $apiFile = __DIR__ . $path . '.php';
    if (file_exists($apiFile)) {
        header('Content-Type: application/json');
        require $apiFile;
        exit;
    }
    http_response_code(404);
    echo json_encode(['error' => 'API endpoint not found']);
    exit;
}

// Page routes
$pageRoutes = [
    '/dashboard'    => 'pages/dashboard.php',
    '/settings'     => 'pages/settings.php',
    '/pages'        => 'pages/pages-list.php',
    '/pages/edit'   => 'pages/page-edit.php',
    '/pages/edit/'  => 'pages/page-edit.php',
    '/sections'     => 'pages/sections-list.php',
    '/sections/edit' => 'pages/section-edit.php',
    '/sections/edit/' => 'pages/section-edit.php',
    '/apartments'   => 'pages/apartments-list.php',
    '/apartments/edit' => 'pages/apartment-edit.php',
    '/apartments/edit/' => 'pages/apartment-edit.php',
    '/testimonials' => 'pages/testimonials-list.php',
    '/testimonials/edit' => 'pages/testimonial-edit.php',
    '/testimonials/edit/' => 'pages/testimonial-edit.php',
    '/gallery'      => 'pages/gallery-list.php',
    '/gallery/edit' => 'pages/gallery-category-edit.php',
    '/gallery/edit/' => 'pages/gallery-category-edit.php',
    '/gallery/images' => 'pages/gallery-images.php',
    '/gallery/images/' => 'pages/gallery-images.php',
    '/navigation'   => 'pages/navigation-list.php',
    '/navigation/edit' => 'pages/navigation-edit.php',
    '/navigation/edit/' => 'pages/navigation-edit.php',
    '/faqs'         => 'pages/faqs-list.php',
    '/faqs/edit'    => 'pages/faq-edit.php',
    '/faqs/edit/'   => 'pages/faq-edit.php',
    '/safari'       => 'pages/safari-list.php',
    '/safari/edit'  => 'pages/safari-edit.php',
    '/safari/edit/' => 'pages/safari-edit.php',
    '/contact'      => 'pages/contact-submissions.php',
];

if (isset($pageRoutes[$path])) {
    $adminPage = $path;
    // AJAX requests get content only (no layout wrapper)
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        require __DIR__ . '/' . $pageRoutes[$path];
        exit;
    }
    require __DIR__ . '/layout.php';
    exit;
}

// 404
http_response_code(404);
$adminPage = '/404';
require __DIR__ . '/layout.php';
