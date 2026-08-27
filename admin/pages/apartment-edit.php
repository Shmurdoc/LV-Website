<?php
// Apartment Edit — Viata Luxe Guesthouse
$db = Database::get();
$pages = $db->query('SELECT id, title FROM pages ORDER BY sort_order ASC, title ASC')->fetchAll();
$apartment = null;
$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $stmt = $db->prepare('SELECT * FROM apartments WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $apartment = $stmt->fetch() ?: null;
}
$isNew = $apartment === null;
$a = $apartment ?? [];
?>
<div class="admin-page">
  <div class="page-header page-header-inline">
    <div><h2><?= $isNew ? 'New Apartment' : 'Edit Apartment' ?></h2><p class="muted small"><?= $isNew ? 'Create a new apartment' : 'Editing ' . e($a['name'] ?? '') ?></p></div>
    <a href="/admin/apartments" class="btn btn-outline">&larr; Back</a>
  </div>
  <div class="form-card"><div class="form-card__body" style="max-width:760px">
    <form method="POST" action="/admin/api/crud.php" data-ajax>
      <?= csrf_field() ?>
      <input type="hidden" name="entity" value="apartment">
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="id" value="<?= (int)($a['id'] ?? 0) ?>">

      <div class="form-row">
        <div class="form-group">
          <label>Name *</label>
          <input type="text" name="name" value="<?= e($a['name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
          <label>Slug *</label>
          <input type="text" name="slug" value="<?= e($a['slug'] ?? '') ?>" required>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Page *</label>
          <select name="page_id" required>
            <option value="">Select page —</option>
            <?php foreach ($pages as $pg): ?>
              <option value="<?= $pg['id'] ?>" <?= (int)($a['page_id'] ?? 0) === (int)$pg['id'] ? 'selected' : '' ?>><?= e($pg['title']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Price Per Night *</label>
          <input type="number" step="0.01" name="price_per_night" value="<?= e($a['price_per_night'] ?? '') ?>" required>
        </div>
      </div>
      <div class="form-group">
        <label>Subtitle</label>
        <input type="text" name="subtitle" value="<?= e($a['subtitle'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Description</label>
        <textarea name="description"><?= e($a['description'] ?? '') ?></textarea>
      </div>
      <div class="form-group">
        <label>Hero Image</label>
        <input type="text" name="hero_image" value="<?= e($a['hero_image'] ?? '') ?>">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Max Guests</label>
          <input type="number" name="max_guests" value="<?= (int)($a['max_guests'] ?? 2) ?>">
        </div>
        <div class="form-group">
          <label>Room Size (m²)</label>
          <input type="number" step="0.1" name="room_size_m2" value="<?= e($a['room_size_m2'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label>Bedrooms</label>
          <input type="number" name="bedrooms" value="<?= (int)($a['bedrooms'] ?? 1) ?>">
        </div>
      </div>
      <div class="form-group">
        <label>Beds Description</label>
        <input type="text" name="beds_description" value="<?= e($a['beds_description'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Meta Title</label>
        <input type="text" name="meta_title" value="<?= e($a['meta_title'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Meta Description</label>
        <textarea name="meta_description"><?= e($a['meta_description'] ?? '') ?></textarea>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Sort Order</label>
          <input type="number" name="sort_order" value="<?= (int)($a['sort_order'] ?? 0) ?>">
        </div>
        <div class="form-group" style="display:flex;align-items:flex-end;padding-bottom:6px">
          <label style="display:flex;align-items:center;gap:6px"><input type="checkbox" name="is_published" value="1" <?= isset($a['is_published']) && $a['is_published'] ? 'checked' : '' ?>> Published</label>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save Apartment</button>
        <a href="/admin/apartments" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div></div>
</div>