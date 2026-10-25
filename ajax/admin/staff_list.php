<?php

declare(strict_types=1);

require_once __DIR__ . '/../../includes/init.php';
require_super_admin();

$pdo = db();
$st = $pdo->query("
    SELECT id, full_name, phone, email, user_type, admin_role, status, last_login_at, created_at, permissions
    FROM users
    WHERE user_type = 3 AND deleted_at IS NULL
    ORDER BY (admin_role = 'super_admin') DESC, id DESC
");
$rows = $st->fetchAll(PDO::FETCH_ASSOC);

$totalPermsCount = count(permissions_all_keys());

$data = array_map(function (array $r) use ($totalPermsCount): array {
    $role = (string)($r['admin_role'] ?? 'staff');
    $isSuper = ($role === 'super_admin');
    
    $permsCount = 0;
    if ($isSuper) {
        $permsCount = $totalPermsCount;
    } else {
        $perms = admin_permissions((int)$r['id']);
        $permsCount = count($perms);
    }

    return [
        'id' => (int)$r['id'],
        'full_name' => htmlspecialchars((string)$r['full_name'], ENT_QUOTES, 'UTF-8'),
        'phone' => htmlspecialchars((string)$r['phone'], ENT_QUOTES, 'UTF-8'),
        'email' => htmlspecialchars((string)($r['email'] ?? ''), ENT_QUOTES, 'UTF-8'),
        'admin_role' => $role,
        'role_label' => admin_role_label($role),
        'is_super' => $isSuper,
        'status' => (int)$r['status'],
        'status_label' => user_status_label((int)$r['status']),
        'perms_count' => $permsCount,
        'perms_total' => $totalPermsCount,
        'last_login_at' => (string)($r['last_login_at'] ?? ''),
        'created_at' => (string)$r['created_at'],
    ];
}, $rows);

json_out([
    'ok' => true,
    'data' => $data,
]);
