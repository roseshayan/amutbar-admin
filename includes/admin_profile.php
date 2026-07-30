<?php

declare(strict_types=1);

function admin_get_profile_data(int $admin_id): ?array
{
    $pdo = db();
    $st = $pdo->prepare("
        SELECT 
            id,
            full_name,
            phone,
            email,
            user_type,
            status,
            display_name,
            avatar_key,
            last_login_at,
            created_at,
            updated_at
        FROM users 
        WHERE id = ? 
          AND user_type = 3 
          AND status = 1 
          AND deleted_at IS NULL 
        LIMIT 1
    ");
    $st->execute([$admin_id]);
    $user = $st->fetch();
    
    return $user ?: null;
}

function admin_update_profile(array $data): array
{
    $pdo = db();
    $admin_id = (int)($data['id'] ?? 0);
    
    if ($admin_id <= 0) {
        return ['ok' => false, 'message' => 'شناسه کاربر نامعتبر است'];
    }
    
    // بررسی وجود کاربر
    $st = $pdo->prepare("SELECT id FROM users WHERE id = ? AND user_type = 3 AND deleted_at IS NULL LIMIT 1");
    $st->execute([$admin_id]);
    if (!$st->fetch()) {
        return ['ok' => false, 'message' => 'کاربر یافت نشد'];
    }
    
    $full_name = sanitize_input((string)($data['full_name'] ?? ''));
    $email = trim((string)($data['email'] ?? ''));
    $phone = preg_replace('/\D+/', '', (string)($data['phone'] ?? ''));
    $password = (string)($data['password'] ?? '');
    $current_password = (string)($data['current_password'] ?? '');
    
    // اعتبارسنجی فیلدهای ضروری
    if ($full_name === '') {
        return ['ok' => false, 'message' => 'نام و نام خانوادگی الزامی است'];
    }
    
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['ok' => false, 'message' => 'ایمیل نامعتبر است'];
    }
    
    if (!preg_match('/^09\d{9}$/', $phone)) {
        return ['ok' => false, 'message' => 'شماره موبایل نامعتبر است'];
    }
    
    // بررسی تکراری نبودن شماره موبایل
    $st = $pdo->prepare("SELECT id FROM users WHERE phone = ? AND id <> ? AND deleted_at IS NULL LIMIT 1");
    $st->execute([$phone, $admin_id]);
    if ($st->fetchColumn()) {
        return ['ok' => false, 'message' => 'این شماره موبایل قبلاً ثبت شده است'];
    }
    
    // بررسی تکراری نبودن ایمیل
    if ($email !== '') {
        $st = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id <> ? AND deleted_at IS NULL LIMIT 1");
        $st->execute([$email, $admin_id]);
        if ($st->fetchColumn()) {
            return ['ok' => false, 'message' => 'این ایمیل قبلاً ثبت شده است'];
        }
    }
    
    // اگر رمز عبور جدید وارد شده، اعتبارسنجی شود
    $update_password = false;
    $new_password_hash = null;
    
    if ($password !== '') {
        if (strlen($password) < 12) {
            return ['ok' => false, 'message' => 'رمز عبور جدید باید حداقل ۱۲ کاراکتر باشد'];
        }
        
        // برای تغییر رمز عبور، رمز عبور فعلی باید وارد شود
        if ($current_password === '') {
            return ['ok' => false, 'message' => 'برای تغییر رمز عبور، رمز عبور فعلی را وارد کنید'];
        }
        
        // بررسی رمز عبور فعلی
        $st = $pdo->prepare("SELECT password_hash FROM users WHERE id = ? LIMIT 1");
        $st->execute([$admin_id]);
        $current_hash = $st->fetchColumn();
        
        if (!$current_hash || !password_verify($current_password, (string)$current_hash)) {
            return ['ok' => false, 'message' => 'رمز عبور فعلی نادرست است'];
        }
        
        $update_password = true;
        $new_password_hash = password_hash($password, PASSWORD_DEFAULT);
    }
    
    try {
        $pdo->beginTransaction();
        
        if ($update_password) {
            $st = $pdo->prepare("
                UPDATE users 
                SET 
                    full_name = ?,
                    phone = ?,
                    email = ?,
                    password_hash = ?,
                    updated_at = NOW(3)
                WHERE id = ?
            ");
            $st->execute([$full_name, $phone, ($email === '' ? null : $email), $new_password_hash, $admin_id]);
            $pdo->prepare("DELETE FROM remember_tokens WHERE user_id=?")->execute([$admin_id]);
            $pdo->prepare("UPDATE jwt_refresh_tokens SET revoked_at=NOW(3) WHERE user_id=? AND revoked_at IS NULL")
                ->execute([$admin_id]);
        } else {
            $st = $pdo->prepare("
                UPDATE users 
                SET 
                    full_name = ?,
                    phone = ?,
                    email = ?,
                    updated_at = NOW(3)
                WHERE id = ?
            ");
            $st->execute([$full_name, $phone, ($email === '' ? null : $email), $admin_id]);
        }
        
        $pdo->commit();
        
        // آپدیت سشن در صورت تغییر شماره موبایل
        if (isset($_SESSION['admin_user_id']) && $_SESSION['admin_user_id'] == $admin_id) {
            $_SESSION['admin_user_id'] = $admin_id;
        }
        
        return ['ok' => true, 'message' => 'پروفایل با موفقیت بروزرسانی شد'];
        
    } catch (Throwable $e) {
        $pdo->rollBack();
        $msg = ((string)env('APP_DEBUG', '0') === '1') ? $e->getMessage() : 'خطا در بروزرسانی پروفایل';
        return ['ok' => false, 'message' => $msg];
    }
}

function admin_update_avatar(int $admin_id, array $avatarFile): array
{
    // Ensure admin exists
    $pdo = db();
    $st = $pdo->prepare("SELECT id FROM users WHERE id=? AND user_type=3 AND deleted_at IS NULL LIMIT 1");
    $st->execute([$admin_id]);
    if (!$st->fetchColumn()) {
        return ['ok' => false, 'message' => 'کاربر یافت نشد', 'status' => 404];
    }

    $res = user_update_avatar($admin_id, $avatarFile);
    if (!$res['ok']) return $res;

    // audit
    audit_log('admin.avatar.updated', 'users', $admin_id, ['avatar_key' => $res['avatar_key'] ?? null]);
    return $res;
}

function admin_get_simple_stats(int $admin_id): array
{
    $pdo = db();
    
    // تعداد کاربران کل
    $st = $pdo->prepare("SELECT COUNT(*) FROM users WHERE deleted_at IS NULL");
    $st->execute();
    $total_users = (int)$st->fetchColumn();
    
    // تعداد کاربران امروز
    $st = $pdo->prepare("SELECT COUNT(*) FROM users WHERE DATE(created_at) = CURDATE() AND deleted_at IS NULL");
    $st->execute();
    $today_users = (int)$st->fetchColumn();
    
    // تعداد ادمین‌ها
    $st = $pdo->prepare("SELECT COUNT(*) FROM users WHERE user_type = 3 AND status = 1 AND deleted_at IS NULL");
    $st->execute();
    $admin_count = (int)$st->fetchColumn();
    
    // آخرین ورود
    $st = $pdo->prepare("SELECT last_login_at FROM users WHERE id = ?");
    $st->execute([$admin_id]);
    $last_login = $st->fetchColumn();
    
    return [
        'total_users' => $total_users,
        'today_users' => $today_users,
        'admin_count' => $admin_count,
        'last_login' => $last_login
    ];
}
