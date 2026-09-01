<?php
/**
 * Admin Panel Layout — Viata Luxe Guesthouse
 * Wraps all admin pages with branded sidebar + header (design system).
 */

require_once __DIR__ . '/includes/admin-nav.php';
$currentPage = $adminPage ?? '';
$unreadCount = get_unread_submissions_count();
$adminName = $_SESSION['admin_full_name'] ?? $_SESSION['admin_name'] ?? $_SESSION['admin_username'] ?? 'Admin';
$initials = strtoupper(mb_substr($adminName, 0, 2));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin &middot; Viata Luxe Guesthouse</title>
    <meta name="csrf_token" content="<?= e(csrf_token()) ?>">
    <link rel="icon" type="image/svg+xml" href="<?= e(url('/Luxury Images/logos/logo-viata-monogram.svg')) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(url('/admin/css/admin.css')) ?>">
</head>
<body class="admin-body" data-admin-base="<?= e(url('/admin')) ?>">
    <aside class="admin-sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="<?= e(url('/admin/dashboard')) ?>" class="sidebar-brand">
                Viata&nbsp;<span>Luxe</span>
                <small>Guesthouse &middot; Admin</small>
            </a>
            <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar" aria-expanded="false">&times;</button>
        </div>
        <nav class="sidebar-nav" aria-label="Admin">
            <?php
            $navGroups = get_admin_nav_groups();
            ?>
            <?php foreach ($navGroups as $group): ?>
                <?php if ($group['label']): ?>
                    <details class="nav-group" open>
                        <summary class="nav-group__label"><?= e($group['label']) ?></summary>
                        <div class="nav-group__items">
                <?php endif; ?>
                <?php foreach ($group['items'] as $item): ?>
                    <?php
                    $isActive = strpos($currentPage, $item['path']) === 0
                        || ($currentPage === '/dashboard' && $item['path'] === '/dashboard');
                    if ($item['icon'] === 'trash') {
                        $isActive = ($currentPage === '/pages' && !empty($_GET['trash']));
                    }
                    ?>
                    <a href="<?= e($item['url']) ?>" class="sidebar-link <?= $isActive ? 'active' : '' ?>" aria-current="<?= $isActive ? 'page' : 'false' ?>">
                        <?= admin_icon($item['icon']) ?>
                        <span class="sidebar-label"><?= e($item['label']) ?></span>
                        <?php if ($item['label'] === 'Contact' && $unreadCount > 0): ?>
                            <span class="badge badge-red"><?= $unreadCount ?></span>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
                <?php if ($group['label']): ?>
                        </div>
                    </details>
                <?php endif; ?>
            <?php endforeach; ?>
        </nav>
        <div class="sidebar-footer">
            <a href="<?= e(url('/')) ?>" target="_blank" rel="noopener" class="sidebar-link">
                <?= admin_icon('globe') ?>
                <span class="sidebar-label">View Website</span>
            </a>
            <a href="<?= e(url('/admin/logout')) ?>" class="sidebar-link">
                <?= admin_icon('logout') ?>
                <span class="sidebar-label">Sign Out</span>
            </a>
        </div>
    </aside>

    <main class="admin-main">
        <header class="admin-header">
            <button class="mobile-toggle" id="mobileToggle" aria-label="Toggle menu" aria-expanded="false">&#9776;</button>
            <h1 class="admin-page-title" id="pageTitle">Dashboard</h1>
            <div class="admin-header-right">
                <a href="<?= e(url('/')) ?>" target="_blank" rel="noopener" class="view-site">
                    <?= admin_icon('globe', 15) ?> View site
                </a>
                <span class="admin-user">
                    <span class="avatar" aria-hidden="true"><?= e($initials) ?></span>
                    <span><?= e($adminName) ?></span>
                </span>
            </div>
        </header>

        <div class="admin-content" id="adminContent">
            <div class="loading"><span class="spinner" aria-hidden="true"></span> Loading&hellip;</div>
            <?= csrf_field() ?>
        </div>
    </main>

    <div class="admin-overlay" id="overlay"></div>

    <!-- Image Browser Modal -->
    <div class="img-browser-modal" id="imgBrowserModal" role="dialog" aria-modal="true" aria-label="Browse images">
        <div class="img-browser-backdrop" data-close-browser></div>
        <div class="img-browser-panel">
            <div class="img-browser-header">
                <h2 class="img-browser-title">Browse Images</h2>
                <button class="img-browser-close" data-close-browser aria-label="Close">&times;</button>
            </div>
            <div class="img-browser-toolbar">
                <input type="text" class="form-input img-browser-search" placeholder="Search images..." aria-label="Search images">
                <select class="form-input img-browser-dir-filter" aria-label="Filter by directory">
                    <option value="">All directories</option>
                </select>
                <label class="btn btn-sm btn-outline img-browser-upload-btn">
                    Upload
                    <input type="file" accept="image/*" class="img-browser-upload-input" hidden>
                </label>
            </div>
            <div class="img-browser-grid" id="imgBrowserGrid">
                <div class="img-browser-loading">Loading images...</div>
            </div>
            <div class="img-browser-footer">
                <span class="img-browser-count"></span>
                <button class="btn btn-primary img-browser-select" disabled>Select Image</button>
            </div>
        </div>
    </div>

    <script src="<?= e(url('/admin/js/admin.js')) ?>"></script>
    <script>
        AdminApp.init('<?= e($currentPage) ?>', '<?= e(url('/admin')) ?>');
    </script>
    <script>
    (function(){
        // ── LIST SEARCH (client-side) ──
        document.addEventListener('DOMContentLoaded', function(){
            var search = document.getElementById('list-search');
            if (!search) return;
            search.addEventListener('input', function(){
                var q = this.value.toLowerCase();
                var table = document.querySelector('.data-table');
                if (!table) return;
                var rows = table.querySelectorAll('tbody tr');
                var visible = 0;
                rows.forEach(function(row){
                    var text = row.textContent.toLowerCase();
                    var show = text.indexOf(q) !== -1;
                    row.style.display = show ? '' : 'none';
                    if (show) visible++;
                });
                // Update count badge if present
                var cnt = document.getElementById('list-count');
                if (cnt) cnt.textContent = visible;
            });
        });

        // ── BULK ACTIONS ──
        document.addEventListener('DOMContentLoaded', function(){
            var table = document.querySelector('.data-table');
            var bar = document.getElementById('bulk-bar');
            if (!table || !bar) return;
            var countEl = document.getElementById('bulk-count');
            var actionEl = document.getElementById('bulk-action');
            var applyBtn = document.getElementById('bulk-apply');
            var deselectBtn = document.getElementById('bulk-deselect');
            var entity = applyBtn ? applyBtn.getAttribute('data-entity') : '';

            // Add checkbox column to thead
            var thead = table.querySelector('thead tr');
            if (thead) {
                var th = document.createElement('th');
                th.className = 'bulk-th';
                var cb = document.createElement('input');
                cb.type = 'checkbox'; cb.id = 'select-all';
                cb.setAttribute('aria-label', 'Select all');
                th.appendChild(cb);
                thead.insertBefore(th, thead.firstChild);
            }

            // Add checkbox to each tbody row
            var rows = table.querySelectorAll('tbody tr');
            rows.forEach(function(row){
                var td = document.createElement('td');
                td.className = 'bulk-td';
                var cb = document.createElement('input');
                cb.type = 'checkbox'; cb.className = 'row-check';
                // Find the entity id from a hidden input or data attribute
                var idInput = row.querySelector('input[name="id"]');
                if (idInput) cb.value = idInput.value;
                td.appendChild(cb);
                row.insertBefore(td, row.firstChild);
            });

            function updateBulkBar(){
                var checked = table.querySelectorAll('.row-check:checked');
                var n = checked.length;
                countEl.textContent = n;
                bar.style.display = n > 0 ? 'flex' : 'none';
            }

            // Select-all checkbox
            var selectAll = document.getElementById('select-all');
            if (selectAll) {
                selectAll.addEventListener('change', function(){
                    table.querySelectorAll('.row-check').forEach(function(cb){
                        cb.checked = selectAll.checked;
                    });
                    updateBulkBar();
                });
            }
            // Row checkboxes
            table.addEventListener('change', function(e){
                if (e.target.classList.contains('row-check')) updateBulkBar();
            });
            // Deselect
            if (deselectBtn) {
                deselectBtn.addEventListener('click', function(){
                    table.querySelectorAll('.row-check').forEach(function(cb){ cb.checked = false; });
                    if (selectAll) selectAll.checked = false;
                    updateBulkBar();
                });
            }
            // Apply bulk action
            if (applyBtn) {
                applyBtn.addEventListener('click', function(){
                    var action = actionEl.value;
                    if (!action) { alert('Select an action first.'); return; }
                    var checked = table.querySelectorAll('.row-check:checked');
                    if (!checked.length) { alert('No items selected.'); return; }
                    var ids = [];
                    checked.forEach(function(cb){ ids.push(cb.value); });
                    if (!confirm('Apply "' + action + '" to ' + ids.length + ' item(s)?')) return;

                    var token = document.querySelector('meta[name="csrf_token"]');
                    var csrfVal = token ? token.content : '';
                    var formData = new FormData();
                    formData.append('entity', entity);
                    formData.append('action', 'bulk_' + action);
                    formData.append('ids', ids.join(','));
                    formData.append('csrf_token', csrfVal);

                    fetch(document.body.getAttribute('data-admin-base') + '/api/crud.php', { method: 'POST', body: formData })
                        .then(function(r){ return r.json(); })
                        .then(function(res){
                            if (res.error) { alert('Error: ' + res.error); return; }
                            window.location.reload();
                        })
                        .catch(function(){ alert('Request failed.'); });
                });
            }
        });
    })();
    </script>
</body>
</html>