<?php

declare(strict_types=1);

require_once __DIR__ . '/../../includes/init.php';
require_once __DIR__ . '/../../includes/api.php';
require_once __DIR__ . '/../../includes/api_http.php';
require_once __DIR__ . '/../../includes/otp.php';

// Always JSON for API
header('Content-Type: application/json; charset=utf-8');

// CORS + OPTIONS
api_cors();

