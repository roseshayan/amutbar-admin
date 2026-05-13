<?php

declare(strict_types=1);

require_once __DIR__ . '/../_bootstrap.php';

// این فایل برای سازگاری باقی مانده؛ پیشنهاد اصلی استفاده از مسیر:
// POST /api/v1/auth/logout (در router)

$in = api_input();
$refreshToken = (string)($in['refresh_token'] ?? '');
if ($refreshToken !== '') {
    api_revoke_refresh($refreshToken);
    api_ok(['message' => 'ok']);
}

// اگر refresh_token ارسال نشود، این endpoint فقط OK برمی‌گرداند.
api_ok(['message' => 'ok']);
