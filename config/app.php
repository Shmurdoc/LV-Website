<?php
/**
 * Application Configuration — Viata Luxe Guesthouse
 */

// Environment — loaded from .env (never commit .env with real secrets)
if (file_exists(dirname(__DIR__) . '/.env')) {
    $env = parse_ini_file(dirname(__DIR__) . '/.env', false, INI_SCANNER_RAW);
    foreach ($env as $k => $v) {
        $v = trim($v);
        if (!getenv($k)) putenv("$k=$v");
        $_ENV[$k] = $v;
        $_SERVER[$k] = $v;
    }
}
function env(string $key, $default = null) {
    return $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key) ?: $default;
}
define('APP_ENV', env('APP_ENV', 'production'));
define('APP_DEBUG', filter_var(env('APP_DEBUG', 'false'), FILTER_VALIDATE_BOOLEAN));

// Base URL (no trailing slash)
define('BASE_URL', rtrim(env('BASE_URL', 'https://viataluxe.com'), '/'));

// Paths
define('ROOT_PATH', dirname(__DIR__));
define('UPLOAD_DIR', ROOT_PATH . '/assets/uploads');
define('LUXURY_IMAGES', ROOT_PATH . '/Luxury Images');

// Timezone
date_default_timezone_set('Africa/Johannesburg');

// Session — hardened per security-and-hardening skill
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 1 : 0);
    ini_set('session.cookie_samesite', 'Strict');
    ini_set('session.use_strict_mode', 1);
    ini_set('session.cookie_lifetime', 0);
    session_start();
}

// Admin session timeout (from .env, default 30m)
define('ADMIN_TIMEOUT', (int)env('ADMIN_TIMEOUT', 1800));

// Upload limits
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'webp']);

// Debug mode error reporting
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', ROOT_PATH . '/logs/php-error.log');
}

// Check that required .env keys exist
if (!env('DB_HOST') || !env('DB_NAME') || !env('DB_USER')) {
    if (!APP_DEBUG) {
        http_response_code(503);
        require ROOT_PATH . '/templates/503.php';
        exit;
    }
}
