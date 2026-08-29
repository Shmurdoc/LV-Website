-- Viata Luxe Guesthouse — Database Schema
-- MySQL 8.0+ / utf8mb4

CREATE DATABASE IF NOT EXISTS viata_luxe
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE viata_luxe;

-- =====================================================
-- TABLE 1: global_settings
-- =====================================================
CREATE TABLE global_settings (
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

-- =====================================================
-- TABLE 2: pages
-- =====================================================
CREATE TABLE pages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    subtitle VARCHAR(255),
    meta_title VARCHAR(255),
    meta_description TEXT,
    og_image VARCHAR(500),
    canonical_url VARCHAR(500),
    hero_image VARCHAR(500),
    hero_kicker VARCHAR(255),
    hero_align ENUM('left','center') DEFAULT 'left',
    template VARCHAR(50) NOT NULL DEFAULT 'default',
    is_published BOOLEAN DEFAULT TRUE,
    is_homepage BOOLEAN DEFAULT FALSE,
    visible_from DATETIME NULL DEFAULT NULL,
    visible_until DATETIME NULL DEFAULT NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_published (is_published),
    INDEX idx_sort (sort_order),
    INDEX idx_deleted (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE 3: sections
-- =====================================================
CREATE TABLE sections (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_id INT UNSIGNED NOT NULL,
    section_type VARCHAR(50) NOT NULL,
    title VARCHAR(255),
    subtitle VARCHAR(255),
    content LONGTEXT,
    image VARCHAR(500),
    link_url VARCHAR(500),
    link_text VARCHAR(100),
    sort_order INT UNSIGNED DEFAULT 0,
    is_visible BOOLEAN DEFAULT TRUE,
    visible_from DATETIME NULL DEFAULT NULL,
    visible_until DATETIME NULL DEFAULT NULL,
    css_class VARCHAR(255),
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE,
    INDEX idx_page_sort (page_id, sort_order),
    INDEX idx_type (section_type),
    INDEX idx_deleted (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE 4: section_orientation
-- =====================================================
CREATE TABLE section_orientation (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    section_id INT UNSIGNED NOT NULL UNIQUE,
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
    FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE 5: apartments
-- =====================================================
CREATE TABLE apartments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_id INT UNSIGNED NOT NULL,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    subtitle VARCHAR(255),
    description TEXT,
    price_per_night DECIMAL(10,2) NOT NULL,
    price_currency VARCHAR(3) DEFAULT 'ZAR',
    max_guests INT UNSIGNED DEFAULT 2,
    room_size_m2 DECIMAL(5,1),
    bedrooms INT UNSIGNED DEFAULT 1,
    beds_description VARCHAR(255),
    hero_image VARCHAR(500),
    sort_order INT UNSIGNED DEFAULT 0,
    is_published BOOLEAN DEFAULT TRUE,
    visible_from DATETIME NULL DEFAULT NULL,
    visible_until DATETIME NULL DEFAULT NULL,
    meta_title VARCHAR(255),
    meta_description TEXT,
    og_image VARCHAR(500),
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE,
    INDEX idx_slug (slug),
    INDEX idx_sort (sort_order),
    INDEX idx_deleted (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE 6: apartment_images
-- =====================================================
CREATE TABLE apartment_images (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    apartment_id INT UNSIGNED NOT NULL,
    image_path VARCHAR(500) NOT NULL,
    alt_text VARCHAR(255),
    caption VARCHAR(255),
    sort_order INT UNSIGNED DEFAULT 0,
    is_hero BOOLEAN DEFAULT FALSE,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (apartment_id) REFERENCES apartments(id) ON DELETE CASCADE,
    INDEX idx_apt_sort (apartment_id, sort_order),
    INDEX idx_deleted (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE 7: apartment_amenities
-- =====================================================
CREATE TABLE apartment_amenities (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    apartment_id INT UNSIGNED NOT NULL,
    amenity_name VARCHAR(255) NOT NULL,
    amenity_icon VARCHAR(100),
    sort_order INT UNSIGNED DEFAULT 0,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (apartment_id) REFERENCES apartments(id) ON DELETE CASCADE,
    INDEX idx_apt (apartment_id),
    INDEX idx_deleted (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE 8: testimonials
-- =====================================================
CREATE TABLE testimonials (
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
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (apartment_id) REFERENCES apartments(id) ON DELETE SET NULL,
    INDEX idx_apt (apartment_id),
    INDEX idx_featured (is_featured),
    INDEX idx_sort (sort_order),
    INDEX idx_deleted (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE 9: faqs
-- =====================================================
CREATE TABLE faqs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_id INT UNSIGNED DEFAULT NULL,
    question VARCHAR(500) NOT NULL,
    answer TEXT NOT NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    is_published BOOLEAN DEFAULT TRUE,
    visible_from DATETIME NULL DEFAULT NULL,
    visible_until DATETIME NULL DEFAULT NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE SET NULL,
    INDEX idx_page (page_id),
    INDEX idx_sort (sort_order),
    INDEX idx_deleted (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE 10: gallery_categories
-- =====================================================
CREATE TABLE gallery_categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    sort_order INT UNSIGNED DEFAULT 0,
    is_published BOOLEAN DEFAULT TRUE,
    visible_from DATETIME NULL DEFAULT NULL,
    visible_until DATETIME NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    INDEX idx_sort (sort_order),
    INDEX idx_deleted (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE 11: gallery_images
-- =====================================================
CREATE TABLE gallery_images (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id INT UNSIGNED NOT NULL,
    image_path VARCHAR(500) NOT NULL,
    alt_text VARCHAR(255),
    caption VARCHAR(255),
    sort_order INT UNSIGNED DEFAULT 0,
    visible_from DATETIME NULL DEFAULT NULL,
    visible_until DATETIME NULL DEFAULT NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES gallery_categories(id) ON DELETE CASCADE,
    INDEX idx_cat_sort (category_id, sort_order),
    INDEX idx_deleted (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE 12: navigation
-- =====================================================
CREATE TABLE navigation (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(100) NOT NULL,
    url VARCHAR(500),
    page_id INT UNSIGNED DEFAULT NULL,
    parent_id INT UNSIGNED DEFAULT NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    is_published BOOLEAN DEFAULT TRUE,
    visible_from DATETIME NULL DEFAULT NULL,
    visible_until DATETIME NULL DEFAULT NULL,
    open_in_new_tab BOOLEAN DEFAULT FALSE,
    css_class VARCHAR(100),
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE SET NULL,
    FOREIGN KEY (parent_id) REFERENCES navigation(id) ON DELETE CASCADE,
    INDEX idx_parent (parent_id),
    INDEX idx_sort (sort_order),
    INDEX idx_deleted (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE 13: safari_activities
-- =====================================================
CREATE TABLE safari_activities (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT,
    image VARCHAR(500),
    video_urls JSON,
    link_url VARCHAR(500),
    link_text VARCHAR(100),
    sort_order INT UNSIGNED DEFAULT 0,
    is_published BOOLEAN DEFAULT TRUE,
    visible_from DATETIME NULL DEFAULT NULL,
    visible_until DATETIME NULL DEFAULT NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_sort (sort_order),
    INDEX idx_deleted (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE 14: contact_submissions
-- =====================================================
CREATE TABLE contact_submissions (
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
    INDEX idx_date (created_at),
    INDEX idx_deleted (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE 15: admin_users
-- =====================================================
CREATE TABLE admin_users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    role ENUM('admin','editor') DEFAULT 'editor',
    last_login TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE 16: page_seo
-- =====================================================
CREATE TABLE page_seo (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_id INT UNSIGNED NOT NULL UNIQUE,
    schema_type VARCHAR(50) DEFAULT 'WebPage',
    schema_json JSON,
    additional_meta JSON,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE,
    INDEX idx_deleted (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE 17: activity_log
-- =====================================================
CREATE TABLE activity_log (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED,
    action VARCHAR(50) NOT NULL,
    entity_type VARCHAR(50),
    entity_id INT UNSIGNED,
    details JSON,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES admin_users(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_entity (entity_type, entity_id),
    INDEX idx_date (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
