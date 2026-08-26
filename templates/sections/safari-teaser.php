<?php
/**
 * Section: Safari Teaser — Viata Luxe Guesthouse
 * Safari promotional block with image and text.
 * Variables: $section
 */

$activities = get_safari_activities();
?>

<div class="reveal" style="display:grid; grid-template-columns:1.1fr 0.9fr; gap:28px; align-items:center">
    <div>
        <?php if (!empty($section['subtitle'])): ?>
        <div class="kicker"><?= e($section['subtitle']) ?></div>
        <?php endif; ?>
        <?php if (!empty($section['title'])): ?>
        <h2 class="section-heading"><?= $section['title'] ?></h2>
        <?php endif; ?>
        <?php if (!empty($section['content'])): ?>
        <p class="subhead" style="margin-top:10px"><?= e($section['content']) ?></p>
        <?php endif; ?>
        <?php if (!empty($section['link_url'])): ?>
        <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:16px">
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
            <div style="font-family:var(--font-display); font-weight:300; font-size:22px">Safari Videos</div>
            <div style="font-size:13px; color:rgba(248,246,241,0.7)">Click to play — Kruger wildlife footage</div>
        </div>
    </div>
    <?php endif; ?>
</div>
