<?php
/**
 * Default Page Template — Viata Luxe Guesthouse
 * Generic section renderer for any page whose template is 'default'.
 * Used by: about, and any future CMS page without a bespoke PHP file.
 */

if (empty($page)) {
    $page = get_page($current_slug ?? current_slug());
}
if (!$page) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

$sections = get_sections($page['id']);
$nav = $nav ?? get_navigation();
$settings = $settings ?? settings_group('branding');
$contact = $contact ?? settings_group('contact');

require __DIR__ . '/../templates/header.php';
?>

<main id="main-content">
<?php foreach ($sections as $section): ?>
    <?php require __DIR__ . '/../templates/render-section.php'; ?>
<?php endforeach; ?>
</main>

<?php
require __DIR__ . '/../templates/footer.php';