<?php
// Pages List — Viata Luxe Guesthouse
$db = Database::get();
$pages = $db->query('SELECT * FROM pages ORDER BY sort_order ASC, title ASC')->fetchAll();
?>
<div class="admin-page">
  <div class="page-header page-header-inline">
    <div><h2>Pages</h2><p class="muted small"><?= count($pages) ?> page(s)</p></div>
    <a href="/admin/pages/edit" class="btn btn-primary"><?= admin_icon('plus', 14) ?> New Page</a>
  </div>
  <?php if (empty($pages)): ?>
    <div class="empty-state"><div class="empty-icon"><?= admin_icon('pages', 24) ?></div><p>No pages yet.</p><a href="/admin/pages/edit" class="btn btn-primary btn-sm">Create your first page</a></div>
  <?php else: ?>
    <div class="data-table-wrap">
    <table class="data-table">
      <thead><tr><th>Title</th><th>Slug</th><th>Template</th><th>Status</th><th>Order</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($pages as $p): ?>
        <tr>
          <td><strong><?= e($p['title']) ?></strong><?= $p['is_homepage'] ? ' <span class="badge badge-gold">Home</span>' : '' ?></td>
          <td><code class="slug">/<?= e($p['slug']) ?></code></td>
          <td><code class="slug"><?= e($p['template']) ?></code></td>
          <td><span class="badge <?= $p['is_published'] ? 'badge-published' : 'badge-draft' ?>"><?= $p['is_published'] ? 'Published' : 'Draft' ?></span></td>
          <td><?= (int)$p['sort_order'] ?></td>
          <td>
            <div class="btn-group">
              <a href="/admin/pages/edit?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline"><?= admin_icon('edit', 13) ?> Edit</a>
              <form method="POST" action="/admin/api/crud.php" data-ajax style="display:inline">
                <?= csrf_field() ?>
                <input type="hidden" name="entity" value="page">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Delete this page?"><?= admin_icon('trash', 13) ?></button>
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