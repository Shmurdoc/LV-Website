-- Migration 008: RBAC — Role-Based Access Control
-- Adds permissions JSON column to admin_users for fine-grained access control.

USE viata_luxe;

-- =====================================================
-- 1. Add permissions column to admin_users
-- =====================================================
-- Permissions stored as JSON array of permission keys.
-- Admin gets all keys; editor gets a curated subset.
-- Keys follow the pattern: "section.action" (e.g. "pages.write", "settings.manage")

ALTER TABLE admin_users
    ADD COLUMN permissions JSON DEFAULT NULL AFTER role;

-- =====================================================
-- 2. Seed default permissions for existing users
-- =====================================================
-- Admin role: all permissions
UPDATE admin_users SET permissions = JSON_ARRAY(
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
    'navigation.manage',
    'settings.manage',
    'users.manage'
) WHERE role = 'admin';

-- Editor role: content read/write, no settings/users/navigation
UPDATE admin_users SET permissions = JSON_ARRAY(
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
    'faqs.read', 'faqs.write'
) WHERE role = 'editor' AND permissions IS NULL;
