<?php
/**
 * Section: Image + Text — Viata Luxe Guesthouse
 * Two-column layout with image on one side, text on the other.
 * Variables: $section
 */

// Schema enum: text-left, text-right, text-top, image-top, text-only, image-only, full-width, centered, grid-2/3/4
// Backward-compat for legacy values
$rawLayout = $section['layout'] ?? 'text-left';
$legacyMap = ['image-left' => 'text-right', 'image-right' => 'text-left', 'image-bottom' => 'text-top'];
$layout = $legacyMap[$rawLayout] ?? $rawLayout;
$isTextOnly  = $layout === 'text-only';
$isImageOnly = $layout === 'image-only';
$isVertical  = in_array($layout, ['image-top', 'text-top'], true);
$imageFirst  = ($layout === 'text-right' || $layout === 'image-top');
?>

<?php if ($isTextOnly): ?>
<div>
    <?php if (!empty($section['title'])): ?>
    <h2 class="section-heading reveal"><?= e($section['title']) ?></h2>
    <?php endif; ?>
    <?php if (!empty($section['subtitle'])): ?>
    <p class="subhead reveal"><?= e($section['subtitle']) ?></p>
    <?php endif; ?>
    <?php if (!empty($section['content'])): ?>
    <div class="prose reveal" style="margin-top:12px"><?= $section['content'] ?></div>
    <?php endif; ?>
    <?php if (!empty($section['link_url']) && !empty($section['link_text'])): ?>
    <div style="margin-top:16px" class="reveal">
        <a class="btn btn--navy" href="<?= e($section['link_url']) ?>"><?= e($section['link_text']) ?></a>
    </div>
    <?php endif; ?>
</div>
<?php elseif ($isImageOnly && !empty($section['image'])): ?>
<div class="card__media reveal" style="border-radius:var(--radius-lg); overflow:hidden">
    <img src="<?= e(image_url($section['image'])) ?>" alt="<?= e($section['title'] ?? '') ?>" loading="lazy" decoding="async" style="width:100%;height:auto">
</div>
<?php elseif ($isVertical): ?>
<div style="display:grid; gap:24px; align-items:center">
    <?php if ($layout === 'image-top' && !empty($section['image'])): ?>
    <div class="card__media" style="border-radius:var(--radius-lg); overflow:hidden">
        <img src="<?= e(image_url($section['image'])) ?>" alt="<?= e($section['title'] ?? '') ?>" loading="lazy" decoding="async">
    </div>
    <?php endif; ?>
    <div>
        <?php if (!empty($section['title'])): ?>
        <h2 class="section-heading reveal"><?= e($section['title']) ?></h2>
        <?php endif; ?>
        <?php if (!empty($section['subtitle'])): ?>
        <p class="subhead reveal"><?= e($section['subtitle']) ?></p>
        <?php endif; ?>
        <?php if (!empty($section['content'])): ?>
        <div class="prose reveal"><?= $section['content'] ?></div>
        <?php endif; ?>
        <?php if (!empty($section['link_url']) && !empty($section['link_text'])): ?>
        <div style="margin-top:16px" class="reveal">
            <a class="btn btn--navy" href="<?= e($section['link_url']) ?>"><?= e($section['link_text']) ?></a>
        </div>
        <?php endif; ?>
    </div>
    <?php if ($layout === 'text-top' && !empty($section['image'])): ?>
    <div class="card__media" style="border-radius:var(--radius-lg); overflow:hidden">
        <img src="<?= e(image_url($section['image'])) ?>" alt="<?= e($section['title'] ?? '') ?>" loading="lazy" decoding="async">
    </div>
    <?php endif; ?>
</div>
<?php else: ?>
<div style="display:grid; grid-template-columns:1.08fr 0.92fr; gap:32px; align-items:center">
    <?php if ($imageFirst && !empty($section['image'])): ?>
    <div class="card__media reveal" style="border-radius:var(--radius-lg); overflow:hidden; min-height:420px">
        <img src="<?= e(image_url($section['image'])) ?>" alt="<?= e($section['title'] ?? '') ?>" loading="lazy" decoding="async" style="width:100%;height:100%;object-fit:cover">
    </div>
    <?php endif; ?>
    <div>
        <?php if (!empty($section['title'])): ?>
        <h2 class="section-heading reveal"><?= e($section['title']) ?></h2>
        <?php endif; ?>
        <?php if (!empty($section['subtitle'])): ?>
        <p class="subhead reveal"><?= e($section['subtitle']) ?></p>
        <?php endif; ?>
        <?php if (!empty($section['content'])): ?>
        <div class="prose reveal" style="margin-top:12px"><?= $section['content'] ?></div>
        <?php endif; ?>
        <?php if (!empty($section['link_url']) && !empty($section['link_text'])): ?>
        <div style="margin-top:16px" class="reveal">
            <a class="btn btn--navy" href="<?= e($section['link_url']) ?>"><?= e($section['link_text']) ?></a>
        </div>
        <?php endif; ?>
    </div>
    <?php if (!$imageFirst && !empty($section['image'])): ?>
    <div class="card__media reveal" style="border-radius:var(--radius-lg); overflow:hidden; min-height:420px">
        <img src="<?= e(image_url($section['image'])) ?>" alt="<?= e($section['title'] ?? '') ?>" loading="lazy" decoding="async" style="width:100%;height:100%;object-fit:cover">
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>
