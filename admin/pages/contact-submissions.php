<?php
// Contact Submissions — Viata Luxe Guesthouse
$db = Database::get();
$submissions = $db->query('SELECT * FROM contact_submissions ORDER BY created_at DESC')->fetchAll();
$unread = count(array_filter($submissions, fn($s) => !$s['is_read']));
?>
<div class="admin-page">
  <div class="page-header page-header-inline">
    <div><h2>Contact Submissions</h2><p class="muted small"><?= count($submissions) ?> submission(s) &middot; <?= $unread ?> unread</p></div>
  </div>
  <?php if (empty($submissions)): ?>
    <div class="empty-state"><div class="empty-icon"><?= admin_icon('inbox', 24) ?></div><p>No contact submissions yet.</p></div>
  <?php else: ?>
    <div class="data-table-wrap">
    <table class="data-table">
      <thead><tr><th>Name</th><th>Email</th><th>Message</th><th>Status</th><th>Received</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($submissions as $s): ?>
        <tr class="<?= $s['is_read'] ? '' : 'unread-row' ?>">
          <td><strong><?= e($s['name']) ?></strong></td>
          <td><a href="mailto:<?= e($s['email']) ?>"><?= e($s['email']) ?></a></td>
          <td class="muted small text-wrap"><?= e(mb_strimwidth($s['message'] ?? '', 0, 80, '…')) ?></td>
          <td><span class="badge <?= $s['is_read'] ? 'badge-muted' : 'badge-gold' ?>"><?= $s['is_read'] ? 'Read' : 'Unread' ?></span></td>
          <td class="small muted"><?= e(date('j M Y H:i', strtotime($s['created_at']))) ?></td>
          <td>
            <div class="btn-group">
              <?php if (!$s['is_read']): ?>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="entity" value="contact_submission">
                  <input type="hidden" name="action" value="mark_read">
                  <input type="hidden" name="id" value="<?= $s['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-outline"><?= admin_icon('check', 13) ?> Read</button>
                </form>
              <?php endif; ?>
              <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                <?= csrf_field() ?>
                <input type="hidden" name="entity" value="contact_submission">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Delete this submission?"><?= admin_icon('trash', 13) ?></button>
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