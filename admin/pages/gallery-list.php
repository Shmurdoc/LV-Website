<?php
// Gallery Categories List — Viata Luxe Guesthouse
$db = Database::get();
$categories = $db->query('
    SELECT gc.*, COUNT(gi.id) AS image_count
    FROM gallery_categories gc
    LEFT JOIN gallery_images gi ON gi.category_id = gc.id
    GROUP BY gc.id
    ORDER BY gc.sort_order ASC, gc.name ASC
')->fetchAll();
?>
<div class="admin-page">
  <div class="page-header page-header-inline">
    <div><h2>Gallery</h2><p class="muted small"><?= count($categories) ?> categor<?= count($categories) === 1 ? 'y' : 'ies' ?></p></div>
    <a href="/admin/gallery/edit" class="btn btn-primary"><?= admin_icon('plus', 14) ?> New Category</a>
  </div>
  <?php if (empty($categories)): ?>
    <div class="empty-state"><div class="empty-icon"><?= admin_icon('gallery', 24) ?></div><p>No gallery categories yet.</p><a href="/admin/gallery/edit" class="btn btn-primary btn-sm">Create a category</a></div>
  <?php else: ?>
    <div class="data-table-wrap">
    <table class="data-table">
      <thead><tr><th>Name</th><th>Slug</th><th>Images</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($categories as $c): ?>
        <tr>
          <td><strong><?= e($c['name']) ?></strong><?php if ($c['description']) echo ' · <span class="muted small">' . e(mb_strimwidth($c['description'], 0, 32, '…')) . '</span>'; ?></td>
          <td><code class="slug"><?= e($c['slug']) ?></code></td>
          <td><span class="badge badge-info"><?= (int)$c['image_count'] ?> img</span></td>
          <td><span class="badge <?= $c['is_published'] ? 'badge-published' : 'badge-draft' ?>"><?= $c['is_published'] ? 'Published' : 'Draft' ?></span></td>
          <td>
            <div class="btn-group">
              <a href="/admin/gallery/images?category_id=<?= $c['id'] ?>" class="btn btn-sm btn-outline"><?= admin_icon('gallery', 13) ?> Images</a>
              <a href="/admin/gallery/edit?id=<?= $c['id'] ?>" class="btn btn-sm btn-outline"><?= admin_icon('edit', 13) ?> Edit</a>
              <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                <?= csrf_field() ?>
                <input type="hidden" name="entity" value="gallery_category">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Delete this gallery category?"><?= admin_icon('trash', 13) ?></button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    </div>
  <?php endif; ?>
</div>