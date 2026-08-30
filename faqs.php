<?php
require_once __DIR__ . '/includes/init.php';
require_admin();
require_once 'views/panel/header.php';
require_once 'views/panel/sidebar.php';
?>

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-2">مدیریت سوالات متداول</h1>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="dashboard.php">داشبورد</a></li>
                        <li class="breadcrumb-item active" aria-current="page">سوالات متداول</li>
                    </ol>
                </nav>
            </div>
            <div class="btn-list">
                <button class="btn btn-outline-primary btn-wave" type="button" onclick="openCategoryModal()">
                    <i class="ri-folder-add-line align-middle"></i> دسته‌بندی جدید
                </button>
                <button class="btn btn-primary btn-wave" type="button" onclick="openFaqModal()">
                    <i class="ri-question-answer-line align-middle"></i> سوال جدید
                </button>
            </div>
        </div>

        <div class="alert alert-primary-transparent d-flex align-items-start gap-2" role="alert">
            <i class="ri-information-line fs-20 mt-1"></i>
            <div>
                هر اپلیکیشن سوالات مستقل خودش را دریافت می‌کند. برای هر پاسخ می‌توانید علاوه بر متن، یک لینک، تصویر و ویدئو (فایل یا آدرس اینترنتی) تعریف کنید.
            </div>
        </div>

        <div class="card custom-card">
            <div class="card-header pb-0">
                <ul class="nav nav-tabs tab-style-1" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#faq-items-pane" type="button" role="tab">
                            <i class="ri-question-line me-1"></i> سوال‌ها
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#faq-categories-pane" type="button" role="tab">
                            <i class="ri-folder-3-line me-1"></i> دسته‌بندی‌ها
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="faq-items-pane" role="tabpanel">
                        <div class="table-responsive">
                            <table id="faqItemsTable" class="table table-bordered table-striped w-100">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>اپلیکیشن</th>
                                    <th>دسته‌بندی</th>
                                    <th>سوال / خلاصه پاسخ</th>
                                    <th>رسانه</th>
                                    <th>ترتیب</th>
                                    <th>وضعیت</th>
                                    <th>عملیات</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="faq-categories-pane" role="tabpanel">
                        <div class="table-responsive">
                            <table id="faqCategoriesTable" class="table table-bordered table-striped w-100">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>اپلیکیشن</th>
                                    <th>عنوان / توضیح</th>
                                    <th>آیکن</th>
                                    <th>ترتیب</th>
                                    <th>تعداد سوال</th>
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

<div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="categoryModalTitle">دسته‌بندی جدید</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
            </div>
            <div class="modal-body">
                <form id="categoryForm">
                    <input type="hidden" name="id" id="category_id" value="0">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">اپلیکیشن هدف <span class="text-danger">*</span></label>
                            <select class="form-select" name="target_app_id" id="category_app" required>
                                <option value="1">اپلیکیشن رانندگان</option>
                                <option value="2">اپلیکیشن اعلام بار / صاحبان بار</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">عنوان دسته‌بندی <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" id="category_title" maxlength="160" required placeholder="مثال: نصب و راه‌اندازی">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">توضیح کوتاه</label>
                            <input type="text" class="form-control" name="description" id="category_description" maxlength="255" placeholder="مثال: راهنمای دانلود، نصب و بروزرسانی برنامه">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">آیکن</label>
                            <select class="form-select" name="icon_key" id="category_icon">
                                <option value="install">نصب</option>
                                <option value="auth">ورود و ثبت‌نام</option>
                                <option value="search_load">پیداکردن بار</option>
                                <option value="request">درخواست حمل</option>
                                <option value="history">تاریخچه</option>
                                <option value="transport">حمل بار</option>
                                <option value="insurance">بیمه</option>
                                <option value="identity">احراز هویت</option>
                                <option value="support">پشتیبانی</option>
                                <option value="general" selected>عمومی</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">ترتیب نمایش</label>
                            <input type="number" class="form-control" name="sort_order" id="category_sort" min="0" value="100">
                            <div class="form-text">عدد کمتر، بالاتر نمایش داده می‌شود.</div>
                        </div>
                        <div class="col-md-8 d-flex align-items-center">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" name="is_active" id="category_active" checked>
                                <label class="form-check-label" for="category_active">این دسته‌بندی در اپ نمایش داده شود</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">انصراف</button>
                <button type="button" class="btn btn-primary" id="saveCategoryBtn">ذخیره دسته‌بندی</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="faqModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="faqModalTitle">سوال جدید</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
            </div>
            <div class="modal-body">
                <form id="faqForm" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="faq_id" value="0">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">اپلیکیشن هدف <span class="text-danger">*</span></label>
                            <select class="form-select" id="faq_app" required>
                                <option value="1">اپلیکیشن رانندگان</option>
                                <option value="2">اپلیکیشن اعلام بار / صاحبان بار</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">دسته‌بندی <span class="text-danger">*</span></label>
                            <select class="form-select" name="category_id" id="faq_category" required></select>
                            <div class="form-text">اگر لیست خالی است، ابتدا یک دسته‌بندی برای همین اپ بسازید.</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">ترتیب نمایش</label>
                            <input type="number" class="form-control" name="sort_order" id="faq_sort" min="0" value="100">
                        </div>

                        <div class="col-12">
                            <label class="form-label">سوال <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="question" id="faq_question" maxlength="255" required placeholder="مثال: چگونه برنامه را روی اندروید نصب کنم؟">
                        </div>
                        <div class="col-12">
                            <label class="form-label">پاسخ <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="answer" id="faq_answer" rows="7" required placeholder="پاسخ کامل را بنویسید. خط‌های جدید در اپ حفظ می‌شوند."></textarea>
                        </div>

                        <div class="col-12"><hr class="my-1"><div class="fw-semibold">لینک اختیاری</div></div>
                        <div class="col-md-4">
                            <label class="form-label">متن دکمه لینک</label>
                            <input type="text" class="form-control" name="link_label" id="faq_link_label" maxlength="120" placeholder="مثال: دانلود مستقیم برنامه">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">آدرس لینک</label>
                            <input type="url" class="form-control" name="link_url" id="faq_link_url" dir="ltr" placeholder="https://...">
                        </div>

                        <div class="col-12"><hr class="my-1"><div class="fw-semibold">تصویر اختیاری</div></div>
                        <div class="col-md-8">
                            <label class="form-label">فایل تصویر</label>
                            <input type="file" class="form-control" name="image" id="faq_image" accept="image/jpeg,image/png,image/webp,image/gif">
                            <div class="form-text">JPG، PNG، WebP یا GIF تا ۵ مگابایت.</div>
                        </div>
                        <div class="col-md-4" id="faqImageExistingWrap" style="display:none">
                            <label class="form-label d-block">تصویر فعلی</label>
                            <a id="faqImageExistingLink" href="#" target="_blank" rel="noopener" class="btn btn-sm btn-outline-info me-2">مشاهده تصویر</a>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="remove_image" id="faq_remove_image">
                                <label class="form-check-label" for="faq_remove_image">حذف تصویر فعلی</label>
                            </div>
                        </div>

                        <div class="col-12"><hr class="my-1"><div class="fw-semibold">ویدئو اختیاری</div></div>
                        <div class="col-md-6">
                            <label class="form-label">آپلود فایل ویدئو</label>
                            <input type="file" class="form-control" name="video" id="faq_video" accept="video/mp4,video/webm,video/quicktime">
                            <div class="form-text">MP4، WebM یا MOV تا ۴۰ مگابایت. فایل آپلودی بر آدرس اینترنتی اولویت دارد.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">یا آدرس اینترنتی ویدئو</label>
                            <input type="url" class="form-control" name="video_url" id="faq_video_url" dir="ltr" placeholder="https://...">
                        </div>
                        <div class="col-12" id="faqVideoExistingWrap" style="display:none">
                            <a id="faqVideoExistingLink" href="#" target="_blank" rel="noopener" class="btn btn-sm btn-outline-danger me-2">مشاهده ویدئوی فعلی</a>
                            <div class="form-check d-inline-block">
                                <input class="form-check-input" type="checkbox" name="remove_video" id="faq_remove_video">
                                <label class="form-check-label" for="faq_remove_video">حذف فایل ویدئوی فعلی</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_active" id="faq_active" checked>
                                <label class="form-check-label" for="faq_active">این سوال در اپ نمایش داده شود</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">انصراف</button>
                <button type="button" class="btn btn-primary" id="saveFaqBtn">ذخیره سوال</button>
            </div>
        </div>
    </div>
</div>

<script>
const BASE_URL = <?= json_encode(base_url(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
let faqItemsDt;
let faqCategoriesDt;

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>'"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[c]));
}

function absoluteMediaUrl(key) {
    const raw = String(key || '').trim();
    if (!raw) return '';
    if (/^https?:\/\//i.test(raw)) return raw;
    return `${window.location.origin}${BASE_URL}/${raw.replace(/^\/+/, '')}`;
}

async function apiJson(url, options = {}) {
    const res = await fetch(url, options);
    const data = await res.json().catch(() => ({}));
    if (!res.ok || data.ok === false) throw new Error(data.message || 'خطا در ارتباط با سرور');
    return data;
}

$(function () {
    faqItemsDt = $('#faqItemsTable').DataTable({
        ajax: `${BASE_URL}/ajax/faqs.php?action=items_list`,
        columns: [
            {data:'id'}, {data:'app'}, {data:'category'}, {data:'question'},
            {data:'media', orderable:false}, {data:'sort_order'}, {data:'status'}, {data:'actions', orderable:false}
        ],
        language: {url:'assets/vendor/datatables/fa.json'},
        order: [[0,'desc']],
        pageLength: 25,
    });

    faqCategoriesDt = $('#faqCategoriesTable').DataTable({
        ajax: `${BASE_URL}/ajax/faqs.php?action=categories_list`,
        columns: [
            {data:'id'}, {data:'app'}, {data:'title'}, {data:'icon_key'},
            {data:'sort_order'}, {data:'items_count'}, {data:'status'}, {data:'actions', orderable:false}
        ],
        language: {url:'assets/vendor/datatables/fa.json'},
        order: [[0,'desc']],
        pageLength: 25,
    });

    $('#faq_app').on('change', () => loadCategoryOptions($('#faq_app').val()));
    $('#saveCategoryBtn').on('click', saveCategory);
    $('#saveFaqBtn').on('click', saveFaq);
});

function resetCategoryForm() {
    const form = document.getElementById('categoryForm');
    form.reset();
    $('#category_id').val('0');
    $('#category_app').val('1');
    $('#category_icon').val('general');
    $('#category_sort').val('100');
    $('#category_active').prop('checked', true);
}

function openCategoryModal() {
    resetCategoryForm();
    $('#categoryModalTitle').text('دسته‌بندی جدید');
    bootstrap.Modal.getOrCreateInstance(document.getElementById('categoryModal')).show();
}

async function editCategory(id) {
    try {
        const data = await apiJson(`${BASE_URL}/ajax/faqs.php?action=category_get&id=${encodeURIComponent(id)}`);
        const item = data.item || {};
        resetCategoryForm();
        $('#category_id').val(item.id || 0);
        $('#category_app').val(String(item.target_app_id || 1));
        $('#category_title').val(item.title || '');
        $('#category_description').val(item.description || '');
        $('#category_icon').val(item.icon_key || 'general');
        $('#category_sort').val(item.sort_order ?? 100);
        $('#category_active').prop('checked', Number(item.is_active) === 1);
        $('#categoryModalTitle').text('ویرایش دسته‌بندی');
        bootstrap.Modal.getOrCreateInstance(document.getElementById('categoryModal')).show();
    } catch (e) {
        Swal.fire('خطا', e.message, 'error');
    }
}

async function saveCategory() {
    const form = document.getElementById('categoryForm');
    if (!form.checkValidity()) { form.reportValidity(); return; }
    const btn = $('#saveCategoryBtn').prop('disabled', true).text('در حال ذخیره...');
    const fd = new FormData(form);
    fd.append('action', 'category_save');
    try {
        const data = await apiJson(`${BASE_URL}/ajax/faqs.php`, {method:'POST', body:fd});
        bootstrap.Modal.getOrCreateInstance(document.getElementById('categoryModal')).hide();
        faqCategoriesDt.ajax.reload(null, false);
        faqItemsDt.ajax.reload(null, false);
        Swal.fire({icon:'success', title:'ذخیره شد', text:data.message || 'دسته‌بندی ذخیره شد', timer:1600, showConfirmButton:false});
    } catch (e) {
        Swal.fire('خطا', e.message, 'error');
    } finally {
        btn.prop('disabled', false).text('ذخیره دسته‌بندی');
    }
}

function deleteCategory(id) {
    Swal.fire({
        icon:'warning', title:'حذف دسته‌بندی؟',
        text:'تمام سوال‌ها و رسانه‌های این دسته‌بندی نیز حذف می‌شوند.',
        showCancelButton:true, confirmButtonText:'بله، حذف شود', cancelButtonText:'انصراف', confirmButtonColor:'#d33'
    }).then(async result => {
        if (!result.isConfirmed) return;
        try {
            const fd = new FormData(); fd.append('action','category_delete'); fd.append('id', String(id));
            await apiJson(`${BASE_URL}/ajax/faqs.php`, {method:'POST', body:fd});
            faqCategoriesDt.ajax.reload(null, false); faqItemsDt.ajax.reload(null, false);
            Swal.fire({icon:'success', title:'حذف شد', timer:1400, showConfirmButton:false});
        } catch (e) { Swal.fire('خطا', e.message, 'error'); }
    });
}

async function loadCategoryOptions(appId, selectedId = null) {
    const sel = $('#faq_category');
    sel.prop('disabled', true).html('<option value="">در حال دریافت...</option>');
    try {
        const data = await apiJson(`${BASE_URL}/ajax/faqs.php?action=category_options&target_app_id=${encodeURIComponent(appId)}`);
        const items = data.items || [];
        if (!items.length) {
            sel.html('<option value="">برای این اپ دسته‌بندی تعریف نشده است</option>');
        } else {
            sel.html('<option value="">انتخاب کنید</option>' + items.map(i => `<option value="${Number(i.id)}">${escapeHtml(i.title)}</option>`).join(''));
            if (selectedId) sel.val(String(selectedId));
        }
    } catch (e) {
        sel.html('<option value="">خطا در دریافت دسته‌بندی‌ها</option>');
    } finally {
        sel.prop('disabled', false);
    }
}

function resetFaqForm() {
    const form = document.getElementById('faqForm');
    form.reset();
    $('#faq_id').val('0');
    $('#faq_app').val('1');
    $('#faq_sort').val('100');
    $('#faq_active').prop('checked', true);
    $('#faq_remove_image,#faq_remove_video').prop('checked', false);
    $('#faqImageExistingWrap,#faqVideoExistingWrap').hide();
    $('#faqImageExistingLink,#faqVideoExistingLink').attr('href','#');
}

async function openFaqModal() {
    resetFaqForm();
    $('#faqModalTitle').text('سوال جدید');
    await loadCategoryOptions('1');
    bootstrap.Modal.getOrCreateInstance(document.getElementById('faqModal')).show();
}

async function editFaq(id) {
    try {
        const data = await apiJson(`${BASE_URL}/ajax/faqs.php?action=item_get&id=${encodeURIComponent(id)}`);
        const item = data.item || {};
        resetFaqForm();
        $('#faq_id').val(item.id || 0);
        $('#faq_app').val(String(item.target_app_id || 1));
        await loadCategoryOptions(item.target_app_id || 1, item.category_id);
        $('#faq_question').val(item.question || '');
        $('#faq_answer').val(item.answer || '');
        $('#faq_link_label').val(item.link_label || '');
        $('#faq_link_url').val(item.link_url || '');
        $('#faq_video_url').val(item.video_url || '');
        $('#faq_sort').val(item.sort_order ?? 100);
        $('#faq_active').prop('checked', Number(item.is_active) === 1);

        if (item.image_key) {
            $('#faqImageExistingWrap').show();
            $('#faqImageExistingLink').attr('href', absoluteMediaUrl(item.image_key));
        }
        if (item.video_key) {
            $('#faqVideoExistingWrap').show();
            $('#faqVideoExistingLink').attr('href', absoluteMediaUrl(item.video_key));
        }
        $('#faqModalTitle').text('ویرایش سوال');
        bootstrap.Modal.getOrCreateInstance(document.getElementById('faqModal')).show();
    } catch (e) {
        Swal.fire('خطا', e.message, 'error');
    }
}

async function saveFaq() {
    const form = document.getElementById('faqForm');
    if (!form.checkValidity()) { form.reportValidity(); return; }
    if (!$('#faq_category').val()) {
        Swal.fire('دسته‌بندی لازم است', 'ابتدا دسته‌بندی این سوال را انتخاب کنید.', 'warning'); return;
    }
    const btn = $('#saveFaqBtn').prop('disabled', true).text('در حال ذخیره...');
    const fd = new FormData(form);
    fd.append('action','item_save');
    try {
        const data = await apiJson(`${BASE_URL}/ajax/faqs.php`, {method:'POST', body:fd});
        bootstrap.Modal.getOrCreateInstance(document.getElementById('faqModal')).hide();
        faqItemsDt.ajax.reload(null, false); faqCategoriesDt.ajax.reload(null, false);
        Swal.fire({icon:'success', title:'ذخیره شد', text:data.message || 'سوال ذخیره شد', timer:1600, showConfirmButton:false});
    } catch (e) {
        Swal.fire('خطا', e.message, 'error');
    } finally {
        btn.prop('disabled', false).text('ذخیره سوال');
    }
}

function deleteFaq(id) {
    Swal.fire({
        icon:'warning', title:'حذف سوال؟', text:'رسانه‌های آپلودشده این سوال نیز حذف می‌شوند.',
        showCancelButton:true, confirmButtonText:'بله، حذف شود', cancelButtonText:'انصراف', confirmButtonColor:'#d33'
    }).then(async result => {
        if (!result.isConfirmed) return;
        try {
            const fd = new FormData(); fd.append('action','item_delete'); fd.append('id', String(id));
            await apiJson(`${BASE_URL}/ajax/faqs.php`, {method:'POST', body:fd});
            faqItemsDt.ajax.reload(null, false); faqCategoriesDt.ajax.reload(null, false);
            Swal.fire({icon:'success', title:'حذف شد', timer:1400, showConfirmButton:false});
        } catch (e) { Swal.fire('خطا', e.message, 'error'); }
    });
}
</script>

<?php require_once 'views/panel/footer.php'; ?>
