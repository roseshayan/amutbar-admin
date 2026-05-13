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
                <h1 class="page-title fw-medium fs-18 mb-2">تنظیمات سایت و اپلیکیشن</h1>
                <div class="">
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">ابزارها و تنظیمات</a></li>
                            <li class="breadcrumb-item active" aria-current="page">تنظیمات سایت/اپ</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="btn-list">
                <button class="btn btn-primary btn-wave" id="btnSaveSettings">
                    <i class="ri-save-3-line align-middle"></i> ذخیره تنظیمات
                </button>
            </div>
        </div>
        <!-- Page Header Close -->

        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">تنظیمات</div>
                    </div>
                    <div class="card-body">

                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="tab-general" data-bs-toggle="tab" data-bs-target="#pane-general" type="button" role="tab">عمومی</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-support" data-bs-toggle="tab" data-bs-target="#pane-support" type="button" role="tab">پشتیبانی</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-app" data-bs-toggle="tab" data-bs-target="#pane-app" type="button" role="tab">اپلیکیشن</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-maint" data-bs-toggle="tab" data-bs-target="#pane-maint" type="button" role="tab">نگهداری</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-verify" data-bs-toggle="tab" data-bs-target="#pane-verify" type="button" role="tab">احراز هویت</button>
                            </li>
                        </ul>

                        <div class="tab-content pt-3">
                            <!-- General -->
                            <div class="tab-pane fade show active" id="pane-general" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-lg-6">
                                        <label for="company_name" class="form-label">نام شرکت</label>
                                        <input type="text" class="form-control" id="company_name" maxlength="150">
                                        <div class="text-muted small mt-1">در اپلیکیشن و بخش‌هایی که نام شرکت نمایش داده می‌شود از این مقدار استفاده می‌شود.</div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="site_name" class="form-label">نام سایت</label>
                                        <input type="text" class="form-control" id="site_name" maxlength="100">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="site_url" class="form-label">آدرس سایت (Base URL)</label>
                                        <input type="url" class="form-control" id="site_url" dir="ltr" placeholder="https://example.com">
                                        <div class="text-muted small mt-1">برای ساخت URLهای کامل (لوگو/نمادک) در API استفاده می‌شود.</div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="form-label">لوگو</label>
                                        <div class="d-flex gap-3 align-items-center flex-wrap">
                                            <img id="logoPreview" src="" alt="logo" style="height:48px; border-radius:8px; display:none;">
                                            <input type="file" class="form-control" id="site_logo" accept="image/png,image/jpeg,image/webp">
                                            <button type="button" class="btn btn-secondary btn-wave" id="btnUploadLogo">آپلود لوگو</button>
                                        </div>
                                        <div class="text-muted small mt-1" id="logoPathText" style="direction:ltr; text-align:left"></div>
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="form-label">نمادک / Favicon</label>
                                        <div class="d-flex gap-3 align-items-center flex-wrap">
                                            <img id="faviconPreview" src="" alt="favicon" style="height:32px; width:32px; border-radius:8px; display:none;">
                                            <input type="file" class="form-control" id="site_favicon" accept="image/png,image/jpeg,image/webp,image/x-icon,image/svg+xml">
                                            <button type="button" class="btn btn-secondary btn-wave" id="btnUploadFavicon">آپلود نمادک</button>
                                        </div>
                                        <div class="text-muted small mt-1" id="faviconPathText" style="direction:ltr; text-align:left"></div>
                                    </div>

                                    <div class="col-12 mt-4">
                                        <div class="fw-semibold mb-2">لینک‌ها</div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="terms_url" class="form-label">لینک صفحه قوانین و مقررات</label>
                                        <input type="url" class="form-control" id="terms_url" dir="ltr" placeholder="https://example.com/terms">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="app_download_url" class="form-label">لینک صفحه دانلود اپلیکیشن</label>
                                        <input type="url" class="form-control" id="app_download_url" dir="ltr" placeholder="https://example.com/app">
                                    </div>
                                    <div class="col-12 mt-3">
                                        <label for="terms_text" class="form-label">متن قوانین و مقررات (برای نمایش داخل اپ)</label>
                                        <textarea class="form-control" id="terms_text" rows="6" placeholder="متن قوانین را اینجا وارد کنید..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Support -->
                            <div class="tab-pane fade" id="pane-support" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="support_phone" class="form-label">شماره تماس پشتیبانی</label>
                                        <input type="text" class="form-control" id="support_phone" dir="ltr">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="support_whatsapp" class="form-label">واتساپ</label>
                                        <input type="text" class="form-control" id="support_whatsapp" dir="ltr">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="support_telegram" class="form-label">تلگرام</label>
                                        <input type="text" class="form-control" id="support_telegram" dir="ltr">
                                    </div>
                                </div>
                            </div>

                            <!-- App -->
                            <div class="tab-pane fade" id="pane-app" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="fw-semibold mb-2">اندروید</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="android_latest" class="form-label">آخرین ورژن (version_code)</label>
                                        <input type="number" class="form-control" id="android_latest" min="0">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="android_min" class="form-label">حداقل ورژن مجاز</label>
                                        <input type="number" class="form-control" id="android_min" min="0">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="android_url" class="form-label">آدرس آپدیت</label>
                                        <input type="url" class="form-control" id="android_url" dir="ltr">
                                    </div>

                                    <div class="col-12 mt-4">
                                        <div class="fw-semibold mb-2">iOS</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="ios_latest" class="form-label">آخرین ورژن (build)</label>
                                        <input type="number" class="form-control" id="ios_latest" min="0">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="ios_min" class="form-label">حداقل ورژن مجاز</label>
                                        <input type="number" class="form-control" id="ios_min" min="0">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="ios_url" class="form-label">آدرس آپدیت</label>
                                        <input type="url" class="form-control" id="ios_url" dir="ltr">
                                    </div>
                                </div>
                            </div>

                            <!-- Maintenance -->
                            <div class="tab-pane fade" id="pane-maint" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="maint_enabled" class="form-label">حالت نگهداری</label>
                                        <select class="form-control" id="maint_enabled">
                                            <option value="0">خاموش</option>
                                            <option value="1">روشن</option>
                                        </select>
                                    </div>
                                    <div class="col-md-8">
                                        <label for="maint_message" class="form-label">پیام نگهداری</label>
                                        <input type="text" class="form-control" id="maint_message" maxlength="255">
                                    </div>
                                </div>
                            </div>

                            <!-- Verification -->
                            <div class="tab-pane fade" id="pane-verify" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="require_video" class="form-label">الزام احراز هویت ویدئویی در ثبت‌نام راننده</label>
                                        <select class="form-control" id="require_video">
                                            <option value="0">غیرفعال</option>
                                            <option value="1">فعال</option>
                                        </select>
                                        <div class="text-muted small mt-1">اگر فعال باشد، راننده بدون تایید ویدئویی نمی‌تواند ثبت‌نام را کامل کند.</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="video_max_seconds" class="form-label">حداکثر زمان ویدئو (ثانیه)</label>
                                        <input type="number" class="form-control" id="video_max_seconds" min="1" max="30">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="video_max_mb" class="form-label">حداکثر حجم ویدئو (MB)</label>
                                        <input type="number" class="form-control" id="video_max_mb" min="1" max="20">
                                        <div class="text-muted small mt-1">توجه: Base64 حدود 33٪ بزرگ‌تر می‌شود؛ بهتر است فایل کمتر از 4MB باشد.</div>
                                    </div>

                                    <div class="col-12 mt-4">
                                        <div class="fw-semibold mb-2">متن جمله (Speech Text)</div>
                                    </div>
                                    <div class="col-12">
                                        <textarea class="form-control" id="video_phrase_template" rows="3" placeholder="اینجانب {full_name} با قوانین {company_name} موافقت می‌کنم."></textarea>
                                        <div class="text-muted small mt-1">از {full_name} و {company_name} می‌توانید استفاده کنید.</div>
                                    </div>

                                    <div class="col-12 mt-4">
                                        <div class="fw-semibold mb-2">راهنمای کاربر</div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="video_guide_text" class="form-label">راهنمای متنی</label>
                                        <textarea class="form-control" id="video_guide_text" rows="4"></textarea>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="video_guide_url" class="form-label">لینک ویدئوی آموزشی</label>
                                        <input type="url" class="form-control" id="video_guide_url" dir="ltr" placeholder="https://...">
                                    </div>

                                    <div class="col-12 mt-4">
                                        <div class="fw-semibold mb-2">آستانه‌ها (API.ir VideoVerify)</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="liveness_threshold" class="form-label">حد آستانه زنده‌سنجی</label>
                                        <input type="number" class="form-control" id="liveness_threshold" min="0" max="100">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="matching_threshold" class="form-label">حد آستانه تطبیق چهره</label>
                                        <input type="number" class="form-control" id="matching_threshold" min="0" max="100">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="speech_threshold" class="form-label">حد آستانه تطبیق گفتار</label>
                                        <input type="number" class="form-control" id="speech_threshold" min="0" max="100">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php
require_once "views/panel/footer.php";
?>

<script>
(function(){
    const els = {
        company_name: document.getElementById('company_name'),
        site_name: document.getElementById('site_name'),
        site_url: document.getElementById('site_url'),
        logo: document.getElementById('site_logo'),
        logoPreview: document.getElementById('logoPreview'),
        logoPathText: document.getElementById('logoPathText'),
        favicon: document.getElementById('site_favicon'),
        faviconPreview: document.getElementById('faviconPreview'),
        faviconPathText: document.getElementById('faviconPathText'),
        terms_url: document.getElementById('terms_url'),
        app_download_url: document.getElementById('app_download_url'),
        support_phone: document.getElementById('support_phone'),
        support_whatsapp: document.getElementById('support_whatsapp'),
        support_telegram: document.getElementById('support_telegram'),
        android_latest: document.getElementById('android_latest'),
        android_min: document.getElementById('android_min'),
        android_url: document.getElementById('android_url'),
        terms_text: document.getElementById('terms_text'),
        ios_latest: document.getElementById('ios_latest'),
        ios_min: document.getElementById('ios_min'),
        ios_url: document.getElementById('ios_url'),
        maint_enabled: document.getElementById('maint_enabled'),
        maint_message: document.getElementById('maint_message'),

        // verification
        require_video: document.getElementById('require_video'),
        video_max_seconds: document.getElementById('video_max_seconds'),
        video_max_mb: document.getElementById('video_max_mb'),
        video_phrase_template: document.getElementById('video_phrase_template'),
        video_guide_text: document.getElementById('video_guide_text'),
        video_guide_url: document.getElementById('video_guide_url'),
        liveness_threshold: document.getElementById('liveness_threshold'),
        matching_threshold: document.getElementById('matching_threshold'),
        speech_threshold: document.getElementById('speech_threshold')
    };

    function setLogo(path){
        if(path){
            els.logoPathText.textContent = path;
            els.logoPreview.src = path;
            els.logoPreview.style.display = 'inline-block';
        } else {
            els.logoPathText.textContent = '';
            els.logoPreview.style.display = 'none';
        }
    }

    function setFavicon(path){
        if(path){
            els.faviconPathText.textContent = path;
            els.faviconPreview.src = path;
            els.faviconPreview.style.display = 'inline-block';
        } else {
            els.faviconPathText.textContent = '';
            els.faviconPreview.style.display = 'none';
        }
    }

    async function load(){
        const res = await fetch('ajax/site-settings.php?action=get', {credentials:'same-origin'});
        const json = await res.json();
        if(!json.ok){
            Swal.fire({title:'خطا', text: json.message||'خطا', icon:'error'});
            return;
        }
        const it = json.items || {};
        els.company_name.value = it['company.name'] || '';
        els.site_name.value = it['site.name'] || '';
        els.site_url.value = it['site.url'] || '';
        setLogo(it['site.logo_path'] || '');
        setFavicon(it['site.favicon_path'] || '');
        els.terms_url.value = it['links.terms_url'] || '';
        els.app_download_url.value = it['links.app_download_url'] || '';
        els.support_phone.value = it['support.phone'] || '';
        els.support_whatsapp.value = it['support.whatsapp'] || '';
        els.support_telegram.value = it['support.telegram'] || '';
        els.android_latest.value = it['app.android.latest_version_code'] || '';
        els.android_min.value = it['app.android.min_supported_code'] || '';
        els.android_url.value = it['app.android.update_url'] || '';
        els.terms_text.value = it['links.terms_text'] || '';
        els.ios_latest.value = it['app.ios.latest_version_code'] || '';
        els.ios_min.value = it['app.ios.min_supported_code'] || '';
        els.ios_url.value = it['app.ios.update_url'] || '';
        els.maint_enabled.value = it['maintenance.enabled'] || '0';
        els.maint_message.value = it['maintenance.message'] || '';

        els.require_video.value = it['onboarding.require_verification_video'] || '0';
        els.video_phrase_template.value = it['verification.video_phrase_template'] || 'اینجانب {full_name} با قوانین {company_name} موافقت می‌کنم.';
        els.video_guide_text.value = it['verification.video_guide_text'] || '';
        els.video_guide_url.value = it['verification.video_guide_url'] || '';
        els.video_max_seconds.value = it['verification.video_max_seconds'] || '10';
        els.video_max_mb.value = it['verification.video_max_mb'] || '5';
        els.liveness_threshold.value = it['verification.api_ir.liveness_threshold'] || '80';
        els.matching_threshold.value = it['verification.api_ir.matching_threshold'] || '90';
        els.speech_threshold.value = it['verification.api_ir.speech_threshold'] || '50';
    }

    async function save(){
        const items = {
            'company.name': els.company_name.value,
            'site.name': els.site_name.value,
            'site.url': els.site_url.value,
            'links.terms_url': els.terms_url.value,
            'links.app_download_url': els.app_download_url.value,
            'support.phone': els.support_phone.value,
            'support.whatsapp': els.support_whatsapp.value,
            'support.telegram': els.support_telegram.value,
            'app.android.latest_version_code': els.android_latest.value,
            'app.android.min_supported_code': els.android_min.value,
            'app.android.update_url': els.android_url.value,
            'links.terms_text': els.terms_text.value,
            'app.ios.latest_version_code': els.ios_latest.value,
            'app.ios.min_supported_code': els.ios_min.value,
            'app.ios.update_url': els.ios_url.value,
            'maintenance.enabled': els.maint_enabled.value,
            'maintenance.message': els.maint_message.value,

            'onboarding.require_verification_video': els.require_video.value,
            'verification.video_phrase_template': els.video_phrase_template.value,
            'verification.video_guide_text': els.video_guide_text.value,
            'verification.video_guide_url': els.video_guide_url.value,
            'verification.video_max_seconds': els.video_max_seconds.value,
            'verification.video_max_mb': els.video_max_mb.value,
            'verification.api_ir.liveness_threshold': els.liveness_threshold.value,
            'verification.api_ir.matching_threshold': els.matching_threshold.value,
            'verification.api_ir.speech_threshold': els.speech_threshold.value
        };
        const fd = new FormData();
        fd.append('action','save');
        fd.append('items', JSON.stringify(items));
        const res = await fetch('ajax/site-settings.php', {method:'POST', body:fd, credentials:'same-origin'});
        const json = await res.json();
        if(json.ok){
            Swal.fire({title:'ذخیره شد', text:'موفق', icon:'success'});
        } else {
            Swal.fire({title:'خطا', text: json.message||'خطا', icon:'error'});
        }
    }

    async function uploadLogo(){
        if(!els.logo.files || !els.logo.files[0]){
            Swal.fire({title:'هشدار', text:'فایل لوگو را انتخاب کن', icon:'warning'});
            return;
        }
        const fd = new FormData();
        fd.append('action','upload_logo');
        fd.append('logo', els.logo.files[0]);
        const res = await fetch('ajax/site-settings.php', {method:'POST', body:fd, credentials:'same-origin'});
        const json = await res.json();
        if(json.ok){
            setLogo(json.path || '');
            Swal.fire({title:'آپلود شد', text:'لوگو بروزرسانی شد', icon:'success'});
        } else {
            Swal.fire({title:'خطا', text: json.message||'خطا', icon:'error'});
        }
    }

    async function uploadFavicon(){
        if(!els.favicon.files || !els.favicon.files[0]){
            Swal.fire({title:'هشدار', text:'فایل نمادک را انتخاب کن', icon:'warning'});
            return;
        }
        const fd = new FormData();
        fd.append('action','upload_favicon');
        fd.append('favicon', els.favicon.files[0]);
        const res = await fetch('ajax/site-settings.php', {method:'POST', body:fd, credentials:'same-origin'});
        const json = await res.json();
        if(json.ok){
            setFavicon(json.path || '');
            Swal.fire({title:'آپلود شد', text:'نمادک بروزرسانی شد', icon:'success'});
        } else {
            Swal.fire({title:'خطا', text: json.message||'خطا', icon:'error'});
        }
    }

    document.getElementById('btnSaveSettings').addEventListener('click', save);
    document.getElementById('btnUploadLogo').addEventListener('click', uploadLogo);
    document.getElementById('btnUploadFavicon').addEventListener('click', uploadFavicon);

    load();
})();
</script>
