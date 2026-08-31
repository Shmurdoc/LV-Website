<?php
// Pages List — Viata Luxe Guesthouse
$db = Database::get();
$trash = !empty($_GET['trash']);
$params = [];
$where = active_where($params, 'pages', include_deleted: $trash);
$pages = $db->prepare("SELECT * FROM pages $where ORDER BY deleted_at IS NULL DESC, sort_order ASC, title ASC");
$pages->execute($params);
$pages = $pages->fetchAll();

function page_status_badge(array $row): string {
    if (!empty($row['deleted_at'])) {
        return '<span class="badge badge-trashed">Trashed</span>';
    }
    if (!empty($row['visible_from']) && $row['visible_from'] > date('Y-m-d H:i:s')) {
        return '<span class="badge badge-scheduled">Scheduled</span>';
    }
    if (!empty($row['visible_until']) && $row['visible_until'] < date('Y-m-d H:i:s')) {
        return '<span class="badge badge-expired">Expired</span>';
    }
    return $row['is_published'] ? '<span class="badge badge-published">Published</span>' : '<span class="badge badge-draft">Draft</span>';
}
?>
<div class="admin-page">
  <div class="page-header page-header-inline">
    <div><h2><?= $trash ? 'Trashed Pages' : 'Pages' ?></h2><p class="muted small"><?= count($pages) ?> page(s)</p></div>
    <div class="btn-group">
      <?php if ($trash): ?>
        <a href="/admin/pages" class="btn btn-outline"><?= admin_icon('list', 14) ?> Active</a>
      <?php else: ?>
        <a href="/admin/pages?trash=1" class="btn btn-outline"><?= admin_icon('trash', 14) ?> Trash</a>
        <a href="/admin/pages/edit" class="btn btn-primary"><?= admin_icon('plus', 14) ?> New Page</a>
      <?php endif; ?>
    </div>
  </div>
  <?= admin_list_search('Search pages…') ?>
  <?= admin_list_bulk_bar('page', [
      ['value' => 'delete', 'label' => 'Move to Trash'],
      ['value' => 'unpublish', 'label' => 'Unpublish'],
  ]) ?>
  <?php if (empty($pages)): ?>
    <div class="empty-state"><div class="empty-icon"><?= admin_icon('pages', 24) ?></div><p><?= $trash ? 'Trash is empty.' : 'No pages yet.' ?></p>
      <?php if (!$trash): ?><a href="/admin/pages/edit" class="btn btn-primary btn-sm">Create your first page</a><?php endif; ?>
    </div>
  <?php else: ?>
    <div class="data-table-wrap">
    <table class="data-table">
      <thead><tr><th>Title</th><th>Slug</th><th>Template</th><th>Status</th><th>Order</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($pages as $p): ?>
        <tr class="<?= !empty($p['deleted_at']) ? 'row-trashed' : '' ?>">
          <td><strong><?= e($p['title']) ?></strong><?= $p['is_homepage'] ? ' <span class="badge badge-gold">Home</span>' : '' ?></td>
          <td><code class="slug">/<?= e($p['slug']) ?></code></td>
          <td><code class="slug"><?= e($p['template']) ?></code></td>
          <td><?= page_status_badge($p) ?></td>
          <td><?= (int)$p['sort_order'] ?></td>
          <td>
            <div class="btn-group">
              <?php if ($trash): ?>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="entity" value="page">
                  <input type="hidden" name="action" value="restore">
                  <input type="hidden" name="id" value="<?= $p['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-outline" data-confirm="Restore this page?"><?= admin_icon('restore', 13) ?> Restore</button>
                </form>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="entity" value="page">
                  <input type="hidden" name="action" value="permanent_delete">
                  <input type="hidden" name="id" value="<?= $p['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Permanently delete? This cannot be undone."><?= admin_icon('trash', 13) ?> Delete Forever</button>
                </form>
              <?php else: ?>
                <a href="/admin/pages/edit?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline"><?= admin_icon('edit', 13) ?> Edit</a>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="entity" value="page">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?= $p['id'] ?>">
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
