<?php
// Navigation List — Viata Luxe Guesthouse
$db = Database::get();
$items = $db->query('
    SELECT n.*, p.slug AS page_slug, p.title AS page_title, parent.label AS parent_label
    FROM navigation n
    LEFT JOIN pages p ON p.id = n.page_id
    LEFT JOIN navigation parent ON parent.id = n.parent_id
    ORDER BY n.sort_order ASC, n.id ASC
')->fetchAll();
?>
<div class="admin-page">
  <div class="page-header page-header-inline">
    <div><h2>Navigation</h2><p class="muted small"><?= count($items) ?> menu item(s)</p></div>
    <a href="/admin/navigation/edit" class="btn btn-primary"><?= admin_icon('plus', 14) ?> New Item</a>
  </div>
  <?php if (empty($items)): ?>
    <div class="empty-state"><div class="empty-icon"><?= admin_icon('navigation', 24) ?></div><p>No navigation items yet.</p><a href="/admin/navigation/edit" class="btn btn-primary btn-sm">Add a menu item</a></div>
  <?php else: ?>
    <div class="data-table-wrap">
    <table class="data-table">
      <thead><tr><th>Label</th><th>Target</th><th>Parent</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($items as $n): ?>
        <tr>
          <td><strong><?= e($n['label']) ?></strong><?php if ($n['open_in_new_tab']) echo ' <span class="badge badge-muted">new&nbsp;tab</span>'; ?></td>
          <td><?= $n['page_slug'] ? '<code class="slug">/' . e($n['page_slug']) . '</code>' : e($n['url'] ?: '—') ?></td>
          <td class="muted"><?= e($n['parent_label'] ?? '—') ?></td>
          <td><?= (int)$n['sort_order'] ?></td>
          <td><span class="badge <?= $n['is_published'] ? 'badge-published' : 'badge-draft' ?>"><?= $n['is_published'] ? 'Published' : 'Draft' ?></span></td>
          <td>
            <div class="btn-group">
              <a href="/admin/navigation/edit?id=<?= $n['id'] ?>" class="btn btn-sm btn-outline"><?= admin_icon('edit', 13) ?> Edit</a>
              <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                <?= csrf_field() ?>
                <input type="hidden" name="entity" value="navigation">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $n['id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Delete this navigation item?"><?= admin_icon('trash', 13) ?></button>
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