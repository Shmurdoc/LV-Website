<?php
/**
 * RBAC Helpers — Viata Luxe Admin
 * Lightweight permission system using JSON column on admin_users.
 */

/**
 * All available permission keys with human labels.
 * Grouped by section for the admin UI.
 */
function get_all_permission_defs(): array
{
    return [
        'dashboard' => [
            'label' => 'Dashboard',
            'keys' => [
                'dashboard.read' => 'View dashboard',
            ],
        ],
        'pages' => [
            'label' => 'Pages',
            'keys' => [
                'pages.read'  => 'View pages',
                'pages.write' => 'Create / edit pages',
            ],
        ],
        'sections' => [
            'label' => 'Sections',
            'keys' => [
                'sections.read'  => 'View sections',
                'sections.write' => 'Create / edit sections',
            ],
        ],
        'apartments' => [
            'label' => 'Apartments',
            'keys' => [
                'apartments.read'  => 'View apartments',
                'apartments.write' => 'Create / edit apartments',
            ],
        ],
        'safari' => [
            'label' => 'Safari',
            'keys' => [
                'safari.read'  => 'View safari activities',
                'safari.write' => 'Create / edit safari activities',
            ],
        ],
        'gallery' => [
            'label' => 'Gallery',
            'keys' => [
                'gallery.read'  => 'View gallery',
                'gallery.write' => 'Create / edit gallery',
            ],
        ],
        'categories' => [
            'label' => 'Categories',
            'keys' => [
                'categories.read'  => 'View categories',
                'categories.write' => 'Create / edit categories',
            ],
        ],
        'testimonials' => [
            'label' => 'Testimonials',
            'keys' => [
                'testimonials.read'  => 'View testimonials',
                'testimonials.write' => 'Create / edit testimonials',
            ],
        ],
        'contact' => [
            'label' => 'Contact Submissions',
            'keys' => [
                'contact.read' => 'View contact submissions',
            ],
        ],
        'dining' => [
            'label' => 'Dining',
            'keys' => [
                'dining.read'  => 'View dining items',
                'dining.write' => 'Create / edit dining items',
            ],
        ],
        'hero' => [
            'label' => 'Hero Slides',
            'keys' => [
                'hero.read'  => 'View hero slides',
                'hero.write' => 'Create / edit hero slides',
            ],
        ],
        'promise' => [
            'label' => 'Promise Pillars',
            'keys' => [
                'promise.read'  => 'View promise pillars',
                'promise.write' => 'Create / edit promise pillars',
            ],
        ],
        'moments' => [
            'label' => 'Moments',
            'keys' => [
                'moments.read'  => 'View moments',
                'moments.write' => 'Create / edit moments',
            ],
        ],
        'faqs' => [
            'label' => 'FAQs',
            'keys' => [
                'faqs.read'  => 'View FAQs',
                'faqs.write' => 'Create / edit FAQs',
            ],
        ],
        'system' => [
            'label' => 'System',
            'keys' => [
                'navigation.manage' => 'Manage navigation',
                'settings.manage'   => 'Manage site settings',
                'users.manage'      => 'Manage admin users',
            ],
        ],
    ];
}

/**
 * Flat list of all permission keys.
 */
function get_all_permission_keys(): array
{
    $keys = [];
    foreach (get_all_permission_defs() as $group) {
        foreach ($group['keys'] as $key => $label) {
            $keys[] = $key;
        }
    }
    return $keys;
}

/**
 * Get the current user's permissions from session.
 * Returns array of permission key strings.
 */
function get_user_permissions(): array
{
    return $_SESSION['admin_permissions'] ?? [];
}

/**
 * Check if the current user has a specific permission.
 */
function has_permission(string $key): bool
{
    $perms = get_user_permissions();
    if (in_array($key, $perms, true)) {
        return true;
    }
    // Admin role always has all permissions
    if (($_SESSION['admin_role'] ?? '') === 'admin') {
        return true;
    }
    return false;
}

/**
 * Require a permission — send 403 JSON or redirect with flash.
 */
function require_permission(string $key): void
{
    if (has_permission($key)) {
        return;
    }
    // API requests get JSON
    if (strpos($_SERVER['REQUEST_URI'] ?? '', '/api/') !== false) {
        http_response_code(403);
        echo json_encode(['error' => 'Permission denied', 'required' => $key]);
        exit;
    }
    // Page requests get redirected
    $_SESSION['flash_error'] = "You don't have permission to access that page.";
    header('Location: ' . url('/admin/dashboard'));
    exit;
}

/**
 * Load permissions into session from the database.
 * Called after login.
 */
function load_user_permissions(int $userId): void
{
    try {
        $db = Database::get();
        $stmt = $db->prepare("SELECT role, permissions FROM admin_users WHERE id = ?");
        $stmt->execute([$userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $_SESSION['admin_role'] = $row['role'];
            if ($row['role'] === 'admin') {
                // Admin gets all permissions
                $_SESSION['admin_permissions'] = get_all_permission_keys();
            } elseif ($row['permissions']) {
                $_SESSION['admin_permissions'] = json_decode($row['permissions'], true) ?? [];
            } else {
                $_SESSION['admin_permissions'] = [];
            }
        }
    } catch (\PDOException $e) {
        // Fallback: no permissions loaded, deny non-admin
        $_SESSION['admin_permissions'] = [];
    }
}

/**
 * Save permissions to the database for a user.
 */
function save_user_permissions(int $userId, array $permissions): void
{
    $db = Database::get();
    $stmt = $db->prepare("UPDATE admin_users SET permissions = ? WHERE id = ?");
    $stmt->execute([json_encode($permissions), $userId]);
}

/**
 * Get permissions for a specific user (from DB, not session).
 */
function get_user_permissions_from_db(int $userId): array
{
    $db = Database::get();
    $stmt = $db->prepare("SELECT role, permissions FROM admin_users WHERE id = ?");
    $stmt->execute([$userId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        return [];
    }
    if ($row['role'] === 'admin') {
        return get_all_permission_keys();
    }
    return json_decode($row['permissions'], true) ?? [];
}

/**
 * Map admin nav paths to required permission keys.
 * Used to hide nav items the user can't access.
 */
function get_nav_permission_map(): array
{
    return [
        '/dashboard'      => 'dashboard.read',
        '/pages'          => 'pages.read',
        '/sections'       => 'sections.read',
        '/apartments'     => 'apartments.read',
        '/safari'         => 'safari.read',
        '/gallery'        => 'gallery.read',
        '/categories'     => 'categories.read',
        '/testimonials'   => 'testimonials.read',
        '/contact'        => 'contact.read',
        '/dining'         => 'dining.read',
        '/hero-slides'    => 'hero.read',
        '/promise-pillars' => 'promise.read',
        '/moments'        => 'moments.read',
        '/faqs'           => 'faqs.read',
        '/navigation'     => 'navigation.manage',
        '/settings'       => 'settings.manage',
        '/users'          => 'users.manage',
    ];
}
