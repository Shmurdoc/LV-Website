<?php
/**
 * Section: Apartments Grid — Viata Luxe Guesthouse
 * 4-column card grid with Lucide icons for amenity bullets.
 * Single source — homepage delegates here via render-section.php (DO NOT duplicate HTML in pages/home.php)
 */

$allApts = get_apartments();

// Map DB amenity_icon names to valid Lucide icon names
$iconMap = [
    'wifi'      => 'wifi',
    'tv'        => 'tv',
    'kitchen'   => 'utensils-crossed',
    'car'       => 'car',
    'hot-tub'   => 'droplets',
    'snowflake' => 'snowflake',
    'balcony'   => 'sun',
    'bath'      => 'bath',
    'dishwasher'=> 'utensils-crossed',
    'patio'     => 'sun',
    'mountain'  => 'mountain',
    'bed'       => 'bed-double',
];
?>

<section class="apartments reveal" id="apartments">
    <div class="apartments__head">
        <span class="kicker">4 Luxury Options</span>
        <h2 class="section-heading"><em class="gold">Our</em> Apartments</h2>
        <?php if (!empty($section['content'])): ?>
        <p class="subhead"><?= e($section['content']) ?></p>
        <?php else: ?>
        <p class="subhead">Each 13 m² suite is self-catering with city views, queen bed, and everything you need for a comfortable Phalaborwa stay.</p>
        <?php endif; ?>
    </div>

    <div class="apartments__grid">
        <?php foreach ($allApts as $i => $apt):
            $img = $apt['hero_image'] ?? null;
            $slug = $apt['slug'] ?? 'apt-' . $apt['id'];
            $amenities = get_apartment_amenities((int)$apt['id']);
        ?>
        <article class="apartment-card reveal reveal--delay-<?= min($i, 3) ?>">
            <a href="<?= e(url($slug)) ?>" class="apartment-card__link" aria-label="View <?= e($apt['name']) ?>">
                <div class="apartment-card__media">
                    <?php if (!empty($img)): ?>
                    <img src="<?= e(image_url($img)) ?>" alt="<?= e($apt['name']) ?>" width="600" height="400" loading="lazy" decoding="async">
                    <?php endif; ?>
                    <span class="apartment-card__price"><?= format_price((float)$apt['price_per_night']) ?> <small>/night</small></span>
                </div>
                <div class="apartment-card__body">
                    <h3 class="apartment-card__title"><?= e($apt['name']) ?></h3>
                    <?php if (!empty($amenities)): ?>
                    <ul class="apartment-card__amenities">
                        <?php foreach (array_slice($amenities, 0, 6) as $am):
                            $lucideName = $iconMap[$am['amenity_icon']] ?? 'check';
                        ?>
                        <li><i data-lucide="<?= $lucideName ?>" class="icon--xs"></i> <?= e($am['amenity_name']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                    <span class="apartment-card__cta">View Details <i data-lucide="arrow-right" class="icon--xs"></i></span>
                </div>
            </a>
        </article>
        <?php endforeach; ?>
    </div>
</section>
