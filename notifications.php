<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/includes/push_service.php';
require_admin();

$pdo = db();
$driversCount = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE user_type=1 AND deleted_at IS NULL")->fetchColumn();
$cargoCount   = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE user_type=2 AND deleted_at IS NULL")->fetchColumn();
$devicesCount = (int)$pdo->query("SELECT COUNT(*) FROM user_push_tokens")->fetchColumn();
$sentLogsCount= (int)$pdo->query("SELECT COUNT(*) FROM push_broadcast_logs")->fetchColumn();

require_once "views/panel/header.php";
require_once "views/panel/sidebar.php";
?>

<link rel="stylesheet" href="assets/vendor/select2/css/select2.min.css">
<script src="assets/vendor/select2/js/select2.full.min.js"></script>

<div class="main-content app-content">
    <div class="container-fluid">

        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-2">ارسال و مدیریت اعلان‌ها (Push Notifications)</h1>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="dashboard.php">داشبورد</a></li>
                        <li class="breadcrumb-item active" aria-current="page">اعلان‌ها و پوش‌نوتیفیکیشن</li>
                    </ol>
                </nav>
            </div>
            <div class="btn-list">
                <button class="btn btn-outline-secondary btn-wave" data-bs-toggle="modal" data-bs-target="#fcmSettingsModal">
                    <i class="ri-settings-4-line align-middle me-1"></i> تنظیمات کلید Firebase (FCM)
                </button>
            </div>
        </div>

        <!-- آمار کلی مخاطبین -->
        <div class="row">
            <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <span class="avatar avatar-md bg-primary-transparent text-primary rounded-circle">
                                <i class="ri-truck-line fs-20"></i>
                            </span>
                            <div>
                                <span class="d-block text-muted fs-12">رانندگان ثبت‌نامی</span>
                                <h5 class="fw-bold mb-0 text-primary"><?= number_format($driversCount) ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <span class="avatar avatar-md bg-success-transparent text-success rounded-circle">
                                <i class="ri-building-line fs-20"></i>
                            </span>
                            <div>
                                <span class="d-block text-muted fs-12">صاحبان بار و باربری‌ها</span>
                                <h5 class="fw-bold mb-0 text-success"><?= number_format($cargoCount) ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <span class="avatar avatar-md bg-info-transparent text-info rounded-circle">
                                <i class="ri-cellphone-line fs-20"></i>
                            </span>
                            <div>
                                <span class="d-block text-muted fs-12">دستگاه‌های فعال ثبت‌شده</span>
                                <h5 class="fw-bold mb-0 text-info"><?= number_format($devicesCount) ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <span class="avatar avatar-md bg-warning-transparent text-warning rounded-circle">
                                <i class="ri-notification-3-line fs-20"></i>
                            </span>
                            <div>
                                <span class="d-block text-muted fs-12">تعداد ارسال‌های پیشین</span>
                                <h5 class="fw-bold mb-0 text-warning"><?= number_format($sentLogsCount) ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- فرم ارسال اعلان و پیش‌نمایش زنده -->
        <div class="row">
            <div class="col-xl-8 col-lg-7">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">ارسال اعلان جدید (نوتیفیکیشن)</div>
                    </div>
                    <div class="card-body">
                        <form id="pushForm">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-bold">مخاطبین دریافت‌کننده *</label>
                                    <div class="d-flex flex-wrap gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="audience" id="aud_all" value="all" checked>
                                            <label class="form-check-label" for="aud_all">
                                                همه کاربران (رانندگان و صاحبان بار)
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="audience" id="aud_drivers" value="drivers">
                                            <label class="form-check-label" for="aud_drivers">
                                                فقط رانندگان (اپ آموت‌بار رانندگان)
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="audience" id="aud_cargo" value="cargo">
                                            <label class="form-check-label" for="aud_cargo">
                                                فقط صاحبان بار (اپ اعلام بار)
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="audience" id="aud_user" value="user">
                                            <label class="form-check-label" for="aud_user">
                                                یک کاربر مشخص
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 d-none" id="specific_user_wrap">
                                    <label class="form-label fw-bold">جستجوی کاربر (شماره تلفن یا نام) *</label>
                                    <select class="form-control" name="target_user_id" id="user_search"></select>
                                </div>

                                <div class="col-12">
                                    <label for="push_title" class="form-label fw-bold">عنوان اعلان *</label>
                                    <input type="text" class="form-control" name="title" id="push_title" required maxlength="120" placeholder="مثلاً: بار جدید در مسیر تهران - مشهد یا اطلاعیه مهم">
                                </div>

                                <div class="col-12">
                                    <label for="push_body" class="form-label fw-bold">متن پیام اعلان *</label>
                                    <textarea class="form-control" name="body" id="push_body" rows="3" required maxlength="500" placeholder="متن پیام که روی گوشی کاربر و در نوار نوتیفیکیشن نمایش داده می‌شود..."></textarea>
                                </div>

                                <div class="col-md-6">
                                    <label for="push_action_type" class="form-label">نوع عملیات هنگام باز کردن</label>
                                    <select class="form-select" name="action_type" id="push_action_type">
                                        <option value="general">پیام عمومی (باز شدن صفحه اصلی برنامه)</option>
                                        <option value="load">ارجاع به بار خاص (شناسه بار)</option>
                                        <option value="ticket_reply">ارجاع به تیکت پشتیبانی</option>
                                        <option value="url">باز کردن لینک اینترنتی</option>
                                    </select>
                                </div>

                                <div class="col-md-6" id="action_data_wrap">
                                    <label for="push_action_data" class="form-label">اطلاعات اضافه / لینک (اختیاری)</label>
                                    <input type="text" class="form-control" name="action_data" id="push_action_data" placeholder="مثلاً لینک یا شماره تیکت / شناسه بار">
                                </div>

                                <div class="col-12 mt-4 text-end">
                                    <button type="submit" class="btn btn-primary btn-wave px-4" id="btn_send_push">
                                        <i class="ri-send-plane-fill align-middle me-1"></i> ارسال نوتیفیکیشن
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- پیش‌نمایش در موبایل -->
            <div class="col-xl-4 col-lg-5">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">پیش‌نمایش روی گوشی</div>
                    </div>
                    <div class="card-body">
                        <p class="text-muted fs-12 mb-3">نمای ظاهری پیام در نوار اعلان گوشی (چه در حالت باز و چه بسته بودن اپلیکیشن):</p>
                        
                        <!-- قاب نوتیفیکیشن شبیه‌سازی اندروید -->
                        <div class="p-3 rounded-3 shadow-sm border" style="background: #ffffff; border-color: #e2e8f0;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="assets/images/logo.png" alt="AmutBar" style="width: 22px; height: 22px; border-radius: 4px;" onerror="this.src='data:image/svg+xml,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'22\' height=\'22\' viewBox=\'0 0 24 24\' fill=\'%2300bffe\'><path d=\'M19 13v6a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-6H3l9-9 9 9h-2z\'/></svg>'">
                                    <span class="fw-bold fs-12 text-dark">آموت‌بار</span>
                                    <span class="text-muted fs-11">• الآن</span>
                                </div>
                                <span class="badge bg-light text-muted fs-10">Push</span>
                            </div>
                            <div class="mt-2">
                                <h6 class="fw-bold fs-13 mb-1 text-dark" id="preview_title">عنوان اعلان اینجا قرار می‌گیرد</h6>
                                <p class="text-muted fs-12 mb-0" id="preview_body" style="line-height: 1.5;">متن پیام اعلان که روی گوشی کاربر در حالت باز یا بسته بودن نمایش داده می‌شود...</p>
                            </div>
                        </div>

                        <div class="alert alert-info mt-4 fs-12 mb-0" role="alert">
                            <i class="ri-information-line me-1"></i>
                            <strong>نحوه دریافت:</strong> این اعلان هم در صندوق پیام‌های اپلیکیشن ذخیره می‌شود و هم از طریق سرور FCM به گوشی کاربر ارسال می‌گردد تا در صورتی که برنامه کاملاً بسته باشد نیز نوتیفیکیشن را در نوار اعلان گوشی دریافت کند.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- جدول تاریخچه ارسال‌ها -->
        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">تاریخچه اعلان‌های ارسال‌شده</div>
                        <button type="button" class="btn btn-sm btn-light" id="btn_refresh_table">
                            <i class="ri-refresh-line align-middle"></i> بروزرسانی
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="pushLogsTable" class="table table-bordered table-hover w-100">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>عنوان</th>
                                        <th>متن</th>
                                        <th>مخاطبین هدف</th>
                                        <th>نوع عملیات</th>
                                        <th>تعداد کاربران</th>
                                        <th>وضعیت ارسال Push</th>
                                        <th>ارسال‌کننده</th>
                                        <th>تاریخ ارسال</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- مودال تنظیمات FCM / Firebase -->
<div class="modal fade" id="fcmSettingsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">تنظیمات کلید Firebase (FCM)</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="fcmSettingsForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="fcm_server_key" class="form-label fw-bold">کلید سرور Firebase (FCM Server Key) *</label>
                        <textarea class="form-control" name="server_key" id="fcm_server_key" rows="4" dir="ltr" placeholder="AAAA... (Firebase Console -> Project Settings -> Cloud Messaging -> Server key)"></textarea>
                        <small class="text-muted d-block mt-1">این کلید برای ارسال مستقیم درخواست‌های Push Notification به سرورهای گوگل استفاده می‌شود.</small>
                    </div>
                    <div class="mb-3">
                        <label for="fcm_channel_id" class="form-label fw-bold">شناسه کانال اعلان اندروید (Notification Channel ID)</label>
                        <input type="text" class="form-control" name="channel_id" id="fcm_channel_id" dir="ltr" value="amutbar_high_importance">
                    </div>
                    <div class="p-3 bg-light rounded text-muted fs-12">
                        <i class="ri-shield-check-line text-success me-1"></i>
                        تعداد دستگاه‌های فعال ثبت‌شده: <strong id="fcm_active_devices_count">0</strong>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
                    <button type="submit" class="btn btn-primary" id="btn_save_fcm">ذخیره تنظیمات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const BASE_URL = "<?= base_url(); ?>";

    function toast(msg, isSuccess = true) {
        if (window.Toastify) {
            Toastify({
                text: msg,
                duration: 3500,
                gravity: "top",
                position: "right",
                backgroundColor: isSuccess ? "#28a745" : "#dc3545"
            }).showToast();
        } else {
            alert(msg);
        }
    }

    $(function () {
        // بروزرسانی پیش‌نمایش در حین تایپ
        $('#push_title').on('input', function () {
            const val = $(this).val().trim();
            $('#preview_title').text(val !== '' ? val : 'عنوان اعلان اینجا قرار می‌گیرد');
        });
        $('#push_body').on('input', function () {
            const val = $(this).val().trim();
            $('#preview_body').text(val !== '' ? val : 'متن پیام اعلان که روی گوشی کاربر در حالت باز یا بسته بودن نمایش داده می‌شود...');
        });

        // تغییر مخاطبین
        $('input[name="audience"]').on('change', function () {
            if ($(this).val() === 'user') {
                $('#specific_user_wrap').removeClass('d-none');
            } else {
                $('#specific_user_wrap').addClass('d-none');
            }
        });

        // سلکت ۲ برای جستجوی کاربر
        $('#user_search').select2({
            ajax: {
                url: `${BASE_URL}/ajax/notifications.php?action=users_search`,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { q: params.term };
                },
                processResults: function (data) {
                    return { results: data.items || [] };
                },
                cache: true
            },
            placeholder: 'شماره تلفن یا نام کاربر را تایپ کنید...',
            minimumInputLength: 2,
            width: '100%',
            dir: 'rtl'
        });

        // جدول تاریخچه
        const table = $('#pushLogsTable').DataTable({
            ajax: `${BASE_URL}/ajax/notifications.php?action=list`,
            columns: [
                { data: 'id' },
                { data: 'title' },
                { data: 'body' },
                { data: 'audience' },
                { data: 'type' },
                { data: 'recipients_count' },
                { data: 'push_status' },
                { data: 'sender' },
                { data: 'date' }
            ],
            order: [[0, 'desc']],
            language: {
                search: "جستجو:",
                lengthMenu: "نمایش _MENU_ ردیف",
                info: "نمایش _START_ تا _END_ از _TOTAL_ ردیف",
                infoEmpty: "هیچ رکوردی ثبت نشده است",
                processing: "در حال بارگذاری...",
                zeroRecords: "موردی یافت نشد",
                paginate: { previous: "قبلی", next: "بعدی" }
            }
        });

        $('#btn_refresh_table').on('click', function () {
            table.ajax.reload();
        });

        // ارسال اعلان
        $('#pushForm').on('submit', function (e) {
            e.preventDefault();
            const submitBtn = $('#btn_send_push');
            submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> در حال ارسال...');

            const formData = $(this).serialize() + '&action=send';

            $.post(`${BASE_URL}/ajax/notifications.php`, formData, function (res) {
                submitBtn.prop('disabled', false).html('<i class="ri-send-plane-fill align-middle me-1"></i> ارسال نوتیفیکیشن');
                if (res && res.ok) {
                    if (window.Swal) {
                        Swal.fire({
                            title: 'با موفقیت ارسال شد!',
                            text: res.message,
                            icon: 'success',
                            confirmButtonText: 'تایید'
                        });
                    } else {
                        toast(res.message, true);
                    }
                    $('#push_title').val('').trigger('input');
                    $('#push_body').val('').trigger('input');
                    $('#push_action_data').val('');
                    table.ajax.reload(null, false);
                    return;
                }
                toast(res?.message || 'خطا در ارسال نوتیفیکیشن', false);
            }, 'json').fail(function (xhr) {
                submitBtn.prop('disabled', false).html('<i class="ri-send-plane-fill align-middle me-1"></i> ارسال نوتیفیکیشن');
                const msg = xhr.responseJSON?.message || 'خطای سرور در برقراری ارتباط';
                toast(msg, false);
            });
        });

        // لود تنظیمات FCM در مودال
        $('#fcmSettingsModal').on('show.bs.modal', function () {
            $.getJSON(`${BASE_URL}/ajax/notifications.php?action=get_settings`, function (res) {
                if (res && res.ok) {
                    $('#fcm_server_key').val(res.settings?.server_key || '');
                    $('#fcm_channel_id').val(res.settings?.channel_id || 'amutbar_high_importance');
                    $('#fcm_active_devices_count').text((res.stats?.active_devices || 0) + ' (' + (res.stats?.users_with_device || 0) + ' کاربر)');
                }
            });
        });

        // ذخیره تنظیمات FCM
        $('#fcmSettingsForm').on('submit', function (e) {
            e.preventDefault();
            const btn = $('#btn_save_fcm');
            btn.prop('disabled', true).text('در حال ذخیره...');
            const formData = $(this).serialize() + '&action=save_settings';

            $.post(`${BASE_URL}/ajax/notifications.php`, formData, function (res) {
                btn.prop('disabled', false).text('ذخیره تنظیمات');
                if (res && res.ok) {
                    toast(res.message, true);
                    $('#fcmSettingsModal').modal('hide');
                    return;
                }
                toast(res?.message || 'خطا در ذخیره تنظیمات', false);
            }, 'json').fail(function () {
                btn.prop('disabled', false).text('ذخیره تنظیمات');
                toast('خطا در ذخیره تنظیمات', false);
            });
        });
    });
</script>

<?php require_once "views/panel/footer.php"; ?>
