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
            'site.name','site.url','site.logo_path','site.favicon_path',
            'links.terms_url','links.terms_text','links.app_download_url',
            'support.phone','support.whatsapp','support.telegram',
            'app.android.latest_version_code','app.android.min_supported_code','app.android.update_url',
            'app.ios.latest_version_code','app.ios.min_supported_code','app.ios.update_url',
            'maintenance.enabled','maintenance.message',

            // Verification / onboarding
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
            'links.terms_url','links.terms_text','links.app_download_url',
            'support.phone','support.whatsapp','support.telegram',
            'app.android.latest_version_code','app.android.min_supported_code','app.android.update_url',
            'app.ios.latest_version_code','app.ios.min_supported_code','app.ios.update_url',
            'maintenance.enabled','maintenance.message',

            // Verification / onboarding
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

    if ($action === 'upload_logo') {
        if (!isset($_FILES['logo']) || $_FILES['logo']['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('آپلود ناموفق بود');
        }

        $f = $_FILES['logo'];
        $tmp = $f['tmp_name'];
        $mime = mime_content_type($tmp);
        if (!in_array($mime, ['image/png','image/jpeg','image/webp'], true)) {
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
        $mime = mime_content_type($tmp);

        // Favicon can be png/jpg/webp/ico/svg
        $allowedMimes = ['image/png','image/jpeg','image/webp','image/x-icon','image/vnd.microsoft.icon','image/svg+xml'];
        if (!in_array($mime, $allowedMimes, true)) {
            throw new RuntimeException('فرمت فایل نامعتبر است (png/jpg/webp/ico/svg)');
        }

        $ext = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/webp' => 'webp',
            'image/x-icon', 'image/vnd.microsoft.icon' => 'ico',
            'image/svg+xml' => 'svg',
            default => 'png'
        };

        $dir = BASE_PATH . '/storage/uploads/system';
        ensure_dir($dir);

        $filename = 'site-favicon.' . $ext;
        $dest = $dir . '/' . $filename;
        if (!move_uploaded_file($tmp, $dest)) {
            throw new RuntimeException('ذخیره فایل ناموفق بود');
        }

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
