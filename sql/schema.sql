-- Viata Luxe Guesthouse — Database Schema
-- MySQL 8.0+ / utf8mb4
-- Canonical source: sql/schema-production.sql (synced 2026-08-31)

CREATE DATABASE IF NOT EXISTS viata_luxe
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE viata_luxe;

-- =====================================================
-- TABLE: site_settings (canonical settings store)
-- =====================================================
CREATE TABLE IF NOT EXISTS site_settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL,
    setting_value TEXT,
    setting_type ENUM('text','textarea','image','url','email','phone','boolean','json') DEFAULT 'text',
    setting_group VARCHAR(50) NOT NULL DEFAULT 'general',
    sort_order INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY setting_key (setting_key),
    INDEX idx_group (setting_group),
    INDEX idx_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- VIEW: global_settings (backwards-compat wrapper)
-- =====================================================
CREATE OR REPLACE VIEW global_settings AS
SELECT id, setting_key, setting_value, setting_type, setting_group,
       sort_order, created_at, updated_at
FROM site_settings;

-- =====================================================
-- TABLE: pages
-- =====================================================
CREATE TABLE IF NOT EXISTS pages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) NOT NULL,
    title VARCHAR(255) NOT NULL,
    subtitle VARCHAR(255) DEFAULT NULL,
    meta_title VARCHAR(255) DEFAULT NULL,
    meta_description TEXT,
    og_image VARCHAR(500) DEFAULT NULL,
    canonical_url VARCHAR(500) DEFAULT NULL,
    hero_image VARCHAR(500) DEFAULT NULL,
    hero_kicker VARCHAR(255) DEFAULT NULL,
    hero_title VARCHAR(255) DEFAULT NULL,
    hero_lead TEXT,
    hero_align ENUM('left','center') DEFAULT 'left',
    template VARCHAR(50) NOT NULL DEFAULT 'default',
    is_published BOOLEAN DEFAULT TRUE,
    is_homepage BOOLEAN DEFAULT FALSE,
    visible_from DATETIME NULL DEFAULT NULL,
    visible_until DATETIME NULL DEFAULT NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY slug (slug),
    INDEX idx_slug (slug),
    INDEX idx_published (is_published),
    INDEX idx_sort (sort_order),
    INDEX idx_deleted (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: sections
-- =====================================================
CREATE TABLE IF NOT EXISTS sections (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_id INT UNSIGNED NOT NULL,
    section_type VARCHAR(50) NOT NULL,
    title VARCHAR(255) DEFAULT NULL,
    subtitle VARCHAR(255) DEFAULT NULL,
    content LONGTEXT,
    image VARCHAR(500) DEFAULT NULL,
    link_url VARCHAR(500) DEFAULT NULL,
    link_text VARCHAR(100) DEFAULT NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    is_visible BOOLEAN DEFAULT TRUE,
    visible_from DATETIME NULL DEFAULT NULL,
    visible_until DATETIME NULL DEFAULT NULL,
    css_class VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    INDEX idx_page_sort (page_id, sort_order),
    INDEX idx_type (section_type),
    INDEX idx_deleted (deleted_at),
    CONSTRAINT sections_ibfk_1 FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: section_orientation
-- =====================================================
CREATE TABLE IF NOT EXISTS section_orientation (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    section_id INT UNSIGNED NOT NULL,
    layout ENUM('text-left','text-right','text-top','image-top','text-only','image-only','full-width','centered','grid-2','grid-3','grid-4') DEFAULT 'text-left',
    background_color VARCHAR(20) DEFAULT NULL,
    background_image VARCHAR(500) DEFAULT NULL,
    text_color VARCHAR(20) DEFAULT NULL,
    padding_top VARCHAR(10) DEFAULT '4rem',
    padding_bottom VARCHAR(10) DEFAULT '4rem',
    padding_left VARCHAR(10) DEFAULT '2rem',
    padding_right VARCHAR(10) DEFAULT '2rem',
    max_width VARCHAR(10) DEFAULT '1200px',
    alignment ENUM('left','center','right') DEFAULT 'left',
    vertical_alignment ENUM('top','center','bottom') DEFAULT 'center',
    animation VARCHAR(50) DEFAULT 'fade-up',
    responsive_stack ENUM('stack','hide-image','hide-text') DEFAULT 'stack',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY section_id (section_id),
    CONSTRAINT section_orientation_ibfk_1 FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: public_categories (unified taxonomy)
-- =====================================================
CREATE TABLE IF NOT EXISTS public_categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    entity_type ENUM('apartment','gallery','safari') NOT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    description TEXT,
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_entity_slug (entity_type, slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: apartments
-- =====================================================
CREATE TABLE IF NOT EXISTS apartments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_id INT UNSIGNED NOT NULL,
    category_id INT UNSIGNED DEFAULT NULL,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL,
    subtitle VARCHAR(255) DEFAULT NULL,
    tagline VARCHAR(255) DEFAULT NULL,
    description TEXT,
    price_per_night DECIMAL(10,2) NOT NULL,
    price_currency VARCHAR(3) DEFAULT 'ZAR',
    max_guests INT UNSIGNED DEFAULT 2,
    room_size_m2 DECIMAL(5,1) DEFAULT NULL,
    bedrooms INT UNSIGNED DEFAULT 1,
    bathrooms TINYINT UNSIGNED NOT NULL DEFAULT 1,
    features JSON DEFAULT NULL,
    beds_description VARCHAR(255) DEFAULT NULL,
    hero_image VARCHAR(500) DEFAULT NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    is_published BOOLEAN DEFAULT TRUE,
    is_featured BOOLEAN NOT NULL DEFAULT FALSE,
    visible_from DATETIME NULL DEFAULT NULL,
    visible_until DATETIME NULL DEFAULT NULL,
    meta_title VARCHAR(255) DEFAULT NULL,
    meta_description TEXT,
    og_image VARCHAR(500) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    area_sqm DECIMAL(5,1) GENERATED ALWAYS AS (room_size_m2) STORED,
    price_from DECIMAL(10,2) GENERATED ALWAYS AS (price_per_night) STORED,
    UNIQUE KEY slug (slug),
    INDEX idx_slug (slug),
    INDEX idx_sort (sort_order),
    INDEX idx_deleted (deleted_at),
    INDEX idx_featured (is_featured),
    INDEX fk_apt_category (category_id),
    CONSTRAINT apartments_ibfk_1 FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE,
    CONSTRAINT fk_apt_category FOREIGN KEY (category_id) REFERENCES public_categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: apartment_images
-- =====================================================
CREATE TABLE IF NOT EXISTS apartment_images (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    apartment_id INT UNSIGNED NOT NULL,
    image_path VARCHAR(500) NOT NULL,
    alt_text VARCHAR(255) DEFAULT NULL,
    caption VARCHAR(255) DEFAULT NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    is_hero BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    INDEX idx_apt_sort (apartment_id, sort_order),
    INDEX idx_deleted (deleted_at),
    CONSTRAINT apartment_images_ibfk_1 FOREIGN KEY (apartment_id) REFERENCES apartments(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: apartment_amenities
-- =====================================================
CREATE TABLE IF NOT EXISTS apartment_amenities (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    apartment_id INT UNSIGNED NOT NULL,
    amenity_name VARCHAR(255) NOT NULL,
    amenity_icon VARCHAR(100) DEFAULT NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    INDEX idx_apt (apartment_id),
    INDEX idx_deleted (deleted_at),
    CONSTRAINT apartment_amenities_ibfk_1 FOREIGN KEY (apartment_id) REFERENCES apartments(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: testimonials
-- =====================================================
CREATE TABLE IF NOT EXISTS testimonials (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    apartment_id INT UNSIGNED DEFAULT NULL,
    reviewer_name VARCHAR(255) NOT NULL,
    review_text TEXT NOT NULL,
    rating TINYINT UNSIGNED DEFAULT 5,
    source VARCHAR(50) DEFAULT 'direct',
    is_featured BOOLEAN DEFAULT FALSE,
    is_published BOOLEAN DEFAULT TRUE,
    visible_from DATETIME NULL DEFAULT NULL,
    visible_until DATETIME NULL DEFAULT NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    INDEX idx_apt (apartment_id),
    INDEX idx_featured (is_featured),
    INDEX idx_sort (sort_order),
    INDEX idx_deleted (deleted_at),
    CONSTRAINT testimonials_ibfk_1 FOREIGN KEY (apartment_id) REFERENCES apartments(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: faqs
-- =====================================================
CREATE TABLE IF NOT EXISTS faqs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_id INT UNSIGNED DEFAULT NULL,
    question VARCHAR(500) NOT NULL,
    answer TEXT NOT NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    is_published BOOLEAN DEFAULT TRUE,
    visible_from DATETIME NULL DEFAULT NULL,
    visible_until DATETIME NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    INDEX idx_page (page_id),
    INDEX idx_sort (sort_order),
    INDEX idx_deleted (deleted_at),
    CONSTRAINT faqs_ibfk_1 FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: gallery_categories → ARCHIVED (use public_categories with entity_type='gallery')
-- =====================================================

-- =====================================================
-- TABLE: gallery_images
-- =====================================================
CREATE TABLE IF NOT EXISTS gallery_images (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    public_category_id INT UNSIGNED NOT NULL,
    image_path VARCHAR(500) NOT NULL,
    alt_text VARCHAR(255) DEFAULT NULL,
    caption VARCHAR(255) DEFAULT NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    visible_from DATETIME NULL DEFAULT NULL,
    visible_until DATETIME NULL DEFAULT NULL,
    is_featured BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    INDEX idx_cat_sort (public_category_id, sort_order),
    INDEX idx_deleted (deleted_at),
    INDEX idx_featured (is_featured),
    INDEX idx_cat_featured_sort (public_category_id, is_featured, sort_order),
    CONSTRAINT fk_gi_public_cat FOREIGN KEY (public_category_id) REFERENCES public_categories(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: navigation
-- =====================================================
CREATE TABLE IF NOT EXISTS navigation (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(100) NOT NULL,
    url VARCHAR(500) DEFAULT NULL,
    page_id INT UNSIGNED DEFAULT NULL,
    parent_id INT UNSIGNED DEFAULT NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    is_published BOOLEAN DEFAULT TRUE,
    visible_from DATETIME NULL DEFAULT NULL,
    visible_until DATETIME NULL DEFAULT NULL,
    open_in_new_tab BOOLEAN DEFAULT FALSE,
    css_class VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    INDEX idx_parent (parent_id),
    INDEX idx_sort (sort_order),
    INDEX idx_deleted (deleted_at),
    CONSTRAINT navigation_ibfk_1 FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE SET NULL,
    CONSTRAINT navigation_ibfk_2 FOREIGN KEY (parent_id) REFERENCES navigation(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: safari_activities
-- =====================================================
CREATE TABLE IF NOT EXISTS safari_activities (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT,
    image VARCHAR(500) DEFAULT NULL,
    video_urls JSON DEFAULT NULL,
    link_url VARCHAR(500) DEFAULT NULL,
    link_text VARCHAR(100) DEFAULT NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    is_published BOOLEAN DEFAULT TRUE,
    visible_from DATETIME NULL DEFAULT NULL,
    visible_until DATETIME NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    INDEX idx_sort (sort_order),
    INDEX idx_deleted (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: hero_slides
-- =====================================================
CREATE TABLE IF NOT EXISTS hero_slides (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_id INT UNSIGNED NOT NULL DEFAULT 1,
    image_path VARCHAR(500) NOT NULL,
    alt_text VARCHAR(255) DEFAULT NULL,
    caption VARCHAR(255) DEFAULT NULL,
    link_url VARCHAR(500) DEFAULT NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    is_published BOOLEAN NOT NULL DEFAULT TRUE,
    visible_from DATETIME NULL DEFAULT NULL,
    visible_until DATETIME NULL DEFAULT NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_sort (sort_order),
    INDEX idx_published (is_published),
    INDEX idx_deleted (deleted_at),
    INDEX idx_page_sort (page_id, sort_order),
    CONSTRAINT fk_hero_slides_page FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: dining_items
-- =====================================================
CREATE TABLE IF NOT EXISTS dining_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_id INT UNSIGNED NOT NULL DEFAULT 1,
    title VARCHAR(100) NOT NULL,
    time_label VARCHAR(100) DEFAULT NULL,
    text TEXT,
    icon VARCHAR(100) DEFAULT NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    is_published BOOLEAN NOT NULL DEFAULT TRUE,
    visible_from DATETIME NULL DEFAULT NULL,
    visible_until DATETIME NULL DEFAULT NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_sort (sort_order),
    INDEX idx_published (is_published),
    INDEX idx_deleted (deleted_at),
    INDEX idx_page_sort (page_id, sort_order),
    CONSTRAINT fk_dining_items_page FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: promise_pillars
-- =====================================================
CREATE TABLE IF NOT EXISTS promise_pillars (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_id INT UNSIGNED NOT NULL DEFAULT 1,
    title VARCHAR(100) NOT NULL,
    icon VARCHAR(20) DEFAULT NULL,
    text TEXT NOT NULL,
    link_url VARCHAR(500) DEFAULT NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    is_published BOOLEAN NOT NULL DEFAULT TRUE,
    visible_from DATETIME NULL DEFAULT NULL,
    visible_until DATETIME NULL DEFAULT NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_sort (sort_order),
    INDEX idx_published (is_published),
    INDEX idx_deleted (deleted_at),
    INDEX idx_page_sort (page_id, sort_order),
    CONSTRAINT fk_promise_pillars_page FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: moments
-- =====================================================
CREATE TABLE IF NOT EXISTS moments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_id INT UNSIGNED NOT NULL DEFAULT 1,
    image_path VARCHAR(500) NOT NULL,
    alt_text VARCHAR(255) DEFAULT NULL,
    kicker VARCHAR(50) DEFAULT NULL,
    title VARCHAR(100) NOT NULL,
    text TEXT,
    sort_order INT UNSIGNED DEFAULT 0,
    is_published BOOLEAN NOT NULL DEFAULT TRUE,
    visible_from DATETIME NULL DEFAULT NULL,
    visible_until DATETIME NULL DEFAULT NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_sort (sort_order),
    INDEX idx_published (is_published),
    INDEX idx_deleted (deleted_at),
    INDEX idx_page_sort (page_id, sort_order),
    CONSTRAINT fk_moments_page FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: contact_submissions
-- =====================================================
CREATE TABLE IF NOT EXISTS contact_submissions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    is_replied BOOLEAN DEFAULT FALSE,
    admin_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    visible_from DATETIME NULL DEFAULT NULL,
    visible_until DATETIME NULL DEFAULT NULL,
    INDEX idx_read (is_read),
    INDEX idx_date (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: admin_users
-- =====================================================
CREATE TABLE IF NOT EXISTS admin_users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) DEFAULT NULL,
    role ENUM('admin','editor') DEFAULT 'editor',
    last_login TIMESTAMP NULL DEFAULT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY username (username),
    UNIQUE KEY email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: page_seo
-- =====================================================
CREATE TABLE IF NOT EXISTS page_seo (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_id INT UNSIGNED NOT NULL,
    schema_type VARCHAR(50) DEFAULT 'WebPage',
    schema_json JSON DEFAULT NULL,
    additional_meta JSON DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY page_id (page_id),
    INDEX idx_deleted (deleted_at),
    CONSTRAINT page_seo_ibfk_1 FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: activity_log
-- =====================================================
CREATE TABLE IF NOT EXISTS activity_log (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED DEFAULT NULL,
    action VARCHAR(50) NOT NULL,
    entity_type VARCHAR(50) DEFAULT NULL,
    entity_id INT UNSIGNED DEFAULT NULL,
    details JSON DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    INDEX idx_entity (entity_type, entity_id),
    INDEX idx_date (created_at),
    CONSTRAINT activity_log_ibfk_1 FOREIGN KEY (user_id) REFERENCES admin_users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
