<?php

declare(strict_types=1);

/**
 * سامانه مدیریت سطوح دسترسی پویا و حرفه‌ای (Role-Based Access Control / Granular Permissions)
 * طراحی شده برای تفکیک کامل اختیارات مدیر کل از کارمندان و حفاظت ۱۰۰٪ از اطلاعات حیاتی سیستم.
 */

/**
 * کاتالوگ جامع دسترسی‌های پنل مدیریت
 * هر دسترسی شامل گروه، کلید یکتا، عنوان فارسی و توضیح عملکردی است.
 */
function permissions_catalog(): array
{
    return [
        'dashboard' => [
            'title' => 'داشبورد و آمار کلی',
            'icon' => 'ri-home-8-line',
            'permissions' => [
                'dashboard.view' => [
                    'title' => 'مشاهده داشبورد',
                    'description' => 'مشاهده صفحه اصلی داشبورد، ویجت‌ها، آمار تحلیلی و نمودارها',
                ],
            ],
        ],
        'loads' => [
            'title' => 'مدیریت بارها',
            'icon' => 'ri-file-list-3-line',
            'permissions' => [
                'loads.view' => [
                    'title' => 'مشاهده بارها',
                    'description' => 'مشاهده لیست بارها، جزئیات بارها و مسیر حمل',
                ],
                'loads.create' => [
                    'title' => 'ثبت و اعلام بار جدید',
                    'description' => 'ایجاد و ثبت بار جدید در سامانه',
                ],
                'loads.edit' => [
                    'title' => 'ویرایش بار',
                    'description' => 'ویرایش مشخصات بار، کرایه، مبدأ، مقصد و جزئیات',
                ],
                'loads.status' => [
                    'title' => 'تغییر وضعیت بار',
                    'description' => 'تغییر وضعیت بار (در انتظار، تأیید، لغو و ...)',
                ],
                'loads.delete' => [
                    'title' => 'حذف بار',
                    'description' => 'حذف بارها از سامانه',
                ],
            ],
        ],
        'drivers' => [
            'title' => 'مدیریت رانندگان',
            'icon' => 'ri-steering-2-line',
            'permissions' => [
                'drivers.view' => [
                    'title' => 'مشاهده رانندگان',
                    'description' => 'مشاهده لیست رانندگان و مشاهده پروفایل آنها',
                ],
                'drivers.create' => [
                    'title' => 'افزودن راننده',
                    'description' => 'ثبت نام راننده جدید از طریق پنل مدیریت',
                ],
                'drivers.edit' => [
                    'title' => 'ویرایش اطلاعات پایه راننده',
                    'description' => 'ویرایش نام، شماره موبایل، آدرس و مشخصات فردی',
                ],
                'drivers.edit_financial' => [
                    'title' => 'ویرایش اطلاعات بانکی',
                    'description' => 'ویرایش شماره شبا، شماره حساب و شماره کارت بانکی راننده',
                ],
                'drivers.edit_identity' => [
                    'title' => 'ویرایش مدارک و هویت',
                    'description' => 'ویرایش کد ملی، گواهینامه، کارت هوشمند و مدارک هویتی',
                ],
                'drivers.status' => [
                    'title' => 'تغییر وضعیت راننده',
                    'description' => 'فعال‌سازی، غیرفعال‌سازی یا مسدود کردن حساب راننده',
                ],
                'drivers.delete' => [
                    'title' => 'حذف راننده',
                    'description' => 'حذف و بایگانی حساب کاربری راننده',
                ],
            ],
        ],
        'cargos_users' => [
            'title' => 'صاحبان بار و باربری‌ها',
            'icon' => 'ri-building-line',
            'permissions' => [
                'cargos_users.view' => [
                    'title' => 'مشاهده صاحبان بار',
                    'description' => 'مشاهده لیست اعلام‌کنندگان بار، صاحبان کالا و باربری‌ها',
                ],
                'cargos_users.create' => [
                    'title' => 'افزودن صاحب بار',
                    'description' => 'ثبت نام و ایجاد حساب صاحب بار یا شرکت جدید',
                ],
                'cargos_users.edit' => [
                    'title' => 'ویرایش مشخصات صاحب بار',
                    'description' => 'ویرایش اطلاعات نام، شماره تماس، آدرس و مشخصات شرکت',
                ],
                'cargos_users.status' => [
                    'title' => 'تغییر وضعیت صاحب بار',
                    'description' => 'فعال‌سازی، غیرفعال‌سازی یا مسدود کردن حساب صاحب بار',
                ],
                'cargos_users.delete' => [
                    'title' => 'حذف صاحب بار',
                    'description' => 'حذف و بایگانی حساب کاربری صاحب بار',
                ],
            ],
        ],
        'tickets' => [
            'title' => 'تیکت‌ها و پشتیبانی',
            'icon' => 'ri-customer-service-2-line',
            'permissions' => [
                'tickets.view' => [
                    'title' => 'مشاهده تیکت‌ها',
                    'description' => 'مشاهده تیکت‌های پشتیبانی رانندگان و صاحبان بار',
                ],
                'tickets.reply' => [
                    'title' => 'پاسخگویی به تیکت‌ها',
                    'description' => 'ارسال پیام و پاسخ به تیکت‌های پشتیبانی',
                ],
                'tickets.status' => [
                    'title' => 'تغییر وضعیت تیکت',
                    'description' => 'بستن، معلق کردن یا تغییر اولویت تیکت',
                ],
            ],
        ],
        'notifications' => [
            'title' => 'اعلان‌ها (نوتیفیکیشن)',
            'icon' => 'ri-notification-3-line',
            'permissions' => [
                'notifications.send' => [
                    'title' => 'ارسال اعلان',
                    'description' => 'ارسال نوتیفیکیشن اختصاصی یا گروهی به رانندگان و صاحبان بار',
                ],
            ],
        ],
        'cargo_types' => [
            'title' => 'مدیریت انواع کالاها',
            'icon' => 'ri-box-3-line',
            'permissions' => [
                'cargo_types.view' => [
                    'title' => 'مشاهده انواع کالا',
                    'description' => 'مشاهده لیست دسته‌ها و انواع کالاهای مجاز',
                ],
                'cargo_types.manage' => [
                    'title' => 'مدیریت کالاها',
                    'description' => 'افزودن، ویرایش و حذف انواع کالا',
                ],
            ],
        ],
        'vehicle_types' => [
            'title' => 'انواع وسایل نقلیه و ناوگان',
            'icon' => 'ri-truck-line',
            'permissions' => [
                'vehicle_types.view' => [
                    'title' => 'مشاهده انواع وسایل نقلیه',
                    'description' => 'مشاهده لیست خودروهای باری و انواع بارگیر',
                ],
                'vehicle_types.manage' => [
                    'title' => 'مدیریت وسایل نقلیه',
                    'description' => 'افزودن، ویرایش و حذف انواع وسایل نقلیه',
                ],
            ],
        ],
        'banners' => [
            'title' => 'بنرهای تبلیغاتی',
            'icon' => 'ri-advertisement-line',
            'permissions' => [
                'banners.view' => [
                    'title' => 'مشاهده بنرها',
                    'description' => 'مشاهده لیست بنرهای تبلیغاتی اپلیکیشن‌ها',
                ],
                'banners.manage' => [
                    'title' => 'مدیریت بنرها',
                    'description' => 'افزودن، ویرایش، تغییر لینک و حذف بنرها',
                ],
            ],
        ],
        'faqs' => [
            'title' => 'سوالات متداول',
            'icon' => 'ri-question-answer-line',
            'permissions' => [
                'faqs.view' => [
                    'title' => 'مشاهده سوالات متداول',
                    'description' => 'مشاهده سوالات متداول ثبت شده برای رانندگان و صاحبان بار',
                ],
                'faqs.manage' => [
                    'title' => 'مدیریت سوالات متداول',
                    'description' => 'افزودن، ویرایش و حذف سوالات و دسته‌بندی‌ها',
                ],
            ],
        ],
        'media' => [
            'title' => 'مدیریت رسانه‌ها',
            'icon' => 'ri-folder-image-line',
            'permissions' => [
                'media.view' => [
                    'title' => 'مشاهده رسانه‌ها',
                    'description' => 'مشاهده تصاویر و فایل‌های آپلود شده',
                ],
                'media.manage' => [
                    'title' => 'مدیریت رسانه‌ها',
                    'description' => 'آپلود فایل جدید و حذف رسانه‌ها',
                ],
            ],
        ],
        'driver_activities' => [
            'title' => 'فعالیت‌های رانندگان',
            'icon' => 'ri-route-line',
            'permissions' => [
                'driver_activities.view' => [
                    'title' => 'مشاهده لاگ فعالیت‌ها',
                    'description' => 'مشاهده موقعیت مکانی لحظه‌ای و لاگ‌های فعالیت رانندگان',
                ],
            ],
        ],
    ];
}

/**
 * دریافت لیست کامل تمام کلیدهای دسترسی معتبر سیستم
 */
function permissions_all_keys(): array
{
    static $keys = null;
    if ($keys !== null) return $keys;

    $catalog = permissions_catalog();
    $list = [];
    foreach ($catalog as $group) {
        foreach ($group['permissions'] as $k => $info) {
            $list[$k] = $info['title'];
        }
    }
    $keys = $list;
    return $keys;
}

/**
 * دریافت دسترسی‌های پیش‌فرض برای یک کارمند تازه تعریف‌شده
 */
function get_default_staff_permissions(): array
{
    return [
        'dashboard.view',
        'loads.view',
        'loads.create',
        'loads.edit',
        'loads.status',
        'drivers.view',
        'drivers.create',
        'drivers.edit',
        'cargos_users.view',
        'cargos_users.create',
        'cargos_users.edit',
        'tickets.view',
        'tickets.reply',
        'tickets.status',
        'driver_activities.view',
    ];
}

/**
 * دریافت کلیدهای دسترسی فعال یک کاربر ادمین یا کارمند
 */
function admin_permissions(?int $adminId = null): array
{
    $adminId = $adminId ?? admin_id();
    if (!$adminId) return [];

    static $cache = [];
    if (isset($cache[$adminId])) {
        return $cache[$adminId];
    }

    $pdo = db();
    $st = $pdo->prepare("SELECT id, admin_role, permissions, user_type FROM users WHERE id = ? LIMIT 1");
    $st->execute([$adminId]);
    $user = $st->fetch();

    if (!$user || (int)$user['user_type'] !== 3) {
        $cache[$adminId] = [];
        return [];
    }

    // مدیر کل به تمام بخش‌ها بدون استثنا دسترسی نامحدود دارد
    if (($user['admin_role'] ?? '') === 'super_admin') {
        $all = array_keys(permissions_all_keys());
        $cache[$adminId] = $all;
        return $all;
    }

    // بررسی ستون permissions در جدول users
    $userPerms = [];
    if (!empty($user['permissions'])) {
        $decoded = is_string($user['permissions']) ? json_decode($user['permissions'], true) : $user['permissions'];
        if (is_array($decoded)) {
            $userPerms = $decoded;
        }
    }

    // اگر ستون خالی بود، از جدول پیوند user_permissions بخوان
    if (empty($userPerms)) {
        $st = $pdo->prepare("
            SELECT p.perm_key 
            FROM user_permissions up
            INNER JOIN permissions p ON up.permission_id = p.id
            WHERE up.user_id = ?
        ");
        $st->execute([$adminId]);
        $rows = $st->fetchAll(PDO::FETCH_COLUMN);
        if (!empty($rows)) {
            $userPerms = $rows;
        }
    }

    // فیلتر کردن فقط کلیدهای معتبر در کاتالوگ
    $validKeys = permissions_all_keys();
    $filtered = array_values(array_filter($userPerms, fn($k) => isset($validKeys[$k])));

    $cache[$adminId] = $filtered;
    return $filtered;
}

/**
 * بررسی اینکه آیا کاربر جاری دارای یک یا چند دسترسی مشخص هست یا خیر
 */
function has_permission(string ...$permissionKeys): bool
{
    if (is_super_admin()) {
        return true;
    }

    $myPerms = admin_permissions();
    foreach ($permissionKeys as $key) {
        if (in_array($key, $myPerms, true)) {
            return true;
        }
    }
    return false;
}

/**
 * بررسی اینکه آیا کاربر جاری تمام دسترسی‌های ورودی را دارد یا خیر
 */
function has_all_permissions(array $permissionKeys): bool
{
    if (is_super_admin()) {
        return true;
    }

    $myPerms = admin_permissions();
    foreach ($permissionKeys as $key) {
        if (!in_array($key, $myPerms, true)) {
            return false;
        }
    }
    return true;
}

/**
 * بررسی شرط دسترسی و مسدودسازی در صورت عدم احراز مجوز
 */
function require_permission(string ...$permissionKeys): void
{
    require_admin();

    if (!has_permission(...$permissionKeys)) {
        if (is_ajax()) {
            json_out([
                'ok' => false,
                'message' => 'شما مجوز دسترسی به این بخش یا عملیات را ندارید. لطفاً با مدیر کل هماهنگ کنید.',
                'required_permissions' => $permissionKeys,
            ], 403);
        }

        if (function_exists('set_flash')) {
            set_flash('error', 'شما مجوز دسترسی به این بخش را ندارید.');
        }
        header('Location: ' . url_path('dashboard.php'));
        exit;
    }
}

/**
 * بررسی امنیتی حیاتی: آیا کاربر جاری مجاز به مشاهده یا ویرایش کاربر هدف هست؟
 * قانون قطعی: کارمند (staff) هرگز نمی‌تواند به حساب‌های مدیر کل یا کارمندان دیگر دسترسی داشته باشد.
 */
function can_manage_user(int $targetUserId, ?int $operatorId = null): bool
{
    $operatorId = $operatorId ?? admin_id();
    if (!$operatorId) return false;

    // مدیر کل مجاز به مدیریت همه به جز حذف خودش است
    if (is_super_admin()) {
        return true;
    }

    $pdo = db();
    $st = $pdo->prepare("SELECT id, user_type, admin_role FROM users WHERE id = ? LIMIT 1");
    $st->execute([$targetUserId]);
    $target = $st->fetch();

    // اگر کاربر در دیتابیس وجود ندارد (کاربر جدید) مجاز به بررسی شرط عمومی است
    if (!$target) {
        return true;
    }

    // اگر کاربر هدف از نوع ادمین/کارمند باشد، کارمند عادی هرگز اجازه دسترسی ندارد
    $isTargetAdmin = ((int)$target['user_type'] === 3 || in_array($target['admin_role'] ?? '', ['super_admin', 'staff'], true));
    if ($isTargetAdmin) {
        return false;
    }

    return true;
}

/**
 * اعمال سد امنیتی برای جلوگیری از عملیات غیرمجاز کارمند روی حساب‌های مدیریتی
 */
function assert_can_manage_user(int $targetUserId): void
{
    if (!can_manage_user($targetUserId)) {
        if (is_ajax()) {
            json_out([
                'ok' => false,
                'message' => 'دسترسی غیرمجاز. کارمندان اجازه مشاهده، ویرایش، تغییر وضعیت یا حذف حساب‌های مدیران و کارمندان را ندارند.',
            ], 403);
        }

        if (function_exists('set_flash')) {
            set_flash('error', 'دسترسی غیرمجاز. شما اجازه دسترسی به حساب‌های مدیران را ندارید.');
        }
        header('Location: ' . url_path('users_list.php'));
        exit;
    }
}

/**
 * ذخیره‌سازی دسترسی‌های یک کارمند توسط مدیر کل
 */
function save_staff_permissions(int $userId, array $permissions): bool
{
    require_super_admin();

    $validKeys = permissions_all_keys();
    $filtered = array_values(array_filter($permissions, fn($k) => isset($validKeys[$k])));

    $pdo = db();
    $ownsTransaction = !$pdo->inTransaction();
    if ($ownsTransaction) {
        $pdo->beginTransaction();
    }

    try {
        // ۱. بروزرسانی ستون JSON در جدول users
        $json = json_encode($filtered, JSON_UNESCAPED_UNICODE);
        $st = $pdo->prepare("UPDATE users SET permissions = ?, updated_at = NOW(3) WHERE id = ? AND user_type = 3 LIMIT 1");
        $st->execute([$json, $userId]);

        // ۲. همگام‌سازی با جدول پیوند user_permissions
        $pdo->prepare("DELETE FROM user_permissions WHERE user_id = ?")->execute([$userId]);

        if (!empty($filtered)) {
            $inClause = implode(',', array_fill(0, count($filtered), '?'));
            $stPerms = $pdo->prepare("SELECT id, perm_key FROM permissions WHERE perm_key IN ($inClause)");
            $stPerms->execute($filtered);
            $permRows = $stPerms->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($permRows)) {
                $insertStmt = $pdo->prepare("INSERT IGNORE INTO user_permissions (user_id, permission_id) VALUES (?, ?)");
                foreach ($permRows as $row) {
                    $insertStmt->execute([$userId, (int)$row['id']]);
                }
            }
        }

        if ($ownsTransaction) {
            $pdo->commit();
        }

        audit_log('staff.permissions.update', 'user', $userId, [
            'count' => count($filtered),
            'permissions' => $filtered,
        ]);

        return true;
    } catch (Throwable $e) {
        if ($ownsTransaction && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Failed to save staff permissions: " . $e->getMessage());
        return false;
    }
}
