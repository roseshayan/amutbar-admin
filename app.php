<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>باربری هوشمند | دانلود اپلیکیشن رانندگان و صاحبان بار</title>
    <!-- استایل اصلی پروژه (فایل ارسالی) -->
    <link id="style" href="assets/vendor/bootstrap/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.min.css">
    <!-- بازنویسی رنگ‌ها و تنظیمات اضافی -->
    <style>
        /* بازنویسی متغیرهای رنگ با رنگ‌های درخواستی */
        :root {
            --primary-rgb: 0, 191, 254;   /* #00BFFE */
            --secondary-rgb: 114, 114, 115; /* #727273 */
            --primary-color: rgb(var(--primary-rgb));
            --secondary-color: rgb(var(--secondary-rgb));
            --primary01: rgba(var(--primary-rgb), 0.1);
            --primary05: rgba(var(--primary-rgb), 0.5);
            --primary08: rgba(var(--primary-rgb), 0.8);
        }

        /* تنظیم فونت برای کل صفحه (IRANSans قبلاً در CSS اصلی تعریف شده) */
        body {
            font-family: 'IRANSans', sans-serif;
            background-color: var(--default-body-bg-color);
            color: var(--default-text-color);
        }

        /* استایل‌های اختصاصی لندینگ */
        .landing-header {
            background: var(--custom-white);
            border-bottom: 1px solid var(--default-border);
            padding: 0.8rem 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .landing-header .logo img {
            height: 2.5rem;
        }
        .landing-header .nav-link {
            color: var(--menu-prime-color);
            font-weight: 500;
            margin: 0 0.5rem;
        }
        .landing-header .nav-link:hover {
            color: var(--primary-color);
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, #0099cc 100%);
            color: #fff;
            padding: 4rem 0;
            position: relative;
            overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.05)" d="M0,128L48,144C96,160,192,192,288,186.7C384,181,480,139,576,128C672,117,768,139,864,149.3C960,160,1056,160,1152,144C1248,128,1344,96,1392,80L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"/></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.3;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }
        .hero-section .container {
            position: relative;
            z-index: 2;
        }
        .hero-title {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .hero-sub {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }
        .btn-download {
            background: var(--custom-white);
            color: var(--primary-color);
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 3rem;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .btn-download:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            color: var(--primary-color);
        }
        .btn-download i {
            font-size: 1.5rem;
        }

        .app-card {
            background: var(--custom-white);
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            padding: 2rem;
            transition: all 0.3s;
            border: 1px solid var(--default-border);
            height: 100%;
        }
        .app-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            border-color: var(--primary01);
        }
        .app-card .app-icon {
            width: 80px;
            height: 80px;
            background: var(--primary01);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: var(--primary-color);
            font-size: 2.5rem;
        }
        .app-card h4 {
            font-weight: 600;
            color: var(--default-text-color);
        }
        .app-card .feature-list {
            list-style: none;
            padding: 0;
            text-align: right;
        }
        .app-card .feature-list li {
            padding: 0.4rem 0;
            border-bottom: 1px solid var(--default-border);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .app-card .feature-list li:last-child {
            border-bottom: none;
        }
        .app-card .feature-list i {
            color: var(--primary-color);
            font-size: 1.2rem;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
        }
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            right: 0;
            width: 60px;
            height: 4px;
            background: var(--primary-color);
            border-radius: 4px;
        }
        .section-sub {
            color: var(--text-muted);
            font-size: 1.1rem;
            margin-bottom: 2.5rem;
        }

        .stats-section {
            background: var(--default-background);
            padding: 3rem 0;
            border-top: 1px solid var(--default-border);
            border-bottom: 1px solid var(--default-border);
        }
        .stat-item {
            text-align: center;
        }
        .stat-item .number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        .stat-item .label {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .testimonial-card {
            background: var(--custom-white);
            border-radius: 1rem;
            padding: 2rem;
            border: 1px solid var(--default-border);
            box-shadow: 0 5px 15px rgba(0,0,0,0.03);
        }
        .testimonial-card .avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--primary01);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: var(--primary-color);
        }

        .footer-landing {
            background: #1a1a2e;
            color: rgba(255,255,255,0.7);
            padding: 3rem 0 1.5rem;
            border-top: 3px solid var(--primary-color);
        }
        .footer-landing a {
            color: rgba(255,255,255,0.7);
            transition: color 0.3s;
        }
        .footer-landing a:hover {
            color: #fff;
            text-decoration: none;
        }
        .footer-landing .social-link {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            margin-left: 0.5rem;
            color: #fff;
            transition: all 0.3s;
        }
        .footer-landing .social-link:hover {
            background: var(--primary-color);
            transform: translateY(-3px);
        }

        /* ریسپانسیو */
        @media (max-width: 767.98px) {
            .hero-title {
                font-size: 2rem;
            }
            .app-card {
                margin-bottom: 1.5rem;
            }
            .stat-item .number {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>

<!-- ====== هدر ====== -->
<header class="landing-header">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light p-0">
            <div class="container-fluid px-0">
                <a class="navbar-brand logo" href="#">
                    <img src="assets/images/brand-logos/logo.png" alt="لوگو باربری هوشمند" height="40" />
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="#driver">اپلیکیشن رانندگان</a></li>
                        <li class="nav-item"><a class="nav-link" href="#owner">اپلیکیشن صاحبان بار</a></li>
                        <li class="nav-item"><a class="nav-link" href="#features">امکانات</a></li>
                        <li class="nav-item"><a class="nav-link" href="#testimonials">نظرات</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-primary btn-sm text-white px-3" href="#download">دانلود</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>

<!-- ====== بخش اصلی (Hero) ====== -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-4 mb-lg-0">
                <h1 class="hero-title">سامانه هوشمند حمل بار</h1>
                <p class="hero-sub">
                    ارتباط مستقیم رانندگان و صاحبان بار، بدون واسطه و با بهترین قیمت.
                    اپلیکیشن اختصاصی برای هر دو طرف، حمل بار را آسان‌تر از همیشه.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="#" class="btn-download">
                        <i class="ri-android-fill"></i> دانلود برای اندروید
                    </a>
                    <a href="#" class="btn-download" style="background: rgba(255,255,255,0.15); color:#fff; backdrop-filter: blur(4px);">
                        <i class="ri-apple-fill"></i> دانلود برای iOS
                    </a>
                </div>
            </div>
            <div class="col-lg-5 text-center">
                <img src="assets/images/hero-apps.png" alt="نمایش اپلیکیشن" class="img-fluid" style="max-height: 400px;" />
            </div>
        </div>
    </div>
</section>

<!-- ====== معرفی دو اپلیکیشن ====== -->
<section class="py-5" id="driver">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">اپلیکیشن‌های تخصصی</h2>
            <p class="section-sub">هر کاربر با توجه به نقش خود، اپلیکیشن مخصوص را دریافت می‌کند</p>
        </div>
        <div class="row g-4">
            <!-- اپلیکیشن رانندگان -->
            <div class="col-md-6" id="driver">
                <div class="app-card text-center">
                    <div class="app-icon"><i class="ri-taxi-line"></i></div>
                    <h4>اپلیکیشن رانندگان</h4>
                    <p class="text-muted">بارهای موجود را مشاهده، پیشنهاد قیمت بده و بار را جابجا کن</p>
                    <ul class="feature-list">
                        <li><i class="ri-check-line"></i> مشاهده بارهای نزدیک</li>
                        <li><i class="ri-check-line"></i> پیشنهاد قیمت لحظه‌ای</li>
                        <li><i class="ri-check-line"></i> مسیریابی هوشمند</li>
                        <li><i class="ri-check-line"></i> دریافت اعلان بارهای جدید</li>
                    </ul>
                    <a href="#" class="btn btn-primary mt-3">دانلود اپلیکیشن رانندگان</a>
                </div>
            </div>
            <!-- اپلیکیشن صاحبان بار -->
            <div class="col-md-6" id="owner">
                <div class="app-card text-center">
                    <div class="app-icon"><i class="ri-stack-line"></i></div>
                    <h4>اپلیکیشن صاحبان بار</h4>
                    <p class="text-muted">بار خود را ثبت کن و از بین رانندگان بهترین پیشنهاد را انتخاب کن</p>
                    <ul class="feature-list">
                        <li><i class="ri-check-line"></i> ثبت سریع بار</li>
                        <li><i class="ri-check-line"></i> دریافت پیشنهادات رانندگان</li>
                        <li><i class="ri-check-line"></i> رهگیری لحظه‌ای بار</li>
                        <li><i class="ri-check-line"></i> امتیازدهی به رانندگان</li>
                    </ul>
                    <a href="#" class="btn btn-primary mt-3">دانلود اپلیکیشن صاحبان بار</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ====== امکانات ویژه ====== -->
<section class="py-5 bg-light" id="features">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">چرا باربری هوشمند؟</h2>
            <p class="section-sub">مزایای استفاده از سامانه برای همه کاربران</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center p-3">
                    <i class="ri-money-dollar-circle-line" style="font-size: 3rem; color: var(--primary-color);"></i>
                    <h5 class="mt-3">شفافیت قیمت</h5>
                    <p class="text-muted">پیشنهادات رقابتی رانندگان را مشاهده و بهترین گزینه را انتخاب کنید.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-3">
                    <i class="ri-map-pin-line" style="font-size: 3rem; color: var(--primary-color);"></i>
                    <h5 class="mt-3">ردیابی لحظه‌ای</h5>
                    <p class="text-muted">موقعیت بار خود را در لحظه مشاهده کنید و از تحویل به موقع مطمئن شوید.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-3">
                    <i class="ri-shield-check-line" style="font-size: 3rem; color: var(--primary-color);"></i>
                    <h5 class="mt-3">امنیت و اعتماد</h5>
                    <p class="text-muted">سیستم امتیازدهی و احراز هویت، کیفیت خدمات را تضمین می‌کند.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ====== آمار ====== -->
<section class="stats-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-6 col-md-3 stat-item">
                <div class="number">۱۲,۵۰۰+</div>
                <div class="label">راننده فعال</div>
            </div>
            <div class="col-6 col-md-3 stat-item">
                <div class="number">۸,۲۰۰+</div>
                <div class="label">بار ثبت شده</div>
            </div>
            <div class="col-6 col-md-3 stat-item">
                <div class="number">۴.۹</div>
                <div class="label">میانگین امتیاز</div>
            </div>
            <div class="col-6 col-md-3 stat-item">
                <div class="number">۹۸٪</div>
                <div class="label">رضایت کاربران</div>
            </div>
        </div>
    </div>
</section>

<!-- ====== نظرات کاربران ====== -->
<section class="py-5" id="testimonials">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">نظرات کاربران</h2>
            <p class="section-sub">آنچه رانندگان و صاحبان بار درباره ما می‌گویند</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="avatar"><i class="ri-user-3-fill"></i></div>
                        <div>
                            <h6 class="mb-0">علی رضایی</h6>
                            <small class="text-muted">راننده</small>
                        </div>
                    </div>
                    <p class="mb-0">“با این اپلیکیشن دیگه مجبور نیستم توی شرکت‌های واسطه وقت تلف کنم. مستقیم با صاحب بار در ارتباطم.”</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="avatar"><i class="ri-user-3-fill"></i></div>
                        <div>
                            <h6 class="mb-0">محمد کریمی</h6>
                            <small class="text-muted">صاحب بار</small>
                        </div>
                    </div>
                    <p class="mb-0">“خیلی سریع بارم ثبت میشه و توی کمتر از ۱۰ دقیقه چندین پیشنهاد دریافت می‌کنم. عالی!”</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="avatar"><i class="ri-user-3-fill"></i></div>
                        <div>
                            <h6 class="mb-0">سارا حسینی</h6>
                            <small class="text-muted">مدیر لجستیک</small>
                        </div>
                    </div>
                    <p class="mb-0">“سیستم ردیابی بار و امتیازدهی به رانندگان باعث شده کیفیت حمل بار به شدت افزایش پیدا کنه.”</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ====== دعوت به دانلود ====== -->
<section class="py-5" style="background: var(--primary01);" id="download">
    <div class="container text-center">
        <h2 class="mb-3" style="color: var(--primary-color);">همین حالا شروع کن!</h2>
        <p class="mb-4" style="font-size: 1.1rem;">اپلیکیشن مخصوص خود را دانلود کن و از حمل بار بدون دردسر لذت ببر.</p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="#" class="btn-download" style="background: var(--primary-color); color:#fff;">
                <i class="ri-android-fill"></i> دریافت نسخه اندروید
            </a>
            <a href="#" class="btn-download" style="background: var(--secondary-color); color:#fff; border-color: var(--secondary-color);">
                <i class="ri-apple-fill"></i> دریافت نسخه iOS
            </a>
        </div>
        <p class="mt-3 text-muted small">نسخه وب نیز به زودی ارائه می‌شود.</p>
    </div>
</section>

<!-- ====== فوتر ====== -->
<footer class="footer-landing">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4 mb-md-0">
                <img src="assets/images/brand-logos/logo.png" alt="لوگو" height="40" class="mb-3" style="filter: brightness(0) invert(1);" />
                <p>ارائه‌دهنده سامانه هوشمند حمل بار، اتصال مستقیم رانندگان و صاحبان بار.</p>
                <div>
                    <a href="#" class="social-link"><i class="ri-instagram-line"></i></a>
                    <a href="#" class="social-link"><i class="ri-telegram-line"></i></a>
                    <a href="#" class="social-link"><i class="ri-whatsapp-line"></i></a>
                </div>
            </div>
            <div class="col-md-2 col-6 mb-4 mb-md-0">
                <h6 class="text-white">دسترسی سریع</h6>
                <ul class="list-unstyled">
                    <li><a href="#driver">اپلیکیشن رانندگان</a></li>
                    <li><a href="#owner">اپلیکیشن صاحبان بار</a></li>
                    <li><a href="#features">امکانات</a></li>
                    <li><a href="#testimonials">نظرات</a></li>
                </ul>
            </div>
            <div class="col-md-3 col-6 mb-4 mb-md-0">
                <h6 class="text-white">ارتباط با ما</h6>
                <ul class="list-unstyled">
                    <li><i class="ri-phone-line ml-1"></i> ۰۲۱-۱۲۳۴۵۶۷۸</li>
                    <li><i class="ri-mail-line ml-1"></i> info@barbari.com</li>
                    <li><i class="ri-map-pin-line ml-1"></i> تهران، خیابان آزادی</li>
                </ul>
            </div>
            <div class="col-md-3">
                <h6 class="text-white">دانلود اپلیکیشن</h6>
                <a href="#" class="btn btn-outline-light btn-sm w-100 mb-2"><i class="ri-android-fill"></i> گوگل پلی</a>
                <a href="#" class="btn btn-outline-light btn-sm w-100"><i class="ri-apple-fill"></i> اپ استور</a>
            </div>
        </div>
        <hr class="my-3" style="border-color: rgba(255,255,255,0.1);" />
        <div class="text-center small">
            &copy; ۱۴۰۴ باربری هوشمند. تمامی حقوق محفوظ است.
        </div>
    </div>
</footer>

<!-- Bootstrap JS (برای هامبورگر و ...) -->
<script src="assets/vendor/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>