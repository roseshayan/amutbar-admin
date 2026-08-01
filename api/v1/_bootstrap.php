<?php

declare(strict_types=1);

// API نباید Warning، Stack trace یا مسیر فایل‌های PHP را به کلاینت نمایش دهد.
// جزئیات فنی فقط در error log سرور ثبت می‌شود.
define('AMUTBAR_API_REQUEST', true);
ini_set('display_errors', '0');
ini_set('html_errors', '0');
ini_set('log_errors', '1');

require_once __DIR__ . '/../../includes/init.php';
require_once __DIR__ . '/../../includes/api.php';
require_once __DIR__ . '/../../includes/api_http.php';
require_once __DIR__ . '/../../includes/otp.php';

// includes/init.php تنظیم Debug عمومی پنل را بارگذاری می‌کند؛ برای API دوباره
// نمایش خطا را خاموش می‌کنیم تا حتی با APP_DEBUG=1 نیز خروجی فنی نشت نکند.
ini_set('display_errors', '0');
ini_set('html_errors', '0');

// Always JSON for API
header('Content-Type: application/json; charset=utf-8');

// CORS + OPTIONS
api_cors();

set_exception_handler(static function (Throwable $e): void {
    error_log('api.unhandled: ' . $e->getMessage());
    if (!headers_sent()) {
        header('Content-Type: application/json; charset=utf-8');
    }
    http_response_code(500);
    echo json_encode(
        ['ok' => false, 'message' => 'خطایی در پردازش اطلاعات رخ داد. لطفاً دوباره تلاش کنید.'],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
    exit;
});
