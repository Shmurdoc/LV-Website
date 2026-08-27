<?php
// Apartments List — Viata Luxe Guesthouse
$db = Database::get();
$apartments = $db->query('
    SELECT a.*, p.title AS page_title
    FROM apartments a
    LEFT JOIN pages p ON p.id = a.page_id
    ORDER BY a.sort_order ASC, a.name ASC
')->fetchAll();
?>
<div class="admin-page">
  <div class="page-header" style="display:flex;align-items:center;justify-content:space-between">
    <div><h2>Apartments</h2><p class="muted small"><?= count($apartments) ?> apartment(s)</p></div>
    <a href="/admin/apartments/edit" class="btn btn-primary">+ New Apartment</a>
  </div>
  <?php if (empty($apartments)): ?>
    <div class="empty-state"><p>No apartments yet.</p></div>
  <?php else: ?>
    <table class="data-table">
      <thead><tr><th>Name</th><th>Slug</th><th>Price</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($apartments as $a): ?>
        <tr>
          <td><strong><?= e($a['name']) ?></strong><?php if ($a['page_title']) echo ' <span class="muted small">— ' . e($a['page_title']) . '</span>'; ?></td>
          <td><code style="font-size:0.8rem;background:#f3f4f6;padding:2px 6px;border-radius:4px">/<?= e($a['slug']) ?></code></td>
          <td><?= format_price((float)$a['price_per_night']) ?></td>
          <td><span class="<?= $a['is_published'] ? 'badge-published' : 'badge-draft' ?>"><?= $a['is_published'] ? 'Published' : 'Draft' ?></span></td>
          <td>
            <div class="btn-group">
              <a href="/admin/apartments/edit?id=<?= $a['id'] ?>" class="btn btn-sm btn-outline">Edit</a>
              <form method="POST" action="/admin/api/crud.php" data-ajax style="display:inline">
                <?= csrf_field() ?>
                <input type="hidden" name="entity" value="apartment">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $a['id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger" data-confirm="Delete this apartment?">Delete</button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>