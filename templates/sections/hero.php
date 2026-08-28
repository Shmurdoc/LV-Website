<?php
/**
 * Section: Hero — Viata Luxe Guesthouse
 * Renders a hero section with background image, title, subtitle, CTA.
 * Variables: $section
 */

$slides = [];
if (!empty($section['content'])) {
    // Content may contain JSON-encoded slides or simple HTML
    $decoded = json_decode($section['content'], true);
    if (is_array($decoded)) {
        $slides = $decoded;
    }
}
?>

<?php if (!empty($section['title'])): ?>
    <?php if (!empty($section['subtitle'])): ?>
    <div class="kicker hero__kicker reveal"><?= e($section['subtitle']) ?></div>
    <?php endif; ?>
    <h1 class="hero__title reveal reveal--delay-1"><?= e($section['title']) ?></h1>
    <?php if (!empty($section['content']) && empty($slides)): ?>
    <p class="hero__line reveal reveal--delay-1"><?= e(strip_tags($section['content'])) ?></p>
    <?php endif; ?>
    <?php if (!empty($section['link_text']) && !empty($section['link_url'])): ?>
    <div class="hero__actions reveal reveal--delay-2">
        <a class="btn btn--gold" href="<?= e($section['link_url']) ?>" target="_blank" rel="noopener"><?= e($section['link_text']) ?></a>
        <a class="btn btn--ghost" href="<?= url('/accomodation/') ?>">Explore Stays</a>
    </div>
    <?php elseif (!empty($section['link_text'])): ?>
    <div class="hero__actions reveal reveal--delay-2">
        <span class="hero__link kicker"><?= e($section['link_text']) ?></span>
    </div>
    <?php endif; ?>
<?php endif; ?>
