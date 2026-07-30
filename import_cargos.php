<?php

declare(strict_types=1);

// فراخوانی توابع پایه سیستم (مسیر را بر اساس محل قرارگیری فایل تنظیم کن)
require_once __DIR__ . '/includes/init.php';

// برای امنیت، بهتر است فقط ادمین بتواند این فایل را اجرا کند
require_admin();

$pdo = db();
$txtFile = __DIR__ . '/cargos.txt';

echo "<html dir='rtl'><body style='font-family: Tahoma, sans-serif; padding: 20px;'>";

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    $csrf = htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8');
    echo "<h2>واردسازی فهرست کالاها</h2>";
    echo "<p>این عملیات محتوای فایل cargos.txt را به پایگاه داده اضافه می‌کند.</p>";
    echo "<form method='post'>";
    echo "<input type='hidden' name='_csrf_token' value='{$csrf}'>";
    echo "<button type='submit' style='padding:10px 15px'>شروع واردسازی</button>";
    echo "</form></body></html>";
    exit;
}

csrf_require_valid();

if (!file_exists($txtFile)) {
    die("<h3 style='color: red;'>خطا: فایل cargos.txt پیدا نشد! لطفا فایل را در کنار این اسکریپت قرار دهید.</h3></body></html>");
}

// خواندن فایل (نادیده گرفتن خطوط خالی و حذف فاصله‌های اضافی در ابتدا و انتهای خط)
$lines = file($txtFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

if (empty($lines)) {
    die("<h3 style='color: orange;'>فایل cargos.txt خالی است.</h3></body></html>");
}

$inserted = 0;
$skipped = 0;

try {
    $pdo->beginTransaction();

    $stCheck = $pdo->prepare("SELECT id FROM cargos_list WHERE title = ? LIMIT 1");
    $stInsert = $pdo->prepare("INSERT INTO cargos_list (title) VALUES (?)");

    foreach ($lines as $line) {
        $title = trim($line);
        if ($title === '') continue;

        // بررسی تکراری نبودن کالا در دیتابیس
        $stCheck->execute([$title]);
        if ($stCheck->fetchColumn()) {
            $skipped++;
            continue; // اگر بود، رد شو
        }

        // درج در دیتابیس
        $stInsert->execute([$title]);
        $inserted++;
    }

    $pdo->commit();

    echo "<h2 style='color: green;'>واردسازی با موفقیت انجام شد! 🎉</h2>";
    echo "<ul>";
    echo "<li><strong>تعداد کل خطوط خوانده شده:</strong> " . count($lines) . "</li>";
    echo "<li><strong style='color: green;'>تعداد ثبت شده جدید:</strong> $inserted</li>";
    echo "<li><strong style='color: orange;'>تعداد تکراری (رد شده):</strong> $skipped</li>";
    echo "</ul>";
    echo "<a href='dashboard.php' style='display: inline-block; padding: 10px 15px; background: #007bff; color: #fff; text-decoration: none; border-radius: 5px;'>بازگشت به داشبورد</a>";
} catch (Throwable $e) {
    $pdo->rollBack();
    echo "<h3 style='color: red;'>خطا در ثبت اطلاعات در دیتابیس:</h3>";
    $message = ((string)env('APP_DEBUG', '0') === '1')
        ? htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')
        : 'عملیات واردسازی ناموفق بود.';
    echo "<p>{$message}</p>";
}

echo "</body></html>";
