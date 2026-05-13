-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 13, 2026 at 09:14 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `amutbar`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_audit_logs`
--

CREATE TABLE `admin_audit_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `actor_user_id` bigint UNSIGNED NOT NULL,
  `action` varchar(96) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_type` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entity_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload_json` json DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_audit_logs`
--

INSERT INTO `admin_audit_logs` (`id`, `actor_user_id`, `action`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `payload_json`, `created_at`) VALUES
(1, 1, 'users.save', 'user', 16, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '[]', '2026-01-13 23:12:33.702'),
(2, 1, 'users.delete', 'user', 16, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '[]', '2026-01-13 23:14:22.771'),
(3, 1, 'users.save', 'user', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '[]', '2026-01-21 02:16:46.405'),
(4, 1, 'users.save', 'user', 17, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '[]', '2026-01-21 02:18:00.314'),
(5, 1, 'users.save', 'user', 17, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '[]', '2026-01-21 02:18:25.219'),
(6, 1, 'users.save', 'user', 17, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '[]', '2026-01-21 02:18:48.008'),
(7, 1, 'users.delete', 'user', 17, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '[]', '2026-01-21 02:18:51.063'),
(8, 1, 'users.save', 'user', 22, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '[]', '2026-02-08 21:56:42.911'),
(9, 1, 'admin.avatar.updated', 'users', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '{\"avatar_key\": \"storage/uploads/avatars/avatar_1_1770673695.png\"}', '2026-02-10 01:18:15.067'),
(10, 1, 'admin.avatar.updated', 'users', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '{\"avatar_key\": \"storage/uploads/avatars/avatar_1_1770673832.webp\"}', '2026-02-10 01:20:32.518'),
(11, 1, 'users.save', 'user', 23, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '[]', '2026-02-10 01:23:25.119'),
(12, 1, 'admin.avatar.updated', 'users', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '{\"avatar_key\": \"storage/uploads/avatars/avatar_1_1770674071.webp\"}', '2026-02-10 01:24:31.370'),
(13, 1, 'users.delete', 'user', 30, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '[]', '2026-02-10 03:27:08.953'),
(14, 1, 'users.save', 'user', 32, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '[]', '2026-02-10 03:59:57.127'),
(15, 1, 'users.save', 'user', 32, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '[]', '2026-02-10 04:00:02.481'),
(16, 1, 'users.save', 'user', 32, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '[]', '2026-02-10 04:00:20.005'),
(17, 1, 'vehicle.toggle', 'vehicle_type_toggle', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '[]', '2026-02-13 22:03:15.267'),
(18, 1, 'vehicle.update', 'vehicle_type_update', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '[]', '2026-02-13 22:03:28.262'),
(19, 1, 'vehicle.create', 'vehicle_type_create', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '[]', '2026-02-13 22:04:13.108'),
(20, 1, 'vehicle.update', 'vehicle_type_update', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '[]', '2026-02-13 22:04:17.544'),
(21, 1, 'vehicle.create', 'vehicle_type_create', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '[]', '2026-02-13 22:04:48.582'),
(22, 1, 'vehicle.create', 'vehicle_type_create', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '[]', '2026-02-13 22:05:49.462');

-- --------------------------------------------------------

--
-- Table structure for table `api_tokens`
--

CREATE TABLE `api_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `token_hash` char(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_id` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `platform` tinyint UNSIGNED DEFAULT NULL,
  `ip_address` varbinary(16) DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` datetime(3) DEFAULT NULL,
  `expires_at` datetime(3) DEFAULT NULL,
  `revoked_at` datetime(3) DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_versions`
--

CREATE TABLE `app_versions` (
  `id` bigint UNSIGNED NOT NULL,
  `app_id` tinyint UNSIGNED NOT NULL,
  `platform` tinyint UNSIGNED NOT NULL,
  `latest_version_name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `latest_version_code` int UNSIGNED NOT NULL,
  `min_supported_code` int UNSIGNED NOT NULL,
  `force_update` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `released_at` datetime(3) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_daily_stats`
--

CREATE TABLE `auth_daily_stats` (
  `stat_date` date NOT NULL,
  `user_type` tinyint UNSIGNED NOT NULL,
  `event_type` tinyint UNSIGNED NOT NULL,
  `cnt` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `auth_daily_stats`
--

INSERT INTO `auth_daily_stats` (`stat_date`, `user_type`, `event_type`, `cnt`) VALUES
('2026-02-10', 3, 1, 4),
('2026-02-10', 3, 2, 3),
('2026-02-10', 3, 3, 34),
('2026-02-12', 1, 4, 1),
('2026-02-12', 3, 1, 1),
('2026-02-12', 3, 3, 30),
('2026-02-13', 3, 3, 235),
('2026-02-14', 3, 1, 1),
('2026-02-14', 3, 3, 14);

-- --------------------------------------------------------

--
-- Table structure for table `auth_events`
--

CREATE TABLE `auth_events` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `user_type` tinyint UNSIGNED NOT NULL,
  `event_type` tinyint UNSIGNED NOT NULL,
  `ip_address` varbinary(16) DEFAULT NULL,
  `user_agent_hash` binary(32) DEFAULT NULL,
  `payload_json` json DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `auth_events`
--

INSERT INTO `auth_events` (`id`, `user_id`, `user_type`, `event_type`, `ip_address`, `user_agent_hash`, `payload_json`, `created_at`) VALUES
(1, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:17:40.166'),
(2, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:17:42.587'),
(3, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:17:42.598'),
(4, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:18:01.456'),
(5, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:18:01.464'),
(6, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:18:15.042'),
(7, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:18:15.052'),
(8, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:18:15.065'),
(9, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:20:02.813'),
(10, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:20:04.485'),
(11, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:20:04.493'),
(12, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:20:05.545'),
(13, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:20:05.552'),
(14, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:20:25.499'),
(15, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:20:25.506'),
(16, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:20:32.503'),
(17, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:20:32.508'),
(18, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:20:32.515'),
(19, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:22:09.484'),
(20, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:23:25.051'),
(21, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:23:25.116'),
(22, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:23:26.160'),
(23, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:23:26.195'),
(24, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:23:26.320'),
(25, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:24:22.405'),
(26, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:24:23.944'),
(27, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:24:23.950'),
(28, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:24:25.537'),
(29, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:24:25.544'),
(30, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:24:31.355'),
(31, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:24:31.361'),
(32, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:24:31.369'),
(33, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:24:32.998'),
(34, 1, 3, 3, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"via\": \"remember_token\"}', '2026-02-10 01:25:06.958'),
(35, 1, 3, 2, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, NULL, '2026-02-10 01:25:06.960'),
(36, 1, 3, 1, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"remember\": false}', '2026-02-10 01:25:41.111'),
(37, 1, 3, 2, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, NULL, '2026-02-10 01:39:15.025'),
(38, 1, 3, 1, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"remember\": false}', '2026-02-10 01:39:20.389'),
(39, 1, 3, 2, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, NULL, '2026-02-10 02:18:56.116'),
(40, 1, 3, 1, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"remember\": false}', '2026-02-10 02:19:00.239'),
(41, 1, 3, 1, 0x7f000001, 0x816147bedd84317a66534e444e60842858f0f2a3b3fa43710b4ef836da4252d2, '{\"remember\": false}', '2026-02-10 15:57:09.682'),
(42, 1, 3, 1, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"remember\": true}', '2026-02-12 10:32:51.853'),
(43, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-12 10:32:51.891'),
(44, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-12 10:32:53.603'),
(45, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-12 10:32:53.612'),
(46, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-12 10:32:53.763'),
(47, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-12 10:33:53.649'),
(48, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-12 10:34:12.964'),
(49, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-12 10:34:33.387'),
(50, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-12 10:34:48.153'),
(51, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-12 10:34:48.163'),
(52, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-12 10:34:48.529'),
(53, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-12 10:34:57.302'),
(54, 32, 1, 4, 0x7f000001, 0xfbee6a2f67e57fdf4b4a5c91322cb0c9295120089fd6e229c9ddc232ee044883, '{\"platform\": 1, \"device_id\": \"android-unique-id\"}', '2026-02-12 10:40:15.651'),
(55, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-12 10:46:04.797'),
(56, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-12 10:46:04.802'),
(57, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-12 10:46:05.066'),
(58, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-12 10:46:06.237'),
(59, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-12 10:46:06.436'),
(60, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-12 10:47:19.079'),
(61, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-12 10:47:20.727'),
(62, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-12 10:47:20.866'),
(63, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-12 10:47:20.874'),
(64, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-12 10:47:21.441'),
(65, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-12 10:47:21.450'),
(66, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-12 10:47:21.594'),
(67, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-12 10:48:43.465'),
(68, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-12 10:48:43.573'),
(69, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-12 10:48:43.582'),
(70, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-12 10:48:45.234'),
(71, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-12 10:48:45.341'),
(72, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-12 10:48:45.383'),
(73, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-12 10:48:49.245'),
(74, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:30:21.395'),
(75, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:30:21.408'),
(76, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:30:22.194'),
(77, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:30:22.198'),
(78, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:32:03.152'),
(79, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:32:03.162'),
(80, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:33:01.041'),
(81, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:33:01.048'),
(82, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:33:04.049'),
(83, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:33:04.056'),
(84, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:33:06.583'),
(85, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:33:06.717'),
(86, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:33:06.747'),
(87, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:33:07.521'),
(88, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:33:07.534'),
(89, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:33:42.081'),
(90, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:33:42.152'),
(91, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:33:42.215'),
(92, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:33:43.198'),
(93, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:33:43.321'),
(94, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:33:43.327'),
(95, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:33:44.451'),
(96, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:33:44.456'),
(97, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:33:44.526'),
(98, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:33:44.548'),
(99, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:34:06.617'),
(100, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:34:06.624'),
(101, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:34:06.746'),
(102, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:34:06.766'),
(103, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:34:07.745'),
(104, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:34:07.749'),
(105, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:34:07.833'),
(106, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:34:07.842'),
(107, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:34:11.252'),
(108, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:34:11.292'),
(109, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:34:54.134'),
(110, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:35:07.180'),
(111, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:35:07.192'),
(112, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:35:07.441'),
(113, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:35:07.459'),
(114, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:35:08.626'),
(115, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:35:08.651'),
(116, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:35:17.510'),
(117, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:39:22.660'),
(118, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:39:22.667'),
(119, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:39:22.940'),
(120, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:39:22.953'),
(121, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:39:24.089'),
(122, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:39:24.107'),
(123, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:39:28.665'),
(124, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:39:52.115'),
(125, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:40:02.868'),
(126, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:40:02.873'),
(127, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:40:03.092'),
(128, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:40:03.104'),
(129, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:40:05.744'),
(130, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:45:53.871'),
(131, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:45:53.890'),
(132, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:45:54.211'),
(133, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:45:54.227'),
(134, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:45:56.280'),
(135, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:46:03.773'),
(136, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:46:03.780'),
(137, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:46:04.020'),
(138, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:46:04.032'),
(139, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:46:09.353'),
(140, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:46:16.454'),
(141, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:46:16.461'),
(142, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:46:16.630'),
(143, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:46:16.660'),
(144, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:46:19.410'),
(145, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:47:36.107'),
(146, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:47:36.113'),
(147, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:47:36.497'),
(148, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:47:36.508'),
(149, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:47:38.534'),
(150, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:47:40.438'),
(151, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:47:43.029'),
(152, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:47:43.052'),
(153, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:47:44.948'),
(154, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:48:50.498'),
(155, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:48:50.504'),
(156, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:48:50.924'),
(157, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:48:50.955'),
(158, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:48:52.740'),
(159, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:48:55.257'),
(160, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:49:06.267'),
(161, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:49:21.373'),
(162, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:49:39.606'),
(163, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:49:46.908'),
(164, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:49:46.917'),
(165, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:49:47.057'),
(166, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:49:47.082'),
(167, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:50:50.380'),
(168, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:50:50.387'),
(169, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:50:50.553'),
(170, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:50:50.566'),
(171, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:50:51.855'),
(172, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:51:00.558'),
(173, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:51:01.624'),
(174, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:51:06.795'),
(175, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:51:06.801'),
(176, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:51:07.002'),
(177, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:51:07.021'),
(178, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:51:31.508'),
(179, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:51:31.514'),
(180, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:51:31.669'),
(181, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:51:31.700'),
(182, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:51:34.018'),
(183, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:51:34.824'),
(184, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:51:34.950'),
(185, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:51:35.091'),
(186, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:51:35.211'),
(187, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:51:52.135'),
(188, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:51:52.141'),
(189, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:51:52.311'),
(190, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:51:52.329'),
(191, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:51:53.486'),
(192, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:52:46.050'),
(193, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:52:46.055'),
(194, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:52:46.424'),
(195, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:52:46.438'),
(196, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 21:52:47.276'),
(197, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:03:00.218'),
(198, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:03:00.225'),
(199, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:03:00.581'),
(200, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:03:00.614'),
(201, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:03:01.775'),
(202, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:03:13.659'),
(203, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:03:13.664'),
(204, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:03:13.993'),
(205, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:03:14.025'),
(206, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:03:15.254'),
(207, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:03:15.264'),
(208, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:03:15.303'),
(209, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:03:16.517'),
(210, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:03:16.549'),
(211, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:03:28.253'),
(212, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:03:28.260'),
(213, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:03:28.299'),
(214, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:03:29.859'),
(215, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:03:29.863'),
(216, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:03:30.183'),
(217, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:03:30.198'),
(218, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:03:38.284'),
(219, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:04:13.100'),
(220, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:04:13.106'),
(221, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:04:13.146'),
(222, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:04:15.276'),
(223, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:04:15.301'),
(224, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:04:17.535'),
(225, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:04:17.541'),
(226, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:04:17.580'),
(227, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:04:18.786'),
(228, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:04:30.017'),
(229, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:04:30.022'),
(230, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:04:30.426'),
(231, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:04:30.443'),
(232, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:04:32.202'),
(233, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:04:48.573'),
(234, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:04:48.580'),
(235, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:04:48.621'),
(236, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:04:52.807'),
(237, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:04:52.813'),
(238, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:04:53.113'),
(239, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:04:53.137'),
(240, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:05:34.950'),
(241, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:05:49.452'),
(242, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:05:49.460'),
(243, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:05:49.485'),
(244, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:05:52.094'),
(245, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:05:52.101'),
(246, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:05:52.311'),
(247, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:05:54.475'),
(248, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:05:54.676'),
(249, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:05:54.766'),
(250, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:06:26.461'),
(251, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:06:39.281'),
(252, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:06:39.294'),
(253, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:06:47.438'),
(254, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:09:04.978'),
(255, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:09:05.002'),
(256, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:09:05.259'),
(257, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:09:19.678'),
(258, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:09:19.685'),
(259, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:09:19.833'),
(260, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:09:30.452'),
(261, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:09:30.459'),
(262, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:09:30.590'),
(263, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:12:33.634'),
(264, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:12:33.672'),
(265, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:12:34.646'),
(266, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:14:01.743'),
(267, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:14:01.757'),
(268, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:14:02.378'),
(269, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:14:05.033'),
(270, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:14:05.044'),
(271, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:14:05.660'),
(272, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:14:27.210'),
(273, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:14:27.227'),
(274, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:14:27.805'),
(275, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:14:38.456'),
(276, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:14:38.474'),
(277, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:14:39.098'),
(278, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:14:47.999'),
(279, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:14:48.017'),
(280, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:14:48.316'),
(281, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:15:31.569'),
(282, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:15:31.585'),
(283, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:15:32.217'),
(284, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:15:39.936'),
(285, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:15:39.948'),
(286, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:15:40.499'),
(287, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:15:45.907'),
(288, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:15:45.919'),
(289, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:15:46.141'),
(290, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:15:54.410'),
(291, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:15:54.419'),
(292, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:15:54.581'),
(293, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:28:11.698'),
(294, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:28:11.713'),
(295, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:28:12.037'),
(296, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:28:14.381'),
(297, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:28:14.395'),
(298, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:28:14.614'),
(299, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:28:19.755'),
(300, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:28:19.773'),
(301, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:28:19.922'),
(302, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:28:24.417'),
(303, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:28:25.089'),
(304, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:28:25.104'),
(305, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:28:25.214'),
(306, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:28:25.245'),
(307, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:28:53.161'),
(308, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-13 22:28:53.171'),
(309, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-14 00:17:22.040'),
(310, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-14 00:17:22.062'),
(311, 1, 3, 1, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"remember\": true}', '2026-02-14 00:37:21.774'),
(312, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-14 00:37:21.820'),
(313, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-14 00:43:13.414'),
(314, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-14 00:43:13.603'),
(315, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-14 00:43:13.645'),
(316, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-14 00:43:19.625'),
(317, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-14 00:43:19.702'),
(318, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-14 00:43:19.739'),
(319, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-14 00:43:56.326'),
(320, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-14 00:43:56.366'),
(321, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-14 00:43:58.684'),
(322, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-14 00:44:09.839'),
(323, 1, 3, 3, 0x7f000001, 0x53304b2e8c092ed66b5666499bea55dc9f17570f682a0413aeaf69034cef3f69, '{\"via\": \"remember_token\"}', '2026-02-14 00:44:09.886');

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `placement` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_app_id` tinyint UNSIGNED DEFAULT NULL,
  `target_user_type` tinyint UNSIGNED DEFAULT NULL,
  `target_platform` tinyint UNSIGNED DEFAULT NULL,
  `action_type` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `action_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_at` datetime(3) DEFAULT NULL,
  `end_at` datetime(3) DEFAULT NULL,
  `priority` smallint UNSIGNED NOT NULL DEFAULT '100',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `min_version_code` int UNSIGNED DEFAULT NULL,
  `data_json` json DEFAULT NULL,
  `created_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `call_logs`
--

CREATE TABLE `call_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `load_id` bigint UNSIGNED NOT NULL,
  `driver_id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `event_type` tinyint UNSIGNED NOT NULL,
  `duration_sec` int UNSIGNED DEFAULT NULL,
  `result_code` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_platform` tinyint UNSIGNED DEFAULT NULL,
  `client_version_code` int UNSIGNED DEFAULT NULL,
  `ip_address` varbinary(16) DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cargo_types`
--

CREATE TABLE `cargo_types` (
  `id` smallint UNSIGNED NOT NULL,
  `title` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province_id` int UNSIGNED NOT NULL,
  `county_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `company_name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_full_name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_national_code` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `registration_no` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `economic_code` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province_id` int UNSIGNED NOT NULL,
  `city_id` bigint UNSIGNED NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `verification_status` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `verified_at` datetime(3) DEFAULT NULL,
  `verified_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `reject_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  `deleted_at` datetime(3) DEFAULT NULL,
  `registration_date` date DEFAULT NULL COMMENT 'تاریخ ثبت',
  `postal_code` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'کد پستی'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company_documents`
--

CREATE TABLE `company_documents` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `doc_type` tinyint UNSIGNED NOT NULL,
  `file_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `reviewed_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `reviewed_at` datetime(3) DEFAULT NULL,
  `reject_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company_verification_events`
--

CREATE TABLE `company_verification_events` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `old_status` tinyint UNSIGNED DEFAULT NULL,
  `new_status` tinyint UNSIGNED NOT NULL,
  `actor_user_id` bigint UNSIGNED NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` bigint UNSIGNED NOT NULL,
  `load_id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `driver_id` bigint UNSIGNED NOT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `last_message_at` datetime(3) DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `full_name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `national_code` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `smart_card_number` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vehicle_type_id` smallint UNSIGNED NOT NULL,
  `plate_number` varchar(24) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_year` smallint UNSIGNED DEFAULT NULL,
  `color` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacity_kg` decimal(10,2) DEFAULT NULL,
  `province_id` int UNSIGNED NOT NULL,
  `city_id` bigint UNSIGNED NOT NULL,
  `verification_status` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `verified_at` datetime(3) DEFAULT NULL,
  `verified_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `reject_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating_avg` decimal(3,2) NOT NULL DEFAULT '0.00',
  `rating_count` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  `deleted_at` datetime(3) DEFAULT NULL,
  `plate_active` varchar(24) COLLATE utf8mb4_unicode_ci GENERATED ALWAYS AS ((case when (`deleted_at` is null) then `plate_number` else NULL end)) STORED,
  `license_serial` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'سریال گواهینامه',
  `license_base` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'پایه گواهینامه',
  `vin_number` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'شماره VIN',
  `insurance_number` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'شماره بیمه نامه',
  `insurance_expiry` date DEFAULT NULL COMMENT 'تاریخ اتمام بیمه',
  `engine_number` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'شماره موتور',
  `chassis_number` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'شماره شاسی',
  `issued_from` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'صادره از',
  `address` text COLLATE utf8mb4_unicode_ci COMMENT 'آدرس',
  `home_phone` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'تلفن منزل',
  `postal_code` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'کد پستی',
  `extra_phones` json DEFAULT NULL COMMENT 'شماره موبایل اضافه (آرایه‌ای از شماره‌ها)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`id`, `user_id`, `full_name`, `national_code`, `smart_card_number`, `vehicle_type_id`, `plate_number`, `model_year`, `color`, `capacity_kg`, `province_id`, `city_id`, `verification_status`, `verified_at`, `verified_by_user_id`, `reject_reason`, `rating_avg`, `rating_count`, `created_at`, `updated_at`, `deleted_at`, `license_serial`, `license_base`, `vin_number`, `insurance_number`, `insurance_expiry`, `engine_number`, `chassis_number`, `issued_from`, `address`, `home_phone`, `postal_code`, `extra_phones`) VALUES
(4, 32, 'الیار شکوهی نیا', '1552027384', '65415681', 1, '32 م 568 ایران 55', NULL, 'آبی', 1500.00, 123, 1230001001576, 0, NULL, NULL, NULL, 0.00, 0, '2026-02-10 03:37:31.381', '2026-02-10 04:00:19.995', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[]');

-- --------------------------------------------------------

--
-- Table structure for table `driver_documents`
--

CREATE TABLE `driver_documents` (
  `id` bigint UNSIGNED NOT NULL,
  `driver_id` bigint UNSIGNED NOT NULL,
  `doc_type` tinyint UNSIGNED NOT NULL,
  `file_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `reviewed_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `reviewed_at` datetime(3) DEFAULT NULL,
  `reject_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_locations_current`
--

CREATE TABLE `driver_locations_current` (
  `driver_id` bigint UNSIGNED NOT NULL,
  `lat` decimal(10,7) NOT NULL,
  `lng` decimal(10,7) NOT NULL,
  `geohash` char(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `speed_kmh` decimal(6,2) DEFAULT NULL,
  `heading_deg` smallint UNSIGNED DEFAULT NULL,
  `accuracy_m` decimal(6,2) DEFAULT NULL,
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_location_logs`
--

CREATE TABLE `driver_location_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `driver_id` bigint UNSIGNED NOT NULL,
  `lat` decimal(10,7) NOT NULL,
  `lng` decimal(10,7) NOT NULL,
  `geohash` char(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `speed_kmh` decimal(6,2) DEFAULT NULL,
  `heading_deg` smallint UNSIGNED DEFAULT NULL,
  `accuracy_m` decimal(6,2) DEFAULT NULL,
  `captured_at` datetime(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_verification_events`
--

CREATE TABLE `driver_verification_events` (
  `id` bigint UNSIGNED NOT NULL,
  `driver_id` bigint UNSIGNED NOT NULL,
  `old_status` tinyint UNSIGNED DEFAULT NULL,
  `new_status` tinyint UNSIGNED NOT NULL,
  `actor_user_id` bigint UNSIGNED NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `external_api_credentials`
--

CREATE TABLE `external_api_credentials` (
  `id` bigint UNSIGNED NOT NULL,
  `provider_id` smallint UNSIGNED NOT NULL,
  `env` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `key_id` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_key_enc` varbinary(1024) DEFAULT NULL,
  `api_secret_enc` varbinary(1024) DEFAULT NULL,
  `bearer_token_enc` varbinary(2048) DEFAULT NULL,
  `extra_json` json DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  `created_by_user_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `external_api_credentials`
--

INSERT INTO `external_api_credentials` (`id`, `provider_id`, `env`, `status`, `key_id`, `api_key_enc`, `api_secret_enc`, `bearer_token_enc`, `extra_json`, `created_at`, `updated_at`, `created_by_user_id`) VALUES
(1, 1, 3, 1, '', 0x4e485675534339776457527164545a4e61466c5763464a6d59314a474d57354c626b31475a7a5230616e6c686330527353554e574d574e75646b4e745a6c46554f555a42536d70575a334e45564468365a697446656e6458636b39755a6e6c4b645464304f45565161486c5351556c6a5a45746d524545725130566a5a6d39434d305a58655663334e5459334f57464d4d334a515457396163575a764b32684f656d706f6344677759306c334c304e4361455a696154646a5a33704a5758565961586c6e62307452505430364f766b523753484c3058304f74752f3169577632644b773d, 0x646a5a74646a5a484d6b4a784e31524a4c7a46705a485652516a4e355232317355326c3353697434566a6c744d474572626d31784d6b73795a453833546c704c57545672526d45304b3246545a4646546447527462467079627a525164553032524464324b7a424b656c684862314a74656e7049636e6c78526d6b334e31467a576b784b4d47566f543255335755523263476c494c31643154466459656c5272515842704e3070574d324d78565846354c3363314d3051325630394e61456869556b786e526b5a33505430364f754e4b334d2b742f6f4c752b4c434358474d707765553d, 0x62315672525746435a575643536d70334e475253616c49305a7a4a73646b52534c306435614864335a324e6f54334a5a5245643261446377516e4e555a4563794e464e4a54566c796257393155484579636e6c34516b70584f584534596c5653656e465a57454e6d567974476245784a5a6b77775a573145576e705a5257395159323569566e6c5a536d457761545a5a567a4a4b4e466c77553264435748566e5a584e4b4f454e6b5a5463776157394f556b743252474649517a6c304d3035764e32497257444e42505430364f7369735539527454492b5a6469627249685044494a6b3d, NULL, '2026-01-13 23:00:34.439', '2026-02-10 03:45:08.000', 1);

-- --------------------------------------------------------

--
-- Table structure for table `external_api_providers`
--

CREATE TABLE `external_api_providers` (
  `id` smallint UNSIGNED NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `base_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `docs_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auth_type` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `priority` smallint UNSIGNED NOT NULL DEFAULT '100',
  `timeout_ms` int UNSIGNED NOT NULL DEFAULT '8000',
  `retry_count` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `rate_limit_rpm` int UNSIGNED DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `external_api_providers`
--

INSERT INTO `external_api_providers` (`id`, `name`, `slug`, `base_url`, `docs_url`, `auth_type`, `status`, `priority`, `timeout_ms`, `retry_count`, `rate_limit_rpm`, `created_at`, `updated_at`) VALUES
(1, 'api_ir', 'api_ir', 'https://s.api.ir', 'https://s.api.ir/scalar/v1#tag/switch1', 2, 1, 100, 8000, 1, NULL, '2026-01-13 23:00:06.689', '2026-01-13 23:00:06.689');

-- --------------------------------------------------------

--
-- Table structure for table `external_api_request_logs`
--

CREATE TABLE `external_api_request_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `provider_id` smallint UNSIGNED NOT NULL,
  `credential_id` bigint UNSIGNED DEFAULT NULL,
  `operation` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `http_method` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_id` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `http_status` smallint UNSIGNED DEFAULT NULL,
  `latency_ms` int UNSIGNED DEFAULT NULL,
  `error_code` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_redacted_json` json DEFAULT NULL,
  `response_redacted_json` json DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `external_api_request_logs`
--

INSERT INTO `external_api_request_logs` (`id`, `provider_id`, `credential_id`, `operation`, `http_method`, `url_path`, `request_id`, `http_status`, `latency_ms`, `error_code`, `request_redacted_json`, `response_redacted_json`, `created_at`) VALUES
(1, 1, 1, 'ShahkarLite', 'POST', 'api/sw1/ShahkarLite', NULL, 200, NULL, NULL, '\"{\\\"nationalCode\\\":\\\"1552027384\\\",\\\"mobile\\\":\\\"09129248289\\\",\\\"birthDate\\\":\\\"1380\\\\/10\\\\/04\\\",\\\"serialNumber\\\":\\\"1G34567890\\\",\\\"plateNumber\\\":\\\"32\\\\u0645568\\\\u0627\\\\u06cc\\\\u0631\\\\u0627\\\\u064655\\\"}\"', '\"{\\\"data\\\":false,\\\"success\\\":false,\\\"code\\\":406,\\\"error\\\":null,\\\"message\\\":\\\"no credit on account | شارژ حساب کاربری شما کافی نمی باشد\\\"}\"', '2026-02-10 03:43:55.235'),
(2, 1, 1, 'ShahkarLite', 'POST', 'api/sw1/ShahkarLite', NULL, 200, NULL, NULL, '\"{\\\"nationalCode\\\":\\\"1552027384\\\",\\\"mobile\\\":\\\"09129248289\\\",\\\"birthDate\\\":\\\"1380\\\\/10\\\\/04\\\",\\\"serialNumber\\\":\\\"1G34567890\\\",\\\"plateNumber\\\":\\\"32\\\\u0645568\\\\u0627\\\\u06cc\\\\u0631\\\\u0627\\\\u064655\\\"}\"', '\"{\\\"data\\\":false,\\\"success\\\":true,\\\"code\\\":0,\\\"error\\\":null,\\\"message\\\":null}\"', '2026-02-10 03:45:14.858'),
(3, 1, 1, 'Shahkar2', 'POST', '/api/sw1/Shahkar2', NULL, 200, NULL, NULL, '\"{\\\"nationalCode\\\":\\\"1552027384\\\",\\\"mobile\\\":\\\"09129248289\\\",\\\"birthDate\\\":\\\"1380\\\\/10\\\\/04\\\",\\\"serialNumber\\\":\\\"1G34567890\\\",\\\"plateNumber\\\":\\\"32\\\\u0645568\\\\u0627\\\\u06cc\\\\u0631\\\\u0627\\\\u064655\\\"}\"', '\"{\\\"data\\\":null,\\\"success\\\":false,\\\"code\\\":404,\\\"error\\\":null,\\\"message\\\":\\\"service is not active | سرویس فعال نمی باشد\\\"}\"', '2026-02-10 03:48:09.146'),
(4, 1, 1, 'PersonImage', 'POST', '/api/sw1/PersonImage', NULL, 200, NULL, NULL, '\"{\\\"nationalCode\\\":\\\"1552027384\\\",\\\"mobile\\\":\\\"09129248289\\\",\\\"birthDate\\\":\\\"1380\\\\/10\\\\/04\\\",\\\"serialNumber\\\":\\\"1G34567890\\\",\\\"plateNumber\\\":\\\"32\\\\u0645568\\\\u0627\\\\u06cc\\\\u0631\\\\u0627\\\\u064655\\\"}\"', '\"{\\\"data\\\":null,\\\"success\\\":false,\\\"code\\\":500,\\\"error\\\":null,\\\"message\\\":\\\"فراخوانی نا موفق مرجع |  باز گشت هزینه فراخوانی\\\"}\"', '2026-02-10 03:51:56.831'),
(5, 1, 1, 'PersonImage', 'POST', '/api/sw1/PersonImage', NULL, 200, NULL, NULL, '\"{\\\"nationalCode\\\":\\\"1552027384\\\",\\\"mobile\\\":\\\"09129248289\\\",\\\"birthDate\\\":\\\"1380\\\\/10\\\\/04\\\",\\\"serialNumber\\\":\\\"1G39352909\\\",\\\"plateNumber\\\":\\\"32\\\\u0645568\\\\u0627\\\\u06cc\\\\u0631\\\\u0627\\\\u064655\\\"}\"', '\"{\\\"data\\\":null,\\\"success\\\":false,\\\"code\\\":500,\\\"error\\\":null,\\\"message\\\":\\\"فراخوانی نا موفق مرجع |  باز گشت هزینه فراخوانی\\\"}\"', '2026-02-10 04:00:33.790'),
(6, 1, 1, 'PersonImage', 'POST', '/api/sw1/PersonImage', NULL, 200, NULL, NULL, '\"{\\\"nationalCode\\\":\\\"1552027384\\\",\\\"mobile\\\":\\\"09129248289\\\",\\\"birthDate\\\":\\\"1380\\\\/10\\\\/04\\\",\\\"serialNumber\\\":\\\"1G39352909\\\",\\\"plateNumber\\\":\\\"32\\\\u0645568\\\\u0627\\\\u06cc\\\\u0631\\\\u0627\\\\u064655\\\"}\"', '\"{\\\"data\\\":null,\\\"success\\\":false,\\\"code\\\":500,\\\"error\\\":null,\\\"message\\\":\\\"فراخوانی نا موفق مرجع |  باز گشت هزینه فراخوانی\\\"}\"', '2026-02-10 04:04:26.920'),
(7, 1, 1, 'PersonImage', 'POST', '/api/sw1/PersonImage', NULL, 200, NULL, NULL, '\"{\\\"nationalCode\\\":\\\"1552027384\\\",\\\"mobile\\\":\\\"09129248289\\\",\\\"birthDate\\\":\\\"1380\\\\/10\\\\/04\\\",\\\"serialNumber\\\":\\\"1G39352909\\\",\\\"plateNumber\\\":\\\"32\\\\u0645568\\\\u0627\\\\u06cc\\\\u0631\\\\u0627\\\\u064655\\\"}\"', '\"{\\\"data\\\":null,\\\"success\\\":false,\\\"code\\\":500,\\\"error\\\":null,\\\"message\\\":\\\"فراخوانی نا موفق مرجع |  باز گشت هزینه فراخوانی\\\"}\"', '2026-02-10 04:04:43.721'),
(8, 1, 1, 'ShahkarLite', 'POST', 'api/sw1/ShahkarLite', NULL, 0, NULL, NULL, '\"{\\\"nationalCode\\\":\\\"1552027384\\\",\\\"mobile\\\":\\\"09129248289\\\",\\\"birthDate\\\":\\\"1380\\\\/10\\\\/04\\\",\\\"serialNumber\\\":\\\"1G39352909\\\",\\\"plateNumber\\\":\\\"32\\\\u0645568\\\\u0627\\\\u06cc\\\\u0631\\\\u0627\\\\u064655\\\"}\"', 'false', '2026-02-10 04:08:53.856'),
(9, 1, 1, 'ShahkarLite', 'POST', 'api/sw1/ShahkarLite', NULL, 200, NULL, NULL, '\"{\\\"nationalCode\\\":\\\"1552027384\\\",\\\"mobile\\\":\\\"09129248289\\\",\\\"birthDate\\\":\\\"1380\\\\/10\\\\/04\\\",\\\"serialNumber\\\":\\\"1G39352909\\\",\\\"plateNumber\\\":\\\"32\\\\u0645568\\\\u0627\\\\u06cc\\\\u0631\\\\u0627\\\\u064655\\\"}\"', '\"{\\\"data\\\":false,\\\"success\\\":true,\\\"code\\\":0,\\\"error\\\":null,\\\"message\\\":null}\"', '2026-02-10 04:09:01.685'),
(10, 1, 1, 'PersonImage', 'POST', '/api/sw1/PersonImage', NULL, 200, NULL, NULL, '\"{\\\"nationalCode\\\":\\\"0312449348\\\",\\\"mobile\\\":\\\"09129248289\\\",\\\"birthDate\\\":\\\"1380\\\\/10\\\\/04\\\",\\\"serialNumber\\\":\\\"1G39352909\\\",\\\"plateNumber\\\":\\\"32\\\\u0645568\\\\u0627\\\\u06cc\\\\u0631\\\\u0627\\\\u064655\\\"}\"', '\"{\\\"data\\\":{\\\"imageBase64\\\":\\\"/9j/4AAQSkZJRgABAQECWAJYAAD/2wBDACAWGBwYFCAcGhwkIiAmMFA0MCwsMGJGSjpQdGZ6eHJmcG6AkLicgIiuim5woNqirr7EztDOfJri8uDI8LjKzsb/2wBDASIkJDAqMF40NF7GhHCExsbGxsbGxsbGxsbGxsbGxsbGxsbGxsbGxsbGxsbGxsbGxsbGxsbGxsbGxsbGxsbGxsb/wAARCAEsAOEDASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwDoKKKKACiiigAoopCQOtAC0UisG6UtABRSbhux3o3DdjvQAtFFFABRRSFgCM96AFooooAKKKKACik3DOKWgAoopCcDJoAWikHIpaACikzQCCSPSgBaKKKACiiigApFbcKCQOpxTVI+Y9qADfz049acfTODUZ9FbPtTtrZzu5oABhc80jA7skZFGw/3qXa396gBwwRx0paQDAxRn5se1ADT/rB7igf6xvpSt95fXNLj5s0AIzYPTOOtBbGMDOaaxGSQ3XtQFfA+bFADg4PXg+9I3VfrShB35PvQRnHtQA6k6gEHFLTNrD7rcUAO6Dk0ZApuwn7zUhU93+lADjgMBjrTqaFOcsc0p6UAHOenFI3KHFKCCuc01B8pHagBQcID7UK27jGDTdhxjdxSgEHcxoAXID4xyaQf6xqCQzDHOKVlJOQcUALkc+1Ju5A9RTdjdN3B60oU5BJzigB9FFFACEA9RS0UUAIAB0FLRRQAUUUhOOtAC0jKG60Bg3SloAQKF6ClpNw3Y71FcXMVuheVtoH60AS4A5wPrVKbVrOEkGXcR2UZrC1DVprtmSMlIvQdT9az8470Abdx4gbOLeMADu3U05PEXyLvgy3cg8Vg5pQCwz6UAdAfEMe4YiO3vnrVmLW7ORwu5lyeCw4rlc+1FAHdK6tjawOfQ0pAPWuLtbya0k3xNj1B6GtzT9aE0uy4ITd909hQBs0U0OpOAc06gBvlrnpTqKQnAyaAFopByKWgBAAOgpaKQEEkelAC0UUUAFFFFABSK24UEgdTimrjLHtQAu/npx60p6Yzg1GfRWz7U7a+c7hmgBRhc88U1h82SMijY3973pcP/eoAcMY46UtVry5WytjIeccAeprnbrVLm5UguVXPReKANi/1SK2JERDyAY9hWBd3U1188r5OfwFVwThs+nU0hOUA75oAVUyOvXpSKmScnGKeoOMFenekLJnpzQA1kI96VMhW+lBdv4eBSBsZz1NACdaUYUndzSDrUm5SOQc+1ADCNx+UUAHPHWnB1BwFpQR0C0Aaui6gImMc5+U4wx7V0QORkVxORjgVoaZqclqwjkJaI8Y/u0AdNznpx60jcoaEYOgYEEEZzSIPlI7UAKDhAfahW3e1N2NjG7ilAIO5iKAFyA+McmkH+sagkFhjkilZTnKnFAC5H5Um4ZA9Rmm7G5G7g9aUKcgk5xQA+iiigBCAeopaKKAEAA6CloooAKKOtMlkEaMxPQZoA5vXrrzbvywflj4/GssOV6VLcsXlZycliTUFADixbr0ppHpTtvy7s0FcKD60AJvbGM02lxSgUAIDTgfWlAPpUqRE84pXHYi256UoUjrVlIR3FSCEelTzFqBRZCTxRhlq/wCUKY0WaOYOQpZ4pd1SSQ7elQkYqiGrG1ouoAOLec/Kx+Q+h9K6EVwq9eK7KynMtjFK3Urz9aYizRRRQAgAHQUtICD07UBsk+1AC0UUUAFFFFABTVbcuaCwXrSLwCSMA0AAY5GRwelOPPGcVHjJwpOP5U7Yf7xoAAQBxnk0ybCI7soZQMkU/wAv/aPtUF7lLOVtxOFNAHIzNulYgYyelRU5+TQKABUJqRYc9aWOrC1LZcUNS3XHNSCBR2qRBmpABUXZokiHyh6CpAoA6U/FGKm5Q0CgLTwKcFoAixSbasFeKjIxQBA6AjpVKVcGtE8iqky81UWRJFUAZrptGffpu3OShxj0rmyOa0dDnMd6IyfllG0j3rUxOlzhc0K2cgjBFJ5f+0fagDbkk5oAUH5yMUi/eagEMwI7d6Urk5BIoAUsACfSk3fNj2pPL7bjilC4OSc0AOooooAKKKKACiiigAqpqYLafMB121aBz0qC9Aa1kU916UAcY+d3NC9akuQBKcdKbGMmgaJYxU6jFMQYqSs2aokSpQKiSpgKkoKKULRtpDAU8U0CnBaBCtyKjNSYphoAYagmTjNTmmNg8U0DM5+DVjTFJ1GED++KiuQFerWjrnUUbGQMn9K1Rg9zqqKQHIBpaYgopMgkj0oDZJHpQAtFFFABRRRQAU1W3A0rMF601e7Y60AG45zj5TTjjp61HgMcLmneX/tHNAACBkjPXFJImQ3fI6Uvlj1NHl/7RoA5G9VQ7bQQAaZbp8m71q7qUMaGRQeVOarW/MH0pMpbjWcJTPNJPoKCu5jmnrECKWhWoLcFT0zUyXq/xKaj+z8cGomiYGloGqNJJ1fpUuQRWVGxUjPFWlmPrxUtFJlzikLgDk1Cztt4qq2TxkmiwNlp7yMNhcsfaoWuif4f1qARHNTrbjFPQnUiM7ZyM1JHOGbaeDUnlKBTHhBGR1FGg7NFa9/1i/StDw+m6eVv7qVn3n31+lbfh2PbbSOf4mx+VWtiJbmsDhAfahWJOCMGk8sepoC7eck0yRc/Pj1FIP8AWNRncwwOB3pWXJzkigBdw59qTd8wGOopPLHqcUoTBzkmgB1FFFABRRRQAUUUUAFIelAOelIzAdTQBydy+8uxXB5BH41Han92w96uX0ai5lHQFqp24wzj0qDVrW4rHbUYck4GSfQVMyg9aRY9pyOKAsyMTNuA6HOOWp4du4yM4z2z9acYFdsspyfSp1T915XIT0ouhJMrMu4ZAqSFd3FSFdi7R+GaLdTvqbl2Jwm2OomTvVlwdtNXoRU3GUJJSn3R+NIksrDIZT823GasvD8u3kg9aZHbJGwdVO4HIJq1YhpjWZ4m2ygqfU8g1Mh3DNJIjSNl2Jp6IFHFJ2GrlO5UyXKoO9WhI9oU8roOp9ajVA9+c9FXNWJkDDjrmhscYp3ub8b741f1GafUcC7IUX0UVJWpgFFJkZx6UBskj0oAWiiigAooooAKarbgaVmC9aavdscGgA3HOcfKaccdPWo8AnCk07y/9o5oAAQMkZ64oZTu3DB9qPL9zRs/2jQBgayd14cDHAqnAmHf6Vqa1bFSJ16Hgis+1bc+3HBzUdTXoJjmnqBTG+ViKepzUspEoxTqYtOzikMjk60sAw9Mc/NU0K85oAmb7tMxzUhHFRE80hjtuaXFKDlaTFMQhUUxuKeTUbHJoAiRCZpH+gq9aw+dICeijJqkj/wryTyfatvT4tkGT1b+VNK7FJ8sSyDhAfahWJOCMGk8sepoC7TkkmtTAXPz4x1FIP8AWNRkMwx270rLk5yQaAFyOfak3fMBjqKTy/8AaOKUJg5yTQA6iiigAooooAKKKKACikByMikLgcdT6UAU9YUtYMR/CQawIARMpJrqWUSxsrrw3GKqJpcKNuyxPbPapaLi1bUxJ/8AWGkQ81JdLtkYehxUSnFSykWFoc4FNQ0pGakshHzMfQVbgZfWqjowBK9aiikkRstTsK5rsyhagfrkVC0jSJ+7IB96dD5rD94AB6jvSsBOtLSdKCaQxjmozT2NOtoTPLsU475poTGxZDAHpnniugUYAAqhDp7LKGkIIHOB3q8x2rmtIqxnNp7DqKKKogKKQHJPtSK2SfagB1FFFABRRRQAU1WyvPUUM2OxNAyASRye1ACBjkEkYP6UpwflNM2hjwCBTvLX3oAAw7DqaCCCSp/Cjy1o8taAHA5GaWiigDn9TTZdP78iqNbWsRZVZR24NYp61DNIskRqkDVXBxStJtFTYq5MTmhYwagFwnepUvEU4IOKLMCWNcEjFSZIqFrqMfdBNJ9oVuxH4UDJ92aaTUCzAnHP5VJuzSAUmr+kpmR39Bis6tvTY/LtQT1bmqiTN6Fumv8AcNOorQyGlsKPXtQpOSG6ik8taUKE5AJoAMnfjtjikXh2oBLMDjAFKyBjk0ABYYJ9KMnfjtik8taUIFORQA6iiigAooooAKKKQnA5oAWikVs0hY5wBk0AOopFzj5utLQBV1HAs2JGQCK5+VMHI5U9DW/qf/Hi/wCFc6JSoKnlT+lJlIYeKctIxDjI6ikWpZaJMjOcVJ50IHzAflUYj3U77MpqShyXMIPC8/SpPNV6j+zLxxUggAHFDDUQ8jkUz7tPIxTDSAltkEs6KehPNdEAAMDoKwbH/j6i/wB6t+tImUgooprEhSRVEjqKQcgUtABRSAnJ44pASXYelADqKKKACiiigApqZ5B6ilbPYZpACAT3NADN3O7dz3FO3jOMijaWPIAFLtX0FADd/HUdaG2HnOD60/aPQUbR6CgAU5UE0tFFAFPVf+PF/qK5xhkVv6vKotjHn5jzj2rBqWXHYgOVORTlfJpzLmozH6cUh2LKSVYRwRWdll609ZsUrFKRph1pCwqgLinCYt0GaVmO6J5HGKZGpY5PSkVSx+b8qsAbRSAktOLqL/eFb1YFuwW5jY8AMK360jsZz3CkYZU0tFUQR7wEGDzQh+bGc0/aPQUmMfdAzQAHIcehpMhZDnjIpQGJy3GO1KQD1GaAG+YMH9KA2WXnPFO2r6CjABzigBaKKKACiiigAoopDxQAtFIrA0hbnAGTQA6mSyLFG0jnCqMmlXOPm61g67f7nNtGflX7x9T6UARyTNcRPO3/AC0fj2A6VWq5bxiXT0Qfe25H1qqBUM0iMIpKl20xl5qSxOKb5ak9KeAaUJRcQqRqvapcDsKaq0+lcdhUHNSUxafSGMwGYKeh4rU0q5MsBic5lhO1vf0NZg++o9SBUL3TWWrSOnTd8w9RWkDOZ1FFV7e9t7gDy5VJP8OeamY4UkVZmOopAcgUtABRSAnJ4pASWI9KAHUUUUAFFFFABTUJ5B6ilYkdBmkAIBJ6mgBm7ndu57inlxnGRTD6uAoHU1nXmsW8OVhUSN69hQBotKqKWZlUZ6ms671iFCRApkb16CsO5vZrlsyNx6DoKrlye9AGhc6vdTcb9i+i8VnkknJpM05RuXGcGgDa045tIiO2RSXkJDGVR8v8Xt70mlc2+3+61aGARyAc8GpaKTMj8aQip7i3aE7lGYz+lQkDrUbGi1EUVIF6VCcg8U8SH0pDJsYptN8ylHNAxwOBSk0VFI5yFQZZuABQhE1qDJdDH3U5P17Vn6k2b+XHrj9K27S3+zQ7WOXPLH3rnZn3zu/qxNapWMm7grsMAHBz1rQt9YuIMJIfNT/a6/nWeVBOc8U1zk8UyTrbTUYbiMbW2v02t1q0h+bGc1xAdh3NaNlrE9uwDnzE9D1/OgDpySHHoaTIEhycZFVLXUre6YAOFb+6eKukA9RQA3zBg/pQGyw5zxS7V9BShQDkCgBaKKKAEJAGScCs281mCDKxfvH9ulYM99cT8SSsR6ZqsTQBcu9RuLrh3+X+6OBVMmkoAycUAFJSsMU4KAAWoAZRSsQTwMCkoA1dFf8A1ifQ1rCsTSnCXCjs/BraXO4+lIY4qCCCMg9RWfc2xh+ZMmP+VaQNGARg8g9RSauNOxhk0A1avLTy/nj+4eo9Kq4xUWNb3HCnriogakjVpW2oMmlYLhJIAOOTVyxtDH++lH709Af4R/jT7a0SH52O6TsewqyatKxnKVyvey+TayP328VzNbGsy4jWPP3jniserICiinIMtzQA2ig9TRQA4HFXrfVrqBQok3KOzDNUDjAxmnNgBcCgDpbPWoJ8LN+6f36GtJHV1yjBh6g5rhs1NBdTW7bopGQ+1AHa0Vy39t3n/PQf98iigDOzStgkHsaRdvegsC3I4oAeQSCOMdqbsOPegsAMLTcn1NAD9vJ47cUAMOuMVHk+poyT3oAGxuOKMUU9dpUAnGKAJ7E/vlHviugiO7OeveubiYRyoQejA103QBhSGMbgk857Uu9W6k0srFULcFaqxXBaQArtycYpXHYtjOOmR71mXiJHLhDwecelaDPhfc8DFV5hEQI52GexA5FDVwTsVra0e4ORwvqa1IhHCgRBj196qI0cMiCJht6dc1dJGKEht3Gl89KQuPw708U1gccUyTB1Jy87MenQVR71d1MYnA9s1TBwRTEHJwpPGacBtkoIXrmms2TQA8J82TSP0BPWmZPrSg8/Nk0AO4MfuKXG5RimsRjC0mSOhoAeE5HpQwwnIxzTMn1NJk+tABRRRQAUUUUAFFFFABSYpaKACiiigAFdXA3mW8beqg1ygrpdNJNjDn+7QBYABBRuhqJbRVfdnPOelTetLn939aRSKxP+lquOACR9agntpHkY4yCcirUoGxH/AIhUyqMZosFyglt5eMjJNFzK4by14xj8TV5wDxTDGjYZlBI70gTFhJMSbvvY5p5HFCUp70xHN6p/x+EegFU6s6gc3sv1qtTEFFFFABRRRQAUUUUAFFFFABRRRQB//9kAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=\\\"},\\\"success\\\":true,\\\"code\\\":0,\\\"error\\\":null,\\\"message\\\":null}\"', '2026-02-10 04:12:05.894'),
(11, 1, 1, 'ShahkarLite', 'POST', 'api/sw1/ShahkarLite', NULL, 0, NULL, NULL, '\"{\\\"nationalCode\\\":\\\"0312449348\\\",\\\"mobile\\\":\\\"09129248289\\\",\\\"birthDate\\\":\\\"1380\\\\/10\\\\/04\\\",\\\"serialNumber\\\":\\\"1G39352909\\\",\\\"plateNumber\\\":\\\"32\\\\u0645568\\\\u0627\\\\u06cc\\\\u0631\\\\u0627\\\\u064655\\\"}\"', 'false', '2026-02-10 04:12:51.582'),
(12, 1, 1, 'ShahkarLite', 'POST', 'api/sw1/ShahkarLite', NULL, 200, NULL, NULL, '\"{\\\"nationalCode\\\":\\\"0312449348\\\",\\\"mobile\\\":\\\"09129248289\\\",\\\"birthDate\\\":\\\"1380\\\\/10\\\\/04\\\",\\\"serialNumber\\\":\\\"1G39352909\\\",\\\"plateNumber\\\":\\\"32\\\\u0645568\\\\u0627\\\\u06cc\\\\u0631\\\\u0627\\\\u064655\\\"}\"', '\"{\\\"data\\\":true,\\\"success\\\":true,\\\"code\\\":0,\\\"error\\\":null,\\\"message\\\":null}\"', '2026-02-10 04:12:59.967');

-- --------------------------------------------------------

--
-- Table structure for table `field_settings`
--

CREATE TABLE `field_settings` (
  `id` smallint UNSIGNED NOT NULL,
  `field_key` varchar(96) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'user, driver, company',
  `field_label` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` smallint UNSIGNED NOT NULL DEFAULT '100',
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `field_settings`
--

INSERT INTO `field_settings` (`id`, `field_key`, `entity_type`, `field_label`, `is_required`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'full_name', 'user', 'نام و نام خانوادگی', 1, 1, 10, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(2, 'phone', 'user', 'شماره موبایل', 1, 1, 20, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(3, 'code_meli', 'user', 'کد ملی', 0, 1, 30, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(4, 'birth_date', 'user', 'تاریخ تولد', 0, 1, 40, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(5, 'gender', 'user', 'جنسیت', 0, 1, 50, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(6, 'avatar', 'user', 'عکس پروفایل', 0, 1, 60, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(7, 'plate_number', 'driver', 'پلاک ماشین', 1, 1, 10, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(8, 'vehicle_type_id', 'driver', 'نوع وسیله نقلیه', 1, 1, 20, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(9, 'national_card_image', 'driver', 'عکس کارت ملی', 1, 1, 30, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(10, 'license_image', 'driver', 'عکس گواهینامه', 1, 1, 40, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(11, 'license_serial', 'driver', 'شماره گواهینامه', 1, 1, 50, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(12, 'license_base', 'driver', 'پایه گواهینامه', 0, 1, 60, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(13, 'vehicle_card_image', 'driver', 'عکس کارت ماشین', 1, 1, 70, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(14, 'green_card_image', 'driver', 'عکس برگه سبز', 1, 1, 80, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(15, 'insurance_image', 'driver', 'عکس بیمه نامه خودرو', 1, 1, 90, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(16, 'insurance_number', 'driver', 'شماره بیمه نامه', 1, 1, 100, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(17, 'insurance_expiry', 'driver', 'تاریخ اتمام بیمه', 1, 1, 110, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(18, 'vin_number', 'driver', 'شماره VIN', 0, 1, 120, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(19, 'engine_number', 'driver', 'شماره موتور', 0, 1, 130, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(20, 'chassis_number', 'driver', 'شماره شاسی', 0, 1, 140, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(21, 'issued_from', 'driver', 'صادره از', 0, 1, 150, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(22, 'address', 'driver', 'آدرس', 0, 1, 160, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(23, 'home_phone', 'driver', 'شماره منزل', 0, 1, 170, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(24, 'postal_code', 'driver', 'کد پستی', 0, 1, 180, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(25, 'extra_phones', 'driver', 'شماره موبایل اضافه', 0, 1, 190, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(26, 'verification_video', 'driver', 'ویدئو احراز هویت', 0, 1, 200, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(27, 'company_name', 'company', 'نام شرکت', 1, 1, 10, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(28, 'national_card_image', 'company', 'عکس کارت ملی', 1, 1, 20, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(29, 'registration_no', 'company', 'شماره ثبت', 1, 1, 30, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(30, 'registration_date', 'company', 'تاریخ ثبت', 0, 1, 40, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(31, 'economic_code', 'company', 'شماره اقتصادی', 1, 1, 50, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(32, 'address', 'company', 'آدرس', 0, 1, 60, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860'),
(33, 'postal_code', 'company', 'کد پستی', 0, 1, 70, '2026-02-08 21:19:12.860', '2026-02-08 21:19:12.860');

-- --------------------------------------------------------

--
-- Table structure for table `identity_verification_jobs`
--

CREATE TABLE `identity_verification_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `subject_user_id` bigint UNSIGNED NOT NULL,
  `subject_kind` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `check_type` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_id` smallint UNSIGNED NOT NULL,
  `credential_id` bigint UNSIGNED DEFAULT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '1=pending,2=success,3=retryable,4=failed,5=canceled',
  `request_id` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_redacted_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `response_redacted_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `started_at` datetime(3) DEFAULT NULL,
  `finished_at` datetime(3) DEFAULT NULL,
  `created_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `identity_verification_jobs`
--

INSERT INTO `identity_verification_jobs` (`id`, `subject_user_id`, `subject_kind`, `check_type`, `provider_id`, `credential_id`, `status`, `request_id`, `request_redacted_json`, `response_redacted_json`, `started_at`, `finished_at`, `created_by_user_id`, `created_at`) VALUES
(1, 32, 1, 'ShahkarLite', 1, NULL, 4, NULL, '{\"nationalCode\":\"1552027384\",\"mobile\":\"09129248289\",\"birthDate\":\"1380\\/10\\/04\",\"serialNumber\":\"1G34567890\",\"plateNumber\":\"32م568ایران55\"}', '{\"success\":false,\"error\":\"اعتبارنامه فعال برای api_ir یافت نشد\"}', '2026-02-10 03:42:38.873', '2026-02-10 03:42:38.910', 1, '2026-02-10 03:42:38.873'),
(2, 32, 1, 'ShahkarLite', 1, NULL, 2, NULL, '{\"nationalCode\":\"1552027384\",\"mobile\":\"09129248289\",\"birthDate\":\"1380\\/10\\/04\",\"serialNumber\":\"1G34567890\",\"plateNumber\":\"32م568ایران55\"}', '{\"success\":false,\"code\":406,\"data\":false,\"error\":null,\"message\":\"no credit on account | شارژ حساب کاربری شما کافی نمی باشد\"}', '2026-02-10 03:43:54.864', '2026-02-10 03:43:55.243', 1, '2026-02-10 03:43:54.864'),
(3, 32, 1, 'ShahkarLite', 1, NULL, 2, NULL, '{\"nationalCode\":\"1552027384\",\"mobile\":\"09129248289\",\"birthDate\":\"1380\\/10\\/04\",\"serialNumber\":\"1G34567890\",\"plateNumber\":\"32م568ایران55\"}', '{\"success\":true,\"code\":0,\"data\":false,\"error\":null,\"message\":null}', '2026-02-10 03:45:12.831', '2026-02-10 03:45:14.861', 1, '2026-02-10 03:45:12.831'),
(4, 32, 1, 'Shahkar2', 1, NULL, 2, NULL, '{\"nationalCode\":\"1552027384\",\"mobile\":\"09129248289\",\"birthDate\":\"1380\\/10\\/04\",\"serialNumber\":\"1G34567890\",\"plateNumber\":\"32م568ایران55\"}', '{\"success\":false,\"code\":404,\"data\":null,\"error\":null,\"message\":\"service is not active | سرویس فعال نمی باشد\"}', '2026-02-10 03:48:09.015', '2026-02-10 03:48:09.148', 1, '2026-02-10 03:48:09.015'),
(5, 32, 1, 'PersonImage', 1, NULL, 2, NULL, '{\"nationalCode\":\"1552027384\",\"mobile\":\"09129248289\",\"birthDate\":\"1380\\/10\\/04\",\"serialNumber\":\"1G34567890\",\"plateNumber\":\"32م568ایران55\"}', '{\"success\":false,\"code\":500,\"data\":null,\"error\":null,\"message\":\"فراخوانی نا موفق مرجع |  باز گشت هزینه فراخوانی\"}', '2026-02-10 03:51:55.438', '2026-02-10 03:51:56.834', 1, '2026-02-10 03:51:55.438'),
(6, 32, 1, 'PersonImage', 1, NULL, 2, NULL, '{\"nationalCode\":\"1552027384\",\"mobile\":\"09129248289\",\"birthDate\":\"1380\\/10\\/04\",\"serialNumber\":\"1G39352909\",\"plateNumber\":\"32م568ایران55\"}', '{\"success\":false,\"code\":500,\"data\":null,\"error\":null,\"message\":\"فراخوانی نا موفق مرجع |  باز گشت هزینه فراخوانی\"}', '2026-02-10 04:00:27.067', '2026-02-10 04:00:33.798', 1, '2026-02-10 04:00:27.067'),
(7, 32, 1, 'PersonImage', 1, NULL, 2, NULL, '{\"nationalCode\":\"1552027384\",\"mobile\":\"09129248289\",\"birthDate\":\"1380\\/10\\/04\",\"serialNumber\":\"1G39352909\",\"plateNumber\":\"32م568ایران55\"}', '{\"success\":false,\"code\":500,\"data\":null,\"error\":null,\"message\":\"فراخوانی نا موفق مرجع |  باز گشت هزینه فراخوانی\"}', '2026-02-10 04:04:23.230', '2026-02-10 04:04:26.925', 1, '2026-02-10 04:04:23.230'),
(8, 32, 1, 'PersonImage', 1, NULL, 2, NULL, '{\"nationalCode\":\"1552027384\",\"mobile\":\"09129248289\",\"birthDate\":\"1380\\/10\\/04\",\"serialNumber\":\"1G39352909\",\"plateNumber\":\"32م568ایران55\"}', '{\"success\":false,\"code\":500,\"data\":null,\"error\":null,\"message\":\"فراخوانی نا موفق مرجع |  باز گشت هزینه فراخوانی\"}', '2026-02-10 04:04:38.600', '2026-02-10 04:04:43.724', 1, '2026-02-10 04:04:38.600'),
(9, 32, 1, 'ShahkarLite', 1, NULL, 4, NULL, '{\"nationalCode\":\"1552027384\",\"mobile\":\"09129248289\",\"birthDate\":\"1380\\/10\\/04\",\"serialNumber\":\"1G39352909\",\"plateNumber\":\"32م568ایران55\"}', '{\"success\":false,\"error\":\"خطای cURL: Resolving timed out after 8015 milliseconds\"}', '2026-02-10 04:08:45.821', '2026-02-10 04:08:56.362', 1, '2026-02-10 04:08:45.821'),
(10, 32, 1, 'ShahkarLite', 1, NULL, 2, NULL, '{\"nationalCode\":\"1552027384\",\"mobile\":\"09129248289\",\"birthDate\":\"1380\\/10\\/04\",\"serialNumber\":\"1G39352909\",\"plateNumber\":\"32م568ایران55\"}', '{\"success\":true,\"code\":0,\"data\":false,\"error\":null,\"message\":null}', '2026-02-10 04:08:59.635', '2026-02-10 04:09:01.688', 1, '2026-02-10 04:08:59.635'),
(11, 32, 1, 'PersonImage', 1, NULL, 2, NULL, '{\"nationalCode\":\"0312449348\",\"mobile\":\"09129248289\",\"birthDate\":\"1380\\/10\\/04\",\"serialNumber\":\"1G39352909\",\"plateNumber\":\"32م568ایران55\"}', '{\"success\":true,\"code\":0,\"data\":{\"imageBase64\":\"\\/9j\\/4AAQSkZJRgABAQECWAJYAAD\\/2wBDACAWGBwYFCAcGhwkIiAmMFA0MCwsMGJGSjpQdGZ6eHJmcG6AkLicgIiuim5woNqirr7EztDOfJri8uDI8LjKzsb\\/2wBDASIkJDAqMF40NF7GhHCExsbGxsbGxsbGxsbGxsbGxsbGxsbGxsbGxsbGxsbGxsbGxsbGxsbGxsbGxsbGxsbGxsb\\/wAARCAEsAOEDASIAAhEBAxEB\\/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL\\/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6\\/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL\\/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6\\/9oADAMBAAIRAxEAPwDoKKKKACiiigAoopCQOtAC0UisG6UtABRSbhux3o3DdjvQAtFFFABRRSFgCM96AFooooAKKKKACik3DOKWgAoopCcDJoAWikHIpaACikzQCCSPSgBaKKKACiiigApFbcKCQOpxTVI+Y9qADfz049acfTODUZ9FbPtTtrZzu5oABhc80jA7skZFGw\\/3qXa396gBwwRx0paQDAxRn5se1ADT\\/rB7igf6xvpSt95fXNLj5s0AIzYPTOOtBbGMDOaaxGSQ3XtQFfA+bFADg4PXg+9I3VfrShB35PvQRnHtQA6k6gEHFLTNrD7rcUAO6Dk0ZApuwn7zUhU93+lADjgMBjrTqaFOcsc0p6UAHOenFI3KHFKCCuc01B8pHagBQcID7UK27jGDTdhxjdxSgEHcxoAXID4xyaQf6xqCQzDHOKVlJOQcUALkc+1Ju5A9RTdjdN3B60oU5BJzigB9FFFACEA9RS0UUAIAB0FLRRQAUUUhOOtAC0jKG60Bg3SloAQKF6ClpNw3Y71FcXMVuheVtoH60AS4A5wPrVKbVrOEkGXcR2UZrC1DVprtmSMlIvQdT9az8470Abdx4gbOLeMADu3U05PEXyLvgy3cg8Vg5pQCwz6UAdAfEMe4YiO3vnrVmLW7ORwu5lyeCw4rlc+1FAHdK6tjawOfQ0pAPWuLtbya0k3xNj1B6GtzT9aE0uy4ITd909hQBs0U0OpOAc06gBvlrnpTqKQnAyaAFopByKWgBAAOgpaKQEEkelAC0UUUAFFFFABSK24UEgdTimrjLHtQAu\\/npx60p6Yzg1GfRWz7U7a+c7hmgBRhc88U1h82SMijY3973pcP\\/eoAcMY46UtVry5WytjIeccAeprnbrVLm5UguVXPReKANi\\/1SK2JERDyAY9hWBd3U1188r5OfwFVwThs+nU0hOUA75oAVUyOvXpSKmScnGKeoOMFenekLJnpzQA1kI96VMhW+lBdv4eBSBsZz1NACdaUYUndzSDrUm5SOQc+1ADCNx+UUAHPHWnB1BwFpQR0C0Aaui6gImMc5+U4wx7V0QORkVxORjgVoaZqclqwjkJaI8Y\\/u0AdNznpx60jcoaEYOgYEEEZzSIPlI7UAKDhAfahW3e1N2NjG7ilAIO5iKAFyA+McmkH+sagkFhjkilZTnKnFAC5H5Um4ZA9Rmm7G5G7g9aUKcgk5xQA+iiigBCAeopaKKAEAA6CloooAKKOtMlkEaMxPQZoA5vXrrzbvywflj4\\/GssOV6VLcsXlZycliTUFADixbr0ppHpTtvy7s0FcKD60AJvbGM02lxSgUAIDTgfWlAPpUqRE84pXHYi256UoUjrVlIR3FSCEelTzFqBRZCTxRhlq\\/wCUKY0WaOYOQpZ4pd1SSQ7elQkYqiGrG1ouoAOLec\\/Kx+Q+h9K6EVwq9eK7KynMtjFK3Urz9aYizRRRQAgAHQUtICD07UBsk+1AC0UUUAFFFFABTVbcuaCwXrSLwCSMA0AAY5GRwelOPPGcVHjJwpOP5U7Yf7xoAAQBxnk0ybCI7soZQMkU\\/wAv\\/aPtUF7lLOVtxOFNAHIzNulYgYyelRU5+TQKABUJqRYc9aWOrC1LZcUNS3XHNSCBR2qRBmpABUXZokiHyh6CpAoA6U\\/FGKm5Q0CgLTwKcFoAixSbasFeKjIxQBA6AjpVKVcGtE8iqky81UWRJFUAZrptGffpu3OShxj0rmyOa0dDnMd6IyfllG0j3rUxOlzhc0K2cgjBFJ5f+0fagDbkk5oAUH5yMUi\\/eagEMwI7d6Urk5BIoAUsACfSk3fNj2pPL7bjilC4OSc0AOooooAKKKKACiiigAqpqYLafMB121aBz0qC9Aa1kU916UAcY+d3NC9akuQBKcdKbGMmgaJYxU6jFMQYqSs2aokSpQKiSpgKkoKKULRtpDAU8U0CnBaBCtyKjNSYphoAYagmTjNTmmNg8U0DM5+DVjTFJ1GED++KiuQFerWjrnUUbGQMn9K1Rg9zqqKQHIBpaYgopMgkj0oDZJHpQAtFFFABRRRQAU1W3A0rMF601e7Y60AG45zj5TTjjp61HgMcLmneX\\/tHNAACBkjPXFJImQ3fI6Uvlj1NHl\\/7RoA5G9VQ7bQQAaZbp8m71q7qUMaGRQeVOarW\\/MH0pMpbjWcJTPNJPoKCu5jmnrECKWhWoLcFT0zUyXq\\/xKaj+z8cGomiYGloGqNJJ1fpUuQRWVGxUjPFWlmPrxUtFJlzikLgDk1Cztt4qq2TxkmiwNlp7yMNhcsfaoWuif4f1qARHNTrbjFPQnUiM7ZyM1JHOGbaeDUnlKBTHhBGR1FGg7NFa9\\/1i\\/StDw+m6eVv7qVn3n31+lbfh2PbbSOf4mx+VWtiJbmsDhAfahWJOCMGk8sepoC7eck0yRc\\/Pj1FIP8AWNRncwwOB3pWXJzkigBdw59qTd8wGOopPLHqcUoTBzkmgB1FFFABRRRQAUUUUAFIelAOelIzAdTQBydy+8uxXB5BH41Han92w96uX0ai5lHQFqp24wzj0qDVrW4rHbUYck4GSfQVMyg9aRY9pyOKAsyMTNuA6HOOWp4du4yM4z2z9acYFdsspyfSp1T915XIT0ouhJMrMu4ZAqSFd3FSFdi7R+GaLdTvqbl2Jwm2OomTvVlwdtNXoRU3GUJJSn3R+NIksrDIZT823GasvD8u3kg9aZHbJGwdVO4HIJq1YhpjWZ4m2ygqfU8g1Mh3DNJIjSNl2Jp6IFHFJ2GrlO5UyXKoO9WhI9oU8roOp9ajVA9+c9FXNWJkDDjrmhscYp3ub8b741f1GafUcC7IUX0UVJWpgFFJkZx6UBskj0oAWiiigAooooAKarbgaVmC9aavdscGgA3HOcfKaccdPWo8AnCk07y\\/9o5oAAQMkZ64oZTu3DB9qPL9zRs\\/2jQBgayd14cDHAqnAmHf6Vqa1bFSJ16Hgis+1bc+3HBzUdTXoJjmnqBTG+ViKepzUspEoxTqYtOzikMjk60sAw9Mc\\/NU0K85oAmb7tMxzUhHFRE80hjtuaXFKDlaTFMQhUUxuKeTUbHJoAiRCZpH+gq9aw+dICeijJqkj\\/wryTyfatvT4tkGT1b+VNK7FJ8sSyDhAfahWJOCMGk8sepoC7TkkmtTAXPz4x1FIP8AWNRkMwx270rLk5yQaAFyOfak3fMBjqKTy\\/8AaOKUJg5yTQA6iiigAooooAKKKKACikByMikLgcdT6UAU9YUtYMR\\/CQawIARMpJrqWUSxsrrw3GKqJpcKNuyxPbPapaLi1bUxJ\\/8AWGkQ81JdLtkYehxUSnFSykWFoc4FNQ0pGakshHzMfQVbgZfWqjowBK9aiikkRstTsK5rsyhagfrkVC0jSJ+7IB96dD5rD94AB6jvSsBOtLSdKCaQxjmozT2NOtoTPLsU475poTGxZDAHpnniugUYAAqhDp7LKGkIIHOB3q8x2rmtIqxnNp7DqKKKogKKQHJPtSK2SfagB1FFFABRRRQAU1WyvPUUM2OxNAyASRye1ACBjkEkYP6UpwflNM2hjwCBTvLX3oAAw7DqaCCCSp\\/Cjy1o8taAHA5GaWiigDn9TTZdP78iqNbWsRZVZR24NYp61DNIskRqkDVXBxStJtFTYq5MTmhYwagFwnepUvEU4IOKLMCWNcEjFSZIqFrqMfdBNJ9oVuxH4UDJ92aaTUCzAnHP5VJuzSAUmr+kpmR39Bis6tvTY\\/LtQT1bmqiTN6Fumv8AcNOorQyGlsKPXtQpOSG6ik8taUKE5AJoAMnfjtjikXh2oBLMDjAFKyBjk0ABYYJ9KMnfjtik8taUIFORQA6iiigAooooAKKKQnA5oAWikVs0hY5wBk0AOopFzj5utLQBV1HAs2JGQCK5+VMHI5U9DW\\/qf\\/Hi\\/wCFc6JSoKnlT+lJlIYeKctIxDjI6ikWpZaJMjOcVJ50IHzAflUYj3U77MpqShyXMIPC8\\/SpPNV6j+zLxxUggAHFDDUQ8jkUz7tPIxTDSAltkEs6KehPNdEAAMDoKwbH\\/j6i\\/wB6t+tImUgooprEhSRVEjqKQcgUtABRSAnJ44pASXYelADqKKKACiiigApqZ5B6ilbPYZpACAT3NADN3O7dz3FO3jOMijaWPIAFLtX0FADd\\/HUdaG2HnOD60\\/aPQUbR6CgAU5UE0tFFAFPVf+PF\\/qK5xhkVv6vKotjHn5jzj2rBqWXHYgOVORTlfJpzLmozH6cUh2LKSVYRwRWdll609ZsUrFKRph1pCwqgLinCYt0GaVmO6J5HGKZGpY5PSkVSx+b8qsAbRSAktOLqL\\/eFb1YFuwW5jY8AMK360jsZz3CkYZU0tFUQR7wEGDzQh+bGc0\\/aPQUmMfdAzQAHIcehpMhZDnjIpQGJy3GO1KQD1GaAG+YMH9KA2WXnPFO2r6CjABzigBaKKKACiiigAoopDxQAtFIrA0hbnAGTQA6mSyLFG0jnCqMmlXOPm61g67f7nNtGflX7x9T6UARyTNcRPO3\\/AC0fj2A6VWq5bxiXT0Qfe25H1qqBUM0iMIpKl20xl5qSxOKb5ak9KeAaUJRcQqRqvapcDsKaq0+lcdhUHNSUxafSGMwGYKeh4rU0q5MsBic5lhO1vf0NZg++o9SBUL3TWWrSOnTd8w9RWkDOZ1FFV7e9t7gDy5VJP8OeamY4UkVZmOopAcgUtABRSAnJ4pASWI9KAHUUUUAFFFFABTUJ5B6ilYkdBmkAIBJ6mgBm7ndu57inlxnGRTD6uAoHU1nXmsW8OVhUSN69hQBotKqKWZlUZ6ms671iFCRApkb16CsO5vZrlsyNx6DoKrlye9AGhc6vdTcb9i+i8VnkknJpM05RuXGcGgDa045tIiO2RSXkJDGVR8v8Xt70mlc2+3+61aGARyAc8GpaKTMj8aQip7i3aE7lGYz+lQkDrUbGi1EUVIF6VCcg8U8SH0pDJsYptN8ylHNAxwOBSk0VFI5yFQZZuABQhE1qDJdDH3U5P17Vn6k2b+XHrj9K27S3+zQ7WOXPLH3rnZn3zu\\/qxNapWMm7grsMAHBz1rQt9YuIMJIfNT\\/a6\\/nWeVBOc8U1zk8UyTrbTUYbiMbW2v02t1q0h+bGc1xAdh3NaNlrE9uwDnzE9D1\\/OgDpySHHoaTIEhycZFVLXUre6YAOFb+6eKukA9RQA3zBg\\/pQGyw5zxS7V9BShQDkCgBaKKKAEJAGScCs281mCDKxfvH9ulYM99cT8SSsR6ZqsTQBcu9RuLrh3+X+6OBVMmkoAycUAFJSsMU4KAAWoAZRSsQTwMCkoA1dFf8A1ifQ1rCsTSnCXCjs\\/BraXO4+lIY4qCCCMg9RWfc2xh+ZMmP+VaQNGARg8g9RSauNOxhk0A1avLTy\\/nj+4eo9Kq4xUWNb3HCnriogakjVpW2oMmlYLhJIAOOTVyxtDH++lH709Af4R\\/jT7a0SH52O6TsewqyatKxnKVyvey+TayP328VzNbGsy4jWPP3jniserICiinIMtzQA2ig9TRQA4HFXrfVrqBQok3KOzDNUDjAxmnNgBcCgDpbPWoJ8LN+6f36GtJHV1yjBh6g5rhs1NBdTW7bopGQ+1AHa0Vy39t3n\\/PQf98iigDOzStgkHsaRdvegsC3I4oAeQSCOMdqbsOPegsAMLTcn1NAD9vJ47cUAMOuMVHk+poyT3oAGxuOKMUU9dpUAnGKAJ7E\\/vlHviugiO7OeveubiYRyoQejA103QBhSGMbgk857Uu9W6k0srFULcFaqxXBaQArtycYpXHYtjOOmR71mXiJHLhDwecelaDPhfc8DFV5hEQI52GexA5FDVwTsVra0e4ORwvqa1IhHCgRBj196qI0cMiCJht6dc1dJGKEht3Gl89KQuPw708U1gccUyTB1Jy87MenQVR71d1MYnA9s1TBwRTEHJwpPGacBtkoIXrmms2TQA8J82TSP0BPWmZPrSg8\\/Nk0AO4MfuKXG5RimsRjC0mSOhoAeE5HpQwwnIxzTMn1NJk+tABRRRQAUUUUAFFFFABSYpaKACiiigAFdXA3mW8beqg1ygrpdNJNjDn+7QBYABBRuhqJbRVfdnPOelTetLn939aRSKxP+lquOACR9agntpHkY4yCcirUoGxH\\/AIhUyqMZosFyglt5eMjJNFzK4by14xj8TV5wDxTDGjYZlBI70gTFhJMSbvvY5p5HFCUp70xHN6p\\/x+EegFU6s6gc3sv1qtTEFFFFABRRRQAUUUUAFFFFABRRRQB\\/\\/9kAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=\"},\"error\":null,\"message\":null}', '2026-02-10 04:12:02.729', '2026-02-10 04:12:05.919', 1, '2026-02-10 04:12:02.729'),
(12, 32, 1, 'ShahkarLite', 1, NULL, 4, NULL, '{\"nationalCode\":\"0312449348\",\"mobile\":\"09129248289\",\"birthDate\":\"1380\\/10\\/04\",\"serialNumber\":\"1G39352909\",\"plateNumber\":\"32م568ایران55\"}', '{\"success\":false,\"error\":\"خطای cURL: Resolving timed out after 8001 milliseconds\"}', '2026-02-10 04:12:43.573', '2026-02-10 04:12:54.002', 1, '2026-02-10 04:12:43.573'),
(13, 32, 1, 'ShahkarLite', 1, NULL, 2, NULL, '{\"nationalCode\":\"0312449348\",\"mobile\":\"09129248289\",\"birthDate\":\"1380\\/10\\/04\",\"serialNumber\":\"1G39352909\",\"plateNumber\":\"32م568ایران55\"}', '{\"success\":true,\"code\":0,\"data\":true,\"error\":null,\"message\":null}', '2026-02-10 04:12:56.346', '2026-02-10 04:12:59.969', 1, '2026-02-10 04:12:56.346');

-- --------------------------------------------------------

--
-- Table structure for table `identity_verification_services`
--

CREATE TABLE `identity_verification_services` (
  `id` smallint UNSIGNED NOT NULL,
  `code` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(96) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_kind` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT '0=all, 1=driver, 2=company',
  `provider_id` smallint UNSIGNED NOT NULL,
  `http_method` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'POST',
  `endpoint_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `config_json` json DEFAULT NULL,
  `is_active` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `sort_order` smallint UNSIGNED NOT NULL DEFAULT '100',
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `identity_verification_services`
--

INSERT INTO `identity_verification_services` (`id`, `code`, `title`, `description`, `subject_kind`, `provider_id`, `http_method`, `endpoint_path`, `config_json`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'ShahkarLite', 'احراز هویت شاهکار Lite​', 'این وب سرویس نسخه Lite وب سرویس شاهکار است. شاهکار تطبیق کد ملی با شماره موبایل را استعلام می کند. تفاوت آن به نسخه اصلی شاهکار در قیمت و نوع ارسال دیتا است. در نسخه اصلی شاهکار دیتا به صورت رمز شده استعلام می گردد.', 0, 1, 'POST', 'api/sw1/ShahkarLite', '{\"required\": [\"mobile\", \"nationalCode\"]}', 1, 100, '2026-01-26 21:21:44.608', '2026-01-26 21:22:25.469'),
(2, 'Shahkar', 'احراز هویت شاهکار', 'وب سرویس شاهکار تطبیق کد ملی با شماره موبایل را استعلام می نماید. در این نسخه دیتا به صورت رمز شده استعلام می گردد. همچنین امکان استعلام شناسه ملی و سیم کارت اشخاص حقوقی هم دارد', 0, 1, 'POST', '/api/sw1/Shahkar', '{\"required\": [\"isCompany\", \"mobile\", \"nationalCode\"]}', 1, 100, '2026-02-10 03:49:16.645', '2026-02-10 03:49:43.487'),
(3, 'Shahkar2', 'احراز هویت شاهکار 2​', 'این وب سرویس به شاهکار 2 است معروف است در شاهکار اصلی اطلاعات را در اپراتور ها استعلام می نماید ولی در شاهکار 2 استعلام در مرجع اپراتور ها و ثبت احوال تطبیق انجام می شود', 0, 1, 'POST', '/api/sw1/Shahkar2', '{\"required\": [\"birthDate\", \"mobile\", \"nationalCode\"]}', 1, 100, '2026-02-10 03:47:11.072', '2026-02-10 03:49:41.604'),
(5, 'PersonImage', 'استعلام عکس هویتی', 'این وب سرویس با دریافت کد ملی و تاریخ تولد عکس کارت ملی را از ثبت احوال استعلام می نماید. ارائه این سرویس به شرکت ها و سازمان ها با ارائه مجوز مقدور است.', 0, 1, 'POST', '/api/sw1/PersonImage', '{\"required\": [\"mobile\", \"nationalCode\", \"serialNumber\"]}', 1, 100, '2026-02-10 03:51:43.175', '2026-02-10 03:51:43.175'),
(6, 'VideoMatch', 'احراز ویدئویی بایومتریک​', 'احراز هویت بایومتریک، یا احراز هویت ویدئویی نوعی احراز هویت است که فیلم کوتاه از چهره فرد گرفته شده و با اطلاعات هویتی و تصویر کارت ملی وی مقایسه می گردد و در نهایت تطبیق آنها اعلام می شود', 0, 1, 'POST', '/api/sw1/VideoMatch', '{\"defaults\": {\"matchingThreshold\": 90}, \"required\": [\"birthDate\", \"nationalCode\", \"serialNumber\", \"videoBase64\"]}', 1, 100, '2026-02-10 04:17:46.944', '2026-02-10 04:18:25.468'),
(8, 'VideoVerify', 'احراز ویدئویی بایومتریک جامع + Live', 'احراز هویت بایومتریک +Live (VideoVerify): فیلم کوتاه از چهره فرد + زنده‌سنجی + تطبیق گفتار با متن اعلامی.', 0, 1, 'POST', '/api/sw1/VideoVerify', '{\"defaults\": {\"livenessThreshold\": 80, \"matchingThreshold\": 90, \"speechThreshold\": 50}, \"required\": [\"birthDate\", \"nationalCode\", \"serialNumber\", \"speechText\", \"videoBase64\"]}', 1, 110, '2026-02-27 00:00:00.000', '2026-02-27 00:00:00.000'),
(7, 'IPLocation', 'وب سرویس تشخیص موقعیت IP​', 'لوکیشن یک IP را بر می گرداند', 0, 1, 'POST', '/api/sw1/IPLocation', '{\"required\": [\"ip\"]}', 1, 100, '2026-02-14 00:43:56.332', '2026-02-14 00:44:09.842');

-- --------------------------------------------------------

--
-- Table structure for table `jwt_refresh_tokens`
--

CREATE TABLE `jwt_refresh_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `jti_hash` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_id` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `platform` tinyint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `rotated_at` datetime(3) DEFAULT NULL,
  `revoked_at` datetime(3) DEFAULT NULL,
  `expires_at` datetime(3) NOT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jwt_refresh_tokens`
--

INSERT INTO `jwt_refresh_tokens` (`id`, `user_id`, `jti_hash`, `device_id`, `platform`, `ip_address`, `user_agent`, `parent_id`, `rotated_at`, `revoked_at`, `expires_at`, `created_at`) VALUES
(1, 32, '5d764dcf783ee6353797f26a377ad5d49aa0e44c2c28d306bb8c7b3e86f96065', 'android-unique-id', 1, '127.0.0.1', 'PostmanRuntime/7.51.1', NULL, NULL, NULL, '2026-05-13 10:40:15.000', '2026-02-12 10:40:15.647');

-- --------------------------------------------------------

--
-- Table structure for table `loads`
--

CREATE TABLE `loads` (
  `id` bigint UNSIGNED NOT NULL,
  `public_code` char(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `created_by_user_id` bigint UNSIGNED NOT NULL,
  `load_status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `title` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargo_type_id` smallint UNSIGNED DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `weight_kg` decimal(10,2) DEFAULT NULL,
  `volume_m3` decimal(10,2) DEFAULT NULL,
  `package_count` int UNSIGNED DEFAULT NULL,
  `origin_province_id` int UNSIGNED NOT NULL,
  `origin_city_id` bigint UNSIGNED NOT NULL,
  `origin_address` text COLLATE utf8mb4_unicode_ci,
  `origin_lat` decimal(10,7) DEFAULT NULL,
  `origin_lng` decimal(10,7) DEFAULT NULL,
  `origin_geohash` char(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dest_province_id` int UNSIGNED NOT NULL,
  `dest_city_id` bigint UNSIGNED NOT NULL,
  `dest_address` text COLLATE utf8mb4_unicode_ci,
  `dest_lat` decimal(10,7) DEFAULT NULL,
  `dest_lng` decimal(10,7) DEFAULT NULL,
  `dest_geohash` char(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pickup_window_start` datetime(3) DEFAULT NULL,
  `pickup_window_end` datetime(3) DEFAULT NULL,
  `delivery_window_start` datetime(3) DEFAULT NULL,
  `delivery_window_end` datetime(3) DEFAULT NULL,
  `price_type` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `proposed_price` decimal(15,2) DEFAULT NULL,
  `primary_vehicle_type_id` smallint UNSIGNED NOT NULL,
  `assigned_driver_id` bigint UNSIGNED DEFAULT NULL,
  `published_at` datetime(3) DEFAULT NULL,
  `assigned_at` datetime(3) DEFAULT NULL,
  `delivered_at` datetime(3) DEFAULT NULL,
  `cancelled_at` datetime(3) DEFAULT NULL,
  `expires_at` datetime(3) DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  `deleted_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `load_applications`
--

CREATE TABLE `load_applications` (
  `id` bigint UNSIGNED NOT NULL,
  `load_id` bigint UNSIGNED NOT NULL,
  `driver_id` bigint UNSIGNED NOT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `offered_price` decimal(15,2) DEFAULT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `load_status_events`
--

CREATE TABLE `load_status_events` (
  `id` bigint UNSIGNED NOT NULL,
  `load_id` bigint UNSIGNED NOT NULL,
  `old_status` tinyint UNSIGNED DEFAULT NULL,
  `new_status` tinyint UNSIGNED NOT NULL,
  `actor_user_id` bigint UNSIGNED DEFAULT NULL,
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `load_vehicle_types`
--

CREATE TABLE `load_vehicle_types` (
  `load_id` bigint UNSIGNED NOT NULL,
  `vehicle_type_id` smallint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint UNSIGNED NOT NULL,
  `conversation_id` bigint UNSIGNED NOT NULL,
  `sender_user_id` bigint UNSIGNED NOT NULL,
  `message_type` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `body` text COLLATE utf8mb4_unicode_ci,
  `attachment_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `delivered_at` datetime(3) DEFAULT NULL,
  `seen_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `type` varchar(48) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_json` json DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `read_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `otp_codes`
--

CREATE TABLE `otp_codes` (
  `id` bigint UNSIGNED NOT NULL,
  `phone` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purpose` tinyint UNSIGNED NOT NULL,
  `code_hash` char(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` datetime(3) NOT NULL,
  `consumed_at` datetime(3) DEFAULT NULL,
  `attempt_count` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `otp_codes`
--

INSERT INTO `otp_codes` (`id`, `phone`, `purpose`, `code_hash`, `expires_at`, `consumed_at`, `attempt_count`, `ip_address`, `created_at`) VALUES
(1, '09351794610', 1, '1140c3b131b8efaab92255d7b8eeaf08b7e2281904ba9ebb207ed0846a6ba155', '2026-02-12 10:41:31.160', '2026-02-12 10:38:01.013', 0, '127.0.0.1', '2026-02-12 10:36:31.160'),
(2, '09129248289', 1, 'd9969cb3d3575e127c2ab808be1a26e2a0291bdf3b6d333fe6eaf206aaa4e9db', '2026-02-12 10:43:21.692', '2026-02-12 10:40:15.641', 0, '127.0.0.1', '2026-02-12 10:38:21.692');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` smallint UNSIGNED NOT NULL,
  `perm_key` varchar(96) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

CREATE TABLE `provinces` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tel_prefix` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provinces`
--

INSERT INTO `provinces` (`id`, `name`, `slug`, `tel_prefix`) VALUES
(100, 'مرکزی', 'مرکزی', '086'),
(101, 'گیلان', 'گیلان', '013'),
(102, 'مازندران', 'مازندران', '011'),
(103, 'آذربایجان شرقی', 'آذربایجان-شرقی', '041'),
(104, 'آذربایجان غربی', 'آذربایجان-غربی', '044'),
(105, 'کرمانشاه', 'کرمانشاه', '083'),
(106, 'خوزستان', 'خوزستان', '061'),
(107, 'فارس', 'فارس', '071'),
(108, 'کرمان', 'کرمان', '034'),
(109, 'خراسان رضوی', 'خراسان-رضوی', '051'),
(110, 'اصفهان', 'اصفهان', '031'),
(111, 'سیستان و بلوچستان', 'سیستان-و-بلوچستان', '054'),
(112, 'کردستان', 'کردستان', '087'),
(113, 'همدان', 'همدان', '081'),
(114, 'چهارمحال و بختیاری', 'چهارمحال-و-بختیاری', '038'),
(115, 'لرستان', 'لرستان', '066'),
(116, 'ایلام', 'ایلام', '084'),
(117, 'کهگیلویه و بویراحمد', 'کهگیلویه-و-بویراحمد', '074'),
(118, 'بوشهر', 'بوشهر', '077'),
(119, 'زنجان', 'زنجان', '024'),
(120, 'سمنان', 'سمنان', '023'),
(121, 'یزد', 'یزد', '035'),
(122, 'هرمزگان', 'هرمزگان', '076'),
(123, 'تهران', 'تهران', '021'),
(124, 'اردبیل', 'اردبیل', '045'),
(125, 'قم', 'قم', '025'),
(126, 'قزوین', 'قزوین', '028'),
(127, 'گلستان', 'گلستان', '017'),
(128, 'خراسان شمالی', 'خراسان-شمالی', '058'),
(129, 'خراسان جنوبی', 'خراسان-جنوبی', '056'),
(130, 'البرز', 'البرز', '026');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` bigint UNSIGNED NOT NULL,
  `load_id` bigint UNSIGNED NOT NULL,
  `rater_user_id` bigint UNSIGNED NOT NULL,
  `target_user_id` bigint UNSIGNED NOT NULL,
  `score` tinyint UNSIGNED NOT NULL,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `remember_tokens`
--

CREATE TABLE `remember_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `token_hash` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` datetime(3) NOT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `remember_tokens`
--

INSERT INTO `remember_tokens` (`id`, `user_id`, `token_hash`, `expires_at`, `created_at`, `updated_at`) VALUES
(14, 1, '553bf6a5c06a2c6892be3f1549e50df05e00387f77fc36eae616f4a24f983b5b', '2026-02-21 00:37:21.000', '2026-02-14 00:37:21.785', '2026-02-14 00:37:21.785');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` bigint UNSIGNED NOT NULL,
  `reporter_user_id` bigint UNSIGNED NOT NULL,
  `target_user_id` bigint UNSIGNED DEFAULT NULL,
  `load_id` bigint UNSIGNED DEFAULT NULL,
  `conversation_id` bigint UNSIGNED DEFAULT NULL,
  `category` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `resolved_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `resolved_at` datetime(3) DEFAULT NULL,
  `resolution_note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` smallint UNSIGNED NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scope` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_id` smallint UNSIGNED NOT NULL,
  `permission_id` smallint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `setting_key` varchar(96) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_unicode_ci,
  `updated_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`setting_key`, `setting_value`, `updated_by_user_id`, `updated_at`) VALUES
('app.android.latest_version_code', '', 1, '2026-02-10 00:45:20.463'),
('app.android.min_supported_code', '', 1, '2026-02-10 00:45:20.463'),
('app.android.update_url', '', 1, '2026-02-10 00:45:20.463'),
('app.ios.latest_version_code', '', 1, '2026-02-10 00:45:20.463'),
('app.ios.min_supported_code', '', 1, '2026-02-10 00:45:20.463'),
('app.ios.update_url', '', 1, '2026-02-10 00:45:20.463'),
('company.name', 'آموت بار', 1, '2026-02-10 00:45:20.453'),
('links.app_download_url', '', 1, '2026-02-10 00:45:20.462'),
('links.terms_url', '', 1, '2026-02-10 00:45:20.461'),
('maintenance.enabled', '0', 1, '2026-02-10 00:45:20.463'),
('maintenance.message', '', 1, '2026-02-10 00:45:20.463'),
('site.favicon_path', 'storage/uploads/system/site-favicon.png', 1, '2026-02-10 00:37:41.291'),
('site.logo_path', 'storage/uploads/system/site-logo.png', 1, '2026-02-10 00:38:00.862'),
('site.name', 'آموت اپ', 1, '2026-02-10 00:45:20.460'),
('site.url', 'http://amutbar-admin.test', 1, '2026-02-10 00:45:20.461'),
('support.phone', '09351794610', 1, '2026-02-10 00:45:20.462'),
('support.telegram', '', 1, '2026-02-10 00:45:20.463'),
('support.whatsapp', '', 1, '2026-02-10 00:45:20.463');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `full_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_meli` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_date` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `national_card_serial` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `father_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('1','2') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '1 = Men, 2 = Women',
  `user_type` tinyint UNSIGNED NOT NULL COMMENT '1 = Driver, 2 = Company, 3 = Admin',
  `status` tinyint UNSIGNED NOT NULL DEFAULT '3' COMMENT '1 = Active, 1 = DeActive, 3 = Pending, 4 = Suspended',
  `display_name` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `theme_mode` enum('light','dark') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login_at` datetime(3) DEFAULT NULL,
  `failed_login_attempts` smallint UNSIGNED NOT NULL DEFAULT '0',
  `locked_until` datetime(3) DEFAULT NULL,
  `jwt_token_version` int UNSIGNED NOT NULL DEFAULT '1' COMMENT 'برای ابطال سراسری JWT (logout-all)',
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  `deleted_at` datetime(3) DEFAULT NULL,
  `phone_active` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci GENERATED ALWAYS AS ((case when (`deleted_at` is null) then `phone` else NULL end)) STORED,
  `email_active` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci GENERATED ALWAYS AS ((case when (`deleted_at` is null) then `email` else NULL end)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `code_meli`, `birth_date`, `national_card_serial`, `phone`, `email`, `password_hash`, `father_name`, `gender`, `user_type`, `status`, `display_name`, `avatar_key`, `theme_mode`, `last_login_at`, `failed_login_attempts`, `locked_until`, `jwt_token_version`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'شایان', '1552027384', '1380/10/04', NULL, '09351794610', 'namayandeshayan@gmail.com', '$2y$10$vN.AaYKqXdy5GJdsbPCZVegqerAiwO5C9ntCWvm8eLBMDG7cuiahO', 'یعقوب', '1', 3, 1, 'عمو شایان', 'storage/uploads/avatars/avatar_1_1770674071.webp', 'light', '2026-02-14 00:44:09.884', 0, NULL, 1, '2025-12-18 15:06:36.758', '2026-02-14 00:44:09.884', NULL),
(32, 'الیار شکوهی نیا', '0312449348', '1380/10/04', '1G39352909', '09129248289', 'sudoshayanna@gmail.com', '$2y$10$4xZVVZwgpvvSq.HHiVBopO5vNiMBQ1ExM0u9cS864uVyuN.FVjscC', 'یعقوب', '1', 1, 1, 'الیار', NULL, NULL, '2026-02-12 10:40:15.649', 0, NULL, 1, '2026-02-10 03:37:31.378', '2026-02-12 10:40:15.649', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_files`
--

CREATE TABLE `user_files` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `file_type` tinyint UNSIGNED NOT NULL COMMENT '1=عکس پروفایل, 2=عکس کارت ملی, 3=عکس گواهینامه, 4=عکس کارت ماشین, 5=عکس برگه سبز, 6=ویدئو احراز هویت, 7=عکس بیمه نامه',
  `file_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` int UNSIGNED DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_relations`
--

CREATE TABLE `user_relations` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `driver_id` bigint UNSIGNED NOT NULL,
  `relation_type` tinyint UNSIGNED NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `user_id` bigint UNSIGNED NOT NULL,
  `role_id` smallint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_types`
--

CREATE TABLE `vehicle_types` (
  `id` smallint UNSIGNED NOT NULL,
  `parent_id` smallint UNSIGNED DEFAULT NULL,
  `code` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body_type` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `max_weight_kg` decimal(10,2) DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicle_types`
--

INSERT INTO `vehicle_types` (`id`, `parent_id`, `code`, `title`, `body_type`, `max_weight_kg`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, NULL, '1', 'نیسان', NULL, 2200.00, 'نیسان', 1, '2026-02-13 22:02:57', '2026-02-13 22:04:18'),
(2, 1, '11', 'نیسان روباز', 'روباز', 2200.00, 'نیسان روباز', 1, '2026-02-13 22:04:13', '2026-02-13 22:04:13'),
(3, 1, '12', 'نیسان مسقف', 'مسقف', 2200.00, 'نیسان مسقف', 1, '2026-02-13 22:04:49', '2026-02-13 22:04:49'),
(4, 1, '13', 'نیسان یخچالی', 'یخچالی', 2200.00, 'نیسان یخچالی', 1, '2026-02-13 22:05:49', '2026-02-13 22:05:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_audit_logs`
--
ALTER TABLE `admin_audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_admin_audit_actor_time` (`actor_user_id`,`created_at`),
  ADD KEY `ix_admin_audit_entity` (`entity_type`,`entity_id`,`created_at`);

--
-- Indexes for table `api_tokens`
--
ALTER TABLE `api_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_api_tokens_hash` (`token_hash`),
  ADD KEY `ix_api_tokens_user` (`user_id`,`created_at`),
  ADD KEY `ix_api_tokens_expires` (`expires_at`);

--
-- Indexes for table `app_versions`
--
ALTER TABLE `app_versions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_app_versions_lookup` (`app_id`,`platform`,`is_active`,`latest_version_code`),
  ADD KEY `ix_app_versions_min` (`app_id`,`platform`,`min_supported_code`),
  ADD KEY `fk_app_versions_creator` (`created_by_user_id`);

--
-- Indexes for table `auth_daily_stats`
--
ALTER TABLE `auth_daily_stats`
  ADD PRIMARY KEY (`stat_date`,`user_type`,`event_type`);

--
-- Indexes for table `auth_events`
--
ALTER TABLE `auth_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_created` (`user_id`,`created_at`),
  ADD KEY `idx_created` (`created_at`),
  ADD KEY `idx_type_event_created` (`user_type`,`event_type`,`created_at`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_banners_active_time` (`is_active`,`start_at`,`end_at`,`priority`),
  ADD KEY `ix_banners_targeting` (`placement`,`target_app_id`,`target_user_type`,`target_platform`,`is_active`),
  ADD KEY `fk_banners_creator` (`created_by_user_id`);

--
-- Indexes for table `call_logs`
--
ALTER TABLE `call_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_call_logs_driver_time` (`driver_id`,`created_at`),
  ADD KEY `ix_call_logs_company_time` (`company_id`,`created_at`),
  ADD KEY `ix_call_logs_load_time` (`load_id`,`created_at`),
  ADD KEY `ix_call_logs_driver_event_time` (`driver_id`,`event_type`,`created_at`);

--
-- Indexes for table `cargo_types`
--
ALTER TABLE `cargo_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_cargo_types_title` (`title`),
  ADD KEY `ix_cargo_types_active` (`is_active`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_cities_province` (`province_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_companies_user` (`user_id`),
  ADD KEY `ix_companies_verify` (`verification_status`,`created_at`),
  ADD KEY `ix_companies_city` (`city_id`),
  ADD KEY `ix_companies_owner_national` (`owner_national_code`),
  ADD KEY `fk_companies_verified_by` (`verified_by_user_id`),
  ADD KEY `fk_companies_province` (`province_id`);

--
-- Indexes for table `company_documents`
--
ALTER TABLE `company_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_company_docs_company` (`company_id`,`doc_type`),
  ADD KEY `ix_company_docs_status` (`status`,`created_at`),
  ADD KEY `fk_company_docs_reviewer` (`reviewed_by_user_id`);

--
-- Indexes for table `company_verification_events`
--
ALTER TABLE `company_verification_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_company_verif_events_company_time` (`company_id`,`created_at`),
  ADD KEY `fk_company_verif_events_actor` (`actor_user_id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_conversations_load_driver` (`load_id`,`driver_id`),
  ADD KEY `ix_conversations_company_last` (`company_id`,`last_message_at`),
  ADD KEY `ix_conversations_driver_last` (`driver_id`,`last_message_at`);

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_drivers_user` (`user_id`),
  ADD UNIQUE KEY `uq_drivers_plate_active` (`plate_active`),
  ADD KEY `ix_drivers_verify` (`verification_status`,`created_at`),
  ADD KEY `ix_drivers_city` (`city_id`),
  ADD KEY `ix_drivers_national` (`national_code`),
  ADD KEY `ix_drivers_vehicle_type` (`vehicle_type_id`),
  ADD KEY `fk_drivers_verified_by` (`verified_by_user_id`),
  ADD KEY `fk_drivers_province` (`province_id`);

--
-- Indexes for table `driver_documents`
--
ALTER TABLE `driver_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_driver_docs_driver` (`driver_id`,`doc_type`),
  ADD KEY `ix_driver_docs_status` (`status`,`created_at`),
  ADD KEY `fk_driver_docs_reviewer` (`reviewed_by_user_id`);

--
-- Indexes for table `driver_locations_current`
--
ALTER TABLE `driver_locations_current`
  ADD PRIMARY KEY (`driver_id`),
  ADD KEY `ix_driver_loc_geohash` (`geohash`,`updated_at`);

--
-- Indexes for table `driver_location_logs`
--
ALTER TABLE `driver_location_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_driver_loclog_driver_time` (`driver_id`,`captured_at`),
  ADD KEY `ix_driver_loclog_geohash_time` (`geohash`,`captured_at`);

--
-- Indexes for table `driver_verification_events`
--
ALTER TABLE `driver_verification_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_driver_verif_events_driver_time` (`driver_id`,`created_at`),
  ADD KEY `fk_driver_verif_events_actor` (`actor_user_id`);

--
-- Indexes for table `external_api_credentials`
--
ALTER TABLE `external_api_credentials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_external_api_credentials_provider` (`provider_id`,`env`,`status`),
  ADD KEY `fk_external_api_credentials_created_by` (`created_by_user_id`);

--
-- Indexes for table `external_api_providers`
--
ALTER TABLE `external_api_providers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_external_api_providers_slug` (`slug`),
  ADD KEY `ix_external_api_providers_status` (`status`,`priority`);

--
-- Indexes for table `external_api_request_logs`
--
ALTER TABLE `external_api_request_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_external_api_logs_provider_time` (`provider_id`,`created_at`),
  ADD KEY `ix_external_api_logs_request` (`request_id`),
  ADD KEY `fk_external_api_logs_cred` (`credential_id`);

--
-- Indexes for table `field_settings`
--
ALTER TABLE `field_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_field_settings_key` (`field_key`,`entity_type`),
  ADD KEY `ix_field_settings_entity` (`entity_type`,`is_active`,`sort_order`);

--
-- Indexes for table `identity_verification_jobs`
--
ALTER TABLE `identity_verification_jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_idv_jobs_user` (`subject_user_id`),
  ADD KEY `idx_idv_jobs_provider` (`provider_id`),
  ADD KEY `idx_idv_jobs_credential` (`credential_id`),
  ADD KEY `idx_idv_jobs_status` (`status`),
  ADD KEY `fk_idv_jobs_created_by` (`created_by_user_id`);

--
-- Indexes for table `identity_verification_services`
--
ALTER TABLE `identity_verification_services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_identity_services_code` (`code`),
  ADD KEY `idx_identity_services_provider` (`provider_id`);

--
-- Indexes for table `jwt_refresh_tokens`
--
ALTER TABLE `jwt_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_jrt_jti_hash` (`jti_hash`),
  ADD KEY `ix_jrt_user_time` (`user_id`,`created_at`),
  ADD KEY `ix_jrt_device_time` (`device_id`,`created_at`),
  ADD KEY `ix_jrt_expires` (`expires_at`),
  ADD KEY `fk_jrt_parent` (`parent_id`);

--
-- Indexes for table `loads`
--
ALTER TABLE `loads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_loads_public_code` (`public_code`),
  ADD KEY `ix_loads_company_status_time` (`company_id`,`load_status`,`created_at`),
  ADD KEY `ix_loads_status_published` (`load_status`,`published_at`),
  ADD KEY `ix_loads_route_vehicle_status_pub` (`origin_city_id`,`dest_city_id`,`primary_vehicle_type_id`,`load_status`,`published_at`),
  ADD KEY `ix_loads_geo_vehicle_status_pub` (`origin_geohash`,`load_status`,`primary_vehicle_type_id`,`published_at`),
  ADD KEY `ix_loads_assigned_driver` (`assigned_driver_id`,`load_status`),
  ADD KEY `fk_loads_created_by` (`created_by_user_id`),
  ADD KEY `fk_loads_primary_vehicle` (`primary_vehicle_type_id`),
  ADD KEY `fk_loads_cargo` (`cargo_type_id`),
  ADD KEY `fk_loads_dest_province` (`dest_province_id`),
  ADD KEY `fk_loads_origin_province` (`origin_province_id`),
  ADD KEY `fk_loads_dest_city` (`dest_city_id`);

--
-- Indexes for table `load_applications`
--
ALTER TABLE `load_applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_load_app_unique` (`load_id`,`driver_id`),
  ADD KEY `ix_load_app_load_status` (`load_id`,`status`,`created_at`),
  ADD KEY `ix_load_app_driver_status` (`driver_id`,`status`,`created_at`);

--
-- Indexes for table `load_status_events`
--
ALTER TABLE `load_status_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_load_status_events_load_time` (`load_id`,`created_at`),
  ADD KEY `fk_load_status_events_actor` (`actor_user_id`);

--
-- Indexes for table `load_vehicle_types`
--
ALTER TABLE `load_vehicle_types`
  ADD PRIMARY KEY (`load_id`,`vehicle_type_id`),
  ADD KEY `ix_lvt_vehicle` (`vehicle_type_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_messages_conversation_id` (`conversation_id`,`id`),
  ADD KEY `ix_messages_sender_time` (`sender_user_id`,`created_at`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_notifications_user_read_time` (`user_id`,`is_read`,`created_at`);

--
-- Indexes for table `otp_codes`
--
ALTER TABLE `otp_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_otp_phone_purpose` (`phone`,`purpose`,`created_at`),
  ADD KEY `ix_otp_expires` (`expires_at`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_permissions_key` (`perm_key`);

--
-- Indexes for table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ratings_unique` (`load_id`,`rater_user_id`,`target_user_id`),
  ADD KEY `ix_ratings_target` (`target_user_id`,`created_at`),
  ADD KEY `ix_ratings_load` (`load_id`),
  ADD KEY `fk_ratings_rater` (`rater_user_id`);

--
-- Indexes for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_remember_tokens_token` (`token_hash`),
  ADD KEY `ix_remember_tokens_user` (`user_id`),
  ADD KEY `ix_remember_tokens_expires` (`expires_at`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_reports_status_time` (`status`,`created_at`),
  ADD KEY `ix_reports_reporter` (`reporter_user_id`,`created_at`),
  ADD KEY `fk_reports_target` (`target_user_id`),
  ADD KEY `fk_reports_load` (`load_id`),
  ADD KEY `fk_reports_conv` (`conversation_id`),
  ADD KEY `fk_reports_resolver` (`resolved_by_user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_roles_name_scope` (`name`,`scope`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `fk_role_permissions_perm` (`permission_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`setting_key`),
  ADD KEY `fk_system_settings_user` (`updated_by_user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code_meli` (`code_meli`),
  ADD UNIQUE KEY `uq_users_phone_active` (`phone_active`),
  ADD UNIQUE KEY `uq_users_email_active` (`email_active`),
  ADD KEY `ix_users_type_status` (`user_type`,`status`),
  ADD KEY `ix_users_created` (`created_at`);

--
-- Indexes for table `user_files`
--
ALTER TABLE `user_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_user_files_user_type` (`user_id`,`file_type`);

--
-- Indexes for table `user_relations`
--
ALTER TABLE `user_relations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_relations_unique` (`company_id`,`driver_id`,`relation_type`),
  ADD KEY `ix_user_relations_company` (`company_id`,`relation_type`,`updated_at`),
  ADD KEY `ix_user_relations_driver` (`driver_id`,`relation_type`,`updated_at`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `ix_user_roles_role` (`role_id`);

--
-- Indexes for table `vehicle_types`
--
ALTER TABLE `vehicle_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_vehicle_types_code` (`code`),
  ADD KEY `ix_vehicle_types_parent` (`parent_id`),
  ADD KEY `ix_vehicle_types_active` (`is_active`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_audit_logs`
--
ALTER TABLE `admin_audit_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `api_tokens`
--
ALTER TABLE `api_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `app_versions`
--
ALTER TABLE `app_versions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_events`
--
ALTER TABLE `auth_events`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=324;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `call_logs`
--
ALTER TABLE `call_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cargo_types`
--
ALTER TABLE `cargo_types`
  MODIFY `id` smallint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_documents`
--
ALTER TABLE `company_documents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_verification_events`
--
ALTER TABLE `company_verification_events`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `driver_documents`
--
ALTER TABLE `driver_documents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `driver_location_logs`
--
ALTER TABLE `driver_location_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `driver_verification_events`
--
ALTER TABLE `driver_verification_events`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `external_api_credentials`
--
ALTER TABLE `external_api_credentials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `external_api_providers`
--
ALTER TABLE `external_api_providers`
  MODIFY `id` smallint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `external_api_request_logs`
--
ALTER TABLE `external_api_request_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `field_settings`
--
ALTER TABLE `field_settings`
  MODIFY `id` smallint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `identity_verification_jobs`
--
ALTER TABLE `identity_verification_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `identity_verification_services`
--
ALTER TABLE `identity_verification_services`
  MODIFY `id` smallint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jwt_refresh_tokens`
--
ALTER TABLE `jwt_refresh_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `loads`
--
ALTER TABLE `loads`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `load_applications`
--
ALTER TABLE `load_applications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `load_status_events`
--
ALTER TABLE `load_status_events`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `otp_codes`
--
ALTER TABLE `otp_codes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` smallint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` smallint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `user_files`
--
ALTER TABLE `user_files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_relations`
--
ALTER TABLE `user_relations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicle_types`
--
ALTER TABLE `vehicle_types`
  MODIFY `id` smallint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_audit_logs`
--
ALTER TABLE `admin_audit_logs`
  ADD CONSTRAINT `fk_admin_audit_actor` FOREIGN KEY (`actor_user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `api_tokens`
--
ALTER TABLE `api_tokens`
  ADD CONSTRAINT `fk_api_tokens_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `app_versions`
--
ALTER TABLE `app_versions`
  ADD CONSTRAINT `fk_app_versions_creator` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `banners`
--
ALTER TABLE `banners`
  ADD CONSTRAINT `fk_banners_creator` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `call_logs`
--
ALTER TABLE `call_logs`
  ADD CONSTRAINT `fk_call_logs_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_call_logs_driver` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_call_logs_load` FOREIGN KEY (`load_id`) REFERENCES `loads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `cities_ibfk_1` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `fk_companies_province` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`),
  ADD CONSTRAINT `fk_companies_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_companies_verified_by` FOREIGN KEY (`verified_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `company_documents`
--
ALTER TABLE `company_documents`
  ADD CONSTRAINT `fk_company_docs_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_company_docs_reviewer` FOREIGN KEY (`reviewed_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `company_verification_events`
--
ALTER TABLE `company_verification_events`
  ADD CONSTRAINT `fk_company_verif_events_actor` FOREIGN KEY (`actor_user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_company_verif_events_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `fk_conversations_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_conversations_driver` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_conversations_load` FOREIGN KEY (`load_id`) REFERENCES `loads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `drivers`
--
ALTER TABLE `drivers`
  ADD CONSTRAINT `fk_drivers_city` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`),
  ADD CONSTRAINT `fk_drivers_province` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`),
  ADD CONSTRAINT `fk_drivers_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_drivers_vehicle_type` FOREIGN KEY (`vehicle_type_id`) REFERENCES `vehicle_types` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_drivers_verified_by` FOREIGN KEY (`verified_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `driver_documents`
--
ALTER TABLE `driver_documents`
  ADD CONSTRAINT `fk_driver_docs_driver` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_driver_docs_reviewer` FOREIGN KEY (`reviewed_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `driver_locations_current`
--
ALTER TABLE `driver_locations_current`
  ADD CONSTRAINT `fk_driver_loc_driver` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `driver_location_logs`
--
ALTER TABLE `driver_location_logs`
  ADD CONSTRAINT `fk_driver_loclog_driver` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `driver_verification_events`
--
ALTER TABLE `driver_verification_events`
  ADD CONSTRAINT `fk_driver_verif_events_actor` FOREIGN KEY (`actor_user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_driver_verif_events_driver` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `external_api_credentials`
--
ALTER TABLE `external_api_credentials`
  ADD CONSTRAINT `fk_external_api_credentials_created_by` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_external_api_credentials_provider` FOREIGN KEY (`provider_id`) REFERENCES `external_api_providers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `external_api_request_logs`
--
ALTER TABLE `external_api_request_logs`
  ADD CONSTRAINT `fk_external_api_logs_cred` FOREIGN KEY (`credential_id`) REFERENCES `external_api_credentials` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_external_api_logs_provider` FOREIGN KEY (`provider_id`) REFERENCES `external_api_providers` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `identity_verification_jobs`
--
ALTER TABLE `identity_verification_jobs`
  ADD CONSTRAINT `fk_idv_jobs_created_by` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_idv_jobs_credential` FOREIGN KEY (`credential_id`) REFERENCES `external_api_credentials` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_idv_jobs_provider` FOREIGN KEY (`provider_id`) REFERENCES `external_api_providers` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_idv_jobs_user` FOREIGN KEY (`subject_user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `identity_verification_services`
--
ALTER TABLE `identity_verification_services`
  ADD CONSTRAINT `fk_identity_services_provider` FOREIGN KEY (`provider_id`) REFERENCES `external_api_providers` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `jwt_refresh_tokens`
--
ALTER TABLE `jwt_refresh_tokens`
  ADD CONSTRAINT `fk_jrt_parent` FOREIGN KEY (`parent_id`) REFERENCES `jwt_refresh_tokens` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_jrt_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `loads`
--
ALTER TABLE `loads`
  ADD CONSTRAINT `fk_loads_assigned_driver` FOREIGN KEY (`assigned_driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_loads_cargo` FOREIGN KEY (`cargo_type_id`) REFERENCES `cargo_types` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_loads_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_loads_created_by` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_loads_dest_city` FOREIGN KEY (`dest_city_id`) REFERENCES `cities` (`id`),
  ADD CONSTRAINT `fk_loads_dest_province` FOREIGN KEY (`dest_province_id`) REFERENCES `provinces` (`id`),
  ADD CONSTRAINT `fk_loads_origin_city` FOREIGN KEY (`origin_city_id`) REFERENCES `cities` (`id`),
  ADD CONSTRAINT `fk_loads_origin_province` FOREIGN KEY (`origin_province_id`) REFERENCES `provinces` (`id`),
  ADD CONSTRAINT `fk_loads_primary_vehicle` FOREIGN KEY (`primary_vehicle_type_id`) REFERENCES `vehicle_types` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `load_applications`
--
ALTER TABLE `load_applications`
  ADD CONSTRAINT `fk_load_app_driver` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_load_app_load` FOREIGN KEY (`load_id`) REFERENCES `loads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `load_status_events`
--
ALTER TABLE `load_status_events`
  ADD CONSTRAINT `fk_load_status_events_actor` FOREIGN KEY (`actor_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_load_status_events_load` FOREIGN KEY (`load_id`) REFERENCES `loads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `load_vehicle_types`
--
ALTER TABLE `load_vehicle_types`
  ADD CONSTRAINT `fk_lvt_load` FOREIGN KEY (`load_id`) REFERENCES `loads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_lvt_vehicle` FOREIGN KEY (`vehicle_type_id`) REFERENCES `vehicle_types` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_messages_conversation` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_messages_sender` FOREIGN KEY (`sender_user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notifications_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `fk_ratings_load` FOREIGN KEY (`load_id`) REFERENCES `loads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ratings_rater` FOREIGN KEY (`rater_user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ratings_target` FOREIGN KEY (`target_user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD CONSTRAINT `remember_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `fk_reports_conv` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reports_load` FOREIGN KEY (`load_id`) REFERENCES `loads` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reports_reporter` FOREIGN KEY (`reporter_user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reports_resolver` FOREIGN KEY (`resolved_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reports_target` FOREIGN KEY (`target_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `fk_role_permissions_perm` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_role_permissions_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD CONSTRAINT `fk_system_settings_user` FOREIGN KEY (`updated_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `user_files`
--
ALTER TABLE `user_files`
  ADD CONSTRAINT `fk_user_files_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_relations`
--
ALTER TABLE `user_relations`
  ADD CONSTRAINT `fk_user_relations_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_relations_driver` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `fk_user_roles_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_roles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vehicle_types`
--
ALTER TABLE `vehicle_types`
  ADD CONSTRAINT `fk_vehicle_types_parent` FOREIGN KEY (`parent_id`) REFERENCES `vehicle_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
