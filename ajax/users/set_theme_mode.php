<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/init.php';
require_admin();

$mode = (string)($_POST['mode'] ?? '');
$mode = strtolower(trim($mode));
if (!in_array($mode, ['light', 'dark'], true)) {
    json_out(['ok' => false, 'message' => 'mode_invalid'], 400);
}

$adminId = (int)($_SESSION['admin_user_id'] ?? 0);
if ($adminId <= 0) {
    json_out(['ok' => false, 'message' => 'unauthorized'], 401);
}


$pdo = db();
try {
    $st = $pdo->prepare("UPDATE users SET theme_mode = ? WHERE id = ? LIMIT 1");
    $st->execute([$mode, $adminId]);
} catch (Throwable $e) {
    // If column doesn't exist yet on an old DB, don't break the UI.
    json_out(['ok' => false, 'message' => 'db_not_updated'], 500);
}

json_out(['ok' => true]);
