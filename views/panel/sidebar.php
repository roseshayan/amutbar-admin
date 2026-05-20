<?php
require_once __DIR__ . '/../../includes/settings.php';
$siteLogoPath = (string)settings_get('site.logo_path', '');
$sidebarLogoHref = $siteLogoPath !== '' ? $siteLogoPath : 'assets/images/brand-logos/logo.png';
?>

<aside class="app-sidebar sticky" id="sidebar">

    <div class="main-sidebar-header">
        <a href="dashboard.php" class="header-logo">
            <img src="<?= htmlspecialchars($sidebarLogoHref, ENT_QUOTES, 'UTF-8') ?>" alt="logo" class="desktop-logo">
            <img src="<?= htmlspecialchars($sidebarLogoHref, ENT_QUOTES, 'UTF-8') ?>" alt="logo" class="toggle-dark">
            <img src="<?= htmlspecialchars($sidebarLogoHref, ENT_QUOTES, 'UTF-8') ?>" alt="logo" class="desktop-dark">
            <img src="<?= htmlspecialchars($sidebarLogoHref, ENT_QUOTES, 'UTF-8') ?>" alt="logo" class="toggle-logo">
        </a>
    </div>
    <div class="main-sidebar" id="sidebar-scroll">

        <nav class="main-menu-container nav nav-pills flex-column sub-open">
            <div class="slide-left" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                </svg>
            </div>
            <ul class="main-menu">

                <li class="slide__category"><span class="category-name">اصلی</span></li>
                <li class="slide">
                    <a href="dashboard.php" class="side-menu__item">
                        <i class="ri-home-8-line side-menu__icon"></i>
                        <span class="side-menu__label">داشبورد</span>
                    </a>
                </li>

                <li class="slide__category"><span class="category-name">عملیات حمل و نقل</span></li>
                <li class="slide has-sub">
                    <a href="javascript:void(0);" class="side-menu__item">
                        <i class="ri-arrow-right-s-line side-menu__angle"></i>
                        <i class="ri-file-list-3-line side-menu__icon"></i>
                        <span class="side-menu__label">مدیریت بارها</span>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide side-menu__label1">
                            <a href="javascript:void(0)">بارها</a>
                        </li>
                        <li class="slide">
                            <a href="loads_list.php" class="side-menu__item">لیست بارها</a>
                        </li>
                        <li class="slide">
                            <a href="load_create.php" class="side-menu__item">اعلام بار جدید</a>
                        </li>
                    </ul>
                </li>

                <li class="slide__category"><span class="category-name">کاربران و پشتیبانی</span></li>
                <li class="slide has-sub">
                    <a href="javascript:void(0);" class="side-menu__item">
                        <i class="ri-arrow-right-s-line side-menu__angle"></i>
                        <i class="ri-team-line side-menu__icon"></i>
                        <span class="side-menu__label">مدیریت کاربران</span>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide side-menu__label1">
                            <a href="javascript:void(0)">کاربران</a>
                        </li>
                        <li class="slide">
                            <a href="users_list.php" class="side-menu__item">لیست کاربران</a>
                        </li>
                        <li class="slide">
                            <a href="user_create.php" class="side-menu__item">افزودن کاربر جدید</a>
                        </li>
                    </ul>
                </li>
                <li class="slide">
                    <a href="support_tickets.php" class="side-menu__item">
                        <i class="ri-customer-service-2-line side-menu__icon"></i>
                        <span class="side-menu__label">تیکت‌ها و پشتیبانی</span>
                    </a>
                </li>

                <li class="slide__category"><span class="category-name">اطلاعات پایه و محتوا</span></li>
                <li class="slide">
                    <a href="cargos_list.php" class="side-menu__item">
                        <i class="ri-box-3-line side-menu__icon"></i>
                        <span class="side-menu__label">مدیریت کالاها</span>
                    </a>
                </li>
                <li class="slide">
                    <a href="vehicle-types.php" class="side-menu__item">
                        <i class="ri-truck-line side-menu__icon"></i>
                        <span class="side-menu__label">انواع وسایل نقلیه</span>
                    </a>
                </li>
                <li class="slide">
                    <a href="banners_list.php" class="side-menu__item">
                        <i class="ri-advertisement-line side-menu__icon"></i>
                        <span class="side-menu__label">بنرهای تبلیغاتی</span>
                    </a>
                </li>
                <li class="slide">
                    <a href="media-library.php" class="side-menu__item">
                        <i class="ri-folder-image-line side-menu__icon"></i>
                        <span class="side-menu__label">مدیریت رسانه‌ها</span>
                    </a>
                </li>

                <li class="slide__category"><span class="category-name">تنظیمات و توسعه‌دهندگان</span></li>
                <li class="slide">
                    <a href="site-settings.php" class="side-menu__item">
                        <i class="ri-settings-3-line side-menu__icon"></i>
                        <span class="side-menu__label">تنظیمات عمومی</span>
                    </a>
                </li>
                <li class="slide">
                    <a href="identity-services.php" class="side-menu__item">
                        <i class="ri-shield-check-line side-menu__icon"></i>
                        <span class="side-menu__label">سرویس‌های احراز هویت</span>
                    </a>
                </li>
                <li class="slide">
                    <a href="external-apis.php" class="side-menu__item">
                        <i class="ri-plug-line side-menu__icon"></i>
                        <span class="side-menu__label">API های خارجی</span>
                    </a>
                </li>
                <li class="slide">
                    <a href="internal-apis.php" class="side-menu__item">
                        <i class="ri-code-s-slash-line side-menu__icon"></i>
                        <span class="side-menu__label">API های داخلی</span>
                    </a>
                </li>

                <li class="slide__category"><span class="category-name">گزارشات</span></li>
                <li class="slide">
                    <a href="auth-report.php" class="side-menu__item">
                        <i class="ri-file-chart-line side-menu__icon"></i>
                        <span class="side-menu__label">گزارش ورود و خروج</span>
                    </a>
                </li>

            </ul>
            <div class="slide-right" id="slide-right">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                </svg>
            </div>
        </nav>
    </div>
</aside>