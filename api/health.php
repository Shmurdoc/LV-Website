<?php
// Health check — no auth, lightweight, for uptime/K8s/monitoring
header('Content-Type: application/json');
$checks = ['db' => false, 'env' => APP_ENV ?? 'unknown'];
$ok = true;

try {
    $db = Database::get();
    $db->query('SELECT 1')->fetch();
    $checks['db'] = true;
} catch (Throwable $e) {
    $checks['db_error'] = APP_DEBUG ? $e->getMessage() : 'db unavailable';
    $ok = false;
}

http_response_code($ok ? 200 : 503);
echo json_encode(['ok' => $ok, 'checks' => $checks, 'time' => gmdate('c')], JSON_UNESCAPED_SLASHES);
