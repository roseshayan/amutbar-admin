<?php

declare(strict_types=1);

require_once __DIR__ . '/../../includes/init.php';
require_admin();
require_once __DIR__ . '/../../includes/jalali.php';

try {
    driver_activity_ensure_schema();
} catch (Throwable $e) {
    json_out(['draw' => (int)($_POST['draw'] ?? $_GET['draw'] ?? 0), 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => [], 'error' => 'جدول فعالیت رانندگان آماده نیست.']);
}

$pdo = db();
$req = array_merge($_GET, $_POST);

$draw = (int)($req['draw'] ?? 0);
$start = max(0, (int)($req['start'] ?? 0));
$length = (int)($req['length'] ?? 25);
if ($length <= 0 || $length > 200) $length = 25;

$search = trim((string)($req['search']['value'] ?? ''));
$driverId = (int)($req['driver_id'] ?? 0);
$userId = (int)($req['user_id'] ?? 0);
$eventKey = trim((string)($req['event_key'] ?? ''));
$entityType = trim((string)($req['entity_type'] ?? ''));

$todayG = date('Y-m-d');
$defaultFromG = date('Y-m-d', strtotime('-7 days'));
$fromJ = (string)($req['from'] ?? gdate_to_jalali_str($defaultFromG));
$toJ = (string)($req['to'] ?? gdate_to_jalali_str($todayG));
$fromG = jdate_str_to_gdate($fromJ) ?? $defaultFromG;
$toG = jdate_str_to_gdate($toJ) ?? $todayG;
if (strtotime($fromG) > strtotime($toG)) [$fromG, $toG] = [$toG, $fromG];

$where = " WHERE dal.created_at >= :from_dt AND dal.created_at < DATE_ADD(:to_dt, INTERVAL 1 DAY) ";
$params = [':from_dt' => $fromG . ' 00:00:00', ':to_dt' => $toG . ' 00:00:00'];

if ($driverId > 0) {
    $where .= " AND dal.driver_id = :driver_id ";
    $params[':driver_id'] = $driverId;
}
if ($userId > 0) {
    $where .= " AND dal.user_id = :user_id ";
    $params[':user_id'] = $userId;
}
if ($eventKey !== '') {
    $where .= " AND dal.event_key = :event_key ";
    $params[':event_key'] = $eventKey;
}
if ($entityType !== '') {
    $where .= " AND dal.entity_type = :entity_type ";
    $params[':entity_type'] = $entityType;
}
if ($search !== '') {
    $where .= " AND (u.full_name LIKE :search OR u.phone LIKE :search OR d.full_name LIKE :search OR dal.event_title LIKE :search OR dal.screen_title LIKE :search OR dal.event_key LIKE :search OR dal.entity_type LIKE :search OR CAST(dal.entity_id AS CHAR) LIKE :search) ";
    $params[':search'] = '%' . $search . '%';
}

$baseFrom = " FROM driver_activity_logs dal
    LEFT JOIN users u ON dal.user_id = u.id
    LEFT JOIN drivers d ON dal.driver_id = d.id ";

$stTotal = $pdo->prepare("SELECT COUNT(*) {$baseFrom} {$where}");
$stTotal->execute($params);
$recordsFiltered = (int)$stTotal->fetchColumn();

$stAll = $pdo->query("SELECT COUNT(*) FROM driver_activity_logs");
$recordsTotal = (int)$stAll->fetchColumn();

$orderCol = (int)($req['order'][0]['column'] ?? 0);
$orderDir = strtolower((string)($req['order'][0]['dir'] ?? 'desc')) === 'asc' ? 'ASC' : 'DESC';
$orderMap = [
    0 => 'dal.created_at',
    1 => 'u.full_name',
    2 => 'dal.event_title',
    3 => 'dal.screen_title',
    4 => 'dal.entity_type',
    5 => 'dal.client_platform',
];
$orderBy = $orderMap[$orderCol] ?? 'dal.created_at';

$sql = "SELECT dal.*, INET6_NTOA(dal.ip_address) AS ip_text,
        u.full_name AS user_full_name, u.phone AS user_phone,
        d.full_name AS driver_full_name, d.plate_number
    {$baseFrom}
    {$where}
    ORDER BY {$orderBy} {$orderDir}, dal.id DESC
    LIMIT {$length} OFFSET {$start}";

$st = $pdo->prepare($sql);
$st->execute($params);

$platformLabel = static function ($p): string {
    return match ((int)$p) {
        1 => 'Android',
        2 => 'iOS',
        3 => 'Web',
        default => '-',
    };
};

$entityLabel = static function (?string $type, $id): string {
    $type = (string)$type;
    if ($type === '' && empty($id)) return '-';
    $labels = [
        'load' => 'بار',
        'company' => 'باربری',
        'ticket' => 'تیکت',
        'profile' => 'پروفایل',
        'support' => 'پشتیبانی',
    ];
    $label = $labels[$type] ?? ($type !== '' ? $type : 'مورد');
    return $label . (!empty($id) ? ' #' . (int)$id : '');
};

$data = [];
while ($r = $st->fetch()) {
    $payload = [];
    if (!empty($r['payload_json'])) {
        $decoded = json_decode((string)$r['payload_json'], true);
        if (is_array($decoded)) $payload = $decoded;
    }
    $payloadPretty = !empty($payload)
        ? json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
        : '';

    $driverName = $r['driver_full_name'] ?: ($r['user_full_name'] ?: '-');
    $phone = $r['user_phone'] ?: '-';
    $plate = $r['plate_number'] ?: '-';

    $created = (string)$r['created_at'];
    $createdJ = $created;
    try {
        $createdJ = gdate_to_jalali_str(substr($created, 0, 10)) . ' ' . substr($created, 11, 8);
    } catch (Throwable $e) {}

    $data[] = [
        'created_at' => htmlspecialchars($createdJ, ENT_QUOTES, 'UTF-8'),
        'driver' => '<div class="fw-semibold">' . htmlspecialchars($driverName, ENT_QUOTES, 'UTF-8') . '</div><div class="text-muted fs-12">' . htmlspecialchars($phone, ENT_QUOTES, 'UTF-8') . ' / پلاک: ' . htmlspecialchars($plate, ENT_QUOTES, 'UTF-8') . '</div>',
        'event' => '<span class="badge bg-primary-transparent">' . htmlspecialchars((string)$r['event_title'], ENT_QUOTES, 'UTF-8') . '</span><div class="text-muted fs-12 mt-1">' . htmlspecialchars((string)$r['event_key'], ENT_QUOTES, 'UTF-8') . '</div>',
        'screen' => htmlspecialchars((string)($r['screen_title'] ?: $r['screen_key'] ?: '-'), ENT_QUOTES, 'UTF-8'),
        'entity' => htmlspecialchars($entityLabel($r['entity_type'] ?? null, $r['entity_id'] ?? null), ENT_QUOTES, 'UTF-8'),
        'client' => htmlspecialchars($platformLabel($r['client_platform']) . ' ' . ((string)($r['app_version_name'] ?? '') ?: '') . (!empty($r['client_version_code']) ? ' (' . (int)$r['client_version_code'] . ')' : ''), ENT_QUOTES, 'UTF-8'),
        'ip' => htmlspecialchars((string)($r['ip_text'] ?: '-'), ENT_QUOTES, 'UTF-8'),
        'payload' => $payloadPretty !== '' ? '<pre class="mb-0 text-start direction-ltr small" style="white-space:pre-wrap;max-width:420px">' . htmlspecialchars($payloadPretty, ENT_QUOTES, 'UTF-8') . '</pre>' : '-',
    ];
}

json_out([
    'draw' => $draw,
    'recordsTotal' => $recordsTotal,
    'recordsFiltered' => $recordsFiltered,
    'data' => $data,
]);
