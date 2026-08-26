<?php
/**
 * Front Controller — Viata Luxe Guesthouse
 * Routes all public requests through this file.
 */

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/functions.php';

// Get the request path
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');

// Route map: URI path => page file
$routes = [
    ''                  => 'pages/home.php',
    '/'                 => 'pages/home.php',
    '/home'             => 'pages/home.php',
    '/accomodation'     => 'pages/accommodation.php',
    '/bachelor-apartment'   => 'pages/apartment.php',
    '/classic-apartment-2'  => 'pages/apartment.php',
    '/comfort-apartment-3'  => 'pages/apartment.php',
    '/deluxe-apartment-4'   => 'pages/apartment.php',
    '/gallery'          => 'pages/gallery.php',
    '/safari'           => 'pages/safari.php',
    '/contact'          => 'pages/contact.php',
];

// Check for exact match
if (isset($routes[$uri])) {
    require __DIR__ . '/' . $routes[$uri];
    exit;
}

// Check for trailing slash variants
$uriWithSlash = $uri . '/';
if (isset($routes[$uriWithSlash])) {
    require __DIR__ . '/' . $routes[$uriWithSlash];
    exit;
}

// Check for apartment pages by slug (dynamic lookup)
$db = Database::get();
$stmt = $db->prepare('SELECT slug FROM apartments WHERE slug = :slug AND is_published = 1');
$stmt->execute(['slug' => ltrim($uri, '/')]);
$apartment = $stmt->fetch();
if ($apartment) {
    require __DIR__ . '/pages/apartment.php';
    exit;
}

// Check pages table for dynamic routes
$slug = ltrim($uri, '/');
if ($slug) {
    $page = get_page($slug);
    if ($page) {
        $templateFile = __DIR__ . '/pages/' . $page['template'] . '.php';
        if (file_exists($templateFile)) {
            require $templateFile;
            exit;
        }
    }
}

// 404
http_response_code(404);
require __DIR__ . '/pages/404.php';
