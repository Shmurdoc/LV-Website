<?php
// Admin Dashboard — stats + recent activity (design-system cards)
$stats = get_admin_stats();
$activity = get_recent_activity(10);
$cards = [
    ['k' => 'pages',        'label' => 'Pages',       'icon' => 'pages',       'tint' => 'gold'],
    ['k' => 'sections',     'label' => 'Sections',    'icon' => 'sections',    'tint' => 'navy'],
    ['k' => 'apartments',   'label' => 'Apartments',  'icon' => 'apartments',  'tint' => 'blue'],
    ['k' => 'testimonials', 'label' => 'Reviews',     'icon' => 'star',        'tint' => 'gold'],
    ['k' => 'gallery_images','label' => 'Gallery',    'icon' => 'gallery',     'tint' => 'green'],
    ['k' => 'faqs',         'label' => 'FAQs',        'icon' => 'faqs',        'tint' => 'navy'],
    ['k' => 'contact_unread','label' => 'Unread Messages', 'icon' => 'inbox',  'tint' => 'red'],
    ['k' => 'sections',     'label' => 'Book Now CTA', 'icon' => 'bed',        'tint' => 'gold'],
];
?>
<div class="admin-page">
  <div class="page-header">
    <h2>Dashboard</h2>
    <p class="muted small">Overview &amp; quick actions &middot; <?= date('l, j F Y') ?></p>
  </div>

  <div class="stats-grid">
    <?php foreach ($cards as $card): ?>
      <div class="stat-card">
        <div class="stat-card__icon stat-icon--<?= e($card['tint']) ?>" aria-hidden="true"><?= admin_icon($card['icon']) ?></div>
        <div>
          <div class="stat-card__label"><?= e($card['label']) ?></div>
          <div class="stat-card__value"><?= (int)($stats[$card['k']] ?? 0) ?></div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="card card-pad">
    <div class="page-header-inline">
      <h3><?= admin_icon('sections', 16) ?> Recent activity</h3>
      <a href="/admin/contact" class="btn btn-sm btn-outline"><?= admin_icon('inbox', 14) ?> View all</a>
    </div>
    <?php if (empty($activity)): ?>
      <p class="muted small">No activity yet — changes you make will appear here.</p>
    <?php else: ?>
      <ul class="activity">
      <?php foreach ($activity as $a): ?>
        <li>
          <span class="activity-dot" aria-hidden="true"></span>
          <span><strong><?= e($a['username'] ?? $a['full_name'] ?? 'system') ?></strong> &middot; <?= e($a['action']) ?>&nbsp;<?= e($a['entity_type'] ?? '') ?><?= $a['entity_id'] ? ' #' . e($a['entity_id']) : '' ?></span>
          <span class="activity-time"><?= e(date('j M H:i', strtotime($a['created_at']))) ?></span>
        </li>
      <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>
</div>