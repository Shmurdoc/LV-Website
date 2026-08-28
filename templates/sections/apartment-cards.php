<?php
/**
 * Section: Apartment Cards — Viata Luxe Guesthouse
 * Grid of apartment overview cards (for accommodation page).
 * Variables: $section, $apartments
 */

$apartments = $apartments ?? get_apartments();
?>

<?php if (!empty($section['title'])): ?>
<h2 class="section-heading reveal"><?= e($section['title']) ?></h2>
<?php endif; ?>

<section class="rooms mt-14">
<?php foreach ($apartments as $i => $apt): ?>
    <article class="room reveal<?= $i % 2 === 1 ? ' room--reverse' : '' ?>" data-grade="<?= $i + 1 ?>">
        <?php
        $img = $apt['hero_image'] ?? $apt['image'] ?? null;
        $roomSize = $apt['room_size_m2'] ?? $apt['room_size'] ?? null;
        $beds = $apt['beds_description'] ?? $apt['bed_type'] ?? 'Queen 157cm';
        $short = $apt['subtitle'] ?? $apt['short_description'] ?? $apt['name'];
        ?>
        <?php if (!empty($img)): ?>
        <div class="room__media" data-lightbox href="<?= e(image_url($img)) ?>">
            <img src="<?= e(image_url($img)) ?>" alt="<?= e($apt['name']) ?>" width="1200" height="800" loading="lazy" decoding="async">
            <div class="room__grade room__grade--<?= $i + 1 ?>"></div>
            <span class="room__badge"><?= e($apt['name']) ?> · <?= e($beds) ?><?= $roomSize ? ' · ' . e($roomSize) . ' m²' : '' ?></span>
        </div>
        <?php endif; ?>
        <div class="room__body">
            <div class="room__kicker">0<?= $i + 1 ?> — <?= e($apt['name']) ?></div>
            <h2 class="room__title"><?= e($short) ?></h2>
            <div class="room__specs">
                <?php if ($roomSize): ?><span class="spec"><strong><?= e($roomSize) ?> m²</strong></span><?php endif; ?>
                <span class="spec">Sleeps <strong><?= e($apt['max_guests']) ?></strong></span>
                <span class="spec"><?= e($beds) ?></span>
                <span class="spec">Bushveld Views</span>
            </div>
            <?php if (!empty($apt['description'])): ?>
            <p class="room__copy"><?= e(mb_strimwidth($apt['description'], 0, 300, '...')) ?></p>
            <?php endif; ?>
            <div class="room__price">
                <strong><?= format_price((float)$apt['price_per_night']) ?></strong>
                <span>per night</span>
            </div>
            <div class="room__actions">
                <a class="btn btn--navy" href="<?= e(url($apt['slug'])) ?>">View <?= e($apt['name']) ?> detail →</a>
                <a class="link" href="<?= e(setting('booking_url', '#')) ?>" target="_blank" rel="noopener">Book Now</a>
            </div>
        </div>
    </article>
<?php endforeach; ?>
</section>
