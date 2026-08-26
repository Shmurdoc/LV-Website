<?php
/**
 * Application Configuration — Viata Luxe Guesthouse
 */

// Environment
define('APP_ENV', 'development');
define('APP_DEBUG', true);

// Base URL (no trailing slash)
define('BASE_URL', 'http://localhost/viata-luxe');

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

// Admin session timeout (30 minutes)
define('ADMIN_TIMEOUT', 1800);

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
}
