<?php

declare(strict_types=1);

require_once __DIR__ . '/../_bootstrap.php';

$u = api_require_auth();
api_ok(['user' => $u]);
