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
    <?php
    $specialsUrl = $section['link_url'] ?? '';
    if (empty($specialsUrl)) {
        $wa = preg_replace('/[^0-9]/','', setting('booking_whatsapp_number', setting('whatsapp','27618417838')));
        $msg = setting('booking_whatsapp_message', 'Hi Viata Luxe, I’d like to enquire about the 3-night stay offer.');
        $specialsUrl = 'https://wa.me/'.$wa.'?text='.rawurlencode($msg);
    }
    ?>
    <a href="<?= e($specialsUrl) ?>" class="btn btn--primary" target="_blank" rel="noopener"><?= e($section['link_text'] ?? 'Claim Offer') ?><i data-lucide="arrow-right" class="icon--cta"></i></a>
</div>
