<?php

declare(strict_types=1);
require_once __DIR__ . '/includes/init.php';

$settings = settings_get_many([
    'company.name','site.name','site.logo_path','site.favicon_path','site.url',
    'links.terms_url','support.phone','support.whatsapp','support.telegram',
    'app.android.update_url','app.ios.update_url',
    'cargo.app.android.update_url','cargo.app.ios.update_url',
]);

$companyName = trim((string)($settings['company.name'] ?? '')) ?: 'آموت بار';
$siteName = trim((string)($settings['site.name'] ?? '')) ?: $companyName;
$termsUrl = trim((string)($settings['links.terms_url'] ?? '')) ?: 'terms.php';
$supportPhone = trim((string)($settings['support.phone'] ?? ''));
$whatsapp = preg_replace('/\D+/', '', (string)($settings['support.whatsapp'] ?? ''));
$telegram = trim((string)($settings['support.telegram'] ?? ''));

function landing_asset_url(?string $value): string
{
    $value = trim((string)$value);
    if ($value === '') return '';
    if (preg_match('~^https?://~i', $value)) return $value;
    return url_path(ltrim($value, '/'));
}

function safe_download_url(?string $value, string $siteBase): string
{
    $value = trim((string)$value);
    if ($value === '') return '';
    if (filter_var($value, FILTER_VALIDATE_URL)) {
        return in_array(strtolower((string)parse_url($value, PHP_URL_SCHEME)), ['http','https'], true) ? $value : '';
    }
    if (preg_match('~^[a-z][a-z0-9+.-]*:~i', $value)) return '';
    return rtrim($siteBase, '/') . '/' . ltrim($value, '/');
}

$logoUrl = landing_asset_url($settings['site.logo_path'] ?? '');
if ($logoUrl === '') $logoUrl = asset('images/brand-logos/logo.png');
$faviconUrl = landing_asset_url($settings['site.favicon_path'] ?? '');

$siteBase = rtrim(trim((string)($settings['site.url'] ?? '')), '/');
if ($siteBase === '') {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $siteBase = $scheme . '://' . (string)($_SERVER['HTTP_HOST'] ?? 'localhost') . base_url();
}
$driverAndroid = safe_download_url($settings['app.android.update_url'] ?? '', $siteBase);
$driverIos = safe_download_url($settings['app.ios.update_url'] ?? '', $siteBase);
$cargoAndroid = safe_download_url($settings['cargo.app.android.update_url'] ?? '', $siteBase);
$cargoIos = safe_download_url($settings['cargo.app.ios.update_url'] ?? '', $siteBase);
$hasAnyDownload = $driverAndroid || $driverIos || $cargoAndroid || $cargoIos;
?>
<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
    <meta name="theme-color" content="#00bffe">
    <meta name="description" content="دانلود اپلیکیشن رانندگان و اپلیکیشن اعلام بار <?= htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') ?>">
    <title>دانلود اپلیکیشن <?= htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') ?></title>
    <?php if ($faviconUrl !== ''): ?><link rel="icon" href="<?= htmlspecialchars($faviconUrl, ENT_QUOTES, 'UTF-8') ?>"><?php endif; ?>
    <style>
        :root{--primary:#00bffe;--primary-dark:#008dbd;--ink:#182229;--muted:#6f7a82;--line:#e5edf1;--soft:#f4fbfd;--white:#fff;--shadow:0 22px 70px rgba(19,73,97,.12)}
        *{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;font-family:Tahoma,"Segoe UI",sans-serif;background:#fff;color:var(--ink);line-height:1.8}a{text-decoration:none;color:inherit}.shell{width:min(1160px,calc(100% - 34px));margin:auto}
        .top{position:sticky;top:0;z-index:50;background:rgba(255,255,255,.92);backdrop-filter:blur(14px);border-bottom:1px solid rgba(229,237,241,.8)}.nav{min-height:74px;display:flex;align-items:center;justify-content:space-between;gap:18px}.brand{display:flex;align-items:center;gap:11px;font-weight:900}.brand img{width:48px;height:48px;object-fit:contain;border-radius:14px}.brand-fallback{width:46px;height:46px;border-radius:14px;background:var(--primary);color:#fff;display:grid;place-items:center}.navlinks{display:flex;align-items:center;gap:8px}.navlinks a{padding:9px 12px;color:#53616a;font-size:14px}.navlinks .primary{background:var(--primary);color:#fff;border-radius:12px;font-weight:800}
        .hero{overflow:hidden;position:relative;background:linear-gradient(135deg,#f7fdff,#eaf9ff 55%,#f9fcfd);border-bottom:1px solid var(--line)}.hero:before{content:"";position:absolute;width:520px;height:520px;border-radius:50%;background:rgba(0,191,254,.12);left:-180px;top:-230px}.hero-grid{position:relative;display:grid;grid-template-columns:1.15fr .85fr;align-items:center;gap:55px;padding:84px 0 80px}.eyebrow{display:inline-flex;align-items:center;gap:8px;background:#fff;border:1px solid #dff3fb;border-radius:999px;padding:7px 12px;color:var(--primary-dark);font-size:13px;font-weight:800}.hero h1{font-size:clamp(34px,5vw,58px);line-height:1.35;margin:19px 0}.hero p{font-size:18px;color:var(--muted);max-width:650px}.hero-actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:28px}.btn{display:inline-flex;align-items:center;justify-content:center;gap:9px;padding:13px 18px;border-radius:14px;font-weight:800;border:1px solid var(--line);background:white}.btn-primary{background:var(--primary);border-color:var(--primary);color:white;box-shadow:0 10px 26px rgba(0,191,254,.26)}.btn:hover{transform:translateY(-1px)}
        .phone-stage{min-height:380px;display:grid;place-items:center}.visual{width:min(390px,100%);aspect-ratio:1/1;border-radius:50%;background:linear-gradient(145deg,#00bffe,#65d9ff);box-shadow:0 35px 90px rgba(0,157,210,.26);position:relative;display:grid;place-items:center}.visual:after{content:"";position:absolute;inset:18%;border:1px dashed rgba(255,255,255,.6);border-radius:50%}.visual .truck{position:relative;z-index:1;font-size:105px;filter:drop-shadow(0 14px 18px rgba(0,0,0,.14))}.visual .badge{position:absolute;background:white;border-radius:15px;padding:10px 14px;box-shadow:0 12px 34px rgba(26,70,87,.14);font-size:13px;font-weight:800}.visual .one{top:18%;right:-3%}.visual .two{bottom:20%;left:-5%}
        .section{padding:76px 0}.section-head{text-align:center;max-width:720px;margin:0 auto 36px}.section-head h2{font-size:clamp(27px,4vw,39px);margin:0 0 10px}.section-head p{color:var(--muted);margin:0}.apps{display:grid;grid-template-columns:repeat(2,1fr);gap:24px}.app-card{border:1px solid var(--line);border-radius:25px;padding:28px;background:#fff;box-shadow:0 8px 35px rgba(22,65,81,.05);position:relative;overflow:hidden}.app-card:before{content:"";position:absolute;width:150px;height:150px;border-radius:50%;background:var(--soft);left:-55px;top:-55px}.app-head{display:flex;align-items:center;gap:15px;position:relative}.app-icon{width:62px;height:62px;border-radius:18px;background:rgba(0,191,254,.11);display:grid;place-items:center;font-size:30px}.app-head h3{font-size:21px;margin:0 0 3px}.app-head small{color:var(--muted)}.features{list-style:none;padding:0;margin:24px 0;display:grid;gap:10px}.features li{display:flex;gap:9px;align-items:flex-start;color:#526069}.features li:before{content:"✓";width:24px;height:24px;display:grid;place-items:center;border-radius:50%;background:#eafff4;color:#159357;font-weight:900;flex:0 0 auto}.downloads{display:flex;gap:10px;flex-wrap:wrap}.store{flex:1;min-width:160px;padding:12px 15px;border-radius:13px;border:1px solid #dbe8ee;background:#fafdfe;font-weight:800;display:flex;justify-content:center;gap:8px}.store.android{border-color:#beeefc;color:#087fa4;background:#f2fcff}.store.disabled{opacity:.46;cursor:not-allowed;pointer-events:none}
        .flow{background:var(--soft);border-top:1px solid var(--line);border-bottom:1px solid var(--line)}.feature-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}.feature{background:white;border:1px solid var(--line);border-radius:20px;padding:23px}.feature span{width:44px;height:44px;border-radius:13px;background:rgba(0,191,254,.1);color:var(--primary-dark);display:grid;place-items:center;font-size:21px}.feature h4{margin:14px 0 5px}.feature p{margin:0;color:var(--muted);font-size:14px}
        .support{padding:58px 0}.support-box{background:linear-gradient(135deg,#132933,#194250);color:white;border-radius:28px;padding:34px;display:flex;align-items:center;justify-content:space-between;gap:25px}.support-box p{margin:6px 0 0;color:rgba(255,255,255,.72)}.support-actions{display:flex;gap:9px;flex-wrap:wrap}.support-actions a{padding:11px 14px;border-radius:12px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.14);white-space:nowrap}.footer{border-top:1px solid var(--line);padding:24px 0 35px;color:var(--muted);font-size:13px}.footer-row{display:flex;justify-content:space-between;align-items:center;gap:15px;flex-wrap:wrap}.footer a{color:var(--primary-dark)}
        .empty{margin-top:18px;padding:13px 15px;border:1px dashed #f1bd73;background:#fff9f0;color:#8a5c1d;border-radius:13px;font-size:13px}
        @media(max-width:850px){.navlinks a:not(.primary){display:none}.hero-grid{grid-template-columns:1fr;padding:55px 0}.phone-stage{min-height:300px}.visual{width:300px}.apps,.feature-grid{grid-template-columns:1fr}.support-box{align-items:flex-start;flex-direction:column}}
        @media(max-width:520px){.hero h1{font-size:34px}.hero p{font-size:15px}.hero-grid{gap:28px}.visual{width:240px}.visual .truck{font-size:80px}.visual .badge{font-size:11px}.section{padding:55px 0}.app-card{padding:21px}.support-box{padding:24px}.store{min-width:100%}}
    </style>
</head>
<body>
<header class="top"><div class="shell nav">
    <a class="brand" href="#top">
        <?php if ($logoUrl !== ''): ?><img src="<?= htmlspecialchars($logoUrl, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') ?>"><?php else: ?><span class="brand-fallback">آ</span><?php endif; ?>
        <span><?= htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') ?></span>
    </a>
    <nav class="navlinks"><a href="#apps">اپلیکیشن‌ها</a><a href="#features">امکانات</a><a href="<?= htmlspecialchars($termsUrl, ENT_QUOTES, 'UTF-8') ?>">قوانین</a><a class="primary" href="#apps">دانلود</a></nav>
</div></header>
<main id="top">
    <section class="hero"><div class="shell hero-grid">
        <div>
            <span class="eyebrow">● سامانه هوشمند حمل بار</span>
            <h1>یک حساب، دو مسیر؛<br>رانندگی و اعلام بار</h1>
            <p>اپلیکیشن مناسب نقش خود را دانلود کنید. یک شماره موبایل می‌تواند در هر دو اپلیکیشن <?= htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') ?> استفاده شود و اطلاعات هر نقش به‌صورت مستقل مدیریت می‌شود.</p>
            <div class="hero-actions"><a class="btn btn-primary" href="#apps">دانلود اپلیکیشن‌ها</a><a class="btn" href="<?= htmlspecialchars($termsUrl, ENT_QUOTES, 'UTF-8') ?>">مشاهده قوانین و مقررات</a></div>
            <?php if (!$hasAnyDownload): ?><div class="empty">لینک نسخه‌ها هنوز از پنل مدیریت تنظیم نشده است. به محض ثبت لینک Android یا iOS، دکمه دانلود همین صفحه فعال می‌شود.</div><?php endif; ?>
        </div>
        <div class="phone-stage"><div class="visual"><span class="truck">🚚</span><span class="badge one">برای رانندگان</span><span class="badge two">برای صاحبان بار</span></div></div>
    </div></section>

    <section class="section" id="apps"><div class="shell">
        <div class="section-head"><h2>اپلیکیشن موردنیازتان را انتخاب کنید</h2><p>نسخه‌های دانلودی مستقیماً از پنل مدیریت کنترل می‌شوند؛ بنابراین لینک‌های این صفحه همیشه از همان تنظیمات اپ‌ها خوانده می‌شوند.</p></div>
        <div class="apps">
            <article class="app-card">
                <div class="app-head"><div class="app-icon">🚛</div><div><h3>اپلیکیشن رانندگان</h3><small>پیداکردن بار و مدیریت سفر</small></div></div>
                <ul class="features"><li>مشاهده و جستجوی بارهای مناسب</li><li>دسترسی به اطلاعات حمل و تماس مرتبط</li><li>پیگیری فعالیت‌ها، تاریخچه و پشتیبانی</li><li>احراز هویت و مدیریت مشخصات راننده و خودرو</li></ul>
                <div class="downloads">
                    <a class="store android <?= $driverAndroid ? '' : 'disabled' ?>" href="<?= htmlspecialchars($driverAndroid ?: '#', ENT_QUOTES, 'UTF-8') ?>" <?= $driverAndroid ? 'target="_blank" rel="noopener"' : 'aria-disabled="true"' ?>>🤖 دانلود Android</a>
                    <a class="store <?= $driverIos ? '' : 'disabled' ?>" href="<?= htmlspecialchars($driverIos ?: '#', ENT_QUOTES, 'UTF-8') ?>" <?= $driverIos ? 'target="_blank" rel="noopener"' : 'aria-disabled="true"' ?>> دانلود iOS</a>
                </div>
            </article>
            <article class="app-card">
                <div class="app-head"><div class="app-icon">📦</div><div><h3>اپلیکیشن اعلام بار</h3><small>ویژه صاحبان بار و باربری‌ها</small></div></div>
                <ul class="features"><li>ثبت و مدیریت سریع درخواست حمل بار</li><li>پیگیری بارهای فعال و سوابق درخواست‌ها</li><li>مدیریت اطلاعات کسب‌وکار و احراز هویت</li><li>دسترسی به پشتیبانی و راهنمای مستقل اپ</li></ul>
                <div class="downloads">
                    <a class="store android <?= $cargoAndroid ? '' : 'disabled' ?>" href="<?= htmlspecialchars($cargoAndroid ?: '#', ENT_QUOTES, 'UTF-8') ?>" <?= $cargoAndroid ? 'target="_blank" rel="noopener"' : 'aria-disabled="true"' ?>>🤖 دانلود Android</a>
                    <a class="store <?= $cargoIos ? '' : 'disabled' ?>" href="<?= htmlspecialchars($cargoIos ?: '#', ENT_QUOTES, 'UTF-8') ?>" <?= $cargoIos ? 'target="_blank" rel="noopener"' : 'aria-disabled="true"' ?>> دانلود iOS</a>
                </div>
            </article>
        </div>
    </div></section>

    <section class="section flow" id="features"><div class="shell">
        <div class="section-head"><h2>ساخته‌شده برای یک جریان کاری واقعی</h2><p>دو اپ جدا، اما یک هویت کاربری؛ بدون ساخت حساب‌های تکراری و بدون قفل شدن شماره موبایل بین نقش‌ها.</p></div>
        <div class="feature-grid">
            <div class="feature"><span>1</span><h4>ثبت‌نام یکپارچه</h4><p>با همان شماره وارد هر اپ می‌شوید و نقش موردنیاز برای همان اپ فعال می‌شود.</p></div>
            <div class="feature"><span>2</span><h4>محتوای مستقل هر اپ</h4><p>سوالات متداول، راهنما و نیازهای پشتیبانی می‌توانند متناسب با راننده یا صاحب بار باشند.</p></div>
            <div class="feature"><span>3</span><h4>بروزرسانی از پنل</h4><p>لینک نسخه‌ها، قوانین و محتوای راهنما بدون انتشار مجدد وب‌سایت قابل مدیریت است.</p></div>
        </div>
    </div></section>

    <?php if ($supportPhone !== '' || $whatsapp !== '' || $telegram !== ''): ?>
    <section class="support"><div class="shell"><div class="support-box"><div><strong style="font-size:22px">برای نصب یا ورود مشکل دارید؟</strong><p>از یکی از راه‌های رسمی با پشتیبانی <?= htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') ?> ارتباط بگیرید.</p></div><div class="support-actions">
        <?php if ($supportPhone !== ''): ?><a href="tel:<?= htmlspecialchars(preg_replace('/[^0-9+]/', '', $supportPhone), ENT_QUOTES, 'UTF-8') ?>">☎ تماس</a><?php endif; ?>
        <?php if ($whatsapp !== ''): ?><a href="https://wa.me/<?= htmlspecialchars($whatsapp, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">واتساپ</a><?php endif; ?>
        <?php if ($telegram !== ''): ?><a href="<?= htmlspecialchars(str_starts_with($telegram, 'http') ? $telegram : 'https://t.me/' . ltrim($telegram, '@'), ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">تلگرام</a><?php endif; ?>
    </div></div></div></section>
    <?php endif; ?>
</main>
<footer class="footer"><div class="shell footer-row"><span>© <?= date('Y') ?> <?= htmlspecialchars($companyName, ENT_QUOTES, 'UTF-8') ?> — تمامی حقوق محفوظ است.</span><span><a href="<?= htmlspecialchars($termsUrl, ENT_QUOTES, 'UTF-8') ?>">قوانین و مقررات</a></span></div></footer>
</body>
</html>
