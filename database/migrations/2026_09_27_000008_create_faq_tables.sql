-- FAQ content for the driver (1) and cargo-owner (2) apps.
CREATE TABLE IF NOT EXISTS `faq_categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `target_app_id` tinyint UNSIGNED NOT NULL COMMENT '1 = Driver app, 2 = Cargo-owner app',
  `title` varchar(160) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `icon_key` varchar(40) NOT NULL DEFAULT 'general',
  `sort_order` int UNSIGNED NOT NULL DEFAULT 100,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  KEY `ix_faq_categories_app_active_sort` (`target_app_id`, `is_active`, `sort_order`, `id`),
  KEY `fk_faq_categories_creator` (`created_by_user_id`),
  CONSTRAINT `fk_faq_categories_creator_user` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `faq_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` bigint UNSIGNED NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `link_label` varchar(120) DEFAULT NULL,
  `link_url` varchar(2048) DEFAULT NULL,
  `image_key` varchar(512) DEFAULT NULL,
  `video_key` varchar(512) DEFAULT NULL,
  `video_url` varchar(2048) DEFAULT NULL,
  `sort_order` int UNSIGNED NOT NULL DEFAULT 100,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  KEY `ix_faq_items_category_active_sort` (`category_id`, `is_active`, `sort_order`, `id`),
  KEY `fk_faq_items_creator` (`created_by_user_id`),
  CONSTRAINT `fk_faq_items_category` FOREIGN KEY (`category_id`) REFERENCES `faq_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_faq_items_creator_user` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
