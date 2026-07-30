<?php
declare(strict_types=1);

function env(string $key, $default = null)
{
    $v = $_ENV[$key] ?? getenv($key);
    if ($v === false || $v === null || $v === '') return $default;
    return $v;
}

// تابع کمکی برای رمزنگاری/رمزگشایی
function get_encryption_key(): string
{
    $key = (string)env('API_ENCRYPTION_KEY', env('API_TOKEN_PEPPER', ''));
    if ($key === '' && strtolower((string)env('APP_ENV', 'local')) === 'production') {
        throw new RuntimeException('API_ENCRYPTION_KEY is required in production');
    }
    if ($key === '') $key = 'local-only-encryption-key-change-me';

    // اگر کلید کوتاه است، آن را به 32 بایت تبدیل می‌کنیم
    if (strlen($key) < 32) {
        $key = hash('sha256', $key);
    } elseif (strlen($key) > 32) {
        $key = substr($key, 0, 32);
    }

    return $key;
}
