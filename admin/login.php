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
    <title>Admin Login — Viata Luxe</title>
    <link rel="stylesheet" href="/css/tokens.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', var(--font-sans, sans-serif);
            background: var(--navy, #0a1f2f);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #fff;
            border-radius: 12px;
            padding: 3rem 2.5rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
        }
        .login-card h1 {
            font-size: 1.5rem;
            color: var(--navy, #0a1f2f);
            text-align: center;
            margin-bottom: 0.5rem;
        }
        .login-card p {
            text-align: center;
            color: #666;
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.4rem;
            font-size: 0.85rem;
        }
        .form-group input {
            width: 100%;
            padding: 0.7rem 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s;
        }
        .form-group input:focus {
            outline: none;
            border-color: var(--gold, #c9a84c);
            box-shadow: 0 0 0 3px rgba(201,168,76,0.15);
        }
        .error {
            background: #fee;
            color: #c00;
            padding: 0.7rem 1rem;
            border-radius: 8px;
            margin-bottom: 1.25rem;
            font-size: 0.85rem;
            border: 1px solid #fcc;
        }
        .btn {
            width: 100%;
            padding: 0.8rem;
            background: var(--gold, #c9a84c);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn:hover { background: #b8963f; }
    </style>
</head>
<body>
    <div class="login-card">
        <h1>Viata Luxe</h1>
        <p>Admin Panel</p>
        <?php if ($error): ?>
            <div class="error" role="alert"><?= e($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="/admin/login">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="username">Username or Email</label>
                <input type="text" id="username" name="username" required autofocus autocomplete="username" value="<?= e($_POST['username'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn">Sign In</button>
        </form>
    </div>
</body>
</html>
