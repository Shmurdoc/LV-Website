<?php
// Gallery Images — Viata Luxe Guesthouse — full CRUD (DB-driven, no hardcoded fallback)
$db = Database::get();
$trash = !empty($_GET['trash']);
$category_id = (int)($_GET['category_id'] ?? 0);
$category = null;
if ($category_id) {
    $params = ['id' => $category_id];
    $whereExtra = active_where($params, 'gi', include_deleted: $trash);
    $stmt = $db->prepare("SELECT * FROM gallery_images gi $whereExtra AND gi.public_category_id = :id ORDER BY gi.deleted_at IS NULL DESC, gi.sort_order ASC, gi.id ASC");
    $stmt->execute($params);
    $images = $stmt->fetchAll();
    // Also load the category (even if trashed)
    $catStmt = $db->prepare('SELECT * FROM public_categories WHERE id = :id');
    $catStmt->execute(['id' => $category_id]);
    $category = $catStmt->fetch() ?: null;
}
?>
<div class="admin-page">
  <div class="page-header page-header--spread">
    <div><h2><?= $trash ? 'Trashed Images' : 'Gallery Images' ?></h2><p class="muted small"><?= $category ? e($category['name']) . ' — ' . count($images) . ' image(s)' : 'Select a category' ?></p></div>
    <div class="btn-group">
      <?php if ($trash): ?>
        <a href="/admin/gallery/images?category_id=<?= $category_id ?>" class="btn btn-outline">Active</a>
      <?php else: ?>
        <a href="/admin/gallery/images?category_id=<?= $category_id ?>&trash=1" class="btn btn-outline"><?= admin_icon('trash', 14) ?> Trash</a>
      <?php endif; ?>
      <a href="/admin/gallery" class="btn btn-outline">&larr; Back</a>
    </div>
  </div>

  <?php if (!$category): ?>
    <div class="empty-state">
      <p>No category selected. <a href="/admin/gallery">Pick a gallery category</a> to view its images.</p>
    </div>
  <?php else: ?>

    <?php if (!$trash): ?>
    <div class="form-card" style="margin-bottom:20px"><div class="form-card__body form-card__body--narrow">
      <h3 class="section-heading--sm">Add image to <?= e($category['name']) ?></h3>
      <form method="POST" action="/admin/api/crud.php" data-ajax>
        <?= csrf_field() ?>
        <input type="hidden" name="entity" value="gallery_image">
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="category_id" value="<?= (int)$category['id'] ?>">
        <div class="form-group">
          <label>Image path * <span class="muted small">e.g. Luxury Images/pool/pool-overview-entertainment-area.jpg or uploads/gallery/...</span></label>
          <div class="flex gap-2">
            <input type="text" name="image_path" required placeholder="Luxury Images/bedrooms/bedroom-chevron-pillows-headboard.jpg" class="grow">
            <button type="button" class="btn btn-sm btn-outline browse-btn" data-target="image_path">Browse</button>
          </div>
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
        <div class="form-group">
          <label class="checkbox-label"><input type="checkbox" name="is_featured" value="1"> Featured — show on homepage preview (8 max)</label>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Add Image</button>
        </div>
      </form>
    </div></div>
    <?php endif; ?>

    <?php if (empty($images)): ?>
      <div class="empty-state"><p><?= $trash ? 'Trash is empty for this category.' : 'No images in this category yet.' ?></p></div>
    <?php else: ?>
      <div class="gallery-grid">
        <?php foreach ($images as $img): ?>
          <div class="card gallery-thumb <?= !empty($img['deleted_at']) ? 'row-trashed' : '' ?>">
            <?php if ($img['image_path']): ?>
              <img src="<?= e(image_url($img['image_path'])) ?>" alt="<?= e($img['alt_text'] ?? '') ?>" class="gallery-img" onerror="this.style.display='none'">
            <?php endif; ?>
            <div class="gallery-caption">
              <div class="small"><strong><?= e($img['alt_text'] ?: '(no alt text)') ?></strong></div>
              <div class="muted small" style="word-break:break-all"><?= e($img['image_path']) ?></div>
              <?php if ($img['caption']): ?><div class="muted small"><?= e($img['caption']) ?></div><?php endif; ?>
              <div class="muted small">Order <?= (int)$img['sort_order'] ?> · #<?= (int)$img['id'] ?></div>
              <?php if (!empty($img['is_featured'])): ?>
                <span class="badge badge-published">Featured</span>
              <?php endif; ?>
              <?php if (!empty($img['deleted_at'])): ?>
                <span class="badge badge-trashed">Trashed</span>
              <?php endif; ?>
              <div class="btn-group" style="margin-top:8px">
                <?php if ($trash): ?>
                  <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="entity" value="gallery_image">
                    <input type="hidden" name="action" value="restore">
                    <input type="hidden" name="id" value="<?= $img['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline" data-confirm="Restore?">Restore</button>
                  </form>
                  <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="entity" value="gallery_image">
                    <input type="hidden" name="action" value="permanent_delete">
                    <input type="hidden" name="id" value="<?= $img['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Permanently delete?">Delete Forever</button>
                  </form>
                <?php else: ?>
                  <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="entity" value="gallery_image">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $img['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Move to trash?"><?= admin_icon('trash', 13) ?> Delete</button>
                  </form>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</div>
