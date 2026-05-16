<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_admin();

header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? ($_POST['action'] ?? '');
$pdo = db();

if ($action === 'list') {
    $st = $pdo->query("SELECT * FROM banners ORDER BY id DESC");
    $rows = $st->fetchAll();

    $data = [];
    foreach ($rows as $r) {
        $imgUrl = ltrim($r['image_key'], '/');

        $targetApp = $r['target_app_id'] == 1
            ? '<span class="badge bg-primary-transparent">رانندگان</span>'
            : '<span class="badge bg-success-transparent">صاحبان بار</span>';

        $placement = match ($r['placement']) {
            'dashboard' => 'داشبورد',
            'profile' => 'پروفایل کاربری',
            default => $r['placement']
        };

        $status = $r['is_active'] == 1
            ? '<span class="badge bg-success">فعال</span>'
            : '<span class="badge bg-danger">غیرفعال</span>';

        $data[] = [
            'id' => $r['id'],
            'image' => '<img src="' . $imgUrl . '" style="height:50px; border-radius:6px; object-fit:cover;">',
            'title_body' => '<strong>' . htmlspecialchars($r['title'] ?? '-') . '</strong><br><small class="text-muted">' . htmlspecialchars($r['body'] ?? '') . '</small>',
            'targeting' => $targetApp . '<br>' . $placement,
            'action_value' => !empty($r['action_value']) ? '<a href="' . htmlspecialchars($r['action_value']) . '" target="_blank" dir="ltr" style="font-size:12px;">Link <i class="ri-external-link-line"></i></a>' : '-',
            'priority' => $r['priority'],
            'status' => $status,
            'actions' => '
                <button class="btn btn-sm btn-outline-danger mf-del" onclick="deleteBanner(' . $r['id'] . ')">حذف</button>
            '
        ];
    }

    echo json_encode(['data' => $data], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($action === 'save') {
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');
    $action_value = trim($_POST['action_value'] ?? '');
    $target_app_id = (int)($_POST['target_app_id'] ?? 1);
    $placement = trim($_POST['placement'] ?? 'dashboard');
    $priority = (int)($_POST['priority'] ?? 100);
    $action_type = (int)($_POST['action_type'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // دریافت ID ادمین که در حال ساختن بنر است
    $admin_id = admin_id();

    if (empty($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['ok' => false, 'message' => 'تصویر بنر الزامی است']);
        exit;
    }

    $file = $_FILES['image'];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($file['type'], $allowedTypes)) {
        echo json_encode(['ok' => false, 'message' => 'فرمت تصویر نامعتبر است']);
        exit;
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'banner_' . time() . '_' . uniqid() . '.' . $ext;

    $dir = BASE_PATH . '/storage/uploads/banners';
    if (!is_dir($dir)) @mkdir($dir, 0777, true);

    $dest = $dir . '/' . $filename;

    if (move_uploaded_file($file['tmp_name'], $dest)) {
        $imageKey = 'storage/uploads/banners/' . $filename;

        $st = $pdo->prepare("INSERT INTO banners (title, body, image_key, placement, target_app_id, action_type, action_value, priority, is_active, created_by_user_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(3), NOW(3))");
        $st->execute([$title, $body, $imageKey, $placement, $target_app_id, $action_type, $action_value, $priority, $is_active, $admin_id]);

        echo json_encode(['ok' => true, 'message' => 'بنر با موفقیت ذخیره شد']);
    } else {
        echo json_encode(['ok' => false, 'message' => 'خطا در ذخیره تصویر']);
    }
    exit;
}

if ($action === 'delete') {
    $id = (int)($_POST['id'] ?? 0);

    $st = $pdo->prepare("SELECT image_key FROM banners WHERE id = ?");
    $st->execute([$id]);
    $banner = $st->fetch();

    if ($banner) {
        $path = BASE_PATH . '/' . ltrim($banner['image_key'], '/');
        if (is_file($path)) @unlink($path);

        $pdo->prepare("DELETE FROM banners WHERE id = ?")->execute([$id]);
        echo json_encode(['ok' => true]);
    } else {
        echo json_encode(['ok' => false, 'message' => 'بنر یافت نشد']);
    }
    exit;
}

echo json_encode(['ok' => false, 'message' => 'عملیات نامعتبر']);
