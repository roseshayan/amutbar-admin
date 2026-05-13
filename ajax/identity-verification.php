<?php
declare(strict_types=1);

header('Content-Type: application/json');

require_once '../includes/init.php';
require_admin();

require_once '../includes/IdentityVerificationRunner.php';

$action = $_GET['action'] ?? ($_POST['action'] ?? '');

try {
    $pdo = db();
    $runner = new IdentityVerificationRunner($pdo);

    if ($action === 'list_services') {
        $userType = (int)($_GET['user_type'] ?? 0);
        if (!in_array($userType, [1,2], true)) {
            echo json_encode(['ok' => false, 'message' => 'user_type نامعتبر است']);
            exit;
        }
        $items = $runner->listActiveServicesForUserType($userType);
        echo json_encode(['ok' => true, 'items' => $items]);
        exit;
    }

    if ($action === 'run') {
        $subjectUserId = (int)($_POST['user_id'] ?? 0);
        $serviceIds = $_POST['service_ids'] ?? [];

        if ($subjectUserId <= 0 || !is_array($serviceIds) || count($serviceIds) === 0) {
            echo json_encode(['ok' => false, 'message' => 'ورودی نامعتبر است']);
            exit;
        }

        $actorAdminId = admin_id();

        $overrides = $_POST['overrides'] ?? [];
        if (is_string($overrides)) {
            $tmp = json_decode($overrides, true);
            $overrides = is_array($tmp) ? $tmp : [];
        }
        if (!is_array($overrides)) $overrides = [];

        $results = [];
        foreach ($serviceIds as $sid) {
            $sid = (int)$sid;
            if ($sid <= 0) continue;
            $results[] = $runner->runServiceForUser($sid, $subjectUserId, $actorAdminId, $overrides);
        }

        echo json_encode(['ok' => true, 'results' => $results]);
        exit;
    }

    echo json_encode(['ok' => false, 'message' => 'action نامعتبر است']);
} catch (Throwable $e) {
    $debug = (string)env('APP_DEBUG', '0') === '1';
    echo json_encode(['ok' => false, 'message' => $debug ? $e->getMessage() : 'خطای سرور']);
}
