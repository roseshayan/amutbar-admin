    <?php
    require_once "views/panel/header.php";
    require_once "views/panel/sidebar.php";

    require_once __DIR__ . '/includes/jdf.php';

    // ایجاد کانکشن تنها یک بار
    $pdo = db();

    // دریافت انواع وسایل نقلیه
    $stmt_vehicle = $pdo->query("SELECT id, title FROM vehicle_types WHERE is_active = 1 ORDER BY title");
    $vehicleTypes = $stmt_vehicle->fetchAll();

    // دریافت استان‌ها
    $stmt_province = $pdo->query("SELECT id, name FROM provinces ORDER BY name");
    $provinces = $stmt_province->fetchAll();

    // بارگذاری تنظیمات فیلدها
    $fieldSettings = require_once __DIR__ . '/config/field_settings.php';
    ?>

    <div class="main-content app-content">
        <div class="container-fluid">

            <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h1 class="page-title fw-medium fs-18 mb-2">افزودن کاربر</h1>
                    <div>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="dashboard.php">داشبورد</a></li>
                                <li class="breadcrumb-item"><a href="users_list.php">کاربران</a></li>
                                <li class="breadcrumb-item active" aria-current="page">افزودن</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="btn-list">
                    <a href="users_list.php" class="btn btn-secondary btn-wave">
                        <i class="ri-arrow-go-back-line align-middle"></i> بازگشت
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-body">

                            <form id="userForm" enctype="multipart/form-data" onsubmit="return false;">
                                <!-- آپلود عکس پروفایل -->
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <label for="avatar" class="form-label">عکس پروفایل</label>
                                        <div class="avatar-upload">
                                            <div class="avatar-edit">
                                                <input type='file' id="avatar" name="avatar"
                                                    accept=".png, .jpg, .jpeg" />
                                                <label for="avatar"></label>
                                            </div>
                                            <div class="avatar-preview">
                                                <div id="avatarPreview"
                                                    style="background-image: url('<?= htmlspecialchars(asset('images/faces/default-avatar.png'), ENT_QUOTES, 'UTF-8') ?>');">
                                                </div>
                                            </div>
                                            <div class="avatar-help">
                                                <small>برای تغییر کلیک کنید</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6 col-lg-3 mb-3">
                                        <label for="full_name" class="form-label">نام و نام خانوادگی</label>
                                        <input id="full_name" class="form-control" name="full_name" required>
                                    </div>
                                    <div class="col-md-6 col-lg-3 mb-3">
                                        <label for="phone" class="form-label">شماره موبایل</label>
                                        <input id="phone" class="form-control" name="phone" maxlength="11"
                                            style="direction:ltr" required>
                                    </div>
                                    <div class="col-md-6 col-lg-3 mb-3">
                                        <label for="code_meli" class="form-label">کد ملی</label>
                                        <input id="code_meli" type="tel" maxlength="10" class="form-control"
                                            name="code_meli" style="direction:ltr">
                                    </div>
                                    <div class="col-md-6 col-lg-3 mb-3">
                                        <label for="birth_date" class="form-label">تاریخ تولد</label>
                                        <input id="birth_date" class="form-control" name="birth_date"
                                            placeholder="انتخاب تاریخ">
                                        <small class="text-muted">(شمسی)</small>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-lg-3 mb-3">
                                        <label for="father_name" class="form-label">نام پدر</label>
                                        <input id="father_name" type="text" class="form-control" name="father_name">
                                    </div>
                                    <div class="col-md-6 col-lg-3 mb-3">
                                        <label>جنسیت</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" id="men"
                                                value="1" checked>
                                            <label class="form-check-label" for="men">
                                                آقا
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" id="women"
                                                value="2">
                                            <label class="form-check-label" for="women">
                                                خانم
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-3 mb-3">
                                        <label for="national_card_serial" class="form-label">سریال کارت ملی</label>
                                        <input id="national_card_serial" type="text" class="form-control"
                                            name="national_card_serial">
                                    </div>
                                    <div class="col-md-6 col-lg-3 mb-3">
                                        <label for="user_type" class="form-label">نوع کاربر</label>
                                        <select id="user_type" class="form-select" name="user_type" required
                                            onchange="toggleExtraFields()">
                                            <option selected disabled>-- انتخاب کنید --</option>
                                            <option value="1">راننده</option>
                                            <option value="2">باربری</option>
                                            <option value="3">ادمین</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-lg-3 mb-3">
                                        <label for="display_name" class="form-label">نام نمایشی</label>
                                        <input id="display_name" type="text" class="form-control" name="display_name">
                                    </div>
                                    <div class="col-md-6 col-lg-3 mb-3">
                                        <label for="email" class="form-label">ایمیل (اختیاری)</label>
                                        <input id="email" type="email" class="form-control" name="email"
                                            style="direction:ltr">
                                    </div>
                                    <div class="col-md-6 col-lg-3 mb-3">
                                        <label for="passwordInput" class="form-label">رمز عبور (برای ادمین الزامی، برای
                                            سایرین اختیاری)</label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control" name="password"
                                                id="passwordInput">
                                            <a href="javascript:void(0);" class="show-password-button text-muted"
                                                onclick="togglePassword(this)">
                                                <i class="ri-eye-off-line align-middle"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="status" class="form-label">وضعیت</label>
                                        <select id="status" class="form-select" name="status" required>
                                            <option value="1">فعال</option>
                                            <option value="2">غیرفعال</option>
                                            <option value="3" selected>در انتظار</option>
                                            <option value="4">مسدود</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- بخش اضافی برای راننده -->
                                <div id="driverFields" class="card mt-4" style="display: none;">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">اطلاعات راننده</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- پلاک ماشین با فرمت جدا -->
                                            <div class="col-12 mb-4">
                                                <label class="form-label">پلاک ماشین</label>
                                                <div class="d-flex flex-wrap align-items-center gap-2"
                                                    style="gap: 0.5rem;">
                                                    <div class="plate-input-group">
                                                        <input aria-label="plate_part1" type="number"
                                                            class="form-control plate-part"
                                                            id="plate_part1" name="plate_part1"
                                                            maxlength="2" min="10" max="99"
                                                            placeholder="12"
                                                            style="width: 70px; text-align: center;">
                                                        <div class="plate-separator">–</div>
                                                    </div>

                                                    <div class="plate-input-group">
                                                        <select aria-label="plate_part2" class="form-select plate-part"
                                                            id="plate_part2" name="plate_part2"
                                                            style="width: 90px;">
                                                            <option value="">حرف</option>
                                                            <?php
                                                            $persianLetters = ['الف', 'ب', 'پ', 'ت', 'ث', 'ج', 'چ', 'ح', 'خ', 'د', 'ذ', 'ر', 'ز', 'ژ', 'س', 'ش', 'ص', 'ض', 'ط', 'ظ', 'ع', 'غ', 'ف', 'ق', 'ک', 'گ', 'ل', 'م', 'ن', 'و', 'ه', 'ی'];
                                                            foreach ($persianLetters as $letter) {
                                                                echo "<option value=\"$letter\">$letter</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                        <div class="plate-separator">–</div>
                                                    </div>

                                                    <div class="plate-input-group">
                                                        <input aria-label="plate_part3" type="number"
                                                            class="form-control plate-part"
                                                            id="plate_part3" name="plate_part3"
                                                            maxlength="3" min="100" max="999"
                                                            placeholder="345"
                                                            style="width: 90px; text-align: center;">
                                                        <div class="plate-separator">ایران</div>
                                                    </div>

                                                    <div class="plate-input-group">
                                                        <input aria-label="plate_part4" type="number"
                                                            class="form-control plate-part"
                                                            id="plate_part4" name="plate_part4"
                                                            maxlength="2" min="10" max="99"
                                                            placeholder="67"
                                                            style="width: 70px; text-align: center;">
                                                    </div>

                                                    <div class="ms-3">
                                                        <span class="badge bg-light text-dark border">
                                                            <span id="platePreview">---</span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <small class="text-muted d-block mt-2">فرمت: 12 الف 345 ایران 67</small>
                                            </div>

                                            <div class="col-md-6 col-lg-3 mb-3">
                                                <label for="smart_card_number" class="form-label">شماره کارت
                                                    هوشمند</label>
                                                <input type="text" class="form-control" id="smart_card_number"
                                                    name="smart_card_number">
                                            </div>
                                            <div class="col-md-6 col-lg-3 mb-3">
                                                <label for="vehicle_type_id" class="form-label">نوع وسیله نقلیه</label>
                                                <select class="form-select" id="vehicle_type_id" name="vehicle_type_id">
                                                    <option value="">انتخاب کنید</option>
                                                    <?php foreach ($vehicleTypes as $type): ?>
                                                        <option value="<?php echo $type['id']; ?>"><?php echo htmlspecialchars($type['title']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="col-md-6 col-lg-3 mb-3">
                                                <label for="model_year" class="form-label">سال ساخت (شمسی)</label>
                                                <select class="form-select" id="model_year" name="model_year">
                                                    <option value="">انتخاب کنید</option>
                                                    <?php
                                                    $currentYear = jdate('Y');
                                                    for ($year = convertPersianNumbersToEnglishPHP($currentYear); $year >= 1360; $year--) {
                                                        echo "<option value=\"$year\">$year</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="col-md-6 col-lg-3 mb-3">
                                                <label for="color" class="form-label">رنگ</label>
                                                <input type="text" class="form-control" id="color" name="color">
                                            </div>

                                            <div class="col-md-6 col-lg-3 mb-3">
                                                <label for="capacity_kg" class="form-label">ظرفیت (کیلوگرم)</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="capacity_kg"
                                                        name="capacity_kg" min="0" step="0.01">
                                                    <span class="input-group-text">کیلوگرم</span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-lg-3 mb-3">
                                                <label for="province_id" class="form-label">استان</label>
                                                <select class="form-select select2-province" id="province_id"
                                                    name="province_id">
                                                    <option value="">انتخاب استان</option>
                                                    <?php foreach ($provinces as $province): ?>
                                                        <option value="<?php echo $province['id']; ?>"><?php echo htmlspecialchars($province['name']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="col-md-6 col-lg-3 mb-3">
                                                <label for="city_id" class="form-label">شهر</label>
                                                <select class="form-select select2-city" id="city_id" name="city_id">
                                                    <option value="">ابتدا استان را انتخاب کنید</option>
                                                </select>
                                            </div>

                                            <!-- فیلدهای جدید راننده -->
                                            <div class="col-md-6 col-lg-3 mb-3">
                                                <label for="issued_from" class="form-label">صادره از</label>
                                                <input type="text" class="form-control" id="issued_from"
                                                    name="issued_from">
                                            </div>

                                            <div class="col-md-6 col-lg-3 mb-3">
                                                <label for="home_phone" class="form-label">شماره منزل</label>
                                                <input type="tel" class="form-control" id="home_phone" name="home_phone"
                                                    maxlength="11">
                                            </div>

                                            <div class="col-md-6 col-lg-3 mb-3">
                                                <label for="postal_code" class="form-label">کد پستی</label>
                                                <input type="text" class="form-control" id="postal_code"
                                                    name="postal_code" maxlength="10">
                                            </div>

                                            <div class="col-md-6 col-lg-3 mb-3">
                                                <label for="license_serial" class="form-label">شماره گواهینامه</label>
                                                <input type="text" class="form-control" id="license_serial"
                                                    name="license_serial">
                                            </div>

                                            <div class="col-md-6 col-lg-3 mb-3">
                                                <label for="license_base" class="form-label">پایه گواهینامه</label>
                                                <select class="form-select" id="license_base" name="license_base">
                                                    <option value="">انتخاب کنید</option>
                                                    <option value="اول">اول</option>
                                                    <option value="دوم">دوم</option>
                                                    <option value="سوم">سوم</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6 col-lg-3 mb-3">
                                                <label for="vin_number" class="form-label">شماره VIN</label>
                                                <input type="text" class="form-control" id="vin_number"
                                                    name="vin_number">
                                            </div>

                                            <div class="col-md-6 col-lg-3 mb-3">
                                                <label for="insurance_number" class="form-label">شماره بیمه نامه</label>
                                                <input type="text" class="form-control" id="insurance_number"
                                                    name="insurance_number">
                                            </div>

                                            <div class="col-md-6 col-lg-3 mb-3">
                                                <label for="insurance_expiry" class="form-label">تاریخ اتمام
                                                    بیمه</label>
                                                <input type="text" class="form-control" id="insurance_expiry"
                                                    name="insurance_expiry" placeholder="انتخاب تاریخ">
                                            </div>

                                            <div class="col-md-6 col-lg-3 mb-3">
                                                <label for="engine_number" class="form-label">شماره موتور</label>
                                                <input type="text" class="form-control" id="engine_number"
                                                    name="engine_number">
                                            </div>

                                            <div class="col-md-6 col-lg-3 mb-3">
                                                <label for="chassis_number" class="form-label">شماره شاسی</label>
                                                <input type="text" class="form-control" id="chassis_number"
                                                    name="chassis_number">
                                            </div>

                                            <div class="col-md-12 mb-3">
                                                <label for="address" class="form-label">آدرس</label>
                                                <textarea class="form-control" id="address" name="address"
                                                    rows="2"></textarea>
                                            </div>

                                            <!-- شماره موبایل اضافه -->
                                            <div class="col-12 mb-3">
                                                <label class="form-label">شماره موبایل اضافه</label>
                                                <div id="extraPhonesContainer">
                                                    <div class="input-group mb-2 extra-phone-input">
                                                        <input aria-label="extra_phones" type="tel" class="form-control"
                                                            name="extra_phones[]"
                                                            maxlength="11" placeholder="مثال: 09123456789">
                                                        <button type="button"
                                                            class="btn btn-outline-danger remove-extra-phone"
                                                            onclick="removeExtraPhone(this)">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-primary mt-2"
                                                    onclick="addExtraPhone()">
                                                    <i class="ri-add-line"></i> افزودن شماره موبایل
                                                </button>
                                            </div>

                                            <!-- آپلود فایل‌ها برای راننده -->
                                            <div class="col-12">
                                                <h6 class="mb-3">آپلود فایل‌ها</h6>
                                                <div class="row">
                                                    <div class="col-md-6 col-lg-4 mb-3">
                                                        <label for="national_card_image" class="form-label">عکس کارت
                                                            ملی</label>
                                                        <input type="file" class="form-control" id="national_card_image"
                                                            name="national_card_image" accept="image/*">
                                                        <img id="national_card_image_preview"
                                                            class="img-thumbnail mt-2 d-none" alt="preview">
                                                        <div class="form-text">فرمت‌های مجاز: JPG, PNG</div>
                                                    </div>
                                                    <div class="col-md-6 col-lg-4 mb-3">
                                                        <label for="license_image" class="form-label">عکس
                                                            گواهینامه</label>
                                                        <input type="file" class="form-control" id="license_image"
                                                            name="license_image" accept="image/*">
                                                        <img id="license_image_preview"
                                                            class="img-thumbnail mt-2 d-none" alt="preview">
                                                    </div>
                                                    <div class="col-md-6 col-lg-4 mb-3">
                                                        <label for="vehicle_card_image" class="form-label">عکس کارت
                                                            ماشین</label>
                                                        <input type="file" class="form-control" id="vehicle_card_image"
                                                            name="vehicle_card_image" accept="image/*">
                                                        <img id="vehicle_card_image_preview"
                                                            class="img-thumbnail mt-2 d-none" alt="preview">
                                                    </div>
                                                    <div class="col-md-6 col-lg-4 mb-3">
                                                        <label for="green_card_image" class="form-label">عکس برگه
                                                            سبز</label>
                                                        <input type="file" class="form-control" id="green_card_image"
                                                            name="green_card_image" accept="image/*">
                                                        <img id="green_card_image_preview"
                                                            class="img-thumbnail mt-2 d-none" alt="preview">
                                                    </div>
                                                    <div class="col-md-6 col-lg-4 mb-3">
                                                        <label for="insurance_image" class="form-label">عکس بیمه نامه
                                                            خودرو</label>
                                                        <input type="file" class="form-control" id="insurance_image"
                                                            name="insurance_image" accept="image/*">
                                                        <img id="insurance_image_preview"
                                                            class="img-thumbnail mt-2 d-none" alt="preview">
                                                    </div>
                                                    <div class="col-md-6 col-lg-4 mb-3">
                                                        <label for="verification_video" class="form-label">ویدئو احراز
                                                            هویت</label>
                                                        <input type="file" class="form-control" id="verification_video"
                                                            name="verification_video" accept="video/*">
                                                        <video id="verification_video_preview" class="mt-2 d-none"
                                                            controls style="max-width:100%;height:auto"></video>
                                                        <div class="form-text">فرمت‌های مجاز: MP4, MOV</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- بخش اضافی برای باربری -->
                                <div id="companyFields" class="card mt-4" style="display: none;">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">اطلاعات باربری</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="company_name" class="form-label">نام باربری</label>
                                                <input type="text" class="form-control" id="company_name"
                                                    name="company_name">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="registration_no" class="form-label">شماره ثبت</label>
                                                <input type="text" class="form-control" id="registration_no"
                                                    name="registration_no">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="registration_date" class="form-label">تاریخ ثبت</label>
                                                <input type="text" class="form-control" id="registration_date"
                                                    name="registration_date" placeholder="انتخاب تاریخ">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="economic_code" class="form-label">کد اقتصادی</label>
                                                <input type="text" class="form-control" id="economic_code"
                                                    name="economic_code">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="company_province_id" class="form-label">استان</label>
                                                <select class="form-select select2-province" id="company_province_id"
                                                    name="company_province_id">
                                                    <option value="">انتخاب استان</option>
                                                    <?php foreach ($provinces as $province): ?>
                                                        <option value="<?php echo $province['id']; ?>"><?php echo htmlspecialchars($province['name']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="company_city_id" class="form-label">شهر</label>
                                                <select class="form-select select2-city" id="company_city_id"
                                                    name="company_city_id">
                                                    <option value="">ابتدا استان را انتخاب کنید</option>
                                                </select>
                                            </div>

                                            <div class="col-md-12 mb-3">
                                                <label for="company_address" class="form-label">آدرس</label>
                                                <textarea class="form-control" id="company_address"
                                                    name="company_address" rows="2"></textarea>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="company_postal_code" class="form-label">کد پستی</label>
                                                <input type="text" class="form-control" id="company_postal_code"
                                                    name="company_postal_code" maxlength="10">
                                            </div>

                                            <!-- آپلود فایل‌ها برای باربری -->
                                            <div class="col-12 mt-3">
                                                <h6 class="mb-3">آپلود فایل‌ها</h6>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="company_national_card_image" class="form-label">عکس
                                                            کارت ملی</label>
                                                        <input type="file" class="form-control"
                                                            id="company_national_card_image"
                                                            name="company_national_card_image" accept="image/*">
                                                        <img id="company_national_card_image_preview"
                                                            class="img-thumbnail mt-2 d-none" alt="preview">
                                                        <div class="form-text">فرمت‌های مجاز: JPG, PNG</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 d-flex gap-2">
                                    <button class="btn btn-primary" onclick="saveUser()">ذخیره</button>
                                    <a class="btn btn-outline-secondary" href="users_list.php">انصراف</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        /* استایل کلی فرم */
        .form-card {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e9ecef;
        }

        .form-card .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 1.25rem 1.5rem;
        }

        .form-card .card-header h5 {
            font-weight: 600;
            margin: 0;
        }

        .form-card .card-body {
            padding: 2rem;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        /* استایل بخش‌های مخفی */
        #driverFields,
        #companyFields {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 2rem;
            transition: all 0.3s;
        }

        #driverFields.active,
        #companyFields.active {
            border-color: #667eea;
        }

        /* استایل Select2 */
        /*.select2-container--default .select2-selection--single {*/
        /*    border: 1px solid #ced4da;*/
        /*    border-radius: 8px;*/
        /*    height: calc(2.25rem + 2px);*/
        /*    padding: 0.375rem 0.75rem;*/
        /*}*/

        /*.select2-container--default .select2-selection--single .select2-selection__rendered {*/
        /*    line-height: 1.5;*/
        /*    color: #495057;*/
        /*}*/

        /*.select2-container--default .select2-selection--single .select2-selection__arrow {*/
        /*    height: 100%;*/
        /*}*/

        /* استایل دکمه‌ها */
        .btn-primary {
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(102, 126, 234, 0.4);
        }

        /* استایل بخش تاریخ‌ها */
        .date-input-group {
            position: relative;
        }

        .date-input-group .form-control {
            padding-right: 40px;
        }

        .date-input-group::after {
            content: '\F4C5';
            font-family: 'bootstrap-icons';
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            pointer-events: none;
        }

        /* استایل آپلود فایل */
        .file-upload-container {
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            background: #f8f9fa;
            transition: all 0.3s;
        }

        .file-upload-container:hover {
            border-color: #667eea;
            background: #f0f3ff;
        }

        .file-upload-container input[type="file"] {
            display: none;
        }

        .file-upload-label {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: #667eea;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .file-upload-label:hover {
            background: #5a67d8;
        }

        .plate-input-group {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .plate-separator {
            font-weight: bold;
            color: #6c757d;
            user-select: none;
        }

        .plate-part {
            text-align: center;
        }

        #platePreview {
            font-family: monospace;
            font-weight: bold;
            font-size: 1.1em;
            letter-spacing: 1px;
        }

        /* آپلود عکس پروفایل - نسخه کامل */
        .avatar-upload {
            position: relative;
            max-width: 150px;
            margin: 0 auto 20px;
        }

        .avatar-upload .avatar-edit {
            position: absolute;
            right: 5px;
            bottom: 20px;
            z-index: 2;
        }

        .avatar-upload .avatar-edit input {
            display: none;
        }

        .avatar-upload .avatar-edit input+label {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            margin-bottom: 0;
            border-radius: 100%;
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: 3px solid #ffffff;
            box-shadow: 0 3px 10px rgba(0, 123, 255, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .avatar-upload .avatar-edit input+label:hover {
            background: linear-gradient(135deg, #0056b3, #004494);
            transform: scale(1.1) rotate(15deg);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
        }

        .avatar-upload .avatar-edit input+label::after {
            content: "\F4CB";
            /* آیکون camera-fill */
            font-family: "bootstrap-icons";
            color: #ffffff;
            font-size: 18px;
            font-weight: bold;
        }

        .avatar-upload .avatar-preview {
            width: 150px;
            height: 150px;
            position: relative;
            border-radius: 100%;
            border: 5px solid #f8f9fa;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }

        .avatar-upload .avatar-preview:hover {
            border-color: #007bff;
            box-shadow: 0 6px 18px rgba(0, 123, 255, 0.2);
        }

        .avatar-upload .avatar-preview>div {
            width: 100%;
            height: 100%;
            border-radius: 100%;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            transition: transform 0.3s ease;
        }

        .avatar-upload:hover .avatar-preview>div {
            transform: scale(1.05);
        }

        /* متن کمکی زیر آواتار */
        .avatar-upload .avatar-help {
            text-align: center;
            margin-top: 10px;
            font-size: 12px;
            color: #6c757d;
        }

        /* استایل برای شماره موبایل اضافه */
        .extra-phone-input {
            max-width: 400px;
        }
    </style>

    <link rel="stylesheet" href="node_modules/persian-datepicker/dist/css/persian-datepicker.min.css">
    <link rel="stylesheet" href="node_modules/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="node_modules/select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.rtl.min.css">

    <script src="node_modules/persian-date/dist/persian-date.min.js"></script>
    <script src="node_modules/persian-datepicker/dist/js/persian-datepicker.min.js"></script>
    <script src="node_modules/select2/dist/js/select2.full.min.js"></script>

    <script>
        const BASE_URL = "<?= base_url(); ?>";

        document.addEventListener("DOMContentLoaded", () => {
            setupPlateEvents();
            initDatePickers();
            initAvatarUpload();
            initFilePreviews();
            initSelect2();
        });

        function initSelect2() {
            // تنظیمات Select2 برای استان‌ها (با AJAX)
            $('.select2-province').select2({
                theme: 'bootstrap-5',
                placeholder: 'انتخاب استان',
                allowClear: true,
                ajax: {
                    url: `${BASE_URL}/ajax/locations/get_provinces_select2.php`,
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.items || [],
                            pagination: {
                                more: (params.page * 10) < (data.total_count || 0)
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 0,
                language: {
                    noResults: function() {
                        return "استانی یافت نشد";
                    },
                    searching: function() {
                        return "در حال جستجو...";
                    }
                }
            });

            // تنظیمات اولیه Select2 برای شهرها
            $('.select2-city').select2({
                theme: 'bootstrap-5',
                placeholder: 'ابتدا استان را انتخاب کنید',
                allowClear: true,
                disabled: true,
                language: {
                    noResults: function() {
                        return "ابتدا استان را انتخاب کنید";
                    }
                }
            });

            // رویداد تغییر استان برای راننده
            $('#province_id').on('select2:select', function(e) {
                const provinceId = $(this).val();
                loadCitiesAjax(provinceId, '#city_id');
            });

            // رویداد تغییر استان برای باربری
            $('#company_province_id').on('select2:select', function(e) {
                const provinceId = $(this).val();
                loadCitiesAjax(provinceId, '#company_city_id');
            });

            // رویداد clear برای استان
            $('.select2-province').on('select2:clear', function(e) {
                const targetSelector = $(this).attr('id') === 'province_id' ? '#city_id' : '#company_city_id';
                $(targetSelector).val('').trigger('change');
                $(targetSelector).prop('disabled', true);
                $(targetSelector).select2({
                    theme: 'bootstrap-5',
                    placeholder: 'ابتدا استان را انتخاب کنید',
                    allowClear: true,
                    disabled: true
                });
            });
        }

        // مقداردهی اولیه Select2
        function initDatePickers() {
            // تاریخ تولد
            $('#birth_date').val('').pDatepicker({
                format: 'YYYY/MM/DD',
                autoClose: true,
                persianDigit: false,
                maxDate: new persianDate().valueOf(),
                toolbox: {
                    calendarSwitch: {
                        enabled: true
                    },
                    todayButton: {
                        enabled: true,
                        text: "امروز"
                    }
                }
            });

            // تاریخ اتمام بیمه
            $('#insurance_expiry').val('').pDatepicker({
                format: 'YYYY/MM/DD',
                persianDigit: false,
                autoClose: true,
                toolbox: {
                    calendarSwitch: {
                        enabled: true
                    },
                    todayButton: {
                        enabled: true,
                        text: "امروز"
                    }
                }
            });

            // تاریخ ثبت شرکت
            $('#registration_date').val('').pDatepicker({
                format: 'YYYY/MM/DD',
                persianDigit: false,
                autoClose: true,
                toolbox: {
                    calendarSwitch: {
                        enabled: true
                    },
                    todayButton: {
                        enabled: true,
                        text: "امروز"
                    }
                }
            });
        }

        // بارگذاری شهرها با AJAX برای Select2
        function loadCitiesAjax(provinceId, targetSelector) {
            if (!provinceId) {
                $(targetSelector).html('<option value="">ابتدا استان را انتخاب کنید</option>');
                $(targetSelector).val('').trigger('change');
                return;
            }

            // غیرفعال کردن Select2 فعلی
            $(targetSelector).prop('disabled', true);

            // پاک کردن گزینه‌های قبلی
            $(targetSelector).empty().append('<option value="">در حال بارگذاری...</option>');
            $(targetSelector).val('').trigger('change');

            // اگر قبلاً select2 فعال بوده، destroy کنیم تا دوباره AJAX درست bind شود
            if ($(targetSelector).hasClass('select2-hidden-accessible')) {
                $(targetSelector).select2('destroy');
            }

            // تنظیمات جدید Select2 با AJAX
            $(targetSelector).select2({
                theme: 'bootstrap-5',
                placeholder: 'انتخاب شهر',
                allowClear: true,
                ajax: {
                    url: `${BASE_URL}/ajax/locations/get_cities_select2.php`,
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            province_id: provinceId,
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        const total = (data.total_count || 0);
                        return {
                            results: data.items || [],
                            pagination: {
                                more: (params.page * 10) < total
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 0,
                language: {
                    noResults: function() {
                        return "شهری یافت نشد";
                    },
                    searching: function() {
                        return "در حال جستجو...";
                    }
                }
            }).prop('disabled', false);
        }

        // آپلود عکس پروفایل
        function initAvatarUpload() {
            function readURL(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#avatarPreview').css('background-image', 'url(' + e.target.result + ')');
                        $('#avatarPreview').hide();
                        $('#avatarPreview').fadeIn(650);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#avatar").change(function() {
                readURL(this);
            });
        }

        // پیش‌نمایش فایل‌های آپلودی (کارت ملی، گواهینامه، ...)
        function initFilePreviews() {
            function previewImage(inputId, imgId) {
                const $in = $('#' + inputId);
                const $img = $('#' + imgId);
                if ($in.length === 0 || $img.length === 0) return;
                $in.on('change', function() {
                    const file = this.files && this.files[0] ? this.files[0] : null;
                    if (!file) {
                        $img.addClass('d-none').attr('src', '');
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $img.attr('src', e.target.result).removeClass('d-none');
                    };
                    reader.readAsDataURL(file);
                });
            }

            function previewVideo(inputId, videoId) {
                const $in = $('#' + inputId);
                const $vid = $('#' + videoId);
                if ($in.length === 0 || $vid.length === 0) return;
                $in.on('change', function() {
                    const file = this.files && this.files[0] ? this.files[0] : null;
                    if (!file) {
                        $vid.addClass('d-none').attr('src', '');
                        return;
                    }
                    const url = URL.createObjectURL(file);
                    $vid.attr('src', url).removeClass('d-none');
                });
            }

            previewImage('national_card_image', 'national_card_image_preview');
            previewImage('license_image', 'license_image_preview');
            previewImage('vehicle_card_image', 'vehicle_card_image_preview');
            previewImage('green_card_image', 'green_card_image_preview');
            previewImage('insurance_image', 'insurance_image_preview');
            previewImage('company_national_card_image', 'company_national_card_image_preview');
            previewVideo('verification_video', 'verification_video_preview');
        }

        function toggleExtraFields() {
            const userType = document.getElementById('user_type').value;
            const driverFields = document.getElementById('driverFields');
            const companyFields = document.getElementById('companyFields');

            driverFields.style.display = userType === '1' ? 'block' : 'none';
            companyFields.style.display = userType === '2' ? 'block' : 'none';

            // تنظیم مجدد Select2 پس از نمایش بخش‌ها
            setTimeout(() => {
                $('.select2-province').select2({
                    theme: 'bootstrap-5',
                    placeholder: 'انتخاب استان',
                    allowClear: true
                });

                $('.select2-city').select2({
                    theme: 'bootstrap-5',
                    placeholder: 'انتخاب شهر',
                    allowClear: true
                });
            }, 100);
        }

        function setupPlateEvents() {
            const plateInputs = document.querySelectorAll('.plate-part');
            plateInputs.forEach(input => {
                input.addEventListener('input', updatePlatePreview);
                input.addEventListener('change', updatePlatePreview);
            });

            document.querySelectorAll('input[type="number"].plate-part').forEach(input => {
                input.addEventListener('keypress', (e) => {
                    if (!/^\d$/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete' && e.key !== 'Tab') {
                        e.preventDefault();
                    }
                });

                input.addEventListener('input', function() {
                    const maxLength = parseInt(this.getAttribute('maxlength'));
                    if (this.value.length >= maxLength) {
                        const allInputs = Array.from(document.querySelectorAll('.plate-part'));
                        const currentIndex = allInputs.indexOf(this);
                        if (currentIndex < allInputs.length - 1) {
                            allInputs[currentIndex + 1].focus();
                        }
                    }
                });
            });
        }

        function updatePlatePreview() {
            const part1 = document.getElementById('plate_part1').value || '';
            const part2 = document.getElementById('plate_part2').value || '';
            const part3 = document.getElementById('plate_part3').value || '';
            const part4 = document.getElementById('plate_part4').value || '';

            let preview = '';
            if (part1) preview += part1;
            if (part2) preview += (preview ? ' ' : '') + part2;
            if (part3) preview += (preview ? ' ' : '') + part3;
            if (part4) preview += (preview ? ' ایران ' : '') + part4;

            document.getElementById('platePreview').textContent = preview || '---';
        }

        function togglePassword(btn) {
            const input = document.getElementById('passwordInput');
            const icon = btn.querySelector('i');
            if (!input) return;
            const show = (input.type === 'password');
            input.type = show ? 'text' : 'password';
            input.style.webkitTextSecurity = show ? 'none' : 'disc';
            if (icon) {
                icon.classList.toggle('ri-eye-line', show);
                icon.classList.toggle('ri-eye-off-line', !show);
            }
        }

        // اضافه کردن شماره موبایل اضافه
        function addExtraPhone() {
            const container = document.getElementById('extraPhonesContainer');
            const newInput = document.createElement('div');
            newInput.className = 'input-group mb-2 extra-phone-input';
            newInput.innerHTML = `
            <input type="tel" class="form-control" name="extra_phones[]" maxlength="11" placeholder="مثال: 09123456789">
            <button type="button" class="btn btn-outline-danger remove-extra-phone" onclick="removeExtraPhone(this)">
                <i class="ri-delete-bin-line"></i>
            </button>
            `;
            container.appendChild(newInput);
        }

        function removeExtraPhone(button) {
            const inputGroup = button.closest('.extra-phone-input');
            if (inputGroup) {
                inputGroup.remove();
            }
        }

        function loader(title) {
            Swal.fire({
                title: title || 'در حال پردازش...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => Swal.showLoading()
            });
        }

        async function saveUser() {
            const form = document.getElementById('userForm');
            const fd = new FormData(form);

            // ترکیب پلاک
            const platePart1 = document.getElementById('plate_part1').value;
            const platePart2 = document.getElementById('plate_part2').value;
            const platePart3 = document.getElementById('plate_part3').value;
            const platePart4 = document.getElementById('plate_part4').value;

            if (platePart1 && platePart2 && platePart3 && platePart4) {
                fd.append('plate_number', `${platePart1} ${platePart2} ${platePart3} ایران ${platePart4}`);
            }

            loader('در حال ذخیره...');
            try {
                const res = await fetch(`${BASE_URL}/ajax/users/users_save.php`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
                    body: fd
                });

                const data = await res.json();
                Swal.close();

                if (!res.ok || !data.ok) {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطا',
                        text: data.message || 'ذخیره ناموفق بود'
                    });
                    return;
                }

                Swal.fire({
                    icon: 'success',
                    title: 'موفق',
                    text: 'ذخیره شد'
                });
                setTimeout(() => location.href = 'users_list.php', 1000);

            } catch (e) {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'خطا',
                    text: 'خطا در ارتباط با سرور'
                });
                console.log(e);
            }
        }
    </script>

    <?php require_once "views/panel/footer.php"; ?>
