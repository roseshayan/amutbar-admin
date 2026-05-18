<?php

declare(strict_types=1);

use JetBrains\PhpStorm\NoReturn;

function is_ajax(): bool
{
    return strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest';
}

#[NoReturn]
function json_out(array $data, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function require_post(): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        json_out(['ok' => false, 'message' => 'Method not allowed'], 405);
    }
}

function asset(string $path): string
{
    return '/assets/' . ltrim($path, '/');
}

#[NoReturn]
function redirect(string $url): void
{
    header("Location: $url");
    exit;
}

function base_url(): string
{
    $dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
    // اگر در روت هستید '/' می‌شود
    return $dir === '/' ? '' : $dir;
}

function url_path(string $path): string
{
    return base_url() . '/' . ltrim($path, '/');
}

// Sanitize Text
function sanitize_input($input): string
{
    $input = trim($input);
    $input = strip_tags($input);
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

// Sanitize Phone Number
function sanitize_phone($phone): array|bool|string|null
{
    $phone = preg_replace("/[^0-9]/", "", $phone);
    if (preg_match("/^09\d{9}$/", $phone)) {
        return $phone;
    }
    return false;
}

// Sanitize Email
function sanitize_email($email)
{
    $email = trim($email);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return $email;
    }
    return false;
}

// Sanitize Code Meli
function validate_national_code($code): ?string
{
    if (empty($code)) {
        return 'کد ملی الزامی است';
    }

    if (!preg_match('/^[0-9]{10}$/', $code)) {
        return 'کد ملی باید 10 رقم باشد';
    }

    for ($i = 0; $i < 10; $i++) {
        if (preg_match('/^' . $i . '{10}$/', $code)) {
            return 'کد ملی معتبر نیست';
        }
    }

    $sum = 0;
    for ($i = 0; $i < 9; $i++) {
        $sum += ((10 - $i) * intval(substr($code, $i, 1)));
    }

    $remainder = $sum % 11;
    $control_digit = intval(substr($code, 9, 1));

    if (!(($remainder < 2 && $remainder == $control_digit) || ($remainder >= 2 && $remainder == 11 - $control_digit))) {
        return 'کد ملی معتبر نیست';
    }

    return null;
}

// Sanitize BirthDate
function validate_birth_date($year, $month, $day): ?string
{
    if (empty($year) || empty($month) || empty($day)) {
        return 'تاریخ تولد باید کامل وارد شود';
    }

    $year = (int)$year;
    $month = (int)$month;
    $day = (int)$day;

    // اعتبارسنجی ماه
    if ($month < 1 || $month > 12) {
        return 'ماه تولد معتبر نیست';
    }

    // اعتبارسنجی روز بر اساس ماه و سال کبیسه
    if ($month <= 6 && $day > 31) {
        return 'روز تولد معتبر نیست';
    }
    if ($month > 6 && $month <= 11 && $day > 30) {
        return 'روز تولد معتبر نیست';
    }
    if ($month == 12) {
        $is_leap = ($year - 1) % 4 === 0;
        if (($is_leap && $day > 30) || (!$is_leap && $day > 29)) {
            return 'روز تولد معتبر نیست';
        }
    }

    // محاسبه تاریخ شمسی کنونی
    $currentDate = new DateTime();
    $currentYear = (int)$currentDate->format('Y') - 621; // سال شمسی
    $currentMonth = (int)$currentDate->format('m'); // ماه
    $currentDay = (int)$currentDate->format('d'); // روز

    // محاسبه سن
    $age = $currentYear - $year;
    if ($currentMonth < $month || ($currentMonth == $month && $currentDay < $day)) {
        $age--;
    }

    // بررسی اینکه سن حداقل 18 سال تمام باشد
    if ($age < 18) {
        return 'حداقل سن 18 سال تمام است';
    }

    return null;
}

// فرمت تاریخ شمسی (تابع کمکی)
function jalali_date($date): string
{
    if (!$date) return '-';

    $timestamp = strtotime($date);
    if (!$timestamp) return $date;

    return jdate('Y/m/d H:i', $timestamp);
}

function convertPersianNumbersToEnglishPHP($string): array|string
{
    $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
    $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

    $string = str_replace($persian, $english, $string);
    return str_replace($arabic, $english, $string);
}

// فارسی: تبدیل ویدئو به نسخه سبک‌تر (H264 + scale + CRF)
function compress_video_if_possible(string $src, string $dst): bool
{
    // اگر ffmpeg روی سرور نصب نبود، false برمی‌گردانیم
    $ff = trim((string)shell_exec('command -v ffmpeg'));
    if ($ff === '') return false;

    // نکته: scale را طوری می‌گیریم که عرض حداکثر 640 باشد و نسبت حفظ شود
    $cmd = $ff . ' -y -i ' . escapeshellarg($src)
        . ' -vf "scale=\'min(640,iw)\':-2"'
        . ' -c:v libx264 -profile:v baseline -level 3.0 -preset veryfast -crf 28'
        . ' -c:a aac -b:a 64k -ac 1 -ar 16000'
        . ' -movflags +faststart '
        . escapeshellarg($dst)
        . ' 2>/dev/null';

    shell_exec($cmd);

    return file_exists($dst) && filesize($dst) > 0;
}
