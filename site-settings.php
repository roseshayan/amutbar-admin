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
                                <button class="nav-link" id="tab-sms" data-bs-toggle="tab" data-bs-target="#pane-sms" type="button" role="tab">پنل پیامک</button>
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
                                        <div class="input-group">
                                            <input type="url" class="form-control" id="terms_url" dir="ltr" placeholder="https://example.com/terms.php">
                                            <button class="btn btn-outline-secondary" type="button" id="btnSuggestTermsUrl" title="استفاده از صفحه داخلی">خودکار</button>
                                            <button class="btn btn-outline-info" type="button" id="btnOpenTermsUrl" title="باز کردن صفحه"><i class="ri-external-link-line"></i></button>
                                        </div>
                                        <div class="text-muted small mt-1">صفحه <code>terms.php</code> متن زیر را به‌صورت پویا نمایش می‌دهد.</div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="app_download_url" class="form-label">لینک صفحه دانلود اپلیکیشن</label>
                                        <div class="input-group">
                                            <input type="url" class="form-control" id="app_download_url" dir="ltr" placeholder="https://example.com/app.php">
                                            <button class="btn btn-outline-secondary" type="button" id="btnSuggestAppUrl" title="استفاده از صفحه داخلی">خودکار</button>
                                            <button class="btn btn-outline-info" type="button" id="btnOpenAppUrl" title="باز کردن صفحه"><i class="ri-external-link-line"></i></button>
                                        </div>
                                        <div class="text-muted small mt-1">لینک‌های دانلود Android/iOS این لندینگ از همین تنظیمات اپ خوانده می‌شوند.</div>
                                    </div>
                                    <div class="col-12 mt-3">
                                        <label for="terms_text" class="form-label">متن قوانین و مقررات</label>
                                        <textarea class="form-control" id="terms_text" rows="12" placeholder="## عنوان بخش اول&#10;متن قوانین...&#10;&#10;## عنوان بخش دوم&#10;متن بخش دوم..."></textarea>
                                        <div class="text-muted small mt-1">برای ساخت بخش‌های جداگانه در وب و اپ، عنوان هر بخش را با <code>##</code> شروع کنید؛ مثال: <code>## حریم خصوصی</code>.</div>
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

                                    <div class="col-12 mt-5">
                                        <hr>
                                        <div class="fw-semibold mb-2">اپ اعلام بار صاحبان بار — Android</div>
                                        <div class="text-muted small">اگر خالی بماند، تنظیمات اپ رانندگان به‌عنوان مقدار جایگزین استفاده می‌شود.</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="cargo_android_latest" class="form-label">آخرین ورژن (version_code)</label>
                                        <input type="number" class="form-control" id="cargo_android_latest" min="0">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="cargo_android_min" class="form-label">حداقل ورژن مجاز</label>
                                        <input type="number" class="form-control" id="cargo_android_min" min="0">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="cargo_android_url" class="form-label">آدرس آپدیت</label>
                                        <input type="url" class="form-control" id="cargo_android_url" dir="ltr">
                                    </div>

                                    <div class="col-12 mt-4">
                                        <div class="fw-semibold mb-2">اپ اعلام بار صاحبان بار — iOS</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="cargo_ios_latest" class="form-label">آخرین ورژن (build)</label>
                                        <input type="number" class="form-control" id="cargo_ios_latest" min="0">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="cargo_ios_min" class="form-label">حداقل ورژن مجاز</label>
                                        <input type="number" class="form-control" id="cargo_ios_min" min="0">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="cargo_ios_url" class="form-label">آدرس آپدیت</label>
                                        <input type="url" class="form-control" id="cargo_ios_url" dir="ltr">
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
                                    <div class="col-md-6 mt-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="require_national_serial">
                                            <label class="form-check-label" for="require_national_serial">
                                                دریافت «سریال کارت ملی» در احراز هویت رانندگان الزامی باشد
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="require_video" class="form-label">الزام احراز هویت ویدئویی در ثبت‌نام راننده</label>
                                        <select class="form-control" id="require_video">
                                            <option value="0">غیرفعال</option>
                                            <option value="1">فعال</option>
                                        </select>
                                        <div class="text-muted small mt-1">اگر فعال باشد، راننده بدون تایید ویدئویی نمی‌تواند ثبت‌نام را کامل کند.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="video_max_seconds" class="form-label">حداکثر زمان ویدئو (ثانیه)</label>
                                        <input type="number" class="form-control" id="video_max_seconds" min="1" max="30">
                                    </div>
                                    <div class="col-md-6">
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

                            <div class="tab-pane fade" id="pane-sms" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="alert alert-secondary d-flex align-items-center justify-content-between p-4 shadow-sm border-0">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                                    <i class="ri-message-3-line fs-24"></i>
                                                </div>
                                                <div>
                                                    <div class="text-muted mb-1 fs-13">موجودی پیامک‌های پنل (ملی پیامک)</div>
                                                    <div class="fs-18 fw-bold text-dark">
                                                        <span id="sms_credit_amount" class="fs-24 text-primary">در حال دریافت...</span>
                                                        <span class="fs-14 fw-normal text-muted ms-1">پیامک</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-primary btn-wave" id="btnRefreshCredit">
                                                <i class="ri-refresh-line align-middle me-1"></i> بروزرسانی موجودی
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-4">
                                        <div class="fw-semibold mb-3 border-bottom pb-2">آزمایش ارسال پیامک</div>
                                        <div class="row g-3 align-items-end bg-light p-3 rounded border">
                                            <div class="col-md-4">
                                                <label for="test_sms_from" class="form-label">شماره خط فرستنده مثال : 9982003626, 50002710054653</label>
                                                <input type="text" class="form-control" id="test_sms_from" dir="ltr" placeholder="مثال: 9982003626, 50002710054653">
                                            </div>
                                            <div class="col-md-5">
                                                <label for="test_sms_to" class="form-label">شماره موبایل گیرنده (شما)</label>
                                                <input type="text" class="form-control" id="test_sms_to" dir="ltr" placeholder="مثال: 09123456789">
                                            </div>
                                            <div class="col-md-3">
                                                <button type="button" class="btn btn-dark btn-wave w-100" id="btnSendTestSms">
                                                    <i class="ri-send-plane-fill align-middle me-1"></i> ارسال پیامک تست
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-4">
                                        <div class="text-muted small">
                                            <i class="ri-information-line align-middle"></i>
                                            توجه: کلید API و تنظیمات پایه‌ای پیامک از فایل امنیتی <code>.env</code> سرور فراخوانی می‌شوند.
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
</div>

<?php
require_once "views/panel/footer.php";
?>

<script>
    (function() {
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
            cargo_android_latest: document.getElementById('cargo_android_latest'),
            cargo_android_min: document.getElementById('cargo_android_min'),
            cargo_android_url: document.getElementById('cargo_android_url'),
            cargo_ios_latest: document.getElementById('cargo_ios_latest'),
            cargo_ios_min: document.getElementById('cargo_ios_min'),
            cargo_ios_url: document.getElementById('cargo_ios_url'),
            maint_enabled: document.getElementById('maint_enabled'),
            maint_message: document.getElementById('maint_message'),

            // verification
            require_national_serial: document.getElementById('require_national_serial'),
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

        function setLogo(path) {
            if (path) {
                els.logoPathText.textContent = path;
                els.logoPreview.src = path;
                els.logoPreview.style.display = 'inline-block';
            } else {
                els.logoPathText.textContent = '';
                els.logoPreview.style.display = 'none';
            }
        }

        function setFavicon(path) {
            if (path) {
                els.faviconPathText.textContent = path;
                els.faviconPreview.src = path;
                els.faviconPreview.style.display = 'inline-block';
            } else {
                els.faviconPathText.textContent = '';
                els.faviconPreview.style.display = 'none';
            }
        }

        // تابع دریافت اعتبار پیامک
        async function fetchSmsCredit() {
            const creditEl = document.getElementById('sms_credit_amount');
            // نمایش لودینگ
            creditEl.innerHTML = '<span class="spinner-border spinner-border-sm text-primary" role="status"></span>';

            try {
                const res = await fetch('ajax/site-settings.php?action=get_sms_credit', {
                    credentials: 'same-origin'
                });
                const json = await res.json();

                if (json.ok) {
                    // نمایش عدد با جداکننده هزارگان و فونت فارسی
                    creditEl.className = "fs-24 text-primary";
                    creditEl.textContent = new Intl.NumberFormat('fa-IR').format(json.amount);
                } else {
                    creditEl.className = "fs-16 text-danger";
                    creditEl.textContent = 'خطا: ' + json.message;
                }
            } catch (e) {
                creditEl.className = "fs-16 text-danger";
                creditEl.textContent = 'خطا در ارتباط با سرور';
            }
        }

        // وصل کردن دکمه بروزرسانی
        document.getElementById('btnRefreshCredit').addEventListener('click', fetchSmsCredit);

        // اجرای تابع فقط زمانی که کاربر روی تب پیامک کلیک کند (برای جلوگیری از درخواست اضافه)
        document.getElementById('tab-sms').addEventListener('shown.bs.tab', function() {
            // اگر هنوز لود نشده بود، لود کن
            if (document.getElementById('sms_credit_amount').textContent.includes('در حال دریافت')) {
                fetchSmsCredit();
            }
        });

        // تابع ارسال پیامک تستی
        async function sendTestSms() {
            const btn = document.getElementById('btnSendTestSms');
            const from = document.getElementById('test_sms_from').value.trim();
            const to = document.getElementById('test_sms_to').value.trim();

            if (!from || !to) {
                Swal.fire({
                    icon: 'warning',
                    title: 'هشدار',
                    text: 'لطفاً خط فرستنده و موبایل گیرنده را وارد کنید.'
                });
                return;
            }

            // تغییر دکمه به حالت لودینگ
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال ارسال...';
            btn.disabled = true;

            try {
                const fd = new FormData();
                fd.append('action', 'send_test_sms');
                fd.append('from', from);
                fd.append('to', to);

                const res = await fetch('ajax/site-settings.php', {
                    method: 'POST',
                    body: fd,
                    credentials: 'same-origin'
                });

                const json = await res.json();

                if (json.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'موفق',
                        text: json.message
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطا در ارسال',
                        text: json.message
                    });
                }
            } catch (e) {
                Swal.fire({
                    icon: 'error',
                    title: 'خطا',
                    text: 'خطا در ارتباط با سرور'
                });
            }

            // بازگرداندن دکمه به حالت عادی
            btn.innerHTML = originalText;
            btn.disabled = false;
        }

        document.getElementById('btnSendTestSms').addEventListener('click', sendTestSms);

        function publicPageBase() {
            const configured = (els.site_url.value || '').trim().replace(/\/+$/, '');
            if (configured) return configured;
            const dir = window.location.pathname.replace(/\/[^/]*$/, '').replace(/\/+$/, '');
            return `${window.location.origin}${dir}`;
        }

        function suggestPublicUrl(input, file) {
            input.value = `${publicPageBase()}/${file}`;
            input.dispatchEvent(new Event('change'));
        }

        function openPublicUrl(input) {
            const url = (input.value || '').trim();
            if (!url) return;
            try { window.open(new URL(url, window.location.href).toString(), '_blank', 'noopener'); } catch (_) {}
        }

        document.getElementById('btnSuggestTermsUrl').addEventListener('click', () => suggestPublicUrl(els.terms_url, 'terms.php'));
        document.getElementById('btnSuggestAppUrl').addEventListener('click', () => suggestPublicUrl(els.app_download_url, 'app.php'));
        document.getElementById('btnOpenTermsUrl').addEventListener('click', () => openPublicUrl(els.terms_url));
        document.getElementById('btnOpenAppUrl').addEventListener('click', () => openPublicUrl(els.app_download_url));

        async function load() {
            const res = await fetch('ajax/site-settings.php?action=get', {
                credentials: 'same-origin'
            });
            const json = await res.json();
            if (!json.ok) {
                Swal.fire({
                    title: 'خطا',
                    text: json.message || 'خطا',
                    icon: 'error'
                });
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
            els.cargo_android_latest.value = it['cargo.app.android.latest_version_code'] || '';
            els.cargo_android_min.value = it['cargo.app.android.min_supported_code'] || '';
            els.cargo_android_url.value = it['cargo.app.android.update_url'] || '';
            els.cargo_ios_latest.value = it['cargo.app.ios.latest_version_code'] || '';
            els.cargo_ios_min.value = it['cargo.app.ios.min_supported_code'] || '';
            els.cargo_ios_url.value = it['cargo.app.ios.update_url'] || '';
            els.maint_enabled.value = it['maintenance.enabled'] || '0';
            els.maint_message.value = it['maintenance.message'] || '';

            els.require_national_serial.checked = (it['auth.require_national_serial'] === '1');
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

        async function save() {
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
                'cargo.app.android.latest_version_code': els.cargo_android_latest.value,
                'cargo.app.android.min_supported_code': els.cargo_android_min.value,
                'cargo.app.android.update_url': els.cargo_android_url.value,
                'cargo.app.ios.latest_version_code': els.cargo_ios_latest.value,
                'cargo.app.ios.min_supported_code': els.cargo_ios_min.value,
                'cargo.app.ios.update_url': els.cargo_ios_url.value,
                'maintenance.enabled': els.maint_enabled.value,
                'maintenance.message': els.maint_message.value,

                'auth.require_national_serial': els.require_national_serial.checked ? '1' : '0',
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
            fd.append('action', 'save');
            fd.append('items', JSON.stringify(items));
            const res = await fetch('ajax/site-settings.php', {
                method: 'POST',
                body: fd,
                credentials: 'same-origin'
            });
            const json = await res.json();
            if (json.ok) {
                Swal.fire({
                    title: 'ذخیره شد',
                    text: 'موفق',
                    icon: 'success'
                });
            } else {
                Swal.fire({
                    title: 'خطا',
                    text: json.message || 'خطا',
                    icon: 'error'
                });
            }
        }

        async function uploadLogo() {
            if (!els.logo.files || !els.logo.files[0]) {
                Swal.fire({
                    title: 'هشدار',
                    text: 'فایل لوگو را انتخاب کن',
                    icon: 'warning'
                });
                return;
            }
            const fd = new FormData();
            fd.append('action', 'upload_logo');
            fd.append('logo', els.logo.files[0]);
            const res = await fetch('ajax/site-settings.php', {
                method: 'POST',
                body: fd,
                credentials: 'same-origin'
            });
            const json = await res.json();
            if (json.ok) {
                setLogo(json.path || '');
                Swal.fire({
                    title: 'آپلود شد',
                    text: 'لوگو بروزرسانی شد',
                    icon: 'success'
                });
            } else {
                Swal.fire({
                    title: 'خطا',
                    text: json.message || 'خطا',
                    icon: 'error'
                });
            }
        }

        async function uploadFavicon() {
            if (!els.favicon.files || !els.favicon.files[0]) {
                Swal.fire({
                    title: 'هشدار',
                    text: 'فایل نمادک را انتخاب کن',
                    icon: 'warning'
                });
                return;
            }
            const fd = new FormData();
            fd.append('action', 'upload_favicon');
            fd.append('favicon', els.favicon.files[0]);
            const res = await fetch('ajax/site-settings.php', {
                method: 'POST',
                body: fd,
                credentials: 'same-origin'
            });
            const json = await res.json();
            if (json.ok) {
                setFavicon(json.path || '');
                Swal.fire({
                    title: 'آپلود شد',
                    text: 'نمادک بروزرسانی شد',
                    icon: 'success'
                });
            } else {
                Swal.fire({
                    title: 'خطا',
                    text: json.message || 'خطا',
                    icon: 'error'
                });
            }
        }

        document.getElementById('btnSaveSettings').addEventListener('click', save);
        document.getElementById('btnUploadLogo').addEventListener('click', uploadLogo);
        document.getElementById('btnUploadFavicon').addEventListener('click', uploadFavicon);

        load();
    })();
</script>
