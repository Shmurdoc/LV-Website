-- MySQL dump 10.13  Distrib 9.1.0, for Win64 (x86_64)
--
-- Host: localhost    Database: viata_luxe
-- ------------------------------------------------------
-- Server version	9.1.0
--
-- NOTE (2026-08-31): gallery_categories table was archived to _archive_gallery_categories.
-- Gallery images now use public_categories (entity_type='gallery') with public_category_id FK.
-- See sql/migrations/007_merge_taxonomy.sql for the migration.

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned DEFAULT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entity_id` int unsigned DEFAULT NULL,
  `details` json DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_entity` (`entity_type`,`entity_id`),
  KEY `idx_date` (`created_at`),
  CONSTRAINT `activity_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=396 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','editor') COLLATE utf8mb4_unicode_ci DEFAULT 'editor',
  `last_login` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `apartment_amenities`
--

DROP TABLE IF EXISTS `apartment_amenities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `apartment_amenities` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `apartment_id` int unsigned NOT NULL,
  `amenity_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amenity_icon` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int unsigned DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_apt` (`apartment_id`),
  KEY `idx_deleted` (`deleted_at`),
  CONSTRAINT `apartment_amenities_ibfk_1` FOREIGN KEY (`apartment_id`) REFERENCES `apartments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `apartment_images`
--

DROP TABLE IF EXISTS `apartment_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `apartment_images` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `apartment_id` int unsigned NOT NULL,
  `image_path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `caption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int unsigned DEFAULT '0',
  `is_hero` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_apt_sort` (`apartment_id`,`sort_order`),
  KEY `idx_deleted` (`deleted_at`),
  CONSTRAINT `apartment_images_ibfk_1` FOREIGN KEY (`apartment_id`) REFERENCES `apartments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `apartments`
--

DROP TABLE IF EXISTS `apartments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `apartments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int unsigned NOT NULL,
  `category_id` int unsigned DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tagline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price_per_night` decimal(10,2) NOT NULL,
  `price_currency` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT 'ZAR',
  `max_guests` int unsigned DEFAULT '2',
  `room_size_m2` decimal(5,1) DEFAULT NULL,
  `bedrooms` int unsigned DEFAULT '1',
  `bathrooms` tinyint unsigned NOT NULL DEFAULT '1',
  `features` json DEFAULT NULL,
  `beds_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_image` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int unsigned DEFAULT '0',
  `is_published` tinyint(1) DEFAULT '1',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `visible_from` datetime DEFAULT NULL,
  `visible_until` datetime DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `og_image` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `area_sqm` decimal(5,1) GENERATED ALWAYS AS (`room_size_m2`) STORED,
  `price_from` decimal(10,2) GENERATED ALWAYS AS (`price_per_night`) STORED,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `page_id` (`page_id`),
  KEY `idx_slug` (`slug`),
  KEY `idx_sort` (`sort_order`),
  KEY `idx_deleted` (`deleted_at`),
  KEY `idx_featured` (`is_featured`),
  KEY `fk_apt_category` (`category_id`),
  CONSTRAINT `apartments_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_apt_category` FOREIGN KEY (`category_id`) REFERENCES `public_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contact_submissions`
--

DROP TABLE IF EXISTS `contact_submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_submissions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `is_replied` tinyint(1) DEFAULT '0',
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `visible_from` datetime DEFAULT NULL,
  `visible_until` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_read` (`is_read`),
  KEY `idx_date` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dining_items`
--

DROP TABLE IF EXISTS `dining_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dining_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int unsigned NOT NULL DEFAULT '1',
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time_label` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int unsigned DEFAULT '0',
  `is_published` tinyint(1) NOT NULL DEFAULT '1',
  `visible_from` datetime DEFAULT NULL,
  `visible_until` datetime DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_sort` (`sort_order`),
  KEY `idx_published` (`is_published`),
  KEY `idx_deleted` (`deleted_at`),
  KEY `idx_page_sort` (`page_id`,`sort_order`),
  CONSTRAINT `fk_dining_items_page` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `faqs`
--

DROP TABLE IF EXISTS `faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faqs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int unsigned DEFAULT NULL,
  `question` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int unsigned DEFAULT '0',
  `is_published` tinyint(1) DEFAULT '1',
  `visible_from` datetime DEFAULT NULL,
  `visible_until` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_page` (`page_id`),
  KEY `idx_sort` (`sort_order`),
  KEY `idx_deleted` (`deleted_at`),
  CONSTRAINT `faqs_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gallery_categories`
--

DROP TABLE IF EXISTS `gallery_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gallery_categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `sort_order` int unsigned DEFAULT '0',
  `is_published` tinyint(1) DEFAULT '1',
  `visible_from` datetime DEFAULT NULL,
  `visible_until` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_sort` (`sort_order`),
  KEY `idx_deleted` (`deleted_at`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gallery_images`
--

DROP TABLE IF EXISTS `gallery_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gallery_images` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int unsigned NOT NULL,
  `image_path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `caption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int unsigned DEFAULT '0',
  `visible_from` datetime DEFAULT NULL,
  `visible_until` datetime DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_cat_sort` (`category_id`,`sort_order`),
  KEY `idx_deleted` (`deleted_at`),
  KEY `idx_featured` (`is_featured`),
  KEY `idx_cat_featured_sort` (`category_id`,`is_featured`,`sort_order`),
  CONSTRAINT `gallery_images_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `gallery_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary view structure for view `global_settings`
--

DROP TABLE IF EXISTS `global_settings`;
/*!50001 DROP VIEW IF EXISTS `global_settings`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `global_settings` AS SELECT 
 1 AS `id`,
 1 AS `setting_key`,
 1 AS `setting_value`,
 1 AS `setting_type`,
 1 AS `setting_group`,
 1 AS `sort_order`,
 1 AS `created_at`,
 1 AS `updated_at`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `hero_slides`
--

DROP TABLE IF EXISTS `hero_slides`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hero_slides` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int unsigned NOT NULL DEFAULT '1',
  `image_path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `caption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int unsigned DEFAULT '0',
  `is_published` tinyint(1) NOT NULL DEFAULT '1',
  `visible_from` datetime DEFAULT NULL,
  `visible_until` datetime DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_sort` (`sort_order`),
  KEY `idx_published` (`is_published`),
  KEY `idx_deleted` (`deleted_at`),
  KEY `idx_page_sort` (`page_id`,`sort_order`),
  CONSTRAINT `fk_hero_slides_page` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `moments`
--

DROP TABLE IF EXISTS `moments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `moments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int unsigned NOT NULL DEFAULT '1',
  `image_path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kicker` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `sort_order` int unsigned DEFAULT '0',
  `is_published` tinyint(1) NOT NULL DEFAULT '1',
  `visible_from` datetime DEFAULT NULL,
  `visible_until` datetime DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_sort` (`sort_order`),
  KEY `idx_published` (`is_published`),
  KEY `idx_deleted` (`deleted_at`),
  KEY `idx_page_sort` (`page_id`,`sort_order`),
  CONSTRAINT `fk_moments_page` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `navigation`
--

DROP TABLE IF EXISTS `navigation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `navigation` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `page_id` int unsigned DEFAULT NULL,
  `parent_id` int unsigned DEFAULT NULL,
  `sort_order` int unsigned DEFAULT '0',
  `is_published` tinyint(1) DEFAULT '1',
  `visible_from` datetime DEFAULT NULL,
  `visible_until` datetime DEFAULT NULL,
  `open_in_new_tab` tinyint(1) DEFAULT '0',
  `css_class` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`),
  KEY `idx_parent` (`parent_id`),
  KEY `idx_sort` (`sort_order`),
  KEY `idx_deleted` (`deleted_at`),
  CONSTRAINT `navigation_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE SET NULL,
  CONSTRAINT `navigation_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `navigation` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `page_seo`
--

DROP TABLE IF EXISTS `page_seo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `page_seo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int unsigned NOT NULL,
  `schema_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'WebPage',
  `schema_json` json DEFAULT NULL,
  `additional_meta` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_id` (`page_id`),
  KEY `idx_deleted` (`deleted_at`),
  CONSTRAINT `page_seo_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `og_image` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `canonical_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_image` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_kicker` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_lead` text COLLATE utf8mb4_unicode_ci,
  `hero_align` enum('left','center') COLLATE utf8mb4_unicode_ci DEFAULT 'left',
  `template` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  `is_published` tinyint(1) DEFAULT '1',
  `is_homepage` tinyint(1) DEFAULT '0',
  `visible_from` datetime DEFAULT NULL,
  `visible_until` datetime DEFAULT NULL,
  `sort_order` int unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_slug` (`slug`),
  KEY `idx_published` (`is_published`),
  KEY `idx_sort` (`sort_order`),
  KEY `idx_deleted` (`deleted_at`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `promise_pillars`
--

DROP TABLE IF EXISTS `promise_pillars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `promise_pillars` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int unsigned NOT NULL DEFAULT '1',
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int unsigned DEFAULT '0',
  `is_published` tinyint(1) NOT NULL DEFAULT '1',
  `visible_from` datetime DEFAULT NULL,
  `visible_until` datetime DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_sort` (`sort_order`),
  KEY `idx_published` (`is_published`),
  KEY `idx_deleted` (`deleted_at`),
  KEY `idx_page_sort` (`page_id`,`sort_order`),
  CONSTRAINT `fk_promise_pillars_page` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `public_categories`
--

DROP TABLE IF EXISTS `public_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `public_categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `entity_type` enum('apartment','gallery','safari') COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `sort_order` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_entity_slug` (`entity_type`,`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `safari_activities`
--

DROP TABLE IF EXISTS `safari_activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `safari_activities` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_urls` json DEFAULT NULL,
  `link_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_text` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int unsigned DEFAULT '0',
  `is_published` tinyint(1) DEFAULT '1',
  `visible_from` datetime DEFAULT NULL,
  `visible_until` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_sort` (`sort_order`),
  KEY `idx_deleted` (`deleted_at`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `section_orientation`
--

DROP TABLE IF EXISTS `section_orientation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `section_orientation` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `section_id` int unsigned NOT NULL,
  `layout` enum('text-left','text-right','text-top','image-top','text-only','image-only','full-width','centered','grid-2','grid-3','grid-4') COLLATE utf8mb4_unicode_ci DEFAULT 'text-left',
  `background_color` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `background_image` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text_color` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `padding_top` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT '4rem',
  `padding_bottom` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT '4rem',
  `padding_left` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT '2rem',
  `padding_right` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT '2rem',
  `max_width` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT '1200px',
  `alignment` enum('left','center','right') COLLATE utf8mb4_unicode_ci DEFAULT 'left',
  `vertical_alignment` enum('top','center','bottom') COLLATE utf8mb4_unicode_ci DEFAULT 'center',
  `animation` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'fade-up',
  `responsive_stack` enum('stack','hide-image','hide-text') COLLATE utf8mb4_unicode_ci DEFAULT 'stack',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `section_id` (`section_id`),
  CONSTRAINT `section_orientation_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sections`
--

DROP TABLE IF EXISTS `sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sections` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int unsigned NOT NULL,
  `section_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `image` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_text` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int unsigned DEFAULT '0',
  `is_visible` tinyint(1) DEFAULT '1',
  `visible_from` datetime DEFAULT NULL,
  `visible_until` datetime DEFAULT NULL,
  `css_class` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_page_sort` (`page_id`,`sort_order`),
  KEY `idx_type` (`section_type`),
  KEY `idx_deleted` (`deleted_at`),
  CONSTRAINT `sections_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `site_settings`
--

DROP TABLE IF EXISTS `site_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `site_settings` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_unicode_ci,
  `setting_type` enum('text','textarea','image','url','email','phone','boolean','json') COLLATE utf8mb4_unicode_ci DEFAULT 'text',
  `setting_group` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `sort_order` int unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`),
  KEY `idx_group` (`setting_group`),
  KEY `idx_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `testimonials` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `apartment_id` int unsigned DEFAULT NULL,
  `reviewer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `review_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` tinyint unsigned DEFAULT '5',
  `source` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'direct',
  `is_featured` tinyint(1) DEFAULT '0',
  `is_published` tinyint(1) DEFAULT '1',
  `visible_from` datetime DEFAULT NULL,
  `visible_until` datetime DEFAULT NULL,
  `sort_order` int unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_apt` (`apartment_id`),
  KEY `idx_featured` (`is_featured`),
  KEY `idx_sort` (`sort_order`),
  KEY `idx_deleted` (`deleted_at`),
  CONSTRAINT `testimonials_ibfk_1` FOREIGN KEY (`apartment_id`) REFERENCES `apartments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping routines for database 'viata_luxe'
--

--
-- Final view structure for view `global_settings`
--

/*!50001 DROP VIEW IF EXISTS `global_settings`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `global_settings` AS select `site_settings`.`id` AS `id`,`site_settings`.`setting_key` AS `setting_key`,`site_settings`.`setting_value` AS `setting_value`,`site_settings`.`setting_type` AS `setting_type`,`site_settings`.`setting_group` AS `setting_group`,`site_settings`.`sort_order` AS `sort_order`,`site_settings`.`created_at` AS `created_at`,`site_settings`.`updated_at` AS `updated_at` from `site_settings` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-31 11:12:21
