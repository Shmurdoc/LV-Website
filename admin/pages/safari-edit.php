<?php
// Safari Edit — Viata Luxe Guesthouse
$db = Database::get();
$activity = null;
$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $stmt = $db->prepare('SELECT * FROM safari_activities WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $activity = $stmt->fetch() ?: null;
}
if (!$id) {
    $activity = ['is_published' => 1, 'sort_order' => 0];
}
$isNew = $activity === null || (int)($_GET['id'] ?? 0) === 0;
$s = $activity ?? [];
?>
<div class="admin-page">
  <div class="page-header page-header-inline">
    <div><h2><?= $isNew ? 'New Safari Activity' : 'Edit Safari Activity' ?></h2><p class="muted small"><?= $isNew ? 'Create a new activity' : 'Editing "' . e($s['title'] ?? '') . '"' ?></p></div>
    <a href="/admin/safari" class="btn btn-outline">&larr; Back</a>
  </div>
  <div class="form-card"><div class="form-card__body form-card__body--narrow">
    <form method="POST" action="/admin/api/crud.php" data-ajax>
      <?= csrf_field() ?>
      <input type="hidden" name="entity" value="safari">
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="id" value="<?= (int)($s['id'] ?? 0) ?>">

      <div class="form-group">
        <label>Title *</label>
        <input type="text" name="title" value="<?= e($s['title'] ?? '') ?>" required>
      </div>
      <div class="form-group">
        <label>Content</label>
        <textarea name="content"><?= e($s['content'] ?? '') ?></textarea>
      </div>
      <div class="form-group">
        <label>Image</label>
        <input type="text" name="image" value="<?= e($s['image'] ?? '') ?>">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Link URL</label>
          <input type="text" name="link_url" value="<?= e($s['link_url'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label>Link Text</label>
          <input type="text" name="link_text" value="<?= e($s['link_text'] ?? '') ?>">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Sort Order</label>
          <input type="number" name="sort_order" value="<?= (int)($s['sort_order'] ?? 0) ?>">
        </div>
        <div class="form-group form-row--bottom">
          <label class="checkbox-label"><input type="checkbox" name="is_published" value="1" <?= isset($s['is_published']) && $s['is_published'] ? 'checked' : '' ?>> Published</label>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save Activity</button>
        <a href="/admin/safari" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div></div>
</div>