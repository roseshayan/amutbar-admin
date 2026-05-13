<?php

declare(strict_types=1);

require_once __DIR__ . '/../../_bootstrap.php';
api_require_admin_user();

$page = max(1, (int)($_GET['page'] ?? 1));
$per = (int)($_GET['per_page'] ?? 25);
if ($per < 1) $per = 25;
if ($per > 100) $per = 100;
$off = ($page - 1) * $per;

$q = trim((string)($_GET['q'] ?? ''));
$type = (int)($_GET['user_type'] ?? 0);
$status = (int)($_GET['status'] ?? 0);

$where = 'deleted_at IS NULL';
$params = [];

if ($q !== '') {
    $where .= ' AND (full_name LIKE ? OR phone LIKE ? OR email LIKE ?)';
    $like = "%{$q}%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}
if (in_array($type, [1,2,3], true)) {
    $where .= ' AND user_type = ?';
    $params[] = $type;
}
if (in_array($status, [1,2,3,4], true)) {
    $where .= ' AND status = ?';
    $params[] = $status;
}

$pdo = db();
$st = $pdo->prepare("SELECT COUNT(*) FROM users WHERE {$where}");
$st->execute($params);
$total = (int)$st->fetchColumn();

$sql = "SELECT id, full_name, phone, email, user_type, status, created_at, updated_at
        FROM users
        WHERE {$where}
        ORDER BY id DESC
        LIMIT {$per} OFFSET {$off}";

$st = $pdo->prepare($sql);
$st->execute($params);
$items = $st->fetchAll();

api_ok([
    'page' => $page,
    'per_page' => $per,
    'total' => $total,
    'items' => array_map(static function(array $r){
        return [
            'id' => (int)$r['id'],
            'full_name' => (string)$r['full_name'],
            'phone' => (string)$r['phone'],
            'email' => $r['email'] !== null ? (string)$r['email'] : null,
            'user_type' => (int)$r['user_type'],
            'status' => (int)$r['status'],
            'created_at' => (string)$r['created_at'],
            'updated_at' => (string)$r['updated_at'],
        ];
    }, $items)
]);
