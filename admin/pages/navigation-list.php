<?php
// Navigation List — Viata Luxe Guesthouse
$db = Database::get();
$items = $db->query('
    SELECT n.*, p.title AS page_title, parent.label AS parent_label
    FROM navigation n
    LEFT JOIN pages p ON p.id = n.page_id
    LEFT JOIN navigation parent ON parent.id = n.parent_id
    ORDER BY n.sort_order ASC, n.id ASC
')->fetchAll();
?>
<div class="admin-page">
  <div class="page-header" style="display:flex;align-items:center;justify-content:space-between">
    <div><h2>Navigation</h2><p class="muted small"><?= count($items) ?> item(s)</p></div>
    <a href="/admin/navigation/edit" class="btn btn-primary">+ New Item</a>
  </div>
  <?php if (empty($items)): ?>
    <div class="empty-state"><p>No navigation items yet.</p></div>
  <?php else: ?>
    <table class="data-table">
      <thead><tr><th>Label</th><th>Target</th><th>Parent</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($items as $n): ?>
        <tr>
          <td><strong><?= e($n['label']) ?></strong><?php if ($n['open_in_new_tab']) echo ' <span class="muted small">↗</span>'; ?></td>
          <td><?= $n['page_title'] ? e('/' . $n['page_title']) : e($n['url'] ?: '—') ?></td>
          <td><?= e($n['parent_label'] ?? '—') ?></td>
          <td><?= (int)$n['sort_order'] ?></td>
          <td><span class="<?= $n['is_published'] ? 'badge-published' : 'badge-draft' ?>"><?= $n['is_published'] ? 'Published' : 'Draft' ?></span></td>
          <td>
            <div class="btn-group">
              <a href="/admin/navigation/edit?id=<?= $n['id'] ?>" class="btn btn-sm btn-outline">Edit</a>
              <form method="POST" action="/admin/api/crud.php" data-ajax style="display:inline">
                <?= csrf_field() ?>
                <input type="hidden" name="entity" value="navigation">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $n['id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger" data-confirm="Delete this navigation item?">Delete</button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>