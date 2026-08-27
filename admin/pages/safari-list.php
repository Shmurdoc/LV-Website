<?php
// Safari List — Viata Luxe Guesthouse
$db = Database::get();
$activities = $db->query('SELECT * FROM safari_activities ORDER BY sort_order ASC, id DESC')->fetchAll();
?>
<div class="admin-page">
  <div class="page-header" style="display:flex;align-items:center;justify-content:space-between">
    <div><h2>Safari Activities</h2><p class="muted small"><?= count($activities) ?> activity/ies</p></div>
    <a href="/admin/safari/edit" class="btn btn-primary">+ New Activity</a>
  </div>
  <?php if (empty($activities)): ?>
    <div class="empty-state"><p>No safari activities yet.</p></div>
  <?php else: ?>
    <table class="data-table">
      <thead><tr><th>Title</th><th>Image</th><th>Status</th><th>Order</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($activities as $s): ?>
        <tr>
          <td><strong><?= e($s['title']) ?></strong></td>
          <td><?= $s['image'] ? 'Yes' : '—' ?></td>
          <td><span class="<?= $s['is_published'] ? 'badge-published' : 'badge-draft' ?>"><?= $s['is_published'] ? 'Published' : 'Draft' ?></span></td>
          <td><?= (int)$s['sort_order'] ?></td>
          <td>
            <div class="btn-group">
              <a href="/admin/safari/edit?id=<?= $s['id'] ?>" class="btn btn-sm btn-outline">Edit</a>
              <form method="POST" action="/admin/api/crud.php" data-ajax style="display:inline">
                <?= csrf_field() ?>
                <input type="hidden" name="entity" value="safari">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger" data-confirm="Delete this safari activity?">Delete</button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>