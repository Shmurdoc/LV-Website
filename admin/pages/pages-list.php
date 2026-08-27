<?php
// Pages List — Viata Luxe Guesthouse
$db = Database::get();
$pages = $db->query('SELECT * FROM pages ORDER BY sort_order ASC, title ASC')->fetchAll();
?>
<div class="admin-page">
  <div class="page-header" style="display:flex;align-items:center;justify-content:space-between">
    <div><h2>Pages</h2><p class="muted small"><?= count($pages) ?> page(s)</p></div>
    <a href="/admin/pages/edit" class="btn btn-primary">+ New Page</a>
  </div>
  <?php if (empty($pages)): ?>
    <div class="empty-state"><p>No pages yet.</p></div>
  <?php else: ?>
    <table class="data-table">
      <thead><tr><th>Title</th><th>Slug</th><th>Template</th><th>Status</th><th>Order</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($pages as $p): ?>
        <tr>
          <td><strong><?= e($p['title']) ?></strong></td>
          <td><code style="font-size:0.8rem;background:#f3f4f6;padding:2px 6px;border-radius:4px">/<?= e($p['slug']) ?></code></td>
          <td><?= e($p['template']) ?></td>
          <td><span class="<?= $p['is_published'] ? 'badge-published' : 'badge-draft' ?>"><?= $p['is_published'] ? 'Published' : 'Draft' ?></span></td>
          <td><?= (int)$p['sort_order'] ?></td>
          <td>
            <div class="btn-group">
              <a href="/admin/pages/edit?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline">Edit</a>
              <form method="POST" action="/admin/api/crud.php" data-ajax style="display:inline">
                <?= csrf_field() ?>
                <input type="hidden" name="entity" value="page">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger" data-confirm="Delete this page?">Delete</button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
