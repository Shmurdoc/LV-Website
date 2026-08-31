<?php
// Sections List — Viata Luxe Guesthouse
$db = Database::get();
$trash = !empty($_GET['trash']);
$perPage = 20;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;

$params = [];
$where = active_where($params, 's', include_deleted: $trash);
$countStmt = $db->prepare("SELECT COUNT(*) FROM sections s $where");
$countStmt->execute($params);
$total = (int)$countStmt->fetchColumn();

$params2 = [];
$where2 = active_where($params2, 's', include_deleted: $trash);
$sections = $db->prepare("
    SELECT s.*, p.title AS page_title
    FROM sections s
    LEFT JOIN pages p ON p.id = s.page_id
    $where2
    ORDER BY s.deleted_at IS NULL DESC, s.page_id ASC, s.sort_order ASC
    LIMIT :limit OFFSET :offset
");
$sections->bindValue(':limit', $perPage, PDO::PARAM_INT);
$sections->bindValue(':offset', $offset, PDO::PARAM_INT);
foreach ($params2 as $k => $v) $sections->bindValue($k, $v);
$sections->execute();
$sections = $sections->fetchAll();

function section_status_badge(array $row): string {
    if (!empty($row['deleted_at'])) return '<span class="badge badge-trashed">Trashed</span>';
    if (!empty($row['visible_from']) && $row['visible_from'] > date('Y-m-d H:i:s')) return '<span class="badge badge-scheduled">Scheduled</span>';
    if (!empty($row['visible_until']) && $row['visible_until'] < date('Y-m-d H:i:s')) return '<span class="badge badge-expired">Expired</span>';
    return $row['is_visible'] ? '<span class="badge badge-published">Visible</span>' : '<span class="badge badge-draft">Hidden</span>';
}
?>
<div class="admin-page">
  <div class="page-header page-header-inline">
    <div><h2><?= $trash ? 'Trashed Sections' : 'Sections' ?></h2><p class="muted small"><?= $total ?> section(s) &middot; building blocks of each page</p></div>
    <div class="btn-group">
      <?php if ($trash): ?>
        <a href="/admin/sections" class="btn btn-outline"><?= admin_icon('list', 14) ?> Active</a>
      <?php else: ?>
        <a href="/admin/sections?trash=1" class="btn btn-outline"><?= admin_icon('trash', 14) ?> Trash</a>
        <a href="/admin/sections/edit" class="btn btn-primary"><?= admin_icon('plus', 14) ?> New Section</a>
      <?php endif; ?>
    </div>
  </div>
  <?= admin_list_search('Search sections…') ?>
  <?= admin_list_bulk_bar('section', [
      ['value' => 'delete', 'label' => 'Move to Trash'],
      ['value' => 'unpublish', 'label' => 'Hide'],
  ]) ?>
  <?php if (empty($sections)): ?>
    <div class="empty-state"><div class="empty-icon"><?= admin_icon('sections', 24) ?></div><p><?= $trash ? 'Trash is empty.' : 'No sections yet.' ?></p>
      <?php if (!$trash): ?><a href="/admin/sections/edit" class="btn btn-primary btn-sm">Add a section</a><?php endif; ?>
    </div>
  <?php else: ?>
    <div class="data-table-wrap">
    <table class="data-table">
      <thead><tr><th>Type</th><th>Title</th><th>Page</th><th>Status</th><th>Order</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($sections as $s): ?>
        <tr class="<?= !empty($s['deleted_at']) ? 'row-trashed' : '' ?>">
          <td><code class="slug"><?= e($s['section_type']) ?></code></td>
          <td><strong><?= e($s['title'] ?: '(untitled)') ?></strong></td>
          <td class="muted"><?= e($s['page_title'] ?? '—') ?></td>
          <td><?= section_status_badge($s) ?></td>
          <td><?= (int)$s['sort_order'] ?></td>
          <td>
            <div class="btn-group">
              <?php if ($trash): ?>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="entity" value="section">
                  <input type="hidden" name="action" value="restore">
                  <input type="hidden" name="id" value="<?= $s['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-outline" data-confirm="Restore this section?"><?= admin_icon('restore', 13) ?> Restore</button>
                </form>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="entity" value="section">
                  <input type="hidden" name="action" value="permanent_delete">
                  <input type="hidden" name="id" value="<?= $s['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Permanently delete?"><?= admin_icon('trash', 13) ?> Delete Forever</button>
                </form>
              <?php else: ?>
                <a href="/admin/sections/edit?id=<?= $s['id'] ?>" class="btn btn-sm btn-outline"><?= admin_icon('edit', 13) ?> Edit</a>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="entity" value="section">
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
