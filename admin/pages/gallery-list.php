<?php
// Gallery List — Viata Luxe Guesthouse
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
  <div class="page-header" style="display:flex;align-items:center;justify-content:space-between">
    <div><h2>Gallery</h2><p class="muted small"><?= count($categories) ?> category/categories</p></div>
    <a href="/admin/gallery/edit" class="btn btn-primary">+ New Category</a>
  </div>
  <?php if (empty($categories)): ?>
    <div class="empty-state"><p>No gallery categories yet.</p></div>
  <?php else: ?>
    <table class="data-table">
      <thead><tr><th>Name</th><th>Slug</th><th>Images</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($categories as $c): ?>
        <tr>
          <td><strong><?= e($c['name']) ?></strong></td>
          <td><code style="font-size:0.8rem;background:#f3f4f6;padding:2px 6px;border-radius:4px"><?= e($c['slug']) ?></code></td>
          <td><?= (int)$c['image_count'] ?></td>
          <td><span class="<?= $c['is_published'] ? 'badge-published' : 'badge-draft' ?>"><?= $c['is_published'] ? 'Published' : 'Draft' ?></span></td>
          <td>
            <div class="btn-group">
              <a href="/admin/gallery/images?category_id=<?= $c['id'] ?>" class="btn btn-sm btn-outline">Images</a>
              <a href="/admin/gallery/edit?id=<?= $c['id'] ?>" class="btn btn-sm btn-outline">Edit</a>
              <form method="POST" action="/admin/api/crud.php" data-ajax style="display:inline">
                <?= csrf_field() ?>
                <input type="hidden" name="entity" value="gallery_category">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger" data-confirm="Delete this gallery category?">Delete</button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>