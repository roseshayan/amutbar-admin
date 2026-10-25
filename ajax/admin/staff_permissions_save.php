<?php

declare(strict_types=1);

require_once __DIR__ . '/../../includes/init.php';
require_super_admin();
require_post();

$userId = (int)($_POST['user_id'] ?? 0);
$permissions = $_POST['permissions'] ?? [];

if ($userId <= 0) {
    json_out(['ok' => false, 'message' => 'شناسه کارمند نامعتبر است'], 422);
}
if (!is_array($permissions)) {
    $permissions = [];
}

$pdo = db();
$st = $pdo->prepare("SELECT id, admin_role, full_name FROM users WHERE id = ? AND user_type = 3 AND deleted_at IS NULL LIMIT 1");
$st->execute([$userId]);
$user = $st->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    json_out(['ok' => false, 'message' => 'کارمند یافت نشد'], 404);
}

if (($user['admin_role'] ?? '') === 'super_admin') {
    json_out(['ok' => false, 'message' => 'حساب مدیر کل به صورت خودکار دارای تمام دسترسی‌های سامانه است و نیازی به ویرایش ندارد.'], 422);
}

$ok = save_staff_permissions($userId, $permissions);

if ($ok) {
    json_out([
        'ok' => true,
        'message' => 'سطوح دسترسی کارمند با موفقیت بروزرسانی شد',
        'count' => count($permissions),
    ]);
} else {
    json_out(['ok' => false, 'message' => 'خطا در ذخیره‌سازی دسترسی‌ها. لطفاً دوباره تلاش کنید.'], 500);
}
