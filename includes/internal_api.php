<?php

declare(strict_types=1);

require_once __DIR__ . '/settings.php';

/**
 * Internal API management (پنل)
 * - تنظیمات OTP/JWT
 * - فعال/غیرفعال کردن endpointها
 * - maintenance mode
 */

function internal_api_endpoints_catalog(): array
{
    // key => [title, group, default_enabled, maintenance_allow]
    return [
        'api.meta.app_config' => ['پیکربندی اپ (app-config)', 'Meta', true, true],
        'api.meta.vehicle_types' => ['لیست وسیله نقلیه (vehicle-types)', 'Meta', true, true],

        'api.auth.request_otp' => ['ارسال کد OTP', 'Auth', true, true],
        'api.auth.verify_otp' => ['تأیید کد OTP', 'Auth', true, true],
        'api.auth.refresh' => ['رفرش توکن', 'Auth', true, true],
        'api.auth.logout' => ['خروج', 'Auth', true, true],

        'api.me.get' => ['پروفایل من', 'Profile', true, true],
        'api.me.avatar' => ['آپلود آواتار', 'Profile', true, true],

        'api.driver.upsert' => ['ثبت/ویرایش پروفایل راننده', 'Onboarding', true, false],
        'api.company.upsert' => ['ثبت/ویرایش پروفایل باربری', 'Onboarding', true, false],
    ];
}

function internal_api_setting_key(string $key): string
{
    return 'internal_api.' . $key;
}

function internal_api_bool(string $key, bool $default = false): bool
{
    $v = settings_get(internal_api_setting_key($key));
    if ($v === null) return $default;
    return (string)$v === '1' || strtolower((string)$v) === 'true';
}

function internal_api_int(string $key, int $default): int
{
    $v = settings_get(internal_api_setting_key($key));
    if ($v === null || $v === '') return $default;
    return (int)$v;
}

function internal_api_str(string $key, string $default = ''): string
{
    $v = settings_get(internal_api_setting_key($key));
    if ($v === null) return $default;
    return (string)$v;
}

// ---------------- OTP/JWT settings ----------------

function api_jwt_access_ttl_sec(): int
{
    return max(60, internal_api_int('jwt.access_ttl_sec', (int)env('JWT_ACCESS_TTL_SEC', '900')));
}

function api_jwt_refresh_ttl_days(): int
{
    return max(1, internal_api_int('jwt.refresh_ttl_days', (int)env('JWT_REFRESH_TTL_DAYS', '90')));
}

function api_otp_ttl_sec(): int
{
    return max(60, internal_api_int('otp.ttl_sec', (int)env('OTP_TTL_SEC', '300')));
}

function api_otp_cooldown_sec(): int
{
    return max(0, internal_api_int('otp.cooldown_sec', (int)env('OTP_COOLDOWN_SEC', '60')));
}

function api_otp_length(): int
{
    $len = internal_api_int('otp.length', (int)env('OTP_LENGTH', '6'));
    if ($len < 4) $len = 4;
    if ($len > 8) $len = 8;
    return $len;
}

function api_otp_max_attempts(): int
{
    return max(1, internal_api_int('otp.max_attempts', (int)env('OTP_MAX_ATTEMPTS', '5')));
}

function api_otp_max_per_phone_hour(): int
{
    return max(1, internal_api_int('otp.max_per_phone_hour', (int)env('OTP_MAX_PER_PHONE_HOUR', '10')));
}

function api_otp_max_per_phone_day(): int
{
    return max(1, internal_api_int('otp.max_per_phone_day', (int)env('OTP_MAX_PER_PHONE_DAY', '30')));
}

function api_otp_max_per_ip_hour(): int
{
    return max(1, internal_api_int('otp.max_per_ip_hour', (int)env('OTP_MAX_PER_IP_HOUR', '60')));
}

// ------------- Endpoint enable/maintenance guards -------------

function api_endpoint_enabled(string $endpointKey): bool
{
    $catalog = internal_api_endpoints_catalog();
    $default = isset($catalog[$endpointKey]) ? (bool)$catalog[$endpointKey][2] : true;
    return internal_api_bool('endpoint.enabled.' . $endpointKey, $default);
}

function api_guard_endpoint(string $endpointKey): void
{
    if (!api_endpoint_enabled($endpointKey)) {
        json_out(['ok' => false, 'message' => 'این API موقتاً غیرفعال است', 'code' => 'endpoint_disabled'], 403);
    }
}

function api_guard_global(string $endpointKey): void
{
    $enabled = internal_api_bool('maintenance.enabled', false);
    if (!$enabled) return;

    $catalog = internal_api_endpoints_catalog();
    $allow = isset($catalog[$endpointKey]) ? (bool)$catalog[$endpointKey][3] : false;
    if ($allow) return;

    $msg = internal_api_str('maintenance.message', 'سرویس در حال بروزرسانی است. لطفاً بعداً تلاش کنید.');
    json_out(['ok' => false, 'message' => $msg, 'code' => 'maintenance'], 503);
}
