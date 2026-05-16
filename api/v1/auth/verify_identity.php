<?php
require_once __DIR__ . '/../_bootstrap.php';
// لود کردن کلاس کمکی که فرستادید
require_once __DIR__ . '/../../../includes/ExternalApiHelper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_err('Method not allowed', 405);
}

// 1. بررسی لاگین
$user = api_require_auth();
$pdo = db();
$user_id = (int)($user['id'] ?? 0);
if ($user_id <= 0) api_err('Unauthorized - لطفا مجدد وارد شوید', 401);

// 2. دریافت ورودی‌ها
$in = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$full_name = trim($in['full_name'] ?? '');
$national_code = trim($in['national_code'] ?? '');
$birth_date = trim($in['birth_date'] ?? '');
$card_serial = trim($in['card_serial'] ?? '');

// اضافه کردن تنظیمات برای بررسی الزامی بودن سریال کارت
require_once __DIR__ . '/../../../includes/settings.php';
$requireSerial = (settings_get('auth.require_national_serial') === '1');

// بررسی خالی بودن فیلدهای پایه
if (empty($full_name) || empty($national_code) || empty($birth_date)) {
    api_err('لطفاً تمام فیلدها (نام، کدملی، تاریخ تولد) را وارد کنید.');
}

// اگر سریال الزامی بود، چک کن که خالی نباشه
if ($requireSerial && empty($card_serial)) {
    api_err('وارد کردن سریال کارت ملی الزامی است.');
}

try {
    $user_mobile = (string)($user['phone'] ?? '');
    if ($user_mobile === '') api_err('شماره موبایل کاربر یافت نشد.');

    $apiHelper = new ExternalApiHelper($pdo);
    $providerSlug = 'api_ir';

    // --- 1. استعلام شاهکار لایت (همیشه اجرا می‌شود) ---
    $shahkarBody = [
        'mobile' => $user_mobile,
        'nationalCode' => $national_code
    ];

    try {
        $shahkarRes = $apiHelper->callExternalApi($providerSlug, '/api/sw1/ShahkarLite', 'POST', $shahkarBody);
    } catch (Exception $e) {
        api_err('خطا در ارتباط با سرویس شاهکار: ' . $e->getMessage());
    }

    if (empty($shahkarRes['success']) || $shahkarRes['success'] !== true) {
        $msg = $shahkarRes['message'] ?? 'خطای ناشناخته در سرویس شاهکار';
        api_err("استعلام شاهکار ناموفق: $msg");
    }

    if (($shahkarRes['data'] ?? false) !== true) {
        api_err('کد ملی وارد شده متعلق به این شماره موبایل نیست.');
    }


    // --- 2. استعلام عکس هویتی (فقط اگر سریال کارت فعال باشد) ---
    $avatarFilename = null;

    if ($requireSerial) {
        $photoBody = [
            'birthDate' => $birth_date,
            'nationalCode' => $national_code,
            'serialNumber' => $card_serial
        ];

        try {
            $photoRes = $apiHelper->callExternalApi($providerSlug, '/api/sw1/PersonImage', 'POST', $photoBody);
        } catch (Exception $e) {
            api_err('خطا در ارتباط با سرویس عکس: ' . $e->getMessage());
        }

        if (empty($photoRes['success']) || $photoRes['success'] !== true) {
            $msg = $photoRes['message'] ?? 'اطلاعات هویتی (سریال/تاریخ تولد) صحیح نیست.';
            api_err("استعلام عکس تایید نشد: $msg");
        }

        $imageBase64 = $photoRes['data']['imageBase64'] ?? null;
        if (!empty($imageBase64)) {
            $imgBin = base64_decode($imageBase64);
            if ($imgBin) {
                $fileName = 'avatar_' . $user_id . '_' . time() . '.jpg';
                $uploadDir = __DIR__ . '/../../../storage/avatars/';

                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                file_put_contents($uploadDir . $fileName, $imgBin);

                $avatarFilename = $fileName;
            }
        }
    }

    // --- 3. ثبت در دیتابیس ---
    $pdo->beginTransaction();
    try {
        // آپدیت یوزر (اگر $card_serial خالی باشه هم همون خالی ذخیره میشه که درسته)
        $pdo->prepare("UPDATE users SET full_name=?, code_meli=?, birth_date=?, national_card_serial=?, updated_at=NOW(3) WHERE id=? LIMIT 1")
            ->execute([$full_name, $national_code, $birth_date, $card_serial, $user_id]);

        $st = $pdo->prepare("SELECT id FROM drivers WHERE user_id=? AND deleted_at IS NULL LIMIT 1");
        $st->execute([$user_id]);
        $driverId = (int)($st->fetchColumn() ?: 0);

        if ($driverId > 0) {
            $pdo->prepare("UPDATE drivers SET full_name=?, national_code=?, verification_status=1, verified_at=NOW(3), reject_reason=NULL, updated_at=NOW(3) WHERE id=? LIMIT 1")
                ->execute([$full_name, $national_code, $driverId]);
        } else {
            $pdo->prepare("INSERT INTO drivers (user_id, full_name, national_code, vehicle_type_id, plate_number, verification_status, verified_at, created_at, updated_at) VALUES (?, ?, ?, 0, '', 1, NOW(3), NOW(3), NOW(3))")
                ->execute([$user_id, $full_name, $national_code]);
        }

        if ($avatarFilename) {
            $newKey = 'storage/avatars/' . $avatarFilename;
            $pdo->prepare("UPDATE users SET avatar_key=?, updated_at=NOW(3) WHERE id=? LIMIT 1")
                ->execute([$newKey, $user_id]);
        }

        $pdo->commit();
    } catch (Throwable $e) {
        $pdo->rollBack();
        api_err('خطا در ذخیره‌سازی اطلاعات: ' . $e->getMessage(), 500);
    }

    api_ok([
        'message' => 'احراز هویت با موفقیت انجام شد.',
        'profile' => [
            'full_name' => $full_name,
            'avatar' => $avatarFilename,
            'verification_status' => 1
        ]
    ]);
} catch (Exception $e) {
    api_err('خطا در پردازش: ' . $e->getMessage());
}
