<?php
// Safari Activities List — Viata Luxe Guesthouse
$db = Database::get();
$activities = $db->query('SELECT * FROM safari_activities ORDER BY sort_order ASC, id DESC')->fetchAll();
?>
<div class="admin-page">
  <div class="page-header page-header-inline">
    <div><h2>Safari Activities</h2><p class="muted small"><?= count($activities) ?> activit<?= count($activities) === 1 ? 'y' : 'ies' ?></p></div>
    <a href="/admin/safari/edit" class="btn btn-primary"><?= admin_icon('plus', 14) ?> New Activity</a>
  </div>
  <?php if (empty($activities)): ?>
    <div class="empty-state"><div class="empty-icon"><?= admin_icon('safari', 24) ?></div><p>No safari activities yet.</p><a href="/admin/safari/edit" class="btn btn-primary btn-sm">Add an activity</a></div>
  <?php else: ?>
    <div class="data-table-wrap">
    <table class="data-table">
      <thead><tr><th>Title</th><th>Image</th><th>Video</th><th>Status</th><th>Order</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($activities as $s): ?>
        <?php $vids = json_decode($s['video_urls'] ?? '[]', true); $hasVid = is_array($vids) && array_filter($vids); ?>
        <tr>
          <td><strong><?= e($s['title']) ?></strong></td>
          <td><?= $s['image'] ? '<span class="badge badge-gold">Has image</span>' : '<span class="muted">—</span>' ?></td>
          <td><?= $hasVid ? '<span class="badge badge-info">' . sprintf('%d video(s)', count(array_filter($vids))) . '</span>' : '<span class="muted">—</span>' ?></td>
          <td><span class="badge <?= $s['is_published'] ? 'badge-published' : 'badge-draft' ?>"><?= $s['is_published'] ? 'Published' : 'Draft' ?></span></td>
          <td><?= (int)$s['sort_order'] ?></td>
          <td>
            <div class="btn-group">
              <a href="/admin/safari/edit?id=<?= $s['id'] ?>" class="btn btn-sm btn-outline"><?= admin_icon('edit', 13) ?> Edit</a>
              <form method="POST" action="/admin/api/crud.php" data-ajax style="display:inline">
                <?= csrf_field() ?>
                <input type="hidden" name="entity" value="safari">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Delete this safari activity?"><?= admin_icon('trash', 13) ?></button>
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