-- Migration: Staff Granular Permissions System
-- Date: 2026-10-25

-- 1. Add permissions JSON column to users table if not exists
ALTER TABLE `users`
ADD COLUMN `permissions` JSON NULL AFTER `admin_role`;

-- 2. Ensure user_permissions pivot table exists
CREATE TABLE IF NOT EXISTS `user_permissions` (
    `user_id` BIGINT UNSIGNED NOT NULL,
    `permission_id` SMALLINT UNSIGNED NOT NULL,
    PRIMARY KEY (`user_id`, `permission_id`),
    CONSTRAINT `fk_user_perms_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_user_perms_perm` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Populate permissions catalog table
INSERT IGNORE INTO `permissions` (`perm_key`, `description`) VALUES
('dashboard.view', 'مشاهده صفحه اصلی داشبورد و آمار'),
('loads.view', 'مشاهده لیست و جزئیات بارها'),
('loads.create', 'ثبت و اعلام بار جدید'),
('loads.edit', 'ویرایش اطلاعات بارها'),
('loads.status', 'تغییر وضعیت بارها'),
('loads.delete', 'حذف بارها'),
('drivers.view', 'مشاهده لیست و پروفایل رانندگان'),
('drivers.create', 'افزودن راننده جدید'),
('drivers.edit', 'ویرایش مشخصات عمومی راننده'),
('drivers.edit_financial', 'ویرایش شماره حساب و اطلاعات بانکی راننده'),
('drivers.edit_identity', 'ویرایش کد ملی و مدارک هویتی راننده'),
('drivers.status', 'تغییر وضعیت راننده (فعال/غیرفعال/مسدود)'),
('drivers.delete', 'حذف و بایگانی حساب راننده'),
('cargos_users.view', 'مشاهده لیست و پروفایل صاحبان بار'),
('cargos_users.create', 'افزودن صاحب بار جدید'),
('cargos_users.edit', 'ویرایش اطلاعات صاحب بار'),
('cargos_users.status', 'تغییر وضعیت صاحب بار (فعال/غیرفعال/مسدود)'),
('cargos_users.delete', 'حذف و بایگانی حساب صاحب بار'),
('tickets.view', 'مشاهده تیکت‌های پشتیبانی'),
('tickets.reply', 'پاسخگویی به تیکت‌های کاربران'),
('tickets.status', 'تغییر وضعیت و بستن تیکت‌ها'),
('notifications.send', 'ارسال اعلان (نوتیفیکیشن) به کاربران'),
('cargo_types.view', 'مشاهده انواع کالاها'),
('cargo_types.manage', 'مدیریت (افزودن/ویرایش/حذف) انواع کالاها'),
('vehicle_types.view', 'مشاهده انواع ناوگان و وسایل نقلیه'),
('vehicle_types.manage', 'مدیریت (افزودن/ویرایش/حذف) وسایل نقلیه'),
('banners.view', 'مشاهده بنرهای تبلیغاتی'),
('banners.manage', 'مدیریت (افزودن/ویرایش/حذف) بنرها'),
('faqs.view', 'مشاهده سوالات متداول'),
('faqs.manage', 'مدیریت (افزودن/ویرایش/حذف) سوالات متداول'),
('media.view', 'مشاهده رسانه‌ها و تصاویر'),
('media.manage', 'مدیریت (آپلود/حذف) فایل‌ها در رسانه‌دان'),
('driver_activities.view', 'مشاهده گزارش فعالیت و رهگیری رانندگان');
