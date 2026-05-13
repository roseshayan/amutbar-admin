<?php

declare(strict_types=1);

function admin_id(): ?int
{
    // اول کوکی را بررسی کن
    if (isset($_COOKIE['admin_remember_token'])) {
        $token = $_COOKIE['admin_remember_token'];
        $pdo = db();
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
                $_SESSION['admin_user_id'] = $user_id;
                // remember-login counts as a login event (but only when session is created)
                auth_mark_last_login($user_id);
                auth_log_event($user_id, 3, 3, ['via' => 'remember_token']);
                return $user_id;
            }
        }
    }

    // اگر کوکی معتبر نبود، سشن را بررسی کن
    return isset($_SESSION['admin_user_id']) ? (int)$_SESSION['admin_user_id'] : null;
}

function require_admin(): void
{
    if (!admin_id()) {
        if (is_ajax()) json_out(['ok' => false, 'message' => 'Unauthorized'], 401);
        header('Location: /index.php?page=login');
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
        'secure' => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax'
    ];

    setcookie('admin_remember_token', $token, $cookie_options);
}

function admin_login(string $phone, string $password, bool $remember = false): bool
{
    $pdo = db();
    $st = $pdo->prepare("SELECT id, full_name, avatar_key, password_hash, user_type, status FROM users WHERE phone_active = ? LIMIT 1");
    $st->execute([$phone]);
    $u = $st->fetch();

    if (!$u) return false;
    if ((int)$u['user_type'] !== 3) return false;
    if ((int)$u['status'] !== 1) return false;

    if (!password_verify($password, (string)$u['password_hash'])) {
        auth_log_event((int)$u['id'], 3, 5, ['reason' => 'bad_password']);
        return false;
    }

    $user_id = $u['id'];

    $_SESSION['admin_user_id'] = (int)$user_id;

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
        'secure' => isset($_SERVER['HTTPS']),
        'httponly' => true
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
