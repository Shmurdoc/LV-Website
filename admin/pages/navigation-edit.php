<?php
// Navigation Edit — Viata Luxe Guesthouse
$db = Database::get();
$pages = $db->query('SELECT id, title FROM pages ORDER BY sort_order ASC, title ASC')->fetchAll();
$navItems = $db->query('SELECT id, label FROM navigation ORDER BY sort_order ASC, label ASC')->fetchAll();
$item = null;
$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $stmt = $db->prepare('SELECT * FROM navigation WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $item = $stmt->fetch() ?: null;
}
if (!$id) {
    $item = ['is_published' => 1, 'sort_order' => 0];
}
$isNew = $item === null || (int)($_GET['id'] ?? 0) === 0;
$n = $item ?? [];
?>
<div class="admin-page">
  <div class="page-header" style="display:flex;align-items:center;justify-content:space-between">
    <div><h2><?= $isNew ? 'New Navigation Item' : 'Edit Navigation Item' ?></h2><p class="muted small"><?= $isNew ? 'Create a new menu item' : 'Editing "' . e($n['label'] ?? '') . '"' ?></p></div>
    <a href="/admin/navigation" class="btn btn-outline">&larr; Back</a>
  </div>
  <div class="card" style="padding:20px;max-width:760px">
    <form method="POST" action="/admin/api/crud.php" data-ajax>
      <?= csrf_field() ?>
      <input type="hidden" name="entity" value="navigation">
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="id" value="<?= (int)($n['id'] ?? 0) ?>">

      <div class="form-group">
        <label>Label *</label>
        <input type="text" name="label" value="<?= e($n['label'] ?? '') ?>" required>
      </div>
      <div class="form-group">
        <label>URL (custom link)</label>
        <input type="text" name="url" value="<?= e($n['url'] ?? '') ?>" placeholder="/about or https://example.com">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Link to Page</label>
          <select name="page_id">
            <option value="">None</option>
            <?php foreach ($pages as $pg): ?>
              <option value="<?= $pg['id'] ?>" <?= (int)($n['page_id'] ?? 0) === (int)$pg['id'] ? 'selected' : '' ?>><?= e($pg['title']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Parent</label>
          <select name="parent_id">
            <option value="">Top level</option>
            <?php foreach ($navItems as $ni): ?>
              <?php if ((int)$ni['id'] !== $id): ?>
                <option value="<?= $ni['id'] ?>" <?= (int)($n['parent_id'] ?? 0) === (int)$ni['id'] ? 'selected' : '' ?>><?= e($ni['label']) ?></option>
              <?php endif; ?>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Sort Order</label>
          <input type="number" name="sort_order" value="<?= (int)($n['sort_order'] ?? 0) ?>">
        </div>
        <div class="form-group">
          <label>CSS Class</label>
          <input type="text" name="css_class" value="<?= e($n['css_class'] ?? '') ?>">
        </div>
      </div>
      <div class="form-group" style="display:flex;gap:20px">
        <label style="display:flex;align-items:center;gap:6px"><input type="checkbox" name="is_published" value="1" <?= isset($n['is_published']) && $n['is_published'] ? 'checked' : '' ?>> Published</label>
        <label style="display:flex;align-items:center;gap:6px"><input type="checkbox" name="open_in_new_tab" value="1" <?= isset($n['open_in_new_tab']) && $n['open_in_new_tab'] ? 'checked' : '' ?>> Open in new tab</label>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save Navigation</button>
        <a href="/admin/navigation" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div>
</div>