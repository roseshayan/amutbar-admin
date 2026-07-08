<?php
require_once __DIR__ . '/includes/init.php';
require_admin();
require_once __DIR__ . '/includes/jalali.php';

try { driver_activity_ensure_schema(); } catch (Throwable $e) {}

$todayG = date('Y-m-d');
$fromJ = gdate_to_jalali_str(date('Y-m-d', strtotime('-7 days')));
$toJ = gdate_to_jalali_str($todayG);
$userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
$driverId = isset($_GET['driver_id']) ? (int)$_GET['driver_id'] : 0;

$eventOptions = [
    '' => 'همه فعالیت‌ها',
    'app_open' => 'باز شدن اپلیکیشن',
    'dashboard_view' => 'مشاهده داشبورد',
    'nearby_loads_view' => 'بارهای اطراف من',
    'search_page_view' => 'صفحه جستجوی بار',
    'search_filter_set' => 'انتخاب فیلتر جستجو',
    'search_loads' => 'جستجوی بار',
    'load_card_open' => 'باز کردن کارت بار',
    'load_detail_view' => 'مشاهده جزئیات بار',
    'call_button_tap' => 'کلیک تماس',
    'map_route_open' => 'باز کردن مسیر نقشه',
    'profile_view' => 'مشاهده پروفایل',
    'identity_info_view' => 'مشخصات هویتی',
    'vehicle_info_view' => 'مشخصات خودرو',
    'vehicle_info_update' => 'ویرایش مشخصات خودرو',
    'support_view' => 'پشتیبانی',
    'support_contact_tap' => 'ارتباط سریع با پشتیبانی',
    'ticket_create' => 'ثبت تیکت',
    'ticket_view' => 'مشاهده تیکت',
    'ticket_reply' => 'پاسخ تیکت',
    'logout' => 'خروج از حساب',
];

require_once 'views/panel/header.php';
require_once 'views/panel/sidebar.php';
?>

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-2">گزارش جزئی فعالیت رانندگان</h1>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="dashboard.php">داشبورد</a></li>
                        <li class="breadcrumb-item active" aria-current="page">فعالیت رانندگان</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="alert alert-info mb-3">
                            این گزارش جزئیات کار راننده داخل اپ را نشان می‌دهد: ورود به صفحه‌ها، جستجوی بار، باز کردن بار، تماس، نقشه، پروفایل و تیکت‌ها. گزارش ورود/خروج قبلی فقط آمار کلی بود و برای رفتار جزئی کافی نبود.
                        </div>
                        <form id="filtersForm" class="row g-3 align-items-end">
                            <div class="col-12 col-md-2">
                                <label class="form-label" for="from">از تاریخ</label>
                                <input id="from" name="from" class="form-control" value="<?= htmlspecialchars($fromJ, ENT_QUOTES, 'UTF-8') ?>" placeholder="YYYY/MM/DD">
                            </div>
                            <div class="col-12 col-md-2">
                                <label class="form-label" for="to">تا تاریخ</label>
                                <input id="to" name="to" class="form-control" value="<?= htmlspecialchars($toJ, ENT_QUOTES, 'UTF-8') ?>" placeholder="YYYY/MM/DD">
                            </div>
                            <div class="col-12 col-md-2">
                                <label class="form-label" for="user_id">شناسه کاربر</label>
                                <input id="user_id" name="user_id" type="number" min="0" class="form-control" value="<?= $userId > 0 ? $userId : '' ?>" placeholder="مثلاً 53">
                            </div>
                            <div class="col-12 col-md-2">
                                <label class="form-label" for="driver_id">شناسه راننده</label>
                                <input id="driver_id" name="driver_id" type="number" min="0" class="form-control" value="<?= $driverId > 0 ? $driverId : '' ?>" placeholder="اختیاری">
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label" for="event_key">نوع فعالیت</label>
                                <select id="event_key" name="event_key" class="form-select">
                                    <?php foreach ($eventOptions as $k => $v): ?>
                                        <option value="<?= htmlspecialchars($k, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($v, ENT_QUOTES, 'UTF-8') ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12 col-md-1 d-grid">
                                <button class="btn btn-primary btn-wave" type="submit">فیلتر</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="driverActivitiesTable" class="table table-striped table-bordered w-100 align-middle">
                                <thead>
                                    <tr>
                                        <th>زمان</th>
                                        <th>راننده</th>
                                        <th>فعالیت</th>
                                        <th>صفحه</th>
                                        <th>مورد مرتبط</th>
                                        <th>کلاینت</th>
                                        <th>IP</th>
                                        <th>جزئیات</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const BASE_URL = "<?= base_url(); ?>";
    let table;

    function filterPayload() {
        return {
            from: document.getElementById('from').value,
            to: document.getElementById('to').value,
            user_id: document.getElementById('user_id').value,
            driver_id: document.getElementById('driver_id').value,
            event_key: document.getElementById('event_key').value,
        };
    }

    document.addEventListener('DOMContentLoaded', () => {
        table = $('#driverActivitiesTable').DataTable({
            processing: true,
            serverSide: true,
            searchDelay: 500,
            order: [[0, 'desc']],
            pageLength: 25,
            ajax: {
                url: `${BASE_URL}/ajax/driver-activities/list.php`,
                type: 'POST',
                data: function(d) {
                    return Object.assign(d, filterPayload());
                }
            },
            columns: [
                {data: 'created_at'},
                {data: 'driver', orderable: true},
                {data: 'event'},
                {data: 'screen'},
                {data: 'entity'},
                {data: 'client'},
                {data: 'ip'},
                {data: 'payload', orderable: false, searchable: false},
            ],
            language: { url: 'assets/vendor/datatables/fa.json' },
        });

        document.getElementById('filtersForm').addEventListener('submit', (e) => {
            e.preventDefault();
            table.ajax.reload();
        });
    });
</script>

<?php require_once 'views/panel/footer.php'; ?>
