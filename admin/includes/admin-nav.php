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
            'label' => null,
            'items' => [
                ['label' => 'Dashboard', 'url' => "$base/dashboard", 'path' => '/dashboard', 'icon' => 'dashboard'],
            ],
        ],
        [
            'label' => 'Site & Identity',
            'items' => [
                ['label' => 'Pages',      'url' => "$base/pages",       'path' => '/pages',       'icon' => 'pages'],
                ['label' => 'Navigation', 'url' => "$base/navigation",  'path' => '/navigation',  'icon' => 'navigation'],
                ['label' => 'Settings',   'url' => "$base/settings",    'path' => '/settings',    'icon' => 'settings'],
            ],
        ],
        [
            'label' => 'Structure',
            'items' => [
                ['label' => 'Sections',    'url' => "$base/sections",     'path' => '/sections',     'icon' => 'sections'],
                ['label' => 'Hero Slides', 'url' => "$base/hero-slides",  'path' => '/hero-slides',  'icon' => 'gallery'],
            ],
        ],
        [
            'label' => 'Stays & Inventory',
            'items' => [
                ['label' => 'Apartments', 'url' => "$base/apartments", 'path' => '/apartments', 'icon' => 'apartments'],
                ['label' => 'Dining',     'url' => "$base/dining",     'path' => '/dining',     'icon' => 'sections'],
            ],
        ],
        [
            'label' => 'Experiences',
            'items' => [
                ['label' => 'Safari',      'url' => "$base/safari",      'path' => '/safari',      'icon' => 'safari'],
                ['label' => 'Gallery',     'url' => "$base/gallery",     'path' => '/gallery',     'icon' => 'gallery'],
                ['label' => 'Categories',  'url' => "$base/categories",  'path' => '/categories',  'icon' => 'tags'],
            ],
        ],
        [
            'label' => 'Engagement & Reputation',
            'items' => [
                ['label' => 'Testimonials', 'url' => "$base/testimonials",     'path' => '/testimonials',     'icon' => 'testimonials'],
                ['label' => 'Contact',      'url' => "$base/contact",          'path' => '/contact',          'icon' => 'contact'],
                ['label' => 'Promises',     'url' => "$base/promise-pillars",  'path' => '/promise-pillars',  'icon' => 'sections'],
                ['label' => 'Moments',      'url' => "$base/moments",          'path' => '/moments',          'icon' => 'gallery'],
            ],
        ],
        [
            'label' => 'System & Operations',
            'items' => [
                ['label' => 'FAQs',   'url' => "$base/faqs",              'path' => '/faqs',          'icon' => 'faqs'],
                ['label' => 'Trash',  'url' => "$base/pages?trash=1",     'path' => '/pages',         'icon' => 'trash'],
            ],
        ],
    ];
}
