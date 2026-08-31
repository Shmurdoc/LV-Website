<?php
/**
 * About — Viata Luxe Guesthouse
 * DB-driven page using the section renderer.
 * Sections: hero, image-text, stats (via render-section.php).
 */

$page = get_page('about');
if (!$page) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

$nav      = get_navigation();
$settings = settings_group('branding');

require __DIR__ . '/../templates/header.php';
?>

<main id="main-content">
<?php
$__aboutSections = get_sections($page['id']);

// Render each section in order via the section renderer
foreach ($__aboutSections as $section) {
    require __DIR__ . '/../templates/render-section.php';
}
?>
</main>

<?php require __DIR__ . '/../templates/footer.php'; ?>
