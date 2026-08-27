<?php
// Admin Dashboard — stats + recent activity (uses get_admin_stats / get_recent_activity)
$stats = get_admin_stats();
$activity = get_recent_activity(8);
?>
<div class="admin-page">
  <div class="page-header"><h2>Dashboard</h2><span class="muted">Overview</span></div>
  <div class="stats-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin:16px 0">
    <?php foreach (['pages'=>'Pages','sections'=>'Sections','apartments'=>'Apartments','testimonials'=>'Reviews','gallery_images'=>'Gallery','faqs'=>'FAQs','contact_unread'=>'Unread'] as $k=>$label): ?>
      <div class="card" style="padding:18px"><div class="muted small"><?= e($label) ?></div><div style="font-size:22px;font-weight:700"><?= (int)($stats[$k]??0) ?></div></div>
    <?php endforeach; ?>
  </div>
  <div class="card" style="padding:18px">
    <h3 style="margin-bottom:10px">Recent activity</h3>
    <?php if (empty($activity)): ?><p class="muted small">No activity yet.</p><?php else: ?>
      <ul style="display:grid;gap:8px">
      <?php foreach ($activity as $a): ?>
        <li class="small"><strong><?= e($a['username']??$a['full_name']??'system') ?></strong> <?= e($a['action']) ?> <?= e($a['entity_type']??'') ?> #<?= e($a['entity_id']??'') ?> <span class="muted"><?= e($a['created_at']) ?></span></li>
      <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>
</div>
