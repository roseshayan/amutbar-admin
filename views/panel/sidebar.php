<!-- SIDEBAR -->

<?php
require_once __DIR__ . '/../../includes/settings.php';
$siteLogoPath = (string)settings_get('site.logo_path', '');
$sidebarLogoHref = $siteLogoPath !== '' ? $siteLogoPath : 'assets/images/brand-logos/logo.png';
?>

<aside class="app-sidebar sticky" id="sidebar">

    <!-- Start::main-sidebar-header -->
    <div class="main-sidebar-header">
        <a href="dashboard.php" class="header-logo">
            <img src="<?= htmlspecialchars($sidebarLogoHref, ENT_QUOTES, 'UTF-8') ?>" alt="logo" class="desktop-logo">
            <img src="<?= htmlspecialchars($sidebarLogoHref, ENT_QUOTES, 'UTF-8') ?>" alt="logo" class="toggle-dark">
            <img src="<?= htmlspecialchars($sidebarLogoHref, ENT_QUOTES, 'UTF-8') ?>" alt="logo" class="desktop-dark">
            <img src="<?= htmlspecialchars($sidebarLogoHref, ENT_QUOTES, 'UTF-8') ?>" alt="logo" class="toggle-logo">
        </a>
    </div>
    <!-- End::main-sidebar-header -->

    <!-- Start::main-sidebar -->
    <div class="main-sidebar" id="sidebar-scroll">

        <!-- Start::nav -->
        <nav class="main-menu-container nav nav-pills flex-column sub-open">
            <div class="slide-left" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                    viewbox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                </svg>
            </div>
            <ul class="main-menu">
                <!-- Start::slide__category -->
                <li class="slide__category"><span class="category-name">داشبورد پنل مدیریت</span></li>
                <!-- End::slide__category -->

                <!-- Start::slide -->
                <li class="slide">
                    <a href="dashboard.php" class="side-menu__item">
                        <span class="side-menu__label"><i class="bi bi-house"></i> داشبورد </span>
                    </a>
                </li>
                <!-- End::slide -->

                <!-- Start::slide -->
                <li class="slide has-sub">
                    <a href="javascript:void(0);" class="side-menu__item">
                        <i class="ri-arrow-right-s-line side-menu__angle"></i>
                        <i class="bi bi-person side-menu__icon"></i>
                        <span class="side-menu__label">کاربران</span>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide side-menu__label1">
                            <a href="javascript:void(0)">کاربران</a>
                        </li>
                        <li class="slide">
                            <a href="users_list.php" class="side-menu__item">لیست کاربران</a>
                        </li>
                        <li class="slide">
                            <a href="user_create.php" class="side-menu__item">افزودن کاربر</a>
                        </li>
                    </ul>
                </li>
                <!-- End::slide -->

                <li class="slide">
                    <a href="vehicle-types.php" class="side-menu__item">
                        <i class="ri-truck-line side-menu__icon"></i>
                        <span class="side-menu__label">وسیله‌های نقلیه</span>
                    </a>
                </li>

                <!-- Start::slide__category -->
                <li class="slide__category"><span class="category-name">ابزارها و تنظیمات</span></li>
                <!-- End::slide__category -->

                <li class="slide">
                    <a href="media-library.php" class="side-menu__item">
                        <i class="ri-image-2-line side-menu__icon"></i>
                        <span class="side-menu__label">مدیریت رسانه</span>
                    </a>
                </li>

                <li class="slide">
                    <a href="banners_list.php" class="side-menu__item">
                        <i class="ri-image-2-line side-menu__icon"></i>
                        <span class="side-menu__label">مدیریت بنر های اپ</span>
                    </a>
                </li>

                <!-- Start::slide -->
                <li class="slide">
                    <a href="external-apis.php" class="side-menu__item">
                        <i class="bi bi-link side-menu__icon"></i>
                        <span class="side-menu__label">API ها</span>
                    </a>
                </li>

                <li class="slide">
                    <a href="internal-apis.php" class="side-menu__item">
                        <i class="bi bi-link-45deg ri-sliders-line side-menu__icon"></i>
                        <span class="side-menu__label">API های داخلی</span>
                    </a>
                </li>
                <!-- End::slide -->

                <!-- Start::slide -->
                <li class="slide">
                    <a href="identity-services.php" class="side-menu__item">
                        <i class="ri-shield-check-line side-menu__icon"></i>
                        <span class="side-menu__label">سرویس‌های احراز هویت</span>
                    </a>
                </li>
                <!-- End::slide -->

                <!-- Start::slide -->
                <li class="slide">
                    <a href="site-settings.php" class="side-menu__item">
                        <i class="ri-settings-3-line side-menu__icon"></i>
                        <span class="side-menu__label">تنظیمات سایت/اپ</span>
                    </a>
                </li>
                <!-- End::slide -->

                <!-- Start::slide -->
                <li class="slide">
                    <a href="auth-report.php" class="side-menu__item">
                        <i class="ri-file-chart-line side-menu__icon"></i>
                        <span class="side-menu__label">گزارش ورود/خروج</span>
                    </a>
                </li>
                <!-- End::slide -->

            </ul>
            <div class="slide-right" id="slide-right">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                    viewbox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                </svg>
            </div>
        </nav>
        <!-- End::nav -->

    </div>
    <!-- End::main-sidebar -->

</aside>
<!-- END SIDEBAR -->