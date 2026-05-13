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
$birth_date = trim($in['birth_date'] ?? ''); // فرمت: 1370/01/01
$card_serial = trim($in['card_serial'] ?? '');

if (empty($full_name) || empty($national_code) || empty($birth_date) || empty($card_serial)) {
    api_err('لطفاً تمام فیلدها (نام، کدملی، تاریخ تولد، سریال کارت) را وارد کنید.');
}

try {
    // 3. دریافت شماره موبایل کاربر
    $user_mobile = (string)($user['phone'] ?? '');
    if ($user_mobile === '') api_err('شماره موبایل کاربر یافت نشد.');

    // ایجاد نمونه از کلاس کمکی
    $apiHelper = new ExternalApiHelper($pdo);

    // نام اسلاگ در دیتابیس (باید مطمئن شوید در جدول external_api_providers مقدار slug برابر api_ir است)
    $providerSlug = 'api_ir';

    // ---------------------------------------------------------
    // 4. فراخوانی شاهکار لایت (Shahkar Lite)
    // طبق مستندات: تطبیق موبایل و کد ملی
    // ---------------------------------------------------------
    $shahkarBody = [
        'mobile' => $user_mobile,
        'nationalCode' => $national_code
    ];

    // نکته: طبق مستندات متنی شما، آدرس اندپوینت باید دقیق باشد.
    // اگر آدرس پایه در دیتابیس https://s.api.ir است، اینجا ادامه آن را می‌نویسیم.
    // فرض: آدرس کامل https://s.api.ir/v1/shahkar/lite باشد.
    try {
        $shahkarRes = $apiHelper->callExternalApi($providerSlug, '/api/sw1/ShahkarLite', 'POST', $shahkarBody);
    } catch (Exception $e) {
        api_err('خطا در ارتباط با سرویس شاهکار: ' . $e->getMessage());
    }

    // بررسی پاسخ شاهکار طبق مستندات متنی شما
    // Response: { "data": true, "success": true, ... }
    if (empty($shahkarRes['success']) || $shahkarRes['success'] !== true) {
        $msg = $shahkarRes['message'] ?? 'خطای ناشناخته در سرویس شاهکار';
        api_err("استعلام شاهکار ناموفق: $msg");
    }

    // فیلد data باید true باشد
    if (($shahkarRes['data'] ?? false) !== true) {
        api_err('کد ملی وارد شده متعلق به این شماره موبایل نیست.');
    }

    // ---------------------------------------------------------
    // 5. استعلام عکس هویتی (Photo Inquiry)
    // ---------------------------------------------------------
    // فرمت تاریخ برای این سرویس: 1370/1/1 (طبق مستندات متنی شما)
    // کدی که از فلاتر می آید 1370/01/01 است. معمولا api.ir با هر دو کار میکند اما اگر حساس بود:
    // $birth_date_clean = sprintf("%d/%d/%d", ...explode('/', $birth_date)); 

    $photoBody = [
        'birthDate' => $birth_date,
        'nationalCode' => $national_code,
        'serialNumber' => $card_serial
    ];

    // فرض: آدرس کامل https://s.api.ir/v1/estelam/photo باشد
    try {
        $photoRes = $apiHelper->callExternalApi($providerSlug, '/api/sw1/PersonImage', 'POST', $photoBody);
    } catch (Exception $e) {
        api_err('خطا در ارتباط با سرویس عکس: ' . $e->getMessage());
    }

    // بررسی پاسخ عکس
    // Response: { "data": { "imageBase64": "..." }, "success": true }
    if (empty($photoRes['success']) || $photoRes['success'] !== true) {
        $msg = $photoRes['message'] ?? 'اطلاعات هویتی (سریال/تاریخ تولد) صحیح نیست.';
        api_err("استعلام عکس تایید نشد: $msg");
    }

    $imageBase64 = $photoRes['data']['imageBase64'] ?? null;
    $avatarFilename = null;

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

    // 6. ثبت در دیتابیس (همسان با ساختار جدید پروژه)
    $pdo->beginTransaction();
    try {
        // همسان سازی و ذخیره اطلاعات هویتی کاربر (برای سرویس‌های بعدی مثل VideoVerify)
        $pdo->prepare("UPDATE users SET full_name=?, code_meli=?, birth_date=?, national_card_serial=?, updated_at=NOW(3) WHERE id=? LIMIT 1")
            ->execute([$full_name, $national_code, $birth_date, $card_serial, $user_id]);

        // آپدیت/ایجاد پروفایل راننده + تایید
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

        // اگر عکس ذخیره شد، به avatar_key تبدیل می‌کنیم
        if ($avatarFilename) {
            $newKey = 'storage/avatars/' . $avatarFilename;
            $pdo->prepare("UPDATE users SET avatar_key=?, updated_at=NOW(3) WHERE id=? LIMIT 1")
                ->execute([$newKey, $user_id]);
        }

        $pdo->commit();
    } catch (Throwable $e) {
        $pdo->rollBack();
        api_err('خطا در پردازش: ' . $e->getMessage(), 500);
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
