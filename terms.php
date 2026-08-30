<?php

declare(strict_types=1);
require_once __DIR__ . '/includes/init.php';

$settings = settings_get_many([
    'company.name', 'site.name', 'site.logo_path', 'site.favicon_path',
    'links.terms_text', 'links.app_download_url',
    'support.phone', 'support.whatsapp',
]);

$companyName = trim((string)($settings['company.name'] ?? '')) ?: 'آموت بار';
$siteName = trim((string)($settings['site.name'] ?? '')) ?: $companyName;
$appDownloadUrl = trim((string)($settings['links.app_download_url'] ?? ''));
$supportPhone = trim((string)($settings['support.phone'] ?? ''));
$supportWhatsapp = preg_replace('/\D+/', '', (string)($settings['support.whatsapp'] ?? ''));

function public_asset_url(?string $value): string
{
    $value = trim((string)$value);
    if ($value === '') return '';
    if (preg_match('~^https?://~i', $value)) return $value;
    return url_path(ltrim($value, '/'));
}

$logoUrl = public_asset_url($settings['site.logo_path'] ?? '');
if ($logoUrl === '') $logoUrl = asset('images/brand-logos/logo.png');
$faviconUrl = public_asset_url($settings['site.favicon_path'] ?? '');

$defaultTerms = <<<'TXT'
## تعاریف و اصطلاحات
آموت بار بستر هوشمند اعلان و مدیریت بار است. «کاربر» به هر شخص حقیقی یا حقوقی ثبت‌نام‌شده، «صاحب بار» به ثبت‌کننده درخواست حمل و «راننده» به متصدی حمل دارای صلاحیت و مدارک معتبر گفته می‌شود.

## موارد سلب مسئولیت و حدود تعهدات
حمل بار برون‌شهری باید مطابق قوانین و با اسناد و مجوزهای لازم انجام شود. کرایه بر پایه توافق طرفین تعیین می‌شود. حمل کالاهای غیرقانونی یا فاقد مجوز ممنوع است و مسئولیت اطلاعات و کالای اعلام‌شده بر عهده ثبت‌کننده آن است.

## کلیات و احکام قرارداد
استفاده از سامانه به منزله پذیرش این مقررات است. آموت بار می‌تواند ضوابط را در چارچوب قوانین به‌روزرسانی کند و نسخه جدید از زمان انتشار معتبر خواهد بود.

## مقررات ثبت‌نام و امنیت حساب کاربری
کاربر موظف است اطلاعات هویتی و مشخصات وسیله نقلیه را صحیح وارد کند و از واگذاری حساب یا کدهای تایید به اشخاص دیگر خودداری نماید. مسئولیت حفاظت از دسترسی حساب بر عهده دارنده آن است.

## قوانین عملیاتی و حمل بار
سامانه نقش بستر ارتباطی میان صاحب بار و راننده را دارد. ظرفیت مجاز خودرو، مقررات بارگیری، بارنامه، بیمه و سایر الزامات قانونی باید توسط طرفین رعایت شود.

## حریم خصوصی و حفاظت از داده‌ها
اطلاعات کاربران صرفاً برای ارائه خدمات، امنیت، احراز هویت و الزامات قانونی پردازش می‌شود. اطلاعات موردنیاز یک سفارش فقط در حد لازم در اختیار طرف مرتبط قرار می‌گیرد.

## تعرفه‌ها و طرح‌های تشویقی
شرایط تعرفه‌ها، اعتبارها و طرح‌های تشویقی مطابق توضیحات همان خدمت یا کمپین است و ممکن است دارای تاریخ انقضا یا محدودیت استفاده باشد.

## الزامات فنی و مالکیت معنوی
مهندسی معکوس، دسترسی غیرمجاز، خزش مخرب و سوءاستفاده از زیرساخت سامانه ممنوع است. حقوق برند، طراحی و نرم‌افزار متعلق به صاحبان قانونی آن است.

## مسدودسازی و تعلیق دسترسی
در صورت نقض قوانین، ایجاد خطر برای کاربران یا زیرساخت، تقلب یا ارائه اطلاعات خلاف واقع، دسترسی حساب می‌تواند تا زمان بررسی محدود یا مسدود شود.

## توافقات الکترونیکی و اعتبار قانونی
ثبت درخواست، تایید عملیات و ورود کد یکبارمصرف می‌تواند به عنوان اعلام اراده الکترونیکی کاربر ثبت شود. سوابق سامانه مطابق قوانین قابل نگهداری و استناد هستند.
TXT;

$termsText = trim((string)($settings['links.terms_text'] ?? ''));
if ($termsText === '') $termsText = $defaultTerms;

/** @return array<int,array{title:string,body:string}> */
function parse_terms_sections(string $text): array
{
    $text = str_replace(["\r\n", "\r"], "\n", trim($text));
    $sections = [];
    $title = '';
    $buffer = [];
    $flush = static function () use (&$sections, &$title, &$buffer): void {
        $body = trim(implode("\n", $buffer));
        if ($title !== '' || $body !== '') {
            $sections[] = ['title' => $title !== '' ? $title : 'قوانین و مقررات', 'body' => $body];
        }
        $buffer = [];
    };

    foreach (explode("\n", $text) as $line) {
        if (preg_match('/^\s*##\s+(.+)$/u', $line, $m)) {
            if ($title !== '' || $buffer) $flush();
            $title = trim($m[1]);
            continue;
        }
        $buffer[] = $line;
    }
    $flush();
    return array_values(array_filter($sections, static fn($s) => $s['title'] !== '' || $s['body'] !== ''));
}

$sections = parse_terms_sections($termsText);
?>
<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
    <title>قوانین و مقررات | <?= htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') ?></title>
    <?php if ($faviconUrl !== ''): ?><link rel="icon" href="<?= htmlspecialchars($faviconUrl, ENT_QUOTES, 'UTF-8') ?>"><?php endif; ?>
    <style>
        :root{--primary:#00bffe;--primary-dark:#0099cc;--ink:#20242a;--muted:#707782;--line:#e8ebef;--surface:#fff;--soft:#f6fbfd;--shadow:0 16px 50px rgba(18,50,66,.08)}
        *{box-sizing:border-box} body{margin:0;font-family:Tahoma,"Segoe UI",sans-serif;color:var(--ink);background:linear-gradient(180deg,#f7fcfe 0,#fff 34%);line-height:1.95}
        a{color:inherit}.topbar{height:4px;background:var(--primary)}.shell{width:min(940px,calc(100% - 32px));margin:auto}.header{padding:26px 0 12px}.nav{display:flex;align-items:center;justify-content:space-between;gap:16px}.brand{display:flex;align-items:center;gap:12px;text-decoration:none;font-weight:800}.brand img{width:48px;height:48px;object-fit:contain;border-radius:12px;background:white}.brand-mark{width:46px;height:46px;display:grid;place-items:center;border-radius:14px;background:var(--primary);color:white;font-weight:900}.download{padding:10px 16px;border-radius:12px;text-decoration:none;border:1px solid rgba(0,191,254,.25);color:var(--primary-dark);font-weight:700;background:white}
        .hero{text-align:center;padding:42px 14px 30px}.hero-icon{width:80px;height:80px;border-radius:26px;margin:0 auto 20px;display:grid;place-items:center;background:rgba(0,191,254,.11);color:var(--primary);font-size:36px}.hero h1{font-size:clamp(26px,5vw,40px);margin:0 0 12px}.hero p{margin:0 auto;color:var(--muted);max-width:650px}
        .notice{display:flex;gap:12px;align-items:flex-start;background:#effaff;border:1px solid #c8effe;padding:15px 17px;border-radius:16px;margin:4px 0 22px;color:#315765}.notice b{color:#0b809f}.terms{display:grid;gap:12px;padding-bottom:50px}.term{background:var(--surface);border:1px solid var(--line);border-radius:18px;box-shadow:0 4px 22px rgba(30,60,80,.035);overflow:hidden}.term button{font:inherit;width:100%;border:0;background:white;padding:18px 20px;display:flex;align-items:center;justify-content:space-between;gap:16px;text-align:right;cursor:pointer;color:var(--ink)}.term-title{display:flex;align-items:center;gap:13px;font-weight:800}.index{min-width:36px;height:36px;border-radius:11px;display:grid;place-items:center;background:var(--soft);color:var(--primary-dark);font-size:13px}.arrow{font-size:20px;color:#9097a1;transition:.25s}.body{display:none;padding:0 22px 22px 70px;color:#4f5863;white-space:pre-line;text-align:justify}.term.open{border-color:#caeffb;box-shadow:var(--shadow)}.term.open .body{display:block}.term.open .arrow{transform:rotate(180deg);color:var(--primary)}
        .footer{border-top:1px solid var(--line);padding:25px 0 38px;color:var(--muted);font-size:13px}.footer-row{display:flex;justify-content:space-between;gap:15px;flex-wrap:wrap}.contact-links{display:flex;gap:14px;flex-wrap:wrap}.contact-links a{text-decoration:none;color:var(--primary-dark)}
        @media(max-width:600px){.download{display:none}.header{padding-top:18px}.hero{padding-top:26px}.term button{padding:16px}.body{padding:0 17px 19px}.index{min-width:32px;height:32px}.hero-icon{width:68px;height:68px;border-radius:21px}.notice{font-size:13px}}
    </style>
</head>
<body>
<div class="topbar"></div>
<header class="header"><div class="shell nav">
    <a class="brand" href="<?= htmlspecialchars($appDownloadUrl !== '' ? $appDownloadUrl : 'app.php', ENT_QUOTES, 'UTF-8') ?>">
        <?php if ($logoUrl !== ''): ?><img src="<?= htmlspecialchars($logoUrl, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') ?>"><?php else: ?><span class="brand-mark">آ</span><?php endif; ?>
        <span><?= htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') ?></span>
    </a>
    <?php if ($appDownloadUrl !== ''): ?><a class="download" href="<?= htmlspecialchars($appDownloadUrl, ENT_QUOTES, 'UTF-8') ?>">دانلود اپلیکیشن‌ها</a><?php endif; ?>
</div></header>
<main class="shell">
    <section class="hero">
        <div class="hero-icon">⚖</div>
        <h1>قوانین و مقررات</h1>
        <p>شرایط استفاده از خدمات <?= htmlspecialchars($companyName, ENT_QUOTES, 'UTF-8') ?>. این متن مستقیماً از پنل مدیریت به‌روزرسانی می‌شود.</p>
    </section>
    <div class="notice"><span>ⓘ</span><div><b>توجه:</b> ثبت‌نام، ورود یا ادامه استفاده از خدمات به منزله مطالعه و پذیرش مقررات جاری سامانه است.</div></div>
    <section class="terms" aria-label="بخش‌های قوانین">
        <?php foreach ($sections as $i => $section): ?>
            <article class="term <?= $i === 0 ? 'open' : '' ?>">
                <button type="button" aria-expanded="<?= $i === 0 ? 'true' : 'false' ?>">
                    <span class="term-title"><span class="index"><?= htmlspecialchars((string)($i + 1), ENT_QUOTES, 'UTF-8') ?></span><?= htmlspecialchars($section['title'], ENT_QUOTES, 'UTF-8') ?></span>
                    <span class="arrow">⌄</span>
                </button>
                <div class="body"><?= htmlspecialchars($section['body'], ENT_QUOTES, 'UTF-8') ?></div>
            </article>
        <?php endforeach; ?>
    </section>
</main>
<footer class="footer"><div class="shell footer-row">
    <span>© <?= date('Y') ?> <?= htmlspecialchars($companyName, ENT_QUOTES, 'UTF-8') ?> — تمامی حقوق محفوظ است.</span>
    <span class="contact-links">
        <?php if ($supportPhone !== ''): ?><a href="tel:<?= htmlspecialchars(preg_replace('/[^0-9+]/', '', $supportPhone), ENT_QUOTES, 'UTF-8') ?>">تماس با پشتیبانی</a><?php endif; ?>
        <?php if ($supportWhatsapp !== ''): ?><a href="https://wa.me/<?= htmlspecialchars($supportWhatsapp, ENT_QUOTES, 'UTF-8') ?>">واتساپ</a><?php endif; ?>
    </span>
</div></footer>
<script>
document.querySelectorAll('.term>button').forEach(btn=>btn.addEventListener('click',()=>{const card=btn.closest('.term');const open=card.classList.toggle('open');btn.setAttribute('aria-expanded',open?'true':'false')}));
</script>
</body>
</html>
