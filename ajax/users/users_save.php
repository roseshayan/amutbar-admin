<?php

declare(strict_types=1);

require_once __DIR__ . '/../../includes/init.php';
require_admin();
require_post();

// اجرای ذخیره‌سازی
$result = users_complete_save($_POST);

if (($result['ok'] ?? false) === true) {
    audit_log('users.save', 'user', (int)$result['id']);
}
json_out($result);