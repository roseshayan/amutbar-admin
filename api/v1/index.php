<?php

declare(strict_types=1);

require_once __DIR__ . '/_bootstrap.php';

// -----------------------------
// Minimal API Router
// -----------------------------

function api_path(): string
{
    $uri = (string)($_SERVER['REQUEST_URI'] ?? '/');
    $uri = explode('?', $uri, 2)[0];

    // base: /api/v1 (or /subdir/api/v1)
    $scriptDir = str_replace('\\', '/', dirname((string)($_SERVER['SCRIPT_NAME'] ?? '/api/v1/index.php')));
    $base = rtrim($scriptDir, '/');
    if ($base !== '' && str_starts_with($uri, $base)) {
        $uri = substr($uri, strlen($base));
    }

    return '/' . trim($uri, '/');
}

function api_user_with_profile(array $u): array
{
    $pdo = db();

    require_once __DIR__ . '/../../includes/settings.php';

    $requireVideo = (string)settings_get('onboarding.require_verification_video', '0') === '1';

    $driver = null;
    $company = null;

    if ((int)$u['user_type'] === 1) {
        $st = $pdo->prepare("SELECT id, full_name, national_code, vehicle_type_id, plate_number, model_year, color, capacity_kg, province_id, city_id, verification_status, reject_reason, rating_avg, rating_count, created_at, updated_at FROM drivers WHERE user_id=? AND deleted_at IS NULL LIMIT 1");
        $st->execute([(int)$u['id']]);
        $driver = $st->fetch() ?: null;
    }

    if ((int)$u['user_type'] === 2) {
        $st = $pdo->prepare("SELECT id, company_name, owner_full_name, owner_national_code, registration_no, economic_code, province_id, city_id, address, verification_status, reject_reason, created_at, updated_at FROM companies WHERE user_id=? AND deleted_at IS NULL LIMIT 1");
        $st->execute([(int)$u['id']]);
        $company = $st->fetch() ?: null;
    }

    // onboarding flags
    $needsVehicleInfo = false;
    if ($driver !== null) {
        $needsVehicleInfo = ((int)($driver['vehicle_type_id'] ?? 0) <= 0) || (trim((string)($driver['plate_number'] ?? '')) === '');
    }

    $videoVerified = false;
    if ($requireVideo && (int)($u['user_type'] ?? 0) === 1) {
        // آخرین نتیجه VideoVerify (یا VideoMatch در صورت نبود) را بررسی می‌کنیم
        $st = $pdo->prepare("SELECT response_redacted_json, status FROM identity_verification_jobs WHERE subject_user_id=? AND check_type IN ('VideoVerify','VideoMatch') ORDER BY id DESC LIMIT 1");
        $st->execute([(int)$u['id']]);
        $jr = $st->fetch();
        if ($jr && (int)($jr['status'] ?? 0) === 2) {
            $resp = json_decode((string)($jr['response_redacted_json'] ?? ''), true);
            if (is_array($resp) && !empty($resp['success']) && isset($resp['data']) && is_array($resp['data'])) {
                $d = $resp['data'];
                // برای VideoVerify باید هر سه مورد true باشند؛ برای VideoMatch حداقل isMatch
                $isMatch = (bool)($d['isMatch'] ?? false);
                $isLive = array_key_exists('isLiveness', $d) ? (bool)$d['isLiveness'] : true;
                $isSpeech = array_key_exists('isSpeechMatched', $d) ? (bool)$d['isSpeechMatched'] : true;
                if ($isMatch && $isLive && $isSpeech) {
                    $videoVerified = true;
                }
            }
        }
    }

    $needsVideo = false;
    if ($requireVideo && $driver !== null) {
        $needsVideo = !$videoVerified;
    }

    return [
        'user' => $u,
        'driver' => $driver,
        'company' => $company,
        'onboarding' => [
            'profile_completed' => ($driver !== null || $company !== null),
            'verification_status' => $driver['verification_status'] ?? ($company['verification_status'] ?? null),
            'needs_vehicle_info' => $needsVehicleInfo,
            'require_verification_video' => $requireVideo,
            'needs_verification_video' => $needsVideo,
        ],
    ];
}

$method = api_method();
$path = api_path();

// -----------------------------
// Endpoint key (برای کنترل از پنل)
// -----------------------------
$endpointKey = 'api.unknown';
if ($path === '/meta/app-config') $endpointKey = 'api.meta.app_config';
if ($path === '/meta/vehicle-types') $endpointKey = 'api.meta.vehicle_types';
if ($path === '/auth/request-otp') $endpointKey = 'api.auth.request_otp';
if ($path === '/auth/verify-otp') $endpointKey = 'api.auth.verify_otp';
if ($path === '/auth/verify-identity') $endpointKey = 'api.auth.verify_identity';
if ($path === '/auth/refresh') $endpointKey = 'api.auth.refresh';
if ($path === '/auth/logout') $endpointKey = 'api.auth.logout';
if ($path === '/me') $endpointKey = 'api.me.get';
if ($path === '/me/avatar') $endpointKey = 'api.me.avatar';
if ($path === '/driver/profile') $endpointKey = 'api.driver.upsert';
if ($path === '/company/profile') $endpointKey = 'api.company.upsert';
if ($path === '/driver/verification-video') $endpointKey = 'api.driver.verification_video';

// Maintenance + enable/disable
api_guard_global($endpointKey);
if ($endpointKey !== 'api.unknown') api_guard_endpoint($endpointKey);

// Health
if ($method === 'GET' && $path === '/health') {
    api_ok(['service' => 'amutbar-api', 'version' => 'v1']);
}

// Meta
if ($method === 'GET' && $path === '/meta/app-config') {
    require_once __DIR__ . '/../../includes/settings.php';

    $keys = [
        'company.name',
        'site.name',
        'site.url',
        'site.logo_path',
        'site.favicon_path',
        'links.terms_url',
        'links.app_download_url',
        'support.phone',
        'support.whatsapp',
        'support.telegram',
        'app.android.latest_version_code',
        'app.android.min_supported_code',
        'app.android.update_url',
        'links.terms_text',
        'app.ios.latest_version_code',
        'app.ios.min_supported_code',
        'app.ios.update_url',
        'maintenance.enabled',
        'maintenance.message',

        // onboarding / verification
        'auth.require_national_serial',
        'onboarding.require_verification_video',
        'verification.video_phrase_template',
        'verification.video_guide_text',
        'verification.video_guide_url',
        'verification.video_max_seconds',
        'verification.video_max_mb',
        'verification.api_ir.liveness_threshold',
        'verification.api_ir.matching_threshold',
        'verification.api_ir.speech_threshold',
    ];

    $it = settings_get_many($keys);
    foreach ($keys as $k) {
        if (!array_key_exists($k, $it)) $it[$k] = null;
    }

    $base = trim((string)($it['site.url'] ?? ''));
    $base = rtrim($base, '/');

    $abs = function (?string $p) use ($base): ?string {
        $p = trim((string)$p);
        if ($p === '') return null;
        if (preg_match('~^https?://~i', $p)) return $p;
        if ($base === '') return $p;
        return $base . '/' . ltrim($p, '/');
    };

    api_ok([
        'company_name' => $it['company.name'] !== null ? (string)$it['company.name'] : null,
        'site_name' => $it['site.name'] !== null ? (string)$it['site.name'] : null,
        'site_url' => $it['site.url'] !== null ? (string)$it['site.url'] : null,
        'logo_url' => $abs($it['site.logo_path'] ?? null),
        'favicon_url' => $abs($it['site.favicon_path'] ?? null),
        'links' => [
            'terms_url' => $it['links.terms_url'] !== null ? (string)$it['links.terms_url'] : null,
            'terms_text' => $it['links.terms_text'] !== null ? (string)$it['links.terms_text'] : null,
            'app_download_url' => $it['links.app_download_url'] !== null ? (string)$it['links.app_download_url'] : null,
        ],
        'support' => [
            'phone' => $it['support.phone'] !== null ? (string)$it['support.phone'] : null,
            'whatsapp' => $it['support.whatsapp'] !== null ? (string)$it['support.whatsapp'] : null,
            'telegram' => $it['support.telegram'] !== null ? (string)$it['support.telegram'] : null,
        ],
        'app' => [
            'android' => [
                'latest_version_code' => $it['app.android.latest_version_code'] !== null ? (int)$it['app.android.latest_version_code'] : null,
                'min_supported_code' => $it['app.android.min_supported_code'] !== null ? (int)$it['app.android.min_supported_code'] : null,
                'update_url' => $it['app.android.update_url'] !== null ? (string)$it['app.android.update_url'] : null,
            ],
            'ios' => [
                'latest_version_code' => $it['app.ios.latest_version_code'] !== null ? (int)$it['app.ios.latest_version_code'] : null,
                'min_supported_code' => $it['app.ios.min_supported_code'] !== null ? (int)$it['app.ios.min_supported_code'] : null,
                'update_url' => $it['app.ios.update_url'] !== null ? (string)$it['app.ios.update_url'] : null,
            ],
        ],
        'maintenance' => [
            'enabled' => (string)($it['maintenance.enabled'] ?? '0') === '1',
            'message' => $it['maintenance.message'] !== null ? (string)$it['maintenance.message'] : null,
        ],

        'onboarding' => [
            'require_verification_video' => (string)($it['onboarding.require_verification_video'] ?? '0') === '1',
        ],

        'auth' => [
            'require_national_serial' => ($it['auth.require_national_serial'] === '1')
        ],

        'verification' => [
            'video_phrase_template' => $it['verification.video_phrase_template'] !== null ? (string)$it['verification.video_phrase_template'] : null,
            'video_guide_text' => $it['verification.video_guide_text'] !== null ? (string)$it['verification.video_guide_text'] : null,
            'video_guide_url' => $it['verification.video_guide_url'] !== null ? (string)$it['verification.video_guide_url'] : null,
            'video_max_seconds' => $it['verification.video_max_seconds'] !== null ? (int)$it['verification.video_max_seconds'] : 10,
            'video_max_mb' => $it['verification.video_max_mb'] !== null ? (int)$it['verification.video_max_mb'] : 5,
            'api_ir' => [
                'liveness_threshold' => $it['verification.api_ir.liveness_threshold'] !== null ? (int)$it['verification.api_ir.liveness_threshold'] : 80,
                'matching_threshold' => $it['verification.api_ir.matching_threshold'] !== null ? (int)$it['verification.api_ir.matching_threshold'] : 90,
                'speech_threshold' => $it['verification.api_ir.speech_threshold'] !== null ? (int)$it['verification.api_ir.speech_threshold'] : 50,
            ],
        ],
    ]);
}

if ($method === 'GET' && $path === '/meta/provinces') {
    $pdo = db();
    $rows = $pdo->query("SELECT id, name FROM provinces ORDER BY name ASC")->fetchAll();
    api_ok(['items' => array_map(fn($r) => ['id' => (int)$r['id'], 'name' => (string)$r['name']], $rows)]);
}

if ($method === 'GET' && $path === '/meta/cities') {
    $pdo = db();
    $provinceId = isset($_GET['province_id']) ? (int)$_GET['province_id'] : null;
    if (!$provinceId) api_err('province_id الزامی است', 422);
    $st = $pdo->prepare("SELECT id, province_id, name, lat, lng FROM cities WHERE province_id=? ORDER BY name ASC");
    $st->execute([$provinceId]);
    $rows = $st->fetchAll();
    api_ok(['items' => array_map(fn($r) => [
        'id' => (int)$r['id'],
        'province_id' => (int)$r['province_id'],
        'name' => (string)$r['name'],
        'lat' => $r['lat'] !== null ? (float)$r['lat'] : null,
        'lng' => $r['lng'] !== null ? (float)$r['lng'] : null,
    ], $rows)]);
}

if ($method === 'GET' && $path === '/meta/vehicle-types') {
    $pdo = db();
    $rows = $pdo->query("SELECT id, parent_id, code, title, body_type, max_weight_kg, description FROM vehicle_types WHERE is_active=1 ORDER BY parent_id ASC, id ASC")->fetchAll();
    api_ok(['items' => array_map(fn($r) => [
        'id' => (int)$r['id'],
        'parent_id' => $r['parent_id'] !== null ? (int)$r['parent_id'] : null,
        'code' => (string)$r['code'],
        'title' => (string)$r['title'],
        'body_type' => $r['body_type'] !== null ? (string)$r['body_type'] : null,
        'max_weight_kg' => $r['max_weight_kg'] !== null ? (float)$r['max_weight_kg'] : null,
        'description' => $r['description'] !== null ? (string)$r['description'] : null,
    ], $rows)]);
}

if ($method === 'GET' && $path === '/meta/cargo-types') {
    $pdo = db();
    $rows = $pdo->query("SELECT id, title, description FROM cargo_types WHERE is_active=1 ORDER BY title ASC")->fetchAll();
    api_ok(['items' => array_map(fn($r) => [
        'id' => (int)$r['id'],
        'title' => (string)$r['title'],
        'description' => $r['description'] !== null ? (string)$r['description'] : null,
    ], $rows)]);
}

// Auth: request OTP
if ($method === 'POST' && $path === '/auth/request-otp') {
    $in = api_input();
    $phone = api_require_phone((string)($in['phone'] ?? ''));
    // 1=driver, 2=company (برای اپ رانندگان معمولاً 1 ارسال می‌شود)
    // نکته امنیتی: در این مرحله وضعیت/وجود کاربر را افشا نمی‌کنیم.
    $userType = (int)($in['user_type'] ?? 1);
    if (!in_array($userType, [1, 2], true)) api_err('user_type نامعتبر است', 422);

    $res = otp_issue($phone, 1, api_otp_ttl_sec(), api_otp_cooldown_sec());
    if (!$res['ok']) api_err($res['message'], (int)($res['status'] ?? 400), $res);
    api_ok(['message' => 'کد تایید ارسال شد'] + $res);
}

// Auth: verify OTP + issue token
if ($method === 'POST' && $path === '/auth/verify-otp') {
    $in = api_input();
    $phone = api_require_phone((string)($in['phone'] ?? ''));
    $code = preg_replace('/\D+/', '', (string)($in['code'] ?? ''));
    $otpLen = api_otp_length();
    if (!preg_match('/^\d{' . $otpLen . '}$/', $code)) api_err('کد تایید نامعتبر است', 422);
    $userType = (int)($in['user_type'] ?? 1);
    if (!in_array($userType, [1, 2], true)) api_err('user_type نامعتبر است', 422);

    $otpId = isset($in['otp_id']) ? (int)$in['otp_id'] : null;

    $check = otp_consume($phone, 1, $code, api_otp_max_attempts(), $otpId);
    if (!$check['ok']) api_err($check['message'], (int)$check['status']);

    $pdo = db();
    $pdo->beginTransaction();
    try {
        $st = $pdo->prepare("SELECT id, user_type, status, full_name, phone, email FROM users WHERE phone_active=? LIMIT 1");
        $st->execute([$phone]);
        $u = $st->fetch();

        if ($u) {
            if ((int)$u['user_type'] !== $userType) {
                $pdo->rollBack();
                api_err('این شماره برای نقش دیگری ثبت شده است', 409);
            }
            if ((int)$u['status'] === 4) {
                $pdo->rollBack();
                api_err('حساب کاربری مسدود است', 403);
            }
            if ((int)$u['status'] === 2) {
                $pdo->rollBack();
                api_err('حساب کاربری غیرفعال است', 403);
            }

            // If pending, activate on successful OTP.
            if ((int)$u['status'] === 3) {
                $pdo->prepare("UPDATE users SET status=1, updated_at=NOW(3) WHERE id=?")
                    ->execute([(int)$u['id']]);
            }

            $userId = (int)$u['id'];
        } else {
            // Minimal placeholder; profile endpoints will complete.
            $pdo->prepare("INSERT INTO users (full_name, phone, user_type, status, created_at, updated_at) VALUES (?, ?, ?, 1, NOW(3), NOW(3))")
                ->execute(['کاربر', $phone, $userType]);
            $userId = (int)$pdo->lastInsertId();
        }

        $pdo->commit();
    } catch (Throwable $e) {
        $pdo->rollBack();
        $msg = ((string)env('APP_DEBUG', '0') === '1') ? $e->getMessage() : 'خطای سرور';
        api_err($msg, 500);
    }

    $deviceId = api_str($in, 'device_id', 64);
    $platform = api_platform_from_string(api_str($in, 'platform', 16));
    // صدور JWT access/refresh (رفرش پیش‌فرض: 90 روز)
    $token = api_issue_token($userId, $deviceId, $platform, 90);

    // auth logging
    auth_mark_last_login((int)$userId);
    auth_log_event((int)$userId, (int)$userType, 4, ['platform' => $platform, 'device_id' => $deviceId]);

    // Load user for response
    $st = db()->prepare("SELECT id, full_name, phone, email, user_type, status, display_name, avatar_key FROM users WHERE id=? LIMIT 1");
    $st->execute([$userId]);
    $user = $st->fetch();

    $profile = api_user_with_profile([
        'id' => (int)$user['id'],
        'full_name' => (string)$user['full_name'],
        'display_name' => $user['display_name'] !== null ? (string)$user['display_name'] : null,
        'avatar_key' => $user['avatar_key'] !== null ? (string)$user['avatar_key'] : null,
        'phone' => (string)$user['phone'],
        'email' => $user['email'] !== null ? (string)$user['email'] : null,
        'user_type' => (int)$user['user_type'],
        'status' => (int)$user['status'],
    ]);

    // مرحله/مسیر پیشنهادی برای اپلیکیشن
    // (برای جلوگیری از ابهام، مبنا را «کامل بودن پروفایل» گذاشتیم)
    $next = ($profile['onboarding']['profile_completed'] ?? false) ? 'dashboard' : 'onboarding';
    api_ok([
        'auth' => $token,
        'profile' => $profile,
        'flow' => [
            'user_type' => $userType,
            'next' => $next,
            'profile_completed' => (bool)($profile['onboarding']['profile_completed'] ?? false),
            'verification_status' => $profile['onboarding']['verification_status'] ?? null,
        ],
    ]);
}

// Auth: verify identity (External API - api.ir)
if ($method === 'POST' && $path === '/auth/verify-identity') {
    $u = api_require_auth();
    if ((int)$u['user_type'] !== 1) api_err('Forbidden', 403);

    $in = api_input();
    $fullName = api_str($in, 'full_name', 120);
    $nationalCode = api_str($in, 'national_code', 16);
    $birthDate = api_str($in, 'birth_date', 32); // مثال: 1370/01/01
    $cardSerial = api_str($in, 'card_serial', 64);

    if (!$fullName) api_err('full_name الزامی است', 422);
    if (!$nationalCode) api_err('national_code الزامی است', 422);
    if (!$birthDate) api_err('birth_date الزامی است', 422);

    // --- اصلاح بخش اعتبارسنجی داینامیک بر اساس تنظیمات ادمین ---
    require_once __DIR__ . '/../../includes/settings.php';
    $requireSerial = (settings_get('auth.require_national_serial') === '1');

    // اگر سریال از پنل ادمین الزامی بود و فرستاده نشده بود، خطا بدهد
    if ($requireSerial && !$cardSerial) {
        api_err('card_serial الزامی است', 422);
    }
    // --------------------------------------------------------

    require_once __DIR__ . '/../../includes/ExternalApiHelper.php';
    $pdo = db();
    $apiHelper = new ExternalApiHelper($pdo);
    $providerSlug = 'api_ir';

    // 1) شاهکار لایت: تطبیق موبایل و کد ملی (همیشه اجرا می‌شود)
    $shahkarBody = [
        'mobile' => (string)$u['phone'],
        'nationalCode' => $nationalCode,
    ];
    try {
        $shahkarRes = $apiHelper->callExternalApi($providerSlug, '/api/sw1/ShahkarLite', 'POST', $shahkarBody);
    } catch (Throwable $e) {
        api_err('خطا در ارتباط با سرویس شاهکار: ' . $e->getMessage(), 502);
    }
    if (empty($shahkarRes['success']) || $shahkarRes['success'] !== true) {
        $msg = $shahkarRes['message'] ?? 'خطای ناشناخته در سرویس شاهکار';
        api_err('استعلام شاهکار ناموفق: ' . (string)$msg, 400);
    }
    if (($shahkarRes['data'] ?? false) !== true) {
        api_err('کد ملی وارد شده متعلق به این شماره موبایل نیست.', 422);
    }

    // 2) استعلام عکس (فقط و فقط اگر سریال کارت ملی در تنظیمات فعال باشد)
    $avatarKey = null;
    if ($requireSerial) {
        $photoBody = [
            'birthDate' => $birthDate,
            'nationalCode' => $nationalCode,
            'serialNumber' => $cardSerial,
        ];
        try {
            $photoRes = $apiHelper->callExternalApi($providerSlug, '/api/sw1/PersonImage', 'POST', $photoBody);
        } catch (Throwable $e) {
            api_err('خطا در ارتباط با سرویس عکس: ' . $e->getMessage(), 502);
        }
        if (empty($photoRes['success']) || $photoRes['success'] !== true) {
            $msg = $photoRes['message'] ?? 'اطلاعات هویتی صحیح نیست.';
            api_err('استعلام عکس تایید نشد: ' . (string)$msg, 400);
        }

        // ذخیره عکس به عنوان avatar_key (اگر imageBase64 موجود باشد)
        $imageBase64 = $photoRes['data']['imageBase64'] ?? null;
        if (is_string($imageBase64) && $imageBase64 !== '') {
            $bin = base64_decode($imageBase64, true);
            if ($bin !== false && strlen($bin) > 0) {
                $dir = __DIR__ . '/../../storage/uploads/avatars';
                if (!is_dir($dir)) {
                    @mkdir($dir, 0775, true);
                }
                $filename = 'avatar_' . (int)$u['id'] . '_' . time() . '.jpg';
                $abs = $dir . '/' . $filename;
                if (@file_put_contents($abs, $bin) !== false) {
                    $avatarKey = 'storage/uploads/avatars/' . $filename;
                    $pdo->prepare("UPDATE users SET avatar_key=?, updated_at=NOW(3) WHERE id=? LIMIT 1")
                        ->execute([$avatarKey, (int)$u['id']]);
                }
            }
        }
    }

    // 3) ثبت اطلاعات نهایی در دیتابیس
    $pdo->beginTransaction();
    try {
        $st = $pdo->prepare("SELECT id FROM drivers WHERE user_id=? AND deleted_at IS NULL LIMIT 1");
        $st->execute([(int)$u['id']]);
        $driverId = (int)($st->fetchColumn() ?: 0);
        if ($driverId > 0) {
            $pdo->prepare("UPDATE drivers SET full_name=?, national_code=?, verification_status=1, verified_at=NOW(3), reject_reason=NULL, updated_at=NOW(3) WHERE id=? LIMIT 1")
                ->execute([$fullName, $nationalCode, $driverId]);
        } else {
            $pdo->prepare("INSERT INTO drivers (user_id, full_name, national_code, verification_status, verified_at, created_at, updated_at) VALUES (?, ?, ?, 1, NOW(3), NOW(3), NOW(3))")
                ->execute([(int)$u['id'], $fullName, $nationalCode]);
        }

        // در جدول users هم نام و فیلدها را همسان می‌کنیم
        $pdo->prepare("UPDATE users SET full_name=?, code_meli=?, birth_date=?, national_card_serial=?, updated_at=NOW(3) WHERE id=? LIMIT 1")
            ->execute([$fullName, $nationalCode, $birthDate, $cardSerial, (int)$u['id']]);

        $pdo->commit();
    } catch (Throwable $e) {
        $pdo->rollBack();
        api_err('خطا در ذخیره‌سازی اطلاعات: ' . $e->getMessage(), 500);
    }

    api_ok([
        'message' => 'احراز هویت با موفقیت انجام شد.',
        'avatar_key' => $avatarKey,
        'me' => api_user_with_profile(api_require_auth()),
    ]);
}

// Auth: refresh (rotate refresh token)
if ($method === 'POST' && $path === '/auth/refresh') {
    $in = api_input();
    $refreshToken = (string)($in['refresh_token'] ?? '');
    if ($refreshToken === '') api_err('refresh_token الزامی است', 422);
    $deviceId = api_str($in, 'device_id', 64);

    $res = api_refresh_rotate($refreshToken, $deviceId);
    if (!$res['ok']) api_err($res['message'], (int)($res['status'] ?? 401));

    api_ok(['auth' => [
        'token_type' => $res['token_type'],
        'access_token' => $res['access_token'],
        'refresh_token' => $res['refresh_token'],
        'access_expires_in' => $res['access_expires_in'],
        'refresh_expires_in' => $res['refresh_expires_in'],
    ]]);
}

// Auth: logout
if ($method === 'POST' && $path === '/auth/logout') {
    api_require_method(['POST']);
    // دو حالت:
    // 1) logout دستگاه فعلی: refresh_token ارسال می‌شود و revoke می‌گردد.
    // 2) logout همه دستگاه‌ها: all_devices=1 و نیازمند Authorization است.
    $in = api_input();

    $all = (string)($in['all_devices'] ?? '0') === '1';
    if ($all) {
        $u = api_require_auth();
        api_revoke_all_user_tokens((int)$u['id']);
        auth_log_event((int)$u['id'], (int)$u['user_type'], 2);
        api_ok(['message' => 'خروج از همه دستگاه‌ها انجام شد']);
    }

    $refreshToken = (string)($in['refresh_token'] ?? '');
    if ($refreshToken === '') {
        api_ok(['message' => 'خروج انجام شد']);
    }
    $dec = jwt_decode($refreshToken);
    if ($dec['ok']) {
        $uid = (int)($dec['payload']['sub'] ?? 0);
        $ut = (int)($dec['payload']['ut'] ?? 0);
        if ($uid > 0 && $ut > 0) auth_log_event($uid, $ut, 2);
    }

    api_revoke_refresh($refreshToken);
    api_ok(['message' => 'خروج انجام شد']);
}

// Me: upload avatar
if ($method === 'POST' && $path === '/me/avatar') {
    $u = api_require_auth();

    if (empty($_FILES['avatar']) || !is_array($_FILES['avatar'])) {
        api_err('avatar الزامی است', 422);
    }

    $res = user_update_avatar((int)$u['id'], $_FILES['avatar']);
    if (!$res['ok']) api_err($res['message'], (int)($res['status'] ?? 400));

    api_ok(['avatar_key' => $res['avatar_key']]);
}

// Me
if ($method === 'GET' && $path === '/me') {
    $u = api_require_auth();
    api_ok(api_user_with_profile($u));
}

// Driver: profile upsert
if ($method === 'POST' && $path === '/driver/profile') {
    $u = api_require_auth();
    if ((int)$u['user_type'] !== 1) api_err('Forbidden', 403);

    $in = api_input();
    $pdo = db();

    $fullName = api_str($in, 'full_name', 120);
    $nationalCode = api_str($in, 'national_code', 16);
    $vehicleTypeId = api_int($in, 'vehicle_type_id');
    $plate = api_str($in, 'plate_number', 24);

    if (!$fullName) api_err('full_name الزامی است', 422);
    if (!$nationalCode) api_err('national_code الزامی است', 422);
    if (!$vehicleTypeId || $vehicleTypeId <= 0) api_err('vehicle_type_id الزامی است', 422);
    if (!$plate) api_err('plate_number الزامی است', 422);

    $smart = api_str($in, 'smart_card_number', 32);
    $modelYear = api_int($in, 'model_year');
    $color = api_str($in, 'color', 32);
    $capacityKg = api_decimal($in, 'capacity_kg');
    $provinceId = api_int($in, 'province_id');
    $cityId = api_int($in, 'city_id');

    $pdo->beginTransaction();
    try {
        // Unique plate across active drivers
        $st = $pdo->prepare("SELECT id FROM drivers WHERE plate_active=? AND deleted_at IS NULL AND user_id<>? LIMIT 1");
        $st->execute([$plate, (int)$u['id']]);
        if ($st->fetchColumn()) {
            $pdo->rollBack();
            api_err('این پلاک قبلاً ثبت شده است', 409);
        }

        $st = $pdo->prepare("SELECT id FROM drivers WHERE user_id=? AND deleted_at IS NULL LIMIT 1");
        $st->execute([(int)$u['id']]);
        $driverId = (int)($st->fetchColumn() ?: 0);

        if ($driverId > 0) {
            $up = $pdo->prepare("UPDATE drivers SET full_name=?, national_code=?, smart_card_number=?, vehicle_type_id=?, plate_number=?, model_year=?, color=?, capacity_kg=?, province_id=?, city_id=?, updated_at=NOW(3) WHERE id=? LIMIT 1");
            $up->execute([$fullName, $nationalCode, $smart, $vehicleTypeId, $plate, $modelYear, $color, $capacityKg, $provinceId, $cityId, $driverId]);
        } else {
            $ins = $pdo->prepare("INSERT INTO drivers (user_id, full_name, national_code, smart_card_number, vehicle_type_id, plate_number, model_year, color, capacity_kg, province_id, city_id, verification_status, created_at, updated_at)
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, NOW(3), NOW(3))");
            $ins->execute([(int)$u['id'], $fullName, $nationalCode, $smart, $vehicleTypeId, $plate, $modelYear, $color, $capacityKg, $provinceId, $cityId]);
            $driverId = (int)$pdo->lastInsertId();
        }

        // Keep users.full_name synced
        $pdo->prepare("UPDATE users SET full_name=?, updated_at=NOW(3) WHERE id=?")
            ->execute([$fullName, (int)$u['id']]);

        $pdo->commit();
    } catch (Throwable $e) {
        $pdo->rollBack();
        $msg = ((string)env('APP_DEBUG', '0') === '1') ? $e->getMessage() : 'خطا در ذخیره‌سازی';
        api_err($msg, 500);
    }

    $st = db()->prepare("SELECT id, full_name, national_code, smart_card_number, vehicle_type_id, plate_number, model_year, color, capacity_kg, province_id, city_id, verification_status, reject_reason, rating_avg, rating_count, created_at, updated_at FROM drivers WHERE id=? LIMIT 1");
    $st->execute([$driverId]);
    api_ok(['driver' => ($st->fetch() ?: null)]);
}

// Driver: upload docs (license + vehicle card)
if ($method === 'POST' && $path === '/driver/docs') {
    $u = api_require_auth();
    if ((int)$u['user_type'] !== 1) api_err('Forbidden', 403);

    $hasAny = false;

    if (!empty($_FILES['license_image']) && is_array($_FILES['license_image'])) {
        $hasAny = true;
        save_user_file((int)$u['id'], 3, $_FILES['license_image']);
    }
    if (!empty($_FILES['vehicle_card_image']) && is_array($_FILES['vehicle_card_image'])) {
        $hasAny = true;
        save_user_file((int)$u['id'], 4, $_FILES['vehicle_card_image']);
    }

    if (!$hasAny) api_err('هیچ فایلی ارسال نشده است', 422);

    api_ok(['uploaded' => true]);
}

// Driver: upload verification video + call api.ir VideoVerify
// Driver: upload verification video + call api.ir VideoVerify
if ($method === 'POST' && $path === '/driver/verification-video') {
    $u = api_require_auth();
    if ((int)$u['user_type'] !== 1) api_err('Forbidden', 403);

    require_once __DIR__ . '/../../includes/settings.php';
    require_once __DIR__ . '/../../includes/IdentityVerificationRunner.php';

    // فارسی: فشرده سازی ویدئو با ffmpeg (اگر نصب باشد)
    function _amut_ffmpeg_path(): string
    {
        $p = trim((string)shell_exec('command -v ffmpeg'));
        return $p ?: '';
    }

    function _amut_compress_video(string $src, string $dst): bool
    {
        $ff = _amut_ffmpeg_path();
        if ($ff === '') return false;

        // فارسی: خروجی mp4 سبک (حداکثر عرض 640)، صدای کم‌حجم، faststart برای استریم بهتر
        $cmd =
            $ff . ' -y -i ' . escapeshellarg($src) .
            ' -vf "scale=\'min(640,iw)\':-2,fps=24" ' .
            ' -c:v libx264 -profile:v baseline -level 3.0 -preset veryfast -crf 28 ' .
            ' -pix_fmt yuv420p ' .
            ' -c:a aac -b:a 64k -ac 1 -ar 16000 ' .
            ' -movflags +faststart ' .
            escapeshellarg($dst) .
            ' 2>/dev/null';

        shell_exec($cmd);

        return is_file($dst) && filesize($dst) > 0;
    }

    $requireVideo = (string)settings_get('onboarding.require_verification_video', '0') === '1';
    if (!$requireVideo) {
        api_ok(['skipped' => true, 'message' => 'احراز هویت ویدئویی توسط ادمین غیرفعال است.']);
    }

    if (empty($_FILES['verification_video']) || !is_array($_FILES['verification_video'])) {
        api_err('verification_video الزامی است', 422);
    }

    $f = $_FILES['verification_video'];
    if (($f['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        api_err('آپلود ویدئو ناموفق بود', 400);
    }

    // فارسی: سقف نهایی فایل (بعد از فشرده سازی) از تنظیمات
    $maxMb = (int)settings_get('verification.video_max_mb', '5');
    if ($maxMb <= 0) $maxMb = 5;
    $maxBytesFinal = $maxMb * 1024 * 1024;

    // فارسی: سقف فایل خام برای پذیرش اولیه (می‌توانید در تنظیمات هم بگذارید)
    $rawMaxMb = (int)settings_get('verification.video_raw_max_mb', '15');
    if ($rawMaxMb <= 0) $rawMaxMb = 15;
    $maxBytesRaw = $rawMaxMb * 1024 * 1024;

    $rawSize = (int)($f['size'] ?? 0);
    if ($rawSize <= 0 || $rawSize > $maxBytesRaw) {
        api_err('حجم ویدئو بیش از حد مجاز است', 422);
    }

    $mime = (string)($f['type'] ?? '');
    $allowedMimes = ['video/mp4', 'video/quicktime', 'video/x-matroska', 'video/3gpp', 'video/webm'];
    if ($mime !== '' && !in_array($mime, $allowedMimes, true)) {
        api_err('فرمت ویدئو نامعتبر است', 422);
    }

    $ext = strtolower(pathinfo((string)($f['name'] ?? ''), PATHINFO_EXTENSION));
    if ($ext === '') $ext = 'mp4';

    $uploadDir = BASE_PATH . '/storage/uploads/videos';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // فارسی: ابتدا خام را با نام موقت ذخیره می‌کنیم
    $base = 'verify_' . (int)$u['id'] . '_' . uniqid('', true);
    $rawFilename = $base . '_raw.' . $ext;
    $rawDest = $uploadDir . '/' . $rawFilename;

    if (!move_uploaded_file((string)$f['tmp_name'], $rawDest)) {
        api_err('ذخیره ویدئو ناموفق بود', 500);
    }

    // فارسی: تلاش برای فشرده سازی به mp4 (حتی اگر ورودی mov/webm باشد)
    $finalFilename = $base . '.mp4';
    $finalDest = $uploadDir . '/' . $finalFilename;

    $compressed = _amut_compress_video($rawDest, $finalDest);

    // فارسی: اگر فشرده سازی موفق شد، خام حذف شود. در غیر اینصورت همان خام را نگه می‌داریم.
    $usedPath = $compressed ? $finalDest : $rawDest;
    $usedFilename = $compressed ? $finalFilename : $rawFilename;

    if ($compressed) {
        @unlink($rawDest);
    }

    // فارسی: کنترل نهایی حجم (روی نسخه نهایی/استفاده شده)
    $usedSize = is_file($usedPath) ? (int)filesize($usedPath) : 0;
    if ($usedSize <= 0) {
        @unlink($usedPath);
        api_err('ذخیره ویدئو ناموفق بود', 500);
    }
    if ($usedSize > $maxBytesFinal) {
        @unlink($usedPath);
        api_err('حجم ویدئو بیش از حد مجاز است', 422);
    }

    $pdo = db();
    $fileKey = 'videos/' . $usedFilename;

    // فارسی: mime واقعی خروجی (اگر mp4 شد)
    $storedMime = $compressed ? 'video/mp4' : ($mime ?: null);

    $meta = json_encode([
        'original_name' => (string)($f['name'] ?? ''),
        'uploaded_at' => date('Y-m-d H:i:s'),
        'raw_size' => $rawSize,
        'final_size' => $usedSize,
        'compressed' => $compressed ? 1 : 0,
    ], JSON_UNESCAPED_UNICODE);

    $st = $pdo->prepare("INSERT INTO user_files (user_id, file_type, file_key, mime_type, file_size, metadata, created_at, updated_at) VALUES (?, 6, ?, ?, ?, ?, NOW(3), NOW(3))");
    $st->execute([(int)$u['id'], $fileKey, $storedMime, $usedSize, $meta]);

    // ساخت speechText از template
    $companyName = (string)settings_get('company.name', settings_get('site.name', ''));
    $tpl = (string)settings_get('verification.video_phrase_template', 'اینجانب {full_name} با قوانین {company_name} موافقت می‌کنم.');
    $speechText = str_replace(
        ['{full_name}', '{company_name}'],
        [(string)($u['full_name'] ?? ''), $companyName],
        $tpl
    );

    $overrides = [
        'speechText' => $speechText,
        'livenessThreshold' => (int)settings_get('verification.api_ir.liveness_threshold', '80'),
        'matchingThreshold' => (int)settings_get('verification.api_ir.matching_threshold', '90'),
        'speechThreshold' => (int)settings_get('verification.api_ir.speech_threshold', '50'),
    ];

    // اجرای سرویس VideoVerify اگر در دیتابیس تعریف شده باشد
    $svcId = 0;
    $st = $pdo->prepare("SELECT id FROM identity_verification_services WHERE code='VideoVerify' AND is_active=1 LIMIT 1");
    $st->execute();
    $svcId = (int)($st->fetchColumn() ?: 0);

    if ($svcId <= 0) {
        api_ok([
            'uploaded' => true,
            'file_key' => $fileKey,
            'message' => 'ویدئو ذخیره شد اما سرویس VideoVerify در پنل تعریف/فعال نیست.',
        ]);
    }

    $runner = new IdentityVerificationRunner($pdo);
    $run = $runner->runServiceForUser($svcId, (int)$u['id'], null, $overrides);
    if (empty($run['ok'])) {
        api_ok([
            'uploaded' => true,
            'file_key' => $fileKey,
            'verified' => false,
            'verification' => $run,
        ]);
    }

    $result = $run['result'] ?? [];
    $passed = false;
    if (is_array($result) && !empty($result['success']) && is_array($result['data'] ?? null)) {
        $d = $result['data'];
        $passed = (bool)($d['isMatch'] ?? false) && (bool)($d['isLiveness'] ?? false) && (bool)($d['isSpeechMatched'] ?? false);
    }

    api_ok([
        'uploaded' => true,
        'file_key' => $fileKey,
        'verified' => $passed,
        'verification' => $run,
    ]);
}

// دریافت بنرهای تبلیغاتی
if ($method === 'GET' && $path === '/banners') {
    $pdo = db();
    // 1 برای اپلیکیشن رانندگان
    $targetAppId = isset($_GET['target_app_id']) ? (int)$_GET['target_app_id'] : 1;
    $placement = isset($_GET['placement']) ? trim($_GET['placement']) : 'dashboard';

    // استفاده از فیلدهای دیتابیس شامل action_value و target_app_id
    $st = $pdo->prepare("
        SELECT id, title, body, image_key, action_value, action_type 
        FROM banners 
        WHERE is_active = 1 
          AND target_app_id = ? 
          AND placement = ? 
          AND (start_at IS NULL OR start_at <= NOW()) 
          AND (end_at IS NULL OR end_at >= NOW())
        ORDER BY priority ASC, id DESC
    ");
    $st->execute([$targetAppId, $placement]);
    $rows = $st->fetchAll();

    require_once __DIR__ . '/../../includes/settings.php';
    $siteUrl = rtrim((string)settings_get('site.url', ''), '/');
    if ($siteUrl === '') {
        $siteUrl = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];
    }

    $banners = array_map(function ($r) use ($siteUrl) {
        $urlRel = ltrim((string)$r['image_key'], '/');
        $imgUrl = preg_match('~^https?://~i', $urlRel) ? $urlRel : $siteUrl . '/' . $urlRel;
        return [
            'id' => (int)$r['id'],
            'title' => (string)$r['title'],
            'body' => (string)$r['body'],
            'image_url' => $imgUrl,
            // برای فلاتر همون کلید target_url رو میفرستیم که کدهاش تغییر نکنه
            'target_url' => !empty($r['action_value']) ? (string)$r['action_value'] : null,
        ];
    }, $rows);

    api_ok(['items' => $banners]);
}

api_err('Not found', 404, ['path' => $path]);
