<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';

$phone = $argv[1] ?? '09123456789';
$password = $argv[2] ?? 'admin123456';
$fullName = $argv[3] ?? 'مدیر ارشد سامانه';

$phone = preg_replace('/\D+/', '', $phone);
if (!preg_match('/^09\d{9}$/', $phone)) {
    echo "خطا: شماره موبایل باید با فرمت 09xxxxxxxxx باشد." . PHP_EOL;
    echo "نحوه استفاده: php scripts/create_admin.php <شماره_موبایل> <رمز_عبور> [نام]" . PHP_EOL;
    exit(1);
}

if (strlen($password) < 6) {
    echo "خطا: رمز عبور باید حداقل ۶ کاراکتر باشد." . PHP_EOL;
    exit(1);
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$pdo = db();

$st = $pdo->prepare("SELECT id, user_type FROM users WHERE phone_active = ? LIMIT 1");
$st->execute([$phone]);
$existing = $st->fetch();

if ($existing) {
    $pdo->prepare("
        UPDATE users 
        SET password_hash = ?, 
            user_type = 3, 
            status = 1, 
            failed_login_attempts = 0, 
            locked_until = NULL, 
            updated_at = NOW(3) 
        WHERE id = ?
    ")->execute([$hash, (int)$existing['id']]);

    echo "======================================================" . PHP_EOL;
    echo " اکانت ادمین بروزرسانی شد!" . PHP_EOL;
    echo " شماره موبایل: {$phone}" . PHP_EOL;
    echo " رمز عبور: {$password}" . PHP_EOL;
    echo " نقش: مدیر کل (user_type = 3)" . PHP_EOL;
    echo "======================================================" . PHP_EOL;
} else {
    $pdo->prepare("
        INSERT INTO users (
            full_name, phone, password_hash, 
            user_type, status, created_at, updated_at
        ) VALUES (?, ?, ?, 3, 1, NOW(3), NOW(3))
    ")->execute([$fullName, $phone, $hash]);

    echo "======================================================" . PHP_EOL;
    echo " اکانت ادمین جدید ساخته شد!" . PHP_EOL;
    echo " شماره موبایل: {$phone}" . PHP_EOL;
    echo " رمز عبور: {$password}" . PHP_EOL;
    echo " نقش: مدیر کل (user_type = 3)" . PHP_EOL;
    echo "======================================================" . PHP_EOL;
}
