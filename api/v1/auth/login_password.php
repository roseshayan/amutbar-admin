<?php

declare(strict_types=1);

require_once __DIR__ . '/../_bootstrap.php';
require_post();

$phone = preg_replace('/\D+/', '', (string)($_POST['phone'] ?? ''));
$password = (string)($_POST['password'] ?? '');
$device_id = trim((string)($_POST['device_id'] ?? ''));
$platform = api_platform_from_string($_POST['platform'] ?? null);

if (!preg_match('/^09\d{9}$/', $phone)) api_err('شماره موبایل نامعتبر است', 422);
if ($password === '') api_err('رمز عبور الزامی است', 422);

$pdo = db();
$st = $pdo->prepare("SELECT id, password_hash, user_type, status, full_name, phone, email, display_name, avatar_key FROM users WHERE phone_active = ? LIMIT 1");
$st->execute([$phone]);
$u = $st->fetch();

if (!$u) api_err('اطلاعات ورود نادرست است', 401);
if ((int)$u['status'] !== 1) api_err('حساب کاربری فعال نیست', 403);
if (empty($u['password_hash']) || !password_verify($password, (string)$u['password_hash'])) {
    api_err('اطلاعات ورود نادرست است', 401);
}

$tok = api_issue_token((int)$u['id'], $device_id !== '' ? $device_id : null, $platform);
api_ok([
    'token' => $tok,
    'user' => [
        'id' => (int)$u['id'],
        'full_name' => (string)$u['full_name'],
        'display_name' => $u['display_name'] !== null ? (string)$u['display_name'] : null,
        'avatar_key' => $u['avatar_key'] !== null ? (string)$u['avatar_key'] : null,
        'phone' => (string)$u['phone'],
        'email' => $u['email'] !== null ? (string)$u['email'] : null,
        'user_type' => (int)$u['user_type'],
        'status' => (int)$u['status'],
    ]
]);
