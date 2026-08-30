<?php
// Categories List — Viata Luxe Guesthouse
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../includes/admin-functions.php';
require_once __DIR__ . '/../includes/taxonomy.php';

$filter = $_GET['type'] ?? '';
$types = ['apartment', 'gallery', 'safari'];

if ($filter && in_array($filter, $types)) {
    $categories = get_public_categories($filter, false);
} else {
    $categories = [];
    foreach ($types as $t) {
        $categories = array_merge($categories, get_public_categories($t, false));
    }
}
?>
<div class="admin-page">
  <div class="page-header page-header-inline">
    <div><h2>Public Categories</h2><p class="muted small"><?= count($categories) ?> categories</p></div>
    <div class="btn-group">
      <a href="/admin/categories" class="btn btn-outline btn-sm <?= !$filter ? 'active' : '' ?>">All</a>
      <?php foreach ($types as $t): ?>
        <a href="/admin/categories?type=<?= $t ?>" class="btn btn-outline btn-sm <?= $filter === $t ? 'active' : '' ?>"><?= ucfirst($t) ?></a>
      <?php endforeach; ?>
      <a href="/admin/categories/edit" class="btn btn-primary btn-sm"><?= admin_icon('plus', 14) ?> New Category</a>
    </div>
  </div>
  <?php if (empty($categories)): ?>
    <div class="empty-state"><p>No categories found.</p>
      <a href="/admin/categories/edit" class="btn btn-primary btn-sm">Create your first category</a>
    </div>
  <?php else: ?>
    <div class="data-table-wrap">
    <table class="data-table">
      <thead><tr><th>Name</th><th>Slug</th><th>Type</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($categories as $c): ?>
        <tr>
          <td><strong><?= e($c['name']) ?></strong></td>
          <td><code class="slug"><?= e($c['slug']) ?></code></td>
          <td><span class="badge badge-draft"><?= e(ucfirst($c['entity_type'])) ?></span></td>
          <td><?= (int)$c['sort_order'] ?></td>
          <td><?= $c['is_active'] ? '<span class="badge badge-published">Active</span>' : '<span class="badge badge-draft">Inactive</span>' ?></td>
          <td>
            <div class="btn-group">
              <a href="/admin/categories/edit?id=<?= $c['id'] ?>" class="btn btn-sm btn-outline"><?= admin_icon('edit', 13) ?> Edit</a>
              <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                <?= csrf_field() ?>
                <input type="hidden" name="entity" value="public_category">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Delete this category? Content will be unlinked."><?= admin_icon('trash', 13) ?></button>
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
