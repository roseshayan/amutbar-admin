<?php
declare(strict_types=1);

/** The stored user_type is the original account type, not the current app. */
function api_user_has_app_role(array $user, int $role): bool
{
    $original = (int)$user['user_type'];
    // Mobile enrolment must never grant or inherit administrator access.
    if ($original === 3 || $role === 3) return $original === 3 && $role === 3;
    if (!in_array($role, [1, 2], true) || !in_array($original, [1, 2], true)) return false;
    $st = db()->prepare('SELECT status FROM user_app_roles WHERE user_id=? AND app_role=?');
    $st->execute([(int)$user['id'], $role]);
    $status = $st->fetchColumn();
    // Keep existing mobile tokens/accounts working before their first enrolment.
    return $status === false ? $original === $role : (int)$status === 1;
}

/** Called only after successful OTP verification, inside the user's transaction. */
function api_enroll_app_role(array $user, int $role): bool
{
    if (!in_array((int)$user['user_type'], [1, 2], true)
        || !in_array($role, [1, 2], true)) return false;
    db()->prepare('INSERT INTO user_app_roles (user_id, app_role, status) VALUES (?, ?, 1)
        ON DUPLICATE KEY UPDATE user_id=VALUES(user_id)')
        ->execute([(int)$user['id'], $role]);
    // An explicitly disabled membership is never reactivated by login.
    return api_user_has_app_role($user, $role);
}

/** Only call after OTP consumption. A phone always resolves to one live account. */
function api_login_mobile_account(string $phone, int $role): array
{
    if (!in_array($role, [1, 2], true)) {
        return ['ok' => false, 'status' => 422, 'message' => 'نقش نامعتبر است'];
    }
    $pdo = db();
    $pdo->beginTransaction();
    try {
        // A unique phone_active serializes simultaneous first logins from both apps.
        // On a duplicate, preserve the identity, original type and account status.
        $pdo->prepare("INSERT INTO users (full_name, phone, user_type, status, created_at, updated_at)
            VALUES (?, ?, ?, 1, NOW(3), NOW(3))
            ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)")
            ->execute(['کاربر', $phone, $role]);
        $st = $pdo->prepare('SELECT id, user_type, status FROM users WHERE phone_active=? LIMIT 1 FOR UPDATE');
        $st->execute([$phone]);
        $user = $st->fetch();
        if (!$user || !in_array((int)$user['user_type'], [1, 2], true)) {
            $pdo->rollBack();
            return ['ok' => false, 'status' => 403, 'message' => 'این حساب امکان ورود به اپلیکیشن را ندارد'];
        }
        if (!in_array((int)$user['status'], [1, 3], true)) {
            $pdo->rollBack();
            return ['ok' => false, 'status' => 403, 'message' => 'حساب کاربری غیرفعال یا مسدود است'];
        }
        if (!api_enroll_app_role($user, $role)) {
            $pdo->rollBack();
            return ['ok' => false, 'status' => 403, 'message' => 'دسترسی شما به این اپلیکیشن غیرفعال است'];
        }
        if ((int)$user['status'] === 3) {
            $pdo->prepare('UPDATE users SET status=1, updated_at=NOW(3) WHERE id=?')->execute([(int)$user['id']]);
        }
        $pdo->commit();
        return ['ok' => true, 'user_id' => (int)$user['id']];
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        error_log('auth.mobile_account failed: ' . $e->getMessage());
        return ['ok' => false, 'status' => 500, 'message' => 'ورود انجام نشد. لطفاً دوباره تلاش کنید.'];
    }
}
