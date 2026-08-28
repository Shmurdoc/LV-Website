<?php
/**
 * Single Apartment Detail — Viata Luxe Guesthouse
 * Dynamic page based on URL slug. Renders a dedicated detail layout:
 * hero → images → amenities → facts → testimonial → booking CTA.
 */

// Determine which apartment from the URI
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
// Strip base path (for nested directories like /work/final website)
$docRoot = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/\\');
$baseDir = str_replace('\\', '/', dirname(__DIR__));
$basePath = '/' . ltrim(str_replace($docRoot, '', $baseDir), '/');
if ($basePath !== '/' && strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}
$slug = ltrim(rtrim($uri, '/'), '/');
$apartment = get_apartment($slug);

if (!$apartment) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

$images = get_apartment_images($apartment['id']);
$amenities = get_apartment_amenities($apartment['id']);
$testimonials = get_apartment_testimonials($apartment['id']);
$allTestimonial = get_featured_testimonials();

// Build a page object for header (SEO)
$page = [
    'id' => $apartment['page_id'],
    'slug' => $apartment['slug'],
    'title' => $apartment['name'],
    'meta_title' => $apartment['meta_title'] ?? $apartment['name'] . ' — Viata Luxe Guesthouse',
    'meta_description' => $apartment['meta_description'] ?? ($apartment['subtitle'] . '. ' . $apartment['description']),
    'og_image' => $apartment['og_image'] ?? $apartment['hero_image'],
];

$nav = $nav ?? get_navigation();
$settings = $settings ?? settings_group('branding');
$contact = $contact ?? settings_group('contact');
$booking = settings_group('booking');

require __DIR__ . '/../templates/header.php';
?>

<main id="main-content">
    <!-- Apartment hero -->
    <section class="page-head">
        <div class="page-head__inner">
            <div class="kicker reveal"><?= e($apartment['name']) ?> · Viata Luxe</div>
            <h1 class="page-head__title reveal"><?= e($apartment['name']) ?></h1>
            <?php if (!empty($apartment['subtitle'])): ?>
            <p class="page-head__lead reveal"><?= e($apartment['subtitle']) ?> — <?= e($apartment['description']) ?></p>
            <?php else: ?>
            <p class="page-head__lead reveal"><?= e($apartment['description']) ?></p>
            <?php endif; ?>
            <div class="page-head__meta reveal">
                <span class="chip">Sleeps <?= e($apartment['max_guests']) ?></span>
                <span class="chip"><?= e($apartment['room_size_m2']) ?> m²</span>
                <span class="chip"><?= e($apartment['beds_description']) ?></span>
                <span class="chip">From <?= format_price((float)$apartment['price_per_night']) ?></span>
            </div>
        </div>
    </section>

    <!-- Photo gallery -->
    <?php if (!empty($images)): ?>
    <section class="container section">
        <div class="preview-grid reveal" role="list" style="grid-template-columns:repeat(auto-fill,minmax(320px,1fr))">
        <?php foreach ($images as $i => $img): ?>
            <img src="<?= e(image_url($img['image_path'])) ?>" alt="<?= e($img['alt_text'] ?? $apartment['name']) ?>" width="600" height="450" loading="lazy" decoding="async" role="listitem">
        <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Amenities -->
    <div class="container section">
        <div class="kicker reveal">Amenities</div>
        <h2 class="section-heading reveal">Everything you need</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:12px;margin-top:20px">
        <?php foreach ($amenities as $amenity): ?>
            <div style="border:1px solid var(--line);border-radius:12px;padding:14px 16px" class="reveal">
                <span class="amenity__icon" aria-hidden="true">◐</span>
                <strong><?= e($amenity['amenity_name']) ?></strong>
            </div>
        <?php endforeach; ?>
        </div>
    </section>

    <!-- Testimonial -->
    <?php $aptReview = array_filter($testimonials, fn($t) => $t['is_published']); ?>
    <?php $review = !empty($aptReview) ? reset($aptReview) : (!empty($allTestimonial) ? reset($allTestimonial) : null); ?>
    <?php if ($review): ?>
    <section class="container section" style="background:var(--ivory);border:1px solid var(--line);border-radius:var(--radius-lg);padding:clamp(18px,3vw,32px)">
        <div class="review__stars" aria-label="<?= (int)$review['rating'] ?> out of 5 stars"><?= str_repeat('★', (int)$review['rating']) ?></div>
        <p style="font-family:var(--font-display);font-size:clamp(20px,3vw,28px)">"<?= e($review['review_text']) ?>"</p>
        <p><strong><?= e($review['reviewer_name']) ?></strong></p>
    </section>
    <?php endif; ?>

    <!-- Booking CTA -->
    <section class="book section">
        <div class="container">
            <div class="book__inner reveal">
                <div>
                    <h2 class="book__title">Ready to stay<br>in <?= e($apartment['name']) ?>?</h2>
                    <p class="book__text" style="margin-top:12px">Pick your dates — NightsBridge confirms instantly.</p>
                </div>
                <div>
                    <div class="book__card">
                        <div class="kicker" style="color:var(--gold-300);margin-bottom:10px">Book Now</div>
                        <a class="btn btn--gold" href="<?= e(setting('booking_url', 'https://book.nightsbridge.com/38331')) ?>" target="_blank" rel="noopener" style="width:100%;justify-content:center">Check Availability — NightsBridge</a>
                        <p class="small" style="color:rgba(248,246,241,0.6);margin-top:10px;text-align:center">From <?= format_price((float)$apartment['price_per_night']) ?> · Self-catering · Secure</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
require __DIR__ . '/../templates/footer.php';