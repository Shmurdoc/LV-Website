<?php
// User Edit — Viata Luxe Guesthouse
require_permission('users.manage');

$db = Database::get();
$id = (int)($_GET['id'] ?? 0);
$isNew = empty($id);
$user = null;
$error = '';
$success = '';

if (!$isNew) {
    $stmt = $db->prepare("SELECT * FROM admin_users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();
    if (!$user) {
        $_SESSION['flash_error'] = 'User not found.';
        header('Location: /admin/users');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $error = 'Invalid request.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $full_name = trim($_POST['full_name'] ?? '');
        $role = $_POST['role'] ?? 'editor';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $password = $_POST['password'] ?? '';

        if (!$username || !$email) {
            $error = 'Username and email are required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email address.';
        } elseif ($isNew && !$password) {
            $error = 'Password is required for new users.';
        } elseif (strlen($password) > 0 && strlen($password) < 8) {
            $error = 'Password must be at least 8 characters.';
        } else {
            // Check unique constraints
            $check = $db->prepare("SELECT id FROM admin_users WHERE (username = ? OR email = ?) AND id != ?");
            $check->execute([$username, $email, $id ?: 0]);
            if ($check->fetch()) {
                $error = 'Username or email already exists.';
            } else {
                // Build permissions based on role
                $permissions = [];
                if ($role === 'admin') {
                    $permissions = get_all_permission_keys();
                } else {
                    // Editor: content permissions
                    $permissions = [
                        'dashboard.read',
                        'pages.read', 'pages.write',
                        'sections.read', 'sections.write',
                        'apartments.read', 'apartments.write',
                        'safari.read', 'safari.write',
                        'gallery.read', 'gallery.write',
                        'categories.read', 'categories.write',
                        'testimonials.read', 'testimonials.write',
                        'contact.read',
                        'dining.read', 'dining.write',
                        'hero.read', 'hero.write',
                        'promise.read', 'promise.write',
                        'moments.read', 'moments.write',
                        'faqs.read', 'faqs.write',
                    ];
                }

                if ($isNew) {
                    $stmt = $db->prepare("INSERT INTO admin_users (username, email, password_hash, full_name, role, permissions, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT), $full_name, $role, json_encode($permissions), $is_active]);
                    $_SESSION['flash_success'] = 'User created.';
                } else {
                    $sql = "UPDATE admin_users SET username=?, email=?, full_name=?, role=?, permissions=?, is_active=?";
                    $params = [$username, $email, $full_name, $role, json_encode($permissions), $is_active];
                    if ($password) {
                        $sql .= ", password_hash=?";
                        $params[] = password_hash($password, PASSWORD_DEFAULT);
                    }
                    $sql .= " WHERE id=?";
                    $params[] = $id;
                    $db->prepare($sql)->execute($params);
                    $_SESSION['flash_success'] = 'User updated.';
                }
                header('Location: /admin/users');
                exit;
            }
        }
    }
}
?>
<div class="admin-page">
  <div class="page-header">
    <h2><?= $isNew ? 'New User' : 'Edit User' ?></h2>
    <a href="/admin/users" class="btn btn-outline btn-sm"><?= admin_icon('list', 14) ?> Back to Users</a>
  </div>
  <?php if ($error): ?>
    <div class="alert alert-error" role="alert"><?= e($error) ?></div>
  <?php endif; ?>
  <form method="POST" class="settings-form" style="max-width:640px">
    <?= csrf_field() ?>
    <div class="form-group">
      <label for="username">Username *</label>
      <input type="text" id="username" name="username" required value="<?= e($user['username'] ?? $_POST['username'] ?? '') ?>">
    </div>
    <div class="form-group">
      <label for="email">Email *</label>
      <input type="email" id="email" name="email" required value="<?= e($user['email'] ?? $_POST['email'] ?? '') ?>">
    </div>
    <div class="form-group">
      <label for="full_name">Full Name</label>
      <input type="text" id="full_name" name="full_name" value="<?= e($user['full_name'] ?? $_POST['full_name'] ?? '') ?>">
    </div>
    <div class="form-group">
      <label for="password"><?= $isNew ? 'Password *' : 'New Password (leave blank to keep current)' ?></label>
      <input type="password" id="password" name="password" <?= $isNew ? 'required' : '' ?> minlength="8">
    </div>
    <div class="form-group">
      <label for="role">Role</label>
      <select id="role" name="role">
        <option value="editor" <?= ($user['role'] ?? 'editor') === 'editor' ? 'selected' : '' ?>>Editor — content only</option>
        <option value="admin" <?= ($user['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin — full access</option>
      </select>
      <p class="form-help">Admin: full access to all sections. Editor: content pages only, no settings/users/navigation.</p>
    </div>
    <?php if (!$isNew && $user['id'] != ($_SESSION['admin_id'] ?? 0)): ?>
    <div class="form-group">
      <label class="toggle-label">
        <input type="checkbox" name="is_active" value="1" <?= ($user['is_active'] ?? 1) ? 'checked' : '' ?>>
        Active account
      </label>
    </div>
    <?php endif; ?>
    <div class="btn-group" style="margin-top:1.5rem">
      <button type="submit" class="btn btn-primary"><?= $isNew ? 'Create User' : 'Save Changes' ?></button>
      <a href="/admin/users" class="btn btn-outline">Cancel</a>
    </div>
  </form>
</div>
