<?php
// Testimonial Edit — Viata Luxe Guesthouse
$db = Database::get();
$apartments = $db->query('SELECT id, name, slug FROM apartments ORDER BY sort_order ASC, name ASC')->fetchAll();
$testimonial = null;
$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $stmt = $db->prepare('SELECT * FROM testimonials WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $testimonial = $stmt->fetch() ?: null;
}
if (!$id) {
    $testimonial = ['rating' => 5, 'source' => 'direct', 'is_published' => 1];
}
$isNew = $testimonial === null || !isset($_GET['id']) || (int)($_GET['id'] ?? 0) === 0;
$t = $testimonial ?? [];
?>
<div class="admin-page">
  <div class="page-header" style="display:flex;align-items:center;justify-content:space-between">
    <div><h2><?= $isNew ? 'New Testimonial' : 'Edit Testimonial' ?></h2><p class="muted small"><?= $isNew ? 'Create a new review' : 'Editing "' . e($t['reviewer_name'] ?? '') . '"' ?></p></div>
    <a href="/admin/testimonials" class="btn btn-outline">&larr; Back</a>
  </div>
  <div class="card" style="padding:20px;max-width:760px">
    <form method="POST" action="/admin/api/crud.php" data-ajax>
      <?= csrf_field() ?>
      <input type="hidden" name="entity" value="testimonial">
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="id" value="<?= (int)($t['id'] ?? 0) ?>">

      <div class="form-group">
        <label>Reviewer Name *</label>
        <input type="text" name="reviewer_name" value="<?= e($t['reviewer_name'] ?? '') ?>" required>
      </div>
      <div class="form-group">
        <label>Review Text *</label>
        <textarea name="review_text" required><?= e($t['review_text'] ?? '') ?></textarea>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Apartment</label>
          <select name="apartment_id">
            <option value="">No apartment</option>
            <?php foreach ($apartments as $ap): ?>
              <option value="<?= $ap['id'] ?>" <?= (int)($t['apartment_id'] ?? 0) === (int)$ap['id'] ? 'selected' : '' ?>><?= e($ap['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Rating</label>
          <select name="rating">
            <?php for ($r = 1; $r <= 5; $r++): ?>
              <option value="<?= $r ?>" <?= (int)($t['rating'] ?? 5) === $r ? 'selected' : '' ?>><?= $r ?> stars</option>
            <?php endfor; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Source</label>
          <input type="text" name="source" value="<?= e($t['source'] ?? 'direct') ?>">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Sort Order</label>
          <input type="number" name="sort_order" value="<?= (int)($t['sort_order'] ?? 0) ?>">
        </div>
        <div class="form-group" style="display:flex;align-items:flex-end;gap:20px;padding-bottom:6px">
          <label style="display:flex;align-items:center;gap:6px"><input type="checkbox" name="is_featured" value="1" <?= isset($t['is_featured']) && $t['is_featured'] ? 'checked' : '' ?>> Featured</label>
          <label style="display:flex;align-items:center;gap:6px"><input type="checkbox" name="is_published" value="1" <?= isset($t['is_published']) && $t['is_published'] ? 'checked' : '' ?>> Published</label>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save Testimonial</button>
        <a href="/admin/testimonials" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div>
</div>