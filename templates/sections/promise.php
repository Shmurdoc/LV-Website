<?php
/**
 * Section: Promise — Viata Luxe Guesthouse
 * 5 pillars (Our Rooms / Amenities / Dining / Safari / Moments) + editorial copy
 * Variables: $section
 * Single source — promise_pillars via get_promise_pillars()
 */
$pid = (int)($section['page_id'] ?? $page['id'] ?? 1);
$pillars = function_exists('get_promise_pillars') ? get_promise_pillars($pid) : [];
// Fallback to hardcoded if DB empty (no fallback masking — empty check only)
if (empty($pillars) && !empty($section['content'])) {
    // content is editorial HTML — keep as is, pillars will be hidden
}
?>
<div class="promise__grid">
    <div class="reveal">
        <?php if (!empty($section['subtitle'])): ?>
        <div class="kicker"><?= e($section['subtitle']) ?></div>
        <?php endif; ?>
        <?php if (!empty($section['title'])): ?>
        <h2 class="promise__title"><?= e($section['title']) ?></h2>
        <?php endif; ?>
        <?php if (!empty($section['content'])): ?>
        <div class="promise__copy" style="margin-top:14px"><?= sanitize_html($section['content']) ?></div>
        <?php endif; ?>
    </div>
    <div class="reveal reveal--delay-1">
        <?php if (!empty($pillars)): ?>
        <div class="pillars">
            <?php foreach (array_slice($pillars, 0, 3) as $p): ?>
            <div class="pillar">
                <div class="pillar__icon" aria-hidden="true"><?= e($p['icon'] ?? '◐') ?></div>
                <div class="pillar__title"><?= e($p['title']) ?></div>
                <div class="pillar__text"><?= e($p['text']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="pillars" style="margin-top:14px">
            <?php foreach (array_slice($pillars, 3, 2) as $idx => $p): ?>
            <div class="pillar" <?= $idx===1 ? 'style="grid-column:span 2"' : '' ?>>
                <div class="pillar__icon" aria-hidden="true"><?= e($p['icon'] ?? '◐') ?></div>
                <div class="pillar__title"><?= e($p['title']) ?></div>
                <div class="pillar__text"><?= e($p['text']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
