<?php
/**
 * Gallery — Viata Luxe Guesthouse
 * Masonry grid with lightbox.
 */

$page = get_page('gallery');
if (!$page) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

$sections = get_sections($page['id']);
$categories = get_gallery_categories();
$nav = get_navigation();
$settings = settings_group('branding');
$contact = settings_group('contact');

require __DIR__ . '/../templates/header.php';
?>

<main id="main-content">
<?php foreach ($sections as $section): ?>
    <?php require __DIR__ . '/../templates/render-section.php'; ?>
<?php endforeach; ?>
</main>

<?php
require __DIR__ . '/../templates/footer.php';
