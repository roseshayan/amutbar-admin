# راهنمای استقرار امن آموت‌بار

## مانع مهم بسته‌ی اولیه

آرشیو اولیه‌ی پنل پوشه‌ی `assets/` را نداشت، درحالی‌که قالب پنل به CSS،
JavaScript، فونت، تصویر و کتابخانه‌های داخل آن وابسته است. پیش از هر استقرار
باید پوشه‌ی کامل `assets/` را از نسخه‌ی فعلی سرور یا سورس اصلی قالب در ریشه
پنل قرار دهید. بدون آن، API قابل استفاده است اما رابط پنل صحیح نمایش داده
نمی‌شود.

## نصب

```bash
composer install --no-dev --prefer-dist --optimize-autoloader
cp .env.example .env
```

برای نصب تازه، ابتدا `database/schema.sql` و سپس
`database/reference_seed.sql` را روی دیتابیس خالی اجرا کنید. برای سامانه‌ی
فعال، dump را جایگزین نکنید؛ فقط migrationهای لازم را با backup و در پنجره
نگهداری اجرا کنید.

ترتیب migrationهای دیتابیس فعال:

1. `2026_07_08_000001_create_driver_activity_logs.sql`
2. `2026_07_30_000002_allow_company_location_onboarding.sql`
3. `2026_07_30_000003_add_ticket_attachment_columns.sql`
4. `2026_07_30_000004_expand_admin_audit_ip.sql`

وب‌سرور باید اجازه‌ی نوشتن محدود به `storage/logs` و `storage/uploads` بدهد.
ریشه‌ی پروژه و فایل‌های تنظیمات نباید writable عمومی باشند.

فایل‌های `.htaccess` برای Apache آماده‌اند. در Nginx باید معادل همان
قواعد را صریحاً تنظیم کنید: مسیرهای ناشناخته زیر `/api/v1/` به
`api/v1/index.php` هدایت شوند، دسترسی وب به فایل‌های dot، `.env`، SQL،
log، `includes/` و `database/` بسته باشد و هیچ فایل PHP داخل
`storage/uploads/` به PHP-FPM ارسال نشود.

## اسرار و کلیدها

همه‌ی موارد `CHANGE_ME` را با مقادیر تصادفی مستقل جایگزین کنید. برای نمونه:

```bash
openssl rand -hex 32
```

ZIP اولیه شامل تنظیمات واقعی و کلیدهای امضای موبایل بوده است؛ بنابراین
رمز دیتابیس، JWT، pepperها، کلید رمزنگاری، توکن‌های سرویس بیرونی و کلید
جدید Cargo باید خارج از کد ایجاد/تعویض شوند. کلید Driver را اگر اپ منتشر
شده است بدون بررسی سازوکار فروشگاه تعویض نکنید.

در production الزامی است:

- `APP_ENV=production`
- `APP_DEBUG=0`
- HTTPS معتبر و redirect کامل HTTP به HTTPS
- `JWT_SECRET` حداقل ۳۲ کاراکتر
- `API_ENCRYPTION_KEY` و `OTP_PEPPER` قوی و مستقل
- `API_CORS_ORIGINS` محدود به دامنه‌های واقعی

## تنظیم اپ‌ها

در «تنظیمات سایت» پنل، نسخه و لینک دانلود Driver و Cargo را جداگانه وارد
کنید. Cargo از کلیدهای `cargo.app.android.*` و `cargo.app.ios.*` استفاده
می‌کند و در نبود مقدار، برای سازگاری موقت به مقادیر Driver برمی‌گردد.

## بررسی پس از استقرار

```bash
curl -fsS https://YOUR_DOMAIN/YOUR_ADMIN_PATH/api/v1/health
curl -fsS 'https://YOUR_DOMAIN/YOUR_ADMIN_PATH/api/v1/meta/app-config?target_app_id=2'
```

سپس این سناریو را در محیط staging با داده‌ی آزمایشی کامل کنید:

1. OTP و احراز هویت یک کاربر `user_type=2`
2. ثبت پروفایل حقیقی و حقوقی
3. ایجاد بار از Cargo
4. مشاهده جزئیات همان بار فقط با توکن Driver
5. ثبت تماس و نمایش تاریخچه
6. بستن بار توسط همان صاحب بار و ناپدیدشدن از جستجوی Driver
7. رد دسترسی متقاطع بین نقش‌ها و مالکیت شرکت‌ها

پس از تست، لاگ خطا، audit log، نرخ OTP، refresh token و فضای uploads را
پایش کنید و backup/restore واقعی را یک‌بار آزمایش کنید.
