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

// بررسی امنیتی و سطوح دسترسی روی کاربران انتخاب شده
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$stCheck = $pdo->prepare("SELECT id, user_type, admin_role FROM users WHERE id IN ($placeholders)");
$stCheck->execute($ids);
$targets = $stCheck->fetchAll(PDO::FETCH_ASSOC);

foreach ($targets as $target) {
    $tId = (int)$target['id'];
    $tType = (int)$target['user_type'];
    $tRole = (string)($target['admin_role'] ?? '');
    $isTargetAdmin = ($tType === 3 || in_array($tRole, ['super_admin', 'staff'], true));

    // ۱. حفاظت ۱۰۰٪ از مدیر کل و مدیران سیستم در برابر کارمندان
    if (is_staff() && $isTargetAdmin) {
        json_out(['ok' => false, 'message' => 'دسترسی غیرمجاز. کارمندان به هیچ وجه مجاز به حذف یا تغییر وضعیت حساب‌های مدیران و کارمندان نیستند.'], 403);
    }

    // ۲. مدیر کل اصلی سیستم تحت هیچ شرایطی حذف یا غیرفعال نمی‌شود
    if ($tId === 1 || ($tRole === 'super_admin' && ($action === 'delete' || $action === 'deactivate'))) {
        json_out(['ok' => false, 'message' => 'امکان حذف یا غیرفعال‌سازی مدیر کل اصلی سیستم وجود ندارد.'], 403);
    }

    // ۳. بررسی پرمیشن کارمند برای رانندگان و صاحبان بار
    if (is_staff()) {
        if ($action === 'delete') {
            if ($tType === 1 && !has_permission('drivers.delete')) {
                json_out(['ok' => false, 'message' => 'شما مجوز حذف حساب‌های رانندگان را ندارید.'], 403);
            }
            if ($tType === 2 && !has_permission('cargos_users.delete')) {
                json_out(['ok' => false, 'message' => 'شما مجوز حذف حساب‌های صاحبان بار را ندارید.'], 403);
            }
        } elseif ($action === 'deactivate' || $action === 'activate') {
            if ($tType === 1 && !has_permission('drivers.status')) {
                json_out(['ok' => false, 'message' => 'شما مجوز تغییر وضعیت رانندگان را ندارید.'], 403);
            }
            if ($tType === 2 && !has_permission('cargos_users.status')) {
                json_out(['ok' => false, 'message' => 'شما مجوز تغییر وضعیت صاحبان بار را ندارید.'], 403);
            }
        }
    }
}

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
    error_log('admin.users action failed: ' . $e->getMessage());
    json_out(['ok' => false, 'message' => 'انجام عملیات ممکن نشد. لطفاً دوباره تلاش کنید.'], 500);
}
