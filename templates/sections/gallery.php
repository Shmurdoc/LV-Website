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
    <section class="reveal" style="margin-top:44px">
        <div class="kicker" style="display:flex;align-items:center;gap:10px">
            <?= e($cat['name']) ?>
            <span class="small muted" style="color:var(--ink-55)"><?= count($catImages) ?> frames</span>
        </div>
        <h3 class="section-heading" style="font-size:clamp(24px,3vw,34px)"><?= e($cat['name']) ?></h3>
        <?php if (!empty($cat['description'])): ?>
        <p class="subhead"><?= e($cat['description']) ?></p>
        <?php endif; ?>
        <div class="preview-grid" style="margin-top:18px" role="list">
        <?php foreach ($catImages as $img): ?>
            <img src="<?= e(image_url($img['image_path'])) ?>" alt="<?= e($img['alt_text'] ?? $cat['name'] . ' image') ?>" width="400" height="300" loading="lazy" decoding="async" role="listitem">
        <?php endforeach; ?>
        </div>
    </section>
<?php endforeach; ?>
<?php endif; ?>

<?php else: ?>
<?php $gallery_images = get_featured_gallery(12); ?>
<?php if (!empty($section['title'])): ?>
<div class="reveal" style="display:flex; justify-content:space-between; align-items:end; gap:24px; flex-wrap:wrap">
    <div>
        <div class="kicker"><?= e($section['subtitle'] ?? 'Gallery') ?></div>
        <h2 class="section-heading"><?= e($section['title']) ?></h2>
    </div>
    <?php if (!empty($section['link_url'])): ?>
    <a class="btn btn--navy" href="<?= e($section['link_url']) ?>"><?= e($section['link_text'] ?? 'View Gallery') ?></a>
    <?php endif; ?>
</div>
<?php endif; ?>

<div class="preview-grid reveal" style="margin-top:22px" role="list">
<?php if (empty($gallery_images)): ?>
    <p class="muted small" role="status" style="grid-column:1/-1">No images yet — add photos in Gallery Categories.</p>
<?php else: ?>
<?php foreach ($gallery_images as $img): ?>
    <img src="<?= e(image_url($img['image_path'])) ?>" alt="<?= e($img['alt_text'] ?? $img['category_name'] ?? 'Gallery image') ?>" loading="lazy" decoding="async" width="400" height="300" role="listitem">
<?php endforeach; ?>
<?php endif; ?>
</div>
<?php endif; ?>