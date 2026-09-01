<?php
/**
 * Section: Booking CTA — Viata Luxe Guesthouse
 * Dark-background call to action with booking link.
 * Variables: $section, $booking (settings)
 */

$booking = $booking ?? settings_group('booking');
?>

<div class="book__inner reveal">
    <div>
        <?php if (!empty($section['title'])): ?>
        <h2 class="book__title"><?= e($section['title']) ?></h2>
        <?php endif; ?>
        <?php if (!empty($section['content'])): ?>
        <p class="book__text mt-12"><?= e($section['content']) ?></p>
        <?php endif; ?>
        <?php if (!empty($section['link_text'])): ?>
        <?php $facts = array_map('trim', explode('|', $section['link_text'])); ?>
        <div class="book__facts">
            <?php foreach ($facts as $f): ?>
            <span><?= e($f) ?></span>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    <div>
        <div class="book__card">
            <div class="book__label kicker"><?= e(setting('booking_cta_label', 'Book Now')) ?></div>
            <a class="book__cta-btn btn btn--gold" href="<?= e(setting('booking_url', 'https://book.nightsbridge.com/38331')) ?>" target="_blank" rel="noopener">
                <?= e(setting('booking_cta_text', 'Check Availability — NightsBridge')) ?><i data-lucide="arrow-right" class="icon--cta"></i>
            </a>
            <p class="book__fine-print small"><?= e(setting('booking_cta_fine_print', 'Self-catering · Secure · Instant confirm')) ?></p>
        </div>
    </div>
</div>
