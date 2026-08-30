<?php
/**
 * Section: Stats / Counters — Viata Luxe Guesthouse
 * Animated number counters.
 * Variables: $section
 */

$stats = [];
if (!empty($section['content'])) {
    $decoded = json_decode($section['content'], true);
    if (is_array($decoded) && isset($decoded[0])) {
        $stats = $decoded;
    } elseif (is_array($decoded) && !empty($decoded)) {
        // single object fallback wrap
        $stats = [$decoded];
    }
}
// No fallback masking — if JSON invalid or empty, show empty state (section content is single source)
?>

<?php if (!empty($section['title'])): ?>
<h2 class="section-heading center reveal"><?= e($section['title']) ?></h2>
<?php endif; ?>

<div class="stats-bar">
    <div class="stats-bar__inner">
    <?php foreach ($stats as $i => $stat): ?>
        <div class="stat-item reveal reveal--delay-<?= min($i,3) ?>">
            <span class="stat-item__number counter" data-target="<?= e($stat['value'] ?? '0') ?>"><?= e($stat['value'] ?? '0') ?></span>
            <span class="stat-item__label"><?= e($stat['label'] ?? '') ?></span>
        </div>
    <?php endforeach; ?>
    </div>
</div>
