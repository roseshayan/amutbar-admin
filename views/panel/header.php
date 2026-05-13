<?php
require_once __DIR__ . '/../../includes/init.php';
require_admin();
require_once __DIR__ . '/../../includes/settings.php';
$admin_id = $_SESSION['admin_user_id'];
$admin_info = users_get($admin_id);
$admin_name = !empty($admin_info['display_name']) ? $admin_info['display_name'] : $admin_info['full_name'];
$admin_avatar = !empty($admin_info['avatar_key']) ? (string)$admin_info['avatar_key'] : 'assets/images/faces/21.jpg';
$themeMode = (string)($admin_info['theme_mode'] ?? 'light');
$themeMode = in_array($themeMode, ['light', 'dark'], true) ? $themeMode : 'light';
if ($admin_avatar !== '' && !preg_match('~^https?://~i', $admin_avatar) && $admin_avatar[0] !== '/') {
    $admin_avatar = '/' . ltrim($admin_avatar, '/');
}

// Site branding
$siteLogoPath = (string)settings_get('site.logo_path', '');
$logoDesktop = $siteLogoPath !== '' ? $siteLogoPath : 'assets/images/brand-logos/desktop-logo.png';
$logoToggle  = $siteLogoPath !== '' ? $siteLogoPath : 'assets/images/brand-logos/toggle-logo.png';
$logoDark    = $siteLogoPath !== '' ? $siteLogoPath : 'assets/images/brand-logos/desktop-dark.png';
$logoToggleDark = $siteLogoPath !== '' ? $siteLogoPath : 'assets/images/brand-logos/toggle-dark.png';
?>
<!DOCTYPE html>
<html lang="fa" data-nav-layout="vertical" data-theme-mode="<?= $themeMode ?>" data-header-styles="<?= $themeMode ?>" data-width="fullwidth"
    data-menu-styles="<?= $themeMode ?>" data-toggled="close" dir="rtl">

<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="Description" content="پنل مدیریت آموت اپ">
    <meta name="Author" content="Shayan Namayandeh - namayandeshayan@gmail.com - KFMY Company">

    <!-- TITLE -->
    <title>پنل مدیریت آموت اپ</title>

    <!-- FAVICON -->
    <?php
    $faviconPath = (string)settings_get('site.favicon_path', '');
    $faviconHref = $faviconPath !== '' ? $faviconPath : 'assets/images/brand-logos/favicon.ico';
    if ($faviconHref !== '' && !preg_match('~^https?://~i', $faviconHref) && $faviconHref[0] !== '/') {
        $faviconHref = '/' . ltrim($faviconHref, '/');
    }
    ?>
    <link rel="icon" href="<?= htmlspecialchars($faviconHref, ENT_QUOTES, 'UTF-8') ?>" type="image/x-icon">

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="assets/vendor/bootstrap/bootstrap.rtl.min.css" rel="stylesheet">

    <!-- STYLES CSS -->
    <link href="assets/css/styles.min.css" rel="stylesheet">

    <!-- ICONS CSS -->
    <link href="assets/icon-fonts/icons.css" rel="stylesheet">

    <!-- NODE WAVES CSS -->
    <link href="assets/libs/node-waves/waves.min.css" rel="stylesheet">

    <!-- SIMPLEBAR CSS -->
    <link rel="stylesheet" href="assets/libs/simplebar/simplebar.min.css">

    <!-- PICKER CSS -->
    <link rel="stylesheet" href="assets/libs/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" href="assets/libs/%40simonwep/pickr/themes/nano.min.css">

    <!-- AUTO COMPLETE CSS -->
    <link rel="stylesheet" href="assets/libs/%40tarekraafat/autocomplete.js/css/autoComplete.css">

    <!-- CHOICES CSS -->
    <link rel="stylesheet" href="assets/libs/choices.js/public/assets/styles/choices.min.css">

    <!-- Datatable -->
    <link rel="stylesheet" href="assets/vendor/datatables/dataTables.bootstrap5.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="assets/vendor/sweetalert2/sweetalert2.min.css">

    <!-- Toastify CSS -->
    <link rel="stylesheet" href="assets/vendor/toastify-js/toastify.css">

    <!-- jQuery -->
    <script src="assets/vendor/jquery/jquery.min.js"></script>

    <!-- CHOICES JS -->
    <script src="assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>

    <!-- Toastify JS -->
    <script src="assets/vendor/toastify-js/toastify.js"></script>

    <!-- MAIN JS -->
    <script src="assets/js/main.js"></script>
</head>

<body>

    <!-- LOADER -->
    <div id="loader">
        <img src="assets/images/media/loader.svg" alt="">
    </div>
    <!-- END LOADER -->

    <!-- PAGE -->
    <div class="page">
        <!-- HEADER -->
        <header class="app-header sticky" id="header">
            <!-- Start::main-header-container -->
            <div class="main-header-container container-fluid">
                <!-- Start::header-content-left -->
                <div class="header-content-left">
                    <!-- Start::header-element -->
                    <div class="header-element">
                        <div class="horizontal-logo">
                            <a href="dashboard.php" class="header-logo">
                                <img src="<?= htmlspecialchars($logoDesktop, ENT_QUOTES, 'UTF-8') ?>" alt="logo" class="desktop-logo">
                                <img src="<?= htmlspecialchars($logoToggle, ENT_QUOTES, 'UTF-8') ?>" alt="logo" class="toggle-logo">
                                <img src="<?= htmlspecialchars($logoDark, ENT_QUOTES, 'UTF-8') ?>" alt="logo" class="desktop-dark">
                                <img src="<?= htmlspecialchars($logoToggleDark, ENT_QUOTES, 'UTF-8') ?>" alt="logo" class="toggle-dark">
                            </a>
                        </div>
                    </div>
                    <!-- End::header-element -->
                    <!-- Start::header-element -->
                    <div class="header-element">
                        <!-- Start::header-link -->
                        <a aria-label="Hide Sidebar" class="sidemenu-toggle header-link" data-bs-toggle="sidebar"
                            href="javascript:void(0);">
                            <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon menu-btn" width="32" height="32"
                                fill="#000000" viewbox="0 0 256 256">
                                <path d="M224,128a8,8,0,0,1-8,8H40a8,8,0,0,1,0-16H216A8,8,0,0,1,224,128ZM40,72H216a8,8,0,0,0,0-16H40a8,8,0,0,0,0,16ZM216,184H40a8,8,0,0,0,0,16H216a8,8,0,0,0,0-16Z">
                                </path>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon menu-btn-close" width="32"
                                height="32" fill="#000000" viewbox="0 0 256 256">
                                <path d="M205.66,194.34a8,8,0,0,1-11.32,11.32L128,139.31,61.66,205.66a8,8,0,0,1-11.32-11.32L116.69,128,50.34,61.66A8,8,0,0,1,61.66,50.34L128,116.69l66.34-66.35a8,8,0,0,1,11.32,11.32L139.31,128Z">
                                </path>
                            </svg>
                        </a>
                        <!-- End::header-link -->
                    </div>
                    <!-- End::header-element -->
                </div>
                <!-- End::header-content-left -->

                <!-- Start::header-content-right -->
                <ul class="header-content-right">
                    <!-- Start::header-element -->
                    <li class="header-element d-md-none d-block">
                        <a href="javascript:void(0);" class="header-link" data-bs-toggle="modal"
                            data-bs-target="#header-responsive-search">
                            <!-- Start::header-link-icon -->
                            <i class="bi bi-search header-link-icon"></i>
                            <!-- End::header-link-icon -->
                        </a>
                    </li>
                    <!-- End::header-element -->
                    <!-- Start::header-element -->
                    <li class="header-element search-dropdown dropdown d-md-block d-none">
                        <!-- Start::header-link|dropdown-toggle -->
                        <a href="javascript:void(0);" class="header-link dropdown-toggle" data-bs-auto-close="outside"
                            data-bs-toggle="dropdown">
                            <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon" width="32" height="32"
                                fill="#000000" viewbox="0 0 256 256">
                                <path d="M228.24,219.76l-51.38-51.38a86.15,86.15,0,1,0-8.48,8.48l51.38,51.38a6,6,0,0,0,8.48-8.48ZM38,112a74,74,0,1,1,74,74A74.09,74.09,0,0,1,38,112Z">
                                </path>
                            </svg>
                        </a>
                        <ul class="main-header-dropdown dropdown-menu dropdown-menu-end overflow-visible"
                            data-popper-placement="none">
                            <li class="px-3 py-2">
                                <div class="header-element header-search d-md-block d-none my-auto">
                                    <!-- Start::header-link -->
                                    <input type="text" class="header-search-bar form-control" id="header-search"
                                        placeholder="جستجو" spellcheck="false" autocomplete="off" autocapitalize="off">
                                    <a href="javascript:void(0);" class="header-search-icon border-0">
                                        <i class="bi bi-search"></i>
                                    </a>
                                    <!-- End::header-link -->
                                </div>
                            </li>
                        </ul>
                    </li>
                    <!-- End::header-element -->

                    <!-- Start::header-element -->
                    <li class="header-element header-theme-mode">
                        <!-- Start::header-link|layout-setting -->
                        <a href="javascript:void(0);" class="header-link layout-setting">
                            <span class="light-layout">
                                <!-- Start::header-link-icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon"
                                    viewbox="0 0 256 256">
                                    <rect width="256" height="256" fill="none"></rect>
                                    <path d="M108.11,28.11A96.09,96.09,0,0,0,227.89,147.89,96,96,0,1,1,108.11,28.11Z"
                                        fill="none" stroke="currentColor" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="16"></path>
                                </svg>
                                <!-- End::header-link-icon -->
                            </span>
                            <span class="dark-layout">
                                <!-- Start::header-link-icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon"
                                    viewbox="0 0 256 256">
                                    <rect width="256" height="256" fill="none"></rect>
                                    <line x1="128" y1="40" x2="128" y2="32" fill="none" stroke="currentColor"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line>
                                    <circle cx="128" cy="128" r="56" fill="none" stroke="currentColor"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="16"></circle>
                                    <line x1="64" y1="64" x2="56" y2="56" fill="none" stroke="currentColor"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line>
                                    <line x1="64" y1="192" x2="56" y2="200" fill="none" stroke="currentColor"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line>
                                    <line x1="192" y1="64" x2="200" y2="56" fill="none" stroke="currentColor"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line>
                                    <line x1="192" y1="192" x2="200" y2="200" fill="none" stroke="currentColor"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line>
                                    <line x1="40" y1="128" x2="32" y2="128" fill="none" stroke="currentColor"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line>
                                    <line x1="128" y1="216" x2="128" y2="224" fill="none" stroke="currentColor"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line>
                                    <line x1="216" y1="128" x2="224" y2="128" fill="none" stroke="currentColor"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line>
                                </svg>
                                <!-- End::header-link-icon -->
                            </span>
                        </a>
                        <!-- End::header-link|layout-setting -->
                    </li>
                    <!-- End::header-element -->

                    <!-- Start::header-element -->
                    <li class="header-element notifications-dropdown d-xl-block d-none dropdown">
                        <!-- Start::header-link|dropdown-toggle -->
                        <a href="javascript:void(0);" class="header-link dropdown-toggle" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside" id="messageDropdown" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon animate-bell"
                                viewbox="0 0 256 256">
                                <rect width="256" height="256" fill="none"></rect>
                                <path d="M96,192a32,32,0,0,0,64,0" fill="none" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="16"></path>
                                <path d="M184,24a102.71,102.71,0,0,1,36.29,40" fill="none" stroke="currentColor"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></path>
                                <path d="M35.71,64A102.71,102.71,0,0,1,72,24" fill="none" stroke="currentColor"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></path>
                                <path d="M56,112a72,72,0,0,1,144,0c0,35.82,8.3,56.6,14.9,68A8,8,0,0,1,208,192H48a8,8,0,0,1-6.88-12C47.71,168.6,56,147.81,56,112Z"
                                    fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="16"></path>
                            </svg>
                            <span class="header-icon-pulse bg-secondary rounded pulse pulse-secondary"></span>
                        </a>
                        <!-- End::header-link|dropdown-toggle -->
                        <!-- Start::main-header-dropdown -->
                        <div class="main-header-dropdown dropdown-menu dropdown-menu-end" data-popper-placement="none">
                            <div class="p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="mb-0 fs-16">هشدارها</p>
                                    <a href="javascript:void(0);" class="badge bg-secondary-transparent"
                                        id="notifiation-data">15 خوانده نشده</a>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <div class="pb-0 px-3">
                                <ul class="nav nav-tabs mb-0 tab-style-8 scaleX" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="activity-tab" data-bs-toggle="tab"
                                            data-bs-target="#activity-tab-pane" type="button" role="tab"
                                            aria-controls="activity-tab-pane" aria-selected="true">فعالیت ها
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="notes-tab" data-bs-toggle="tab"
                                            data-bs-target="#notes-tab-pane" type="button" role="tab"
                                            aria-controls="notes-tab-pane" aria-selected="false" tabindex="-1">یادداشت
                                            ها
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="alert-tab" data-bs-toggle="tab"
                                            data-bs-target="#alert-tab-pane" type="button" role="tab"
                                            aria-controls="alert-tab-pane" aria-selected="false" tabindex="-1">هشدارها
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            <div class="dropdown-divider"></div>
                            <div class="tab-content">
                                <div class="tab-pane show active p-0 border-0" id="activity-tab-pane" role="tabpanel"
                                    aria-labelledby="activity-tab" tabindex="0">
                                    <ul class="list-unstyled mb-0" id="header-notification-scroll1">
                                        <li class="dropdown-item">
                                            <div class="d-flex align-items-start">
                                                <div class="pe-2 lh-1"> <span
                                                        class="avatar avatar-md avatar-rounded svg-white">
                                                        <img src="assets/images/faces/2.jpg" alt="img"> </span>
                                                </div>
                                                <div class="flex-grow-1 d-flex align-items-start justify-content-between">
                                                    <div>
                                                        <p class="mb-0 fw-semibold">محمد</p>
                                                        <div class="fw-normal fs-13 header-notification-text text-truncate">
                                                            بروز رسانی اطلاعات سفارش
                                                        </div>
                                                        <span class="text-muted fs-12">2 دقیقه پیش</span>

                                                    </div>
                                                    <div><a href="javascript:void(0);"
                                                            class="min-w-fit-content text-muted dropdown-item-close1"><i
                                                                class="ri-close-line fs-5"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dropdown-item">
                                            <div class="d-flex align-items-start">
                                                <div class="pe-2 lh-1"> <span
                                                        class="avatar avatar-md bg-primary-transparent avatar-rounded svg-white">
                                                        SM </span>
                                                </div>
                                                <div class="flex-grow-1 d-flex align-items-start justify-content-between">
                                                    <div>
                                                        <p class="mb-0 fw-semibold">محمد</p>
                                                        <div class="fw-normal fs-13 header-notification-text text-truncate">
                                                            بروز رسانی اطلاعات سفارش
                                                        </div>
                                                        <span class="text-muted fs-12">5 دقیقه پیش</span>

                                                    </div>
                                                    <div><a href="javascript:void(0);"
                                                            class="min-w-fit-content text-muted dropdown-item-close1"><i
                                                                class="ri-close-line fs-5"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dropdown-item">
                                            <div class="d-flex align-items-start">
                                                <div class="pe-2 lh-1"> <span
                                                        class="avatar avatar-md avatar-rounded svg-white">
                                                        <img src="assets/images/faces/6.jpg" alt="img"> </span>
                                                </div>
                                                <div class="flex-grow-1 d-flex align-items-start justify-content-between">
                                                    <div>
                                                        <p class="mb-0 fw-semibold">محمد</p>
                                                        <div class="fw-normal fs-13 header-notification-text text-truncate">
                                                            بروز رسانی اطلاعات سفارش
                                                        </div>
                                                        <span class="text-muted fs-12">دیروز</span>
                                                    </div>
                                                    <div><a href="javascript:void(0);"
                                                            class="min-w-fit-content text-muted dropdown-item-close1"><i
                                                                class="ri-close-line fs-5"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dropdown-item">
                                            <div class="d-flex align-items-start">
                                                <div class="pe-2 lh-1"> <span
                                                        class="avatar avatar-md bg-secondary-transparent avatar-rounded svg-white">
                                                        TR </span>
                                                </div>
                                                <div class="flex-grow-1 d-flex align-items-start justify-content-between">
                                                    <div>
                                                        <p class="mb-0 fw-semibold">محمد</p>
                                                        <div class="fw-normal fs-13 header-notification-text text-truncate">
                                                            بروز رسانی اطلاعات سفارش
                                                        </div>
                                                        <span class="text-muted fs-12">2 روز پیش</span>
                                                    </div>
                                                    <div><a href="javascript:void(0);"
                                                            class="min-w-fit-content text-muted dropdown-item-close1"><i
                                                                class="ri-close-line fs-5"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dropdown-item">
                                            <div class="d-flex align-items-start">
                                                <div class="pe-2 lh-1"> <span
                                                        class="avatar avatar-md avatar-rounded svg-white">
                                                        <img src="assets/images/faces/14.jpg" alt="img"> </span>
                                                </div>
                                                <div class="flex-grow-1 d-flex align-items-start justify-content-between">
                                                    <div>
                                                        <p class="mb-0 fw-semibold">محمد</p>
                                                        <div class="fw-normal fs-13 header-notification-text text-truncate">
                                                            بروز رسانی اطلاعات سفارش
                                                        </div>
                                                        <span class="text-muted fs-12">1 ساعت پیش</span>
                                                    </div>
                                                    <div><a href="javascript:void(0);"
                                                            class="min-w-fit-content text-muted dropdown-item-close1"><i
                                                                class="ri-close-line fs-5"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tab-pane p-0 border-0" id="notes-tab-pane" role="tabpanel"
                                    aria-labelledby="notes-tab" tabindex="0">
                                    <ul class="list-unstyled mb-0" id="header-notification-scroll2">
                                        <li class="dropdown-item">
                                            <div class="d-flex align-items-start">
                                                <div class="pe-2 lh-1"> <span
                                                        class="avatar avatar-md avatar-rounded bg-primary">
                                                        <i class="ri-file-text-line fs-16"></i> </span>
                                                </div>
                                                <div class="flex-grow-1 d-flex align-items-start justify-content-between">
                                                    <div>
                                                        <p class="mb-0 fw-semibold"><a href="javascript:void(0);">
                                                                محمد</a></p>
                                                        <div class="fw-normal fs-13 header-notification-text text-truncate">
                                                            بروز رسانی اطلاعات سفارش
                                                        </div>
                                                        <span class="fs-13 text-muted">2 دقیقه پیش</span>
                                                    </div>
                                                    <div>
                                                        <a href="javascript:void(0);"
                                                            class="min-w-fit-content text-muted dropdown-item-close1"><i
                                                                class="ri-close-line fs-5"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dropdown-item">
                                            <div class="d-flex align-items-start">
                                                <div class="pe-2 lh-1"> <span
                                                        class="avatar avatar-md avatar-rounded bg-secondary">
                                                        <i class="ri-box-3-line fs-16"></i></span>
                                                </div>
                                                <div class="flex-grow-1 d-flex align-items-start justify-content-between">
                                                    <div>
                                                        <p class="mb-0 fw-semibold">محمد</p>
                                                        <div class="fw-normal fs-13 header-notification-text text-truncate">
                                                            بروز رسانی اطلاعات سفارش
                                                        </div>
                                                        <span class="fs-13 text-muted">2 دقیقه پیش</span>
                                                    </div>
                                                    <div><a href="javascript:void(0);"
                                                            class="min-w-fit-content text-muted dropdown-item-close1"><i
                                                                class="ri-close-line fs-5"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dropdown-item">
                                            <div class="d-flex align-items-start">
                                                <div class="pe-2 lh-1"> <span
                                                        class="avatar avatar-md avatar-rounded bg-success">
                                                        <i class="ri-mail-open-line fs-16"></i> </span>
                                                </div>
                                                <div class="flex-grow-1 d-flex align-items-start justify-content-between">
                                                    <div>
                                                        <p class="mb-0 fw-semibold"><a href="javascript:void(0);">
                                                                محسن</a></p>
                                                        <div class="fw-normal fs-13 header-notification-text text-truncate">
                                                            بروز رسانی سفارش
                                                        </div>
                                                        <span class="fs-13 text-muted">10 روز پیش</span>
                                                    </div>
                                                    <div><a href="javascript:void(0);"
                                                            class="min-w-fit-content text-muted dropdown-item-close1"><i
                                                                class="ri-close-line fs-5"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dropdown-item">
                                            <div class="d-flex align-items-start">
                                                <div class="pe-2 lh-1"> <span
                                                        class="avatar avatar-md avatar-rounded bg-info">
                                                        <i class="ri-bank-card-line fs-16"></i> </span>
                                                </div>
                                                <div class="flex-grow-1 d-flex align-items-start justify-content-between">
                                                    <div>
                                                        <p class="mb-0 fw-semibold"><a href="javascript:void(0);">
                                                                محمد</a></p>
                                                        <div class="fw-normal fs-13 header-notification-text text-truncate">
                                                            بروزرسانی
                                                        </div>
                                                        <span class="fs-13 text-muted">دیروز</span>
                                                    </div>
                                                    <div><a href="javascript:void(0);"
                                                            class="min-w-fit-content text-muted dropdown-item-close1"><i
                                                                class="ri-close-line fs-5"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dropdown-item">
                                            <div class="d-flex align-items-start">
                                                <div class="pe-2 lh-1"> <span
                                                        class="avatar avatar-md avatar-rounded bg-warning">
                                                        <i class="ri-group-line fs-16"></i> </span>
                                                </div>
                                                <div class="flex-grow-1 d-flex align-items-start justify-content-between">
                                                    <div>
                                                        <p class="mb-0 fw-semibold"><a href="javascript:void(0);">
                                                                احسان</a></p>
                                                        <div class="fw-normal fs-13 header-notification-text text-truncate">
                                                            بروزرسانی
                                                        </div>
                                                        <span class="fs-13 text-muted">2 روز پیش</span>
                                                    </div>
                                                    <div><a href="javascript:void(0);"
                                                            class="min-w-fit-content text-muted dropdown-item-close1"><i
                                                                class="ri-close-line fs-5"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tab-pane p-0 border-0" id="alert-tab-pane" role="tabpanel"
                                    aria-labelledby="alert-tab" tabindex="0">
                                    <ul class="list-unstyled mb-0" id="header-notification-scroll3">
                                        <li class="dropdown-item">
                                            <div class="d-flex align-items-start">
                                                <div class="pe-2 lh-1"> <span
                                                        class="avatar avatar-md avatar-rounded bg-primary-transparent">
                                                        <i class="ri-mail-line fs-16"></i> </span>
                                                </div>
                                                <div class="flex-grow-1 d-flex align-items-start justify-content-between">
                                                    <div>
                                                        <p class="mb-0 fw-semibold"><a href="javascript:void(0);">
                                                                محمد</a></p>
                                                        <div class="fw-normal fs-13 header-notification-text text-truncate">
                                                            بروزرسانی
                                                        </div>
                                                        <span class="fs-13 text-muted">2 دقیقه پیش</span>
                                                    </div>
                                                    <div><a href="javascript:void(0);"
                                                            class="min-w-fit-content text-muted dropdown-item-close1"><i
                                                                class="ri-close-line fs-5"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dropdown-item">
                                            <div class="d-flex align-items-start">
                                                <div class="pe-2 lh-1"> <span
                                                        class="avatar avatar-md avatar-rounded bg-secondary-transparent">
                                                        <i class="ri-folder-warning-line fs-16"></i></span>
                                                </div>
                                                <div class="flex-grow-1 d-flex align-items-start justify-content-between">
                                                    <div>
                                                        <p class="mb-0 fw-semibold"><a href="javascript:void(0);">
                                                                محمد</a></p>
                                                        <div class="fw-normal fs-13 header-notification-text text-truncate">
                                                            بروزرسانی
                                                        </div>
                                                        <span class="fs-13 text-muted">15 دقیقه پیش</span>
                                                    </div>
                                                    <div><a href="javascript:void(0);"
                                                            class="min-w-fit-content text-muted dropdown-item-close1"><i
                                                                class="ri-close-line fs-5"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dropdown-item">
                                            <div class="d-flex align-items-start">
                                                <div class="pe-2 lh-1"> <span
                                                        class="avatar avatar-md avatar-rounded bg-success-transparent">
                                                        <i class="ri-database-2-line fs-16"></i> </span>
                                                </div>
                                                <div class="flex-grow-1 d-flex align-items-start justify-content-between">
                                                    <div>
                                                        <p class="mb-0 fw-semibold"><a href="javascript:void(0);">
                                                                محمد</a></p>
                                                        <div class="fw-normal fs-13 header-notification-text text-truncate">
                                                            بروزرسانی
                                                        </div>
                                                        <span class="fs-13 text-muted">23 دقیقه پیش</span>
                                                    </div>
                                                    <div><a href="javascript:void(0);"
                                                            class="min-w-fit-content text-muted dropdown-item-close1"><i
                                                                class="ri-close-line fs-5"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dropdown-item">
                                            <div class="d-flex align-items-start">
                                                <div class="pe-2 lh-1"> <span
                                                        class="avatar avatar-md avatar-rounded bg-info-transparent">
                                                        <i class="ri-bank-card-line fs-16"></i> </span>
                                                </div>
                                                <div class="flex-grow-1 d-flex align-items-start justify-content-between">
                                                    <div>
                                                        <p class="mb-0 fw-semibold"><a href="javascript:void(0);">
                                                                محمد</a></p>
                                                        <div class="fw-normal fs-13 header-notification-text text-truncate">
                                                            بروزرسانی
                                                        </div>
                                                        <span class="fs-13 text-muted">دیروز</span>
                                                    </div>
                                                    <div><a href="javascript:void(0);"
                                                            class="min-w-fit-content text-muted dropdown-item-close1"><i
                                                                class="ri-close-line fs-5"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dropdown-item">
                                            <div class="d-flex align-items-start">
                                                <div class="pe-2 lh-1"> <span
                                                        class="avatar avatar-md avatar-rounded bg-warning-transparent">
                                                        <i class="ri-stack-line fs-16"></i> </span>
                                                </div>
                                                <div class="flex-grow-1 d-flex align-items-start justify-content-between">
                                                    <div>
                                                        <p class="mb-0 fw-semibold"><a href="javascript:void(0);">
                                                                محمد</a></p>
                                                        <div class="fw-normal fs-13 header-notification-text text-truncate">
                                                            بروزرسانی
                                                        </div>
                                                        <span class="fs-13 text-muted">2 روز پیش</span>
                                                    </div>
                                                    <div><a href="javascript:void(0);"
                                                            class="min-w-fit-content text-muted dropdown-item-close1"><i
                                                                class="ri-close-line fs-5"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="p-3 empty-header-item1 border-top">
                                <div class="d-grid text-center">
                                    <a href="checkout.html" class="text-primary text-decoration-underline">مشاهده همه<i
                                            class="ri-arrow-right-line"></i></a>
                                </div>
                            </div>
                            <div class="p-5 empty-item1 d-none">
                                <div class="text-center">
                                    <span class="avatar avatar-xl avatar-rounded bg-secondary-transparent">
                                        <i class="ri-notification-off-line fs-2"></i>
                                    </span>
                                    <h6 class="fw-medium mt-3">هشداری وجود ندارد</h6>
                                </div>
                            </div>
                        </div>
                        <!-- End::main-header-dropdown -->
                    </li>
                    <!-- End::header-element -->

                    <!-- Start::header-element -->
                    <li class="header-element header-fullscreen">
                        <!-- Start::header-link -->
                        <a onclick="openFullscreen();" href="javascript:void(0);" class="header-link">
                            <svg xmlns="http://www.w3.org/2000/svg" class="full-screen-open header-link-icon"
                                viewbox="0 0 256 256">
                                <rect width="256" height="256" fill="none"></rect>
                                <polyline points="168 48 208 48 208 88" fill="none" stroke="currentColor"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></polyline>
                                <polyline points="88 208 48 208 48 168" fill="none" stroke="currentColor"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></polyline>
                                <polyline points="208 168 208 208 168 208" fill="none" stroke="currentColor"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></polyline>
                                <polyline points="48 88 48 48 88 48" fill="none" stroke="currentColor"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></polyline>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="full-screen-close header-link-icon d-none"
                                viewbox="0 0 256 256">
                                <rect width="256" height="256" fill="none"></rect>
                                <polyline points="160 48 208 48 208 96" fill="none" stroke="currentColor"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></polyline>
                                <line x1="144" y1="112" x2="208" y2="48" fill="none" stroke="currentColor"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line>
                                <polyline points="96 208 48 208 48 160" fill="none" stroke="currentColor"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></polyline>
                                <line x1="112" y1="144" x2="48" y2="208" fill="none" stroke="currentColor"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line>
                            </svg>
                        </a>
                        <!-- End::header-link -->
                    </li>
                    <!-- End::header-element -->

                    <!-- Start::header-element -->
                    <li class="header-element dropdown">
                        <!-- Start::header-link|dropdown-toggle -->
                        <a href="javascript:void(0);" class="header-link dropdown-toggle" id="mainHeaderProfile"
                            data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <div class="me-xl-2 me-0">
                                    <img src="<?= $admin_avatar; ?>" alt="<?= $admin_name; ?>" class="avatar avatar-sm avatar-rounded">
                                </div>
                                <div class="d-xl-block d-none lh-1">
                                    <span class="fw-medium lh-1"><?= $admin_name; ?></span>
                                </div>
                            </div>
                        </a>
                        <!-- End::header-link|dropdown-toggle -->
                        <ul class="main-header-dropdown dropdown-menu pt-0 overflow-hidden header-profile-dropdown dropdown-menu-end"
                            aria-labelledby="mainHeaderProfile">
                            <li>
                                <div class="py-2 px-3 text-center"><span class="fw-semibold"> <?= $admin_name; ?> </span> <span
                                        class="d-block fs-12 text-muted"> <?= user_type_label($admin_info['user_type']); ?> </span></div>
                            </li>
                            <li><a class="dropdown-item d-flex align-items-center" href="profile.php"><i
                                        class="ti ti-user text-primary me-2 fs-16"></i>حساب کاربری</a>
                            </li>
                            <li><a class="dropdown-item d-flex align-items-center" href="settings.php"><i
                                        class="ti ti-settings text-info me-2 fs-16"></i>تنظیمات</a>
                            </li>
                            <li><a class="dropdown-item d-flex align-items-center" href="tel:+989351794610"><i
                                        class="ti ti-headset text-warning me-2 fs-16"></i>پشتیبانی</a>
                            </li>
                            <li class="py-2 px-3"><a class="btn btn-primary btn-sm w-100" href="logout.php">خروج</a>
                            </li>
                        </ul>
                    </li>
                    <!-- End::header-element -->
                </ul>
                <!-- End::header-content-right -->
            </div>
            <!-- End::main-header-container -->
        </header>

        <div class="modal fade" id="header-responsive-search" tabindex="-1" aria-labelledby="header-responsive-search"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="input-group">
                            <input type="text" class="form-control border-end-0" placeholder="جستجو" aria-label="جستجو"
                                aria-describedby="button-addon2">
                            <button class="btn btn-primary" type="button" id="button-addon2"><i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END HEADER -->