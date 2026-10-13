<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/push_service.php';
require_once __DIR__ . '/../includes/jdf.php';
require_admin();

header('Content-Type: application/json; charset=utf-8');

$pdo = db();
$action = (string)($_GET['action'] ?? $_POST['action'] ?? 'list');

if ($action === 'list') {
    $st = $pdo->query("
        SELECT l.*, a.username AS admin_username, u.phone AS target_phone, u.full_name AS target_name
        FROM push_broadcast_logs l
        LEFT JOIN admins a ON l.admin_id = a.id
        LEFT JOIN users u ON l.target_user_id = u.id
        ORDER BY l.id DESC
        LIMIT 200
    ");
    $rows = $st->fetchAll();

    $data = [];
    foreach ($rows as $r) {
        $audBadge = match ($r['target_audience']) {
            'drivers' => '<span class="badge bg-primary-transparent">رانندگان</span>',
            'cargo'   => '<span class="badge bg-success-transparent">صاحبان بار</span>',
            'user'    => '<span class="badge bg-info-transparent">کاربر: ' . htmlspecialchars($r['target_phone'] ?: (string)$r['target_user_id']) . '</span>',
            default   => '<span class="badge bg-purple-transparent">همه کاربران</span>'
        };

        $actionBadge = match ($r['action_type']) {
            'ticket_reply' => '<span class="badge bg-warning">پاسخ تیکت</span>',
            'load'         => '<span class="badge bg-info">بار جدید</span>',
            'url'          => '<span class="badge bg-secondary">لینک</span>',
            default        => '<span class="badge bg-light text-dark">عمومی</span>'
        };

        $shamsiDate = jdate('Y/m/d H:i', strtotime($r['created_at']));

        $data[] = [
            'id' => (int)$r['id'],
            'title' => '<strong>' . htmlspecialchars($r['title']) . '</strong>',
            'body' => htmlspecialchars(mb_strimwidth($r['body'], 0, 80, '...')),
            'audience' => $audBadge,
            'type' => $actionBadge,
            'recipients_count' => '<span class="fw-bold">' . number_format((int)$r['sent_count']) . '</span> کاربر',
            'push_status' => '<span class="badge bg-success">موفق: ' . (int)$r['success_count'] . '</span> ' . ((int)$r['failed_count'] > 0 ? '<span class="badge bg-danger">ناموفق: ' . (int)$r['failed_count'] . '</span>' : ''),
            'sender' => htmlspecialchars($r['admin_username'] ?: 'سیستم'),
            'date' => $shamsiDate,
        ];
    }

    echo json_encode(['data' => $data], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($action === 'send') {
    require_post();
    $audience = trim((string)($_POST['audience'] ?? 'all'));
    $title = trim((string)($_POST['title'] ?? ''));
    $body = trim((string)($_POST['body'] ?? ''));
    $actionType = trim((string)($_POST['action_type'] ?? 'general'));
    $actionData = trim((string)($_POST['action_data'] ?? ''));
    $targetUserId = (int)($_POST['target_user_id'] ?? 0);

    if ($title === '' || $body === '') {
        json_out(['ok' => false, 'message' => 'عنوان و متن نوتیفیکیشن الزامی است'], 422);
    }

    if (!in_array($audience, ['all', 'drivers', 'cargo', 'user'], true)) {
        json_out(['ok' => false, 'message' => 'مخاطب هدف نامعتبر است'], 422);
    }

    if ($audience === 'user' && $targetUserId <= 0) {
        json_out(['ok' => false, 'message' => 'لطفاً کاربر مورد نظر را انتخاب کنید'], 422);
    }

    $adminId = admin_id();

    $res = push_broadcast(
        $audience,
        $title,
        $body,
        $actionType,
        $actionData !== '' ? $actionData : null,
        $targetUserId > 0 ? $targetUserId : null,
        $adminId
    );

    if ($res['ok']) {
        audit_log('push.send', 'push_broadcast', 0, [
            'audience' => $audience,
            'title' => $title,
            'users' => $res['users_count'] ?? 0,
            'tokens' => $res['tokens_count'] ?? 0,
        ]);
        json_out([
            'ok' => true,
            'message' => 'اعلان با موفقیت برای ' . ($res['users_count'] ?? 0) . ' کاربر ثبت و ارسال شد.',
            'details' => $res
        ]);
    }

    json_out(['ok' => false, 'message' => $res['message'] ?? 'خطا در ارسال'], 500);
}

if ($action === 'users_search') {
    $q = trim((string)($_GET['q'] ?? ''));
    if (mb_strlen($q) < 2) {
        echo json_encode(['items' => []]);
        exit;
    }

    $st = $pdo->prepare("
        SELECT id, phone, full_name, user_type 
        FROM users 
        WHERE (phone LIKE ? OR full_name LIKE ?) AND deleted_at IS NULL
        LIMIT 20
    ");
    $st->execute(['%' . $q . '%', '%' . $q . '%']);
    $rows = $st->fetchAll();

    $items = array_map(function ($r) {
        $role = (int)$r['user_type'] === 1 ? 'راننده' : 'صاحب‌بار';
        $name = !empty($r['full_name']) ? $r['full_name'] : 'بی‌نام';
        return [
            'id' => (int)$r['id'],
            'text' => $name . ' (' . $r['phone'] . ') - ' . $role,
        ];
    }, $rows ?: []);

    echo json_encode(['items' => $items], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($action === 'get_settings') {
    $settings = push_get_settings();
    $stTokens = $pdo->query("SELECT COUNT(*) AS total_tokens, COUNT(DISTINCT user_id) AS total_users FROM user_push_tokens");
    $stats = $stTokens->fetch() ?: ['total_tokens' => 0, 'total_users' => 0];

    json_out([
        'ok' => true,
        'settings' => $settings,
        'stats' => [
            'active_devices' => (int)$stats['total_tokens'],
            'users_with_device' => (int)$stats['total_users'],
        ]
    ]);
}

if ($action === 'save_settings') {
    require_post();
    $serverKey = trim((string)($_POST['server_key'] ?? ''));
    $channelId = trim((string)($_POST['channel_id'] ?? 'amutbar_high_importance'));

    $adminId = admin_id();
    settings_set('fcm.server_key', $serverKey, $adminId);
    settings_set('fcm.channel_id', $channelId !== '' ? $channelId : 'amutbar_high_importance', $adminId);

    audit_log('push.settings', 'fcm_settings_update', 0);
    json_out(['ok' => true, 'message' => 'تنظیمات سرویس نوتیفیکیشن با موفقیت ذخیره شد.']);
}

json_out(['ok' => false, 'message' => 'action نامعتبر'], 400);
