<?php
// Contact Submissions — Viata Luxe Guesthouse
$db = Database::get();
$submissions = $db->query('SELECT * FROM contact_submissions ORDER BY created_at DESC')->fetchAll();
?>
<div class="admin-page">
  <div class="page-header">
    <div><h2>Contact Submissions</h2><p class="muted small"><?= count($submissions) ?> submission(s)</p></div>
  </div>
  <?php if (empty($submissions)): ?>
    <div class="empty-state"><p>No contact submissions yet.</p></div>
  <?php else: ?>
    <table class="data-table">
      <thead><tr><th>Name</th><th>Email</th><th>Message</th><th>Status</th><th>Received</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($submissions as $s): ?>
        <tr <?= $s['is_read'] ? '' : 'style="background:#fffbe6"' ?>>
          <td><strong><?= e($s['name']) ?></strong></td>
          <td><a href="mailto:<?= e($s['email']) ?>"><?= e($s['email']) ?></a></td>
          <td class="muted small"><?= e(mb_strimwidth($s['message'] ?? '', 0, 80, '…')) ?></td>
          <td><span class="<?= $s['is_read'] ? 'badge-published' : 'badge-draft' ?>"><?= $s['is_read'] ? 'Read' : 'Unread' ?></span></td>
          <td class="small muted"><?= e($s['created_at']) ?></td>
          <td>
            <div class="btn-group">
              <?php if (!$s['is_read']): ?>
                <form method="POST" action="/admin/api/crud.php" data-ajax style="display:inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="entity" value="contact_submission">
                  <input type="hidden" name="action" value="mark_read">
                  <input type="hidden" name="id" value="<?= $s['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-outline">Mark read</button>
                </form>
              <?php endif; ?>
              <form method="POST" action="/admin/api/crud.php" data-ajax style="display:inline">
                <?= csrf_field() ?>
                <input type="hidden" name="entity" value="contact_submission">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger" data-confirm="Delete this submission?">Delete</button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>