<?php
// Gallery Categories List — Viata Luxe Guesthouse
$db = Database::get();
$trash = !empty($_GET['trash']);
$params = [];
$where = active_where($params, 'gc', include_deleted: $trash);
$categories = $db->prepare("
    SELECT gc.*, COUNT(gi.id) AS image_count
    FROM public_categories gc
    LEFT JOIN gallery_images gi ON gi.public_category_id = gc.id
    $where AND gc.entity_type = 'gallery'
    GROUP BY gc.id
    ORDER BY gc.deleted_at IS NULL DESC, gc.sort_order ASC, gc.name ASC
");
$categories->execute($params);
$categories = $categories->fetchAll();

function gallery_status_badge(array $row): string {
    return !empty($row['is_active']) ? '<span class="badge badge-published">Active</span>' : '<span class="badge badge-draft">Inactive</span>';
}
?>
<div class="admin-page">
  <div class="page-header page-header-inline">
    <div><h2><?= $trash ? 'Trashed Gallery Categories' : 'Gallery' ?></h2><p class="muted small"><?= count($categories) ?> categor<?= count($categories) === 1 ? 'y' : 'ies' ?></p></div>
    <div class="btn-group">
      <?php if ($trash): ?>
        <a href="/admin/gallery" class="btn btn-outline"><?= admin_icon('list', 14) ?> Active</a>
      <?php else: ?>
        <a href="/admin/gallery?trash=1" class="btn btn-outline"><?= admin_icon('trash', 14) ?> Trash</a>
        <a href="/admin/gallery/edit" class="btn btn-primary"><?= admin_icon('plus', 14) ?> New Category</a>
      <?php endif; ?>
    </div>
  </div>
  <?= admin_list_search('Search categories…') ?>
  <?= admin_list_bulk_bar('gallery_category', [
      ['value' => 'delete', 'label' => 'Move to Trash'],
  ]) ?>
  <?php if (empty($categories)): ?>
    <div class="empty-state"><div class="empty-icon"><?= admin_icon('gallery', 24) ?></div><p><?= $trash ? 'Trash is empty.' : 'No gallery categories yet.' ?></p>
      <?php if (!$trash): ?><a href="/admin/gallery/edit" class="btn btn-primary btn-sm">Create a category</a><?php endif; ?>
    </div>
  <?php else: ?>
    <div class="data-table-wrap">
    <table class="data-table">
      <thead><tr><th>Name</th><th>Slug</th><th>Images</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($categories as $c): ?>
        <tr class="<?= !empty($c['deleted_at']) ? 'row-trashed' : '' ?>">
          <td><strong><?= e($c['name']) ?></strong><?php if ($c['description']) echo ' · <span class="muted small">' . e(mb_strimwidth($c['description'], 0, 32, '…')) . '</span>'; ?></td>
          <td><code class="slug"><?= e($c['slug']) ?></code></td>
          <td><span class="badge badge-info"><?= (int)$c['image_count'] ?> img</span></td>
          <td><?= gallery_status_badge($c) ?></td>
          <td>
            <div class="btn-group">
              <?php if ($trash): ?>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="entity" value="gallery_category">
                  <input type="hidden" name="action" value="restore">
                  <input type="hidden" name="id" value="<?= $c['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-outline" data-confirm="Restore?"><?= admin_icon('restore', 13) ?> Restore</button>
                </form>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="entity" value="gallery_category">
                  <input type="hidden" name="action" value="permanent_delete">
                  <input type="hidden" name="id" value="<?= $c['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Permanently delete?"><?= admin_icon('trash', 13) ?> Delete Forever</button>
                </form>
              <?php else: ?>
                <a href="/admin/gallery/images?category_id=<?= $c['id'] ?>" class="btn btn-sm btn-outline"><?= admin_icon('gallery', 13) ?> Images</a>
                <a href="/admin/gallery/edit?id=<?= $c['id'] ?>" class="btn btn-sm btn-outline"><?= admin_icon('edit', 13) ?> Edit</a>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="entity" value="gallery_category">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?= $c['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Move to trash?"><?= admin_icon('trash', 13) ?></button>
                </form>
              <?php endif; ?>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    </div>
  <?php endif; ?>
</div>
