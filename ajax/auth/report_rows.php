<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/init.php';
require_admin();
require_once __DIR__ . '/../../includes/jalali.php';

// DataTables server-side endpoint for auth_daily_stats (بهینه + شمسی)

$pdo = db();

$draw = (int)($_GET['draw'] ?? 0);
$start = max(0, (int)($_GET['start'] ?? 0));
$length = (int)($_GET['length'] ?? 25);
if ($length <= 0 || $length > 200) $length = 25;

$search = trim((string)($_GET['search']['value'] ?? ''));

// Filters (Jalali in UI)
$todayG = date('Y-m-d');
$defaultFromG = date('Y-m-d', strtotime('-30 days'));

$fromJ = (string)($_GET['from'] ?? gdate_to_jalali_str($defaultFromG));
$toJ = (string)($_GET['to'] ?? gdate_to_jalali_str($todayG));

$fromG = jdate_str_to_gdate($fromJ) ?? $defaultFromG;
$toG = jdate_str_to_gdate($toJ) ?? $todayG;

if (strtotime($fromG) > strtotime($toG)) {
    [$fromG, $toG] = [$toG, $fromG];
}

$userType = (int)($_GET['user_type'] ?? 0);
$eventType = (int)($_GET['event_type'] ?? 0);
if (!in_array($userType, [0, 1, 2, 3], true)) $userType = 0;
if (!in_array($eventType, [0, 1, 2, 3, 4, 5], true)) $eventType = 0;

// WHERE (نام‌دار، برای جلوگیری از خطای mix placeholders)
$where = ' WHERE stat_date BETWEEN :from AND :to ';
$params = [':from' => $fromG, ':to' => $toG];

if ($userType !== 0) {
    $where .= ' AND user_type = :user_type ';
    $params[':user_type'] = $userType;
}
if ($eventType !== 0) {
    $where .= ' AND event_type = :event_type ';
    $params[':event_type'] = $eventType;
}

// Optional search (on date). Accept both Gregorian and Jalali patterns.
if ($search !== '') {
    $sg = $search;
    $sjToG = jdate_str_to_gdate($search);
    if ($sjToG) $sg = $sjToG;
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $sg)) {
        $where .= ' AND stat_date = :sdate ';
        $params[':sdate'] = $sg;
    }
}

// Ordering
$orderCol = (int)($_GET['order'][0]['column'] ?? 0);
$orderDir = strtolower((string)($_GET['order'][0]['dir'] ?? 'desc')) === 'asc' ? 'ASC' : 'DESC';
$orderBy = 'stat_date';
if ($orderCol === 1) $orderBy = 'user_type';
if ($orderCol === 2) $orderBy = 'event_type';
if ($orderCol === 3) $orderBy = 'cnt';

// recordsFiltered
$stCount = $pdo->prepare("SELECT COUNT(*) FROM auth_daily_stats {$where}");
$stCount->execute($params);
$recordsFiltered = (int)$stCount->fetchColumn();

// recordsTotal (بدون search ولی با فیلترهای پایه)
$whereBase = ' WHERE stat_date BETWEEN :from AND :to ';
$paramsBase = [':from' => $fromG, ':to' => $toG];
if ($userType !== 0) {
    $whereBase .= ' AND user_type = :user_type ';
    $paramsBase[':user_type'] = $userType;
}
if ($eventType !== 0) {
    $whereBase .= ' AND event_type = :event_type ';
    $paramsBase[':event_type'] = $eventType;
}
$stTotal = $pdo->prepare("SELECT COUNT(*) FROM auth_daily_stats {$whereBase}");
$stTotal->execute($paramsBase);
$recordsTotal = (int)$stTotal->fetchColumn();

// LIMIT/OFFSET را int کرده و مستقیم در SQL می‌گذاریم تا mix placeholder پیش نیاید
$limit = $length;
$offset = $start;

$sql = "SELECT stat_date, user_type, event_type, cnt
        FROM auth_daily_stats
        {$where}
        ORDER BY {$orderBy} {$orderDir}
        LIMIT {$limit} OFFSET {$offset}";

$st = $pdo->prepare($sql);
$st->execute($params);

$eventLabels = [
    1 => 'ورود موفق (رمز/ادمین)',
    2 => 'خروج',
    3 => 'ورود با یادآوری (ادمین)',
    4 => 'ورود موفق (OTP/اپ)',
    5 => 'ورود ناموفق',
];

$data = [];
while ($r = $st->fetch()) {
    $ut = (int)$r['user_type'];
    $et = (int)$r['event_type'];
    $data[] = [
        'date' => gdate_to_jalali_str((string)$r['stat_date']),
        'user_type' => user_type_label($ut),
        'event' => $eventLabels[$et] ?? ('رویداد #' . $et),
        'cnt' => number_format((int)$r['cnt']),
    ];
}

json_out([
    'draw' => $draw,
    'recordsTotal' => $recordsTotal,
    'recordsFiltered' => $recordsFiltered,
    'data' => $data,
]);
