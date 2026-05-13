<?php

require_once __DIR__ . '/includes/init.php';

$page = $_GET['page'] ?? null;

if (!admin_id()) {
    // اگر لاگین نیست، همیشه صفحه ورود
    require __DIR__ . '/login.php';
    exit;
}

switch ($page) {
    case 'companies_pending':
        require __DIR__ . '/companies_pending.php';
        break;

    case 'dashboard':
    default:
        require __DIR__ . '/dashboard.php';
        break;
}
