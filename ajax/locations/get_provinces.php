<?php

declare(strict_types=1);

require_once __DIR__ . '/../../includes/init.php';
require_admin();

$pdo = db();
$st = $pdo->query("SELECT id, name FROM provinces ORDER BY name");
$provinces = $st->fetchAll();

json_out(['ok' => true, 'provinces' => $provinces]);
