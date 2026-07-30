<?php

declare(strict_types=1);

function admin_id(): ?int
{
    $pdo = db();

    // نشست موجود باید در هر درخواست با وضعیت فعلی کاربر تطبیق داده شود.
    if (isset($_SESSION['admin_user_id'])) {
        $sessionUserId = (int)$_SESSION['admin_user_id'];
        $st = $pdo->prepare("SELECT id FROM users WHERE id=? AND user_type=3 AND status=1 AND deleted_at IS NULL LIMIT 1");
        $st->execute([$sessionUserId]);
        if ($st->fetchColumn()) return $sessionUserId;
        unset($_SESSION['admin_user_id']);
    }

    // اگر نشست معتبر نداریم، کوکی یادآوری را بررسی کن.
    if (isset($_COOKIE['admin_remember_token'])) {
        $token = (string)$_COOKIE['admin_remember_token'];
        $st = $pdo->prepare("SELECT user_id FROM remember_tokens WHERE token_hash = ? AND expires_at > NOW() LIMIT 1");
        $st->execute([hash('sha256', $token)]);
        $row = $st->fetch();

        if ($row && isset($row['user_id'])) {
            $user_id = (int)$row['user_id'];

            // کاربر را بررسی کن
            $st = $pdo->prepare("SELECT id, user_type, status FROM users WHERE id = ? AND deleted_at IS NULL LIMIT 1");
            $st->execute([$user_id]);
            $user = $st->fetch();

            if ($user && (int)$user['user_type'] === 3 && (int)$user['status'] === 1) {
                session_regenerate_id(true);
                $_SESSION['admin_user_id'] = $user_id;
                unset($_SESSION['csrf_token']);
                auth_mark_last_login($user_id);
                auth_log_event($user_id, 3, 3, ['via' => 'remember_token']);
                return $user_id;
            }
        }
    }

    return null;
}

function require_admin(): void
{
    if (!admin_id()) {
        if (is_ajax()) json_out(['ok' => false, 'message' => 'Unauthorized'], 401);
        header('Location: ' . url_path('login.php'));
        exit;
    }
}

function create_remember_token(int $user_id): void
{
    $token = bin2hex(random_bytes(32));
    $expires_at = date('Y-m-d H:i:s', time() + (7 * 24 * 60 * 60)); // 7 روز

    $pdo = db();

    // حذف توکن‌های قبلی این کاربر
    $pdo->prepare("DELETE FROM remember_tokens WHERE user_id = ?")
        ->execute([$user_id]);

    // ذخیره توکن جدید
    $st = $pdo->prepare("INSERT INTO remember_tokens (user_id, token_hash, expires_at) VALUES (?, ?, ?)");
    $st->execute([
        $user_id,
        hash('sha256', $token),
        $expires_at
    ]);

    // تنظیم کوکی
    $cookie_options = [
        'expires' => time() + (7 * 24 * 60 * 60),
        'path' => '/',
        'domain' => '',
        'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || strtolower((string)($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https',
        'httponly' => true,
        'samesite' => 'Lax'
    ];

    setcookie('admin_remember_token', $token, $cookie_options);
}

function admin_login(string $phone, string $password, bool $remember = false): bool
{
    $pdo = db();
    $st = $pdo->prepare("SELECT id, full_name, avatar_key, password_hash, user_type, status, failed_login_attempts, locked_until FROM users WHERE phone_active = ? LIMIT 1");
    $st->execute([$phone]);
    $u = $st->fetch();

    if (!$u) return false;
    if ((int)$u['user_type'] !== 3) return false;
    if ((int)$u['status'] !== 1) return false;
    if (!empty($u['locked_until']) && strtotime((string)$u['locked_until']) > time()) return false;

    if (!password_verify($password, (string)$u['password_hash'])) {
        $attempts = (int)($u['failed_login_attempts'] ?? 0) + 1;
        $lockedUntil = $attempts >= 5 ? date('Y-m-d H:i:s', time() + 15 * 60) : null;
        $pdo->prepare("UPDATE users SET failed_login_attempts=?, locked_until=?, updated_at=NOW(3) WHERE id=? LIMIT 1")
            ->execute([$attempts, $lockedUntil, (int)$u['id']]);
        auth_log_event((int)$u['id'], 3, 5, ['reason' => 'bad_password']);
        return false;
    }

    $user_id = $u['id'];

    session_regenerate_id(true);
    $_SESSION['admin_user_id'] = (int)$user_id;
    unset($_SESSION['csrf_token']);
    $pdo->prepare("UPDATE users SET failed_login_attempts=0, locked_until=NULL, updated_at=NOW(3) WHERE id=? LIMIT 1")
        ->execute([(int)$user_id]);

    auth_mark_last_login((int)$user_id);
    auth_log_event((int)$user_id, 3, 1, ['remember' => $remember]);

    // اگر کاربر گزینه "مرا به خاطر بسپار" را انتخاب کرده بود
    if ($remember) {
        create_remember_token((int)$user_id);
    }

    return true;
}

function admin_logout(): void
{
    // log before session is destroyed
    $uid = admin_id();
    if ($uid) {
        auth_log_event((int)$uid, 3, 2);
    }

    // حذف توکن یادآوری از دیتابیس
    if (isset($_COOKIE['admin_remember_token'])) {
        $token = $_COOKIE['admin_remember_token'];
        $pdo = db();
        $pdo->prepare("DELETE FROM remember_tokens WHERE token_hash = ?")
            ->execute([hash('sha256', $token)]);
    }

    // حذف کوکی
    $cookie_options = [
        'expires' => time() - 3600,
        'path' => '/',
        'domain' => '',
        'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || strtolower((string)($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https',
        'httponly' => true,
        'samesite' => 'Lax',
    ];

    setcookie('admin_remember_token', '', $cookie_options);

    // حذف سشن
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'] ?? '', $p['secure'] ?? false, $p['httponly'] ?? true);
    }

    session_destroy();
}
