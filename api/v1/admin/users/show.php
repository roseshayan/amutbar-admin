<?php

declare(strict_types=1);

require_once __DIR__ . '/../../_bootstrap.php';
api_require_admin_user();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) api_err('شناسه نامعتبر', 422);
$u = users_get($id);
if (!$u) api_err('کاربر یافت نشد', 404);
api_ok(['user' => $u]);
