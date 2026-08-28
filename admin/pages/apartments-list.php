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
  <div class="page-header page-header-inline">
    <div><h2>Apartments</h2><p class="muted small"><?= count($apartments) ?> apartment(s)</p></div>
    <a href="/admin/apartments/edit" class="btn btn-primary"><?= admin_icon('plus', 14) ?> New Apartment</a>
  </div>
  <?php if (empty($apartments)): ?>
    <div class="empty-state"><div class="empty-icon"><?= admin_icon('apartments', 24) ?></div><p>No apartments yet.</p><a href="/admin/apartments/edit" class="btn btn-primary btn-sm">Create your first apartment</a></div>
  <?php else: ?>
    <div class="data-table-wrap">
    <table class="data-table">
      <thead><tr><th>Name</th><th>Slug</th><th>Price</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($apartments as $a): ?>
        <tr>
          <td><strong><?= e($a['name']) ?></strong><?php if ($a['page_title']) echo ' <span class="muted small">&middot; ' . e($a['page_title']) . '</span>'; ?></td>
          <td><code class="slug">/<?= e($a['slug']) ?></code></td>
          <td><strong><?= format_price((float)$a['price_per_night']) ?></strong></td>
          <td><span class="badge <?= $a['is_published'] ? 'badge-published' : 'badge-draft' ?>"><?= $a['is_published'] ? 'Published' : 'Draft' ?></span></td>
          <td>
            <div class="btn-group">
              <a href="/admin/apartments/edit?id=<?= $a['id'] ?>" class="btn btn-sm btn-outline"><?= admin_icon('edit', 13) ?> Edit</a>
              <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                <?= csrf_field() ?>
                <input type="hidden" name="entity" value="apartment">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $a['id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger-outline" data-confirm="Delete this apartment?"><?= admin_icon('trash', 13) ?></button>
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