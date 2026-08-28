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
        <p class="book__text" style="margin-top:12px"><?= e($section['content']) ?></p>
        <?php endif; ?>
        <?php if (!empty($section['link_text'])): ?>
        <div class="book__facts">
            <span><?= e($section['link_text']) ?></span>
        </div>
        <?php endif; ?>
    </div>
    <div>
        <div class="book__card">
            <div class="kicker" style="color:var(--gold-300);margin-bottom:10px">Book Now</div>
            <a class="btn btn--gold" href="<?= e(setting('booking_url', 'https://book.nightsbridge.com/38331')) ?>" target="_blank" rel="noopener" style="width:100%;justify-content:center">
                <?= e(setting('booking_cta_text', 'Check Availability — NightsBridge')) ?>
            </a>
            <p class="small" style="color:rgba(248,246,241,0.6);margin-top:10px;text-align:center">Self-catering · Secure · Instant confirm</p>
        </div>
    </div>
</div>
