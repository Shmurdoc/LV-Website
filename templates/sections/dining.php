<?php
/**
 * Section: Dining — Viata Luxe Guesthouse
 * Dining options showcase with image and grid of items.
 * Variables: $section
 * Single source — dining_items via get_dining_items(), no duplication in pages/home.php
 */
$pid = (int)($section['page_id'] ?? $page['id'] ?? 1);
$items = function_exists('get_dining_items') ? get_dining_items($pid) : [];
// Fallback: if DB empty, try JSON in section content, else original hardcoded (no masking — only if DB returns empty)
if (empty($items) && !empty($section['content'])) {
    $decoded = json_decode($section['content'], true);
    if (is_array($decoded) && isset($decoded[0]['title'])) {
        $items = $decoded;
    }
}
if (empty($items)) {
    $items = [
        ['title' => 'Self-Catering', 'time_label' => 'In your apartment', 'text' => 'Full kitchen with oven, hob, microwave, fridge, and all utensils.'],
        ['title' => 'Braai & Boma', 'time_label' => 'Outdoor area', 'text' => 'Traditional South African braai setup under the Limpopo stars.'],
        ['title' => 'Local Restaurants', 'time_label' => '5-10 min drive', 'text' => 'Bushveld dining, Italian, steakhouse — curated recommendations on arrival.'],
        ['title' => 'Private Bush Dinner', 'time_label' => 'On request', 'text' => 'Chef-prepared multi-course dinner in the bushveld setting.'],
    ];
}
?>
<div class="dining__inner">
    <?php if (!empty($section['image'])): ?>
    <div class="dining__media reveal">
        <img src="<?= e(image_url($section['image'])) ?>" alt="<?= e($section['title'] ?? 'Dining') ?>" width="600" height="450" loading="lazy"/>
        <span class="dining__media-badge"><?= e($section['link_text'] ?? 'Open-air dining') ?></span>
    </div>
    <?php endif; ?>
    <div class="dining__body">
        <?php if (!empty($section['subtitle'])): ?>
        <span class="kicker"><?= e($section['subtitle']) ?></span>
        <?php endif; ?>
        <?php if (!empty($section['title'])): ?>
        <h2 class="section-heading" id="dining-heading"><?= e($section['title']) ?></h2>
        <?php endif; ?>
        <?php if (!empty($section['content']) && empty($items)): ?>
        <p class="subhead"><?= e($section['content']) ?></p>
        <?php elseif (!empty($section['content']) && is_string($section['content']) && json_decode($section['content']) === null): ?>
        <p class="subhead"><?= e(strip_tags($section['content'])) ?></p>
        <?php endif; ?>
        <div class="dining__grid">
        <?php
        $diningIcons = ['utensils', 'flame', 'map-pin', 'chef-hat'];
        foreach ($items as $idx => $item): ?>
            <div class="dining-item">
                <div class="dining-item__icon"><i data-lucide="<?= $diningIcons[$idx % count($diningIcons)] ?>" class="icon--md"></i></div>
                <h3 class="dining-item__title"><?= e($item['title'] ?? '') ?></h3>
                <span class="dining-item__time"><?= e($item['time_label'] ?? $item['time'] ?? '') ?></span>
                <p class="dining-item__text"><?= e($item['text'] ?? '') ?></p>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
</div>
