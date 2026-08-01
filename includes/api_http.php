<?php

declare(strict_types=1);

// -----------------------------
// API HTTP Utilities
// -----------------------------

function api_cors(): void
{
    // Mobile apps usually don't send Origin; browsers do.
    $origin = (string)($_SERVER['HTTP_ORIGIN'] ?? '');
    $env = (string)env('APP_ENV', 'local');

    $allowed = trim((string)env('API_CORS_ORIGINS', ''));
    $allowOrigin = null;
    $debugEnabled = (string)env('APP_DEBUG', '0') === '1';
    $isLocalDevelopmentOrigin = ($env === 'local' || $debugEnabled)
        && $origin !== ''
        && preg_match('~^https?://(?:localhost|127\.0\.0\.1)(?::\d+)?$~D', $origin) === 1;

    if ($isLocalDevelopmentOrigin) {
        $allowOrigin = $origin;
    } elseif ($allowed !== '' && $origin !== '') {
        $list = array_filter(array_map('trim', explode(',', $allowed)));
        if (in_array($origin, $list, true)) $allowOrigin = $origin;
    } elseif ($env === 'local') {
        // Dev-friendly default
        $allowOrigin = $origin !== '' ? $origin : '*';
    }

    if ($allowOrigin !== null) {
        header('Access-Control-Allow-Origin: ' . $allowOrigin);
        header('Vary: Origin');
        if ($allowOrigin !== '*') {
            header('Access-Control-Allow-Credentials: true');
        }
    }

    header('Access-Control-Allow-Headers: Authorization, Content-Type, X-Requested-With');
    header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
        http_response_code(204);
        exit;
    }
}

function api_method(): string
{
    return strtoupper((string)($_SERVER['REQUEST_METHOD'] ?? 'GET'));
}

function api_require_method(array $allowed): void
{
    $m = api_method();
    $allowed = array_map('strtoupper', $allowed);
    if (!in_array($m, $allowed, true)) {
        header('Allow: ' . implode(', ', $allowed));
        api_err('Method not allowed', 405);
    }
}

function api_input_json(): array
{
    $ct = strtolower((string)($_SERVER['CONTENT_TYPE'] ?? $_SERVER['HTTP_CONTENT_TYPE'] ?? ''));
    if (str_contains($ct, 'application/json')) {
        $raw = file_get_contents('php://input');
        if ($raw === false || trim($raw) === '') return [];
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }
    return [];
}

function api_input(): array
{
    $m = api_method();
    if ($m === 'GET') return $_GET;

    $json = api_input_json();
    if ($json) return $json;

    // Fallback: form-data / x-www-form-urlencoded
    return $_POST ?: [];
}

function api_str(array $in, string $key, int $maxLen = 1000): ?string
{
    if (!array_key_exists($key, $in)) return null;
    $v = trim((string)$in[$key]);
    if ($v === '') return null;
    if (mb_strlen($v) > $maxLen) $v = mb_substr($v, 0, $maxLen);
    return $v;
}

function api_int(array $in, string $key): ?int
{
    if (!array_key_exists($key, $in)) return null;
    if ($in[$key] === null || $in[$key] === '') return null;
    return (int)$in[$key];
}

function api_decimal(array $in, string $key): ?string
{
    if (!array_key_exists($key, $in)) return null;
    $v = trim((string)$in[$key]);
    if ($v === '') return null;
    if (!preg_match('/^-?\d+(?:\.\d+)?$/', $v)) return null;
    return $v;
}

function api_phone_normalize(string $phone): string
{
    return preg_replace('/\D+/', '', $phone);
}

function api_require_phone(string $phone): string
{
    $p = api_phone_normalize($phone);
    if (!preg_match('/^09\d{9}$/', $p)) api_err('شماره موبایل نامعتبر است', 422);
    return $p;
}
