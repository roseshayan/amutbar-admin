<?php

declare(strict_types=1);

header('Content-Type: application/json');

require_once '../includes/init.php';
require_admin();

require_once '../includes/settings.php';

$action = $_GET['action'] ?? ($_POST['action'] ?? '');

function ensure_dir(string $path): void
{
    if (!is_dir($path)) {
        mkdir($path, 0775, true);
    }
}

try {
    $adminId = admin_id();

    if ($action === 'get') {
        $keys = [
            'company.name',
            'site.name',
            'site.url',
            'site.logo_path',
            'site.favicon_path',
            'links.terms_url',
            'links.terms_text',
            'links.app_download_url',
            'support.phone',
            'support.whatsapp',
            'support.telegram',
            'app.android.latest_version_code',
            'app.android.min_supported_code',
            'app.android.update_url',
            'app.ios.latest_version_code',
            'app.ios.min_supported_code',
            'app.ios.update_url',
            'cargo.app.android.latest_version_code',
            'cargo.app.android.min_supported_code',
            'cargo.app.android.update_url',
            'cargo.app.ios.latest_version_code',
            'cargo.app.ios.min_supported_code',
            'cargo.app.ios.update_url',
            'maintenance.enabled',
            'maintenance.message',

            // Verification / onboarding
            'auth.require_national_serial',
            'onboarding.require_verification_video',
            'verification.video_phrase_template',
            'verification.video_guide_text',
            'verification.video_guide_url',
            'verification.video_max_seconds',
            'verification.video_max_mb',
            'verification.api_ir.liveness_threshold',
            'verification.api_ir.matching_threshold',
            'verification.api_ir.speech_threshold'
        ];
        $vals = settings_get_many($keys);
        // defaults
        foreach ($keys as $k) {
            if (!array_key_exists($k, $vals)) $vals[$k] = null;
        }
        echo json_encode(['ok' => true, 'items' => $vals]);
        exit;
    }

    if ($action === 'save') {
        $items = $_POST['items'] ?? [];
        if (is_string($items)) {
            $tmp = json_decode($items, true);
            $items = is_array($tmp) ? $tmp : [];
        }
        if (!is_array($items)) $items = [];

        // whitelisting (از ذخیره کلیدهای دلخواه جلوگیری کن)
        $allowed = [
            'company.name',
            'site.name',
            'site.url',
            'links.terms_url',
            'links.terms_text',
            'links.app_download_url',
            'support.phone',
            'support.whatsapp',
            'support.telegram',
            'app.android.latest_version_code',
            'app.android.min_supported_code',
            'app.android.update_url',
            'app.ios.latest_version_code',
            'app.ios.min_supported_code',
            'app.ios.update_url',
            'cargo.app.android.latest_version_code',
            'cargo.app.android.min_supported_code',
            'cargo.app.android.update_url',
            'cargo.app.ios.latest_version_code',
            'cargo.app.ios.min_supported_code',
            'cargo.app.ios.update_url',
            'maintenance.enabled',
            'maintenance.message',

            // Verification / onboarding
            'auth.require_national_serial',
            'onboarding.require_verification_video',
            'verification.video_phrase_template',
            'verification.video_guide_text',
            'verification.video_guide_url',
            'verification.video_max_seconds',
            'verification.video_max_mb',
            'verification.api_ir.liveness_threshold',
            'verification.api_ir.matching_threshold',
            'verification.api_ir.speech_threshold'
        ];
        $filtered = [];
        foreach ($allowed as $k) {
            if (array_key_exists($k, $items)) {
                $filtered[$k] = $items[$k];
            }
        }
        settings_set_many($filtered, $adminId);
        echo json_encode(['ok' => true]);
        exit;
    }

    // --- ارسال پیامک تستی ---
    if ($action === 'send_test_sms') {
        $to = trim($_POST['to'] ?? '');
        $from = trim($_POST['from'] ?? '');

        if (empty($to) || empty($from)) {
            echo json_encode(['ok' => false, 'message' => 'شماره فرستنده و گیرنده الزامی است']);
            exit;
        }

        // استفاده از کلید جدید API که در env قرار دادی
        $apiKey = trim((string)env('PAYAMAK_APIKEY_CONSOLE', ''));
        if ($apiKey === '') {
            echo json_encode(['ok' => false, 'message' => 'کلید PAYAMAK_APIKEY_CONSOLE در فایل .env تنظیم نشده است']);
            exit;
        }
        $url = 'https://console.melipayamak.com/api/send/simple/' . rawurlencode($apiKey);

        $data = [
            'from' => $from,
            'to' => $to,
            'text' => 'این یک پیامک آزمایشی از سیستم مدیریت آموت‌بار است. اتصال با موفقیت برقرار شد!'
        ];
        $data_string = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ]);

        $result = curl_exec($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($curl_error) {
            echo json_encode(['ok' => false, 'message' => 'خطا در ارتباط با سرور: ' . $curl_error]);
            exit;
        }

        $decoded = json_decode((string)$result, true);

        if (isset($decoded['recId'])) {
            echo json_encode(['ok' => true, 'message' => 'پیامک با موفقیت ارسال شد (کد رهگیری: ' . $decoded['recId'] . ')']);
        } else {
            echo json_encode(['ok' => false, 'message' => $decoded['status'] ?? 'خطای نامشخص از سمت ملی پیامک']);
        }
        exit;
    }
    // ---------------------------------

    // --- دریافت اعتبار پنل پیامکی ---
    if ($action === 'get_sms_credit') {
        // کلید API را از فایل env می‌خوانیم، در غیر این صورت از کلیدی که دادی استفاده می‌کند
        $apiKey = trim((string)env('PAYAMAK_APIKEY_CONSOLE', ''));
        if ($apiKey === '') {
            echo json_encode(['ok' => false, 'message' => 'کلید PAYAMAK_APIKEY_CONSOLE در فایل .env تنظیم نشده است']);
            exit;
        }
        $url = "https://console.melipayamak.com/api/receive/credit/" . rawurlencode($apiKey);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: 0'
        ]);

        $result = curl_exec($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($curl_error) {
            echo json_encode(['ok' => false, 'message' => 'خطا در ارتباط با سرور پیامک']);
            exit;
        }

        $decoded = json_decode((string)$result, true);

        if (isset($decoded['amount'])) {
            echo json_encode(['ok' => true, 'amount' => $decoded['amount']]);
        } else {
            echo json_encode(['ok' => false, 'message' => $decoded['status'] ?? 'خطای نامشخص از سمت ملی پیامک']);
        }
        exit;
    }
    // ---------------------------------

    if ($action === 'upload_logo') {
        if (!isset($_FILES['logo']) || $_FILES['logo']['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('آپلود ناموفق بود');
        }

        $f = $_FILES['logo'];
        $tmp = $f['tmp_name'];
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($tmp);
        if ((int)($f['size'] ?? 0) <= 0 || (int)$f['size'] > 5 * 1024 * 1024) {
            throw new RuntimeException('حجم فایل باید حداکثر ۵ مگابایت باشد');
        }
        if (!in_array($mime, ['image/png', 'image/jpeg', 'image/webp'], true) || @getimagesize($tmp) === false) {
            throw new RuntimeException('فرمت فایل نامعتبر است (png/jpg/webp)');
        }

        $ext = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/webp' => 'webp',
            default => 'png'
        };

        $dir = BASE_PATH . '/storage/uploads/system';
        ensure_dir($dir);

        $filename = 'site-logo.' . $ext;
        $dest = $dir . '/' . $filename;
        if (!move_uploaded_file($tmp, $dest)) {
            throw new RuntimeException('ذخیره فایل ناموفق بود');
        }
        @chmod($dest, 0640);

        $publicPath = 'storage/uploads/system/' . $filename;
        settings_set('site.logo_path', $publicPath, $adminId);

        echo json_encode(['ok' => true, 'path' => $publicPath]);
        exit;
    }

    if ($action === 'upload_favicon') {
        if (!isset($_FILES['favicon']) || $_FILES['favicon']['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('آپلود ناموفق بود');
        }

        $f = $_FILES['favicon'];
        $tmp = $f['tmp_name'];
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($tmp);

        if ((int)($f['size'] ?? 0) <= 0 || (int)$f['size'] > 1024 * 1024) {
            throw new RuntimeException('حجم نمادک باید حداکثر ۱ مگابایت باشد');
        }
        // SVG برای جلوگیری از اجرای اسکریپت آپلودی پذیرفته نمی‌شود.
        $allowedMimes = ['image/png', 'image/jpeg', 'image/webp', 'image/x-icon', 'image/vnd.microsoft.icon'];
        if (!in_array($mime, $allowedMimes, true)) {
            throw new RuntimeException('فرمت فایل نامعتبر است (png/jpg/webp/ico)');
        }

        $ext = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/webp' => 'webp',
            'image/x-icon', 'image/vnd.microsoft.icon' => 'ico',
            default => 'png'
        };

        $dir = BASE_PATH . '/storage/uploads/system';
        ensure_dir($dir);

        $filename = 'site-favicon.' . $ext;
        $dest = $dir . '/' . $filename;
        if (!move_uploaded_file($tmp, $dest)) {
            throw new RuntimeException('ذخیره فایل ناموفق بود');
        }
        @chmod($dest, 0640);

        $publicPath = 'storage/uploads/system/' . $filename;
        settings_set('site.favicon_path', $publicPath, $adminId);

        echo json_encode(['ok' => true, 'path' => $publicPath]);
        exit;
    }

    echo json_encode(['ok' => false, 'message' => 'action نامعتبر است']);
} catch (Throwable $e) {
    $debug = (string)env('APP_DEBUG', '0') === '1';
    echo json_encode(['ok' => false, 'message' => $debug ? $e->getMessage() : 'خطای سرور']);
}
