<?php

declare(strict_types=1);

require_once __DIR__ . '/../../_bootstrap.php';
api_require_admin_user();
require_post();

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) api_err('شناسه نامعتبر', 422);
$res = users_save($_POST);

if (($res['ok'] ?? false) === true) {
    audit_log('api.users.update', 'user', (int)$res['id']);
    api_ok(['id' => (int)$res['id']]);
}

api_err((string)($res['message'] ?? 'خطا'), 422);
