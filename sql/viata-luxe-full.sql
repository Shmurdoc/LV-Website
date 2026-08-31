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
-- Dumping data for table `activity_log`
--

LOCK TABLES `activity_log` WRITE;
/*!40000 ALTER TABLE `activity_log` DISABLE KEYS */;
INSERT INTO `activity_log` VALUES (1,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 15:05:54'),(2,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 15:06:20'),(3,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 16:01:37'),(4,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 16:06:17'),(5,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 18:11:23'),(6,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 18:15:32'),(7,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 18:18:40'),(8,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 18:19:26'),(9,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 18:20:48'),(10,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 18:21:09'),(11,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 18:21:32'),(12,1,'create','testimonial',7,NULL,'127.0.0.1','2026-08-27 18:21:32'),(13,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 18:32:10'),(14,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 18:32:11'),(15,1,'create','testimonial',8,NULL,'127.0.0.1','2026-08-27 18:32:12'),(16,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 18:33:00'),(17,1,'create','testimonial',9,NULL,'127.0.0.1','2026-08-27 18:33:02'),(18,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 18:45:51'),(19,1,'create','testimonial',10,NULL,'127.0.0.1','2026-08-27 18:45:52'),(20,1,'update','testimonial',10,NULL,'127.0.0.1','2026-08-27 18:45:53'),(21,1,'delete','testimonial',10,NULL,'127.0.0.1','2026-08-27 18:45:54'),(22,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 18:46:13'),(23,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 18:46:31'),(24,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 18:46:51'),(25,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 18:47:34'),(26,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 18:47:47'),(27,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 18:48:15'),(28,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 18:49:06'),(29,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 18:50:28'),(30,1,'create','testimonial',11,NULL,'127.0.0.1','2026-08-27 18:50:28'),(31,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 18:51:33'),(32,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 18:52:19'),(33,1,'create','testimonial',12,NULL,'127.0.0.1','2026-08-27 18:52:21'),(34,1,'update','testimonial',12,NULL,'127.0.0.1','2026-08-27 18:52:21'),(35,1,'delete','testimonial',12,NULL,'127.0.0.1','2026-08-27 18:52:22'),(36,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 18:52:38'),(37,1,'create','testimonial',13,NULL,'127.0.0.1','2026-08-27 18:52:39'),(38,1,'update','testimonial',13,NULL,'127.0.0.1','2026-08-27 18:52:39'),(39,1,'delete','testimonial',13,NULL,'127.0.0.1','2026-08-27 18:52:40'),(40,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 18:53:39'),(41,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 18:55:38'),(42,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 18:55:39'),(43,1,'create','testimonial',14,NULL,'127.0.0.1','2026-08-27 18:55:40'),(44,1,'update','testimonial',14,NULL,'127.0.0.1','2026-08-27 18:55:41'),(45,1,'delete','testimonial',14,NULL,'127.0.0.1','2026-08-27 18:55:42'),(46,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 19:00:28'),(47,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 19:00:29'),(48,1,'create','testimonial',15,NULL,'127.0.0.1','2026-08-27 19:00:30'),(49,1,'update','testimonial',15,NULL,'127.0.0.1','2026-08-27 19:00:32'),(50,1,'delete','testimonial',15,NULL,'127.0.0.1','2026-08-27 19:00:33'),(51,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 19:03:03'),(52,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 19:03:03'),(53,1,'create','testimonial',16,NULL,'127.0.0.1','2026-08-27 19:03:05'),(54,1,'update','testimonial',16,NULL,'127.0.0.1','2026-08-27 19:03:06'),(55,1,'delete','testimonial',16,NULL,'127.0.0.1','2026-08-27 19:03:07'),(56,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 19:44:22'),(57,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 19:44:23'),(58,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 19:45:26'),(59,1,'create','testimonial',17,NULL,'127.0.0.1','2026-08-27 19:45:27'),(60,1,'update','testimonial',17,NULL,'127.0.0.1','2026-08-27 19:45:28'),(61,1,'delete','testimonial',17,NULL,'127.0.0.1','2026-08-27 19:45:29'),(62,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 19:46:00'),(63,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 19:46:01'),(64,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 19:48:40'),(65,1,'create','testimonial',18,NULL,'127.0.0.1','2026-08-27 19:48:41'),(66,1,'update','testimonial',18,NULL,'127.0.0.1','2026-08-27 19:48:42'),(67,1,'delete','testimonial',18,NULL,'127.0.0.1','2026-08-27 19:48:43'),(68,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 19:49:09'),(69,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 19:49:11'),(70,1,'create','testimonial',19,NULL,'127.0.0.1','2026-08-27 19:49:13'),(71,1,'update','testimonial',19,NULL,'127.0.0.1','2026-08-27 19:49:15'),(72,1,'delete','testimonial',19,NULL,'127.0.0.1','2026-08-27 19:49:16'),(73,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 19:49:36'),(74,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 19:49:36'),(75,1,'create','testimonial',20,NULL,'127.0.0.1','2026-08-27 19:49:38'),(76,1,'update','testimonial',20,NULL,'127.0.0.1','2026-08-27 19:49:40'),(77,1,'delete','testimonial',20,NULL,'127.0.0.1','2026-08-27 19:49:41'),(78,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 21:38:04'),(79,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 21:39:54'),(80,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 21:40:19'),(81,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 21:40:53'),(82,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 21:40:54'),(83,1,'create','testimonial',21,NULL,'127.0.0.1','2026-08-27 21:40:55'),(84,1,'update','testimonial',21,NULL,'127.0.0.1','2026-08-27 21:40:57'),(85,1,'delete','testimonial',21,NULL,'127.0.0.1','2026-08-27 21:40:57'),(86,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 21:44:01'),(87,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-27 21:44:02'),(88,1,'create','testimonial',22,NULL,'127.0.0.1','2026-08-27 21:44:03'),(89,1,'update','testimonial',22,NULL,'127.0.0.1','2026-08-27 21:44:05'),(90,1,'delete','testimonial',22,NULL,'127.0.0.1','2026-08-27 21:44:06'),(91,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-28 00:54:05'),(92,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-28 00:54:06'),(93,1,'create','testimonial',23,NULL,'127.0.0.1','2026-08-28 00:54:08'),(94,1,'update','testimonial',23,NULL,'127.0.0.1','2026-08-28 00:54:10'),(95,1,'delete','testimonial',23,NULL,'127.0.0.1','2026-08-28 00:54:11'),(96,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-28 01:06:36'),(97,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-28 01:06:37'),(98,1,'create','testimonial',24,NULL,'127.0.0.1','2026-08-28 01:06:38'),(99,1,'update','testimonial',24,NULL,'127.0.0.1','2026-08-28 01:06:40'),(100,1,'delete','testimonial',24,NULL,'127.0.0.1','2026-08-28 01:06:41'),(101,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-28 08:57:27'),(102,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-28 08:57:28'),(103,1,'create','testimonial',25,NULL,'127.0.0.1','2026-08-28 08:57:29'),(104,1,'update','testimonial',25,NULL,'127.0.0.1','2026-08-28 08:57:31'),(105,1,'delete','testimonial',25,NULL,'127.0.0.1','2026-08-28 08:57:32'),(106,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-28 09:32:24'),(107,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-28 09:32:25'),(108,1,'create','testimonial',26,NULL,'127.0.0.1','2026-08-28 09:32:27'),(109,1,'update','testimonial',26,NULL,'127.0.0.1','2026-08-28 09:32:30'),(110,1,'delete','testimonial',26,NULL,'127.0.0.1','2026-08-28 09:32:31'),(111,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-28 11:21:42'),(112,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-28 11:21:43'),(113,1,'create','testimonial',27,NULL,'127.0.0.1','2026-08-28 11:21:44'),(114,1,'update','testimonial',27,NULL,'127.0.0.1','2026-08-28 11:21:47'),(115,1,'delete','testimonial',27,NULL,'127.0.0.1','2026-08-28 11:21:47'),(116,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-28 11:27:47'),(117,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-28 11:28:05'),(118,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-28 11:35:41'),(119,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-28 11:35:42'),(120,1,'create','testimonial',28,NULL,'127.0.0.1','2026-08-28 11:35:49'),(121,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-28 11:37:09'),(122,1,'create','testimonial',29,NULL,'127.0.0.1','2026-08-28 11:37:10'),(123,1,'update','testimonial',29,NULL,'127.0.0.1','2026-08-28 11:37:13'),(124,1,'delete','testimonial',29,NULL,'127.0.0.1','2026-08-28 11:37:13'),(125,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-28 11:38:15'),(126,1,'create','testimonial',30,NULL,'127.0.0.1','2026-08-28 11:38:16'),(127,1,'update','testimonial',30,NULL,'127.0.0.1','2026-08-28 11:38:18'),(128,1,'delete','testimonial',30,NULL,'127.0.0.1','2026-08-28 11:38:18'),(129,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-28 11:38:46'),(130,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 07:44:49'),(131,1,'create','testimonial',31,NULL,'127.0.0.1','2026-08-29 07:44:50'),(132,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 07:45:35'),(133,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 07:49:54'),(134,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 07:50:23'),(135,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 07:50:51'),(136,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 07:51:31'),(137,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 07:51:52'),(138,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 07:52:09'),(139,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 08:29:54'),(140,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 08:33:28'),(141,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 08:36:23'),(142,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 08:39:19'),(143,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 08:40:45'),(144,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 08:46:46'),(145,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 09:00:05'),(146,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 09:02:13'),(147,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 09:08:04'),(148,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 09:27:25'),(149,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 09:33:37'),(150,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 09:41:50'),(151,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 09:46:11'),(152,1,'login','admin_users',1,NULL,'::1','2026-08-29 09:56:37'),(153,1,'login','admin_users',1,NULL,'::1','2026-08-29 10:01:17'),(154,1,'login','admin_users',1,NULL,'::1','2026-08-29 10:01:51'),(155,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 11:27:43'),(156,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 11:30:24'),(157,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 11:33:33'),(158,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 11:34:47'),(159,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 11:36:31'),(160,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 11:37:34'),(161,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 13:00:15'),(162,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 13:01:36'),(163,1,'login','admin_users',1,NULL,'::1','2026-08-29 13:02:51'),(164,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 14:02:20'),(165,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 14:14:44'),(166,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 14:16:31'),(167,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 14:17:45'),(168,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 14:20:00'),(169,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 14:21:43'),(170,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 14:29:12'),(171,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 14:40:09'),(172,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 14:41:17'),(173,1,'delete','page',6,NULL,'127.0.0.1','2026-08-29 14:41:23'),(174,1,'restore','page',1,NULL,'127.0.0.1','2026-08-29 14:41:25'),(175,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 14:42:53'),(176,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 14:46:22'),(177,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 14:47:34'),(178,1,'restore','contact_submission',25,NULL,'127.0.0.1','2026-08-29 14:47:38'),(179,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 14:50:26'),(180,1,'delete','faq',10,NULL,'127.0.0.1','2026-08-29 14:50:27'),(181,1,'restore','faq',1,NULL,'127.0.0.1','2026-08-29 14:50:28'),(182,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 14:56:09'),(183,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 14:56:16'),(184,1,'create','testimonial',32,NULL,'127.0.0.1','2026-08-29 14:56:18'),(185,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 14:56:41'),(186,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 14:58:45'),(187,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 14:58:50'),(188,1,'create','testimonial',33,NULL,'127.0.0.1','2026-08-29 14:58:53'),(189,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 14:59:24'),(190,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 15:00:57'),(191,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 15:01:54'),(192,1,'delete','faq',10,NULL,'127.0.0.1','2026-08-29 15:01:56'),(193,1,'restore','faq',10,NULL,'127.0.0.1','2026-08-29 15:01:57'),(194,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 15:02:38'),(195,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 15:11:26'),(196,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 15:11:28'),(197,1,'create','testimonial',34,NULL,'127.0.0.1','2026-08-29 15:11:29'),(198,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 15:11:50'),(199,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 15:13:54'),(200,1,'create','testimonial',35,NULL,'127.0.0.1','2026-08-29 15:13:55'),(201,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 15:16:41'),(202,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 15:16:42'),(203,1,'create','testimonial',36,NULL,'127.0.0.1','2026-08-29 15:16:43'),(204,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 15:17:10'),(205,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 15:18:29'),(206,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 15:22:27'),(207,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 15:22:29'),(208,1,'create','testimonial',37,NULL,'127.0.0.1','2026-08-29 15:22:31'),(209,1,'update','testimonial',37,NULL,'127.0.0.1','2026-08-29 15:22:31'),(210,1,'delete','testimonial',37,NULL,'127.0.0.1','2026-08-29 15:22:32'),(211,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 15:22:45'),(212,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 15:24:20'),(213,1,'login','admin_users',1,NULL,'::1','2026-08-29 16:36:42'),(214,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 20:01:20'),(215,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 20:01:21'),(216,1,'create','testimonial',38,NULL,'127.0.0.1','2026-08-29 20:01:22'),(217,1,'update','testimonial',38,NULL,'127.0.0.1','2026-08-29 20:01:24'),(218,1,'delete','testimonial',38,NULL,'127.0.0.1','2026-08-29 20:01:24'),(219,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 20:01:33'),(220,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 20:02:53'),(221,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 20:03:38'),(222,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 20:03:39'),(223,1,'create','testimonial',39,NULL,'127.0.0.1','2026-08-29 20:03:42'),(224,1,'update','testimonial',39,NULL,'127.0.0.1','2026-08-29 20:03:44'),(225,1,'delete','testimonial',39,NULL,'127.0.0.1','2026-08-29 20:03:46'),(226,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 20:03:55'),(227,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 20:05:18'),(228,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 21:15:57'),(229,1,'create','testimonial',40,NULL,'127.0.0.1','2026-08-29 21:15:59'),(230,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 21:16:00'),(231,1,'update','testimonial',40,NULL,'127.0.0.1','2026-08-29 21:16:00'),(232,1,'delete','testimonial',40,NULL,'127.0.0.1','2026-08-29 21:16:01'),(233,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 21:16:09'),(234,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 21:17:30'),(235,1,'login','admin_users',1,NULL,'::1','2026-08-29 21:18:43'),(236,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 21:28:59'),(237,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 21:29:00'),(238,1,'create','testimonial',41,NULL,'127.0.0.1','2026-08-29 21:29:03'),(239,1,'update','testimonial',41,NULL,'127.0.0.1','2026-08-29 21:29:05'),(240,1,'delete','testimonial',41,NULL,'127.0.0.1','2026-08-29 21:29:07'),(241,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 21:29:20'),(242,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 21:30:45'),(243,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 21:33:29'),(244,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 21:33:30'),(245,1,'create','testimonial',42,NULL,'127.0.0.1','2026-08-29 21:33:31'),(246,1,'update','testimonial',42,NULL,'127.0.0.1','2026-08-29 21:33:32'),(247,1,'delete','testimonial',42,NULL,'127.0.0.1','2026-08-29 21:33:33'),(248,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 21:33:48'),(249,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 21:35:15'),(250,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 22:15:29'),(251,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 22:15:30'),(252,1,'create','testimonial',43,NULL,'127.0.0.1','2026-08-29 22:15:37'),(253,1,'update','testimonial',43,NULL,'127.0.0.1','2026-08-29 22:15:37'),(254,1,'delete','testimonial',43,NULL,'127.0.0.1','2026-08-29 22:15:38'),(255,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 22:16:12'),(256,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 22:17:34'),(257,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 22:22:09'),(258,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 22:22:10'),(259,1,'create','testimonial',44,NULL,'127.0.0.1','2026-08-29 22:22:15'),(260,1,'update','testimonial',44,NULL,'127.0.0.1','2026-08-29 22:22:16'),(261,1,'delete','testimonial',44,NULL,'127.0.0.1','2026-08-29 22:22:18'),(262,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 22:22:29'),(263,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 22:24:01'),(264,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 22:57:03'),(265,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 22:57:03'),(266,1,'create','testimonial',45,NULL,'127.0.0.1','2026-08-29 22:57:05'),(267,1,'update','testimonial',45,NULL,'127.0.0.1','2026-08-29 22:57:06'),(268,1,'delete','testimonial',45,NULL,'127.0.0.1','2026-08-29 22:57:06'),(269,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 22:57:20'),(270,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-29 22:58:54'),(271,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 01:33:16'),(272,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 01:33:17'),(273,1,'create','testimonial',46,NULL,'127.0.0.1','2026-08-30 01:33:20'),(274,1,'update','testimonial',46,NULL,'127.0.0.1','2026-08-30 01:33:20'),(275,1,'delete','testimonial',46,NULL,'127.0.0.1','2026-08-30 01:33:21'),(276,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 01:33:35'),(277,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 01:35:04'),(278,1,'login','admin_users',1,NULL,'::1','2026-08-30 01:51:21'),(279,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 01:59:50'),(280,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 01:59:51'),(281,1,'create','testimonial',47,NULL,'127.0.0.1','2026-08-30 01:59:53'),(282,1,'update','testimonial',47,NULL,'127.0.0.1','2026-08-30 01:59:54'),(283,1,'delete','testimonial',47,NULL,'127.0.0.1','2026-08-30 01:59:55'),(284,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 02:00:09'),(285,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 02:02:46'),(286,1,'create','testimonial',48,NULL,'127.0.0.1','2026-08-30 02:02:51'),(287,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 02:03:19'),(288,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 02:05:05'),(289,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 02:05:58'),(290,1,'create','testimonial',49,NULL,'127.0.0.1','2026-08-30 02:05:59'),(291,1,'update','testimonial',49,NULL,'127.0.0.1','2026-08-30 02:06:00'),(292,1,'delete','testimonial',49,NULL,'127.0.0.1','2026-08-30 02:06:00'),(293,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 02:06:33'),(294,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 02:09:13'),(295,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 02:09:27'),(296,1,'login','admin_users',1,NULL,'::1','2026-08-30 02:25:08'),(297,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 02:29:31'),(298,1,'create','testimonial',50,NULL,'127.0.0.1','2026-08-30 02:29:32'),(299,1,'update','testimonial',50,NULL,'127.0.0.1','2026-08-30 02:29:32'),(300,1,'delete','testimonial',50,NULL,'127.0.0.1','2026-08-30 02:29:32'),(301,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 02:30:01'),(302,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 02:32:00'),(303,1,'create','testimonial',51,NULL,'127.0.0.1','2026-08-30 02:32:02'),(304,1,'update','testimonial',51,NULL,'127.0.0.1','2026-08-30 02:32:02'),(305,1,'delete','testimonial',51,NULL,'127.0.0.1','2026-08-30 02:32:03'),(306,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 02:35:36'),(307,1,'create','testimonial',52,NULL,'127.0.0.1','2026-08-30 02:35:37'),(308,1,'update','testimonial',52,NULL,'127.0.0.1','2026-08-30 02:35:37'),(309,1,'delete','testimonial',52,NULL,'127.0.0.1','2026-08-30 02:35:38'),(310,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 02:36:11'),(311,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 02:38:17'),(312,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 02:38:32'),(313,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 02:40:14'),(314,1,'create','testimonial',53,NULL,'127.0.0.1','2026-08-30 02:40:16'),(315,1,'update','testimonial',53,NULL,'127.0.0.1','2026-08-30 02:40:16'),(316,1,'delete','testimonial',53,NULL,'127.0.0.1','2026-08-30 02:40:16'),(317,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 02:40:49'),(318,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 02:42:50'),(319,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 02:43:05'),(320,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 02:45:31'),(321,1,'create','testimonial',54,NULL,'127.0.0.1','2026-08-30 02:45:33'),(322,1,'update','testimonial',54,NULL,'127.0.0.1','2026-08-30 02:45:33'),(323,1,'delete','testimonial',54,NULL,'127.0.0.1','2026-08-30 02:45:33'),(324,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 02:46:04'),(325,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 02:48:14'),(326,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 02:48:26'),(327,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 04:02:55'),(328,1,'create','testimonial',55,NULL,'127.0.0.1','2026-08-30 04:02:58'),(329,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 04:04:08'),(330,1,'login','admin_users',1,NULL,'::1','2026-08-30 05:37:27'),(331,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 05:51:10'),(332,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 05:51:11'),(333,1,'create','testimonial',56,NULL,'127.0.0.1','2026-08-30 05:51:11'),(334,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 05:51:39'),(335,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 05:52:28'),(336,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 05:55:32'),(337,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 05:55:34'),(338,1,'create','testimonial',57,NULL,'127.0.0.1','2026-08-30 05:55:34'),(339,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 05:56:01'),(340,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 05:56:59'),(341,1,'login','admin_users',1,NULL,'::1','2026-08-30 09:02:25'),(342,1,'login','admin_users',1,NULL,'::1','2026-08-30 09:08:24'),(343,1,'login','admin_users',1,NULL,'::1','2026-08-30 10:13:41'),(344,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 13:20:37'),(345,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 13:20:37'),(346,1,'create','testimonial',58,NULL,'127.0.0.1','2026-08-30 13:20:38'),(347,1,'update','testimonial',58,NULL,'127.0.0.1','2026-08-30 13:20:39'),(348,1,'delete','testimonial',58,NULL,'127.0.0.1','2026-08-30 13:20:40'),(349,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 13:21:03'),(350,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 13:22:14'),(351,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 13:23:10'),(352,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 13:23:10'),(353,1,'create','testimonial',59,NULL,'127.0.0.1','2026-08-30 13:23:12'),(354,1,'update','testimonial',59,NULL,'127.0.0.1','2026-08-30 13:23:12'),(355,1,'delete','testimonial',59,NULL,'127.0.0.1','2026-08-30 13:23:13'),(356,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 13:23:33'),(357,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 13:24:58'),(358,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 14:07:41'),(359,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 14:07:43'),(360,1,'create','testimonial',60,NULL,'127.0.0.1','2026-08-30 14:07:45'),(361,1,'update','testimonial',60,NULL,'127.0.0.1','2026-08-30 14:07:46'),(362,1,'delete','testimonial',60,NULL,'127.0.0.1','2026-08-30 14:07:47'),(363,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 14:08:07'),(364,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 14:10:44'),(365,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 14:10:45'),(366,1,'create','testimonial',61,NULL,'127.0.0.1','2026-08-30 14:10:47'),(367,1,'update','testimonial',61,NULL,'127.0.0.1','2026-08-30 14:10:47'),(368,1,'delete','testimonial',61,NULL,'127.0.0.1','2026-08-30 14:10:48'),(369,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 14:11:09'),(370,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 14:13:00'),(371,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 14:20:05'),(372,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 14:20:05'),(373,1,'create','testimonial',62,NULL,'127.0.0.1','2026-08-30 14:20:08'),(374,1,'update','testimonial',62,NULL,'127.0.0.1','2026-08-30 14:20:09'),(375,1,'delete','testimonial',62,NULL,'127.0.0.1','2026-08-30 14:20:10'),(376,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 14:20:32'),(377,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 14:22:54'),(378,1,'create','testimonial',63,NULL,'127.0.0.1','2026-08-30 14:22:58'),(379,1,'update','testimonial',63,NULL,'127.0.0.1','2026-08-30 14:22:59'),(380,1,'delete','testimonial',63,NULL,'127.0.0.1','2026-08-30 14:22:59'),(381,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 15:09:36'),(382,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 15:09:37'),(383,1,'create','testimonial',64,NULL,'127.0.0.1','2026-08-30 15:09:39'),(384,1,'update','testimonial',64,NULL,'127.0.0.1','2026-08-30 15:09:40'),(385,1,'delete','testimonial',64,NULL,'127.0.0.1','2026-08-30 15:09:40'),(386,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 15:10:08'),(387,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 15:12:08'),(388,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 15:12:09'),(389,1,'create','testimonial',65,NULL,'127.0.0.1','2026-08-30 15:12:13'),(390,1,'update','testimonial',65,NULL,'127.0.0.1','2026-08-30 15:12:14'),(391,1,'delete','testimonial',65,NULL,'127.0.0.1','2026-08-30 15:12:15'),(392,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 15:12:42'),(393,1,'login','admin_users',1,NULL,'127.0.0.1','2026-08-30 15:14:53'),(394,1,'login','admin_users',1,NULL,'::1','2026-08-30 16:52:35'),(395,1,'login','admin_users',1,NULL,'::1','2026-08-30 16:56:21');
/*!40000 ALTER TABLE `activity_log` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES (1,'admin','admin@vialuxe.co.za','$2y$12$pirfgnHjY/.bE57.ev47mOvhVOIUXoBvGsa7SiRiaIdTo49ygrtZy','System Administrator','admin','2026-08-30 16:56:21',1,'2026-08-27 12:13:09','2026-08-30 16:56:21');
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `apartment_amenities`
--

LOCK TABLES `apartment_amenities` WRITE;
/*!40000 ALTER TABLE `apartment_amenities` DISABLE KEYS */;
INSERT INTO `apartment_amenities` VALUES (1,1,'Free WiFi','wifi',1,NULL),(2,1,'DStv','tv',2,NULL),(3,1,'Full Kitchen','kitchen',3,NULL),(4,1,'Secure Parking','car',4,NULL),(5,1,'Swimming Pool','droplets',5,NULL),(6,1,'Air Conditioning','snowflake',6,NULL),(7,1,'Private Balcony','balcony',7,NULL),(8,2,'Free WiFi','wifi',1,NULL),(9,2,'DStv','tv',2,NULL),(10,2,'Full Kitchen','kitchen',3,NULL),(11,2,'Secure Parking','car',4,NULL),(12,2,'Swimming Pool','droplets',5,NULL),(13,2,'Air Conditioning','snowflake',6,NULL),(14,2,'Ensuite Bathroom','bath',7,NULL),(15,3,'Free WiFi','wifi',1,NULL),(16,3,'DStv','tv',2,NULL),(17,3,'Full Kitchen','kitchen',3,NULL),(18,3,'Secure Parking','car',4,NULL),(19,3,'Swimming Pool','droplets',5,NULL),(20,3,'Air Conditioning','snowflake',6,NULL),(21,3,'Dishwasher','dishwasher',7,NULL),(22,3,'Private Patio','patio',8,NULL),(23,4,'Free WiFi','wifi',1,NULL),(24,4,'DStv','tv',2,NULL),(25,4,'Gourmet Kitchen','kitchen',3,NULL),(26,4,'Secure Parking','car',4,NULL),(27,4,'Swimming Pool','droplets',5,NULL),(28,4,'Air Conditioning','snowflake',6,NULL),(29,4,'Soaking Tub','bath',7,NULL),(30,4,'Panoramic Views','mountain',8,NULL),(31,4,'Premium Linens','bed',9,NULL);
/*!40000 ALTER TABLE `apartment_amenities` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `apartment_images`
--

LOCK TABLES `apartment_images` WRITE;
/*!40000 ALTER TABLE `apartment_images` DISABLE KEYS */;
INSERT INTO `apartment_images` VALUES (1,1,'/Luxury Images/apartments-classic-1/apt1-kitchen-dining-main.jpg','Bilateral apartment bedroom','Queen bed with bushveld views',1,1,'2026-08-27 12:13:09',NULL),(2,1,'/Luxury Images/apartments-classic-1/apt1-kitchen-stove-closeup.jpg','Bilateral apartment kitchen','Full modern kitchen',2,0,'2026-08-27 12:13:09',NULL),(3,1,'/Luxury Images/bathrooms/bathroom-1-sink-toilet-yellow-mat.jpg','Bilateral apartment bathroom','Ensuite bathroom',3,0,'2026-08-27 12:13:09',NULL),(4,2,'/Luxury Images/apartments-classic-2/apt2-bedroom-main-view.jpg','Classic apartment bedroom','Elegant queen bedroom',1,1,'2026-08-27 12:13:09',NULL),(5,2,'/Luxury Images/apartments-classic-2/apt2-living-room-main-view.jpg','Classic apartment living','Open plan living area',2,0,'2026-08-27 12:13:09',NULL),(6,2,'/Luxury Images/apartments-classic-2/apt2-bathroom-sink-area.jpg','Classic apartment patio','Private patio with views',3,0,'2026-08-27 12:13:09',NULL),(7,3,'/Luxury Images/apartments-classic-3/apt3-bedroom-main-view.jpg','Comfort apartment bedroom','Spacious queen bedroom',1,1,'2026-08-27 12:13:09',NULL),(8,3,'/Luxury Images/apartments-classic-3/apt3-kitchen-wide-angle.jpg','Comfort apartment kitchen','Gourmet kitchen',2,0,'2026-08-27 12:13:09',NULL),(9,3,'/Luxury Images/apartments-classic-3/apt3-living-room-entertainment-unit.jpg','Comfort apartment lounge','Separate living area',3,0,'2026-08-27 12:13:09',NULL),(10,4,'/Luxury Images/apartments-classic-4/apt4-bedroom-main-view.jpg','Deluxe apartment bedroom','King bed with premium linens',1,1,'2026-08-27 12:13:09',NULL),(11,4,'/Luxury Images/apartments-classic-4/apt4-bathroom-shower-glass.jpg','Deluxe apartment bathroom','Luxury soaking tub',2,0,'2026-08-27 12:13:09',NULL),(12,4,'/Luxury Images/apartments-classic-4/apt4-living-room-sectional-sofa.jpg','Deluxe apartment panorama','Panoramic bushveld views',3,0,'2026-08-27 12:13:09',NULL);
/*!40000 ALTER TABLE `apartment_images` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `apartments`
--

LOCK TABLES `apartments` WRITE;
/*!40000 ALTER TABLE `apartments` DISABLE KEYS */;
INSERT INTO `apartments` (`id`, `page_id`, `category_id`, `name`, `slug`, `subtitle`, `tagline`, `description`, `price_per_night`, `price_currency`, `max_guests`, `room_size_m2`, `bedrooms`, `bathrooms`, `features`, `beds_description`, `hero_image`, `sort_order`, `is_published`, `is_featured`, `visible_from`, `visible_until`, `meta_title`, `meta_description`, `og_image`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,2,NULL,'Classic Apartment 1 (Bachelor)','bachelor-apartment','One Bedroom Apartment','Bachelor Suite','One Bedroom Apartment. The Deluxe Room at Viata Luxe offers breathtaking views of Phalaborwa, especially enchanting at night. Explore Phalaborwa with curated tours ΓÇö local culture and stunning landscapes. Breakfast and dinner available on request with menus from our affiliated exclusive restaurants, delivered to your apartment. 13 m┬▓, queen bed, self-catering.',950.00,'ZAR',2,13.0,1,1,'[\"Full kitchen\", \"Jacuzzi access\", \"Secure parking\"]','Queen 157cm','/Luxury Images/apartments-classic-1/apt1-kitchen-dining-main.jpg',1,1,0,NULL,NULL,NULL,NULL,NULL,'2026-08-27 12:13:09','2026-08-29 21:55:16',NULL),(2,2,NULL,'Classic Apartment 2','classic-apartment-2','Classic Suite','Classic Comfort','Sophisticated classic suite with city views, self-catering, en-suite bathroom and free WiFi. 13 m┬▓ queen apartment with DSTV.',950.00,'ZAR',2,13.0,1,1,'[\"Full kitchen\", \"Jacuzzi access\", \"Secure parking\"]','Queen 157cm','/Luxury Images/apartments-classic-2/apt2-bedroom-main-view.jpg',2,1,0,NULL,NULL,NULL,NULL,NULL,'2026-08-27 12:13:09','2026-08-29 21:55:16',NULL),(3,2,NULL,'Comfort Apartment 3','comfort-apartment-3','Comfort Suite','Comfort & Space','Spacious comfort apartment with queen bed, city views, self-catering kitchen, en-suite and free WiFi. 13 m┬▓.',1050.00,'ZAR',2,13.0,1,1,'[\"Private jacuzzi\", \"Full kitchen\", \"Outdoor boma\"]','Queen 157cm','/Luxury Images/apartments-classic-3/apt3-bedroom-main-view.jpg',3,1,1,NULL,NULL,NULL,NULL,NULL,'2026-08-27 12:13:09','2026-08-29 21:55:16',NULL),(4,2,NULL,'Deluxe Apartment 4','deluxe-apartment-4','Deluxe Suite','Premium Suite','Grand deluxe suite ΓÇö super clean units, new amenities, premium linens. 13 m┬▓ with city views and self-catering.',1200.00,'ZAR',2,13.0,1,1,'[\"Premium jacuzzi\", \"Full kitchen\", \"Outdoor boma\"]','Queen 157cm','/Luxury Images/apartments-classic-4/apt4-bedroom-main-view.jpg',4,1,0,NULL,NULL,NULL,NULL,NULL,'2026-08-27 12:13:09','2026-08-29 21:55:16',NULL);
/*!40000 ALTER TABLE `apartments` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `contact_submissions`
--

LOCK TABLES `contact_submissions` WRITE;
/*!40000 ALTER TABLE `contact_submissions` DISABLE KEYS */;
INSERT INTO `contact_submissions` VALUES (1,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö 10+ chars',0,0,NULL,'2026-08-27 15:05:57',NULL,NULL,NULL),(2,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö 10+ chars',0,0,NULL,'2026-08-27 15:06:23',NULL,NULL,NULL),(3,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö 10+ chars',0,0,NULL,'2026-08-27 16:06:23',NULL,NULL,NULL),(4,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö 10+ chars',0,0,NULL,'2026-08-27 18:11:26',NULL,NULL,NULL),(5,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö 10+ chars',0,0,NULL,'2026-08-27 18:15:37',NULL,NULL,NULL),(6,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö 10+ chars',0,0,NULL,'2026-08-27 18:32:14',NULL,NULL,NULL),(7,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö 10+ chars',0,0,NULL,'2026-08-27 18:55:42',NULL,NULL,NULL),(8,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö 10+ chars',0,0,NULL,'2026-08-27 19:00:32',NULL,NULL,NULL),(9,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö 10+ chars',0,0,NULL,'2026-08-27 19:03:06',NULL,NULL,NULL),(10,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö 10+ chars',0,0,NULL,'2026-08-27 19:44:27',NULL,NULL,NULL),(11,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö 10+ chars',0,0,NULL,'2026-08-27 19:46:04',NULL,NULL,NULL),(12,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö 10+ chars',0,0,NULL,'2026-08-27 19:49:13',NULL,NULL,NULL),(13,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö 10+ chars',0,0,NULL,'2026-08-27 19:49:40',NULL,NULL,NULL),(14,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö 10+ chars',0,0,NULL,'2026-08-27 21:40:58',NULL,NULL,NULL),(15,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö 10+ chars',0,0,NULL,'2026-08-27 21:44:06',NULL,NULL,NULL),(16,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö 10+ chars',0,0,NULL,'2026-08-28 00:54:10',NULL,NULL,NULL),(17,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö 10+ chars',0,0,NULL,'2026-08-28 01:06:41',NULL,NULL,NULL),(18,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö 10+ chars',0,0,NULL,'2026-08-28 08:57:32',NULL,NULL,NULL),(19,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö 10+ chars',0,0,NULL,'2026-08-28 09:32:30',NULL,NULL,NULL),(20,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö 10+ chars',0,0,NULL,'2026-08-28 11:21:48',NULL,NULL,NULL),(21,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö 10+ chars',0,0,NULL,'2026-08-28 11:26:01',NULL,NULL,NULL),(22,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö 10+ chars',0,0,NULL,'2026-08-28 11:28:23',NULL,NULL,NULL),(23,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö 10+ chars',0,0,NULL,'2026-08-28 11:35:50',NULL,NULL,NULL),(24,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö 10+ chars',0,0,NULL,'2026-08-28 11:38:49',NULL,NULL,NULL),(25,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö 10+ chars',0,0,NULL,'2026-08-29 07:45:39',NULL,NULL,NULL),(26,'Playwright Tester','pw@test.com','Brutal audit message from Playwright',0,0,NULL,'2026-08-29 15:09:32',NULL,NULL,NULL),(27,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö this is a test message with more than 10 chars',0,0,NULL,'2026-08-29 15:11:32',NULL,NULL,NULL),(28,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö this is a test message with more than 10 chars',0,0,NULL,'2026-08-29 15:16:47',NULL,NULL,NULL),(29,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö this is a test message with more than 10 chars',0,0,NULL,'2026-08-29 15:22:32',NULL,NULL,NULL),(30,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö this is a test message with more than 10 chars',0,0,NULL,'2026-08-29 20:01:25',NULL,NULL,NULL),(31,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö this is a test message with more than 10 chars',0,0,NULL,'2026-08-29 20:03:43',NULL,NULL,NULL),(32,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö this is a test message with more than 10 chars',0,0,NULL,'2026-08-29 21:16:03',NULL,NULL,NULL),(33,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö this is a test message with more than 10 chars',0,0,NULL,'2026-08-29 21:29:05',NULL,NULL,NULL),(34,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö this is a test message with more than 10 chars',0,0,NULL,'2026-08-29 21:33:34',NULL,NULL,NULL),(35,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö this is a test message with more than 10 chars',0,0,NULL,'2026-08-29 22:15:40',NULL,NULL,NULL),(36,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö this is a test message with more than 10 chars',0,0,NULL,'2026-08-29 22:22:17',NULL,NULL,NULL),(37,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö this is a test message with more than 10 chars',0,0,NULL,'2026-08-29 22:57:09',NULL,NULL,NULL),(38,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö this is a test message with more than 10 chars',0,0,NULL,'2026-08-30 01:33:24',NULL,NULL,NULL),(39,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö this is a test message with more than 10 chars',0,0,NULL,'2026-08-30 01:59:58',NULL,NULL,NULL),(40,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö this is a test message with more than 10 chars',0,0,NULL,'2026-08-30 02:02:49',NULL,NULL,NULL),(41,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö this is a test message with more than 10 chars',0,0,NULL,'2026-08-30 02:06:38',NULL,NULL,NULL),(42,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö this is a test message with more than 10 chars',0,0,NULL,'2026-08-30 02:30:07',NULL,NULL,NULL),(43,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö this is a test message with more than 10 chars',0,0,NULL,'2026-08-30 02:32:57',NULL,NULL,NULL),(44,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö this is a test message with more than 10 chars',0,0,NULL,'2026-08-30 02:36:15',NULL,NULL,NULL),(45,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö this is a test message with more than 10 chars',0,0,NULL,'2026-08-30 02:40:54',NULL,NULL,NULL),(46,'Playwright Tester','pw@test.com','Brutal audit message from Playwright ΓÇö this is a test message with more than 10 chars',0,0,NULL,'2026-08-30 02:46:09',NULL,NULL,NULL);
/*!40000 ALTER TABLE `contact_submissions` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `dining_items`
--

LOCK TABLES `dining_items` WRITE;
/*!40000 ALTER TABLE `dining_items` DISABLE KEYS */;
INSERT INTO `dining_items` VALUES (1,1,'Self-Catering','In your apartment','Full kitchen with oven, hob, microwave, fridge, and all utensils.',NULL,1,1,NULL,NULL,NULL,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(2,1,'Braai & Boma','Outdoor area','Traditional South African braai setup under the Limpopo stars.',NULL,2,1,NULL,NULL,NULL,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(3,1,'Local Restaurants','5-10 min drive','Bushveld dining, Italian, steakhouse ΓÇö curated recommendations on arrival.',NULL,3,1,NULL,NULL,NULL,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(4,1,'Private Bush Dinner','On request','Chef-prepared multi-course dinner in the bushveld setting.',NULL,4,1,NULL,NULL,NULL,'2026-08-29 17:53:06','2026-08-29 17:53:06');
/*!40000 ALTER TABLE `dining_items` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `faqs`
--

LOCK TABLES `faqs` WRITE;
/*!40000 ALTER TABLE `faqs` DISABLE KEYS */;
INSERT INTO `faqs` VALUES (1,2,'What time is check-in and check-out?','Check-in is from 14:00 (2pm) and check-out is by 11:00 (11am). Early check-in and late check-out may be arranged subject to availability.',1,1,NULL,NULL,'2026-08-27 12:13:09','2026-08-27 12:13:09',NULL),(2,2,'Is breakfast included?','Our apartments are self-catering with fully equipped kitchens. Breakfast hampers can be arranged on request at an additional cost.',2,1,NULL,NULL,'2026-08-27 12:13:09','2026-08-27 12:13:09',NULL),(3,2,'Do you accept children?','Yes, children of all ages are welcome. The Deluxe apartment is particularly suitable for families with its spacious layout.',3,1,NULL,NULL,'2026-08-27 12:13:09','2026-08-27 12:13:09',NULL),(4,2,'Is there WiFi?','Yes, complimentary high-speed WiFi is available throughout the guesthouse.',4,1,NULL,NULL,'2026-08-27 12:13:09','2026-08-27 12:13:09',NULL),(5,2,'Can I bring my pet?','Unfortunately, pets are not permitted due to the bushveld environment and wildlife.',5,1,NULL,NULL,'2026-08-27 12:13:09','2026-08-27 12:13:09',NULL),(6,2,'What is your cancellation policy?','Free cancellation up to 7 days before arrival. Cancellations within 7 days are subject to a 50% charge. No-shows are charged in full.',6,1,NULL,NULL,'2026-08-27 12:13:09','2026-08-27 12:13:09',NULL),(7,2,'Is parking secure?','Yes, we offer covered, secure parking for all guests at no additional charge.',7,1,NULL,NULL,'2026-08-27 12:13:09','2026-08-27 12:13:09',NULL),(8,2,'Do you offer airport transfers?','Airport transfers can be arranged on request. Please contact us for pricing and availability.',8,1,NULL,NULL,'2026-08-27 12:13:09','2026-08-27 12:13:09',NULL),(9,NULL,'How do I book?','You can book directly through our website using the NightsBridge booking system, or contact us via email or WhatsApp for assistance.',9,1,NULL,NULL,'2026-08-27 12:13:09','2026-08-27 12:13:09',NULL),(10,NULL,'What payment methods do you accept?','We accept EFT, credit cards (Visa, Mastercard), and instant payment. A 50% deposit is required to confirm your booking.',10,1,NULL,NULL,'2026-08-27 12:13:09','2026-08-29 15:01:57',NULL);
/*!40000 ALTER TABLE `faqs` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `gallery_categories`
--

LOCK TABLES `gallery_categories` WRITE;
/*!40000 ALTER TABLE `gallery_categories` DISABLE KEYS */;
INSERT INTO `gallery_categories` VALUES (1,'Luxe Bedrooms','bedrooms','Elegantly styled bedrooms ??? warm linen, curated details',1,1,NULL,NULL,'2026-08-28 13:23:57',NULL),(2,'Kitchens','kitchens','Fully equipped self-catering kitchens',2,1,NULL,NULL,'2026-08-28 13:23:57',NULL),(3,'Luxe Bathrooms','bathrooms','Modern ensuite bathrooms',3,1,NULL,NULL,'2026-08-28 13:23:57',NULL),(4,'Luxe Living Rooms','living','Open-plan living spaces with city views',4,1,NULL,NULL,'2026-08-28 13:23:57',NULL),(5,'Luxe Outdoors','outdoors','Pool, garden, braai and the Limpopo bushveld',5,1,NULL,NULL,'2026-08-28 13:23:57',NULL);
/*!40000 ALTER TABLE `gallery_categories` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `gallery_images`
--

LOCK TABLES `gallery_images` WRITE;
/*!40000 ALTER TABLE `gallery_images` DISABLE KEYS */;
INSERT INTO `gallery_images` VALUES (1,1,'Luxury Images/bedrooms/bedroom-chevron-pillows-headboard.jpg','Luxe Bedrooms ΓÇö chevron pillows headboard','Bedroom chevron',1,NULL,NULL,1,'2026-08-29 19:05:35',NULL),(2,1,'Luxury Images/bedrooms/bedroom-grey-curtains-ac-white-bedding.jpg','Luxe Bedrooms ΓÇö grey curtains','Grey curtains',2,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(3,1,'Luxury Images/bedrooms/bedroom-grey-headboard-mint-pillows.jpg','Luxe Bedrooms ΓÇö mint pillows','Mint pillows',3,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(4,1,'Luxury Images/bedrooms/bedroom-1-main-view-paisley.jpg','Luxe Bedrooms ΓÇö paisley main','Paisley main',4,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(5,1,'Luxury Images/bedrooms/bedroom-paisley-pillows-gold-throw.jpg','Luxe Bedrooms ΓÇö gold throw','Gold throw',5,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(6,1,'Luxury Images/bedrooms/bedroom-padded-headboard-grey-pillows.jpg','Luxe Bedrooms ΓÇö padded headboard','Padded headboard',6,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(7,1,'Luxury Images/bedrooms/bedroom-white-bedding-lamp-closeup.jpg','Luxe Bedrooms ΓÇö white bedding lamp','White bedding',7,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(8,1,'Luxury Images/apartments-classic-2/apt2-bedroom-main-view.jpg','Luxe Bedrooms ΓÇö Classic 2','Classic 2',8,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(9,1,'Luxury Images/apartments-classic-4/apt4-bedroom-main-view.jpg','Luxe Bedrooms ΓÇö Deluxe 4','Deluxe 4',9,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(10,2,'Luxury Images/kitchens/kitchen-wood-cabinets-marble-backsplash.jpg','Kitchens ΓÇö marble backsplash','Marble',2,NULL,NULL,1,'2026-08-29 19:05:35',NULL),(11,2,'Luxury Images/kitchens/kitchen-dining-set-fruits.jpg','Kitchens ΓÇö dining fruits','Dining fruits',2,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(12,2,'Luxury Images/kitchens/kitchen-red-fridge-round-table.jpg','Kitchens ΓÇö red fridge','Red fridge',3,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(13,2,'Luxury Images/kitchens/kitchen-stove-counter-closeup.jpg','Kitchens ΓÇö stove counter','Stove',4,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(14,2,'Luxury Images/apartments-classic-1/apt1-kitchen-dining-main.jpg','Kitchens ΓÇö Classic 1','Classic 1',5,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(15,2,'Luxury Images/apartments-classic-3/apt3-kitchen-wide-angle.jpg','Kitchens ΓÇö Comfort 3 wide','Comfort 3',6,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(16,2,'Luxury Images/apartments-classic-4/apt4-kitchen-wide-angle.jpg','Kitchens ΓÇö Deluxe 4 wide','Deluxe 4',7,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(17,2,'Luxury Images/food-dining/scones-closeup-bowl.jpg','Kitchens ΓÇö scones bowl','Scones',4,NULL,NULL,1,'2026-08-29 19:05:35',NULL),(18,2,'Luxury Images/food-dining/rose-champagne-berries-tray.jpg','Kitchens ΓÇö champagne tray','Champagne',9,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(19,3,'Luxury Images/bathrooms/bathroom-1-sink-toilet-yellow-mat.jpg','Bathroom ΓÇö yellow mat','Yellow mat',7,NULL,NULL,1,'2026-08-29 19:05:35',NULL),(20,3,'Luxury Images/bathrooms/bathroom-1-shower-glass-toilet.jpg','Bathroom ΓÇö glass toilet','Glass toilet',2,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(21,3,'Luxury Images/bathrooms/bathroom-shower-head-closeup.jpg','Bathroom ΓÇö shower head','Shower head',3,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(22,3,'Luxury Images/apartments-classic-2/apt2-bathroom-sink-area.jpg','Bathroom ΓÇö Classic 2 sink','Classic 2',4,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(23,3,'Luxury Images/apartments-classic-2/apt2-bathroom-toilet-view.jpg','Bathroom ΓÇö Classic 2 toilet','Classic 2 toilet',5,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(24,3,'Luxury Images/apartments-classic-3/apt3-bathroom-sink-toilet.jpg','Bathroom ΓÇö Comfort 3','Comfort 3',6,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(25,3,'Luxury Images/apartments-classic-3/apt3-bathroom-faucet-closeup.jpg','Bathroom ΓÇö Comfort 3 faucet','Faucet',7,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(26,3,'Luxury Images/apartments-classic-4/apt4-bathroom-shower-glass.jpg','Bathroom ΓÇö Deluxe 4 shower','Deluxe shower',8,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(27,3,'Luxury Images/apartments-classic-4/apt4-bathroom-sink-mirror.jpg','Bathroom ΓÇö Deluxe 4 sink','Deluxe sink',9,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(28,4,'Luxury Images/living-rooms/living-room-tv-smart-console.jpg','Living ΓÇö smart console','Smart console',3,NULL,NULL,1,'2026-08-29 19:05:35',NULL),(29,4,'Luxury Images/living-rooms/living-room-black-sofas-tv-unit.jpg','Living ΓÇö black sofas','Black sofas',2,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(30,4,'Luxury Images/living-rooms/living-room-brown-sofa-leaf-pillows.jpg','Living ΓÇö leaf pillows','Leaf pillows',3,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(31,4,'Luxury Images/living-rooms/living-room-1-orange-cushions.jpg','Living ΓÇö orange cushions','Orange cushions',4,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(32,4,'Luxury Images/apartments-classic-2/apt2-living-room-main-view.jpg','Living ΓÇö Classic 2','Classic 2',5,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(33,4,'Luxury Images/apartments-classic-3/apt3-living-room-entertainment-unit.jpg','Living ΓÇö Comfort 3','Comfort 3',6,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(34,4,'Luxury Images/apartments-classic-4/apt4-living-room-sectional-sofa.jpg','Living ΓÇö Deluxe 4','Deluxe 4',7,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(35,4,'Luxury Images/bedrooms/bedroom-1-side-angle-divider.jpg','Living ΓÇö divider','Divider',8,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(36,4,'Luxury Images/gallery-scenic/exterior-grey-cottages-red-doors.jpg','Living ΓÇö exterior','Exterior',9,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(37,5,'Luxury Images/pool/pool-overview-entertainment-area.jpg','Outdoors ΓÇö entertainment area','Pool entertainment',1,NULL,NULL,1,'2026-08-29 19:05:35',NULL),(38,5,'Luxury Images/pool/pool-overview-gazebo-garden.jpg','Outdoors ΓÇö gazebo garden','Gazebo garden',5,NULL,NULL,1,'2026-08-29 19:05:35',NULL),(39,5,'Luxury Images/pool/pool-overview-gazebo-angle.jpg','Outdoors ΓÇö gazebo angle','Gazebo angle',3,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(40,5,'Luxury Images/pool/poolside-refreshments-drinks.jpg','Outdoors ΓÇö refreshments','Refreshments',4,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(41,5,'Luxury Images/activities/elephants-river-crossing-herd.jpg','Outdoors ΓÇö elephants crossing','Elephants',5,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(42,5,'Luxury Images/activities/elephants-river-herd-grazing.jpg','Outdoors ΓÇö elephants grazing','Elephants grazing',6,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(43,5,'Luxury Images/activities/zebra-golden-hour-closeup.jpg','Outdoors ΓÇö zebra golden hour','Zebra',6,NULL,NULL,1,'2026-08-29 19:05:35',NULL),(44,5,'Luxury Images/activities/hippos-water-group.jpg','Outdoors ΓÇö hippos','Hippos',8,NULL,NULL,0,'2026-08-29 19:05:35',NULL),(45,5,'Luxury Images/gallery-scenic/wildlife-buffalo-closeup-herd.jpg','Outdoors ΓÇö buffalo herd','Buffalo',8,NULL,NULL,1,'2026-08-29 19:05:35',NULL);
/*!40000 ALTER TABLE `gallery_images` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `hero_slides`
--

LOCK TABLES `hero_slides` WRITE;
/*!40000 ALTER TABLE `hero_slides` DISABLE KEYS */;
INSERT INTO `hero_slides` VALUES (1,1,'Luxury Images/pool/pool-overview-entertainment-area.jpg','Pool nestled in lush garden at golden hour ΓÇö Viata Luxe','Serenity by the Pool ΓÇö Lush garden, golden hour',NULL,1,1,NULL,NULL,NULL,'2026-08-29 17:53:06','2026-08-29 19:27:18'),(2,1,'Luxury Images/bedrooms/bedroom-chevron-pillows-headboard.jpg','Bedroom with chevron pillows and warm linen ΓÇö Viata Luxe','Our Rooms ΓÇö Elegantly decorated, tranquil',NULL,2,1,NULL,NULL,NULL,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(3,1,'Luxury Images/food-dining/rose-champagne-berries-tray.jpg','Ros├⌐ champagne and berries tray on crisp linen ΓÇö Viata Luxe','Dining Options ΓÇö Gourmet delivered to your apartment',NULL,3,1,NULL,NULL,NULL,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(4,1,'Luxury Images/activities/elephants-river-crossing-herd.jpg','Elephants crossing river at sunset ΓÇö Kruger safari','Safari ΓÇö Kruger minutes away, Kedibone Safari',NULL,4,1,NULL,NULL,NULL,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(5,1,'Luxury Images/gallery-scenic/exterior-grey-cottages-red-doors.jpg','Viata Luxe exterior ΓÇö grey cottages with red doors, paved courtyard','86 Nollie Bosman Street ΓÇö Phalaborwa, Limpopo',NULL,5,1,NULL,NULL,NULL,'2026-08-29 17:53:06','2026-08-29 17:53:06');
/*!40000 ALTER TABLE `hero_slides` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `moments`
--

LOCK TABLES `moments` WRITE;
/*!40000 ALTER TABLE `moments` DISABLE KEYS */;
INSERT INTO `moments` VALUES (1,1,'Luxury Images/pool/pool-overview-gazebo-garden.jpg','Outdoor chillers ΓÇö gazebo garden','Relaxation','Relaxation in Our Outdoor Chillers','Cozy nooks to unwind, enjoy a refreshing drink ΓÇö designed for guests to truly relax.',1,1,NULL,NULL,NULL,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(2,1,'Luxury Images/activities/braai-outdoor-chicken-grilling.jpg','Braai under the stars ΓÇö well-equipped braai area','Tradition','Braai Under the Stars','The quintessential South African tradition ΓÇö well-equipped braai area invites you to gather.',2,1,NULL,NULL,NULL,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(3,1,'Luxury Images/pool/poolside-refreshments-drinks.jpg','Serenity by the pool ΓÇö lush garden escape','Tranquility','Serenity by the Pool','Tranquility meets luxury ΓÇö outdoor pool nestled within lush garden, escape from the African sun.',3,1,NULL,NULL,NULL,'2026-08-29 17:53:06','2026-08-29 17:53:06');
/*!40000 ALTER TABLE `moments` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `navigation`
--

LOCK TABLES `navigation` WRITE;
/*!40000 ALTER TABLE `navigation` DISABLE KEYS */;
INSERT INTO `navigation` VALUES (1,'Home',NULL,1,NULL,1,1,NULL,NULL,0,NULL,'2026-08-27 12:13:09',NULL),(2,'Accommodation',NULL,2,NULL,2,1,NULL,NULL,0,NULL,'2026-08-27 12:13:09',NULL),(3,'Gallery',NULL,3,NULL,4,1,NULL,NULL,0,NULL,'2026-08-27 12:13:09',NULL),(4,'Safari',NULL,4,NULL,3,1,NULL,NULL,0,NULL,'2026-08-27 12:13:09',NULL),(5,'Contact',NULL,5,NULL,5,1,NULL,NULL,0,NULL,'2026-08-27 12:13:09',NULL),(7,'Book Now','https://book.nightsbridge.com/38331',NULL,NULL,7,0,NULL,NULL,1,NULL,'2026-08-27 12:13:09',NULL),(8,'About',NULL,6,NULL,6,1,NULL,NULL,0,NULL,'2026-08-29 19:05:34',NULL);
/*!40000 ALTER TABLE `navigation` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `page_seo`
--

LOCK TABLES `page_seo` WRITE;
/*!40000 ALTER TABLE `page_seo` DISABLE KEYS */;
INSERT INTO `page_seo` VALUES (1,1,'LodgingBusiness','{\"geo\": {\"@type\": \"GeoCoordinates\", \"latitude\": -23.952, \"longitude\": 31.145}, \"url\": \"https://viataluxe.com\", \"name\": \"Viata Luxe Guesthouse\", \"@type\": \"LodgingBusiness\", \"email\": \"info@viataluxe.com\", \"image\": \"https://viataluxe.com/Luxury%20Images/pool/pool-overview-entertainment-area.jpg\", \"address\": {\"@type\": \"PostalAddress\", \"postalCode\": \"1390\", \"addressRegion\": \"Limpopo\", \"streetAddress\": \"86 Nollie Bosman Street\", \"addressCountry\": \"ZA\", \"addressLocality\": \"Phalaborwa\"}, \"@context\": \"https://schema.org\", \"telephone\": \"+27157810518\", \"priceRange\": \"R950-R1200\", \"description\": \"Luxury self-catering guesthouse in Phalaborwa, minutes from Kruger National Park. 4 curated apartments with city views, tours, and personalised service.\", \"amenityFeature\": [{\"name\": \"Free WiFi\", \"@type\": \"LocationFeatureSpecification\", \"value\": true}, {\"name\": \"Secure Parking\", \"@type\": \"LocationFeatureSpecification\", \"value\": true}, {\"name\": \"Air Conditioning\", \"@type\": \"LocationFeatureSpecification\", \"value\": true}, {\"name\": \"Self-Catering\", \"@type\": \"LocationFeatureSpecification\", \"value\": true}, {\"name\": \"Pool\", \"@type\": \"LocationFeatureSpecification\", \"value\": true}], \"hasOfferCatalog\": {\"name\": \"Viata Luxe Apartments\", \"@type\": \"OfferCatalog\", \"itemListElement\": [{\"@type\": \"Offer\", \"price\": \"950\", \"itemOffered\": {\"name\": \"Classic Apartment 1 (Bachelor)\", \"@type\": \"Room\"}, \"priceCurrency\": \"ZAR\"}, {\"@type\": \"Offer\", \"price\": \"950\", \"itemOffered\": {\"name\": \"Classic Apartment 2\", \"@type\": \"Room\"}, \"priceCurrency\": \"ZAR\"}, {\"@type\": \"Offer\", \"price\": \"1050\", \"itemOffered\": {\"name\": \"Comfort Apartment 3\", \"@type\": \"Room\"}, \"priceCurrency\": \"ZAR\"}, {\"@type\": \"Offer\", \"price\": \"1200\", \"itemOffered\": {\"name\": \"Deluxe Apartment 4\", \"@type\": \"Room\"}, \"priceCurrency\": \"ZAR\"}]}}','{\"og:type\": \"website\", \"og:site_name\": \"Viata Luxe Guesthouse\"}','2026-08-27 12:13:09','2026-08-29 17:53:06',NULL),(2,2,'WebPage','{\"name\": \"Accommodation\", \"@type\": \"WebPage\", \"@context\": \"https://schema.org\", \"description\": \"4 luxury self-catering apartments in Limpopo\"}',NULL,'2026-08-27 12:13:09','2026-08-27 12:13:09',NULL),(3,3,'WebPage','{\"name\": \"Gallery\", \"@type\": \"WebPage\", \"@context\": \"https://schema.org\", \"description\": \"Photos of Viata Luxe Guesthouse apartments and facilities\"}',NULL,'2026-08-27 12:13:09','2026-08-27 12:13:09',NULL),(4,4,'WebPage','{\"name\": \"Safari & Activities\", \"@type\": \"WebPage\", \"@context\": \"https://schema.org\", \"description\": \"Game drives and bushveld experiences in Limpopo\"}',NULL,'2026-08-27 12:13:09','2026-08-27 12:13:09',NULL),(5,5,'ContactPage','{\"name\": \"Contact Us\", \"@type\": \"ContactPage\", \"@context\": \"https://schema.org\", \"description\": \"Contact Viata Luxe Guesthouse\"}',NULL,'2026-08-27 12:13:09','2026-08-27 12:13:09',NULL),(6,6,'AboutPage','{\"name\": \"About Us\", \"@type\": \"AboutPage\", \"@context\": \"https://schema.org\", \"description\": \"The story behind Viata Luxe Guesthouse\"}',NULL,'2026-08-27 12:13:09','2026-08-27 12:13:09',NULL);
/*!40000 ALTER TABLE `page_seo` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,'home','Home','Luxury Self-Catering in Limpopo','Viata Luxe Guesthouse ΓÇö Luxury Accommodation in Phalaborwa Near Kruger National Park','Discover Viata Luxe Guesthouse in Phalaborwa ΓÇö your elegant home away from home just minutes from Kruger National Park. Book luxury accommodation with comfort, style, and top-tier service.','https://viataluxe.com/Luxury%20Images/pool/pool-overview-entertainment-area.jpg',NULL,NULL,NULL,NULL,NULL,'left','home',1,1,NULL,NULL,1,'2026-08-27 12:13:09','2026-08-29 18:45:30',NULL),(2,'accommodation','Accommodation','4 Luxury Apartments','Accommodation ΓÇö Viata Luxe Guesthouse','Choose from 4 luxury self-catering apartments in Limpopo. Queen beds, full kitchens, jacuzzi access, secure parking.',NULL,NULL,'Luxury Images/home-hero/hero-bedroom-chevron-pillows-wide.jpg','Accommodation ΓÇö 4 Apartments ┬╖ Viata Luxe',NULL,NULL,'left','default',1,0,NULL,NULL,2,'2026-08-27 12:13:09','2026-08-30 10:57:23',NULL),(3,'gallery','Gallery','See Our Spaces','Gallery ΓÇö Viata Luxe Guesthouse','Browse photos of our luxury apartments, facilities, and the beautiful Limpopo bushveld.',NULL,NULL,'Luxury Images/home-hero/hero-living-dining-eclectic.jpg','Gallery ΓÇö Luxe Bedrooms, Kitchens, Bathrooms, Living Rooms, Outdoors',NULL,NULL,'left','default',1,0,NULL,NULL,3,'2026-08-27 12:13:09','2026-08-30 10:57:23',NULL),(4,'safari','Safari & Activities','Discover Limpopo','Safari & Activities ΓÇö Viata Luxe Guesthouse','Game drives, bushveld walks, and Limpopo adventures. Your gateway to South Africa\'s wildlife.',NULL,NULL,'Luxury Images/gallery-scenic/wildlife-buffalo-closeup-herd.jpg','Safari ΓÇö Kedibone Safari Tours and Activities','Kedibone <em>Safari.</em>','At Viata Luxe Guesthouse, we proudly collaborate with <strong>Kedibone Safari</strong> to offer thrilling wildlife and adventure &mdash; <strong>Daily Kruger Safaris</strong> from Phalaborwa Gate + <strong>Exclusive Private Overnight Kruger Tours</strong> + <strong>Wildlife Photographic Safaris</strong> + <strong>Photographic &amp; Lightroom Training</strong> &mdash; professional guidance, immersive wild.','left','default',1,0,NULL,NULL,4,'2026-08-27 12:13:09','2026-08-30 10:57:23',NULL),(5,'contact','Contact Us','Get in Touch','Contact ΓÇö Viata Luxe Guesthouse','Contact Viata Luxe Guesthouse for reservations, inquiries, and special requests.',NULL,NULL,'Luxury Images/pool/pool-overview-entertainment-area.jpg','Contact ΓÇö Reach Us Anytime',NULL,NULL,'center','default',1,0,NULL,NULL,5,'2026-08-27 12:13:09','2026-08-30 10:30:52',NULL),(6,'about','About Us','Our Story','About ΓÇö Viata Luxe Guesthouse','Learn about Viata Luxe Guesthouse, our story, and our commitment to luxury hospitality.',NULL,NULL,NULL,NULL,NULL,NULL,'left','default',1,0,NULL,NULL,6,'2026-08-27 12:13:09','2026-08-29 19:05:35',NULL);
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `promise_pillars`
--

LOCK TABLES `promise_pillars` WRITE;
/*!40000 ALTER TABLE `promise_pillars` DISABLE KEYS */;
INSERT INTO `promise_pillars` VALUES (1,1,'Our Rooms','ΓùÉ','Elegantly decorated Bachelor and Superior apartments ΓÇö sophistication and tranquility for your getaway.',NULL,1,1,NULL,NULL,NULL,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(2,1,'Our Amenities','Γ¼ó','Fresh breakfast on request, free Wi-Fi, secure parking ΓÇö attentive staff, easy Kruger access.',NULL,2,1,NULL,NULL,NULL,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(3,1,'Dining Options','Γ£ª','Breakfast & dinner on request ΓÇö gourmet menus delivered to your apartment, indulgent and relaxed.',NULL,3,1,NULL,NULL,NULL,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(4,1,'Safari ΓÇö Kedibone','Γùë','Daily Kruger Safaris from Phalaborwa Gate + Private Overnight Tours ΓÇö intimate, luxurious.',NULL,4,1,NULL,NULL,NULL,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(5,1,'Moments at Viata Luxe','Γÿ╛','Relaxation in outdoor chillers ┬╖ Braai under the stars ┬╖ Serenity by the pool ΓÇö garden, fire, water.',NULL,5,1,NULL,NULL,NULL,'2026-08-29 17:53:06','2026-08-29 17:53:06');
/*!40000 ALTER TABLE `promise_pillars` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `public_categories`
--

LOCK TABLES `public_categories` WRITE;
/*!40000 ALTER TABLE `public_categories` DISABLE KEYS */;
INSERT INTO `public_categories` VALUES (1,'apartment','Classic','classic',NULL,1,1,'2026-08-30 06:03:36','2026-08-30 06:03:36'),(2,'apartment','Luxury','luxury',NULL,2,1,'2026-08-30 06:03:36','2026-08-30 06:03:36'),(3,'apartment','Family','family',NULL,3,1,'2026-08-30 06:03:36','2026-08-30 06:03:36'),(4,'gallery','Bedrooms','bedrooms',NULL,1,1,'2026-08-30 06:03:36','2026-08-30 06:03:36'),(5,'gallery','Bathrooms','bathrooms',NULL,2,1,'2026-08-30 06:03:36','2026-08-30 06:03:36'),(6,'gallery','Kitchen & Dining','kitchen-dining',NULL,3,1,'2026-08-30 06:03:36','2026-08-30 06:03:36'),(7,'gallery','Pool & Entertainment','pool-entertainment',NULL,4,1,'2026-08-30 06:03:36','2026-08-30 06:03:36'),(8,'gallery','Scenic Views','scenic-views',NULL,5,1,'2026-08-30 06:03:36','2026-08-30 06:03:36'),(9,'safari','Game Drives','game-drives',NULL,1,1,'2026-08-30 06:03:36','2026-08-30 06:03:36'),(10,'safari','Bush Walks','bush-walks',NULL,2,1,'2026-08-30 06:03:36','2026-08-30 06:03:36'),(11,'safari','Photography','photography',NULL,3,1,'2026-08-30 06:03:36','2026-08-30 06:03:36'),(12,'safari','Cultural','cultural',NULL,4,1,'2026-08-30 06:03:36','2026-08-30 06:03:36');
/*!40000 ALTER TABLE `public_categories` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `safari_activities`
--

LOCK TABLES `safari_activities` WRITE;
/*!40000 ALTER TABLE `safari_activities` DISABLE KEYS */;
INSERT INTO `safari_activities` VALUES (5,'Kedibone Safari ΓÇö Daily Safaris','Daily Kruger Safaris from the Phalaborwa Gate - an immersive day in the wild - and Exclusive Private Overnight Kruger Tours for a more intimate, luxurious safari.','uploads/safari/game-drive.jpg','[\"https://youtu.be/QSGZBKwRycw\"]',NULL,'Watch Safari Video',1,1,NULL,NULL,'2026-08-27 17:43:56','2026-08-29 21:39:25',NULL),(6,'Classic Safari ΓÇö Photographic','Wildlife photographic safaris and Lightroom training with professional guidance.','uploads/safari/bushwalk.jpg','[\"https://youtu.be/UHpP4w8cBlI\"]',NULL,'Watch Video',2,1,NULL,NULL,'2026-08-27 17:43:56','2026-08-29 21:39:25',NULL),(7,'Boat Safaris ΓÇö Olifants River','Scenic boat safaris on the Olifants River ΓÇö hippos, crocodiles, diverse birdlife. Visit Foskor Mine Museum and Masorini Archaeological Site.','uploads/safari/cultural.jpg','[\"https://youtu.be/aZXatNfE3Ww\"]',NULL,'Watch Video',3,1,NULL,NULL,'2026-08-27 17:43:56','2026-08-29 21:39:25',NULL),(8,'Adventure ΓÇö Blyde & Amarula','Blyde River Canyon, one of the largest canyons in the world ΓÇö hiking and boat trips. Visit the Amarula Lapa for a tasting.','uploads/safari/birding.jpg','[\"https://youtu.be/sz-FMRRfpIk\"]',NULL,'Watch Video',4,1,NULL,NULL,'2026-08-27 17:43:56','2026-08-29 21:39:25',NULL);
/*!40000 ALTER TABLE `safari_activities` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `section_orientation`
--

LOCK TABLES `section_orientation` WRITE;
/*!40000 ALTER TABLE `section_orientation` DISABLE KEYS */;
INSERT INTO `section_orientation` VALUES (1,1,'full-width',NULL,NULL,NULL,'0','0','2rem','2rem','1200px','center','center','fade-up','stack','2026-08-27 12:13:09','2026-08-27 12:13:09'),(2,2,'centered',NULL,NULL,NULL,'4rem','4rem','2rem','2rem','1200px','center','center','fade-up','stack','2026-08-27 12:13:09','2026-08-27 12:13:09'),(3,3,'text-right',NULL,NULL,NULL,'4rem','4rem','2rem','2rem','1200px','left','center','fade-up','stack','2026-08-27 12:13:09','2026-08-27 12:13:09'),(4,4,'full-width','var(--cream)',NULL,NULL,'4rem','4rem','2rem','2rem','1200px','center','center','fade-up','stack','2026-08-27 12:13:09','2026-08-30 02:27:03'),(5,5,'centered',NULL,NULL,NULL,'4rem','4rem','2rem','2rem','1200px','center','center','fade-up','stack','2026-08-27 12:13:09','2026-08-27 12:13:09'),(6,6,'full-width','#0B1D33',NULL,'#F8F6F1','5rem','5rem','2rem','2rem','1200px','center','center','fade-up','stack','2026-08-27 12:13:09','2026-08-27 12:13:09'),(7,7,'text-left','var(--white)',NULL,NULL,'4rem','4rem','2rem','2rem','1200px','left','center','fade-up','stack','2026-08-27 12:13:09','2026-08-30 02:27:03'),(8,8,'centered',NULL,NULL,NULL,'4rem','4rem','2rem','2rem','1200px','center','center','fade-up','stack','2026-08-27 12:13:09','2026-08-27 12:13:09'),(9,9,'full-width','#C9A84C',NULL,'#0B1D33','2rem','2rem','2rem','2rem','1200px','center','center','slide-left','stack','2026-08-27 12:13:09','2026-08-27 12:13:09'),(10,10,'full-width',NULL,NULL,NULL,'0','0','2rem','2rem','1200px','center','center','fade-up','stack','2026-08-27 12:13:09','2026-08-27 12:13:09'),(11,11,'centered',NULL,NULL,NULL,'4rem','4rem','2rem','2rem','1200px','center','center','fade-up','stack','2026-08-27 12:13:09','2026-08-27 12:13:09'),(12,12,'centered',NULL,NULL,NULL,'4rem','4rem','2rem','2rem','1200px','center','center','fade-up','stack','2026-08-27 12:13:09','2026-08-27 12:13:09'),(13,13,'full-width','#0B1D33',NULL,'#F8F6F1','5rem','5rem','2rem','2rem','1200px','center','center','fade-up','stack','2026-08-27 12:13:09','2026-08-27 12:13:09'),(14,14,'full-width',NULL,NULL,NULL,'0','0','2rem','2rem','1200px','center','center','fade-up','stack','2026-08-27 12:13:09','2026-08-27 12:13:09'),(15,15,'centered',NULL,NULL,NULL,'4rem','4rem','2rem','2rem','1200px','center','center','fade-up','stack','2026-08-27 12:13:09','2026-08-27 12:13:09'),(16,16,'full-width',NULL,NULL,NULL,'0','0','2rem','2rem','1200px','center','center','fade-up','stack','2026-08-27 12:13:09','2026-08-27 12:13:09'),(17,17,'text-left',NULL,NULL,NULL,'4rem','4rem','2rem','2rem','1200px','left','center','fade-up','stack','2026-08-27 12:13:09','2026-08-27 12:13:09'),(18,18,'centered',NULL,NULL,NULL,'4rem','4rem','2rem','2rem','1200px','center','center','fade-up','stack','2026-08-27 12:13:09','2026-08-27 12:13:09'),(19,19,'full-width','#0B1D33',NULL,'#F8F6F1','5rem','5rem','2rem','2rem','1200px','center','center','fade-up','stack','2026-08-27 12:13:09','2026-08-27 12:13:09'),(20,20,'full-width',NULL,NULL,NULL,'0','0','2rem','2rem','1200px','center','center','fade-up','stack','2026-08-27 12:13:09','2026-08-27 12:13:09'),(21,21,'centered',NULL,NULL,NULL,'4rem','4rem','2rem','2rem','1200px','center','center','fade-up','stack','2026-08-27 12:13:09','2026-08-27 12:13:09'),(22,22,'text-left',NULL,NULL,NULL,'4rem','4rem','2rem','2rem','1200px','left','center','fade-up','stack','2026-08-27 12:13:09','2026-08-27 12:13:09'),(23,23,'centered',NULL,NULL,NULL,'4rem','4rem','2rem','2rem','1200px','center','center','fade-up','stack','2026-08-27 12:13:09','2026-08-27 12:13:09'),(24,24,'full-width','#0B1D33',NULL,'#F8F6F1','5rem','5rem','2rem','2rem','1200px','center','center','fade-up','stack','2026-08-27 12:13:09','2026-08-27 12:13:09'),(25,25,'centered',NULL,NULL,NULL,'4rem','4rem','2rem','2rem','1200px','left','center','fade-up','stack','2026-08-27 12:16:37','2026-08-27 12:16:37'),(26,26,'centered',NULL,NULL,NULL,'4rem','4rem','2rem','2rem','1200px','left','center','fade-up','stack','2026-08-27 12:16:37','2026-08-27 12:16:37'),(27,27,'text-right',NULL,NULL,NULL,'4rem','4rem','2rem','2rem','1200px','left','center','fade-up','stack','2026-08-27 17:54:42','2026-08-27 17:54:42'),(28,28,'centered',NULL,NULL,NULL,'4rem','4rem','2rem','2rem','1200px','center','center','fade-up','stack','2026-08-27 18:03:52','2026-08-27 18:03:52');
/*!40000 ALTER TABLE `section_orientation` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `sections`
--

LOCK TABLES `sections` WRITE;
/*!40000 ALTER TABLE `sections` DISABLE KEYS */;
INSERT INTO `sections` VALUES (1,1,'hero','Viata Guesthouse ΓÇö Luxury in Phalaborwa','Phalaborwa ┬╖ Minutes to Kruger National Park','Prepare to embark on an unexpected soul journey as you enter Viata Luxe Guest House, nestled in the tranquil town of Phalaborwa, just moments from the Kruger National Park. Personalized service, elegant interiors, and a captivating atmosphere that celebrates nature and relaxation.','uploads/hero/hero-main.jpg','https://book.nightsbridge.com/38331','Book Now ΓÇö NightsBridge',10,1,NULL,NULL,'hero--slideshow','2026-08-27 12:13:09','2026-08-29 17:53:06',NULL),(2,1,'stats','Why Viata Luxe?',NULL,'[{\"value\":\"4\",\"label\":\"Luxury Apartments\"},{\"value\":\"5\",\"label\":\"Minutes to Kruger\"},{\"value\":\"4.8\",\"label\":\"Guest Rating\"},{\"value\":\"100\",\"label\":\"Self-Catering\"}]',NULL,NULL,NULL,80,1,NULL,NULL,NULL,'2026-08-27 12:13:09','2026-08-29 17:53:06',NULL),(3,1,'promise','Prepare to embark.','Viata Guesthouse ΓÇö Luxury Accommodation in Phalaborwa','<p>True to its promise of luxury, a stay at Viata Luxe is marked by <strong>personalized service, elegant interiors, and a captivating atmosphere</strong> that celebrates the beauty of nature and relaxation. Here, every detail is thoughtfully curated to create an extraordinary experience ΓÇö the perfect retreat for comfort and indulgence.</p><p>Discover Viata Luxe Guesthouse, a premier destination that combines luxury with exceptional service. Our dedicated, well-trained staff is committed to making every guest\'s stay truly memorable, from arrival to departure.</p>','uploads/about/guesthouse-exterior.jpg','/accommodation','View Apartments',30,1,NULL,NULL,'promise','2026-08-27 12:13:09','2026-08-29 17:53:06',NULL),(4,1,'gallery','45 frames. One story.','Gallery preview',NULL,NULL,'/gallery','Open Gallery',60,1,NULL,NULL,NULL,'2026-08-27 12:13:09','2026-08-30 02:18:13',NULL),(5,1,'testimonials','Guest Voices','What Our Guests Say','From around the world, our guests share their Viata Luxe experience.',NULL,NULL,NULL,100,1,NULL,NULL,NULL,'2026-08-27 12:13:09','2026-08-29 17:53:06',NULL),(6,1,'booking-cta','Come as you are. Leave at gate open.','Experience Luxury','One check to confirm dates. Rooms, bush, gate time ΓÇö all on the next pages.',NULL,NULL,'Minutes to gate | From R950 / night | Host on arrival',130,1,NULL,NULL,NULL,'2026-08-27 12:13:09','2026-08-29 17:53:06',NULL),(7,1,'safari-teaser','Kedibone Safari.','Safari ΓÇö Kedibone','In collaboration with Kedibone Safari we offer a wide range of wildlife and adventure experiences ΓÇö Daily Kruger Safaris from Phalaborwa Gate, Exclusive Private Overnight Kruger Tours, Wildlife Photographic Safaris and Photographic & Lightroom Training.','uploads/safari/safari-hero.jpg','/safari','Explore Safari',70,1,NULL,NULL,NULL,'2026-08-27 12:13:09','2026-08-30 15:01:36',NULL),(8,1,'pricing','Our Apartments','4 Luxury Options','Choose from our range of luxury self-catering apartments.',NULL,NULL,NULL,90,1,NULL,NULL,NULL,'2026-08-27 12:13:09','2026-08-29 17:53:06',NULL),(9,1,'specials','Stay 3 nights, save 10%','Limited Time','Book direct via WhatsApp and mention this offer. Valid for stays before 31 October 2026.',NULL,'https://wa.me/27618417838?text=Hi%20Viata%20Luxe%2C%20I%E2%80%99d%20like%20to%20enquire%20about%20the%203-night%20stay%20offer.','Claim Offer',120,1,NULL,NULL,NULL,'2026-08-27 12:13:09','2026-08-29 17:53:06',NULL),(10,2,'hero','Accommodation','Luxury Self-Catering Apartments',NULL,'uploads/hero/accommodation-hero.jpg',NULL,NULL,1,1,NULL,NULL,NULL,'2026-08-27 12:13:09','2026-08-27 12:13:09',NULL),(11,2,'apartment-cards','Our Apartments','4 Luxury Options','Each apartment offers a unique experience with premium finishes and bushveld views.',NULL,NULL,NULL,2,1,NULL,NULL,NULL,'2026-08-27 12:13:09','2026-08-27 12:13:09',NULL),(12,2,'stats','Every Apartment Includes',NULL,'[{\"value\":\"100%\",\"label\":\"Free WiFi\"},{\"value\":\"24/7\",\"label\":\"Security\"},{\"value\":\"Free\",\"label\":\"Parking\"},{\"value\":\"All\",\"label\":\"Full Kitchen\"}]',NULL,NULL,NULL,3,1,NULL,NULL,NULL,'2026-08-27 12:13:09','2026-08-27 12:13:09',NULL),(13,2,'booking-cta','Reserve Your Apartment','Instant Confirmation','Book online for instant confirmation. Best rates guaranteed.',NULL,NULL,NULL,4,1,NULL,NULL,NULL,'2026-08-27 12:13:09','2026-08-27 12:13:09',NULL),(14,3,'hero','Gallery','A Visual Journey',NULL,'uploads/hero/gallery-hero.jpg',NULL,NULL,1,1,NULL,NULL,NULL,'2026-08-27 12:13:09','2026-08-27 12:13:09',NULL),(15,3,'gallery','Our Spaces','Browse Photos',NULL,NULL,NULL,NULL,2,1,NULL,NULL,NULL,'2026-08-27 12:13:09','2026-08-27 12:13:09',NULL),(16,4,'hero','Safari & Activities','Discover Limpopo',NULL,'uploads/hero/safari-hero.jpg',NULL,NULL,1,1,NULL,NULL,NULL,'2026-08-27 12:13:09','2026-08-27 12:13:09',NULL),(17,4,'safari-teaser','Game Drives','Big 5 Encounters','Experience the thrill of seeing elephants, lions, leopards, rhinos, and buffalo in their natural habitat.','uploads/safari/game-drive.jpg',NULL,NULL,2,1,NULL,NULL,NULL,'2026-08-27 12:13:09','2026-08-27 12:13:09',NULL),(18,4,'image-text','Bushveld Walks','Guided Nature Trails','Explore the Limpopo bushveld on foot with our expert guides. Discover hidden waterfalls, ancient trees, and incredible birdlife.','uploads/safari/bushwalk.jpg',NULL,NULL,3,1,NULL,NULL,NULL,'2026-08-27 12:13:09','2026-08-27 12:13:09',NULL),(19,4,'specials','Safari Packages','All-Inclusive Options','Combine your stay with curated safari experiences. Game drives, bush walks, and cultural tours.',NULL,NULL,NULL,5,1,NULL,NULL,NULL,'2026-08-27 12:13:09','2026-08-27 18:03:52',NULL),(20,5,'hero','Contact Us','Get in Touch',NULL,'uploads/hero/contact-hero.jpg',NULL,NULL,1,1,NULL,NULL,NULL,'2026-08-27 12:13:09','2026-08-27 12:13:09',NULL),(21,5,'text-content','We\'d Love to Hear From You','Reach Out','<p>Whether you have a question about availability, need help planning your stay, or want to arrange a special request, our team is here to help. We would love to hear from you!.</p><p><strong>Email:</strong> info@viataluxe.com</p><p><strong>Phone:</strong> 015 781 0518 (Tel) I 079 418 2077 (Mobile)</p><p><strong>Address:</strong> 86 Nollie Bosman Street (Corner 13 Prinsloo & Nollie Bosman) Phalaborwa, 1390</p>',NULL,NULL,NULL,2,1,NULL,NULL,NULL,'2026-08-27 12:13:09','2026-08-27 17:45:34',NULL),(22,6,'hero','About Us','Our Story',NULL,'uploads/hero/about-hero.jpg',NULL,NULL,1,1,NULL,NULL,NULL,'2026-08-27 12:13:09','2026-08-27 12:13:09',NULL),(23,6,'image-text','The Viata Luxe Difference','Since 2018','<p>Viata Luxe Guesthouse was born from a passion for hospitality and a love of the Limpopo bushveld. What started as a vision to create the perfect escape has grown into an award-winning guesthouse.</p><p>Our commitment to excellence has earned us recognition as one of Limpopo\'s top accommodation providers, with hundreds of 5-star reviews from guests around the world.</p>','uploads/about/founder.jpg',NULL,NULL,2,1,NULL,NULL,NULL,'2026-08-27 12:13:09','2026-08-27 12:13:09',NULL),(24,6,'stats','Our Achievements',NULL,'[{\"value\":\"2018\",\"label\":\"Established\"},{\"value\":\"4.9\",\"label\":\"Google Rating\"},{\"value\":\"500+\",\"label\":\"Happy Guests\"},{\"value\":\"4\",\"label\":\"Luxury Apartments\"}]',NULL,NULL,NULL,3,1,NULL,NULL,NULL,'2026-08-27 12:13:09','2026-08-27 12:13:09',NULL),(25,5,'contact-form','Send us a message','We reply within hours',NULL,NULL,NULL,NULL,3,1,NULL,NULL,NULL,'2026-08-27 12:16:08','2026-08-27 12:16:08',NULL),(26,2,'faqs','Frequently Asked Questions','Everything you need to know',NULL,NULL,NULL,NULL,5,1,NULL,NULL,NULL,'2026-08-27 12:16:08','2026-08-27 12:16:08',NULL),(27,1,'moments','Outdoor chillers. Fire. Water.','Moments','Not a resort itinerary. Three moments that actually happen at Viata ΓÇö then the bush does the rest.','uploads/gallery/jacuzzi-1.jpg','/gallery','See Gallery',40,1,NULL,NULL,NULL,'2026-08-27 17:54:42','2026-08-29 17:53:06',NULL),(28,4,'safari-activities','Kedibone Safari','Wildlife & Adventure','Game drives, boat safaris, cultural tours and adventure in the Greater Kruger.',NULL,NULL,NULL,4,1,NULL,NULL,NULL,'2026-08-27 18:03:52','2026-08-27 18:03:52',NULL),(29,1,'featured','Four doors. One standard: luxe.','Accommodation ┬╖ 4 Apartments','Classic Apartment 1 (Bachelor) ┬╖ Classic Apartment 2 ┬╖ Comfort Apartment 3 ┬╖ Deluxe Apartment 4 ΓÇö each 13 m┬▓, queen bed, self-catering, city views.','Luxury Images/apartments-classic-4/apt4-bedroom-main-view.jpg','/accomodation/','Explore all 4',50,1,NULL,NULL,'featured','2026-08-29 17:34:42','2026-08-30 15:03:27',NULL),(30,1,'trust-bar','Trust','Minutes to Kruger Gate','No catalogue. 4 apartments, each curated. From R950 ┬╖ Host on arrival',NULL,NULL,NULL,20,1,NULL,NULL,'trust','2026-08-29 17:53:06','2026-08-29 18:58:47',NULL),(31,1,'dining','Eat like you\'re meant to be here','Dining','Each apartment has a fully equipped kitchen for self-catering. For special evenings, explore Phalaborwa\'s restaurants or let us arrange a private bush dinner.','Luxury Images/food-dining/rose-champagne-berries-tray.jpg',NULL,'Open-air dining',110,1,NULL,NULL,'dining','2026-08-29 17:53:06','2026-08-29 17:53:06',NULL);
/*!40000 ALTER TABLE `sections` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `site_settings`
--

LOCK TABLES `site_settings` WRITE;
/*!40000 ALTER TABLE `site_settings` DISABLE KEYS */;
INSERT INTO `site_settings` VALUES (1,'site_name','Viata Luxe Guesthouse ΓÇö Luxury Accommodation in Phalaborwa Near Kruger National Park','text','general',1,'2026-08-27 12:13:09','2026-08-29 19:41:19'),(2,'site_tagline','Luxury self-catering in Phalaborwa ΓÇö bushveld views, secure parking, swimming pool.','text','general',2,'2026-08-27 12:13:09','2026-08-30 05:38:00'),(3,'site_description','Discover Viata Luxe Guesthouse in Phalaborwa ΓÇö your elegant home away from home just minutes from Kruger National Park. Book luxury accommodation with comfort, style, and top-tier service.','textarea','general',3,'2026-08-27 12:13:09','2026-08-29 19:41:19'),(4,'logo','/Luxury Images/logos/logo-kruger-national-park.png','image','general',4,'2026-08-27 12:13:09','2026-08-29 21:45:59'),(5,'favicon','/Luxury Images/logos/logo-viata-monogram-gold.png','image','branding',13,'2026-08-27 12:13:09','2026-08-29 17:53:06'),(6,'contact_email','info@viataluxe.com','email','general',6,'2026-08-27 12:13:09','2026-08-27 17:43:56'),(7,'contact_phone','015 781 0518 / 079 418 2077','phone','general',7,'2026-08-27 12:13:09','2026-08-27 17:43:56'),(8,'whatsapp_number','27794182077','phone','general',8,'2026-08-27 12:13:09','2026-08-27 17:43:56'),(9,'address','86 Nollie Bosman Street, Phalaborwa, 1390','text','general',9,'2026-08-27 12:13:09','2026-08-27 17:43:56'),(10,'facebook_url','','url','social',1,'2026-08-27 12:13:09','2026-08-27 17:43:56'),(11,'instagram_url','','url','social',2,'2026-08-27 12:13:09','2026-08-27 17:43:56'),(12,'youtube_url','','url','social',3,'2026-08-27 12:13:09','2026-08-27 17:43:56'),(13,'tiktok_url','','url','social',4,'2026-08-27 12:13:09','2026-08-27 12:13:09'),(14,'tripadvisor_url','','url','social',5,'2026-08-27 12:13:09','2026-08-27 17:43:56'),(15,'booking_url','https://book.nightsbridge.com/38331','url','booking',1,'2026-08-27 12:13:09','2026-08-27 12:13:09'),(16,'booking_cta_text','BOOK NOW','text','booking',2,'2026-08-27 12:13:09','2026-08-29 22:32:52'),(17,'booking_button_text','Book Now','text','booking',3,'2026-08-27 12:13:09','2026-08-27 12:13:09'),(18,'hero_title','The Bush is Calling','text','hero',1,'2026-08-27 12:13:09','2026-08-27 12:13:09'),(19,'hero_subtitle','4-Star Luxury Self-Catering','text','hero',2,'2026-08-27 12:13:09','2026-08-27 12:13:09'),(20,'hero_bg_image','uploads/hero/hero-main.jpg','image','hero',3,'2026-08-27 12:13:09','2026-08-27 12:13:09'),(21,'footer_about','Viata Luxe Guesthouse offers award-winning luxury self-catering accommodation in the heart of Limpopo. Perfect for business travelers, weekend getaways, and bushveld adventures.','textarea','footer',1,'2026-08-27 12:13:09','2026-08-27 12:13:09'),(22,'footer_copyright','┬⌐ 2026 Viata Luxe Guesthouse. 86 Nollie Bosman Street, Phalaborwa 1390.','text','footer',2,'2026-08-27 12:13:09','2026-08-29 17:53:06'),(23,'email','info@viataluxe.com','email','contact',1,'2026-08-27 17:43:56','2026-08-27 17:43:56'),(24,'phone_tel','+27157810518','phone','contact',2,'2026-08-27 17:43:56','2026-08-27 17:43:56'),(25,'phone_tel_display','015 781 0518','phone','contact',3,'2026-08-27 17:43:56','2026-08-27 17:43:56'),(26,'phone_mobile','+27794182077','phone','contact',4,'2026-08-27 17:43:56','2026-08-27 17:43:56'),(27,'phone_mobile_display','079 418 2077','phone','contact',5,'2026-08-27 17:43:56','2026-08-27 17:43:56'),(28,'whatsapp','27794182077','phone','contact',6,'2026-08-27 17:43:56','2026-08-27 17:43:56'),(29,'address_full','86 Nollie Bosman Street, Phalaborwa, 1390','text','contact',7,'2026-08-27 17:43:56','2026-08-27 17:43:56'),(30,'meta_description_home','Discover Viata Luxe Guesthouse in Phalaborwa ΓÇö elegant accommodation minutes from Kruger National Park. Book luxury self-catering with comfort, style, and top-tier service.','textarea','general',10,'2026-08-27 17:43:56','2026-08-29 19:41:19'),(31,'logo_dark','/Luxury Images/logos/logo-kruger-national-park.png','image','branding',10,'2026-08-27 18:09:49','2026-08-29 21:45:59'),(32,'logo_light','/Luxury Images/logos/logo-kruger-national-park-text.png','image','branding',11,'2026-08-27 18:09:49','2026-08-29 21:56:33'),(33,'site_name_brand','Viata Luxe Guesthouse','text','branding',14,'2026-08-27 18:09:49','2026-08-29 17:53:06'),(34,'footer_credit','Built with pride by Recast Media','text','footer',3,'2026-08-27 18:09:49','2026-08-27 18:09:49'),(35,'og_image_home','https://viataluxe.com/Luxury%20Images/pool/pool-overview-entertainment-area.jpg','image','general',11,'2026-08-27 18:09:49','2026-08-29 18:31:36'),(36,'logo_monogram','/Luxury Images/logos/logo-viata-monogram-gold.png','image','branding',12,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(41,'preloader_mark','Viata Luxe','text','branding',20,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(42,'preloader_sub','Phalaborwa ┬╖ Kruger Minutes','text','branding',21,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(43,'preloader_bg','#0B1A2E','text','branding',22,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(44,'trust_badge_text','86 Nollie Bosman St','text','trust',1,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(45,'trust_badge_sub','┬╖ Phalaborwa 1390','text','trust',2,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(46,'trust_nightsbridge','NightsBridge ┬╖ Instant book','text','trust',3,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(47,'trust_kicker','Minutes to Kruger Gate','text','trust',4,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(48,'trust_right_bold','No catalogue.','text','trust',5,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(49,'trust_right_text','4 apartments, each curated.','text','trust',6,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(50,'trust_right_muted','From R950 ┬╖ Host on arrival','text','trust',7,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(51,'booking_whatsapp_number','27618417838','phone','booking',4,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(55,'seo_title_home','Viata Luxe Guesthouse ΓÇö Luxury Accommodation in Phalaborwa Near Kruger National Park','text','seo',1,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(56,'seo_description_home','Discover Viata Luxe Guesthouse in Phalaborwa ΓÇö your elegant home away from home just minutes from Kruger National Park. Book luxury accommodation with comfort, style, and top-tier service.','textarea','seo',2,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(57,'seo_og_title','Viata Luxe Guesthouse ΓÇö Luxury Accommodation in Phalaborwa','text','seo',3,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(58,'seo_og_description','Elegant guesthouse minutes from Kruger National Park. 4 curated apartments, self-catering, from R950/night.','textarea','seo',4,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(59,'seo_og_type','website','text','seo',5,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(60,'seo_og_url','https://viataluxe.com/','url','seo',6,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(61,'seo_og_image','https://viataluxe.com/Luxury%20Images/pool/pool-overview-entertainment-area.jpg','image','seo',7,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(62,'seo_og_locale','en_ZA','text','seo',8,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(63,'seo_site_name','Viata Luxe Guesthouse','text','seo',9,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(64,'seo_twitter_card','summary_large_image','text','seo',10,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(65,'seo_canonical','https://viataluxe.com/','url','seo',11,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(66,'footer_brand','Viata Luxe ┬╖ Phalaborwa','text','footer',1,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(67,'footer_legal','┬⌐ 2026 Viata Luxe Guesthouse. All rights reserved.','text','footer',4,'2026-08-29 17:53:06','2026-08-29 17:53:06'),(85,'booking_whatsapp_message','Hi Viata Luxe, I\'d like to enquire about the 3-night stay offer.','text','booking',5,'2026-08-29 18:31:36','2026-08-29 18:31:36'),(122,'safari_beyond_title','Beyond the Gate','text','safari',0,'2026-08-30 03:08:28','2026-08-30 03:08:28'),(123,'safari_beyond_lead','Phalaborwa sits at the crossroads of the Lowveld ? Blyde River Canyon to the south, cultural heritage sites within minutes, and sunset drinks at Amarula on the banks of the Olifants.','text','safari',0,'2026-08-30 03:08:28','2026-08-30 03:08:28'),(124,'safari_intro_text','Every stay at Viata Luxe is a gateway to the wild ? just 15 minutes from the Phalaborwa Gate of Kruger National Park. Safari drives at dawn, boat cruises on the Olifants, and canyon vistas that stretch to the horizon.','text','safari',0,'2026-08-30 03:08:28','2026-08-30 03:08:28'),(125,'safari_gallery_title','Moments from the Bush','text','safari',0,'2026-08-30 03:08:28','2026-08-30 03:08:28'),(126,'safari_cta_title','Ready to Explore?','text','safari',0,'2026-08-30 03:08:28','2026-08-30 03:08:28'),(127,'safari_cta_lead','Download our full safari pricelist or get in touch to tailor your Limpopo adventure.','text','safari',0,'2026-08-30 03:08:28','2026-08-30 03:08:28'),(128,'safari_beyond_cards','[{\"micro\":\"Foskor Mine Museum\",\"title\":\"Phalaborwa mining history\",\"text\":\"Showcases significance in mining industry ΓÇö close town heritage.\",\"chip\":\"Phalaborwa town\",\"image\":\"Luxury Images/activities/hippos-water-group.jpg\"},{\"micro\":\"Masorini ΓÇö Kruger\",\"title\":\"BaPhalaborwa Iron Age\",\"text\":\"Ancient smelting remnants within Kruger ΓÇö fascinating discovery.\",\"chip\":\"Inside Kruger\",\"image\":\"Luxury Images/activities/elephant-river-sunset-solitary.jpg\"},{\"micro\":\"Blyde River Canyon\",\"title\":\"Largest canyon ΓÇö hiking + boat\",\"text\":\"Breathtaking landscapes, trails, boat trips ΓÇö world-class canyon.\",\"chip\":\"Iconic\",\"image\":\"Luxury Images/activities/blyde-river-canyon-panorama.jpg\"},{\"micro\":\"Amarula Lapa\",\"title\":\"Liqueur tasting\",\"text\":\"Learn how South Africa\'s beloved Amarula is made ΓÇö tasting included.\",\"chip\":\"Tasting\",\"image\":\"Luxury Images/activities/river-landscape-panoramic.jpg\"}]','json','safari',0,'2026-08-30 05:45:07','2026-08-30 05:45:07');
/*!40000 ALTER TABLE `site_settings` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `testimonials`
--

LOCK TABLES `testimonials` WRITE;
/*!40000 ALTER TABLE `testimonials` DISABLE KEYS */;
INSERT INTO `testimonials` VALUES (1,1,'Kurhula Hlomane','I enjoyed every moment of my stay. What an ambience.',5,'booking.com',1,1,NULL,NULL,1,'2026-08-27 12:13:09',NULL),(2,2,'Shawn Radov','First class services, friendly staff, amazing food my stay was a great working holiday experience',5,'google',1,1,NULL,NULL,2,'2026-08-27 12:13:09',NULL),(3,3,'Ntsako Phoebe Mabunda','The service, the warmth and the beauty of this place was absolutely amazing. I will definitely be staying for longer next time. The host is an absolute professional and could easily be the kindest person I know.',5,'google',1,1,NULL,NULL,3,'2026-08-27 12:13:09',NULL),(4,4,'Dylan Chapman','Amazing guesthouse! The units were super clean, amenities new, and the staff are really friendly. Will definitely be staying there again',5,'google',1,1,NULL,NULL,4,'2026-08-27 12:13:09',NULL),(40,NULL,'QA_CRUD_1788038151537_UPD','Updated record QA',4,'qa',1,1,NULL,NULL,99,'2026-08-29 21:15:59','2026-08-29 21:16:01'),(41,NULL,'QA_CRUD_1788038935119_UPD','Updated record QA',4,'qa',1,1,NULL,NULL,99,'2026-08-29 21:29:03','2026-08-29 21:29:07'),(42,NULL,'QA_CRUD_1788039205176_UPD','Updated record QA',4,'qa',1,1,NULL,NULL,99,'2026-08-29 21:33:31','2026-08-29 21:33:33'),(43,NULL,'QA_CRUD_1788041722831_UPD','Updated record QA',4,'qa',1,1,NULL,NULL,99,'2026-08-29 22:15:37','2026-08-29 22:15:38'),(44,NULL,'QA_CRUD_1788042122476_UPD','Updated record QA',4,'qa',1,1,NULL,NULL,99,'2026-08-29 22:22:15','2026-08-29 22:22:18'),(45,NULL,'QA_CRUD_1788044216999_UPD','Updated record QA',4,'qa',1,1,NULL,NULL,99,'2026-08-29 22:57:05','2026-08-29 22:57:06'),(46,NULL,'QA_CRUD_1788053592507_UPD','Updated record QA',4,'qa',1,1,NULL,NULL,99,'2026-08-30 01:33:20','2026-08-30 01:33:21'),(47,NULL,'QA_CRUD_1788055185246_UPD','Updated record QA',4,'qa',1,1,NULL,NULL,99,'2026-08-30 01:59:53','2026-08-30 01:59:55'),(48,NULL,'QA_CRUD_1788055339703','Fresh record QA',5,'qa',1,1,NULL,NULL,99,'2026-08-30 02:02:51',NULL),(49,NULL,'QA_CRUD_1788055553488_UPD','Updated record QA',4,'qa',1,1,NULL,NULL,99,'2026-08-30 02:05:59','2026-08-30 02:06:00'),(50,NULL,'QA_CRUD_1788056967897_UPD','Updated record QA',4,'qa',1,1,NULL,NULL,99,'2026-08-30 02:29:32','2026-08-30 02:29:32'),(51,NULL,'QA_CRUD_1788057114642_UPD','Updated record QA',4,'qa',1,1,NULL,NULL,99,'2026-08-30 02:32:02','2026-08-30 02:32:03'),(52,NULL,'QA_CRUD_1788057332744_UPD','Updated record QA',4,'qa',1,1,NULL,NULL,99,'2026-08-30 02:35:37','2026-08-30 02:35:38'),(53,NULL,'QA_CRUD_1788057609826_UPD','Updated record QA',4,'qa',1,1,NULL,NULL,99,'2026-08-30 02:40:16','2026-08-30 02:40:16'),(54,NULL,'QA_CRUD_1788057928298_UPD','Updated record QA',4,'qa',1,1,NULL,NULL,99,'2026-08-30 02:45:33','2026-08-30 02:45:33'),(55,NULL,'QA_CRUD_1788062572719','Fresh record QA',5,'qa',1,1,NULL,NULL,99,'2026-08-30 04:02:58',NULL),(56,NULL,'QA_CRUD_1788069056059','Fresh record QA',5,'qa',1,1,NULL,NULL,99,'2026-08-30 05:51:11',NULL),(57,NULL,'QA_CRUD_1788069328959','Fresh record QA',5,'qa',1,1,NULL,NULL,99,'2026-08-30 05:55:34',NULL),(58,NULL,'QA_CRUD_1788096032355_UPD','Updated record QA',4,'qa',1,1,NULL,NULL,99,'2026-08-30 13:20:38','2026-08-30 13:20:40'),(59,NULL,'QA_CRUD_1788096185062_UPD','Updated record QA',4,'qa',1,1,NULL,NULL,99,'2026-08-30 13:23:12','2026-08-30 13:23:13'),(60,NULL,'QA_CRUD_1788098856264_UPD','Updated record QA',4,'qa',1,1,NULL,NULL,99,'2026-08-30 14:07:45','2026-08-30 14:07:47'),(61,NULL,'QA_CRUD_1788099038336_UPD','Updated record QA',4,'qa',1,1,NULL,NULL,99,'2026-08-30 14:10:47','2026-08-30 14:10:48'),(62,NULL,'QA_CRUD_1788099600557_UPD','Updated record QA',4,'qa',1,1,NULL,NULL,99,'2026-08-30 14:20:08','2026-08-30 14:20:10'),(63,NULL,'QA_CRUD_1788099767774_UPD','Updated record QA',4,'qa',1,1,NULL,NULL,99,'2026-08-30 14:22:58','2026-08-30 14:22:59'),(64,NULL,'QA_CRUD_1788102570763_UPD','Updated record QA',4,'qa',1,1,NULL,NULL,99,'2026-08-30 15:09:39','2026-08-30 15:09:40'),(65,NULL,'QA_CRUD_1788102721897_UPD','Updated record QA',4,'qa',1,1,NULL,NULL,99,'2026-08-30 15:12:13','2026-08-30 15:12:15');
/*!40000 ALTER TABLE `testimonials` ENABLE KEYS */;
UNLOCK TABLES;

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

-- Dump completed on 2026-08-31 11:12:56
