-- Viata Luxe — Phase 1 Migration: Soft-delete + Visibility Scheduling
-- Run: mysql -u root viata_luxe < sql/migrations/001_soft_delete_visibility.sql

-- =====================================================
-- 1. SOFT-DELETE: Add deleted_at to all content tables
-- =====================================================
ALTER TABLE pages
  ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL AFTER updated_at,
  ADD INDEX idx_deleted (deleted_at);

ALTER TABLE sections
  ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL AFTER updated_at,
  ADD INDEX idx_deleted (deleted_at);

ALTER TABLE apartments
  ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL AFTER updated_at,
  ADD INDEX idx_deleted (deleted_at);

ALTER TABLE apartment_images
  ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL AFTER created_at,
  ADD INDEX idx_deleted (deleted_at);

ALTER TABLE apartment_amenities
  ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL AFTER sort_order,
  ADD INDEX idx_deleted (deleted_at);

ALTER TABLE testimonials
  ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL AFTER created_at,
  ADD INDEX idx_deleted (deleted_at);

ALTER TABLE faqs
  ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL AFTER updated_at,
  ADD INDEX idx_deleted (deleted_at);

ALTER TABLE gallery_categories
  ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL AFTER created_at,
  ADD INDEX idx_deleted (deleted_at);

ALTER TABLE gallery_images
  ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL AFTER created_at,
  ADD INDEX idx_deleted (deleted_at);

ALTER TABLE contact_submissions
  ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL AFTER created_at,
  ADD INDEX idx_deleted (deleted_at);

ALTER TABLE navigation
  ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL AFTER created_at,
  ADD INDEX idx_deleted (deleted_at);

ALTER TABLE safari_activities
  ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL AFTER updated_at,
  ADD INDEX idx_deleted (deleted_at);

ALTER TABLE page_seo
  ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL AFTER updated_at,
  ADD INDEX idx_deleted (deleted_at);

-- =====================================================
-- 2. VISIBILITY SCHEDULING: Add visible_from/visible_until
-- =====================================================
ALTER TABLE pages
  ADD COLUMN visible_from DATETIME NULL DEFAULT NULL AFTER is_homepage,
  ADD COLUMN visible_until DATETIME NULL DEFAULT NULL AFTER visible_from;

ALTER TABLE sections
  ADD COLUMN visible_from DATETIME NULL DEFAULT NULL AFTER is_visible,
  ADD COLUMN visible_until DATETIME NULL DEFAULT NULL AFTER visible_from;

ALTER TABLE apartments
  ADD COLUMN visible_from DATETIME NULL DEFAULT NULL AFTER is_published,
  ADD COLUMN visible_until DATETIME NULL DEFAULT NULL AFTER visible_from;

ALTER TABLE testimonials
  ADD COLUMN visible_from DATETIME NULL DEFAULT NULL AFTER is_published,
  ADD COLUMN visible_until DATETIME NULL DEFAULT NULL AFTER visible_from;

ALTER TABLE faqs
  ADD COLUMN visible_from DATETIME NULL DEFAULT NULL AFTER is_published,
  ADD COLUMN visible_until DATETIME NULL DEFAULT NULL AFTER visible_from;

ALTER TABLE gallery_categories
  ADD COLUMN visible_from DATETIME NULL DEFAULT NULL AFTER is_published,
  ADD COLUMN visible_until DATETIME NULL DEFAULT NULL AFTER visible_from;

ALTER TABLE gallery_images
  ADD COLUMN visible_from DATETIME NULL DEFAULT NULL AFTER sort_order,
  ADD COLUMN visible_until DATETIME NULL DEFAULT NULL AFTER visible_from;

ALTER TABLE contact_submissions
  ADD COLUMN visible_from DATETIME NULL DEFAULT NULL AFTER deleted_at,
  ADD COLUMN visible_until DATETIME NULL DEFAULT NULL AFTER visible_from;

ALTER TABLE navigation
  ADD COLUMN visible_from DATETIME NULL DEFAULT NULL AFTER is_published,
  ADD COLUMN visible_until DATETIME NULL DEFAULT NULL AFTER visible_from;

ALTER TABLE safari_activities
  ADD COLUMN visible_from DATETIME NULL DEFAULT NULL AFTER is_published,
  ADD COLUMN visible_until DATETIME NULL DEFAULT NULL AFTER visible_from;
