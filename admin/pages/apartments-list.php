<?php
// Apartments List — Viata Luxe Guesthouse
$db = Database::get();
$trash = !empty($_GET['trash']);
$params = [];
$where = active_where($params, 'a', include_deleted: $trash);
$apartments = $db->prepare("
    SELECT a.*, p.title AS page_title
    FROM apartments a
    LEFT JOIN pages p ON p.id = a.page_id
    $where
    ORDER BY a.deleted_at IS NULL DESC, a.sort_order ASC, a.name ASC
");
$apartments->execute($params);
$apartments = $apartments->fetchAll();

function apt_status_badge(array $row): string {
    if (!empty($row['deleted_at'])) return '<span class="badge badge-trashed">Trashed</span>';
    if (!empty($row['visible_from']) && $row['visible_from'] > date('Y-m-d H:i:s')) return '<span class="badge badge-scheduled">Scheduled</span>';
    if (!empty($row['visible_until']) && $row['visible_until'] < date('Y-m-d H:i:s')) return '<span class="badge badge-expired">Expired</span>';
    return $row['is_published'] ? '<span class="badge badge-published">Published</span>' : '<span class="badge badge-draft">Draft</span>';
}
?>
<div class="admin-page">
  <div class="page-header page-header-inline">
    <div><h2><?= $trash ? 'Trashed Apartments' : 'Apartments' ?></h2><p class="muted small"><?= count($apartments) ?> apartment(s)</p></div>
    <div class="btn-group">
      <?php if ($trash): ?>
        <a href="/admin/apartments" class="btn btn-outline"><?= admin_icon('list', 14) ?> Active</a>
      <?php else: ?>
        <a href="/admin/apartments?trash=1" class="btn btn-outline"><?= admin_icon('trash', 14) ?> Trash</a>
        <a href="/admin/apartments/edit" class="btn btn-primary"><?= admin_icon('plus', 14) ?> New Apartment</a>
      <?php endif; ?>
    </div>
  </div>
  <?= admin_list_search('Search apartments…') ?>
  <?= admin_list_bulk_bar('apartment', [
      ['value' => 'delete', 'label' => 'Move to Trash'],
      ['value' => 'unpublish', 'label' => 'Unpublish'],
  ]) ?>
  <?php if (empty($apartments)): ?>
    <div class="empty-state"><div class="empty-icon"><?= admin_icon('apartments', 24) ?></div><p><?= $trash ? 'Trash is empty.' : 'No apartments yet.' ?></p>
      <?php if (!$trash): ?><a href="/admin/apartments/edit" class="btn btn-primary btn-sm">Create your first apartment</a><?php endif; ?>
    </div>
  <?php else: ?>
    <div class="data-table-wrap">
    <table class="data-table">
      <thead><tr><th>Name</th><th>Slug</th><th>Price</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($apartments as $a): ?>
        <tr class="<?= !empty($a['deleted_at']) ? 'row-trashed' : '' ?>">
          <td><strong><?= e($a['name']) ?></strong><?php if ($a['page_title']) echo ' <span class="muted small">&middot; ' . e($a['page_title']) . '</span>'; ?></td>
          <td><code class="slug">/<?= e($a['slug']) ?></code></td>
          <td><strong><?= format_price((float)$a['price_per_night']) ?></strong></td>
          <td><?= apt_status_badge($a) ?></td>
          <td>
            <div class="btn-group">
              <?php if ($trash): ?>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="entity" value="apartment">
                  <input type="hidden" name="action" value="restore">
                  <input type="hidden" name="id" value="<?= $a['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-outline" data-confirm="Restore this apartment?"><?= admin_icon('restore', 13) ?> Restore</button>
                </form>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="entity" value="apartment">
                  <input type="hidden" name="action" value="permanent_delete">
                  <input type="hidden" name="id" value="<?= $a['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Permanently delete?"><?= admin_icon('trash', 13) ?> Delete Forever</button>
                </form>
              <?php else: ?>
                <a href="/admin/apartments/edit?id=<?= $a['id'] ?>" class="btn btn-sm btn-outline"><?= admin_icon('edit', 13) ?> Edit</a>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="entity" value="apartment">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?= $a['id'] ?>">
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
