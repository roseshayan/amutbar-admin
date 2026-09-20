-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 18, 2026 at 05:46 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

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
  `action` varchar(96) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_type` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entity_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload_json` json DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_audit_logs`
--


-- --------------------------------------------------------

--
-- Table structure for table `api_tokens`
--

CREATE TABLE `api_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `token_hash` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_id` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `platform` tinyint UNSIGNED DEFAULT NULL,
  `ip_address` varbinary(16) DEFAULT NULL,
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `latest_version_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `latest_version_code` int UNSIGNED NOT NULL,
  `min_supported_code` int UNSIGNED NOT NULL,
  `force_update` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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


-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `placement` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_app_id` tinyint UNSIGNED DEFAULT NULL,
  `target_user_type` tinyint UNSIGNED DEFAULT NULL,
  `target_platform` tinyint UNSIGNED DEFAULT NULL,
  `action_type` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `action_value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

--
-- Dumping data for table `banners`
--


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
  `result_code` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_platform` tinyint UNSIGNED DEFAULT NULL,
  `client_version_code` int UNSIGNED DEFAULT NULL,
  `ip_address` varbinary(16) DEFAULT NULL,
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cargos_list`
--

CREATE TABLE `cargos_list` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cargos_list`
--


-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `province_id` int UNSIGNED NOT NULL,
  `county_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--


-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `company_name` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_full_name` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_national_code` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `registration_no` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `economic_code` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province_id` int UNSIGNED DEFAULT NULL,
  `city_id` bigint UNSIGNED DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `verification_status` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `verified_at` datetime(3) DEFAULT NULL,
  `verified_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `reject_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  `deleted_at` datetime(3) DEFAULT NULL,
  `registration_date` date DEFAULT NULL COMMENT 'تاریخ ثبت',
  `postal_code` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'کد پستی'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `companies`
--


-- --------------------------------------------------------

--
-- Table structure for table `company_documents`
--

CREATE TABLE `company_documents` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `doc_type` tinyint UNSIGNED NOT NULL,
  `file_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `reviewed_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `reviewed_at` datetime(3) DEFAULT NULL,
  `reject_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `full_name` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `national_code` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `smart_card_number` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vehicle_type_id` smallint UNSIGNED DEFAULT NULL,
  `plate_number` varchar(24) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_year` smallint UNSIGNED DEFAULT NULL,
  `color` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacity_kg` decimal(10,2) DEFAULT NULL,
  `province_id` int UNSIGNED DEFAULT NULL,
  `city_id` bigint UNSIGNED DEFAULT NULL,
  `verification_status` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `verified_at` datetime(3) DEFAULT NULL,
  `verified_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `reject_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating_avg` decimal(3,2) NOT NULL DEFAULT '0.00',
  `rating_count` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  `deleted_at` datetime(3) DEFAULT NULL,
  `plate_active` varchar(24) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci GENERATED ALWAYS AS ((case when (`deleted_at` is null) then `plate_number` else NULL end)) STORED,
  `license_serial` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'سریال گواهینامه',
  `license_base` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'پایه گواهینامه',
  `vin_number` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'شماره VIN',
  `insurance_number` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'شماره بیمه نامه',
  `insurance_expiry` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'تاریخ اتمام بیمه',
  `engine_number` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'شماره موتور',
  `chassis_number` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'شماره شاسی',
  `issued_from` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'صادره از',
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'آدرس',
  `home_phone` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'تلفن منزل',
  `postal_code` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'کد پستی',
  `extra_phones` json DEFAULT NULL COMMENT 'شماره موبایل اضافه (آرایه‌ای از شماره‌ها)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `drivers`
--


-- --------------------------------------------------------

--
-- Table structure for table `driver_documents`
--

CREATE TABLE `driver_documents` (
  `id` bigint UNSIGNED NOT NULL,
  `driver_id` bigint UNSIGNED NOT NULL,
  `doc_type` tinyint UNSIGNED NOT NULL,
  `file_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `reviewed_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `reviewed_at` datetime(3) DEFAULT NULL,
  `reject_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `geohash` char(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `geohash` char(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `driver_verification_events`
--


-- --------------------------------------------------------

--
-- Table structure for table `external_api_credentials`
--

CREATE TABLE `external_api_credentials` (
  `id` bigint UNSIGNED NOT NULL,
  `provider_id` smallint UNSIGNED NOT NULL,
  `env` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `key_id` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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


-- --------------------------------------------------------

--
-- Table structure for table `external_api_providers`
--

CREATE TABLE `external_api_providers` (
  `id` smallint UNSIGNED NOT NULL,
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `base_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `docs_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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


-- --------------------------------------------------------

--
-- Table structure for table `external_api_request_logs`
--

CREATE TABLE `external_api_request_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `provider_id` smallint UNSIGNED NOT NULL,
  `credential_id` bigint UNSIGNED DEFAULT NULL,
  `operation` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `http_method` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_id` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `http_status` smallint UNSIGNED DEFAULT NULL,
  `latency_ms` int UNSIGNED DEFAULT NULL,
  `error_code` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_redacted_json` json DEFAULT NULL,
  `response_redacted_json` json DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `external_api_request_logs`
--


-- --------------------------------------------------------

--
-- Table structure for table `field_settings`
--

CREATE TABLE `field_settings` (
  `id` smallint UNSIGNED NOT NULL,
  `field_key` varchar(96) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'user, driver, company',
  `field_label` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` smallint UNSIGNED NOT NULL DEFAULT '100',
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `field_settings`
--


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


-- --------------------------------------------------------

--
-- Table structure for table `loads`
--

CREATE TABLE `loads` (
  `id` bigint UNSIGNED NOT NULL,
  `public_code` char(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `created_by_user_id` bigint UNSIGNED NOT NULL,
  `phone_coordination` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `load_status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `load_type` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '1=دربستی, 2=روباری',
  `title` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargo_type_id` int UNSIGNED DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `weight_kg` decimal(10,2) DEFAULT NULL,
  `is_tonnage_free` tinyint(1) NOT NULL DEFAULT '0',
  `volume_m3` decimal(10,2) DEFAULT NULL,
  `package_count` int UNSIGNED DEFAULT NULL,
  `origin_province_id` int UNSIGNED NOT NULL,
  `origin_city_id` bigint UNSIGNED NOT NULL,
  `origin_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `origin_lat` decimal(10,7) DEFAULT NULL,
  `origin_lng` decimal(10,7) DEFAULT NULL,
  `origin_geohash` char(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dest_province_id` int UNSIGNED NOT NULL,
  `dest_city_id` bigint UNSIGNED NOT NULL,
  `dest_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `dest_lat` decimal(10,7) DEFAULT NULL,
  `dest_lng` decimal(10,7) DEFAULT NULL,
  `dest_geohash` char(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `deleted_at` datetime(3) DEFAULT NULL,
  `has_insurance` tinyint(1) NOT NULL DEFAULT '0',
  `insurance_value` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loads`
--


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
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `notes` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `attachment_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `type` varchar(48) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_json` json DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `read_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--


-- --------------------------------------------------------

--
-- Table structure for table `otp_codes`
--

CREATE TABLE `otp_codes` (
  `id` bigint UNSIGNED NOT NULL,
  `phone` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `purpose` tinyint UNSIGNED NOT NULL,
  `code_hash` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` datetime(3) NOT NULL,
  `consumed_at` datetime(3) DEFAULT NULL,
  `attempt_count` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `otp_codes`
--


-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` smallint UNSIGNED NOT NULL,
  `perm_key` varchar(96) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

CREATE TABLE `provinces` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tel_prefix` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provinces`
--


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
  `comment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `category` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `resolved_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `resolved_at` datetime(3) DEFAULT NULL,
  `resolution_note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` smallint UNSIGNED NOT NULL,
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `scope` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
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
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '1=باز (درانتظار), 2=پاسخ داده شده, 3=بسته',
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `support_tickets`
--


-- --------------------------------------------------------

--
-- Table structure for table `support_ticket_messages`
--

CREATE TABLE `support_ticket_messages` (
  `id` bigint UNSIGNED NOT NULL,
  `ticket_id` bigint UNSIGNED NOT NULL,
  `sender_user_id` bigint UNSIGNED NOT NULL COMMENT 'میتواند راننده یا ادمین باشد',
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_type` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '1=text, 2=image, 3=pdf',
  `attachment_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `support_ticket_messages`
--


-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `setting_key` varchar(96) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `updated_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_settings`
--


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `full_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_meli` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_date` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `national_card_serial` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `father_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('1','2') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '1' COMMENT '1 = Men, 2 = Women',
  `user_type` tinyint UNSIGNED NOT NULL COMMENT '1 = Driver, 2 = Company, 3 = Admin',
  `status` tinyint UNSIGNED NOT NULL DEFAULT '3' COMMENT '1 = Active, 1 = DeActive, 3 = Pending, 4 = Suspended',
  `display_name` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `theme_mode` enum('light','dark') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login_at` datetime(3) DEFAULT NULL,
  `failed_login_attempts` smallint UNSIGNED NOT NULL DEFAULT '0',
  `locked_until` datetime(3) DEFAULT NULL,
  `jwt_token_version` int UNSIGNED NOT NULL DEFAULT '1' COMMENT 'برای ابطال سراسری JWT (logout-all)',
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  `deleted_at` datetime(3) DEFAULT NULL,
  `phone_active` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci GENERATED ALWAYS AS ((case when (`deleted_at` is null) then `phone` else NULL end)) STORED,
  `email_active` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci GENERATED ALWAYS AS ((case when (`deleted_at` is null) then `email` else NULL end)) STORED,
  `code_meli_active` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci GENERATED ALWAYS AS ((case when (`deleted_at` is null) then `code_meli` else NULL end)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--


-- --------------------------------------------------------

--
-- Table structure for table `user_files`
--

CREATE TABLE `user_files` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `file_type` tinyint UNSIGNED NOT NULL COMMENT '1=عکس پروفایل, 2=عکس کارت ملی, 3=عکس گواهینامه, 4=عکس کارت ماشین, 5=عکس برگه سبز, 6=ویدئو احراز هویت, 7=عکس بیمه نامه, 8=مجوز فعالیت شرکت',
  `file_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` int UNSIGNED DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_files`
--


-- --------------------------------------------------------

--
-- Table structure for table `user_relations`
--

CREATE TABLE `user_relations` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `driver_id` bigint UNSIGNED NOT NULL,
  `relation_type` tinyint UNSIGNED NOT NULL,
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `code` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body_type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `max_weight_kg` decimal(10,2) DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicle_types`
--


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
-- Indexes for table `cargos_list`
--
ALTER TABLE `cargos_list`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `fk_loads_dest_province` (`dest_province_id`),
  ADD KEY `fk_loads_origin_province` (`origin_province_id`),
  ADD KEY `fk_loads_dest_city` (`dest_city_id`),
  ADD KEY `fk_loads_cargos_list` (`cargo_type_id`);

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
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_support_tickets_user` (`user_id`);

--
-- Indexes for table `support_ticket_messages`
--
ALTER TABLE `support_ticket_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_stm_ticket` (`ticket_id`),
  ADD KEY `fk_stm_sender` (`sender_user_id`);

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
  ADD UNIQUE KEY `uq_users_code_meli_active` (`code_meli_active`),
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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1931;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `call_logs`
--
ALTER TABLE `call_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cargos_list`
--
ALTER TABLE `cargos_list`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12900011002625;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `field_settings`
--
ALTER TABLE `field_settings`
  MODIFY `id` smallint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `identity_verification_jobs`
--
ALTER TABLE `identity_verification_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `identity_verification_services`
--
ALTER TABLE `identity_verification_services`
  MODIFY `id` smallint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `jwt_refresh_tokens`
--
ALTER TABLE `jwt_refresh_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `loads`
--
ALTER TABLE `loads`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `otp_codes`
--
ALTER TABLE `otp_codes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

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
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `support_ticket_messages`
--
ALTER TABLE `support_ticket_messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `user_files`
--
ALTER TABLE `user_files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

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
-- Constraints for table `auth_events`
--
ALTER TABLE `auth_events`
  ADD CONSTRAINT `auth_events_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

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
  ADD CONSTRAINT `fk_companies_city` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`),
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
  ADD CONSTRAINT `fk_loads_cargos_list` FOREIGN KEY (`cargo_type_id`) REFERENCES `cargos_list` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
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
-- Constraints for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD CONSTRAINT `fk_support_tickets_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `support_ticket_messages`
--
ALTER TABLE `support_ticket_messages`
  ADD CONSTRAINT `fk_stm_sender` FOREIGN KEY (`sender_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_stm_ticket` FOREIGN KEY (`ticket_id`) REFERENCES `support_tickets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
