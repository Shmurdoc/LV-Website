<?php
// FAQs List — Viata Luxe Guesthouse
$db = Database::get();
$trash = !empty($_GET['trash']);
$params = [];
$where = active_where($params, 'f', include_deleted: $trash);
$faqs = $db->prepare("
    SELECT f.*, p.title AS page_title
    FROM faqs f
    LEFT JOIN pages p ON p.id = f.page_id
    $where
    ORDER BY f.deleted_at IS NULL DESC, f.sort_order ASC, f.id DESC
");
$faqs->execute($params);
$faqs = $faqs->fetchAll();

function faq_status_badge(array $row): string {
    if (!empty($row['deleted_at'])) return '<span class="badge badge-trashed">Trashed</span>';
    if (!empty($row['visible_from']) && $row['visible_from'] > date('Y-m-d H:i:s')) return '<span class="badge badge-scheduled">Scheduled</span>';
    if (!empty($row['visible_until']) && $row['visible_until'] < date('Y-m-d H:i:s')) return '<span class="badge badge-expired">Expired</span>';
    return $row['is_published'] ? '<span class="badge badge-published">Published</span>' : '<span class="badge badge-draft">Draft</span>';
}
?>
<div class="admin-page">
  <div class="page-header page-header-inline">
    <div><h2><?= $trash ? 'Trashed FAQs' : 'FAQs' ?></h2><p class="muted small"><?= count($faqs) ?> question(s)</p></div>
    <div class="btn-group">
      <?php if ($trash): ?>
        <a href="/admin/faqs" class="btn btn-outline"><?= admin_icon('list', 14) ?> Active</a>
      <?php else: ?>
        <a href="/admin/faqs?trash=1" class="btn btn-outline"><?= admin_icon('trash', 14) ?> Trash</a>
        <a href="/admin/faqs/edit" class="btn btn-primary"><?= admin_icon('plus', 14) ?> New FAQ</a>
      <?php endif; ?>
    </div>
  </div>
  <?php if (empty($faqs)): ?>
    <div class="empty-state"><div class="empty-icon"><?= admin_icon('faqs', 24) ?></div><p><?= $trash ? 'Trash is empty.' : 'No FAQs yet.' ?></p>
      <?php if (!$trash): ?><a href="/admin/faqs/edit" class="btn btn-primary btn-sm">Add your first FAQ</a><?php endif; ?>
    </div>
  <?php else: ?>
    <div class="data-table-wrap">
    <table class="data-table">
      <thead><tr><th>Question</th><th>Answer</th><th>Page</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($faqs as $f): ?>
        <tr class="<?= !empty($f['deleted_at']) ? 'row-trashed' : '' ?>">
          <td><strong><?= e($f['question']) ?></strong></td>
          <td class="muted small text-wrap"><?= e(mb_strimwidth($f['answer'] ?? '', 0, 60, '…')) ?></td>
          <td><?= e($f['page_title'] ?? '—') ?></td>
          <td><?= faq_status_badge($f) ?></td>
          <td>
            <div class="btn-group">
              <?php if ($trash): ?>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="entity" value="faq">
                  <input type="hidden" name="action" value="restore">
                  <input type="hidden" name="id" value="<?= $f['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-outline" data-confirm="Restore this FAQ?"><?= admin_icon('restore', 13) ?> Restore</button>
                </form>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="entity" value="faq">
                  <input type="hidden" name="action" value="permanent_delete">
                  <input type="hidden" name="id" value="<?= $f['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Permanently delete?"><?= admin_icon('trash', 13) ?> Delete Forever</button>
                </form>
              <?php else: ?>
                <a href="/admin/faqs/edit?id=<?= $f['id'] ?>" class="btn btn-sm btn-outline"><?= admin_icon('edit', 13) ?> Edit</a>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="entity" value="faq">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?= $f['id'] ?>">
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
