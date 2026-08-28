<?php
// Settings — Viata Luxe Guesthouse
$db = Database::get();
$settings = $db->query('SELECT * FROM global_settings ORDER BY setting_group ASC, sort_order ASC, setting_key ASC')->fetchAll();
$groups = [];
foreach ($settings as $s) {
    $groups[$s['setting_group']][] = $s;
}
?>
<div class="admin-page">
  <div class="page-header"><div><h2>Settings</h2><p class="muted small"><?= count($settings) ?> setting(s)</p></div></div>

  <?php if (empty($groups)): ?>
    <div class="empty-state"><p>No settings yet.</p></div>
  <?php else: ?>
    <?php foreach ($groups as $group => $items): ?>
      <h3 class="section-heading" style="text-transform:capitalize"><?= e($group ?: 'general') ?></h3>
      <div class="form-card"><div class="form-card__body">
        <?php foreach ($items as $s): ?>
          <form method="POST" action="/admin/api/crud.php" data-ajax class="settings-form">
            <?= csrf_field() ?>
            <input type="hidden" name="entity" value="setting">
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="key" value="<?= e($s['setting_key']) ?>">
            <div class="form-group mb-1">
              <label class="settings-label">
                <strong><?= e($s['setting_key']) ?></strong>
                <span class="muted small badge settings-badge"><?= e($s['setting_type']) ?: 'text' ?></span>
              </label>
              <?php if (($s['setting_type'] ?? 'text') === 'textarea'): ?>
                <textarea name="value"><?= e($s['setting_value'] ?? '') ?></textarea>
              <?php else: ?>
                <input type="text" name="value" value="<?= e($s['setting_value'] ?? '') ?>">
              <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-sm btn-primary">Save</button>
          </form>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

  <h3 class="section-heading">Add setting</h3>
  <div class="card card--narrow">
    <form method="POST" action="/admin/api/crud.php" data-ajax>
      <?= csrf_field() ?>
      <input type="hidden" name="entity" value="setting">
      <input type="hidden" name="action" value="save">
      <div class="form-group">
        <label>Key *</label>
        <input type="text" name="key" placeholder="site_phone" required>
      </div>
      <div class="form-group">
        <label>Value</label>
        <input type="text" name="value">
      </div>
      <button type="submit" class="btn btn-primary">Add Setting</button>
    </form>
  </div></div>
</div>