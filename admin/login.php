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

                header('Location: /admin/dashboard');
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
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; }
        body {
            font-family: 'Manrope', system-ui, sans-serif;
            background: var(--navy, #0B1A2E);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow: hidden;
        }
        /* soft gold aurora */
        body::before, body::after {
            content: ''; position: absolute; border-radius: 50%; filter: blur(90px); opacity: .5;
        }
        body::before { width: 420px; height: 420px; background: rgba(140,116,52,.40); top: -80px; left: -80px; }
        body::after  { width: 380px; height: 380px; background: rgba(19,40,66,.80); bottom: -100px; right: -60px; }

        .login-card {
            position: relative; z-index: 2;
            background: var(--cream, #FFFFFF);
            border-radius: 18px;
            padding: 3rem 3rem 2.75rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 30px 70px rgba(0,0,0,.45);
            border: 1px solid rgba(255,255,255,.08);
        }
        .login-lock {
            width: 52px; height: 52px; margin: 0 auto 18px;
            display: grid; place-items: center;
            border-radius: 14px;
            background: var(--gold-100, #F5F0E1);
            color: var(--gold, #8C7434);
        }
        .login-lock svg { width: 24px; height: 24px; }
        .login-card h1 {
            font-family: 'Fraunces', serif; font-weight: 300;
            font-size: 1.6rem; color: var(--navy, #0B1A2E);
            text-align: center; letter-spacing: .02em;
        }
        .login-card .brand-sub {
            text-align: center; color: var(--muted, #8A93A0);
            font-size: .78rem; letter-spacing: .18em; text-transform: uppercase;
            margin-bottom: 2rem;
        }
        .form-group { margin-bottom: 1.25rem; }
        .form-group label {
            display: block; font-weight: 600; color: var(--text, #1A2233);
            margin-bottom: .45rem; font-size: .82rem;
        }
        .form-group .field {
            position: relative;
        }
        .form-group .field svg {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            width: 18px; height: 18px; color: #A7B0BD;
        }
        .form-group input {
            width: 100%;
            padding: .82rem 1rem .82rem 44px;
            border: 1px solid var(--border, #E5E8EE);
            border-radius: 10px;
            font-size: .95rem;
            font-family: inherit;
            color: var(--text, #1A2233);
            background: #FAFBFC;
            transition: border-color .2s, box-shadow .2s;
        }
        .form-group input:focus {
            outline: none;
            border-color: var(--gold, #8C7434);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(140,116,52,.16);
        }
        .error {
            display: flex; align-items: flex-start; gap: 8px;
            background: #FDECEC; color: #B91C1C;
            padding: .7rem 1rem; border-radius: 10px;
            margin-bottom: 1.25rem; font-size: .82rem;
            border: 1px solid #F6D0D0;
        }
        .error svg { width: 16px; height: 16px; flex: none; margin-top: 1px; }
        .btn {
            width: 100%;
            padding: .85rem;
            background: var(--gold, #8C7434);
            color: #fff;
            border: none; border-radius: 10px;
            font-size: .95rem; font-weight: 700; font-family: inherit;
            cursor: pointer;
            transition: background .2s, transform .1s;
        }
        .btn:hover { background: var(--gold-600, #7A642C); }
        .btn:active { transform: translateY(1px); }
        .login-foot {
            margin-top: 1.5rem; text-align: center;
            font-size: .74rem; color: var(--muted, #8A93A0);
        }
        .login-foot a { color: var(--gold, #8C7434); text-decoration: none; font-weight: 600; }
        .login-foot a:hover { text-decoration: underline; }
        /* login icon inline helper */
        .icon { display: none; }
    </style>
</head>
<body>
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
        <form method="POST" action="/admin/login">
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
</body>
</html>
