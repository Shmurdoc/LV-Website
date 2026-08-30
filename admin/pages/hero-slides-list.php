<?php
// Hero Slides List — Viata Luxe Guesthouse (Track B)
$db = Database::get();
$trash = !empty($_GET['trash']);
$params = [];
$where = active_where($params, 'h', include_deleted: $trash);
$slides = $db->prepare("SELECT h.* FROM hero_slides h $where ORDER BY h.deleted_at IS NULL DESC, h.sort_order ASC, h.id ASC");
$slides->execute($params);
$slides = $slides->fetchAll();
function hero_slide_badge(array $r): string {
    if (!empty($r['deleted_at'])) return '<span class="badge badge-trashed">Trashed</span>';
    if (!empty($r['visible_from']) && $r['visible_from'] > date('Y-m-d H:i:s')) return '<span class="badge badge-scheduled">Scheduled</span>';
    if (!empty($r['visible_until']) && $r['visible_until'] < date('Y-m-d H:i:s')) return '<span class="badge badge-expired">Expired</span>';
    return $r['is_published'] ? '<span class="badge badge-published">Published</span>' : '<span class="badge badge-draft">Draft</span>';
}
?>
<div class="admin-page">
  <div class="page-header page-header-inline">
    <div><h2><?= $trash ? 'Trashed Hero Slides' : 'Hero Slides' ?></h2><p class="muted small"><?= count($slides) ?> slide(s) — homepage slideshow (5)</p></div>
    <div class="btn-group">
      <?php if ($trash): ?>
        <a href="/admin/hero-slides" class="btn btn-outline"><?= admin_icon('list', 14) ?> Active</a>
      <?php else: ?>
        <a href="/admin/hero-slides?trash=1" class="btn btn-outline"><?= admin_icon('trash', 14) ?> Trash</a>
        <a href="/admin/hero-slides/edit" class="btn btn-primary"><?= admin_icon('plus', 14) ?> New Slide</a>
      <?php endif; ?>
    </div>
  </div>
  <?php if (empty($slides)): ?>
    <div class="empty-state"><p><?= $trash ? 'Trash is empty.' : 'No slides yet.' ?></p></div>
  <?php else: ?>
    <div class="data-table-wrap">
    <table class="data-table">
      <thead><tr><th>Preview</th><th>Caption</th><th>Alt</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($slides as $s): ?>
        <tr class="<?= !empty($s['deleted_at']) ? 'row-trashed' : '' ?>">
          <td><img src="<?= e(url($s['image_path'])) ?>" alt="" style="width:80px;height:48px;object-fit:cover;border-radius:6px"></td>
          <td><strong><?= e($s['caption'] ?: '—') ?></strong><br><small class="muted"><?= e($s['image_path']) ?></small></td>
          <td><?= e($s['alt_text'] ?: '—') ?></td>
          <td><?= (int)$s['sort_order'] ?></td>
          <td><?= hero_slide_badge($s) ?></td>
          <td>
            <div class="btn-group">
              <?php if ($trash): ?>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline"><?= csrf_field() ?><input type="hidden" name="entity" value="hero_slide"><input type="hidden" name="action" value="restore"><input type="hidden" name="id" value="<?= $s['id'] ?>"><button type="submit" class="btn btn-sm btn-outline" data-confirm="Restore?">Restore</button></form>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline"><?= csrf_field() ?><input type="hidden" name="entity" value="hero_slide"><input type="hidden" name="action" value="permanent_delete"><input type="hidden" name="id" value="<?= $s['id'] ?>"><button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Permanently delete?">Delete Forever</button></form>
              <?php else: ?>
                <a href="/admin/hero-slides/edit?id=<?= $s['id'] ?>" class="btn btn-sm btn-outline">Edit</a>
                <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline"><?= csrf_field() ?><input type="hidden" name="entity" value="hero_slide"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $s['id'] ?>"><button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Move to trash?">Delete</button></form>
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
