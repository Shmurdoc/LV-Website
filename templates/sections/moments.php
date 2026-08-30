<?php
/**
 * Section: Moments — Viata Luxe Guesthouse
 * 3 moments cards (Relaxation / Braai / Serenity)
 * Variables: $section
 * Single source — moments via get_moments()
 */
$pid = (int)($section['page_id'] ?? $page['id'] ?? 1);
$items = function_exists('get_moments') ? get_moments($pid) : [];
?>
<div class="moments__head reveal">
    <div>
        <?php if (!empty($section['subtitle'])): ?>
        <div class="kicker"><?= e($section['subtitle']) ?></div>
        <?php endif; ?>
        <?php if (!empty($section['title'])): ?>
        <h2 class="moments__title"><?= nl2br(e($section['title'])) ?></h2>
        <?php endif; ?>
    </div>
    <?php if (!empty($section['content'])): ?>
    <p class="moments__text"><?= e(strip_tags($section['content'])) ?></p>
    <?php endif; ?>
</div>
<?php if (!empty($items)): ?>
<div class="moments__grid">
    <?php foreach ($items as $i => $m): ?>
    <article class="moment reveal <?= $i ? 'reveal--delay-'.$i : '' ?>">
        <?php if (!empty($m['image_path'])): ?>
        <div class="moment__media"><img src="<?= e(image_url($m['image_path'])) ?>" alt="<?= e($m['alt_text'] ?? $m['title']) ?>" width="800" height="600" loading="lazy" decoding="async"></div>
        <?php endif; ?>
        <div class="moment__body">
            <?php if (!empty($m['kicker'])): ?><div class="moment__kicker"><?= e($m['kicker']) ?></div><?php endif; ?>
            <div class="moment__title"><?= e($m['title']) ?></div>
            <?php if (!empty($m['text'])): ?><p class="moment__text"><?= e($m['text']) ?></p><?php endif; ?>
        </div>
    </article>
    <?php endforeach; ?>
</div>
<?php endif; ?>
