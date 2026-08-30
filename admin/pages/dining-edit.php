<?php
// Dining Item Edit — Viata Luxe Guesthouse (Track B)
$db = Database::get();
$row = null;
$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $stmt = $db->prepare('SELECT * FROM dining_items WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch() ?: null;
}
if (!$id) $row = ['sort_order' => 0, 'is_published' => 1, 'page_id' => 1];
$isNew = $row === null || !isset($_GET['id']) || (int)($_GET['id'] ?? 0) === 0;
$r = $row ?? [];
?>
<div class="admin-page">
  <div class="page-header page-header-inline">
    <div><h2><?= $isNew ? 'New Dining Item' : 'Edit Dining Item' ?></h2><p class="muted small"><?= $isNew ? 'Add dining card' : 'Editing "' . e($r['title'] ?? '') . '"' ?></p></div>
    <a href="/admin/dining" class="btn btn-outline">&larr; Back</a>
  </div>
  <div class="form-card"><div class="form-card__body form-card__body--narrow">
    <form method="POST" action="/admin/api/crud.php" data-ajax>
      <?= csrf_field() ?>
      <input type="hidden" name="entity" value="dining_item">
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="id" value="<?= (int)($r['id'] ?? 0) ?>">
      <input type="hidden" name="page_id" value="1">
      <div class="form-group">
        <label>Title *</label>
        <input type="text" name="title" value="<?= e($r['title'] ?? '') ?>" required>
      </div>
      <div class="form-group">
        <label>Time Label (e.g. In your apartment)</label>
        <input type="text" name="time_label" value="<?= e($r['time_label'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Text</label>
        <textarea name="text"><?= e($r['text'] ?? '') ?></textarea>
      </div>
      <div class="form-group">
        <label>Icon (optional)</label>
        <input type="text" name="icon" value="<?= e($r['icon'] ?? '') ?>">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Sort Order</label>
          <input type="number" name="sort_order" value="<?= (int)($r['sort_order'] ?? 0) ?>">
        </div>
        <div class="form-group form-row--gap form-row--bottom">
          <label class="checkbox-label"><input type="checkbox" name="is_published" value="1" <?= isset($r['is_published']) && $r['is_published'] ? 'checked' : '' ?>> Published</label>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Visible From</label><input type="datetime-local" name="visible_from" value="<?= e($r['visible_from'] ?? '') ?>"></div>
        <div class="form-group"><label>Visible Until</label><input type="datetime-local" name="visible_until" value="<?= e($r['visible_until'] ?? '') ?>"></div>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save Dining Item</button>
        <a href="/admin/dining" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div></div>
</div>
