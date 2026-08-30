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
  <div class="form-card"><div class="form-card__body form-card__body--narrow">
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
        <div class="flex gap-2">
          <input type="text" name="hero_image" value="<?= e($a['hero_image'] ?? '') ?>" class="grow">
          <button type="button" class="btn btn-sm btn-outline browse-btn" data-target="hero_image">Browse</button>
        </div>
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
        <label>Tagline</label>
        <input type="text" name="tagline" value="<?= e($a['tagline'] ?? '') ?>" placeholder="e.g. One Bedroom Apartment">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Bathrooms</label>
          <input type="number" name="bathrooms" value="<?= (int)($a['bathrooms'] ?? 1) ?>" min="1" max="10">
        </div>
        <div class="form-group">
          <label>Beds Description</label>
          <input type="text" name="beds_description" value="<?= e($a['beds_description'] ?? '') ?>">
        </div>
      </div>
      <div class="form-group">
        <label>Features (JSON array) <span class="muted small">e.g. ["Sleeps 2","Full kitchen"] — also used in pricing cards</span></label>
        <textarea name="features" rows="2" placeholder='["Sleeps 2","Full kitchen","Jacuzzi access","Secure parking"]'><?= e(is_array($a['features'] ?? null) ? json_encode($a['features']) : ($a['features'] ?? '')) ?></textarea>
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
        <div class="form-group form-row--gap form-row--bottom">
          <label class="checkbox-label"><input type="checkbox" name="is_published" value="1" <?= isset($a['is_published']) && $a['is_published'] ? 'checked' : '' ?>> Published</label>
          <label class="checkbox-label"><input type="checkbox" name="is_featured" value="1" <?= !empty($a['is_featured']) ? 'checked' : '' ?>> Featured (pricing highlight)</label>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save Apartment</button>
        <a href="/admin/apartments" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div></div>

  <?php if (!$isNew): ?>
  <?php $aptImages = $db->prepare('SELECT * FROM apartment_images WHERE apartment_id = :id ORDER BY sort_order'); $aptImages->execute(['id' => $a['id']]); $aptImages = $aptImages->fetchAll(); ?>
  <?php $aptAmenities = $db->prepare('SELECT * FROM apartment_amenities WHERE apartment_id = :id ORDER BY sort_order'); $aptAmenities->execute(['id' => $a['id']]); $aptAmenities = $aptAmenities->fetchAll(); ?>
  <div class="form-card" style="margin-top:20px"><div class="form-card__body">
    <h3 class="section-heading--sm">Images — <?= e($a['name']) ?> (<?= count($aptImages) ?>)</h3>
    <div class="gallery-grid">
      <?php foreach ($aptImages as $img): ?>
        <div class="card gallery-thumb">
          <img src="<?= e(image_url($img['image_path'])) ?>" alt="<?= e($img['alt_text'] ?? '') ?>" class="gallery-img" onerror="this.style.display='none'">
          <div class="gallery-caption">
            <div class="small"><strong><?= e($img['alt_text'] ?: '(no alt)') ?></strong> <?= $img['is_hero'] ? '<span class="badge badge-published">Hero</span>' : '' ?></div>
            <div class="muted small" style="word-break:break-all"><?= e($img['image_path']) ?></div>
            <div class="muted small">Order <?= (int)$img['sort_order'] ?></div>
            <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline" style="margin-top:6px">
              <?= csrf_field() ?>
              <input type="hidden" name="entity" value="apartment_image">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= $img['id'] ?>">
              <button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Delete image?"><?= admin_icon('trash', 12) ?> Delete</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <h4 class="section-heading--sm" style="margin-top:16px">Add image</h4>
    <form method="POST" action="/admin/api/crud.php" data-ajax>
      <?= csrf_field() ?>
      <input type="hidden" name="entity" value="apartment_image">
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="apartment_id" value="<?= (int)$a['id'] ?>">
      <div class="form-row">
        <div class="form-group"><label>Image path *</label>
          <div class="flex gap-2">
            <input type="text" name="image_path" required placeholder="Luxury Images/apartments-classic-1/apt1-kitchen-dining-main.jpg" class="grow">
            <button type="button" class="btn btn-sm btn-outline browse-btn" data-target="image_path">Browse</button>
          </div>
        </div>
        <div class="form-group"><label>Alt text</label><input type="text" name="alt_text"></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Sort order</label><input type="number" name="sort_order" value="<?= count($aptImages)+1 ?>"></div>
        <div class="form-group form-row--bottom"><label class="checkbox-label"><input type="checkbox" name="is_hero" value="1"> Hero image</label></div>
      </div>
      <div class="form-actions"><button type="submit" class="btn btn-sm btn-primary">Add Image</button></div>
    </form>
  </div></div>

  <div class="form-card" style="margin-top:16px"><div class="form-card__body">
    <h3 class="section-heading--sm">Amenities — <?= e($a['name']) ?> (<?= count($aptAmenities) ?>)</h3>
    <?php if ($aptAmenities): ?>
      <table class="data-table"><thead><tr><th>Name</th><th>Icon</th><th>Order</th><th></th></tr></thead><tbody>
        <?php foreach ($aptAmenities as $am): ?><tr>
          <td><?= e($am['amenity_name']) ?></td><td class="muted small"><?= e($am['amenity_icon'] ?? '') ?></td><td><?= (int)$am['sort_order'] ?></td>
          <td><form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline"><?= csrf_field() ?><input type="hidden" name="entity" value="apartment_amenity"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $am['id'] ?>"><button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Delete amenity?"><?= admin_icon('trash', 12) ?></button></form></td>
        </tr><?php endforeach; ?>
      </tbody></table>
    <?php else: ?><p class="muted small">No amenities yet.</p><?php endif; ?>
    <h4 class="section-heading--sm" style="margin-top:16px">Add amenity</h4>
    <form method="POST" action="/admin/api/crud.php" data-ajax>
      <?= csrf_field() ?>
      <input type="hidden" name="entity" value="apartment_amenity">
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="apartment_id" value="<?= (int)$a['id'] ?>">
      <div class="form-row">
        <div class="form-group"><label>Amenity name *</label><input type="text" name="amenity_name" required placeholder="Free WiFi"></div>
        <div class="form-group"><label>Icon</label><input type="text" name="amenity_icon" placeholder="wifi"></div>
        <div class="form-group"><label>Order</label><input type="number" name="sort_order" value="<?= count($aptAmenities)+1 ?>"></div>
      </div>
      <div class="form-actions"><button type="submit" class="btn btn-sm btn-primary">Add Amenity</button></div>
    </form>
  </div></div>
  <?php endif; ?>
</div>