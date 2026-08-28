<?php
// FAQ Edit — Viata Luxe Guesthouse
$db = Database::get();
$pages = $db->query('SELECT id, title FROM pages ORDER BY sort_order ASC, title ASC')->fetchAll();
$faq = null;
$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $stmt = $db->prepare('SELECT * FROM faqs WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $faq = $stmt->fetch() ?: null;
}
if (!$id) {
    $faq = ['is_published' => 1];
}
$isNew = $faq === null || (int)($_GET['id'] ?? 0) === 0;
$f = $faq ?? [];
?>
<div class="admin-page">
  <div class="page-header page-header-inline">
    <div><h2><?= $isNew ? 'New FAQ' : 'Edit FAQ' ?></h2><p class="muted small"><?= $isNew ? 'Create a new question' : 'Editing "' . e($f['question'] ?? '') . '"' ?></p></div>
    <a href="/admin/faqs" class="btn btn-outline">&larr; Back</a>
  </div>
  <div class="form-card"><div class="form-card__body form-card__body--narrow">
    <form method="POST" action="/admin/api/crud.php" data-ajax>
      <?= csrf_field() ?>
      <input type="hidden" name="entity" value="faq">
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="id" value="<?= (int)($f['id'] ?? 0) ?>">

      <div class="form-group">
        <label>Question *</label>
        <input type="text" name="question" value="<?= e($f['question'] ?? '') ?>" required>
      </div>
      <div class="form-group">
        <label>Answer *</label>
        <textarea name="answer" required><?= e($f['answer'] ?? '') ?></textarea>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Page</label>
          <select name="page_id">
            <option value="">No page</option>
            <?php foreach ($pages as $pg): ?>
              <option value="<?= $pg['id'] ?>" <?= (int)($f['page_id'] ?? 0) === (int)$pg['id'] ? 'selected' : '' ?>><?= e($pg['title']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Sort Order</label>
          <input type="number" name="sort_order" value="<?= (int)($f['sort_order'] ?? 0) ?>">
        </div>
      </div>
      <div class="form-group">
        <label class="checkbox-label"><input type="checkbox" name="is_published" value="1" <?= isset($f['is_published']) && $f['is_published'] ? 'checked' : '' ?>> Published</label>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save FAQ</button>
        <a href="/admin/faqs" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div></div>
</div>