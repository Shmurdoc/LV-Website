<?php
/**
 * Admin Panel Layout — Viata Luxe Guesthouse
 * Wraps all admin pages with sidebar + header.
 */

$adminNav = get_admin_nav();
$currentPage = $adminPage ?? '';
$unreadCount = get_unread_submissions_count();
$adminName = $_SESSION['admin_name'] ?? $_SESSION['admin_username'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Viata Luxe Guesthouse</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/admin/css/admin.css">
</head>
<body class="admin-body">
    <aside class="admin-sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="/admin/dashboard" class="sidebar-brand">Viata Luxe</a>
            <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">&times;</button>
        </div>
        <nav class="sidebar-nav">
            <?php foreach ($adminNav as $item): ?>
                <?php
                $isActive = strpos($currentPage, $item['url']) === 0
                    || ($currentPage === '/dashboard' && $item['url'] === '/admin/dashboard');
                ?>
                <a href="<?= $item['url'] ?>" class="sidebar-link <?= $isActive ? 'active' : '' ?>">
                    <span class="sidebar-icon"><?= $item['icon'] ?></span>
                    <span class="sidebar-label"><?= $item['label'] ?></span>
                    <?php if ($item['label'] === 'Contact' && $unreadCount > 0): ?>
                        <span class="badge badge-red"><?= $unreadCount ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <div class="sidebar-footer">
            <a href="/" target="_blank" class="sidebar-link">
                <span class="sidebar-icon">🌐</span>
                <span class="sidebar-label">View Site</span>
            </a>
            <a href="/admin/logout" class="sidebar-link">
                <span class="sidebar-icon">🚪</span>
                <span class="sidebar-label">Sign Out</span>
            </a>
        </div>
    </aside>

    <main class="admin-main">
        <header class="admin-header">
            <button class="mobile-toggle" id="mobileToggle" aria-label="Toggle menu">&#9776;</button>
            <h1 class="admin-page-title" id="pageTitle"></h1>
            <div class="admin-header-right">
                <span class="admin-user"><?= e($adminName) ?></span>
            </div>
        </header>

        <div class="admin-content" id="adminContent">
            <!-- Page content loaded here -->
            <div class="loading">Loading...</div>
            <?= csrf_field() ?>
        </div>
    </main>

    <div class="admin-overlay" id="overlay"></div>

    <script src="/admin/js/admin.js"></script>
    <script>
        AdminApp.init('<?= e($currentPage) ?>');
    </script>
</body>
</html>
