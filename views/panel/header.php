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

    <audio id="notifSound" src="assets/audio/ding.mp3" preload="auto"></audio>
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
                        <a href="support_tickets.php" class="header-link">
                            <i class="ri-customer-service-2-line header-link-icon"></i>
                            <span id="ticket-badge" class="badge bg-danger rounded-pill header-icon-badge pulse pulse-secondary">0</span>
                        </a>
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