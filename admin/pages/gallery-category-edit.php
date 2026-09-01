<?php
// Gallery Category Edit — Viata Luxe Guesthouse
$db = Database::get();
$category = null;
$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $stmt = $db->prepare('SELECT * FROM public_categories WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $category = $stmt->fetch() ?: null;
}
if (!$id) {
    $category = ['is_active' => 1];
}
$isNew = $category === null || (int)($_GET['id'] ?? 0) === 0;
$c = $category ?? [];
?>
<div class="admin-page">
  <div class="page-header page-header-inline">
    <div><h2><?= $isNew ? 'New Gallery Category' : 'Edit Gallery Category' ?></h2><p class="muted small"><?= $isNew ? 'Create a new category' : 'Editing "' . e($c['name'] ?? '') . '"' ?></p></div>
    <a href="<?= url('/admin/gallery') ?>" class="btn btn-outline">&larr; Back</a>
  </div>
  <div class="form-card"><div class="form-card__body form-card__body--narrow">
    <form method="POST" action="<?= url('/admin/api/crud.php') ?>" data-ajax>
      <?= csrf_field() ?>
      <input type="hidden" name="entity" value="gallery_category">
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="id" value="<?= (int)($c['id'] ?? 0) ?>">

      <div class="form-row">
        <div class="form-group">
          <label>Name *</label>
          <input type="text" name="name" value="<?= e($c['name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
          <label>Slug *</label>
          <input type="text" name="slug" value="<?= e($c['slug'] ?? '') ?>" required>
        </div>
      </div>
      <div class="form-group">
        <label>Description</label>
        <textarea name="description"><?= e($c['description'] ?? '') ?></textarea>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Sort Order</label>
          <input type="number" name="sort_order" value="<?= (int)($c['sort_order'] ?? 0) ?>">
        </div>
        <div class="form-group form-row--bottom">
          <label class="checkbox-label"><input type="checkbox" name="is_active" value="1" <?= isset($c['is_active']) && $c['is_active'] ? 'checked' : (isset($c['is_published']) && $c['is_published'] ? 'checked' : (!isset($c['id']) ? 'checked' : '')) ?>> Active</label>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save Category</button>
        <a href="<?= url('/admin/gallery') ?>" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div></div>
</div>