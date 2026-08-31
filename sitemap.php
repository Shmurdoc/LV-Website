<?php
/**
 * Dynamic XML Sitemap — Viata Luxe Guesthouse
 * Generated from pages + apartments tables.
 * Replaces static sitemap.xml (2026-08-31).
 */

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/functions.php';

header('Content-Type: application/xml; charset=UTF-8');

$db = Database::get();

// Page slug → URL path mapping (mirrors index.php $routes)
$pageUrlMap = [
    'home'          => '/',
    'accommodation' => '/accommodation',
    'gallery'       => '/gallery',
    'safari'        => '/safari',
    'contact'       => '/contact',
    'about'         => '/about',
];

// Priority and changefreq per page type
$pagePriority = [
    'home'          => '1.0',
    'accommodation' => '0.9',
    'gallery'       => '0.7',
    'safari'        => '0.8',
    'contact'       => '0.8',
    'about'         => '0.7',
];
$pageChangefreq = [
    'home'          => 'weekly',
    'accommodation' => 'weekly',
    'gallery'       => 'monthly',
    'safari'        => 'monthly',
    'contact'       => 'monthly',
    'about'         => 'monthly',
];

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

// Pages
$stmt = $db->query("SELECT slug, updated_at FROM pages WHERE is_published = 1 AND deleted_at IS NULL ORDER BY sort_order");
while ($page = $stmt->fetch()) {
    $slug = $page['slug'];
    if (!isset($pageUrlMap[$slug])) continue;

    $loc = rtrim(BASE_URL, '/') . $pageUrlMap[$slug];
    $lastmod = date('Y-m-d', strtotime($page['updated_at']));
    $priority = $pagePriority[$slug] ?? '0.5';
    $changefreq = $pageChangefreq[$slug] ?? 'monthly';

    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($loc) . "</loc>\n";
    echo "    <lastmod>{$lastmod}</lastmod>\n";
    echo "    <changefreq>{$changefreq}</changefreq>\n";
    echo "    <priority>{$priority}</priority>\n";
    echo "  </url>\n";
}

// Apartments
$stmt = $db->query("SELECT slug, updated_at FROM apartments WHERE is_published = 1 AND deleted_at IS NULL ORDER BY sort_order");
while ($apt = $stmt->fetch()) {
    $loc = rtrim(BASE_URL, '/') . '/' . $apt['slug'];
    $lastmod = date('Y-m-d', strtotime($apt['updated_at']));

    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($loc) . "</loc>\n";
    echo "    <lastmod>{$lastmod}</lastmod>\n";
    echo "    <changefreq>monthly</changefreq>\n";
    echo "    <priority>0.8</priority>\n";
    echo "  </url>\n";
}

echo "</urlset>\n";
