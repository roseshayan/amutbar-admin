<?php

declare(strict_types=1);

$page_title = 'پروفایل ادمین';

require_once "views/panel/header.php";
require_once "views/panel/sidebar.php";

$admin_id = admin_id();
$admin_data = admin_get_profile_data($admin_id);
$stats = admin_get_simple_stats($admin_id);

if (!$admin_data) {
    header('Location: /index.php?page=login');
    exit;
}
?>

<!-- MAIN-CONTENT -->
<div class="main-content app-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-2">پروفایل</h1>
                <div class="">
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="dashboard.php">داشبورد</a></li>
                            <li class="breadcrumb-item active" aria-current="page">پروفایل</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="btn-list">
                <a href="profile-settings.php" class="btn btn-primary btn-wave">
                    <i class="ri-edit-box-line align-middle"></i> ویرایش پروفایل
                </a>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Start:: row-1 -->
        <div class="row">
            <div class="col-xl-6">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card custom-card profile-card">
                            <div class="card-body pb-0 position-relative">
                                <div class="profile-banner-img">
                                    <img src="assets/images/media/media-74.jpg" class="rounded w-100" alt="بک‌گراند پروفایل">
                                </div>
                                <span class="avatar avatar-xxl avatar-rounded bg-light">
                                    <?php if ($admin_data['avatar_key']): ?>
                                        <img src="<?= htmlspecialchars($admin_data['avatar_key']) ?>" alt="آواتار">
                                    <?php else: ?>
                                        <span class="avatar-initials fs-24">
                                            <?= mb_substr($admin_data['full_name'], 0, 1) ?>
                                        </span>
                                    <?php endif; ?>
                                </span>
                                <div class="mt-4 mb-0 p-4 d-flex align-items-center flex-wrap gap-3 justify-content-between">
                                    <div>
                                        <h5 class="fw-semibold mb-3"><?= htmlspecialchars($admin_data['full_name']) ?></h5>
                                        <span class="d-block fw-medium text-muted mb-1">مدیر سیستم</span>
                                        <p class="fs-12 mb-0 fw-medium text-muted">
                                            <span class="me-3"><i class="ri-user-line me-1 align-middle"></i>ادمین</span>
                                            <span><i class="ri-calendar-line me-1 align-middle"></i>عضو از <?= jalali_date($admin_data['created_at']) ?></span>
                                        </p>
                                    </div>
                                </div>

                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item p-3 border-top">
                                        <span class="fw-medium fs-15 d-block mb-3">اطلاعات حساب:</span>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <p class="mb-1"><strong>نام و نام خانوادگی:</strong></p>
                                                <p class="text-muted"><?= htmlspecialchars($admin_data['full_name']) ?></p>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <p class="mb-1"><strong>شماره موبایل:</strong></p>
                                                <p class="text-muted"><?= htmlspecialchars($admin_data['phone']) ?></p>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <p class="mb-1"><strong>ایمیل:</strong></p>
                                                <p class="text-muted"><?= htmlspecialchars($admin_data['email'] ?: 'ثبت نشده') ?></p>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <p class="mb-1"><strong>نوع کاربر:</strong></p>
                                                <p class="text-muted"><?= user_type_label($admin_data['user_type']) ?></p>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <p class="mb-1"><strong>وضعیت:</strong></p>
                                                <p class="text-muted"><?= user_status_label($admin_data['status']) ?></p>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <p class="mb-1"><strong>آخرین ورود:</strong></p>
                                                <p class="text-muted"><?= $admin_data['last_login_at'] ? jalali_date($admin_data['last_login_at']) : '' ?></p>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="card custom-card overflow-hidden">
                            <div class="card-header">
                                <div class="card-title">
                                    آمار کلی
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-medium">کل کاربران:</span>
                                            <span class="badge bg-primary rounded-pill"><?= number_format($stats['total_users']) ?></span>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-medium">کاربران امروز:</span>
                                            <span class="badge bg-success rounded-pill"><?= number_format($stats['today_users']) ?></span>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-medium">تعداد ادمین‌ها:</span>
                                            <span class="badge bg-info rounded-pill"><?= number_format($stats['admin_count']) ?></span>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-medium">آخرین فعالیت:</span>
                                            <span class="text-muted"><?= $stats['last_login'] ? jalali_date($stats['last_login']) : 'اولین ورود' ?></span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="card custom-card overflow-hidden">
                            <div class="card-header justify-content-between">
                                <div class="card-title">
                                    دسترسی‌های سریع
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <a href="users_list.php" class="text-dark d-block py-2">
                                            <i class="ri-user-line me-2 text-primary"></i>
                                            مدیریت کاربران
                                        </a>
                                    </li>
                                    <li class="list-group-item">
                                        <a href="profile-settings.php" class="text-dark d-block py-2">
                                            <i class="ri-settings-3-line me-2 text-success"></i>
                                            تنظیمات پروفایل
                                        </a>
                                    </li>
                                    <li class="list-group-item">
                                        <a href="dashboard.php" class="text-dark d-block py-2">
                                            <i class="ri-dashboard-line me-2 text-info"></i>
                                            بازگشت به داشبورد
                                        </a>
                                    </li>
                                    <li class="list-group-item">
                                        <a href="logout.php" class="text-dark d-block py-2">
                                            <i class="ri-logout-box-r-line me-2 text-danger"></i>
                                            خروج از سیستم
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card custom-card">
                    <div class="card-header">
                        <ul class="nav nav-pills mb-0 gap-2" id="profile-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link bg-light active" id="about-tab" data-bs-toggle="tab" data-bs-target="#about-tab-pane" type="button" role="tab">
                                    <i class="ri-information-line me-1 fs-16"></i>اطلاعات حساب
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link bg-light" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity-tab-pane" type="button" role="tab">
                                    <i class="ri-history-line me-1 fs-16"></i>تاریخچه فعالیت
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body p-0">
                        <div class="tab-content" id="profile-tabs-content">
                            <!-- تب اطلاعات حساب -->
                            <div class="tab-pane show active p-4 border-0" id="about-tab-pane" role="tabpanel" aria-labelledby="about-tab" tabindex="0">
                                <div class="mb-4">
                                    <h6 class="fw-semibold mb-3">جزئیات حساب کاربری</h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <p class="mb-1 text-muted">شناسه کاربری:</p>
                                            <p class="fw-medium">#<?= $admin_data['id'] ?></p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <p class="mb-1 text-muted">تاریخ ایجاد:</p>
                                            <p class="fw-medium"><?= jalali_date($admin_data['created_at']) ?></p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <p class="mb-1 text-muted">آخرین بروزرسانی:</p>
                                            <p class="fw-medium"><?= jalali_date($admin_data['updated_at']) ?></p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <p class="mb-1 text-muted">وضعیت حساب:</p>
                                            <span class="badge bg-success">فعال</span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h6 class="fw-semibold mb-3">اطلاعات تماس</h6>
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="avatar avatar-sm avatar-rounded bg-light text-muted me-3">
                                            <i class="ri-phone-line"></i>
                                        </span>
                                        <div>
                                            <p class="mb-0 fw-medium">شماره تماس</p>
                                            <p class="mb-0 text-muted"><?= htmlspecialchars($admin_data['phone']) ?></p>
                                        </div>
                                    </div>

                                    <?php if ($admin_data['email']): ?>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar avatar-sm avatar-rounded bg-light text-muted me-3">
                                                <i class="ri-mail-line"></i>
                                            </span>
                                            <div>
                                                <p class="mb-0 fw-medium">آدرس ایمیل</p>
                                                <p class="mb-0 text-muted"><?= htmlspecialchars($admin_data['email']) ?></p>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- تب تاریخچه فعالیت -->
                            <div class="tab-pane p-4 border-0" id="activity-tab-pane" role="tabpanel" aria-labelledby="activity-tab" tabindex="0">
                                <h6 class="fw-semibold mb-3">فعالیت‌های اخیر</h6>

                                <div class="alert alert-info">
                                    <div class="d-flex">
                                        <div class="me-3">
                                            <i class="ri-information-line fs-20"></i>
                                        </div>
                                        <div class="flex-fill">
                                            <h6 class="alert-heading">اطلاعات سیستم</h6>
                                            <p class="mb-0">تاریخچه فعالیت‌های شما به زودی در این بخش نمایش داده خواهد شد.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center py-4">
                                    <i class="ri-history-line fs-48 text-muted mb-3"></i>
                                    <p class="text-muted">هیچ فعالیتی برای نمایش وجود ندارد.</p>
                                    <a href="dashboard.php" class="btn btn-primary btn-sm">شروع فعالیت</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="ri-shield-check-line me-1"></i>
                                حساب شما توسط سیستم محافظت می‌شود
                            </small>
                            <a href="profile-settings.php" class="btn btn-primary btn-sm">
                                <i class="ri-edit-box-line me-1"></i>
                                ویرایش پروفایل
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End:: row-1 -->
    </div>
</div>
<!-- END MAIN-CONTENT -->

<?php require_once "views/panel/footer.php"; ?>
