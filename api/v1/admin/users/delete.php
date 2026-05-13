<?php

declare(strict_types=1);

require_once __DIR__ . '/../../_bootstrap.php';
api_require_admin_user();
require_post();

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) api_err('شناسه نامعتبر', 422);

if (!users_soft_delete($id)) api_err('حذف انجام نشد', 400);

audit_log('api.users.delete', 'user', $id);
api_ok();
