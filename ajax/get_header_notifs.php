<?php

declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';
require_admin();

$pdo = db();
// گرفتن تعداد تیکت‌های باز (در انتظار پاسخ ادمین)
$st = $pdo->query("SELECT COUNT(*) FROM support_tickets WHERE status = 1");
$openTicketsCount = (int)$st->fetchColumn();

echo json_encode([
    'ok' => true,
    'open_tickets' => $openTicketsCount
]);
