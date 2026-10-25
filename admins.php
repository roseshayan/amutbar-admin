<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/init.php';
require_super_admin();

$page_title = 'مدیریت کارمندان و سطوح دسترسی';
require_once __DIR__ . '/views/panel/header.php';
require_once __DIR__ . '/views/panel/sidebar.php';

$pdo = db();
$totalSuperAdmins = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE user_type = 3 AND admin_role = 'super_admin' AND deleted_at IS NULL")->fetchColumn();
$totalActiveStaff = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE user_type = 3 AND admin_role = 'staff' AND status = 1 AND deleted_at IS NULL")->fetchColumn();
$totalPermsCount = count(permissions_all_keys());
?>

<div class="main-content app-content">
    <div class="container-fluid">

        <!-- هدر صفحه -->
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-2">مدیریت کارمندان و سطوح دسترسی (RBAC)</h1>
                <div>
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="dashboard.php">داشبورد</a></li>
                            <li class="breadcrumb-item active" aria-current="page">مدیریت کارمندان</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="btn-list">
                <button type="button" class="btn btn-primary btn-wave" onclick="openAddModal()">
                    <i class="ri-user-add-line align-middle me-1"></i> تعریف کارمند جدید
                </button>
            </div>
        </div>

        <!-- کارت‌های آماری -->
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="avatar avatar-md bg-primary-transparent text-primary avatar-rounded">
                                    <i class="ri-shield-star-line fs-18"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <span class="d-block text-muted fs-12 mb-1">مدیران کل سامانه</span>
                                <span class="fw-semibold fs-20"><?= number_format($totalSuperAdmins) ?> نفر</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="avatar avatar-md bg-success-transparent text-success avatar-rounded">
                                    <i class="ri-team-line fs-18"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <span class="d-block text-muted fs-12 mb-1">کارمندان فعال با دسترسی مجاز</span>
                                <span class="fw-semibold fs-20"><?= number_format($totalActiveStaff) ?> نفر</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="avatar avatar-md bg-info-transparent text-info avatar-rounded">
                                    <i class="ri-key-2-line fs-18"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <span class="d-block text-muted fs-12 mb-1">کل مجوزهای تفکیک‌شده</span>
                                <span class="fw-semibold fs-20"><?= $totalPermsCount ?> دسترسی در ۱۲ بخش</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- جدول لیست مدیران و کارمندان -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            لیست حساب‌های مدیریتی و کارمندان پنل
                        </div>
                        <button class="btn btn-sm btn-outline-secondary" onclick="loadStaffList()">
                            <i class="ri-refresh-line align-middle"></i> بروزرسانی
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="staffTable" class="table table-bordered text-nowrap w-100 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">شناسه</th>
                                        <th>نام و نام خانوادگی</th>
                                        <th>شماره موبایل</th>
                                        <th>سطح دسترسی</th>
                                        <th>وضعیت حساب</th>
                                        <th>دسترسی‌های فعال</th>
                                        <th>تاریخ ثبت</th>
                                        <th style="width: 280px;" class="text-center">عملیات مدیریت</th>
                                    </tr>
                                </thead>
                                <tbody id="staffTableBody">
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            <span class="spinner-border spinner-border-sm me-2"></span> در حال بارگذاری لیست کارمندان...
                                        </td>
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

<!-- مودال تعریف کارمند جدید -->
<div class="modal fade" id="addStaffModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-semibold">
                    <i class="ri-user-add-line text-primary me-1"></i> تعریف کارمند جدید
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
            </div>
            <form id="addStaffForm" onsubmit="submitAddStaff(event)">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="add_full_name" class="form-label">نام و نام خانوادگی <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="add_full_name" name="full_name" required placeholder="مثال: علی رضایی">
                    </div>
                    <div class="mb-3">
                        <label for="add_phone" class="form-label">شماره موبایل <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="add_phone" name="phone" required maxlength="11" placeholder="0912*******" style="direction:ltr">
                        <small class="text-muted">این شماره به عنوان نام کاربری ورود به پنل استفاده می‌شود.</small>
                    </div>
                    <div class="mb-3">
                        <label for="add_password" class="form-label">رمز عبور ورود <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="add_password" name="password" required minlength="8" placeholder="حداقل ۸ کاراکتر">
                    </div>
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" role="switch" id="add_grant_default" name="grant_default" checked value="1">
                        <label class="form-check-label fw-normal fs-13" for="add_grant_default">
                            اعطای دسترسی‌های استاندارد روزمره به صورت پیش‌فرض (قابل ویرایش بعدی)
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">انصراف</button>
                    <button type="submit" class="btn btn-primary" id="addStaffSubmitBtn">
                        <i class="ri-check-line align-middle me-1"></i> ثبت و ایجاد کارمند
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- مودال ویرایش کارمند -->
<div class="modal fade" id="editStaffModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-semibold">
                    <i class="ri-edit-line text-primary me-1"></i> ویرایش مشخصات کارمند
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
            </div>
            <form id="editStaffForm" onsubmit="submitEditStaff(event)">
                <input type="hidden" id="edit_staff_id" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_full_name" class="form-label">نام و نام خانوادگی <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_full_name" name="full_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_phone" class="form-label">شماره موبایل <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="edit_phone" name="phone" required maxlength="11" style="direction:ltr">
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">رمز عبور جدید (اختیاری)</label>
                        <input type="password" class="form-control" id="edit_password" name="password" minlength="8" placeholder="تنها در صورت نیاز به تغییر رمز وارد کنید">
                        <small class="text-muted">در صورت خالی ماندن، رمز عبور قبلی بدون تغییر حفظ می‌شود.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">انصراف</button>
                    <button type="submit" class="btn btn-primary" id="editStaffSubmitBtn">
                        <i class="ri-save-line align-middle me-1"></i> ذخیره تغییرات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- مودال جامع ماتریس دسترسی‌ها (Permissions Matrix) -->
<div class="modal fade" id="permissionsModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <div>
                    <h6 class="modal-title fw-bold text-primary mb-1">
                        <i class="ri-shield-keyhole-line align-middle me-1"></i> تنظیم سطوح دسترسی کارمند: <span id="permModalStaffName" class="text-dark"></span>
                    </h6>
                    <small class="text-muted" id="permModalStaffPhone"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
            </div>
            <div class="modal-body p-4">
                
                <!-- نوار ابزار انتخاب سریع -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4 p-3 bg-light rounded border">
                    <div>
                        <span class="fw-semibold text-muted fs-13">تعداد دسترسی‌های انتخاب شده: </span>
                        <span class="badge bg-primary fs-14" id="selectedPermsCounter">0</span>
                        <span class="text-muted fs-12"> از <?= $totalPermsCount ?> دسترسی</span>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="toggleAllPerms(true)">
                            <i class="ri-checkbox-line align-middle me-1"></i> انتخاب تمام دسترسی‌های سیستم
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="toggleAllPerms(false)">
                            <i class="ri-close-circle-line align-middle me-1"></i> لغو انتخاب همه
                        </button>
                    </div>
                </div>

                <!-- کانتینر دسته‌بندی‌های مجوزها -->
                <div id="permissionsGrid" class="row g-3">
                    <div class="col-12 text-center py-5">
                        <span class="spinner-border spinner-border-sm text-primary"></span> در حال دریافت کاتالوگ دسترسی‌ها...
                    </div>
                </div>

            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">انصراف</button>
                <button type="button" class="btn btn-success px-4" id="savePermsBtn" onclick="savePermissions()">
                    <i class="ri-check-double-line align-middle me-1"></i> ذخیره نهایی دسترسی‌ها
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let staffList = [];
let activeStaffId = null;
let currentCatalog = null;

document.addEventListener('DOMContentLoaded', () => {
    loadStaffList();
});

// بارگذاری لیست کارمندان و ادمین‌ها
async function loadStaffList() {
    const tbody = document.getElementById('staffTableBody');
    tbody.innerHTML = `<tr><td colspan="8" class="text-center py-4 text-muted"><span class="spinner-border spinner-border-sm me-2"></span> در حال دریافت اطلاعات...</td></tr>`;

    try {
        const res = await fetch('ajax/admin/staff_list.php', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const json = await res.json();

        if (!json.ok) {
            tbody.innerHTML = `<tr><td colspan="8" class="text-center text-danger py-4">${json.message || 'خطا در دریافت لیست'}</td></tr>`;
            return;
        }

        staffList = json.data || [];
        renderStaffTable();
    } catch (e) {
        console.error(e);
        tbody.innerHTML = `<tr><td colspan="8" class="text-center text-danger py-4">خطا در برقراری ارتباط با سرور</td></tr>`;
    }
}

// رسم جدول کارمندان
function renderStaffTable() {
    const tbody = document.getElementById('staffTableBody');
    if (staffList.length === 0) {
        tbody.innerHTML = `<tr><td colspan="8" class="text-center text-muted py-4">هیچ کاربری یافت نشد</td></tr>`;
        return;
    }

    let html = '';
    staffList.forEach(u => {
        const isSuper = u.is_super;
        const roleBadge = isSuper 
            ? `<span class="badge bg-purple-transparent fs-12 px-2 py-1"><i class="ri-shield-star-line me-1"></i>مدیر کل سیستم</span>`
            : `<span class="badge bg-primary-transparent fs-12 px-2 py-1"><i class="ri-user-settings-line me-1"></i>کارمند</span>`;

        const statusBadge = (u.status === 1)
            ? `<span class="badge bg-success-transparent fs-12">فعال</span>`
            : `<span class="badge bg-danger-transparent fs-12">غیرفعال / مسدود</span>`;

        const permsBadge = isSuper
            ? `<span class="badge bg-warning-transparent fs-12 text-warning fw-semibold"><i class="ri-lock-unlock-line me-1"></i>دسترسی کامل (۳۳ از ۳۳)</span>`
            : `<span class="badge bg-info-transparent fs-12 fw-semibold"><i class="ri-key-line me-1"></i>${u.perms_count} از ${u.perms_total} دسترسی</span>`;

        let actionBtns = '';
        if (isSuper) {
            actionBtns = `<span class="text-muted fs-12"><i class="ri-shield-check-line text-success me-1"></i>حساب ارشد غیرقابل تغییر</span>`;
        } else {
            const toggleStatusBtn = (u.status === 1)
                ? `<button class="btn btn-sm btn-outline-warning" onclick="toggleStaffStatus(${u.id}, 2)" title="غیرفعال‌سازی"><i class="ri-pause-circle-line"></i></button>`
                : `<button class="btn btn-sm btn-outline-success" onclick="toggleStaffStatus(${u.id}, 1)" title="فعال‌سازی"><i class="ri-play-circle-line"></i></button>`;

            actionBtns = `
                <button class="btn btn-sm btn-primary" onclick="openPermissionsModal(${u.id})" title="تنظیم ماتریس دسترسی‌ها">
                    <i class="ri-shield-keyhole-line me-1"></i>دسترسی‌ها
                </button>
                <button class="btn btn-sm btn-outline-info ms-1" onclick="openEditModal(${u.id}, '${escapeHtml(u.full_name)}', '${escapeHtml(u.phone)}')" title="ویرایش مشخصات">
                    <i class="ri-edit-line"></i>
                </button>
                ${toggleStatusBtn}
                <button class="btn btn-sm btn-outline-danger ms-1" onclick="deleteStaff(${u.id}, '${escapeHtml(u.full_name)}')" title="حذف کارمند">
                    <i class="ri-delete-bin-line"></i>
                </button>
            `;
        }

        html += `
            <tr>
                <td class="fw-semibold">#${u.id}</td>
                <td class="fw-medium">${u.full_name}</td>
                <td style="direction:ltr" class="text-end font-monospace">${u.phone}</td>
                <td>${roleBadge}</td>
                <td>${statusBadge}</td>
                <td>${permsBadge}</td>
                <td class="text-muted fs-12">${u.created_at ? new Date(u.created_at).toLocaleDateString('fa-IR') : '-'}</td>
                <td class="text-center">${actionBtns}</td>
            </tr>
        `;
    });

    tbody.innerHTML = html;
}

// باز کردن مودال تعریف کارمند
function openAddModal() {
    document.getElementById('addStaffForm').reset();
    const modal = new bootstrap.Modal(document.getElementById('addStaffModal'));
    modal.show();
}

// ثبت کارمند جدید
async function submitAddStaff(e) {
    e.preventDefault();
    const btn = document.getElementById('addStaffSubmitBtn');
    btn.disabled = true;
    btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1"></span> در حال ثبت...`;

    const form = document.getElementById('addStaffForm');
    const fd = new FormData(form);

    try {
        const res = await fetch('ajax/admin/staff_save.php', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: fd
        });
        const json = await res.json();

        if (!json.ok) {
            Swal.fire({ icon: 'error', title: 'خطا', text: json.message || 'ثبت با خطا مواجه شد' });
            return;
        }

        bootstrap.Modal.getInstance(document.getElementById('addStaffModal')).hide();
        Swal.fire({ icon: 'success', title: 'موفق', text: json.message, timer: 2000, showConfirmButton: false });
        loadStaffList();
    } catch (err) {
        console.error(err);
        Swal.fire({ icon: 'error', title: 'خطا', text: 'خطا در ارتباط با سرور' });
    } finally {
        btn.disabled = false;
        btn.innerHTML = `<i class="ri-check-line align-middle me-1"></i> ثبت و ایجاد کارمند`;
    }
}

// باز کردن مودال ویرایش
function openEditModal(id, name, phone) {
    document.getElementById('edit_staff_id').value = id;
    document.getElementById('edit_full_name').value = name;
    document.getElementById('edit_phone').value = phone;
    document.getElementById('edit_password').value = '';

    const modal = new bootstrap.Modal(document.getElementById('editStaffModal'));
    modal.show();
}

// ثبت ویرایش
async function submitEditStaff(e) {
    e.preventDefault();
    const btn = document.getElementById('editStaffSubmitBtn');
    btn.disabled = true;
    btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1"></span> در حال ذخیره...`;

    const form = document.getElementById('editStaffForm');
    const fd = new FormData(form);

    try {
        const res = await fetch('ajax/admin/staff_save.php', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: fd
        });
        const json = await res.json();

        if (!json.ok) {
            Swal.fire({ icon: 'error', title: 'خطا', text: json.message || 'خطا در ویرایش' });
            return;
        }

        bootstrap.Modal.getInstance(document.getElementById('editStaffModal')).hide();
        Swal.fire({ icon: 'success', title: 'موفق', text: json.message, timer: 2000, showConfirmButton: false });
        loadStaffList();
    } catch (err) {
        console.error(err);
        Swal.fire({ icon: 'error', title: 'خطا', text: 'خطا در ارتباط با سرور' });
    } finally {
        btn.disabled = false;
        btn.innerHTML = `<i class="ri-save-line align-middle me-1"></i> ذخیره تغییرات`;
    }
}

// تغییر وضعیت فعال/غیرفعال کارمند
async function toggleStaffStatus(id, newStatus) {
    const actionText = (newStatus === 1) ? 'فعال‌سازی' : 'غیرفعال‌سازی و مسدود کردن';
    const confirm = await Swal.fire({
        title: `آیا از ${actionText} این کارمند اطمینان دارید؟`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'بله، تغییر وضعیت بده',
        cancelButtonText: 'انصراف'
    });

    if (!confirm.isConfirmed) return;

    try {
        const fd = new FormData();
        fd.append('id', id);
        fd.append('status', newStatus);

        const res = await fetch('ajax/admin/staff_status.php', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: fd
        });
        const json = await res.json();

        if (!json.ok) {
            Swal.fire({ icon: 'error', title: 'خطا', text: json.message });
            return;
        }

        Swal.fire({ icon: 'success', title: 'موفق', text: json.message, timer: 1500, showConfirmButton: false });
        loadStaffList();
    } catch (e) {
        console.error(e);
        Swal.fire({ icon: 'error', title: 'خطا', text: 'ارتباط با سرور برقرار نشد' });
    }
}

// حذف کارمند
async function deleteStaff(id, name) {
    const confirm = await Swal.fire({
        title: `حذف کارمند "${name}"`,
        text: 'آیا مطمئن هستید؟ با حذف این کارمند، دسترسی وی به پنل مسدود و نشست‌های فعال وی باطل خواهند شد.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'بله، حذف کن',
        cancelButtonText: 'انصراف'
    });

    if (!confirm.isConfirmed) return;

    try {
        const fd = new FormData();
        fd.append('id', id);

        const res = await fetch('ajax/admin/staff_delete.php', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: fd
        });
        const json = await res.json();

        if (!json.ok) {
            Swal.fire({ icon: 'error', title: 'خطا', text: json.message });
            return;
        }

        Swal.fire({ icon: 'success', title: 'حذف شد', text: json.message, timer: 1800, showConfirmButton: false });
        loadStaffList();
    } catch (e) {
        console.error(e);
        Swal.fire({ icon: 'error', title: 'خطا', text: 'ارتباط با سرور برقرار نشد' });
    }
}

// باز کردن مودال مدیریت ماتریس دسترسی‌ها
async function openPermissionsModal(id) {
    activeStaffId = id;
    const grid = document.getElementById('permissionsGrid');
    grid.innerHTML = `<div class="col-12 text-center py-5"><span class="spinner-border spinner-border-sm text-primary"></span> در حال دریافت دسترسی‌ها...</div>`;

    const modal = new bootstrap.Modal(document.getElementById('permissionsModal'));
    modal.show();

    try {
        const res = await fetch(`ajax/admin/staff_permissions_get.php?id=${id}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const json = await res.json();

        if (!json.ok) {
            grid.innerHTML = `<div class="col-12 text-center text-danger py-4">${json.message}</div>`;
            return;
        }

        document.getElementById('permModalStaffName').textContent = json.user.full_name;
        document.getElementById('permModalStaffPhone').textContent = `شماره موبایل: ${json.user.phone}`;

        currentCatalog = json.catalog;
        renderPermissionsGrid(json.catalog, json.active_permissions || []);
    } catch (e) {
        console.error(e);
        grid.innerHTML = `<div class="col-12 text-center text-danger py-4">خطا در بارگذاری اطلاعات دسترسی‌ها</div>`;
    }
}

// رسم ماتریس دسترسی‌ها در مودال
function renderPermissionsGrid(catalog, activePerms) {
    const grid = document.getElementById('permissionsGrid');
    const activeSet = new Set(activePerms);
    let html = '';

    for (const [groupKey, group] of Object.entries(catalog)) {
        let permsListHtml = '';
        const groupPermKeys = Object.keys(group.permissions);

        for (const [permKey, p] of Object.entries(group.permissions)) {
            const isChecked = activeSet.has(permKey) ? 'checked' : '';
            permsListHtml += `
                <div class="col-md-6 col-12 mb-2">
                    <div class="form-check p-2 rounded hover-bg-light border">
                        <input class="form-check-input perm-checkbox perm-group-${groupKey}" 
                               type="checkbox" 
                               value="${permKey}" 
                               id="chk_${permKey}" 
                               ${isChecked}
                               onchange="updateSelectedCount()">
                        <label class="form-check-label d-block ms-1" for="chk_${permKey}">
                            <span class="fw-semibold text-dark fs-13">${p.title}</span>
                            <small class="text-muted d-block fs-11 mt-1">${p.description}</small>
                        </label>
                    </div>
                </div>
            `;
        }

        html += `
            <div class="col-12">
                <div class="card border shadow-none mb-3">
                    <div class="card-header bg-light d-flex align-items-center justify-content-between py-2">
                        <div class="fw-bold fs-14 text-primary">
                            <i class="${group.icon || 'ri-shield-line'} me-1 align-middle"></i> ${group.title}
                        </div>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2" onclick="toggleGroupPerms('${groupKey}', true)">انتخاب این بخش</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" onclick="toggleGroupPerms('${groupKey}', false)">لغو این بخش</button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-2">
                            ${permsListHtml}
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    grid.innerHTML = html;
    updateSelectedCount();
}

// انتخاب / لغو تمام دسترسی‌های سیستم
function toggleAllPerms(check) {
    document.querySelectorAll('.perm-checkbox').forEach(chk => {
        chk.checked = check;
    });
    updateSelectedCount();
}

// انتخاب / لغو یک بخش خاص
function toggleGroupPerms(groupKey, check) {
    document.querySelectorAll(`.perm-group-${groupKey}`).forEach(chk => {
        chk.checked = check;
    });
    updateSelectedCount();
}

// بروزرسانی شمارنده دسترسی‌های انتخاب شده
function updateSelectedCount() {
    const checked = document.querySelectorAll('.perm-checkbox:checked').length;
    document.getElementById('selectedPermsCounter').textContent = checked;
}

// ذخیره دسترسی‌های کارمند
async function savePermissions() {
    if (!activeStaffId) return;

    const checkedBoxes = document.querySelectorAll('.perm-checkbox:checked');
    const perms = Array.from(checkedBoxes).map(c => c.value);

    const btn = document.getElementById('savePermsBtn');
    btn.disabled = true;
    btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1"></span> در حال ذخیره‌سازی...`;

    try {
        const fd = new FormData();
        fd.append('user_id', activeStaffId);
        perms.forEach(p => fd.append('permissions[]', p));

        const res = await fetch('ajax/admin/staff_permissions_save.php', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: fd
        });
        const json = await res.json();

        if (!json.ok) {
            Swal.fire({ icon: 'error', title: 'خطا', text: json.message || 'ذخیره انجام نشد' });
            return;
        }

        bootstrap.Modal.getInstance(document.getElementById('permissionsModal')).hide();
        Swal.fire({
            icon: 'success',
            title: 'موفق',
            text: `تعداد ${json.count} دسترسی با موفقیت برای این کارمند فعال شد.`,
            timer: 2000,
            showConfirmButton: false
        });
        loadStaffList();
    } catch (e) {
        console.error(e);
        Swal.fire({ icon: 'error', title: 'خطا', text: 'خطا در ارتباط با سرور' });
    } finally {
        btn.disabled = false;
        btn.innerHTML = `<i class="ri-check-double-line align-middle me-1"></i> ذخیره نهایی دسترسی‌ها`;
    }
}

function escapeHtml(text) {
    if (!text) return '';
    return text.replace(/[&<>"']/g, function(m) {
        return {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[m];
    });
}
</script>

<style>
.hover-bg-light:hover {
    background-color: rgba(115, 93, 255, 0.05);
    border-color: rgba(115, 93, 255, 0.3) !important;
}
.form-check-input:checked {
    background-color: var(--primary-color, #735dff);
    border-color: var(--primary-color, #735dff);
}
</style>

<?php require_once __DIR__ . '/views/panel/footer.php'; ?>
