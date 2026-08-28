<?php
/**
 * Section: Safari Teaser — Viata Luxe Guesthouse
 * Safari promotional block with image and text.
 * Variables: $section
 */

$activities = get_safari_activities();
?>

<div class="layout-2col-wide reveal">
    <div>
        <?php if (!empty($section['subtitle'])): ?>
        <div class="kicker"><?= e($section['subtitle']) ?></div>
        <?php endif; ?>
        <?php if (!empty($section['title'])): ?>
        <h2 class="section-heading"><?= e($section['title']) ?></h2>
        <?php endif; ?>
        <?php if (!empty($section['content'])): ?>
        <p class="subhead mt-10"><?= e($section['content']) ?></p>
        <?php endif; ?>
        <?php if (!empty($section['link_url'])): ?>
        <div class="safari-teaser__links mt-16">
            <a class="btn btn--navy" href="<?= e($section['link_url']) ?>"><?= e($section['link_text'] ?? 'Explore Safari') ?></a>
        </div>
        <?php endif; ?>
    </div>
    <?php if (!empty($section['image'])): ?>
    <div class="safari-tease reveal reveal--delay-1">
        <div class="safari-tease__media">
            <img src="<?= e(image_url($section['image'])) ?>" alt="<?= e($section['title'] ?? 'Safari') ?>" loading="lazy" decoding="async">
            <div class="safari-tease__veil"></div>
        </div>
        <div class="safari-tease__body">
            <div class="safari-teaser__body-title">Safari Videos</div>
            <div class="safari-teaser__body-sub">Click to play — Kruger wildlife footage</div>
        </div>
    </div>
    <?php endif; ?>
</div>
