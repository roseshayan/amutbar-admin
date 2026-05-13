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
                <h1 class="page-title fw-medium fs-18 mb-2">مدیریت وسیله‌های نقلیه</h1>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">تنظیمات</a></li>
                        <li class="breadcrumb-item active" aria-current="page">وسیله‌های نقلیه</li>
                    </ol>
                </nav>
            </div>
            <div class="btn-list">
                <button class="btn btn-primary btn-wave" data-bs-toggle="modal" data-bs-target="#vtModal">
                    <i class="ri-add-line align-middle"></i> افزودن
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="vtTable" class="table table-bordered table-hover w-100">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>عنوان</th>
                                    <th>کد</th>
                                    <th>Parent</th>
                                    <th>Body</th>
                                    <th>حداکثر وزن (kg)</th>
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

<div class="modal fade" id="vtModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">وسیله نقلیه</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="vtForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="vt_id">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label for="vt_title" class="form-label">عنوان *</label>
                            <input type="text" class="form-control" name="title" id="vt_title" required maxlength="128">
                        </div>
                        <div class="col-md-6">
                            <label for="vt_code" class="form-label">کد *</label>
                            <input type="text" class="form-control" name="code" id="vt_code" required maxlength="64">
                        </div>
                        <div class="col-md-6">
                            <label for="vt_parent_id" class="form-label">Parent (اختیاری)</label>
                            <select class="form-select" name="parent_id" id="vt_parent_id">
                                <option value="">-</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="vt_body_type" class="form-label">نوع بدنه</label>
                            <input type="text" class="form-control" name="body_type" id="vt_body_type" maxlength="64"
                                   placeholder="مثلاً: نیسان - مسقف">
                        </div>
                        <div class="col-md-6">
                            <label for="vt_max_weight_kg" class="form-label">حداکثر وزن (kg)</label>
                            <input type="number" class="form-control" name="max_weight_kg" id="vt_max_weight_kg" min="0"
                                   step="0.01">
                        </div>
                        <div class="col-md-6">
                            <label for="vt_is_active" class="form-label">وضعیت</label>
                            <select class="form-select" name="is_active" id="vt_is_active">
                                <option value="1">فعال</option>
                                <option value="0">غیرفعال</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="vt_description" class="form-label">توضیحات</label>
                            <textarea class="form-control" name="description" id="vt_description" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
                    <button type="submit" class="btn btn-primary">ذخیره</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const BASE_URL = "<?= base_url(); ?>";

    function toast(msg) {
        if (window.Toastify) Toastify({text: msg, duration: 3000, gravity: "top", position: "right"}).showToast();
        else alert(msg);
    }

    function resetForm() {
        $('#vt_id').val('');
        $('#vt_title').val('');
        $('#vt_code').val('');
        $('#vt_parent_id').val('');
        $('#vt_body_type').val('');
        $('#vt_max_weight_kg').val('');
        $('#vt_is_active').val('1');
        $('#vt_description').val('');
    }

    function loadParents() {
        $.getJSON(`${BASE_URL}/ajax/vehicle-types.php?action=parents`, function (res) {
            if (!res || !res.ok) return;
            const sel = $('#vt_parent_id');
            sel.html('<option value="">-</option>');
            (res.items || []).forEach(it => {
                sel.append(`<option value="${it.id}">${it.title} (#${it.id})</option>`);
            });
        });
    }

    $(function () {
        loadParents();

        const table = $('#vtTable').DataTable({
            ajax: `${BASE_URL}/ajax/vehicle-types.php?action=list`,
            columns: [
                {data: 'id'},
                {data: 'title'},
                {data: 'code'},
                {data: 'parent_id'},
                {data: 'body_type'},
                {data: 'max_weight_kg'},
                {data: 'status_badge'},
                {data: 'actions'}
            ],
            order: [[0, 'desc']],
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

        $('#vtModal').on('show.bs.modal', function () {
            loadParents();
        });

        $('#vtForm').on('submit', function (e) {
            e.preventDefault();
            const formData = $(this).serialize();
            $.post(`${BASE_URL}/ajax/vehicle-types.php?action=save`, formData, function (res) {
                if (res && res.ok) {
                    toast('ذخیره شد');
                    $('#vtModal').modal('hide');
                    table.ajax.reload(null, false);
                    resetForm();
                    return;
                }
                toast(res?.message || 'خطا');
            }, 'json');
        });

        $(document).on('click', '.vt-edit', function () {
            const id = $(this).data('id');
            $.getJSON(`${BASE_URL}/ajax/vehicle-types.php?action=get&id=${id}`, function (res) {
                if (!res || !res.ok) return toast('یافت نشد');
                const it = res.item;
                $('#vt_id').val(it.id);
                $('#vt_title').val(it.title);
                $('#vt_code').val(it.code);
                $('#vt_parent_id').val(it.parent_id || '');
                $('#vt_body_type').val(it.body_type || '');
                $('#vt_max_weight_kg').val(it.max_weight_kg || '');
                $('#vt_is_active').val(it.is_active ? '1' : '0');
                $('#vt_description').val(it.description || '');
                $('#vtModal').modal('show');
            });
        });

        $(document).on('click', '.vt-toggle', function () {
            const id = $(this).data('id');
            $.post(`${BASE_URL}/ajax/vehicle-types.php?action=toggle`, {id}, function (res) {
                if (res && res.ok) {
                    table.ajax.reload(null, false);
                    return;
                }
                toast(res?.message || 'خطا');
            }, 'json');
        });

        $(document).on('click', '.vt-delete', function () {
            if (!confirm('حذف شود؟')) return;
            const id = $(this).data('id');
            $.post(`${BASE_URL}/ajax/vehicle-types.php?action=delete`, {id}, function (res) {
                if (res && res.ok) {
                    table.ajax.reload(null, false);
                    loadParents();
                    return;
                }
                toast(res?.message || 'خطا');
            }, 'json');
        });
    });
</script>

<?php require_once "views/panel/footer.php"; ?>