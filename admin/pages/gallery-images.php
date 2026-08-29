<?php
// Gallery Images — Viata Luxe Guesthouse — full CRUD (DB-driven, no hardcoded fallback)
$db = Database::get();
$category_id = (int)($_GET['category_id'] ?? 0);
$category = null;
if ($category_id) {
    $stmt = $db->prepare('SELECT * FROM gallery_categories WHERE id = :id');
    $stmt->execute(['id' => $category_id]);
    $category = $stmt->fetch() ?: null;
}
$images = [];
if ($category) {
    $stmt = $db->prepare('SELECT * FROM gallery_images WHERE category_id = :id ORDER BY sort_order ASC, id ASC');
    $stmt->execute(['id' => $category_id]);
    $images = $stmt->fetchAll();
}
?>
<div class="admin-page">
  <div class="page-header page-header--spread">
    <div><h2>Gallery Images</h2><p class="muted small"><?= $category ? e($category['name']) . ' — ' . count($images) . ' image(s)' : 'Select a category' ?></p></div>
    <a href="/admin/gallery" class="btn btn-outline">&larr; Back</a>
  </div>

  <?php if (!$category): ?>
    <div class="empty-state">
      <p>No category selected. <a href="/admin/gallery">Pick a gallery category</a> to view its images.</p>
    </div>
  <?php else: ?>

    <div class="form-card" style="margin-bottom:20px"><div class="form-card__body form-card__body--narrow">
      <h3 class="section-heading--sm">Add image to <?= e($category['name']) ?></h3>
      <form method="POST" action="/admin/api/crud.php" data-ajax>
        <?= csrf_field() ?>
        <input type="hidden" name="entity" value="gallery_image">
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="category_id" value="<?= (int)$category['id'] ?>">
        <div class="form-group">
          <label>Image path * <span class="muted small">e.g. Luxury Images/pool/pool-overview-entertainment-area.jpg or uploads/gallery/...</span></label>
          <input type="text" name="image_path" required placeholder="Luxury Images/bedrooms/bedroom-chevron-pillows-headboard.jpg">
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Alt text</label>
            <input type="text" name="alt_text" placeholder="Accessible description">
          </div>
          <div class="form-group">
            <label>Sort order</label>
            <input type="number" name="sort_order" value="<?= count($images) + 1 ?>">
          </div>
        </div>
        <div class="form-group">
          <label>Caption</label>
          <input type="text" name="caption" placeholder="Optional caption">
        </div>
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Add Image</button>
        </div>
      </form>
    </div></div>

    <?php if (empty($images)): ?>
      <div class="empty-state"><p>No images in this category yet.</p></div>
    <?php else: ?>
      <div class="gallery-grid">
        <?php foreach ($images as $img): ?>
          <div class="card gallery-thumb">
            <?php if ($img['image_path']): ?>
              <img src="<?= e(image_url($img['image_path'])) ?>" alt="<?= e($img['alt_text'] ?? '') ?>" class="gallery-img" onerror="this.style.display='none'">
            <?php endif; ?>
            <div class="gallery-caption">
              <div class="small"><strong><?= e($img['alt_text'] ?: '(no alt text)') ?></strong></div>
              <div class="muted small" style="word-break:break-all"><?= e($img['image_path']) ?></div>
              <?php if ($img['caption']): ?><div class="muted small"><?= e($img['caption']) ?></div><?php endif; ?>
              <div class="muted small">Order <?= (int)$img['sort_order'] ?> · #<?= (int)$img['id'] ?></div>
              <div class="btn-group" style="margin-top:8px">
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="entity" value="gallery_image">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?= $img['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Delete this image?"><?= admin_icon('trash', 13) ?> Delete</button>
                </form>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</div>
