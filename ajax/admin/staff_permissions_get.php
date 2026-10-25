<?php

declare(strict_types=1);

require_once __DIR__ . '/../../includes/init.php';
require_super_admin();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    json_out(['ok' => false, 'message' => 'شناسه کارمند نامعتبر است'], 422);
}

$pdo = db();
$st = $pdo->prepare("SELECT id, full_name, phone, admin_role, user_type FROM users WHERE id = ? AND user_type = 3 AND deleted_at IS NULL LIMIT 1");
$st->execute([$id]);
$user = $st->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    json_out(['ok' => false, 'message' => 'کارمند یافت نشد'], 404);
}

$activePerms = admin_permissions($id);
$catalog = permissions_catalog();

json_out([
    'ok' => true,
    'user' => [
        'id' => (int)$user['id'],
        'full_name' => htmlspecialchars((string)$user['full_name'], ENT_QUOTES, 'UTF-8'),
        'phone' => htmlspecialchars((string)$user['phone'], ENT_QUOTES, 'UTF-8'),
        'admin_role' => (string)($user['admin_role'] ?? 'staff'),
        'is_super' => (($user['admin_role'] ?? '') === 'super_admin'),
    ],
    'active_permissions' => $activePerms,
    'catalog' => $catalog,
]);
