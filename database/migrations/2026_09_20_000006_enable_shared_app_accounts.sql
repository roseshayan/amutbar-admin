-- Run before deploying the shared-account API. Safe to run again.
-- Keep phone_active and code_meli_active unique: one person, multiple app roles.
CREATE TABLE IF NOT EXISTS `user_app_roles` (
  `user_id` bigint UNSIGNED NOT NULL,
  `app_role` tinyint UNSIGNED NOT NULL COMMENT '1=Driver, 2=Company/Cargo owner',
  `status` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '1=Active, 2=Disabled',
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`user_id`, `app_role`),
  KEY `ix_user_app_roles_role_status` (`app_role`, `status`),
  CONSTRAINT `fk_user_app_roles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `user_app_roles` (`user_id`, `app_role`, `status`)
SELECT `id`, `user_type`, 1 FROM `users`
WHERE `user_type` IN (1, 2) AND `deleted_at` IS NULL
ON DUPLICATE KEY UPDATE `user_id`=VALUES(`user_id`);
