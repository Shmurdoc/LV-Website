<?php
/**
 * Section: Stats / Counters — Viata Luxe Guesthouse
 * Animated number counters.
 * Variables: $section
 */

$stats = [];
if (!empty($section['content'])) {
    $decoded = json_decode($section['content'], true);
    if (is_array($decoded)) {
        $stats = $decoded;
    }
}
?>

<?php if (!empty($section['title'])): ?>
<h2 class="section-heading center reveal"><?= $section['title'] ?></h2>
<?php endif; ?>

<div class="stats-bar__inner" style="margin-top:24px">
<?php foreach ($stats as $i => $stat): ?>
    <div class="stat-item reveal reveal--delay-<?= $i ?>">
        <span class="stat-item__number counter" data-target="<?= e($stat['value'] ?? '0') ?>"><?= e($stat['value'] ?? '0') ?></span>
        <span class="stat-item__label"><?= e($stat['label'] ?? '') ?></span>
    </div>
<?php endforeach; ?>
</div>
