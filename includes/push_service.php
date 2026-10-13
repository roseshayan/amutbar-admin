<?php

declare(strict_types=1);

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/settings.php';

/**
 * دریافت تنظیمات مربوط به FCM / سرویس پوش نوتیفیکیشن
 */
function push_get_settings(): array
{
    $serverKey = (string)settings_get('fcm.server_key', '');
    if ($serverKey === '') {
        $serverKey = (string)(env('FCM_SERVER_KEY', '') ?? '');
    }

    $channelId = (string)settings_get('fcm.channel_id', 'amutbar_high_importance');
    if ($channelId === '') $channelId = 'amutbar_high_importance';

    return [
        'server_key' => trim($serverKey),
        'channel_id' => trim($channelId),
    ];
}

/**
 * ثبت یا به‌روزرسانی توکن نوتیفیکیشن دستگاه کاربر
 */
function push_register_device_token(int $userId, string $token, string $platform = 'android', int $appType = 1): bool
{
    $token = trim($token);
    if ($userId <= 0 || $token === '') {
        return false;
    }

    $platform = in_array(strtolower($platform), ['ios', 'android', 'web'], true) ? strtolower($platform) : 'android';
    if (!in_array($appType, [1, 2], true)) {
        $appType = 1;
    }

    $pdo = db();
    $sql = "INSERT INTO user_push_tokens (user_id, token, platform, app_type, created_at, updated_at)
            VALUES (?, ?, ?, ?, NOW(3), NOW(3))
            ON DUPLICATE KEY UPDATE platform=VALUES(platform), app_type=VALUES(app_type), updated_at=NOW(3)";
    $st = $pdo->prepare($sql);
    return $st->execute([$userId, $token, $platform, $appType]);
}

/**
 * حذف توکن پوش دستگاه (مثلاً هنگام خروج کاربر)
 */
function push_unregister_device_token(int $userId, ?string $token = null): bool
{
    if ($userId <= 0) return false;
    $pdo = db();
    if ($token !== null && trim($token) !== '') {
        $st = $pdo->prepare("DELETE FROM user_push_tokens WHERE user_id=? AND token=?");
        return $st->execute([$userId, trim($token)]);
    }
    $st = $pdo->prepare("DELETE FROM user_push_tokens WHERE user_id=?");
    return $st->execute([$userId]);
}

/**
 * ارسال مستقیم پیام پوش به دسته‌ای از توکن‌ها از طریق FCM
 */
function push_send_fcm(array $tokens, string $title, string $body, array $data = []): array
{
    $tokens = array_values(array_unique(array_filter(array_map('trim', $tokens))));
    if (empty($tokens)) {
        return ['success' => 0, 'failed' => 0, 'errors' => ['no_tokens']];
    }

    $cfg = push_get_settings();
    $serverKey = $cfg['server_key'];

    if ($serverKey === '') {
        return [
            'success' => 0,
            'failed' => count($tokens),
            'errors' => ['fcm_server_key_not_configured'],
            'note' => 'کلید سرور Firebase (FCM) در تنظیمات ثبت نشده است اما پیام‌ها در صندوق اعلان برنامه ذخیره شدند.'
        ];
    }

    $channelId = $cfg['channel_id'];

    $dataPayload = array_merge([
        'title' => $title,
        'body'  => $body,
        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        'sound' => 'default',
        'channel_id' => $channelId,
    ], array_map('strval', $data));

    $notificationPayload = [
        'title' => $title,
        'body'  => $body,
        'sound' => 'default',
        'android_channel_id' => $channelId,
        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
    ];

    $totalSuccess = 0;
    $totalFailed = 0;
    $invalidTokens = [];

    // ارسال دسته‌ای (FCM Legacy حداکثر ۱۰۰۰ توکن در هر درخواست می‌پذیرد)
    $chunks = array_chunk($tokens, 500);

    foreach ($chunks as $chunk) {
        $fields = [
            'registration_ids' => $chunk,
            'notification'     => $notificationPayload,
            'data'             => $dataPayload,
            'priority'         => 'high',
            'content_available' => true,
        ];

        $headers = [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json; charset=utf-8'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields, JSON_UNESCAPED_UNICODE));
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && $result) {
            $response = json_decode($result, true);
            $success = (int)($response['success'] ?? 0);
            $failure = (int)($response['failure'] ?? 0);
            $totalSuccess += $success;
            $totalFailed  += $failure;

            // شناسایی توکن‌های منقضی شده جهت پاکسازی از دیتابیس
            if (!empty($response['results']) && is_array($response['results'])) {
                foreach ($response['results'] as $idx => $resItem) {
                    $err = $resItem['error'] ?? null;
                    if ($err === 'NotRegistered' || $err === 'InvalidRegistration') {
                        if (isset($chunk[$idx])) {
                            $invalidTokens[] = $chunk[$idx];
                        }
                    }
                }
            }
        } else {
            $totalFailed += count($chunk);
        }
    }

    // پاکسازی توکن‌های باطله
    if (!empty($invalidTokens)) {
        try {
            $pdo = db();
            $inClause = implode(',', array_fill(0, count($invalidTokens), '?'));
            $st = $pdo->prepare("DELETE FROM user_push_tokens WHERE token IN ($inClause)");
            $st->execute($invalidTokens);
        } catch (Throwable $e) {
            // نادیده گرفتن خطای پاکسازی توکن‌های غیرفعال
        }
    }

    return [
        'success' => $totalSuccess,
        'failed'  => $totalFailed,
        'invalid_cleaned' => count($invalidTokens)
    ];
}

/**
 * ارسال اعلان همگانی یا گروهی از پنل مدیریت
 * 1. در جدول notifications ذخیره می‌کند تا در اپ داخل لیست اعلان‌ها بیاید.
 * 2. توکن‌های دستگاه‌های متصل را پیدا کرده و با push_send_fcm پیام صادر می‌کند.
 * 3. نتیجه را در جدول push_broadcast_logs ثبت می‌کند.
 */
function push_broadcast(
    string $audience,
    string $title,
    string $body,
    string $actionType = 'general',
    ?string $actionData = null,
    ?int $targetUserId = null,
    ?int $adminId = null
): array {
    $pdo = db();
    $title = trim($title);
    $body = trim($body);

    if ($title === '' || $body === '') {
        return ['ok' => false, 'message' => 'عنوان و متن اعلان الزامی است'];
    }

    // ۱. دریافت کاربران هدف
    $userQuery = "SELECT id, user_type FROM users WHERE deleted_at IS NULL AND status=1";
    $params = [];

    if ($audience === 'drivers') {
        $userQuery .= " AND user_type = 1";
    } elseif ($audience === 'cargo') {
        $userQuery .= " AND user_type = 2";
    } elseif ($audience === 'user') {
        if (!$targetUserId || $targetUserId <= 0) {
            return ['ok' => false, 'message' => 'کاربر هدف مشخص نشده است'];
        }
        $userQuery .= " AND id = ?";
        $params[] = $targetUserId;
    } // 'all' نیازی به فیلتر اضافه ندارد

    $stUsers = $pdo->prepare($userQuery);
    $stUsers->execute($params);
    $targetUsers = $stUsers->fetchAll();

    if (empty($targetUsers)) {
        return ['ok' => false, 'message' => 'هیچ کاربر فعالی با این معیارها یافت نشد'];
    }

    $targetUserIds = array_map(fn($u) => (int)$u['id'], $targetUsers);

    // ۲. ذخیره در جدول notifications (صندوق پیام درون‌برنامه‌ای)
    $payloadData = [
        'action_type' => $actionType,
        'action_data' => $actionData,
        'broadcast'   => true,
        'created_at'  => date('Y-m-d H:i:s'),
    ];
    $jsonPayload = json_encode($payloadData, JSON_UNESCAPED_UNICODE);

    try {
        $pdo->beginTransaction();
        $stInsertNotif = $pdo->prepare("
            INSERT INTO notifications (user_id, type, title, body, data_json, is_read, created_at)
            VALUES (?, ?, ?, ?, ?, 0, NOW(3))
        ");

        foreach ($targetUserIds as $uid) {
            $stInsertNotif->execute([
                $uid,
                $actionType,
                $title,
                $body,
                $jsonPayload
            ]);
        }
        $pdo->commit();
    } catch (Throwable $e) {
        $pdo->rollBack();
        return ['ok' => false, 'message' => 'خطا در ذخیره‌سازی پیام در دیتابیس: ' . $e->getMessage()];
    }

    // ۳. جمع‌آوری توکن‌های فعال برای ارسال Push
    $tokenQuery = "SELECT token FROM user_push_tokens WHERE user_id IN (" . implode(',', $targetUserIds) . ")";
    $tokens = $pdo->query($tokenQuery)->fetchAll(PDO::FETCH_COLUMN) ?: [];

    // ۴. ارسال فیزیکی به گوشی‌ها
    $pushResult = ['success' => 0, 'failed' => 0];
    if (!empty($tokens)) {
        $pushResult = push_send_fcm($tokens, $title, $body, [
            'action_type' => $actionType,
            'action_data' => $actionData ?? '',
            'notif_id'    => 'broadcast_' . time(),
        ]);
    }

    // ۵. ثبت لاگ ارسال در جدول push_broadcast_logs
    try {
        $stLog = $pdo->prepare("
            INSERT INTO push_broadcast_logs 
            (admin_id, target_audience, target_user_id, title, body, action_type, action_data, sent_count, success_count, failed_count, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(3))
        ");
        $stLog->execute([
            $adminId,
            $audience,
            $targetUserId,
            $title,
            $body,
            $actionType,
            $actionData,
            count($targetUserIds),
            (int)($pushResult['success'] ?? 0),
            (int)($pushResult['failed'] ?? 0),
        ]);
    } catch (Throwable $e) {
        error_log('push_broadcast_log error: ' . $e->getMessage());
    }

    return [
        'ok' => true,
        'users_count'  => count($targetUserIds),
        'tokens_count' => count($tokens),
        'success'      => $pushResult['success'] ?? 0,
        'failed'       => $pushResult['failed'] ?? 0,
        'note'         => $pushResult['note'] ?? null,
    ];
}
