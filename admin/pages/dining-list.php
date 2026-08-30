<?php
// Dining Items List — Viata Luxe Guesthouse (Track B)
$db = Database::get();
$trash = !empty($_GET['trash']);
$params = [];
$where = active_where($params, 'd', include_deleted: $trash);
$rows = $db->prepare("SELECT d.* FROM dining_items d $where ORDER BY d.deleted_at IS NULL DESC, d.sort_order ASC, d.id ASC");
$rows->execute($params);
$rows = $rows->fetchAll();
function dining_badge(array $r): string {
    if (!empty($r['deleted_at'])) return '<span class="badge badge-trashed">Trashed</span>';
    if (!empty($r['visible_from']) && $r['visible_from'] > date('Y-m-d H:i:s')) return '<span class="badge badge-scheduled">Scheduled</span>';
    if (!empty($r['visible_until']) && $r['visible_until'] < date('Y-m-d H:i:s')) return '<span class="badge badge-expired">Expired</span>';
    return $r['is_published'] ? '<span class="badge badge-published">Published</span>' : '<span class="badge badge-draft">Draft</span>';
}
?>
<div class="admin-page">
  <div class="page-header page-header-inline">
    <div><h2><?= $trash ? 'Trashed Dining Items' : 'Dining Items' ?></h2><p class="muted small"><?= count($rows) ?> item(s) — 4 cards</p></div>
    <div class="btn-group">
      <?php if ($trash): ?>
        <a href="/admin/dining" class="btn btn-outline"><?= admin_icon('list', 14) ?> Active</a>
      <?php else: ?>
        <a href="/admin/dining?trash=1" class="btn btn-outline"><?= admin_icon('trash', 14) ?> Trash</a>
        <a href="/admin/dining/edit" class="btn btn-primary"><?= admin_icon('plus', 14) ?> New Item</a>
      <?php endif; ?>
    </div>
  </div>
  <?php if (empty($rows)): ?>
    <div class="empty-state"><p><?= $trash ? 'Trash is empty.' : 'No dining items yet.' ?></p></div>
  <?php else: ?>
    <div class="data-table-wrap">
    <table class="data-table">
      <thead><tr><th>Title</th><th>Time Label</th><th>Text</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($rows as $r): ?>
        <tr class="<?= !empty($r['deleted_at']) ? 'row-trashed' : '' ?>">
          <td><strong><?= e($r['title']) ?></strong></td>
          <td><?= e($r['time_label'] ?: '—') ?></td>
          <td><?= e(mb_strimwidth($r['text'] ?? '', 0, 80, '…')) ?></td>
          <td><?= (int)$r['sort_order'] ?></td>
          <td><?= dining_badge($r) ?></td>
          <td>
            <div class="btn-group">
              <?php if ($trash): ?>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline"><?= csrf_field() ?><input type="hidden" name="entity" value="dining_item"><input type="hidden" name="action" value="restore"><input type="hidden" name="id" value="<?= $r['id'] ?>"><button type="submit" class="btn btn-sm btn-outline" data-confirm="Restore?">Restore</button></form>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline"><?= csrf_field() ?><input type="hidden" name="entity" value="dining_item"><input type="hidden" name="action" value="permanent_delete"><input type="hidden" name="id" value="<?= $r['id'] ?>"><button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Permanently delete?">Delete Forever</button></form>
              <?php else: ?>
                <a href="/admin/dining/edit?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline">Edit</a>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline"><?= csrf_field() ?><input type="hidden" name="entity" value="dining_item"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $r['id'] ?>"><button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Move to trash?">Delete</button></form>
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
