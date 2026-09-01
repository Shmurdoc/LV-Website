<?php
// Users List — Viata Luxe Guesthouse
require_permission('users.manage');

$db = Database::get();
$users = $db->query("
    SELECT id, username, email, full_name, role, is_active, last_login, created_at,
           JSON_LENGTH(permissions) AS perm_count
    FROM admin_users
    ORDER BY role ASC, username ASC
")->fetchAll();

function user_status_badge(array $row): string {
    if (!$row['is_active']) return '<span class="badge badge-draft">Disabled</span>';
    return '<span class="badge badge-published">Active</span>';
}
?>
<div class="admin-page">
  <div class="page-header page-header-inline">
    <div><h2>Admin Users</h2><p class="muted small"><?= count($users) ?> user(s)</p></div>
    <div class="btn-group">
      <a href="/admin/users/edit" class="btn btn-primary"><?= admin_icon('plus', 14) ?> New User</a>
    </div>
  </div>
  <?= admin_list_search('Search users…') ?>
  <?php if (empty($users)): ?>
    <div class="empty-state"><div class="empty-icon"><?= admin_icon('settings', 24) ?></div><p>No admin users found.</p></div>
  <?php else: ?>
    <div class="data-table-wrap">
    <table class="data-table">
      <thead><tr><th>User</th><th>Email</th><th>Role</th><th>Permissions</th><th>Status</th><th>Last Login</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($users as $u): ?>
        <tr>
          <td><strong><?= e($u['full_name'] ?: $u['username']) ?></strong><br><span class="muted small">@<?= e($u['username']) ?></span></td>
          <td class="muted small"><?= e($u['email']) ?></td>
          <td><span class="badge <?= $u['role'] === 'admin' ? 'badge-published' : 'badge-scheduled' ?>"><?= e(ucfirst($u['role'])) ?></span></td>
          <td class="muted small"><?= $u['perm_count'] ?> keys</td>
          <td><?= user_status_badge($u) ?></td>
          <td class="muted small"><?= $u['last_login'] ? date('d M Y H:i', strtotime($u['last_login'])) : 'Never' ?></td>
          <td>
            <div class="btn-group">
              <?php if ($u['id'] != ($_SESSION['admin_id'] ?? 0)): ?>
              <a href="/admin/users/edit?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline">Edit</a>
              <?php else: ?>
              <span class="muted small">You</span>
              <?php endif; ?>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    </div>
  <?php endif; ?>
</div>
