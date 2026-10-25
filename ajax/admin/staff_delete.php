<?php

declare(strict_types=1);

require_once __DIR__ . '/../../includes/init.php';
require_super_admin();
require_post();

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    json_out(['ok' => false, 'message' => 'شناسه کارمند نامعتبر است'], 422);
}

if ($id === (int)admin_id()) {
    json_out(['ok' => false, 'message' => 'امکان حذف حساب کاربری خودتان وجود ندارد'], 422);
}

$pdo = db();
$st = $pdo->prepare("SELECT id, full_name, admin_role, user_type FROM users WHERE id = ? LIMIT 1");
$st->execute([$id]);
$target = $st->fetch(PDO::FETCH_ASSOC);

if (!$target) {
    json_out(['ok' => false, 'message' => 'کاربر یافت نشد'], 404);
}

if ($id === 1 || ($target['admin_role'] ?? '') === 'super_admin') {
    json_out(['ok' => false, 'message' => 'امکان حذف مدیر کل سیستم وجود ندارد'], 403);
}

try {
    $pdo->prepare("UPDATE users SET status = 2, deleted_at = NOW(3), updated_at = NOW(3) WHERE id = ? LIMIT 1")
        ->execute([$id]);

    // باطل کردن توکن‌ها و نشست‌ها
    $pdo->prepare("DELETE FROM remember_tokens WHERE user_id = ?")->execute([$id]);
    $pdo->prepare("UPDATE jwt_refresh_tokens SET revoked_at = NOW(3) WHERE user_id = ? AND revoked_at IS NULL")->execute([$id]);

    audit_log('staff.delete', 'user', $id, ['name' => $target['full_name']]);
    json_out(['ok' => true, 'message' => 'حساب کارمند با موفقیت حذف و مسدود شد']);
} catch (Throwable $e) {
    error_log('staff_delete error: ' . $e->getMessage());
    json_out(['ok' => false, 'message' => 'خطا در حذف کارمند. لطفاً دوباره تلاش کنید.'], 500);
}
