-- Migration 005: Visibility contract fixes (2026-08-31)
-- These are PHP-level query fixes, not SQL schema changes.
-- Documented here for audit trail.

-- FIX 1: get_page() — added deleted_at IS NULL
-- File: includes/functions.php, line 140
-- Before: WHERE slug = :slug AND is_published = 1
-- After:  WHERE slug = :slug AND is_published = 1 AND deleted_at IS NULL

-- FIX 2: get_apartments() — added visibility window filtering
-- File: includes/functions.php, line 299
-- Before: WHERE is_published = 1 AND deleted_at IS NULL
-- After:  WHERE is_published = 1 AND deleted_at IS NULL
--         AND (visible_from IS NULL OR visible_from <= NOW())
--         AND (visible_until IS NULL OR visible_until >= NOW())

-- FIX 3: get_apartment() — added visibility window filtering
-- File: includes/functions.php, line 325
-- Before: WHERE slug = :slug AND is_published = 1 AND deleted_at IS NULL
-- After:  WHERE slug = :slug AND is_published = 1 AND deleted_at IS NULL
--         AND (visible_from IS NULL OR visible_from <= NOW())
--         AND (visible_until IS NULL OR visible_until >= NOW())

-- ROLLBACK: Revert the three SQL statements above to their original form.
