<?php

declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';
require_admin();

header('Content-Type: application/json; charset=utf-8');
$action = $_GET['action'] ?? ($_POST['action'] ?? '');
$pdo = db();

if ($action === 'list') {
    $st = $pdo->query("SELECT * FROM cargos_list ORDER BY id DESC");
    $rows = $st->fetchAll();
    $data = [];
    foreach ($rows as $r) {
        $data[] = [
            'id' => $r['id'],
            'title' => htmlspecialchars($r['title']),
            'actions' => '
                <a href="cargo_edit.php?id=' . $r['id'] . '" class="btn btn-sm btn-icon btn-primary"><i class="ri-edit-line"></i></a>
                <button class="btn btn-sm btn-icon btn-danger" onclick="deleteCargo(' . $r['id'] . ')"><i class="ri-delete-bin-line"></i></button>
            '
        ];
    }
    echo json_encode(['data' => $data]);
    exit;
}

if ($action === 'save') {
    $id = (int)($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');

    if (empty($title)) {
        echo json_encode(['ok' => false, 'message' => 'نام کالا الزامی است']);
        exit;
    }

    if ($id > 0) {
        $pdo->prepare("UPDATE cargos_list SET title=? WHERE id=?")->execute([$title, $id]);
    } else {
        $pdo->prepare("INSERT INTO cargos_list (title) VALUES (?)")->execute([$title]);
    }
    echo json_encode(['ok' => true]);
    exit;
}

if ($action === 'delete') {
    $id = (int)$_POST['id'];
    try {
        $pdo->prepare("DELETE FROM cargos_list WHERE id=?")->execute([$id]);
        echo json_encode(['ok' => true]);
    } catch (Throwable $e) {
        echo json_encode(['ok' => false, 'message' => 'این کالا در بارها استفاده شده و قابل حذف نیست.']);
    }
    exit;
}
