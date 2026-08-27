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
  <div class="page-header" style="display:flex;align-items:center;justify-content:space-between">
    <div><h2>FAQs</h2><p class="muted small"><?= count($faqs) ?> question(s)</p></div>
    <a href="/admin/faqs/edit" class="btn btn-primary">+ New FAQ</a>
  </div>
  <?php if (empty($faqs)): ?>
    <div class="empty-state"><p>No FAQs yet.</p></div>
  <?php else: ?>
    <table class="data-table">
      <thead><tr><th>Question</th><th>Answer</th><th>Page</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($faqs as $f): ?>
        <tr>
          <td><strong><?= e($f['question']) ?></strong></td>
          <td class="muted small"><?= e(mb_strimwidth($f['answer'] ?? '', 0, 60, '…')) ?></td>
          <td><?= e($f['page_title'] ?? '—') ?></td>
          <td><span class="<?= $f['is_published'] ? 'badge-published' : 'badge-draft' ?>"><?= $f['is_published'] ? 'Published' : 'Draft' ?></span></td>
          <td>
            <div class="btn-group">
              <a href="/admin/faqs/edit?id=<?= $f['id'] ?>" class="btn btn-sm btn-outline">Edit</a>
              <form method="POST" action="/admin/api/crud.php" data-ajax style="display:inline">
                <?= csrf_field() ?>
                <input type="hidden" name="entity" value="faq">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $f['id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger" data-confirm="Delete this FAQ?">Delete</button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>