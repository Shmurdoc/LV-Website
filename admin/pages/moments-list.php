<?php
// Moments List — Viata Luxe Guesthouse
$db = Database::get();
$trash = !empty($_GET['trash']);
$params = [];
$where = active_where($params, 'm', include_deleted: $trash);
$rows = $db->prepare("SELECT m.* FROM moments m $where ORDER BY m.deleted_at IS NULL DESC, m.sort_order ASC, m.id ASC");
$rows->execute($params);
$rows = $rows->fetchAll();
function moment_badge(array $r): string {
    if (!empty($r['deleted_at'])) return '<span class="badge badge-trashed">Trashed</span>';
    if (!empty($r['visible_from']) && $r['visible_from'] > date('Y-m-d H:i:s')) return '<span class="badge badge-scheduled">Scheduled</span>';
    if (!empty($r['visible_until']) && $r['visible_until'] < date('Y-m-d H:i:s')) return '<span class="badge badge-expired">Expired</span>';
    return $r['is_published'] ? '<span class="badge badge-published">Published</span>' : '<span class="badge badge-draft">Draft</span>';
}
?>
<div class="admin-page">
  <div class="page-header page-header-inline">
    <div><h2><?= $trash ? 'Trashed Moments' : 'Moments' ?></h2><p class="muted small"><?= count($rows) ?> moment(s)</p></div>
    <div class="btn-group">
      <?php if ($trash): ?>
        <a href="/admin/moments" class="btn btn-outline"><?= admin_icon('list', 14) ?> Active</a>
      <?php else: ?>
        <a href="/admin/moments?trash=1" class="btn btn-outline"><?= admin_icon('trash', 14) ?> Trash</a>
        <a href="/admin/moments/edit" class="btn btn-primary"><?= admin_icon('plus', 14) ?> New Moment</a>
      <?php endif; ?>
    </div>
  </div>
  <?= admin_list_search('Search moments…') ?>
  <?= admin_list_bulk_bar('moment', [
      ['value' => 'delete', 'label' => 'Move to Trash'],
      ['value' => 'unpublish', 'label' => 'Unpublish'],
  ]) ?>
  <?php if (empty($rows)): ?>
    <div class="empty-state"><p><?= $trash ? 'Trash is empty.' : 'No moments yet.' ?></p></div>
  <?php else: ?>
    <div class="data-table-wrap">
    <table class="data-table">
      <thead><tr><th>Image</th><th>Kicker</th><th>Title</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($rows as $r): ?>
        <tr class="<?= !empty($r['deleted_at']) ? 'row-trashed' : '' ?>">
          <td><img src="<?= e(url($r['image_path'])) ?>" alt="" style="width:80px;height:48px;object-fit:cover;border-radius:6px"></td>
          <td><?= e($r['kicker'] ?: '—') ?></td>
          <td><strong><?= e($r['title']) ?></strong><br><small class="muted"><?= e(mb_strimwidth($r['text'] ?? '', 0, 60, '…')) ?></small></td>
          <td><?= (int)$r['sort_order'] ?></td>
          <td><?= moment_badge($r) ?></td>
          <td>
            <div class="btn-group">
              <?php if ($trash): ?>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline"><?= csrf_field() ?><input type="hidden" name="entity" value="moment"><input type="hidden" name="action" value="restore"><input type="hidden" name="id" value="<?= $r['id'] ?>"><button type="submit" class="btn btn-sm btn-outline" data-confirm="Restore?">Restore</button></form>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline"><?= csrf_field() ?><input type="hidden" name="entity" value="moment"><input type="hidden" name="action" value="permanent_delete"><input type="hidden" name="id" value="<?= $r['id'] ?>"><button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Permanently delete?">Delete Forever</button></form>
              <?php else: ?>
                <a href="/admin/moments/edit?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline">Edit</a>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline"><?= csrf_field() ?><input type="hidden" name="entity" value="moment"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $r['id'] ?>"><button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Move to trash?">Delete</button></form>
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
