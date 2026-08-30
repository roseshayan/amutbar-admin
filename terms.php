<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>قوانین و مقررات | شرکت حمل و نقل آموت بار</title>
    
    <!-- فایل استایل اصلی پروژه -->
    <link rel="stylesheet" href="assets/css/styles.min.css">

    <style>
        :root {
            /* رنگ‌های درخواستی */
            --primary-hex: #00bffe;
            --primary-rgb: 0, 191, 254;
            --secondary-hex: #727273;
            --secondary-rgb: 114, 114, 115;

            --bg-page: var(--default-body-bg-color, #f5f6fa);
            --text-main: var(--default-text-color, #222f36);
            --border-color: var(--default-border, #f3f2f9);
            --surface: var(--custom-white, #ffffff);
            --font-family: var(--default-font-family, Tahoma, sans-serif);
        }

        body {
            font-family: var(--font-family) !important;
            background-color: var(--bg-page);
            color: var(--text-main);
            line-height: 1.85;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        /* هدر با تم آبی آموت بار */
        .page-hero {
            background: linear-gradient(135deg, #083b56 0%, #0084b4 50%, var(--primary-hex) 100%);
            color: #ffffff;
            text-align: center;
            padding: 3rem 1.25rem 4.5rem;
            border-bottom-left-radius: 2rem;
            border-bottom-right-radius: 2rem;
            box-shadow: 0 10px 25px rgba(var(--primary-rgb), 0.25);
            position: relative;
        }

        .hero-badge-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.85rem;
        }

        .page-hero h1 {
            font-size: 1.35rem;
            font-weight: 700;
            margin: 0 0 0.4rem 0;
        }

        .page-hero p {
            font-size: 0.85rem;
            color: #e0f2fe;
            margin: 0 auto 1rem;
            max-width: 500px;
        }

        .version-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.12);
            padding: 4px 14px;
            border-radius: 50px;
            font-size: 0.75rem;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.25);
        }

        /* کانتینر محتوا */
        .content-container {
            max-width: 760px;
            margin: -2.5rem auto 3rem;
            padding: 0 1rem;
            position: relative;
            z-index: 10;
        }

        /* کادر اعلان */
        .alert-notice {
            background-color: var(--surface);
            border: 1px solid var(--border-color);
            border-inline-start: 4px solid var(--primary-hex);
            border-radius: 12px;
            padding: 1rem 1.15rem;
            font-size: 0.82rem;
            color: var(--text-main);
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 1.25rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
        }

        .alert-notice svg {
            flex-shrink: 0;
            color: var(--primary-hex);
            margin-top: 2px;
        }

        /* آکاردئون‌ها */
        .terms-wrapper {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }

        .term-card {
            background: var(--surface);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .term-card:hover {
            border-color: rgba(var(--primary-rgb), 0.4);
            box-shadow: 0 4px 14px rgba(var(--primary-rgb), 0.08);
        }

        .term-toggle-btn {
            width: 100%;
            background: transparent;
            border: none;
            outline: none;
            padding: 1rem 1.25rem;
            text-align: right;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            font-family: inherit;
        }

        .term-title-box {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .term-index {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: rgba(var(--primary-rgb), 0.12);
            color: #0284c7;
            font-size: 0.8rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .term-title-text {
            font-size: 0.92rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .term-arrow {
            color: var(--secondary-hex);
            transition: transform 0.25s ease;
            flex-shrink: 0;
        }

        .term-body-content {
            display: none;
            padding: 0.6rem 1.35rem 1.25rem;
            font-size: 0.85rem;
            color: var(--secondary-hex);
            border-top: 1px solid var(--border-color);
        }

        .term-body-content p {
            margin: 0 0 0.5rem 0;
        }

        .term-body-content p:last-child {
            margin-bottom: 0;
        }

        .term-body-content ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .term-body-content ul li {
            position: relative;
            padding-right: 1.25rem;
            margin-bottom: 0.45rem;
        }

        .term-body-content ul li::before {
            content: '•';
            position: absolute;
            right: 0;
            color: var(--primary-hex);
            font-weight: bold;
            font-size: 1.2rem;
            line-height: 1;
        }

        /* وضعیت فعال آیتم */
        .term-card.is-open {
            border-color: rgba(var(--primary-rgb), 0.5);
        }

        .term-card.is-open .term-body-content {
            display: block;
        }

        .term-card.is-open .term-arrow {
            transform: rotate(180deg);
            color: #0284c7;
        }

        .term-card.is-open .term-title-text {
            color: #0284c7;
        }

        /* فوتر */
        .page-footer {
            text-align: center;
            padding: 2rem 1rem;
            font-size: 0.78rem;
            color: var(--secondary-hex);
            border-top: 1px solid var(--border-color);
            background: var(--surface);
        }
    </style>
</head>
<body>

    <!-- هدر صفحه -->
    <header class="page-hero">
        <div class="hero-badge-icon">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
        </div>
        <h1>شرایط و ضوابط عمومی سامانه</h1>
        <p>شرکت حمل و نقل آموت بار</p>
        <div class="version-badge">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
            <span>نسخه معتبر و رسمی</span>
        </div>
    </header>

    <!-- بدنه اصلی -->
    <main class="content-container">
        
        <!-- اعلان بالا -->
        <div class="alert-notice">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <div>
                ورود، ثبت‌نام و استفاده از خدمات سامانه آموت بار به منزله مطالعه دقیق و پذیرش کامل کلیه بندهای این سند می‌باشد.
            </div>
        </div>

        <!-- لیست بخش‌های قوانین -->
        <div class="terms-wrapper">

            <!-- ۱. تعاریف و اصطلاحات -->
            <div class="term-card is-open">
                <button type="button" class="term-toggle-btn" onclick="toggleAccordion(this)">
                    <div class="term-title-box">
                        <span class="term-index">۱</span>
                        <span class="term-title-text">تعاریف و اصطلاحات</span>
                    </div>
                    <svg class="term-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="term-body-content">
                    <ul>
                        <li><strong>آموت بار:</strong> شرکت حمل و نقل آموت بار، ارائه‌دهنده و مالک انحصاری بستر هوشمند اعلان و مدیریت بار.</li>
                        <li><strong>سامانه / اپلیکیشن:</strong> کلیه ابزارهای نرم‌افزاری برخط متعلق به آموت بار جهت هماهنگی جابه‌جایی بار.</li>
                        <li><strong>کاربر:</strong> هر شخص حقیقی یا حقوقی ثبت‌نام‌شده که از خدمات سامانه بهره‌برداری می‌کند.</li>
                        <li><strong>صاحب بار:</strong> متقاضی حقیقی یا حقوقی که درخواست حمل محموله خود را در سامانه ثبت می‌نماید.</li>
                        <li><strong>راننده (متصدی حمل):</strong> شخص حقیقی دارای صلاحیت و مدارک معتبر که برای پذیرش درخواست حمل بار ثبت‌نام نموده است.</li>
                        <li><strong>بارنامه رسمی:</strong> سند رسمی حمل کالا دارای کد رهگیری سازمان راهداری که توسط شرکت‌های باربری مجاز صادر می‌گردد.</li>
                    </ul>
                </div>
            </div>

            <!-- ۲. موارد سلب مسئولیت -->
            <div class="term-card">
                <button type="button" class="term-toggle-btn" onclick="toggleAccordion(this)">
                    <div class="term-title-box">
                        <span class="term-index">۲</span>
                        <span class="term-title-text">موارد سلب مسئولیت و حدود تعهدات</span>
                    </div>
                    <svg class="term-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="term-body-content">
                    <p><strong>الزام صدور بارنامه:</strong> حمل بار برون‌شهری بدون بارنامه رسمی تخلف محسوب می‌شود و اخذ آن بر عهده صاحب بار و راننده از طریق شرکت‌های حمل مجاز است.</p>
                    <p><strong>کرایه توافقی:</strong> هزینه حمل بر پایه توافق مستقیم طرفین تعیین می‌شود و پس از تایید سفر، هیچ‌گونه ادعایی در خصوص مبالغ پذیرفته نخواهد بود.</p>
                    <p><strong>کالاهای ممنوعه:</strong> حمل هرگونه کالای غیرقانونی، قاچاق، مواد محترقه، سلاح یا احشام بدون مجوز دامپزشکی ممنوع بوده و مسئولیت آن متوجه متقاضی است.</p>
                    <p><strong>تراکنش‌های غیرسیستمی:</strong> پرداخت‌هایی که خارج از درگاه‌های رسمی ثبت‌شده انجام شوند، مورد تایید نبوده و مسئولیتی متوجه آموت بار نمی‌باشد.</p>
                </div>
            </div>

            <!-- ۳. کلیات و احکام قرارداد -->
            <div class="term-card">
                <button type="button" class="term-toggle-btn" onclick="toggleAccordion(this)">
                    <div class="term-title-box">
                        <span class="term-index">۳</span>
                        <span class="term-title-text">کلیات و احکام قرارداد</span>
                    </div>
                    <svg class="term-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="term-body-content">
                    <p>این سند مطابق با قوانین جاری جمهوری اسلامی ایران و قانون تجارت الکترونیک تنظیم شده و مراجع قضایی مرجع رسیدگی به اختلافات احتمالی خواهند بود.</p>
                    <p>آموت بار این حق را دارد که در صورت نیاز ضوابط را به‌روزرسانی کند. ادامه استفاده کاربر به معنای پذیرش تغییرات است.</p>
                    <p>ورود هرگونه خسارت به اعتبار یا زیرساخت‌های تجاری آموت بار پیگرد قانونی و مسدودسازی حساب را در پی خواهد داشت.</p>
                </div>
            </div>

            <!-- ۴. ثبت‌نام و امنیت حساب کاربری -->
            <div class="term-card">
                <button type="button" class="term-toggle-btn" onclick="toggleAccordion(this)">
                    <div class="term-title-box">
                        <span class="term-index">۴</span>
                        <span class="term-title-text">مقررات ثبت‌نام و امنیت حساب کاربری</span>
                    </div>
                    <svg class="term-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="term-body-content">
                    <p>شرط عضویت، داشتن حداقل ۱۸ سال تمام و اهلیت قانونی برای انجام معاملات است.</p>
                    <p>شرکت‌ها و سازمان‌ها از طریق نماینده رسمی و پس از احراز هویت امکان ایجاد و بهره‌برداری از حساب را دارند.</p>
                    <p>اطلاعات هویتی و مشخصات خودرو باید مطابق با واقعیت ثبت شوند. واگذاری یا اجاره حساب کاربری به اشخاص دیگر اکیداً ممنوع است.</p>
                    <p>مسئولیت حفظ رمز عبور و کد تایید بر عهده کاربر بوده و تمامی فعالیت‌های حساب به نام دارنده آن ثبت می‌گردد.</p>
                </div>
            </div>

            <!-- ۵. قوانین عملیاتی و حمل بار -->
            <div class="term-card">
                <button type="button" class="term-toggle-btn" onclick="toggleAccordion(this)">
                    <div class="term-title-box">
                        <span class="term-index">۵</span>
                        <span class="term-title-text">قوانین عملیاتی و حمل بار</span>
                    </div>
                    <svg class="term-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="term-body-content">
                    <p>سامانه آموت بار نقش بستری هوشمند جهت برقراری ارتباط میان صاحب بار و راننده را داراست و خود متصدی حمل مستقیم محسوب نمی‌شود.</p>
                    <p>میزان تناژ بارگیری باید دقیقاً بر اساس ظرفیت اسمی کارت خودرو باشد و حمل اضافه بار غیرقانونی است.</p>
                    <p>در صورت لغو بی‌دلیل سفر پس از اعزام راننده به مبدأ، حق مطالبه حق توقف طبق عرف باربری برای راننده محفوظ است.</p>
                    <p>رسیدگی به حوادث و تصادفات جاده‌ای بر عهده مراجع پلیس راهور، شرکت‌های بیمه‌گر و بر مبنای بارنامه رسمی است.</p>
                </div>
            </div>

            <!-- ۶. حریم خصوصی و امنیت داده‌ها -->
            <div class="term-card">
                <button type="button" class="term-toggle-btn" onclick="toggleAccordion(this)">
                    <div class="term-title-box">
                        <span class="term-index">۶</span>
                        <span class="term-title-text">حریم خصوصی و حفاظت از داده‌ها</span>
                    </div>
                    <svg class="term-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="term-body-content">
                    <p>اطلاعات هویتی کاربران نزد آموت بار محرمانه بوده و تنها در صورت دستور مراجع قضایی در اختیار مراجع ذی‌صلاح قرار می‌گیرد.</p>
                    <p>شماره تماس و لوکیشن بارگیری و تخلیه صرفاً به منظور هماهنگی همان سفارش بین فرستنده و راننده به اشتراک گذاشته می‌شود.</p>
                    <p>هرگونه سوءاستفاده، فروش یا بهره‌برداری تبلیغاتی از اطلاعات سایر کاربران ممنوع و مستوجب پیگرد است.</p>
                </div>
            </div>

            <!-- ۷. تعرفه‌ها و کدهای هدیه -->
            <div class="term-card">
                <button type="button" class="term-toggle-btn" onclick="toggleAccordion(this)">
                    <div class="term-title-box">
                        <span class="term-index">۷</span>
                        <span class="term-title-text">تعرفه‌ها، طرح‌های تشویقی و هدایا</span>
                    </div>
                    <svg class="term-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="term-body-content">
                    <p>کدهای تخفیف و اعتبارات هدیه غیرقابل انتقال به دیگران و فاقد قابلیت تبدیل به وجه نقد هستند.</p>
                    <p>آموت بار مجاز است در صورت تخلف یا پایان مهلت طرح‌های تشویقی، اعتبارات هدیه را اصلاح یا حذف کند.</p>
                    <p>هزینه‌های پرداخت‌شده بابت خدمات نرم‌افزاری پس از تخصیص خدمت، غیرقابل استرداد می‌باشند.</p>
                </div>
            </div>

            <!-- ۸. الزامات فنی و مالکیت معنوی -->
            <div class="term-card">
                <button type="button" class="term-toggle-btn" onclick="toggleAccordion(this)">
                    <div class="term-title-box">
                        <span class="term-index">۸</span>
                        <span class="term-title-text">الزامات فنی و مالکیت معنوی</span>
                    </div>
                    <svg class="term-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="term-body-content">
                    <p>استفاده از ربات، ابزارهای خزش خودکار (Scraping) و مهندسی معکوس کدهای سامانه اکیداً ممنوع است.</p>
                    <p>کلیه حقوق مادی و معنوی مربوط به برند، سورس‌کدها، طراحی و لوگو منحصراً در اختیار شرکت حمل و نقل آموت بار قرار دارد.</p>
                </div>
            </div>

            <!-- ۹. مسدودسازی و تعلیق دسترسی -->
            <div class="term-card">
                <button type="button" class="term-toggle-btn" onclick="toggleAccordion(this)">
                    <div class="term-title-box">
                        <span class="term-index">۹</span>
                        <span class="term-title-text">مسدودسازی و تعلیق دسترسی</span>
                    </div>
                    <svg class="term-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="term-body-content">
                    <p>در صورت نقض ضوابط یا انجام رفتاری که سلامت شبکه حمل‌ونقل را به مخاطره اندازد، آموت بار حق تعلیق موقت یا انسداد دائم حساب کاربری را داراست.</p>
                    <p>انسداد حساب ناشی از تخلف، حق هرگونه اعتراض را از کاربر متخلف سلب می‌نماید.</p>
                </div>
            </div>

            <!-- ۱۰. توافقات الکترونیکی -->
            <div class="term-card">
                <button type="button" class="term-toggle-btn" onclick="toggleAccordion(this)">
                    <div class="term-title-box">
                        <span class="term-index">۱۰</span>
                        <span class="term-title-text">توافقات الکترونیکی و اعتبار قانونی</span>
                    </div>
                    <svg class="term-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="term-body-content">
                    <p>کلیک بر روی دکمه‌های تایید و ورود کدهای یکبارمصرف در اپلیکیشن آموت بار به منزله امضای الکترونیک معتبر و اعلام اراده قطعی است.</p>
                    <p>لاگ‌ها و داده‌پیام‌های ثبت‌شده در سرورهای سامانه در مراجع داوری و قضایی به عنوان ادله رسمی الکترونیکی پذیرفته خواهند بود.</p>
                </div>
            </div>

        </div>
    </main>

    <!-- فوتر -->
    <footer class="page-footer">
        <p>© تمامی حقوق مادی و معنوی این سامانه متعلق به شرکت حمل و نقل آموت بار می‌باشد.</p>
    </footer>

    <script>
        function toggleAccordion(button) {
            const currentCard = button.closest('.term-card');
            const isOpen = currentCard.classList.contains('is-open');

            document.querySelectorAll('.term-card').forEach(function(card) {
                card.classList.remove('is-open');
            });

            if (!isOpen) {
                currentCard.classList.add('is-open');
            }
        }
    </script>
</body>
</html>