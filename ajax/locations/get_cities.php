<?php

declare(strict_types=1);

require_once __DIR__ . '/../../includes/init.php';
require_admin();

$provinceId = (int)($_GET['province_id'] ?? 0);
if ($provinceId <= 0) {
    json_out(['ok' => false, 'message' => 'شناسه استان نامعتبر'], 422);
}

$pdo = db();
$st = $pdo->prepare("SELECT id, name FROM cities WHERE province_id = ? ORDER BY name");
$st->execute([$provinceId]);
$cities = $st->fetchAll();

json_out(['ok' => true, 'cities' => $cities]);
