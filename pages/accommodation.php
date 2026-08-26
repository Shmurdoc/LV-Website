<?php
/**
 * Accommodation Overview — Viata Luxe Guesthouse
 * Shows all 4 apartment cards.
 */

$page = get_page('accommodation');
if (!$page) {
    // Fallback: try the typo slug
    $page = get_page('accomodation');
}
if (!$page) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

$sections = get_sections($page['id']);
$apartments = get_apartments();
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
