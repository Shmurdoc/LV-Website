<?php
// Sections List — Viata Luxe Guesthouse
$db = Database::get();
$sections = $db->query('
    SELECT s.*, p.title AS page_title
    FROM sections s
    LEFT JOIN pages p ON p.id = s.page_id
    ORDER BY s.page_id ASC, s.sort_order ASC
')->fetchAll();
?>
<div class="admin-page">
  <div class="page-header" style="display:flex;align-items:center;justify-content:space-between">
    <div><h2>Sections</h2><p class="muted small"><?= count($sections) ?> section(s)</p></div>
    <a href="/admin/sections/edit" class="btn btn-primary">+ New Section</a>
  </div>
  <?php if (empty($sections)): ?>
    <div class="empty-state"><p>No sections yet.</p></div>
  <?php else: ?>
    <table class="data-table">
      <thead><tr><th>Type</th><th>Title</th><th>Page</th><th>Visible</th><th>Order</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($sections as $s): ?>
        <tr>
          <td><code style="font-size:0.8rem;background:#f3f4f6;padding:2px 6px;border-radius:4px"><?= e($s['section_type']) ?></code></td>
          <td><strong><?= e($s['title'] ?: '(untitled)') ?></strong></td>
          <td><?= e($s['page_title'] ?? '—') ?></td>
          <td><span class="<?= $s['is_visible'] ? 'badge-published' : 'badge-draft' ?>"><?= $s['is_visible'] ? 'Visible' : 'Hidden' ?></span></td>
          <td><?= (int)$s['sort_order'] ?></td>
          <td>
            <div class="btn-group">
              <a href="/admin/sections/edit?id=<?= $s['id'] ?>" class="btn btn-sm btn-outline">Edit</a>
              <form method="POST" action="/admin/api/crud.php" data-ajax style="display:inline">
                <?= csrf_field() ?>
                <input type="hidden" name="entity" value="section">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger" data-confirm="Delete this section?">Delete</button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>