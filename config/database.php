<?php
/**
 * Database Configuration — Viata Luxe Guesthouse
 * MySQL 8.0+ via PDO
 *
 * Auto-detects environment:
 *   - Localhost  → local WAMP MySQL (root, no password, viata_luxe)
 *   - Live       → production credentials from .env
 */

if (!function_exists('env')) {
    function env(string $key, $default = null) {
        return $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key) ?: $default;
    }
}

// Detect if running locally
$isLocal = in_array(
    $_SERVER['SERVER_NAME'] ?? '',
    ['localhost', '127.0.0.1', '::1']
) || str_starts_with($_SERVER['SERVER_ADDR'] ?? '', '127.');

if ($isLocal) {
    // Local WAMP — hardcoded for instant dev
    define('DB_HOST', '127.0.0.1');
    define('DB_PORT', '3306');
    define('DB_NAME', 'viata_luxe');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_CHARSET', 'utf8mb4');
} else {
    // Production — from .env
    define('DB_HOST', env('DB_HOST', '127.0.0.1'));
    define('DB_PORT', env('DB_PORT', '3306'));
    define('DB_NAME', env('DB_NAME', 'viata_luxe'));
    define('DB_USER', env('DB_USER', 'root'));
    define('DB_PASS', env('DB_PASS', ''));
    define('DB_CHARSET', env('DB_CHARSET', 'utf8mb4'));
}
