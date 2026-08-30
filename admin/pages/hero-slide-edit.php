<?php
// Hero Slide Edit — Viata Luxe Guesthouse (Track B)
$db = Database::get();
$slide = null;
$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $stmt = $db->prepare('SELECT * FROM hero_slides WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $slide = $stmt->fetch() ?: null;
}
if (!$id) $slide = ['sort_order' => 0, 'is_published' => 1, 'page_id' => 1];
$isNew = $slide === null || !isset($_GET['id']) || (int)($_GET['id'] ?? 0) === 0;
$s = $slide ?? [];
?>
<div class="admin-page">
  <div class="page-header page-header-inline">
    <div><h2><?= $isNew ? 'New Hero Slide' : 'Edit Hero Slide' ?></h2><p class="muted small"><?= $isNew ? 'Add slide to homepage hero slideshow' : 'Editing slide #' . (int)($s['id'] ?? 0) ?></p></div>
    <a href="/admin/hero-slides" class="btn btn-outline">&larr; Back</a>
  </div>
  <div class="form-card"><div class="form-card__body form-card__body--narrow">
    <form method="POST" action="/admin/api/crud.php" data-ajax>
      <?= csrf_field() ?>
      <input type="hidden" name="entity" value="hero_slide">
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="id" value="<?= (int)($s['id'] ?? 0) ?>">
      <input type="hidden" name="page_id" value="1">
      <div class="form-group">
        <label>Image Path * (e.g. Luxury Images/pool/pool-overview-entertainment-area.jpg)</label>
        <div class="flex gap-2">
          <input type="text" name="image_path" value="<?= e($s['image_path'] ?? '') ?>" required class="grow">
          <button type="button" class="btn btn-sm btn-outline browse-btn" data-target="image_path">Browse</button>
        </div>
      </div>
      <div class="form-group">
        <label>Alt Text</label>
        <input type="text" name="alt_text" value="<?= e($s['alt_text'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Caption (shown on slide)</label>
        <input type="text" name="caption" value="<?= e($s['caption'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Link URL (optional)</label>
        <input type="text" name="link_url" value="<?= e($s['link_url'] ?? '') ?>">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Sort Order</label>
          <input type="number" name="sort_order" value="<?= (int)($s['sort_order'] ?? 0) ?>">
        </div>
        <div class="form-group form-row--gap form-row--bottom">
          <label class="checkbox-label"><input type="checkbox" name="is_published" value="1" <?= isset($s['is_published']) && $s['is_published'] ? 'checked' : '' ?>> Published</label>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Visible From (optional)</label>
          <input type="datetime-local" name="visible_from" value="<?= e($s['visible_from'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label>Visible Until (optional)</label>
          <input type="datetime-local" name="visible_until" value="<?= e($s['visible_until'] ?? '') ?>">
        </div>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save Slide</button>
        <a href="/admin/hero-slides" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div></div>
</div>
