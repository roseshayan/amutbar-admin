<?php

declare(strict_types=1);

require_once __DIR__ . '/../../includes/init.php';
require_admin();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) json_out(['ok' => false, 'message' => 'شناسه نامعتبر'], 422);

$u = users_get($id);
if (!$u) json_out(['ok' => false, 'message' => 'کاربر یافت نشد'], 404);

json_out(['ok' => true, 'user' => $u]);
