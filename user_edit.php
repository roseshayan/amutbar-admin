<?php
require_once __DIR__ . '/includes/init.php';
require_admin();
require_once __DIR__ . '/includes/app_roles.php';

$id = (int)($_GET["id"] ?? 0);
if ($id <= 0) redirect("users_list.php");

require_once "views/panel/header.php";
require_once "views/panel/sidebar.php";

require_once __DIR__ . '/includes/jdf.php';

// دریافت اطلاعات کاربر و اطلاعات راننده (اگر وجود دارد)
$user = users_get($id);
if (!$user) {
    redirect("users_list.php");
}

// اگر کاربر راننده است، اطلاعات راننده را بگیر
$driverInfo = null;
if (($user['user_type'] ?? 0) == 1) {
    $driverInfo = get_driver_info($user['id']);
}

// اطلاعات باربری
$companyInfo = null;
if (($user['user_type'] ?? 0) == 2) {
    $companyInfo = get_company_info($user['id']);
}

// --- کدهای جدید برای دریافت فایل‌های آپلودی کاربر ---
function get_user_files($userId)
{
    $pdo = db();
    $st = $pdo->prepare("SELECT file_type, file_key FROM user_files WHERE user_id = ?");
    $st->execute([$userId]);
    $files = [];
    while ($row = $st->fetch()) {
        $files[$row['file_type']] = $row['file_key'];
    }
    return $files;
}

$userFiles = get_user_files($id);

// تابعی برای ساخت URL کامل تصویر (با توجه به BASE_URL یا مسیر نسبی)
function get_file_url($fileKey)
{
    if (empty($fileKey)) return '';
    if (preg_match('~^https?://~i', $fileKey)) return $fileKey;
    return '/' . ltrim($fileKey, '/');
}
// ---------------------------------------------------

// تابع کمکی برای دریافت اطلاعات راننده
function get_driver_info($userId)
{
    $pdo = db();
    $st = $pdo->prepare("SELECT * FROM drivers WHERE user_id = ? AND deleted_at IS NULL LIMIT 1");
    $st->execute([$userId]);
    return $st->fetch() ?: null;
}

// تابع کمکی برای دریافت اطلاعات باربری
function get_company_info($userId)
{
    $pdo = db();
    $st = $pdo->prepare("SELECT * FROM companies WHERE user_id = ? AND deleted_at IS NULL LIMIT 1");
    $st->execute([$userId]);
    return $st->fetch() ?: null;
}

$extraPhones = [];
if (!empty($driverInfo['extra_phones'])) {
    $tmp = json_decode((string)$driverInfo['extra_phones'], true);
    if (is_array($tmp)) $extraPhones = $tmp;
}

function jdate_str_to_gdate(string $jdate): ?string
{
    $jdate = trim($jdate);
    if (!preg_match('/^(\d{4})[\/-](\d{1,2})[\/-](\d{1,2})$/', $jdate, $m)) {
        return null;
    }
    [$gy, $gm, $gd] = jalali_to_gregorian((int)$m[1], (int)$m[2], (int)$m[3]);
    return sprintf('%04d-%02d-%02d', $gy, $gm, $gd);
}

?>

<div class="main-content app-content">
    <div class="container-fluid">

        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-2">ویرایش کاربر</h1>
                <div>
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="dashboard.php">داشبورد</a></li>
                            <li class="breadcrumb-item"><a href="users_list.php">کاربران</a></li>
                            <li class="breadcrumb-item active" aria-current="page">ویرایش</li>
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
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <input type="hidden" name="driver_id" value="<?php echo $driverInfo['id'] ?? 0; ?>">
                            <input type="hidden" name="company_id" value="<?php echo $companyInfo['id'] ?? 0; ?>">

                            <?php
                            $avatarBg = !empty($user['avatar_key']) ? (string)$user['avatar_key'] : asset('images/faces/default-avatar.png');
                            if ($avatarBg !== '' && !preg_match('~^https?://~i', $avatarBg) && $avatarBg[0] !== '/') {
                                $avatarBg = '/' . ltrim($avatarBg, '/');
                            }
                            ?>
                            <!-- آپلود عکس پروفایل -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <label for="avatar" class="form-label">عکس پروفایل</label>
                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type='file' id="avatar" name="avatar"
                                                accept=".png, .jpg, .jpeg, .webp" />
                                            <label for="avatar"></label>
                                        </div>
                                        <div class="avatar-preview">
                                            <div id="avatarPreview"
                                                style="background-image: url('<?= htmlspecialchars($avatarBg, ENT_QUOTES, 'UTF-8') ?>');"></div>
                                        </div>
                                        <div class="avatar-help"><small>برای تغییر کلیک کنید</small></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6 col-lg-3 mb-3">
                                    <label for="full_name" class="form-label">نام و نام خانوادگی</label>
                                    <input id="full_name" class="form-control" name="full_name"
                                        value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>"
                                        required>
                                </div>
                                <div class="col-md-6 col-lg-3 mb-3">
                                    <label for="phone" class="form-label">شماره موبایل</label>
                                    <input id="phone" class="form-control" name="phone" maxlength="11"
                                        style="direction:ltr"
                                        value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-6 col-lg-3 mb-3">
                                    <label for="code_meli" class="form-label">کد ملی</label>
                                    <input id="code_meli" type="tel" maxlength="10" class="form-control"
                                        name="code_meli" style="direction:ltr"
                                        value="<?php echo htmlspecialchars($user['code_meli'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6 col-lg-3 mb-3">
                                    <label for="birth_date" class="form-label">تاریخ تولد</label>
                                    <input id="birth_date" class="form-control" name="birth_date"
                                        value="<?php
                                                $birthDate = jdate_str_to_gdate(htmlspecialchars($user['birth_date'] ?? ''));
                                                echo $birthDate;
                                                ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-lg-3 mb-3">
                                    <label for="father_name" class="form-label">نام پدر</label>
                                    <input id="father_name" type="text" class="form-control" name="father_name"
                                        value="<?php echo htmlspecialchars($user['father_name'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6 col-lg-3 mb-3">
                                    <label>جنسیت</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="men"
                                            value="1" <?php echo ($user['gender'] ?? 0) == 1 ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="men">
                                            آقا
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="women"
                                            value="2" <?php echo ($user['gender'] ?? 0) == 2 ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="women">
                                            خانم
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3 mb-3">
                                    <label for="national_card_serial" class="form-label">سریال کارت ملی</label>
                                    <input id="national_card_serial" type="text" class="form-control"
                                        name="national_card_serial"
                                        value="<?php echo htmlspecialchars($user['national_card_serial'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6 col-lg-3 mb-3">
                                    <label for="user_type" class="form-label">نوع کاربر</label>
                                    <select id="user_type" class="form-select" name="user_type" required
                                        onchange="toggleExtraFields()">
                                        <option value="1" <?php echo ($user['user_type'] ?? 0) == 1 ? 'selected' : ''; ?>>
                                            راننده
                                        </option>
                                        <option value="2" <?php echo ($user['user_type'] ?? 0) == 2 ? 'selected' : ''; ?>>
                                            باربری
                                        </option>
                                        <option value="3" <?php echo ($user['user_type'] ?? 0) == 3 ? 'selected' : ''; ?>>
                                            ادمین
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 col-lg-3 mb-3">
                                    <label for="display_name" class="form-label">نام نمایشی</label>
                                    <input id="display_name" type="text" class="form-control" name="display_name"
                                        value="<?php echo htmlspecialchars($user['display_name'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6 col-lg-3 mb-3">
                                    <label for="email" class="form-label">ایمیل (اختیاری)</label>
                                    <input id="email" type="email" class="form-control" name="email"
                                        style="direction:ltr"
                                        value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6 col-lg-3 mb-3">
                                    <label for="passwordInput" class="form-label">رمز عبور (برای ادمین الزامی، برای
                                        سایرین اختیاری)</label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control" name="password"
                                            id="passwordInput"
                                            placeholder="رمز عبور جدید (در صورت تغییر)">
                                        <a href="javascript:void(0);" class="show-password-button text-muted"
                                            onclick="togglePassword(this)">
                                            <i class="ri-eye-off-line align-middle"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="status" class="form-label">وضعیت</label>
                                    <select id="status" class="form-select" name="status" required>
                                        <option value="1" <?php echo ($user['status'] ?? 3) == 1 ? 'selected' : ''; ?>>
                                            فعال
                                        </option>
                                        <option value="2" <?php echo ($user['status'] ?? 3) == 2 ? 'selected' : ''; ?>>
                                            غیرفعال
                                        </option>
                                        <option value="3" <?php echo ($user['status'] ?? 3) == 3 ? 'selected' : ''; ?>>
                                            در انتظار
                                        </option>
                                        <option value="4" <?php echo ($user['status'] ?? 3) == 4 ? 'selected' : ''; ?>>
                                            مسدود
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- بخش اضافی برای راننده -->
                            <div id="driverFields" class="card mt-4"
                                style="display: <?php echo ($user['user_type'] ?? 0) == 1 ? 'block' : 'none'; ?>;">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">اطلاعات راننده</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- پلاک ماشین با فرمت جدا -->
                                        <div class="col-12 mb-4">
                                            <label for="plate_part1" class="form-label">پلاک ماشین</label>
                                            <div class="d-flex flex-wrap align-items-center gap-2"
                                                style="gap: 0.5rem;">
                                                <div class="plate-input-group">
                                                    <input type="number" class="form-control plate-part"
                                                        id="plate_part1" name="plate_part1"
                                                        maxlength="2" min="10" max="99"
                                                        placeholder="12"
                                                        value="<?php echo $driverInfo ? extract_plate_part($driverInfo['plate_number'] ?? '', 1) : ''; ?>"
                                                        style="width: 70px; text-align: center;">
                                                    <div class="plate-separator">–</div>
                                                </div>

                                                <div class="plate-input-group">
                                                    <select aria-label="" class="form-select plate-part"
                                                        id="plate_part2" name="plate_part2"
                                                        style="width: 90px;">
                                                        <option value="">حرف</option>
                                                        <?php
                                                        $persianLetters = ['الف', 'ب', 'پ', 'ت', 'ث', 'ج', 'چ', 'ح', 'خ', 'د', 'ذ', 'ر', 'ز', 'ژ', 'س', 'ش', 'ص', 'ض', 'ط', 'ظ', 'ع', 'غ', 'ف', 'ق', 'ک', 'گ', 'ل', 'م', 'ن', 'و', 'ه', 'ی'];
                                                        $currentLetter = $driverInfo ? extract_plate_part($driverInfo['plate_number'] ?? '', 2) : '';
                                                        foreach ($persianLetters as $letter) {
                                                            $selected = ($currentLetter === $letter) ? 'selected' : '';
                                                            echo "<option value=\"$letter\" $selected>$letter</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                    <div class="plate-separator">–</div>
                                                </div>

                                                <div class="plate-input-group">
                                                    <input aria-label="" type="number"
                                                        class="form-control plate-part"
                                                        id="plate_part3" name="plate_part3"
                                                        maxlength="3" min="100" max="999"
                                                        placeholder="345"
                                                        value="<?php echo $driverInfo ? extract_plate_part($driverInfo['plate_number'] ?? '', 3) : ''; ?>"
                                                        style="width: 90px; text-align: center;">
                                                    <div class="plate-separator">ایران</div>
                                                </div>

                                                <div class="plate-input-group">
                                                    <input aria-label="" type="number"
                                                        class="form-control plate-part"
                                                        id="plate_part4" name="plate_part4"
                                                        maxlength="2" min="10" max="99"
                                                        placeholder="67"
                                                        value="<?php echo $driverInfo ? extract_plate_part($driverInfo['plate_number'] ?? '', 4) : ''; ?>"
                                                        style="width: 70px; text-align: center;">
                                                </div>

                                                <div class="ms-3">
                                                    <span class="badge bg-light text-dark border">
                                                        <span id="platePreview"><?php echo htmlspecialchars($driverInfo['plate_number'] ?? ''); ?></span>
                                                    </span>
                                                </div>
                                            </div>
                                            <small class="text-muted d-block mt-2">فرمت: 12 الف 345 ایران 67</small>
                                        </div>

                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <label for="smart_card_number" class="form-label">شماره کارت
                                                هوشمند</label>
                                            <input type="text" class="form-control" id="smart_card_number"
                                                name="smart_card_number"
                                                value="<?php echo htmlspecialchars($driverInfo['smart_card_number'] ?? ''); ?>">
                                        </div>

                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <label for="vehicle_type_id" class="form-label">نوع وسیله نقلیه</label>
                                            <select class="form-select" id="vehicle_type_id" name="vehicle_type_id">
                                                <option value="">انتخاب کنید</option>
                                                <?php
                                                $vehicleTypes = get_vehicle_types();
                                                foreach ($vehicleTypes as $type) {
                                                    $selected = ($driverInfo && $driverInfo['vehicle_type_id'] == $type['id']) ? 'selected' : '';
                                                    echo "<option value=\"{$type['id']}\" $selected>{$type['title']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <label for="model_year" class="form-label">سال ساخت</label>
                                            <select class="form-select" id="model_year" name="model_year">
                                                <option value="">انتخاب کنید</option>
                                                <?php
                                                $currentYear = jdate('Y');
                                                for ($year = convertPersianNumbersToEnglishPHP($currentYear); $year >= 1360; $year--) {
                                                    $selected = ($driverInfo && $driverInfo['model_year'] == $year) ? 'selected' : '';
                                                    echo "<option value=\"$year\" $selected>$year</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <label for="color" class="form-label">رنگ</label>
                                            <input type="text" class="form-control" id="color" name="color"
                                                value="<?php echo htmlspecialchars($driverInfo['color'] ?? ''); ?>">
                                        </div>

                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <label for="capacity_kg" class="form-label">ظرفیت (کیلوگرم)</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="capacity_kg"
                                                    name="capacity_kg" min="0" step="0.01"
                                                    value="<?php echo $driverInfo ? htmlspecialchars($driverInfo['capacity_kg'] ?? '') : ''; ?>">
                                                <span class="input-group-text">کیلوگرم</span>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <label for="province_id" class="form-label">استان</label>
                                            <select class="form-select select2-province" id="province_id"
                                                name="province_id">
                                                <option value="">انتخاب استان</option>
                                                <?php
                                                $provinces = get_provinces();
                                                foreach ($provinces as $province) {
                                                    $selected = ($driverInfo && $driverInfo['province_id'] == $province['id']) ? 'selected' : '';
                                                    echo "<option value=\"{$province['id']}\" $selected>{$province['name']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <label for="city_id" class="form-label">شهر</label>
                                            <select class="form-select select2-city" id="city_id" name="city_id">
                                                <option value="">ابتدا استان را انتخاب کنید</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <label for="issued_from" class="form-label">صادره از</label>
                                            <input type="text" class="form-control" id="issued_from"
                                                name="issued_from"
                                                value="<?= htmlspecialchars($driverInfo['issued_from'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <label for="home_phone" class="form-label">شماره منزل</label>
                                            <input type="tel" class="form-control" id="home_phone" name="home_phone"
                                                maxlength="11"
                                                value="<?= htmlspecialchars($driverInfo['home_phone'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <label for="postal_code" class="form-label">کد پستی</label>
                                            <input type="text" class="form-control" id="postal_code"
                                                name="postal_code" maxlength="10"
                                                value="<?= htmlspecialchars($driverInfo['postal_code'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <label for="license_serial" class="form-label">شماره گواهینامه</label>
                                            <input type="text" class="form-control" id="license_serial"
                                                name="license_serial"
                                                value="<?= htmlspecialchars($driverInfo['license_serial'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <label for="license_base" class="form-label">پایه گواهینامه</label>
                                            <?php $lb = (string)($driverInfo['license_base'] ?? ''); ?>
                                            <select class="form-select" id="license_base" name="license_base">
                                                <option value="">انتخاب کنید</option>
                                                <option value="اول" <?= $lb === 'اول' ? 'selected' : '' ?>>اول
                                                </option>
                                                <option value="دوم" <?= $lb === 'دوم' ? 'selected' : '' ?>>دوم
                                                </option>
                                                <option value="سوم" <?= $lb === 'سوم' ? 'selected' : '' ?>>سوم
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <label for="vin_number" class="form-label">شماره VIN</label>
                                            <input type="text" class="form-control" id="vin_number"
                                                name="vin_number"
                                                value="<?= htmlspecialchars($driverInfo['vin_number'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <label for="insurance_number" class="form-label">شماره بیمه نامه</label>
                                            <input type="text" class="form-control" id="insurance_number"
                                                name="insurance_number"
                                                value="<?= htmlspecialchars($driverInfo['insurance_number'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <label for="insurance_expiry" class="form-label">تاریخ اتمام
                                                بیمه</label>
                                            <input type="text" class="form-control" id="insurance_expiry"
                                                name="insurance_expiry"
                                                value="<?= htmlspecialchars($driverInfo['insurance_expiry'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <label for="engine_number" class="form-label">شماره موتور</label>
                                            <input type="text" class="form-control" id="engine_number"
                                                name="engine_number"
                                                value="<?= htmlspecialchars($driverInfo['engine_number'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <label for="chassis_number" class="form-label">شماره شاسی</label>
                                            <input type="text" class="form-control" id="chassis_number"
                                                name="chassis_number"
                                                value="<?= htmlspecialchars($driverInfo['chassis_number'] ?? '') ?>">
                                        </div>
                                        <?php $vs = (int)($driverInfo['verification_status'] ?? 0); ?>
                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <label for="driver_verification_status" class="form-label">وضعیت احراز هویت</label>
                                            <select class="form-select" id="driver_verification_status" name="driver_verification_status" onchange="toggleDriverRejectReason()">
                                                <option value="0" <?= $vs === 0 ? 'selected' : '' ?>>تأیید نشده</option>
                                                <option value="1" <?= $vs === 1 ? 'selected' : '' ?>>تأیید شده</option>
                                                <option value="2" <?= $vs === 2 ? 'selected' : '' ?>>رد شده</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 mb-3" id="driverRejectReasonWrapper" style="display: <?= $vs === 2 ? 'block' : 'none' ?>;">
                                            <label for="driver_reject_reason" class="form-label text-danger">دلیل رد احراز هویت (نمایش به کاربر)</label>
                                            <textarea class="form-control border-danger" id="driver_reject_reason" name="driver_reject_reason" rows="2" placeholder="دلیل رد شدن مدارک یا مشخصات را بنویسید..."><?= htmlspecialchars($driverInfo['reject_reason'] ?? '') ?></textarea>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="address" class="form-label">آدرس</label>
                                            <textarea class="form-control" id="address" name="address"
                                                rows="2"><?= htmlspecialchars($driverInfo['address'] ?? '') ?></textarea>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label class="form-label">شماره موبایل اضافه</label>
                                            <div id="extraPhonesContainer">
                                                <?php if (!empty($extraPhones)): ?>
                                                    <?php foreach ($extraPhones as $ph): ?>
                                                        <div class="input-group mb-2 extra-phone-input">
                                                            <input aria-label="extra_phones" type="tel"
                                                                class="form-control" name="extra_phones[]"
                                                                maxlength="11"
                                                                value="<?= htmlspecialchars((string)$ph) ?>"
                                                                placeholder="مثال: 09123456789">
                                                            <button type="button"
                                                                class="btn btn-outline-danger remove-extra-phone"
                                                                onclick="removeExtraPhone(this)">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </button>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <div class="input-group mb-2 extra-phone-input">
                                                        <input aria-label="extra_phones" type="tel"
                                                            class="form-control" name="extra_phones[]"
                                                            maxlength="11" placeholder="مثال: 09123456789">
                                                        <button type="button"
                                                            class="btn btn-outline-danger remove-extra-phone"
                                                            onclick="removeExtraPhone(this)">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-primary mt-2"
                                                onclick="addExtraPhone()">
                                                <i class="ri-add-line"></i> افزودن شماره موبایل
                                            </button>
                                        </div>
                                        <!-- آپلود فایل‌ها برای راننده -->
                                        <div id="driverFiles" class="card mt-4">
                                            <div class="card-header">
                                                <h5 class="card-title mb-0">آپلود فایل‌های راننده</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6 col-lg-4 mb-3">
                                                        <label for="national_card_image" class="form-label">عکس کارت ملی</label>
                                                        <input type="file" class="form-control" id="national_card_image" name="national_card_image" accept="image/*">
                                                        <?php
                                                        $ncImg = get_file_url($userFiles[2] ?? ''); // 2 برای کارت ملی
                                                        ?>
                                                        <img id="national_card_image_preview" class="img-thumbnail mt-2 <?= $ncImg ? '' : 'd-none' ?>" src="<?= htmlspecialchars($ncImg) ?>" alt="preview">
                                                    </div>

                                                    <div class="col-md-6 col-lg-4 mb-3">
                                                        <label for="license_image" class="form-label">عکس گواهینامه</label>
                                                        <input type="file" class="form-control" id="license_image" name="license_image" accept="image/*">
                                                        <?php
                                                        $lcImg = get_file_url($userFiles[3] ?? ''); // 3 برای گواهینامه
                                                        ?>
                                                        <img id="license_image_preview" class="img-thumbnail mt-2 <?= $lcImg ? '' : 'd-none' ?>" src="<?= htmlspecialchars($lcImg) ?>" alt="preview">
                                                    </div>

                                                    <div class="col-md-6 col-lg-4 mb-3">
                                                        <label for="vehicle_card_image" class="form-label">عکس کارت ماشین</label>
                                                        <input type="file" class="form-control" id="vehicle_card_image" name="vehicle_card_image" accept="image/*">
                                                        <?php
                                                        $vcImg = get_file_url($userFiles[4] ?? ''); // 4 برای کارت ماشین
                                                        ?>
                                                        <img id="vehicle_card_image_preview" class="img-thumbnail mt-2 <?= $vcImg ? '' : 'd-none' ?>" src="<?= htmlspecialchars($vcImg) ?>" alt="preview">
                                                    </div>

                                                    <div class="col-md-6 col-lg-4 mb-3">
                                                        <label for="green_card_image" class="form-label">عکس برگه سبز</label>
                                                        <input type="file" class="form-control" id="green_card_image" name="green_card_image" accept="image/*">
                                                        <?php
                                                        $gcImg = get_file_url($userFiles[5] ?? ''); // 5 برای برگه سبز
                                                        ?>
                                                        <img id="green_card_image_preview" class="img-thumbnail mt-2 <?= $gcImg ? '' : 'd-none' ?>" src="<?= htmlspecialchars($gcImg) ?>" alt="preview">
                                                    </div>

                                                    <div class="col-md-6 col-lg-4 mb-3">
                                                        <label for="insurance_image" class="form-label">عکس بیمه نامه خودرو</label>
                                                        <input type="file" class="form-control" id="insurance_image" name="insurance_image" accept="image/*">
                                                        <?php
                                                        $inImg = get_file_url($userFiles[7] ?? ''); // 7 برای بیمه
                                                        ?>
                                                        <img id="insurance_image_preview" class="img-thumbnail mt-2 <?= $inImg ? '' : 'd-none' ?>" src="<?= htmlspecialchars($inImg) ?>" alt="preview">
                                                    </div>

                                                    <div class="col-md-6 col-lg-4 mb-3">
                                                        <label for="verification_video" class="form-label">ویدئو احراز هویت</label>
                                                        <input type="file" class="form-control" id="verification_video" name="verification_video" accept="video/*">
                                                        <?php
                                                        $vidUrl = get_file_url($userFiles[6] ?? ''); // 6 برای ویدیو
                                                        ?>
                                                        <video id="verification_video_preview" class="mt-2 <?= $vidUrl ? '' : 'd-none' ?>" controls style="max-width:100%;height:auto" src="<?= htmlspecialchars($vidUrl) ?>"></video>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- بخش اضافی برای باربری -->
                            <div id="companyFields" class="card mt-4"
                                style="display: <?php echo ($user['user_type'] ?? 0) == 2 ? 'block' : 'none'; ?>;">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">اطلاعات باربری</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="company_name" class="form-label">نام باربری</label>
                                            <input type="text" class="form-control" id="company_name"
                                                name="company_name"
                                                value="<?php echo htmlspecialchars($companyInfo['company_name'] ?? ''); ?>">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="registration_no" class="form-label">شماره ثبت</label>
                                            <input type="text" class="form-control" id="registration_no"
                                                name="registration_no"
                                                value="<?php echo htmlspecialchars($companyInfo['registration_no'] ?? ''); ?>">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="registration_date" class="form-label">تاریخ ثبت</label>
                                            <input type="text" class="form-control" id="registration_date"
                                                name="registration_date"
                                                value="<?= htmlspecialchars($companyInfo['registration_date'] ?? '') ?>"
                                                placeholder="انتخاب تاریخ">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="economic_code" class="form-label">کد اقتصادی</label>
                                            <input type="text" class="form-control" id="economic_code"
                                                name="economic_code"
                                                value="<?php echo htmlspecialchars($companyInfo['economic_code'] ?? ''); ?>">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="company_province_id" class="form-label">استان</label>
                                            <select class="form-select select2-province" id="company_province_id"
                                                name="company_province_id">
                                                <option value="">انتخاب استان</option>
                                                <?php
                                                foreach ($provinces as $province) {
                                                    $selected = ($companyInfo && $companyInfo['province_id'] == $province['id']) ? 'selected' : '';
                                                    echo "<option value=\"{$province['id']}\" $selected>{$province['name']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="company_city_id" class="form-label">شهر</label>
                                            <select class="form-select select2-city" id="company_city_id"
                                                name="company_city_id">
                                                <option value="">ابتدا استان را انتخاب کنید</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="company_postal_code" class="form-label">کد پستی</label>
                                            <input type="text" class="form-control" id="company_postal_code"
                                                name="company_postal_code" maxlength="10"
                                                value="<?= htmlspecialchars($companyInfo['postal_code'] ?? '') ?>">
                                        </div>

                                        <?php $vsComp = (int)($companyInfo['verification_status'] ?? 0); ?>
                                        <div class="col-md-6 mb-3">
                                            <label for="company_verification_status" class="form-label">وضعیت احراز هویت</label>
                                            <select class="form-select" id="company_verification_status" name="company_verification_status" onchange="toggleCompanyRejectReason()">
                                                <option value="0" <?= $vsComp === 0 ? 'selected' : '' ?>>تأیید نشده</option>
                                                <option value="1" <?= $vsComp === 1 ? 'selected' : '' ?>>تأیید شده</option>
                                                <option value="2" <?= $vsComp === 2 ? 'selected' : '' ?>>رد شده</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 mb-3" id="companyRejectReasonWrapper" style="display: <?= $vsComp === 2 ? 'block' : 'none' ?>;">
                                            <label for="company_reject_reason" class="form-label text-danger">دلیل رد احراز هویت (نمایش به کاربر)</label>
                                            <textarea class="form-control border-danger" id="company_reject_reason" name="company_reject_reason" rows="2" placeholder="دلیل رد شدن مدارک را بنویسید..."><?= htmlspecialchars($companyInfo['reject_reason'] ?? '') ?></textarea>
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label for="company_address" class="form-label">آدرس</label>
                                            <textarea class="form-control" id="company_address"
                                                name="company_address"
                                                rows="2"><?php echo htmlspecialchars($companyInfo['address'] ?? ''); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if (($user['user_type'] ?? 0) == 2): ?>
                                <div id="companyFiles" class="card mt-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">آپلود فایل‌های باربری</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <label for="company_national_card_image" class="form-label">عکس کارت
                                                    ملی</label>
                                                <input type="file" class="form-control"
                                                    id="company_national_card_image"
                                                    name="company_national_card_image" accept="image/*">
                                                <img id="company_national_card_image_preview"
                                                    class="img-thumbnail mt-2 d-none" alt="preview">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="mt-4 d-flex gap-2">
                                <button class="btn btn-primary" onclick="saveUser()">ذخیره</button>
                                <a class="btn btn-outline-secondary" href="users_list.php">انصراف</a>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>

        <div class="text-muted small mb-2">
            یادداشت: بخش احراز هویت در همین صفحه قابل اجراست (برای اجرای سرویس بایومتریک نیاز است ویدئو از اپلیکیشن
            ارسال شود).
        </div>
        <div class="row mt-4">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">احراز هویت</div>
                    </div>
                    <div class="card-body">

                        <label class="form-label" for="idvAppRole">اپلیکیشن مورد استعلام</label>
                        <select class="form-select mb-3" id="idvAppRole">
                            <?php foreach ([1 => 'رانندگان', 2 => 'اعلام بار'] as $role => $label): ?>
                                <?php if (api_user_has_app_role($user, $role)): ?>
                                    <option value="<?= $role ?>" <?= (int)$user['user_type'] === $role ? 'selected' : '' ?>><?= $label ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <label class="form-label" for="idvIp">IP مورد استعلام (فقط برای سرویس موقعیت IP)</label>
                        <input class="form-control mb-3" id="idvIp" dir="ltr" placeholder="مثلاً 8.8.8.8">
                        <div class="text-muted mb-3">ابتدا تغییرات اطلاعات کاربر را ذخیره کنید. هر اجرای دستی ممکن است از اعتبار سرویس کسر کند.</div>
                        <div id="idvServicesWrap" class="row g-2"></div>

                        <div class="mt-3 d-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn-primary" id="btnRunIdv">
                                اجرای احراز هویت انتخاب‌شده
                            </button>
                            <button type="button" class="btn btn-light" id="btnReloadIdv">
                                بارگذاری مجدد
                            </button>
                        </div>

                        <hr class="my-3">
                        <div class="mb-2 fw-semibold">نتیجه اجرا</div>
                        <pre id="idvOutput" class="p-3 bg-light"
                            style="max-height:320px; overflow:auto; direction:ltr; text-align:left;"></pre>

                        <div class="text-muted small mt-2">
                            ویدئو باید در نسخهٔ جدید همان اپ با متن ضبط معتبر ارسال شده باشد.
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
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
</style>

<link rel="stylesheet" href="node_modules/persian-datepicker/dist/css/persian-datepicker.min.css">
<link rel="stylesheet" href="node_modules/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="node_modules/select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.rtl.min.css">

<script src="node_modules/persian-date/dist/persian-date.min.js"></script>
<script src="node_modules/persian-datepicker/dist/js/persian-datepicker.min.js"></script>
<script src="node_modules/select2/dist/js/select2.full.min.js"></script>

<script>
    const BASE_URL = "<?= base_url(); ?>";
    const USER_ID = <?php echo (int)$id; ?>;
    const USER_TYPE = <?php echo (int)($user['user_type'] ?? 0); ?>;

    // وضعیت اولیه شهرها
    const driverCityId = <?php echo $driverInfo ? ($driverInfo['city_id'] ?? 0) : 0; ?>;
    const companyCityId = <?php echo $companyInfo ? ($companyInfo['city_id'] ?? 0) : 0; ?>;
    const driverProvinceId = <?php echo $driverInfo ? ($driverInfo['province_id'] ?? 0) : 0; ?>;
    const companyProvinceId = <?php echo $companyInfo ? ($companyInfo['province_id'] ?? 0) : 0; ?>;

    document.addEventListener("DOMContentLoaded", () => {
        initSelect2();
        initDatePickers();
        initAvatarUpload();
        initFilePreviews();

        // بارگذاری اولیه شهرها
        if (driverProvinceId > 0) {
            setTimeout(() => loadCities(driverProvinceId), 100);
        }
        if (companyProvinceId > 0) {
            setTimeout(() => loadCompanyCities(companyProvinceId), 100);
        }

        // رویدادهای پلاک
        setupPlateEvents();

        // مقداردهی اولیه پلاک
        updatePlatePreview();
    });

    function initSelect2() {
        if (!window.jQuery || !$.fn.select2) return;
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

        // وقتی استان تغییر کرد، شهرها دوباره لود شوند
        $('#province_id').on('change', function() {
            loadCities(this.value);
        });
        $('#company_province_id').on('change', function() {
            loadCompanyCities(this.value);
        });
    }

    function initDatePickers() {
        if (!window.jQuery || !$.fn.pDatepicker) return;
        $('#birth_date').val('').pDatepicker({
            format: 'YYYY/MM/DD',
            persianDigit: false,
            autoClose: true,
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
        $('#insurance_expiry').pDatepicker({
            format: 'YYYY/MM/DD',
            persianDigit: false,
            autoClose: true,
            initialValue: false,
            maxDate: new persianDate().valueOf(),
        });
        $('#registration_date').pDatepicker({
            format: 'YYYY/MM/DD',
            persianDigit: false,
            autoClose: true,
            initialValue: false,
            maxDate: new persianDate().valueOf(),
        });
    }

    function initAvatarUpload() {
        const $in = $('#avatar');
        if (!$in.length) return;
        $in.on('change', function() {
            const file = this.files && this.files[0] ? this.files[0] : null;
            if (!file) return;
            if (!/^image\//.test(file.type)) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#avatarPreview').css('background-image', `url(${e.target.result})`);
            };
            reader.readAsDataURL(file);
        });
    }

    function initFilePreviews() {
        function previewImage(inputId, imgId) {
            const $in = $('#' + inputId);
            const $img = $('#' + imgId);
            if (!$in.length || !$img.length) return;
            $in.on('change', function() {
                const file = this.files && this.files[0] ? this.files[0] : null;
                if (!file) {
                    $img.addClass('d-none').attr('src', '');
                    return;
                }
                if (!/^image\//.test(file.type)) {
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
            const $v = $('#' + videoId);
            if (!$in.length || !$v.length) return;
            $in.on('change', function() {
                const file = this.files && this.files[0] ? this.files[0] : null;
                if (!file) {
                    $v.addClass('d-none');
                    return;
                }
                const url = URL.createObjectURL(file);
                $v.removeClass('d-none').attr('src', url);
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
    }

    function setupPlateEvents() {
        // رویدادهای تغییر برای بخش‌های پلاک
        const plateInputs = document.querySelectorAll('.plate-part');
        plateInputs.forEach(input => {
            input.addEventListener('input', updatePlatePreview);
            input.addEventListener('change', updatePlatePreview);
        });

        // جلوگیری از وارد کردن کاراکتر غیرعدد در فیلدهای عددی
        document.querySelectorAll('input[type="number"].plate-part').forEach(input => {
            input.addEventListener('keypress', (e) => {
                if (!/^\d$/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete' && e.key !== 'Tab') {
                    e.preventDefault();
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

        // انتقال اتوماتیک بین فیلدها
        autoTabPlateFields();
    }

    function autoTabPlateFields() {
        const part1 = document.getElementById('plate_part1');
        const part3 = document.getElementById('plate_part3');
        const part4 = document.getElementById('plate_part4');

        // اگر فیلد اول کامل شد، به فیلد حرفی (که سلکت است) نرویم
        if (part1.value.length >= 2) {
            // به صورت خودکار به سومی نمی‌رویم چون انتخاب حرف در وسط است
        }

        // اگر فیلد سوم کامل شد، به فیلد چهارم برو
        if (part3.value.length >= 3) {
            part4.focus();
        }
    }

    async function loadCities(provinceId) {
        const citySelect = document.getElementById('city_id');
        if (!provinceId) {
            citySelect.innerHTML = '<option value="">ابتدا استان را انتخاب کنید</option>';
            $('#city_id').val('').trigger('change');
            return;
        }
        try {
            const response = await fetch(`${BASE_URL}/ajax/locations/get_cities.php?province_id=${provinceId}`);
            const data = await response.json();
            const cities = (data && data.ok && Array.isArray(data.cities)) ? data.cities : [];

            citySelect.innerHTML = '<option value="">انتخاب شهر</option>';
            cities.forEach(city => {
                const option = document.createElement('option');
                option.value = city.id;
                option.textContent = city.name;
                if (String(city.id) === String(driverCityId)) option.selected = true;
                citySelect.appendChild(option);
            });

            $('#city_id').trigger('change.select2');
        } catch (error) {
            console.error('Error loading cities:', error);
        }
    }

    async function loadCompanyCities(provinceId) {
        const citySelect = document.getElementById('company_city_id');
        if (!provinceId) {
            citySelect.innerHTML = '<option value="">ابتدا استان را انتخاب کنید</option>';
            $('#company_city_id').val('').trigger('change');
            return;
        }
        try {
            const response = await fetch(`${BASE_URL}/ajax/locations/get_cities.php?province_id=${provinceId}`);
            const data = await response.json();
            const cities = (data && data.ok && Array.isArray(data.cities)) ? data.cities : [];

            citySelect.innerHTML = '<option value="">انتخاب شهر</option>';
            cities.forEach(city => {
                const option = document.createElement('option');
                option.value = city.id;
                option.textContent = city.name;
                if (String(city.id) === String(companyCityId)) option.selected = true;
                citySelect.appendChild(option);
            });

            $('#company_city_id').trigger('change.select2');
        } catch (error) {
            console.error('Error loading company cities:', error);
        }
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
            console.log(e)
        }
    }

    // ===== Identity Verification (Admin Panel) =====
    const idvEscape = value => String(value ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#39;'}[c]));
    let idvBusy = false;
    async function idvJson(url, options = {}) {
        const response = await fetch(url, {credentials: 'same-origin', ...options});
        let body;
        try { body = await response.json(); } catch (_) { throw new Error('پاسخ سرور قابل پردازش نبود.'); }
        if (!response.ok || !body.ok) throw new Error((body.message || 'استعلام انجام نشد.') + (body.request_id ? ` — کد پیگیری: ${body.request_id}` : ''));
        return body;
    }
    async function loadIdvServices() {
        const wrap = document.getElementById('idvServicesWrap');
        if (!wrap || idvBusy) return;
        wrap.textContent = 'در حال بارگذاری...';
        const role = document.getElementById('idvAppRole').value;
        if (!role) { wrap.textContent = 'کاربر عضو هیچ‌یک از اپلیکیشن‌ها نیست.'; return; }
        try {
            const json = await idvJson(`${BASE_URL}/ajax/identity-verification.php?action=list_services&user_type=${encodeURIComponent(role)}`);
            if (role !== document.getElementById('idvAppRole').value) return;
            wrap.innerHTML = (json.items || []).map(s => `
                <div class="col-12 col-md-6 col-lg-4">
                    <label class="form-check form-switch">
                        <input class="form-check-input idv-check" type="checkbox" value="${Number(s.id)}" ${s.available === false ? 'disabled' : ''}>
                        <span class="form-check-label">${idvEscape(s.title)} <span dir="ltr">(${idvEscape(s.code)})</span></span>
                    </label><div class="text-muted small">${idvEscape(s.description)}</div>
                </div>`).join('') || 'سرویس فعالی وجود ندارد.';
        } catch (e) { wrap.textContent = e instanceof TypeError ? 'ارتباط با سرور برقرار نشد؛ بارگذاری مجدد را بزنید.' : e.message; }
    }
    async function runIdvSelected() {
        if (idvBusy) return;
        const out = document.getElementById('idvOutput');
        const checks = [...document.querySelectorAll('.idv-check:checked')].map(x => x.value);
        if (!checks.length) { out.textContent = 'حداقل یک سرویس را انتخاب کنید.'; return; }
        const overrides = {};
        const plate = [1,2,3,4].map(n => document.getElementById(`plate_part${n}`)?.value || '');
        if (plate.every(Boolean)) overrides.plateNumber = `${plate[0]}${plate[1]}${plate[2]}ایران${plate[3]}`;
        const ip = document.getElementById('idvIp').value.trim();
        if (ip) overrides.ip = ip;
        const fd = new FormData();
        fd.append('action', 'run');
        fd.append('user_id', String(USER_ID));
        fd.append('app_role', document.getElementById('idvAppRole').value);
        fd.append('_csrf_token', <?= json_encode(csrf_token()) ?>);
        checks.forEach(id => fd.append('service_ids[]', id));
        fd.append('overrides', JSON.stringify(overrides));
        idvBusy = true;
        ['btnRunIdv', 'btnReloadIdv', 'idvAppRole'].forEach(id => document.getElementById(id).disabled = true);
        out.textContent = 'در حال اجرای استعلام؛ منتظر بمانید...';
        try {
            const json = await idvJson(`${BASE_URL}/ajax/identity-verification.php`, {method: 'POST', body: fd});
            const results = json.results || [];
            out.textContent = results.map((r, i) => {
                const name = r.service?.title || `استعلام ${i + 1}`;
                const message = !r.ok ? (r.message || 'اجرای استعلام ناموفق بود') : r.verified === false ? 'اطلاعات تأیید نشد' : r.verified === true ? 'تأیید شد' : 'استعلام انجام شد؛ نتیجه نیازمند بررسی است';
                const details = r.ok && r.verified == null ? '\n' + JSON.stringify(r.result?.data ?? null, null, 2) : '';
                return `${name}: ${message}${r.request_id ? ' — کد پیگیری: ' + r.request_id : ''}${details}`;
            }).join('\n\n');
            const failed = !results.length || results.some(r => !r.ok || r.verified === false);
            Swal.fire({icon: failed ? 'warning' : 'success', title: failed ? 'نتایج را بررسی کنید' : 'استعلام انجام شد', text: 'نتیجهٔ هر سرویس در پایین نمایش داده شد.'});
        } catch (e) { out.textContent = e instanceof TypeError ? 'ارتباط قطع شد؛ نتیجه ممکن است ثبت شده باشد. پیش از اجرای دوباره وضعیت را بررسی کنید.' : e.message; }
        finally {
            idvBusy = false;
            ['btnRunIdv', 'btnReloadIdv', 'idvAppRole'].forEach(id => document.getElementById(id).disabled = false);
        }
    }
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('btnReloadIdv')?.addEventListener('click', loadIdvServices);
        document.getElementById('btnRunIdv')?.addEventListener('click', runIdvSelected);
        document.getElementById('idvAppRole')?.addEventListener('change', loadIdvServices);
        loadIdvServices();
    });

    function addExtraPhone() {
        const c = document.getElementById('extraPhonesContainer');
        const div = document.createElement('div');
        div.className = 'input-group mb-2 extra-phone-input';
        div.innerHTML = '<input aria-label="extra_phones" type="tel" class="form-control" name="extra_phones[]" maxlength="11" placeholder="مثال: 09123456789"><button type="button" class="btn btn-outline-danger remove-extra-phone" onclick="removeExtraPhone(this)"><i class="ri-delete-bin-line"></i></button>';
        c.appendChild(div);
    }

    function removeExtraPhone(btn) {
        const row = btn.closest('.extra-phone-input');
        if (!row) return;
        const c = document.getElementById('extraPhonesContainer');
        if (c.querySelectorAll('.extra-phone-input').length <= 1) {
            const inp = row.querySelector('input[name="extra_phones[]"]');
            if (inp) inp.value = '';
            return;
        }
        row.remove();
    }

    function toggleDriverRejectReason() {
        const status = document.getElementById('driver_verification_status').value;
        document.getElementById('driverRejectReasonWrapper').style.display = (status === '2') ? 'block' : 'none';
    }

    function toggleCompanyRejectReason() {
        const status = document.getElementById('company_verification_status').value;
        const wrap = document.getElementById('companyRejectReasonWrapper');
        if (wrap) wrap.style.display = (status === '2') ? 'block' : 'none';
    }
</script>

<?php
// تابع کمکی برای استخراج بخش‌های پلاک
function extract_plate_part($plateNumber, $part)
{
    if (empty($plateNumber)) return '';

    // فرمت: 12 الف 345 ایران 67
    $parts = preg_split('/\s+/', $plateNumber);

    switch ($part) {
        case 1: // بخش اول (عدد)
            return isset($parts[0]) ? $parts[0] : '';
        case 2: // بخش دوم (حرف)
            return isset($parts[1]) ? $parts[1] : '';
        case 3: // بخش سوم (عدد)
            return isset($parts[2]) ? $parts[2] : '';
        case 4: // بخش چهارم (عدد بعد از ایران)
            if (isset($parts[3]) && $parts[3] === 'ایران' && isset($parts[4])) {
                return $parts[4];
            }
            return '';
        default:
            return '';
    }
}

// تابع برای دریافت انواع وسایل نقلیه
function get_vehicle_types()
{
    $pdo = db();
    $st = $pdo->query("SELECT id, title FROM vehicle_types WHERE is_active = 1 ORDER BY title");
    return $st->fetchAll();
}

// تابع برای دریافت استان‌ها
function get_provinces()
{
    $pdo = db();
    $st = $pdo->query("SELECT id, name FROM provinces ORDER BY name");
    return $st->fetchAll();
}

require_once "views/panel/footer.php"; ?>
