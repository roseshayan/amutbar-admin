-- IPv6 text can require up to 45 characters.
ALTER TABLE `admin_audit_logs`
  MODIFY `ip_address` varchar(45) NULL;
