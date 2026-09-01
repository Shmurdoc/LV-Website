<?php
/**
 * Section: Hero — Viata Luxe Guesthouse
 * Inner content for hero (slideshow outer handled by render-section.php)
 * Variables: $section
 */
?>
<?php if (!empty($section['subtitle'])): ?>
<div class="kicker hero-kicker reveal"><?= e($section['subtitle']) ?></div>
<?php endif; ?>
<?php if (!empty($section['title'])): ?>
<h1 class="hero__title reveal reveal--delay-1"><?= nl2br(e($section['title'])) ?></h1>
<?php endif; ?>
<?php if (!empty($section['content'])): ?>
<p class="hero__line reveal reveal--delay-2"><?= e(strip_tags($section['content'])) ?></p>
<?php endif; ?>
<?php if (!empty($section['link_text']) && !empty($section['link_url'])): ?>
<div class="hero__actions reveal reveal--delay-3">
    <a class="btn btn--gold" href="<?= e($section['link_url']) ?>" target="_blank" rel="noopener"><?= e($section['link_text']) ?></a>
    <a class="btn btn--ghost" href="<?= e(url('/accomodation/')) ?>"><?= e(setting('homepage_hero_cta_explore', 'Explore Accommodation')) ?></a>
</div>
<?php elseif (!empty($section['link_text'])): ?>
<div class="hero__actions reveal reveal--delay-3">
    <span class="hero__link kicker"><?= e($section['link_text']) ?></span>
</div>
<?php else: ?>
<div class="hero__actions reveal reveal--delay-3">
    <a class="btn btn--gold" href="<?= e(setting('booking_url', 'https://book.nightsbridge.com/38331')) ?>" target="_blank" rel="noopener"><?= e(setting('homepage_hero_cta_book', 'Book Now — NightsBridge')) ?></a>
    <a class="btn btn--ghost" href="<?= e(url('/accomodation/')) ?>"><?= e(setting('homepage_hero_cta_explore', 'Explore Accommodation')) ?></a>
</div>
<?php endif; ?>
