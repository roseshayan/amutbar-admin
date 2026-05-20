<?php
require_once "includes/init.php";
require_admin();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) redirect('loads_list.php');

$pdo = db();
$st = $pdo->prepare("
    SELECT l.*, 
           c1.name as o_city, p1.name as o_prov,
           c2.name as d_city, p2.name as d_prov,
           cl.title as cargo_title,
           comp.company_name
    FROM loads l
    LEFT JOIN cities c1 ON l.origin_city_id = c1.id
    LEFT JOIN provinces p1 ON c1.province_id = p1.id
    LEFT JOIN cities c2 ON l.dest_city_id = c2.id
    LEFT JOIN provinces p2 ON c2.province_id = p2.id
    LEFT JOIN cargos_list cl ON l.cargo_type_id = cl.id
    LEFT JOIN companies comp ON l.company_id = comp.id
    WHERE l.id = ? AND l.deleted_at IS NULL
");
$st->execute([$id]);
$load = $st->fetch();

if (!$load) redirect('loads_list.php');

$vehicles = $pdo->query("SELECT id, title FROM vehicle_types WHERE is_active=1 ORDER BY title ASC")->fetchAll();

require_once "views/panel/header.php";
require_once "views/panel/sidebar.php";

$hasMapData = (!empty($load['origin_lat']) && !empty($load['dest_lat']));
?>

<link rel="stylesheet" href="assets/vendor/select2/css/select2.min.css">
<script src="assets/vendor/select2/js/select2.full.min.js"></script>
<link rel="stylesheet" href="assets/vendor/leaflet/leaflet.css" />
<script src="assets/vendor/leaflet/leaflet.js"></script>

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-2">ویرایش اعلام بار #<?= $load['public_code'] ?></h1>
            </div>
            <div class="btn-list">
                <a href="loads_list.php" class="btn btn-secondary btn-wave">بازگشت</a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <form id="loadEditForm">
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">وضعیت بار</label>
                                    <select name="load_status" class="form-select">
                                        <option value="1" <?= $load['load_status'] == 1 ? 'selected' : '' ?>>فعال (در انتظار راننده)</option>
                                        <option value="2" <?= $load['load_status'] == 2 ? 'selected' : '' ?>>تخصیص داده شده</option>
                                        <option value="3" <?= $load['load_status'] == 3 ? 'selected' : '' ?>>پایان یافته</option>
                                        <option value="4" <?= $load['load_status'] == 4 ? 'selected' : '' ?>>لغو شده</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">شماره تلفن هماهنگی *</label>
                                    <input type="tel" name="phone_coordination" id="phone_coordination" class="form-control" value="<?= htmlspecialchars((string)$load['phone_coordination']) ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">شرکت باربری (جستجو) *</label>
                                    <select name="company_id" id="company_search" class="form-control">
                                        <option value="<?= $load['company_id'] ?>" selected="selected"><?= htmlspecialchars((string)$load['company_name']) ?></option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">شهر مبدا *</label>
                                    <select name="origin_city_id" id="origin_city_search" class="form-control">
                                        <option value="<?= $load['origin_city_id'] ?>" selected="selected"><?= $load['o_city'] . ' (' . $load['o_prov'] . ')' ?></option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">شهر مقصد *</label>
                                    <select name="dest_city_id" id="dest_city_search" class="form-control">
                                        <option value="<?= $load['dest_city_id'] ?>" selected="selected"><?= $load['d_city'] . ' (' . $load['d_prov'] . ')' ?></option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">نوع بار *</label>
                                    <select name="load_type" id="load_type" class="form-select" onchange="checkLoadType()">
                                        <option value="1" <?= $load['load_type'] == 1 ? 'selected' : '' ?>>دربستی</option>
                                        <option value="2" <?= $load['load_type'] == 2 ? 'selected' : '' ?>>روباری</option>
                                    </select>
                                </div>
                                <div class="col-md-4" id="vehicle_wrap">
                                    <label class="form-label">نوع بارگیر مورد نیاز *</label>
                                    <select name="primary_vehicle_type_id" class="form-select">
                                        <?php foreach ($vehicles as $v): ?>
                                            <option value="<?= $v['id'] ?>" <?= $load['primary_vehicle_type_id'] == $v['id'] ? 'selected' : '' ?>><?= $v['title'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">نوع کالا *</label>
                                    <select name="cargo_type_id" id="cargo_search" class="form-control">
                                        <option value="<?= $load['cargo_type_id'] ?>" selected="selected"><?= htmlspecialchars((string)$load['cargo_title']) ?></option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" id="weight_label">وزن بار (<?= $load['load_type'] == 1 ? 'تن' : 'کیلوگرم' ?>) *</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" name="weight" id="weight_input" class="form-control" value="<?= $load['weight_kg'] ?>" <?= $load['is_tonnage_free'] == 1 ? 'disabled' : '' ?>>
                                        <div class="input-group-text">
                                            <input type="checkbox" name="is_tonnage_free" value="1" id="free_ton" onchange="toggleWeightInput(this)" <?= $load['is_tonnage_free'] == 1 ? 'checked' : '' ?>>
                                            <label class="mb-0 ms-1" for="free_ton">تناژ آزاد</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">نوع محاسبه صافی *</label>
                                    <select name="price_type" class="form-select">
                                        <option value="1" <?= $load['price_type'] == 1 ? 'selected' : '' ?>>صافی سرویسی</option>
                                        <option value="2" <?= $load['price_type'] == 2 ? 'selected' : '' ?>>صافی تنی</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">کرایه پیشنهادی (تومان) *</label>
                                    <input type="text" id="proposed_price" class="form-control price-format" value="<?= number_format((int)$load['proposed_price']) ?>">
                                    <input type="hidden" name="proposed_price" id="proposed_price_hidden" value="<?= (int)$load['proposed_price'] ?>">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">توضیحات برای راننده</label>
                                    <textarea name="description" class="form-control" rows="2"><?= htmlspecialchars((string)$load['description']) ?></textarea>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" id="insurance_check" name="has_insurance" value="1" onchange="toggleInsuranceModal(this)" <?= $load['has_insurance'] == 1 ? 'checked' : '' ?>>
                                        <label class="form-check-label fw-bold text-primary" for="insurance_check">نیاز به صدور بیمه‌نامه و بارنامه رسمی شرکت دارد</label>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" id="map_check" value="1" onchange="toggleMapFields(this)" <?= $hasMapData ? 'checked' : '' ?>>
                                        <label class="form-check-label fw-bold text-success" for="map_check">ثبت مختصات دقیق روی نقشه برای مسیریابی (اختیاری)</label>
                                    </div>
                                </div>
                            </div>

                            <div id="insurance_fields" style="display: <?= $load['has_insurance'] == 1 ? 'block' : 'none' ?>;">
                                <hr class="my-4">
                                <h5 class="fs-14 fw-bold text-success mb-3">مشخصات بارنامه و بیمه تکمیلی</h5>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">ارزش واقعی کالا (تومان)</label>
                                        <input type="text" id="insurance_value" class="form-control price-format" value="<?= number_format((int)$load['insurance_value']) ?>">
                                        <input type="hidden" name="insurance_value" id="insurance_value_hidden" value="<?= (int)$load['insurance_value'] ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">آدرس دقیق محل بارگیری</label>
                                        <input type="text" name="origin_address" maxlength="150" class="form-control" value="<?= htmlspecialchars((string)$load['origin_address']) ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">آدرس دقیق محل تخلیه</label>
                                        <input type="text" name="dest_address" maxlength="150" class="form-control" value="<?= htmlspecialchars((string)$load['dest_address']) ?>">
                                    </div>
                                </div>
                            </div>

                            <div id="map_fields" style="display: <?= $hasMapData ? 'block' : 'none' ?>;">
                                <hr class="my-4">
                                <h5 class="fs-14 fw-bold text-info mb-3">ویرایش مختصات مبدا و مقصد روی نقشه</h5>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label">مبدا بارگیری</label>
                                        <div class="input-group mb-2">
                                            <input type="text" id="map_search_origin" class="form-control" placeholder="جستجوی شهر یا خیابان...">
                                            <button class="btn btn-outline-secondary" type="button" onclick="searchMap(mapOrigin, markerOrigin, 'map_search_origin', 'origin_lat', 'origin_lng')"><i class="ri-search-line"></i></button>
                                        </div>
                                        <div id="map_origin" style="height: 300px; border-radius: 8px; z-index: 1;"></div>
                                        <input type="hidden" name="origin_lat" id="origin_lat" value="<?= $load['origin_lat'] ?>">
                                        <input type="hidden" name="origin_lng" id="origin_lng" value="<?= $load['origin_lng'] ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">مقصد تخلیه</label>
                                        <div class="input-group mb-2">
                                            <input type="text" id="map_search_dest" class="form-control" placeholder="جستجوی شهر یا خیابان...">
                                            <button class="btn btn-outline-secondary" type="button" onclick="searchMap(mapDest, markerDest, 'map_search_dest', 'dest_lat', 'dest_lng')"><i class="ri-search-line"></i></button>
                                        </div>
                                        <div id="map_dest" style="height: 300px; border-radius: 8px; z-index: 1;"></div>
                                        <input type="hidden" name="dest_lat" id="dest_lat" value="<?= $load['dest_lat'] ?>">
                                        <input type="hidden" name="dest_lng" id="dest_lng" value="<?= $load['dest_lng'] ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="button" class="btn btn-primary" onclick="submitEditForm()">ثبت تغییرات</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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
            minimumInputLength: 1
        });

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

        function initCitySearch(selector) {
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
                    }
                },
                minimumInputLength: 2,
                language: {
                    inputTooShort: function() {
                        return "حداقل 2 حرف وارد کنید";
                    }
                }
            });
        }

        initCitySearch('#origin_city_search');
        initCitySearch('#dest_city_search');

        // اگر از قبل مختصات دارد، نقشه‌ها همان اول لود شوند
        if ($('#map_check').is(':checked')) {
            initMaps();
        }
    });

    function checkLoadType() {
        let type = $('#load_type').val();
        if (type === '2') $('#weight_label').text('وزن بار (کیلوگرم) *');
        else $('#weight_label').text('وزن بار (تن) *');
    }

    function toggleWeightInput(chk) {
        $('#weight_input').prop('disabled', chk.checked);
        if (chk.checked) $('#weight_input').val('');
    }

    function toggleInsuranceModal(chk) {
        if (chk.checked) $('#insurance_fields').show();
        else $('#insurance_fields').hide();
    }

    // --- اسکریپت نقشه (Leaflet) ---
    let mapOrigin, mapDest, markerOrigin, markerDest;

    function toggleMapFields(chk) {
        if (chk.checked) {
            $('#map_fields').slideDown('fast', function() {
                if (!mapOrigin) initMaps();
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
        let startOLat = $('#origin_lat').val() || 35.6892;
        let startOLng = $('#origin_lng').val() || 51.3890;
        mapOrigin = L.map('map_origin').setView([startOLat, startOLng], $('#origin_lat').val() ? 14 : 11);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapOrigin);
        markerOrigin = L.marker([startOLat, startOLng], {
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

        let startDLat = $('#dest_lat').val() || 35.6892;
        let startDLng = $('#dest_lng').val() || 51.3890;
        mapDest = L.map('map_dest').setView([startDLat, startDLng], $('#dest_lat').val() ? 14 : 11);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapDest);
        markerDest = L.marker([startDLat, startDLng], {
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

    function submitEditForm() {
        if (!$('#company_search').val()) return Swal.fire('خطا', 'انتخاب شرکت باربری الزامی است', 'warning');
        if (!$('#phone_coordination').val()) return Swal.fire('خطا', 'شماره تلفن هماهنگی الزامی است', 'warning');
        if (!$('#proposed_price_hidden').val()) return Swal.fire('خطا', 'کرایه پیشنهادی الزامی است', 'warning');

        $.post('<?= base_url() ?>/ajax/loads.php?action=update', $('#loadEditForm').serialize(), function(res) {
            if (res.ok) {
                Swal.fire('موفق', 'بار با موفقیت ویرایش شد.', 'success')
                    .then(() => {
                        window.location.href = 'loads_list.php';
                    });
            } else {
                Swal.fire('خطا', res.message || 'ثبت ناموفق', 'error');
            }
        }, 'json');
    }
</script>

<?php require_once "views/panel/footer.php"; ?>