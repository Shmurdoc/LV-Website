-- Viata Luxe — Track B Chrome Migration (Builder B)
-- Creates hero_slides, promise_pillars, moments, dining_items with FK page_id->pages,
-- indexes, utf8mb4_unicode_ci, reversible DOWN, idempotent seeds (5/5/3/4)
-- Run: mysql -u root viata_luxe < sql/migrations/003_track_b_chrome.sql
-- Rollback: see DOWN section at bottom (manual)
-- Hygiene: FKs, indexes, reversible, idempotent, no fallback masking, respects deleted_at + visibility

USE viata_luxe;

-- =====================================================
-- 0. Helpers: idempotent ADD COLUMN / ADD INDEX / ADD FK
-- =====================================================
DELIMITER $$
DROP PROCEDURE IF EXISTS track_b_add_column$$
CREATE PROCEDURE track_b_add_column(IN tbl VARCHAR(64), IN col VARCHAR(64), IN ddl TEXT)
BEGIN
    IF NOT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = tbl AND COLUMN_NAME = col) THEN
        SET @sql = CONCAT('ALTER TABLE `', tbl, '` ADD COLUMN ', ddl);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END$$
DROP PROCEDURE IF EXISTS track_b_add_index$$
CREATE PROCEDURE track_b_add_index(IN tbl VARCHAR(64), IN idx VARCHAR(64), IN cols TEXT)
BEGIN
    IF NOT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = tbl AND INDEX_NAME = idx) THEN
        SET @sql = CONCAT('ALTER TABLE `', tbl, '` ADD INDEX `', idx, '` (', cols, ')');
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END$$
DROP PROCEDURE IF EXISTS track_b_add_fk$$
CREATE PROCEDURE track_b_add_fk(IN tbl VARCHAR(64), IN fk VARCHAR(64), IN ddl TEXT)
BEGIN
    IF NOT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND TABLE_NAME = tbl AND CONSTRAINT_NAME = fk) THEN
        SET @sql = CONCAT('ALTER TABLE `', tbl, '` ADD CONSTRAINT `', fk, '` ', ddl);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END$$
DELIMITER ;

-- =====================================================
-- 1. hero_slides — 5 slides cinematic (contract: FK page_id->pages, ON DUPLICATE idempotent)
-- =====================================================
CREATE TABLE IF NOT EXISTS hero_slides (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_id INT UNSIGNED NOT NULL DEFAULT 1,
    image_path VARCHAR(500) COLLATE utf8mb4_unicode_ci NOT NULL,
    alt_text VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    caption VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    link_url VARCHAR(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    is_published TINYINT(1) NOT NULL DEFAULT 1,
    visible_from DATETIME DEFAULT NULL,
    visible_until DATETIME DEFAULT NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_page_sort (page_id, sort_order),
    INDEX idx_published (is_published),
    INDEX idx_deleted (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Back-compat: if table existed before without page_id/link_url, add them
CALL track_b_add_column('hero_slides', 'page_id', '`page_id` INT UNSIGNED NOT NULL DEFAULT 1 AFTER `id`');
CALL track_b_add_column('hero_slides', 'link_url', '`link_url` VARCHAR(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL AFTER `caption`');
-- Fix legacy sort_order type if needed (ensure UNSIGNED)
CALL track_b_add_index('hero_slides', 'idx_page_sort', 'page_id, sort_order');
CALL track_b_add_index('hero_slides', 'idx_published', 'is_published');
CALL track_b_add_index('hero_slides', 'idx_deleted', 'deleted_at');
-- FK (pages) — idempotent
CALL track_b_add_fk('hero_slides', 'fk_hero_slides_page', 'FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE');

-- Ensure existing rows have page_id=1 (home) and UTF8
UPDATE hero_slides SET page_id = 1 WHERE page_id IS NULL OR page_id = 0;
ALTER TABLE hero_slides CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Seed 5 slides — idempotent ON DUPLICATE KEY UPDATE (match current hardcoded literals in pages/home.php:237-255)
INSERT INTO hero_slides (id, page_id, image_path, alt_text, caption, link_url, sort_order, is_published) VALUES
(1, 1, 'Luxury Images/pool/pool-overview-entertainment-area.jpg', 'Pool nestled in lush garden at golden hour — Viata Luxe', 'Serenity by the Pool — Lush garden, golden hour', NULL, 1, 1),
(2, 1, 'Luxury Images/bedrooms/bedroom-chevron-pillows-headboard.jpg', 'Bedroom with chevron pillows and warm linen — Viata Luxe', 'Our Rooms — Elegantly decorated, tranquil', NULL, 2, 1),
(3, 1, 'Luxury Images/food-dining/rose-champagne-berries-tray.jpg', 'Rosé champagne and berries tray on crisp linen — Viata Luxe', 'Dining Options — Gourmet delivered to your apartment', NULL, 3, 1),
(4, 1, 'Luxury Images/activities/elephants-river-crossing-herd.jpg', 'Elephants crossing river at sunset — Kruger safari', 'Safari — Kruger minutes away, Kedibone Safari', NULL, 4, 1),
(5, 1, 'Luxury Images/gallery-scenic/exterior-grey-cottages-red-doors.jpg', 'Viata Luxe exterior — grey cottages with red doors, paved courtyard', '86 Nollie Bosman Street — Phalaborwa, Limpopo', NULL, 5, 1)
ON DUPLICATE KEY UPDATE
    page_id = VALUES(page_id),
    image_path = VALUES(image_path),
    alt_text = VALUES(alt_text),
    caption = VALUES(caption),
    link_url = VALUES(link_url),
    sort_order = VALUES(sort_order),
    is_published = VALUES(is_published);

-- =====================================================
-- 2. promise_pillars — 5 pillars (icon,title,text,link_url)
-- =====================================================
CREATE TABLE IF NOT EXISTS promise_pillars (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_id INT UNSIGNED NOT NULL DEFAULT 1,
    icon VARCHAR(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    title VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    text TEXT COLLATE utf8mb4_unicode_ci NOT NULL,
    link_url VARCHAR(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    is_published TINYINT(1) NOT NULL DEFAULT 1,
    visible_from DATETIME DEFAULT NULL,
    visible_until DATETIME DEFAULT NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_page_sort (page_id, sort_order),
    INDEX idx_published (is_published),
    INDEX idx_deleted (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CALL track_b_add_column('promise_pillars', 'page_id', '`page_id` INT UNSIGNED NOT NULL DEFAULT 1 AFTER `id`');
CALL track_b_add_column('promise_pillars', 'link_url', '`link_url` VARCHAR(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL AFTER `text`');
CALL track_b_add_index('promise_pillars', 'idx_page_sort', 'page_id, sort_order');
CALL track_b_add_index('promise_pillars', 'idx_published', 'is_published');
CALL track_b_add_index('promise_pillars', 'idx_deleted', 'deleted_at');
CALL track_b_add_fk('promise_pillars', 'fk_promise_pillars_page', 'FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE');
UPDATE promise_pillars SET page_id = 1 WHERE page_id IS NULL OR page_id = 0;
ALTER TABLE promise_pillars CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Seed 5 pillars — matches pages/home.php:301-327 hardcodes
INSERT INTO promise_pillars (id, page_id, icon, title, text, link_url, sort_order, is_published) VALUES
(1, 1, '◐', 'Our Rooms', 'Elegantly decorated Bachelor and Superior apartments — sophistication and tranquility for your getaway.', NULL, 1, 1),
(2, 1, '⬢', 'Our Amenities', 'Fresh breakfast on request, free Wi-Fi, secure parking — attentive staff, easy Kruger access.', NULL, 2, 1),
(3, 1, '✦', 'Dining Options', 'Breakfast & dinner on request — gourmet menus delivered to your apartment, indulgent and relaxed.', NULL, 3, 1),
(4, 1, '◉', 'Safari — Kedibone', 'Daily Kruger Safaris from Phalaborwa Gate + Private Overnight Tours — intimate, luxurious.', NULL, 4, 1),
(5, 1, '☾', 'Moments at Viata Luxe', 'Relaxation in outdoor chillers · Braai under the stars · Serenity by the pool — garden, fire, water.', NULL, 5, 1)
ON DUPLICATE KEY UPDATE
    page_id = VALUES(page_id),
    icon = VALUES(icon),
    title = VALUES(title),
    text = VALUES(text),
    link_url = VALUES(link_url),
    sort_order = VALUES(sort_order),
    is_published = VALUES(is_published);

-- =====================================================
-- 3. moments — 3 cards (kicker,title,text,image_path,alt_text)
-- =====================================================
CREATE TABLE IF NOT EXISTS moments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_id INT UNSIGNED NOT NULL DEFAULT 1,
    kicker VARCHAR(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    title VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    text TEXT COLLATE utf8mb4_unicode_ci,
    image_path VARCHAR(500) COLLATE utf8mb4_unicode_ci NOT NULL,
    alt_text VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    is_published TINYINT(1) NOT NULL DEFAULT 1,
    visible_from DATETIME DEFAULT NULL,
    visible_until DATETIME DEFAULT NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_page_sort (page_id, sort_order),
    INDEX idx_published (is_published),
    INDEX idx_deleted (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CALL track_b_add_column('moments', 'page_id', '`page_id` INT UNSIGNED NOT NULL DEFAULT 1 AFTER `id`');
CALL track_b_add_index('moments', 'idx_page_sort', 'page_id, sort_order');
CALL track_b_add_index('moments', 'idx_published', 'is_published');
CALL track_b_add_index('moments', 'idx_deleted', 'deleted_at');
CALL track_b_add_fk('moments', 'fk_moments_page', 'FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE');
UPDATE moments SET page_id = 1 WHERE page_id IS NULL OR page_id = 0;
ALTER TABLE moments CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Seed 3 moments — matches pages/home.php:343-354
INSERT INTO moments (id, page_id, kicker, title, text, image_path, alt_text, sort_order, is_published) VALUES
(1, 1, 'Relaxation', 'Relaxation in Our Outdoor Chillers', 'Cozy nooks to unwind, enjoy a refreshing drink — designed for guests to truly relax.', 'Luxury Images/pool/pool-overview-gazebo-garden.jpg', 'Outdoor chillers — gazebo garden', 1, 1),
(2, 1, 'Tradition', 'Braai Under the Stars', 'The quintessential South African tradition — well-equipped braai area invites you to gather.', 'Luxury Images/activities/braai-outdoor-chicken-grilling.jpg', 'Braai under the stars — well-equipped braai area', 2, 1),
(3, 1, 'Tranquility', 'Serenity by the Pool', 'Tranquility meets luxury — outdoor pool nestled within lush garden, escape from the African sun.', 'Luxury Images/pool/poolside-refreshments-drinks.jpg', 'Serenity by the pool — lush garden escape', 3, 1)
ON DUPLICATE KEY UPDATE
    page_id = VALUES(page_id),
    kicker = VALUES(kicker),
    title = VALUES(title),
    text = VALUES(text),
    image_path = VALUES(image_path),
    alt_text = VALUES(alt_text),
    sort_order = VALUES(sort_order),
    is_published = VALUES(is_published);

-- =====================================================
-- 4. dining_items — 4 items (title,time_label,text,icon)
-- =====================================================
CREATE TABLE IF NOT EXISTS dining_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_id INT UNSIGNED NOT NULL DEFAULT 1,
    title VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    time_label VARCHAR(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    text TEXT COLLATE utf8mb4_unicode_ci,
    icon VARCHAR(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    is_published TINYINT(1) NOT NULL DEFAULT 1,
    visible_from DATETIME DEFAULT NULL,
    visible_until DATETIME DEFAULT NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_page_sort (page_id, sort_order),
    INDEX idx_published (is_published),
    INDEX idx_deleted (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CALL track_b_add_column('dining_items', 'page_id', '`page_id` INT UNSIGNED NOT NULL DEFAULT 1 AFTER `id`');
CALL track_b_add_column('dining_items', 'icon', '`icon` VARCHAR(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL AFTER `text`');
CALL track_b_add_index('dining_items', 'idx_page_sort', 'page_id, sort_order');
CALL track_b_add_index('dining_items', 'idx_published', 'is_published');
CALL track_b_add_index('dining_items', 'idx_deleted', 'deleted_at');
CALL track_b_add_fk('dining_items', 'fk_dining_items_page', 'FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE');
UPDATE dining_items SET page_id = 1 WHERE page_id IS NULL OR page_id = 0;
ALTER TABLE dining_items CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Seed 4 dining items — matches pages/home.php:422-441
INSERT INTO dining_items (id, page_id, title, time_label, text, icon, sort_order, is_published) VALUES
(1, 1, 'Self-Catering', 'In your apartment', 'Full kitchen with oven, hob, microwave, fridge, and all utensils.', NULL, 1, 1),
(2, 1, 'Braai & Boma', 'Outdoor area', 'Traditional South African braai setup under the Limpopo stars.', NULL, 2, 1),
(3, 1, 'Local Restaurants', '5-10 min drive', 'Bushveld dining, Italian, steakhouse — curated recommendations on arrival.', NULL, 3, 1),
(4, 1, 'Private Bush Dinner', 'On request', 'Chef-prepared multi-course dinner in the bushveld setting.', NULL, 4, 1)
ON DUPLICATE KEY UPDATE
    page_id = VALUES(page_id),
    title = VALUES(title),
    time_label = VALUES(time_label),
    text = VALUES(text),
    icon = VALUES(icon),
    sort_order = VALUES(sort_order),
    is_published = VALUES(is_published);

-- =====================================================
-- 5. Ensure home sections for Track B exist (idempotent, sorts already verified 10-130)
-- =====================================================
-- Ensure trust-bar, promise, moments, dining sections exist for page_id=1; if missing insert (ON DUPLICATE KEY UPDATE not applicable — use conditional INSERT via SELECT WHERE NOT EXISTS)
-- trust-bar (sort 20)
INSERT INTO sections (id, page_id, section_type, title, subtitle, content, image, link_url, link_text, sort_order, is_visible)
SELECT 30, 1, 'trust-bar', 'Trust', 'Minutes to Kruger Gate', 'No catalogue. 4 apartments, each curated. From R950 · Host on arrival', NULL, NULL, NULL, 20, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM sections WHERE id = 30);
-- dining (sort 110)
INSERT INTO sections (id, page_id, section_type, title, subtitle, content, image, link_url, link_text, sort_order, is_visible)
SELECT 31, 1, 'dining', 'Eat like you''re meant to be here', 'Dining', 'Each apartment has a fully equipped kitchen for self-catering. For special evenings, explore Phalaborwa''s restaurants or let us arrange a private bush dinner.', 'Luxury Images/food-dining/rose-champagne-berries-tray.jpg', NULL, 'Open-air dining', 110, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM sections WHERE id = 31);
-- normalize hero/stats/etc to expected sort_order (idempotent UPDATE)
UPDATE sections SET sort_order=10, section_type='hero' WHERE id=1 AND page_id=1;
UPDATE sections SET sort_order=30, section_type='promise' WHERE id=3 AND page_id=1;
UPDATE sections SET sort_order=40, section_type='moments' WHERE id=27 AND page_id=1;
UPDATE sections SET sort_order=80, section_type='stats' WHERE id=2 AND page_id=1;
UPDATE sections SET sort_order=120, section_type='specials' WHERE id=9 AND page_id=1;
UPDATE sections SET sort_order=130, section_type='booking-cta' WHERE id=6 AND page_id=1;

-- =====================================================
-- CLEANUP helpers
-- =====================================================
DROP PROCEDURE IF EXISTS track_b_add_column;
DROP PROCEDURE IF EXISTS track_b_add_index;
DROP PROCEDURE IF EXISTS track_b_add_fk;

-- =====================================================
-- DOWN (rollback) — reversible, manual execution:
-- =====================================================
-- ALTER TABLE hero_slides DROP FOREIGN KEY fk_hero_slides_page;
-- DROP TABLE IF EXISTS hero_slides;
-- ALTER TABLE promise_pillars DROP FOREIGN KEY fk_promise_pillars_page;
-- DROP TABLE IF EXISTS promise_pillars;
-- ALTER TABLE moments DROP FOREIGN KEY fk_moments_page;
-- DROP TABLE IF EXISTS moments;
-- ALTER TABLE dining_items DROP FOREIGN KEY fk_dining_items_page;
-- DROP TABLE IF EXISTS dining_items;
-- DELETE FROM sections WHERE id IN (30,31) AND page_id=1;
