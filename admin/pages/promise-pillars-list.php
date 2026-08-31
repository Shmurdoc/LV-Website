<?php
// Promise Pillars List — Viata Luxe Guesthouse
$db = Database::get();
$trash = !empty($_GET['trash']);
$params = [];
$where = active_where($params, 'p', include_deleted: $trash);
$rows = $db->prepare("SELECT p.* FROM promise_pillars p $where ORDER BY p.deleted_at IS NULL DESC, p.sort_order ASC, p.id ASC");
$rows->execute($params);
$rows = $rows->fetchAll();
function pillar_badge(array $r): string {
    if (!empty($r['deleted_at'])) return '<span class="badge badge-trashed">Trashed</span>';
    if (!empty($r['visible_from']) && $r['visible_from'] > date('Y-m-d H:i:s')) return '<span class="badge badge-scheduled">Scheduled</span>';
    if (!empty($r['visible_until']) && $r['visible_until'] < date('Y-m-d H:i:s')) return '<span class="badge badge-expired">Expired</span>';
    return $r['is_published'] ? '<span class="badge badge-published">Published</span>' : '<span class="badge badge-draft">Draft</span>';
}
?>
<div class="admin-page">
  <div class="page-header page-header-inline">
    <div><h2><?= $trash ? 'Trashed Promise Pillars' : 'Promise Pillars' ?></h2><p class="muted small"><?= count($rows) ?> pillar(s)</p></div>
    <div class="btn-group">
      <?php if ($trash): ?>
        <a href="/admin/promise-pillars" class="btn btn-outline"><?= admin_icon('list', 14) ?> Active</a>
      <?php else: ?>
        <a href="/admin/promise-pillars?trash=1" class="btn btn-outline"><?= admin_icon('trash', 14) ?> Trash</a>
        <a href="/admin/promise-pillars/edit" class="btn btn-primary"><?= admin_icon('plus', 14) ?> New Pillar</a>
      <?php endif; ?>
    </div>
  </div>
  <?= admin_list_search('Search pillars…') ?>
  <?= admin_list_bulk_bar('promise_pillar', [
      ['value' => 'delete', 'label' => 'Move to Trash'],
      ['value' => 'unpublish', 'label' => 'Unpublish'],
  ]) ?>
  <?php if (empty($rows)): ?>
    <div class="empty-state"><p><?= $trash ? 'Trash is empty.' : 'No pillars yet.' ?></p></div>
  <?php else: ?>
    <div class="data-table-wrap">
    <table class="data-table">
      <thead><tr><th>Icon</th><th>Title</th><th>Text</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($rows as $r): ?>
        <tr class="<?= !empty($r['deleted_at']) ? 'row-trashed' : '' ?>">
          <td><span style="font-size:18px"><?= e($r['icon'] ?: '—') ?></span></td>
          <td><strong><?= e($r['title']) ?></strong></td>
          <td><?= e(mb_strimwidth($r['text'] ?? '', 0, 80, '…')) ?></td>
          <td><?= (int)$r['sort_order'] ?></td>
          <td><?= pillar_badge($r) ?></td>
          <td>
            <div class="btn-group">
              <?php if ($trash): ?>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline"><?= csrf_field() ?><input type="hidden" name="entity" value="promise_pillar"><input type="hidden" name="action" value="restore"><input type="hidden" name="id" value="<?= $r['id'] ?>"><button type="submit" class="btn btn-sm btn-outline" data-confirm="Restore?">Restore</button></form>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline"><?= csrf_field() ?><input type="hidden" name="entity" value="promise_pillar"><input type="hidden" name="action" value="permanent_delete"><input type="hidden" name="id" value="<?= $r['id'] ?>"><button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Permanently delete?">Delete Forever</button></form>
              <?php else: ?>
                <a href="/admin/promise-pillars/edit?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline">Edit</a>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline"><?= csrf_field() ?><input type="hidden" name="entity" value="promise_pillar"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $r['id'] ?>"><button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Move to trash?">Delete</button></form>
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
