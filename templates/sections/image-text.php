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
    <div class="prose reveal mt-12"><?= $section['content'] ?></div>
    <?php endif; ?>
    <?php if (!empty($section['link_url']) && !empty($section['link_text'])): ?>
    <div class="mt-16 reveal">
        <a class="btn btn--navy" href="<?= e($section['link_url']) ?>"><?= e($section['link_text']) ?></a>
    </div>
    <?php endif; ?>
</div>
<?php elseif ($isImageOnly && !empty($section['image'])): ?>
<div class="img-text-media card__media reveal">
    <img src="<?= e(image_url($section['image'])) ?>" alt="<?= e($section['title'] ?? '') ?>" loading="lazy" decoding="async">
</div>
<?php elseif ($isVertical): ?>
<div class="layout-stacked">
    <?php if ($layout === 'image-top' && !empty($section['image'])): ?>
    <div class="img-text-media card__media">
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
        <div class="prose reveal"><?= sanitize_html($section['content']) ?></div>
        <?php endif; ?>
        <?php if (!empty($section['link_url']) && !empty($section['link_text'])): ?>
        <div class="mt-16 reveal">
            <a class="btn btn--navy" href="<?= e($section['link_url']) ?>"><?= e($section['link_text']) ?></a>
        </div>
        <?php endif; ?>
    </div>
    <?php if ($layout === 'text-top' && !empty($section['image'])): ?>
    <div class="img-text-media card__media">
        <img src="<?= e(image_url($section['image'])) ?>" alt="<?= e($section['title'] ?? '') ?>" loading="lazy" decoding="async">
    </div>
    <?php endif; ?>
</div>
<?php else: ?>
<div class="layout-2col">
    <?php if ($imageFirst && !empty($section['image'])): ?>
    <div class="img-text-media--tall card__media reveal">
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
<div class="prose reveal mt-12"><?= sanitize_html($section['content']) ?></div>
        <?php endif; ?>
        <?php if (!empty($section['link_url']) && !empty($section['link_text'])): ?>
        <div class="mt-16 reveal">
            <a class="btn btn--navy" href="<?= e($section['link_url']) ?>"><?= e($section['link_text']) ?></a>
        </div>
        <?php endif; ?>
    </div>
    <?php if (!$imageFirst && !empty($section['image'])): ?>
    <div class="img-text-media--tall card__media reveal">
        <img src="<?= e(image_url($section['image'])) ?>" alt="<?= e($section['title'] ?? '') ?>" loading="lazy" decoding="async">
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>
