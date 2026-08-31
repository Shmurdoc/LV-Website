<?php
// Testimonials List — Viata Luxe Guesthouse
$db = Database::get();
$trash = !empty($_GET['trash']);
$perPage = 20;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;

$params = [];
$where = active_where($params, 't', include_deleted: $trash);
$countStmt = $db->prepare("SELECT COUNT(*) FROM testimonials t $where");
$countStmt->execute($params);
$total = (int)$countStmt->fetchColumn();

$params2 = [];
$where2 = active_where($params2, 't', include_deleted: $trash);
$testimonials = $db->prepare("
    SELECT t.*, a.slug AS apartment_slug, a.name AS apartment_name
    FROM testimonials t
    LEFT JOIN apartments a ON a.id = t.apartment_id
    $where2
    ORDER BY t.deleted_at IS NULL DESC, t.sort_order ASC, t.id DESC
    LIMIT :limit OFFSET :offset
");
$testimonials->bindValue(':limit', $perPage, PDO::PARAM_INT);
$testimonials->bindValue(':offset', $offset, PDO::PARAM_INT);
foreach ($params2 as $k => $v) $testimonials->bindValue($k, $v);
$testimonials->execute();
$testimonials = $testimonials->fetchAll();

function testimonial_status_badge(array $row): string {
    if (!empty($row['deleted_at'])) return '<span class="badge badge-trashed">Trashed</span>';
    if (!empty($row['visible_from']) && $row['visible_from'] > date('Y-m-d H:i:s')) return '<span class="badge badge-scheduled">Scheduled</span>';
    if (!empty($row['visible_until']) && $row['visible_until'] < date('Y-m-d H:i:s')) return '<span class="badge badge-expired">Expired</span>';
    return $row['is_published'] ? '<span class="badge badge-published">Published</span>' : '<span class="badge badge-draft">Draft</span>';
}
?>
<div class="admin-page">
  <div class="page-header page-header-inline">
    <div><h2><?= $trash ? 'Trashed Testimonials' : 'Testimonials' ?></h2><p class="muted small"><?= $total ?> review(s)</p></div>
    <div class="btn-group">
      <?php if ($trash): ?>
        <a href="/admin/testimonials" class="btn btn-outline"><?= admin_icon('list', 14) ?> Active</a>
      <?php else: ?>
        <a href="/admin/testimonials?trash=1" class="btn btn-outline"><?= admin_icon('trash', 14) ?> Trash</a>
        <a href="/admin/testimonials/edit" class="btn btn-primary"><?= admin_icon('plus', 14) ?> New Testimonial</a>
      <?php endif; ?>
    </div>
  </div>
  <?= admin_list_search('Search reviews…') ?>
  <?= admin_list_bulk_bar('testimonial', [
      ['value' => 'delete', 'label' => 'Move to Trash'],
      ['value' => 'unpublish', 'label' => 'Unpublish'],
  ]) ?>
  <?php if (empty($testimonials)): ?>
    <div class="empty-state"><p><?= $trash ? 'Trash is empty.' : 'No testimonials yet.' ?></p></div>
  <?php else: ?>
    <div class="data-table-wrap">
    <table class="data-table">
      <thead><tr><th>Reviewer</th><th>Rating</th><th>Apartment</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($testimonials as $t): ?>
        <tr class="<?= !empty($t['deleted_at']) ? 'row-trashed' : '' ?>">
          <td><strong><?= e($t['reviewer_name']) ?></strong><?php if ($t['is_featured']) echo ' <span class="badge badge-published">Featured</span>'; ?></td>
          <td><span class="rating-stars" title="<?= (int)$t['rating'] ?>/5"><?= str_repeat('★', (int)$t['rating']) ?></span></td>
          <td><?= e($t['apartment_name'] ?? '—') ?></td>
          <td><?= testimonial_status_badge($t) ?></td>
          <td>
            <div class="btn-group">
              <?php if ($trash): ?>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="entity" value="testimonial">
                  <input type="hidden" name="action" value="restore">
                  <input type="hidden" name="id" value="<?= $t['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-outline" data-confirm="Restore?">Restore</button>
                </form>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="entity" value="testimonial">
                  <input type="hidden" name="action" value="permanent_delete">
                  <input type="hidden" name="id" value="<?= $t['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Permanently delete?">Delete Forever</button>
                </form>
              <?php else: ?>
                <a href="/admin/testimonials/edit?id=<?= $t['id'] ?>" class="btn btn-sm btn-outline">Edit</a>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="entity" value="testimonial">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?= $t['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Move to trash?">Delete</button>
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
