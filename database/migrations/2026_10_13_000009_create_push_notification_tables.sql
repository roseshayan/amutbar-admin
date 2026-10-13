CREATE TABLE IF NOT EXISTS `user_push_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` bigint UNSIGNED NOT NULL,
  `token` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `platform` varchar(32) DEFAULT 'android',
  `app_type` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '1=driver, 2=cargo',
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  UNIQUE KEY `uq_push_user_token` (`user_id`, `token`),
  KEY `ix_push_app_type` (`app_type`),
  CONSTRAINT `fk_push_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `push_broadcast_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `admin_id` bigint UNSIGNED DEFAULT NULL,
  `target_audience` varchar(32) NOT NULL,
  `target_user_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `body` text NOT NULL,
  `action_type` varchar(32) DEFAULT 'general',
  `action_data` varchar(255) DEFAULT NULL,
  `sent_count` int UNSIGNED NOT NULL DEFAULT 0,
  `success_count` int UNSIGNED NOT NULL DEFAULT 0,
  `failed_count` int UNSIGNED NOT NULL DEFAULT 0,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
