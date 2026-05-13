<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/init.php';
//require_admin();

$provinceId = (int)($_GET['province_id'] ?? 0);
$search = trim((string)($_GET['search'] ?? ''));
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;

if ($provinceId <= 0) {
    json_out(['items' => [], 'total_count' => 0]);
}

$pdo = db();

// ساخت شرط جستجو
$where = "WHERE province_id = :province_id";
$params = [':province_id' => $provinceId];

if ($search !== '') {
    $where .= " AND name LIKE :search";
    $params[':search'] = "%{$search}%";
}

// دریافت تعداد کل
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM cities {$where}");
$countStmt->execute($params);
$totalCount = (int)$countStmt->fetchColumn();

// دریافت شهرها
$sql = "SELECT id, name FROM cities {$where} ORDER BY name LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$cities = $stmt->fetchAll();

// فرمت مناسب برای Select2
$items = array_map(function ($city) {
    return [
        'id' => $city['id'],
        'text' => $city['name']
    ];
}, $cities);

json_out([
    'items' => $items,
    'total_count' => $totalCount
]);