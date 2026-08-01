-- Run once after taking a database backup.
-- Archived users keep their national code for audit/history, but only active
-- (not soft-deleted) users reserve a national code for uniqueness.
ALTER TABLE `users`
  DROP INDEX `code_meli`,
  ADD COLUMN `code_meli_active` varchar(10)
    GENERATED ALWAYS AS (
      CASE WHEN `deleted_at` IS NULL THEN `code_meli` ELSE NULL END
    ) STORED,
  ADD UNIQUE KEY `uq_users_code_meli_active` (`code_meli_active`);
