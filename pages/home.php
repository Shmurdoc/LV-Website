<?php
/**
 * Homepage — Viata Luxe Guesthouse
 * Loads sections from DB and renders them in order.
 */

$page = get_page('home');
if (!$page) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

$sections = get_sections($page['id']);
$nav = get_navigation();
$settings = settings_group('branding');
$contact = settings_group('contact');
$booking = settings_group('booking');

require __DIR__ . '/../templates/header.php';
?>

<main id="main-content">
<?php foreach ($sections as $section): ?>
    <?php require __DIR__ . '/../templates/render-section.php'; ?>
<?php endforeach; ?>
</main>

<?php
require __DIR__ . '/../templates/footer.php';
