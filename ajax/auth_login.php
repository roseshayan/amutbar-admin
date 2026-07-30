<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_post();
csrf_require_valid();

if (!is_ajax()) {
    json_out(['ok' => false, 'message' => 'Bad request'], 400);
}

$phone = preg_replace('/\D+/', '', (string)($_POST['phone'] ?? ''));
$pass = (string)($_POST['password'] ?? '');
$remember = isset($_POST['remember']) && $_POST['remember'] == '1';

if ($phone === '' || $pass === '') {
    json_out(['ok' => false, 'message' => 'شماره موبایل و رمز عبور الزامی است'], 422);
}

if (!preg_match('/^09\d{9}$/', $phone)) {
    json_out(['ok' => false, 'message' => 'فرمت شماره موبایل نامعتبر است'], 422);
}

if (!admin_login($phone, $pass, $remember)) {
    json_out(['ok' => false, 'message' => 'اطلاعات ورود صحیح نیست یا دسترسی ندارید'], 401);
}

json_out(['ok' => true, 'redirect' => 'dashboard.php']);
