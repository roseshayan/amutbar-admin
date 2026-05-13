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
                <h1 class="page-title fw-medium fs-18 mb-2">مدیریت سرویس‌های احراز هویت</h1>
                <div class="">
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">ابزارها و تنظیمات</a></li>
                            <li class="breadcrumb-item active" aria-current="page">سرویس‌های احراز هویت</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="btn-list">
                <button class="btn btn-primary btn-wave" id="btnAddService">
                    <i class="ri-add-line align-middle"></i> افزودن سرویس
                </button>
            </div>
        </div>
        <!-- Page Header Close -->

        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">لیست سرویس‌ها</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="servicesTable" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>کد</th>
                                    <th>عنوان</th>
                                    <th>نقش</th>
                                    <th>ارائه‌دهنده</th>
                                    <th>Method</th>
                                    <th>Endpoint</th>
                                    <th>وضعیت</th>
                                    <th>اولویت</th>
                                    <th>عملیات</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="text-muted small mt-2">
                            نکته: برای api.ir کافی است endpoint_path را مانند <span style="direction:ltr">/api/sw1/ShahkarLite</span> وارد کنید.
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal افزودن/ویرایش سرویس -->
<div class="modal fade" id="serviceModal" tabindex="-1" aria-labelledby="serviceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="serviceModalLabel">افزودن سرویس</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="serviceForm">
                <div class="modal-body">
                    <input type="hidden" id="service_id" name="id">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="service_code">کد (code) *</label>
                                <input type="text" class="form-control" id="service_code" name="code" required maxlength="64" dir="ltr">
                                <small class="text-muted">نمونه: <span dir="ltr">shaHkar_lite</span></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="service_title">عنوان *</label>
                                <input type="text" class="form-control" id="service_title" name="title" required maxlength="96">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="service_description">توضیحات</label>
                        <input type="text" class="form-control" id="service_description" name="description" maxlength="255">
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="service_subject_kind">محدوده استفاده</label>
                                <select class="form-control" id="service_subject_kind" name="subject_kind">
                                    <option value="0">همه</option>
                                    <option value="1">فقط راننده</option>
                                    <option value="2">فقط باربری</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="service_provider_id">ارائه‌دهنده *</label>
                                <select class="form-control" id="service_provider_id" name="provider_id" required>
                                    <option value="">انتخاب کنید</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="service_is_active">وضعیت</label>
                                <select class="form-control" id="service_is_active" name="is_active">
                                    <option value="1">فعال</option>
                                    <option value="0">غیرفعال</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="service_http_method">HTTP Method</label>
                                <select class="form-control" id="service_http_method" name="http_method">
                                    <option value="POST">POST</option>
                                    <option value="GET">GET</option>
                                    <option value="PUT">PUT</option>
                                    <option value="PATCH">PATCH</option>
                                    <option value="DELETE">DELETE</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="service_sort_order">اولویت</label>
                                <input type="number" class="form-control" id="service_sort_order" name="sort_order" value="100" min="1" max="1000">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="service_endpoint_path">Endpoint Path *</label>
                                <input type="text" class="form-control" id="service_endpoint_path" name="endpoint_path" required maxlength="255" dir="ltr">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="service_config_json">config_json (Mapping/Required/Defaults)</label>
                        <textarea class="form-control" id="service_config_json" name="config_json" rows="6" dir="ltr" style="text-align:left"></textarea>
                        <div class="text-muted small mt-1" style="direction:ltr; text-align:left">
                            Example: {"required":["nationalCode","mobile"],"defaults":{"matchingThreshold":90}}
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

<?php
require_once "views/panel/footer.php";
?>

<script>
(function(){
    const modalEl = document.getElementById('serviceModal');
    const modal = new bootstrap.Modal(modalEl);

    const form = document.getElementById('serviceForm');
    const providerSelect = document.getElementById('service_provider_id');

    let table;

    function roleLabel(v){
        v = parseInt(v||0,10);
        if(v===1) return 'راننده';
        if(v===2) return 'باربری';
        return 'همه';
    }

    async function loadProviders(){
        const res = await fetch('ajax/identity-services.php?action=list_providers', {credentials:'same-origin'});
        const json = await res.json();
        providerSelect.innerHTML = '<option value="">انتخاب کنید</option>';
        if(json.ok && Array.isArray(json.items)){
            json.items.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.textContent = p.name;
                providerSelect.appendChild(opt);
            });
        }
    }

    function initTable(){
        table = $('#servicesTable').DataTable({
            ajax: 'ajax/identity-services.php?action=list_services',
            columns: [
                {data:'id'},
                {data:'code'},
                {data:'title'},
                {data:'subject_kind', render: (d)=> roleLabel(d)},
                {data:'provider_name', defaultContent:''},
                {data:'http_method'},
                {data:'endpoint_path', render: (d)=> `<span style="direction:ltr">${d||''}</span>`},
                {data:'is_active', render: (d)=> d==1?'<span class="badge bg-success">فعال</span>':'<span class="badge bg-secondary">غیرفعال</span>'},
                {data:'sort_order'},
                {data:null, orderable:false, render:(row)=>{
                    return `
                      <div class="btn-list">
                        <button class="btn btn-sm btn-primary btn-wave" data-act="edit" data-id="${row.id}">ویرایش</button>
                        <button class="btn btn-sm btn-danger btn-wave" data-act="del" data-id="${row.id}">حذف</button>
                      </div>
                    `;
                }}
            ],
            order: [[8,'asc'],[0,'desc']],
            language: {
                url: 'assets/vendor/datatables/fa.json'
            }
        });

        $('#servicesTable tbody').on('click', 'button', async function(){
            const act = this.getAttribute('data-act');
            const id = this.getAttribute('data-id');
            if(act==='edit'){
                await openEdit(parseInt(id,10));
            }
            if(act==='del'){
                await doDelete(parseInt(id,10));
            }
        });
    }

    function resetForm(){
        form.reset();
        document.getElementById('service_id').value = '';
        document.getElementById('service_config_json').value = '';
        document.getElementById('service_http_method').value = 'POST';
        document.getElementById('service_subject_kind').value = '0';
        document.getElementById('service_is_active').value = '1';
        document.getElementById('service_sort_order').value = '100';
    }

    async function openEdit(id){
        resetForm();
        const res = await fetch('ajax/identity-services.php?action=get_service&id='+id, {credentials:'same-origin'});
        const json = await res.json();
        if(!json.ok || !json.item){
            Swal.fire({title:'خطا', text: json.message || 'یافت نشد', icon:'error'});
            return;
        }
        const s = json.item;
        document.getElementById('serviceModalLabel').textContent = 'ویرایش سرویس';
        document.getElementById('service_id').value = s.id;
        document.getElementById('service_code').value = s.code || '';
        document.getElementById('service_title').value = s.title || '';
        document.getElementById('service_description').value = s.description || '';
        document.getElementById('service_subject_kind').value = String(s.subject_kind||0);
        document.getElementById('service_provider_id').value = String(s.provider_id||'');
        document.getElementById('service_http_method').value = (s.http_method || 'POST').toUpperCase();
        document.getElementById('service_endpoint_path').value = s.endpoint_path || '';
        document.getElementById('service_is_active').value = String(s.is_active||0);
        document.getElementById('service_sort_order').value = String(s.sort_order||100);
        document.getElementById('service_config_json').value = s.config_json ? JSON.stringify(JSON.parse(s.config_json), null, 2) : '';
        modal.show();
    }

    async function doDelete(id){
        const c = await Swal.fire({
            title:'حذف سرویس',
            text:'مطمئنی؟',
            icon:'warning',
            showCancelButton:true,
            confirmButtonText:'حذف',
            cancelButtonText:'لغو'
        });
        if(!c.isConfirmed) return;
        const fd = new FormData();
        fd.append('action','delete_service');
        fd.append('id', String(id));
        const res = await fetch('ajax/identity-services.php', {method:'POST', body:fd, credentials:'same-origin'});
        const json = await res.json();
        if(json.ok){
            Swal.fire({title:'انجام شد', text:'حذف شد', icon:'success'});
            table.ajax.reload(null,false);
        } else {
            Swal.fire({title:'خطا', text: json.message||'خطا', icon:'error'});
        }
    }

    document.getElementById('btnAddService').addEventListener('click', function(){
        resetForm();
        document.getElementById('serviceModalLabel').textContent = 'افزودن سرویس';
        modal.show();
    });

    form.addEventListener('submit', async function(e){
        e.preventDefault();
        const fd = new FormData(form);
        fd.append('action','save_service');

        // config_json اگر JSON معتبر باشد، همان ارسال می‌شود
        const res = await fetch('ajax/identity-services.php', {method:'POST', body:fd, credentials:'same-origin'});
        const json = await res.json();
        if(json.ok){
            modal.hide();
            Swal.fire({title:'ذخیره شد', text:'موفق', icon:'success'});
            table.ajax.reload(null,false);
        } else {
            Swal.fire({title:'خطا', text: json.message||'خطا', icon:'error'});
        }
    });

    (async function boot(){
        await loadProviders();
        initTable();
    })();
})();
</script>
