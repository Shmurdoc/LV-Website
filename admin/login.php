<?php
/**
 * Admin Login Page — Viata Luxe Guesthouse
 */
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Rate limit: 5 attempts / 15 min per IP (security checklist)
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $_SESSION['login_rate'][$ip] = array_filter($_SESSION['login_rate'][$ip] ?? [], fn($t) => time()-$t < 900);
    if (count($_SESSION['login_rate'][$ip] ?? []) >= 5) {
        $error = 'Too many attempts. Try again in 15 minutes.';
    } elseif (!csrf_verify()) {
        $error = 'Invalid request. Please refresh and try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $_SESSION['login_rate'][$ip][] = time();

        if ($username && $password) {
            $db = Database::get();
            $stmt = $db->prepare('SELECT id, username, password_hash, full_name, role, is_active FROM admin_users WHERE username = :u1 OR email = :u2 LIMIT 1');
            $stmt->execute(['u1' => $username, 'u2' => $username]);
            $user = $stmt->fetch();

            if ($user && $user['is_active'] && password_verify($password, $user['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['admin_name'] = $user['full_name'];
                $_SESSION['admin_role'] = $user['role'];
                $_SESSION['last_activity'] = time();

                try {
                    $db->prepare('UPDATE admin_users SET last_login = NOW() WHERE id = :id')->execute(['id' => $user['id']]);
                    log_activity('login', 'admin_users', $user['id']);
                } catch (\Throwable $e) {
                    error_log('Login post-auth warning: ' . $e->getMessage());
                }

                header('Location: ' . url('/admin/dashboard'));
                exit;
            }
            $error = 'Invalid username or password.';
        } else {
            $error = 'Please enter username and password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In &middot; Viata Luxe Admin</title>
    <link rel="icon" type="image/png" href="<?= e(url('/Luxury Images/logos/logo-viata-monogram-gold.png')) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300;9..144,500&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(url('/admin/css/login.css')) ?>">
</head>
<body>
    <!-- Nature hero — left panel -->
    <div class="login-hero">
        <img src="<?= e(url('/Luxury Images/activities/blyde-river-canyon-panorama.jpg')) ?>" alt="Blyde River Canyon — Kruger region" width="1920" height="1080" fetchpriority="high" decoding="async">
        <div class="login-hero__veil"></div>
        <div class="login-hero__content">
            <div class="login-hero__brand">Viata <em>Luxe</em></div>
            <div class="login-hero__tagline">Guesthouse &middot; Admin Panel</div>
            <div class="login-hero__rule"></div>
            <div class="login-hero__loc">Phalaborwa &middot; Kruger Minutes</div>
        </div>
    </div>

    <!-- Login form — right panel -->
    <div class="login-panel">
    <div class="login-card">
        <div class="login-lock" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        </div>
        <h1>Viata Luxe</h1>
        <p class="brand-sub">Guesthouse &middot; Admin Panel</p>
        <?php if ($error): ?>
            <div class="error" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12" y2="16"/></svg>
                <span><?= e($error) ?></span>
            </div>
        <?php endif; ?>
        <form method="POST" action="<?= e(url('/admin/login')) ?>">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="username">Username or email</label>
                <div class="field">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <input type="text" id="username" name="username" required autofocus autocomplete="username" value="<?= e($_POST['username'] ?? '') ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="field">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <input type="password" id="password" name="password" required autocomplete="current-password">
                </div>
            </div>
            <button type="submit" class="btn">Sign in to Admin</button>
        </form>
        <div class="login-foot">
            <a href="<?= e(url('/')) ?>" rel="noopener">&larr; Back to website</a>
        </div>
    </div>
    </div>
</body>
</html>
