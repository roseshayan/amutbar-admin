<?php
declare(strict_types=1);

function api_user_with_profile(array $u): array
{
    $pdo = db();
    $publicUser = api_public_user_payload($u);
    if ($publicUser) $u = $publicUser;

    require_once __DIR__ . '/settings.php';

    $mobileRole = in_array((int)$u['user_type'], [1, 2], true);
    $requireVideo = $mobileRole && verification_policy((int)$u['user_type'])['require_video'];

    $driver = null;
    $company = null;

    if ((int)$u['user_type'] === 1) {
        // فیلدهای ماشین (vin, chassis, engine, insurance) اضافه شد
        $st = $pdo->prepare("SELECT id, full_name, national_code, vehicle_type_id, plate_number, smart_card_number, model_year, color, capacity_kg, province_id, city_id, verification_status, reject_reason, rating_avg, rating_count, vin_number, insurance_number, insurance_expiry, engine_number, chassis_number, created_at, updated_at FROM drivers WHERE user_id=? AND deleted_at IS NULL LIMIT 1");
        $st->execute([(int)$u['id']]);
        $driver = $st->fetch() ?: null;
    }

    if ((int)$u['user_type'] === 2) {
        $st = $pdo->prepare("SELECT id, company_name, owner_full_name, owner_national_code, registration_no, registration_date, economic_code, province_id, city_id, address, postal_code, verification_status, reject_reason, created_at, updated_at FROM companies WHERE user_id=? AND deleted_at IS NULL LIMIT 1");
        $st->execute([(int)$u['id']]);
        $company = $st->fetch() ?: null;
        if ($company !== null) {
            $company['entity_type'] = trim((string)($company['registration_no'] ?? '')) !== '' ? 2 : 1;
        }
    }

    // onboarding flags
    $needsVehicleInfo = false;
    if ($driver !== null) {
        $needsVehicleInfo = ((int)($driver['vehicle_type_id'] ?? 0) <= 0) || (trim((string)($driver['plate_number'] ?? '')) === '');
    }

    $needsVideo = $mobileRole && verification_needs_video((int)$u['id'], (int)$u['user_type']);

    return [
        'user' => $publicUser ?: $u,
        'driver' => $driver,
        'company' => $company,
        'onboarding' => [
            // برای صاحب بار، اطلاعات مکانی بخشی اختیاری از پروفایل است و
            // نباید مسیر ثبت‌نام را متوقف کند.
            'profile_completed' => $driver !== null || $company !== null,
            'identity_verified' => $driver !== null
                ? ((int)($driver['verification_status'] ?? 0) === 1)
                : ($company !== null && (int)($company['verification_status'] ?? 0) === 1),
            'verification_status' => $driver['verification_status'] ?? ($company['verification_status'] ?? null),
            'needs_vehicle_info' => $needsVehicleInfo,
            'require_verification_video' => $requireVideo,
            'needs_verification_video' => $needsVideo,
        ],
    ];
}

