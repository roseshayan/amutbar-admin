<?php

declare(strict_types=1);

require_once __DIR__ . '/../../includes/init.php';
require_super_admin();
require_post();

$id = (int)($_POST['id'] ?? 0);
$fullName = sanitize_input(trim((string)($_POST['full_name'] ?? '')));
$phone = preg_replace('/\D+/', '', (string)($_POST['phone'] ?? ''));
$password = (string)($_POST['password'] ?? '');
$grantDefault = !empty($_POST['grant_default']);

if ($fullName === '') {
    json_out(['ok' => false, 'message' => 'نام و نام خانوادگی الزامی است'], 422);
}
if (!preg_match('/^09\d{9}$/', $phone)) {
    json_out(['ok' => false, 'message' => 'شماره موبایل را به صورت ۱۱ رقمی معتبر (مانند 09121234567) وارد کنید'], 422);
}

$pdo = db();

if ($id > 0) {
    // ویرایش مشخصات کارمند
    $stCheck = $pdo->prepare("SELECT id, admin_role, user_type FROM users WHERE id = ? AND user_type = 3 AND deleted_at IS NULL LIMIT 1");
    $stCheck->execute([$id]);
    $existing = $stCheck->fetch();
    if (!$existing) {
        json_out(['ok' => false, 'message' => 'کارمند مورد نظر یافت نشد'], 404);
    }

    // بررسی تکراری نبودن شماره موبایل
    $stDup = $pdo->prepare("SELECT id FROM users WHERE phone = ? AND id <> ? AND deleted_at IS NULL LIMIT 1");
    $stDup->execute([$phone, $id]);
    if ($stDup->fetchColumn()) {
        json_out(['ok' => false, 'message' => 'این شماره موبایل قبلاً برای کاربر دیگری ثبت شده است'], 422);
    }

    if ($password !== '' && strlen($password) < 8) {
        json_out(['ok' => false, 'message' => 'رمز عبور جدید باید حداقل ۸ کاراکتر باشد'], 422);
    }

    try {
        if ($password !== '') {
            $passHash = password_hash($password, PASSWORD_DEFAULT);
            $st = $pdo->prepare("UPDATE users SET full_name = ?, phone = ?, password_hash = ?, updated_at = NOW(3) WHERE id = ? LIMIT 1");
            $st->execute([$fullName, $phone, $passHash, $id]);
        } else {
            $st = $pdo->prepare("UPDATE users SET full_name = ?, phone = ?, updated_at = NOW(3) WHERE id = ? LIMIT 1");
            $st->execute([$fullName, $phone, $id]);
        }

        audit_log('staff.edit', 'user', $id, ['full_name' => $fullName, 'phone' => $phone]);
        json_out(['ok' => true, 'message' => 'مشخصات کارمند با موفقیت ویرایش شد']);
    } catch (Throwable $e) {
        error_log('staff_save error: ' . $e->getMessage());
        json_out(['ok' => false, 'message' => 'خطا در ویرایش مشخصات. لطفاً مجدداً تلاش کنید.'], 500);
    }
} else {
    // ثبت کارمند جدید
    if ($password === '') {
        json_out(['ok' => false, 'message' => 'تعیین رمز عبور برای کارمند جدید الزامی است'], 422);
    }
    if (strlen($password) < 8) {
        json_out(['ok' => false, 'message' => 'رمز عبور باید حداقل ۸ کاراکتر باشد'], 422);
    }

    $stDup = $pdo->prepare("SELECT id FROM users WHERE phone = ? AND deleted_at IS NULL LIMIT 1");
    $stDup->execute([$phone]);
    if ($stDup->fetchColumn()) {
        json_out(['ok' => false, 'message' => 'این شماره موبایل قبلاً در سیستم ثبت شده است'], 422);
    }

    try {
        $pdo->beginTransaction();

        $passHash = password_hash($password, PASSWORD_DEFAULT);
        $initialPerms = $grantDefault ? get_default_staff_permissions() : [];
        $permsJson = json_encode($initialPerms, JSON_UNESCAPED_UNICODE);

        $st = $pdo->prepare("
            INSERT INTO users (full_name, phone, password_hash, user_type, admin_role, status, permissions, created_at, updated_at)
            VALUES (?, ?, ?, 3, 'staff', 1, ?, NOW(3), NOW(3))
        ");
        $st->execute([$fullName, $phone, $passHash, $permsJson]);
        $newId = (int)$pdo->lastInsertId();

        // اتصال به جدول user_roles
        $pdo->prepare("INSERT IGNORE INTO user_roles (user_id, role_id) VALUES (?, 2)")
            ->execute([$newId]);

        // اتصال به جدول user_permissions
        if (!empty($initialPerms)) {
            $inClause = implode(',', array_fill(0, count($initialPerms), '?'));
            $stP = $pdo->prepare("SELECT id FROM permissions WHERE perm_key IN ($inClause)");
            $stP->execute($initialPerms);
            $pIds = $stP->fetchAll(PDO::FETCH_COLUMN);

            $stIns = $pdo->prepare("INSERT IGNORE INTO user_permissions (user_id, permission_id) VALUES (?, ?)");
            foreach ($pIds as $pId) {
                $stIns->execute([$newId, (int)$pId]);
            }
        }

        $pdo->commit();
        audit_log('staff.create', 'user', $newId, ['full_name' => $fullName, 'phone' => $phone]);

        json_out(['ok' => true, 'id' => $newId, 'message' => 'کارمند جدید با موفقیت تعریف شد']);
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log('staff_create error: ' . $e->getMessage());
        json_out(['ok' => false, 'message' => 'خطا در ثبت کارمند جدید. لطفاً مجدداً تلاش کنید.'], 500);
    }
}
