-- Migration: Add auth.require_iran_ip system setting
INSERT INTO `system_settings` (`setting_key`, `setting_value`, `updated_by_user_id`, `updated_at`)
VALUES ('auth.require_iran_ip', '1', 1, NOW(3))
ON DUPLICATE KEY UPDATE `updated_at` = NOW(3);
