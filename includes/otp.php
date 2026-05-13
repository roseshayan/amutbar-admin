<?php

declare(strict_types=1);

// -----------------------------
// OTP (SMS) utilities
// Table: otp_codes
// -----------------------------

// Purposes
// 1: App login/register (driver/company)
// 3: Admin forgot-password (already used in ajax/*)

function otp_pepper(): string
{
    return (string)env('OTP_PEPPER', env('CSRF_SECRET', ''));
}

function otp_hash(string $phone, int $purpose, string $code): string
{
    return hash('sha256', $phone . '|' . $purpose . '|' . $code . '|' . otp_pepper());
}

function otp_issue(string $phone, int $purpose, int $expiresSec = 300, int $cooldownSec = 60): array
{
    $pdo = db();

    // -----------------------------
    // Rate limits (بهینه و ساده)
    // -----------------------------
    // برای جلوگیری از اسپم/حملات، روی موبایل و IP محدودیت داریم.
    // مقادیر قابل تنظیم از ENV:
    // OTP_MAX_PER_PHONE_HOUR, OTP_MAX_PER_PHONE_DAY, OTP_MAX_PER_IP_HOUR
    // از تنظیمات پنل بخوان (fallback روی ENV)
    $maxPhoneHour = function_exists('api_otp_max_per_phone_hour') ? api_otp_max_per_phone_hour() : (int)env('OTP_MAX_PER_PHONE_HOUR', 5);
    $maxPhoneDay = function_exists('api_otp_max_per_phone_day') ? api_otp_max_per_phone_day() : (int)env('OTP_MAX_PER_PHONE_DAY', 20);
    $maxIpHour = function_exists('api_otp_max_per_ip_hour') ? api_otp_max_per_ip_hour() : (int)env('OTP_MAX_PER_IP_HOUR', 30);
    if ($maxPhoneHour <= 0) $maxPhoneHour = 5;
    if ($maxPhoneDay <= 0) $maxPhoneDay = 20;
    if ($maxIpHour <= 0) $maxIpHour = 30;

    $ip = $_SERVER['REMOTE_ADDR'] ?? null;

    $stHour = $pdo->prepare("SELECT COUNT(*) FROM otp_codes WHERE phone=? AND purpose=? AND created_at >= DATE_SUB(NOW(3), INTERVAL 1 HOUR)");
    $stHour->execute([$phone, $purpose]);
    if ((int)$stHour->fetchColumn() >= $maxPhoneHour) {
        return ['ok' => false, 'status' => 429, 'message' => 'تعداد درخواست زیاد است. کمی بعد تلاش کنید'];
    }

    $stDay = $pdo->prepare("SELECT COUNT(*) FROM otp_codes WHERE phone=? AND purpose=? AND created_at >= DATE_SUB(NOW(3), INTERVAL 1 DAY)");
    $stDay->execute([$phone, $purpose]);
    if ((int)$stDay->fetchColumn() >= $maxPhoneDay) {
        return ['ok' => false, 'status' => 429, 'message' => 'تعداد درخواست امروز زیاد است. فردا دوباره تلاش کنید'];
    }

    if ($ip) {
        $stIp = $pdo->prepare("SELECT COUNT(*) FROM otp_codes WHERE ip_address=? AND created_at >= DATE_SUB(NOW(3), INTERVAL 1 HOUR)");
        $stIp->execute([$ip]);
        if ((int)$stIp->fetchColumn() >= $maxIpHour) {
            return ['ok' => false, 'status' => 429, 'message' => 'تعداد درخواست زیاد است. کمی بعد تلاش کنید'];
        }
    }

    $last = $pdo->prepare("SELECT created_at FROM otp_codes WHERE phone=? AND purpose=? ORDER BY id DESC LIMIT 1");
    $last->execute([$phone, $purpose]);
    $lastAt = $last->fetchColumn();

    if ($lastAt) {
        $diff = time() - strtotime((string)$lastAt);
        if ($diff < $cooldownSec) {
            return [
                'ok' => false,
                'status' => 429,
                'message' => 'کمی صبر کنید',
                'resend_in_sec' => ($cooldownSec - $diff),
            ];
        }
    }

    $len = function_exists('api_otp_length') ? api_otp_length() : 6;
    $min = (int)pow(10, $len - 1);
    $max = (int)pow(10, $len) - 1;
    $code = (string)random_int($min, $max);
    $hash = otp_hash($phone, $purpose, $code);

    $ins = $pdo->prepare("
        INSERT INTO otp_codes (phone, purpose, code_hash, expires_at, attempt_count, ip_address, created_at)
        VALUES (?, ?, ?, DATE_ADD(NOW(3), INTERVAL ? SECOND), 0, ?, NOW(3))
    ");
    $ins->execute([$phone, $purpose, $hash, $expiresSec, $ip]);
    $otpId = (int)$pdo->lastInsertId();

    $sms = payamak_send_otp($phone, $code);
    if (!$sms['ok']) {
        // Dev fallback: allow local testing without SMS credentials.
        if ((string)env('APP_ENV', 'local') === 'local') {
            return [
                'ok' => true,
                'otp_id' => $otpId,
                'expires_in_sec' => $expiresSec,
                'resend_in_sec' => $cooldownSec,
                'dev_code' => $code,
                'dev_note' => 'SMS provider not configured; returning code because APP_ENV=local',
            ];
        }
        return ['ok' => false, 'status' => 500, 'message' => 'ارسال پیامک ناموفق بود'];
    }

    return [
        'ok' => true,
        'otp_id' => $otpId,
        'expires_in_sec' => $expiresSec,
        'resend_in_sec' => $cooldownSec,
    ];
}

function otp_consume(string $phone, int $purpose, string $code, int $maxAttempts = 5, ?int $otpId = null): array
{
    $pdo = db();

    if ($otpId !== null && $otpId > 0) {
        $st = $pdo->prepare("
            SELECT id, code_hash, attempt_count
            FROM otp_codes
            WHERE id=? AND phone=? AND purpose=? AND consumed_at IS NULL AND expires_at > NOW(3)
            LIMIT 1
        ");
        $st->execute([(int)$otpId, $phone, $purpose]);
    } else {
        $st = $pdo->prepare("
            SELECT id, code_hash, attempt_count
            FROM otp_codes
            WHERE phone=? AND purpose=? AND consumed_at IS NULL AND expires_at > NOW(3)
            ORDER BY id DESC
            LIMIT 1
        ");
        $st->execute([$phone, $purpose]);
    }
    $otp = $st->fetch();
    if (!$otp) {
        return ['ok' => false, 'status' => 400, 'message' => 'کد منقضی شده یا یافت نشد'];
    }

    if ((int)$otp['attempt_count'] >= $maxAttempts) {
        return ['ok' => false, 'status' => 429, 'message' => 'تلاش‌های ناموفق زیاد است. دوباره کد بگیرید'];
    }

    $hash = otp_hash($phone, $purpose, $code);
    if (!hash_equals((string)$otp['code_hash'], $hash)) {
        $pdo->prepare("UPDATE otp_codes SET attempt_count = attempt_count + 1 WHERE id=?")
            ->execute([(int)$otp['id']]);
        return ['ok' => false, 'status' => 401, 'message' => 'کد تایید اشتباه است'];
    }

    $pdo->prepare("UPDATE otp_codes SET consumed_at = NOW(3) WHERE id=?")
        ->execute([(int)$otp['id']]);

    return ['ok' => true];
}
