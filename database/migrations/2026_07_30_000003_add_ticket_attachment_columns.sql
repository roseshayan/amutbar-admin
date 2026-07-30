-- Columns already used by both the admin panel and mobile API.
-- Run once on databases created from an older dump.
ALTER TABLE `support_ticket_messages`
  ADD COLUMN `message_type` tinyint UNSIGNED NOT NULL DEFAULT 1
    COMMENT '1=text, 2=image, 3=pdf' AFTER `message`,
  ADD COLUMN `attachment_key` varchar(255) NULL AFTER `message_type`,
  ADD COLUMN `attachment_name` varchar(191) NULL AFTER `attachment_key`;
