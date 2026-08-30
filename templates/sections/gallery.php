<?php
/**
 * Section: Gallery — Viata Luxe Guesthouse
 * On the Gallery page renders all categories + images.
 * On homepage renders a feature-limited preview grid.
 * Variables: $section
 */

$isGalleryPage = (($page['slug'] ?? current_slug()) === 'gallery');

if ($isGalleryPage):
    $categories = get_gallery_categories();
?>
<?php if (!empty($section['title'])): ?>
<div class="reveal">
    <div class="kicker"><?= e($section['subtitle'] ?? 'Gallery') ?></div>
    <h2 class="section-heading"><?= e($section['title']) ?></h2>
</div>
<?php endif; ?>

<?php if (empty($categories)): ?>
<p class="muted small" role="status">No gallery categories yet — add them in admin.</p>
<?php else: ?>
<?php foreach ($categories as $cat): ?>
    <?php $catImages = get_gallery_images((int)$cat['id']); ?>
    <?php if (empty($catImages)) continue; ?>
    <section class="gallery-section reveal">
        <div class="kicker flex-row-wrap">
            <?= e($cat['name']) ?>
            <span class="small muted"><?= count($catImages) ?> frames</span>
        </div>
        <h3 class="section-heading"><?= e($cat['name']) ?></h3>
        <div class="preview-grid" role="list">
        <?php foreach ($catImages as $img): ?>
            <img src="<?= e(image_url($img['image_path'])) ?>" alt="<?= e($img['alt_text'] ?? $cat['name'] . ' image') ?>" width="400" height="300" loading="lazy" decoding="async" role="listitem">
        <?php endforeach; ?>
        </div>
    </section>
<?php endforeach; ?>
<?php endif; ?>

<?php else: ?>
<?php $gallery_images = get_featured_gallery(8); ?>
<?php if (!empty($section['title'])): ?>
<div class="flex-between-end reveal">
    <div>
        <div class="kicker"><?= e($section['subtitle'] ?? 'Gallery') ?></div>
        <?php
        $parts = explode('.', $section['title'], 2);
        $firstPart = $parts[0] . '.';
        $secondPart = !empty($parts[1]) ? $parts[1] : '';
        ?>
        <h2 class="section-heading"><?= e($firstPart) ?><br><?php if ($secondPart): ?><em class="gold"><?= e(ltrim($secondPart)) ?></em><?php endif; ?></h2>
    </div>
    <?php if (!empty($section['link_url'])): ?>
    <a class="btn btn--navy" href="<?= e(preg_match('#^https?://#i', $section['link_url']) ? $section['link_url'] : url(ltrim($section['link_url'],'/'))) ?>"><?= e($section['link_text'] ?? 'View Gallery') ?></a>
    <?php endif; ?>
</div>
<?php endif; ?>

<div class="gallery-header preview-grid reveal" role="list">
<?php if (empty($gallery_images)): ?>
    <p class="gallery-empty muted small" role="status">No images yet — add photos in Gallery Categories.</p>
<?php else: ?>
<?php foreach ($gallery_images as $img): ?>
    <img src="<?= e(image_url($img['image_path'])) ?>" alt="<?= e($img['alt_text'] ?? $img['category_name'] ?? 'Gallery image') ?>" loading="lazy" decoding="async" width="400" height="300" role="listitem">
<?php endforeach; ?>
<?php endif; ?>
</div>
<?php endif; ?>
