<?php
/**
 * Section: Specials Banner — Viata Luxe Guesthouse
 * Promotional offer bar.
 * Variables: $section
 */
?>

<div class="specials__inner">
    <div class="specials__text">
        <?php if (!empty($section['subtitle'])): ?>
        <span class="specials__label"><?= e($section['subtitle']) ?></span>
        <?php endif; ?>
        <?php if (!empty($section['title'])): ?>
        <h2 class="specials__title"><?= e($section['title']) ?></h2>
        <?php endif; ?>
        <?php if (!empty($section['content'])): ?>
        <p class="specials__detail"><?= e($section['content']) ?></p>
        <?php endif; ?>
    </div>
    <?php if (!empty($section['link_url'])): ?>
    <a href="<?= e($section['link_url']) ?>" class="btn btn--primary" target="_blank" rel="noopener"><?= e($section['link_text'] ?? 'Claim Offer') ?></a>
    <?php endif; ?>
</div>
