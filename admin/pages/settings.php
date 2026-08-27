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
      <h3 style="margin:24px 0 12px;text-transform:capitalize"><?= e($group ?: 'general') ?></h3>
      <div class="card" style="padding:20px">
        <?php foreach ($items as $s): ?>
          <form method="POST" action="/admin/api/crud.php" data-ajax style="padding:16px 0;border-bottom:1px solid var(--admin-border)">
            <?= csrf_field() ?>
            <input type="hidden" name="entity" value="setting">
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="key" value="<?= e($s['setting_key']) ?>">
            <div class="form-group" style="margin-bottom:8px">
              <label style="display:flex;align-items:center;gap:8px;text-transform:none">
                <strong><?= e($s['setting_key']) ?></strong>
                <span class="muted small badge" style="background:var(--admin-bg);color:var(--admin-muted)"><?= e($s['setting_type']) ?: 'text' ?></span>
              </label>
              <?php if (($s['setting_type'] ?? 'text') === 'textarea'): ?>
                <textarea name="value" style="min-height:60px"><?= e($s['setting_value'] ?? '') ?></textarea>
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

  <h3 style="margin:24px 0 12px">Add setting</h3>
  <div class="card" style="padding:20px;max-width:520px">
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
  </div>
</div>