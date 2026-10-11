# راهنمای جامع استرس تست و تخمین منابع سرور (Server Sizing & Benchmark Guide)

این راهنما به شما کمک می‌کند با اجرای سناریوهای استرس تست روی سرورهای مجازی (VPS) مختلف با سیستم‌عامل‌های گوناگون لینوکس (مانند Ubuntu 22.04 / 24.04 یا Debian 12)، حداقل و بهینه‌ترین مشخصات سخت‌افزاری مورد نیاز برای راه‌اندازی اپلیکیشن‌های آموت‌بار (راننده و اعلام بار) را به صورت دقیق و تجربی استخراج کنید.

---

## ۱. نصب ابزار k6

ابزار **Grafana k6** استانداردترین و بهینه‌ترین ابزار تست بار در دنیا است که با مصرف حداقل منابع می‌تواند هزاران کاربر همزمان را شبیه‌سازی کند.

### در ویندوز (توسعه‌دهنده):
```powershell
winget install k6 --source winget
# یا با choco:
# choco install k6
```

### در لینوکس (اوبونتو/دبیان):
```bash
sudo gpg -k
sudo gpg --no-default-keyring --keyring /usr/share/keyrings/k6-archive-keyring.gpg --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys C5AD17C747E3415A3642D57D77C6C491D6AC1D69
echo "deb [signed-by=/usr/share/keyrings/k6-archive-keyring.gpg] https://dl.k6.io/deb stable main" | sudo tee /etc/apt/sources.list.d/k6.list
sudo apt-get update
sudo apt-get install k6
```

### یا اجرا با داکر (بدون نیاز به نصب):
```bash
docker run --rm -i grafana/k6 run - < tests/stress/scenarios/smoke.js
```

---

## ۲. سناریوهای آماده استرس تست در پروژه

| نام فایل سناریو | هدف سناریو | تعداد کاربران همزمان (VUs) | مدت زمان |
| :--- | :--- | :--- | :--- |
| `scenarios/smoke.js` | تست سلامت اولیه و پینگ کلیه اندپوینت‌ها | ۲ کاربر | ۲۰ ثانیه |
| `scenarios/public_endpoints.js` | تست پرترددترین اندپوینت‌های خواندنی (کانفیگ، بنر، شهرها) | تا ۳۰۰ کاربر | ۴ دقیقه |
| `scenarios/driver_journey.js` | شبیه‌سازی رفتار واقعی راننده (جستجوی بار، محاسبه نرخ، تماس) | تا ۲۰۰ کاربر | ۴ دقیقه |
| `scenarios/company_journey.js` | شبیه‌سازی اعلام بار توسط باربری و بستن بار | تا ۸۰ کاربر | ۳ دقیقه |
| `scenarios/full_stress_test.js` | تست ترکیبی سنگین تا مرز اشباع (Spike & Stress) | تا ۸۰۰ کاربر | ۸ دقیقه |

### نمونه دستورات اجرا:
```bash
# تست اولیه با آدرس لوکال یا سرور تست
k6 run tests/stress/scenarios/smoke.js

# تست پرتردد با تعیین آدرس سرور مجازی لینوکس
k6 run -e BASE_URL=http://YOUR_SERVER_IP/api/v1 tests/stress/scenarios/public_endpoints.js

# اجرای تست جامع سنگین
k6 run -e BASE_URL=https://your-domain.com/api/v1 tests/stress/scenarios/full_stress_test.js
```

---

## ۳. مانیتورینگ زنده سرور مجازی در زمان تست

یک اسکریپت بهینه‌شده به نام `vps_monitor.sh` در همین پوشه قرار داده شده است. آن را به سرور مجازی لینوکس منتقل کرده و پیش از شروع تست اجرا کنید:

```bash
chmod +x vps_monitor.sh
./vps_monitor.sh
```

این اسکریپت هر ۲ ثانیه یک‌بار مصرف پردازنده (CPU)، حافظه رم (RAM)، فرآیندهای فعال PHP-FPM، کانکشن‌های MySQL و زمان تاخیر دیسک را در ترمینال نمایش داده و در یک فایل اکسل `stress_metrics_*.csv` ذخیره می‌کند.

---

## ۴. جدول تخمین سرور (Sizing Matrix) بر اساس تست‌های واقعی

این تخمین‌ها بر مبنای پشته **Nginx + PHP 8.2-FPM + MariaDB 10.11 + OPcache** روی سیستم‌عامل Ubuntu محاسبه شده است:

| کانفیگ سخت‌افزاری VPS | کاربران فعال همزمان (CCU) | کاربران فعال روزانه (DAU) | توان پاسخگویی (RPS) | توصیه برای محیط |
| :--- | :--- | :--- | :--- | :--- |
| **۱ هسته CPU / ۲ گیگابایت RAM** | ۵۰ الی ۱۰۰ کاربر | ۱,۰۰۰ الی ۳,۰۰۰ کاربر | ~۶۰ تا ۱۰۰ درخواست/ثانیه | محیط Staging و شروع اولیه |
| **۲ هسته CPU / ۴ گیگابایت RAM** | ۲۰۰ الی ۵۰۰ کاربر | ۵,۰۰۰ الی ۱۵,۰۰۰ کاربر | ~۲۰۰ تا ۴۰۰ درخواست/ثانیه | **پیشنهاد استاندارد برای پروداکشن** |
| **۴ هسته CPU / ۸ گیگابایت RAM** | ۱,۰۰۰ الی ۲,۵۰۰ کاربر | ۳۰,۰۰۰ الی ۸۰,۰۰۰ کاربر | ~۸۰۰ تا ۱,۵۰۰ درخواست/ثانیه | ترافیک سنگین کشوری |
| **۸ هسته CPU / ۱۶ گیگابایت RAM** | ۵,۰۰۰+ کاربر | ۱۵۰,۰۰۰+ کاربر | ~۳,۰۰۰+ درخواست/ثانیه | مقیاس سازمانی (Enterprise) |

---

## ۵. فرمول‌های کلیدی تنظیم و تیونینگ سرور (Tuning Guide)

### ۱. تنظیم اصولی استخر PHP-FPM (`/etc/php/8.2/fpm/pool.d/www.conf`)
بزرگ‌ترین گلوگاه در سرورهای لینوکس، کمبود یا مصرف بیش از حد پروسس‌های PHP-FPM است.
هر پروسس PHP-FPM در این پروژه به طور متوسط **۳۰ الی ۴۰ مگابایت رم** مصرف می‌کند.

فرمول محاسبه `pm.max_children`:
$$\text{pm.max\_children} = \frac{\text{کل رم سرور (MB)} - \text{رم سیستم‌عامل و دیتابیس (حدود 1000MB)}}{35\text{MB}}$$

**نمونه تنظیم برای سرور ۴ گیگابایت رم:**
```ini
pm = dynamic
pm.max_children = 80
pm.start_servers = 15
pm.min_spare_servers = 10
pm.max_spare_servers = 25
pm.max_requests = 1000   ; جلوگیری از نشت حافظه (Memory Leak)
```

### ۲. فعال‌سازی الزامی OPcache (`/etc/php/8.2/fpm/conf.d/10-opcache.ini`)
فعال بودن OPcache سرعت پاسخ‌دهی کدهای PHP را **۴ تا ۸ برابر** افزایش داده و بار پردازنده را به شدت کاهش می‌دهد:
```ini
opcache.enable=1
opcache.enable_cli=0
opcache.memory_consumption=128
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.revalidate_freq=0
opcache.validate_timestamps=0   ; در محیط پروداکشن (توسط CI/CD ری‌لود می‌شود)
```

### ۳. بهینه‌سازی وب‌سرور Nginx (`/etc/nginx/nginx.conf`)
```nginx
worker_processes auto;
worker_rlimit_nofile 65535;

events {
    worker_connections 2048;
    multi_accept on;
    use epoll;
}

http {
    keepalive_timeout 30;
    keepalive_requests 1000;
    
    # Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_proxied any;
    gzip_comp_level 5;
    gzip_types application/json text/plain text/css application/javascript;
}
```

### ۴. تنظیم بافر MariaDB/MySQL (`/etc/mysql/my.cnf`)
بافر دیتابیس برای نگهداری ایندکس‌ها و کش جدول‌ها باید حدود **۵۰ الی ۶۰ درصد** از رم اختصاص داده شده به دیتابیس باشد:
```ini
[mysqld]
innodb_buffer_pool_size = 1G   ; برای سرور 4GB رم
innodb_log_file_size = 256M
innodb_flush_log_at_trx_commit = 2
max_connections = 300
```
