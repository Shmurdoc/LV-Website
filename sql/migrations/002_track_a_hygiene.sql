-- Viata Luxe — Track A Hygiene Migration (Builder A)
-- Reversible, idempotent — use IF NOT EXISTS / ADD COLUMN IF NOT EXISTS equivalent via procedure
-- Run: mysql -u root viata_luxe < sql/migrations/002_track_a_hygiene.sql
-- Rollback: see DOWN section at bottom

USE viata_luxe;

-- =====================================================
-- 1. site_settings canonical + global_settings VIEW alias
-- =====================================================
-- Ensure site_settings is the canonical TABLE (if global_settings table existed before, it was renamed)
-- Keep VIEW alias for B/C (functions previously used global_settings, now use site_settings)
CREATE TABLE IF NOT EXISTS site_settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_type ENUM('text','textarea','image','url','email','phone','boolean','json') DEFAULT 'text',
    setting_group VARCHAR(50) NOT NULL DEFAULT 'general',
    sort_order INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_group (setting_group),
    INDEX idx_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- VIEW alias — idempotent drop/create
DROP VIEW IF EXISTS global_settings;
CREATE VIEW global_settings AS
    SELECT id, setting_key, setting_value, setting_type, setting_group, sort_order, created_at, updated_at
    FROM site_settings;

-- =====================================================
-- 2. apartments hygiene columns (tagline, bathrooms, is_featured, features JSON, generated cols)
-- =====================================================
-- Use INFORMATION_SCHEMA checks via ADD COLUMN IF NOT EXISTS pattern (MySQL 8.0.29+ does not support IF NOT EXISTS for ADD COLUMN, so we use procedure)
DELIMITER $$
DROP PROCEDURE IF EXISTS track_a_add_column$$
CREATE PROCEDURE track_a_add_column(IN tbl VARCHAR(64), IN col VARCHAR(64), IN ddl TEXT)
BEGIN
    IF NOT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = tbl AND COLUMN_NAME = col) THEN
        SET @sql = CONCAT('ALTER TABLE `', tbl, '` ADD COLUMN ', ddl);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END$$
DELIMITER ;

CALL track_a_add_column('apartments', 'tagline', '`tagline` VARCHAR(255) NULL DEFAULT NULL AFTER `subtitle`');
CALL track_a_add_column('apartments', 'bathrooms', '`bathrooms` TINYINT UNSIGNED NOT NULL DEFAULT 1 AFTER `bedrooms`');
CALL track_a_add_column('apartments', 'is_featured', '`is_featured` TINYINT(1) NOT NULL DEFAULT 0 AFTER `is_published`');
CALL track_a_add_column('apartments', 'features', '`features` JSON NULL DEFAULT NULL AFTER `bathrooms`');
-- generated columns idempotent (check via same proc)
CALL track_a_add_column('apartments', 'area_sqm', '`area_sqm` DECIMAL(5,1) GENERATED ALWAYS AS (`room_size_m2`) STORED');
CALL track_a_add_column('apartments', 'price_from', '`price_from` DECIMAL(10,2) GENERATED ALWAYS AS (`price_per_night`) STORED');

-- indexes for is_featured (if not exists)
DELIMITER $$
DROP PROCEDURE IF EXISTS track_a_add_index$$
CREATE PROCEDURE track_a_add_index(IN tbl VARCHAR(64), IN idx VARCHAR(64), IN ddl TEXT)
BEGIN
    IF NOT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = tbl AND INDEX_NAME = idx) THEN
        SET @sql = CONCAT('ALTER TABLE `', tbl, '` ADD INDEX `', idx, '` (', ddl, ')');
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END$$
DELIMITER ;
CALL track_a_add_index('apartments', 'idx_featured', 'is_featured');

-- backfill is_featured: set Comfort Apartment 3 as featured (if none featured)
UPDATE apartments SET is_featured = 1 WHERE slug = 'comfort-apartment-3' AND (SELECT COUNT(*) FROM (SELECT 1 FROM apartments WHERE is_featured = 1) t) = 0;

-- backfill features JSON from amenities if null
UPDATE apartments a
SET a.features = (
    SELECT JSON_ARRAYAGG(amenity_name)
    FROM apartment_amenities am
    WHERE am.apartment_id = a.id AND am.deleted_at IS NULL
    ORDER BY am.sort_order
)
WHERE a.features IS NULL;

-- Ensure price fallback data (R950/R950/R1050/R1200) already correct per seed — no overwrite

DROP PROCEDURE IF EXISTS track_a_add_column;
DROP PROCEDURE IF EXISTS track_a_add_index;

-- =====================================================
-- 3. gallery_images.is_featured + seed 8 preview images
-- =====================================================
-- Add column is_featured if not exists (idempotent via proc)
DELIMITER $$
CREATE PROCEDURE tmp_add_gallery_featured()
BEGIN
    IF NOT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'gallery_images' AND COLUMN_NAME = 'is_featured') THEN
        ALTER TABLE gallery_images ADD COLUMN is_featured TINYINT(1) NOT NULL DEFAULT 0 AFTER visible_until, ADD INDEX idx_featured (is_featured);
    END IF;
END$$
DELIMITER ;
CALL tmp_add_gallery_featured();
DROP PROCEDURE tmp_add_gallery_featured;

-- Seed 8 preview images as featured (idempotent: clear then set 8)
UPDATE gallery_images SET is_featured = 0 WHERE is_featured = 1;
-- 8 distinct editorial picks matching H-13 original hardcoded alts
UPDATE gallery_images SET is_featured = 1, sort_order = 1 WHERE image_path = 'Luxury Images/bedrooms/bedroom-chevron-pillows-headboard.jpg' LIMIT 1;
UPDATE gallery_images SET is_featured = 1, sort_order = 2 WHERE image_path = 'Luxury Images/kitchens/kitchen-wood-cabinets-marble-backsplash.jpg' LIMIT 1;
UPDATE gallery_images SET is_featured = 1, sort_order = 3 WHERE image_path = 'Luxury Images/living-rooms/living-room-tv-smart-console.jpg' LIMIT 1;
UPDATE gallery_images SET is_featured = 1, sort_order = 4 WHERE image_path = 'Luxury Images/food-dining/scones-closeup-bowl.jpg' LIMIT 1;
UPDATE gallery_images SET is_featured = 1, sort_order = 5 WHERE image_path = 'Luxury Images/pool/pool-overview-gazebo-garden.jpg' LIMIT 1;
UPDATE gallery_images SET is_featured = 1, sort_order = 6 WHERE image_path = 'Luxury Images/activities/zebra-golden-hour-closeup.jpg' LIMIT 1;
UPDATE gallery_images SET is_featured = 1, sort_order = 7 WHERE image_path = 'Luxury Images/bathrooms/bathroom-1-sink-toilet-yellow-mat.jpg' LIMIT 1;
UPDATE gallery_images SET is_featured = 1, sort_order = 8 WHERE image_path = 'Luxury Images/gallery-scenic/wildlife-buffalo-closeup-herd.jpg' LIMIT 1;

-- Ensure utf8mb4_unicode_ci (hygiene)
ALTER TABLE apartments CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE gallery_images CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE testimonials CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE gallery_categories CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE site_settings CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- =====================================================
-- DOWN (rollback) — manual
-- =====================================================
-- DROP VIEW global_settings;
-- ALTER TABLE apartments DROP COLUMN tagline, DROP COLUMN bathrooms, DROP COLUMN is_featured, DROP COLUMN features, DROP COLUMN area_sqm, DROP COLUMN price_from;
-- ALTER TABLE gallery_images DROP COLUMN is_featured;
-- CREATE TABLE global_settings ... (restore if needed)
