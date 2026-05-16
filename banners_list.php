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
                <h1 class="page-title fw-medium fs-18 mb-2">مدیریت بنرهای تبلیغاتی</h1>
                <div>
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="dashboard.php">داشبورد</a></li>
                            <li class="breadcrumb-item active" aria-current="page">بنرها</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="btn-list">
                <button class="btn btn-primary btn-wave" data-bs-toggle="modal" data-bs-target="#bannerModal">
                    <i class="ri-add-line align-middle"></i> افزودن بنر جدید
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="bannersTable" class="table table-bordered table-striped w-100">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>تصویر</th>
                                        <th>عنوان و متن</th>
                                        <th>اپلیکیشن / محل</th>
                                        <th>لینک (Action)</th>
                                        <th>اولویت</th>
                                        <th>وضعیت</th>
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

<div class="modal fade" id="bannerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">افزودن بنر جدید</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="bannerForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">عنوان بنر</label>
                            <input type="text" name="title" class="form-control" required placeholder="عنوان بنر">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">توضیح کوتاه (Body)</label>
                            <input type="text" name="body" class="form-control" placeholder="اختیاری">
                        </div>

                        <div class="col-md-12 border-top pt-3 mt-3"></div>

                        <div class="col-md-4">
                            <label class="form-label">اپلیکیشن هدف</label>
                            <select name="target_app_id" class="form-select">
                                <option value="1">اپلیکیشن رانندگان</option>
                                <option value="2">اپلیکیشن صاحبان بار</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">محل نمایش</label>
                            <select name="placement" class="form-select">
                                <option value="dashboard">داشبورد (صفحه اصلی)</option>
                                <option value="profile">پروفایل کاربری</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">اولویت نمایش</label>
                            <input type="number" name="priority" class="form-control" value="100">
                            <small class="text-muted">اعداد کوچکتر اولویت بالاتری دارند</small>
                        </div>

                        <div class="col-md-12 border-top pt-3 mt-3"></div>

                        <div class="col-md-6">
                            <label class="form-label">لینک مقصد (Action Value)</label>
                            <input type="url" name="action_value" class="form-control" dir="ltr" placeholder="https://...">
                            <input type="hidden" name="action_type" value="1">
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                                <label class="form-check-label" for="is_active">بنر فعال باشد</label>
                            </div>
                        </div>

                        <div class="col-12 mt-3">
                            <label class="form-label">تصویر بنر (الزامی)</label>
                            <input type="file" name="image" class="form-control" accept="image/jpeg, image/png, image/webp" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                <button type="button" class="btn btn-primary" id="btnSaveBanner">ذخیره بنر</button>
            </div>
        </div>
    </div>
</div>

<script>
    const BASE_URL = "<?= base_url(); ?>";
    let dt;

    $(function() {
        dt = $('#bannersTable').DataTable({
            ajax: {
                url: `${BASE_URL}/ajax/banners.php?action=list`,
                type: 'GET'
            },
            columns: [{
                    data: 'id'
                },
                {
                    data: 'image',
                    orderable: false
                },
                {
                    data: 'title_body'
                },
                {
                    data: 'targeting'
                },
                {
                    data: 'action_value'
                },
                {
                    data: 'priority'
                },
                {
                    data: 'status'
                },
                {
                    data: 'actions',
                    orderable: false
                }
            ],
            language: {
                url: 'assets/vendor/datatables/fa.json'
            },
            order: [
                [0, 'desc']
            ]
        });

        $('#btnSaveBanner').on('click', async function() {
            const form = document.getElementById('bannerForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const fd = new FormData(form);
            fd.append('action', 'save');

            const btn = $(this);
            btn.prop('disabled', true).text('در حال ذخیره...');

            try {
                const res = await fetch(`${BASE_URL}/ajax/banners.php`, {
                    method: 'POST',
                    body: fd
                });
                const data = await res.json();

                if (data.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'موفق',
                        text: data.message
                    });
                    $('#bannerModal').modal('hide');
                    form.reset();
                    dt.ajax.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطا',
                        text: data.message
                    });
                }
            } catch (e) {
                Swal.fire({
                    icon: 'error',
                    title: 'خطا',
                    text: 'خطا در ارتباط با سرور'
                });
            } finally {
                btn.prop('disabled', false).text('ذخیره بنر');
            }
        });
    });

    function deleteBanner(id) {
        Swal.fire({
            icon: 'warning',
            title: 'حذف بنر',
            text: 'آیا از حذف این بنر مطمئن هستید؟ تصویر آن نیز حذف خواهد شد.',
            showCancelButton: true,
            confirmButtonText: 'بله، حذف شود',
            cancelButtonText: 'انصراف'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(`${BASE_URL}/ajax/banners.php`, {
                    action: 'delete',
                    id: id
                }, function(res) {
                    if (res.ok) {
                        toast('بنر با موفقیت حذف شد');
                        dt.ajax.reload();
                    } else {
                        Swal.fire('خطا', res.message || 'خطا در حذف', 'error');
                    }
                }, 'json');
            }
        });
    }

    function toast(msg) {
        if (window.Toastify) Toastify({
            text: msg,
            duration: 3000
        }).showToast();
        else alert(msg);
    }
</script>

<?php require_once "views/panel/footer.php"; ?>