<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/init.php';
require_admin();
require_once __DIR__ . '/includes/internal_api.php';

$catalog = internal_api_endpoints_catalog();

// load current settings
$keys = [
    internal_api_setting_key('maintenance.enabled'),
    internal_api_setting_key('maintenance.message'),
    internal_api_setting_key('jwt.access_ttl_sec'),
    internal_api_setting_key('jwt.refresh_ttl_days'),
    internal_api_setting_key('otp.ttl_sec'),
    internal_api_setting_key('otp.cooldown_sec'),
    internal_api_setting_key('otp.length'),
    internal_api_setting_key('otp.max_attempts'),
    internal_api_setting_key('otp.max_per_phone_hour'),
    internal_api_setting_key('otp.max_per_phone_day'),
    internal_api_setting_key('otp.max_per_ip_hour'),
];

foreach ($catalog as $k => $_) {
    $keys[] = internal_api_setting_key('endpoint.enabled.' . $k);
}

$cur = settings_get_many($keys);

function _cur(string $k, $default = null)
{
    global $cur;
    return $cur[$k] ?? $default;
}

require_once "views/panel/header.php";
require_once "views/panel/sidebar.php";
?>

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-2">مدیریت APIهای داخلی اپلیکیشن</h1>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">تنظیمات</a></li>
                        <li class="breadcrumb-item active" aria-current="page">APIهای داخلی</li>
                    </ol>
                </nav>
            </div>
            <div class="btn-list">
                <button id="btnSaveInternalApi" class="btn btn-primary btn-wave">
                    <i class="ri-save-3-line align-middle"></i> ذخیره تنظیمات
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-xl-4">
                <div class="card custom-card">
                    <div class="card-header"><div class="card-title">Maintenance</div></div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="maintenance_enabled" <?= ((string)_cur(internal_api_setting_key('maintenance.enabled'), '0') === '1') ? 'checked' : '' ?>>
                            <label class="form-check-label" for="maintenance_enabled">فعال کردن حالت تعمیرات</label>
                        </div>
                        <label class="form-label" for="maintenance_message">پیام</label>
                        <textarea id="maintenance_message" class="form-control" rows="3" placeholder="پیام برای اپلیکیشن..."><?= htmlspecialchars((string)_cur(internal_api_setting_key('maintenance.message'), ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                        <div class="mt-2 fs-12 text-muted">در حالت Maintenance، فقط endpointهای Allow شده (مثل OTP و Meta) پاسخ می‌دهند.</div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="card custom-card">
                    <div class="card-header"><div class="card-title">JWT / Session</div></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="jwt_access_ttl_sec">عمر Access Token (ثانیه)</label>
                            <input id="jwt_access_ttl_sec" type="number" class="form-control" min="60" max="86400" value="<?= (int)(_cur(internal_api_setting_key('jwt.access_ttl_sec'), (string)api_jwt_access_ttl_sec())) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="jwt_refresh_ttl_days">عمر Refresh Token (روز)</label>
                            <input id="jwt_refresh_ttl_days" type="number" class="form-control" min="1" max="365" value="<?= (int)(_cur(internal_api_setting_key('jwt.refresh_ttl_days'), (string)api_jwt_refresh_ttl_days())) ?>">
                        </div>
                        <div class="fs-12 text-muted">پیشنهادی: access=900s (15min) و refresh=90days برای تجربه کاربری بهتر.</div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="card custom-card">
                    <div class="card-header"><div class="card-title">OTP</div></div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label" for="otp_ttl_sec">اعتبار کد (ثانیه)</label>
                                <input id="otp_ttl_sec" type="number" class="form-control" min="60" max="1800" value="<?= (int)(_cur(internal_api_setting_key('otp.ttl_sec'), (string)api_otp_ttl_sec())) ?>">
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="otp_cooldown_sec">Cooldown (ثانیه)</label>
                                <input id="otp_cooldown_sec" type="number" class="form-control" min="0" max="600" value="<?= (int)(_cur(internal_api_setting_key('otp.cooldown_sec'), (string)api_otp_cooldown_sec())) ?>">
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="otp_length">طول کد</label>
                                <input id="otp_length" type="number" class="form-control" min="4" max="8" value="<?= (int)(_cur(internal_api_setting_key('otp.length'), (string)api_otp_length())) ?>">
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="otp_max_attempts">حداکثر تلاش</label>
                                <input id="otp_max_attempts" type="number" class="form-control" min="1" max="10" value="<?= (int)(_cur(internal_api_setting_key('otp.max_attempts'), (string)api_otp_max_attempts())) ?>">
                            </div>
                            <div class="col-4">
                                <label class="form-label" for="otp_max_per_phone_hour">موبایل/ساعت</label>
                                <input id="otp_max_per_phone_hour" type="number" class="form-control" min="1" max="100" value="<?= (int)(_cur(internal_api_setting_key('otp.max_per_phone_hour'), (string)api_otp_max_per_phone_hour())) ?>">
                            </div>
                            <div class="col-4">
                                <label class="form-label" for="otp_max_per_phone_day">موبایل/روز</label>
                                <input id="otp_max_per_phone_day" type="number" class="form-control" min="1" max="500" value="<?= (int)(_cur(internal_api_setting_key('otp.max_per_phone_day'), (string)api_otp_max_per_phone_day())) ?>">
                            </div>
                            <div class="col-4">
                                <label class="form-label" for="otp_max_per_ip_hour">IP/ساعت</label>
                                <input id="otp_max_per_ip_hour" type="number" class="form-control" min="1" max="500" value="<?= (int)(_cur(internal_api_setting_key('otp.max_per_ip_hour'), (string)api_otp_max_per_ip_hour())) ?>">
                            </div>
                        </div>
                        <div class="mt-2 fs-12 text-muted">محدودیت‌ها برای جلوگیری از اسپم و حملات brute-force.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-header"><div class="card-title">کنترل Endpointها</div></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                <tr>
                                    <th style="width: 280px;">گروه</th>
                                    <th>Endpoint</th>
                                    <th style="width: 140px;">فعال</th>
                                    <th style="width: 200px;">در Maintenance اجازه دارد</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($catalog as $key => $info):
                                    [$title, $group, $defEnabled, $allowMaintenance] = $info;
                                    $curVal = (string)_cur(internal_api_setting_key('endpoint.enabled.' . $key), $defEnabled ? '1' : '0');
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars((string)$group, ENT_QUOTES, 'UTF-8') ?></td>
                                        <td>
                                            <div class="fw-semibold"><?= htmlspecialchars((string)$title, ENT_QUOTES, 'UTF-8') ?></div>
                                            <div class="fs-12 text-muted"><?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?></div>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input endpoint-toggle" type="checkbox"
                                                       data-key="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>" <?= $curVal === '1' ? 'checked' : '' ?>>
                                            </div>
                                        </td>
                                        <td>
                                            <?= $allowMaintenance ? '<span class="badge bg-success">بله</span>' : '<span class="badge bg-light text-dark">خیر</span>' ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
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
    $('#btnSaveInternalApi').on('click', function () {
        const endpoints = {};
        $('.endpoint-toggle').each(function () {
            const k = $(this).data('key');
            endpoints[k] = $(this).is(':checked') ? 1 : 0;
        });
        const payload = {
            maintenance_enabled: $('#maintenance_enabled').is(':checked') ? 1 : 0,
            maintenance_message: $('#maintenance_message').val(),
            jwt_access_ttl_sec: $('#jwt_access_ttl_sec').val(),
            jwt_refresh_ttl_days: $('#jwt_refresh_ttl_days').val(),
            otp_ttl_sec: $('#otp_ttl_sec').val(),
            otp_cooldown_sec: $('#otp_cooldown_sec').val(),
            otp_length: $('#otp_length').val(),
            otp_max_attempts: $('#otp_max_attempts').val(),
            otp_max_per_phone_hour: $('#otp_max_per_phone_hour').val(),
            otp_max_per_phone_day: $('#otp_max_per_phone_day').val(),
            otp_max_per_ip_hour: $('#otp_max_per_ip_hour').val(),
            endpoints
        };

        $.ajax({
            url: `${BASE_URL}/ajax/internal-apis.php`,
            type: 'POST',
            data: JSON.stringify(payload),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            success: function (res) {
                if (res && res.ok) {
                    toast('success', 'ذخیره شد');
                    return;
                }
                toast('error', res?.message || 'خطا در ذخیره');
            },
            error: function () { toast('error', 'خطا در ارتباط'); }
        });
    });

    function toast(type, msg) {
        if (window.Toastify) {
            Toastify({ text: msg, duration: 3000, gravity: "top", position: "left" }).showToast();
            return;
        }
        alert(msg);
    }
</script>

<?php require_once "views/panel/footer.php"; ?>
