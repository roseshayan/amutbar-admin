<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_admin();

$pdo = db();
$action = (string)($_GET['action'] ?? $_POST['action'] ?? 'list');

if ($action === 'list') {
    $rows = $pdo->query("SELECT id, parent_id, code, title, body_type, max_weight_kg, is_active FROM vehicle_types ORDER BY id DESC")->fetchAll();
    $data = [];
    foreach (($rows ?: []) as $r) {
        $id = (int)$r['id'];
        $active = (int)($r['is_active'] ?? 1) === 1;
        $data[] = [
            'id' => $id,
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
    $st = $pdo->prepare("SELECT id, parent_id, code, title, body_type, max_weight_kg, description, is_active FROM vehicle_types WHERE id=? LIMIT 1");
    $st->execute([$id]);
    $it = $st->fetch();
    if (!$it) json_out(['ok' => false, 'message' => 'یافت نشد'], 404);
    json_out(['ok' => true, 'item' => [
        'id' => (int)$it['id'],
        'parent_id' => $it['parent_id'] !== null ? (int)$it['parent_id'] : null,
        'code' => (string)$it['code'],
        'title' => (string)$it['title'],
        'body_type' => $it['body_type'] !== null ? (string)$it['body_type'] : null,
        'max_weight_kg' => $it['max_weight_kg'] !== null ? (float)$it['max_weight_kg'] : null,
        'description' => $it['description'] !== null ? (string)$it['description'] : null,
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

    if ($title === '' || $code === '') json_out(['ok' => false, 'message' => 'عنوان و کد الزامی است'], 422);

    try {
        if ($id > 0) {
            $st = $pdo->prepare("UPDATE vehicle_types SET parent_id=:parent_id, code=:code, title=:title, body_type=:body_type, max_weight_kg=:max_weight_kg, description=:description, is_active=:is_active, updated_at=NOW(3) WHERE id=:id LIMIT 1");
            $st->execute([
                ':parent_id' => $parentId,
                ':code' => $code,
                ':title' => $title,
                ':body_type' => $body !== '' ? $body : null,
                ':max_weight_kg' => $maxW,
                ':description' => $desc !== '' ? $desc : null,
                ':is_active' => $isActive,
                ':id' => $id,
            ]);
            audit_log("vehicle.update", 'vehicle_type_update', $id);
            json_out(['ok' => true]);
        }

        $st = $pdo->prepare("INSERT INTO vehicle_types (parent_id, code, title, body_type, max_weight_kg, description, is_active, created_at, updated_at) VALUES (:parent_id, :code, :title, :body_type, :max_weight_kg, :description, :is_active, NOW(3), NOW(3))");
        $st->execute([
            ':parent_id' => $parentId,
            ':code' => $code,
            ':title' => $title,
            ':body_type' => $body !== '' ? $body : null,
            ':max_weight_kg' => $maxW,
            ':description' => $desc !== '' ? $desc : null,
            ':is_active' => $isActive,
        ]);
        $nid = (int)$pdo->lastInsertId();
        audit_log("vehicle.create", 'vehicle_type_create', $nid);
        json_out(['ok' => true, 'id' => $nid]);
    } catch (Throwable $e) {
        if (str_contains($e->getMessage(), 'uq_vehicle_types_code')) {
            json_out(['ok' => false, 'message' => 'کد تکراری است'], 409);
        }
        json_out(['ok' => false, 'message' => 'خطا در ذخیره'], 500);
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
