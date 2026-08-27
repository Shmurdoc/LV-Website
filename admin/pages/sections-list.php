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
  <div class="page-header page-header-inline">
    <div><h2>Sections</h2><p class="muted small"><?= count($sections) ?> section(s) &middot; building blocks of each page</p></div>
    <a href="/admin/sections/edit" class="btn btn-primary"><?= admin_icon('plus', 14) ?> New Section</a>
  </div>
  <?php if (empty($sections)): ?>
    <div class="empty-state"><div class="empty-icon"><?= admin_icon('sections', 24) ?></div><p>No sections yet.</p><a href="/admin/sections/edit" class="btn btn-primary btn-sm">Add a section</a></div>
  <?php else: ?>
    <div class="data-table-wrap">
    <table class="data-table">
      <thead><tr><th>Type</th><th>Title</th><th>Page</th><th>Visible</th><th>Order</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($sections as $s): ?>
        <tr>
          <td><code class="slug"><?= e($s['section_type']) ?></code></td>
          <td><strong><?= e($s['title'] ?: '(untitled)') ?></strong></td>
          <td class="muted"><?= e($s['page_title'] ?? '—') ?></td>
          <td><span class="badge <?= $s['is_visible'] ? 'badge-published' : 'badge-draft' ?>"><?= $s['is_visible'] ? 'Visible' : 'Hidden' ?></span></td>
          <td><?= (int)$s['sort_order'] ?></td>
          <td>
            <div class="btn-group">
              <a href="/admin/sections/edit?id=<?= $s['id'] ?>" class="btn btn-sm btn-outline"><?= admin_icon('edit', 13) ?> Edit</a>
              <form method="POST" action="/admin/api/crud.php" data-ajax style="display:inline">
                <?= csrf_field() ?>
                <input type="hidden" name="entity" value="section">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Delete this section?"><?= admin_icon('trash', 13) ?></button>
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