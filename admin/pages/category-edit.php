<?php
// Category Edit — Viata Luxe Guesthouse
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../includes/admin-functions.php';
require_once __DIR__ . '/../includes/taxonomy.php';

$cat = null;
$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $cat = get_public_category($id);
}
$isNew = $cat === null;
$c = $cat ?? [];
?>
<div class="admin-page">
  <div class="page-header page-header-inline">
    <div><h2><?= $isNew ? 'New Category' : 'Edit Category' ?></h2><p class="muted small"><?= $isNew ? 'Create a new public category' : 'Editing ' . e($c['name'] ?? '') ?></p></div>
    <a href="/admin/categories" class="btn btn-outline">&larr; Back</a>
  </div>
  <div class="form-card"><div class="form-card__body form-card__body--narrow">
    <form method="POST" action="/admin/api/crud.php" data-ajax>
      <?= csrf_field() ?>
      <input type="hidden" name="entity" value="public_category">
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="id" value="<?= (int)($c['id'] ?? 0) ?>">

      <div class="form-group">
        <label>Entity Type *</label>
        <select name="entity_type" required <?= !$isNew ? 'disabled' : '' ?>>
          <option value="">Select type —</option>
          <?php foreach (['apartment', 'gallery', 'safari'] as $t): ?>
            <option value="<?= $t ?>" <?= ($c['entity_type'] ?? '') === $t ? 'selected' : '' ?>><?= ucfirst($t) ?></option>
          <?php endforeach; ?>
        </select>
        <?php if (!$isNew): ?>
          <input type="hidden" name="entity_type" value="<?= e($c['entity_type']) ?>">
        <?php endif; ?>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Name *</label>
          <input type="text" name="name" value="<?= e($c['name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
          <label>Slug *</label>
          <input type="text" name="slug" value="<?= e($c['slug'] ?? '') ?>" required pattern="[a-z0-9\-]+" placeholder="auto-generated-from-name">
        </div>
      </div>

      <div class="form-group">
        <label>Description</label>
        <textarea name="description" rows="3"><?= e($c['description'] ?? '') ?></textarea>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Sort Order</label>
          <input type="number" name="sort_order" value="<?= (int)($c['sort_order'] ?? 0) ?>">
        </div>
        <div class="form-group form-row--bottom">
          <label class="checkbox-label"><input type="checkbox" name="is_active" value="1" <?= isset($c['is_active']) && $c['is_active'] ? 'checked' : '' ?>> Active (visible on public site)</label>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save Category</button>
        <a href="/admin/categories" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div></div>
</div>

<script>
document.querySelector('input[name="name"]')?.addEventListener('input', function() {
    const slugField = document.querySelector('input[name="slug"]');
    if (slugField && !slugField.dataset.manual) {
        slugField.value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
    }
});
document.querySelector('input[name="slug"]')?.addEventListener('input', function() {
    this.dataset.manual = '1';
});
</script>
