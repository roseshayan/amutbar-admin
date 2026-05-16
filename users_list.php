<?php
require_once __DIR__ . '/includes/init.php';
require_admin();
require_once "views/panel/header.php";
require_once "views/panel/sidebar.php";
?>

<div class="main-content app-content">
    <div class="container-fluid">

        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-2">مدیریت کاربران</h1>
                <div>
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="dashboard.php">داشبورد</a></li>
                            <li class="breadcrumb-item active" aria-current="page">کاربران</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="btn-list">
                <a href="user_create.php" class="btn btn-primary btn-wave">
                    <i class="ri-user-add-line align-middle"></i> افزودن کاربر
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body">

                        <div class="d-flex align-items-center gap-2 mb-3 bg-light p-3 rounded" id="bulkActionsWrapper" style="display: none !important;">
                            <span class="text-muted small fw-bold"><i class="ri-checkbox-multiple-line"></i> عملیات گروهی (<span id="selectedCount">0</span> کاربر):</span>
                            <button class="btn btn-sm btn-success" onclick="bulkAction('activate')">فعال‌سازی</button>
                            <button class="btn btn-sm btn-warning" onclick="bulkAction('deactivate')">غیرفعال‌سازی</button>
                            <button class="btn btn-sm btn-danger" onclick="bulkAction('delete')">حذف دائمی</button>
                        </div>

                        <div class="table-responsive">
                            <table id="usersTable" class="table table-striped table-bordered w-100">
                                <thead>
                                    <tr>
                                        <th width="30" class="text-center"><input type="checkbox" id="selectAllUsers" class="form-check-input"></th>
                                        <th>ID</th>
                                        <th>نام</th>
                                        <th>موبایل</th>
                                        <th>ایمیل</th>
                                        <th>نوع</th>
                                        <th>وضعیت</th>
                                        <th>تاریخ ایجاد</th>
                                        <th>عملیات</th>
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

    function loader(title) {
        Swal.fire({
            title: title || 'در حال پردازش...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => Swal.showLoading()
        });
    }

    let dt;
    document.addEventListener('DOMContentLoaded', () => {
        dt = $('#usersTable').DataTable({
            processing: true,
            serverSide: true,
            searchDelay: 350,
            order: [
                [1, 'desc']
            ], // مرتب سازی بر اساس ID (ستون دوم)
            ajax: {
                url: `${BASE_URL}/ajax/users/users_list.php`,
                type: 'POST'
            },
            columns: [{
                    data: null,
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(row) {
                        return `<input type="checkbox" class="form-check-input user-select-checkbox" value="${row.id}">`;
                    }
                },
                {
                    data: 'id'
                },
                {
                    data: 'full_name'
                },
                {
                    data: 'phone'
                },
                {
                    data: 'email'
                },
                {
                    data: 'user_type_label'
                },
                {
                    data: 'status',
                    render: function(data, type, row) {
                        // نمایش رنگی وضعیت‌ها
                        if (data == 1) return `<span class="badge bg-success-transparent">${row.status_label}</span>`;
                        if (data == 2) return `<span class="badge bg-danger-transparent">${row.status_label}</span>`;
                        return `<span class="badge bg-warning-transparent">${row.status_label}</span>`;
                    }
                },
                {
                    "data": "created_at",
                    "render": function(data) {
                        return data ? new Date(data).toLocaleString('fa-IR') : '-';
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(row) {
                        // مدیریت دکمه وضعیت تکی فعال یا غیرفعال
                        const statusBtn = row.status == 1 ?
                            `<button class="btn btn-sm btn-outline-warning ms-1" onclick="toggleStatus(${row.id}, 'deactivate')">غیرفعال کردن</button>` :
                            `<button class="btn btn-sm btn-outline-success ms-1" onclick="toggleStatus(${row.id}, 'activate')">فعال کردن</button>`;

                        return `
                            <a class="btn btn-sm btn-outline-primary" href="user_edit.php?id=${row.id}">ویرایش</a>
                            ${statusBtn}
                            <button class="btn btn-sm btn-outline-danger ms-1" onclick="deleteUser(${row.id})">حذف دائم</button>
                        `;
                    }
                }
            ],
            language: {
                url: 'assets/vendor/datatables/fa.json',
            },
            drawCallback: function() {
                // ریست کردن وضعیت چک‌باکس‌ها بعد از لود صفحه جدید جدول
                $('#selectAllUsers').prop('checked', false);
                updateBulkActionsWrapper();
            }
        });

        // مدیریت انتخاب همه چک‌باکس‌ها
        $('#selectAllUsers').on('change', function() {
            $('.user-select-checkbox').prop('checked', this.checked);
            updateBulkActionsWrapper();
        });

        // مدیریت لیسنر چک‌باکس‌های تکی داخل جدول
        $('#usersTable').on('change', '.user-select-checkbox', function() {
            updateBulkActionsWrapper();
        });
    });

    // به‌روزرسانی نوار عملیات گروهی بالا
    function updateBulkActionsWrapper() {
        const checkedBoxes = $('.user-select-checkbox:checked');
        const count = checkedBoxes.length;
        if (count > 0) {
            $('#selectedCount').text(count);
            $('#bulkActionsWrapper').attr('style', 'display: flex !important;');
        } else {
            $('#bulkActionsWrapper').attr('style', 'display: none !important;');
        }
    }

    // عملیات تغییر وضعیت تکی کاربر
    async function toggleStatus(id, action) {
        loader('در حال تغییر وضعیت...');
        try {
            const fd = new FormData();
            fd.append('ids[]', id);
            fd.append('action', action);

            const res = await fetch(`${BASE_URL}/ajax/users/users_delete.php`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: fd
            });
            const data = await res.json();
            Swal.close();

            if (data.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'موفق',
                    text: 'وضعیت کاربر با موفقیت تغییر کرد.'
                });
                dt.ajax.reload(null, false);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'خطا',
                    text: data.message || 'عملیات ناموفق بود'
                });
            }
        } catch (e) {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'خطا',
                text: 'خطا در ارتباط با سرور'
            });
        }
    }

    // عملیات حذف تکی کاربر با پیام هشدار اختصاصی دائم
    async function deleteUser(id) {
        const r = await Swal.fire({
            icon: 'warning',
            title: 'حذف دائمی کاربر',
            text: 'آیا واقعاً می‌خواهی حذف کنی؟ با حذف کاربر به صورت دائم اطلاعات آن حذف خواهد شد.',
            showCancelButton: true,
            confirmButtonText: 'بله، کاملاً حذف شود',
            cancelButtonText: 'انصراف',
            confirmButtonColor: '#d33'
        });
        if (!r.isConfirmed) return;

        loader('در حال حذف کامل...');
        try {
            const fd = new FormData();
            fd.append('ids[]', id);
            fd.append('action', 'delete');

            const res = await fetch(`${BASE_URL}/ajax/users/users_delete.php`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: fd
            });
            const data = await res.json();
            Swal.close();

            if (data.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'موفق',
                    text: 'کاربر و کلیه اطلاعات مربوطه کاملاً حذف شدند.'
                });
                dt.ajax.reload(null, false);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'خطا',
                    text: data.message || 'حذف انجام نشد'
                });
            }
        } catch (e) {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'خطا',
                text: 'خطا در ارتباط با سرور'
            });
        }
    }

    // عملیات چندتایی (حذف گروهی، غیرفعال‌سازی یا فعال‌سازی گروهی)
    async function bulkAction(action) {
        const checkedBoxes = $('.user-select-checkbox:checked');
        const ids = [];
        checkedBoxes.each(function() {
            ids.push($(this).val());
        });

        if (ids.length === 0) return;

        if (action === 'delete') {
            const r = await Swal.fire({
                icon: 'warning',
                title: 'حذف دائمی گروهی کاربران',
                text: `آیا واقعاً می‌خواهی ${ids.length} کاربر انتخاب شده را حذف کنی؟ با حذف کاربران به صورت دائم اطلاعات آن‌ها حذف خواهد شد.`,
                showCancelButton: true,
                confirmButtonText: 'بله، حذف گروهی انجام شود',
                cancelButtonText: 'انصراف',
                confirmButtonColor: '#d33'
            });
            if (!r.isConfirmed) return;
        }

        loader('در حال اعمال عملیات گروهی...');
        try {
            const fd = new FormData();
            ids.forEach(id => fd.append('ids[]', id));
            fd.append('action', action);

            const res = await fetch(`${BASE_URL}/ajax/users/users_delete.php`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: fd
            });
            const data = await res.json();
            Swal.close();

            if (data.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'موفق',
                    text: 'عملیات گروهی با موفقیت اعمال شد.'
                });
                dt.ajax.reload(null, false);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'خطا',
                    text: data.message || 'عملیات گروهی ناموفق بود'
                });
            }
        } catch (e) {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'خطا',
                text: 'خطا در ارتباط با سرور'
            });
        }
    }
</script>

<?php require_once "views/panel/footer.php"; ?>