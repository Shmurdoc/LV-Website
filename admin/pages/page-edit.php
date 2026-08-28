<?php
// Page Edit — Viata Luxe Guesthouse
$db = Database::get();
$page = null;
$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $stmt = $db->prepare('SELECT * FROM pages WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $page = $stmt->fetch() ?: null;
}
$isNew = $page === null;
$p = $page ?? [];
?>
<div class="admin-page">
  <div class="page-header page-header-inline">
    <div><h2><?= $isNew ? 'New Page' : 'Edit Page' ?></h2><p class="muted small"><?= $isNew ? 'Create a new page' : 'Editing "' . e($p['title'] ?? '') . '"' ?></p></div>
    <a href="/admin/pages" class="btn btn-outline">&larr; Back</a>
  </div>
  <div class="form-card"><div class="form-card__body form-card__body--narrow">
    <form method="POST" action="/admin/api/crud.php" data-ajax>
      <?= csrf_field() ?>
      <input type="hidden" name="entity" value="page">
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="id" value="<?= (int)($p['id'] ?? 0) ?>">

      <div class="form-group">
        <label>Title *</label>
        <input type="text" name="title" value="<?= e($p['title'] ?? '') ?>" required>
      </div>
      <div class="form-group">
        <label>Slug *</label>
        <input type="text" name="slug" value="<?= e($p['slug'] ?? '') ?>" required>
      </div>
      <div class="form-group">
        <label>Subtitle</label>
        <input type="text" name="subtitle" value="<?= e($p['subtitle'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Template</label>
        <select name="template">
          <?php foreach (['default','homepage','about','apartments','contact','gallery','safari','faq'] as $t): ?>
            <option value="<?= e($t) ?>" <?= ($p['template'] ?? 'default') === $t ? 'selected' : '' ?>><?= e(ucfirst($t)) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label>Meta Title</label>
        <input type="text" name="meta_title" value="<?= e($p['meta_title'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Meta Description</label>
        <textarea name="meta_description"><?= e($p['meta_description'] ?? '') ?></textarea>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Sort Order</label>
          <input type="number" name="sort_order" value="<?= (int)($p['sort_order'] ?? 0) ?>">
        </div>
        <div class="form-group form-row--gap form-row--bottom">
          <label class="checkbox-label"><input type="checkbox" name="is_published" value="1" <?= isset($p['is_published']) && $p['is_published'] ? 'checked' : '' ?>> Published</label>
          <label class="checkbox-label"><input type="checkbox" name="is_homepage" value="1" <?= isset($p['is_homepage']) && $p['is_homepage'] ? 'checked' : '' ?>> Homepage</label>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save Page</button>
        <a href="/admin/pages" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div></div>
</div>