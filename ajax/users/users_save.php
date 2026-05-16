<?php

declare(strict_types=1);

require_once __DIR__ . '/../../includes/init.php';
require_admin();
require_post();

// --- شروع بخش تبدیل اعداد فارسی/عربی به انگلیسی ---
$persian_digits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
$arabic_digits  = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
$english_digits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

if (!empty($_POST['birth_date'])) {
    $temp_date = str_replace($persian_digits, $english_digits, $_POST['birth_date']);
    $_POST['birth_date'] = str_replace($arabic_digits, $english_digits, $temp_date);
}

// پیشنهاد: بهتره برای سایر فیلدهای عددی مهم هم این کار رو بکنی
if (!empty($_POST['code_meli'])) {
    $temp_meli = str_replace($persian_digits, $english_digits, $_POST['code_meli']);
    $_POST['code_meli'] = str_replace($arabic_digits, $english_digits, $temp_meli);
}

// اجرای ذخیره‌سازی
$result = users_complete_save($_POST);

if (($result['ok'] ?? false) === true) {
    audit_log('users.save', 'user', (int)$result['id']);
}
json_out($result);
