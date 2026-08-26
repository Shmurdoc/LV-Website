<?php
/**
 * Single Apartment Detail — Viata Luxe Guesthouse
 * Dynamic page based on URL slug.
 */

// Determine which apartment from the URI
$slug = ltrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$apartment = get_apartment($slug);

if (!$apartment) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

$page = get_page($apartment['slug']);
if (!$page) {
    // Create a minimal page object from apartment data
    $page = [
        'id' => $apartment['page_id'],
        'slug' => $apartment['slug'],
        'title' => $apartment['name'],
        'meta_title' => $apartment['meta_title'] ?? $apartment['name'] . ' — Viata Luxe Guesthouse',
        'meta_description' => $apartment['meta_description'] ?? '',
    ];
}

$sections = get_sections($apartment['page_id']);
$images = get_apartment_images($apartment['id']);
$amenities = get_apartment_amenities($apartment['id']);
$testimonials = get_apartment_testimonials($apartment['id']);
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
