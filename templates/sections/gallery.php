<?php
/**
 * Section: Gallery — Viata Luxe Guesthouse
 * Masonry grid of gallery images.
 * Variables: $section
 */

$gallery_images = get_featured_gallery(8);
?>

<?php if (!empty($section['title'])): ?>
<div class="reveal" style="display:flex; justify-content:space-between; align-items:end; gap:24px; flex-wrap:wrap">
    <div>
        <div class="kicker"><?= e($section['subtitle'] ?? 'Gallery') ?></div>
        <h2 class="section-heading"><?= $section['title'] ?></h2>
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
