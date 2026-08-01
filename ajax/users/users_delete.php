<?php

declare(strict_types=1);

require_once __DIR__ . '/../../includes/init.php';
require_admin();
require_post();

// پشتیبانی از حذف/تغییر وضعیت تکی (id) و گروهی (ids[])
$ids = $_POST['ids'] ?? [];
if (!empty($_POST['id']) && empty($ids)) {
    $ids = [$_POST['id']];
}

$action = trim($_POST['action'] ?? 'delete'); // delete, deactivate, activate

if (empty($ids) || !is_array($ids)) {
    json_out(['ok' => false, 'message' => 'هیچ کاربری انتخاب نشده است'], 422);
}

// پاکسازی آیدی‌ها
$ids = array_map('intval', $ids);
$ids = array_filter($ids, fn($id) => $id > 0);

if (empty($ids)) {
    json_out(['ok' => false, 'message' => 'شناسه‌های ارسالی نامعتبر است'], 422);
}

// جلوگیری از عملیات روی اکانت مدیریت فعلی
$currentAdminId = (int)(admin_id() ?? 0);
if (in_array($currentAdminId, $ids, true)) {
    json_out(['ok' => false, 'message' => 'امکان حذف یا تغییر وضعیت اکانت مدیریتی فعلی شما وجود ندارد.'], 422);
}

$pdo = db();

try {
    $pdo->beginTransaction();

    // ساخت علامت سوال برای کوئری IN به تعداد کاربران انتخاب شده
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    if ($action === 'delete') {
        // حذف نرم از ایجاد خطای Foreign Key جلوگیری می‌کند و سوابق عملیاتی را نگه می‌دارد.
        foreach ($ids as $id) {
            if (!users_soft_delete((int)$id)) {
                throw new RuntimeException('کاربر یافت نشد یا قبلاً حذف شده است.');
            }
            audit_log('users.soft_delete', 'user', (int)$id);
        }
    } elseif ($action === 'deactivate') {
        // تغییر وضعیت فیلد وضعیت به ۲ (غیرفعال)
        $st = $pdo->prepare("UPDATE users SET status = 2, updated_at = NOW(3) WHERE id IN ($placeholders)");
        $st->execute($ids);

        foreach ($ids as $id) {
            audit_log('users.deactivate', 'user', $id);
        }
    } elseif ($action === 'activate') {
        // تغییر وضعیت فیلد وضعیت به ۱ (فعال)
        $st = $pdo->prepare("UPDATE users SET status = 1, updated_at = NOW(3) WHERE id IN ($placeholders)");
        $st->execute($ids);

        foreach ($ids as $id) {
            audit_log('users.activate', 'user', $id);
        }
    } else {
        $pdo->rollBack();
        json_out(['ok' => false, 'message' => 'عملیات درخواستی نامعتبر است'], 400);
    }

    $pdo->commit();
    json_out(['ok' => true, 'message' => 'عملیات با موفقیت انجام شد']);
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    json_out(['ok' => false, 'message' => 'خطا در دیتابیس: ' . $e->getMessage()], 500);
}
