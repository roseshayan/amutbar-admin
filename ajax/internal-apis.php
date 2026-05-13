<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_admin();
require_post();
require_once __DIR__ . '/../includes/internal_api.php';

$raw = file_get_contents('php://input');
$in = json_decode($raw ?: '[]', true);
if (!is_array($in)) json_out(['ok' => false, 'message' => 'payload نامعتبر'], 400);

$catalog = internal_api_endpoints_catalog();

// اعتبارسنجی ساده
$items = [];
$items[internal_api_setting_key('maintenance.enabled')] = ((int)($in['maintenance_enabled'] ?? 0) === 1) ? '1' : '0';
$items[internal_api_setting_key('maintenance.message')] = trim((string)($in['maintenance_message'] ?? ''));

$items[internal_api_setting_key('jwt.access_ttl_sec')] = (string)max(60, min(86400, (int)($in['jwt_access_ttl_sec'] ?? api_jwt_access_ttl_sec())));
$items[internal_api_setting_key('jwt.refresh_ttl_days')] = (string)max(1, min(365, (int)($in['jwt_refresh_ttl_days'] ?? api_jwt_refresh_ttl_days())));

$items[internal_api_setting_key('otp.ttl_sec')] = (string)max(60, min(1800, (int)($in['otp_ttl_sec'] ?? api_otp_ttl_sec())));
$items[internal_api_setting_key('otp.cooldown_sec')] = (string)max(0, min(600, (int)($in['otp_cooldown_sec'] ?? api_otp_cooldown_sec())));
$items[internal_api_setting_key('otp.length')] = (string)max(4, min(8, (int)($in['otp_length'] ?? api_otp_length())));
$items[internal_api_setting_key('otp.max_attempts')] = (string)max(1, min(10, (int)($in['otp_max_attempts'] ?? api_otp_max_attempts())));
$items[internal_api_setting_key('otp.max_per_phone_hour')] = (string)max(1, min(100, (int)($in['otp_max_per_phone_hour'] ?? api_otp_max_per_phone_hour())));
$items[internal_api_setting_key('otp.max_per_phone_day')] = (string)max(1, min(500, (int)($in['otp_max_per_phone_day'] ?? api_otp_max_per_phone_day())));
$items[internal_api_setting_key('otp.max_per_ip_hour')] = (string)max(1, min(500, (int)($in['otp_max_per_ip_hour'] ?? api_otp_max_per_ip_hour())));

$endpoints = $in['endpoints'] ?? [];
if (!is_array($endpoints)) $endpoints = [];
foreach ($catalog as $key => $info) {
    $v = isset($endpoints[$key]) ? (int)$endpoints[$key] : null;
    if ($v === null) continue;
    $items[internal_api_setting_key('endpoint.enabled.' . $key)] = ($v === 1) ? '1' : '0';
}

try {
    settings_set_many($items, (int)($_SESSION['admin_user_id'] ?? 1));
    audit_log("internal_api.update", 'internal_api_settings_update', count($items));
    json_out(['ok' => true]);
} catch (Throwable $e) {
    json_out(['ok' => false, 'message' => 'خطا در ذخیره تنظیمات'], 500);
}
