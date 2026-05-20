<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_admin();

header('Content-Type: application/json; charset=utf-8');
$action = $_GET['action'] ?? ($_POST['action'] ?? '');
$pdo = db();

if ($action === 'list') {
    // باید تابع jdate را داشته باشیم
    require_once __DIR__ . '/../includes/jdf.php';

    $st = $pdo->query("
        SELECT t.*, u.full_name, u.phone 
        FROM support_tickets t 
        JOIN users u ON t.user_id = u.id 
        ORDER BY CASE WHEN t.status = 1 THEN 0 ELSE 1 END, t.updated_at DESC
    ");
    $rows = $st->fetchAll();

    $data = [];
    foreach ($rows as $r) {
        $status = match ((int)$r['status']) {
            1 => '<span class="badge bg-warning">در انتظار پاسخ</span>',
            2 => '<span class="badge bg-success">پاسخ داده شده</span>',
            3 => '<span class="badge bg-secondary">بسته شده</span>',
            default => 'نامشخص'
        };

        $data[] = [
            'id' => $r['id'],
            'user' => htmlspecialchars($r['full_name']) . '<br><small dir="ltr">' . $r['phone'] . '</small>',
            'subject' => htmlspecialchars($r['subject']),
            'status' => $status,
            // تاریخ شمسی شد
            'updated_at' => jdate('Y/m/d H:i', strtotime($r['updated_at'])),
            'actions' => '<a href="support_ticket_view.php?id=' . $r['id'] . '" class="btn btn-sm btn-primary">مشاهده / پاسخ</a>'
        ];
    }
    echo json_encode(['data' => $data], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($action === 'reply') {
    $ticketId = (int)($_POST['ticket_id'] ?? 0);
    $message = trim($_POST['message'] ?? '');
    $adminId = admin_id();

    // الزام به داشتن حداقل یک پیام یا فایل
    $hasFile = !empty($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK;
    if (!$ticketId || (!$message && !$hasFile)) {
        echo json_encode(['ok' => false, 'message' => 'متن پیام یا فایل الزامی است']);
        exit;
    }

    $messageType = 1;        // متن ساده
    $attachmentKey = null;
    $attachmentName = null;

    // پردازش فایل در صورت وجود
    if ($hasFile) {
        $file = $_FILES['attachment'];
        $allowedImages = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $allowedFiles = ['application/pdf'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if ($file['size'] > $maxSize) {
            echo json_encode(['ok' => false, 'message' => 'حجم فایل نباید بیشتر از ۵ مگابایت باشد']);
            exit;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (in_array($mime, $allowedImages)) {
            $messageType = 2; // image
            $ext = 'jpg';
            if ($mime === 'image/png') $ext = 'png';
            elseif ($mime === 'image/gif') $ext = 'gif';
            elseif ($mime === 'image/webp') $ext = 'webp';
        } elseif (in_array($mime, $allowedFiles)) {
            $messageType = 3; // pdf
            $ext = 'pdf';
        } else {
            echo json_encode(['ok' => false, 'message' => 'فرمت فایل مجاز نیست (تنها تصاویر و PDF)']);
            exit;
        }

        // ایجاد پوشه در صورت عدم وجود
        $uploadDir = __DIR__ . '/../storage/uploads/tickets';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0775, true);
        }

        // نام یکتا
        $filename = 'ticket_' . $ticketId . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $dest = $uploadDir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            echo json_encode(['ok' => false, 'message' => 'خطا در ذخیره فایل']);
            exit;
        }

        $attachmentKey = 'uploads/tickets/' . $filename;  // مسیر نسبی
        $attachmentName = $file['name'];

        // اگر پیام خالی بود، یک پیش‌فرض بگذاریم
        if ($message === '') {
            $message = ($messageType === 2) ? 'تصویر ارسال شد' : 'فایل PDF ارسال شد';
        }
    }

    $pdo->beginTransaction();
    try {
        $stUser = $pdo->prepare("SELECT user_id FROM support_tickets WHERE id=?");
        $stUser->execute([$ticketId]);
        $targetUserId = $stUser->fetchColumn();

        $pdo->prepare("INSERT INTO support_ticket_messages 
            (ticket_id, sender_user_id, message, message_type, attachment_key, attachment_name, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, NOW(3))")
            ->execute([$ticketId, $adminId, $message, $messageType, $attachmentKey, $attachmentName]);

        $pdo->prepare("UPDATE support_tickets SET status=2, updated_at=NOW(3) WHERE id=?")
            ->execute([$ticketId]);

        // ثبت نوتیفیکیشن (با دیتای مشابه قبل)
        if ($targetUserId) {
            $dataJson = json_encode(['ticket_id' => $ticketId], JSON_UNESCAPED_UNICODE);
            $pdo->prepare("INSERT INTO notifications (user_id, type, title, body, data_json, created_at) 
                VALUES (?, 'ticket_reply', 'پاسخ به تیکت', 'پشتیبانی به تیکت شما پاسخ داد.', ?, NOW(3))")
                ->execute([$targetUserId, $dataJson]);
        }

        $pdo->commit();
        echo json_encode(['ok' => true, 'file_key' => $attachmentKey]);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['ok' => false, 'message' => 'خطا در ثبت']);
    }
    exit;
}

if ($action === 'close') {
    $ticketId = (int)($_POST['ticket_id'] ?? 0);
    $pdo->prepare("UPDATE support_tickets SET status=3, updated_at=NOW(3) WHERE id=?")->execute([$ticketId]);
    echo json_encode(['ok' => true]);
    exit;
}
