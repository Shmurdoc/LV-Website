<?php
// Safari Activities List — Viata Luxe Guesthouse
$db = Database::get();
$trash = !empty($_GET['trash']);
$params = [];
$where = active_where($params, 's', include_deleted: $trash);
$activities = $db->prepare("SELECT * FROM safari_activities s $where ORDER BY s.deleted_at IS NULL DESC, s.sort_order ASC, s.id DESC");
$activities->execute($params);
$activities = $activities->fetchAll();

function safari_status_badge(array $row): string {
    if (!empty($row['deleted_at'])) return '<span class="badge badge-trashed">Trashed</span>';
    if (!empty($row['visible_from']) && $row['visible_from'] > date('Y-m-d H:i:s')) return '<span class="badge badge-scheduled">Scheduled</span>';
    if (!empty($row['visible_until']) && $row['visible_until'] < date('Y-m-d H:i:s')) return '<span class="badge badge-expired">Expired</span>';
    return $row['is_published'] ? '<span class="badge badge-published">Published</span>' : '<span class="badge badge-draft">Draft</span>';
}
?>
<div class="admin-page">
  <div class="page-header page-header-inline">
    <div><h2><?= $trash ? 'Trashed Safari Activities' : 'Safari Activities' ?></h2><p class="muted small"><?= count($activities) ?> activit<?= count($activities) === 1 ? 'y' : 'ies' ?></p></div>
    <div class="btn-group">
      <?php if ($trash): ?>
        <a href="/admin/safari" class="btn btn-outline"><?= admin_icon('list', 14) ?> Active</a>
      <?php else: ?>
        <a href="/admin/safari?trash=1" class="btn btn-outline"><?= admin_icon('trash', 14) ?> Trash</a>
        <a href="/admin/safari/edit" class="btn btn-primary"><?= admin_icon('plus', 14) ?> New Activity</a>
      <?php endif; ?>
    </div>
  </div>
  <?= admin_list_search('Search safari…') ?>
  <?= admin_list_bulk_bar('safari', [
      ['value' => 'delete', 'label' => 'Move to Trash'],
      ['value' => 'unpublish', 'label' => 'Unpublish'],
  ]) ?>
  <?php if (empty($activities)): ?>
    <div class="empty-state"><div class="empty-icon"><?= admin_icon('safari', 24) ?></div><p><?= $trash ? 'Trash is empty.' : 'No safari activities yet.' ?></p>
      <?php if (!$trash): ?><a href="/admin/safari/edit" class="btn btn-primary btn-sm">Add an activity</a><?php endif; ?>
    </div>
  <?php else: ?>
    <div class="data-table-wrap">
    <table class="data-table">
      <thead><tr><th>Title</th><th>Image</th><th>Video</th><th>Status</th><th>Order</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($activities as $s): ?>
        <?php $vids = json_decode($s['video_urls'] ?? '[]', true); $hasVid = is_array($vids) && array_filter($vids); ?>
        <tr class="<?= !empty($s['deleted_at']) ? 'row-trashed' : '' ?>">
          <td><strong><?= e($s['title']) ?></strong></td>
          <td><?= $s['image'] ? '<span class="badge badge-gold">Has image</span>' : '<span class="muted">—</span>' ?></td>
          <td><?= $hasVid ? '<span class="badge badge-info">' . sprintf('%d video(s)', count(array_filter($vids))) . '</span>' : '<span class="muted">—</span>' ?></td>
          <td><?= safari_status_badge($s) ?></td>
          <td><?= (int)$s['sort_order'] ?></td>
          <td>
            <div class="btn-group">
              <?php if ($trash): ?>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="entity" value="safari">
                  <input type="hidden" name="action" value="restore">
                  <input type="hidden" name="id" value="<?= $s['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-outline" data-confirm="Restore?"><?= admin_icon('restore', 13) ?> Restore</button>
                </form>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="entity" value="safari">
                  <input type="hidden" name="action" value="permanent_delete">
                  <input type="hidden" name="id" value="<?= $s['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Permanently delete?"><?= admin_icon('trash', 13) ?> Delete Forever</button>
                </form>
              <?php else: ?>
                <a href="/admin/safari/edit?id=<?= $s['id'] ?>" class="btn btn-sm btn-outline"><?= admin_icon('edit', 13) ?> Edit</a>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="entity" value="safari">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?= $s['id'] ?>">
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
