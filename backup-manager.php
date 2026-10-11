<?php
declare(strict_types=1);

require_once "views/panel/header.php";
require_once "views/panel/sidebar.php";
require_once "includes/backup_service.php";
require_once "includes/FilesIrClient.php";

$cloudApiKey = (string)env('FILES_IR_API_KEY', '');
$cloudEnabled = (string)env('BACKUP_CLOUD_ENABLED', '0') === '1';
$localKeepLast = (int)env('BACKUP_LOCAL_KEEP_LAST', 5);
$cloudKeepLast = (int)env('FILES_IR_KEEP_LAST', 2);
?>

<!-- MAIN-CONTENT -->
<div class="main-content app-content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-2">مدیریت پشتیبان‌گیری و بازیابی (Backup & Disaster Recovery)</h1>
                <div>
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">ابزارها و تنظیمات</a></li>
                            <li class="breadcrumb-item active" aria-current="page">پشتیبان‌گیری و ابری</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="btn-list d-flex gap-2">
                <button class="btn btn-outline-primary btn-wave" data-bs-toggle="modal" data-bs-target="#settingsModal">
                    <i class="ri-settings-4-line align-middle"></i> تنظیمات ابری و زمان‌بندی
                </button>
                <button class="btn btn-success btn-wave" id="btnCreateBackup" onclick="openCreateModal()">
                    <i class="ri-hard-drive-2-line align-middle"></i> ایجاد بک‌آپ جدید
                </button>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Alert Banner -->
        <div id="statusAlert" class="alert d-none" role="alert"></div>

        <!-- Metric Cards -->
        <div class="row">
            <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="d-block mb-1 text-muted fs-13">نسخه‌های محلی سرور</span>
                                <h4 class="fw-semibold mb-0" id="statLocalCount">0</h4>
                                <span class="fs-12 text-muted mt-1 d-block" id="statLocalSize">حجم: ۰ مگابایت</span>
                            </div>
                            <div class="avatar bg-primary-transparent text-primary p-2 fs-20">
                                <i class="ri-database-2-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="d-block mb-1 text-muted fs-13">وضعیت فضای ابری (Files.ir)</span>
                                <h4 class="fw-semibold mb-0" id="statCloudStatus">
                                    <?= $cloudEnabled && !empty($cloudApiKey) ? '<span class="text-success">فعال</span>' : '<span class="text-muted">غیرفعال</span>' ?>
                                </h4>
                                <span class="fs-12 text-muted mt-1 d-block" id="statCloudFiles">نسخه‌های ابری: ۰ عدد</span>
                            </div>
                            <div class="avatar bg-info-transparent text-info p-2 fs-20">
                                <i class="ri-cloud-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="d-block mb-1 text-muted fs-13">آخرین نسخه پشتیبان</span>
                                <h4 class="fw-semibold mb-0 fs-15 text-truncate" id="statLastBackup">در حال دریافت...</h4>
                                <span class="fs-12 text-muted mt-1 d-block">شامل دیتابیس و فایل‌های بارگذاری‌شده</span>
                            </div>
                            <div class="avatar bg-success-transparent text-success p-2 fs-20">
                                <i class="ri-shield-check-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="d-block mb-1 text-muted fs-13">سیاست چرخش و نگهداری</span>
                                <h4 class="fw-semibold mb-0 fs-15 text-primary"><?= $cloudKeepLast ?> نسخه ابری / <?= $localKeepLast ?> نسخه محلی</h4>
                                <span class="fs-12 text-muted mt-1 d-block">حذف خودکار نسخه‌های قدیمی‌تر</span>
                            </div>
                            <div class="avatar bg-warning-transparent text-warning p-2 fs-20">
                                <i class="ri-recycle-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Backups List Table -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            <i class="ri-file-zip-line text-primary align-middle me-1"></i> لیست فایل‌های پشتیبان سرور (Local Backups)
                        </div>
                        <button class="btn btn-sm btn-outline-light text-dark" onclick="loadBackups()">
                            <i class="ri-refresh-line"></i> بروزرسانی
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-hover" id="backupsTable">
                                <thead>
                                    <tr>
                                        <th>ردیف</th>
                                        <th>نام فایل آرشیو</th>
                                        <th>تاریخ و زمان ایجاد</th>
                                        <th>حجم فایل</th>
                                        <th>محتویات</th>
                                        <th class="text-center">عملیات</th>
                                    </tr>
                                </thead>
                                <tbody id="backupsTbody">
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">در حال بارگذاری لیست فایل‌ها...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cloud Backups List (Files.ir) -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            <i class="ri-cloud-fill text-info align-middle me-1"></i> نسخه‌های ذخیره‌شده در فضای ابری Files.ir
                        </div>
                        <span class="badge bg-info-transparent">حداکثر ۲ نسخه اخیر نگهداری می‌شود</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-hover">
                                <thead>
                                    <tr>
                                        <th>شناسه در Files.ir</th>
                                        <th>نام فایل</th>
                                        <th>تاریخ ثبت در ابری</th>
                                        <th>حجم</th>
                                        <th>وضعیت همگام‌سازی</th>
                                    </tr>
                                </thead>
                                <tbody id="cloudBackupsTbody">
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">نسخه‌ای در فضای ابری یافت نشد.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- MAIN-CONTENT CLOSE -->

<!-- Create Backup Modal -->
<div class="modal fade" id="createBackupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">ایجاد نسخه پشتیبان کامل (Full System Backup)</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted fs-13 mb-3">
                    این فرآیند از تمام پایگاه‌داده (تمامی جداول، مایگریشن‌ها، سوابق بارها و رانندگان) به همراه مستندات بارگذاری‌شده (تصاویر مدارک هویتی و گواهینامه‌ها) یک آرشیو جامع <code>.zip</code> ایجاد می‌کند.
                </p>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="chkSyncCloud" <?= $cloudEnabled && !empty($cloudApiKey) ? 'checked' : '' ?>>
                    <label class="form-check-label fs-13" for="chkSyncCloud">
                        ارسال همزمان به فضای ابری Files.ir (نگهداری ۲ نسخه اخیر)
                    </label>
                </div>

                <div id="backupProgressBox" class="d-none">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        <span class="fs-13 text-primary fw-medium" id="backupStatusText">در حال خروجی‌گیری از پایگاه داده و فایل‌ها...</span>
                    </div>
                    <div class="progress progress-xs">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" style="width: 100%"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" id="btnCancelBackup">انصراف</button>
                <button type="button" class="btn btn-primary" id="btnStartBackup" onclick="startBackupProcess()">
                    <i class="ri-check-line align-middle"></i> شروع پشتیبان‌گیری
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Restore Confirmation Modal -->
<div class="modal fade" id="restoreModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger-transparent">
                <h6 class="modal-title text-danger">
                    <i class="ri-alert-fill align-middle me-1"></i> تایید بازیابی اضطراری (Disaster Recovery Restore)
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning fs-13 mb-3">
                    <strong>هشدار بسیار مهم:</strong> با انجام عملیات بازیابی، کلیه اطلاعات فعلی پایگاه داده با نسخه موجود در فایل بک‌آپ جایگزین خواهند شد.
                </div>
                <p class="fs-13 text-muted">آیا از بازیابی فایل زیر اطمینان کامل دارید؟</p>
                <div class="p-2 bg-light rounded text-center fw-bold fs-14 text-dark font-monospace" id="restoreTargetFilename">---</div>

                <div id="restoreProgressBox" class="d-none mt-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="spinner-border spinner-border-sm text-danger" role="status"></div>
                        <span class="fs-13 text-danger fw-medium" id="restoreStatusText">در حال ایمپورت پایگاه داده و جایگزینی فایل‌ها...</span>
                    </div>
                    <div class="progress progress-xs">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" style="width: 100%"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" id="btnCancelRestore">لغو</button>
                <button type="button" class="btn btn-danger" id="btnConfirmRestore" onclick="executeRestore()">
                    <i class="ri-refresh-line align-middle"></i> تایید و بازیابی اطلاعات
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Settings Modal -->
<div class="modal fade" id="settingsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">تنظیمات پشتیبان‌گیری ابری و زمان‌بندی خودکار</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs nav-tabs-header mb-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" role="tab" href="#tabCloudSettings">تنظیمات فضای ابری (Files.ir)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" role="tab" href="#tabCronSettings">زمان‌بندی خودکار سرور (Cron Job)</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Cloud Tab -->
                    <div class="tab-pane active" id="tabCloudSettings" role="tabpanel">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="cfgCloudEnabled" <?= $cloudEnabled ? 'checked' : '' ?>>
                            <label class="form-check-label fw-medium fs-13" for="cfgCloudEnabled">فعال‌سازی ارسال خودکار به فضای ابری Files.ir</label>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-13">کلید دسترسی API (Files.ir API Key):</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="cfgApiKey" value="<?= htmlspecialchars($cloudApiKey) ?>" placeholder="توکن API دریافتی از پنل Files.ir">
                                <button class="btn btn-outline-secondary" type="button" onclick="testCloudConnection()">
                                    <i class="ri-wifi-line"></i> تست اتصال
                                </button>
                            </div>
                            <span class="fs-12 text-muted mt-1 d-block">
                                برای دریافت کلید، به پنل کاربری خود در <a href="https://my.files.ir/account-settings/#developers" target="_blank" class="text-primary">my.files.ir/account-settings</a> مراجعه نمایید.
                            </span>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fs-13">تعداد نسخه‌های ابری نگهداری‌شده:</label>
                                <input type="number" class="form-control" id="cfgCloudKeep" value="<?= $cloudKeepLast ?>" min="1" max="10">
                                <span class="fs-12 text-muted">همیشه ۲ نسخه اخیر روی ابری ذخیره شده و نسخه‌های قدیمی‌تر خودکار حذف می‌شوند.</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fs-13">تعداد نسخه‌های محلی روی سرور:</label>
                                <input type="number" class="form-control" id="cfgLocalKeep" value="<?= $localKeepLast ?>" min="1" max="30">
                                <span class="fs-12 text-muted">تعداد نسخه‌های پشتیبان نگهداری‌شده روی هارد سرور.</span>
                            </div>
                        </div>

                        <div id="testConnAlert" class="alert d-none mt-2"></div>
                    </div>

                    <!-- Cron Tab -->
                    <div class="tab-pane" id="tabCronSettings" role="tabpanel">
                        <p class="fs-13 text-muted mb-3">
                            برای پشتیبان‌گیری تمام‌خودکار، دستورات زیر را از طریق دستور <code>crontab -e</code> در ترمینال سرور لینوکس خود قرار دهید:
                        </p>

                        <div class="mb-3">
                            <label class="fw-medium fs-13 text-dark">۱. پشتیبان‌گیری روزانه (هر شب ساعت ۳:۰۰ بامداد - پیشنهادی):</label>
                            <div class="bg-light p-2 rounded font-monospace fs-12 text-dark user-select-all">
                                0 3 * * * /usr/bin/php /var/www/amutbar-admin/scripts/backup_runner.php >> /var/www/amutbar-admin/storage/logs/backup.log 2>&1
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="fw-medium fs-13 text-dark">۲. پشتیبان‌گیری هر ۱۲ ساعت یک‌بار (ساعت ۳ بامداد و ۳ عصر):</label>
                            <div class="bg-light p-2 rounded font-monospace fs-12 text-dark user-select-all">
                                0 3,15 * * * /usr/bin/php /var/www/amutbar-admin/scripts/backup_runner.php >> /var/www/amutbar-admin/storage/logs/backup.log 2>&1
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="fw-medium fs-13 text-dark">۳. پشتیبان‌گیری هفتگی (جمعه‌ها ساعت ۲ بامداد):</label>
                            <div class="bg-light p-2 rounded font-monospace fs-12 text-dark user-select-all">
                                0 2 * * 5 /usr/bin/php /var/www/amutbar-admin/scripts/backup_runner.php >> /var/www/amutbar-admin/storage/logs/backup.log 2>&1
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
                <button type="button" class="btn btn-primary" onclick="saveSettings()">
                    <i class="ri-save-line align-middle"></i> ذخیره تنظیمات
                </button>
            </div>
        </div>
    </div>
</div>

<?php require_once "views/panel/footer.php"; ?>

<script>
let currentRestoreFile = '';

document.addEventListener('DOMContentLoaded', function () {
    loadBackups();
});

function showAlert(message, type = 'success') {
    const el = document.getElementById('statusAlert');
    el.className = `alert alert-${type} mb-4`;
    el.innerHTML = message;
    el.classList.remove('d-none');
    setTimeout(() => {
        el.classList.add('d-none');
    }, 8000);
}

function loadBackups() {
    fetch('ajax/backup_action.php?action=list')
        .then(res => res.json())
        .then(data => {
            if (!data.ok) return;

            // Render Metrics
            const locals = data.local_backups || [];
            document.getElementById('statLocalCount').innerText = locals.length;

            let totalBytes = 0;
            locals.forEach(b => totalBytes += (b.size_bytes || 0));
            document.getElementById('statLocalSize').innerText = 'مجموع حجم: ' + formatBytes(totalBytes);

            if (locals.length > 0) {
                document.getElementById('statLastBackup').innerText = locals[0].created_at;
            } else {
                document.getElementById('statLastBackup').innerText = 'هیچ نسخه‌ای ایجاد نشده';
            }

            // Cloud Status
            const clouds = data.cloud_backups || [];
            document.getElementById('statCloudFiles').innerText = 'نسخه‌های ابری: ' + clouds.length + ' عدد';

            // Render Local Table
            const tbody = document.getElementById('backupsTbody');
            if (locals.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">هنوز هیچ نسخه پشتیبانی ایجاد نشده است. برای ایجاد روی دکمه «ایجاد بک‌آپ جدید» کلیک کنید.</td></tr>';
            } else {
                let html = '';
                locals.forEach((b, idx) => {
                    html += `
                        <tr>
                            <td>${idx + 1}</td>
                            <td><strong class="font-monospace text-primary">${escapeHtml(b.filename)}</strong></td>
                            <td><i class="ri-time-line text-muted me-1"></i>${escapeHtml(b.created_at)}</td>
                            <td><span class="badge bg-light text-dark fs-12">${escapeHtml(b.size_formatted)}</span></td>
                            <td>
                                <span class="badge bg-success-transparent me-1"><i class="ri-database-2-line"></i> دیتابیس کامل</span>
                                <span class="badge bg-info-transparent"><i class="ri-folder-zip-line"></i> پوشه آپلودها</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="ajax/backup_action.php?action=download&filename=${encodeURIComponent(b.filename)}" class="btn btn-outline-primary" title="دانلود فایل">
                                        <i class="ri-download-2-line"></i> دانلود
                                    </a>
                                    <button class="btn btn-outline-warning" onclick="openRestoreModal('${escapeHtml(b.filename)}')" title="بازیابی اطلاعات">
                                        <i class="ri-history-line"></i> بازیابی
                                    </button>
                                    <button class="btn btn-outline-danger" onclick="deleteBackup('${escapeHtml(b.filename)}')" title="حذف">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                tbody.innerHTML = html;
            }

            // Render Cloud Table
            const cloudTbody = document.getElementById('cloudBackupsTbody');
            if (clouds.length === 0) {
                cloudTbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-3">نسخه‌ای در فضای ابری Files.ir یافت نشد.</td></tr>';
            } else {
                let cHtml = '';
                clouds.forEach(cb => {
                    cHtml += `
                        <tr>
                            <td><code>#${escapeHtml(cb.id || '-')}</code></td>
                            <td><strong class="text-info">${escapeHtml(cb.name || cb.file_name)}</strong></td>
                            <td>${escapeHtml(cb.created_at || '-')}</td>
                            <td>${formatBytes(cb.file_size || 0)}</td>
                            <td><span class="badge bg-success-transparent"><i class="ri-check-line"></i> ذخیره‌شده و محافظت‌شده</span></td>
                        </tr>
                    `;
                });
                cloudTbody.innerHTML = cHtml;
            }
        })
        .catch(err => console.error(err));
}

function openCreateModal() {
    document.getElementById('backupProgressBox').classList.add('d-none');
    document.getElementById('btnStartBackup').disabled = false;
    document.getElementById('btnCancelBackup').disabled = false;
    const modal = new bootstrap.Modal(document.getElementById('createBackupModal'));
    modal.show();
}

function startBackupProcess() {
    const btn = document.getElementById('btnStartBackup');
    const cancelBtn = document.getElementById('btnCancelBackup');
    const progressBox = document.getElementById('backupProgressBox');
    const syncCloud = document.getElementById('chkSyncCloud').checked;

    btn.disabled = true;
    cancelBtn.disabled = true;
    progressBox.classList.remove('d-none');

    const formData = new FormData();
    formData.append('action', 'create');
    if (syncCloud) {
        formData.append('sync_cloud', '1');
    }

    fetch('ajax/backup_action.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        bootstrap.Modal.getInstance(document.getElementById('createBackupModal')).hide();
        if (data.ok) {
            let msg = `<strong>پشتیبان‌گیری با موفقیت انجام شد:</strong> فایل <code>${data.filename}</code> با حجم ${data.size_formatted} ایجاد شد.`;
            if (data.cloud && data.cloud.synced) {
                msg += ` <br><span class="text-success"><i class="ri-cloud-check-line"></i> با فضای ابری Files.ir همگام‌سازی شد (حفظ ۲ نسخه اخیر).</span>`;
            }
            showAlert(msg, 'success');
            loadBackups();
        } else {
            showAlert('خطا در پشتیبان‌گیری: ' + (data.message || 'خطای سرور'), 'danger');
        }
    })
    .catch(err => {
        bootstrap.Modal.getInstance(document.getElementById('createBackupModal')).hide();
        showAlert('خطای ارتباط با سرور: ' + err.message, 'danger');
    });
}

function openRestoreModal(filename) {
    currentRestoreFile = filename;
    document.getElementById('restoreTargetFilename').innerText = filename;
    document.getElementById('restoreProgressBox').classList.add('d-none');
    document.getElementById('btnConfirmRestore').disabled = false;
    document.getElementById('btnCancelRestore').disabled = false;
    const modal = new bootstrap.Modal(document.getElementById('restoreModal'));
    modal.show();
}

function executeRestore() {
    if (!currentRestoreFile) return;

    const btn = document.getElementById('btnConfirmRestore');
    const cancelBtn = document.getElementById('btnCancelRestore');
    const progressBox = document.getElementById('restoreProgressBox');

    btn.disabled = true;
    cancelBtn.disabled = true;
    progressBox.classList.remove('d-none');

    const formData = new FormData();
    formData.append('action', 'restore');
    formData.append('filename', currentRestoreFile);

    fetch('ajax/backup_action.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        bootstrap.Modal.getInstance(document.getElementById('restoreModal')).hide();
        if (data.ok) {
            showAlert(`<strong>بازیابی با موفقیت کامل انجام شد!</strong> ${data.message} (${data.restored_files_count} فایل مستندات بازیابی شدند).`, 'success');
        } else {
            showAlert('خطا در بازیابی: ' + (data.message || 'خطای نامشخص'), 'danger');
        }
    })
    .catch(err => {
        bootstrap.Modal.getInstance(document.getElementById('restoreModal')).hide();
        showAlert('خطای ارتباط با سرور: ' + err.message, 'danger');
    });
}

function deleteBackup(filename) {
    if (!confirm(`آیا از حذف قطعی فایل ${filename} از سرور اطمینان دارید؟`)) {
        return;
    }

    const formData = new FormData();
    formData.append('action', 'delete');
    formData.append('filename', filename);

    fetch('ajax/backup_action.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.ok) {
            showAlert('فایل پشتیبان با موفقیت حذف شد.', 'success');
            loadBackups();
        } else {
            showAlert(data.message || 'خطا در حذف فایل', 'danger');
        }
    });
}

function testCloudConnection() {
    const apiKey = document.getElementById('cfgApiKey').value.trim();
    const alertBox = document.getElementById('testConnAlert');

    if (!apiKey) {
        alertBox.className = 'alert alert-warning mt-2';
        alertBox.innerText = 'لطفاً کلید API را وارد کنید.';
        alertBox.classList.remove('d-none');
        return;
    }

    alertBox.className = 'alert alert-info mt-2';
    alertBox.innerText = 'در حال برقراری ارتباط با Files.ir...';
    alertBox.classList.remove('d-none');

    const formData = new FormData();
    formData.append('action', 'test_cloud');
    formData.append('api_key', apiKey);

    fetch('ajax/backup_action.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.ok) {
            alertBox.className = 'alert alert-success mt-2';
            alertBox.innerHTML = `<i class="ri-checkbox-circle-line me-1"></i> ${data.message}`;
        } else {
            alertBox.className = 'alert alert-danger mt-2';
            alertBox.innerHTML = `<i class="ri-error-warning-line me-1"></i> ${data.message}`;
        }
    })
    .catch(err => {
        alertBox.className = 'alert alert-danger mt-2';
        alertBox.innerText = 'خطای ارتباط: ' + err.message;
    });
}

function saveSettings() {
    const cloudEnabled = document.getElementById('cfgCloudEnabled').checked ? '1' : '0';
    const apiKey = document.getElementById('cfgApiKey').value.trim();
    const localKeep = document.getElementById('cfgLocalKeep').value;
    const cloudKeep = document.getElementById('cfgCloudKeep').value;

    const formData = new FormData();
    formData.append('action', 'save_settings');
    formData.append('cloud_enabled', cloudEnabled);
    formData.append('api_key', apiKey);
    formData.append('local_keep', localKeep);
    formData.append('cloud_keep', cloudKeep);

    fetch('ajax/backup_action.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        bootstrap.Modal.getInstance(document.getElementById('settingsModal')).hide();
        if (data.ok) {
            showAlert(data.message, 'success');
            loadBackups();
        } else {
            showAlert('خطا در ذخیره تنظیمات: ' + (data.message || 'نامشخص'), 'danger');
        }
    });
}

function formatBytes(bytes) {
    if (bytes >= 1073741824) return (bytes / 1073741824).toFixed(2) + ' GB';
    if (bytes >= 1048576) return (bytes / 1048576).toFixed(2) + ' MB';
    if (bytes >= 1024) return (bytes / 1024).toFixed(2) + ' KB';
    return bytes + ' B';
}

function escapeHtml(text) {
    if (!text) return '';
    return String(text).replace(/[&<>"']/g, function (m) {
        return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' })[m];
    });
}
</script>
