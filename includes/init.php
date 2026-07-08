<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

if (class_exists(\Dotenv\Dotenv::class)) {
    $dotenv = \Dotenv\Dotenv::createImmutable(BASE_PATH);
    $dotenv->safeLoad();
}

$debug = (string)env('APP_DEBUG', '0') === '1';
ini_set('display_errors', $debug ? '1' : '0');
error_reporting($debug ? E_ALL : 0);

ini_set('log_errors', '1');
ini_set('error_log', BASE_PATH . '/storage/logs/php-error.log');

ob_start();

session_name((string)env('SESSION_NAME', 'AMUTBARSESSID'));

$appEnv = (string)env('APP_ENV', 'local');

// روی لوکال، Secure را همیشه false نگه دار (حتی اگر سرور اشتباه HTTPS را on بدهد)
$secureCookie = false;

if ($appEnv !== 'local') {
    $secureCookie =
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['REQUEST_SCHEME'] ?? '') === 'https')
        || ((int)($_SERVER['SERVER_PORT'] ?? 0) === 443)
        || (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
}

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => $secureCookie,
    'httponly' => true,
    'samesite' => 'Lax',
]);

session_start();

date_default_timezone_set('Asia/Tehran');


require_once __DIR__ . '/db.php';

//require_once __DIR__ . '/jdf.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/settings.php';
require_once __DIR__ . '/internal_api.php';
require_once __DIR__ . '/ExternalApiHelper.php';

require_once __DIR__ . '/audit.php';
require_once __DIR__ . '/auth_log.php';
require_once __DIR__ . '/driver_activity.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/sms.php';
require_once __DIR__ . '/otp.php';

require_once __DIR__ . '/users.php';
require_once __DIR__ . '/admin_profile.php';