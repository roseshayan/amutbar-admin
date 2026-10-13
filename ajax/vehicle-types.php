<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_admin();

$pdo = db();
$action = (string)($_GET['action'] ?? $_POST['action'] ?? 'list');

if ($action === 'list') {
    $rows = $pdo->query("SELECT id, parent_id, code, title, body_type, max_weight_kg, icon, is_active FROM vehicle_types ORDER BY id DESC")->fetchAll();
    $data = [];
    $baseUrl = rtrim(base_url(), '/');
    foreach (($rows ?: []) as $r) {
        $id = (int)$r['id'];
        $active = (int)($r['is_active'] ?? 1) === 1;
        $icon = !empty($r['icon']) ? (string)$r['icon'] : '';
        $iconUrl = '';
        $iconHtml = '<span class="avatar avatar-sm bg-light text-muted rounded"><i class="ri-truck-line fs-18"></i></span>';
        if ($icon !== '') {
            $iconUrl = preg_match('~^https?://~i', $icon) ? $icon : ($baseUrl . '/' . ltrim($icon, '/'));
            $iconHtml = '<img src="' . htmlspecialchars($iconUrl) . '" alt="" style="width:36px; height:36px; object-fit:contain; border-radius:6px; background:#f8f9fa; padding:2px; border:1px solid #e9ecef;">';
        }

        $data[] = [
            'id' => $id,
            'icon_preview' => $iconHtml,
            'icon_url' => $iconUrl,
            'title' => (string)$r['title'],
            'code' => (string)$r['code'],
            'parent_id' => $r['parent_id'] !== null ? (int)$r['parent_id'] : '-',
            'body_type' => $r['body_type'] !== null ? (string)$r['body_type'] : '-',
            'max_weight_kg' => $r['max_weight_kg'] !== null ? (string)$r['max_weight_kg'] : '-',
            'status_badge' => $active ? '<span class="badge bg-success">فعال</span>' : '<span class="badge bg-light text-dark">غیرفعال</span>',
            'actions' =>
                '<button type="button" class="btn btn-sm btn-outline-primary vt-edit" data-id="' . $id . '">ویرایش</button> '
                . '<button type="button" class="btn btn-sm btn-outline-secondary vt-toggle" data-id="' . $id . '">' . ($active ? 'غیرفعال' : 'فعال') . '</button> '
                . '<button type="button" class="btn btn-sm btn-outline-danger vt-delete" data-id="' . $id . '">حذف</button>',
        ];
    }
    json_out(['data' => $data]);
}

if ($action === 'parents') {
    $rows = $pdo->query("SELECT id, title FROM vehicle_types WHERE is_active=1 ORDER BY title ASC")->fetchAll();
    json_out(['ok' => true, 'items' => array_map(fn($r) => ['id' => (int)$r['id'], 'title' => (string)$r['title']], $rows ?: [])]);
}

if ($action === 'get') {
    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) json_out(['ok' => false, 'message' => 'id نامعتبر'], 400);
    $st = $pdo->prepare("SELECT id, parent_id, code, title, body_type, max_weight_kg, description, icon, is_active FROM vehicle_types WHERE id=? LIMIT 1");
    $st->execute([$id]);
    $it = $st->fetch();
    if (!$it) json_out(['ok' => false, 'message' => 'یافت نشد'], 404);

    $baseUrl = rtrim(base_url(), '/');
    $icon = !empty($it['icon']) ? (string)$it['icon'] : null;
    $iconUrl = null;
    if ($icon !== null) {
        $iconUrl = preg_match('~^https?://~i', $icon) ? $icon : ($baseUrl . '/' . ltrim($icon, '/'));
    }

    json_out(['ok' => true, 'item' => [
        'id' => (int)$it['id'],
        'parent_id' => $it['parent_id'] !== null ? (int)$it['parent_id'] : null,
        'code' => (string)$it['code'],
        'title' => (string)$it['title'],
        'body_type' => $it['body_type'] !== null ? (string)$it['body_type'] : null,
        'max_weight_kg' => $it['max_weight_kg'] !== null ? (float)$it['max_weight_kg'] : null,
        'description' => $it['description'] !== null ? (string)$it['description'] : null,
        'icon' => $icon,
        'icon_url' => $iconUrl,
        'is_active' => (int)$it['is_active'] === 1,
    ]]);
}

if ($action === 'save') {
    require_post();
    $id = (int)($_POST['id'] ?? 0);
    $title = trim((string)($_POST['title'] ?? ''));
    $code = trim((string)($_POST['code'] ?? ''));
    $parentId = ($_POST['parent_id'] ?? '') !== '' ? (int)$_POST['parent_id'] : null;
    $body = trim((string)($_POST['body_type'] ?? ''));
    $maxW = ($_POST['max_weight_kg'] ?? '') !== '' ? (float)$_POST['max_weight_kg'] : null;
    $desc = trim((string)($_POST['description'] ?? ''));
    $isActive = (int)($_POST['is_active'] ?? 1) === 1 ? 1 : 0;
    $removeIcon = (int)($_POST['remove_icon'] ?? 0) === 1;

    if ($title === '' || $code === '') json_out(['ok' => false, 'message' => 'عنوان و کد الزامی است'], 422);

    $currentIcon = null;
    if ($id > 0) {
        $stOld = $pdo->prepare("SELECT icon FROM vehicle_types WHERE id=? LIMIT 1");
        $stOld->execute([$id]);
        $currentIcon = $stOld->fetchColumn() ?: null;
    }

    $uploadedIconPath = null;
    if (!empty($_FILES['icon']) && is_array($_FILES['icon']) && ($_FILES['icon']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
        $f = $_FILES['icon'];
        $tmp = (string)($f['tmp_name'] ?? '');
        $size = (int)($f['size'] ?? 0);
        if (!is_uploaded_file($tmp) || $size <= 0 || $size > 4 * 1024 * 1024) {
            json_out(['ok' => false, 'message' => 'حجم فایل آیکون نباید بیشتر از ۴ مگابایت باشد'], 422);
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = (string)$finfo->file($tmp);
        $allowed = [
            'image/png' => 'png',
            'image/jpeg' => 'jpg',
            'image/webp' => 'webp',
            'image/svg+xml' => 'svg',
            'image/gif' => 'gif',
        ];

        if (!isset($allowed[$mime])) {
            json_out(['ok' => false, 'message' => 'فرمت تصویر آیکون معتبر نیست (png, jpg, webp, svg مجاز است)'], 422);
        }

        $ext = $allowed[$mime];
        $dir = BASE_PATH . '/storage/uploads/vehicles';
        if (!is_dir($dir)) @mkdir($dir, 0775, true);

        $filename = 'vt_' . bin2hex(random_bytes(8)) . '_' . time() . '.' . $ext;
        $dest = $dir . '/' . $filename;
        if (!move_uploaded_file($tmp, $dest)) {
            json_out(['ok' => false, 'message' => 'خطا در ذخیره‌سازی فایل تصویر'], 500);
        }
        @chmod($dest, 0644);
        $uploadedIconPath = 'storage/uploads/vehicles/' . $filename;

        // پاک کردن آیکون قبلی در صورت وجود
        if ($currentIcon && !str_starts_with($currentIcon, 'http')) {
            $oldPath = BASE_PATH . '/' . ltrim($currentIcon, '/');
            if (is_file($oldPath)) @unlink($oldPath);
        }
    } elseif ($removeIcon && $currentIcon) {
        if (!str_starts_with($currentIcon, 'http')) {
            $oldPath = BASE_PATH . '/' . ltrim($currentIcon, '/');
            if (is_file($oldPath)) @unlink($oldPath);
        }
        $currentIcon = null;
    }

    $finalIcon = $uploadedIconPath !== null ? $uploadedIconPath : ($removeIcon ? null : $currentIcon);

    try {
        if ($id > 0) {
            $st = $pdo->prepare("UPDATE vehicle_types SET parent_id=:parent_id, code=:code, title=:title, body_type=:body_type, max_weight_kg=:max_weight_kg, description=:description, icon=:icon, is_active=:is_active, updated_at=NOW(3) WHERE id=:id LIMIT 1");
            $st->execute([
                ':parent_id' => $parentId,
                ':code' => $code,
                ':title' => $title,
                ':body_type' => $body !== '' ? $body : null,
                ':max_weight_kg' => $maxW,
                ':description' => $desc !== '' ? $desc : null,
                ':icon' => $finalIcon,
                ':is_active' => $isActive,
                ':id' => $id,
            ]);
            audit_log("vehicle.update", 'vehicle_type_update', $id);
            json_out(['ok' => true]);
        }

        $st = $pdo->prepare("INSERT INTO vehicle_types (parent_id, code, title, body_type, max_weight_kg, description, icon, is_active, created_at, updated_at) VALUES (:parent_id, :code, :title, :body_type, :max_weight_kg, :description, :icon, :is_active, NOW(3), NOW(3))");
        $st->execute([
            ':parent_id' => $parentId,
            ':code' => $code,
            ':title' => $title,
            ':body_type' => $body !== '' ? $body : null,
            ':max_weight_kg' => $maxW,
            ':description' => $desc !== '' ? $desc : null,
            ':icon' => $finalIcon,
            ':is_active' => $isActive,
        ]);
        $nid = (int)$pdo->lastInsertId();
        audit_log("vehicle.create", 'vehicle_type_create', $nid);
        json_out(['ok' => true, 'id' => $nid]);
    } catch (Throwable $e) {
        if (str_contains($e->getMessage(), 'uq_vehicle_types_code')) {
            json_out(['ok' => false, 'message' => 'کد تکراری است'], 409);
        }
        json_out(['ok' => false, 'message' => 'خطا در ذخیره: ' . $e->getMessage()], 500);
    }
}

if ($action === 'toggle') {
    require_post();
    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) json_out(['ok' => false, 'message' => 'id نامعتبر'], 400);
    $st = $pdo->prepare("UPDATE vehicle_types SET is_active = IF(is_active=1,0,1), updated_at=NOW(3) WHERE id=? LIMIT 1");
    $st->execute([$id]);
    audit_log("vehicle.toggle", 'vehicle_type_toggle', $id);
    json_out(['ok' => true]);
}

if ($action === 'delete') {
    require_post();
    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) json_out(['ok' => false, 'message' => 'id نامعتبر'], 400);
    try {
        $st = $pdo->prepare("DELETE FROM vehicle_types WHERE id=? LIMIT 1");
        $st->execute([$id]);
        audit_log("vehicle.delete", 'vehicle_type_delete', $id);
        json_out(['ok' => true]);
    } catch (Throwable $e) {
        json_out(['ok' => false, 'message' => 'این مورد در سیستم استفاده شده و قابل حذف نیست'], 409);
    }
}

json_out(['ok' => false, 'message' => 'action نامعتبر'], 400);
