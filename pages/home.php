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
/**
 * Unified section loop — single pass renders all sections via render-section.php.
 * Order is defined once here; DB sort_order controls display within each type.
 */
$__homeSections = get_sections($page['id']);
$__renderOrder = [
    'hero', 'trust-bar', 'promise', 'moments',
    'featured', 'gallery', 'safari-teaser',
    'stats', 'pricing', 'testimonials',
    'dining', 'specials', 'booking-cta',
];
$__seen = [];
foreach ($__homeSections as $section) {
    $type = $section['section_type'] ?? '';
    if (!in_array($type, $__renderOrder, true)) continue;
    if (isset($__seen[$type])) continue;
    $__seen[$type] = true;
    require __DIR__ . '/../templates/render-section.php';
}
?>
</main>

<?php
require __DIR__ . '/../templates/footer.php';
