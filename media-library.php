<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/init.php';
require_admin();

require_once "views/panel/header.php";
require_once "views/panel/sidebar.php";
?>

<div class="main-content app-content">
    <div class="container-fluid">

        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-2">مدیریت رسانه</h1>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">ابزارها</a></li>
                        <li class="breadcrumb-item active" aria-current="page">رسانه</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="row g-2 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">نوع فایل</label>
                                <select id="mf_type" class="form-select">
                                    <option value="">همه</option>
                                    <option value="1">عکس پروفایل</option>
                                    <option value="2">عکس کارت ملی</option>
                                    <option value="3">عکس گواهینامه</option>
                                    <option value="4">عکس کارت ماشین</option>
                                    <option value="5">عکس برگه سبز</option>
                                    <option value="6">ویدئو احراز هویت</option>
                                    <option value="7">عکس بیمه نامه</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">شناسه کاربر</label>
                                <input id="mf_user_id" type="number" class="form-control" placeholder="مثال: 12">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button id="mf_reload" class="btn btn-primary btn-wave w-100">
                                    <i class="ri-refresh-line align-middle"></i> بروزرسانی
                                </button>
                            </div>
                            <div class="col-12">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="mf_include_fs" checked>
                                    <label class="form-check-label" for="mf_include_fs">
                                        نمایش فایل‌های موجود در پوشه uploads حتی اگر در دیتابیس ثبت نشده باشند
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2 mb-3 bg-light p-3 rounded" id="bulkActionsWrapper" style="display: none !important;">
                            <span class="text-muted small fw-bold"><i class="ri-checkbox-multiple-line"></i> عملیات گروهی (<span id="selectedCount">0</span> رسانه):</span>
                            <button class="btn btn-sm btn-danger" id="btnBulkDelete">
                                <i class="ri-delete-bin-line align-middle"></i> حذف دائمی موارد انتخاب شده
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table id="mediaTable" class="table table-bordered table-hover w-100">
                                <thead>
                                    <tr>
                                        <th width="30" class="text-center"><input type="checkbox" id="selectAllMedia" class="form-check-input"></th>
                                        <th>ID</th>
                                        <th>پیش‌نمایش</th>
                                        <th>نوع</th>
                                        <th>کاربر</th>
                                        <th>حجم</th>
                                        <th>Mime</th>
                                        <th>کلید</th>
                                        <th>تاریخ</th>
                                        <th>عملیات</th>
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

<script>
    const BASE_URL = "<?= base_url(); ?>";

    function toast(msg) {
        if (window.Toastify) Toastify({
            text: msg,
            duration: 3000,
            gravity: "top",
            position: "right"
        }).showToast();
        else alert(msg);
    }

    $(function() {
        const table = $('#mediaTable').DataTable({
            ajax: {
                url: `${BASE_URL}/ajax/media-library.php?action=list`,
                data: function(d) {
                    d.file_type = $('#mf_type').val();
                    d.user_id = $('#mf_user_id').val();
                    d.include_fs = $('#mf_include_fs').is(':checked') ? 1 : 0;
                }
            },
            columns: [{
                    data: null,
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        // تفکیک فایل‌های دیتابیس از فایل‌های سیستمی
                        if (row.id !== '-') {
                            return `<input type="checkbox" class="form-check-input media-select-checkbox" data-type="db" value="${row.id}">`;
                        } else {
                            return `<input type="checkbox" class="form-check-input media-select-checkbox" data-type="fs" value="${row.file_key}">`;
                        }
                    }
                },
                {
                    data: 'id'
                },
                {
                    data: 'preview_html',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'file_type_label'
                },
                {
                    data: 'user_label'
                },
                {
                    data: 'file_size_human'
                },
                {
                    data: 'mime_type'
                },
                {
                    data: 'file_key'
                },
                {
                    data: 'created_at'
                },
                {
                    data: 'actions',
                    orderable: false,
                    searchable: false
                }
            ],
            order: [
                [1, 'desc']
            ], // مرتب‌سازی بر اساس ستون دوم (ID)
            language: {
                search: "جستجو:",
                lengthMenu: "نمایش _MENU_ ردیف",
                info: "نمایش _START_ تا _END_ از _TOTAL_ ردیف",
                infoEmpty: "هیچ داده‌ای موجود نیست",
                processing: "در حال بارگذاری...",
                zeroRecords: "داده‌ای پیدا نشد",
                paginate: {
                    previous: "قبلی",
                    next: "بعدی"
                }
            }
        });

        // --------------------------------------------------------
        // کدهای مربوط به عملیات گروهی (حذف چندگانه)
        // --------------------------------------------------------
        $('#selectAllMedia').on('change', function() {
            $('.media-select-checkbox').prop('checked', this.checked);
            updateBulkActionsWrapper();
        });

        $('#mediaTable').on('change', '.media-select-checkbox', function() {
            updateBulkActionsWrapper();
        });

        table.on('draw', function() {
            $('#selectAllMedia').prop('checked', false);
            updateBulkActionsWrapper();
        });

        function updateBulkActionsWrapper() {
            const count = $('.media-select-checkbox:checked').length;
            if (count > 0) {
                $('#selectedCount').text(count);
                $('#bulkActionsWrapper').attr('style', 'display: flex !important;');
            } else {
                $('#bulkActionsWrapper').attr('style', 'display: none !important;');
            }
        }

        $('#btnBulkDelete').on('click', async function() {
            const dbIds = [];
            const fsKeys = [];

            // جدا کردن شناسه‌های فایل‌های دیتابیس و کلیدهای فایل‌های سیستمی
            $('.media-select-checkbox:checked').each(function() {
                if ($(this).data('type') === 'db') {
                    dbIds.push($(this).val());
                } else {
                    fsKeys.push($(this).val());
                }
            });

            if (dbIds.length === 0 && fsKeys.length === 0) return;

            const r = await Swal.fire({
                icon: 'warning',
                title: 'حذف گروهی رسانه‌ها',
                text: `آیا از حذف دائمی ${dbIds.length + fsKeys.length} رسانه انتخاب شده مطمئن هستید؟ این عمل غیرقابل بازگشت است.`,
                showCancelButton: true,
                confirmButtonText: 'بله، همه حذف شوند',
                cancelButtonText: 'انصراف',
                confirmButtonColor: '#d33'
            });

            if (!r.isConfirmed) return;

            Swal.fire({
                title: 'در حال حذف...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.post(`${BASE_URL}/ajax/media-library.php?action=bulk_delete`, {
                ids: dbIds,
                keys: fsKeys
            }, function(res) {
                Swal.close();
                if (res && res.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'موفق',
                        text: res.message
                    });
                    table.ajax.reload(null, false);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطا',
                        text: res?.message || 'خطا در حذف فایل‌ها'
                    });
                }
            }, 'json').fail(function() {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'خطا',
                    text: 'خطا در ارتباط با سرور'
                });
            });
        });
        // --------------------------------------------------------

        $('#mf_reload').on('click', function() {
            table.ajax.reload();
        });

        $('#mf_include_fs').on('change', function() {
            table.ajax.reload();
        });

        $(document).on('click', '.mf-del', function() {
            const id = $(this).data('id');
            if (!confirm('حذف شود؟ این عمل برگشت‌پذیر نیست.')) return;
            $.post(`${BASE_URL}/ajax/media-library.php?action=delete`, {
                id
            }, function(res) {
                if (res && res.ok) {
                    toast('حذف شد');
                    table.ajax.reload(null, false);
                    return;
                }
                toast(res?.message || 'خطا');
            }, 'json');
        });

        $(document).on('click', '.mf-del-fs', function() {
            const key = $(this).data('key');
            if (!confirm('حذف فایل از روی سرور؟ این عمل برگشت‌پذیر نیست.')) return;
            $.post(`${BASE_URL}/ajax/media-library.php?action=delete_fs`, {
                key
            }, function(res) {
                if (res && res.ok) {
                    toast('حذف شد');
                    table.ajax.reload(null, false);
                    return;
                }
                toast(res?.message || 'خطا');
            }, 'json');
        });
    });
</script>

<?php require_once "views/panel/footer.php"; ?>