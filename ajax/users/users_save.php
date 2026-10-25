<?php

declare(strict_types=1);

require_once __DIR__ . '/../../includes/init.php';
require_admin();
require_post();

// --- بررسی سطوح دسترسی و حفاظت از حساب‌های مدیریتی ---
$targetId = (int)($_POST['id'] ?? 0);
$targetUserType = (int)($_POST['user_type'] ?? 0);

if ($targetId > 0) {
    $stCheck = db()->prepare("SELECT id, user_type, admin_role, status, code_meli, national_card_serial FROM users WHERE id = ? LIMIT 1");
    $stCheck->execute([$targetId]);
    $existingUser = $stCheck->fetch(PDO::FETCH_ASSOC);

    if (!$existingUser) {
        json_out(['ok' => false, 'message' => 'کاربر مورد نظر یافت نشد'], 404);
    }

    $isTargetAdmin = ((int)$existingUser['user_type'] === 3 || in_array($existingUser['admin_role'] ?? '', ['super_admin', 'staff'], true));

    // کارمند به هیچ وجه اجازه تغییر اطلاعات مدیران یا کارمندان دیگر را ندارد
    if (is_staff() && $isTargetAdmin) {
        json_out(['ok' => false, 'message' => 'دسترسی غیرمجاز. کارمندان اجازه ویرایش اطلاعات مدیران و کارمندان را ندارند.'], 403);
    }

    // کارمند نمی‌تواند نقش کاربر را به ادمین یا کارمند تغییر دهد
    if (is_staff() && ($targetUserType === 3 || !empty($_POST['admin_role']))) {
        json_out(['ok' => false, 'message' => 'شما مجاز به تعیین یا تغییر سطح دسترسی مدیریتی نیستید.'], 403);
    }

    // بررسی پرمیشن‌های جزئی کارمند
    if (is_staff()) {
        $uType = (int)$existingUser['user_type'];

        if ($uType === 1) {
            if (!has_permission('drivers.edit')) {
                json_out(['ok' => false, 'message' => 'شما مجوز ویرایش اطلاعات رانندگان را ندارید.'], 403);
            }
            if (isset($_POST['status']) && (int)$_POST['status'] !== (int)$existingUser['status'] && !has_permission('drivers.status')) {
                // حفظ وضعیت قبلی در صورت عدم دسترسی به تغییر وضعیت
                $_POST['status'] = $existingUser['status'];
            }
            if (!has_permission('drivers.edit_identity')) {
                // اگر مجوز ویرایش مدارک هویتی ندارد، مقادیر قبلی حفظ شوند
                $_POST['code_meli'] = $existingUser['code_meli'];
                $_POST['national_card_serial'] = $existingUser['national_card_serial'];
            }
        } elseif ($uType === 2) {
            if (!has_permission('cargos_users.edit')) {
                json_out(['ok' => false, 'message' => 'شما مجوز ویرایش اطلاعات صاحبان بار را ندارید.'], 403);
            }
            if (isset($_POST['status']) && (int)$_POST['status'] !== (int)$existingUser['status'] && !has_permission('cargos_users.status')) {
                $_POST['status'] = $existingUser['status'];
            }
        }
    }
} else {
    // ایجاد کاربر جدید
    if ($targetUserType === 3 || !empty($_POST['admin_role'])) {
        if (!is_super_admin()) {
            json_out(['ok' => false, 'message' => 'فقط مدیر کل می‌تواند کاربر ادمین یا کارمند جدید تعریف کند.'], 403);
        }
    }

    if (is_staff()) {
        if ($targetUserType === 1 && !has_permission('drivers.create')) {
            json_out(['ok' => false, 'message' => 'شما مجوز افزودن راننده جدید را ندارید.'], 403);
        }
        if ($targetUserType === 2 && !has_permission('cargos_users.create')) {
            json_out(['ok' => false, 'message' => 'شما مجوز افزودن صاحب بار جدید را ندارید.'], 403);
        }
    }
}

// --- شروع بخش تبدیل اعداد فارسی/عربی به انگلیسی ---
$persian_digits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
$arabic_digits  = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
$english_digits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

if (!empty($_POST['birth_date'])) {
    $temp_date = str_replace($persian_digits, $english_digits, $_POST['birth_date']);
    $_POST['birth_date'] = str_replace($arabic_digits, $english_digits, $temp_date);
}

if (!empty($_POST['insurance_expiry'])) {
    $temp_date = str_replace($persian_digits, $english_digits, $_POST['insurance_expiry']);
    $_POST['insurance_expiry'] = str_replace($arabic_digits, $english_digits, $temp_date);
}

if (!empty($_POST['registration_date'])) {
    $temp_date = str_replace($persian_digits, $english_digits, $_POST['registration_date']);
    $_POST['registration_date'] = str_replace($arabic_digits, $english_digits, $temp_date);
}

// اجرای ذخیره‌سازی
$result = users_complete_save($_POST);

if (($result['ok'] ?? false) === true) {
    audit_log('users.save', 'user', (int)$result['id']);
}
json_out($result);
