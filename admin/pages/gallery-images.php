<?php
// Gallery Images — Viata Luxe Guesthouse
// Read-only listing for a gallery category. Image additions happen via uploads elsewhere.
$db = Database::get();
$category_id = (int)($_GET['category_id'] ?? 0);
$category = null;
if ($category_id) {
    $stmt = $db->prepare('SELECT * FROM gallery_categories WHERE id = :id');
    $stmt->execute(['id' => $category_id]);
    $category = $stmt->fetch() ?: null;
}
$images = [];
if ($category) {
    $stmt = $db->prepare('SELECT * FROM gallery_images WHERE category_id = :id ORDER BY sort_order ASC, id ASC');
    $stmt->execute(['id' => $category_id]);
    $images = $stmt->fetchAll();
}
?>
<div class="admin-page">
  <div class="page-header page-header--spread">
    <div><h2>Gallery Images</h2><p class="muted small"><?= $category ? e($category['name']) . ' — ' . count($images) . ' image(s)' : 'Select a category' ?></p></div>
    <a href="/admin/gallery" class="btn btn-outline">&larr; Back</a>
  </div>

  <?php if (!$category): ?>
    <div class="empty-state">
      <p>No category selected. <a href="/admin/gallery">Pick a gallery category</a> to view its images.</p>
    </div>
  <?php elseif (empty($images)): ?>
    <div class="empty-state"><p>No images in this category yet.</p></div>
  <?php else: ?>
    <div class="gallery-grid">
      <?php foreach ($images as $img): ?>
        <div class="card gallery-thumb">
          <?php if ($img['image_path']): ?>
            <img src="<?= e($img['image_path']) ?>" alt="<?= e($img['alt_text'] ?? '') ?>" class="gallery-img" onerror="this.style.display='none'">
          <?php endif; ?>
          <div class="gallery-caption">
            <div class="small"><strong><?= e($img['alt_text'] ?: '(no alt text)') ?></strong></div>
            <?php if ($img['caption']): ?><div class="muted small"><?= e($img['caption']) ?></div><?php endif; ?>
            <div class="muted small">Order <?= (int)$img['sort_order'] ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <p class="muted small mt-2">Images are added via the gallery upload process elsewhere. This view is read-only.</p>
  <?php endif; ?>
</div>