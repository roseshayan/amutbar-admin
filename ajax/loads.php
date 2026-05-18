<?php

declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';

header('Content-Type: application/json; charset=utf-8');
$action = $_GET['action'] ?? ($_POST['action'] ?? '');
$pdo = db();

function normalize_money($value): ?float
{
    if ($value === null) {
        return null;
    }

    $value = str_replace(
        ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'],
        ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
        $value
    );

    $value = preg_replace('/[^\d.]/', '', $value);

    return $value === '' ? null : (float)$value;
}

// جستجوی کالا
if ($action === 'search_cargo') {
    $q = trim($_GET['q'] ?? '');
    $st = $pdo->prepare("SELECT id, title FROM cargos_list WHERE title LIKE ? LIMIT 15");
    $st->execute(["%$q%"]);
    $items = array_map(fn($r) => ['id' => $r['id'], 'text' => $r['title']], $st->fetchAll());
    echo json_encode(['items' => $items]);
    exit;
}

// جستجوی شرکت‌های باربری
if ($action === 'search_company') {
    $q = trim($_GET['q'] ?? '');
    $st = $pdo->prepare("SELECT id, company_name FROM companies WHERE company_name LIKE ? AND deleted_at IS NULL LIMIT 20");
    $st->execute(["%$q%"]);
    $items = array_map(fn($r) => ['id' => $r['id'], 'text' => $r['company_name']], $st->fetchAll());
    echo json_encode(['items' => $items]);
    exit;
}

// جستجوی هوشمند شهر (هم نام شهر هم استان)
if ($action === 'search_city') {
    $q = trim($_GET['q'] ?? '');
    $st = $pdo->prepare("
        SELECT c.id, c.name as city_name, p.name as province_name 
        FROM cities c 
        JOIN provinces p ON c.province_id = p.id 
        WHERE c.name LIKE ? OR p.name LIKE ? 
        LIMIT 20
    ");
    $st->execute(["%$q%", "%$q%"]);
    $rows = $st->fetchAll();

    $items = array_map(fn($r) => [
        'id' => $r['id'],
        'text' => $r['city_name'] . ' (' . $r['province_name'] . ')'
    ], $rows);

    echo json_encode(['items' => $items]);
    exit;
}

// تخمین قیمت میانگین
if ($action === 'get_avg_price') {
    $st = $pdo->prepare("SELECT AVG(proposed_price) FROM loads WHERE origin_city_id=? AND dest_city_id=? AND load_status=1 AND deleted_at IS NULL");
    $st->execute([$_POST['origin_city_id'], $_POST['dest_city_id']]);
    $avg = $st->fetchColumn();

    $roundedPrice = $avg ? round((float)$avg / 50000) * 50000 : 0;
    echo json_encode(['ok' => true, 'avg_price' => $roundedPrice]);
    exit;
}

// --- بخش ایجاد بار جدید ---
if ($action === 'create') {
    try {
        $originCityId = (int)($_POST['origin_city_id'] ?? 0);
        $destCityId   = (int)($_POST['dest_city_id'] ?? 0);

        if ($originCityId <= 0 || $destCityId <= 0) {
            throw new Exception('شهر مبدا یا مقصد به درستی انتخاب نشده است.');
        }

        // گرفتن استان مبدا و مقصد
        $st = $pdo->prepare("SELECT province_id FROM cities WHERE id=? LIMIT 1");
        $st->execute([$originCityId]);
        $originProvinceId = $st->fetchColumn();

        $st = $pdo->prepare("SELECT province_id FROM cities WHERE id=? LIMIT 1");
        $st->execute([$destCityId]);
        $destProvinceId = $st->fetchColumn();

        if (!$originProvinceId || !$destProvinceId) {
            throw new Exception('استان مبدا یا مقصد در سیستم پیدا نشد.');
        }

        $companyId = (int)($_POST['company_id'] ?? 0);
        if ($companyId <= 0) {
            throw new Exception('لطفا شرکت باربری را انتخاب کنید.');
        }

        $publicCode = (string)rand(10000000, 99999999) . (string)rand(1000, 9999);
        $proposedPrice = normalize_money($_POST['proposed_price'] ?? null);
        $insuranceValue = normalize_money($_POST['insurance_value'] ?? null);

        $sql = "
            INSERT INTO loads (
                public_code, company_id, created_by_user_id, phone_coordination, load_status, load_type,
                cargo_type_id, description, weight_kg, is_tonnage_free, origin_province_id, origin_city_id,
                origin_address, dest_province_id, dest_city_id, dest_address, price_type, proposed_price,
                primary_vehicle_type_id, published_at, created_at, updated_at, has_insurance, insurance_value
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?, ?, ?, ?,
                NOW(3), NOW(3), NOW(3), ?, ?
            )
        ";

        $st = $pdo->prepare($sql);
        $ok = $st->execute([
            $publicCode,
            $companyId,
            admin_id(),
            $_POST['phone_coordination'] ?? null,
            1,
            $_POST['load_type'] ?? 1,
            $_POST['cargo_type_id'] ?? null,
            $_POST['description'] ?? null,
            ($_POST['is_tonnage_free'] ?? '0') === '1' ? null : ($_POST['weight'] ?? null),
            $_POST['is_tonnage_free'] ?? 0,
            $originProvinceId,
            $originCityId,
            $_POST['origin_address'] ?? null,
            $destProvinceId,
            $destCityId,
            $_POST['dest_address'] ?? null,
            $_POST['price_type'] ?? 1,
            $proposedPrice,
            $_POST['primary_vehicle_type_id'] ?? 1,
            $_POST['has_insurance'] ?? 0,
            $insuranceValue
        ]);

        echo json_encode(['ok' => $ok]);
    } catch (Throwable $e) {
        http_response_code(500);
        echo json_encode([
            'ok' => false,
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => basename($e->getFile())
        ]);
    }
    exit;
}

// --- بخش ویرایش بار ---
if ($action === 'update') {
    try {
        $id = (int)($_POST['id'] ?? 0);
        $companyId = (int)($_POST['company_id'] ?? 0);
        if ($companyId <= 0) {
            throw new Exception('لطفا شرکت باربری را انتخاب کنید.');
        }

        $sql = "UPDATE loads SET company_id=?, phone_coordination=?, load_status=?, origin_city_id=?, dest_city_id=?, load_type=?, primary_vehicle_type_id=?, cargo_type_id=?, weight_kg=?, is_tonnage_free=?, price_type=?, proposed_price=?, description=?, has_insurance=?, insurance_value=?, origin_address=?, dest_address=?, updated_at=NOW(3) WHERE id=?";
        $st = $pdo->prepare($sql);
        $ok = $st->execute([
            $companyId,
            $_POST['phone_coordination'] ?? null,
            $_POST['load_status'] ?? 1,
            $_POST['origin_city_id'] ?? null,
            $_POST['dest_city_id'] ?? null,
            $_POST['load_type'] ?? 1,
            $_POST['primary_vehicle_type_id'] ?? null,
            $_POST['cargo_type_id'] ?? null,
            ($_POST['is_tonnage_free'] ?? '0') === '1' ? null : ($_POST['weight'] ?? null),
            $_POST['is_tonnage_free'] ?? 0,
            $_POST['price_type'] ?? 1,
            $_POST['proposed_price'] ?? null,
            $_POST['description'] ?? null,
            $_POST['has_insurance'] ?? 0,
            $_POST['insurance_value'] ?? null,
            $_POST['origin_address'] ?? null,
            $_POST['dest_address'] ?? null,
            $id
        ]);
        echo json_encode(['ok' => $ok]);
    } catch (Throwable $e) {
        http_response_code(500);
        echo json_encode(['ok' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

// --- بخش لیست دیتاتیبل ---
if ($action === 'list') {
    require_once __DIR__ . '/../includes/jdf.php';

    $st = $pdo->query("
        SELECT l.*, 
               c1.name as origin_city, p1.name as origin_province,
               c2.name as dest_city, p2.name as dest_province,
               vt.title as vehicle_title, cl.title as cargo_title,
               comp.company_name
        FROM loads l
        LEFT JOIN cities c1 ON l.origin_city_id = c1.id
        LEFT JOIN provinces p1 ON c1.province_id = p1.id
        LEFT JOIN cities c2 ON l.dest_city_id = c2.id
        LEFT JOIN provinces p2 ON c2.province_id = p2.id
        LEFT JOIN vehicle_types vt ON l.primary_vehicle_type_id = vt.id
        LEFT JOIN cargos_list cl ON l.cargo_type_id = cl.id
        LEFT JOIN companies comp ON l.company_id = comp.id
        WHERE l.deleted_at IS NULL
        ORDER BY l.id DESC
    ");
    $rows = $st->fetchAll();

    $data = [];
    foreach ($rows as $r) {
        $status = match ((int)$r['load_status']) {
            1 => '<span class="badge bg-success">فعال</span>',
            2 => '<span class="badge bg-info">تخصیص شده</span>',
            3 => '<span class="badge bg-secondary">پایان</span>',
            4 => '<span class="badge bg-danger">لغو شده</span>',
            default => 'نامشخص'
        };

        $data[] = [
            'id' => $r['id'],
            'public_code' => '<span class="fw-bold">' . $r['public_code'] . '</span>',
            'company_name' => $r['company_name'] ?? 'نامشخص',
            'origin' => $r['origin_city'] . ' (' . $r['origin_province'] . ')',
            'destination' => $r['dest_city'] . ' (' . $r['dest_province'] . ')',
            'vehicle' => $r['vehicle_title'] ?? 'روباری',
            'cargo' => $r['cargo_title'],
            'price' => number_format((float)$r['proposed_price']),
            'status' => $status,
            'created_at' => jdate('Y/m/d', strtotime($r['created_at'])),
            'actions' => '
                <a href="load_edit.php?id=' . $r['id'] . '" class="btn btn-sm btn-icon btn-primary" title="ویرایش"><i class="ri-edit-line"></i></a>
                <button class="btn btn-sm btn-icon btn-danger" onclick="deleteLoad(' . $r['id'] . ')" title="حذف"><i class="ri-delete-bin-line"></i></button>
            '
        ];
    }
    echo json_encode(['data' => $data]);
    exit;
}

// --- عملیات حذف گروهی ---
if ($action === 'bulk_delete') {
    $ids = $_POST['ids'] ?? [];
    if (!is_array($ids) || empty($ids)) {
        echo json_encode(['ok' => false, 'message' => 'هیچ موردی انتخاب نشده است']);
        exit;
    }

    $cleanIds = array_map('intval', $ids);
    $placeholders = implode(',', array_fill(0, count($cleanIds), '?'));

    $st = $pdo->prepare("UPDATE loads SET deleted_at = NOW(3) WHERE id IN ($placeholders)");
    $ok = $st->execute($cleanIds);

    echo json_encode(['ok' => $ok]);
    exit;
}

// --- حذف تکی ---
if ($action === 'delete') {
    $id = (int)$_POST['id'];
    $ok = $pdo->prepare("UPDATE loads SET deleted_at = NOW(3) WHERE id = ?")->execute([$id]);
    echo json_encode(['ok' => $ok]);
    exit;
}
