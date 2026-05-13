<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/init.php';
require_admin();
require_once __DIR__ . '/includes/jalali.php';

// -----------------------------
// Filters
// -----------------------------
$todayG = date('Y-m-d');
$defaultFromG = date('Y-m-d', strtotime('-30 days'));

$defaultFromJ = gdate_to_jalali_str($defaultFromG);
$todayJ = gdate_to_jalali_str($todayG);

$fromJ = (string)($_GET['from'] ?? $defaultFromJ);
$toJ = (string)($_GET['to'] ?? $todayJ);

$userType = (int)($_GET['user_type'] ?? 0);
$eventType = (int)($_GET['event_type'] ?? 0);

$fromG = jdate_str_to_gdate($fromJ) ?? $defaultFromG;
$toG = jdate_str_to_gdate($toJ) ?? $todayG;

if (strtotime($fromG) > strtotime($toG)) {
    [$fromG, $toG] = [$toG, $fromG];
    [$fromJ, $toJ] = [$toJ, $fromJ];
}

if (!in_array($userType, [0, 1, 2, 3], true)) $userType = 0;
if (!in_array($eventType, [0, 1, 2, 3, 4, 5], true)) $eventType = 0;

$eventLabels = [
    1 => 'ورود موفق (رمز/ادمین)',
    2 => 'خروج',
    3 => 'ورود با یادآوری (ادمین)',
    4 => 'ورود موفق (OTP/اپ)',
    5 => 'ورود ناموفق',
];

$pdo = db();

$where = ' WHERE stat_date BETWEEN ? AND ? ';
$bind = [$fromG, $toG];
if ($userType !== 0) {
    $where .= ' AND user_type = ? ';
    $bind[] = $userType;
}
if ($eventType !== 0) {
    $where .= ' AND event_type = ? ';
    $bind[] = $eventType;
}

// CSV export
if (isset($_GET['export']) && (int)$_GET['export'] === 1) {
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="auth-report-' . $fromJ . '_to_' . $toJ . '.csv"');
    echo "\xEF\xBB\xBF";

    $out = fopen('php://output', 'wb');
    fputcsv($out, ['تاریخ', 'نوع کاربر', 'رویداد', 'تعداد']);

    $st = $pdo->prepare(
        "SELECT stat_date, user_type, event_type, cnt
         FROM auth_daily_stats
         {$where}
         ORDER BY stat_date DESC, user_type ASC, event_type ASC"
    );
    $st->execute($bind);
    while ($r = $st->fetch()) {
        $ut = (int)$r['user_type'];
        $et = (int)$r['event_type'];
        fputcsv($out, [
            gdate_to_jalali_str((string)$r['stat_date']),
            user_type_label($ut),
            $eventLabels[$et] ?? ('رویداد #' . $et),
            (int)$r['cnt'],
        ]);
    }

    fclose($out);
    exit;
}

// Summary cards
$stTotals = $pdo->prepare(
    "SELECT user_type, event_type, SUM(cnt) AS total
     FROM auth_daily_stats
     {$where}
     GROUP BY user_type, event_type"
);
$stTotals->execute($bind);
$totals = $stTotals->fetchAll() ?: [];

$sum = [
    1 => ['login' => 0, 'logout' => 0, 'failed' => 0],
    2 => ['login' => 0, 'logout' => 0, 'failed' => 0],
    3 => ['login' => 0, 'logout' => 0, 'failed' => 0],
    0 => ['login' => 0, 'logout' => 0, 'failed' => 0],
];

foreach ($totals as $t) {
    $ut = (int)$t['user_type'];
    $et = (int)$t['event_type'];
    $c = (int)$t['total'];
    if (in_array($et, [1, 3, 4], true)) {
        $sum[$ut]['login'] += $c;
        $sum[0]['login'] += $c;
        continue;
    }
    if ($et === 2) {
        $sum[$ut]['logout'] += $c;
        $sum[0]['logout'] += $c;
        continue;
    }
    if ($et === 5) {
        $sum[$ut]['failed'] += $c;
        $sum[0]['failed'] += $c;
    }
}

require_once "views/panel/header.php";
require_once "views/panel/sidebar.php";

?>

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-2">گزارش ورود/خروج</h1>
                <div>
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">گزارش‌ها</a></li>
                            <li class="breadcrumb-item active" aria-current="page">ورود/خروج</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="btn-list">
                <a class="btn btn-secondary-light btn-wave me-0" href="auth-report.php?from=<?= urlencode(convertPersianNumbersToEnglishPHP($fromJ)) ?>&to=<?= urlencode(convertPersianNumbersToEnglishPHP($toJ)) ?>&user_type=<?= (int)$userType ?>&event_type=<?= (int)$eventType ?>&export=1">
                    <i class="ri-upload-cloud-line align-middle"></i> خروجی CSV
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <form class="row g-3 align-items-end" method="get" action="auth-report.php">
                            <div class="col-12 col-md-3">
                                <label for="from" class="form-label">از تاریخ</label>
                                <input type="text" id="from" class="form-control" name="from" value="<?= htmlspecialchars($fromJ, ENT_QUOTES, 'UTF-8') ?>" placeholder="YYYY/MM/DD">
                            </div>
                            <div class="col-12 col-md-3">
                                <label for="to" class="form-label">تا تاریخ</label>
                                <input id="to" type="text" class="form-control" name="to" value="<?= htmlspecialchars($toJ, ENT_QUOTES, 'UTF-8') ?>" placeholder="YYYY/MM/DD">
                            </div>
                            <div class="col-12 col-md-3">
                                <label for="user_type" class="form-label">نوع کاربر</label>
                                <select id="user_type" class="form-select" name="user_type">
                                    <option value="0" <?= $userType === 0 ? 'selected' : '' ?>>همه</option>
                                    <option value="1" <?= $userType === 1 ? 'selected' : '' ?>>راننده</option>
                                    <option value="2" <?= $userType === 2 ? 'selected' : '' ?>>باربری</option>
                                    <option value="3" <?= $userType === 3 ? 'selected' : '' ?>>ادمین</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-3">
                                <label for="event_type" class="form-label">نوع رویداد</label>
                                <select id="event_type" class="form-select" name="event_type">
                                    <option value="0" <?= $eventType === 0 ? 'selected' : '' ?>>همه</option>
                                    <option value="1" <?= $eventType === 1 ? 'selected' : '' ?>><?= $eventLabels[1] ?></option>
                                    <option value="2" <?= $eventType === 2 ? 'selected' : '' ?>><?= $eventLabels[2] ?></option>
                                    <option value="3" <?= $eventType === 3 ? 'selected' : '' ?>><?= $eventLabels[3] ?></option>
                                    <option value="4" <?= $eventType === 4 ? 'selected' : '' ?>><?= $eventLabels[4] ?></option>
                                    <option value="5" <?= $eventType === 5 ? 'selected' : '' ?>><?= $eventLabels[5] ?></option>
                                </select>
                            </div>
                            <div class="col-12 d-flex gap-2">
                                <button class="btn btn-primary btn-wave" type="submit"><i class="ri-filter-3-line align-middle"></i> اعمال فیلتر</button>
                                <a class="btn btn-light btn-wave" href="auth-report.php">پاک کردن فیلتر</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-xl-4">
                <div class="card custom-card"><div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div><div class="fs-12 text-muted">ورودهای موفق (کل)</div><div class="fs-20 fw-semibold"><?= number_format($sum[0]['login']) ?></div></div>
                        <div class="text-end"><div class="fs-12 text-muted">خروج (کل)</div><div class="fs-20 fw-semibold"><?= number_format($sum[0]['logout']) ?></div></div>
                    </div>
                    <div class="mt-2 fs-12 text-muted">بازه: <?= htmlspecialchars($fromJ, ENT_QUOTES, 'UTF-8') ?> تا <?= htmlspecialchars($toJ, ENT_QUOTES, 'UTF-8') ?></div>
                </div></div>
            </div>
            <div class="col-12 col-xl-4">
                <div class="card custom-card"><div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div><div class="fs-12 text-muted">ورود ناموفق (کل)</div><div class="fs-20 fw-semibold"><?= number_format($sum[0]['failed']) ?></div></div>
                        <div class="text-end"><div class="fs-12 text-muted">راننده / باربری / ادمین (ورود موفق)</div>
                            <div class="fs-14 fw-semibold"><?= number_format($sum[1]['login']) ?> / <?= number_format($sum[2]['login']) ?> / <?= number_format($sum[3]['login']) ?></div>
                        </div>
                    </div>
                    <div class="mt-2 fs-12 text-muted">تفکیک ورود موفق بر اساس نقش</div>
                </div></div>
            </div>
            <div class="col-12 col-xl-4">
                <div class="card custom-card"><div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div><div class="fs-12 text-muted">ادمین (ورود/خروج/ناموفق)</div><div class="fs-14 fw-semibold"><?= number_format($sum[3]['login']) ?> / <?= number_format($sum[3]['logout']) ?> / <?= number_format($sum[3]['failed']) ?></div></div>
                        <div class="text-end"><div class="fs-12 text-muted">کاربران (ورود/خروج/ناموفق)</div><div class="fs-14 fw-semibold"><?= number_format($sum[1]['login'] + $sum[2]['login']) ?> / <?= number_format($sum[1]['logout'] + $sum[2]['logout']) ?> / <?= number_format($sum[1]['failed'] + $sum[2]['failed']) ?></div></div>
                    </div>
                    <div class="mt-2 fs-12 text-muted">جمع راننده + باربری به عنوان کاربران</div>
                </div></div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">جزئیات روزانه</div>
                        <div class="fs-12 text-muted">داده‌ها از جدول تجمیعی روزانه (بهینه)</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="authReportTable" class="table table-bordered table-striped w-100">
                                <thead><tr><th>تاریخ</th><th>نوع کاربر</th><th>رویداد</th><th>تعداد</th></tr></thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="node_modules/persian-datepicker/dist/css/persian-datepicker.min.css">
<script src="node_modules/persian-date/dist/persian-date.min.js"></script>
<script src="node_modules/persian-datepicker/dist/js/persian-datepicker.min.js"></script>

<script>
    const BASE_URL = "<?= base_url(); ?>";

    function initPersianFilterPickers() {
        if (!$.fn.pDatepicker) return;
        $('#from').pDatepicker({
            format:'YYYY/MM/DD',
            autoClose:true,
            initialValue:true,
            initialValueType:'persian',
            toolbox:{todayButton:{enabled:true,text:"امروز"}}
        });
        $('#to').pDatepicker({
            format:'YYYY/MM/DD',
            autoClose:true,
            initialValue:true,
            initialValueType:'persian',
            toolbox:{todayButton:{enabled:true,text:"امروز"}}
        });
    }

    $(function () {
        initPersianFilterPickers();
        if (!$.fn.DataTable) return;

        const table = $('#authReportTable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            order: [[0, 'desc']],
            ajax: {
                url: `${BASE_URL}/ajax/auth/report_rows.php`,
                type: 'GET',
                data: function (d) {
                    d.from = convertToEnglishDate($('#from').val());
                    d.to = convertToEnglishDate($('#to').val());
                    d.user_type = $('#user_type').val();
                    d.event_type = $('#event_type').val();
                }
            },
            columns: [
                { data: 'date' },
                { data: 'user_type' },
                { data: 'event' },
                { data: 'cnt' }
            ],
            language: {
                search: "جستجو:",
                lengthMenu: "نمایش _MENU_ ردیف",
                info: "نمایش _START_ تا _END_ از _TOTAL_ ردیف",
                infoEmpty: "هیچ داده‌ای موجود نیست",
                processing: "در حال بارگذاری...",
                zeroRecords: "داده‌ای پیدا نشد",
                paginate: {previous: "قبلی", next: "بعدی"}
            }
        });

        function convertToEnglishDate(persianDate) {
            // جدا کردن اجزای تاریخ
            const parts = persianDate.split('/');

            if (parts.length !== 3) {
                return "فرمت تاریخ نامعتبر است";
            }

            // تبدیل اعداد فارسی به انگلیسی
            const persianToEnglish = {
                '۰': '0', '۱': '1', '۲': '2', '۳': '3', '۴': '4',
                '۵': '5', '۶': '6', '۷': '7', '۸': '8', '۹': '9'
            };

            // تبدیل هر بخش
            const englishParts = parts.map(part => {
                return part.split('').map(char =>
                    persianToEnglish[char] || char
                ).join('');
            });

            // اضافه کردن صفر به اعداد یک رقمی
            const formattedParts = englishParts.map((part, index) => {
                if (index > 0 && part.length === 1) {
                    return '0' + part;
                }
                return part;
            });

            return formattedParts.join('/');
        }

        $('#user_type, #event_type').on('change', function () { table.ajax.reload(); });
        $('#from, #to').on('change', function () { table.ajax.reload(); });
    });
</script>

<?php
require_once "views/panel/footer.php";
?>
