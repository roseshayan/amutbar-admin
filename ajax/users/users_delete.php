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

        // لیست تمام جداولی که ممکن است اطلاعات کاربر در آن‌ها باشد
        // این کار جلوی ارور Foreign Key Constraint دیتابیس را می‌گیرد
        $queries = [
            "DELETE FROM auth_events WHERE user_id IN ($placeholders)",
            "DELETE FROM remember_tokens WHERE user_id IN ($placeholders)",
            "DELETE FROM jwt_refresh_tokens WHERE user_id IN ($placeholders)",
            "DELETE FROM user_files WHERE user_id IN ($placeholders)",
            "DELETE FROM drivers WHERE user_id IN ($placeholders)",
            "DELETE FROM companies WHERE user_id IN ($placeholders)",
            "DELETE FROM identity_verification_jobs WHERE subject_user_id IN ($placeholders)",
            "DELETE FROM user_roles WHERE user_id IN ($placeholders)"
        ];

        // پاک کردن وابستگی‌ها قبل از حذف خود کاربر
        foreach ($queries as $sql) {
            try {
                $pdo->prepare($sql)->execute($ids);
            } catch (Throwable $e) {
                // اگر جدولی وجود نداشت یا پاک شده بود، از آن عبور می‌کنیم تا فرآیند متوقف نشود
            }
        }

        // در نهایت حذف فیزیکی و دائمی خود کاربر از سیستم
        $st = $pdo->prepare("DELETE FROM users WHERE id IN ($placeholders)");
        $st->execute($ids);

        foreach ($ids as $id) {
            audit_log('users.hard_delete', 'user', $id);
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
