-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: amutbar
-- ------------------------------------------------------
-- Server version	8.0.30

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
-- Table structure for table `admin_audit_logs`
--

DROP TABLE IF EXISTS `admin_audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `actor_user_id` bigint unsigned NOT NULL,
  `action` varchar(96) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_type` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entity_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  KEY `ix_admin_audit_actor_time` (`actor_user_id`,`created_at`),
  KEY `ix_admin_audit_entity` (`entity_type`,`entity_id`,`created_at`),
  CONSTRAINT `fk_admin_audit_actor` FOREIGN KEY (`actor_user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `admin_audit_logs_chk_1` CHECK (json_valid(`payload_json`))
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_audit_logs`
--

LOCK TABLES `admin_audit_logs` WRITE;
/*!40000 ALTER TABLE `admin_audit_logs` DISABLE KEYS */;
INSERT INTO `admin_audit_logs` VALUES (1,1,'users.save','user',16,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','[]','2026-01-13 23:12:33.702'),(2,1,'users.delete','user',16,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','[]','2026-01-13 23:14:22.771'),(3,1,'users.save','user',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','[]','2026-01-21 02:16:46.405'),(4,1,'users.save','user',17,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','[]','2026-01-21 02:18:00.314'),(5,1,'users.save','user',17,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','[]','2026-01-21 02:18:25.219'),(6,1,'users.save','user',17,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','[]','2026-01-21 02:18:48.008'),(7,1,'users.delete','user',17,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','[]','2026-01-21 02:18:51.063'),(8,1,'users.save','user',22,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','[]','2026-02-08 21:56:42.911'),(9,1,'admin.avatar.updated','users',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','{\"avatar_key\": \"storage/uploads/avatars/avatar_1_1770673695.png\"}','2026-02-10 01:18:15.067'),(10,1,'admin.avatar.updated','users',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','{\"avatar_key\": \"storage/uploads/avatars/avatar_1_1770673832.webp\"}','2026-02-10 01:20:32.518'),(11,1,'users.save','user',23,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','[]','2026-02-10 01:23:25.119'),(12,1,'admin.avatar.updated','users',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','{\"avatar_key\": \"storage/uploads/avatars/avatar_1_1770674071.webp\"}','2026-02-10 01:24:31.370'),(13,1,'users.delete','user',30,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','[]','2026-02-10 03:27:08.953'),(14,1,'users.save','user',32,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','[]','2026-02-10 03:59:57.127'),(15,1,'users.save','user',32,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','[]','2026-02-10 04:00:02.481'),(16,1,'users.save','user',32,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','[]','2026-02-10 04:00:20.005'),(17,1,'vehicle.toggle','vehicle_type_toggle',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','[]','2026-02-13 22:03:15.267'),(18,1,'vehicle.update','vehicle_type_update',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','[]','2026-02-13 22:03:28.262'),(19,1,'vehicle.create','vehicle_type_create',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','[]','2026-02-13 22:04:13.108'),(20,1,'vehicle.update','vehicle_type_update',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','[]','2026-02-13 22:04:17.544'),(21,1,'vehicle.create','vehicle_type_create',3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','[]','2026-02-13 22:04:48.582'),(22,1,'vehicle.create','vehicle_type_create',4,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','[]','2026-02-13 22:05:49.462'),(23,1,'users.delete','user',33,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0','[]','2026-02-22 00:50:06.407'),(24,1,'users.delete','user',32,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0','[]','2026-02-22 00:50:14.944'),(25,1,'users.delete','user',34,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','[]','2026-02-26 18:49:56.023'),(26,1,'internal_api.update','internal_api_settings_update',22,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','[]','2026-02-27 23:14:52.690'),(27,1,'internal_api.update','internal_api_settings_update',22,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','[]','2026-02-27 23:15:10.897'),(28,1,'users.delete','user',37,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','[]','2026-02-28 00:13:09.709'),(29,1,'internal_api.update','internal_api_settings_update',21,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','[]','2026-02-28 00:18:52.721'),(30,1,'users.delete','user',39,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-11 18:16:16.902'),(31,1,'users.delete','user',40,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-11 18:21:42.051'),(32,1,'users.delete','user',41,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-11 18:46:59.743'),(33,1,'users.delete','user',43,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-13 18:15:22.620'),(34,1,'users.save','user',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-16 16:36:44.758'),(35,1,'users.delete','user',44,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-16 16:37:53.081'),(36,1,'users.deactivate','user',44,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-16 16:52:08.293'),(37,1,'users.hard_delete','user',44,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-16 16:52:55.775'),(38,1,'users.save','user',50,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-16 17:50:52.673'),(39,1,'users.activate','user',50,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-16 17:50:57.480'),(40,1,'users.save','user',50,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-16 17:51:54.180'),(41,1,'users.save','user',50,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-16 17:53:06.273'),(42,1,'users.save','user',50,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-16 18:01:26.116'),(43,1,'users.save','user',50,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-16 18:02:37.231'),(44,1,'users.save','user',50,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-16 18:03:03.455'),(45,1,'users.save','user',50,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-16 18:05:26.048'),(46,1,'users.save','user',50,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-16 18:05:34.057'),(47,1,'users.save','user',50,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-16 18:05:40.317'),(48,1,'users.save','user',50,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-16 18:05:52.870'),(49,1,'users.save','user',50,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-16 18:05:59.539'),(50,1,'users.save','user',50,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-16 18:07:40.586'),(51,1,'users.save','user',50,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-16 18:09:13.381'),(52,1,'internal_api.update','internal_api_settings_update',21,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-16 18:47:05.717'),(53,1,'internal_api.update','internal_api_settings_update',21,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-16 19:14:26.151'),(54,1,'internal_api.update','internal_api_settings_update',21,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-16 19:15:20.633'),(55,1,'users.hard_delete','user',50,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-16 20:22:21.573'),(56,1,'users.hard_delete','user',51,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-16 20:22:24.450'),(57,1,'users.save','user',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-16 20:22:30.811'),(58,1,'users.hard_delete','user',52,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-16 21:10:30.121'),(59,1,'users.save','user',53,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-18 14:08:13.419'),(60,1,'users.save','user',53,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-18 14:10:39.953'),(61,1,'users.save','user',53,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-18 14:11:08.593'),(62,1,'users.save','user',53,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-18 14:12:18.258'),(63,1,'users.save','user',53,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-18 14:12:27.308'),(64,1,'users.save','user',53,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-18 14:12:52.606'),(65,1,'users.save','user',53,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-18 14:13:09.223'),(66,1,'users.save','user',53,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-18 14:13:24.960'),(67,1,'users.save','user',53,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-18 14:13:40.102'),(68,1,'users.save','user',53,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-18 14:14:06.905'),(69,1,'users.save','user',53,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-18 14:14:27.235'),(70,1,'users.save','user',53,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-18 14:14:48.042'),(71,1,'users.save','user',53,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-18 14:33:03.388'),(72,1,'users.save','user',53,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-18 14:33:20.947'),(73,1,'users.save','user',53,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-18 14:36:43.368'),(74,1,'users.save','user',53,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-18 14:37:14.569'),(75,1,'users.save','user',53,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-18 14:50:59.754'),(76,1,'users.save','user',53,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-18 17:12:03.163'),(77,1,'users.save','user',53,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-18 17:12:33.258'),(78,1,'users.save','user',53,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-18 17:55:53.526'),(79,1,'users.save','user',53,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-18 17:59:08.609'),(80,1,'users.save','user',53,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-18 18:04:48.720'),(81,1,'users.save','user',53,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-18 18:07:54.331'),(82,1,'users.save','user',54,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-18 20:55:24.244'),(83,1,'users.save','user',54,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','[]','2026-05-18 20:55:31.601'),(84,1,'users.hard_delete','user',53,'5.74.192.91','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','[]','2026-07-30 21:56:55.257'),(85,1,'users.hard_delete','user',55,'5.74.192.91','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','[]','2026-07-30 21:56:55.258'),(86,1,'users.soft_delete','user',56,'5.74.139.30','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','[]','2026-08-01 19:34:48.939'),(87,1,'users.soft_delete','user',54,'5.74.139.30','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','[]','2026-08-01 19:34:48.949'),(88,1,'users.soft_delete','user',57,'5.74.139.30','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','[]','2026-08-01 20:20:28.209'),(89,1,'users.soft_delete','user',60,'5.74.139.30','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','[]','2026-08-01 21:02:39.158'),(90,1,'users.save','user',1,'5.74.139.30','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','[]','2026-08-01 21:05:07.154'),(91,1,'users.soft_delete','user',61,'5.74.139.30','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','[]','2026-08-01 21:30:01.987'),(92,1,'users.soft_delete','user',62,'5.74.139.30','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','[]','2026-08-01 21:32:27.695'),(93,1,'users.soft_delete','user',65,'2.176.231.47','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','[]','2026-08-22 11:03:37.348'),(94,1,'users.soft_delete','user',64,'2.176.231.47','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','[]','2026-08-22 11:04:19.323'),(95,1,'users.save','user',72,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36','[]','2026-09-20 17:23:52.583');
/*!40000 ALTER TABLE `admin_audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `api_tokens`
--

DROP TABLE IF EXISTS `api_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `api_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `token_hash` char(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_id` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `platform` tinyint unsigned DEFAULT NULL,
  `ip_address` varbinary(16) DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` datetime(3) DEFAULT NULL,
  `expires_at` datetime(3) DEFAULT NULL,
  `revoked_at` datetime(3) DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_api_tokens_hash` (`token_hash`),
  KEY `ix_api_tokens_user` (`user_id`,`created_at`),
  KEY `ix_api_tokens_expires` (`expires_at`),
  CONSTRAINT `fk_api_tokens_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `api_tokens`
--

LOCK TABLES `api_tokens` WRITE;
/*!40000 ALTER TABLE `api_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `api_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_versions`
--

DROP TABLE IF EXISTS `app_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `app_versions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `app_id` tinyint unsigned NOT NULL,
  `platform` tinyint unsigned NOT NULL,
  `latest_version_name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `latest_version_code` int unsigned NOT NULL,
  `min_supported_code` int unsigned NOT NULL,
  `force_update` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `released_at` datetime(3) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by_user_id` bigint unsigned DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  KEY `ix_app_versions_lookup` (`app_id`,`platform`,`is_active`,`latest_version_code`),
  KEY `ix_app_versions_min` (`app_id`,`platform`,`min_supported_code`),
  KEY `fk_app_versions_creator` (`created_by_user_id`),
  CONSTRAINT `fk_app_versions_creator` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_versions`
--

LOCK TABLES `app_versions` WRITE;
/*!40000 ALTER TABLE `app_versions` DISABLE KEYS */;
/*!40000 ALTER TABLE `app_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_daily_stats`
--

DROP TABLE IF EXISTS `auth_daily_stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_daily_stats` (
  `stat_date` date NOT NULL,
  `user_type` tinyint unsigned NOT NULL,
  `event_type` tinyint unsigned NOT NULL,
  `cnt` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`stat_date`,`user_type`,`event_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_daily_stats`
--

LOCK TABLES `auth_daily_stats` WRITE;
/*!40000 ALTER TABLE `auth_daily_stats` DISABLE KEYS */;
INSERT INTO `auth_daily_stats` VALUES ('2026-02-10',3,1,4),('2026-02-10',3,2,3),('2026-02-10',3,3,34),('2026-02-12',1,4,1),('2026-02-12',3,1,1),('2026-02-12',3,3,30),('2026-02-13',3,3,235),('2026-02-14',3,1,1),('2026-02-14',3,3,115),('2026-02-20',3,3,50),('2026-02-21',1,4,3),('2026-02-21',3,1,1),('2026-02-21',3,3,35),('2026-02-22',1,4,1),('2026-02-22',3,3,21),('2026-02-24',1,4,1),('2026-02-24',3,3,10),('2026-02-26',1,4,6),('2026-02-26',3,1,1),('2026-02-26',3,3,26),('2026-02-27',1,4,1),('2026-02-27',3,3,57),('2026-02-28',1,4,3),('2026-02-28',3,3,101),('2026-05-11',1,4,4),('2026-05-11',3,1,2),('2026-05-11',3,2,1),('2026-05-13',1,4,1),('2026-05-13',3,1,1),('2026-05-16',1,4,4),('2026-05-16',3,1,2),('2026-05-16',3,2,1),('2026-05-16',3,3,484),('2026-05-18',1,4,1),('2026-05-18',3,3,1420),('2026-07-08',3,1,1),('2026-07-08',3,3,50),('2026-07-17',1,4,2),('2026-07-17',3,1,1),('2026-07-17',3,3,92),('2026-07-21',1,4,1),('2026-07-30',3,1,1),('2026-08-01',1,4,6),('2026-08-01',2,4,6),('2026-08-01',3,3,1),('2026-08-06',1,4,2),('2026-08-06',2,4,2),('2026-08-06',3,1,2),('2026-08-17',1,4,2),('2026-08-17',2,4,2),('2026-08-22',1,4,1),('2026-08-22',3,1,2),('2026-08-22',3,5,1),('2026-08-30',3,1,1),('2026-09-27',3,1,1),('2026-09-27',3,3,1),('2026-09-27',3,5,1),('2026-10-11',3,1,1),('2026-10-13',3,1,1);
/*!40000 ALTER TABLE `auth_daily_stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_events`
--

DROP TABLE IF EXISTS `auth_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `user_type` tinyint unsigned NOT NULL,
  `event_type` tinyint unsigned NOT NULL,
  `ip_address` varbinary(16) DEFAULT NULL,
  `user_agent_hash` binary(32) DEFAULT NULL,
  `payload_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  KEY `idx_user_created` (`user_id`,`created_at`),
  KEY `idx_created` (`created_at`),
  KEY `idx_type_event_created` (`user_type`,`event_type`,`created_at`),
  CONSTRAINT `auth_events_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `auth_events_chk_1` CHECK (json_valid(`payload_json`))
) ENGINE=InnoDB AUTO_INCREMENT=2112 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_events`
--

LOCK TABLES `auth_events` WRITE;
/*!40000 ALTER TABLE `auth_events` DISABLE KEYS */;
INSERT INTO `auth_events` VALUES (2098,68,2,4,_binary '��e',_binary 'F�}2�s�I\"\Z\n��\�\�8�R��g�z�\�G\�4','{\"platform\":1,\"device_id\":\"SP1A.210812.016\"}','2026-08-17 12:08:20.049'),(2099,69,1,4,_binary '��e',_binary 'F�}2�s�I\"\Z\n��\�\�8�R��g�z�\�G\�4','{\"platform\":1,\"device_id\":\"SP1A.210812.016\"}','2026-08-17 13:24:53.253'),(2100,70,2,4,_binary 'b`�+',_binary 'F�}2�s�I\"\Z\n��\�\�8�R��g�z�\�G\�4','{\"platform\":1,\"device_id\":\"SP1A.210812.016\"}','2026-08-17 13:37:39.369'),(2101,71,1,4,_binary '��e',_binary 'F�}2�s�I\"\Z\n��\�\�8�R��g�z�\�G\�4','{\"platform\":1,\"device_id\":\"SP1A.210812.016\"}','2026-08-17 13:43:05.051'),(2102,1,3,5,_binary '�\�/',_binary '@�o\�ɯ��.\�\"c�\�x�o�^�\��\�u��Ƭ','{\"reason\":\"bad_password\"}','2026-08-22 11:02:55.315'),(2103,1,3,1,_binary '�\�/',_binary '@�o\�ɯ��.\�\"c�\�x�o�^�\��\�u��Ƭ','{\"remember\":false}','2026-08-22 11:02:59.169'),(2104,72,1,4,_binary '�\�/',_binary 'F�}2�s�I\"\Z\n��\�\�8�R��g�z�\�G\�4','{\"platform\":1,\"device_id\":\"R16NW\"}','2026-08-22 11:03:57.226'),(2105,1,3,1,_binary '�\�/',_binary '@�o\�ɯ��.\�\"c�\�x�o�^�\��\�u��Ƭ','{\"remember\":false}','2026-08-22 13:54:54.246'),(2106,1,3,1,_binary '�\�y',_binary '@�o\�ɯ��.\�\"c�\�x�o�^�\��\�u��Ƭ','{\"remember\":false}','2026-08-30 21:57:58.767'),(2107,1,3,5,_binary '\0\0',_binary '�b�L����\Z�E_������nlI#�e�i\�\�','{\"reason\":\"bad_password\"}','2026-09-27 16:46:42.556'),(2108,1,3,1,_binary '\0\0',_binary '�b�L����\Z�E_������nlI#�e�i\�\�','{\"remember\":true}','2026-09-27 16:46:45.382'),(2109,1,3,3,_binary '\0\0',_binary '�b�L����\Z�E_������nlI#�e�i\�\�','{\"via\":\"remember_token\"}','2026-09-27 19:32:55.572'),(2110,1,3,1,_binary '\0\0',_binary '�T\�O�wj\�_\no�5m\�)~ă�V#T\0\�h=','{\"remember\":false}','2026-10-11 16:02:57.854'),(2111,1,3,1,_binary '\0\0',_binary '�T\�O�wj\�_\no�5m\�)~ă�V#T\0\�h=','{\"remember\":true}','2026-10-13 15:16:34.332');
/*!40000 ALTER TABLE `auth_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `banners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `placement` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_app_id` tinyint unsigned DEFAULT NULL,
  `target_user_type` tinyint unsigned DEFAULT NULL,
  `target_platform` tinyint unsigned DEFAULT NULL,
  `action_type` tinyint unsigned NOT NULL DEFAULT '0',
  `action_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_at` datetime(3) DEFAULT NULL,
  `end_at` datetime(3) DEFAULT NULL,
  `priority` smallint unsigned NOT NULL DEFAULT '100',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `min_version_code` int unsigned DEFAULT NULL,
  `data_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_by_user_id` bigint unsigned DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  KEY `ix_banners_active_time` (`is_active`,`start_at`,`end_at`,`priority`),
  KEY `ix_banners_targeting` (`placement`,`target_app_id`,`target_user_type`,`target_platform`,`is_active`),
  KEY `fk_banners_creator` (`created_by_user_id`),
  CONSTRAINT `fk_banners_creator` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `banners_chk_1` CHECK (json_valid(`data_json`))
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banners`
--

LOCK TABLES `banners` WRITE;
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
INSERT INTO `banners` VALUES (4,'بنر 2','بنر 2','storage/uploads/banners/banner_1778956740_6a08b9c4d84e7.jpg','dashboard',1,NULL,NULL,1,'https://amutbar.com',NULL,NULL,101,1,NULL,NULL,1,'2026-05-16 22:09:00.886','2026-05-16 22:09:00.886'),(5,'بنر پروفایل کاربری رانندگان','تست','storage/uploads/banners/banner_1779100482_6a0aeb4228432.jpg','profile',1,NULL,NULL,1,'https://google.com',NULL,NULL,100,1,NULL,NULL,1,'2026-05-18 14:04:42.165','2026-05-18 14:04:42.165'),(7,'تست','تست','storage/uploads/banners/banner_1779100674_6a0aec02b62b3.jpg','profile',1,NULL,NULL,1,'https://google.com',NULL,NULL,101,1,NULL,NULL,1,'2026-05-18 14:07:54.746','2026-05-18 14:07:54.746');
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `call_logs`
--

DROP TABLE IF EXISTS `call_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `call_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `load_id` bigint unsigned NOT NULL,
  `driver_id` bigint unsigned NOT NULL,
  `company_id` bigint unsigned NOT NULL,
  `event_type` tinyint unsigned NOT NULL,
  `duration_sec` int unsigned DEFAULT NULL,
  `result_code` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_platform` tinyint unsigned DEFAULT NULL,
  `client_version_code` int unsigned DEFAULT NULL,
  `ip_address` varbinary(16) DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  KEY `ix_call_logs_driver_time` (`driver_id`,`created_at`),
  KEY `ix_call_logs_company_time` (`company_id`,`created_at`),
  KEY `ix_call_logs_load_time` (`load_id`,`created_at`),
  KEY `ix_call_logs_driver_event_time` (`driver_id`,`event_type`,`created_at`),
  CONSTRAINT `fk_call_logs_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_call_logs_driver` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_call_logs_load` FOREIGN KEY (`load_id`) REFERENCES `loads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `call_logs`
--

LOCK TABLES `call_logs` WRITE;
/*!40000 ALTER TABLE `call_logs` DISABLE KEYS */;
INSERT INTO `call_logs` VALUES (3,11,27,9,1,NULL,'DIALED',1,3,_binary '\�\�\�','Dart/3.12 (dart:io)','2026-08-06 16:19:25.085'),(4,9,27,1,1,NULL,'DIALED',1,3,_binary '\�\�\�','Dart/3.12 (dart:io)','2026-08-06 16:25:45.258'),(5,10,27,7,1,NULL,'DIALED',1,3,_binary '\�\�\�','Dart/3.12 (dart:io)','2026-08-06 16:25:54.592'),(6,12,27,10,1,NULL,'DIALED',1,3,_binary 'b`�+','Dart/3.12 (dart:io)','2026-08-17 13:15:34.574');
/*!40000 ALTER TABLE `call_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cargos_list`
--

DROP TABLE IF EXISTS `cargos_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cargos_list` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cargos_list`
--

LOCK TABLES `cargos_list` WRITE;
/*!40000 ALTER TABLE `cargos_list` DISABLE KEYS */;
INSERT INTO `cargos_list` VALUES (1,'مواد - کیسه: سیمان'),(2,'مواد - کیسه: گچ'),(3,'مواد - کیسه: خاک رس'),(4,'مواد - کیسه: مواد شیمیایی پودری (مانند کربنات کلسیم)'),(5,'مواد - کیسه: مواد شیمیایی پودری (مانند سودا اش)'),(6,'مواد - کیسه: کود شیمیایی'),(7,'مواد - کیسه: پودر سنگ'),(8,'مواد - کیسه: آرد'),(9,'مواد - کیسه: شکر'),(10,'مواد - کیسه: حبوبات'),(11,'مواد - کیسه: بذر و تخمه'),(12,'مواد - کیسه: خوراک دام و طیور'),(13,'مواد - کیسه پالتی: مواد پتروشیمی صادراتی (پلی‌اتیلن) در جامبوبگ'),(14,'مواد - کیسه پالتی: مواد پتروشیمی صادراتی (پلی‌پروپیلن) در جامبوبگ'),(15,'مواد - کیسه پالتی: مواد پتروشیمی صادراتی (پی‌وی‌سی) در جامبوبگ'),(16,'مواد - کیسه پالتی: برنج'),(17,'مواد - کیسه پالتی: شکر'),(18,'مواد - کیسه پالتی: مواد معدنی صادراتی (مانند باریت)'),(19,'مواد - کیسه پالتی: مواد معدنی صادراتی (مانند گچ)'),(20,'مواد - کیسه پالتی: کنسانتره میوه خشک'),(21,'مواد - کیسه پالتی: پودرهای شیمیایی و معدنی سنگین (مانند بنتونیت)'),(22,'مواد پی وی سی (PVC): پودر PVC'),(23,'مواد پی وی سی (PVC): گرانول PVC'),(24,'مواد پی وی سی (PVC): محصولات ساخته‌شده (لوله و اتصالات)'),(25,'مواد پی وی سی (PVC): محصولات ساخته‌شده (پروفیل در و پنجره)'),(26,'مواد پی وی سی (PVC): ورق‌های PVC'),(27,'مواد پی وی سی (PVC): کفپوش و دیوارپوش'),(28,'مواد شوینده - کارتن: پودر لباسشویی دستی'),(29,'مواد شوینده - کارتن: پودر لباسشویی ماشینی'),(30,'مواد شوینده - کارتن: مایع ظرفشویی'),(31,'مواد شوینده - کارتن: مایع دستشویی'),(32,'مواد شوینده - کارتن: شامپو'),(33,'مواد شوینده - کارتن: نرم‌کننده مو'),(34,'مواد شوینده - کارتن: سفیدکننده‌ها (وایتکس)'),(35,'مواد شوینده - کارتن: پاک‌کننده‌های سطوح (جرم‌گیر)'),(36,'مواد شوینده - کارتن: پاک‌کننده‌های سطوح (شیشه‌شوی)'),(37,'مواد غذایی یخچالی: فرآورده‌های گوشتی (سوسیس)'),(38,'مواد غذایی یخچالی: فرآورده‌های گوشتی (کالباس)'),(39,'مواد غذایی یخچالی: محصولات لبنی (شیر)'),(40,'مواد غذایی یخچالی: محصولات لبنی (ماست)'),(41,'مواد غذایی یخچالی: محصولات لبنی (پنیر)'),(42,'مواد غذایی یخچالی: محصولات لبنی (خامه)'),(43,'مواد غذایی یخچالی: محصولات لبنی (کره)'),(44,'مواد غذایی یخچالی: مواد پروتئینی منجمد (گوشت قرمز)'),(45,'مواد غذایی یخچالی: مواد پروتئینی منجمد (گوشت مرغ)'),(46,'مواد غذایی یخچالی: سبزیجات تازه'),(47,'مواد غذایی یخچالی: میوه‌جات تازه'),(48,'مواد غذایی یخچالی: داروها و واکسن‌های دامپزشکی'),(49,'مواد غذایی یخچالی: قارچ'),(50,'مواد غذایی یخچالی: صیفی‌جات حساس'),(51,'مواد - سبد: انواع میوه (سیب)'),(52,'مواد - سبد: انواع میوه (پرتقال)'),(53,'مواد - سبد: انواع میوه (موز)'),(54,'مواد - سبد: سبزیجات برگی'),(55,'مواد - سبد: سبزیجات غده‌ای (سیب‌زمینی)'),(56,'مواد - سبد: سبزیجات غده‌ای (پیاز)'),(57,'مواد - سبد: سبزیجات غده‌ای (گوجه‌فرنگی)'),(58,'مواد - سبد: تخم مرغ خوراکی'),(59,'مواد - سبد: تخم مرغ نطفه‌دار'),(60,'مواد - سبد: محصولات پلاستیکی و قطعات کوچک صنعتی'),(61,'مواد - سبد: نان و محصولات نانوایی'),(62,'مواد - سبد: محصولات دریایی و شیلاتی'),(63,'مواد غذایی - پالت: انواع کنسروها (تن ماهی)'),(64,'مواد غذایی - پالت: انواع کنسروها (لوبیا)'),(65,'مواد غذایی - پالت: انواع کنسروها (ذرت)'),(66,'مواد غذایی - پالت: روغن نباتی خوراکی (بطری)'),(67,'مواد غذایی - پالت: روغن نباتی خوراکی (قوطی)'),(68,'مواد غذایی - پالت: رب گوجه فرنگی'),(69,'مواد غذایی - پالت: انواع سس (مایونز)'),(70,'مواد غذایی - پالت: انواع سس (کچاپ)'),(71,'مواد غذایی - پالت: آبمیوه و نوشیدنی‌ها'),(72,'مواد غذایی - پالت: کمپوت و مربا'),(73,'مواد غذایی - پالت: ماکارونی و رشته‌های بسته‌بندی'),(74,'مواد غذایی - پالت: بیسکویت، کیک و شیرینی‌جات صنعتی'),(75,'مواد غذایی - پالت: لبنیات استریل (شیر پاکتی)'),(76,'مواد غذایی - پالت: لبنیات استریل (خامه پاکتی)'),(77,'مواد - بشکه: روغن‌های صنعتی (موتور)'),(78,'مواد - بشکه: روغن‌های صنعتی (هیدرولیک)'),(79,'مواد - بشکه: روغن‌های صنعتی (گیربکس)'),(80,'مواد - بشکه: حلال‌های شیمیایی (استون)'),(81,'مواد - بشکه: حلال‌های شیمیایی (تولوئن)'),(82,'مواد - بشکه: حلال‌های شیمیایی (تینر)'),(83,'مواد - بشکه: حلال‌های شیمیایی (متانول)'),(84,'مواد - بشکه: مواد اولیه شوینده (سولفونیک اسید)'),(85,'مواد - بشکه: مواد اولیه شوینده (تگزاپون)'),(86,'مواد - بشکه: رزین‌ها و چسب‌های صنعتی'),(87,'مواد - بشکه: محصولات غذایی فله‌ای (عسل صنعتی)'),(88,'مواد - بشکه: محصولات غذایی فله‌ای (شیره انگور)'),(89,'مواد - بشکه: محصولات غذایی فله‌ای (گلوکز مایع)'),(90,'مواد - فله: سنگ‌آهن'),(91,'مواد - فله: کنسانتره و گندله'),(92,'مواد - فله: ذغال سنگ'),(93,'مواد - فله: کک'),(94,'مواد - فله: سیمان فله'),(95,'مواد - فله: کلینکر'),(96,'مواد - فله: گندم'),(97,'مواد - فله: جو'),(98,'مواد - فله: ذرت دامی'),(99,'مواد - فله: دانه‌های روغنی (سویا)'),(100,'مواد - فله: دانه‌های روغنی (کلزا)'),(101,'مواد - فله: انواع شن و ماسه'),(102,'مواد - فله: بالاست'),(103,'مواد - فله: مصالح ساختمانی'),(104,'مواد - فله: زغال سنگ'),(105,'مواد - عدل (Bales): ضایعات پرس‌شده (کارتن)'),(106,'مواد - عدل (Bales): ضایعات پرس‌شده (پلاستیک)'),(107,'مواد - عدل (Bales): ضایعات پرس‌شده (پت)'),(108,'مواد - عدل (Bales): ضایعات پرس‌شده (ضایعات فلزی)'),(109,'مواد - عدل (Bales): الیاف نساجی (پنبه)'),(110,'مواد - عدل (Bales): الیاف نساجی (پشم)'),(111,'مواد - عدل (Bales): الیاف نساجی (اکریلیک)'),(112,'مواد - عدل (Bales): الیاف نساجی (پلی‌استر)'),(113,'مواد - عدل (Bales): انواع پارچه و منسوجات'),(114,'مواد - عدل (Bales): کاغذ باطله و خمیر کاغذ فشرده'),(115,'مواد - عدل (Bales): یونجه و کاه پرس‌شده'),(116,'مواد - عدل (Bales): محصولات بازیافتی فشرده'),(117,'مواد - فلکسی تانک: روغن‌های صنعتی و روان‌کارها'),(118,'مواد - فلکسی تانک: مواد پایه نفتی و محصولات پتروشیمی مایع'),(119,'مواد - فلکسی تانک: حلال‌های شیمیایی و مواد اولیه مایع صنعتی'),(120,'مواد - فلکسی تانک: ملاس (شیره چغندر قند یا نیشکر)'),(121,'خوراکی');
/*!40000 ALTER TABLE `cargos_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cities`
--

DROP TABLE IF EXISTS `cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province_id` int unsigned NOT NULL,
  `county_id` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_cities_province` (`province_id`),
  CONSTRAINT `cities_ibfk_1` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12900011002625 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cities`
--

LOCK TABLES `cities` WRITE;
/*!40000 ALTER TABLE `cities` DISABLE KEYS */;
INSERT INTO `cities` VALUES (1000001001101,'اراک','اراک',100,1000001),(1000001001600,'کارچان','کارچان',100,1000001),(1000001002074,'ساروق','ساروق',100,1000001),(1000001002536,'داودآباد','داودآباد',100,1000001),(1000002001103,'آشتیان','آشتیان',100,1000002),(1000003001104,'تفرش','تفرش',100,1000003),(1000004001105,'خمین','خمین',100,1000004),(1000004002541,'قورچی باشی','قورچی-باشی',100,1000004),(1000005001106,'دلیجان','دلیجان',100,1000005),(1000005001107,'نراق','نراق',100,1000005),(1000006001110,'ساوه','ساوه',100,1000006),(1000006001111,'غرق آباد','غرق-آباد',100,1000006),(1000006002137,'نوبران','نوبران',100,1000006),(1000006002827,'آوه','آوه',100,1000006),(1000007001112,'آستانه','آستانه',100,1000007),(1000007001113,'شازند','شازند',100,1000007),(1000007002032,'مهاجران','مهاجران',100,1000007),(1000007002380,'هندودر','هندودر',100,1000007),(1000007002582,'توره','توره',100,1000007),(1000007002774,'شهباز','شهباز',100,1000007),(1000009001115,'محلات','محلات',100,1000009),(1000009001116,'نیمور','نیمور',100,1000009),(1010001001117,'آستارا','آستارا',101,1010001),(1010001002583,'لوندویل','لوندویل',101,1010001),(1010002001118,'کیاشهر','کیاشهر',101,1010002),(1010002001119,'آستانه اشرفیه','آستانه-اشرفیه',101,1010002),(1010003001120,'بندرانزلی','بندرانزلی',101,1010003),(1010004001124,'هشتپر تالش','هشتپر-تالش',101,1010004),(1010004002381,'لیسار','لیسار',101,1010004),(1010004002382,'اسالم','اسالم',101,1010004),(1010004002584,'حویق','حویق',101,1010004),(1010004002585,'چوبر','چوبر',101,1010004),(1010005001007,'پیربازار','پیربازار',101,1010005),(1010005001125,'خشکبیجار','خشکبیجار',101,1010005),(1010005001127,'کوچصفهان','کوچصفهان',101,1010005),(1010005001128,'لشت نشاء','لشت-نشاء',101,1010005),(1010005001129,'رشت','رشت',101,1010005),(1010005001130,'سنگر','سنگر',101,1010005),(1010005002075,'لولمان','لولمان',101,1010005),(1010006001131,'لوشان','لوشان',101,1010006),(1010006001132,'رودبار','رودبار',101,1010006),(1010006001133,'منجیل','منجیل',101,1010006),(1010006002138,'جیرنده','جیرنده',101,1010006),(1010006002139,'رستم آباد','رستم-آباد',101,1010006),(1010006002229,'توتکابن','توتکابن',101,1010006),(1010006002383,'بره سر','بره-سر',101,1010006),(1010007001135,'چابکسر','چابکسر',101,1010007),(1010007001136,'رحیم آباد','رحیم-آباد',101,1010007),(1010007001137,'رودسر','رودسر',101,1010007),(1010007001138,'کلاچای','کلاچای',101,1010007),(1010007001139,'واجارگاه','واجارگاه',101,1010007),(1010008001058,'طاهر گوراب','طاهر-گوراب',101,1010008),(1010008001059,'ضیابر','ضیابر',101,1010008),(1010008001140,'صومعه سرا','صومعه-سرا',101,1010008),(1010008002140,'مرجقل','مرجقل',101,1010008),(1010008002328,'گوراب زرمیخ','گوراب-زرمیخ',101,1010008),(1010009001142,'فومن','فومن',101,1010009),(1010009001143,'ماسوله','ماسوله',101,1010009),(1010009002804,'ماکلوان','ماکلوان',101,1010009),(1020001001148,'رینه','رینه',102,1020001),(1020001001149,'آمل','آمل',102,1020001),(1020001002388,'گزنک','گزنک',102,1020001),(1020001002586,'دابودشت','دابودشت',102,1020001),(1020001002772,'امامزاده عبدالله','امامزاده-عبدالله',102,1020001),(1020001002875,'بابکان','بابکان',102,1020001),(1020002001153,'امیرکلا','امیرکلا',102,1020002),(1020002001154,'بابل','بابل',102,1020002),(1020002002389,'خوش رودپی','خوش-رودپی',102,1020002),(1020002002390,'گلوگاه','گلوگاه',102,1020002),(1020002002391,'مرزیکلا','مرزیکلا',102,1020002),(1020002002392,'زرگر','زرگر',102,1020002),(1020002002587,'گتاب','گتاب',102,1020002),(1020004001158,'بهشهر','بهشهر',102,1020004),(1020004001159,'رستمکلا','رستمکلا',102,1020004),(1020004002647,'خلیل شهر','خلیل-شهر',102,1020004),(1020005001100,'شیرود','شیرود',102,1020005),(1020005001161,'تنکابن','تنکابن',102,1020005),(1020005001162,'خرم آباد','خرم-آباد',102,1020005),(1020005001166,'نشتارود','نشتارود',102,1020005),(1020006001069,'دالخانی','دالخانی',102,1020006),(1020006001167,'رامسر','رامسر',102,1020006),(1020006001168,'کتالم و سادات شهر','کتالم-و-سادات-شهر',102,1020006),(1020007001006,'اکند','اکند',102,1020007),(1020007001169,'ساری','ساری',102,1020007),(1020007002170,'کیاسر','کیاسر',102,1020007),(1020007002588,'فریم','فریم',102,1020007),(1020007002746,'پایین هولار','پایین-هولار',102,1020007),(1020007002874,'فرح آباد','فرح-آباد',102,1020007),(1020008001170,'آلاشت','آلاشت',102,1020008),(1020008001171,'پل سفید','پل-سفید',102,1020008),(1020008001172,'زیرآب','زیرآب',102,1020008),(1030002001200,'اهر','اهر',103,1030002),(1030003001067,'اسفهلان','اسفهلان',103,1030003),(1030003001068,'لاهیجان','لاهیجان',103,1030003),(1030003001206,'خسروشاه','خسروشاه',103,1030003),(1030003001213,'باسمنج','باسمنج',103,1030003),(1030003001214,'تبریز','تبریز',103,1030003),(1030003001215,'سردرود','سردرود',103,1030003),(1030005001216,'مهربان','مهربان',103,1030005),(1030005001220,'سراب','سراب',103,1030005),(1030005002330,'شربیان','شربیان',103,1030005),(1030005002399,'دوزدوزان','دوزدوزان',103,1030005),(1030006001223,'مراغه','مراغه',103,1030006),(1030006002331,'خداجوخراجو','خداجوخراجو',103,1030006),(1030007001026,'دیزج حسین بیگ','دیزج-حسین-بیگ',103,1030007),(1030007001227,'زنوز','زنوز',103,1030007),(1030007001228,'مرند','مرند',103,1030007),(1030007002143,'کشکسرای','کشکسرای',103,1030007),(1030007002400,'یامچی','یامچی',103,1030007),(1030007002589,'بناب مرند','بناب-مرند',103,1030007),(1040001001235,'ارومیه','ارومیه',104,1040001),(1040001002150,'قوشچی','قوشچی',104,1040001),(1040001002151,'نوشین','نوشین',104,1040001),(1040001002403,'سیلوانه','سیلوانه',104,1040001),(1040001002404,'سرو','سرو',104,1040001),(1040002001236,'پیرانشهر','پیرانشهر',104,1040002),(1040002002546,'لاجان','لاجان',104,1040002),(1040003001238,'خوی','خوی',104,1040003),(1040003001239,'فیرورق','فیرورق',104,1040003),(1040003002043,'دیزج دیز','دیزج-دیز',104,1040003),(1040003002044,'قطور','قطور',104,1040003),(1040003002405,'ایواوغلی','ایواوغلی',104,1040003),(1040003002735,'زرآباد','زرآباد',104,1040003),(1040004001240,'سردشت','سردشت',104,1040004),(1040004002547,'ربط','ربط',104,1040004),(1040004002922,'نلاس','نلاس',104,1040004),(1040005001241,'تازه شهر','تازه-شهر',104,1040005),(1040005001242,'سلماس','سلماس',104,1040005),(1040006001246,'ماکو','ماکو',104,1040006),(1040006002231,'بازرگان','بازرگان',104,1040006),(1040007001248,'مهاباد','مهاباد',104,1040007),(1040007002734,'خلیفان','خلیفان',104,1040007),(1040007002869,'گوگ تپه','گوگ-تپه',104,1040007),(1040008001005,'بکتاش','بکتاش',104,1040008),(1040008001251,'میاندوآب','میاندوآب',104,1040008),(1040009001253,'محمدیار','محمدیار',104,1040009),(1040009001254,'نقده','نقده',104,1040009),(1050001001256,'اسلام آبادغرب','اسلام-آبادغرب',105,1050001),(1050001002411,'حمیل','حمیل',105,1050001),(1050002001258,'کرمانشاه','کرمانشاه',105,1050002),(1050002002196,'رباط','رباط',105,1050002),(1050002002410,'هلشی','هلشی',105,1050002),(1050002002412,'کوزران','کوزران',105,1050002),(1050002002896,'قلعه','قلعه',105,1050002),(1050003001261,'پاوه','پاوه',105,1050003),(1050003001262,'نودشه','نودشه',105,1050003),(1050003001263,'نوسود','نوسود',105,1050003),(1050003002103,'باینگان','باینگان',105,1050003),(1050003002761,'بانوره','بانوره',105,1050003),(1050004001264,'سرپل ذهاب','سرپل-ذهاب',105,1050004),(1050005001265,'سنقر','سنقر',105,1050005),(1050005002594,'سطر','سطر',105,1050005),(1050006001266,'قصرشیرین','قصرشیرین',105,1050006),(1050006002197,'سومار','سومار',105,1050006),(1050007001267,'کنگاور','کنگاور',105,1050007),(1050007002834,'گودین','گودین',105,1050007),(1050008001268,'گیلانغرب','گیلانغرب',105,1050008),(1050008002413,'سرمست','سرمست',105,1050008),(1050009001260,'جوانرود','جوانرود',105,1050009),(1050009002900,'شروینه','شروینه',105,1050009),(1060001001269,'اروندکنار','اروندکنار',106,1060001),(1060001001270,'آبادان','آبادان',106,1060001),(1060001002010,'چویبده','چویبده',106,1060001),(1060002001271,'اندیمشک','اندیمشک',106,1060002),(1060002002595,'حسینیه','حسینیه',106,1060002),(1060002002801,'آزادی','آزادی',106,1060002),(1060002002802,'چم گلک','چم-گلک',106,1060002),(1060002002803,'بیدروبه','بیدروبه',106,1060002),(1060003001272,'اهواز','اهواز',106,1060003),(1060003002791,'الهایی','الهایی',106,1060003),(1060004001274,'ایذه','ایذه',106,1060004),(1060005001275,'بندرامام خمینی','بندرامام-خمینی',106,1060005),(1060005001276,'بندرماهشهر','بندرماهشهر',106,1060005),(1060005002311,'چمران','چمران',106,1060005),(1060006001279,'بهبهان','بهبهان',106,1060006),(1060006002500,'سردشت','سردشت',106,1060006),(1060006002797,'منصوریه','منصوریه',106,1060006),(1060006002798,'تشان','تشان',106,1060006),(1060007001280,'خرمشهر','خرمشهر',106,1060007),(1060007002312,'مینوشهر','مینوشهر',106,1060007),(1060007002334,'مقاومت','مقاومت',106,1060007),(1060008001282,'دزفول','دزفول',106,1060008),(1060008002035,'حمزه','حمزه',106,1060008),(1060008002100,'شمس آباد','شمس-آباد',106,1060008),(1060008002416,'سالند','سالند',106,1060008),(1060008002417,'شهر امام','شهر-امام',106,1060008),(1060008002418,'صفی آباد','صفی-آباد',106,1060008),(1060008002419,'میانرود','میانرود',106,1060008),(1060008002717,'چغامیش','چغامیش',106,1060008),(1060008002800,'سیاه منصور','سیاه-منصور',106,1060008),(1060008002927,'شهیون','شهیون',106,1060008),(1060008002967,'منتظران','منتظران',106,1060008),(1060008002983,'جندی شاپور','جندی-شاپور',106,1060008),(1060009001283,'بستان','بستان',106,1060009),(1060009001284,'سوسنگرد','سوسنگرد',106,1060009),(1060009002795,'کوت سیدنعیم','کوت-سیدنعیم',106,1060009),(1060009002796,'ابوحمیظه','ابوحمیظه',106,1060009),(1070001001296,'آباده','آباده',107,1070001),(1070001001297,'صغاد','صغاد',107,1070001),(1070001002198,'بهمن','بهمن',107,1070001),(1070001002335,'ایزدخواست','ایزدخواست',107,1070001),(1070001002597,'سورمق','سورمق',107,1070001),(1070002001298,'استهبان','استهبان',107,1070002),(1070002002177,'رونیز','رونیز',107,1070002),(1070002002552,'ایج','ایج',107,1070002),(1070003001299,'اقلید','اقلید',107,1070003),(1070003002014,'دژکرد','دژکرد',107,1070003),(1070003002082,'حسن اباد','حسن-اباد',107,1070003),(1070003002297,'سده','سده',107,1070003),(1070004001301,'جهرم','جهرم',107,1070004),(1070004002178,'قطب آباد','قطب-آباد',107,1070004),(1070004002707,'دوزه','دوزه',107,1070004),(1070005001000,'پاسخن','پاسخن',107,1070005),(1070005001303,'داراب','داراب',107,1070005),(1070005002016,'فدامی','فدامی',107,1070005),(1070005002081,'دوبرجی','دوبرجی',107,1070005),(1070005002179,'جنت شهر','جنت-شهر',107,1070005),(1070005002905,'رستاق','رستاق',107,1070005),(1070006001304,'اردکان','اردکان',107,1070006),(1070006002711,'هماشهر','هماشهر',107,1070006),(1070007001309,'شیراز','شیراز',107,1070007),(1070007002015,'شهرصدرا','شهرصدرا',107,1070007),(1070007002057,'خانه زنیان','خانه-زنیان',107,1070007),(1070007002298,'داریان','داریان',107,1070007),(1070008001310,'فسا','فسا',107,1070008),(1070008002107,'زاهدشهر','زاهدشهر',107,1070008),(1070008002180,'ششده','ششده',107,1070008),(1070008002652,'نوبندگان','نوبندگان',107,1070008),(1070008002762,'قره بلاغ','قره-بلاغ',107,1070008),(1070008002829,'میانشهر','میانشهر',107,1070008),(1070009001313,'فیروزآباد','فیروزآباد',107,1070009),(1070009001314,'میمند','میمند',107,1070009),(1080001001008,'کشکوییه','کشکوییه',108,1080001),(1080001001328,'بافت','بافت',108,1080001),(1080001002200,'بزنجان','بزنجان',108,1080001),(1080002001079,'دهبکری','دهبکری',108,1080002),(1080002001330,'بم','بم',108,1080002),(1080002001331,'بروات','بروات',108,1080002),(1080003001332,'جیرفت','جیرفت',108,1080003),(1080003002237,'جبالبارز','جبالبارز',108,1080003),(1080003002339,'درب بهشت','درب-بهشت',108,1080003),(1080003002769,'بلوک','بلوک',108,1080003),(1080003002953,'علی آباد','علی-آباد',108,1080003),(1080004001335,'رفسنجان','رفسنجان',108,1080004),(1080004002085,'صفاییه','صفاییه',108,1080004),(1080004002227,'مس سرچشمه','مس-سرچشمه',108,1080004),(1080004002319,'کشکوییه','کشکوییه',108,1080004),(1080004002427,'بهرمان','بهرمان',108,1080004),(1080004002954,'جوادیه الهیه','جوادیه-الهیه',108,1080004),(1080005001337,'زرند','زرند',108,1080005),(1080005002201,'خانوک','خانوک',108,1080005),(1080005002600,'ریحان','ریحان',108,1080005),(1080005002601,'یزدان شهر','یزدان-شهر',108,1080005),(1080005002955,'سیریز','سیریز',108,1080005),(1080006001338,'سیرجان','سیرجان',108,1080006),(1080006002320,'زیدآباد','زیدآباد',108,1080006),(1080006002340,'نجف شهر','نجف-شهر',108,1080006),(1080006002561,'پاریز','پاریز',108,1080006),(1080006002722,'هماشهر','هماشهر',108,1080006),(1080006002823,'خواجو شهر','خواجو-شهر',108,1080006),(1080006002825,'بلورد','بلورد',108,1080006),(1080007001339,'شهربابک','شهربابک',108,1080007),(1080007002030,'خورسند','خورسند',108,1080007),(1080007002031,'جوزم','جوزم',108,1080007),(1080007002086,'خاتون آباد','خاتون-آباد',108,1080007),(1080007002238,'دهج','دهج',108,1080007),(1080008001329,'راین','راین',108,1080008),(1080008001341,'شهداد','شهداد',108,1080008),(1080008001342,'گلباف','گلباف',108,1080008),(1080008001343,'جوپار','جوپار',108,1080008),(1080008001344,'ماهان','ماهان',108,1080008),(1080008001345,'چترود','چترود',108,1080008),(1080008001346,'کرمان','کرمان',108,1080008),(1080008002239,'باغین','باغین',108,1080008),(1080008002321,'محی آباد','محی-آباد',108,1080008),(1080008002323,'زنگی آباد','زنگی-آباد',108,1080008),(1080008002324,'کاظم آباد','کاظم-آباد',108,1080008),(1080008002428,'اختیارآباد','اختیارآباد',108,1080008),(1080008002603,'اندوهجرد','اندوهجرد',108,1080008),(1080009001009,'چاه مرید','چاه-مرید',108,1080009),(1080009001347,'کهنوج','کهنوج',108,1080009),(1080009002956,'ده کهان','ده-کهان',108,1080009),(1090004001357,'تایباد','تایباد',109,1090004),(1090004002562,'کاریز','کاریز',109,1090004),(1090004002654,'مشهد ریزه','مشهد-ریزه',109,1090004),(1090005001024,'نسر','نسر',109,1090005),(1090005001361,'تربت حیدریه','تربت-حیدریه',109,1090005),(1090005002243,'کدکن','کدکن',109,1090005),(1090005002341,'بایک','بایک',109,1090005),(1090005002434,'رباط سنگ','رباط-سنگ',109,1090005),(1090006001363,'تربت جام','تربت-جام',109,1090006),(1090006002067,'احمدآباد صولت','احمدآباد-صولت',109,1090006),(1090006002342,'نصرآباد','نصرآباد',109,1090006),(1090006002655,'نیل شهر','نیل-شهر',109,1090006),(1090006002888,'سمیع آباد','سمیع-آباد',109,1090006),(1090007001364,'لطف آباد','لطف-آباد',109,1090007),(1090007001365,'درگز','درگز',109,1090007),(1090007001366,'نوخندان','نوخندان',109,1090007),(1090007002202,'چاپشلو','چاپشلو',109,1090007),(1090008001367,'سبزوار','سبزوار',109,1090008),(1090008002245,'روداب','روداب',109,1090008),(1100001001395,'اردستان','اردستان',110,1100001),(1100001001396,'زواره','زواره',110,1100001),(1100001002214,'مهاباد','مهاباد',110,1100001),(1100002001406,'اصفهان','اصفهان',110,1100002),(1100002002021,'قهجاورستان','قهجاورستان',110,1100002),(1100002002611,'بهارستان','بهارستان',110,1100002),(1100002002830,'زیار','زیار',110,1100002),(1100003001410,'خمینی شهر','خمینی-شهر',110,1100003),(1100003001411,'درچه','درچه',110,1100003),(1100003002188,'کوشک','کوشک',110,1100003),(1100003002755,'اصغرآباد','اصغرآباد',110,1100003),(1100004001031,'ویست','ویست',110,1100004),(1100004001413,'خوانسار','خوانسار',110,1100004),(1100005001414,'سمیرم','سمیرم',110,1100005),(1100005002249,'حنا','حنا',110,1100005),(1100005002351,'ونک','ونک',110,1100005),(1100005002443,'کمه','کمه',110,1100005),(1100005002923,'بیده','بیده',110,1100005),(1100005002936,'فتح آباد','فتح-آباد',110,1100005),(1100006001417,'داران','داران',110,1100006),(1100006002251,'دامنه','دامنه',110,1100006),(1100007001418,'فریدونشهر','فریدونشهر',110,1100007),(1100007002569,'برف انبار','برف-انبار',110,1100007),(1100008001048,'طاد','طاد',110,1100008),(1100008001049,'بوستان زر','بوستان-زر',110,1100008),(1100008001419,'فلاورجان','فلاورجان',110,1100008),(1100008001420,'کلیشاد و سودرجان','کلیشاد-و-سودرجان',110,1100008),(1100008001439,'قهدریجان','قهدریجان',110,1100008),(1100008002215,'پیربکران','پیربکران',110,1100008),(1100008002252,'ابریشم','ابریشم',110,1100008),(1100008002444,'بهاران شهر','بهاران-شهر',110,1100008),(1100008002445,'اشترجان','اشترجان',110,1100008),(1100008002789,'زازران','زازران',110,1100008),(1100008002997,'مینادشت','مینادشت',110,1100008),(1100009001422,'شهرضا','شهرضا',110,1100009),(1100009002446,'منظریه','منظریه',110,1100009),(1110001001443,'ایرانشهر','ایرانشهر',111,1110001),(1110001002450,'بزمان','بزمان',111,1110001),(1110002001446,'چاه بهار','چاه-بهار',111,1110002),(1110002002894,'پلان','پلان',111,1110002),(1110003001449,'خاش','خاش',111,1110003),(1110003002915,'ده رییس','ده-رییس',111,1110003),(1110003002979,'اسماعیل آباد','اسماعیل-آباد',111,1110003),(1110004001450,'زابل','زابل',111,1110004),(1110004002453,'بنجار','بنجار',111,1110004),(1110005001451,'زاهدان','زاهدان',111,1110005),(1110005002454,'نصرت آباد','نصرت-آباد',111,1110005),(1110005002891,'سرجنگل','سرجنگل',111,1110005),(1110006001047,'اسفندک','اسفندک',111,1110006),(1110006001453,'سراوان','سراوان',111,1110006),(1110006002047,'محمدی','محمدی',111,1110006),(1110006002048,'گشت','گشت',111,1110006),(1110006002455,'سیرکان','سیرکان',111,1110006),(1110007001001,'چانف','چانف',111,1110007),(1110007001448,'نیک شهر','نیک-شهر',111,1110007),(1110007002456,'بنت','بنت',111,1110007),(1110008002461,'راسک','راسک',111,1110008),(1110008002501,'پیشین','پیشین',111,1110008),(1110008002890,'پارود','پارود',111,1110008),(1110009001447,'کنارک','کنارک',111,1110009),(1120001001454,'بانه','بانه',112,1120001),(1120001002457,'آرمرده','آرمرده',112,1120001),(1120001002458,'کانی سور','کانی-سور',112,1120001),(1120001002459,'بویین سفلی','بویین-سفلی',112,1120001),(1120002001455,'بیجار','بیجار',112,1120002),(1120002002190,'یاسوکند','یاسوکند',112,1120002),(1120002002460,'بابارشانی','بابارشانی',112,1120002),(1120002002784,'توپ آغاج','توپ-آغاج',112,1120002),(1120002002785,'پیرتاج','پیرتاج',112,1120002),(1120003001456,'سقز','سقز',112,1120003),(1120003002502,'صاحب','صاحب',112,1120003),(1120003002924,'سنته','سنته',112,1120003),(1120004001458,'سنندج','سنندج',112,1120004),(1120004002462,'شویشه','شویشه',112,1120004),(1120004002895,'حسین آباد','حسین-آباد',112,1120004),(1120005001037,'مالوجه','مالوجه',112,1120005),(1120005001461,'سریش آباد','سریش-آباد',112,1120005),(1120005001462,'قروه','قروه',112,1120005),(1120005002426,'دلبران','دلبران',112,1120005),(1120005002463,'دزج','دزج',112,1120005),(1120006001463,'مریوان','مریوان',112,1120006),(1120006002055,'کانی دینار','کانی-دینار',112,1120006),(1120006002464,'چناره','چناره',112,1120006),(1120006002753,'برده رشه','برده-رشه',112,1120006),(1120007001459,'دیواندره','دیواندره',112,1120007),(1120007002465,'زرینه','زرینه',112,1120007),(1120007002925,'هزارکانیان','هزارکانیان',112,1120007),(1120008001457,'کامیاران','کامیاران',112,1120008),(1120008002466,'موچش','موچش',112,1120008),(1120009002205,'سروآباد','سروآباد',112,1120009),(1120009002752,'اورامان تخت','اورامان-تخت',112,1120009),(1130001001464,'تویسرکان','تویسرکان',113,1130001),(1130001001465,'سرکان','سرکان',113,1130001),(1130001002467,'فرسفج','فرسفج',113,1130001),(1130002001466,'سامن','سامن',113,1130002),(1130002001467,'ملایر','ملایر',113,1130002),(1130002002253,'ازندریان','ازندریان',113,1130002),(1130002002468,'جوکار','جوکار',113,1130002),(1130002002658,'زنگنه','زنگنه',113,1130002),(1130002002871,'اسلام شهر آق گل','اسلام-شهر-آق-گل',113,1130002),(1130003001468,'نهاوند','نهاوند',113,1130003),(1130003002206,'فیروزان','فیروزان',113,1130003),(1130003002659,'برزول','برزول',113,1130003),(1130003002660,'گیان','گیان',113,1130003),(1130004001474,'مریانج','مریانج',113,1130004),(1130004001475,'همدان','همدان',113,1130004),(1130004002254,'جورقان','جورقان',113,1130004),(1130004002356,'قهاوند','قهاوند',113,1130004),(1130005001473,'کبودرآهنگ','کبودرآهنگ',113,1130005),(1130005002469,'گل تپه','گل-تپه',113,1130005),(1130005002470,'شیرین سو','شیرین-سو',113,1130005),(1130006001469,'اسدآباد','اسدآباد',113,1130006),(1130006002758,'آجین','آجین',113,1130006),(1130006002994,'پالیز','پالیز',113,1130006),(1130007001471,'بهار','بهار',113,1130007),(1130007001472,'لالجین','لالجین',113,1130007),(1130007002216,'صالح آباد','صالح-آباد',113,1130007),(1130007002757,'مهاجران','مهاجران',113,1130007),(1130008002171,'رزن','رزن',113,1130008),(1130008002357,'دمق','دمق',113,1130008),(1130009002192,'فامنین','فامنین',113,1130009),(1140001001477,'بلداجی','بلداجی',114,1140001),(1140001001478,'بروجن','بروجن',114,1140001),(1140001001479,'فرادبنه','فرادبنه',114,1140001),(1140001002152,'گندمان','گندمان',114,1140001),(1140001002306,'سفیددشت','سفیددشت',114,1140001),(1140001002706,'نقنه','نقنه',114,1140001),(1140002001482,'شهرکرد','شهرکرد',114,1140002),(1140002001484,'هفشجان','هفشجان',114,1140002),(1140002002113,'سورشجان','سورشجان',114,1140002),(1140002002123,'کیان','کیان',114,1140002),(1140002002359,'طاقانک','طاقانک',114,1140002),(1140002002471,'نافچ','نافچ',114,1140002),(1140002002614,'سودجان','سودجان',114,1140002),(1140002002838,'هارونی','هارونی',114,1140002),(1140003001485,'جونقان','جونقان',114,1140003),(1140003001486,'فارسان','فارسان',114,1140003),(1140003002124,'بآباحیدر','بآباحیدر',114,1140003),(1140003002730,'پردنجان','پردنجان',114,1140003),(1140003002805,'گوجان','گوجان',114,1140003),(1140003002806,'چلیچه','چلیچه',114,1140003),(1140003002938,'فیل آباد','فیل-آباد',114,1140003),(1140004001487,'لردگان','لردگان',114,1140004),(1140004002731,'منج','منج',114,1140004),(1140004002840,'سردشت','سردشت',114,1140004),(1140005001476,'اردل','اردل',114,1140005),(1140005002807,'کاج','کاج',114,1140005),(1140005002808,'دشتک','دشتک',114,1140005),(1140005002809,'سرخون','سرخون',114,1140005),(1140006002360,'چلگرد','چلگرد',114,1140006),(1140006002839,'صمصامی','صمصامی',114,1140006),(1140006002841,'بازفت','بازفت',114,1140006),(1140007002153,'شلمزار','شلمزار',114,1140007),(1140007002154,'ناغان','ناغان',114,1140007),(1140007002358,'گهرو','گهرو',114,1140007),(1140007002729,'دستنا','دستنا',114,1140007),(1140008001481,'سامان','سامان',114,1140008),(1140008002918,'هوره','هوره',114,1140008),(1140009001480,'بن','بن',114,1140009),(1140009002732,'وردنجان','وردنجان',114,1140009),(1140009002919,'یان چشمه','یان-چشمه',114,1140009),(1150001001056,'تیتکان بزنوید','تیتکان-بزنوید',115,1150001),(1150001001489,'الیگودرز','الیگودرز',115,1150001),(1150001002728,'شول آباد','شول-آباد',115,1150001),(1150001002876,'چمن سلطان','چمن-سلطان',115,1150001),(1150001002877,'شاهپوراباد','شاهپوراباد',115,1150001),(1150002001490,'اشترینان','اشترینان',115,1150002),(1150002001492,'بروجرد','بروجرد',115,1150002),(1150002002971,'ونایی','ونایی',115,1150002),(1150003001496,'خرم آباد','خرم-آباد',115,1150003),(1150003002361,'زاغه','زاغه',115,1150003),(1150003002474,'سپیددشت','سپیددشت',115,1150003),(1150003002475,'بیران شهر','بیران-شهر',115,1150003),(1150004001494,'نورآباد','نورآباد',115,1150004),(1150004002727,'هفت چشمه','هفت-چشمه',115,1150004),(1150004002995,'برخوردار','برخوردار',115,1150004),(1150005001491,'دورود','دورود',115,1150005),(1150005002363,'چالانچولان','چالانچولان',115,1150005),(1150006001495,'کوهدشت','کوهدشت',115,1150006),(1150006002257,'گراب','گراب',115,1150006),(1150006002364,'کوهنانی','کوهنانی',115,1150006),(1150006002615,'درب گنبد','درب-گنبد',115,1150006),(1150007001488,'ازنا','ازنا',115,1150007),(1150007002365,'مومن آباد','مومن-آباد',115,1150007),(1150008001497,'پلدختر','پلدختر',115,1150008),(1150008002996,'سراب حمام','سراب-حمام',115,1150008),(1150009001493,'الشتر','الشتر',115,1150009),(1150009002367,'فیروزآباد','فیروزآباد',115,1150009),(1160001001500,'ایلام','ایلام',116,1160001),(1160001002974,'جعفراباد','جعفراباد',116,1160001),(1160002001503,'دره شهر','دره-شهر',116,1160002),(1160002002849,'ماژین','ماژین',116,1160002),(1160003001063,'دشت عباس','دشت-عباس',116,1160003),(1160003001504,'میمه','میمه',116,1160003),(1160003001505,'دهلران','دهلران',116,1160003),(1160003001506,'موسیان','موسیان',116,1160003),(1160003002127,'پهله','پهله',116,1160003),(1160004001507,'سرابله','سرابله',116,1160004),(1160004002617,'آسمان آباد','آسمان-آباد',116,1160004),(1160004002850,'شباب','شباب',116,1160004),(1160004002851,'بلاوه','بلاوه',116,1160004),(1160005001509,'صالح آباد','صالح-آباد',116,1160005),(1160005001510,'مهران','مهران',116,1160005),(1160006001501,'آبدانان','آبدانان',116,1160006),(1160006002056,'سراب باغ','سراب-باغ',116,1160006),(1160006002616,'مورموری','مورموری',116,1160006),(1160007001498,'ایوان','ایوان',116,1160007),(1160007002476,'زرنه','زرنه',116,1160007),(1160008001508,'ارکواز','ارکواز',116,1160008),(1160008002028,'دلگشا','دلگشا',116,1160008),(1160008002745,'مهر','مهر',116,1160008),(1160009002155,'لومار','لومار',116,1160009),(1170001001080,'سپیدار','سپیدار',117,1170001),(1170001001512,'یاسوج','یاسوج',117,1170001),(1170001002023,'مادوان','مادوان',117,1170001),(1170001002083,'چیتاب','چیتاب',117,1170001),(1170001002478,'گراب سفلی','گراب-سفلی',117,1170001),(1170002001514,'دهدشت','دهدشت',117,1170002),(1170002002156,'سوق','سوق',117,1170002),(1170002002479,'قلعه رییسی','قلعه-رییسی',117,1170002),(1170002002480,'دیشموک','دیشموک',117,1170002),(1170003001515,'دوگنبدان','دوگنبدان',117,1170003),(1170004001511,'سی سخت','سی-سخت',117,1170004),(1170004002084,'پاتاوه','پاتاوه',117,1170004),(1170005001081,'قلعه ممبی','قلعه-ممبی',117,1170005),(1170005001082,'سرآسیاب یوسفی','سرآسیاب-یوسفی',117,1170005),(1170005002368,'لیکک','لیکک',117,1170005),(1170006001513,'چرام','چرام',117,1170006),(1170006002828,'سرفاریاب','سرفاریاب',117,1170006),(1170007002130,'باشت','باشت',117,1170007),(1170007002917,'بوستان','بوستان',117,1170007),(1170008002129,'لنده','لنده',117,1170008),(1170009002477,'مارگون','مارگون',117,1170009),(1180001001516,'خارک','خارک',118,1180001),(1180001001517,'بوشهر','بوشهر',118,1180001),(1180001002481,'چغادک','چغادک',118,1180001),(1180001002867,'عالی شهر','عالی-شهر',118,1180001),(1180002001518,'اهرم','اهرم',118,1180002),(1180002002258,'دلوار','دلوار',118,1180002),(1180002002816,'آباد','آباد',118,1180002),(1180003001519,'سعد آباد','سعد-آباد',118,1180003),(1180003001520,'برازجان','برازجان',118,1180003),(1180003002157,'شبانکاره','شبانکاره',118,1180003),(1180003002308,'آب پخش','آب-پخش',118,1180003),(1180003002369,'دالکی','دالکی',118,1180003),(1180003002482,'وحدتیه','وحدتیه',118,1180003),(1180003002618,'تنگ ارم','تنگ-ارم',118,1180003),(1180003002619,'کلمه','کلمه',118,1180003),(1180003002817,'بوشکان','بوشکان',118,1180003),(1180004001521,'خورموج','خورموج',118,1180004),(1180004002050,'شنبه','شنبه',118,1180004),(1180004002131,'کاکی','کاکی',118,1180004),(1180004002818,'بادوله','بادوله',118,1180004),(1180005001522,'بندردیر','بندردیر',118,1180005),(1180005002483,'بردخون','بردخون',118,1180005),(1180005002484,'آبدان','آبدان',118,1180005),(1180005002737,'بردستان','بردستان',118,1180005),(1180005002819,'دوراهک','دوراهک',118,1180005),(1180006001076,'شیرینو','شیرینو',118,1180006),(1180006001523,'بندرکنگان','بندرکنگان',118,1180006),(1180006002620,'بنک','بنک',118,1180006),(1180006002661,'سیراف','سیراف',118,1180006),(1180007001525,'بندرریگ','بندرریگ',118,1180007),(1180007001526,'بندرگناوه','بندرگناوه',118,1180007),(1180008001524,'بندردیلم','بندردیلم',118,1180008),(1180008002370,'امام حسن','امام-حسن',118,1180008),(1180009002087,'انارستان','انارستان',118,1180009),(1180009002259,'جم','جم',118,1180009),(1180009002662,'ریز','ریز',118,1180009),(1180009002933,'بهارستان','بهارستان',118,1180009),(1190001001528,'ابهر','ابهر',119,1190001),(1190001001530,'صایین قلعه','صایین-قلعه',119,1190001),(1190001001531,'هیدج','هیدج',119,1190001),(1190003001533,'قیدار','قیدار',119,1190003),(1190003002039,'سهرورد','سهرورد',119,1190003),(1190003002260,'گرماب','گرماب',119,1190003),(1190003002371,'سجاس','سجاس',119,1190003),(1190003002376,'زرین رود','زرین-رود',119,1190003),(1190003002832,'کرسف','کرسف',119,1190003),(1190003002833,'نوربهار','نوربهار',119,1190003),(1190004001534,'زنجان','زنجان',119,1190004),(1190004002038,'ارمغانخانه','ارمغانخانه',119,1190004),(1190004002764,'نیک پی','نیک-پی',119,1190004),(1190006002327,'زرین آباد','زرین-آباد',119,1190006),(1190006002485,'حلب','حلب',119,1190006),(1190007001529,'خرمدره','خرمدره',119,1190007),(1190008002262,'آب بر','آب-بر',119,1190008),(1190008002486,'چورزق','چورزق',119,1190008),(1190009002207,'ماه نشان','ماه-نشان',119,1190009),(1190009002261,'دندی','دندی',119,1190009),(1200001001540,'دامغان','دامغان',120,1200001),(1200001002263,'امیریه','امیریه',120,1200001),(1200001002264,'دیباج','دیباج',120,1200001),(1200001002759,'کلاته','کلاته',120,1200001),(1200002001542,'سمنان','سمنان',120,1200002),(1200003001545,'بسطام','بسطام',120,1200003),(1200003001546,'مجن','مجن',120,1200003),(1200003001547,'شاهرود','شاهرود',120,1200003),(1200003002160,'کلاته خیج','کلاته-خیج',120,1200003),(1200003002161,'بیارجمند','بیارجمند',120,1200003),(1200003002775,'رودیان','رودیان',120,1200003),(1200004001548,'ایوانکی','ایوانکی',120,1200004),(1200004001550,'گرمسار','گرمسار',120,1200004),(1200005001543,'شهمیرزاد','شهمیرزاد',120,1200005),(1200005001544,'مهدی شهر','مهدی-شهر',120,1200005),(1200005002022,'درجزین','درجزین',120,1200005),(1200006001549,'آرادان','آرادان',120,1200006),(1200006002768,'کهن آباد','کهن-آباد',120,1200006),(1200007002162,'میامی','میامی',120,1200007),(1200007002990,'رضوان','رضوان',120,1200007),(1200008001541,'سرخه','سرخه',120,1200008),(1210001001551,'اردکان','اردکان',121,1210001),(1210001002265,'احمد آباد','احمد-آباد',121,1210001),(1210001002623,'عقدا','عقدا',121,1210001),(1210001002984,'خرانق','خرانق',121,1210001),(1210002001554,'بافق','بافق',121,1210002),(1210003001555,'تفت','تفت',121,1210003),(1210003001556,'نیر','نیر',121,1210003),(1210003002976,'بخ','بخ',121,1210003),(1210004001558,'مهریز','مهریز',121,1210004),(1210005001017,'یزد_منطقه تاریخی','یزد_منطقه-تاریخی',121,1210005),(1210005001562,'یزد','یزد',121,1210005),(1210005002126,'شاهدیه','شاهدیه',121,1210005),(1210005002163,'حمیدیا','حمیدیا',121,1210005),(1210006001552,'میبد','میبد',121,1210006),(1210006002024,'بفروییه','بفروییه',121,1210006),(1210006002322,'ندوشن','ندوشن',121,1210006),(1210007001293,'ابرکوه','ابرکوه',121,1210007),(1210007002372,'مهردشت','مهردشت',121,1210007),(1210008001560,'اشکذر','اشکذر',121,1210008),(1210008002377,'خضرآباد','خضرآباد',121,1210008),(1210008002985,'مجومرد','مجومرد',121,1210008),(1210009001559,'هرات','هرات',121,1210009),(1220001001563,'ابوموسی','ابوموسی',122,1220001),(1220002001567,'بندر عباس','بندر-عباس',122,1220002),(1220002001572,'هرمز','هرمز',122,1220002),(1220002002063,'قلعه قاضی','قلعه-قاضی',122,1220002),(1220002002088,'تخت','تخت',122,1220002),(1220002002307,'فین','فین',122,1220002),(1220002002812,'تازیان پایین','تازیان-پایین',122,1220002),(1220003001570,'کنگ','کنگ',122,1220003),(1220003001571,'بندر لنگه','بندر-لنگه',122,1220003),(1220003002193,'کیش','کیش',122,1220003),(1220003002266,'چارک','چارک',122,1220003),(1220003002813,'لمزان','لمزان',122,1220003),(1220004001033,'رمکان','رمکان',122,1220004),(1220004001034,'لافت','لافت',122,1220004),(1220004001573,'قشم','قشم',122,1220004),(1220004002487,'سوزا','سوزا',122,1220004),(1220004002627,'درگهان','درگهان',122,1220004),(1220004002940,'طبل','طبل',122,1220004),(1220005001035,'زهوکی','زهوکی',122,1220005),(1220005001036,'کرگان','کرگان',122,1220005),(1220005001575,'میناب','میناب',122,1220005),(1220005002003,'هشتبندی','هشتبندی',122,1220005),(1220005002064,'سندرک','سندرک',122,1220005),(1220005002814,'تیرور','تیرور',122,1220005),(1220005002910,'بند زرک','بند-زرک',122,1220005),(1220006001565,'بندر جاسک','بندر-جاسک',122,1220006),(1220006002872,'لیردف','لیردف',122,1220006),(1220007001574,'دهبارز','دهبارز',122,1220007),(1220007002006,'بیکاء','بیکاء',122,1220007),(1220007002663,'زیارتعلی','زیارتعلی',122,1220007),(1220007002911,'بالا شهر','بالا-شهر',122,1220007),(1220008001566,'حاجی آباد','حاجی-آباد',122,1220008),(1220008002092,'سرگز','سرگز',122,1220008),(1220008002626,'فارغان','فارغان',122,1220008),(1220009001568,'بستک','بستک',122,1220009),(1220009002625,'جناح','جناح',122,1220009),(1220009002959,'هنگوییه','هنگوییه',122,1220009),(1220009002960,'کوهیچ','کوهیچ',122,1220009),(1230001001576,'تهران','تهران',123,1230001),(1230002001578,'دماوند','دماوند',123,1230002),(1230002001579,'رودهن','رودهن',123,1230002),(1230002001580,'کیلان','کیلان',123,1230002),(1230002002269,'آبسرد','آبسرد',123,1230002),(1230002002629,'آبعلی','آبعلی',123,1230002),(1230003002115,'ری','ری',123,1230003),(1230003002167,'حسن آباد','حسن-آباد',123,1230003),(1230003002270,'باقرشهر','باقرشهر',123,1230003),(1230003002488,'کهریزک','کهریزک',123,1230003),(1230003002886,'قیام دشت','قیام-دشت',123,1230003),(1230003002887,'قلعه نو','قلعه-نو',123,1230003),(1230004001582,'فشم','فشم',123,1230004),(1230004001583,'لواسان','لواسان',123,1230004),(1230004002117,'تجریش','تجریش',123,1230004),(1230004002778,'شمشک','شمشک',123,1230004),(1230006001594,'ورامین','ورامین',123,1230006),(1230006002168,'جوادآباد','جوادآباد',123,1230006),(1230009001588,'شهریار','شهریار',123,1230009),(1230009002277,'صباشهر','صباشهر',123,1230009),(1230009002293,'وحیدیه','وحیدیه',123,1230009),(1230009002303,'شاهدشهر','شاهدشهر',123,1230009),(1230009002491,'فردوسیه','فردوسیه',123,1230009),(1230009002632,'باغستان','باغستان',123,1230009),(1230009002633,'اندیشه','اندیشه',123,1230009),(1240001001195,'اردبیل','اردبیل',124,1240001),(1240001002299,'هیر','هیر',124,1240001),(1240001002882,'ثمرین','ثمرین',124,1240001),(1240001002949,'آراللو','آراللو',124,1240001),(1240002001230,'بیله سوار','بیله-سوار',124,1240002),(1240002002209,'جعفرآباد','جعفرآباد',124,1240002),(1240003001219,'خلخال','خلخال',124,1240003),(1240003002164,'هشتجین','هشتجین',124,1240003),(1240003002165,'کلور','کلور',124,1240003),(1240004001229,'مشگین شهر','مشگین-شهر',124,1240004),(1240004002300,'رضی','رضی',124,1240004),(1240004002301,'لاهرود','لاهرود',124,1240004),(1240004002704,'فخراباد','فخراباد',124,1240004),(1240004002723,'مرادلو','مرادلو',124,1240004),(1240004002749,'قصابه','قصابه',124,1240004),(1240004002948,'النی','النی',124,1240004),(1240005001232,'گرمی','گرمی',124,1240005),(1240005002880,'زهرا','زهرا',124,1240005),(1240006001032,'اولتان','اولتان',124,1240006),(1240006001231,'پارس آباد','پارس-آباد',124,1240006),(1240006002029,'مغان سر','مغان-سر',124,1240006),(1240006002750,'اسلام اباد','اسلام-اباد',124,1240006),(1240007001073,'فیروزآباد','فیروزآباد',124,1240007),(1240007001218,'گیوی','گیوی',124,1240007),(1240008001197,'نمین','نمین',124,1240008),(1240008002493,'آبی بیگلو','آبی-بیگلو',124,1240008),(1240008002576,'عنبران','عنبران',124,1240008),(1240009001198,'نیر','نیر',124,1240009),(1240009002636,'کوراییم','کوراییم',124,1240009),(1250001001114,'قم','قم',125,1250001),(1250001002027,'سلفچگان','سلفچگان',125,1250001),(1250001002515,'دستجرد','دستجرد',125,1250001),(1250001002516,'قنوات','قنوات',125,1250001),(1250002001062,'قاهان','قاهان',125,1250002),(1250002002274,'جعفریه','جعفریه',125,1250002),(1250003002275,'کهک','کهک',125,1250003),(1260001001537,'بویین زهرا','بویین-زهرا',126,1260001),(1260001001538,'شال','شال',126,1260001),(1260001002159,'دانسفهان','دانسفهان',126,1260001),(1260001002577,'ارداق','ارداق',126,1260001),(1260001002638,'سگزآباد','سگزآباد',126,1260001),(1260001002991,'عصمت آباد','عصمت-آباد',126,1260001),(1260002001532,'تاکستان','تاکستان',126,1260002),(1260002002114,'اسفرورین','اسفرورین',126,1260002),(1260002002158,'ضیاآباد','ضیاآباد',126,1260002),(1260002002374,'خرمدشت','خرمدشت',126,1260002),(1260002002639,'نرجه','نرجه',126,1260002),(1260003001595,'قزوین','قزوین',126,1260003),(1260003002125,'اقبالیه','اقبالیه',126,1260003),(1260003002375,'محمودآبادنمونه','محمودآبادنمونه',126,1260003),(1260003002494,'معلم کلایه','معلم-کلایه',126,1260003),(1260003002495,'رازمیان','رازمیان',126,1260003),(1260003002579,'کوهین','کوهین',126,1260003),(1260003002667,'سیردان','سیردان',126,1260003),(1260004001535,'آبیک','آبیک',126,1260004),(1260004002637,'خاکعلی','خاکعلی',126,1260004),(1260004002992,'زیاران','زیاران',126,1260004),(1260004002993,'قشلاق','قشلاق',126,1260004),(1260005001539,'الوند','الوند',126,1260005),(1260005002040,'شریفیه','شریفیه',126,1260005),(1260005002218,'محمدیه','محمدیه',126,1260005),(1260005002578,'بیدستان','بیدستان',126,1260005),(1260006001536,'آوج','آوج',126,1260006),(1260006002517,'آبگرم','آبگرم',126,1260006),(1270001001178,'بندرگز','بندرگز',127,1270001),(1270001002194,'نوکنده','نوکنده',127,1270001),(1270002001156,'بندرترکمن','بندرترکمن',127,1270002),(1270002002975,'سیجوال','سیجوال',127,1270002),(1270003001174,'علی آباد','علی-آباد',127,1270003),(1270003002507,'فاضل آباد','فاضل-آباد',127,1270003),(1270003002836,'مزرعه','مزرعه',127,1270003),(1270003002837,'سنگدوین','سنگدوین',127,1270003),(1270004001179,'کردکوی','کردکوی',127,1270004),(1270005001181,'گرگان','گرگان',127,1270005),(1270005002018,'جلین','جلین',127,1270005),(1270005002508,'سرخنکلاته','سرخنکلاته',127,1270005),(1270005002868,'قرق','قرق',127,1270005),(1270006001030,'کرند','کرند',127,1270006),(1270006001186,'گنبدکاووس','گنبدکاووس',127,1270006),(1270006002496,'اینچه برون','اینچه-برون',127,1270006),(1270007001071,'القجر','القجر',127,1270007),(1270007001188,'مینودشت','مینودشت',127,1270007),(1270007002883,'دوزین','دوزین',127,1270007),(1270008001180,'آق قلا','آق-قلا',127,1270008),(1270008002509,'انبار آلوم','انبار-آلوم',127,1270008),(1270009001185,'کلاله','کلاله',127,1270009),(1270009002835,'فراغی','فراغی',127,1270009),(1280001001349,'اسفراین','اسفراین',128,1280001),(1280002001353,'بجنورد','بجنورد',128,1280002),(1280002002431,'حصارگرمخان','حصارگرمخان',128,1280002),(1280002002847,'چناران شهر','چناران-شهر',128,1280002),(1280003001350,'جاجرم','جاجرم',128,1280003),(1280003002432,'سنخواست','سنخواست',128,1280003),(1280003002433,'شوقان','شوقان',128,1280003),(1280004001038,'خانلق','خانلق',128,1280004),(1280004001368,'شیروان','شیروان',128,1280004),(1280004002435,'لوجلی','لوجلی',128,1280004),(1280004002845,'زیارت','زیارت',128,1280004),(1280004002846,'قوشخانه','قوشخانه',128,1280004),(1280005001377,'فاروج','فاروج',128,1280005),(1280005002079,'تیتکانلو','تیتکانلو',128,1280005),(1280006001352,'آشخانه','آشخانه',128,1280006),(1280006002567,'قاضی','قاضی',128,1280006),(1280006002848,'آوا','آوا',128,1280006),(1280007002053,'ایور','ایور',128,1280007),(1280007002054,'گرمه','گرمه',128,1280007),(1280007002641,'درق','درق',128,1280007),(1280008002132,'راز','راز',128,1280008),(1280008002942,'غلامان','غلامان',128,1280008),(1280008002943,'یکه سعود','یکه-سعود',128,1280008),(1280009002430,'صفی آباد','صفی-آباد',128,1280009),(1290001001355,'بیرجند','بیرجند',129,1290001),(1290002002051,'قهستان','قهستان',129,1290002),(1290002002052,'طبس مسینا','طبس-مسینا',129,1290002),(1290002002276,'اسدیه','اسدیه',129,1290002),(1290002002725,'گزیک','گزیک',129,1290002),(1290003001354,'سربیشه','سربیشه',129,1290003),(1290003002642,'مود','مود',129,1290003),(1290003002897,'درح','درح',129,1290003),(1290004001374,'خضری دشت بیاض','خضری-دشت-بیاض',129,1290004),(1290004001375,'قاین','قاین',129,1290004),(1290004002643,'اسفدن','اسفدن',129,1290004),(1290004002645,'آرین شهر','آرین-شهر',129,1290004),(1290004002646,'نیمبلوک','نیمبلوک',129,1290004),(1290005001356,'نهبندان','نهبندان',129,1290005),(1290005002441,'شوسف','شوسف',129,1290005),(1290006001371,'سرایان','سرایان',129,1290006),(1290006002346,'آیسک','آیسک',129,1290006),(1290006002563,'سه قلعه','سه-قلعه',129,1290006),(1290007001002,'باغستان','باغستان',129,1290007),(1290007001372,'اسلامیه','اسلامیه',129,1290007),(1290007001373,'فردوس','فردوس',129,1290007),(1290008001370,'بشرویه','بشرویه',129,1290008),(1290008002726,'ارسک','ارسک',129,1290008),(1290009002347,'حاجی آباد','حاجی-آباد',129,1290009),(1290009002644,'زهان','زهان',129,1290009),(1290009002898,'آبیز','آبیز',129,1290009),(1300001001590,'کرج','کرج',130,1300001),(1300001002070,'آسارا','آسارا',130,1300001),(1300001002118,'ماهدشت','ماهدشت',130,1300001),(1300001002272,'کمال شهر','کمال-شهر',130,1300001),(1300001002273,'محمد شهر','محمد-شهر',130,1300001),(1300001002635,'گرمدره','گرمدره',130,1300001),(1300002001587,'هشتگرد','هشتگرد',130,1300002),(1300002002026,'گلسار','گلسار',130,1300002),(1300002002630,'کوهسار','کوهسار',130,1300002),(1300002002664,'مهستان','مهستان',130,1300002),(1300003001586,'نظرآباد','نظرآباد',130,1300003),(1300003002025,'تنکمان','تنکمان',130,1300003),(1300004002169,'طالقان','طالقان',130,1300004),(1300005001584,'اشتهارد','اشتهارد',130,1300005),(1300005002981,'پلنگ آباد','پلنگ-آباد',130,1300005),(1300006002271,'مشکین دشت','مشکین-دشت',130,1300006),(1300006002282,'فردیس','فردیس',130,1300006),(1300007002631,'چهارباغ','چهارباغ',130,1300007),(10000010001108,'زاویه','زاویه',100,10000010),(10000010001109,'مامونیه','مامونیه',100,10000010),(10000010002017,'خشکرود','خشکرود',100,10000010),(10000010002228,'پرندک','پرندک',100,10000010),(10000010002378,'رازقان','رازقان',100,10000010),(10000011001102,'کمیجان','کمیجان',100,10000011),(10000011002379,'میلاجرد','میلاجرد',100,10000011),(10000012002073,'جاورسیان','جاورسیان',100,10000012),(10000012002304,'خنداب','خنداب',100,10000012),(10000013002136,'فرمهین','فرمهین',100,10000013),(10000013002773,'خنجین','خنجین',100,10000013),(10000013002941,'تلخاب','تلخاب',100,10000013),(10100010001144,'کومله','کومله',101,10100010),(10100010001145,'لنگرود','لنگرود',101,10100010),(10100010002076,'چاف و چمخاله','چاف-و-چمخاله',101,10100010),(10100010002141,'شلمان','شلمان',101,10100010),(10100010002385,'اطاقور','اطاقور',101,10100010),(10100011001147,'لاهیجان','لاهیجان',101,10100011),(10100011002386,'رودبنه','رودبنه',101,10100011),(10100012001141,'شفت','شفت',101,10100012),(10100012002387,'احمد سرگوراب','احمد-سرگوراب',101,10100012),(10100013001134,'املش','املش',101,10100013),(10100013002519,'رانکوه','رانکوه',101,10100013),(10100014001121,'پره سر','پره-سر',101,10100014),(10100014001122,'رضوانشهر','رضوانشهر',101,10100014),(10100015001146,'سیاهکل','سیاهکل',101,10100015),(10100015002329,'دیلمان','دیلمان',101,10100015),(10100016001123,'ماسال','ماسال',101,10100016),(10100016002510,'بازار جمعه','بازار-جمعه',101,10100016),(10100017001060,'چوکام','چوکام',101,10100017),(10100017001126,'خمام','خمام',101,10100017),(10200010001176,'قایم شهر','قایم-شهر',102,10200010),(10200010002747,'ارطه','ارطه',102,10200010),(10200014001189,'نور','نور',102,10200014),(10200014001193,'رویان','رویان',102,10200014),(10200014002119,'چمستان','چمستان',102,10200014),(10200014002396,'بلده','بلده',102,10200014),(10200014002648,'ایزدشهر','ایزدشهر',102,10200014),(10200015001194,'نوشهر','نوشهر',102,10200015),(10200015002713,'پول','پول',102,10200015),(10200015002779,'کجور','کجور',102,10200015),(10200016001151,'بابلسر','بابلسر',102,10200016),(10200016002393,'بهنمیر','بهنمیر',102,10200016),(10200016002394,'هادی شهر','هادی-شهر',102,10200016),(10200018001150,'محمودآباد','محمودآباد',102,10200018),(10200018002395,'سرخرود','سرخرود',102,10200018),(10200019001160,'نکا','نکا',102,10200019),(10200020001190,'چالوس','چالوس',102,10200020),(10200020001192,'مرزن آباد','مرزن-آباد',102,10200020),(10200020002748,'هچیرود','هچیرود',102,10200020),(10200021001175,'جویبار','جویبار',102,10200021),(10200021002397,'کوهی خیل','کوهی-خیل',102,10200021),(10200022001157,'گلوگاه','گلوگاه',102,10200022),(10200023001039,'آستانه سرا','آستانه-سرا',102,10200023),(10200023001152,'فریدونکنار','فریدونکنار',102,10200023),(10200024001163,'سلمان شهر','سلمان-شهر',102,10200024),(10200024001164,'عباس آباد','عباس-آباد',102,10200024),(10200024001165,'کلارآباد','کلارآباد',102,10200024),(10200025002305,'سورک','سورک',102,10200025),(10200025002931,'طبقده','طبقده',102,10200025),(10200026001177,'کیاکلا','کیاکلا',102,10200026),(10200027001173,'شیرگاه','شیرگاه',102,10200027),(10200028001191,'کلاردشت','کلاردشت',102,10200028),(10300010001233,'میانه','میانه',103,10300010),(10300010002144,'ترکمانچای','ترکمانچای',103,10300010),(10300010002401,'آقکند','آقکند',103,10300010),(10300010002402,'ترک','ترک',103,10300010),(10300010002842,'اچاچی','اچاچی',103,10300010),(10300011001234,'هشترود','هشترود',103,10300011),(10300011002590,'نظرکهریزی','نظرکهریزی',103,10300011),(10300012001221,'بناب','بناب',103,10300012),(10300012002934,'خوشه مهر','خوشه-مهر',103,10300012),(10300013001025,'کردکندی','کردکندی',103,10300013),(10300013001207,'بستان آباد','بستان-آباد',103,10300013),(10300013002295,'تیکمه داش','تیکمه-داش',103,10300013),(10300014001208,'تسوج','تسوج',103,10300014),(10300014001209,'خامنه','خامنه',103,10300014),(10300014001210,'شبستر','شبستر',103,10300014),(10300014001211,'شرفخانه','شرفخانه',103,10300014),(10300014001212,'صوفیان','صوفیان',103,10300014),(10300014002101,'شندآباد','شندآباد',103,10300014),(10300014002148,'سیس','سیس',103,10300014),(10300014002149,'وایقان','وایقان',103,10300014),(10300014002544,'کوزه کنان','کوزه-کنان',103,10300014),(10300014002946,'داریان','داریان',103,10300014),(10300014002947,'علیشاه','علیشاه',103,10300014),(10300015001199,'کلیبر','کلیبر',103,10300015),(10300015002545,'آبش احمد','آبش-احمد',103,10300015),(10300016001217,'هریس','هریس',103,10300016),(10300016002146,'خواجه','خواجه',103,10300016),(10300016002147,'زرنق','زرنق',103,10300016),(10300016002230,'بخشایش','بخشایش',103,10300016),(10300016002333,'کلوانق','کلوانق',103,10300016),(10300016002935,'اربطان','اربطان',103,10300016),(10300019001225,'جلفا','جلفا',103,10300019),(10300019001226,'هادیشهر','هادیشهر',103,10300019),(10300019002497,'سیه رود','سیه-رود',103,10300019),(10300020001224,'ملکان','ملکان',103,10300020),(10300020002844,'مبارک شهر','مبارک-شهر',103,10300020),(10300021001201,'آذرشهر','آذرشهر',103,10300021),(10300021001202,'گوگان','گوگان',103,10300021),(10300021001203,'ممقان','ممقان',103,10300021),(10300021002763,'تیمورلو','تیمورلو',103,10300021),(10300022001204,'اسکو','اسکو',103,10300022),(10300022001205,'ایلخچی','ایلخچی',103,10300022),(10300022002033,'سهند','سهند',103,10300022),(10300023002145,'قره آغاج','قره-آغاج',103,10300023),(10300024002142,'ورزقان','ورزقان',103,10300024),(10300024002398,'خاروانا','خاروانا',103,10300024),(10300025001222,'عجب شیر','عجب-شیر',103,10300025),(10300025002843,'جوان قلعه','جوان-قلعه',103,10300025),(10300026002332,'خمارلو','خمارلو',103,10300026),(10300026002944,'عاشقلو','عاشقلو',103,10300026),(10300026002945,'لاریجان','لاریجان',103,10300026),(10300027002294,'هوراند','هوراند',103,10300027),(10300028002296,'لیلان','لیلان',103,10300028),(10400010001247,'بوکان','بوکان',104,10400010),(10400010002548,'سیمینه','سیمینه',104,10400010),(10400011001250,'شاهین دژ','شاهین-دژ',104,10400011),(10400011002407,'کشاورز','کشاورز',104,10400011),(10400011002591,'محمودآباد','محمودآباد',104,10400011),(10400012001249,'تکاب','تکاب',104,10400012),(10400012002926,'تخت سلیمان','تخت-سلیمان',104,10400012),(10400013001252,'اشنویه','اشنویه',104,10400013),(10400013002408,'نالوس','نالوس',104,10400013),(10400014001245,'سیه چشمه','سیه-چشمه',104,10400014),(10400014002549,'آواجیق','آواجیق',104,10400014),(10400015001243,'پلدشت','پلدشت',104,10400015),(10400015002733,'نازک علیا','نازک-علیا',104,10400015),(10400016001237,'قره ضیاءالدین','قره-ضیاءالدین',104,10400016),(10400016002879,'حاجیلار','حاجیلار',104,10400016),(10400017001244,'شوط','شوط',104,10400017),(10400017002736,'مرگنلر','مرگنلر',104,10400017),(10400017002899,'یولاگلدی','یولاگلدی',104,10400017),(10400018002232,'چهاربرج','چهاربرج',104,10400018),(10400019002592,'باروق','باروق',104,10400019),(10400020002406,'میرآباد','میرآباد',104,10400020),(10500010001257,'صحنه','صحنه',105,10500010),(10500010002414,'دینور','دینور',105,10500010),(10500011001259,'هرسین','هرسین',105,10500011),(10500011002233,'بیستون','بیستون',105,10500011),(10500012002185,'تازه آباد','تازه-آباد',105,10500012),(10500012002593,'ازگله','ازگله',105,10500012),(10500012002977,'میرآباد','میرآباد',105,10500012),(10500013001255,'کرند','کرند',105,10500013),(10500013002409,'گهواره','گهواره',105,10500013),(10500013002760,'ریجاب','ریجاب',105,10500013),(10500014002034,'شاهو','شاهو',105,10500014),(10500014002102,'روانسر','روانسر',105,10500014),(10600010001004,'باوج','باوج',106,10600010),(10600010001287,'رامهرمز','رامهرمز',106,10600010),(10600010002921,'سلطان آباد','سلطان-آباد',106,10600010),(10600010002929,'رود زرد ماشین','رود-زرد-ماشین',106,10600010),(10600011001288,'شادگان','شادگان',106,10600011),(10600011002008,'دارخوین','دارخوین',106,10600011),(10600011002794,'خنافره','خنافره',106,10600011),(10600012001290,'شوشتر','شوشتر',106,10600012),(10600012002036,'شرافت','شرافت',106,10600012),(10600012002714,'گوریه','گوریه',106,10600012),(10600012002792,'سرداران','سرداران',106,10600012),(10600012002968,'عرب حسن','عرب-حسن',106,10600012),(10600013001291,'مسجدسلیمان','مسجدسلیمان',106,10600013),(10600013002793,'گلگیر','گلگیر',106,10600013),(10600013002930,'عنبر','عنبر',106,10600013),(10600014001281,'شوش','شوش',106,10600014),(10600014002649,'حر','حر',106,10600014),(10600014002718,'فتح المبین','فتح-المبین',106,10600014),(10600015001273,'باغ ملک','باغ-ملک',106,10600015),(10600015002551,'قلعه تل','قلعه-تل',106,10600015),(10600015002720,'میداود','میداود',106,10600015),(10600016002111,'امیدیه','امیدیه',106,10600016),(10600016002420,'جایزان','جایزان',106,10600016),(10600016002969,'میانکوه','میانکوه',106,10600016),(10600017001061,'تراز','تراز',106,10600017),(10600017002104,'لالی','لالی',106,10600017),(10600018001277,'هندیجان','هندیجان',106,10600018),(10600018002650,'زهره','زهره',106,10600018),(10600019001286,'رامشیر','رامشیر',106,10600019),(10600019002719,'مشراگه','مشراگه',106,10600019),(10600020001289,'گتوند','گتوند',106,10600020),(10600020002007,'ترکالکی','ترکالکی',106,10600020),(10600020002009,'صالح شهر','صالح-شهر',106,10600020),(10600020002715,'سماله','سماله',106,10600020),(10600020002716,'جنت مکان','جنت-مکان',106,10600020),(10600021002550,'قلعه خواجه','قلعه-خواجه',106,10600021),(10600021002799,'آبژدان','آبژدان',106,10600021),(10600021002928,'زاووت','زاووت',106,10600021),(10600022001292,'هفتگل','هفتگل',106,10600022),(10600023001285,'هویزه','هویزه',106,10600023),(10600023002313,'رفیع','رفیع',106,10600023),(10600024002106,'ملاثانی','ملاثانی',106,10600024),(10600024002309,'ویس','ویس',106,10600024),(10600024002310,'شیبان','شیبان',106,10600024),(10600025002105,'حمیدیه','حمیدیه',106,10600025),(10600026001278,'آغاجاری','آغاجاری',106,10600026),(10600026002920,'جولکی','جولکی',106,10600026),(10600027002790,'کوت عبداله','کوت-عبداله',106,10600027),(10600028002037,'شاوور','شاوور',106,10600028),(10600028002314,'الوان','الوان',106,10600028),(10600029002415,'دهدز','دهدز',106,10600029),(10600030002596,'صیدون','صیدون',106,10600030),(10700010001315,'کازرون','کازرون',107,10700010),(10700010002109,'خشت','خشت',107,10700010),(10700010002181,'کنارتخته','کنارتخته',107,10700010),(10700010002423,'بالاده','بالاده',107,10700010),(10700011001320,'لار','لار',107,10700011),(10700011002013,'لطیفی','لطیفی',107,10700011),(10700011002182,'بنارویه','بنارویه',107,10700011),(10700011002219,'بیرم','بیرم',107,10700011),(10700011002553,'خور','خور',107,10700011),(10700011002709,'عماد شهر','عماد-شهر',107,10700011),(10700011002952,'دهکویه','دهکویه',107,10700011),(10700012001044,'فتح آباد','فتح-آباد',107,10700012),(10700012001324,'مرودشت','مرودشت',107,10700012),(10700012002080,'رامجرد','رامجرد',107,10700012),(10700012002336,'سیدان','سیدان',107,10700012),(10700012002555,'کامفیروز','کامفیروز',107,10700012),(10700012002776,'خانیمن','خانیمن',107,10700012),(10700012002951,'فاروق','فاروق',107,10700012),(10700012002961,'زنگی آباد','زنگی-آباد',107,10700012),(10700013001325,'نورآباد','نورآباد',107,10700013),(10700013002712,'خومه زار','خومه-زار',107,10700013),(10700013002781,'بابامنیر','بابامنیر',107,10700013),(10700014001326,'نی ریز','نی-ریز',107,10700014),(10700014002058,'قطرویه','قطرویه',107,10700014),(10700014002556,'مشکان','مشکان',107,10700014),(10700015001015,'خیرگو','خیرگو',107,10700015),(10700015001318,'لامرد','لامرد',107,10700015),(10700015002212,'اشکنان','اشکنان',107,10700015),(10700015002235,'اهل','اهل',107,10700015),(10700015002236,'علامرودشت','علامرودشت',107,10700015),(10700015002906,'چاه ورز','چاه-ورز',107,10700015),(10700016001295,'بوانات','بوانات',107,10700016),(10700016002780,'مزایجان','مزایجان',107,10700016),(10700017001321,'ارسنجان','ارسنجان',107,10700017),(10700018001294,'صفاشهر','صفاشهر',107,10700018),(10700018001323,'قادراباد','قادراباد',107,10700018),(10700019001302,'حاجی آباد','حاجی-آباد',107,10700019),(10700019002558,'شهرپیر','شهرپیر',107,10700019),(10700019002651,'دبیران','دبیران',107,10700019),(10700020001312,'قیر','قیر',107,10700020),(10700020002011,'امام شهر','امام-شهر',107,10700020),(10700020002012,'مبارک آباددیز','مبارک-آباددیز',107,10700020),(10700020002422,'کارزین فتح آباد','کارزین-فتح-آباد',107,10700020),(10700020002653,'افزر','افزر',107,10700020),(10700021002211,'مهر','مهر',107,10700021),(10700021002425,'گله دار','گله-دار',107,10700021),(10700021002559,'وراوی','وراوی',107,10700021),(10700021002708,'اسیر','اسیر',107,10700021),(10700021002820,'خوزی','خوزی',107,10700021),(10700021002966,'فال','فال',107,10700021),(10700022001311,'فراشبند','فراشبند',107,10700022),(10700022002059,'دهرم','دهرم',107,10700022),(10700022002060,'نوجین','نوجین',107,10700022),(10700023001322,'سعادت شهر','سعادت-شهر',107,10700023),(10700023002783,'مادرسلیمان','مادرسلیمان',107,10700023),(10700024001317,'خنج','خنج',107,10700024),(10700024002907,'محمله','محمله',107,10700024),(10700025001306,'سروستان','سروستان',107,10700025),(10700025002710,'کوهنجان','کوهنجان',107,10700025),(10700026002337,'مصیری','مصیری',107,10700026),(10700026002777,'کوپن','کوپن',107,10700026),(10700027001319,'گراش','گراش',107,10700027),(10700027002903,'ارد','ارد',107,10700027),(10700028001042,'اکبرآباد','اکبرآباد',107,10700028),(10700028001043,'مظفری','مظفری',107,10700028),(10700028001307,'کوار','کوار',107,10700028),(10700028002904,'طسوج','طسوج',107,10700028),(10700029001308,'خرامه','خرامه',107,10700029),(10700029002782,'سلطان شهر','سلطان-شهر',107,10700029),(10700029002962,'معزآبادجابری','معزآبادجابری',107,10700029),(10700029002963,'خیراباد','خیراباد',107,10700029),(10700030001045,'رحمت آباد','رحمت-آباد',107,10700030),(10700030001305,'زرقان','زرقان',107,10700030),(10700030002421,'لپویی','لپویی',107,10700030),(10700031002503,'بیضا','بیضا',107,10700031),(10700032001057,'توجردی','توجردی',107,10700032),(10700032001072,'باغ صفا','باغ-صفا',107,10700032),(10700032002095,'حسامی','حسامی',107,10700032),(10700032002557,'کره ای','کره-ای',107,10700032),(10700033002108,'قایمیه','قایمیه',107,10700033),(10700033002424,'نودان','نودان',107,10700033),(10700034001300,'خاوران','خاوران',107,10700034),(10700034002234,'باب انار','باب-انار',107,10700034),(10700035001066,'خواجه جمالی','خواجه-جمالی',107,10700035),(10700035001077,'تم شولی','تم-شولی',107,10700035),(10700035002199,'آباده طشک','آباده-طشک',107,10700035),(10700036001046,'کوره','کوره',107,10700036),(10700036001316,'اوز','اوز',107,10700036),(10700037002183,'جویم','جویم',107,10700037),(10800010001348,'بردسیر','بردسیر',108,10800010),(10800010002065,'لاله زار','لاله-زار',108,10800010),(10800010002241,'گلزار','گلزار',108,10800010),(10800010002326,'نگار','نگار',108,10800010),(10800010002824,'دشتکار','دشتکار',108,10800010),(10800011001340,'راور','راور',108,10800011),(10800011002599,'هجدک','هجدک',108,10800011),(10800012001333,'عنبرآباد','عنبرآباد',108,10800012),(10800012002602,'مردهک','مردهک',108,10800012),(10800012002721,'دوساری','دوساری',108,10800012),(10800013002240,'منوجان','منوجان',108,10800013),(10800013002604,'نودژ','نودژ',108,10800013),(10800014001336,'کوهبنان','کوهبنان',108,10800014),(10800014002110,'کیانشهر','کیانشهر',108,10800014),(10800015002325,'رودبار','رودبار',108,10800015),(10800016002176,'قلعه گنج','قلعه-گنج',108,10800016),(10800016002908,'چاه دادخدا','چاه-دادخدا',108,10800016),(10800016002957,'رمشک','رمشک',108,10800016),(10800017001013,'رحمت آباد','رحمت-آباد',108,10800017),(10800017001014,'عباس آباد سردار','عباس-آباد-سردار',108,10800017),(10800017002316,'محمدآباد','محمدآباد',108,10800017),(10800018001327,'رابر','رابر',108,10800018),(10800018002771,'هنزا','هنزا',108,10800018),(10800019001010,'دهنو اسلام آباد','دهنو-اسلام-آباد',108,10800019),(10800019002317,'فهرج','فهرج',108,10800019),(10800020001334,'انار','انار',108,10800020),(10800020002318,'امین شهر','امین-شهر',108,10800020),(10800021002338,'نرماشیر','نرماشیر',108,10800021),(10800021002598,'نظام شهر','نظام-شهر',108,10800021),(10800022001011,'پاسفید','پاسفید',108,10800022),(10800022002429,'فاریاب','فاریاب',108,10800022),(10800023001012,'علی  آباد','علی-آباد',108,10800023),(10800023002315,'ارزوییه','ارزوییه',108,10800023),(10800024002822,'گنبکی','گنبکی',108,10800024),(10800025002821,'زهکلوت','زهکلوت',108,10800025),(10900013001020,'آلماجق','آلماجق',109,10900013),(10900013001022,'شهر کهنه','شهر-کهنه',109,10900013),(10900013001041,'مزرج','مزرج',109,10900013),(10900013001376,'باجگیران','باجگیران',109,10900013),(10900013001378,'قوچان','قوچان',109,10900013),(10900014001055,'فرگ قلعه','فرگ-قلعه',109,10900014),(10900014001381,'کاشمر','کاشمر',109,10900014),(10900015001383,'بیدخت','بیدخت',109,10900015),(10900015001384,'کاخک','کاخک',109,10900015),(10900015001385,'گناباد','گناباد',109,10900015),(10900015002987,'روشناوند','روشناوند',109,10900015),(10900016001392,'مشهد','مشهد',109,10900016),(10900016002436,'رضویه','رضویه',109,10900016),(10900016002437,'ملک آباد','ملک-آباد',109,10900016),(10900016002526,'مشهد ثامن','مشهد-ثامن',109,10900016),(10900017001394,'نیشابور','نیشابور',109,10900017),(10900017002041,'بار','بار',109,10900017),(10900017002564,'چکنه','چکنه',109,10900017),(10900018001386,'چناران','چناران',109,10900018),(10900018002998,'رادکان','رادکان',109,10900018),(10900018002999,'سیدآباد','سیدآباد',109,10900018),(10900019001021,'سده','سده',109,10900019),(10900019001358,'خواف','خواف',109,10900019),(10900019002213,'سنگان','سنگان',109,10900019),(10900019002439,'قاسم آباد','قاسم-آباد',109,10900019),(10900019002565,'نشتیفان','نشتیفان',109,10900019),(10900019002605,'سلامی','سلامی',109,10900019),(10900020001387,'سرخس','سرخس',109,10900020),(10900020002607,'مزدآوند','مزدآوند',109,10900020),(10900022001390,'فریمان','فریمان',109,10900022),(10900022002442,'قلندر آباد','قلندر-آباد',109,10900022),(10900022002566,'فرهادگرد','فرهادگرد',109,10900022),(10900022002608,'سفید سنگ','سفید-سنگ',109,10900022),(10900023001379,'بردسکن','بردسکن',109,10900023),(10900023002090,'شهرآباد','شهرآباد',109,10900023),(10900023002440,'انابد','انابد',109,10900023),(10900027001359,'رشتخوار','رشتخوار',109,10900027),(10900027002606,'جنگل','جنگل',109,10900027),(10900028001019,'چنار','چنار',109,10900028),(10900028001023,'حسن آباد','حسن-آباد',109,10900028),(10900028001391,'کلات','کلات',109,10900028),(10900028002609,'شهر زو','شهر-زو',109,10900028),(10900029001380,'خلیل آباد','خلیل-آباد',109,10900029),(10900029002656,'کندر','کندر',109,10900029),(10900030001360,'فیض آباد','فیض-آباد',109,10900030),(10900030002069,'شادمهر','شادمهر',109,10900030),(10900031001382,'بجستان','بجستان',109,10900031),(10900031002610,'یونسی','یونسی',109,10900031),(10900032001388,'شاندیز','شاندیز',109,10900032),(10900032001389,'طرقبه','طرقبه',109,10900032),(10900033001018,'گرماب','گرماب',109,10900033),(10900033002066,'همت آباد','همت-آباد',109,10900033),(10900033002220,'فیروزه','فیروزه',109,10900033),(10900034001596,'جغتای','جغتای',109,10900034),(10900034002901,'ریواده','ریواده',109,10900034),(10900035002135,'دولت آباد','دولت-آباد',109,10900035),(10900035002950,'چخماق','چخماق',109,10900035),(10900036002184,'نقاب','نقاب',109,10900036),(10900036002889,'حکم آباد','حکم-آباد',109,10900036),(10900037002134,'باخرز','باخرز',109,10900037),(10900037002970,'قلعه نو علیا','قلعه-نو-علیا',109,10900037),(10900038002343,'سلطان آباد','سلطان-آباد',109,10900038),(10900038002902,'مشکان','مشکان',109,10900038),(10900038002986,'نوده انقلاب','نوده-انقلاب',109,10900038),(10900039002244,'داورزن','داورزن',109,10900039),(10900039002964,'ریوند','ریوند',109,10900039),(10900040001362,'صالح آباد','صالح-آباد',109,10900040),(10900040002980,'جنت آباد','جنت-آباد',109,10900040),(10900041002348,'ریوش','ریوش',109,10900041),(10900042001393,'درود','درود',109,10900042),(10900042002246,'قدمگاه','قدمگاه',109,10900042),(10900042002349,'خرو','خرو',109,10900042),(10900042002965,'اسحق آباد','اسحق-آباد',109,10900042),(10900043001054,'شامکان','شامکان',109,10900043),(10900043002344,'ششتمد','ششتمد',109,10900043),(10900044002068,'گلمکان','گلمکان',109,10900044),(10900044002854,'گلبهار','گلبهار',109,10900044),(10900045002438,'عشق آباد','عشق-آباد',109,10900045),(11000010001424,'قمصر','قمصر',110,11000010),(11000010001425,'کاشان','کاشان',110,11000010),(11000010002302,'نیاسر','نیاسر',110,11000010),(11000010002447,'جوشقان قالی','جوشقان-قالی',110,11000010),(11000010002542,'برزک','برزک',110,11000010),(11000010002657,'مشکات','مشکات',110,11000010),(11000010002831,'کامو و چوگان','کامو-و-چوگان',110,11000010),(11000011001426,'گلپایگان','گلپایگان',110,11000011),(11000011001427,'گوگد','گوگد',110,11000011),(11000011002511,'گلشهر','گلشهر',110,11000011),(11000012001429,'باغ بهادران','باغ-بهادران',110,11000012),(11000012001430,'چمگردان','چمگردان',110,11000012),(11000012001431,'زرین شهر','زرین-شهر',110,11000012),(11000012001432,'سده لنجان','سده-لنجان',110,11000012),(11000012002112,'ورنامخواست','ورنامخواست',110,11000012),(11000012002121,'چرمهین','چرمهین',110,11000012),(11000012002128,'فولادشهر','فولادشهر',110,11000012),(11000012002504,'زاینده رود','زاینده-رود',110,11000012),(11000012002788,'باغشاد','باغشاد',110,11000012),(11000013001433,'انارک','انارک',110,11000013),(11000013001435,'نایین','نایین',110,11000013),(11000013002078,'بافران','بافران',110,11000013),(11000014001437,'دهق','دهق',110,11000014),(11000014001438,'علویجه','علویجه',110,11000014),(11000014001440,'نجف آباد','نجف-آباد',110,11000014),(11000014002045,'جوزدان','جوزدان',110,11000014),(11000014002122,'گلدشت','گلدشت',110,11000014),(11000014002570,'کهریزسنگ','کهریزسنگ',110,11000014),(11000015001441,'بادرود','بادرود',110,11000015),(11000015001442,'نطنز','نطنز',110,11000015),(11000015002612,'خالدآباد','خالدآباد',110,11000015),(11000015002754,'طرق رود','طرق-رود',110,11000015),(11000016001401,'شاهین شهر','شاهین-شهر',110,11000016),(11000016001402,'گزبرخوار','گزبرخوار',110,11000016),(11000016001408,'میمه','میمه',110,11000016),(11000016001409,'وزوان','وزوان',110,11000016),(11000016001597,'گرگاب','گرگاب',110,11000016),(11000016001598,'لای بید','لای-بید',110,11000016),(11000017001428,'مبارکه','مبارکه',110,11000017),(11000017001599,'مجلسی','مجلسی',110,11000017),(11000017002189,'دیزیچه','دیزیچه',110,11000017),(11000017002204,'طالخونچه','طالخونچه',110,11000017),(11000017002448,'کرکوند','کرکوند',110,11000017),(11000017002572,'زیباشهر','زیباشهر',110,11000017),(11000017002937,'ده سرخ','ده-سرخ',110,11000017),(11000018001423,'آران و بیدگل','آران-و-بیدگل',110,11000018),(11000018002279,'ابوزیدآباد','ابوزیدآباد',110,11000018),(11000018002505,'نوش آباد','نوش-آباد',110,11000018),(11000018002543,'سفید شهر','سفید-شهر',110,11000018),(11000019001436,'تیران','تیران',110,11000019),(11000019002449,'عسگران','عسگران',110,11000019),(11000019002573,'رضوانشهر','رضوانشهر',110,11000019),(11000020001415,'چادگان','چادگان',110,11000020),(11000020002352,'رزوه','رزوه',110,11000020),(11000021001421,'دهاقان','دهاقان',110,11000021),(11000021002093,'گلشن','گلشن',110,11000021),(11000022001397,'حبیب آباد','حبیب-آباد',110,11000022),(11000022001398,'خورزوق','خورزوق',110,11000022),(11000022001399,'دستگرد','دستگرد',110,11000022),(11000022001400,'دولت آباد','دولت-آباد',110,11000022),(11000022002094,'شاپورآباد','شاپورآباد',110,11000022),(11000022002571,'کمشچه','کمشچه',110,11000022),(11000022002756,'سین','سین',110,11000022),(11000023001434,'خور','خور',110,11000023),(11000023002077,'فرخی','فرخی',110,11000023),(11000023002203,'جندق','جندق',110,11000023),(11000024001416,'بویین و میاندشت','بویین-و-میاندشت',110,11000024),(11000024002250,'افوس','افوس',110,11000024),(11000025001403,'کوهپایه','کوهپایه',110,11000025),(11000025002350,'سجزی','سجزی',110,11000025),(11000025002513,'تودشک','تودشک',110,11000025),(11000026002186,'نیک آباد','نیک-آباد',110,11000026),(11000026002187,'حسن اباد','حسن-اباد',110,11000026),(11000026002247,'محمدآباد','محمدآباد',110,11000026),(11000026002512,'نصرآباد','نصرآباد',110,11000026),(11000027001404,'ورزنه','ورزنه',110,11000027),(11000028001405,'هرند','هرند',110,11000028),(11000028002248,'اژیه','اژیه',110,11000028),(11100010002173,'زهک','زهک',111,11100010),(11100010002978,'جزینک','جزینک',111,11100010),(11100011002514,'دوست محمد','دوست-محمد',111,11100011),(11100011002916,'قرقری','قرقری',111,11100011),(11100012002451,'گلمورتی','گلمورتی',111,11100012),(11100012002914,'چگرد','چگرد',111,11100012),(11100013002355,'مهرستان','مهرستان',111,11100013),(11100013002893,'آشار','آشار',111,11100013),(11100014001027,'سیب','سیب',111,11100014),(11100014002174,'سوران','سوران',111,11100014),(11100014002613,'هیدوچ','هیدوچ',111,11100014),(11100015002353,'ادیمی','ادیمی',111,11100015),(11100016002046,'علی اکبر','علی-اکبر',111,11100016),(11100016002354,'محمدآباد','محمدآباد',111,11100016),(11100017001078,'لادیز','لادیز',111,11100017),(11100017001452,'میرجاوه','میرجاوه',111,11100017),(11100017002912,'ریگ ملک','ریگ-ملک',111,11100017),(11100018001445,'قصرقند','قصرقند',111,11100018),(11100018002892,'ساربوک','ساربوک',111,11100018),(11100019002175,'فنوج','فنوج',111,11100019),(11100019002913,'گتیج','گتیج',111,11100019),(11100020001028,'قاسم آباد','قاسم-آباد',111,11100020),(11100020002049,'محمدان','محمدان',111,11100020),(11100020002172,'بمپور','بمپور',111,11100020),(11100021002452,'نوک آباد','نوک-آباد',111,11100021),(11100022001029,'بریس','بریس',111,11100022),(11100022001444,'نگور','نگور',111,11100022),(11100023002499,'سرباز','سرباز',111,11100023),(11100024002191,'جالق','جالق',111,11100024),(11100025002506,'اسپکه','اسپکه',111,11100025),(11100026002739,'زرآباد','زرآباد',111,11100026),(11200010001460,'دهگلان','دهگلان',112,11200010),(11200010002042,'بلبان آباد','بلبان-آباد',112,11200010),(11300010001064,'شاهنجرین','شاهنجرین',113,11300010),(11300010001470,'قروه درگزین','قروه-درگزین',113,11300010),(11300010002870,'کرفس','کرفس',113,11300010),(11400010002473,'آلونی','آلونی',114,11400010),(11400011002472,'مال خلیفه','مال-خلیفه',114,11400011),(11400012001483,'فرخ شهر','فرخ-شهر',114,11400012),(11500010002255,'سراب دوره','سراب-دوره',115,11500010),(11500010002362,'ویسیان','ویسیان',115,11500010),(11500010002973,'چم پلک','چم-پلک',115,11500010),(11500011002256,'چقابل','چقابل',115,11500011),(11500011002972,'سوری','سوری',115,11500011),(11500012002366,'معمولان','معمولان',115,11500012),(11600010001502,'بدره','بدره',116,11600010),(11600010002909,'چشمه شیرین','چشمه-شیرین',116,11600010),(11600011002574,'توحید','توحید',116,11600011),(11600012001499,'چوار','چوار',116,11600012),(11800010002621,'عسلویه','عسلویه',118,11800010),(11800010002622,'نخل تقی','نخل-تقی',118,11800010),(11800010002878,'چاه مبارک','چاه-مبارک',118,11800010),(11800010002932,'بیدخون','بیدخون',118,11800010),(11900010001527,'سلطانیه','سلطانیه',119,11900010),(12100011001553,'بهاباد','بهاباد',121,12100011),(12100012001557,'مروست','مروست',121,12100012),(12100013001561,'زارچ','زارچ',121,12100013),(12200010001564,'خمیر','خمیر',122,12200010),(12200010002005,'رویدر','رویدر',122,12200010),(12200010002939,'پل','پل',122,12200010),(12200011001569,'پارسیان','پارسیان',122,12200011),(12200011002004,'کوشکنار','کوشکنار',122,12200011),(12200011002751,'دشتی','دشتی',122,12200011),(12200012002267,'سیریک','سیریک',122,12200012),(12200012002724,'گروک','گروک',122,12200012),(12200012002815,'کوهستک','کوهستک',122,12200012),(12200013002061,'سردشت','سردشت',122,12200013),(12200013002062,'گوهران','گوهران',122,12200013),(12300010001581,'اسلامشهر','اسلامشهر',123,12300010),(12300010002489,'چهاردانگه','چهاردانگه',123,12300010),(12300010002770,'احمد آباد مستوفی','احمد-آباد-مستوفی',123,12300010),(12300012001585,'رباطکریم','رباطکریم',123,12300012),(12300012002666,'نصیرشهر','نصیرشهر',123,12300012),(12300012002826,'پرند','پرند',123,12300012),(12300013002120,'پاکدشت','پاکدشت',123,12300013),(12300013002490,'شریف آباد','شریف-آباد',123,12300013),(12300013002705,'فرون اباد','فرون-اباد',123,12300013),(12300014001577,'فیروزکوه','فیروزکوه',123,12300014),(12300014002634,'ارجمند','ارجمند',123,12300014),(12300016002116,'قدس','قدس',123,12300016),(12300017002217,'ملارد','ملارد',123,12300017),(12300017002373,'صفادشت','صفادشت',123,12300017),(12300018001592,'پیشوا','پیشوا',123,12300018),(12300018002989,'جلیل اباد','جلیل-اباد',123,12300018),(12300019002208,'نسیم شهر','نسیم-شهر',123,12300019),(12300019002278,'گلستان','گلستان',123,12300019),(12300019002665,'صالحیه','صالحیه',123,12300019),(12300020001040,'سعیداباد','سعیداباد',123,12300020),(12300020001051,'خسرواباد','خسرواباد',123,12300020),(12300020002268,'بومهن','بومهن',123,12300020),(12300020002628,'پردیس','پردیس',123,12300020),(12300021001593,'قرچک','قرچک',123,12300021),(12400010001196,'سرعین','سرعین',124,12400010),(12400010002881,'اردیموسی','اردیموسی',124,12400010),(12400011001074,'بران علیا','بران-علیا',124,12400011),(12400011002166,'اصلاندوز','اصلاندوز',124,12400011),(12400012001065,'زیوه','زیوه',124,12400012),(12400012002492,'تازه کندانگوت','تازه-کندانگوت',124,12400012),(12700010001182,'آزادشهر','آزادشهر',127,12700010),(12700010002581,'نوده خاندوز','نوده-خاندوز',127,12700010),(12700010002640,'نگین شهر','نگین-شهر',127,12700010),(12700011001183,'خان ببین','خان-ببین',127,12700011),(12700011001184,'رامیان','رامیان',127,12700011),(12700011002195,'دلند','دلند',127,12700011),(12700011002765,'تاتار علیا','تاتار-علیا',127,12700011),(12700012002498,'مراوه','مراوه',127,12700012),(12700012002885,'گلیداغ','گلیداغ',127,12700012),(12700013001155,'گمیش تپه','گمیش-تپه',127,12700013),(12700013002580,'سیمین شهر','سیمین-شهر',127,12700013),(12700014001070,'ینقاق','ینقاق',127,12700014),(12700014001187,'گالیکش','گالیکش',127,12700014),(12700014002884,'صادق آباد','صادق-آباد',127,12700014),(12800010002568,'پیش قلعه','پیش-قلعه',128,12800010),(12900010001003,'ماژان','ماژان',129,12900010),(12900010002242,'خوسف','خوسف',129,12900010),(12900011001369,'طبس','طبس',129,12900011),(12900011002345,'عشق آباد','عشق-آباد',129,12900011),(12900011002624,'دیهوک','دیهوک',129,12900011);
/*!40000 ALTER TABLE `cities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `companies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `company_name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_full_name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_national_code` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `registration_no` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `economic_code` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province_id` int unsigned DEFAULT NULL,
  `city_id` bigint unsigned DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `verification_status` tinyint unsigned NOT NULL DEFAULT '0',
  `verified_at` datetime(3) DEFAULT NULL,
  `verified_by_user_id` bigint unsigned DEFAULT NULL,
  `reject_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  `deleted_at` datetime(3) DEFAULT NULL,
  `registration_date` date DEFAULT NULL COMMENT 'تاریخ ثبت',
  `postal_code` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'کد پستی',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_companies_user` (`user_id`),
  KEY `ix_companies_verify` (`verification_status`,`created_at`),
  KEY `ix_companies_city` (`city_id`),
  KEY `ix_companies_owner_national` (`owner_national_code`),
  KEY `fk_companies_verified_by` (`verified_by_user_id`),
  KEY `fk_companies_province` (`province_id`),
  CONSTRAINT `fk_companies_city` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`),
  CONSTRAINT `fk_companies_province` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`),
  CONSTRAINT `fk_companies_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_companies_verified_by` FOREIGN KEY (`verified_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `companies`
--

LOCK TABLES `companies` WRITE;
/*!40000 ALTER TABLE `companies` DISABLE KEYS */;
INSERT INTO `companies` VALUES (1,54,'آموت‌بار','آموت بار','1551956039','645651651','654165165131',123,1230001001576,'شسبدمسشنبد',0,NULL,NULL,'','2026-05-18 20:55:24.234','2026-08-01 19:34:48.944','2026-08-01 19:34:48.944','1405-02-28','1471934411'),(2,56,'شایان نماینده','شایان نماینده','0312449348',NULL,NULL,NULL,NULL,NULL,1,'2026-08-01 19:04:10.260',NULL,NULL,'2026-08-01 19:04:10.260','2026-08-01 19:34:48.929','2026-08-01 19:34:48.929',NULL,NULL),(7,59,'شایان نماینده','شایان نماینده','0312449348',NULL,NULL,123,1230001001576,NULL,1,'2026-08-01 20:54:08.301',NULL,NULL,'2026-08-01 20:54:08.301','2026-08-01 20:54:39.676',NULL,NULL,NULL),(8,64,'شایان نماینده','شایان نماینده','0312449348',NULL,NULL,NULL,NULL,NULL,1,'2026-08-06 15:59:36.582',NULL,NULL,'2026-08-06 15:59:36.582','2026-08-22 11:04:19.310','2026-08-22 11:04:19.310',NULL,NULL),(9,66,'محمد درویشی نیا','محمد درویشی نیا','4190260975',NULL,NULL,NULL,NULL,NULL,1,'2026-08-06 16:07:40.306',NULL,NULL,'2026-08-06 16:07:40.306','2026-08-06 16:07:40.306',NULL,NULL,NULL),(10,68,'علیرضا درویشی نیا','علیرضا درویشی نیا','4190718335',NULL,NULL,NULL,NULL,NULL,1,'2026-08-17 12:10:08.386',NULL,NULL,'2026-08-17 12:10:08.386','2026-08-17 12:10:08.386',NULL,NULL,NULL);
/*!40000 ALTER TABLE `companies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_documents`
--

DROP TABLE IF EXISTS `company_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `company_documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `doc_type` tinyint unsigned NOT NULL,
  `file_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint unsigned NOT NULL DEFAULT '0',
  `reviewed_by_user_id` bigint unsigned DEFAULT NULL,
  `reviewed_at` datetime(3) DEFAULT NULL,
  `reject_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  KEY `ix_company_docs_company` (`company_id`,`doc_type`),
  KEY `ix_company_docs_status` (`status`,`created_at`),
  KEY `fk_company_docs_reviewer` (`reviewed_by_user_id`),
  CONSTRAINT `fk_company_docs_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_company_docs_reviewer` FOREIGN KEY (`reviewed_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_documents`
--

LOCK TABLES `company_documents` WRITE;
/*!40000 ALTER TABLE `company_documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `company_documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_verification_events`
--

DROP TABLE IF EXISTS `company_verification_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `company_verification_events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `old_status` tinyint unsigned DEFAULT NULL,
  `new_status` tinyint unsigned NOT NULL,
  `actor_user_id` bigint unsigned NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  KEY `ix_company_verif_events_company_time` (`company_id`,`created_at`),
  KEY `fk_company_verif_events_actor` (`actor_user_id`),
  CONSTRAINT `fk_company_verif_events_actor` FOREIGN KEY (`actor_user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_company_verif_events_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_verification_events`
--

LOCK TABLES `company_verification_events` WRITE;
/*!40000 ALTER TABLE `company_verification_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `company_verification_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conversations`
--

DROP TABLE IF EXISTS `conversations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `conversations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `load_id` bigint unsigned NOT NULL,
  `company_id` bigint unsigned NOT NULL,
  `driver_id` bigint unsigned NOT NULL,
  `status` tinyint unsigned NOT NULL DEFAULT '1',
  `last_message_at` datetime(3) DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_conversations_load_driver` (`load_id`,`driver_id`),
  KEY `ix_conversations_company_last` (`company_id`,`last_message_at`),
  KEY `ix_conversations_driver_last` (`driver_id`,`last_message_at`),
  CONSTRAINT `fk_conversations_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_conversations_driver` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_conversations_load` FOREIGN KEY (`load_id`) REFERENCES `loads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conversations`
--

LOCK TABLES `conversations` WRITE;
/*!40000 ALTER TABLE `conversations` DISABLE KEYS */;
/*!40000 ALTER TABLE `conversations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `driver_activity_logs`
--

DROP TABLE IF EXISTS `driver_activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `driver_activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `driver_id` bigint unsigned DEFAULT NULL,
  `event_key` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_title` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `screen_key` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `screen_title` varchar(160) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entity_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entity_id` bigint unsigned DEFAULT NULL,
  `load_id` bigint unsigned DEFAULT NULL,
  `company_id` bigint unsigned DEFAULT NULL,
  `ticket_id` bigint unsigned DEFAULT NULL,
  `client_event_id` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_platform` tinyint unsigned DEFAULT NULL COMMENT '1=android,2=ios,3=web',
  `client_version_code` int unsigned DEFAULT NULL,
  `app_version_name` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_id` varchar(96) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varbinary(16) DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `occurred_at` datetime(3) DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  KEY `ix_driver_activity_user_time` (`user_id`,`created_at`),
  KEY `ix_driver_activity_driver_time` (`driver_id`,`created_at`),
  KEY `ix_driver_activity_event_time` (`event_key`,`created_at`),
  KEY `ix_driver_activity_load_time` (`load_id`,`created_at`),
  KEY `ix_driver_activity_ticket_time` (`ticket_id`,`created_at`),
  KEY `ix_driver_activity_entity` (`entity_type`,`entity_id`,`created_at`),
  CONSTRAINT `driver_activity_logs_chk_1` CHECK (json_valid(`payload_json`))
) ENGINE=InnoDB AUTO_INCREMENT=196 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `driver_activity_logs`
--

LOCK TABLES `driver_activity_logs` WRITE;
/*!40000 ALTER TABLE `driver_activity_logs` DISABLE KEYS */;
INSERT INTO `driver_activity_logs` VALUES (1,53,15,'profile_view','مشاهده پروفایل','profile','پروفایل',NULL,NULL,NULL,NULL,NULL,'1783531643234381-52966b33',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:57:23.000','2026-07-08 20:57:25.775'),(2,53,15,'dashboard_view','مشاهده داشبورد','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1783531643233078-91e9ca9b',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:57:23.000','2026-07-08 20:57:25.784'),(3,53,15,'nearby_loads_view','مشاهده بارهای اطراف من','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1783531643233391-940611b0',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:57:23.000','2026-07-08 20:57:25.929'),(4,53,15,'app_open','باز شدن اپلیکیشن','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1783531643232684-1dc28908',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:57:23.000','2026-07-08 20:57:25.930'),(5,53,15,'tab_change','تغییر تب پایین اپ','call_history','تماس‌ها',NULL,NULL,NULL,NULL,NULL,'1783531646530555-3df12417',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"tab_index\":2,\"tab_title\":\"تماس‌ها\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:57:26.000','2026-07-08 20:57:28.952'),(6,53,15,'tab_change','تغییر تب پایین اپ','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1783531647353462-1eaa2e57',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"tab_index\":0,\"tab_title\":\"داشبورد\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:57:27.000','2026-07-08 20:57:29.737'),(7,53,15,'dashboard_view','مشاهده داشبورد','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1783531647354215-fdda166a',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:57:27.000','2026-07-08 20:57:29.741'),(8,53,15,'tab_change','تغییر تب پایین اپ','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1783531648115426-b54dab8f',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"tab_index\":1,\"tab_title\":\"بارهای اطراف من\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:57:28.000','2026-07-08 20:57:30.511'),(9,53,15,'nearby_loads_view','مشاهده بارهای اطراف من','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1783531648115876-40779d26',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:57:28.000','2026-07-08 20:57:30.512'),(10,53,15,'load_card_open','باز کردن کارت بار','loads_list','لیست بارها','load',9,9,NULL,NULL,'1783531651376964-8fbab0d0',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"origin\":\"تهران (تهران)\",\"destination\":\"مراغه (آذربایجان شرقی)\",\"cargo_title\":\"خوراکی\",\"price\":\"5,000,000\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:57:31.000','2026-07-08 20:57:33.767'),(11,53,15,'load_detail_view','مشاهده جزئیات بار','load_details','جزئیات بار','load',9,9,NULL,NULL,'1783531651388580-cce67c20',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:57:31.000','2026-07-08 20:57:33.902'),(12,53,15,'call_button_tap','کلیک روی برقراری تماس','load_details','جزئیات بار','load',9,9,1,NULL,'1783531652712694-6fe6cf6',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"public_code\":\"358597326497\",\"origin\":\"تهران (تهران)\",\"destination\":\"مراغه (آذربایجان شرقی)\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:57:32.000','2026-07-08 20:57:35.117'),(13,53,15,'dashboard_view','مشاهده داشبورد','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1783531657645243-aa8be410',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:57:37.000','2026-07-08 20:57:40.223'),(14,53,15,'tab_change','تغییر تب پایین اپ','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1783531657644760-197c82da',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"tab_index\":0,\"tab_title\":\"داشبورد\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:57:37.000','2026-07-08 20:57:40.223'),(15,53,15,'tab_change','تغییر تب پایین اپ','call_history','تماس‌ها',NULL,NULL,NULL,NULL,NULL,'1783531658518690-f8d403c7',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"tab_index\":2,\"tab_title\":\"تماس‌ها\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:57:38.000','2026-07-08 20:57:40.923'),(16,53,15,'load_detail_view','مشاهده جزئیات بار','load_details','جزئیات بار','load',9,9,NULL,NULL,'1783531660410884-a2c3fc59',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:57:40.000','2026-07-08 20:57:42.806'),(17,53,15,'load_detail_view','مشاهده جزئیات بار','load_details','جزئیات بار','load',9,9,NULL,NULL,'1783531663310613-99c7471b',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:57:43.000','2026-07-08 20:57:45.940'),(18,53,15,'tab_change','تغییر تب پایین اپ','profile','پروفایل',NULL,NULL,NULL,NULL,NULL,'1783531665539170-9491ef42',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"tab_index\":3,\"tab_title\":\"پروفایل\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:57:45.000','2026-07-08 20:57:47.953'),(19,53,15,'profile_view','مشاهده پروفایل','profile','پروفایل',NULL,NULL,NULL,NULL,NULL,'1783531665539855-e4053823',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:57:45.000','2026-07-08 20:57:47.962'),(20,53,15,'support_view','مشاهده پشتیبانی','support','پشتیبانی و پیام‌ها',NULL,NULL,NULL,NULL,NULL,'1783531667294985-e9d22282',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:57:47.000','2026-07-08 20:57:49.727'),(21,53,15,'support_view','مشاهده پشتیبانی','support','پشتیبانی و پیام‌ها',NULL,NULL,NULL,NULL,NULL,'1783531667303530-1c0862f5',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:57:47.000','2026-07-08 20:57:49.909'),(22,53,15,'ticket_view','مشاهده گفتگوی تیکت','support','پشتیبانی و پیام‌ها','ticket',1,NULL,NULL,1,'1783531668319290-d2d6680b',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"subject\":\"test\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:57:48.000','2026-07-08 20:57:50.738'),(23,53,15,'ticket_view','مشاهده گفتگوی تیکت','ticket_chat','گفتگوی تیکت','ticket',1,NULL,NULL,1,'1783531668343972-76e5004a',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:57:48.000','2026-07-08 20:57:50.893'),(24,53,15,'ticket_view','مشاهده گفتگوی تیکت','support','پشتیبانی و پیام‌ها','ticket',2,NULL,NULL,2,'1783531671651631-3eb5e205',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"subject\":\"test2\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:57:51.000','2026-07-08 20:57:54.075'),(25,53,15,'ticket_view','مشاهده گفتگوی تیکت','ticket_chat','گفتگوی تیکت','ticket',2,NULL,NULL,2,'1783531671670338-4bb9cf99',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:57:51.000','2026-07-08 20:57:54.201'),(26,53,15,'vehicle_info_view','مشاهده مشخصات خودرو','vehicle_info','مشخصات خودرو',NULL,NULL,NULL,NULL,NULL,'1783531680919019-335bfdfa',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:00.000','2026-07-08 20:58:03.347'),(27,53,15,'vehicle_info_view','مشاهده مشخصات خودرو','vehicle_info','مشخصات خودرو و بارگیر',NULL,NULL,NULL,NULL,NULL,'1783531680922468-ea8a1007',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:00.000','2026-07-08 20:58:03.484'),(28,53,15,'support_view','مشاهده پشتیبانی','support','پشتیبانی و پیام‌ها',NULL,NULL,NULL,NULL,NULL,'1783531682899248-17b12b71',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:02.000','2026-07-08 20:58:05.286'),(29,53,15,'support_view','مشاهده پشتیبانی','support','پشتیبانی و پیام‌ها',NULL,NULL,NULL,NULL,NULL,'1783531682915808-842c24e9',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:02.000','2026-07-08 20:58:05.334'),(30,53,15,'support_view','مشاهده پشتیبانی','support','پشتیبانی و پیام‌ها',NULL,NULL,NULL,NULL,NULL,'1783531685396274-5bea8161',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:05.000','2026-07-08 20:58:07.811'),(31,53,15,'support_view','مشاهده پشتیبانی','support','پشتیبانی و پیام‌ها',NULL,NULL,NULL,NULL,NULL,'1783531685381089-65f5b4b1',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:05.000','2026-07-08 20:58:07.813'),(32,53,15,'ticket_create','ثبت تیکت جدید','support','پشتیبانی و پیام‌ها','ticket',3,NULL,NULL,3,'1783531692415075-6d119f0e',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"subject\":\"سلام\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:12.000','2026-07-08 20:58:14.994'),(33,53,15,'ticket_view','مشاهده گفتگوی تیکت','ticket_chat','گفتگوی تیکت','ticket',3,NULL,NULL,3,'1783531692422448-34d213cd',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:12.000','2026-07-08 20:58:15.015'),(34,53,15,'ticket_view','مشاهده گفتگوی تیکت','support','پشتیبانی و پیام‌ها','ticket',3,NULL,NULL,3,'1783531698282103-3234a590',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"subject\":\"سلام\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:18.000','2026-07-08 20:58:20.730'),(35,53,15,'ticket_view','مشاهده گفتگوی تیکت','ticket_chat','گفتگوی تیکت','ticket',3,NULL,NULL,3,'1783531698303921-96848fef',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:18.000','2026-07-08 20:58:21.257'),(36,53,15,'ticket_view','مشاهده گفتگوی تیکت','support','پشتیبانی و پیام‌ها','ticket',3,NULL,NULL,3,'1783531699741282-191a127f',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"subject\":\"سلام\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:19.000','2026-07-08 20:58:22.237'),(37,53,15,'ticket_view','مشاهده گفتگوی تیکت','ticket_chat','گفتگوی تیکت','ticket',3,NULL,NULL,3,'1783531699784590-a0314ea',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:19.000','2026-07-08 20:58:22.275'),(38,53,15,'support_view','مشاهده پشتیبانی','support','پشتیبانی و پیام‌ها',NULL,NULL,NULL,NULL,NULL,'1783531702938607-d760fc97',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:22.000','2026-07-08 20:58:25.369'),(39,53,15,'support_view','مشاهده پشتیبانی','support','پشتیبانی و پیام‌ها',NULL,NULL,NULL,NULL,NULL,'1783531702939959-a640c743',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:22.000','2026-07-08 20:58:25.372'),(40,53,15,'tab_change','تغییر تب پایین اپ','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1783531705835000-9cf1e61',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"tab_index\":0,\"tab_title\":\"داشبورد\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:25.000','2026-07-08 20:58:28.324'),(41,53,15,'dashboard_view','مشاهده داشبورد','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1783531705836333-e2191955',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:25.000','2026-07-08 20:58:28.331'),(42,53,15,'nearby_loads_view','مشاهده بارهای اطراف من','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1783531706288714-80bbb70c',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:26.000','2026-07-08 20:58:28.765'),(43,53,15,'tab_change','تغییر تب پایین اپ','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1783531706288338-7433149f',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"tab_index\":1,\"tab_title\":\"بارهای اطراف من\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:26.000','2026-07-08 20:58:28.788'),(44,53,15,'tab_change','تغییر تب پایین اپ','call_history','تماس‌ها',NULL,NULL,NULL,NULL,NULL,'1783531707549135-4b227f84',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"tab_index\":2,\"tab_title\":\"تماس‌ها\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:27.000','2026-07-08 20:58:30.004'),(45,53,15,'nearby_loads_view','مشاهده بارهای اطراف من','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1783531707903852-9950660e',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:27.000','2026-07-08 20:58:30.474'),(46,53,15,'tab_change','تغییر تب پایین اپ','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1783531707903438-1723af31',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"tab_index\":1,\"tab_title\":\"بارهای اطراف من\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:27.000','2026-07-08 20:58:30.478'),(47,53,15,'tab_change','تغییر تب پایین اپ','call_history','تماس‌ها',NULL,NULL,NULL,NULL,NULL,'1783531708896425-8b8174a7',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"tab_index\":2,\"tab_title\":\"تماس‌ها\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:28.000','2026-07-08 20:58:31.407'),(48,53,15,'tab_change','تغییر تب پایین اپ','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1783531709855357-8abea4ce',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"tab_index\":1,\"tab_title\":\"بارهای اطراف من\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:29.000','2026-07-08 20:58:32.263'),(49,53,15,'nearby_loads_view','مشاهده بارهای اطراف من','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1783531709855899-571db4d9',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:29.000','2026-07-08 20:58:32.265'),(50,53,15,'tab_change','تغییر تب پایین اپ','call_history','تماس‌ها',NULL,NULL,NULL,NULL,NULL,'1783531719964949-cc94557c',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"tab_index\":2,\"tab_title\":\"تماس‌ها\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:39.000','2026-07-08 20:58:42.538'),(51,53,15,'tab_change','تغییر تب پایین اپ','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1783531720816431-cc8a1d31',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"tab_index\":0,\"tab_title\":\"داشبورد\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:40.000','2026-07-08 20:58:43.261'),(52,53,15,'dashboard_view','مشاهده داشبورد','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1783531720817154-f343c6e0',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:40.000','2026-07-08 20:58:43.261'),(53,53,15,'tab_change','تغییر تب پایین اپ','profile','پروفایل',NULL,NULL,NULL,NULL,NULL,'1783531722044512-2246d463',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"tab_index\":3,\"tab_title\":\"پروفایل\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:42.000','2026-07-08 20:58:44.458'),(54,53,15,'profile_view','مشاهده پروفایل','profile','پروفایل',NULL,NULL,NULL,NULL,NULL,'1783531722044940-178a3f0a',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:42.000','2026-07-08 20:58:44.462'),(55,53,15,'dashboard_view','مشاهده داشبورد','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1783531723387788-31f3468a',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:43.000','2026-07-08 20:58:45.842'),(56,53,15,'tab_change','تغییر تب پایین اپ','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1783531723387402-692470a',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"tab_index\":0,\"tab_title\":\"داشبورد\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:43.000','2026-07-08 20:58:45.844'),(57,53,15,'tab_change','تغییر تب پایین اپ','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1783531724746493-adc4a974',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"tab_index\":1,\"tab_title\":\"بارهای اطراف من\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:44.000','2026-07-08 20:58:47.161'),(58,53,15,'nearby_loads_view','مشاهده بارهای اطراف من','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1783531724746973-21be845e',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:44.000','2026-07-08 20:58:47.166'),(59,53,15,'dashboard_view','مشاهده داشبورد','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1783531725574653-298168ed',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:45.000','2026-07-08 20:58:47.967'),(60,53,15,'tab_change','تغییر تب پایین اپ','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1783531725574070-b44bfbd',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"tab_index\":0,\"tab_title\":\"داشبورد\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:45.000','2026-07-08 20:58:47.968'),(61,53,15,'tab_change','تغییر تب پایین اپ','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1783531731692906-fef524b9',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"tab_index\":1,\"tab_title\":\"بارهای اطراف من\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:51.000','2026-07-08 20:58:54.369'),(62,53,15,'nearby_loads_view','مشاهده بارهای اطراف من','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1783531731693357-3758d828',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:51.000','2026-07-08 20:58:54.370'),(63,53,15,'tab_change','تغییر تب پایین اپ','call_history','تماس‌ها',NULL,NULL,NULL,NULL,NULL,'1783531732058274-939776c9',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"tab_index\":2,\"tab_title\":\"تماس‌ها\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:52.000','2026-07-08 20:58:54.669'),(64,53,15,'tab_change','تغییر تب پایین اپ','profile','پروفایل',NULL,NULL,NULL,NULL,NULL,'1783531732802605-9524a81e',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"tab_index\":3,\"tab_title\":\"پروفایل\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:52.000','2026-07-08 20:58:55.196'),(65,53,15,'profile_view','مشاهده پروفایل','profile','پروفایل',NULL,NULL,NULL,NULL,NULL,'1783531732803040-4bb51d4',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:52.000','2026-07-08 20:58:55.197'),(66,53,15,'identity_info_view','مشاهده مشخصات هویتی','identity_info','اطلاعات هویتی',NULL,NULL,NULL,NULL,NULL,'1783531736136163-e398b7d7',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:56.000','2026-07-08 20:58:58.534'),(67,53,15,'identity_info_view','مشاهده مشخصات هویتی','identity_info','اطلاعات هویتی',NULL,NULL,NULL,NULL,NULL,'1783531736122551-61eea4c4',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:56.000','2026-07-08 20:58:58.535'),(68,53,15,'support_view','مشاهده پشتیبانی','support','پشتیبانی و پیام‌ها',NULL,NULL,NULL,NULL,NULL,'1783531738460607-f712fa4',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:58:58.000','2026-07-08 20:59:00.890'),(69,53,15,'ticket_view','مشاهده گفتگوی تیکت','support','پشتیبانی و پیام‌ها','ticket',3,NULL,NULL,3,'1783531740377319-2a1c1ef6',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"subject\":\"سلام\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:59:00.000','2026-07-08 20:59:02.796'),(70,53,15,'ticket_view','مشاهده گفتگوی تیکت','ticket_chat','گفتگوی تیکت','ticket',3,NULL,NULL,3,'1783531740379131-63536830',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:59:00.000','2026-07-08 20:59:02.965'),(71,53,15,'tab_change','تغییر تب پایین اپ','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1783531743796883-88dc851',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"tab_index\":0,\"tab_title\":\"داشبورد\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:59:03.000','2026-07-08 20:59:06.198'),(72,53,15,'dashboard_view','مشاهده داشبورد','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1783531743797553-da9c1c11',1,4,'1.0.3','a4f071602e6b131838b3a8cec99cf39d',_binary '{O','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"a4f071602e6b131838b3a8cec99cf39d\"}}','2026-07-08 20:59:03.000','2026-07-08 20:59:06.316'),(73,53,15,'app_open','باز شدن اپلیکیشن','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1784315249305982-16a8ea5e',1,4,'1.0.3','48486513985bab2f79b679cb87596bbd',_binary '\�m[','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"48486513985bab2f79b679cb87596bbd\"}}','2026-07-17 22:37:29.000','2026-07-17 22:37:11.473'),(74,53,15,'nearby_loads_view','مشاهده بارهای اطراف من','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1784315249310355-c8affe36',1,4,'1.0.3','4a4e9d38baf317f292c901e7f1275c9e',_binary '\�m[','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"4a4e9d38baf317f292c901e7f1275c9e\"}}','2026-07-17 22:37:29.000','2026-07-17 22:37:11.487'),(75,53,15,'dashboard_view','مشاهده داشبورد','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1784315249308852-4e883c30',1,4,'1.0.3','9f7418087b791d87ed71a22d025e5aa4',_binary '\�m[','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"9f7418087b791d87ed71a22d025e5aa4\"}}','2026-07-17 22:37:29.000','2026-07-17 22:37:11.507'),(76,53,15,'profile_view','مشاهده پروفایل','profile','پروفایل',NULL,NULL,NULL,NULL,NULL,'1784315249311367-2ac6f54f',1,4,'1.0.3','0d428e43530d637e642b300640dc4a1f',_binary '\�m[','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-07-17 22:37:29.000','2026-07-17 22:37:11.557'),(77,53,15,'ticket_view','مشاهده گفتگوی تیکت','ticket_chat','گفتگوی تیکت','ticket',3,NULL,NULL,3,'1784315251904136-732259a0',1,4,'1.0.3','0d428e43530d637e642b300640dc4a1f',_binary '\�m[','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-07-17 22:37:31.000','2026-07-17 22:37:14.048'),(78,53,15,'profile_view','مشاهده پروفایل','profile','پروفایل',NULL,NULL,NULL,NULL,NULL,'1784315256618171-36434b76',1,4,'1.0.3','0d428e43530d637e642b300640dc4a1f',_binary '\�m[','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-07-17 22:37:36.000','2026-07-17 22:37:18.820'),(79,53,15,'tab_change','تغییر تب پایین اپ','profile','پروفایل',NULL,NULL,NULL,NULL,NULL,'1784315256614871-b6b55079',1,4,'1.0.3','0d428e43530d637e642b300640dc4a1f',_binary '\�m[','Dart/3.12 (dart:io)','{\"tab_index\":3,\"tab_title\":\"پروفایل\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-07-17 22:37:36.000','2026-07-17 22:37:18.839'),(80,53,15,'vehicle_info_view','مشاهده مشخصات خودرو','vehicle_info','مشخصات خودرو',NULL,NULL,NULL,NULL,NULL,'1784315259611638-69f3269c',1,4,'1.0.3','0d428e43530d637e642b300640dc4a1f',_binary '\�m[','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-07-17 22:37:39.000','2026-07-17 22:37:21.647'),(81,53,15,'vehicle_info_view','مشاهده مشخصات خودرو','vehicle_info','مشخصات خودرو و بارگیر',NULL,NULL,NULL,NULL,NULL,'1784315259662500-535151ac',1,4,'1.0.3','0d428e43530d637e642b300640dc4a1f',_binary '\�m[','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-07-17 22:37:39.000','2026-07-17 22:37:21.742'),(82,53,15,'dashboard_view','مشاهده داشبورد','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1784315263508653-fa89dc20',1,4,'1.0.3','0d428e43530d637e642b300640dc4a1f',_binary '\�m[','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-07-17 22:37:43.000','2026-07-17 22:37:25.657'),(83,53,15,'tab_change','تغییر تب پایین اپ','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1784315263506511-646c8b68',1,4,'1.0.3','0d428e43530d637e642b300640dc4a1f',_binary '\�m[','Dart/3.12 (dart:io)','{\"tab_index\":0,\"tab_title\":\"داشبورد\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-07-17 22:37:43.000','2026-07-17 22:37:25.661'),(84,55,16,'app_open','باز شدن اپلیکیشن','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1784631797548613-b5c043e3',1,4,'1.0.3','7b7313a0a83a53b09ede3606390dbd04',_binary ']v�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"7b7313a0a83a53b09ede3606390dbd04\"}}','2026-07-21 14:33:17.000','2026-07-21 14:33:19.617'),(85,55,16,'dashboard_view','مشاهده داشبورد','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1784631797548949-514ff74c',1,4,'1.0.3','2f9ab8de5427657e3b66e96cefb229db',_binary ']v�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"2f9ab8de5427657e3b66e96cefb229db\"}}','2026-07-21 14:33:17.000','2026-07-21 14:33:19.639'),(86,55,16,'nearby_loads_view','مشاهده بارهای اطراف من','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1784631797549204-4a1f61f4',1,4,'1.0.3','7cfa26afb1d89b88cc1d9e4a9321aa0f',_binary ']v�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"7cfa26afb1d89b88cc1d9e4a9321aa0f\"}}','2026-07-21 14:33:17.000','2026-07-21 14:33:19.646'),(87,55,16,'profile_view','مشاهده پروفایل','profile','پروفایل',NULL,NULL,NULL,NULL,NULL,'1784631797549565-1f5d9b33',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:33:17.000','2026-07-21 14:33:19.657'),(88,55,16,'search_page_view','ورود به صفحه جستجوی بار','search_loads','جستجوی بار',NULL,NULL,NULL,NULL,NULL,'1784631813727110-1286231c',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:33:33.000','2026-07-21 14:33:34.664'),(89,55,16,'search_filter_set','انتخاب فیلتر جستجوی بار','search_loads','جستجوی بار',NULL,NULL,NULL,NULL,NULL,'1784631819729075-42fd0e66',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"filter\":\"origin_province\",\"province_id\":123,\"province_name\":\"تهران\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:33:39.000','2026-07-21 14:33:40.737'),(90,55,16,'search_filter_set','انتخاب فیلتر جستجوی بار','search_loads','جستجوی بار',NULL,NULL,NULL,NULL,NULL,'1784631834122757-7c8344d2',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"filter\":\"dest_province\",\"province_id\":110,\"province_name\":\"اصفهان\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:33:54.000','2026-07-21 14:33:55.124'),(91,55,16,'search_loads','جستجوی بار','search_loads','جستجوی بار',NULL,NULL,NULL,NULL,NULL,'1784631834241052-beacf486',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"origin_province_id\":123,\"origin_province_name\":\"تهران\",\"dest_province_id\":110,\"dest_province_name\":\"اصفهان\",\"raw_count\":0,\"shown_count\":0,\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:33:54.000','2026-07-21 14:33:55.185'),(92,55,16,'search_filter_set','انتخاب فیلتر جستجوی بار','search_loads','جستجوی بار',NULL,NULL,NULL,NULL,NULL,'1784631882043774-95459a82',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"filter\":\"dest_province\",\"province_id\":129,\"province_name\":\"خراسان جنوبی\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:34:42.000','2026-07-21 14:34:43.202'),(93,55,16,'search_loads','جستجوی بار','search_loads','جستجوی بار',NULL,NULL,NULL,NULL,NULL,'1784631882680486-debd808d',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"origin_province_id\":123,\"origin_province_name\":\"تهران\",\"dest_province_id\":129,\"dest_province_name\":\"خراسان جنوبی\",\"raw_count\":0,\"shown_count\":0,\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:34:42.000','2026-07-21 14:34:43.686'),(94,55,16,'search_filter_set','انتخاب فیلتر جستجوی بار','search_loads','جستجوی بار',NULL,NULL,NULL,NULL,NULL,'1784631885061156-39ba5847',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"filter\":\"dest_province\",\"province_id\":104,\"province_name\":\"آذربایجان غربی\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:34:45.000','2026-07-21 14:34:46.080'),(95,55,16,'search_loads','جستجوی بار','search_loads','جستجوی بار',NULL,NULL,NULL,NULL,NULL,'1784631885194689-a01e5553',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"origin_province_id\":123,\"origin_province_name\":\"تهران\",\"dest_province_id\":104,\"dest_province_name\":\"آذربایجان غربی\",\"raw_count\":0,\"shown_count\":0,\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:34:45.000','2026-07-21 14:34:46.184'),(96,55,16,'search_filter_set','انتخاب فیلتر جستجوی بار','search_loads','جستجوی بار',NULL,NULL,NULL,NULL,NULL,'1784631887041924-b7cd87fe',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"filter\":\"dest_province\",\"province_id\":104,\"province_name\":\"آذربایجان غربی\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:34:47.000','2026-07-21 14:34:48.043'),(97,55,16,'search_loads','جستجوی بار','search_loads','جستجوی بار',NULL,NULL,NULL,NULL,NULL,'1784631887175259-53f82d55',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"origin_province_id\":123,\"origin_province_name\":\"تهران\",\"dest_province_id\":104,\"dest_province_name\":\"آذربایجان غربی\",\"raw_count\":0,\"shown_count\":0,\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:34:47.000','2026-07-21 14:34:48.108'),(98,55,16,'search_filter_set','انتخاب فیلتر جستجوی بار','search_loads','جستجوی بار',NULL,NULL,NULL,NULL,NULL,'1784631889407922-95e26443',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"filter\":\"dest_province\",\"province_id\":122,\"province_name\":\"هرمزگان\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:34:49.000','2026-07-21 14:34:50.367'),(99,55,16,'search_loads','جستجوی بار','search_loads','جستجوی بار',NULL,NULL,NULL,NULL,NULL,'1784631889497626-726dd93',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"origin_province_id\":123,\"origin_province_name\":\"تهران\",\"dest_province_id\":122,\"dest_province_name\":\"هرمزگان\",\"raw_count\":0,\"shown_count\":0,\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:34:49.000','2026-07-21 14:34:50.440'),(100,55,16,'tab_change','تغییر تب پایین اپ','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1784631898779903-f5dd5605',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"tab_index\":1,\"tab_title\":\"بارهای اطراف من\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:34:58.000','2026-07-21 14:34:59.717'),(101,55,16,'nearby_loads_view','مشاهده بارهای اطراف من','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1784631898780703-25b942b2',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:34:58.000','2026-07-21 14:34:59.765'),(102,55,16,'load_card_open','باز کردن کارت بار','loads_list','لیست بارها','load',9,9,NULL,NULL,'1784631901412566-49775cdc',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"origin\":\"تهران (تهران)\",\"destination\":\"مراغه (آذربایجان شرقی)\",\"cargo_title\":\"خوراکی\",\"price\":\"5,000,000\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:35:01.000','2026-07-21 14:35:02.353'),(103,55,16,'load_detail_view','مشاهده جزئیات بار','load_details','جزئیات بار','load',9,9,NULL,NULL,'1784631901427422-99ab6882',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:35:01.000','2026-07-21 14:35:02.419'),(104,55,16,'call_button_tap','کلیک روی برقراری تماس','load_details','جزئیات بار','load',9,9,1,NULL,'1784631902374742-aaf5505a',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"public_code\":\"358597326497\",\"origin\":\"تهران (تهران)\",\"destination\":\"مراغه (آذربایجان شرقی)\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:35:02.000','2026-07-21 14:35:03.578'),(105,55,16,'profile_view','مشاهده پروفایل','profile','پروفایل',NULL,NULL,NULL,NULL,NULL,'1784632224292514-c98cd12c',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:40:24.000','2026-07-21 14:40:25.234'),(106,55,16,'tab_change','تغییر تب پایین اپ','profile','پروفایل',NULL,NULL,NULL,NULL,NULL,'1784632224292187-2f07091a',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"tab_index\":3,\"tab_title\":\"پروفایل\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:40:24.000','2026-07-21 14:40:25.239'),(107,55,16,'dashboard_view','مشاهده داشبورد','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1784632254828959-d6a08d76',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:40:54.000','2026-07-21 14:40:55.808'),(108,55,16,'tab_change','تغییر تب پایین اپ','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1784632254828329-8a02ce52',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"tab_index\":0,\"tab_title\":\"داشبورد\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:40:54.000','2026-07-21 14:40:55.826'),(109,55,16,'tab_change','تغییر تب پایین اپ','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1784632288685287-3c05d15b',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"tab_index\":1,\"tab_title\":\"بارهای اطراف من\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:41:28.000','2026-07-21 14:41:29.678'),(110,55,16,'nearby_loads_view','مشاهده بارهای اطراف من','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1784632288685754-f9ce995',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:41:28.000','2026-07-21 14:41:29.684'),(111,55,16,'support_view','مشاهده پشتیبانی','support','پشتیبانی و پیام‌ها',NULL,NULL,NULL,NULL,NULL,'1784632303414807-f74a2de8',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:41:43.000','2026-07-21 14:41:44.418'),(112,55,16,'support_contact_tap','کلیک روی راه ارتباطی پشتیبانی','support','پشتیبانی و پیام‌ها','support',NULL,NULL,NULL,NULL,'1784632306610947-7152d43c',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"url_scheme\":\"tel\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:41:46.000','2026-07-21 14:41:47.595'),(113,55,16,'tab_change','تغییر تب پایین اپ','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1784632542188115-2a849d67',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"tab_index\":0,\"tab_title\":\"داشبورد\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:45:42.000','2026-07-21 14:45:43.185'),(114,55,16,'dashboard_view','مشاهده داشبورد','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1784632542188523-7e6a1e93',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:45:42.000','2026-07-21 14:45:43.193'),(115,55,16,'profile_view','مشاهده پروفایل','profile','پروفایل',NULL,NULL,NULL,NULL,NULL,'1784632546198703-5e92e628',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:45:46.000','2026-07-21 14:45:47.240'),(116,55,16,'tab_change','تغییر تب پایین اپ','profile','پروفایل',NULL,NULL,NULL,NULL,NULL,'1784632546197563-2ec6af45',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"tab_index\":3,\"tab_title\":\"پروفایل\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:45:46.000','2026-07-21 14:45:47.241'),(117,55,16,'tab_change','تغییر تب پایین اپ','call_history','تماس‌ها',NULL,NULL,NULL,NULL,NULL,'1784632563216703-c22d5b12',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"tab_index\":2,\"tab_title\":\"تماس‌ها\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:46:03.000','2026-07-21 14:46:04.222'),(118,55,16,'load_detail_view','مشاهده جزئیات بار','load_details','جزئیات بار','load',9,9,NULL,NULL,'1784632565980249-6cdcc82f',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:46:05.000','2026-07-21 14:46:06.923'),(119,55,16,'tab_change','تغییر تب پایین اپ','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1784632569656796-ed5c2eb6',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"tab_index\":0,\"tab_title\":\"داشبورد\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:46:09.000','2026-07-21 14:46:10.882'),(120,55,16,'dashboard_view','مشاهده داشبورد','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1784632569657280-44ebda07',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:46:09.000','2026-07-21 14:46:11.733'),(121,55,16,'tab_change','تغییر تب پایین اپ','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1784632581103635-1acca7bb',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"tab_index\":1,\"tab_title\":\"بارهای اطراف من\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:46:21.000','2026-07-21 14:46:22.107'),(122,55,16,'nearby_loads_view','مشاهده بارهای اطراف من','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1784632581104160-bfdf5c73',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:46:21.000','2026-07-21 14:46:22.128'),(123,55,16,'support_view','مشاهده پشتیبانی','support','پشتیبانی و پیام‌ها',NULL,NULL,NULL,NULL,NULL,'1784632584356999-89dc9a95',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:46:24.000','2026-07-21 14:46:25.488'),(124,55,16,'ticket_create','ثبت تیکت جدید','support','پشتیبانی و پیام‌ها','ticket',5,NULL,NULL,5,'1784632636496355-16343f21',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"subject\":\"بارهای بیشتری نشان دهید\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:47:16.000','2026-07-21 14:47:17.584'),(125,55,16,'ticket_view','مشاهده گفتگوی تیکت','ticket_chat','گفتگوی تیکت','ticket',5,NULL,NULL,5,'1784632636534583-400d4dd9',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary ']v�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-07-21 14:47:16.000','2026-07-21 14:47:17.593'),(126,67,27,'dashboard_view','مشاهده داشبورد','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1786020558063465-fb9662c2',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary '\�\�\�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 16:19:18.000','2026-08-06 16:19:19.463'),(127,67,27,'profile_view','مشاهده پروفایل','profile','پروفایل',NULL,NULL,NULL,NULL,NULL,'1786020558064083-9a114d1',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary '\�\�\�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 16:19:18.000','2026-08-06 16:19:19.487'),(128,67,27,'app_open','باز شدن اپلیکیشن','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1786020558063156-61918cd2',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary '\�\�\�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 16:19:18.000','2026-08-06 16:19:19.625'),(129,67,27,'nearby_loads_view','مشاهده بارهای اطراف من','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1786020558063713-84fd944',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary '\�\�\�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 16:19:18.000','2026-08-06 16:19:19.650'),(130,67,27,'tab_change','تغییر تب پایین اپ','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1786020562291976-32014731',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary '\�\�\�','Dart/3.12 (dart:io)','{\"tab_index\":1,\"tab_title\":\"بارهای اطراف من\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 16:19:22.000','2026-08-06 16:19:22.238'),(131,67,27,'nearby_loads_view','مشاهده بارهای اطراف من','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1786020562292462-c8c91006',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary '\�\�\�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 16:19:22.000','2026-08-06 16:19:22.243'),(132,67,27,'load_card_open','باز کردن کارت بار','loads_list','لیست بارها','load',11,11,NULL,NULL,'1786020563921481-3ef3a377',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary '\�\�\�','Dart/3.12 (dart:io)','{\"origin\":\"تهران (تهران)\",\"destination\":\"اصفهان (اصفهان)\",\"cargo_title\":\"خوراکی\",\"price\":\"3,800,000\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 16:19:23.000','2026-08-06 16:19:23.903'),(133,67,27,'load_detail_view','مشاهده جزئیات بار','load_details','جزئیات بار','load',11,11,NULL,NULL,'1786020563937798-2a502fd7',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary '\�\�\�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 16:19:23.000','2026-08-06 16:19:24.020'),(134,67,27,'call_button_tap','کلیک روی برقراری تماس','load_details','جزئیات بار','load',11,11,9,NULL,'1786020565054491-eb9989cc',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary '\�\�\�','Dart/3.12 (dart:io)','{\"public_code\":\"489035311573\",\"origin\":\"تهران (تهران)\",\"destination\":\"اصفهان (اصفهان)\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 16:19:25.000','2026-08-06 16:19:25.044'),(135,67,27,'dashboard_view','مشاهده داشبورد','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1786020901577875-c49f3581',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary '\�\�\�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 16:25:01.000','2026-08-06 16:25:01.747'),(136,67,27,'app_open','باز شدن اپلیکیشن','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1786020901577600-93b3dd87',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary '\�\�\�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 16:25:01.000','2026-08-06 16:25:01.808'),(137,67,27,'nearby_loads_view','مشاهده بارهای اطراف من','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1786020901578135-327fe8',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary '\�\�\�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 16:25:01.000','2026-08-06 16:25:01.918'),(138,67,27,'profile_view','مشاهده پروفایل','profile','پروفایل',NULL,NULL,NULL,NULL,NULL,'1786020901578502-a3fb52e3',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary '\�\�\�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 16:25:01.000','2026-08-06 16:25:01.949'),(139,67,27,'profile_view','مشاهده پروفایل','profile','پروفایل',NULL,NULL,NULL,NULL,NULL,'1786020906718331-7d314a99',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary '\�\�\�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 16:25:06.000','2026-08-06 16:25:06.756'),(140,67,27,'tab_change','تغییر تب پایین اپ','profile','پروفایل',NULL,NULL,NULL,NULL,NULL,'1786020906717881-8e5399cd',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary '\�\�\�','Dart/3.12 (dart:io)','{\"tab_index\":3,\"tab_title\":\"پروفایل\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 16:25:06.000','2026-08-06 16:25:06.796'),(141,67,27,'identity_info_view','مشاهده مشخصات هویتی','identity_info','اطلاعات هویتی',NULL,NULL,NULL,NULL,NULL,'1786020909712907-6ad2a296',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary '\�\�\�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 16:25:09.000','2026-08-06 16:25:09.681'),(142,67,27,'identity_info_view','مشاهده مشخصات هویتی','identity_info','اطلاعات هویتی',NULL,NULL,NULL,NULL,NULL,'1786020909715603-4a554bfe',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary '\�\�\�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 16:25:09.000','2026-08-06 16:25:09.696'),(143,67,27,'tab_change','تغییر تب پایین اپ','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1786020928573406-ca711bf2',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary '\�\�\�','Dart/3.12 (dart:io)','{\"tab_index\":0,\"tab_title\":\"داشبورد\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 16:25:28.000','2026-08-06 16:25:28.647'),(144,67,27,'dashboard_view','مشاهده داشبورد','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1786020928573929-d4b3247b',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary '\�\�\�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 16:25:28.000','2026-08-06 16:25:28.649'),(145,67,27,'nearby_loads_view','مشاهده بارهای اطراف من','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1786020934556323-5d8fa506',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary '\�\�\�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 16:25:34.000','2026-08-06 16:25:34.502'),(146,67,27,'tab_change','تغییر تب پایین اپ','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1786020934555943-42e0085c',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary '\�\�\�','Dart/3.12 (dart:io)','{\"tab_index\":1,\"tab_title\":\"بارهای اطراف من\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 16:25:34.000','2026-08-06 16:25:34.502'),(147,67,27,'load_card_open','باز کردن کارت بار','loads_list','لیست بارها','load',9,9,NULL,NULL,'1786020944048965-f16b5c4a',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary '\�\�\�','Dart/3.12 (dart:io)','{\"origin\":\"تهران (تهران)\",\"destination\":\"مراغه (آذربایجان شرقی)\",\"cargo_title\":\"خوراکی\",\"price\":\"5,000,000\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 16:25:44.000','2026-08-06 16:25:44.019'),(148,67,27,'load_detail_view','مشاهده جزئیات بار','load_details','جزئیات بار','load',9,9,NULL,NULL,'1786020944066267-99e80cd7',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary '\�\�\�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 16:25:44.000','2026-08-06 16:25:44.113'),(149,67,27,'call_button_tap','کلیک روی برقراری تماس','load_details','جزئیات بار','load',9,9,1,NULL,'1786020945310027-450fbab1',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary '\�\�\�','Dart/3.12 (dart:io)','{\"public_code\":\"358597326497\",\"origin\":\"تهران (تهران)\",\"destination\":\"مراغه (آذربایجان شرقی)\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 16:25:45.000','2026-08-06 16:25:45.264'),(150,67,27,'load_card_open','باز کردن کارت بار','loads_list','لیست بارها','load',10,10,NULL,NULL,'1786020953648719-d67db813',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary '\�\�\�','Dart/3.12 (dart:io)','{\"origin\":\"تهران (تهران)\",\"destination\":\"مشهد (خراسان رضوی)\",\"cargo_title\":\"مواد غذایی - پالت: ماکارونی و رشته‌های بسته‌بندی\",\"price\":\"5,000,000\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 16:25:53.000','2026-08-06 16:25:53.785'),(151,67,27,'load_detail_view','مشاهده جزئیات بار','load_details','جزئیات بار','load',10,10,NULL,NULL,'1786020953650450-5b622c9d',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary '\�\�\�','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 16:25:53.000','2026-08-06 16:25:53.828'),(152,67,27,'call_button_tap','کلیک روی برقراری تماس','load_details','جزئیات بار','load',10,10,7,NULL,'1786020954646875-a606adb4',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary '\�\�\�','Dart/3.12 (dart:io)','{\"public_code\":\"300772862700\",\"origin\":\"تهران (تهران)\",\"destination\":\"مشهد (خراسان رضوی)\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 16:25:54.000','2026-08-06 16:25:54.585'),(153,67,27,'dashboard_view','مشاهده داشبورد','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1786043595833549-4bf4b869',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary 'b`��','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 22:43:15.000','2026-08-06 22:43:17.479'),(154,67,27,'profile_view','مشاهده پروفایل','profile','پروفایل',NULL,NULL,NULL,NULL,NULL,'1786043595834061-59bfd70b',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary 'b`��','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 22:43:15.000','2026-08-06 22:43:17.550'),(155,67,27,'nearby_loads_view','مشاهده بارهای اطراف من','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1786043595833768-a9be1ed1',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary 'b`��','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 22:43:15.000','2026-08-06 22:43:17.560'),(156,67,27,'app_open','باز شدن اپلیکیشن','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1786043595833279-da23ea9a',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary 'b`��','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 22:43:15.000','2026-08-06 22:43:17.565'),(157,67,27,'nearby_loads_view','مشاهده بارهای اطراف من','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1786043602425012-2652f4ed',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary 'b`��','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 22:43:22.000','2026-08-06 22:43:23.077'),(158,67,27,'tab_change','تغییر تب پایین اپ','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1786043602424449-b71ac206',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary 'b`��','Dart/3.12 (dart:io)','{\"tab_index\":1,\"tab_title\":\"بارهای اطراف من\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 22:43:22.000','2026-08-06 22:43:23.114'),(159,67,27,'dashboard_view','مشاهده داشبورد','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1786043727639722-b48907d',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary 'b`��','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 22:45:27.000','2026-08-06 22:45:29.373'),(160,67,27,'profile_view','مشاهده پروفایل','profile','پروفایل',NULL,NULL,NULL,NULL,NULL,'1786043727641248-55c9043d',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary 'b`��','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 22:45:27.000','2026-08-06 22:45:29.395'),(161,67,27,'app_open','باز شدن اپلیکیشن','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1786043727639461-456737e1',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary 'b`��','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 22:45:27.000','2026-08-06 22:45:29.438'),(162,67,27,'nearby_loads_view','مشاهده بارهای اطراف من','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1786043727639955-a0845ec1',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary 'b`��','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-06 22:45:27.000','2026-08-06 22:45:29.678'),(163,67,27,'nearby_loads_view','مشاهده بارهای اطراف من','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1786959908369011-78f359d1',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary 'b`�+','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-17 13:15:08.000','2026-08-17 13:15:10.294'),(164,67,27,'dashboard_view','مشاهده داشبورد','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1786959908368743-e5ab73a',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary 'b`�+','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-17 13:15:08.000','2026-08-17 13:15:10.516'),(165,67,27,'profile_view','مشاهده پروفایل','profile','پروفایل',NULL,NULL,NULL,NULL,NULL,'1786959908369374-71fe1500',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary 'b`�+','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-17 13:15:08.000','2026-08-17 13:15:10.521'),(166,67,27,'app_open','باز شدن اپلیکیشن','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1786959908368384-7f9d7b85',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary 'b`�+','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-17 13:15:08.000','2026-08-17 13:15:10.549'),(167,67,27,'nearby_loads_view','مشاهده بارهای اطراف من','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1786959916327235-ddc80fb5',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary 'b`�+','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-17 13:15:16.000','2026-08-17 13:15:18.173'),(168,67,27,'tab_change','تغییر تب پایین اپ','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1786959916310683-71792b6c',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary 'b`�+','Dart/3.12 (dart:io)','{\"tab_index\":1,\"tab_title\":\"بارهای اطراف من\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-17 13:15:16.000','2026-08-17 13:15:18.498'),(169,67,27,'load_detail_view','مشاهده جزئیات بار','load_details','جزئیات بار','load',12,12,NULL,NULL,'1786959929980925-7f533bca',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary 'b`�+','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-17 13:15:29.000','2026-08-17 13:15:31.881'),(170,67,27,'load_card_open','باز کردن کارت بار','loads_list','لیست بارها','load',12,12,NULL,NULL,'1786959929979261-d4ef15e6',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary 'b`�+','Dart/3.12 (dart:io)','{\"origin\":\"تهران (تهران)\",\"destination\":\"اصفهان (اصفهان)\",\"cargo_title\":\"مواد - کیسه: کود شیمیایی\",\"price\":\"4,500,000\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-17 13:15:29.000','2026-08-17 13:15:31.927'),(171,67,27,'call_button_tap','کلیک روی برقراری تماس','load_details','جزئیات بار','load',12,12,10,NULL,'1786959933970310-2611d484',1,4,'1.0.3','d96ef18e5f4b6ce496952452052ecb92',_binary 'b`�+','Dart/3.12 (dart:io)','{\"public_code\":\"885502099707\",\"origin\":\"تهران (تهران)\",\"destination\":\"اصفهان (اصفهان)\",\"_client\":{\"platform\":\"android\",\"version_code\":4,\"version_name\":\"1.0.3\",\"device_id\":\"d96ef18e5f4b6ce496952452052ecb92\"}}','2026-08-17 13:15:33.000','2026-08-17 13:15:34.849'),(172,72,28,'app_open','باز شدن اپلیکیشن','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1787384119289863-503bf405',1,6,'1.0.5','0d428e43530d637e642b300640dc4a1f',_binary '�\�/','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":6,\"version_name\":\"1.0.5\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-08-22 11:05:19.000','2026-08-22 11:05:01.463'),(173,72,28,'nearby_loads_view','مشاهده بارهای اطراف من','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1787384119292002-56261f53',1,6,'1.0.5','0d428e43530d637e642b300640dc4a1f',_binary '�\�/','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":6,\"version_name\":\"1.0.5\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-08-22 11:05:19.000','2026-08-22 11:05:01.464'),(174,72,28,'profile_view','مشاهده پروفایل','profile','پروفایل',NULL,NULL,NULL,NULL,NULL,'1787384119293231-b2772986',1,6,'1.0.5','0d428e43530d637e642b300640dc4a1f',_binary '�\�/','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":6,\"version_name\":\"1.0.5\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-08-22 11:05:19.000','2026-08-22 11:05:01.489'),(175,72,28,'dashboard_view','مشاهده داشبورد','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1787384119291127-660819cd',1,6,'1.0.5','0d428e43530d637e642b300640dc4a1f',_binary '�\�/','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":6,\"version_name\":\"1.0.5\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-08-22 11:05:19.000','2026-08-22 11:05:01.501'),(176,72,28,'profile_view','مشاهده پروفایل','profile','پروفایل',NULL,NULL,NULL,NULL,NULL,'1787384138730408-8a3e5395',1,6,'1.0.5','0d428e43530d637e642b300640dc4a1f',_binary '�\�/','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":6,\"version_name\":\"1.0.5\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-08-22 11:05:38.000','2026-08-22 11:05:20.951'),(177,72,28,'app_open','باز شدن اپلیکیشن','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1787384138726533-85360c56',1,6,'1.0.5','0d428e43530d637e642b300640dc4a1f',_binary '�\�/','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":6,\"version_name\":\"1.0.5\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-08-22 11:05:38.000','2026-08-22 11:05:20.967'),(178,72,28,'dashboard_view','مشاهده داشبورد','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1787384138727666-80dd93a2',1,6,'1.0.5','0d428e43530d637e642b300640dc4a1f',_binary '�\�/','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":6,\"version_name\":\"1.0.5\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-08-22 11:05:38.000','2026-08-22 11:05:21.009'),(179,72,28,'nearby_loads_view','مشاهده بارهای اطراف من','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1787384138728823-61ddb425',1,6,'1.0.5','0d428e43530d637e642b300640dc4a1f',_binary '�\�/','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":6,\"version_name\":\"1.0.5\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-08-22 11:05:38.000','2026-08-22 11:05:21.017'),(180,72,28,'app_open','باز شدن اپلیکیشن','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1787384830803601-70c11363',1,6,'1.0.5','0d428e43530d637e642b300640dc4a1f',_binary '\�L','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":6,\"version_name\":\"1.0.5\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-08-22 11:17:10.000','2026-08-22 11:16:53.734'),(181,72,28,'dashboard_view','مشاهده داشبورد','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1787384830803954-e6013a65',1,6,'1.0.5','0d428e43530d637e642b300640dc4a1f',_binary '\�L','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":6,\"version_name\":\"1.0.5\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-08-22 11:17:10.000','2026-08-22 11:16:53.737'),(182,72,28,'nearby_loads_view','مشاهده بارهای اطراف من','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1787384830804160-13184365',1,6,'1.0.5','0d428e43530d637e642b300640dc4a1f',_binary '\�L','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":6,\"version_name\":\"1.0.5\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-08-22 11:17:10.000','2026-08-22 11:16:53.765'),(183,72,28,'profile_view','مشاهده پروفایل','profile','پروفایل',NULL,NULL,NULL,NULL,NULL,'1787384830837783-4d87a6c2',1,6,'1.0.5','0d428e43530d637e642b300640dc4a1f',_binary '\�L','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":6,\"version_name\":\"1.0.5\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-08-22 11:17:10.000','2026-08-22 11:16:53.780'),(184,72,28,'tab_change','تغییر تب پایین اپ','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1787384832255324-2838422f',1,6,'1.0.5','0d428e43530d637e642b300640dc4a1f',_binary '\�L','Dart/3.12 (dart:io)','{\"tab_index\":1,\"tab_title\":\"بارهای اطراف من\",\"_client\":{\"platform\":\"android\",\"version_code\":6,\"version_name\":\"1.0.5\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-08-22 11:17:12.000','2026-08-22 11:16:54.904'),(185,72,28,'nearby_loads_view','مشاهده بارهای اطراف من','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1787384832256488-5e1fa435',1,6,'1.0.5','0d428e43530d637e642b300640dc4a1f',_binary '\�L','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":6,\"version_name\":\"1.0.5\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-08-22 11:17:12.000','2026-08-22 11:16:54.913'),(186,72,28,'nearby_loads_view','مشاهده بارهای اطراف من','nearby_loads','بارهای اطراف من',NULL,NULL,NULL,NULL,NULL,'1787394448096258-a02f6c09',1,6,'1.0.5','0d428e43530d637e642b300640dc4a1f',_binary 'h\�X','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":6,\"version_name\":\"1.0.5\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-08-22 13:57:28.000','2026-08-22 13:57:11.522'),(187,72,28,'app_open','باز شدن اپلیکیشن','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1787394448093940-33e8ef34',1,6,'1.0.5','0d428e43530d637e642b300640dc4a1f',_binary 'h\�X','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":6,\"version_name\":\"1.0.5\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-08-22 13:57:28.000','2026-08-22 13:57:11.531'),(188,72,28,'dashboard_view','مشاهده داشبورد','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1787394448095073-e350dc87',1,6,'1.0.5','0d428e43530d637e642b300640dc4a1f',_binary 'h\�X','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":6,\"version_name\":\"1.0.5\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-08-22 13:57:28.000','2026-08-22 13:57:11.533'),(189,72,28,'profile_view','مشاهده پروفایل','profile','پروفایل',NULL,NULL,NULL,NULL,NULL,'1787394448097814-289e4acf',1,6,'1.0.5','0d428e43530d637e642b300640dc4a1f',_binary 'h\�X','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":6,\"version_name\":\"1.0.5\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-08-22 13:57:28.000','2026-08-22 13:57:11.549'),(190,72,28,'tab_change','تغییر تب پایین اپ','profile','پروفایل',NULL,NULL,NULL,NULL,NULL,'1787394449890000-57d26c43',1,6,'1.0.5','0d428e43530d637e642b300640dc4a1f',_binary 'h\�X','Dart/3.12 (dart:io)','{\"tab_index\":3,\"tab_title\":\"پروفایل\",\"_client\":{\"platform\":\"android\",\"version_code\":6,\"version_name\":\"1.0.5\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-08-22 13:57:29.000','2026-08-22 13:57:12.608'),(191,72,28,'profile_view','مشاهده پروفایل','profile','پروفایل',NULL,NULL,NULL,NULL,NULL,'1787394449896233-3573a4b3',1,6,'1.0.5','0d428e43530d637e642b300640dc4a1f',_binary 'h\�X','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":6,\"version_name\":\"1.0.5\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-08-22 13:57:29.000','2026-08-22 13:57:12.625'),(192,72,28,'support_view','مشاهده پشتیبانی','support','پشتیبانی و پیام‌ها',NULL,NULL,NULL,NULL,NULL,'1787394694497504-4490e248',1,6,'1.0.5','0d428e43530d637e642b300640dc4a1f',_binary 'h�Y','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":6,\"version_name\":\"1.0.5\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-08-22 14:01:34.000','2026-08-22 14:01:17.814'),(193,72,28,'support_view','مشاهده پشتیبانی','support','پشتیبانی و پیام‌ها',NULL,NULL,NULL,NULL,NULL,'1787394694495674-443b8454',1,6,'1.0.5','0d428e43530d637e642b300640dc4a1f',_binary 'h�Y','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":6,\"version_name\":\"1.0.5\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-08-22 14:01:34.000','2026-08-22 14:01:17.869'),(194,72,28,'tab_change','تغییر تب پایین اپ','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1787395145153198-81af49b6',1,6,'1.0.5','0d428e43530d637e642b300640dc4a1f',_binary 'h\�Y','Dart/3.12 (dart:io)','{\"tab_index\":0,\"tab_title\":\"داشبورد\",\"_client\":{\"platform\":\"android\",\"version_code\":6,\"version_name\":\"1.0.5\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-08-22 14:09:05.000','2026-08-22 14:08:47.881'),(195,72,28,'dashboard_view','مشاهده داشبورد','dashboard','داشبورد',NULL,NULL,NULL,NULL,NULL,'1787395145156037-8586f5f2',1,6,'1.0.5','0d428e43530d637e642b300640dc4a1f',_binary 'h\�Y','Dart/3.12 (dart:io)','{\"_client\":{\"platform\":\"android\",\"version_code\":6,\"version_name\":\"1.0.5\",\"device_id\":\"0d428e43530d637e642b300640dc4a1f\"}}','2026-08-22 14:09:05.000','2026-08-22 14:08:48.428');
/*!40000 ALTER TABLE `driver_activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `driver_documents`
--

DROP TABLE IF EXISTS `driver_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `driver_documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `driver_id` bigint unsigned NOT NULL,
  `doc_type` tinyint unsigned NOT NULL,
  `file_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint unsigned NOT NULL DEFAULT '0',
  `reviewed_by_user_id` bigint unsigned DEFAULT NULL,
  `reviewed_at` datetime(3) DEFAULT NULL,
  `reject_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  KEY `ix_driver_docs_driver` (`driver_id`,`doc_type`),
  KEY `ix_driver_docs_status` (`status`,`created_at`),
  KEY `fk_driver_docs_reviewer` (`reviewed_by_user_id`),
  CONSTRAINT `fk_driver_docs_driver` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_driver_docs_reviewer` FOREIGN KEY (`reviewed_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `driver_documents`
--

LOCK TABLES `driver_documents` WRITE;
/*!40000 ALTER TABLE `driver_documents` DISABLE KEYS */;
INSERT INTO `driver_documents` VALUES (3,27,3,'storage/uploads/images/user_67_875eae4ab578f4ba49010605.jpg',0,NULL,NULL,NULL,'2026-08-06 16:18:45.771','2026-08-06 16:18:45.771'),(4,27,4,'storage/uploads/images/user_67_d263d37af245148f36360b68.jpg',0,NULL,NULL,NULL,'2026-08-06 16:18:45.787','2026-08-06 16:18:45.787'),(5,28,3,'storage/uploads/images/user_72_7e0b662021877f288782d98c.jpg',0,NULL,NULL,NULL,'2026-08-22 11:05:00.870','2026-08-22 11:05:00.870'),(6,28,4,'storage/uploads/images/user_72_5a74830e4bf0f96bde25811d.jpg',0,NULL,NULL,NULL,'2026-08-22 11:05:00.889','2026-08-22 11:05:00.889');
/*!40000 ALTER TABLE `driver_documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `driver_location_logs`
--

DROP TABLE IF EXISTS `driver_location_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `driver_location_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `driver_id` bigint unsigned NOT NULL,
  `lat` decimal(10,7) NOT NULL,
  `lng` decimal(10,7) NOT NULL,
  `geohash` char(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `speed_kmh` decimal(6,2) DEFAULT NULL,
  `heading_deg` smallint unsigned DEFAULT NULL,
  `accuracy_m` decimal(6,2) DEFAULT NULL,
  `captured_at` datetime(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_driver_loclog_driver_time` (`driver_id`,`captured_at`),
  KEY `ix_driver_loclog_geohash_time` (`geohash`,`captured_at`),
  CONSTRAINT `fk_driver_loclog_driver` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `driver_location_logs`
--

LOCK TABLES `driver_location_logs` WRITE;
/*!40000 ALTER TABLE `driver_location_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `driver_location_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `driver_locations_current`
--

DROP TABLE IF EXISTS `driver_locations_current`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `driver_locations_current` (
  `driver_id` bigint unsigned NOT NULL,
  `lat` decimal(10,7) NOT NULL,
  `lng` decimal(10,7) NOT NULL,
  `geohash` char(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `speed_kmh` decimal(6,2) DEFAULT NULL,
  `heading_deg` smallint unsigned DEFAULT NULL,
  `accuracy_m` decimal(6,2) DEFAULT NULL,
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`driver_id`),
  KEY `ix_driver_loc_geohash` (`geohash`,`updated_at`),
  CONSTRAINT `fk_driver_loc_driver` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `driver_locations_current`
--

LOCK TABLES `driver_locations_current` WRITE;
/*!40000 ALTER TABLE `driver_locations_current` DISABLE KEYS */;
/*!40000 ALTER TABLE `driver_locations_current` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `driver_verification_events`
--

DROP TABLE IF EXISTS `driver_verification_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `driver_verification_events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `driver_id` bigint unsigned NOT NULL,
  `old_status` tinyint unsigned DEFAULT NULL,
  `new_status` tinyint unsigned NOT NULL,
  `actor_user_id` bigint unsigned NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  KEY `ix_driver_verif_events_driver_time` (`driver_id`,`created_at`),
  KEY `fk_driver_verif_events_actor` (`actor_user_id`),
  CONSTRAINT `fk_driver_verif_events_actor` FOREIGN KEY (`actor_user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_driver_verif_events_driver` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `driver_verification_events`
--

LOCK TABLES `driver_verification_events` WRITE;
/*!40000 ALTER TABLE `driver_verification_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `driver_verification_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `drivers`
--

DROP TABLE IF EXISTS `drivers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `drivers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `full_name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `national_code` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `smart_card_number` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vehicle_type_id` smallint unsigned DEFAULT NULL,
  `plate_number` varchar(24) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_year` smallint unsigned DEFAULT NULL,
  `color` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacity_kg` decimal(10,2) DEFAULT NULL,
  `province_id` int unsigned DEFAULT NULL,
  `city_id` bigint unsigned DEFAULT NULL,
  `verification_status` tinyint unsigned NOT NULL DEFAULT '0',
  `verified_at` datetime(3) DEFAULT NULL,
  `verified_by_user_id` bigint unsigned DEFAULT NULL,
  `reject_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating_avg` decimal(3,2) NOT NULL DEFAULT '0.00',
  `rating_count` int unsigned NOT NULL DEFAULT '0',
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  `deleted_at` datetime(3) DEFAULT NULL,
  `plate_active` varchar(24) COLLATE utf8mb4_unicode_ci GENERATED ALWAYS AS ((case when (`deleted_at` is null) then `plate_number` else NULL end)) STORED,
  `license_serial` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'سریال گواهینامه',
  `license_base` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'پایه گواهینامه',
  `vin_number` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'شماره VIN',
  `insurance_number` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'شماره بیمه نامه',
  `insurance_expiry` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'تاریخ اتمام بیمه',
  `engine_number` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'شماره موتور',
  `chassis_number` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'شماره شاسی',
  `issued_from` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'صادره از',
  `address` text COLLATE utf8mb4_unicode_ci COMMENT 'آدرس',
  `home_phone` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'تلفن منزل',
  `postal_code` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'کد پستی',
  `extra_phones` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin COMMENT 'شماره موبایل اضافه (آرایه‌ای از شماره‌ها)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_drivers_user` (`user_id`),
  UNIQUE KEY `uq_drivers_plate_active` (`plate_active`),
  KEY `ix_drivers_verify` (`verification_status`,`created_at`),
  KEY `ix_drivers_city` (`city_id`),
  KEY `ix_drivers_national` (`national_code`),
  KEY `ix_drivers_vehicle_type` (`vehicle_type_id`),
  KEY `fk_drivers_verified_by` (`verified_by_user_id`),
  KEY `fk_drivers_province` (`province_id`),
  CONSTRAINT `fk_drivers_city` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`),
  CONSTRAINT `fk_drivers_province` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`),
  CONSTRAINT `fk_drivers_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_drivers_vehicle_type` FOREIGN KEY (`vehicle_type_id`) REFERENCES `vehicle_types` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_drivers_verified_by` FOREIGN KEY (`verified_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `drivers_chk_1` CHECK (json_valid(`extra_phones`))
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drivers`
--

LOCK TABLES `drivers` WRITE;
/*!40000 ALTER TABLE `drivers` DISABLE KEYS */;
INSERT INTO `drivers` (`id`, `user_id`, `full_name`, `national_code`, `smart_card_number`, `vehicle_type_id`, `plate_number`, `model_year`, `color`, `capacity_kg`, `province_id`, `city_id`, `verification_status`, `verified_at`, `verified_by_user_id`, `reject_reason`, `rating_avg`, `rating_count`, `created_at`, `updated_at`, `deleted_at`, `license_serial`, `license_base`, `vin_number`, `insurance_number`, `insurance_expiry`, `engine_number`, `chassis_number`, `issued_from`, `address`, `home_phone`, `postal_code`, `extra_phones`) VALUES (26,63,'شایان نماینده','0312449348',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2026-08-01 21:34:11.703',NULL,NULL,0.00,0,'2026-08-01 21:34:11.703','2026-08-01 21:34:11.703',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(27,67,'رسول درویشی نیا','4190412147',NULL,2,'11 ب 111 ایران 11',NULL,NULL,NULL,NULL,NULL,1,'2026-08-06 16:17:04.678',NULL,NULL,0.00,0,'2026-08-06 16:17:04.678','2026-08-06 16:18:29.647',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(28,72,'شایان نماینده','0312449348',NULL,2,'32 م 568 ایران 55',NULL,NULL,NULL,NULL,NULL,1,'2026-09-20 17:23:52.000',1,NULL,0.00,0,'2026-08-22 11:04:21.446','2026-09-20 17:23:52.580',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'[\"\"]');
/*!40000 ALTER TABLE `drivers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `external_api_credentials`
--

DROP TABLE IF EXISTS `external_api_credentials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `external_api_credentials` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `provider_id` smallint unsigned NOT NULL,
  `env` tinyint unsigned NOT NULL DEFAULT '1',
  `status` tinyint unsigned NOT NULL DEFAULT '1',
  `key_id` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_key_enc` varbinary(1024) DEFAULT NULL,
  `api_secret_enc` varbinary(1024) DEFAULT NULL,
  `bearer_token_enc` varbinary(2048) DEFAULT NULL,
  `extra_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  `created_by_user_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_external_api_credentials_provider` (`provider_id`,`env`,`status`),
  KEY `fk_external_api_credentials_created_by` (`created_by_user_id`),
  CONSTRAINT `fk_external_api_credentials_created_by` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_external_api_credentials_provider` FOREIGN KEY (`provider_id`) REFERENCES `external_api_providers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `external_api_credentials_chk_1` CHECK (json_valid(`extra_json`))
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `external_api_credentials`
--

LOCK TABLES `external_api_credentials` WRITE;
/*!40000 ALTER TABLE `external_api_credentials` DISABLE KEYS */;
INSERT INTO `external_api_credentials` VALUES (1,1,3,1,'',_binary 'NHVuSC9wdWRqdTZNaFlWcFJmY1JGMW5Lbk1GZzR0anlhc0RsSUNWMWNudkNtZlFUOUZBSmpWZ3NEVDh6ZitFendXck9uZnlKdTd0OEVQaHlSQUljZEtmREErQ0VjZm9CM0ZXeVc3NTY3OWFMM3JQTW9acWZvK2hOempocDgwY0l3L0NCaEZiaTdjZ3pJWXVYaXlnb0tRPT06OvkR7SHL0X0Otu/1iWv2dKw=',_binary 'djZtdjZHMkJxN1RJLzFpZHVRQjN5R21sU2l3Sit4VjltMGErbm1xMksyZE83TlpLWTVrRmE0K2FTZFFTdGRtbFpybzRQdU02RDd2KzBKelhHb1JtenpIcnlxRmk3N1FzWkxKMGVoT2U3WUR2cGlIL1d1TFdYelRrQXBpN0pWM2MxVXF5L3c1M0Q2V09NaEhiUkxnRkZ3PT06OuNK3M+t/oLu+LCCXGMpweU=',_binary 'b1VrRWFCZWVCSmp3NGRSalI0ZzJsdkRSL0d5aHd3Z2NoT3JZREd2aDcwQnNUZEcyNFNJTVlybW91UHEycnl4QkpXOXE4YlVSenFZWENmVytGbExJZkwwZW1EWnpZRW9QY25iVnlZSmEwaTZZVzJKNFlwU2dCWHVnZXNKOENkZTcwaW9OUkt2RGFIQzl0M05vN2IrWDNBPT06OsisU9RtTI+ZdibrIhPDIJk=',NULL,'2026-01-13 23:00:34.439','2026-02-10 03:45:08.000',1);
/*!40000 ALTER TABLE `external_api_credentials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `external_api_providers`
--

DROP TABLE IF EXISTS `external_api_providers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `external_api_providers` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `base_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `docs_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auth_type` tinyint unsigned NOT NULL DEFAULT '1',
  `status` tinyint unsigned NOT NULL DEFAULT '1',
  `priority` smallint unsigned NOT NULL DEFAULT '100',
  `timeout_ms` int unsigned NOT NULL DEFAULT '8000',
  `retry_count` tinyint unsigned NOT NULL DEFAULT '1',
  `rate_limit_rpm` int unsigned DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_external_api_providers_slug` (`slug`),
  KEY `ix_external_api_providers_status` (`status`,`priority`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `external_api_providers`
--

LOCK TABLES `external_api_providers` WRITE;
/*!40000 ALTER TABLE `external_api_providers` DISABLE KEYS */;
INSERT INTO `external_api_providers` VALUES (1,'api_ir','api_ir','https://s.api.ir','https://s.api.ir/scalar/v1#tag/switch1',2,1,100,8000,1,NULL,'2026-01-13 23:00:06.689','2026-01-13 23:00:06.689');
/*!40000 ALTER TABLE `external_api_providers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `external_api_request_logs`
--

DROP TABLE IF EXISTS `external_api_request_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `external_api_request_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `provider_id` smallint unsigned NOT NULL,
  `credential_id` bigint unsigned DEFAULT NULL,
  `operation` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `http_method` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_id` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `http_status` smallint unsigned DEFAULT NULL,
  `latency_ms` int unsigned DEFAULT NULL,
  `error_code` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_redacted_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `response_redacted_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  KEY `ix_external_api_logs_provider_time` (`provider_id`,`created_at`),
  KEY `ix_external_api_logs_request` (`request_id`),
  KEY `fk_external_api_logs_cred` (`credential_id`),
  CONSTRAINT `fk_external_api_logs_cred` FOREIGN KEY (`credential_id`) REFERENCES `external_api_credentials` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_external_api_logs_provider` FOREIGN KEY (`provider_id`) REFERENCES `external_api_providers` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `external_api_request_logs_chk_1` CHECK (json_valid(`request_redacted_json`)),
  CONSTRAINT `external_api_request_logs_chk_2` CHECK (json_valid(`response_redacted_json`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `external_api_request_logs`
--

LOCK TABLES `external_api_request_logs` WRITE;
/*!40000 ALTER TABLE `external_api_request_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `external_api_request_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faq_categories`
--

DROP TABLE IF EXISTS `faq_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faq_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `target_app_id` tinyint unsigned NOT NULL COMMENT '1 = Driver app, 2 = Cargo-owner app',
  `title` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon_key` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `sort_order` int unsigned NOT NULL DEFAULT '100',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by_user_id` bigint unsigned DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  KEY `ix_faq_categories_app_active_sort` (`target_app_id`,`is_active`,`sort_order`,`id`),
  KEY `fk_faq_categories_creator` (`created_by_user_id`),
  CONSTRAINT `fk_faq_categories_creator` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faq_categories`
--

LOCK TABLES `faq_categories` WRITE;
/*!40000 ALTER TABLE `faq_categories` DISABLE KEYS */;
INSERT INTO `faq_categories` VALUES (1,1,'نصب','راهنمای دانلود برنامه، نسخه اندروید یا آیفون و...','install',1,1,1,'2026-08-30 22:00:08.162','2026-08-30 22:00:08.162'),(2,2,'نصب','راهنمای دانلود برنامه، نسخه آیفون و...','install',100,1,1,'2026-09-27 16:57:02.202','2026-09-27 16:57:02.202'),(3,2,'ورود و ثبت نام','دریافت کد، ثبت شماره جدید و...','auth',101,1,1,'2026-09-27 16:57:21.376','2026-09-27 16:57:21.376'),(4,2,'ثبت اعلام‌بار','اطلاعات اعلام‌بار، کرایه بار و...','search_load',102,1,1,'2026-09-27 16:58:04.525','2026-09-27 16:58:04.525'),(5,2,'بیمه','هزینه صدور بیمه، دریافت خسارت و...','insurance',103,1,1,'2026-09-27 16:58:42.703','2026-09-27 16:58:49.227'),(6,2,'احراز هویت','احراز هویت راننده، مدارک مورد نیاز و...','identity',104,1,1,'2026-09-27 16:59:19.342','2026-09-27 16:59:19.342'),(7,2,'اطلاعات کاربری','ویرایش اطلاعات، خروج از حساب و...','identity',105,1,1,'2026-09-27 16:59:48.106','2026-09-27 16:59:53.625'),(8,2,'پیام‌ها','بازخورد رانندگان، انصراف راننده و...','general',106,1,1,'2026-09-27 17:00:24.644','2026-09-27 17:00:24.644'),(9,2,'تاریخچه بارها','ویرایش اعلام‌بار، تعداد بازدید و...','history',107,1,1,'2026-09-27 17:00:55.338','2026-09-27 17:00:55.338'),(10,2,'دسترسی‌ها','راهنمای دسترسی ویدئو و میکروفن و...','general',108,1,1,'2026-09-27 17:01:26.916','2026-09-27 17:01:32.337');
/*!40000 ALTER TABLE `faq_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faq_items`
--

DROP TABLE IF EXISTS `faq_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faq_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `question` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_label` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_url` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_key` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_key` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_url` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int unsigned NOT NULL DEFAULT '100',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by_user_id` bigint unsigned DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  KEY `ix_faq_items_category_active_sort` (`category_id`,`is_active`,`sort_order`,`id`),
  KEY `fk_faq_items_creator` (`created_by_user_id`),
  CONSTRAINT `fk_faq_items_category` FOREIGN KEY (`category_id`) REFERENCES `faq_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_faq_items_creator` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faq_items`
--

LOCK TABLES `faq_items` WRITE;
/*!40000 ALTER TABLE `faq_items` DISABLE KEYS */;
INSERT INTO `faq_items` VALUES (1,1,'چگونه میتوانم برنامه آموت‌بار رانندگان را نصب کنم؟','تست','دانلود اپلیکیشن','https://amutapp.com/amutadmin/app.php',NULL,NULL,NULL,1,1,1,'2026-08-30 22:00:49.355','2026-09-27 17:06:46.454'),(2,2,'چگونه می‌توانم با گوشی آیفون از برنامه صاحبان کالا آموت‌بار استفاده کنم؟','اگر گوشی آیفون دارید، برای ثبت اعلام‌ بار خود باید از طریق سایت آموت بار اقدام کنید، ابتدا باید وارد سایت آموت بار بشوید.\r\nسپس به قسمت «اپلیکیشن صاحبان کالا» بروید. حال گزینه «نسخه وب اپلیکیشن» را انتخاب کرده و نسبت به ثبت بار خود از طریق این نسخه اقدام کنید.\r\nهمچنین می‌توانید مستقیما با ورود به آدرس https://amutapp.com/shipper/pwa نیز از برنامه استفاده کنید.','ورود به اپلیکیشن','https://amutapp.com/amutadmin/app.php',NULL,NULL,NULL,100,1,1,'2026-09-27 17:03:33.521','2026-09-27 17:06:31.279'),(3,2,'چگونه می‌توانم با گوشی اندروید از برنامه صاحبان کالا آموت‌بار استفاده کنم؟','اگر گوشی اندروید دارید، برای اعلام بار خود می‌توانید در مایکت، کافه بازار یا گوگل پلی «آموت بار صاحبان کالا» را جست و جو و نصب کنید.\r\nهمچنین می‌توانید از طریق سایت آموت بار نیز «برنامه آموت بار صاحبان کالا» را نصب کنید.\r\nابتدا باید وارد سایت آموت بار شوید. سپس به قسمت «اپلیکیشن صاحبان کالا» بروید. حال گزینه «نسخه وب اپلیکیشن» را انتخاب کرده و نسبت به ثبت بار خود از طریق این نسخه اقدام کنید.','دانلود اپلیکیشن','https://amutapp.com/amutadmin/app.php',NULL,NULL,NULL,101,1,1,'2026-09-27 17:05:12.843','2026-09-27 17:05:12.843'),(4,2,'آیا می‌توانم با کامپیوتر(سیستم عامل ویندوز) از برنامه آموت‌بار صاحبان کالا استفاده کنم؟','برای استفاده از برنامه «آموت بار صاحبان کالا» بر روی کامپیوتر، ابتدا باید وارد سایت آموت بار شوید.\r\nسپس به قسمت «اپلیکیشن صاحبان کالا» بروید. حال گزینه «نسخه وب اپلیکیشن» را انتخاب کرده و نسبت به ثبت بار خود از طریق این نسخه اقدام کنید.\r\nهمچنین می‌توانید مستقیما با ورود به آدرس https://amutapp.com/amutadmin/app.php نیز از برنامه استفاده کنید.','دانلود اپلیکیشن','https://amutapp.com/amutadmin/app.php',NULL,NULL,NULL,102,1,1,'2026-09-27 17:06:22.301','2026-09-27 17:06:22.301'),(5,3,'چگونه در برنامه صاحبان کالا آموت بار ثبت نام کنم؟','۱. وارد برنامه \"آموت بار صاحبان کالا\" شوید.\r\nدر صفحه اول شماره موبایلی که به نام خودتان است را وارد کنید.\r\n۲. در صفحه بعد کد تایید ارسال شده از سمت آموت بار را وارد کنید.\r\nدر قسمت بالای صفحه‌ می‌توانید شماره‌ای که پیامک برای آن ارسال می‌شود را مشاهده کنید\r\nبا انتخاب دکمه \"ویرایش شماره موبایل\" می توانید شماره موبایل خود را ویرایش کنید.\r\n۳. بعد از مطالعه قوانین و مقررات آموت بار، تیک قوانین مقررات را فعال کنید تا دکمه ثبت فعال شود. \r\nبا کلیک بر لینک قوانین و مقررات می‌توانید آن را مطالعه کنید.\r\nتوجه داشته باشید، در صورتی که تیک قوانین و مقررات را انتخاب نکرده باشید، دکمه \"ثبت\" غیرفعال خواهدماند. \r\n4.  در مرحله‌ی بعد توضیحات کلی ثبت نام را مشاهده می‌کنید که هر زمان شرایط برای شما فراهم بود می‌توانید دکمه شروع را انتخاب کنید و ثبت نام را انجام دهید.\r\n6. صفحه‌ی بعد وضعیت ثبت نام است که به شما نشان می‌دهد نتیجه‌ی هر مرحله از ثبت نام شما چگونه بوده است و برای مرحله‌ی بعد چه اقدامی باید انجام دهید.  \r\n7. در این صفحه باید کدملی‌ای که با شماره موبایل تطبیق دارد را وارد نمایید. \r\nدر صورتی که کدملی و شماره تلفن باهم تطبیق نداشت دکمه ویرایش شماره موبایل را انتخاب کنید و در صفحه شماره موبایل، آن را اصلاح کنید.\r\n۸. در صفحه‌ی بعد نیاز است که تاریخ تولد خود را وارد کنید.\r\nاز طریق اسکرول کردن سال، ماه و روز تاریخ تولد خود را انتخاب کنید.\r\nتوجه داشته باشید از آنجایی که سن قانونی 18 سال هست، افرادی که کمتر از 18 سال سن دارند اجازه فعالیت در آموت بار را ندارند.\r\n9. پس از وارد کردن تاریخ تولد، در این صفحه نتایج مرحله‌ی قبل را مشاهده می‌کنید و می‌توانید وارد مرحله‌ی بعد شوید.\r\n10. در صفحه‌ی بعد ویدیوی آموزش نحوه‌ی انجام احراز هویت ویدیویی را مشاهده کنید. \r\nپس از یک‌ بار مشاهده‌ی ویدیو، دکمه‌ی ادامه برای شما فعال می‌شود تا بتوانید وارد مرحله‌ی بعد شوید.\r\n۱۱. همانطور که در ویدیوی آموزشی توضیح داده شد،‌ در این بخش اقدام به ضبط ویدیو از خود کنید.\r\n در این مرحله باید چهره خود را داخل فریم دایره‌ای شکل قرار دهید و عبارتی که بعد از شروع ضبط رو صفحه قرار می‌گیرد را بلند بخوانید و سپس گزینه ارسال را انتخاب نمائید.\r\n۱۲. در این صفحه نشان می‌دهد که شما با موفقیت اطلاعات خود را ارسال کردید و باید منتظر بررسی نهایی آن باشید. \r\nنتیجه‌ی بررسی به شما از طریق پیامک و از داخل اپ اطلاع‌رسانی می‌شود.',NULL,NULL,NULL,NULL,NULL,100,1,1,'2026-09-27 17:08:26.367','2026-09-27 17:08:26.367');
/*!40000 ALTER TABLE `faq_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `field_settings`
--

DROP TABLE IF EXISTS `field_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `field_settings` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `field_key` varchar(96) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'user, driver, company',
  `field_label` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` smallint unsigned NOT NULL DEFAULT '100',
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_field_settings_key` (`field_key`,`entity_type`),
  KEY `ix_field_settings_entity` (`entity_type`,`is_active`,`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `field_settings`
--

LOCK TABLES `field_settings` WRITE;
/*!40000 ALTER TABLE `field_settings` DISABLE KEYS */;
INSERT INTO `field_settings` VALUES (1,'full_name','user','نام و نام خانوادگی',1,1,10,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(2,'phone','user','شماره موبایل',1,1,20,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(3,'code_meli','user','کد ملی',0,1,30,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(4,'birth_date','user','تاریخ تولد',0,1,40,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(5,'gender','user','جنسیت',0,1,50,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(6,'avatar','user','عکس پروفایل',0,1,60,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(7,'plate_number','driver','پلاک ماشین',1,1,10,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(8,'vehicle_type_id','driver','نوع وسیله نقلیه',1,1,20,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(9,'national_card_image','driver','عکس کارت ملی',1,1,30,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(10,'license_image','driver','عکس گواهینامه',1,1,40,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(11,'license_serial','driver','شماره گواهینامه',1,1,50,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(12,'license_base','driver','پایه گواهینامه',0,1,60,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(13,'vehicle_card_image','driver','عکس کارت ماشین',1,1,70,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(14,'green_card_image','driver','عکس برگه سبز',1,1,80,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(15,'insurance_image','driver','عکس بیمه نامه خودرو',1,1,90,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(16,'insurance_number','driver','شماره بیمه نامه',1,1,100,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(17,'insurance_expiry','driver','تاریخ اتمام بیمه',1,1,110,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(18,'vin_number','driver','شماره VIN',0,1,120,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(19,'engine_number','driver','شماره موتور',0,1,130,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(20,'chassis_number','driver','شماره شاسی',0,1,140,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(21,'issued_from','driver','صادره از',0,1,150,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(22,'address','driver','آدرس',0,1,160,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(23,'home_phone','driver','شماره منزل',0,1,170,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(24,'postal_code','driver','کد پستی',0,1,180,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(25,'extra_phones','driver','شماره موبایل اضافه',0,1,190,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(26,'verification_video','driver','ویدئو احراز هویت',0,1,200,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(27,'company_name','company','نام شرکت',1,1,10,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(28,'national_card_image','company','عکس کارت ملی',1,1,20,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(29,'registration_no','company','شماره ثبت',1,1,30,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(30,'registration_date','company','تاریخ ثبت',0,1,40,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(31,'economic_code','company','شماره اقتصادی',1,1,50,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(32,'address','company','آدرس',0,1,60,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860'),(33,'postal_code','company','کد پستی',0,1,70,'2026-02-08 21:19:12.860','2026-02-08 21:19:12.860');
/*!40000 ALTER TABLE `field_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `identity_verification_jobs`
--

DROP TABLE IF EXISTS `identity_verification_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `identity_verification_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `subject_user_id` bigint unsigned NOT NULL,
  `subject_kind` tinyint unsigned NOT NULL DEFAULT '0',
  `check_type` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_id` smallint unsigned NOT NULL,
  `credential_id` bigint unsigned DEFAULT NULL,
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1=pending,2=success,3=retryable,4=failed,5=canceled',
  `request_id` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_redacted_json` longtext COLLATE utf8mb4_unicode_ci,
  `response_redacted_json` longtext COLLATE utf8mb4_unicode_ci,
  `started_at` datetime(3) DEFAULT NULL,
  `finished_at` datetime(3) DEFAULT NULL,
  `created_by_user_id` bigint unsigned DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  KEY `idx_idv_jobs_user` (`subject_user_id`),
  KEY `idx_idv_jobs_provider` (`provider_id`),
  KEY `idx_idv_jobs_credential` (`credential_id`),
  KEY `idx_idv_jobs_status` (`status`),
  KEY `fk_idv_jobs_created_by` (`created_by_user_id`),
  CONSTRAINT `fk_idv_jobs_created_by` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_idv_jobs_credential` FOREIGN KEY (`credential_id`) REFERENCES `external_api_credentials` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_idv_jobs_provider` FOREIGN KEY (`provider_id`) REFERENCES `external_api_providers` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_idv_jobs_user` FOREIGN KEY (`subject_user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `identity_verification_jobs`
--

LOCK TABLES `identity_verification_jobs` WRITE;
/*!40000 ALTER TABLE `identity_verification_jobs` DISABLE KEYS */;
INSERT INTO `identity_verification_jobs` VALUES (7,66,2,'ShahkarLite',1,NULL,2,NULL,'{\"nationalCode\":\"[REDACTED]\",\"mobile\":\"[REDACTED]\",\"birthDate\":\"[REDACTED]\"}','{\"success\":true,\"code\":0,\"data\":true,\"error\":null,\"message\":\"استعلام انجام شد\"}','2026-09-20 16:22:27.704','2026-09-20 16:22:28.205',1,'2026-09-20 16:22:27.704'),(8,66,2,'Shahkar2',1,NULL,4,NULL,'{\"nationalCode\":\"[REDACTED]\",\"mobile\":\"[REDACTED]\",\"birthDate\":\"[REDACTED]\"}','{\"success\":false,\"error\":\"خطای HTTP: 404\"}','2026-09-20 16:22:42.194','2026-09-20 16:22:42.249',1,'2026-09-20 16:22:42.194'),(9,72,1,'ShahkarLite',1,NULL,2,NULL,'{\"nationalCode\":\"[REDACTED]\",\"mobile\":\"[REDACTED]\",\"birthDate\":\"[REDACTED]\",\"plateNumber\":\"32م568ایران55\"}','{\"success\":true,\"code\":0,\"data\":true,\"error\":null,\"message\":\"استعلام انجام شد\"}','2026-09-20 17:06:57.605','2026-09-20 17:06:59.146',1,'2026-09-20 17:06:57.605'),(10,67,1,'ShahkarLite',1,NULL,2,NULL,'{\"nationalCode\":\"[REDACTED]\",\"mobile\":\"[REDACTED]\",\"birthDate\":\"[REDACTED]\",\"plateNumber\":\"11ب111ایران11\"}','{\"success\":true,\"code\":0,\"data\":true,\"error\":null,\"message\":\"استعلام انجام شد\"}','2026-09-20 17:22:19.628','2026-09-20 17:22:20.190',1,'2026-09-20 17:22:19.628'),(11,72,1,'PersonData',1,NULL,4,NULL,'{\"nationalCode\":\"[REDACTED]\",\"mobile\":\"[REDACTED]\",\"birthDate\":\"[REDACTED]\",\"serialNumber\":\"[REDACTED]\",\"plateNumber\":\"32م568ایران55\"}','{\"success\":false,\"code\":\"provider_rejected\",\"request_id\":\"8ee2397cab77c08b81f9690b0234c088\"}','2026-09-20 17:23:59.281','2026-09-20 17:23:59.411',1,'2026-09-20 17:23:59.281'),(12,72,1,'Shahkar',1,NULL,2,NULL,'{\"nationalCode\":\"[REDACTED]\",\"mobile\":\"[REDACTED]\",\"birthDate\":\"[REDACTED]\",\"serialNumber\":\"[REDACTED]\",\"plateNumber\":\"32م568ایران55\"}','{\"success\":true,\"code\":0,\"data\":true,\"error\":null,\"message\":\"استعلام انجام شد\"}','2026-09-20 17:24:15.443','2026-09-20 17:24:16.617',1,'2026-09-20 17:24:15.443'),(13,72,1,'PersonData',1,NULL,4,NULL,'{\"nationalCode\":\"[REDACTED]\",\"mobile\":\"[REDACTED]\",\"birthDate\":\"[REDACTED]\",\"serialNumber\":\"[REDACTED]\",\"plateNumber\":\"32م568ایران55\"}','{\"success\":false,\"code\":\"provider_rejected\",\"request_id\":\"f5e572570a6a2303149a3eba57faa210\"}','2026-09-20 17:24:16.620','2026-09-20 17:24:16.716',1,'2026-09-20 17:24:16.620');
/*!40000 ALTER TABLE `identity_verification_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `identity_verification_services`
--

DROP TABLE IF EXISTS `identity_verification_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `identity_verification_services` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(96) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_kind` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '0=all, 1=driver, 2=company',
  `provider_id` smallint unsigned NOT NULL,
  `http_method` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'POST',
  `endpoint_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `config_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `is_active` tinyint unsigned NOT NULL DEFAULT '1',
  `sort_order` smallint unsigned NOT NULL DEFAULT '100',
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_identity_services_code` (`code`),
  KEY `idx_identity_services_provider` (`provider_id`),
  CONSTRAINT `fk_identity_services_provider` FOREIGN KEY (`provider_id`) REFERENCES `external_api_providers` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `identity_verification_services_chk_1` CHECK (json_valid(`config_json`))
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `identity_verification_services`
--

LOCK TABLES `identity_verification_services` WRITE;
/*!40000 ALTER TABLE `identity_verification_services` DISABLE KEYS */;
INSERT INTO `identity_verification_services` VALUES (1,'ShahkarLite','احراز هویت شاهکار Lite​','این وب سرویس نسخه Lite وب سرویس شاهکار است. شاهکار تطبیق کد ملی با شماره موبایل را استعلام می کند. تفاوت آن به نسخه اصلی شاهکار در قیمت و نوع ارسال دیتا است. در نسخه اصلی شاهکار دیتا به صورت رمز شده استعلام می گردد.',0,1,'POST','api/sw1/ShahkarLite','{\"required\": [\"mobile\", \"nationalCode\"]}',1,100,'2026-01-26 21:21:44.608','2026-01-26 21:22:25.469'),(2,'Shahkar','احراز هویت شاهکار','وب سرویس شاهکار تطبیق کد ملی با شماره موبایل را استعلام می نماید. در این نسخه دیتا به صورت رمز شده استعلام می گردد. همچنین امکان استعلام شناسه ملی و سیم کارت اشخاص حقوقی هم دارد',0,1,'POST','/api/sw1/Shahkar','{\"required\": [\"isCompany\", \"mobile\", \"nationalCode\"]}',1,100,'2026-02-10 03:49:16.645','2026-02-10 03:49:43.487'),(3,'Shahkar2','احراز هویت شاهکار 2​','این وب سرویس به شاهکار 2 است معروف است در شاهکار اصلی اطلاعات را در اپراتور ها استعلام می نماید ولی در شاهکار 2 استعلام در مرجع اپراتور ها و ثبت احوال تطبیق انجام می شود',0,1,'POST','/api/sw1/Shahkar2','{\"required\": [\"birthDate\", \"mobile\", \"nationalCode\"]}',1,100,'2026-02-10 03:47:11.072','2026-02-10 03:49:41.604'),(5,'PersonImage','استعلام عکس هویتی','این وب سرویس با دریافت کد ملی و تاریخ تولد عکس کارت ملی را از ثبت احوال استعلام می نماید. ارائه این سرویس به شرکت ها و سازمان ها با ارائه مجوز مقدور است.',0,1,'POST','/api/sw1/PersonImage','{\"required\": [\"mobile\", \"nationalCode\", \"serialNumber\"]}',1,100,'2026-02-10 03:51:43.175','2026-02-10 03:51:43.175'),(6,'VideoMatch','احراز ویدئویی بایومتریک​','احراز هویت بایومتریک، یا احراز هویت ویدئویی نوعی احراز هویت است که فیلم کوتاه از چهره فرد گرفته شده و با اطلاعات هویتی و تصویر کارت ملی وی مقایسه می گردد و در نهایت تطبیق آنها اعلام می شود',0,1,'POST','/api/sw1/VideoMatch','{\"defaults\": {\"matchingThreshold\": 90}, \"required\": [\"birthDate\", \"nationalCode\", \"serialNumber\", \"videoBase64\"]}',1,100,'2026-02-10 04:17:46.944','2026-02-10 04:18:25.468'),(7,'IPLocation','وب سرویس تشخیص موقعیت IP​','لوکیشن یک IP را بر می گرداند',0,1,'POST','/api/sw1/IPLocation','{\"required\": [\"ip\"]}',1,100,'2026-02-14 00:43:56.332','2026-02-14 00:44:09.842'),(8,'VideoVerify','احراز ویدئویی بایومتریک جامع + Live','احراز هویت بایومتریک +Live (VideoVerify): فیلم کوتاه از چهره فرد + زنده‌سنجی + تطبیق گفتار با متن اعلامی.',0,1,'POST','/api/sw1/VideoVerify','{\"defaults\": {\"speechThreshold\": 50, \"livenessThreshold\": 80, \"matchingThreshold\": 90}, \"required\": [\"birthDate\", \"nationalCode\", \"serialNumber\", \"speechText\", \"videoBase64\"]}',1,110,'2026-02-27 23:22:00.620','2026-02-27 23:22:14.867');
/*!40000 ALTER TABLE `identity_verification_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jwt_refresh_tokens`
--

DROP TABLE IF EXISTS `jwt_refresh_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jwt_refresh_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `jti_hash` char(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_id` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `platform` tinyint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `rotated_at` datetime(3) DEFAULT NULL,
  `revoked_at` datetime(3) DEFAULT NULL,
  `expires_at` datetime(3) NOT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_jrt_jti_hash` (`jti_hash`),
  KEY `ix_jrt_user_time` (`user_id`,`created_at`),
  KEY `ix_jrt_device_time` (`device_id`,`created_at`),
  KEY `ix_jrt_expires` (`expires_at`),
  KEY `fk_jrt_parent` (`parent_id`),
  CONSTRAINT `fk_jrt_parent` FOREIGN KEY (`parent_id`) REFERENCES `jwt_refresh_tokens` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_jrt_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jwt_refresh_tokens`
--

LOCK TABLES `jwt_refresh_tokens` WRITE;
/*!40000 ALTER TABLE `jwt_refresh_tokens` DISABLE KEYS */;
INSERT INTO `jwt_refresh_tokens` VALUES (60,56,'d129df223e132fecbb25ee6397be1a404ff37d42b469b01f5081003e19f1876c',NULL,NULL,'5.74.139.30','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,NULL,'2026-08-01 19:34:48.930','2026-10-30 19:03:41.000','2026-08-01 19:03:41.187'),(61,56,'d9b79f4b5328cadc26319e5425edaf4b5d2e2a630ae91389e4a3be43052aa36c','chrome|Win32|Google Inc.',3,'5.74.139.30','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,NULL,'2026-08-01 19:34:48.930','2026-10-30 19:29:23.000','2026-08-01 19:29:23.496'),(62,56,'55bd6707582597899e6a9f73f1f98c9b1a299d219f362523c2ff8e44da7928d8','chrome|Win32|Google Inc.',3,'5.74.139.30','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,NULL,'2026-08-01 19:34:48.930','2026-10-30 19:32:00.000','2026-08-01 19:32:00.968'),(63,57,'c284dac951c2a68e0aa7dabbc409258705fc8e7c8b695ea90c865c54811e4cf4','chrome|Win32|Google Inc.',3,'5.74.139.30','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,NULL,'2026-08-01 20:20:28.205','2026-10-30 19:35:13.000','2026-08-01 19:35:13.823'),(64,58,'344e12c23eebcc91a23669d4a9bb540eeafa9c7b15332ae4b4ce9cd1898a2058','chrome|Win32|Google Inc.',3,'5.74.139.30','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,NULL,NULL,'2026-10-30 20:20:47.000','2026-08-01 20:20:47.291'),(65,59,'860fd54ccfca9ef674e90e6da6f91978459545e112705bf4b15acd4be2d45d0b','chrome|Win32|Google Inc.',3,'5.74.139.30','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-08-01 21:09:08.984','2026-08-01 21:09:08.984','2026-10-30 20:53:55.000','2026-08-01 20:53:55.671'),(66,60,'bc1971570c5844c701da0ff869bd5966a25a0fae1987398bd895e435025a97cd','R16NW',1,'5.74.139.30','Dart/3.12 (dart:io)',NULL,NULL,'2026-08-01 21:02:39.154','2026-10-30 20:57:04.000','2026-08-01 20:57:04.422'),(67,61,'fa354a3bf232cd4b817be2a63eb43660a233732ac8e32983e91455606ea04ca5',NULL,NULL,'5.74.139.30','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0',NULL,NULL,'2026-08-01 21:30:01.978','2026-10-30 21:05:35.000','2026-08-01 21:05:35.230'),(68,59,'70950a060651b3aa187319d873db4bc93578ec168539cfb177abadbabdb85968','chrome|Win32|Google Inc.',3,'5.74.139.30','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',65,'2026-08-01 21:24:23.699','2026-08-01 21:24:23.699','2026-10-30 21:09:08.000','2026-08-01 21:09:08.987'),(69,59,'9c7acd2dd724ee3cd5c882a17d85d820642151e3f07650e08ce9caebc6159a25','chrome|Win32|Google Inc.',3,'5.74.139.30','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',68,NULL,NULL,'2026-10-30 21:24:23.000','2026-08-01 21:24:23.702'),(70,62,'7ebc5fa830edc06c289b5db5cd961a05dd40e3200ff971b51b5a0fdda1b009be','R16NW',1,'5.74.139.30','Dart/3.12 (dart:io)',NULL,NULL,'2026-08-01 21:32:27.685','2026-10-30 21:30:36.000','2026-08-01 21:30:36.037'),(71,62,'37d2a6a0bffe75a9b5ba2f453f7ba9cbe02e0ccd86dbeafa9b7dfe1e092cbf71',NULL,NULL,'5.74.139.30','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0',NULL,NULL,'2026-08-01 21:32:27.685','2026-10-30 21:32:00.000','2026-08-01 21:32:00.521'),(72,63,'70b32231fa6424290788a7ae80499a9d24081de0a241d5fc5514c23070278cb6',NULL,NULL,'5.74.139.30','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0',NULL,NULL,NULL,'2026-10-30 21:33:35.000','2026-08-01 21:33:35.299'),(73,63,'4346e193590f1755132a361ce1a9e3a0720c5d66ce0aaaad7ce989efce73f451',NULL,NULL,'5.74.139.30','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0',NULL,NULL,NULL,'2026-10-30 21:36:32.000','2026-08-01 21:36:32.958'),(74,64,'5566c837c7c42d1ce644caec88f18d3de27e9ded6043db8b4afd1488ad066b3a','R16NW',1,'93.118.127.162','Dart/3.12 (dart:io)',NULL,'2026-08-06 16:29:04.970','2026-08-06 16:29:04.970','2026-11-04 15:58:34.000','2026-08-06 15:58:34.901'),(75,65,'faf4102103e3c3df634ad1a3eca32fc0306718593e613b85e1ea02158a6f415d','R16NW',1,'93.118.127.162','Dart/3.12 (dart:io)',NULL,'2026-08-06 16:28:54.224','2026-08-06 16:28:54.224','2026-11-04 16:06:32.000','2026-08-06 16:06:32.130'),(76,66,'ad181cfbc99a3b49312a34346338150c5161f08e1ba2d4aaa7b0323d63482896','BP4A.251205.006',1,'46.51.97.12','Dart/3.12 (dart:io)',NULL,'2026-08-06 16:30:59.991','2026-08-06 16:30:59.991','2026-11-04 16:06:51.000','2026-08-06 16:06:51.813'),(77,67,'b2d79bdcb3afccab70fca0354a59dac9bdaf441eaf6f280c507444eb5f5b8611','SP1A.210812.016',1,'5.215.203.227','Dart/3.12 (dart:io)',NULL,'2026-08-06 22:43:14.991','2026-08-06 22:43:14.991','2026-11-04 16:16:34.000','2026-08-06 16:16:34.689'),(78,65,'ff2d02213387e8634a8fd5287f060ddf35fd167445cec8b4cfc1dccc8ead9098','R16NW',1,'93.118.127.162','Dart/3.12 (dart:io)',75,'2026-08-06 18:07:30.848','2026-08-06 18:07:30.848','2026-11-04 16:28:54.000','2026-08-06 16:28:54.227'),(79,64,'811690116d92ec4caa38f39c4a2975ce1f828abd20542c3d08928166061eafc6','R16NW',1,'93.118.127.162','Dart/3.12 (dart:io)',74,NULL,'2026-08-22 11:04:19.313','2026-11-04 16:29:04.000','2026-08-06 16:29:04.974'),(80,66,'140e147f97d69c6fe4de42cfbe45a0b275698e8c52fa8b79ac20370b84f62b66','BP4A.251205.006',1,'46.51.97.12','Dart/3.12 (dart:io)',76,'2026-08-16 13:41:06.464','2026-08-16 13:41:06.464','2026-11-04 16:30:59.000','2026-08-06 16:30:59.994'),(81,65,'2cc1910a385ad02cbc39ac9fe1d5dd314ef95b91acdfd2bd8966ec0d7dc416de','R16NW',1,'89.198.8.78','Dart/3.12 (dart:io)',78,'2026-08-12 11:33:22.759','2026-08-12 11:33:22.759','2026-11-04 18:07:30.000','2026-08-06 18:07:30.851'),(82,67,'69af127b3eb8eb70126f15a1a6cebdf1872a6281f2871d40078867064ab9f332','SP1A.210812.016',1,'98.96.253.137','Dart/3.12 (dart:io)',77,'2026-08-17 13:15:07.710','2026-08-17 13:15:07.710','2026-11-04 22:43:14.000','2026-08-06 22:43:14.994'),(83,65,'0950efceb0d04853566bcea824ead3390a7894387ce0f7ed5d69c22fda3420eb','R16NW',1,'2.190.233.211','Dart/3.12 (dart:io)',81,'2026-08-22 11:02:20.437','2026-08-22 11:02:20.437','2026-11-10 11:33:22.000','2026-08-12 11:33:22.762'),(84,66,'2017b90a2b83a1cb1aa7294836e128591255b4d91ef8acf3835de60ccd0028f9','BP4A.251205.006',1,'46.164.106.206','Dart/3.12 (dart:io)',80,'2026-08-16 16:08:21.589','2026-08-16 16:08:21.589','2026-11-14 13:41:06.000','2026-08-16 13:41:06.467'),(85,66,'04fdeb174ba32389e39e6c61c8f9a3eabdd8f4bbff6ab1fa160941c8f061875b','BP4A.251205.006',1,'46.164.106.206','Dart/3.12 (dart:io)',84,NULL,NULL,'2026-11-14 16:08:21.000','2026-08-16 16:08:21.593'),(86,68,'ffadd576999b2c799f243bb1f37af888b25450c7c0e9f1ef0e05455d495684f5','SP1A.210812.016',1,'2.176.251.101','Dart/3.12 (dart:io)',NULL,'2026-08-17 12:51:39.992','2026-08-17 12:51:39.992','2026-11-15 12:08:20.000','2026-08-17 12:08:20.036'),(87,68,'50bf97afc80ad6e18430edc81ce1583bc818fbab0a137ffbb8da84cd0fae21eb','SP1A.210812.016',1,'2.176.251.101','Dart/3.12 (dart:io)',86,NULL,NULL,'2026-11-15 12:51:39.000','2026-08-17 12:51:39.996'),(88,67,'f9a53788837556a68143f148b6abf3e64c4704a780f5a973cb4ec764b72a8f64','SP1A.210812.016',1,'98.96.254.43','Dart/3.12 (dart:io)',82,NULL,NULL,'2026-11-15 13:15:07.000','2026-08-17 13:15:07.713'),(89,69,'f82306d4ef6fd4d2725a11177a757ae059099fd13a2e6582a0ba5b071ec95114','SP1A.210812.016',1,'2.176.251.101','Dart/3.12 (dart:io)',NULL,'2026-08-17 13:40:09.120','2026-08-17 13:40:09.120','2026-11-15 13:24:53.000','2026-08-17 13:24:53.241'),(90,70,'06ff09db7e9a62af0d0d467d1d44ec136451467c9543a7eb7c5e7d55f8bf8832','SP1A.210812.016',1,'98.96.254.43','Dart/3.12 (dart:io)',NULL,NULL,NULL,'2026-11-15 13:37:39.000','2026-08-17 13:37:39.350'),(91,69,'1a263bc6979c31e13205d15621b406d081bc26f2a260cea7cf886b405ad289bc','SP1A.210812.016',1,'2.176.251.101','Dart/3.12 (dart:io)',89,NULL,NULL,'2026-11-15 13:40:09.000','2026-08-17 13:40:09.124'),(92,71,'9823a35a7ee27aa52dadec951d6179bbbb378cec68a0f6cc592cc98ae4d7e92b','SP1A.210812.016',1,'2.176.251.101','Dart/3.12 (dart:io)',NULL,NULL,NULL,'2026-11-15 13:43:05.000','2026-08-17 13:43:05.039'),(93,65,'36a6d49219ec748ff236468261ec74c0fceac64f7826aa0c36404b5b254e17ac','R16NW',1,'104.28.217.89','Dart/3.12 (dart:io)',83,NULL,'2026-08-22 11:03:37.338','2026-11-20 11:02:20.000','2026-08-22 11:02:20.441'),(94,72,'0a5146a53492693fecf1e548428499b614351bfd730c3ac606141d62c36b509f','R16NW',1,'2.176.231.47','Dart/3.12 (dart:io)',NULL,'2026-08-22 13:57:09.831','2026-08-22 13:57:09.831','2026-11-20 11:03:57.000','2026-08-22 11:03:57.205'),(95,72,'ee6e13784f328f5ff635f4c0948990cb275e8ded9ca8de9ac1fecfa85ac740ae','R16NW',1,'104.28.217.88','Dart/3.12 (dart:io)',94,NULL,NULL,'2026-11-20 13:57:09.000','2026-08-22 13:57:09.834');
/*!40000 ALTER TABLE `jwt_refresh_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `load_applications`
--

DROP TABLE IF EXISTS `load_applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `load_applications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `load_id` bigint unsigned NOT NULL,
  `driver_id` bigint unsigned NOT NULL,
  `status` tinyint unsigned NOT NULL DEFAULT '1',
  `offered_price` decimal(15,2) DEFAULT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_load_app_unique` (`load_id`,`driver_id`),
  KEY `ix_load_app_load_status` (`load_id`,`status`,`created_at`),
  KEY `ix_load_app_driver_status` (`driver_id`,`status`,`created_at`),
  CONSTRAINT `fk_load_app_driver` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_load_app_load` FOREIGN KEY (`load_id`) REFERENCES `loads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `load_applications`
--

LOCK TABLES `load_applications` WRITE;
/*!40000 ALTER TABLE `load_applications` DISABLE KEYS */;
/*!40000 ALTER TABLE `load_applications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `load_status_events`
--

DROP TABLE IF EXISTS `load_status_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `load_status_events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `load_id` bigint unsigned NOT NULL,
  `old_status` tinyint unsigned DEFAULT NULL,
  `new_status` tinyint unsigned NOT NULL,
  `actor_user_id` bigint unsigned DEFAULT NULL,
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  KEY `ix_load_status_events_load_time` (`load_id`,`created_at`),
  KEY `fk_load_status_events_actor` (`actor_user_id`),
  CONSTRAINT `fk_load_status_events_actor` FOREIGN KEY (`actor_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_load_status_events_load` FOREIGN KEY (`load_id`) REFERENCES `loads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `load_status_events`
--

LOCK TABLES `load_status_events` WRITE;
/*!40000 ALTER TABLE `load_status_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `load_status_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `load_vehicle_types`
--

DROP TABLE IF EXISTS `load_vehicle_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `load_vehicle_types` (
  `load_id` bigint unsigned NOT NULL,
  `vehicle_type_id` smallint unsigned NOT NULL,
  PRIMARY KEY (`load_id`,`vehicle_type_id`),
  KEY `ix_lvt_vehicle` (`vehicle_type_id`),
  CONSTRAINT `fk_lvt_load` FOREIGN KEY (`load_id`) REFERENCES `loads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_lvt_vehicle` FOREIGN KEY (`vehicle_type_id`) REFERENCES `vehicle_types` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `load_vehicle_types`
--

LOCK TABLES `load_vehicle_types` WRITE;
/*!40000 ALTER TABLE `load_vehicle_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `load_vehicle_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loads`
--

DROP TABLE IF EXISTS `loads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `public_code` char(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` bigint unsigned NOT NULL,
  `created_by_user_id` bigint unsigned NOT NULL,
  `phone_coordination` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `load_status` tinyint unsigned NOT NULL DEFAULT '1',
  `load_type` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1=دربستی, 2=روباری',
  `title` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargo_type_id` int unsigned DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `weight_kg` decimal(10,2) DEFAULT NULL,
  `is_tonnage_free` tinyint(1) NOT NULL DEFAULT '0',
  `volume_m3` decimal(10,2) DEFAULT NULL,
  `package_count` int unsigned DEFAULT NULL,
  `origin_province_id` int unsigned NOT NULL,
  `origin_city_id` bigint unsigned NOT NULL,
  `origin_address` text COLLATE utf8mb4_unicode_ci,
  `origin_lat` decimal(10,7) DEFAULT NULL,
  `origin_lng` decimal(10,7) DEFAULT NULL,
  `origin_geohash` char(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dest_province_id` int unsigned NOT NULL,
  `dest_city_id` bigint unsigned NOT NULL,
  `dest_address` text COLLATE utf8mb4_unicode_ci,
  `dest_lat` decimal(10,7) DEFAULT NULL,
  `dest_lng` decimal(10,7) DEFAULT NULL,
  `dest_geohash` char(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pickup_window_start` datetime(3) DEFAULT NULL,
  `pickup_window_end` datetime(3) DEFAULT NULL,
  `delivery_window_start` datetime(3) DEFAULT NULL,
  `delivery_window_end` datetime(3) DEFAULT NULL,
  `price_type` tinyint unsigned NOT NULL DEFAULT '1',
  `proposed_price` decimal(15,2) DEFAULT NULL,
  `primary_vehicle_type_id` smallint unsigned NOT NULL,
  `assigned_driver_id` bigint unsigned DEFAULT NULL,
  `published_at` datetime(3) DEFAULT NULL,
  `assigned_at` datetime(3) DEFAULT NULL,
  `delivered_at` datetime(3) DEFAULT NULL,
  `cancelled_at` datetime(3) DEFAULT NULL,
  `expires_at` datetime(3) DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  `deleted_at` datetime(3) DEFAULT NULL,
  `has_insurance` tinyint(1) NOT NULL DEFAULT '0',
  `insurance_value` decimal(15,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_loads_public_code` (`public_code`),
  KEY `ix_loads_company_status_time` (`company_id`,`load_status`,`created_at`),
  KEY `ix_loads_status_published` (`load_status`,`published_at`),
  KEY `ix_loads_route_vehicle_status_pub` (`origin_city_id`,`dest_city_id`,`primary_vehicle_type_id`,`load_status`,`published_at`),
  KEY `ix_loads_geo_vehicle_status_pub` (`origin_geohash`,`load_status`,`primary_vehicle_type_id`,`published_at`),
  KEY `ix_loads_assigned_driver` (`assigned_driver_id`,`load_status`),
  KEY `fk_loads_created_by` (`created_by_user_id`),
  KEY `fk_loads_primary_vehicle` (`primary_vehicle_type_id`),
  KEY `fk_loads_dest_province` (`dest_province_id`),
  KEY `fk_loads_origin_province` (`origin_province_id`),
  KEY `fk_loads_dest_city` (`dest_city_id`),
  KEY `fk_loads_cargos_list` (`cargo_type_id`),
  CONSTRAINT `fk_loads_assigned_driver` FOREIGN KEY (`assigned_driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_loads_cargos_list` FOREIGN KEY (`cargo_type_id`) REFERENCES `cargos_list` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_loads_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_loads_created_by` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_loads_dest_city` FOREIGN KEY (`dest_city_id`) REFERENCES `cities` (`id`),
  CONSTRAINT `fk_loads_dest_province` FOREIGN KEY (`dest_province_id`) REFERENCES `provinces` (`id`),
  CONSTRAINT `fk_loads_origin_city` FOREIGN KEY (`origin_city_id`) REFERENCES `cities` (`id`),
  CONSTRAINT `fk_loads_origin_province` FOREIGN KEY (`origin_province_id`) REFERENCES `provinces` (`id`),
  CONSTRAINT `fk_loads_primary_vehicle` FOREIGN KEY (`primary_vehicle_type_id`) REFERENCES `vehicle_types` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loads`
--

LOCK TABLES `loads` WRITE;
/*!40000 ALTER TABLE `loads` DISABLE KEYS */;
INSERT INTO `loads` VALUES (9,'358597326497',1,1,'09351794610',1,1,NULL,121,'تست',1.00,0,NULL,NULL,123,1230001001576,'تهران ای',NULL,NULL,NULL,103,1030006001223,'مراغه ای',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,5000000.00,1,NULL,'2026-05-18 21:14:38.982',NULL,NULL,NULL,NULL,'2026-05-18 21:14:38.982','2026-05-18 21:15:06.368',NULL,1,10000000.00),(10,'300772862700',7,59,'09129248289',1,1,NULL,73,'سلام تسته',1.00,0,NULL,NULL,123,1230001001576,NULL,NULL,NULL,NULL,109,10900016001392,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,5000000.00,2,NULL,'2026-08-01 20:55:10.482',NULL,NULL,NULL,NULL,'2026-08-01 20:55:10.482','2026-08-01 20:55:10.482',NULL,0,NULL),(11,'489035311573',9,66,'09121407123',1,1,NULL,121,'بار کیسه مواد خوراکی',1.00,0,NULL,NULL,123,1230001001576,NULL,NULL,NULL,NULL,110,1100002001406,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,3800000.00,2,NULL,'2026-08-06 16:12:58.922',NULL,NULL,NULL,NULL,'2026-08-06 16:12:58.922','2026-08-06 16:12:58.922',NULL,0,NULL),(12,'885502099707',10,68,'09192999873',1,1,NULL,6,NULL,1.00,0,NULL,NULL,123,1230001001576,NULL,NULL,NULL,NULL,110,1100002001406,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,4500000.00,1,NULL,'2026-08-17 12:58:09.246',NULL,NULL,NULL,NULL,'2026-08-17 12:58:09.246','2026-08-17 12:58:09.246',NULL,0,NULL);
/*!40000 ALTER TABLE `loads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `conversation_id` bigint unsigned NOT NULL,
  `sender_user_id` bigint unsigned NOT NULL,
  `message_type` tinyint unsigned NOT NULL DEFAULT '1',
  `body` text COLLATE utf8mb4_unicode_ci,
  `attachment_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `delivered_at` datetime(3) DEFAULT NULL,
  `seen_at` datetime(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_messages_conversation_id` (`conversation_id`,`id`),
  KEY `ix_messages_sender_time` (`sender_user_id`,`created_at`),
  CONSTRAINT `fk_messages_conversation` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_messages_sender` FOREIGN KEY (`sender_user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migration_history`
--

DROP TABLE IF EXISTS `migration_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migration_history` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `applied_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `migration_name` (`migration_name`),
  KEY `idx_migration_name` (`migration_name`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration_history`
--

LOCK TABLES `migration_history` WRITE;
/*!40000 ALTER TABLE `migration_history` DISABLE KEYS */;
INSERT INTO `migration_history` VALUES (1,'2026_07_08_000001_create_driver_activity_logs.sql','2026-10-11 15:44:57'),(2,'2026_07_30_000002_allow_company_location_onboarding.sql','2026-10-11 15:45:21'),(3,'2026_07_30_000003_add_ticket_attachment_columns.sql','2026-10-11 15:45:21'),(4,'2026_07_30_000004_expand_admin_audit_ip.sql','2026-10-11 15:45:21'),(5,'2026_08_01_000005_allow_archived_national_code_reuse.sql','2026-10-11 15:45:59'),(6,'2026_08_30_000006_add_user_app_roles.sql','2026-10-11 15:45:59'),(7,'2026_08_30_000007_create_faqs.sql','2026-10-11 15:45:59'),(8,'2026_09_20_000006_enable_shared_app_accounts.sql','2026-10-11 15:45:59'),(9,'2026_09_20_000007_video_verification_challenges.sql','2026-10-11 15:45:59'),(10,'2026_09_27_000008_create_faq_tables.sql','2026-10-11 15:45:59'),(11,'2026_10_13_000009_create_push_notification_tables.sql','2026-10-13 16:58:15');
/*!40000 ALTER TABLE `migration_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `type` varchar(48) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `read_at` datetime(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_notifications_user_read_time` (`user_id`,`is_read`,`created_at`),
  CONSTRAINT `fk_notifications_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `notifications_chk_1` CHECK (json_valid(`data_json`))
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `otp_codes`
--

DROP TABLE IF EXISTS `otp_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `otp_codes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `phone` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purpose` tinyint unsigned NOT NULL,
  `code_hash` char(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` datetime(3) NOT NULL,
  `consumed_at` datetime(3) DEFAULT NULL,
  `attempt_count` tinyint unsigned NOT NULL DEFAULT '0',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  KEY `ix_otp_phone_purpose` (`phone`,`purpose`,`created_at`),
  KEY `ix_otp_expires` (`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `otp_codes`
--

LOCK TABLES `otp_codes` WRITE;
/*!40000 ALTER TABLE `otp_codes` DISABLE KEYS */;
INSERT INTO `otp_codes` VALUES (26,'09129248289',1,'fedb1e89b97362b9223a07bf3fcc136f339bc340779111188ec2bbec6660a838','2026-05-11 18:58:36.085','2026-05-11 18:53:51.942',0,'192.168.100.33','2026-05-11 18:53:36.085'),(27,'09129248289',1,'996628b307bfad3f48493a5a176c9e8c8bf5d526ca56f2a3eec278bffefc10e4','2026-05-11 19:43:41.809','2026-05-11 19:39:15.558',0,'192.168.100.33','2026-05-11 19:38:41.809'),(28,'09129248289',1,'039476d0bc5ae097ebd5450a509c6d19ae3f8c19f89e3813e479ff63f13c7758','2026-05-13 18:19:57.543',NULL,0,'127.0.0.1','2026-05-13 18:14:57.543'),(29,'09129248289',1,'f392fd9e4b462d50b1f4c5b40bcd99b27744a79796867e37d2843bc840f6a85a','2026-05-13 18:22:37.833',NULL,0,'127.0.0.1','2026-05-13 18:17:37.833'),(30,'09129248289',1,'f86bc33297963f951581e964f81ea4cc86e89787870e14bf8502e59173247a98','2026-05-13 18:23:44.743',NULL,0,'127.0.0.1','2026-05-13 18:18:44.743'),(31,'09129248289',1,'d896a96754aab58926e8ceb6062ae5ae168c05c7b5b3bba8658d3dc4d1f6f7a3','2026-05-13 18:28:36.802',NULL,0,'127.0.0.1','2026-05-13 18:23:36.802'),(32,'09351794610',1,'e2195fff422e28fbc28d829a0cf9d35cb4ce33e013c6f5d5ba9c4a2a13f06db4','2026-05-13 18:29:31.024','2026-05-13 18:24:42.391',0,'127.0.0.1','2026-05-13 18:24:31.024'),(33,'09129248289',1,'f9ff193ad955fff0588e140a51e7744970fb007627312d74b0734b1c8c113935','2026-05-13 18:30:05.718',NULL,0,'127.0.0.1','2026-05-13 18:25:05.718'),(34,'09121407123',1,'3fb0224c81cfe8821e30eea9c9b9dd9770d7f0bb148465f25e654f63544f1e59','2026-05-13 18:31:28.432',NULL,0,'127.0.0.1','2026-05-13 18:26:28.432'),(35,'09120654653',1,'69069954cb00ab1f42c55c0b4f7fa232f60fce2c06ccc3f722706793a8422b55','2026-05-13 18:32:01.797','2026-05-13 18:27:11.457',0,'127.0.0.1','2026-05-13 18:27:01.797'),(36,'09192999719',1,'7c86f6cc26888c5b2e4fc030fa3caff9f6cf495fc50717d1ea1c1ca94f78aaaf','2026-05-16 16:26:01.032',NULL,0,'127.0.0.1','2026-05-16 16:21:01.032'),(37,'09192999719',1,'137b1c5805f3ca67a28d293ae2e7df6fda78f96497711ca077a2acfeedac5500','2026-05-16 16:27:07.815',NULL,0,'127.0.0.1','2026-05-16 16:22:07.815'),(38,'09351794610',1,'a5ad045204c0b9146bae08ef95f8d9cb8c9a3895cd6aaba70f81565b539faca3','2026-05-16 16:42:40.408',NULL,0,'127.0.0.1','2026-05-16 16:37:40.408'),(39,'09351794610',1,'042ff8d043b17f7fdbf691619861a29379a7bee5465067024b968c83797308ea','2026-05-16 16:55:13.439',NULL,0,'127.0.0.1','2026-05-16 16:50:13.439'),(40,'09351794610',1,'388248416d4df799bd4b4c54187956de9de509a921822d0ef41b904c7f0eb340','2026-05-16 18:14:28.634',NULL,0,'127.0.0.1','2026-05-16 18:09:28.634'),(41,'09351794610',1,'ef3fc3fa99184a1224c795a52dd6ef01e3e22f78075f0551212fec2dec3467e0','2026-05-16 18:29:33.111',NULL,0,'127.0.0.1','2026-05-16 18:24:33.111'),(42,'09351794610',1,'e6a00c1a79df2d778b3834f0d09bdd5985684c073c54187bf5cdb8497d3e9a6a','2026-05-16 18:33:40.817','2026-05-16 18:28:59.277',0,'127.0.0.1','2026-05-16 18:28:40.817'),(43,'09351794610',1,'b126f9ee9a1834d81be129046d27bac2cb54265c96ac3fe678dc1b8795cdc050','2026-05-16 18:51:33.323',NULL,0,'127.0.0.1','2026-05-16 18:46:33.323'),(44,'09351794610',1,'8dd3b9005266ce391a7dd4ae8525f130fd6f5be0c150c1df8de59eaec1771cb8','2026-05-16 19:07:46.119',NULL,0,'127.0.0.1','2026-05-16 19:05:46.119'),(45,'09351794610',1,'762dda799fe7dcd466851274a9b498e40da8f97efb5ab2c01c371896affb45d3','2026-05-16 19:15:55.168',NULL,0,'127.0.0.1','2026-05-16 19:13:55.168'),(46,'09351794610',1,'d691241373ba697b70c568ff30fe97f7895857d3f45acb05167ce38ea744ecf5','2026-05-16 19:22:32.027',NULL,0,'127.0.0.1','2026-05-16 19:20:32.027'),(47,'09351794610',1,'51b7c3627a66c15029686c961a5fc1f3914b06b64c402d7e0c61c04ceb43d127','2026-05-16 20:00:10.690',NULL,0,'127.0.0.1','2026-05-16 19:58:10.690'),(48,'09120654653',1,'773544dc0dbf4fd5ebb1c59c00f7ed95ea3413d15f754ae8c2528f2d39f54b76','2026-05-16 20:19:47.541',NULL,0,'127.0.0.1','2026-05-16 20:17:47.541'),(49,'09120654653',1,'fb855d9606c0af6ae107e6ac9fb3039474e395cea2777ad5f32d4a1a60b85e48','2026-05-16 20:23:04.626','2026-05-16 20:21:26.842',0,'127.0.0.1','2026-05-16 20:21:04.626'),(50,'09129248289',1,'62fcacac7d845f92264f266dd85a8a6e54080c8e5a323900f79edcc047765542','2026-05-16 20:39:21.673','2026-05-16 20:37:39.281',0,'127.0.0.1','2026-05-16 20:37:21.673'),(51,'09129248289',1,'1aaa1fd657b5d60f57c83d292a13fe3b4bcc3d01c62e62fc0d6abd9d4cc6e62e','2026-05-16 21:21:02.403','2026-05-16 21:19:20.063',0,'127.0.0.1','2026-05-16 21:19:02.403'),(52,'09129248289',1,'2f2d93970c4340380edcb64e5f0a82f45256195b53b0e8fc3fae597b8d3ffd5c','2026-05-18 16:32:00.079','2026-05-18 16:30:17.815',0,'127.0.0.1','2026-05-18 16:30:00.079'),(53,'09129248289',1,'4009dc94c5477add24a2c5c00655d7fbbb8ce6f2ee8fb5fee1a8b99fced450a2','2026-07-17 22:21:04.515','2026-07-17 22:19:12.129',0,'204.18.109.91','2026-07-17 22:19:04.515'),(54,'09129248289',1,'df783e70e6de489375d06a155d1010fe4aa4b44feb3361446b0c8ef5562edd88','2026-07-17 22:39:01.372','2026-07-17 22:37:10.960',0,'204.18.109.91','2026-07-17 22:37:01.372'),(55,'09192994069',1,'c885da0b7117a976f448c23c759e825085ebd8e1cce100d01d11aea15b1335fe','2026-07-21 14:32:57.884','2026-07-21 14:31:15.632',0,'93.118.127.162','2026-07-21 14:30:57.884'),(56,'09129248289',1,'e62fa91546efbcc898bbc532c762f9c51bb41dac4c58fff3371003a14cd47cc9','2026-08-01 19:05:30.511','2026-08-01 19:03:41.174',0,'J?','2026-08-01 19:03:30.511'),(57,'09129248289',1,'404af415fd0dab63c31ed5688d9e17614b0dcfebd49e03d2a4d023eac4374234','2026-08-01 19:31:09.984','2026-08-01 19:29:23.488',0,'J?','2026-08-01 19:29:09.984'),(58,'09129248289',1,'0b8c184aff95d30a95b9eb1387279f6da60d6af5d5a003114aff4bcc9a0d7787','2026-08-01 19:33:52.662','2026-08-01 19:32:00.961',0,'J?','2026-08-01 19:31:52.662'),(59,'09129248289',1,'276aff1065c2696ee30de5013a6d2f2a6ea15cfc110807dd330f98fd39281fda','2026-08-01 19:37:04.516','2026-08-01 19:35:13.809',0,'J?','2026-08-01 19:35:04.516'),(60,'09129248289',1,'4ad1899957e7f1488776d8ba497880288fa063c67c397312c015a496a31f1d93','2026-08-01 20:22:30.666','2026-08-01 20:20:47.276',0,'J?','2026-08-01 20:20:30.666'),(61,'09129248289',1,'d496061c746a55d17a58fc9d0e693a5f67f0cf86f6e985ae977215874bfb3d67','2026-08-01 20:55:45.540','2026-08-01 20:53:55.659',0,'J?','2026-08-01 20:53:45.540'),(62,'09351794610',1,'60c96cdf229bd9b17b84287f55572e49eeccbabe33d4ec0d30444e960407f98b','2026-08-01 20:57:27.942','2026-08-01 20:56:17.436',0,'J?','2026-08-01 20:55:27.942'),(63,'09125805766',1,'db01f46e355a59a6dd9610059cf722fcab294cadfb7e74c5d914ead9e42518d6','2026-08-01 20:58:29.366','2026-08-01 20:57:04.410',0,'J?','2026-08-01 20:56:29.366'),(64,'09351794610',1,'e96a66569571535c45d5c02f35474c4da135ee4a222d71d692edc0c6f313163b','2026-08-01 21:07:22.602','2026-08-01 21:05:35.216',0,'J?','2026-08-01 21:05:22.602'),(65,'09351794610',1,'02a1a685251d491bba8a58a80dd6a01fbff7a2d4380117719fb6df3e72c57790','2026-08-01 21:32:09.671','2026-08-01 21:30:36.025',0,'J?','2026-08-01 21:30:09.671'),(66,'09351794610',1,'e3c0e0fb683f24a469bab9d8a62410de376673476b11520d5788577321f936e3','2026-08-01 21:33:50.400','2026-08-01 21:32:00.514',0,'J?','2026-08-01 21:31:50.400'),(67,'09351794610',1,'f63c39a16b514228a2360d6ba5d842da7b0ecb5d6cd4d4145c0acdef9de8dff3','2026-08-01 21:35:21.756','2026-08-01 21:33:35.287',0,'J?','2026-08-01 21:33:21.756'),(68,'09351794610',1,'fd8f255778b720f3044e402b4e1f6bc6358a3d5e6a10e2275a0dfb6a1203ce95','2026-08-01 21:38:16.759','2026-08-01 21:36:32.950',0,'J?','2026-08-01 21:36:16.759'),(69,'09129248289',1,'e03a4c9f86a0fb4efc69559c02b2e6b06effa7b6e1cea877bd24e3d2ca19d3cb','2026-08-06 16:00:25.531','2026-08-06 15:58:34.886',0,']v?','2026-08-06 15:58:25.531'),(70,'09351794610',1,'05e4cad74476f12afdf0f34df688c51219823b2fd8ac44acc71f8e619734e312','2026-08-06 16:07:31.832','2026-08-06 16:06:32.118',0,']v?','2026-08-06 16:05:31.832'),(71,'09120654653',1,'a5e9cda8af2e6384e2b69f6e40287b1a9c459a34c2b923c8dd570369b9f8a280','2026-08-06 16:08:34.397','2026-08-06 16:06:51.790',0,'.3a','2026-08-06 16:06:34.397'),(72,'09192994069',1,'475b708efaf86dd7ceae332f91c601beda96c14f0e177f2348a018caa39e6e34','2026-08-06 16:18:20.423','2026-08-06 16:16:34.674',0,'???','2026-08-06 16:16:20.423'),(73,'09120654653',1,'bedb717357ce70bbc0276dce23bf9c73558d421464b9cd0f1d92f4fe2b4c46d4','2026-08-16 16:10:36.140','2026-08-16 16:08:43.908',0,'.?j?','2026-08-16 16:08:36.140'),(74,'09358223323',1,'0345bf001215d6d031654c69a8ab1bd27e3f44e5b5aeeb3bc9ed7c430a3277b7','2026-08-17 12:10:04.027','2026-08-17 12:08:20.021',0,'??e','2026-08-17 12:08:04.027'),(75,'09192999719',1,'30d6fa2fd40afacdfd451e3930315c7cccf920b1dc0240d2f9839a69ac8654d7','2026-08-17 13:26:41.615','2026-08-17 13:24:53.228',0,'??e','2026-08-17 13:24:41.615'),(76,'09192994069',1,'7a269c0c3df0e086c9e15f781159da06e8bc898c012570ea8722828fc10f6b7f','2026-08-17 13:34:21.983','2026-08-17 13:32:44.112',0,'b`?+','2026-08-17 13:32:21.983'),(77,'09192994069',1,'14ca3c38bf8e5e2bf7d97abb92f8a99bb6152d09a051931de71a45ca09ac56f1','2026-08-17 13:36:28.094','2026-08-17 13:34:41.766',0,'b`?+','2026-08-17 13:34:28.094'),(78,'09123598357',1,'47e6805825f8b5137eac1998032d7f12a7b361ca120965aaaddb52c3c04fa36a','2026-08-17 13:39:14.479','2026-08-17 13:37:39.332',0,'b`?+','2026-08-17 13:37:14.479'),(79,'09358223323',1,'a35f2b51fa99b19e317cc6463b4fa4c88fbe7a319c0d9e7483fb7da07dad6728','2026-08-17 13:42:20.537','2026-08-17 13:40:32.344',0,'??e','2026-08-17 13:40:20.537'),(80,'09192999873',1,'a76ca6df14e95ac430cab161d2b3297748219e3d99be88dbc0f478bfa7214d49','2026-08-17 13:44:47.212','2026-08-17 13:43:05.020',0,'??e','2026-08-17 13:42:47.212'),(81,'09351794610',1,'81f10b31d3dcd2f2591326ab9a9e4ae6344532859cfcbd805dd6217e2a13213d','2026-08-22 11:05:44.753','2026-08-22 11:03:57.185',0,'??/','2026-08-22 11:03:44.753');
/*!40000 ALTER TABLE `otp_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `perm_key` varchar(96) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_permissions_key` (`perm_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provinces`
--

DROP TABLE IF EXISTS `provinces`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `provinces` (
  `id` int unsigned NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tel_prefix` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provinces`
--

LOCK TABLES `provinces` WRITE;
/*!40000 ALTER TABLE `provinces` DISABLE KEYS */;
INSERT INTO `provinces` VALUES (100,'مرکزی','مرکزی','086'),(101,'گیلان','گیلان','013'),(102,'مازندران','مازندران','011'),(103,'آذربایجان شرقی','آذربایجان-شرقی','041'),(104,'آذربایجان غربی','آذربایجان-غربی','044'),(105,'کرمانشاه','کرمانشاه','083'),(106,'خوزستان','خوزستان','061'),(107,'فارس','فارس','071'),(108,'کرمان','کرمان','034'),(109,'خراسان رضوی','خراسان-رضوی','051'),(110,'اصفهان','اصفهان','031'),(111,'سیستان و بلوچستان','سیستان-و-بلوچستان','054'),(112,'کردستان','کردستان','087'),(113,'همدان','همدان','081'),(114,'چهارمحال و بختیاری','چهارمحال-و-بختیاری','038'),(115,'لرستان','لرستان','066'),(116,'ایلام','ایلام','084'),(117,'کهگیلویه و بویراحمد','کهگیلویه-و-بویراحمد','074'),(118,'بوشهر','بوشهر','077'),(119,'زنجان','زنجان','024'),(120,'سمنان','سمنان','023'),(121,'یزد','یزد','035'),(122,'هرمزگان','هرمزگان','076'),(123,'تهران','تهران','021'),(124,'اردبیل','اردبیل','045'),(125,'قم','قم','025'),(126,'قزوین','قزوین','028'),(127,'گلستان','گلستان','017'),(128,'خراسان شمالی','خراسان-شمالی','058'),(129,'خراسان جنوبی','خراسان-جنوبی','056'),(130,'البرز','البرز','026');
/*!40000 ALTER TABLE `provinces` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `push_broadcast_logs`
--

DROP TABLE IF EXISTS `push_broadcast_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `push_broadcast_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` bigint unsigned DEFAULT NULL,
  `target_audience` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_user_id` bigint unsigned DEFAULT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_type` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT 'general',
  `action_data` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sent_count` int unsigned NOT NULL DEFAULT '0',
  `success_count` int unsigned NOT NULL DEFAULT '0',
  `failed_count` int unsigned NOT NULL DEFAULT '0',
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `push_broadcast_logs`
--

LOCK TABLES `push_broadcast_logs` WRITE;
/*!40000 ALTER TABLE `push_broadcast_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `push_broadcast_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ratings`
--

DROP TABLE IF EXISTS `ratings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ratings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `load_id` bigint unsigned NOT NULL,
  `rater_user_id` bigint unsigned NOT NULL,
  `target_user_id` bigint unsigned NOT NULL,
  `score` tinyint unsigned NOT NULL,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_ratings_unique` (`load_id`,`rater_user_id`,`target_user_id`),
  KEY `ix_ratings_target` (`target_user_id`,`created_at`),
  KEY `ix_ratings_load` (`load_id`),
  KEY `fk_ratings_rater` (`rater_user_id`),
  CONSTRAINT `fk_ratings_load` FOREIGN KEY (`load_id`) REFERENCES `loads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ratings_rater` FOREIGN KEY (`rater_user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_ratings_target` FOREIGN KEY (`target_user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ratings`
--

LOCK TABLES `ratings` WRITE;
/*!40000 ALTER TABLE `ratings` DISABLE KEYS */;
/*!40000 ALTER TABLE `ratings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `remember_tokens`
--

DROP TABLE IF EXISTS `remember_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `remember_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `token_hash` char(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` datetime(3) NOT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  KEY `ix_remember_tokens_token` (`token_hash`),
  KEY `ix_remember_tokens_user` (`user_id`),
  KEY `ix_remember_tokens_expires` (`expires_at`),
  CONSTRAINT `remember_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `remember_tokens`
--

LOCK TABLES `remember_tokens` WRITE;
/*!40000 ALTER TABLE `remember_tokens` DISABLE KEYS */;
INSERT INTO `remember_tokens` VALUES (24,1,'616f6ebd6108b3c0526bc5151018009fcc02bc23ab3f3696c2b01ccd0caadece','2026-10-20 15:16:34.000','2026-10-13 15:16:34.342','2026-10-13 15:16:34.342');
/*!40000 ALTER TABLE `remember_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reporter_user_id` bigint unsigned NOT NULL,
  `target_user_id` bigint unsigned DEFAULT NULL,
  `load_id` bigint unsigned DEFAULT NULL,
  `conversation_id` bigint unsigned DEFAULT NULL,
  `category` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint unsigned NOT NULL DEFAULT '1',
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `resolved_by_user_id` bigint unsigned DEFAULT NULL,
  `resolved_at` datetime(3) DEFAULT NULL,
  `resolution_note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_reports_status_time` (`status`,`created_at`),
  KEY `ix_reports_reporter` (`reporter_user_id`,`created_at`),
  KEY `fk_reports_target` (`target_user_id`),
  KEY `fk_reports_load` (`load_id`),
  KEY `fk_reports_conv` (`conversation_id`),
  KEY `fk_reports_resolver` (`resolved_by_user_id`),
  CONSTRAINT `fk_reports_conv` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_reports_load` FOREIGN KEY (`load_id`) REFERENCES `loads` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_reports_reporter` FOREIGN KEY (`reporter_user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_reports_resolver` FOREIGN KEY (`resolved_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_reports_target` FOREIGN KEY (`target_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reports`
--

LOCK TABLES `reports` WRITE;
/*!40000 ALTER TABLE `reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_permissions` (
  `role_id` smallint unsigned NOT NULL,
  `permission_id` smallint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`permission_id`),
  KEY `fk_role_permissions_perm` (`permission_id`),
  CONSTRAINT `fk_role_permissions_perm` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_role_permissions_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permissions`
--

LOCK TABLES `role_permissions` WRITE;
/*!40000 ALTER TABLE `role_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scope` tinyint unsigned NOT NULL DEFAULT '1',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_roles_name_scope` (`name`,`scope`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `support_ticket_messages`
--

DROP TABLE IF EXISTS `support_ticket_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `support_ticket_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` bigint unsigned NOT NULL,
  `sender_user_id` bigint unsigned NOT NULL COMMENT 'میتواند راننده یا ادمین باشد',
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_type` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1=text, 2=image, 3=pdf',
  `attachment_key` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  KEY `fk_stm_ticket` (`ticket_id`),
  KEY `fk_stm_sender` (`sender_user_id`),
  CONSTRAINT `fk_stm_sender` FOREIGN KEY (`sender_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_stm_ticket` FOREIGN KEY (`ticket_id`) REFERENCES `support_tickets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `support_ticket_messages`
--

LOCK TABLES `support_ticket_messages` WRITE;
/*!40000 ALTER TABLE `support_ticket_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `support_ticket_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `support_tickets`
--

DROP TABLE IF EXISTS `support_tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `support_tickets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1=باز (درانتظار), 2=پاسخ داده شده, 3=بسته',
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  KEY `fk_support_tickets_user` (`user_id`),
  CONSTRAINT `fk_support_tickets_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `support_tickets`
--

LOCK TABLES `support_tickets` WRITE;
/*!40000 ALTER TABLE `support_tickets` DISABLE KEYS */;
/*!40000 ALTER TABLE `support_tickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_settings`
--

DROP TABLE IF EXISTS `system_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_settings` (
  `setting_key` varchar(96) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_unicode_ci,
  `updated_by_user_id` bigint unsigned DEFAULT NULL,
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`setting_key`),
  KEY `fk_system_settings_user` (`updated_by_user_id`),
  CONSTRAINT `fk_system_settings_user` FOREIGN KEY (`updated_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_settings`
--

LOCK TABLES `system_settings` WRITE;
/*!40000 ALTER TABLE `system_settings` DISABLE KEYS */;
INSERT INTO `system_settings` VALUES ('app.android.latest_version_code','',1,'2026-08-22 14:13:23.153'),('app.android.min_supported_code','',1,'2026-08-22 14:13:23.154'),('app.android.update_url','',1,'2026-08-22 14:13:23.154'),('app.ios.latest_version_code','',1,'2026-08-22 14:13:23.155'),('app.ios.min_supported_code','',1,'2026-08-22 14:13:23.156'),('app.ios.update_url','',1,'2026-08-22 14:13:23.156'),('auth.require_national_serial','0',1,'2026-08-22 14:13:23.162'),('cargo.app.android.latest_version_code','',1,'2026-08-22 14:13:23.157'),('cargo.app.android.min_supported_code','',1,'2026-08-22 14:13:23.157'),('cargo.app.android.update_url','',1,'2026-08-22 14:13:23.158'),('cargo.app.ios.latest_version_code','',1,'2026-08-22 14:13:23.158'),('cargo.app.ios.min_supported_code','',1,'2026-08-22 14:13:23.159'),('cargo.app.ios.update_url','',1,'2026-08-22 14:13:23.160'),('company.name','آموت‌بار',1,'2026-08-22 14:13:23.148'),('internal_api.endpoint.enabled.api.auth.logout','1',1,'2026-05-16 19:15:20.624'),('internal_api.endpoint.enabled.api.auth.refresh','1',1,'2026-05-16 19:15:20.624'),('internal_api.endpoint.enabled.api.auth.request_otp','1',1,'2026-05-16 19:15:20.624'),('internal_api.endpoint.enabled.api.auth.verify_otp','1',1,'2026-05-16 19:15:20.624'),('internal_api.endpoint.enabled.api.company.upsert','1',1,'2026-05-16 19:15:20.625'),('internal_api.endpoint.enabled.api.driver.upsert','1',1,'2026-05-16 19:15:20.625'),('internal_api.endpoint.enabled.api.driver.verification_video','1',1,'2026-02-27 23:15:10.888'),('internal_api.endpoint.enabled.api.me.avatar','1',1,'2026-05-16 19:15:20.625'),('internal_api.endpoint.enabled.api.me.get','1',1,'2026-05-16 19:15:20.625'),('internal_api.endpoint.enabled.api.meta.app_config','1',1,'2026-05-16 19:15:20.624'),('internal_api.endpoint.enabled.api.meta.vehicle_types','1',1,'2026-05-16 19:15:20.624'),('internal_api.jwt.access_ttl_sec','900',1,'2026-05-16 19:15:20.622'),('internal_api.jwt.refresh_ttl_days','90',1,'2026-05-16 19:15:20.622'),('internal_api.maintenance.enabled','0',1,'2026-05-16 19:15:20.622'),('internal_api.maintenance.message','خراب است!',1,'2026-05-16 19:15:20.622'),('internal_api.otp.cooldown_sec','60',1,'2026-05-16 19:15:20.623'),('internal_api.otp.length','6',1,'2026-05-16 19:15:20.623'),('internal_api.otp.max_attempts','10',1,'2026-05-16 19:15:20.623'),('internal_api.otp.max_per_ip_hour','30',1,'2026-05-16 19:15:20.623'),('internal_api.otp.max_per_phone_day','20',1,'2026-05-16 19:15:20.623'),('internal_api.otp.max_per_phone_hour','15',1,'2026-05-16 19:15:20.623'),('internal_api.otp.ttl_sec','120',1,'2026-05-16 19:15:20.623'),('links.app_download_url','https://amutapp.com/amutadmin/app.php',1,'2026-08-22 14:13:23.151'),('links.terms_text','شرایط و ضوابط عمومی استفاده از خدمات سامانه هوشمند «آموت بار»\nبخش اول: تعاریف و اصطلاحات\nدر این سند، واژه‌ها و اصطلاحات زیر در معانی مشروح ذیل به کار می‌روند:\n\nآموت بار: شرکت حمل و نقل آموت بار (دارای مجوزهای قانونی مربوطه) که به عنوان ارائه‌دهنده و مالک انحصاری بستر نرم‌افزاری و سامانه هوشمند ارتباطی آموت بار شناخته می‌شود.\n\nسامانه / اپلیکیشن: مجموعه نرم‌افزارها، وب‌اپلیکیشن، پنل‌های تحت وب و زیرساخت‌های برخط متعلق به شرکت حمل و نقل آموت بار که دسترسی به خدمات اعلان و مدیریت بار را فراهم می‌سازد.\n\nکاربر: هر شخص حقیقی یا نماینده شخص حقوقی که پس از تکمیل فرایند ثبت‌نام، از بستر و ابزارهای آموت بار بهره‌برداری می‌نماید.\n\nصاحب بار (فرستنده): شخص حقیقی یا حقوقی ثبت‌نام‌شده در سامانه که تقاضای جابه‌جایی و حمل محموله خود را در بستر آموت بار ثبت و اعلام می‌کند.\n\nراننده (متصدی حمل): شخص حقیقی دارای مدارک و صلاحیت‌های قانونی رانندگی و حمل بار که به منظور پذیرش درخواست‌های حمل کالا و ارائه خدمات در سامانه رانندگان آموت بار عضویت دارد.\n\nبار (محموله): انواع کالا، وسایل و مرسولاتی که بنا به درخواست صاحب بار و بر اساس توافق فی‌مابین، توسط ناوگان تحت هدایت راننده از نقطه مبدأ بارگیری و به مقصد مشخص تحویل می‌گردد.\n\nحساب کاربری: شناسه و پنل اختصاصی که پس از ثبت مشخصات هویتی و شماره همراه، به هر کاربر در سامانه اختصاص می‌یابد.\n\nبارنامه رسمی: سند قانونی و ثبتی تحت نظارت سازمان راهداری و حمل‌ونقل جاده‌ای با شناسه رهگیری معتبر که منحصراً توسط شرکت‌های مجاز باربری صادر شده و حدود تعهدات بیمه‌ای و حمل را معین می‌کند.\n\nتوافق‌نامه کاربری: مجموعه شروط، الزامات و مقررات حاضر که نحوه بهره‌برداری از خدمات سامانه را میان کاربر و آموت بار تنظیم می‌نماید.\n\nبخش دوم: موارد سلب مسئولیت و حدود تعهدات\n۱. لزوم صدور بارنامه: حمل هرگونه محموله برون‌شهری بدون اخذ بارنامه رسمی، مغایر با ضوابط قانونی راهداری است. اخذ و صدور بارنامه بر عهده صاحب کالا و راننده از طریق مراجع و شرکت‌های حمل مجاز بوده و جابه‌جایی بدون بارنامه تخلف محسوب می‌شود.\n\n۲. توافقی بودن مبالغ کرایه: هزینه حمل بار بر مبنای نرخ اعلامی و توافق مستقیم میان صاحب کالا و راننده تعیین می‌گردد؛ بنابراین پس از نهایی شدن توافق و تایید حمل، هیچ‌یک از طرفین حق طرح ادعا یا مطالبه وجه مازاد بر توافق را نخواهند داشت.\n\n۳. کالاهای ممنوعه و غیرمجاز: بارگیری و حمل هرگونه اقلام غیرقانونی، مواد محترقه و منفجره، کالاهای قاچاق، سلاح، مشروبات الکلی، مواد مخدر یا احشام بدون مجوز دامپزشکی اکیداً ممنوع است. مسئولیت کامل حقوقی و کیفری ناشی از اظهار خلاف واقع یا حمل این قبیل اقلام منحصراً متوجه اعلام‌کننده و در صورت اطلاع، متوجه راننده خواهد بود.\n\n۴. پرداخت‌های خارج از سامانه: شرکت حمل و نقل آموت بار هیچ‌گونه نظارت، تضمین یا مسئولیتی در قبال مبالغی که خارج از درگاه‌های معتبر و رسمی ثبت‌شده در سامانه رد و بدل می‌گردد بر عهده ندارد و این قبیل تراکنش‌ها غیرقابل پیگیری از سوی سامانه خواهد بود.\n\nبخش سوم: کلیات و احکام قرارداد\n۱. چارچوب حاکم: مفاد این سند تماماً بر اساس قوانین و مقررات جمهوری اسلامی ایران، قانون تجارت الکترونیک و آیین‌نامه‌های حمل‌ونقل جاده‌ای تنظیم شده است. در صورت بروز هرگونه اختلاف، اولویت بر حل مسالمت‌آمیز بوده و در غیر این صورت، مراجع ذی‌صلاح قضایی مرجع رسیدگی نهایی خواهند بود.\n\n۲. پذیرش بی‌قید و شرط: ورود به سامانه، ایجاد حساب کاربری و استفاده مستمر از ابزارهای آموت بار به منزله مطالعه دقیق و قبولی کامل تمامی بندهای این توافق‌نامه است.\n\n۳. تغییرات مقررات: آموت بار این حق را دارد که در هر زمان نسبت به بازبینی و به‌روزرسانی ضوابط اقدام نماید. انتشار نسخه جدید در سامانه به منزله ابلاغ رسمی تلقی شده و ادامه فعالیت کاربران به معنای پذیرش نسخه به‌روزشده است.\n\n۴. خسارت به اعتبار تجاری: هرگونه اقدام عمدی یا غیرعمدی از سوی کاربران که موجب ورود ضرر و زیان مادی، معنوی یا خدشه به نام و نشان تجاری آموت بار گردد، حق پیگیری کیفری، مطالبه خسارت و انسداد دائم حساب کاربری را برای شرکت محفوظ می‌دارد.\n\nبخش چهارم: مقررات ثبت‌نام و امنیت حساب کاربری\n۱. شرط سنی و اهلیت: ایجاد حساب کاربری منوط به داشتن حداقل ۱۸ سال تمام خورشیدی و برخورداری از اهلیت قانونی برای انجام معاملات است.\n\n۲. اشخاص حقوقی: اشخاص حقوقی و نهادها تنها از طریق معرفی کتبی نماینده رسمی خود امکان ایجاد و مدیریت حساب کاربری را خواهند داشت و مسئولیت کلیه اقدامات نماینده بر عهده آن شرکت یا سازمان می‌باشد.\n\n۳. صحت اطلاعات: کاربر ملزم است اطلاعات هویتی، مشخصات فردی، کدملی، شماره تماس فعال و اسناد بارگیر/خودرو را منطبق بر واقعیت و به صورت کامل ثبت کند. هرگونه عدم تطابق اطلاعات بر عهده کاربر بوده و موجب سلب خدمات خواهد شد.\n\n۴. احراز هویت: آموت بار مجاز است در مراحل مختلف فعالیت، مدارک احراز هویت تکمیلی (نظیر مدارک مالکیتی، گواهینامه، کارت هوشمند و...) را از رانندگان و صاحبان بار مطالبه کند. امتناع از ارائه مدارک منجر به غیرفعال شدن حساب خواهد شد.\n\n۵. عدم واگذاری حساب: هر کاربر مجاز به داشتن تنها یک حساب کاربری است. واگذاری، اجاره یا انتقال حساب به اشخاص ثالث ممنوع بوده و دارنده حساب، پاسخگوی تمامی اعمال ثبت‌شده از طریق آن خواهد بود.\n\n۶. حفاظت از کلمات عبور: کاربر مسئول حفظ محرمانگی نام کاربری، رمز عبور و کدهای تایید موقت است. در صورت سرقت اطلاعات یا مفقودی تلفن همراه، کاربر موظف است فوراً تیم پشتیبانی آموت بار را مطلع نماید.\n\nبخش پنجم: قوانین عملیاتی و حمل محموله\n۱. نقش واسطه‌ای سامانه: آموت بار به عنوان بستری هوشمند و تسهیل‌کننده پیوند میان فرستنده بار و راننده عمل می‌کند و خود مستقیماً متصدی حمل، مالک بار یا ضامن نهایی روابط طرفین نمی‌باشد.\n\n۲. تطابق ناوگان و راننده: اعزام راننده یا خودرویی مغایر با اطلاعات ثبت‌شده در پنل آموت بار برای بارگیری ممنوع است و پذیرش بار منحصراً توسط شخص و ناوگان تاییدشده مجاز می‌باشد.\n\n۳. رعایت ظرفیت و تناژ مجاز: میزان بارگیری باید کاملاً مطابق با ظرفیت اسمی درج‌شده در کارت خودرو باشد. بارگیری مازاد بر ظرفیت قانونی (اضافه بار) تحت هیچ شرایطی، حتی با توافق طرفین، مجاز نبوده و عواقب آن متوجه متخلفین است.\n\n۴. اعلام شرایط خاص محموله: چنانچه بار دارای ابعاد غیرمتعارف، شرایط فسادپذیری یا حساسیت ویژه باشد، صاحب کالا موظف است پیش از نهایی‌سازی درخواست، جزئیات را به اطلاع راننده برساند.\n\n۵. لغو غیرموجه بارگیری: در صورتی که پس از تایید سفر و اعزام ناوگان به سمت مبدأ، درخواست از طرف صاحب بار بدون دلیل موجه ابطال شود، حق مطالبه حق توقف یا خسارت ورود به مبدأ برای راننده بر اساس عرف باربری محفوظ بوده و پیگیری آن مستقیماً میان طرفین انجام می‌گیرد.\n\n۶. سوانح و خسارات جاده‌ای: در زمان رخداد تصادفات، واژگونی، آتش‌سوزی، سرقت یا تلف شدن محموله در طول مسیر، جبران خسارت بر اساس بیمه‌نامه‌های معتبر، بارنامه رسمی و گزارشات پلیس راهور صورت پذیرفته و مسئولیتی از این حیث متوجه شرکت آموت بار نخواهد بود.\n\n۷. همراهان غیررسمی: مسئولیت حقوقی و ایمنی افرادی که به عنوان شاگرد یا همراه به همراه راننده در فرایند حمل حضور می‌یابند، منحصراً بر عهده خود راننده است.\n\n۸. مالیات و تکالیف صنفی: انجام تکالیف مالیاتی، عوارض قانونی و امور صنفی بر عهده هر یک از کاربران بوده و آموت بار مسئولیتی در قبال تعهدات انفرادی کاربران به مراجع قانونی ندارد.\n\nبخش ششم: حریم خصوصی و امنیت داده‌ها\n۱. محرمانگی اطلاعات هویتی: کلیه اسناد و مشخصات هویتی کاربران نزد آموت بار محرمانه نگهداری می‌شود؛ مگر در مواردی که به موجب دستور کتبی مراجع قضایی یا مراجع ذی‌صلاح قانونی درخواست گردد.\n\n۲. اطلاعات فرآیند حمل: اطلاعات مربوط به جزئیات بار، شماره تماس هماهنگی و لوکیشن بارگیری و تخلیه به منظور تسهیل فرایند عملیات، صرفاً در اختیار طرفین همان سفارش قرار می‌گیرد.\n\n۳. ممنوعیت سوءاستفاده از اطلاعات: ذخیره‌سازی، افشا، فروش یا هرگونه استفاده تجاری از اطلاعات تماس رانندگان یا فرستندگان خارج از چارچوب سرویس آموت بار تخلف محسوب شده و مستوجب پیگرد قانونی است.\n\n۴. دسترسی‌های سیستمی: سامانه ممکن است جهت ارائه قابلیت‌هایی مانند ردیابی موقعیت بار و ارسال اعلانات، دسترسی‌های مکانی و ارتباطی (پیامک/نوتیفیکیشن) را با اجازه کاربر از سیستم عامل دریافت کند.\n\nبخش هفتم: تعرفه‌ها، هدایا و امتیازات\n۱. کدهای تخفیف و جوایز اعتباری: کدهای تبلیغاتی، تخفیف‌ها یا اعتبارات هدیه‌ای که از سوی آموت بار ارائه می‌شوند غیرقابل فروش، غیرقابل انتقال به غیر و فاقد قابلیت تبدیل به وجه نقد هستند.\n\n۲. ابطال و تغییر طرح‌های تشویقی: آموت بار این اختیار را دارد که مهلت زمانی، مبلغ یا شرایط اعتبارات هدیه را در هر زمان اصلاح یا باطل نماید و یا در صورت بروز تخلف یا ایرادات نرم‌افزاری، موجودی‌های نامعتبر را حذف کند.\n\n۳. عدم استرداد هزینه‌های خدمات: مبالغی که بابت کارمزد خدمات فناوری یا اشتراک‌های نرم‌افزاری به آموت بار واریز می‌گردد، پس از نهایی شدن غیرقابل بازگشت خواهد بود.\n\nبخش هشتم: الزامات فنی و صیانت از مالکیت فکری\n۱. ممنوعیت مداخله سیستمی: استفاده از ربات‌های نرم‌افزاری، اسکریپت‌های خودکار، برنامه‌های داده‌کاوی، فریمینگ (Framing) یا ایجاد سرورهای آینه‌ای (Mirroring) به منظور نفوذ، استخراج داده یا اخلال در سامانه آموت بار ممنوع است.\n\n۲. مالکیت کدها: کلیه حقوق مادی و معنوی سورس‌کدها، علائم تجاری، طراحی‌های بصری، محتوا و پایگاه‌های داده منحصراً متعلق به شرکت حمل و نقل آموت بار است. هرگونه تلاش برای مهندسی معکوس (Reverse Engineering)، دیکامپایل یا کپی‌برداری غیرمجاز با پیگرد شدید قانونی همراه خواهد بود.\n\nبخش نهم: مسدودسازی و تعلیق دسترسی\n۱. اختیار صلاحدیدی: در صورتی که کاربری با رفتار، اظهارات یا نقض تعهدات، موجب تهدید امنیت سامانه، تضییع حقوق سایر اعضا یا افت کیفیت شبکه حمل‌ونقل گردد، آموت بار مجاز است بدون نیاز به اخطار قبلی، حساب کاربری وی را به شکل موقت یا دائم مسدود سازد.\n\n۲. سلب حق ادعا: در صورت انسداد حساب کاربری به دلیل تخلف از شروط این توافق‌نامه، کاربر متخلف حق هرگونه اعتراض یا ادعای خسارت را از خود سلب می‌نماید.\n\nبخش دهم: توافقات از راه دور و استنادپذیری الکترونیکی\n۱. امضای الکترونیکی: هرگونه فشردن دکمه تایید، ورود رمز یکبار مصرف یا ثبت تعهدات در داخل محیط اپلیکیشن، بر اساس قانون تجارت الکترونیک به منزله امضای الکترونیک معتبر و اعلام اراده صریح کاربر تلقی می‌گردد.\n\n۲. اعتبار مبادلات دیجیتال: طرفین اذعان دارند که این قرارداد و تعهدات ناشی از آن، یک سند الزام‌آور حقوقی بوده و داده‌پیام‌های ثبت‌شده در سامانه آموت بار در مراجع قانونی و قضایی به عنوان ادله الکترونیکی قابل استناد خواهد بود.',1,'2026-08-22 14:13:23.150'),('links.terms_url','https://amutapp.com/amutadmin/terms.php',1,'2026-08-22 14:13:23.150'),('maintenance.enabled','0',1,'2026-08-22 14:13:23.160'),('maintenance.message','',1,'2026-08-22 14:13:23.161'),('onboarding.require_verification_video','0',1,'2026-08-22 14:13:23.162'),('site.favicon_path','storage/uploads/system/site-favicon.png',1,'2026-02-10 00:37:41.291'),('site.logo_path','storage/uploads/system/site-logo.png',1,'2026-02-21 21:31:16.179'),('site.name','آموت‌بار',1,'2026-08-22 14:13:23.148'),('site.url','https://amutapp.com/amutadmin',1,'2026-08-22 14:13:23.149'),('support.phone','09351794610',1,'2026-08-22 14:13:23.152'),('support.telegram','',1,'2026-08-22 14:13:23.153'),('support.whatsapp','',1,'2026-08-22 14:13:23.152'),('verification.api_ir.liveness_threshold','80',1,'2026-08-22 14:13:23.166'),('verification.api_ir.matching_threshold','90',1,'2026-08-22 14:13:23.166'),('verification.api_ir.speech_threshold','50',1,'2026-08-22 14:13:23.167'),('verification.video_guide_text','تصویر شما باید در مرکز صفحه باشد.\nپس زمینه شما باید سفید باشد.\nنور به صورت مناسب به صورت شما بتابد.\nصورت شما در ویدئو باید واضح باشد.',1,'2026-08-22 14:13:23.163'),('verification.video_guide_url','https://amutapp.com/assets/help-1080p.mp4',1,'2026-08-22 14:13:23.164'),('verification.video_max_mb','5',1,'2026-08-22 14:13:23.165'),('verification.video_max_seconds','10',1,'2026-08-22 14:13:23.164'),('verification.video_phrase_template','اینجانب {full_name} با قوانین {company_name} موافقت می‌کنم.',1,'2026-08-22 14:13:23.163');
/*!40000 ALTER TABLE `system_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_app_roles`
--

DROP TABLE IF EXISTS `user_app_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_app_roles` (
  `user_id` bigint unsigned NOT NULL,
  `app_role` tinyint unsigned NOT NULL COMMENT '1 = Driver, 2 = Company/Cargo owner',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1 = Active, 2 = Disabled',
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`user_id`,`app_role`),
  KEY `ix_user_app_roles_role_status` (`app_role`,`status`),
  CONSTRAINT `fk_user_app_roles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_app_roles`
--

LOCK TABLES `user_app_roles` WRITE;
/*!40000 ALTER TABLE `user_app_roles` DISABLE KEYS */;
INSERT INTO `user_app_roles` VALUES (66,2,1,'2026-08-06 16:06:51.797','2026-08-30 21:57:22.502'),(67,1,1,'2026-08-06 16:16:34.680','2026-08-30 21:57:22.502'),(68,2,1,'2026-08-17 12:08:20.029','2026-08-30 21:57:22.502'),(69,1,1,'2026-08-17 13:24:53.234','2026-08-30 21:57:22.502'),(70,2,1,'2026-08-17 13:37:39.338','2026-08-30 21:57:22.502'),(71,1,1,'2026-08-17 13:43:05.028','2026-08-30 21:57:22.502'),(72,1,1,'2026-08-22 11:03:57.190','2026-08-30 21:57:22.502');
/*!40000 ALTER TABLE `user_app_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_files`
--

DROP TABLE IF EXISTS `user_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_files` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `file_type` tinyint unsigned NOT NULL COMMENT '1=عکس پروفایل, 2=عکس کارت ملی, 3=عکس گواهینامه, 4=عکس کارت ماشین, 5=عکس برگه سبز, 6=ویدئو احراز هویت, 7=عکس بیمه نامه',
  `file_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` int unsigned DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  KEY `ix_user_files_user_type` (`user_id`,`file_type`),
  CONSTRAINT `fk_user_files_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_files_chk_1` CHECK (json_valid(`metadata`))
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_files`
--

LOCK TABLES `user_files` WRITE;
/*!40000 ALTER TABLE `user_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_push_tokens`
--

DROP TABLE IF EXISTS `user_push_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_push_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `token` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `platform` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT 'android',
  `app_type` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1=driver, 2=cargo',
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_push_user_token` (`user_id`,`token`),
  KEY `ix_push_app_type` (`app_type`),
  CONSTRAINT `fk_push_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_push_tokens`
--

LOCK TABLES `user_push_tokens` WRITE;
/*!40000 ALTER TABLE `user_push_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_push_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_relations`
--

DROP TABLE IF EXISTS `user_relations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_relations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `driver_id` bigint unsigned NOT NULL,
  `relation_type` tinyint unsigned NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_relations_unique` (`company_id`,`driver_id`,`relation_type`),
  KEY `ix_user_relations_company` (`company_id`,`relation_type`,`updated_at`),
  KEY `ix_user_relations_driver` (`driver_id`,`relation_type`,`updated_at`),
  CONSTRAINT `fk_user_relations_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_user_relations_driver` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_relations`
--

LOCK TABLES `user_relations` WRITE;
/*!40000 ALTER TABLE `user_relations` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_relations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_roles`
--

DROP TABLE IF EXISTS `user_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_roles` (
  `user_id` bigint unsigned NOT NULL,
  `role_id` smallint unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `ix_user_roles_role` (`role_id`),
  CONSTRAINT `fk_user_roles_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_user_roles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_roles`
--

LOCK TABLES `user_roles` WRITE;
/*!40000 ALTER TABLE `user_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_meli` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_date` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `national_card_serial` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `father_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('1','2') COLLATE utf8mb4_unicode_ci DEFAULT '1' COMMENT '1 = Men, 2 = Women',
  `user_type` tinyint unsigned NOT NULL COMMENT '1 = Driver, 2 = Company, 3 = Admin',
  `status` tinyint unsigned NOT NULL DEFAULT '3' COMMENT '1 = Active, 1 = DeActive, 3 = Pending, 4 = Suspended',
  `display_name` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `theme_mode` enum('light','dark') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login_at` datetime(3) DEFAULT NULL,
  `failed_login_attempts` smallint unsigned NOT NULL DEFAULT '0',
  `locked_until` datetime(3) DEFAULT NULL,
  `jwt_token_version` int unsigned NOT NULL DEFAULT '1' COMMENT 'برای ابطال سراسری JWT (logout-all)',
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  `deleted_at` datetime(3) DEFAULT NULL,
  `phone_active` varchar(16) COLLATE utf8mb4_unicode_ci GENERATED ALWAYS AS ((case when (`deleted_at` is null) then `phone` else NULL end)) STORED,
  `email_active` varchar(191) COLLATE utf8mb4_unicode_ci GENERATED ALWAYS AS ((case when (`deleted_at` is null) then `email` else NULL end)) STORED,
  `code_meli_active` varchar(10) COLLATE utf8mb4_unicode_ci GENERATED ALWAYS AS ((case when (`deleted_at` is null) then `code_meli` else NULL end)) STORED,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_phone_active` (`phone_active`),
  UNIQUE KEY `uq_users_email_active` (`email_active`),
  UNIQUE KEY `uq_users_code_meli_active` (`code_meli_active`),
  KEY `ix_users_type_status` (`user_type`,`status`),
  KEY `ix_users_created` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `full_name`, `code_meli`, `birth_date`, `national_card_serial`, `phone`, `email`, `password_hash`, `father_name`, `gender`, `user_type`, `status`, `display_name`, `avatar_key`, `theme_mode`, `last_login_at`, `failed_login_attempts`, `locked_until`, `jwt_token_version`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'شایان','1111111111','1380/10/04',NULL,'09123456789','namayandeshayan@gmail.com','$2y$10$rEa9Fo7W3FbMj.4dJtfQgusADJrZruyr2d9wFzWph0zwUYm0rjx2K','یعقوب','1',3,1,'عمو شایان','storage/uploads/avatars/avatar_1_1770674071.webp','light','2026-10-13 15:16:34.329',0,NULL,1,'2025-12-18 15:06:36.758','2026-10-13 18:17:52.287',NULL),(64,'شایان نماینده','0312449348','1380/10/04',NULL,'09129248289',NULL,NULL,NULL,'1',2,2,NULL,NULL,NULL,'2026-08-06 15:58:34.909',0,NULL,2,'2026-08-06 15:58:34.894','2026-08-22 11:04:19.316','2026-08-22 11:04:19.316'),(65,'کاربر',NULL,NULL,NULL,'09351794610',NULL,NULL,NULL,'1',1,2,NULL,NULL,NULL,'2026-08-06 16:06:32.136',0,NULL,2,'2026-08-06 16:06:32.124','2026-08-22 11:03:37.341','2026-08-22 11:03:37.341'),(66,'محمد درویشی نیا','4190260975','1371/10/02',NULL,'09120654653',NULL,NULL,NULL,'1',2,1,NULL,NULL,NULL,'2026-08-06 16:06:51.830',0,NULL,1,'2026-08-06 16:06:51.797','2026-08-06 16:07:40.312',NULL),(67,'رسول درویشی نیا','4190412147','1384/08/03',NULL,'09192994069',NULL,NULL,NULL,'1',1,1,NULL,NULL,NULL,'2026-08-06 16:16:34.694',0,NULL,1,'2026-08-06 16:16:34.680','2026-08-06 16:18:29.655',NULL),(68,'علیرضا درویشی نیا','4190718335','1382/01/27',NULL,'09358223323',NULL,NULL,NULL,'1',2,1,NULL,NULL,NULL,'2026-08-17 12:08:20.044',0,NULL,1,'2026-08-17 12:08:20.029','2026-08-17 12:10:08.387',NULL),(69,'کاربر',NULL,NULL,NULL,'09192999719',NULL,NULL,NULL,'1',1,1,NULL,NULL,NULL,'2026-08-17 13:24:53.246',0,NULL,1,'2026-08-17 13:24:53.234','2026-08-17 13:24:53.246',NULL),(70,'کاربر',NULL,NULL,NULL,'09123598357',NULL,NULL,NULL,'1',2,1,NULL,NULL,NULL,'2026-08-17 13:37:39.361',0,NULL,1,'2026-08-17 13:37:39.338','2026-08-17 13:37:39.361',NULL),(71,'کاربر',NULL,NULL,NULL,'09192999873',NULL,NULL,NULL,'1',1,1,NULL,NULL,NULL,'2026-08-17 13:43:05.045',0,NULL,1,'2026-08-17 13:43:05.028','2026-08-17 13:43:05.045',NULL),(72,'شایان نماینده','0312449348','1380/10/04','1G39352909','09351794610',NULL,NULL,NULL,'1',1,1,NULL,NULL,NULL,'2026-08-22 11:03:57.218',0,NULL,1,'2026-08-22 11:03:57.190','2026-09-20 17:23:52.579',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicle_types`
--

DROP TABLE IF EXISTS `vehicle_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vehicle_types` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` smallint unsigned DEFAULT NULL,
  `code` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body_type` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `max_weight_kg` decimal(10,2) DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_vehicle_types_code` (`code`),
  KEY `ix_vehicle_types_parent` (`parent_id`),
  KEY `ix_vehicle_types_active` (`is_active`),
  CONSTRAINT `fk_vehicle_types_parent` FOREIGN KEY (`parent_id`) REFERENCES `vehicle_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle_types`
--

LOCK TABLES `vehicle_types` WRITE;
/*!40000 ALTER TABLE `vehicle_types` DISABLE KEYS */;
INSERT INTO `vehicle_types` VALUES (1,NULL,'1','نیسان',NULL,2200.00,'نیسان',NULL,1,'2026-02-13 22:02:57','2026-02-13 22:04:18'),(2,1,'11','نیسان روباز','روباز',2200.00,'نیسان روباز',NULL,1,'2026-02-13 22:04:13','2026-02-13 22:04:13'),(3,1,'12','نیسان مسقف','مسقف',2200.00,'نیسان مسقف',NULL,1,'2026-02-13 22:04:49','2026-02-13 22:04:49'),(4,1,'13','نیسان یخچالی','یخچالی',2200.00,'نیسان یخچالی',NULL,1,'2026-02-13 22:05:49','2026-02-13 22:05:49');
/*!40000 ALTER TABLE `vehicle_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `verification_video_challenges`
--

DROP TABLE IF EXISTS `verification_video_challenges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `verification_video_challenges` (
  `token_hash` char(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `app_role` tinyint unsigned NOT NULL,
  `speech_text` varchar(2000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` datetime(3) NOT NULL,
  `used_at` datetime(3) DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`token_hash`),
  KEY `ix_video_challenge_user` (`user_id`,`app_role`,`expires_at`),
  CONSTRAINT `fk_video_challenge_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `verification_video_challenges`
--

LOCK TABLES `verification_video_challenges` WRITE;
/*!40000 ALTER TABLE `verification_video_challenges` DISABLE KEYS */;
/*!40000 ALTER TABLE `verification_video_challenges` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-13 18:33:39
