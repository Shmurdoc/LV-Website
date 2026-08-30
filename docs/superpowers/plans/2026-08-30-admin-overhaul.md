# Admin Overhaul Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [x]`) syntax for tracking.

**Goal:** Add grouped sidebar navigation, a reusable image browser, a public category taxonomy, and fix all identified bugs/UX issues in the admin panel.

**Architecture:** Modular approach — each feature is isolated in new files with minimal changes to existing files. Image browser is a self-contained modal component. Taxonomy uses a pivot table pattern. Sidebar grouping replaces the flat nav array.

**Tech Stack:** PHP 8.3, MySQL 9.1, vanilla JS (no frameworks), Lucide icons, existing admin design system.

---

## Task 1: Admin Sidebar Grouping

**Files:**
- Create: `admin/includes/admin-nav.php`
- Modify: `admin/layout.php`
- Modify: `admin/css/admin.css`

- [x] **Step 1: Create grouped nav config**

Create `admin/includes/admin-nav.php`:

```php
<?php
/**
 * Admin Navigation — Grouped sidebar configuration.
 * Replaces the flat array from get_admin_nav().
 */

function get_admin_nav_groups(): array
{
    $base = admin_base();

    return [
        [
            'label' => null, // Dashboard has no group label
            'items' => [
                ['label' => 'Dashboard', 'url' => "$base/dashboard", 'path' => '/dashboard', 'icon' => 'dashboard'],
            ],
        ],
        [
            'label' => 'Content',
            'items' => [
                ['label' => 'Pages',       'url' => "$base/pages",          'path' => '/pages',          'icon' => 'pages'],
                ['label' => 'Sections',    'url' => "$base/sections",       'path' => '/sections',       'icon' => 'sections'],
                ['label' => 'Hero Slides', 'url' => "$base/hero-slides",    'path' => '/hero-slides',    'icon' => 'gallery'],
                ['label' => 'Navigation',  'url' => "$base/navigation",     'path' => '/navigation',     'icon' => 'navigation'],
            ],
        ],
        [
            'label' => 'Listings',
            'items' => [
                ['label' => 'Apartments', 'url' => "$base/apartments", 'path' => '/apartments', 'icon' => 'apartments'],
                ['label' => 'Dining',     'url' => "$base/dining",     'path' => '/dining',     'icon' => 'sections'],
                ['label' => 'Safari',     'url' => "$base/safari",     'path' => '/safari',     'icon' => 'safari'],
                ['label' => 'Gallery',    'url' => "$base/gallery",    'path' => '/gallery',    'icon' => 'gallery'],
            ],
        ],
        [
            'label' => 'Engagement',
            'items' => [
                ['label' => 'Testimonials', 'url' => "$base/testimonials",     'path' => '/testimonials',     'icon' => 'testimonials'],
                ['label' => 'Contact',      'url' => "$base/contact",          'path' => '/contact',          'icon' => 'contact'],
                ['label' => 'Promises',     'url' => "$base/promise-pillars",  'path' => '/promise-pillars',  'icon' => 'sections'],
                ['label' => 'Moments',      'url' => "$base/moments",          'path' => '/moments',          'icon' => 'gallery'],
            ],
        ],
        [
            'label' => 'System',
            'items' => [
                ['label' => 'FAQs',      'url' => "$base/faqs",       'path' => '/faqs',       'icon' => 'faqs'],
                ['label' => 'Settings',  'url' => "$base/settings",   'path' => '/settings',   'icon' => 'settings'],
                ['label' => 'Trash',     'url' => "$base/pages?trash=1", 'path' => '/pages',  'icon' => 'trash'],
            ],
        ],
    ];
}
```

- [x] **Step 2: Update layout.php to render grouped sidebar**

Replace lines 34-53 of `admin/layout.php` (the `<nav>` content) with:

```php
        <nav class="sidebar-nav" aria-label="Admin">
            <?php
            $navGroups = get_admin_nav_groups();
            $unreadCount = get_unread_submissions_count();
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
                    // Special case: Trash is active only when trash param is set
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
```

- [x] **Step 3: Add nav group CSS**

Append to `admin/css/admin.css` before the `/* ---------- Main column ---------- */` section:

```css
/* ---------- Nav groups ---------- */
.nav-group {
  margin-top: 4px;
}
.nav-group__label {
  font-size: .64rem;
  font-weight: 700;
  letter-spacing: .14em;
  text-transform: uppercase;
  color: rgba(248,246,241,0.34);
  padding: 12px 12px 4px;
  cursor: pointer;
  list-style: none;
  display: flex;
  align-items: center;
  gap: 6px;
  user-select: none;
}
.nav-group__label::-webkit-details-marker { display: none; }
.nav-group__label::after {
  content: '';
  flex: 1;
  height: 1px;
  background: rgba(255,255,255,0.08);
}
.nav-group:not([open]) .nav-group__items {
  display: none;
}
.nav-group__items {
  display: flex;
  flex-direction: column;
  gap: 2px;
}
```

- [x] **Step 4: Test in browser**

Navigate to `http://localhost/final%20website/admin/dashboard`. Verify:
- Sidebar shows grouped sections with labels
- Groups are collapsible (click label to toggle)
- Active page highlight works correctly
- Trash link is in System group
- Contact badge still shows unread count

- [x] **Step 5: Commit**

```bash
git add admin/includes/admin-nav.php admin/layout.php admin/css/admin.css
git commit -m "feat(admin): group sidebar nav into collapsible sections"
```

---

## Task 2: Image Browser API

**Files:**
- Create: `admin/api/images.php`

- [x] **Step 1: Create image browser API endpoint**

Create `admin/api/images.php`:

```php
<?php
/**
 * Image Browser API — lists directories and files from uploads/ and Luxury Images/.
 * Also handles file upload to uploads/.
 */
require_once __DIR__ . '/../includes/admin-functions.php';
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

header('Content-Type: application/json');

if (!is_admin_logged_in()) {
    json_error('Unauthorized', 401);
}

$action = $_GET['action'] ?? $_POST['action'] ?? 'list';
$csrfToken = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

// CSRF check for mutations
if ($action === 'upload') {
    if (!csrf_verify($csrfToken)) {
        json_error('Invalid CSRF token', 403);
    }
}

$rootPath = ROOT_PATH;
$allowedDirs = ['uploads', 'Luxury Images'];
$allowedExts = ['jpg', 'jpeg', 'png', 'webp'];

switch ($action) {
    case 'list':
        handleList($rootPath, $allowedDirs, $allowedExts);
        break;
    case 'upload':
        handleImageUpload($rootPath, $allowedExts);
        break;
    default:
        json_error('Unknown action');
}

function handleList(string $rootPath, array $allowedDirs, array $allowedExts): void
{
    $dir = $_GET['dir'] ?? 'uploads';
    $dir = str_replace('\\', '/', $dir); // normalize

    // Security: only allow browsing allowed top-level directories
    $topLevel = explode('/', $dir)[0];
    if (!in_array($topLevel, $allowedDirs, true)) {
        json_error('Access denied', 403);
    }

    $fullPath = rtrim($rootPath, '/') . '/' . $dir;

    // Security: prevent directory traversal
    $realPath = realpath($fullPath);
    if ($realPath === false || strpos($realPath, realpath($rootPath)) !== 0) {
        json_error('Invalid directory', 400);
    }

    if (!is_dir($realPath)) {
        json_error('Directory not found', 404);
    }

    $dirs = [];
    $files = [];

    $items = scandir($realPath);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        $itemPath = $realPath . '/' . $item;
        $relativePath = $dir . '/' . $item;

        if (is_dir($itemPath)) {
            $dirs[] = [
                'name' => $item,
                'path' => $relativePath,
                'count' => countDirImages($itemPath, $allowedExts),
            ];
        } else {
            $ext = strtolower(pathinfo($item, PATHINFO_EXTENSION));
            if (in_array($ext, $allowedExts, true)) {
                $files[] = [
                    'name' => $item,
                    'path' => $relativePath,
                    'size' => filesize($itemPath),
                    'modified' => date('Y-m-d H:i:s', filemtime($itemPath)),
                ];
            }
        }
    }

    // Sort: dirs alphabetically, files by modified desc
    usort($dirs, fn($a, $b) => strcmp($a['name'], $b['name']));
    usort($files, fn($a, $b) => strcmp($b['modified'], $a['modified']));

    echo json_encode([
        'success' => true,
        'dir' => $dir,
        'dirs' => $dirs,
        'files' => $files,
    ]);
    exit;
}

function countDirImages(string $path, array $allowedExts): int
{
    $count = 0;
    $items = @scandir($path);
    if (!$items) return 0;
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        $itemPath = $path . '/' . $item;
        if (is_dir($itemPath)) {
            $count += countDirImages($itemPath, $allowedExts);
        } else {
            $ext = strtolower(pathinfo($item, PATHINFO_EXTENSION));
            if (in_array($ext, $allowedExts, true)) $count++;
        }
    }
    return $count;
}

function handleImageUpload(string $rootPath, array $allowedExts): void
{
    if (!isset($_FILES['file'])) {
        json_error('No file uploaded');
    }

    $file = $_FILES['file'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        json_error('Upload error: ' . $file['error']);
    }

    if ($file['size'] > 5 * 1024 * 1024) {
        json_error('File too large (max 5MB)');
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExts, true)) {
        json_error('Invalid file type. Allowed: ' . implode(', ', $allowedExts));
    }

    // Verify it's actually an image
    $imageInfo = @getimagesize($file['tmp_name']);
    if ($imageInfo === false) {
        json_error('File is not a valid image');
    }

    $subDir = $_POST['subdir'] ?? 'general';
    $subDir = preg_replace('/[^a-zA-Z0-9_\-\/]/', '', $subDir); // sanitize

    $uploadDir = $rootPath . '/uploads/' . $subDir;
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $filename = 'img_' . uniqid('', true) . '.' . $ext;
    $dest = $uploadDir . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        json_error('Failed to save file');
    }

    $relativePath = 'uploads/' . $subDir . '/' . $filename;

    echo json_encode([
        'success' => true,
        'path' => $relativePath,
        'name' => $file['name'],
    ]);
    exit;
}
```

- [x] **Step 2: Test list endpoint**

Run in terminal:
```bash
curl -s "http://localhost/final%20website/admin/api/images.php?action=list&dir=uploads" -H "Cookie: $(cat cookies.txt)"
```
Expected: JSON with `dirs` (about, apartments, gallery, hero, safari) and `files` arrays.

- [x] **Step 3: Test nested browsing**

Run:
```bash
curl -s "http://localhost/final%20website/admin/api/images.php?action=list&dir=uploads/apartments" -H "Cookie: $(cat cookies.txt)"
```
Expected: JSON with files array containing apartment images.

- [x] **Step 4: Test security — directory traversal blocked**

Run:
```bash
curl -s "http://localhost/final%20website/admin/api/images.php?action=list&dir=../../etc" -H "Cookie: $(cat cookies.txt)"
```
Expected: `{"error":"Access denied"}` with 403.

- [x] **Step 5: Commit**

```bash
git add admin/api/images.php
git commit -m "feat(admin): add image browser API for directory listing and upload"
```

---

## Task 3: Image Browser Modal (JS + CSS)

**Files:**
- Create: `admin/js/image-browser.js`
- Create: `admin/css/image-browser.css`
- Modify: `admin/layout.php` (add script/link tags)

- [x] **Step 1: Create image browser CSS**

Create `admin/css/image-browser.css`:

```css
/* ---------- Image Browser Modal ---------- */
.ib-overlay {
  position: fixed;
  inset: 0;
  background: rgba(11,26,46,0.6);
  z-index: 2000;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  visibility: hidden;
  transition: opacity .2s, visibility .2s;
}
.ib-overlay.open { opacity: 1; visibility: visible; }

.ib-modal {
  background: var(--admin-surface);
  border-radius: var(--admin-r-lg);
  box-shadow: var(--admin-shadow-3);
  width: 90vw;
  max-width: 1100px;
  height: 80vh;
  max-height: 700px;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  transform: translateY(20px);
  transition: transform .25s cubic-bezier(.16,1,.3,1);
}
.ib-overlay.open .ib-modal { transform: translateY(0); }

.ib-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 20px;
  border-bottom: 1px solid var(--admin-border);
}
.ib-header h3 { margin: 0; font-size: 1rem; }
.ib-close {
  background: none; border: none; cursor: pointer;
  color: var(--admin-muted); font-size: 1.4rem; line-height: 1;
  padding: 4px;
}
.ib-close:hover { color: var(--admin-text); }

.ib-body {
  display: flex;
  flex: 1;
  min-height: 0;
}

.ib-tree {
  width: 240px;
  border-right: 1px solid var(--admin-border);
  overflow-y: auto;
  padding: 12px 0;
  flex-shrink: 0;
}
.ib-tree-item {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 6px 16px;
  cursor: pointer;
  font-size: .82rem;
  color: var(--admin-text-2);
  transition: background var(--admin-fast);
}
.ib-tree-item:hover { background: var(--admin-bg); }
.ib-tree-item.active {
  background: rgba(140,116,52,0.1);
  color: var(--admin-gold);
  font-weight: 600;
}
.ib-tree-item svg { width: 14px; height: 14px; flex-shrink: 0; }
.ib-tree-item .count {
  margin-left: auto;
  font-size: .72rem;
  color: var(--admin-muted);
}
.ib-tree-indent { padding-left: 32px; }

.ib-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  min-width: 0;
}

.ib-grid {
  flex: 1;
  overflow-y: auto;
  padding: 16px;
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
  gap: 12px;
  align-content: start;
}
.ib-thumb {
  aspect-ratio: 1;
  border-radius: var(--admin-r-sm);
  overflow: hidden;
  cursor: pointer;
  border: 2px solid transparent;
  transition: border-color var(--admin-fast), box-shadow var(--admin-fast);
  position: relative;
  background: var(--admin-bg);
}
.ib-thumb:hover { border-color: var(--admin-gold); box-shadow: var(--admin-ring); }
.ib-thumb.selected { border-color: var(--admin-gold); box-shadow: 0 0 0 3px rgba(140,116,52,0.3); }
.ib-thumb img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}
.ib-thumb-name {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background: linear-gradient(transparent, rgba(0,0,0,0.7));
  color: #fff;
  font-size: .68rem;
  padding: 12px 6px 4px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.ib-preview {
  border-top: 1px solid var(--admin-border);
  padding: 12px 16px;
  display: flex;
  align-items: center;
  gap: 16px;
  min-height: 80px;
  background: var(--admin-surface-2);
}
.ib-preview-img {
  width: 60px;
  height: 60px;
  border-radius: var(--admin-r-sm);
  object-fit: cover;
  display: none;
}
.ib-preview-img.visible { display: block; }
.ib-preview-info { flex: 1; font-size: .82rem; color: var(--admin-text-2); }
.ib-preview-info strong { display: block; color: var(--admin-text); margin-bottom: 2px; }

.ib-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 20px;
  border-top: 1px solid var(--admin-border);
  background: var(--admin-surface-2);
}
.ib-selected-path {
  font-size: .82rem;
  color: var(--admin-muted);
  flex: 1;
  margin-right: 16px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.ib-upload-zone {
  border: 2px dashed var(--admin-border);
  border-radius: var(--admin-r-md);
  padding: 24px;
  text-align: center;
  color: var(--admin-muted);
  cursor: pointer;
  transition: border-color var(--admin-fast), background var(--admin-fast);
  margin: 16px;
}
.ib-upload-zone:hover,
.ib-upload-zone.dragover {
  border-color: var(--admin-gold);
  background: rgba(140,116,52,0.05);
}
.ib-upload-zone svg { width: 32px; height: 32px; margin-bottom: 8px; }
.ib-upload-progress {
  margin: 0 16px 16px;
  height: 4px;
  background: var(--admin-bg);
  border-radius: 2px;
  overflow: hidden;
  display: none;
}
.ib-upload-progress.visible { display: block; }
.ib-upload-progress-bar {
  height: 100%;
  background: var(--admin-gold);
  border-radius: 2px;
  transition: width .3s;
  width: 0;
}

.ib-empty {
  grid-column: 1 / -1;
  text-align: center;
  padding: 40px;
  color: var(--admin-muted);
}
```

- [x] **Step 2: Create image browser JavaScript**

Create `admin/js/image-browser.js`:

```javascript
/**
 * Image Browser — reusable modal for selecting images from uploads/ and Luxury Images/.
 * Attach to any input via: <input data-image-browser>
 */
const ImageBrowser = (() => {
  let overlay, modal, treeEl, gridEl, previewImg, previewInfo, selectedPathEl;
  let currentDir = 'uploads';
  let selectedPath = '';
  let targetInput = null;
  let onSelectCallback = null;

  function init() {
    // Build DOM
    overlay = document.createElement('div');
    overlay.className = 'ib-overlay';
    overlay.innerHTML = `
      <div class="ib-modal">
        <div class="ib-header">
          <h3>Image Browser</h3>
          <button class="ib-close" aria-label="Close">&times;</button>
        </div>
        <div class="ib-body">
          <div class="ib-tree" id="ibTree"></div>
          <div class="ib-content">
            <div class="ib-grid" id="ibGrid"></div>
            <div class="ib-preview">
              <img class="ib-preview-img" id="ibPreviewImg" alt="">
              <div class="ib-preview-info" id="ibPreviewInfo">Select an image</div>
            </div>
          </div>
        </div>
        <div class="ib-footer">
          <span class="ib-selected-path" id="ibSelectedPath">No image selected</span>
          <button class="btn btn-sm btn-outline ib-cancel">Cancel</button>
          <button class="btn btn-sm btn-primary ib-select" disabled>Select</button>
        </div>
      </div>
    `;
    document.body.appendChild(overlay);

    treeEl = document.getElementById('ibTree');
    gridEl = document.getElementById('ibGrid');
    previewImg = document.getElementById('ibPreviewImg');
    previewInfo = document.getElementById('ibPreviewInfo');
    selectedPathEl = document.getElementById('ibSelectedPath');

    // Event listeners
    overlay.querySelector('.ib-close').addEventListener('click', close);
    overlay.querySelector('.ib-cancel').addEventListener('click', close);
    overlay.querySelector('.ib-select').addEventListener('click', confirm);
    overlay.addEventListener('click', (e) => { if (e.target === overlay) close(); });
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && overlay.classList.contains('open')) close();
    });

    // Attach to all [data-image-browser] inputs
    document.querySelectorAll('[data-image-browser]').forEach(attach);
  }

  function attach(input) {
    // Wrap input in a group and add Browse button
    const wrapper = document.createElement('div');
    wrapper.className = 'input-group';
    wrapper.style.cssText = 'display:flex;gap:8px;align-items:center;';
    input.parentNode.insertBefore(wrapper, input);
    wrapper.appendChild(input);
    input.style.flex = '1';

    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'btn btn-sm btn-outline';
    btn.textContent = 'Browse';
    btn.addEventListener('click', () => open(input));
    wrapper.appendChild(btn);
  }

  function open(input, callback) {
    targetInput = input;
    onSelectCallback = callback || null;
    selectedPath = input.value || '';
    currentDir = 'uploads';
    updateSelectedPath();
    loadDir(currentDir);
    overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function close() {
    overlay.classList.remove('open');
    document.body.style.overflow = '';
    targetInput = null;
    onSelectCallback = null;
  }

  function confirm() {
    if (targetInput && selectedPath) {
      targetInput.value = selectedPath;
      targetInput.dispatchEvent(new Event('change'));
      if (onSelectCallback) onSelectCallback(selectedPath);
    }
    close();
  }

  function updateSelectedPath() {
    selectedPathEl.textContent = selectedPath || 'No image selected';
    const selectBtn = overlay.querySelector('.ib-select');
    selectBtn.disabled = !selectedPath;
  }

  async function loadDir(dir) {
    currentDir = dir;
    gridEl.innerHTML = '<div class="ib-empty">Loading...</div>';

    try {
      const resp = await fetch(`images.php?action=list&dir=${encodeURIComponent(dir)}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      const data = await resp.json();

      if (!data.success) throw new Error(data.error);

      renderTree(data.dir, data.dirs);
      renderGrid(data.files);
    } catch (err) {
      gridEl.innerHTML = `<div class="ib-empty">Error: ${err.message}</div>`;
    }
  }

  function renderTree(currentDir, dirs) {
    // Build tree HTML
    let html = '';
    // Root level items
    ['uploads', 'Luxury Images'].forEach(root => {
      const isExpanded = currentDir === root || currentDir.startsWith(root + '/');
      html += `<div class="ib-tree-item ${currentDir === root ? 'active' : ''}" data-dir="${root}">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
        ${root}
      </div>`;

      if (isExpanded && currentDir.startsWith(root)) {
        // Show subdirectories of current path
        const parts = currentDir.split('/');
        if (parts.length > 1 && parts[0] === root) {
          // We need to show the tree for the current path
          // For simplicity, show the dirs from the API response as siblings
        }
      }
    });

    // Show current subdirectories
    if (dirs && dirs.length > 0) {
      const indent = currentDir.split('/').length;
      dirs.forEach(d => {
        html += `<div class="ib-tree-item ib-tree-indent" data-dir="${d.path}">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
          ${d.name}
          <span class="count">${d.count}</span>
        </div>`;
      });
    }

    treeEl.innerHTML = html;

    // Attach click handlers
    treeEl.querySelectorAll('.ib-tree-item').forEach(item => {
      item.addEventListener('click', () => loadDir(item.dataset.dir));
    });
  }

  function renderGrid(files) {
    if (files.length === 0) {
      gridEl.innerHTML = '<div class="ib-empty">No images in this folder</div>';
      return;
    }

    gridEl.innerHTML = files.map(f => `
      <div class="ib-thumb ${selectedPath === f.path ? 'selected' : ''}" data-path="${f.path}">
        <img src="../${f.path}" alt="${f.name}" loading="lazy" onerror="this.parentElement.style.display='none'">
        <div class="ib-thumb-name">${f.name}</div>
      </div>
    `).join('');

    gridEl.querySelectorAll('.ib-thumb').forEach(thumb => {
      thumb.addEventListener('click', () => selectImage(thumb.dataset.path, thumb));
      thumb.addEventListener('mouseenter', () => previewImage(thumb.dataset.path, thumb.querySelector('img')?.src));
    });
  }

  function selectImage(path, thumbEl) {
    selectedPath = path;
    updateSelectedPath();
    gridEl.querySelectorAll('.ib-thumb').forEach(t => t.classList.remove('selected'));
    thumbEl.classList.add('selected');
  }

  function previewImage(path, src) {
    previewImg.src = src;
    previewImg.classList.add('visible');
    const name = path.split('/').pop();
    const parts = path.split('/');
    previewInfo.innerHTML = `<strong>${name}</strong>${path}`;
  }

  // Auto-init on DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  return { open, close, attach };
})();
```

- [x] **Step 3: Add CSS and JS to layout.php**

In `admin/layout.php`, add before `</head>`:
```php
    <link rel="stylesheet" href="<?= e(url('/admin/css/image-browser.css')) ?>">
```

Add before `</body>` (before the AdminApp.init script):
```php
    <script src="<?= e(url('/admin/js/image-browser.js')) ?>"></script>
```

- [x] **Step 4: Test modal opens**

Navigate to admin dashboard, open browser console, run:
```javascript
const input = document.createElement('input');
document.body.appendChild(input);
ImageBrowser.open(input);
```
Expected: Modal opens with directory tree showing `uploads` and `Luxury Images`.

- [x] **Step 5: Test directory navigation**

Click `uploads` in tree → should show subdirectories. Click `apartments` → should show apartment images. Click an image → should highlight and show preview.

- [x] **Step 6: Commit**

```bash
git add admin/js/image-browser.js admin/css/image-browser.css admin/layout.php
git commit -m "feat(admin): add image browser modal component"
```

---

## Task 4: Wire Image Browser to Edit Forms

**Files:**
- Modify: `admin/pages/apartment-edit.php`
- Modify: `admin/pages/section-edit.php`
- Modify: `admin/pages/gallery-images.php`
- Modify: `admin/pages/hero-slide-edit.php`
- Modify: `admin/pages/dining-edit.php`
- Modify: `admin/pages/safari-edit.php`
- Modify: `admin/pages/moment-edit.php`

- [x] **Step 1: Add data-image-browser to apartment-edit.php**

In `admin/pages/apartment-edit.php`, find the hero image input and add the attribute:
```html
<input type="text" name="hero_image" value="..." class="form-input" data-image-browser>
```

For apartment images section, find the image path input and add:
```html
<input type="text" name="image_path" class="form-input" data-image-browser>
```

- [x] **Step 2: Add data-image-browser to section-edit.php**

Find the section image input:
```html
<input type="text" name="image" value="..." class="form-input" data-image-browser>
```

- [x] **Step 3: Add data-image-browser to gallery-images.php**

Find the image path input in the add-image form:
```html
<input type="text" name="image_path" class="form-input" data-image-browser>
```

- [x] **Step 4: Add data-image-browser to hero-slide-edit.php, dining-edit.php, safari-edit.php, moment-edit.php**

Same pattern — find the image path input and add `data-image-browser` attribute.

- [x] **Step 5: Test on apartment edit**

Navigate to `/admin/apartments/edit?id=1`. Verify:
- Hero image field has "Browse" button
- Clicking Browse opens image browser modal
- Selecting an image populates the input
- Apartment images section also has Browse buttons

- [x] **Step 6: Commit**

```bash
git add admin/pages/apartment-edit.php admin/pages/section-edit.php admin/pages/gallery-images.php admin/pages/hero-slide-edit.php admin/pages/dining-edit.php admin/pages/safari-edit.php admin/pages/moment-edit.php
git commit -m "feat(admin): wire image browser to all edit forms"
```

---

## Task 5: Public Taxonomy — Database Schema

**Files:**
- Create: `sql/categories.sql`
- Create: `includes/functions.php` additions

- [x] **Step 1: Create migration SQL**

Create `sql/categories.sql`:

```sql
-- Categories taxonomy for apartments, gallery, and safari
CREATE TABLE IF NOT EXISTS categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(120) NOT NULL,
  type ENUM('apartment', 'gallery', 'safari') NOT NULL,
  description TEXT,
  sort_order INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY unique_slug_type (slug, type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS entity_categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT NOT NULL,
  entity_type ENUM('apartment', 'gallery', 'safari') NOT NULL,
  entity_id INT NOT NULL,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
  UNIQUE KEY unique_entity (entity_type, entity_id, category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed default categories
INSERT INTO categories (name, slug, type, sort_order) VALUES
('Luxury', 'luxury', 'apartment', 1),
('Family', 'family', 'apartment', 2),
('Romantic', 'romantic', 'apartment', 3),
('Business', 'business', 'apartment', 4),
('Interior', 'interior', 'gallery', 1),
('Nature', 'nature', 'gallery', 2),
('Activities', 'activities', 'gallery', 3),
('Dining', 'dining', 'gallery', 4),
('Adventure', 'adventure', 'safari', 1),
('Cultural', 'cultural', 'safari', 2),
('Wildlife', 'wildlife', 'safari', 3),
('Scenic', 'scenic', 'safari', 4);
```

- [x] **Step 2: Run migration**

```bash
& "C:\wamp64\bin\mysql\mysql9.1.0\bin\mysql.exe" -u root viata_luxe < "C:\wamp64\www\work\final website\sql\categories.sql"
```

Expected: No errors. Verify with:
```bash
& "C:\wamp64\bin\mysql\mysql9.1.0\bin\mysql.exe" -u root viata_luxe -e "SELECT * FROM categories;"
```

- [x] **Step 3: Add category helper functions to includes/functions.php**

Append to `includes/functions.php`:

```php
/**
 * Get categories by type
 */
function get_categories(string $type): array
{
    $db = Database::get();
    $stmt = $db->prepare('SELECT * FROM categories WHERE type = :type ORDER BY sort_order, name');
    $stmt->execute(['type' => $type]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get categories for an entity
 */
function get_entity_categories(string $entityType, int $entityId): array
{
    $db = Database::get();
    $stmt = $db->prepare('
        SELECT c.* FROM categories c
        JOIN entity_categories ec ON ec.category_id = c.id
        WHERE ec.entity_type = :type AND ec.entity_id = :id
        ORDER BY c.sort_order
    ');
    $stmt->execute(['type' => $entityType, 'id' => $entityId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Set categories for an entity (replaces existing)
 */
function set_entity_categories(string $entityType, int $entityId, array $categoryIds): void
{
    $db = Database::get();
    $stmt = $db->prepare('DELETE FROM entity_categories WHERE entity_type = :type AND entity_id = :id');
    $stmt->execute(['type' => $entityType, 'id' => $entityId]);

    if (empty($categoryIds)) return;

    $stmt = $db->prepare('INSERT INTO entity_categories (category_id, entity_type, entity_id) VALUES (:cat, :type, :id)');
    foreach ($categoryIds as $catId) {
        $stmt->execute(['cat' => (int)$catId, 'type' => $entityType, 'id' => $entityId]);
    }
}

/**
 * Get category slug from URL param
 */
function get_active_category(): ?string
{
    return $_GET['category'] ?? null;
}

/**
 * Filter entities by category slug
 */
function filter_by_category(array $entities, string $entityType, ?string $categorySlug): array
{
    if (!$categorySlug) return $entities;

    $db = Database::get();
    $ids = [];
    $stmt = $db->prepare('
        SELECT ec.entity_id FROM entity_categories ec
        JOIN categories c ON c.id = ec.category_id
        WHERE ec.entity_type = :type AND c.slug = :slug
    ');
    $stmt->execute(['type' => $entityType, 'slug' => $categorySlug]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $ids[] = (int)$row['entity_id'];
    }

    if (empty($ids)) return [];

    return array_filter($entities, fn($e) => in_array((int)$e['id'], $ids));
}
```

- [x] **Step 4: Test helpers via PHP CLI**

Create a temp test script and run:
```php
<?php
require __DIR__ . '/config/app.php';
require __DIR__ . '/config/database.php';
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/functions.php';

$cats = get_categories('apartment');
echo "Apartment categories: " . count($cats) . "\n";
foreach ($cats as $c) echo "  - {$c['name']} ({$c['slug']})\n";
```

Expected: 4 apartment categories listed.

- [x] **Step 5: Commit**

```bash
git add sql/categories.sql includes/functions.php
git commit -m "feat(taxonomy): add categories DB schema and helper functions"
```

---

## Task 6: Public Taxonomy — Admin CRUD

**Files:**
- Create: `admin/pages/categories-list.php`
- Create: `admin/pages/category-edit.php`
- Modify: `admin/api/crud.php`
- Modify: `admin/index.php`

- [x] **Step 1: Create categories list page**

Create `admin/pages/categories-list.php`:

```php
<?php
$db = Database::get();
$stmt = $db->query('SELECT c.*, (SELECT COUNT(*) FROM entity_categories ec WHERE ec.category_id = c.id) AS entity_count FROM categories c ORDER BY c.type, c.sort_order, c.name');
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$grouped = ['apartment' => [], 'gallery' => [], 'safari' => []];
foreach ($categories as $cat) {
    $grouped[$cat['type']][] = $cat;
}
$icons = ['apartment' => 'apartments', 'gallery' => 'gallery', 'safari' => 'safari'];
?>
<div class="admin-page">
  <div class="page-header page-header--spread">
    <div>
      <h2>Categories</h2>
      <p class="muted small">Organize apartments, gallery, and safari with tags</p>
    </div>
    <a href="<?= e(url('/admin/categories/edit')) ?>" class="btn btn-sm btn-primary"><?= admin_icon('plus', 14) ?> New Category</a>
  </div>

  <?php foreach ($grouped as $type => $cats): ?>
    <div class="card card-pad mt-3">
      <h3 class="section-heading--sm"><?= admin_icon($icons[$type], 16) ?> <?= ucfirst($type) ?></h3>
      <?php if (empty($cats)): ?>
        <p class="muted small">No <?= $type ?> categories yet.</p>
      <?php else: ?>
        <table class="data-table">
          <thead><tr><th>Name</th><th>Slug</th><th>Entities</th><th></th></tr></thead>
          <tbody>
          <?php foreach ($cats as $cat): ?>
            <tr>
              <td><strong><?= e($cat['name']) ?></strong></td>
              <td class="muted"><?= e($cat['slug']) ?></td>
              <td><?= (int)$cat['entity_count'] ?></td>
              <td class="text-right">
                <a href="<?= e(url('/admin/categories/edit?id=' . $cat['id'])) ?>" class="btn btn-sm btn-outline">Edit</a>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
</div>
```

- [x] **Step 2: Create category edit page**

Create `admin/pages/category-edit.php`:

```php
<?php
$id = (int)($_GET['id'] ?? 0);
$db = Database::get();
$category = null;

if ($id) {
    $stmt = $db->prepare('SELECT * FROM categories WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$category) { json_error('Category not found', 404); }
}
$isEdit = (bool)$category;
?>
<div class="admin-page">
  <div class="page-header">
    <h2><?= $isEdit ? 'Edit Category' : 'New Category' ?></h2>
  </div>

  <div class="card card-pad">
    <form method="post" action="<?= e(url('/admin/api/crud.php')) ?>" data-ajax>
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="entity" value="category">
      <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= (int)$category['id'] ?>">
      <?php endif; ?>

      <div class="form-group">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-input" required value="<?= e($category['name'] ?? '') ?>">
      </div>

      <div class="form-group mt-2">
        <label class="form-label">Slug</label>
        <input type="text" name="slug" class="form-input" value="<?= e($category['slug'] ?? '') ?>" placeholder="auto-generated from name">
      </div>

      <div class="form-group mt-2">
        <label class="form-label">Type</label>
        <select name="type" class="form-input" required>
          <option value="apartment" <?= ($category['type'] ?? '') === 'apartment' ? 'selected' : '' ?>>Apartment</option>
          <option value="gallery" <?= ($category['type'] ?? '') === 'gallery' ? 'selected' : '' ?>>Gallery</option>
          <option value="safari" <?= ($category['type'] ?? '') === 'safari' ? 'selected' : '' ?>>Safari</option>
        </select>
      </div>

      <div class="form-group mt-2">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-input" rows="3"><?= e($category['description'] ?? '') ?></textarea>
      </div>

      <div class="form-group mt-2">
        <label class="form-label">Sort Order</label>
        <input type="number" name="sort_order" class="form-input" value="<?= (int)($category['sort_order'] ?? 0) ?>">
      </div>

      <div class="form-actions mt-3">
        <a href="<?= e(url('/admin/categories')) ?>" class="btn btn-outline">Cancel</a>
        <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Update' : 'Create' ?></button>
      </div>
    </form>
  </div>
</div>
```

- [x] **Step 3: Add category handler to crud.php**

In `admin/api/crud.php`, add the handler function before the closing `}`:

```php
function handleCategory(array $data): void
{
    $db = Database::get();
    $id = (int)($data['id'] ?? 0);
    $name = trim($data['name'] ?? '');
    $slug = trim($data['slug'] ?? '');
    $type = $data['type'] ?? '';
    $description = trim($data['description'] ?? '');
    $sortOrder = (int)($data['sort_order'] ?? 0);

    if (!$name || !in_array($type, ['apartment', 'gallery', 'safari'])) {
        json_error('Name and valid type are required');
    }

    if (!$slug) {
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
        $slug = trim($slug, '-');
    }

    if ($id) {
        $stmt = $db->prepare('UPDATE categories SET name=:name, slug=:slug, type=:type, description=:desc, sort_order=:sort WHERE id=:id');
        $stmt->execute(['name' => $name, 'slug' => $slug, 'type' => $type, 'desc' => $description, 'sort' => $sortOrder, 'id' => $id]);
    } else {
        $stmt = $db->prepare('INSERT INTO categories (name, slug, type, description, sort_order) VALUES (:name, :slug, :type, :desc, :sort)');
        $stmt->execute(['name' => $name, 'slug' => $slug, 'type' => $type, 'desc' => $description, 'sort' => $sortOrder]);
        $id = (int)$db->lastInsertId();
    }

    log_activity('category', $id, $id ? 'updated' : 'created');
    json_response(['success' => true, 'id' => $id]);
}
```

Also add to the action router in crud.php (near the top where other entities are handled):
```php
} elseif ($entity === 'category') {
    handleCategory($data);
```

- [x] **Step 4: Add routes to admin/index.php**

In the route map in `admin/index.php`, add:
```php
'/categories'          => 'categories-list.php',
'/categories/'         => 'categories-list.php',
'/categories/edit'     => 'category-edit.php',
'/categories/edit/'    => 'category-edit.php',
```

- [x] **Step 5: Add Categories to sidebar nav**

In `admin/includes/admin-nav.php`, add to the System group:

```php
['label' => 'Categories', 'url' => "$base/categories", 'path' => '/categories', 'icon' => 'tags'],
```

- [x] **Step 6: Test admin categories**

Navigate to `/admin/categories`. Verify:
- Shows 3 type groups with seeded categories
- "New Category" button works
- Edit button opens form
- Save creates/updates category

- [x] **Step 7: Commit**

```bash
git add admin/pages/categories-list.php admin/pages/category-edit.php admin/api/crud.php admin/index.php admin/includes/admin-nav.php
git commit -m "feat(taxonomy): add admin category CRUD pages"
```

---

## Task 7: Public Taxonomy — Category Filters on Public Pages

**Files:**
- Modify: `pages/accommodation.php`
- Modify: `pages/gallery.php`
- Create: `js/category-filter.js`

- [x] **Step 1: Create generic category filter JS**

Create `js/category-filter.js`:

```javascript
/**
 * Category Filter — filters elements by data-category attribute.
 * Updates URL params and remembers selection.
 */
document.addEventListener('DOMContentLoaded', () => {
  const filterContainers = document.querySelectorAll('[data-category-filter]');
  filterContainers.forEach(container => {
    const type = container.dataset.categoryFilter;
    const items = document.querySelectorAll(`[data-category]`);
    const buttons = container.querySelectorAll('[data-cat]');

    buttons.forEach(btn => {
      btn.addEventListener('click', () => {
        const slug = btn.dataset.cat;

        // Update active state
        buttons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        // Filter items
        items.forEach(item => {
          if (!slug || slug === 'all') {
            item.style.display = '';
          } else {
            const cats = item.dataset.category.split(',');
            item.style.display = cats.includes(slug) ? '' : 'none';
          }
        });

        // Update URL
        const url = new URL(window.location);
        if (slug && slug !== 'all') {
          url.searchParams.set('category', slug);
        } else {
          url.searchParams.delete('category');
        }
        history.replaceState(null, '', url);

        // Remember selection
        sessionStorage.setItem(`filter_${type}`, slug || 'all');
      });
    });

    // Restore from URL or sessionStorage
    const urlSlug = new URLSearchParams(window.location.search).get('category');
    const savedSlug = sessionStorage.getItem(`filter_${type}`);
    const initialSlug = urlSlug || savedSlug || 'all';

    const initialBtn = container.querySelector(`[data-cat="${initialSlug}"]`)
                    || container.querySelector('[data-cat="all"]');
    if (initialBtn) initialBtn.click();
  });
});
```

- [x] **Step 2: Add filter tabs to accommodation.php**

In `pages/accommodation.php`, after the hero section and before the apartment grid, add:

```php
<?php
$apartments = get_apartments();
$activeCategory = get_active_category();
$apartments = filter_by_category($apartments, 'apartment', $activeCategory);
$categories = get_categories('apartment');
?>

<?php if (!empty($categories)): ?>
<div class="filter-bar" data-category-filter="apartment">
  <button data-cat="all" class="filter-btn active">All</button>
  <?php foreach ($categories as $cat): ?>
    <button data-cat="<?= e($cat['slug']) ?>" class="filter-btn"><?= e($cat['name']) ?></button>
  <?php endforeach;>
</div>
<?php endif; ?>
```

Update the apartment grid items to include `data-category` with their category slugs:

```php
<?php
$entityCats = get_entity_categories('apartment', $apartment['id']);
$catSlugs = array_column($entityCats, 'slug');
?>
<div class="apartment-card" data-category="<?= e(implode(',', $catSlugs)) ?>">
```

- [x] **Step 3: Add filter tab CSS to main.css**

Append to `css/main.css`:

```css
/* ---------- Category Filter ---------- */
.filter-bar {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
  padding: 24px 0;
  justify-content: center;
}
.filter-btn {
  padding: 8px 20px;
  border: 1px solid rgba(140,116,52,0.3);
  border-radius: 999px;
  background: transparent;
  color: var(--text);
  font-size: 0.85rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.25s ease;
  font-family: inherit;
}
.filter-btn:hover {
  border-color: var(--gold);
  color: var(--gold);
}
.filter-btn.active {
  background: var(--gold);
  border-color: var(--gold);
  color: #fff;
}
```

- [x] **Step 4: Add category-filter.js to footer**

In `templates/footer.php`, add before `</body>`:

```html
<script src="<?= e(url('/js/category-filter.js')) ?>"></script>
```

- [x] **Step 5: Add filter tabs to gallery page**

Same pattern as accommodation — after the hero, add filter bar with gallery categories. Add `data-category` to gallery items.

- [x] **Step 6: Test public filters**

Navigate to `/accommodation`. Verify:
- Filter tabs appear: All, Luxury, Family, Romantic, Business
- Clicking a tab filters apartments
- URL updates with `?category=luxury`
- Refreshing preserves filter

- [x] **Step 7: Commit**

```bash
git add js/category-filter.js css/main.css templates/footer.php pages/accommodation.php pages/gallery.php
git commit -m "feat(taxonomy): add public category filter tabs"
```

---

## Task 8: Fix Dashboard Stats

**Files:**
- Modify: `admin/pages/dashboard.php`

- [x] **Step 1: Fix duplicate sections key**

In `admin/pages/dashboard.php`, replace line 13:
```php
['k' => 'sections',     'label' => 'Book Now CTA', 'icon' => 'bed',        'tint' => 'gold'],
```
with:
```php
['k' => 'categories',   'label' => 'Categories',   'icon' => 'tags',       'tint' => 'blue'],
```

Also add to `get_admin_stats()` in `admin/includes/admin-functions.php`:
```php
$stats['categories'] = (int) $db->query('SELECT COUNT(*) FROM categories')->fetchColumn();
```

- [x] **Step 2: Fix "View all" link**

In `dashboard.php`, change line 37:
```php
<a href="/admin/contact" class="btn btn-sm btn-outline">
```
to:
```php
<a href="<?= e(url('/admin/contact')) ?>" class="btn btn-sm btn-outline">
```

- [x] **Step 3: Test dashboard**

Navigate to `/admin/dashboard`. Verify:
- 8 distinct stat cards, no duplicates
- "Categories" card shows correct count
- "View all" link works

- [x] **Step 4: Commit**

```bash
git add admin/pages/dashboard.php admin/includes/admin-functions.php
git commit -m "fix(admin): fix dashboard duplicate stats and broken link"
```

---

## Task 9: Fix Section Edit Form

**Files:**
- Modify: `admin/pages/section-edit.php`

- [x] **Step 1: Add missing form fields**

In `admin/pages/section-edit.php`, find the Orientation section and add the missing fields after the existing ones:

```html
<div class="form-group mt-2">
  <label class="form-label">Background Image</label>
  <input type="text" name="background_image" class="form-input" value="<?= e($section['background_image'] ?? '') ?>" data-image-browser>
</div>

<div class="form-row--gap mt-2">
  <div class="form-group grow">
    <label class="form-label">Padding Top</label>
    <input type="number" name="padding_top" class="form-input" value="<?= (int)($section['padding_top'] ?? 0) ?>">
  </div>
  <div class="form-group grow">
    <label class="form-label">Padding Bottom</label>
    <input type="number" name="padding_bottom" class="form-input" value="<?= (int)($section['padding_bottom'] ?? 0) ?>">
  </div>
</div>

<div class="form-group mt-2">
  <label class="form-label">Max Width (px)</label>
  <input type="number" name="max_width" class="form-input" value="<?= (int)($section['max_width'] ?? 0) ?>" placeholder="0 = full width">
</div>

<div class="form-group mt-2">
  <label class="form-label">Vertical Alignment</label>
  <select name="vertical_alignment" class="form-input">
    <option value="start" <?= ($section['vertical_alignment'] ?? '') === 'start' ? 'selected' : '' ?>>Top</option>
    <option value="center" <?= ($section['vertical_alignment'] ?? '') === 'center' ? 'selected' : '' ?>>Middle</option>
    <option value="end" <?= ($section['vertical_alignment'] ?? '') === 'end' ? 'selected' : '' ?>>Bottom</option>
  </select>
</div>

<div class="form-group mt-2">
  <label class="checkbox-label">
    <input type="checkbox" name="responsive_stack" value="1" <?= !empty($section['responsive_stack']) ? 'checked' : '' ?>>
    Stack vertically on mobile
  </label>
</div>
```

- [x] **Step 2: Test section edit**

Navigate to `/admin/sections/edit?id=1`. Verify:
- All new fields appear in the Orientation section
- Background Image field has Browse button
- Save preserves new field values

- [x] **Step 3: Commit**

```bash
git add admin/pages/section-edit.php
git commit -m "fix(admin): add missing section edit form fields"
```

---

## Task 10: Fix Gallery Images Redirect + Inline Edit

**Files:**
- Modify: `admin/pages/gallery-images.php`

- [x] **Step 1: Fix redirect after delete/restore**

In `admin/api/crud.php`, find the `handleGalleryImage` function. Change the redirect after delete/restore from `/admin/gallery` to include the category_id:

```php
// In the delete/restore sections, change the redirect URL
$categoryId = $data['category_id'] ?? 0;
$redirectUrl = $categoryId ? "/admin/gallery/images?category_id=$categoryId" : '/admin/gallery';
```

- [x] **Step 2: Add inline edit to gallery images**

In `admin/pages/gallery-images.php`, add an edit form that toggles on click:

```html
<div class="gallery-card" data-id="<?= (int)$img['id'] ?>">
  <div class="gallery-thumb">
    <img src="<?= e(image_url($img['image_path'])) ?>" alt="<?= e($img['alt_text']) ?>" class="gallery-img" loading="lazy">
  </div>
  <div class="gallery-caption">
    <div class="gallery-edit-fields" style="display:none;">
      <input type="text" name="alt_text" value="<?= e($img['alt_text']) ?>" class="form-input mb-1" placeholder="Alt text">
      <input type="text" name="caption" value="<?= e($img['caption'] ?? '') ?>" class="form-input mb-1" placeholder="Caption">
      <input type="number" name="sort_order" value="<?= (int)$img['sort_order'] ?>" class="form-input mb-1" placeholder="Sort">
      <label class="checkbox-label mb-1"><input type="checkbox" name="is_featured" value="1" <?= !empty($img['is_featured']) ? 'checked' : '' ?>> Featured</label>
      <div class="flex gap-2">
        <button class="btn btn-sm btn-primary gallery-save" data-id="<?= (int)$img['id'] ?>">Save</button>
        <button class="btn btn-sm btn-outline gallery-cancel">Cancel</button>
      </div>
    </div>
    <div class="gallery-display">
      <strong><?= e($img['alt_text'] ?: 'Untitled') ?></strong>
      <p class="muted small text-wrap"><?= e($img['image_path']) ?></p>
      <div class="flex gap-2 mt-1">
        <button class="btn btn-sm btn-outline gallery-edit-toggle">Edit</button>
        <button class="btn btn-sm btn-outline gallery-delete" data-id="<?= (int)$img['id'] ?>">Delete</button>
      </div>
    </div>
  </div>
</div>
```

- [x] **Step 3: Add JS for inline edit toggle and save**

Add to `admin/js/admin.js` or a new inline script:

```javascript
document.addEventListener('click', (e) => {
  if (e.target.classList.contains('gallery-edit-toggle')) {
    const card = e.target.closest('.gallery-card');
    card.querySelector('.gallery-display').style.display = 'none';
    card.querySelector('.gallery-edit-fields').style.display = 'block';
  }
  if (e.target.classList.contains('gallery-cancel')) {
    const card = e.target.closest('.gallery-card');
    card.querySelector('.gallery-display').style.display = '';
    card.querySelector('.gallery-edit-fields').style.display = 'none';
  }
  if (e.target.classList.contains('gallery-save')) {
    const card = e.target.closest('.gallery-card');
    const id = e.target.dataset.id;
    const form = card.querySelector('.gallery-edit-fields');
    const data = new FormData();
    data.append('action', 'save');
    data.append('entity', 'gallery_image');
    data.append('id', id);
    data.append('alt_text', form.querySelector('[name=alt_text]').value);
    data.append('caption', form.querySelector('[name=caption]').value);
    data.append('sort_order', form.querySelector('[name=sort_order]').value);
    data.append('is_featured', form.querySelector('[name=is_featured]').checked ? '1' : '0');
    data.append('csrf_token', document.querySelector('meta[name=csrf_token]').content);

    fetch('/final website/admin/api/crud.php', { method: 'POST', body: data })
      .then(r => r.json())
      .then(d => {
        if (d.success) location.reload();
        else alert(d.error || 'Save failed');
      });
  }
});
```

- [x] **Step 4: Test gallery images**

Navigate to `/admin/gallery/images?category_id=1`. Verify:
- Edit button shows inline form
- Save updates the image without redirecting away
- Cancel reverts to display mode
- Delete redirects back to same category view

- [x] **Step 5: Commit**

```bash
git add admin/pages/gallery-images.php admin/api/crud.php admin/js/admin.js
git commit -m "fix(admin): fix gallery redirect and add inline image edit"
```

---

## Task 11: Fix Apartment Edit — Features Input + Redirect

**Files:**
- Modify: `admin/pages/apartment-edit.php`

- [x] **Step 1: Replace JSON textarea with structured list input**

In `admin/pages/apartment-edit.php`, find the features textarea and replace with:

```html
<div class="form-group mt-2">
  <label class="form-label">Features / Amenities</label>
  <div id="featuresList">
    <?php
    $features = json_decode($apartment['features'] ?? '[]', true) ?: [];
    foreach ($features as $i => $feat): ?>
      <div class="flex gap-2 mb-1">
        <input type="text" name="features[]" value="<?= e($feat) ?>" class="form-input grow" placeholder="e.g. Sleeps 2">
        <button type="button" class="btn btn-sm btn-outline remove-feature">&times;</button>
      </div>
    <?php endforeach; ?>
    <?php if (empty($features)): ?>
      <div class="flex gap-2 mb-1">
        <input type="text" name="features[]" class="form-input grow" placeholder="e.g. Sleeps 2">
        <button type="button" class="btn btn-sm btn-outline remove-feature">&times;</button>
      </div>
    <?php endif; ?>
  </div>
  <button type="button" class="btn btn-sm btn-outline mt-1" id="addFeature">+ Add feature</button>
</div>
```

Add JS at the bottom of the page:

```javascript
document.getElementById('addFeature').addEventListener('click', () => {
  const list = document.getElementById('featuresList');
  const row = document.createElement('div');
  row.className = 'flex gap-2 mb-1';
  row.innerHTML = `
    <input type="text" name="features[]" class="form-input grow" placeholder="e.g. Sleeps 2">
    <button type="button" class="btn btn-sm btn-outline remove-feature">&times;</button>
  `;
  list.appendChild(row);
  row.querySelector('input').focus();
});
document.addEventListener('click', (e) => {
  if (e.target.classList.contains('remove-feature')) {
    const row = e.target.closest('.flex');
    const list = row.parentElement;
    if (list.children.length > 1) row.remove();
    else row.querySelector('input').value = '';
  }
});
```

- [x] **Step 2: Fix redirect after save**

In the form's save handler (or the JS that processes the form response), change the redirect from `/admin/apartments` to stay on the edit page:

```php
// After successful save, redirect back to edit page
$redirectUrl = $id ? url("/admin/apartments/edit?id=$id") : url('/admin/apartments');
```

- [x] **Step 3: Test apartment edit**

Navigate to `/admin/apartments/edit?id=1`. Verify:
- Features show as individual input rows
- "Add feature" button adds new row
- × button removes row
- Save preserves features as JSON array
- After save, stays on edit page (not list)

- [x] **Step 4: Commit**

```bash
git add admin/pages/apartment-edit.php admin/api/crud.php
git commit -m "fix(admin): replace JSON textarea with structured feature input, fix redirect"
```

---

## Task 12: Extract Inline CSS + Format API Handlers

**Files:**
- Modify: `admin/pages/apartment-edit.php`
- Modify: `admin/pages/gallery-images.php`
- Modify: `admin/login.php`
- Create: `admin/css/login.css`
- Modify: `admin/api/crud.php`

- [x] **Step 1: Replace inline styles in apartment-edit.php**

Replace:
- `style="margin-top:20px"` → `class="mt-3"`
- `style="margin-top:16px"` → `class="mt-2"`
- `style="margin-top:6px"` → `class="mt-1"`
- `style="word-break:break-all"` → `class="text-wrap"`

- [x] **Step 2: Replace inline styles in gallery-images.php**

Replace:
- `style="margin-bottom:20px"` → `class="mb-3"`
- `style="margin-top:8px"` → `class="mt-1"`
- `style="word-break:break-all"` → `class="text-wrap"`

- [x] **Step 3: Extract login.php inline styles**

In `admin/login.php`, move the entire `<style>` block to a new file `admin/css/login.css`.

In `admin/login.php`, replace the `<style>` block with:
```html
<link rel="stylesheet" href="<?= e(url('/admin/css/login.css')) ?>">
```

- [x] **Step 4: Format minified API handlers**

In `admin/api/crud.php`, reformat `handleHeroSlide`, `handlePromisePillar`, `handleMoment`, `handleDiningItem` to match the readable style of earlier handlers (proper indentation, line breaks, comments).

- [x] **Step 5: Fix missing timestamps in crud.php**

In `handleGalleryImage()`, add `created_at` to the INSERT:
```php
$stmt->execute([... 'created_at' => date('Y-m-d H:i:s') ...]);
```

In `handleTestimonial()` update section, add:
```php
$data['updated_at'] = date('Y-m-d H:i:s');
```

- [x] **Step 6: Test all pages**

Navigate through admin panel — verify no visual regressions, login page still styled correctly.

- [x] **Step 7: Commit**

```bash
git add admin/pages/apartment-edit.php admin/pages/gallery-images.php admin/login.php admin/css/login.css admin/api/crud.php
git commit -m "chore(admin): extract inline CSS, format API handlers, fix timestamps"
```

---

## Verification

After all tasks are complete, run the verification script:

```bash
& "C:\wamp64\bin\php\php8.3.14\php.exe" "C:\wamp64\www\work\final website\_final_verify.php"
```

Expected: 41/41 PASS.

Then manually verify:
1. Admin sidebar shows grouped navigation
2. Image browser opens from any edit form
3. Categories CRUD works in admin
4. Public pages show filter tabs
5. Dashboard shows correct stats
6. No inline styles remain in modified files
