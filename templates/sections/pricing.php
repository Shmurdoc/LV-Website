<?php
/**
 * Section: Pricing Cards — Viata Luxe Guesthouse
 * 4 apartment price cards.
 * Variables: $section
 */

$apartments = get_apartments();
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

<div class="pricing__cards mt-32">
<?php foreach ($apartments as $i => $apt): ?>
    <article class="price-card reveal reveal--delay-<?= min($i, 3) ?><?= $i === 2 ? ' price-card--featured' : '' ?>">
        <?php
        $img = $apt['hero_image'] ?? $apt['image'] ?? null;
        $roomSize = $apt['room_size_m2'] ?? $apt['room_size'] ?? null;
        $beds = $apt['beds_description'] ?? $apt['bed_type'] ?? 'Queen';
        ?>
        <?php if (!empty($img)): ?>
        <div class="price-card__media">
            <img src="<?= e(image_url($img)) ?>" alt="<?= e($apt['name']) ?>" width="400" height="250" loading="lazy" decoding="async"/>
        </div>
        <?php endif; ?>
        <div class="price-card__body">
            <h3 class="price-card__name"><?= e($apt['name']) ?></h3>
            <div class="price-card__price">
                <strong><?= format_price((float)$apt['price_per_night']) ?></strong>
                <span>/night</span>
            </div>
            <div class="price-card__features">
                <span class="price-card__feature">Sleeps <?= e($apt['max_guests']) ?><?= $roomSize ? ' · ' . e($roomSize) . ' m²' : '' ?></span>
                <span class="price-card__feature"><?= e($beds) ?></span>
                <?php $amenities = get_apartment_amenities((int)$apt['id']); $top = array_slice($amenities, 0, 4); foreach ($top as $am): ?>
                <span class="price-card__feature"><?= e($am['amenity_name']) ?></span>
                <?php endforeach; ?>
                <?php if (empty($amenities)): ?>
                <span class="price-card__feature">Jacuzzi access</span>
                <span class="price-card__feature">Secure parking</span>
                <?php endif; ?>
            </div>
            <a href="<?= e(url($apt['slug'])) ?>" class="btn btn--outline price-card__cta">View Details</a>
        </div>
    </article>
<?php endforeach; ?>
</div>
