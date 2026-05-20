<?php
require_once "includes/init.php";
require_admin();
require_once "views/panel/header.php";
require_once "views/panel/sidebar.php";

$pdo = db();
$vehicles = $pdo->query("SELECT id, title FROM vehicle_types WHERE is_active=1 ORDER BY title ASC")->fetchAll();
?>

<link rel="stylesheet" href="assets/vendor/select2/css/select2.min.css">
<script src="assets/vendor/select2/js/select2.full.min.js"></script>

<link rel="stylesheet" href="assets/vendor/leaflet/leaflet.css" />
<script src="assets/vendor/leaflet/leaflet.js"></script>

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-2">اعلام بار جدید</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <form id="loadForm">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">شماره تلفن هماهنگی *</label>
                                    <input type="tel" name="phone_coordination" id="phone_coordination" class="form-control" placeholder="09123456789">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">شرکت باربری (جستجو) *</label>
                                    <select name="company_id" id="company_search" class="form-control"></select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">شهر مبدا (جستجوی شهر یا استان) *</label>
                                    <select name="origin_city_id" id="origin_city_search" class="form-control"></select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">شهر مقصد (جستجوی شهر یا استان) *</label>
                                    <select name="dest_city_id" id="dest_city_search" class="form-control"></select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">نوع بار *</label>
                                    <select name="load_type" id="load_type" class="form-select" onchange="checkLoadType()">
                                        <option value="1">دربستی</option>
                                        <option value="2">روباری</option>
                                    </select>
                                </div>
                                <div class="col-md-4" id="vehicle_wrap">
                                    <label class="form-label">نوع بارگیر مورد نیاز *</label>
                                    <select name="primary_vehicle_type_id" id="primary_vehicle_type_id" class="form-select">
                                        <?php foreach ($vehicles as $v) echo "<option value='{$v['id']}'>{$v['title']}</option>"; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">نوع کالا (جستجو) *</label>
                                    <select name="cargo_type_id" id="cargo_search" class="form-control"></select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" id="weight_label">وزن بار (تن) *</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" name="weight" id="weight_input" class="form-control">
                                        <div class="input-group-text">
                                            <input type="checkbox" name="is_tonnage_free" value="1" id="free_ton" onchange="toggleWeightInput(this)">
                                            <label class="mb-0 ms-1" for="free_ton">تناژ آزاد</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">نوع محاسبه صافی *</label>
                                    <select name="price_type" class="form-select">
                                        <option value="1">صافی سرویسی</option>
                                        <option value="2">صافی تنی</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">کرایه پیشنهادی (تومان) *</label>
                                    <div class="input-group">
                                        <input type="text" id="proposed_price" class="form-control price-format" placeholder="مثال: 5,000,000">
                                        <input type="hidden" name="proposed_price" id="proposed_price_hidden">
                                        <button type="button" class="btn btn-outline-secondary" onclick="calcAveragePrice()">تخمین نرخ</button>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">توضیحات برای راننده</label>
                                    <textarea name="description" class="form-control" rows="2"></textarea>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" id="insurance_check" name="has_insurance" value="1" onchange="toggleInsuranceModal(this)">
                                        <label class="form-check-label fw-bold text-primary" for="insurance_check">نیاز به صدور بیمه‌نامه و بارنامه رسمی شرکت دارد</label>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" id="map_check" value="1" onchange="toggleMapFields(this)">
                                        <label class="form-check-label fw-bold text-success" for="map_check">ثبت مختصات دقیق روی نقشه برای مسیریابی (اختیاری)</label>
                                    </div>
                                </div>
                            </div>

                            <div id="insurance_fields" style="display:none;">
                                <hr class="my-4">
                                <h5 class="fs-14 fw-bold text-success mb-3">مشخصات بارنامه و بیمه تکمیلی</h5>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">ارزش واقعی کالا (تومان)</label>
                                        <input type="text" id="insurance_value" class="form-control price-format">
                                        <input type="hidden" name="insurance_value" id="insurance_value_hidden">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">آدرس دقیق محل بارگیری</label>
                                        <input type="text" name="origin_address" maxlength="150" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">آدرس دقیق محل تخلیه</label>
                                        <input type="text" name="dest_address" maxlength="150" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div id="map_fields" style="display:none;">
                                <hr class="my-4">
                                <h5 class="fs-14 fw-bold text-info mb-3">انتخاب مختصات مبدا و مقصد روی نقشه</h5>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label">مبدا بارگیری</label>
                                        <div class="input-group mb-2">
                                            <input type="text" id="map_search_origin" class="form-control" placeholder="جستجوی شهر یا خیابان...">
                                            <button class="btn btn-outline-secondary" type="button" onclick="searchMap(mapOrigin, markerOrigin, 'map_search_origin', 'origin_lat', 'origin_lng')"><i class="ri-search-line"></i></button>
                                        </div>
                                        <div id="map_origin" style="height: 300px; border-radius: 8px; z-index: 1;"></div>
                                        <input type="hidden" name="origin_lat" id="origin_lat">
                                        <input type="hidden" name="origin_lng" id="origin_lng">
                                        <small class="text-muted mt-1 d-block">نشانگر را جابجا کنید یا روی نقشه کلیک کنید.</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">مقصد تخلیه</label>
                                        <div class="input-group mb-2">
                                            <input type="text" id="map_search_dest" class="form-control" placeholder="جستجوی شهر یا خیابان...">
                                            <button class="btn btn-outline-secondary" type="button" onclick="searchMap(mapDest, markerDest, 'map_search_dest', 'dest_lat', 'dest_lng')"><i class="ri-search-line"></i></button>
                                        </div>
                                        <div id="map_dest" style="height: 300px; border-radius: 8px; z-index: 1;"></div>
                                        <input type="hidden" name="dest_lat" id="dest_lat">
                                        <input type="hidden" name="dest_lng" id="dest_lng">
                                        <small class="text-muted mt-1 d-block">نشانگر را جابجا کنید یا روی نقشه کلیک کنید.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="button" class="btn btn-primary" onclick="submitLoadForm()">ثبت نهایی و اعلام بار</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // توابع فرمت قیمت
    function formatPrice(val) {
        if (!val) return '';
        val = val.toString().replace(/,/g, '');
        return val.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function unformatPrice(val) {
        if (!val) return '';
        return val.toString().replace(/,/g, '');
    }

    $(document).ready(function() {
        $('.price-format').on('input', function() {
            let val = unformatPrice($(this).val());
            $(this).val(formatPrice(val));
            let hiddenId = $(this).attr('id') + '_hidden';
            $('#' + hiddenId).val(val);
        });

        $('#cargo_search').select2({
            ajax: {
                url: '<?= base_url() ?>/ajax/loads.php?action=search_cargo',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.items
                    };
                }
            },
            placeholder: 'نام کالا را سرچ کنید...',
            minimumInputLength: 1
        });

        function initCitySearch(selector, placeholderText) {
            $(selector).select2({
                ajax: {
                    url: '<?= base_url() ?>/ajax/loads.php?action=search_city',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.items
                        };
                    },
                    cache: true
                },
                placeholder: placeholderText,
                minimumInputLength: 2,
                language: {
                    inputTooShort: function() {
                        return "حداقل 2 حرف وارد کنید";
                    }
                }
            });

            $(selector).on('select2:select', function(e) {
                let data = e.params.data;
                if ($(this).find("option[value='" + data.id + "']").length === 0) {
                    let option = new Option(data.text, data.id, true, true);
                    $(this).append(option).trigger('change');
                }
            });
        }

        initCitySearch('#origin_city_search', 'جستجوی شهر یا استان (مثلا: مشهد)');
        initCitySearch('#dest_city_search', 'جستجوی شهر یا استان (مثلا: تهران)');

        $('#company_search').select2({
            ajax: {
                url: '<?= base_url() ?>/ajax/loads.php?action=search_company',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.items
                    };
                }
            },
            placeholder: 'نام شرکت باربری را سرچ کنید...',
            minimumInputLength: 1
        });
    });

    function checkLoadType() {
        let type = $('#load_type').val();
        if (type === '2') {
            $('#weight_label').text('وزن بار (کیلوگرم) *');
        } else {
            $('#weight_label').text('وزن بار (تن) *');
        }
    }

    function toggleWeightInput(chk) {
        $('#weight_input').prop('disabled', chk.checked);
        if (chk.checked) $('#weight_input').val('');
    }

    function toggleInsuranceModal(chk) {
        if (chk.checked) {
            Swal.fire({
                title: 'نکات مهم صدور بارنامه و بیمه',
                html: `<div class="text-start fs-13 line-height-2">
                    <p class="text-danger fw-bold">نکته ۱: سقف پوشش خسارت بیمه حداکثر تا ۵۰۰ میلیون تومان است.</p>
                    <p>نکته ۲: در صورت وجود هرگونه مغایرت، پرداخت خسارت ممکن است با عدم پذیرش همراه باشد.</p>
                    <p class="fw-bold">نکته ۳: بارهای بیمه‌دار بعد از تایید اطلاعات بیمه، امکان ویرایش نخواهند داشت.</p>
                </div>`,
                icon: 'info',
                confirmButtonText: 'قوانین را قبول دارم و ادامه می‌دهم'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#insurance_fields').show();
                } else {
                    chk.checked = false;
                    $('#insurance_check').prop('checked', false);
                }
            });
        } else {
            $('#insurance_fields').hide();
        }
    }

    function calcAveragePrice() {
        let o = $('#origin_city_search').val();
        let d = $('#dest_city_search').val();
        if (!o || !d) {
            Swal.fire('خطا', 'لطفا ابتدا مبدا و مقصد را مشخص کنید', 'error');
            return;
        }

        $.post('<?= base_url() ?>/ajax/loads.php?action=get_avg_price', {
            origin_city_id: o,
            dest_city_id: d
        }, function(res) {
            if (res.ok && res.avg_price > 0) {
                $('#proposed_price_hidden').val(res.avg_price);
                $('#proposed_price').val(formatPrice(res.avg_price));
                if (window.Toastify) Toastify({
                    text: `قیمت میانگین سیستم اعمال شد`,
                    duration: 3000,
                    backgroundColor: "#28a745"
                }).showToast();
            } else {
                if (window.Toastify) Toastify({
                    text: 'داده سابقه‌ای پیدا نشد، قیمت را دستی وارد کنید.',
                    duration: 3000,
                    backgroundColor: "#ffc107"
                }).showToast();
            }
        }, 'json');
    }

    // --- اسکریپت نقشه (Leaflet) ---
    let mapOrigin, mapDest, markerOrigin, markerDest;

    function toggleMapFields(chk) {
        if (chk.checked) {
            $('#map_fields').slideDown('fast', function() {
                if (!mapOrigin) initMaps();
                // آپدیت سایز نقشه در صورت نمایش اولیه
                setTimeout(() => {
                    mapOrigin.invalidateSize();
                    mapDest.invalidateSize();
                }, 300);
            });
        } else {
            $('#map_fields').slideUp('fast');
            $('#origin_lat, #origin_lng, #dest_lat, #dest_lng').val('');
        }
    }

    function initMaps() {
        // نقطه پیش‌فرض: تهران
        let defaultLat = 35.6892;
        let defaultLng = 51.3890;

        // مبدا
        mapOrigin = L.map('map_origin').setView([defaultLat, defaultLng], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapOrigin);
        markerOrigin = L.marker([defaultLat, defaultLng], {
            draggable: true
        }).addTo(mapOrigin);

        markerOrigin.on('dragend', function() {
            let p = markerOrigin.getLatLng();
            $('#origin_lat').val(p.lat);
            $('#origin_lng').val(p.lng);
        });
        mapOrigin.on('click', function(e) {
            markerOrigin.setLatLng(e.latlng);
            $('#origin_lat').val(e.latlng.lat);
            $('#origin_lng').val(e.latlng.lng);
        });

        // مقصد
        mapDest = L.map('map_dest').setView([defaultLat, defaultLng], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapDest);
        markerDest = L.marker([defaultLat, defaultLng], {
            draggable: true
        }).addTo(mapDest);

        markerDest.on('dragend', function() {
            let p = markerDest.getLatLng();
            $('#dest_lat').val(p.lat);
            $('#dest_lng').val(p.lng);
        });
        mapDest.on('click', function(e) {
            markerDest.setLatLng(e.latlng);
            $('#dest_lat').val(e.latlng.lat);
            $('#dest_lng').val(e.latlng.lng);
        });
    }

    function searchMap(mapObj, markerObj, inputId, latId, lngId) {
        let q = $('#' + inputId).val();
        if (!q) return;
        // استفاده از nominatim با محدودیت کشور ایران
        $.getJSON(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(q)}&countrycodes=ir`, function(data) {
            if (data && data.length > 0) {
                let lat = data[0].lat;
                let lon = data[0].lon;
                mapObj.setView([lat, lon], 14);
                markerObj.setLatLng([lat, lon]);
                $('#' + latId).val(lat);
                $('#' + lngId).val(lon);
            } else {
                Swal.fire('یافت نشد', 'لطفا نام شهر یا محله را دقیق‌تر بنویسید.', 'info');
            }
        });
    }
    // ---------------------------------

    function submitLoadForm() {
        if (!$('#company_search').val()) return Swal.fire('خطا', 'انتخاب شرکت باربری الزامی است', 'warning');
        if (!$('#phone_coordination').val()) return Swal.fire('خطا', 'شماره تلفن هماهنگی الزامی است', 'warning');
        if (!$('#origin_city_search').val()) return Swal.fire('خطا', 'شهر مبدا الزامی است', 'warning');
        if (!$('#dest_city_search').val()) return Swal.fire('خطا', 'شهر مقصد الزامی است', 'warning');
        if (!$('#cargo_search').val()) return Swal.fire('خطا', 'نوع کالا الزامی است', 'warning');
        if (!$('#free_ton').is(':checked') && !$('#weight_input').val()) return Swal.fire('خطا', 'وزن بار الزامی است', 'warning');
        if (!$('#proposed_price_hidden').val()) return Swal.fire('خطا', 'کرایه پیشنهادی الزامی است', 'warning');

        $.post('<?= base_url() ?>/ajax/loads.php?action=create', $('#loadForm').serialize(), function(res) {
            if (res.ok) {
                Swal.fire('موفق', 'بار با موفقیت اعلام و در اپلیکیشن منتشر شد.', 'success')
                    .then(() => {
                        window.location.href = 'loads_list.php';
                    });
            } else {
                Swal.fire('خطا', res.message || 'ثبت ناموفق', 'error');
            }
        }, 'json').fail(function() {
            Swal.fire('خطا', 'ارتباط با سرور برقرار نشد.', 'error');
        });
    }
</script>

<?php require_once "views/panel/footer.php"; ?>