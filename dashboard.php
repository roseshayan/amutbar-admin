<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/includes/jdf.php';
require_admin();

if (!has_permission('dashboard.view')) {
    if (has_permission('loads.view')) {
        header('Location: ' . url_path('loads_list.php'));
        exit;
    }
    if (has_permission('drivers.view') || has_permission('cargos_users.view')) {
        header('Location: ' . url_path('users_list.php'));
        exit;
    }
    if (has_permission('tickets.view')) {
        header('Location: ' . url_path('support_tickets.php'));
        exit;
    }
    die('شما به داشبورد دسترسی ندارید. لطفاً با مدیر کل هماهنگ کنید.');
}

$pdo = db();
$isSuper = is_super_admin();
$role = admin_role();
$admin = current_admin();
$adminName = !empty($admin['full_name']) ? $admin['full_name'] : 'مدیر سیستم';

// ==========================================
// ۱. دریافت آمار و ارقام بر اساس سطح دسترسی
// ==========================================

// آمار بارها
$stLoads = $pdo->query("
    SELECT 
        COUNT(*) AS total,
        SUM(CASE WHEN load_status = 1 THEN 1 ELSE 0 END) AS active_count,
        SUM(CASE WHEN load_status = 1 THEN proposed_price ELSE 0 END) AS active_volume
    FROM loads 
    WHERE deleted_at IS NULL
");
$loadsStats = $stLoads->fetch(PDO::FETCH_ASSOC) ?: ['total' => 0, 'active_count' => 0, 'active_volume' => 0];

// آمار رانندگان
$stDrivers = $pdo->query("
    SELECT 
        COUNT(*) AS total,
        SUM(CASE WHEN verification_status = 1 THEN 1 ELSE 0 END) AS verified_count,
        SUM(CASE WHEN verification_status = 0 THEN 1 ELSE 0 END) AS pending_count
    FROM drivers 
    WHERE deleted_at IS NULL
");
$driversStats = $stDrivers->fetch(PDO::FETCH_ASSOC) ?: ['total' => 0, 'verified_count' => 0, 'pending_count' => 0];

// آمار صاحبان بار / شرکت‌ها
$cargoUsersCount = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE user_type = 2 AND deleted_at IS NULL")->fetchColumn();

// آمار تیکت‌های باز پشتیبانی
$stTickets = $pdo->query("
    SELECT 
        COUNT(*) AS total,
        SUM(CASE WHEN status IN (0, 1) THEN 1 ELSE 0 END) AS open_count
    FROM support_tickets
");
$ticketsStats = $stTickets->fetch(PDO::FETCH_ASSOC) ?: ['total' => 0, 'open_count' => 0];

// آمار دستگاه‌های پوش‌نوتیفیکیشن
$devicesCount = (int)$pdo->query("SELECT COUNT(*) FROM user_push_tokens")->fetchColumn();

// آمار تماس‌های امروز
$callsTodayCount = (int)$pdo->query("
    SELECT COUNT(*) FROM call_logs WHERE created_at >= CURDATE()
")->fetchColumn();

// آخرین بارهای ثبت شده (۵ بار اخیر)
$stRecentLoads = $pdo->query("
    SELECT l.id, l.proposed_price, l.load_status, l.created_at,
           c1.name AS origin_city, c2.name AS dest_city,
           cl.title AS cargo_title, comp.company_name
    FROM loads l
    LEFT JOIN cities c1 ON l.origin_city_id = c1.id
    LEFT JOIN cities c2 ON l.dest_city_id = c2.id
    LEFT JOIN cargos_list cl ON l.cargo_type_id = cl.id
    LEFT JOIN companies comp ON l.company_id = comp.id
    WHERE l.deleted_at IS NULL
    ORDER BY l.id DESC
    LIMIT 5
");
$recentLoads = $stRecentLoads->fetchAll(PDO::FETCH_ASSOC);

// رانندگان در انتظار احراز هویت (۵ راننده اخیر)
$stPendingDrivers = $pdo->query("
    SELECT d.id, d.user_id, d.full_name, d.national_code, d.plate_number, d.created_at,
           u.phone, vt.title AS vehicle_title
    FROM drivers d
    JOIN users u ON d.user_id = u.id
    LEFT JOIN vehicle_types vt ON d.vehicle_type_id = vt.id
    WHERE d.verification_status = 0 AND d.deleted_at IS NULL
    ORDER BY d.id DESC
    LIMIT 5
");
$pendingDrivers = $stPendingDrivers->fetchAll(PDO::FETCH_ASSOC);

// آخرین تیکت‌های پشتیبانی
$stRecentTickets = $pdo->query("
    SELECT t.id, t.subject, t.status, t.created_at, u.full_name, u.phone
    FROM support_tickets t
    LEFT JOIN users u ON t.user_id = u.id
    ORDER BY t.id DESC
    LIMIT 5
");
$recentTickets = $stRecentTickets->fetchAll(PDO::FETCH_ASSOC);

// آخرین تماس‌های رانندگان
$stRecentCalls = $pdo->query("
    SELECT cl.id, cl.created_at, d.full_name AS driver_name, comp.company_name
    FROM call_logs cl
    LEFT JOIN drivers d ON cl.driver_id = d.id
    LEFT JOIN companies comp ON cl.company_id = comp.id
    ORDER BY cl.id DESC
    LIMIT 5
");
$recentCalls = $stRecentCalls->fetchAll(PDO::FETCH_ASSOC);

require_once "views/panel/header.php";
require_once "views/panel/sidebar.php";
?>

<div class="main-content app-content">
    <div class="container-fluid">

        <!-- هدر داشبورد و خوش‌آمدگویی هوشمند بر اساس نقش -->
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <div class="d-flex align-items-center gap-2">
                    <h1 class="page-title fw-bold fs-20 mb-0">داشبورد مدیریت آموت‌بار</h1>
                    <?php if ($isSuper): ?>
                        <span class="badge bg-danger-transparent text-danger fs-12 px-2 py-1">
                            <i class="ri-shield-star-line me-1"></i>سطح دسترسی: مدیر کل (نامحدود)
                        </span>
                    <?php else: ?>
                        <span class="badge bg-info-transparent text-info fs-12 px-2 py-1">
                            <i class="ri-user-star-line me-1"></i>سطح دسترسی: کارمند (عملیاتی)
                        </span>
                    <?php endif; ?>
                </div>
                <p class="fs-13 text-muted mb-0 mt-1">
                    خوش آمدید <?= htmlspecialchars($adminName) ?> عزیز | امروز: <strong><?= jdate('l، j F Y') ?></strong>
                </p>
            </div>
            <div class="btn-list">
                <a href="load_create.php" class="btn btn-primary btn-wave">
                    <i class="ri-add-circle-line align-middle me-1"></i> اعلام بار جدید
                </a>
                <a href="users_list.php" class="btn btn-outline-secondary btn-wave">
                    <i class="ri-user-search-line align-middle me-1"></i> مدیریت کاربران
                </a>
                <a href="support_tickets.php" class="btn btn-outline-warning btn-wave position-relative">
                    <i class="ri-customer-service-2-line align-middle me-1"></i> تیکت‌ها
                    <?php if ((int)$ticketsStats['open_count'] > 0): ?>
                        <span class="badge bg-danger rounded-pill ms-1"><?= (int)$ticketsStats['open_count'] ?></span>
                    <?php endif; ?>
                </a>
                <?php if ($isSuper): ?>
                    <a href="notifications.php" class="btn btn-outline-info btn-wave">
                        <i class="ri-notification-3-line align-middle me-1"></i> ارسال نوتیفیکیشن
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- کارت‌های آمار و KPI های هوشمند -->
        <div class="row">
            <?php if ($isSuper): ?>
                <!-- کارت بارهای فعال و کل بارها (مدیر کل) -->
                <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <div class="card custom-card border-top-card border-top-primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="d-block text-muted fs-12 mb-1">بارهای فعال / کل بارها</span>
                                    <h4 class="fw-bold mb-1 text-primary">
                                        <?= number_format((int)$loadsStats['active_count']) ?>
                                        <small class="fs-13 text-muted fw-normal">/ <?= number_format((int)$loadsStats['total']) ?></small>
                                    </h4>
                                    <span class="badge bg-primary-transparent fs-11">در انتظار بارگیری و حمل</span>
                                </div>
                                <div class="avatar avatar-lg bg-primary-transparent text-primary rounded-3">
                                    <i class="ri-truck-line fs-24"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- کارت حجم مالی پیشنهادی بارها (فقط مدیر کل) -->
                <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <div class="card custom-card border-top-card border-top-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="d-block text-muted fs-12 mb-1">حجم مالی بارهای جاری</span>
                                    <h4 class="fw-bold mb-1 text-success">
                                        <?= number_format((float)$loadsStats['active_volume']) ?>
                                        <span class="fs-12 text-muted fw-normal">تومان</span>
                                    </h4>
                                    <span class="badge bg-success-transparent fs-11">گردش مالی تخمینی فعال</span>
                                </div>
                                <div class="avatar avatar-lg bg-success-transparent text-success rounded-3">
                                    <i class="ri-money-dollar-circle-line fs-24"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- کارت رانندگان (مدیر کل) -->
                <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <div class="card custom-card border-top-card border-top-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="d-block text-muted fs-12 mb-1">رانندگان تایید شده</span>
                                    <h4 class="fw-bold mb-1 text-info">
                                        <?= number_format((int)$driversStats['verified_count']) ?>
                                        <small class="fs-13 text-muted fw-normal">/ <?= number_format((int)$driversStats['total']) ?></small>
                                    </h4>
                                    <?php if ((int)$driversStats['pending_count'] > 0): ?>
                                        <span class="badge bg-warning-transparent text-warning fs-11"><?= (int)$driversStats['pending_count'] ?> راننده منتظر تایید</span>
                                    <?php else: ?>
                                        <span class="badge bg-success-transparent text-success fs-11">همه مدارک بررسی شده</span>
                                    <?php endif; ?>
                                </div>
                                <div class="avatar avatar-lg bg-info-transparent text-info rounded-3">
                                    <i class="ri-steering-2-line fs-24"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- کارت صاحبان بار و دستگاه‌های فعال (مدیر کل) -->
                <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <div class="card custom-card border-top-card border-top-warning">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="d-block text-muted fs-12 mb-1">صاحبان بار / دستگاه‌های فعال</span>
                                    <h4 class="fw-bold mb-1 text-warning">
                                        <?= number_format($cargoUsersCount) ?>
                                        <small class="fs-13 text-muted fw-normal">شرکت (<?= number_format($devicesCount) ?> پوش)</small>
                                    </h4>
                                    <span class="badge bg-warning-transparent fs-11">مخاطبین فعال سامانه</span>
                                </div>
                                <div class="avatar avatar-lg bg-warning-transparent text-warning rounded-3">
                                    <i class="ri-building-line fs-24"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <!-- کارت‌های اختصاصی کارمند (بدون موارد مالی و زیرساخت) -->

                <!-- رانندگان نیازمند بررسی مدارک (وظیفه اصلی کارمند) -->
                <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <div class="card custom-card border-top-card border-top-danger">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="d-block text-muted fs-12 mb-1">مدارک منتظر تایید شما</span>
                                    <h4 class="fw-bold mb-1 text-danger"><?= number_format((int)$driversStats['pending_count']) ?></h4>
                                    <a href="users_list.php" class="badge bg-danger-transparent text-danger fs-11 text-decoration-none">
                                        شروع بررسی مدارک <i class="ri-arrow-left-s-line align-middle"></i>
                                    </a>
                                </div>
                                <div class="avatar avatar-lg bg-danger-transparent text-danger rounded-3">
                                    <i class="ri-file-user-line fs-24"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- تیکت‌های پشتیبانی باز -->
                <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <div class="card custom-card border-top-card border-top-warning">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="d-block text-muted fs-12 mb-1">تیکت‌های در انتظار پاسخ</span>
                                    <h4 class="fw-bold mb-1 text-warning"><?= number_format((int)$ticketsStats['open_count']) ?></h4>
                                    <a href="support_tickets.php" class="badge bg-warning-transparent text-warning fs-11 text-decoration-none">
                                        پاسخ به تیکت‌ها <i class="ri-arrow-left-s-line align-middle"></i>
                                    </a>
                                </div>
                                <div class="avatar avatar-lg bg-warning-transparent text-warning rounded-3">
                                    <i class="ri-customer-service-2-line fs-24"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- بارهای فعال امروز -->
                <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <div class="card custom-card border-top-card border-top-primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="d-block text-muted fs-12 mb-1">بارهای فعال در سامانه</span>
                                    <h4 class="fw-bold mb-1 text-primary"><?= number_format((int)$loadsStats['active_count']) ?></h4>
                                    <a href="loads_list.php" class="badge bg-primary-transparent text-primary fs-11 text-decoration-none">
                                        مشاهده لیست بارها <i class="ri-arrow-left-s-line align-middle"></i>
                                    </a>
                                </div>
                                <div class="avatar avatar-lg bg-primary-transparent text-primary rounded-3">
                                    <i class="ri-truck-line fs-24"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- تماس‌های رانندگان امروز -->
                <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <div class="card custom-card border-top-card border-top-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="d-block text-muted fs-12 mb-1">تماس‌های رانندگان امروز</span>
                                    <h4 class="fw-bold mb-1 text-success"><?= number_format($callsTodayCount) ?></h4>
                                    <span class="badge bg-success-transparent fs-11">پیگیری ارتباطات باربری</span>
                                </div>
                                <div class="avatar avatar-lg bg-success-transparent text-success rounded-3">
                                    <i class="ri-phone-line fs-24"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endif; ?>
        </div>

        <!-- ردیف جداول عملیاتی و نمودارها -->
        <div class="row">

            <!-- ستون سمت راست: آخرین بارها و رانندگان در انتظار احراز هویت -->
            <div class="col-xxl-8 col-xl-7">

                <!-- جدول رانندگان منتظر تایید مدارک -->
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            <i class="ri-file-shield-2-line text-warning me-1"></i> رانندگان در انتظار بررسی هویت و مدارک
                        </div>
                        <a href="users_list.php" class="btn btn-sm btn-outline-primary">مشاهده همه</a>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($pendingDrivers)): ?>
                            <div class="p-4 text-center text-muted">
                                <i class="ri-checkbox-circle-line fs-32 text-success d-block mb-2"></i>
                                همه درخواست‌های احراز هویت رانندگان بررسی شده و موردی در انتظار نیست.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table text-nowrap table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>راننده</th>
                                            <th>شماره تماس</th>
                                            <th>کد ملی</th>
                                            <th>وسیله نقلیه</th>
                                            <th>پلاک</th>
                                            <th>عملیات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($pendingDrivers as $pd): ?>
                                            <tr>
                                                <td class="fw-bold"><?= htmlspecialchars($pd['full_name']) ?></td>
                                                <td><a href="tel:<?= htmlspecialchars($pd['phone']) ?>" dir="ltr"><?= htmlspecialchars($pd['phone']) ?></a></td>
                                                <td><?= htmlspecialchars($pd['national_code']) ?></td>
                                                <td><span class="badge bg-light text-dark"><?= htmlspecialchars($pd['vehicle_title'] ?? 'ثبت نشده') ?></span></td>
                                                <td><code dir="ltr"><?= htmlspecialchars($pd['plate_number'] ?? '---') ?></code></td>
                                                <td>
                                                    <a href="user_create.php?id=<?= $pd['user_id'] ?>" class="btn btn-sm btn-warning btn-wave">
                                                        <i class="ri-eye-line me-1"></i> بررسی مدارک
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- جدول بارهای اخیر ثبت شده در سامانه -->
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            <i class="ri-archive-line text-primary me-1"></i> آخرین بارهای ثبت شده در سامانه
                        </div>
                        <a href="loads_list.php" class="btn btn-sm btn-outline-primary">مدیریت بارها</a>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($recentLoads)): ?>
                            <div class="p-4 text-center text-muted">هیچ باری در سامانه یافت نشد.</div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table text-nowrap table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>کد بار</th>
                                            <th>مسیر</th>
                                            <th>نوع محموله</th>
                                            <th>صاحب بار / شرکت</th>
                                            <th>کرایه پیشنهادی</th>
                                            <th>وضعیت</th>
                                            <th>تاریخ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentLoads as $rl): ?>
                                            <tr>
                                                <td><strong>#<?= (int)$rl['id'] ?></strong></td>
                                                <td>
                                                    <span class="text-primary fw-medium"><?= htmlspecialchars($rl['origin_city'] ?? 'مبدا نامشخص') ?></span>
                                                    <i class="ri-arrow-left-line text-muted mx-1"></i>
                                                    <span class="text-success fw-medium"><?= htmlspecialchars($rl['dest_city'] ?? 'مقصد نامشخص') ?></span>
                                                </td>
                                                <td><?= htmlspecialchars($rl['cargo_title'] ?? 'عمومی') ?></td>
                                                <td><?= htmlspecialchars($rl['company_name'] ?? 'آموت‌بار') ?></td>
                                                <td class="fw-bold text-success">
                                                    <?= number_format((float)$rl['proposed_price']) ?> <span class="fs-10 text-muted">تومان</span>
                                                </td>
                                                <td>
                                                    <?php if ((int)$rl['load_status'] === 1): ?>
                                                        <span class="badge bg-success-transparent text-success">فعال</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary-transparent text-secondary">پایان یافته</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-muted fs-12"><?= jdate('Y/m/d H:i', strtotime($rl['created_at'])) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            <!-- ستون سمت چپ: تیکت‌ها، تماس‌ها و وضعیت سیستم -->
            <div class="col-xxl-4 col-xl-5">

                <!-- آخرین تیکت‌های پشتیبانی -->
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            <i class="ri-customer-service-2-line text-warning me-1"></i> تیکت‌های اخیر
                        </div>
                        <a href="support_tickets.php" class="btn btn-sm btn-outline-warning">همه تیکت‌ها</a>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($recentTickets)): ?>
                            <div class="p-4 text-center text-muted">هیچ تیکت پشتیبانی جدیدی ثبت نشده است.</div>
                        <?php else: ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($recentTickets as $rt): ?>
                                    <li class="list-group-item d-flex align-items-center justify-content-between p-3">
                                        <div>
                                            <span class="fw-bold d-block mb-1"><?= htmlspecialchars($rt['subject']) ?></span>
                                            <span class="text-muted fs-11">
                                                توسط: <?= htmlspecialchars($rt['full_name'] ?? 'کاربر') ?> (<?= htmlspecialchars($rt['phone'] ?? '') ?>)
                                            </span>
                                        </div>
                                        <div class="text-end">
                                            <a href="support_tickets.php" class="btn btn-sm btn-outline-primary btn-wave">مشاهده</a>
                                            <span class="d-block text-muted fs-10 mt-1"><?= jdate('m/d H:i', strtotime($rt['created_at'])) ?></span>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- آخرین تماس‌های رانندگان با باربری‌ها -->
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            <i class="ri-phone-find-line text-info me-1"></i> تماس‌های اخیر رانندگان
                        </div>
                        <span class="badge bg-info-transparent text-info"><?= $callsTodayCount ?> تماس امروز</span>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($recentCalls)): ?>
                            <div class="p-4 text-center text-muted">تماسی ثبت نشده است.</div>
                        <?php else: ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($recentCalls as $rc): ?>
                                    <li class="list-group-item d-flex align-items-center justify-content-between p-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="avatar avatar-sm bg-success-transparent text-success rounded-circle">
                                                <i class="ri-phone-line"></i>
                                            </span>
                                            <div>
                                                <span class="fw-bold d-block fs-12"><?= htmlspecialchars($rc['driver_name'] ?? 'راننده') ?></span>
                                                <span class="text-muted fs-11">به مقصد: <?= htmlspecialchars($rc['company_name'] ?? 'شرکت باربری') ?></span>
                                            </div>
                                        </div>
                                        <span class="text-muted fs-11"><?= jdate('H:i', strtotime($rc['created_at'])) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- ویجت سلامت سرور و ابزارهای حساس (فقط مدیر کل) -->
                <?php if ($isSuper): ?>
                    <div class="card custom-card bg-light border">
                        <div class="card-header border-bottom">
                            <div class="card-title text-dark">
                                <i class="ri-server-line text-primary me-1"></i> وضعیت سیستم و زیرساخت (مدیر کل)
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0 fs-12">
                                <li class="d-flex justify-content-between py-2 border-bottom">
                                    <span class="text-muted">نسخه PHP:</span>
                                    <span class="fw-bold text-dark"><?= PHP_VERSION ?></span>
                                </li>
                                <li class="d-flex justify-content-between py-2 border-bottom">
                                    <span class="text-muted">پایگاه داده:</span>
                                    <span class="fw-bold text-success"><i class="ri-checkbox-circle-fill me-1"></i>MySQL متصل</span>
                                </li>
                                <li class="d-flex justify-content-between py-2 border-bottom">
                                    <span class="text-muted">محیط اجرا (OS):</span>
                                    <span class="fw-bold text-dark"><?= PHP_OS ?></span>
                                </li>
                                <li class="d-flex justify-content-between py-2">
                                    <span class="text-muted">سرویس نوتیفیکیشن (FCM):</span>
                                    <span class="badge bg-success-transparent text-success"><?= $devicesCount ?> دستگاه متصل</span>
                                </li>
                            </ul>
                            <div class="d-grid gap-2 mt-3">
                                <a href="site-settings.php" class="btn btn-sm btn-outline-secondary">
                                    <i class="ri-settings-4-line me-1"></i> ورود به تنظیمات کلان سیستم
                                </a>
                                <a href="backup-manager.php" class="btn btn-sm btn-outline-info">
                                    <i class="ri-database-2-line me-1"></i> وضعیت پشتیبان‌گیری ابری
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

        </div>

    </div>
</div>

<?php require_once "views/panel/footer.php"; ?>