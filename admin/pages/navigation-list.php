<?php
// Navigation List — Viata Luxe Guesthouse
$db = Database::get();
$trash = !empty($_GET['trash']);
$params = [];
$where = active_where($params, 'n', include_deleted: $trash);
$items = $db->prepare("
    SELECT n.*, p.slug AS page_slug, p.title AS page_title, parent.label AS parent_label
    FROM navigation n
    LEFT JOIN pages p ON p.id = n.page_id
    LEFT JOIN navigation parent ON parent.id = n.parent_id
    $where
    ORDER BY n.deleted_at IS NULL DESC, n.sort_order ASC, n.id ASC
");
$items->execute($params);
$items = $items->fetchAll();

function nav_status_badge(array $row): string {
    if (!empty($row['deleted_at'])) return '<span class="badge badge-trashed">Trashed</span>';
    if (!empty($row['visible_from']) && $row['visible_from'] > date('Y-m-d H:i:s')) return '<span class="badge badge-scheduled">Scheduled</span>';
    if (!empty($row['visible_until']) && $row['visible_until'] < date('Y-m-d H:i:s')) return '<span class="badge badge-expired">Expired</span>';
    return $row['is_published'] ? '<span class="badge badge-published">Published</span>' : '<span class="badge badge-draft">Draft</span>';
}
?>
<div class="admin-page">
  <div class="page-header page-header-inline">
    <div><h2><?= $trash ? 'Trashed Navigation' : 'Navigation' ?></h2><p class="muted small"><?= count($items) ?> menu item(s)</p></div>
    <div class="btn-group">
      <?php if ($trash): ?>
        <a href="/admin/navigation" class="btn btn-outline"><?= admin_icon('list', 14) ?> Active</a>
      <?php else: ?>
        <a href="/admin/navigation?trash=1" class="btn btn-outline"><?= admin_icon('trash', 14) ?> Trash</a>
        <a href="/admin/navigation/edit" class="btn btn-primary"><?= admin_icon('plus', 14) ?> New Item</a>
      <?php endif; ?>
    </div>
  </div>
  <?php if (empty($items)): ?>
    <div class="empty-state"><div class="empty-icon"><?= admin_icon('navigation', 24) ?></div><p><?= $trash ? 'Trash is empty.' : 'No navigation items yet.' ?></p>
      <?php if (!$trash): ?><a href="/admin/navigation/edit" class="btn btn-primary btn-sm">Add a menu item</a><?php endif; ?>
    </div>
  <?php else: ?>
    <div class="data-table-wrap">
    <table class="data-table">
      <thead><tr><th>Label</th><th>Target</th><th>Parent</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($items as $n): ?>
        <tr class="<?= !empty($n['deleted_at']) ? 'row-trashed' : '' ?>">
          <td><strong><?= e($n['label']) ?></strong><?php if ($n['open_in_new_tab']) echo ' <span class="badge badge-muted">new&nbsp;tab</span>'; ?></td>
          <td><?= $n['page_slug'] ? '<code class="slug">/' . e($n['page_slug']) . '</code>' : e($n['url'] ?: '—') ?></td>
          <td class="muted"><?= e($n['parent_label'] ?? '—') ?></td>
          <td><?= (int)$n['sort_order'] ?></td>
          <td><?= nav_status_badge($n) ?></td>
          <td>
            <div class="btn-group">
              <?php if ($trash): ?>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="entity" value="navigation">
                  <input type="hidden" name="action" value="restore">
                  <input type="hidden" name="id" value="<?= $n['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-outline" data-confirm="Restore?"><?= admin_icon('restore', 13) ?> Restore</button>
                </form>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="entity" value="navigation">
                  <input type="hidden" name="action" value="permanent_delete">
                  <input type="hidden" name="id" value="<?= $n['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Permanently delete?"><?= admin_icon('trash', 13) ?> Delete Forever</button>
                </form>
              <?php else: ?>
                <a href="/admin/navigation/edit?id=<?= $n['id'] ?>" class="btn btn-sm btn-outline"><?= admin_icon('edit', 13) ?> Edit</a>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="entity" value="navigation">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?= $n['id'] ?>">
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
