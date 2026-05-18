<?php
require_once "includes/init.php";
require_admin();
require_once "views/panel/header.php";
require_once "views/panel/sidebar.php";
require_once "includes/jdf.php";
?>

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-2">لیست بارهای اعلام شده</h1>
            </div>
            <div class="btn-list">
                <!-- دکمه حذف گروهی (پیش‌فرض مخفی) -->
                <button type="button" class="btn btn-danger btn-wave me-2" id="btnBulkDelete" style="display:none;" onclick="bulkDelete()">
                    <i class="ri-delete-bin-line align-middle"></i> حذف موارد انتخاب شده
                </button>

                <a href="load_create.php" class="btn btn-primary btn-wave">
                    <i class="ri-add-line align-middle"></i> اعلام بار جدید
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="loadsTable" class="table table-bordered table-striped w-100">
                                <thead>
                                    <tr>
                                        <!-- هدر چک‌باکس کل -->
                                        <th style="width: 30px;" class="text-center">
                                            <input class="form-check-input" type="checkbox" id="checkAll">
                                        </th>
                                        <th>کد رهگیری</th>
                                        <th>شرکت باربری</th>
                                        <th>مبدا</th>
                                        <th>مقصد</th>
                                        <th>نوع ماشین</th>
                                        <th>کالا</th>
                                        <th>کرایه (تومان)</th>
                                        <th>وضعیت</th>
                                        <th>تاریخ ثبت</th>
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
    $(function() {
        let table = $('#loadsTable').DataTable({
            ajax: {
                url: '<?= base_url() ?>/ajax/loads.php?action=list',
                type: 'GET'
            },
            columns: [{
                    data: 'id',
                    orderable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        return `<input class="form-check-input row-chk" type="checkbox" value="${data}">`;
                    }
                },
                {
                    data: 'public_code'
                },
                {
                    data: 'company_name'
                },
                {
                    data: 'origin'
                },
                {
                    data: 'destination'
                },
                {
                    data: 'vehicle'
                },
                {
                    data: 'cargo'
                },
                {
                    data: 'price'
                },
                {
                    data: 'status'
                },
                {
                    data: 'created_at'
                },
                {
                    data: 'actions',
                    orderable: false
                }
            ],
            order: [
                [9, 'desc'] // سورت پیش‌فرض بر اساس تاریخ ثبت (اکنون ستون نهم است)
            ],
            language: {
                url: 'assets/vendor/datatables/fa.json'
            }
        });

        // مدیریت انتخاب همه چک باکس ها
        $('#checkAll').on('change', function() {
            $('.row-chk').prop('checked', this.checked);
            toggleBulkBtn();
        });

        // مدیریت تغییر وضعیت هر چک باکس به صورت تکی
        $('#loadsTable tbody').on('change', '.row-chk', function() {
            toggleBulkBtn();
            if (!this.checked) {
                $('#checkAll').prop('checked', false);
            }
        });

        // هر بار دیتاتیبل آپدیت شد، تیک‌ها را بردار
        table.on('draw', function() {
            $('#checkAll').prop('checked', false);
            toggleBulkBtn();
        });
    });

    function toggleBulkBtn() {
        if ($('.row-chk:checked').length > 0) {
            $('#btnBulkDelete').fadeIn();
        } else {
            $('#btnBulkDelete').fadeOut();
        }
    }

    function deleteLoad(id) {
        Swal.fire({
            title: 'آیا مطمئن هستید؟',
            text: 'این اعلام بار حذف خواهد شد.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'بله، حذف شود',
            cancelButtonText: 'انصراف'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= base_url() ?>/ajax/loads.php?action=delete', {
                    id: id
                }, function(res) {
                    if (res.ok) $('#loadsTable').DataTable().ajax.reload();
                }, 'json');
            }
        });
    }

    // تابع حذف گروهی
    function bulkDelete() {
        let selectedIds = [];
        $('.row-chk:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) return;

        Swal.fire({
            title: 'حذف گروهی',
            text: `شما در حال حذف ${selectedIds.length} اعلام بار هستید. مطمئنید؟`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'بله، حذف شوند',
            cancelButtonText: 'انصراف'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= base_url() ?>/ajax/loads.php?action=bulk_delete', {
                    ids: selectedIds
                }, function(res) {
                    if (res.ok) {
                        Swal.fire('موفق', 'موارد انتخاب شده با موفقیت حذف شدند.', 'success');
                        $('#loadsTable').DataTable().ajax.reload();
                        $('#checkAll').prop('checked', false);
                        toggleBulkBtn();
                    } else {
                        Swal.fire('خطا', res.message || 'عملیات ناموفق بود', 'error');
                    }
                }, 'json');
            }
        });
    }
</script>

<?php require_once "views/panel/footer.php"; ?>