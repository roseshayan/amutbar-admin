-- Allow one account/identity to use both mobile applications.
-- users remains the canonical identity row (unique phone/national code), while
-- user_app_roles stores the mobile roles enabled for that account.

CREATE TABLE IF NOT EXISTS `user_app_roles` (
  `user_id` bigint UNSIGNED NOT NULL,
  `app_role` tinyint UNSIGNED NOT NULL COMMENT '1 = Driver, 2 = Company/Cargo owner',
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '1 = Active, 2 = Disabled',
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`user_id`,`app_role`),
  KEY `ix_user_app_roles_role_status` (`app_role`,`status`),
  CONSTRAINT `fk_user_app_roles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Backfill existing mobile users. Admin users (type 3) are intentionally omitted.
INSERT IGNORE INTO `user_app_roles` (`user_id`, `app_role`, `status`, `created_at`, `updated_at`)
SELECT `id`, `user_type`, 1, COALESCE(`created_at`, NOW(3)), NOW(3)
FROM `users`
WHERE `user_type` IN (1,2) AND `deleted_at` IS NULL;
