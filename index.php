<?php
/**
 * Front Controller — Viata Luxe Guesthouse
 * Routes all public requests through this file.
 */

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/functions.php';

// Serve static files directly (needed for php -S built-in server)
$rawUri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$staticPath = __DIR__ . $rawUri;
if (is_file($staticPath) && pathinfo($rawUri, PATHINFO_EXTENSION)) {
    $mimeTypes = [
        'jpg'  => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png'  => 'image/png',
        'gif'  => 'image/gif',  'webp' => 'image/webp',  'svg'  => 'image/svg+xml',
        'ico'  => 'image/x-icon', 'avif' => 'image/avif',
        'css'  => 'text/css',   'js'   => 'application/javascript',
        'woff2'=> 'font/woff2', 'woff' => 'font/woff',
    ];
    $ext = strtolower(pathinfo($rawUri, PATHINFO_EXTENSION));
    if (isset($mimeTypes[$ext])) {
        header('Content-Type: ' . $mimeTypes[$ext]);
        readfile($staticPath);
        exit;
    }
}

// Get the request path — strip base path for nested directories
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Compute base path: the project directory relative to DocumentRoot
// e.g. DocumentRoot=C:\wamp64\www, project=C:\wamp64\www\work\final website => base=/work/final website
$docRoot = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/\\');
$baseDir = str_replace('\\', '/', __DIR__);
$basePath = str_replace($docRoot, '', $baseDir);
$basePath = '/' . ltrim($basePath, '/');

// Strip base path from URI
if ($basePath !== '/' && strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}
$uri = rtrim($uri, '/');

// Delegate /admin/* to admin front controller (avoid phantom 404)
if (strpos($uri, '/admin') === 0) {
    require __DIR__ . '/admin/index.php';
    exit;
}
// Also allow /api/* direct files when not via .htaccess (php -S)
if (strpos($uri, '/api/') === 0) {
    $apiFile = __DIR__ . $uri . '.php';
    if (file_exists($apiFile)) { require $apiFile; exit; }
    $apiFile2 = __DIR__ . $uri;
    if (file_exists($apiFile2)) { require $apiFile2; exit; }
}

// Route map: URI path => page file
$routes = [
    ''                  => 'pages/home.php',
    '/'                 => 'pages/home.php',
    '/home'             => 'pages/home.php',
    '/accomodation'     => 'pages/accommodation.php',
    '/accommodation'    => 'pages/accommodation.php',
    '/bachelor-apartment'   => 'pages/apartment.php',
    '/classic-apartment-2'  => 'pages/apartment.php',
    '/comfort-apartment-3'  => 'pages/apartment.php',
    '/deluxe-apartment-4'   => 'pages/apartment.php',
    '/gallery'          => 'pages/gallery.php',
    '/safari'           => 'pages/safari.php',
    '/contact'          => 'pages/contact.php',
    '/api/health'       => 'api/health.php',
    '/api/contact'      => 'api/contact.php',
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

// Dynamic routes — wrapped in try-catch for DB fallback
try {
    $db = Database::get();

    // Check for apartment pages by slug
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
} catch (\PDOException $e) {
    if (APP_DEBUG) {
        http_response_code(503);
        echo '<h1>Database Connection Failed</h1><p>' . htmlspecialchars($e->getMessage()) . '</p>';
        exit;
    }
    http_response_code(503);
    require __DIR__ . '/templates/503.php';
    exit;
}

// 404
http_response_code(404);
require __DIR__ . '/pages/404.php';
