<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_post();
csrf_require_valid();

if (!is_ajax()) json_out(['ok' => false, 'message' => 'Bad request'], 400);

try {
    $phone = preg_replace('/\D+/', '', (string)($_POST['phone'] ?? ''));
    if (!preg_match('/^09\d{9}$/', $phone)) {
        json_out(['ok' => false, 'message' => 'شماره موبایل نامعتبر است'], 422);
    }

    $pdo = db();
    $st = $pdo->prepare("SELECT id FROM users WHERE phone_active=? AND user_type=3 AND status=1 AND deleted_at IS NULL LIMIT 1");
    $st->execute([$phone]);
    $uid = (int)($st->fetchColumn() ?: 0);

    // پاسخ برای شماره موجود و ناموجود یکسان است تا فهرست ادمین‌ها افشا نشود.
    if ($uid > 0) {
        $issued = otp_issue($phone, 3, 300, 60);
        if (!$issued['ok']) {
            error_log('Admin password reset OTP was not issued: status=' . (int)($issued['status'] ?? 500));
        }
    }

    json_out([
        'ok' => true,
        'message' => 'اگر این شماره متعلق به مدیر فعال باشد، کد تایید ارسال می‌شود',
        'expires_in_sec' => 300,
        'resend_in_sec' => 60,
    ]);
} catch (Throwable $e) {
    error_log('admin.password reset otp failed: ' . $e->getMessage());
    json_out(['ok' => false, 'message' => 'درخواست انجام نشد. لطفاً دوباره تلاش کنید.'], 500);
}
