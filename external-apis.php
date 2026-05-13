<?php
require_once "views/panel/header.php";
require_once "views/panel/sidebar.php";
?>

<!-- MAIN-CONTENT -->
<div class="main-content app-content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-2">مدیریت API‌های خارجی</h1>
                <div class="">
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">تنظیمات</a></li>
                            <li class="breadcrumb-item active" aria-current="page">API‌های خارجی</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="btn-list">
                <button class="btn btn-primary btn-wave" data-bs-toggle="modal" data-bs-target="#providerModal">
                    <i class="ri-add-line align-middle"></i> افزودن ارائه‌دهنده
                </button>
                <button class="btn btn-secondary btn-wave" data-bs-toggle="modal" data-bs-target="#credentialModal">
                    <i class="ri-key-2-line align-middle"></i> افزودن اعتبارنامه
                </button>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Start::row-1 -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">ارائه‌دهندگان API</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="providersTable" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>نام</th>
                                    <th>Slug</th>
                                    <th>آدرس پایه</th>
                                    <th>نوع احراز</th>
                                    <th>وضعیت</th>
                                    <th>اولویت</th>
                                    <th>تاریخ ایجاد</th>
                                    <th>عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                <!-- Data will be loaded via Ajax -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--End::row-1 -->

        <!-- Start::row-2 -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">اعتبارنامه‌های API</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="credentialsTable" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>ارائه‌دهنده</th>
                                    <th>محیط</th>
                                    <th>شناسه کلید</th>
                                    <th>وضعیت</th>
                                    <th>تاریخ ایجاد</th>
                                    <th>تاریخ بروزرسانی</th>
                                    <th>عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                <!-- Data will be loaded via Ajax -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--End::row-2 -->

    </div>
</div>

<!-- Modal برای افزودن/ویرایش ارائه‌دهنده -->
<div class="modal fade" id="providerModal" tabindex="-1" aria-labelledby="providerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="providerModalLabel">افزودن ارائه‌دهنده جدید</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="providerForm">
                <div class="modal-body">
                    <input type="hidden" id="provider_id" name="id">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="provider_name" class="form-label">نام ارائه‌دهنده *</label>
                                <input type="text" class="form-control" id="provider_name" name="name" required
                                       maxlength="64">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="provider_slug" class="form-label">Slug *</label>
                                <input type="text" class="form-control" id="provider_slug" name="slug" required
                                       maxlength="64">
                                <small class="form-text text-muted">شناسه یکتای انگلیسی</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="base_url" class="form-label">آدرس پایه (Base URL) *</label>
                        <input type="url" class="form-control" id="base_url" name="base_url" required maxlength="255">
                    </div>

                    <div class="mb-3">
                        <label for="docs_url" class="form-label">آدرس مستندات</label>
                        <input type="url" class="form-control" id="docs_url" name="docs_url" maxlength="255">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="auth_type" class="form-label">نوع احراز هویت *</label>
                                <select class="form-control" id="auth_type" name="auth_type" required>
                                    <option value="">انتخاب کنید</option>
                                    <option value="1">API Key</option>
                                    <option value="2">Bearer Token</option>
                                    <option value="3">Basic Auth</option>
                                    <option value="4">OAuth 2.0</option>
                                    <option value="5">Custom</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">وضعیت *</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="">انتخاب کنید</option>
                                    <option value="1">فعال</option>
                                    <option value="0">غیرفعال</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="priority" class="form-label">اولویت</label>
                                <input type="number" class="form-control" id="priority" name="priority" value="100"
                                       min="1" max="1000">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="timeout_ms" class="form-label">Timeout (میلی‌ثانیه)</label>
                                <input type="number" class="form-control" id="timeout_ms" name="timeout_ms" value="8000"
                                       min="1000" max="30000">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="retry_count" class="form-label">تعداد تلاش مجدد</label>
                                <input type="number" class="form-control" id="retry_count" name="retry_count" value="1"
                                       min="0" max="5">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="rate_limit_rpm" class="form-label">محدودیت نرخ (درخواست در دقیقه)</label>
                        <input type="number" class="form-control" id="rate_limit_rpm" name="rate_limit_rpm" min="1"
                               max="1000">
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

<!-- Modal برای افزودن/ویرایش اعتبارنامه -->
<div class="modal fade" id="credentialModal" tabindex="-1" aria-labelledby="credentialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="credentialModalLabel">افزودن اعتبارنامه جدید</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="credentialForm">
                <div class="modal-body">
                    <input type="hidden" id="credential_id" name="id">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="provider_id_select" class="form-label">ارائه‌دهنده *</label>
                                <select class="form-control" id="provider_id_select" name="provider_id" required>
                                    <option value="">انتخاب کنید</option>
                                    <!-- Options will be loaded via Ajax -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="env" class="form-label">محیط *</label>
                                <select class="form-control" id="env" name="env" required>
                                    <option value="">انتخاب کنید</option>
                                    <option value="1">توسعه (Development)</option>
                                    <option value="2">آزمایشی (Staging)</option>
                                    <option value="3">عملیاتی (Production)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="key_id" class="form-label">شناسه کلید</label>
                        <input type="text" class="form-control" id="key_id" name="key_id" maxlength="64">
                        <small class="form-text text-muted">شناسه عمومی کلید (اختیاری)</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="api_key" class="form-label">API Key</label>
                                <input type="text" class="form-control" id="api_key" name="api_key" maxlength="255">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="api_secret" class="form-label">API Secret</label>
                                <input type="password" class="form-control" id="api_secret" name="api_secret"
                                       maxlength="255">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="bearer_token" class="form-label">Bearer Token</label>
                        <textarea class="form-control" id="bearer_token" name="bearer_token" rows="3"
                                  maxlength="2048"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="extra_json" class="form-label">تنظیمات اضافی (JSON)</label>
                        <textarea class="form-control" id="extra_json" name="extra_json" rows="4"
                                  placeholder='{"custom_field": "value"}'></textarea>
                        <small class="form-text text-muted">تنظیمات خاص API در فرمت JSON</small>
                    </div>

                    <div class="mb-3">
                        <label for="credential_status" class="form-label">وضعیت *</label>
                        <select class="form-control" id="credential_status" name="status" required>
                            <option value="">انتخاب کنید</option>
                            <option value="1">فعال</option>
                            <option value="0">غیرفعال</option>
                            <option value="2">در انتظار تایید</option>
                        </select>
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

<?php
require_once "views/panel/footer.php";
?>

<script>
    $(document).ready(function () {
        const baseUrl = 'ajax/external-apis.php';

        // Initialize providers table
        const providersTable = $('#providersTable').DataTable({
            "ajax": {
                "url": baseUrl + '?action=list_providers',
                "type": "GET",
                "dataSrc": "data",
                "error": function (xhr, error, thrown) {
                    console.error('Error loading providers:', error, thrown);
                    Swal.fire({
                        title: 'خطا',
                        text: 'خطا در بارگذاری داده‌ها',
                        icon: 'error'
                    });
                }
            },
            "columns": [{
                "data": "id"
            },
                {
                    "data": "name"
                },
                {
                    "data": "slug"
                },
                {
                    "data": "base_url"
                },
                {
                    "data": "auth_type",
                    "render": function (data) {
                        const types = {
                            1: 'API Key',
                            2: 'Bearer Token',
                            3: 'Basic Auth',
                            4: 'OAuth 2.0',
                            5: 'Custom'
                        };
                        return types[data] || data;
                    }
                },
                {
                    "data": "status",
                    "render": function (data) {
                        return data == 1 ?
                            '<span class="badge bg-success">فعال</span>' :
                            '<span class="badge bg-danger">غیرفعال</span>';
                    }
                },
                {
                    "data": "priority"
                },
                {
                    "data": "created_at",
                    "render": function (data) {
                        return data ? new Date(data).toLocaleString('fa-IR') : '-';
                    }
                },
                {
                    "data": null,
                    "orderable": false,
                    "render": function (data, type, row) {
                        return `
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-warning btn-wave edit-provider" data-id="${row.id}">
                                <i class="ri-edit-line"></i>
                            </button>
                            <button class="btn btn-danger btn-wave delete-provider" data-id="${row.id}" data-name="${row.name}">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    `;
                    }
                }
            ],
            "language": {
                "url": "assets/vendor/datatables/fa.json"
            },
            "order": [
                [0, "desc"]
            ]
        });

        // Initialize credentials table
        const credentialsTable = $('#credentialsTable').DataTable({
            "ajax": {
                "url": baseUrl + '?action=list_credentials',
                "type": "GET",
                "dataSrc": "data",
                "error": function (xhr, error, thrown) {
                    console.error('Error loading credentials:', error, thrown);
                    Swal.fire({
                        title: 'خطا',
                        text: 'خطا در بارگذاری داده‌ها',
                        icon: 'error'
                    });
                }
            },
            "columns": [{
                "data": "id"
            },
                {
                    "data": "provider_name",
                    "render": function (data) {
                        return data || '-';
                    }
                },
                {
                    "data": "env",
                    "render": function (data) {
                        const envs = {
                            1: 'توسعه',
                            2: 'آزمایشی',
                            3: 'عملیاتی'
                        };
                        return envs[data] || data;
                    }
                },
                {
                    "data": "key_id",
                    "render": function (data) {
                        return data || '-';
                    }
                },
                {
                    "data": "status",
                    "render": function (data) {
                        const statuses = {
                            1: '<span class="badge bg-success">فعال</span>',
                            0: '<span class="badge bg-danger">غیرفعال</span>',
                            2: '<span class="badge bg-warning">در انتظار</span>'
                        };
                        return statuses[data] || data;
                    }
                },
                {
                    "data": "created_at",
                    "render": function (data) {
                        return data ? new Date(data).toLocaleString('fa-IR') : '-';
                    }
                },
                {
                    "data": "updated_at",
                    "render": function (data) {
                        return data ? new Date(data).toLocaleString('fa-IR') : '-';
                    }
                },
                {
                    "data": null,
                    "orderable": false,
                    "render": function (data, type, row) {
                        return `
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-warning btn-wave edit-credential" data-id="${row.id}">
                                <i class="ri-edit-line"></i>
                            </button>
                            <button class="btn btn-danger btn-wave delete-credential" data-id="${row.id}">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    `;
                    }
                }
            ],
            "language": {
                "url": "assets/vendor/datatables/fa.json"
            },
            "order": [
                [0, "desc"]
            ]
        });

        // Load providers for select dropdown
        function loadProvidersForSelect() {
            $.ajax({
                url: baseUrl + '?action=get_providers_select',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        const select = $('#provider_id_select');
                        select.empty().append('<option value="">انتخاب کنید</option>');
                        response.data.forEach(provider => {
                            select.append(`<option value="${provider.id}">${provider.name}</option>`);
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        title: 'خطا',
                        text: 'خطا در دریافت لیست ارائه‌دهندگان',
                        icon: 'error'
                    });
                }
            });
        }

        // Show provider modal for adding
        $('button[data-bs-target="#providerModal"]').click(function () {
            $('#providerForm')[0].reset();
            $('#provider_id').val('');
            $('#providerModalLabel').text('افزودن ارائه‌دهنده جدید');
            $('#auth_type, #status').val('');
            $('#providerModal').modal('show');
        });

        // Edit provider
        $(document).on('click', '.edit-provider', function () {
            const id = $(this).data('id');

            $.ajax({
                url: baseUrl + '?action=get_provider',
                type: 'GET',
                data: {
                    id: id
                },
                dataType: 'json',
                beforeSend: function () {
                    Swal.fire({
                        title: 'در حال بارگذاری...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function (response) {
                    Swal.close();
                    if (response.status === 'success') {
                        const provider = response.data;
                        $('#provider_id').val(provider.id);
                        $('#provider_name').val(provider.name);
                        $('#provider_slug').val(provider.slug);
                        $('#base_url').val(provider.base_url);
                        $('#docs_url').val(provider.docs_url || '');
                        $('#auth_type').val(provider.auth_type);
                        $('#status').val(provider.status);
                        $('#priority').val(provider.priority);
                        $('#timeout_ms').val(provider.timeout_ms);
                        $('#retry_count').val(provider.retry_count);
                        $('#rate_limit_rpm').val(provider.rate_limit_rpm || '');

                        $('#providerModalLabel').text('ویرایش ارائه‌دهنده');
                        $('#providerModal').modal('show');
                    } else {
                        Swal.fire({
                            title: 'خطا',
                            text: response.message,
                            icon: 'error'
                        });
                    }
                },
                error: function () {
                    Swal.close();
                    Swal.fire({
                        title: 'خطا',
                        text: 'خطا در دریافت اطلاعات ارائه‌دهنده',
                        icon: 'error'
                    });
                }
            });
        });

        // Submit provider form
        $('#providerForm').submit(function (e) {
            e.preventDefault();

            const formData = $(this).serialize();
            const action = $('#provider_id').val() ? 'update_provider' : 'add_provider';

            $.ajax({
                url: baseUrl,
                type: 'POST',
                data: formData + '&action=' + action,
                dataType: 'json',
                beforeSend: function () {
                    Swal.fire({
                        title: 'در حال ذخیره...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function (response) {
                    Swal.close();
                    if (response.status === 'success') {
                        Swal.fire({
                            title: 'موفقیت',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'باشه'
                        }).then(() => {
                            $('#providerModal').modal('hide');
                            providersTable.ajax.reload();
                            // برای به‌روزرسانی dropdown در فرم اعتبارنامه
                            loadProvidersForSelect();
                        });
                    } else {
                        Swal.fire({
                            title: 'خطا',
                            text: response.message,
                            icon: 'error'
                        });
                    }
                },
                error: function (xhr, status, error) {
                    Swal.close();
                    Swal.fire({
                        title: 'خطا',
                        text: 'خطا در ذخیره اطلاعات: ' + error,
                        icon: 'error'
                    });
                }
            });
        });

        // Remove invalid class on input
        $('input, select').on('input change', function () {
            $(this).removeClass('is-invalid');
        });

        // Delete provider
        $(document).on('click', '.delete-provider', function () {
            const id = $(this).data('id');
            const name = $(this).data('name');

            Swal.fire({
                title: 'حذف ارائه‌دهنده',
                text: `آیا از حذف ارائه‌دهنده "${name}" مطمئن هستید؟`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'بله، حذف شود',
                cancelButtonText: 'انصراف',
                confirmButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: baseUrl,
                        type: 'POST',
                        data: {
                            id: id,
                            action: 'delete_provider'
                        },
                        dataType: 'json',
                        beforeSend: function () {
                            Swal.fire({
                                title: 'در حال حذف...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },
                        success: function (response) {
                            Swal.close();
                            if (response.status === 'success') {
                                Swal.fire({
                                    title: 'موفقیت',
                                    text: response.message,
                                    icon: 'success'
                                });
                                providersTable.ajax.reload();
                                credentialsTable.ajax.reload();
                                loadProvidersForSelect();
                            } else {
                                Swal.fire({
                                    title: 'خطا',
                                    text: response.message,
                                    icon: 'error'
                                });
                            }
                        },
                        error: function () {
                            Swal.close();
                            Swal.fire({
                                title: 'خطا',
                                text: 'خطا در حذف ارائه‌دهنده',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        });

        // Show credential modal for adding
        $('button[data-bs-target="#credentialModal"]').click(function () {
            loadProvidersForSelect();
            $('#credentialForm')[0].reset();
            $('#credential_id').val('');
            $('#credentialModalLabel').text('افزودن اعتبارنامه جدید');
            $('#env, #credential_status').val('');
            $('#credentialModal').modal('show');
        });

        // Edit credential
        $(document).on('click', '.edit-credential', function () {
            const id = $(this).data('id');

            $.ajax({
                url: baseUrl + '?action=get_credential',
                type: 'GET',
                data: {
                    id: id
                },
                dataType: 'json',
                beforeSend: function () {
                    Swal.fire({
                        title: 'در حال بارگذاری...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function (response) {
                    Swal.close();
                    if (response.status === 'success') {
                        loadProvidersForSelect();

                        setTimeout(() => {
                            const credential = response.data;
                            $('#credential_id').val(credential.id);
                            $('#provider_id_select').val(credential.provider_id);
                            $('#env').val(credential.env);
                            $('#key_id').val(credential.key_id || '');
                            $('#api_key').val(credential.api_key || '');
                            $('#api_secret').val(credential.api_secret || '');
                            $('#bearer_token').val(credential.bearer_token || '');
                            $('#extra_json').val(credential.extra_json || '');
                            $('#credential_status').val(credential.status);

                            $('#credentialModalLabel').text('ویرایش اعتبارنامه');
                            $('#credentialModal').modal('show');
                        }, 500);
                    } else {
                        Swal.fire({
                            title: 'خطا',
                            text: response.message,
                            icon: 'error'
                        });
                    }
                },
                error: function () {
                    Swal.close();
                    Swal.fire({
                        title: 'خطا',
                        text: 'خطا در دریافت اطلاعات اعتبارنامه',
                        icon: 'error'
                    });
                }
            });
        });

        // Submit credential form
        $('#credentialForm').submit(function (e) {
            e.preventDefault();

            const formData = $(this).serialize();
            const action = $('#credential_id').val() ? 'update_credential' : 'add_credential';

            $.ajax({
                url: baseUrl,
                type: 'POST',
                data: formData + '&action=' + action,
                dataType: 'json',
                beforeSend: function () {
                    Swal.fire({
                        title: 'در حال ذخیره...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function (response) {
                    Swal.close();
                    if (response.status === 'success') {
                        Swal.fire({
                            title: 'موفقیت',
                            text: response.message,
                            icon: 'success'
                        }).then(() => {
                            $('#credentialModal').modal('hide');
                            credentialsTable.ajax.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'خطا',
                            text: response.message,
                            icon: 'error'
                        });
                    }
                },
                error: function (xhr, status, error) {
                    Swal.close();
                    Swal.fire({
                        title: 'خطا',
                        text: 'خطا در ذخیره اطلاعات: ' + error,
                        icon: 'error'
                    });
                }
            });
        });

        // Delete credential
        $(document).on('click', '.delete-credential', function () {
            const id = $(this).data('id');

            Swal.fire({
                title: 'حذف اعتبارنامه',
                text: 'آیا از حذف این اعتبارنامه مطمئن هستید؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'بله، حذف شود',
                cancelButtonText: 'انصراف',
                confirmButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: baseUrl,
                        type: 'POST',
                        data: {
                            id: id,
                            action: 'delete_credential'
                        },
                        dataType: 'json',
                        beforeSend: function () {
                            Swal.fire({
                                title: 'در حال حذف...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },
                        success: function (response) {
                            Swal.close();
                            if (response.status === 'success') {
                                Swal.fire({
                                    title: 'موفقیت',
                                    text: response.message,
                                    icon: 'success'
                                });
                                credentialsTable.ajax.reload();
                            } else {
                                Swal.fire({
                                    title: 'خطا',
                                    text: response.message,
                                    icon: 'error'
                                });
                            }
                        },
                        error: function () {
                            Swal.close();
                            Swal.fire({
                                title: 'خطا',
                                text: 'خطا در حذف اعتبارنامه',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        });

        // Auto-generate slug from name
        $('#provider_name').on('blur', function () {
            if (!$('#provider_slug').val()) {
                const name = $(this).val();
                const slug = name.toLowerCase()
                    .replace(/[^\w\u0600-\u06FF\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
                $('#provider_slug').val(slug);
            }
        });
    });
</script>