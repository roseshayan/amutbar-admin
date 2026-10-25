<?php

declare(strict_types=1);

require_once __DIR__ . '/../../includes/init.php';
require_super_admin();
require_post();

$id = (int)($_POST['id'] ?? 0);
$status = (int)($_POST['status'] ?? 0);

if ($id <= 0 || !in_array($status, [1, 2], true)) {
    json_out(['ok' => false, 'message' => 'پارامترهای ارسالی نامعتبر است'], 422);
}

if ($id === (int)admin_id()) {
    json_out(['ok' => false, 'message' => 'امکان تغییر وضعیت حساب کاربری خودتان وجود ندارد'], 422);
}

$pdo = db();
$st = $pdo->prepare("SELECT id, full_name, admin_role, user_type FROM users WHERE id = ? LIMIT 1");
$st->execute([$id]);
$target = $st->fetch(PDO::FETCH_ASSOC);

if (!$target) {
    json_out(['ok' => false, 'message' => 'کاربر یافت نشد'], 404);
}

if ($id === 1 || ($target['admin_role'] ?? '') === 'super_admin') {
    json_out(['ok' => false, 'message' => 'امکان غیرفعال کردن مدیر کل سیستم وجود ندارد'], 403);
}

try {
    $pdo->prepare("UPDATE users SET status = ?, updated_at = NOW(3) WHERE id = ? LIMIT 1")
        ->execute([$status, $id]);

    $label = ($status === 1) ? 'فعال' : 'غیرفعال';
    audit_log('staff.status', 'user', $id, ['status' => $status]);
    json_out(['ok' => true, 'message' => "وضعیت کارمند به {$label} تغییر یافت"]);
} catch (Throwable $e) {
    error_log('staff_status error: ' . $e->getMessage());
    json_out(['ok' => false, 'message' => 'خطا در تغییر وضعیت کارمند'], 500);
}
