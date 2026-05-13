<?php

declare(strict_types=1);

require_once __DIR__ . '/../../includes/init.php';
require_admin();

$req = array_merge($_GET, $_POST);
json_out(users_datatable($req));
