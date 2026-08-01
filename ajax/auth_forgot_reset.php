<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_post();
csrf_require_valid();

if (!is_ajax()) json_out(['ok' => false, 'message' => 'Bad request'], 400);

try {
    $phone = preg_replace('/\D+/', '', (string)($_POST['phone'] ?? ''));
    $code  = preg_replace('/\D+/', '', (string)($_POST['code'] ?? ''));
    $new1  = (string)($_POST['new_password'] ?? '');
    $new2  = (string)($_POST['new_password_confirm'] ?? '');

    if (!preg_match('/^09\d{9}$/', $phone)) json_out(['ok' => false, 'message' => 'شماره موبایل نامعتبر است'], 422);
    if (!preg_match('/^\d{6}$/', $code)) json_out(['ok' => false, 'message' => 'کد تایید نامعتبر است'], 422);
    if (strlen($new1) < 12) json_out(['ok' => false, 'message' => 'رمز عبور باید حداقل ۱۲ کاراکتر باشد'], 422);
    if ($new1 !== $new2) json_out(['ok' => false, 'message' => 'تکرار رمز عبور صحیح نیست'], 422);

    $pdo = db();

    $consumed = otp_consume($phone, 3, $code, 5);
    if (!$consumed['ok']) {
        json_out(
            ['ok' => false, 'message' => (string)$consumed['message']],
            (int)($consumed['status'] ?? 400)
        );
    }

    // پیدا کردن ادمین
    $u = $pdo->prepare("SELECT id FROM users WHERE phone_active=? AND user_type=3 AND status=1 AND deleted_at IS NULL LIMIT 1");
    $u->execute([$phone]);
    $uid = (int)($u->fetchColumn() ?: 0);
    if ($uid <= 0) json_out(['ok' => false, 'message' => 'ادمین یافت نشد'], 404);

    $passHash = password_hash($new1, PASSWORD_DEFAULT);
    $pdo->prepare("UPDATE users SET password_hash=?, failed_login_attempts=0, locked_until=NULL, updated_at=NOW(3) WHERE id=? LIMIT 1")
        ->execute([$passHash, $uid]);
    $pdo->prepare("DELETE FROM remember_tokens WHERE user_id=?")->execute([$uid]);
    $pdo->prepare("UPDATE jwt_refresh_tokens SET revoked_at=NOW(3) WHERE user_id=? AND revoked_at IS NULL")
        ->execute([$uid]);

    json_out(['ok' => true, 'message' => 'رمز عبور با موفقیت تغییر کرد']);
} catch (Throwable $e) {
    error_log('admin.password reset failed: ' . $e->getMessage());
    json_out(['ok' => false, 'message' => 'تغییر رمز انجام نشد. لطفاً دوباره تلاش کنید.'], 500);
}
