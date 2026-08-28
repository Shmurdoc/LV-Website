<?php
// Testimonials List — Viata Luxe Guesthouse
$db = Database::get();
$testimonials = $db->query('
    SELECT t.*, a.slug AS apartment_slug, a.name AS apartment_name
    FROM testimonials t
    LEFT JOIN apartments a ON a.id = t.apartment_id
    ORDER BY t.sort_order ASC, t.id DESC
')->fetchAll();
?>
<div class="admin-page">
  <div class="page-header page-header--spread">
    <div><h2>Testimonials</h2><p class="muted small"><?= count($testimonials) ?> review(s)</p></div>
    <a href="/admin/testimonials/edit" class="btn btn-primary">+ New Testimonial</a>
  </div>
  <?php if (empty($testimonials)): ?>
    <div class="empty-state"><p>No testimonials yet.</p></div>
  <?php else: ?>
    <table class="data-table">
      <thead><tr><th>Reviewer</th><th>Rating</th><th>Apartment</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($testimonials as $t): ?>
        <tr>
          <td><strong><?= e($t['reviewer_name']) ?></strong><?php if ($t['is_featured']) echo ' <span class="badge-published">Featured</span>'; ?></td>
          <td><span class="rating-stars" title="<?= (int)$t['rating'] ?>/5"><?= str_repeat('★', (int)$t['rating']) ?></span></td>
          <td><?= e($t['apartment_name'] ?? '—') ?></td>
          <td><span class="<?= $t['is_published'] ? 'badge-published' : 'badge-draft' ?>"><?= $t['is_published'] ? 'Published' : 'Draft' ?></span></td>
          <td>
            <div class="btn-group">
              <a href="/admin/testimonials/edit?id=<?= $t['id'] ?>" class="btn btn-sm btn-outline">Edit</a>
              <form method="POST" action="/admin/api/crud.php" data-ajax class="form-inline">
                <?= csrf_field() ?>
                <input type="hidden" name="entity" value="testimonial">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $t['id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger" data-confirm="Delete this testimonial?">Delete</button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>