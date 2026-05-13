<?php

declare(strict_types=1);

require_once __DIR__ . '/../../includes/init.php';
//require_admin();

$search = trim((string)($_GET['search'] ?? ''));
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;

$pdo = db();

// ساخت شرط جستجو
$where = "WHERE 1=1";
$params = [];

if ($search !== '') {
    $where .= " AND name LIKE :search";
    $params[':search'] = "%{$search}%";
}

// دریافت تعداد کل
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM provinces {$where}");
$countStmt->execute($params);
$totalCount = (int)$countStmt->fetchColumn();

// دریافت استان‌ها
$sql = "SELECT id, name FROM provinces {$where} ORDER BY name LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$params[':limit'] = $limit;
$params[':offset'] = $offset;

// اجرای کوئری
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
}

$stmt->execute();
$provinces = $stmt->fetchAll();

// فرمت مناسب برای Select2
$items = array_map(function ($province) {
    return [
        'id' => $province['id'],
        'text' => $province['name']
    ];
}, $provinces);

json_out([
    'items' => $items,
    'total_count' => $totalCount
]);
