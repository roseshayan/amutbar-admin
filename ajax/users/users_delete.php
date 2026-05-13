<?php

declare(strict_types=1);

require_once __DIR__ . '/../../includes/init.php';
require_admin();
require_post();

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) json_out(['ok' => false, 'message' => 'شناسه نامعتبر'], 422);

if ($id === (admin_id() ?? 0)) json_out(['ok' => false, 'message' => 'امکان حذف حساب فعلی وجود ندارد'], 422);

$ok = users_soft_delete($id);
if (!$ok) json_out(['ok' => false, 'message' => 'حذف انجام نشد'], 400);

audit_log('users.delete', 'user', $id);
json_out(['ok' => true]);
