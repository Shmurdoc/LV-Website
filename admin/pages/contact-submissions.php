<?php
// Contact Submissions — Viata Luxe Guesthouse
$db = Database::get();
$trash = !empty($_GET['trash']);
$perPage = 20;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;

// Count total
$params = [];
$where = active_where($params, 'cs', include_deleted: $trash);
$countStmt = $db->prepare("SELECT COUNT(*) FROM contact_submissions cs $where");
$countStmt->execute($params);
$total = (int)$countStmt->fetchColumn();

// Fetch page
$params2 = [];
$where2 = active_where($params2, 'cs', include_deleted: $trash);
$submissions = $db->prepare("SELECT * FROM contact_submissions cs $where2 ORDER BY cs.deleted_at IS NULL DESC, cs.created_at DESC LIMIT :limit OFFSET :offset");
$submissions->bindValue(':limit', $perPage, PDO::PARAM_INT);
$submissions->bindValue(':offset', $offset, PDO::PARAM_INT);
foreach ($params2 as $k => $v) $submissions->bindValue($k, $v);
$submissions->execute();
$submissions = $submissions->fetchAll();
$unread = count(array_filter($submissions, fn($s) => !$s['is_read'] && empty($s['deleted_at'])));
?>
<div class="admin-page">
  <div class="page-header page-header-inline">
    <div><h2><?= $trash ? 'Trashed Submissions' : 'Contact Submissions' ?></h2><p class="muted small"><?= $total ?> submission(s) &middot; <?= $unread ?> unread</p></div>
    <?php if ($trash): ?>
      <a href="/admin/contact" class="btn btn-outline"><?= admin_icon('list', 14) ?> Active</a>
    <?php else: ?>
      <a href="/admin/contact?trash=1" class="btn btn-outline"><?= admin_icon('trash', 14) ?> Trash</a>
    <?php endif; ?>
  </div>
  <?= admin_list_search('Search submissions…') ?>
  <?= admin_list_bulk_bar('contact_submission', [
      ['value' => 'delete', 'label' => 'Move to Trash'],
      ['value' => 'unpublish', 'label' => 'Mark as Read'],
  ]) ?>
  <?php if (empty($submissions)): ?>
    <div class="empty-state"><div class="empty-icon"><?= admin_icon('inbox', 24) ?></div><p><?= $trash ? 'Trash is empty.' : 'No contact submissions yet.' ?></p></div>
  <?php else: ?>
    <div class="data-table-wrap">
    <table class="data-table">
      <thead><tr><th>Name</th><th>Email</th><th>Message</th><th>Status</th><th>Received</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($submissions as $s): ?>
        <tr class="<?= $s['is_read'] ? '' : 'unread-row' ?> <?= !empty($s['deleted_at']) ? 'row-trashed' : '' ?>">
          <td><strong><?= e($s['name']) ?></strong></td>
          <td><a href="mailto:<?= e($s['email']) ?>"><?= e($s['email']) ?></a></td>
          <td class="muted small text-wrap"><?= e(mb_strimwidth($s['message'] ?? '', 0, 80, '…')) ?></td>
          <td>
            <?php if (!empty($s['deleted_at'])): ?>
              <span class="badge badge-trashed">Trashed</span>
            <?php else: ?>
              <span class="badge <?= $s['is_read'] ? 'badge-muted' : 'badge-gold' ?>"><?= $s['is_read'] ? 'Read' : 'Unread' ?></span>
            <?php endif; ?>
          </td>
          <td class="small muted"><?= e(date('j M Y H:i', strtotime($s['created_at']))) ?></td>
          <td>
            <div class="btn-group">
              <?php if ($trash): ?>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="entity" value="contact_submission">
                  <input type="hidden" name="action" value="restore">
                  <input type="hidden" name="id" value="<?= $s['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-outline" data-confirm="Restore?">Restore</button>
                </form>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="entity" value="contact_submission">
                  <input type="hidden" name="action" value="permanent_delete">
                  <input type="hidden" name="id" value="<?= $s['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Permanently delete?">Delete Forever</button>
                </form>
              <?php else: ?>
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
    <?= admin_list_pagination($total, $perPage, $page) ?>
  <?php endif; ?>
</div>
