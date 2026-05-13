<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_post();

try {
    $phone = preg_replace('/\D+/', '', (string)($_POST['phone'] ?? ''));
    $code  = preg_replace('/\D+/', '', (string)($_POST['code'] ?? ''));
    $new1  = (string)($_POST['new_password'] ?? '');
    $new2  = (string)($_POST['new_password_confirm'] ?? '');

    if (!preg_match('/^09\d{9}$/', $phone)) json_out(['ok' => false, 'message' => 'شماره موبایل نامعتبر است'], 422);
    if (!preg_match('/^\d{6}$/', $code)) json_out(['ok' => false, 'message' => 'کد تایید نامعتبر است'], 422);
    if (strlen($new1) < 8) json_out(['ok' => false, 'message' => 'رمز عبور حداقل 8 کاراکتر باشد'], 422);
    if ($new1 !== $new2) json_out(['ok' => false, 'message' => 'تکرار رمز عبور صحیح نیست'], 422);

    $pdo = db();

    // آخرین OTP معتبر
    $st = $pdo->prepare("
      SELECT id, code_hash, attempt_count
      FROM otp_codes
      WHERE phone=? AND purpose=3 AND consumed_at IS NULL AND expires_at > NOW(3)
      ORDER BY id DESC
      LIMIT 1
    ");
    $st->execute([$phone]);
    $otp = $st->fetch();
    if (!$otp) json_out(['ok' => false, 'message' => 'کد منقضی شده یا یافت نشد'], 400);

    if ((int)$otp['attempt_count'] >= 5) {
        json_out(['ok' => false, 'message' => 'تلاش‌های ناموفق زیاد است. دوباره کد بگیرید'], 429);
    }

    $pepper = (string)env('OTP_PEPPER', env('CSRF_SECRET', ''));
    $hash = hash('sha256', $phone . '|3|' . $code . '|' . $pepper);

    if (!hash_equals((string)$otp['code_hash'], $hash)) {
        $pdo->prepare("UPDATE otp_codes SET attempt_count = attempt_count + 1 WHERE id=?")
            ->execute([(int)$otp['id']]);
        json_out(['ok' => false, 'message' => 'کد تایید اشتباه است'], 401);
    }

    // مصرف OTP
    $pdo->prepare("UPDATE otp_codes SET consumed_at = NOW(3) WHERE id=?")
        ->execute([(int)$otp['id']]);

    // پیدا کردن ادمین
    $u = $pdo->prepare("SELECT id FROM users WHERE phone_active=? AND user_type=3 AND status=1 LIMIT 1");
    $u->execute([$phone]);
    $uid = (int)($u->fetchColumn() ?: 0);
    if ($uid <= 0) json_out(['ok' => false, 'message' => 'ادمین یافت نشد'], 404);

    $passHash = password_hash($new1, PASSWORD_DEFAULT);
    $pdo->prepare("UPDATE users SET password_hash=?, updated_at=NOW(3) WHERE id=? LIMIT 1")
        ->execute([$passHash, $uid]);

    json_out(['ok' => true, 'message' => 'رمز عبور با موفقیت تغییر کرد']);
} catch (Throwable $e) {
    $msg = ((string)env('APP_DEBUG', '0') === '1') ? ($e->getMessage()) : 'خطای سرور';
    json_out(['ok' => false, 'message' => $msg], 500);
}
