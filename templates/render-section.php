<?php
/**
 * Section Renderer — Viata Luxe Guesthouse
 * Dispatches section rendering based on section_type.
 * Variables expected: $section (from get_sections loop)
 */

// Build CSS classes from orientation data
$classes = ['section'];
$classes[] = 'section-' . $section['section_type'];

if (!empty($section['layout'])) {
    $classes[] = 'layout-' . $section['layout'];
}
if (!empty($section['css_class'])) {
    $classes[] = $section['css_class'];
}

$style = '';
if (!empty($section['background_color'])) {
    $style .= 'background-color:' . e($section['background_color']) . ';';
}
if (!empty($section['background_image'])) {
    $style .= 'background-image:url(' . e(image_url($section['background_image'])) . ');background-size:cover;background-position:center;';
}
if (!empty($section['text_color'])) {
    $style .= 'color:' . e($section['text_color']) . ';';
}
if (!empty($section['padding_top'])) {
    $style .= 'padding-top:' . e($section['padding_top']) . ';';
}
if (!empty($section['padding_bottom'])) {
    $style .= 'padding-bottom:' . e($section['padding_bottom']) . ';';
}

$maxWidth = $section['max_width'] ?? '1200px';
$alignment = $section['alignment'] ?? 'left';
$animation = $section['animation'] ?? 'fade-up';
$isHero = $section['section_type'] === 'hero';
?>

<?php if ($isHero): ?>
<?php
// Hero — full-bleed, editorial, veil 0.4, Ken Burns (main.css)
// Track B: hero_slides slideshow (5 slides) via get_hero_slides(), fallback to single image
$heroPid = (int)($section['page_id'] ?? $page['id'] ?? 1);
$heroSlides = function_exists('get_hero_slides') ? get_hero_slides($heroPid) : [];
if (!empty($heroSlides)):
?>
<section id="hero" class="hero--slideshow <?= implode(' ', $classes) ?>" aria-label="Viata Luxe hero slideshow" data-animation="<?= e($animation) ?>">
    <div class="slides" id="slides">
        <?php foreach ($heroSlides as $idx => $sl): ?>
        <div class="slide<?= $idx === 0 ? ' is-active' : '' ?>" data-caption="<?= e($sl['caption'] ?? '') ?>">
            <div class="slide__media"><img src="<?= e(image_url($sl['image_path'])) ?>" alt="<?= e($sl['alt_text'] ?? '') ?>" width="1920" height="1080" <?= $idx === 0 ? 'fetchpriority="high"' : 'loading="lazy"' ?> decoding="async"></div>
            <div class="slide__veil"></div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="hero__content hero__content--slideshow">
        <?php
        $sectionFile = __DIR__ . '/sections/' . $section['section_type'] . '.php';
        if (file_exists($sectionFile)) { require $sectionFile; }
        ?>
    </div>
    <button class="hero-ctrl hero-ctrl--prev" id="prevSlide" aria-label="Previous slide">‹</button>
    <button class="hero-ctrl hero-ctrl--next" id="nextSlide" aria-label="Next slide">›</button>
    <button id="heroPause" class="hero__pause" aria-pressed="false" aria-label="Pause slideshow" title="Pause">❚❚</button>
    <div class="hero-dots" id="heroDots" role="tablist" aria-label="Slideshow dots"></div>
    <div class="hero-progress" id="heroProgress"></div>
    <div class="slide__caption" id="slideCaption"><?= e($heroSlides[0]['caption'] ?? '') ?></div>
    <div class="hero__meta hide-mobile"><span>Self-catering</span><span>·</span><span>4 Apartments</span><span>·</span><span>Hosted</span></div>
</section>
<?php else: // fallback single-image hero (for subpages / no slides)
$heroImg = $section['image'] ?? $section['background_image'] ?? null;
$heroStyle = $style;
if (!empty($section['background_image']) && !empty($heroImg)) {
    $heroStyle = str_replace('background-image:url(' . e(image_url($section['background_image'])) . ');background-size:cover;background-position:center;', '', $heroStyle);
}
?>
<section class="hero <?= implode(' ', $classes) ?>"<?php if ($heroStyle): ?> style="<?= trim($heroStyle) ?>"<?php endif; ?> data-animation="<?= e($animation) ?>" id="hero">
    <?php if (!empty($heroImg)): ?>
    <div class="hero__media" aria-hidden="true">
        <img src="<?= e(image_url($heroImg)) ?>" alt="" width="1920" height="1080" fetchpriority="high" decoding="async">
    </div>
    <div class="hero__veil" aria-hidden="true"></div>
    <?php endif; ?>
    <div class="hero__content">
    <?php
    $sectionFile = __DIR__ . '/sections/' . $section['section_type'] . '.php';
    if (file_exists($sectionFile)) { require $sectionFile; }
    ?>
    </div>
    <button class="hero__pause" id="heroPause" aria-label="Pause hero animation" aria-pressed="false" title="Pause animation">❚❚</button>
    <div class="hero__scroll" aria-hidden="true"></div>
</section>
<?php endif; ?>
<?php else: ?>
<section class="<?= implode(' ', $classes) ?>"<?php if ($style): ?> style="<?= $style ?>"<?php endif; ?> data-animation="<?= e($animation) ?>">
    <div class="container" style="max-width:<?= e($maxWidth) ?>;margin:0 auto;text-align:<?= e($alignment) ?>;">

    <?php
    $sectionFile = __DIR__ . '/sections/' . $section['section_type'] . '.php';
    if (file_exists($sectionFile)) {
        require $sectionFile;
    } else {
        if (!empty($section['title'])): ?>
            <h2 class="section-title"><?= e($section['title']) ?></h2>
        <?php endif;
        if (!empty($section['subtitle'])): ?>
            <p class="section-subtitle"><?= e($section['subtitle']) ?></p>
        <?php endif;
        if (!empty($section['content'])): ?>
            <div class="section-content"><?= e($section['content']) ?></div>
        <?php endif;
    }
    ?>

    </div>
</section>
<?php endif; ?>
