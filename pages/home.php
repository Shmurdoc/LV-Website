<?php
/**
 * Homepage — Viata Luxe Guesthouse
 * Track B: Global Chrome & Marketing Sections (Builder B)
 * Delegates to section renderer for editorial fidelity, single source.
 * Track A owns: featured/gallery/pricing/testimonials/safari-teaser (filtered loops preserved below, DO NOT EDIT)
 * Track B owns: hero, trust-bar, promise, moments, stats, dining, specials, booking-cta, header/footer/meta
 * No hardcoded HTML for Tracks B — all via get_sections() + render-section.php
 * No fallback masking — is_published/deleted_at/visible window respected via helpers
 */

$page = get_page('home');
if (!$page) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

$nav = get_navigation();
$settings = settings_group('branding');
$contact = settings_group('contact');
$booking = settings_group('booking');

require __DIR__ . '/../templates/header.php';
?>

<main id="main-content">
<?php
$__homeSections = get_sections($page['id']);

// H-07/H-08 hero slideshow (5 slides via hero_slides) — Track B
foreach ($__homeSections as $section) {
    if ($section['section_type'] !== 'hero') continue;
    require __DIR__ . '/../templates/render-section.php';
}
// H-09 trust bar — Track B
foreach ($__homeSections as $section) {
    if ($section['section_type'] !== 'trust-bar') continue;
    require __DIR__ . '/../templates/render-section.php';
}
// H-10 promise pillars (5 cards via promise_pillars) — Track B
foreach ($__homeSections as $section) {
    if ($section['section_type'] !== 'promise') continue;
    require __DIR__ . '/../templates/render-section.php';
}
// H-11 moments (3 cards via moments) — Track B
foreach ($__homeSections as $section) {
    if ($section['section_type'] !== 'moments') continue;
    require __DIR__ . '/../templates/render-section.php';
}
?>
<?php
// Track A — delegate collection-driven sections via render-section.php (single source) like pages/default.php:26-28
// Do NOT edit hero/trust-bar/promise/moments/stats/dining/specials/booking-cta wrappers (Track B)
$__homeSections = get_sections($page['id']);
foreach ($__homeSections as $section) {
    if ($section['section_type'] !== 'featured') continue;
    require __DIR__ . '/../templates/render-section.php';
}
foreach ($__homeSections as $section) {
    if ($section['section_type'] !== 'gallery') continue;
    require __DIR__ . '/../templates/render-section.php';
}
foreach ($__homeSections as $section) {
    if ($section['section_type'] !== 'safari-teaser') continue;
    require __DIR__ . '/../templates/render-section.php';
}
?>
<?php
// H-15 stats (4 counters JSON via section content) — Track B
foreach ($__homeSections as $section) {
    if ($section['section_type'] !== 'stats') continue;
    require __DIR__ . '/../templates/render-section.php';
}
?>
<?php
foreach ($__homeSections as $section) {
    if ($section['section_type'] !== 'pricing') continue;
    require __DIR__ . '/../templates/render-section.php';
}
foreach ($__homeSections as $section) {
    if ($section['section_type'] !== 'testimonials') continue;
    require __DIR__ . '/../templates/render-section.php';
}
?>
<?php
// H-18 dining (4 items via dining_items) — Track B
foreach ($__homeSections as $section) {
    if ($section['section_type'] !== 'dining') continue;
    require __DIR__ . '/../templates/render-section.php';
}
// H-19 specials (hardcoded wa.me replaced via setting booking_whatsapp_number) — Track B
foreach ($__homeSections as $section) {
    if ($section['section_type'] !== 'specials') continue;
    require __DIR__ . '/../templates/render-section.php';
}
// H-20 booking CTA — Track B
foreach ($__homeSections as $section) {
    if ($section['section_type'] !== 'booking-cta') continue;
    require __DIR__ . '/../templates/render-section.php';
}
?>
</main>

<?php
require __DIR__ . '/../templates/footer.php';
