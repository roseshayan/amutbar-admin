<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_post();

if (!is_ajax()) json_out(['ok' => false, 'message' => 'Bad request'], 400);

try {
    $phone = preg_replace('/\D+/', '', (string)($_POST['phone'] ?? ''));
    if (!preg_match('/^09\d{9}$/', $phone)) {
        json_out(['ok' => false, 'message' => 'شماره موبایل نامعتبر است'], 422);
    }

    $pdo = db();

    // فقط ادمین‌ها
    $st = $pdo->prepare("SELECT id FROM users WHERE phone_active=? AND user_type=3 AND status=1 LIMIT 1");
    $st->execute([$phone]);
    $uid = (int)($st->fetchColumn() ?: 0);
    if ($uid <= 0) json_out(['ok' => false, 'message' => 'ادمینی با این شماره یافت نشد'], 404);

    $cooldownSec = 60;
    $expiresSec  = 300;

    $last = $pdo->prepare("SELECT created_at FROM otp_codes WHERE phone=? AND purpose=3 ORDER BY id DESC LIMIT 1");
    $last->execute([$phone]);
    $lastAt = $last->fetchColumn();

    if ($lastAt) {
        $diff = time() - strtotime($lastAt);
        if ($diff < $cooldownSec) {
            json_out(['ok' => false, 'message' => 'کمی صبر کنید', 'resend_in_sec' => ($cooldownSec - $diff)], 429);
        }
    }

    $code = (string)random_int(100000, 999999);
    $pepper = (string)env('OTP_PEPPER', env('CSRF_SECRET', ''));
    $hash = hash('sha256', $phone . '|3|' . $code . '|' . $pepper);

    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
    $ipBin = $ip ? @inet_pton($ip) : null;

    $ins = $pdo->prepare("
    INSERT INTO otp_codes (phone, purpose, code_hash, expires_at, attempt_count, ip_address, created_at)
    VALUES (?, 3, ?, DATE_ADD(NOW(3), INTERVAL ? SECOND), 0, ?, NOW(3))
  ");
    $ins->execute([$phone, $hash, $expiresSec, $ipBin]);

    $sms = payamak_send_otp($phone, $code);
    if (!$sms['ok']) {
        json_out(['ok' => false, 'message' => 'ارسال پیامک ناموفق بود'], 500);
    }

    json_out(['ok' => true, 'message' => 'کد تایید ارسال شد', 'expires_in_sec' => $expiresSec, 'resend_in_sec' => $cooldownSec]);
} catch (Throwable $e) {
    $msg = ((string)env('APP_DEBUG', '0') === '1') ? $e->getMessage() : 'خطای سرور';
    json_out(['ok' => false, 'message' => $msg], 500);
}
