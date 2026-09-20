-- One-use video speech challenges. Existing users/profiles are preserved.
CREATE TABLE IF NOT EXISTS `verification_video_challenges` (
  `token_hash` char(64) NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `app_role` tinyint UNSIGNED NOT NULL,
  `speech_text` varchar(2000) NOT NULL,
  `expires_at` datetime(3) NOT NULL,
  `used_at` datetime(3) DEFAULT NULL,
  `created_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`token_hash`),
  KEY `ix_video_challenge_user` (`user_id`, `app_role`, `expires_at`),
  CONSTRAINT `fk_video_challenge_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
