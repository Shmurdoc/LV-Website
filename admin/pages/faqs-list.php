<?php
// FAQs List — Viata Luxe Guesthouse
$db = Database::get();
$faqs = $db->query('
    SELECT f.*, p.title AS page_title
    FROM faqs f
    LEFT JOIN pages p ON p.id = f.page_id
    ORDER BY f.sort_order ASC, f.id DESC
')->fetchAll();
?>
<div class="admin-page">
  <div class="page-header page-header-inline">
    <div><h2>FAQs</h2><p class="muted small"><?= count($faqs) ?> question(s)</p></div>
    <a href="/admin/faqs/edit" class="btn btn-primary"><?= admin_icon('plus', 14) ?> New FAQ</a>
  </div>
  <?php if (empty($faqs)): ?>
    <div class="empty-state"><div class="empty-icon"><?= admin_icon('faqs', 24) ?></div><p>No FAQs yet.</p><a href="/admin/faqs/edit" class="btn btn-primary btn-sm">Add your first FAQ</a></div>
  <?php else: ?>
    <div class="data-table-wrap">
    <table class="data-table">
      <thead><tr><th>Question</th><th>Answer</th><th>Page</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($faqs as $f): ?>
        <tr>
          <td><strong><?= e($f['question']) ?></strong></td>
          <td class="muted small text-wrap"><?= e(mb_strimwidth($f['answer'] ?? '', 0, 60, '…')) ?></td>
          <td><?= e($f['page_title'] ?? '—') ?></td>
          <td><span class="badge <?= $f['is_published'] ? 'badge-published' : 'badge-draft' ?>"><?= $f['is_published'] ? 'Published' : 'Draft' ?></span></td>
          <td>
            <div class="btn-group">
              <a href="/admin/faqs/edit?id=<?= $f['id'] ?>" class="btn btn-sm btn-outline"><?= admin_icon('edit', 13) ?> Edit</a>
              <form method="POST" action="/admin/api/crud.php" data-ajax style="display:inline">
                <?= csrf_field() ?>
                <input type="hidden" name="entity" value="faq">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $f['id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Delete this FAQ?"><?= admin_icon('trash', 13) ?></button>
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