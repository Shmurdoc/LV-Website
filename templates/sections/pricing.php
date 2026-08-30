<?php
/**
 * Section: Room Cards — Viata Luxe Guesthouse
 * Editorial full-width cards matching reference design.
 * Alternating layout (room--reverse on even cards).
 * Variables: $section
 */

$apartments = get_apartments();
$allTestimonials = get_featured_testimonials();

// Map amenity names to icon symbols (matching reference design aesthetic)
$amenityIcons = [
    'Free WiFi'        => '⬢',
    'DStv'             => '✦',
    'Full Kitchen'     => '◐',
    'Gourmet Kitchen'  => '◐',
    'Secure Parking'   => '⚑',
    'Swimming Pool'    => '≋',
    'Private Pool'     => '≋',
    'Premium Pool'     => '≋',
    'Air Conditioning' => '☾',
    'Ensuite Bathroom' => '◉',
    'Dishwasher'       => '◐',
    'Private Patio'    => '♡',
    'Private Balcony'  => '♡',
    'Soaking Tub'      => '≋',
    'Panoramic Views'  => '◐',
    'Premium Linens'   => '☾',
];

// Amenity descriptions for tooltips
$amenityDescs = [
    'Free WiFi'        => 'Complimentary high-speed',
    'DStv'             => 'Flat-screen satellite',
    'Full Kitchen'     => 'Tea/Coffee, Minibar, Kettle',
    'Gourmet Kitchen'  => 'Fully equipped, premium',
    'Secure Parking'   => 'Covered, on-site',
    'Swimming Pool'    => 'Shared pool',
    'Private Pool'     => 'In-unit pool',
    'Premium Pool'     => 'In-suite, luxury',
    'Air Conditioning' => 'Climate controlled',
    'Ensuite Bathroom' => 'Private, modern fittings',
    'Dishwasher'       => 'Convenience included',
    'Private Patio'    => 'Outdoor relaxation',
    'Private Balcony'  => 'City view perch',
    'Soaking Tub'      => 'Deep soaking,放松',
    'Panoramic Views'  => 'Breathtaking Phalaborwa',
    'Premium Linens'   => 'Curated comfort',
];
?>

<?php if (!empty($section['title'])): ?>
<div class="pricing__head">
    <?php if (!empty($section['subtitle'])): ?>
    <span class="kicker"><?= e($section['subtitle']) ?></span>
    <?php endif; ?>
    <h2 class="section-heading"><?= e($section['title']) ?></h2>
    <?php if (!empty($section['content'])): ?>
    <p class="subhead"><?= e($section['content']) ?></p>
    <?php endif; ?>
</div>
<?php endif; ?>

<section class="rooms">
<?php foreach ($apartments as $i => $apt):
    $isReverse = ($i % 2 === 1);
    $grade = $apt['id'];
    $img = $apt['hero_image'] ?? $apt['image'] ?? null;
    $roomSize = $apt['room_size_m2'] ?? $apt['room_size'] ?? null;
    $beds = $apt['beds_description'] ?? $apt['bed_type'] ?? 'Queen';
    $maxGuests = $apt['max_guests'] ?? 2;

    // Features JSON
    $featJson = null;
    if (!empty($apt['features'])) {
        $decoded = is_string($apt['features']) ? json_decode($apt['features'], true) : $apt['features'];
        if (is_array($decoded) && !empty($decoded)) $featJson = $decoded;
    }

    // Amenities from DB
    $amenities = get_apartment_amenities((int)$apt['id']);

    // Testimonial for this apartment
    $aptTestimonial = null;
    foreach ($allTestimonials as $t) {
        if (isset($t['apartment_id']) && (int)$t['apartment_id'] === (int)$apt['id']) {
            $aptTestimonial = $t;
            break;
        }
    }
    // Fallback: get from apartment-specific function
    if (!$aptTestimonial) {
        $aptReviews = get_apartment_testimonials((int)$apt['id']);
        $published = array_filter($aptReviews, fn($r) => $r['is_published']);
        if (!empty($published)) $aptTestimonial = reset($published);
    }

    // Copy text from section content or apartment description
    $copyText = $apt['description'] ?? '';
    if (!empty($section['content'])) {
        $copyText = $section['content'];
    }
?>
    <article class="room <?= $isReverse ? 'room--reverse' : '' ?> reveal reveal--delay-<?= min($i, 3) ?>" data-grade="<?= $grade ?>" id="<?= $apt['slug'] ?? 'apt-' . $apt['id'] ?>-card">
        <div class="room__media" data-lightbox href="<?= e(image_url($img)) ?>">
            <img src="<?= e(image_url($img)) ?>" alt="<?= e($apt['name']) ?>" width="1200" height="800" loading="lazy" decoding="async">
            <div class="room__grade room__grade--<?= $grade ?>"></div>
            <span class="room__badge"><?= e($apt['name']) ?> · <?= e($roomSize) ?> m² · Sleeps <?= e($maxGuests) ?></span>
        </div>
        <div class="room__body">
            <div class="room__kicker"><?= sprintf('%02d', $i + 1) ?> — <?= e($apt['name']) ?></div>
            <h2 class="room__title"><?= e($apt['subtitle'] ?? $apt['name']) ?></h2>
            <div class="room__specs">
                <?php if ($roomSize): ?><span class="spec"><strong><?= e($roomSize) ?> m²</strong></span><?php endif; ?>
                <span class="spec">Sleeps <strong><?= e($maxGuests) ?></strong></span>
                <span class="spec"><?= e($beds) ?></span>
                <span class="spec">City Views</span>
            </div>
            <p class="room__copy"><?= e($copyText) ?></p>
            <div class="room__price">
                <strong><?= format_price((float)$apt['price_per_night']) ?></strong>
                <span>per night · Self-catering</span>
            </div>
            <?php if (!empty($amenities)): ?>
            <div class="amenities">
                <?php foreach (array_slice($amenities, 0, 4) as $am):
                    $name = $am['amenity_name'];
                    $icon = $amenityIcons[$name] ?? '✦';
                    $desc = $amenityDescs[$name] ?? '';
                ?>
                <div class="amenity">
                    <span class="amenity__icon"><?= $icon ?></span>
                    <span class="amenity__text"><strong><?= e($name) ?></strong><br><?= e($desc) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <div class="room__actions">
                <a class="btn btn--navy" href="<?= e(url($apt['slug'])) ?>">View <?= e($apt['name']) ?> detail →</a>
                <a class="link" href="https://book.nightsbridge.com/38331" target="_blank" rel="noopener">Book Now</a>
            </div>
            <?php if ($aptTestimonial): ?>
            <div class="room__notice">Testimonial — <strong><?= e($aptTestimonial['reviewer_name']) ?></strong>: "<?= e($aptTestimonial['review_text']) ?>"</div>
            <?php endif; ?>
        </div>
    </article>
<?php endforeach; ?>
</section>
