<?php
/**
 * Section: Dining — Viata Luxe Guesthouse
 * Dining options showcase with image and grid of items.
 * Variables: $section
 */

$dining_items = [
    ['title' => 'Self-Catering', 'time' => 'In your apartment', 'text' => 'Full kitchen with oven, hob, microwave, fridge, and all utensils.'],
    ['title' => 'Braai & Boma', 'time' => 'Outdoor area', 'text' => 'Traditional South African braai setup under the Limpopo stars.'],
    ['title' => 'Local Restaurants', 'time' => '5-10 min drive', 'text' => 'Bushveld dining, Italian, steakhouse — curated recommendations on arrival.'],
    ['title' => 'Private Bush Dinner', 'time' => 'On request', 'text' => 'Chef-prepared multi-course dinner in the bushveld setting.'],
];

// Override with DB content if provided
if (!empty($section['content'])) {
    $decoded = json_decode($section['content'], true);
    if (is_array($decoded)) {
        $dining_items = $decoded;
    }
}
?>

<div class="container dining__inner">
    <?php if (!empty($section['image'])): ?>
    <div class="dining__media reveal">
        <img src="<?= e(image_url($section['image'])) ?>" alt="<?= e($section['title'] ?? 'Dining') ?>" width="600" height="450" loading="lazy"/>
        <span class="dining__media-badge">Open-air dining</span>
    </div>
    <?php endif; ?>
    <div class="dining__body">
        <?php if (!empty($section['subtitle'])): ?>
        <span class="kicker"><?= e($section['subtitle']) ?></span>
        <?php endif; ?>
        <?php if (!empty($section['title'])): ?>
        <h2 class="section-heading"><?= $section['title'] ?></h2>
        <?php endif; ?>
        <div class="dining__grid" style="margin-top:16px">
        <?php foreach ($dining_items as $item): ?>
            <div class="dining-item">
                <h3 class="dining-item__title"><?= e($item['title'] ?? '') ?></h3>
                <span class="dining-item__time"><?= e($item['time'] ?? '') ?></span>
                <p class="dining-item__text"><?= e($item['text'] ?? '') ?></p>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
</div>
