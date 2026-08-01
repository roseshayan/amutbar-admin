<?php

declare(strict_types=1);

// -----------------------------
// Users (Admin Panel CRUD)
// -----------------------------

function user_type_label(int $t): string
{
    return match ($t) {
        1 => 'راننده',
        2 => 'باربری',
        3 => 'ادمین',
        default => 'نامشخص'
    };
}

function user_status_label(int $s): string
{
    return match ($s) {
        1 => 'فعال',
        2 => 'غیرفعال',
        3 => 'در انتظار',
        4 => 'مسدود',
        default => 'نامشخص'
    };
}

function user_gender_label(int $s): string
{
    return match ($s) {
        1 => 'آقا',
        2 => 'خانم',
        default => 'نامشخص'
    };
}

function users_get(int $id): ?array
{
    $pdo = db();
    $st = $pdo->prepare("
        SELECT *
        FROM users
        WHERE id = ? AND deleted_at IS NULL
        LIMIT 1
    ");
    $st->execute([$id]);
    $r = $st->fetch();
    return $r ?: null;
}

function users_soft_delete(int $id): bool
{
    $pdo = db();
    $ownsTransaction = !$pdo->inTransaction();

    if ($ownsTransaction) {
        $pdo->beginTransaction();
    }

    try {
        // پروفایل‌ها بایگانی می‌شوند؛ بارها، گفتگوها و سوابق مالی باقی می‌مانند.
        $pdo->prepare("UPDATE companies SET deleted_at=COALESCE(deleted_at, NOW(3)), updated_at=NOW(3) WHERE user_id=?")
            ->execute([$id]);
        $pdo->prepare("UPDATE drivers SET deleted_at=COALESCE(deleted_at, NOW(3)), updated_at=NOW(3) WHERE user_id=?")
            ->execute([$id]);

        // تمام نشست‌ها و توکن‌های فعال کاربر باطل شوند.
        $pdo->prepare("UPDATE jwt_refresh_tokens SET revoked_at=NOW(3) WHERE user_id=? AND revoked_at IS NULL")
            ->execute([$id]);
        $pdo->prepare("UPDATE api_tokens SET revoked_at=NOW(3) WHERE user_id=? AND revoked_at IS NULL")
            ->execute([$id]);
        $pdo->prepare("DELETE FROM remember_tokens WHERE user_id=?")
            ->execute([$id]);

        $st = $pdo->prepare("
            UPDATE users
            SET status=2,
                deleted_at=NOW(3),
                updated_at=NOW(3),
                jwt_token_version=IFNULL(jwt_token_version, 1) + 1
            WHERE id=? AND deleted_at IS NULL
        ");
        $st->execute([$id]);
        $deleted = $st->rowCount() > 0;

        if ($ownsTransaction) {
            $pdo->commit();
        }

        return $deleted;
    } catch (Throwable $e) {
        if ($ownsTransaction && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
}

function users_save(array $in): array
{
    $pdo = db();

    $id = (int)($in['id'] ?? 0);
    $full_name = sanitize_input((string)($in['full_name'] ?? ''));
    $phone = preg_replace('/\D+/', '', (string)($in['phone'] ?? ''));
    $email = trim((string)($in['email'] ?? ''));
    $user_type = (int)($in['user_type'] ?? 0);
    $status = (int)($in['status'] ?? 3);
    $password = (string)($in['password'] ?? '');

    // فیلدهای جدید
    $code_meli = trim((string)($in['code_meli'] ?? ''));
    $birth_date = trim((string)($in['birth_date'] ?? ''));
    $father_name = sanitize_input((string)($in['father_name'] ?? ''));
    $gender = ($in['gender'] ?? '') !== '' ? (int)$in['gender'] : null;
    $national_card_serial = trim((string)($in['national_card_serial'] ?? ''));
    $display_name = sanitize_input((string)($in['display_name'] ?? ''));

    if ($full_name === '') return ['ok' => false, 'message' => 'نام و نام خانوادگی الزامی است'];
    if (!preg_match('/^09\d{9}$/', $phone)) return ['ok' => false, 'message' => 'شماره موبایل نامعتبر است'];
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) return ['ok' => false, 'message' => 'ایمیل نامعتبر است'];
    if (!in_array($user_type, [1, 2, 3], true)) return ['ok' => false, 'message' => 'نوع کاربر نامعتبر است'];
    if (!in_array($status, [1, 2, 3, 4], true)) return ['ok' => false, 'message' => 'وضعیت نامعتبر است'];

    // اعتبارسنجی کد ملی با تابع داخلی موجود در همین فایل
    if ($code_meli !== '' && (!preg_match('/^\d{10}$/', $code_meli) || !validate_meli_code($code_meli))) {
        return ['ok' => false, 'message' => 'کد ملی نامعتبر است'];
    }

    // اعتبارسنجی تاریخ تولد
    if ($birth_date !== '' && !preg_match('/^\d{4}\/\d{2}\/\d{2}$/', $birth_date)) {
        return ['ok' => false, 'message' => 'فرمت تاریخ تولد نامعتبر است (مثال: 1380/10/04)'];
    }

    // اعتبارسنجی جنسیت
    if ($gender !== null && !in_array($gender, [1, 2], true)) {
        return ['ok' => false, 'message' => 'جنسیت نامعتبر است'];
    }

    // Update
    if ($id > 0) {
        $st = $pdo->prepare("SELECT id FROM users WHERE phone = ? AND id <> ? AND deleted_at IS NULL LIMIT 1");
        $st->execute([$phone, $id]);
        if ($st->fetchColumn()) return ['ok' => false, 'message' => 'این شماره موبایل قبلاً ثبت شده است'];

        if ($email !== '') {
            $st = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id <> ? AND deleted_at IS NULL LIMIT 1");
            $st->execute([$email, $id]);
            if ($st->fetchColumn()) return ['ok' => false, 'message' => 'این ایمیل قبلاً ثبت شده است'];
        }

        // بررسی تکراری بودن کد ملی
        if ($code_meli !== '') {
            $st = $pdo->prepare("SELECT id FROM users WHERE code_meli = ? AND id <> ? AND deleted_at IS NULL LIMIT 1");
            $st->execute([$code_meli, $id]);
            if ($st->fetchColumn()) return ['ok' => false, 'message' => 'این کد ملی قبلاً ثبت شده است'];
        }

        $setPass = ($password !== '');
        if ($setPass && strlen($password) < 8) return ['ok' => false, 'message' => 'رمز عبور حداقل 8 کاراکتر باشد'];

        try {
            if ($setPass) {
                $passHash = password_hash($password, PASSWORD_DEFAULT);
                $st = $pdo->prepare("UPDATE users SET full_name=?, phone=?, email=?, user_type=?, status=?, password_hash=?, updated_at=NOW(3), code_meli=?, birth_date=?, father_name=?, gender=?, national_card_serial=?, display_name=? WHERE id=? AND deleted_at IS NULL");
                $st->execute([$full_name, $phone, ($email === '' ? null : $email), $user_type, $status, $passHash, ($code_meli === '' ? null : $code_meli), ($birth_date === '' ? null : $birth_date), ($father_name === '' ? null : $father_name), $gender, ($national_card_serial === '' ? null : $national_card_serial), ($display_name === '' ? null : $display_name), $id]);
            } else {
                $st = $pdo->prepare("UPDATE users SET full_name=?, phone=?, email=?, user_type=?, status=?, updated_at=NOW(3), code_meli=?, birth_date=?, father_name=?, gender=?, national_card_serial=?, display_name=? WHERE id=? AND deleted_at IS NULL");
                $st->execute([$full_name, $phone, ($email === '' ? null : $email), $user_type, $status, ($code_meli === '' ? null : $code_meli), ($birth_date === '' ? null : $birth_date), ($father_name === '' ? null : $father_name), $gender, ($national_card_serial === '' ? null : $national_card_serial), ($display_name === '' ? null : $display_name), $id]);
            }
            return ['ok' => true, 'id' => $id];
        } catch (Throwable $e) {
            $msg = ((string)env('APP_DEBUG', '0') === '1') ? $e->getMessage() : 'خطا در ذخیره‌سازی';
            return ['ok' => false, 'message' => $msg];
        }
    }

    // Create
    if ($user_type === 3 && $password === '') {
        return ['ok' => false, 'message' => 'برای ادمین، رمز عبور الزامی است'];
    }
    if ($password !== '' && strlen($password) < 8) {
        return ['ok' => false, 'message' => 'رمز عبور حداقل 8 کاراکتر باشد'];
    }

    $st = $pdo->prepare("SELECT id FROM users WHERE phone = ? AND deleted_at IS NULL LIMIT 1");
    $st->execute([$phone]);
    if ($st->fetchColumn()) return ['ok' => false, 'message' => 'این شماره موبایل قبلاً ثبت شده است'];

    if ($email !== '') {
        $st = $pdo->prepare("SELECT id FROM users WHERE email = ? AND deleted_at IS NULL LIMIT 1");
        $st->execute([$email]);
        if ($st->fetchColumn()) return ['ok' => false, 'message' => 'این ایمیل قبلاً ثبت شده است'];
    }

    // بررسی تکراری بودن کد ملی
    if ($code_meli !== '') {
        $st = $pdo->prepare("SELECT id FROM users WHERE code_meli = ? AND deleted_at IS NULL LIMIT 1");
        $st->execute([$code_meli]);
        if ($st->fetchColumn()) return ['ok' => false, 'message' => 'این کد ملی قبلاً ثبت شده است'];
    }

    $passHash = ($password !== '') ? password_hash($password, PASSWORD_DEFAULT) : null;

    $st = $pdo->prepare("INSERT INTO users (full_name, phone, email, password_hash, user_type, status, code_meli, birth_date, father_name, gender, national_card_serial, display_name, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(3), NOW(3))");
    $st->execute([
        $full_name,
        $phone,
        ($email === '' ? null : $email),
        $passHash,
        $user_type,
        $status,
        ($code_meli === '' ? null : $code_meli),
        ($birth_date === '' ? null : $birth_date),
        ($father_name === '' ? null : $father_name),
        $gender,
        ($national_card_serial === '' ? null : $national_card_serial),
        ($display_name === '' ? null : $display_name)
    ]);

    return ['ok' => true, 'id' => (int)$pdo->lastInsertId()];
}

function users_datatable(array $req): array
{
    $pdo = db();

    $draw = (int)($req['draw'] ?? 1);
    $start = max(0, (int)($req['start'] ?? 0));
    $length = (int)($req['length'] ?? 10);
    if ($length <= 0 || $length > 200) $length = 25;

    $search = trim((string)($req['search']['value'] ?? ''));

    $columns = ['id', 'full_name', 'phone', 'email', 'user_type', 'status', 'created_at'];
    $orderColIdx = (int)($req['order'][0]['column'] ?? 0);
    $orderDir = strtolower((string)($req['order'][0]['dir'] ?? 'desc')) === 'asc' ? 'ASC' : 'DESC';
    $orderCol = $columns[$orderColIdx] ?? 'id';

    $where = "deleted_at IS NULL";
    $params = [];

    if ($search !== '') {
        $where .= " AND (full_name LIKE ? OR phone LIKE ? OR email LIKE ? OR code_meli LIKE ? OR display_name LIKE ?)";
        $like = "%{$search}%";
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
    }

    $total = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE deleted_at IS NULL")->fetchColumn();

    $st = $pdo->prepare("SELECT COUNT(*) FROM users WHERE {$where}");
    $st->execute($params);
    $filtered = (int)$st->fetchColumn();

    $sql = "SELECT id, full_name, phone, email, user_type, status, created_at, code_meli, display_name
            FROM users
            WHERE {$where}
            ORDER BY {$orderCol} {$orderDir}
            LIMIT {$length} OFFSET {$start}";

    $st = $pdo->prepare($sql);
    $st->execute($params);
    $rows = $st->fetchAll();

    $data = array_map(static function (array $r): array {
        return [
            'id' => (int)$r['id'],
            'full_name' => htmlspecialchars((string)$r['full_name'], ENT_QUOTES, 'UTF-8'),
            'phone' => htmlspecialchars((string)$r['phone'], ENT_QUOTES, 'UTF-8'),
            'email' => htmlspecialchars((string)($r['email'] ?? ''), ENT_QUOTES, 'UTF-8'),
            'user_type' => (int)$r['user_type'],
            'user_type_label' => user_type_label((int)$r['user_type']),
            'status' => (int)$r['status'],
            'status_label' => user_status_label((int)$r['status']),
            'created_at' => (string)$r['created_at'],
            'code_meli' => htmlspecialchars((string)($r['code_meli'] ?? ''), ENT_QUOTES, 'UTF-8'),
            'display_name' => htmlspecialchars((string)($r['display_name'] ?? ''), ENT_QUOTES, 'UTF-8'),
        ];
    }, $rows);

    return [
        'draw' => $draw,
        'recordsTotal' => $total,
        'recordsFiltered' => $filtered,
        'data' => $data,
    ];
}

// تابع اعتبارسنجی کد ملی
function validate_meli_code(string $code): bool
{
    if (!preg_match('/^[0-9]{10}$/', $code)) {
        return false;
    }

    $sum = 0;
    for ($i = 0; $i < 9; $i++) {
        $sum += (int)$code[$i] * (10 - $i);
    }

    $remainder = $sum % 11;
    $controlDigit = (int)$code[9];

    if ($remainder < 2) {
        return $controlDigit === $remainder;
    } else {
        return $controlDigit === (11 - $remainder);
    }
}

// ذخیره‌سازی کامل کاربر (اطلاعات پایه + راننده/باربری)
function users_complete_save(array $data): array
{
    $pdo = db();

    $pdo->beginTransaction();

    // ذخیره‌سازی اطلاعات پایه کاربر
    $baseResult = users_save($data);

    if (!$baseResult['ok']) {
        $pdo->rollBack();
        return $baseResult;
    }

    $userId = (int)$baseResult['id'];
    $userType = (int)($data['user_type'] ?? 0);

    // ذخیره عکس پروفایل
    save_avatar($userId, $_FILES['avatar'] ?? null);

    // اگر کاربر راننده است
    if ($userType === 1) {
        $driverResult = save_driver_info($userId, $data);
        if (!$driverResult['ok']) {
            $pdo->rollBack();
            return $driverResult;
        }
    } // اگر کاربر باربری است
    elseif ($userType === 2) {
        $companyResult = save_company_info($userId, $data);
        if (!$companyResult['ok']) {
            $pdo->rollBack();
            return $companyResult;
        }
    }

    $pdo->commit();
    return $baseResult;
}

// ذخیره عکس پروفایل
function save_avatar(int $userId, ?array $avatarFile): void
{
    if ($avatarFile !== null) user_update_avatar($userId, $avatarFile);
}

/**
 * Update user avatar (for API/panel usage)
 */
function user_update_avatar(int $userId, array $avatarFile): array
{
    if ($userId <= 0) return ['ok' => false, 'message' => 'شناسه نامعتبر است', 'status' => 422];
    if (empty($avatarFile) || !isset($avatarFile['error']) || $avatarFile['error'] !== UPLOAD_ERR_OK) {
        return ['ok' => false, 'message' => 'فایل نامعتبر است', 'status' => 422];
    }

    $allowedTypes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];
    $maxSize = 3 * 1024 * 1024;
    $tmpName = (string)($avatarFile['tmp_name'] ?? '');
    if ($tmpName === '' || !is_uploaded_file($tmpName)) {
        return ['ok' => false, 'message' => 'فایل نامعتبر است', 'status' => 422];
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = (string)$finfo->file($tmpName);
    if (!isset($allowedTypes[$mime]) || @getimagesize($tmpName) === false) {
        return ['ok' => false, 'message' => 'فرمت تصویر مجاز نیست', 'status' => 422];
    }
    $size = (int)($avatarFile['size'] ?? 0);
    if ($size <= 0 || $size > $maxSize) {
        return ['ok' => false, 'message' => 'حجم تصویر بیش از حد مجاز است (حداکثر 3MB)', 'status' => 422];
    }

    $pdo = db();
    $st = $pdo->prepare("SELECT avatar_key FROM users WHERE id=? AND deleted_at IS NULL LIMIT 1");
    $st->execute([$userId]);
    $oldKey = $st->fetchColumn();
    if ($oldKey === false) return ['ok' => false, 'message' => 'کاربر یافت نشد', 'status' => 404];

    $extension = $allowedTypes[$mime];

    $dir = __DIR__ . '/../storage/uploads/avatars';
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }

    $filename = "avatar_{$userId}_" . bin2hex(random_bytes(12)) . ".{$extension}";
    $uploadPath = $dir . '/' . $filename;

    if (!move_uploaded_file($tmpName, $uploadPath)) {
        return ['ok' => false, 'message' => 'خطا در آپلود فایل', 'status' => 500];
    }
    @chmod($uploadPath, 0640);

    $newKey = 'storage/uploads/avatars/' . $filename;
    $pdo->prepare("UPDATE users SET avatar_key=?, updated_at=NOW(3) WHERE id=? LIMIT 1")
        ->execute([$newKey, $userId]);

    if (is_string($oldKey) && $oldKey !== '' && !preg_match('~^https?://~i', $oldKey)) {
        $oldPath = BASE_PATH . '/' . ltrim($oldKey, '/');
        if (str_contains($oldPath, '/storage/uploads/avatars/') && is_file($oldPath)) {
            @unlink($oldPath);
        }
    }

    return ['ok' => true, 'avatar_key' => $newKey, 'message' => 'عکس پروفایل بروزرسانی شد'];
}

// ذخیره اطلاعات راننده
function save_driver_info(int $userId, array $data): array
{
    $pdo = db();

    $driverId = (int)($data['driver_id'] ?? 0);
    $nationalCode = trim((string)($data['code_meli'] ?? ''));
    $plateNumber = trim((string)($data['plate_number'] ?? ''));
    $smartCardNumber = trim((string)($data['smart_card_number'] ?? ''));
    $vehicleTypeId = (int)($data['vehicle_type_id'] ?? 0);
    $modelYear = ($data['model_year'] ?? '') !== '' ? (int)$data['model_year'] : null;
    $color = trim((string)($data['color'] ?? ''));
    $capacityKg = ($data['capacity_kg'] ?? '') !== '' ? (float)$data['capacity_kg'] : null;
    $provinceId = ($data['province_id'] ?? '') !== '' ? (int)$data['province_id'] : null;
    $cityId = ($data['city_id'] ?? '') !== '' ? (int)$data['city_id'] : null;

    // فیلدهای جدید
    $issuedFrom = trim((string)($data['issued_from'] ?? ''));
    $address = trim((string)($data['address'] ?? ''));
    $homePhone = trim((string)($data['home_phone'] ?? ''));
    $postalCode = trim((string)($data['postal_code'] ?? ''));
    $licenseSerial = trim((string)($data['license_serial'] ?? ''));
    $licenseBase = trim((string)($data['license_base'] ?? ''));
    $vinNumber = trim((string)($data['vin_number'] ?? ''));
    $insuranceNumber = trim((string)($data['insurance_number'] ?? ''));
    $insuranceExpiry = trim((string)($data['insurance_expiry'] ?? ''));
    $engineNumber = trim((string)($data['engine_number'] ?? ''));
    $chassisNumber = trim((string)($data['chassis_number'] ?? ''));
    $extraPhonesJson = json_encode($data['extra_phones'] ?? []);

    $verificationStatus = isset($data['driver_verification_status']) ? (int)$data['driver_verification_status'] : (isset($data['verification_status']) ? (int)$data['verification_status'] : null);
    $rejectReason = trim((string)($data['driver_reject_reason'] ?? ($data['reject_reason'] ?? '')));
    $adminId = admin_id() ?: null;
    $verifiedAt = ($verificationStatus === 1) ? date('Y-m-d H:i:s') : null;
    $verifiedBy = ($verificationStatus === 1) ? $adminId : null;

    if (empty($nationalCode)) {
        return ['ok' => false, 'message' => 'کد ملی برای راننده الزامی است'];
    }
    if (empty($plateNumber)) {
        return ['ok' => false, 'message' => 'پلاک ماشین الزامی است'];
    }
    if ($vehicleTypeId <= 0) {
        return ['ok' => false, 'message' => 'نوع وسیله نقلیه الزامی است'];
    }

    try {
        if ($driverId > 0) {
            $stOld = $pdo->prepare("SELECT verification_status FROM drivers WHERE id=?");
            $stOld->execute([$driverId]);
            $oldStatus = (int)($stOld->fetchColumn() ?: 0);

            $st = $pdo->prepare("
                UPDATE drivers 
                SET full_name = ?, national_code = ?, smart_card_number = ?, 
                    vehicle_type_id = ?, plate_number = ?, model_year = ?, color = ?, 
                    capacity_kg = ?, province_id = ?, city_id = ?, 
                    issued_from = ?, address = ?, home_phone = ?, postal_code = ?, 
                    license_serial = ?, license_base = ?, vin_number = ?, 
                    insurance_number = ?, insurance_expiry = ?, engine_number = ?, 
                    verification_status = ?, verified_at = ?, verified_by_user_id = ?, reject_reason = ?,
                    chassis_number = ?, extra_phones = ?, updated_at = NOW(3) 
                WHERE id = ? AND user_id = ? AND deleted_at IS NULL
            ");
            $st->execute([
                trim($data['full_name'] ?? ''),
                $nationalCode,
                $smartCardNumber ?: null,
                $vehicleTypeId,
                $plateNumber,
                $modelYear,
                $color ?: null,
                $capacityKg,
                $provinceId,
                $cityId,
                $issuedFrom ?: null,
                $address ?: null,
                $homePhone ?: null,
                $postalCode ?: null,
                $licenseSerial ?: null,
                $licenseBase ?: null,
                $vinNumber ?: null,
                $insuranceNumber ?: null,
                $insuranceExpiry ?: null,
                $engineNumber ?: null,
                $verificationStatus,
                $verifiedAt,
                $verifiedBy,
                ($verificationStatus === 2 ? $rejectReason : null),
                $chassisNumber ?: null,
                $extraPhonesJson ?: null,
                $driverId,
                $userId
            ]);

            if ($oldStatus !== $verificationStatus && $adminId) {
                $pdo->prepare("INSERT INTO driver_verification_events (driver_id, old_status, new_status, actor_user_id, note, created_at) VALUES (?, ?, ?, ?, ?, NOW(3))")
                    ->execute([$driverId, $oldStatus, $verificationStatus, $adminId, $rejectReason]);
            }
        } else {
            $st = $pdo->prepare("
                INSERT INTO drivers 
                (user_id, full_name, national_code, smart_card_number, vehicle_type_id, 
                 plate_number, model_year, color, capacity_kg, province_id, city_id,
                 issued_from, address, home_phone, postal_code, license_serial, license_base,
                 vin_number, insurance_number, insurance_expiry, engine_number, chassis_number,
                 extra_phones, verification_status, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, NOW(3), NOW(3))
            ");
            $st->execute([
                $userId,
                trim($data['full_name'] ?? ''),
                $nationalCode,
                $smartCardNumber ?: null,
                $vehicleTypeId,
                $plateNumber,
                $modelYear,
                $color ?: null,
                $capacityKg,
                $provinceId,
                $cityId,
                $issuedFrom ?: null,
                $address ?: null,
                $homePhone ?: null,
                $postalCode ?: null,
                $licenseSerial ?: null,
                $licenseBase ?: null,
                $vinNumber ?: null,
                $insuranceNumber ?: null,
                $insuranceExpiry ?: null,
                $engineNumber ?: null,
                $chassisNumber ?: null,
                $extraPhonesJson ?: null
            ]);
        }

        $did = $driverId > 0 ? $driverId : (int)$pdo->lastInsertId();
        save_driver_files($userId, $did, $_FILES);

        return ['ok' => true];
    } catch (Throwable $e) {
        if (str_contains($e->getMessage(), 'uq_drivers_plate_active')) {
            return ['ok' => false, 'message' => 'این پلاک قبلاً ثبت شده است'];
        }
        $msg = ((string)env('APP_DEBUG', '0') === '1') ? $e->getMessage() : 'خطا در ذخیره اطلاعات راننده';
        return ['ok' => false, 'message' => $msg];
    }
}

// ذخیره فایل‌های راننده
function save_driver_files(int $userId, int $driverId, array $files): void
{
    $fileTypes = [
        'national_card_image' => 2,
        'license_image' => 3,
        'vehicle_card_image' => 4,
        'green_card_image' => 5,
        'verification_video' => 6,
        'insurance_image' => 7,
    ];

    foreach ($fileTypes as $fieldName => $fileType) {
        if (isset($files[$fieldName]) && $files[$fieldName]['error'] === UPLOAD_ERR_OK) {
            $key = save_user_file($userId, $fileType, $files[$fieldName], $driverId);
            if ($key && $driverId > 0) {
                save_driver_document($driverId, $fileType, $key);
            }
        }
    }
}

// ذخیره اطلاعات باربری
function save_company_info(int $userId, array $data): array
{
    $pdo = db();

    $companyId = (int)($data['company_id'] ?? 0);
    $companyName = trim((string)($data['company_name'] ?? ''));
    $ownerFullName = trim((string)($data['full_name'] ?? ''));
    $ownerNationalCode = trim((string)($data['code_meli'] ?? ''));
    $registrationNo = trim((string)($data['registration_no'] ?? ''));
    $registrationDate = trim((string)($data['registration_date'] ?? ''));
    $economicCode = trim((string)($data['economic_code'] ?? ''));
    $provinceId = ($data['company_province_id'] ?? '') !== '' ? (int)$data['company_province_id'] : null;
    $cityId = ($data['company_city_id'] ?? '') !== '' ? (int)$data['company_city_id'] : null;
    $address = trim((string)($data['company_address'] ?? ''));
    $postalCode = trim((string)($data['company_postal_code'] ?? ''));

    $verificationStatus = isset($data['company_verification_status']) ? (int)$data['company_verification_status'] : (isset($data['verification_status']) ? (int)$data['verification_status'] : null);
    $rejectReason = trim((string)($data['company_reject_reason'] ?? ''));
    $adminId = admin_id() ?: null;
    $verifiedAt = ($verificationStatus === 1) ? date('Y-m-d H:i:s') : null;
    $verifiedBy = ($verificationStatus === 1) ? $adminId : null;

    if (empty($companyName)) {
        return ['ok' => false, 'message' => 'نام باربری الزامی است'];
    }
    if (empty($ownerNationalCode)) {
        return ['ok' => false, 'message' => 'کد ملی صاحب باربری الزامی است'];
    }

    try {
        if ($companyId > 0) {
            $st = $pdo->prepare("
                UPDATE companies 
                SET company_name = ?, owner_full_name = ?, owner_national_code = ?, 
                    registration_no = ?, registration_date = ?, economic_code = ?, 
                    province_id = ?, city_id = ?, address = ?, postal_code = ?, 
                    verification_status = ?, verified_at = ?, verified_by_user_id = ?,
                    reject_reason = ?, updated_at = NOW(3) 
                WHERE id = ? AND user_id = ? AND deleted_at IS NULL
            ");
            $st->execute([
                $companyName,
                $ownerFullName,
                $ownerNationalCode,
                $registrationNo ?: null,
                $registrationDate ?: null,
                $economicCode ?: null,
                $provinceId,
                $cityId,
                $address ?: null,
                $postalCode ?: null,
                $verificationStatus,
                $verifiedAt,
                $verifiedBy,
                $rejectReason,
                $companyId,
                $userId
            ]);
        } else {
            $st = $pdo->prepare("
                INSERT INTO companies 
                (user_id, company_name, owner_full_name, owner_national_code, 
                 registration_no, registration_date, economic_code, province_id, 
                 city_id, address, postal_code, verification_status, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, NOW(3), NOW(3))
            ");
            $st->execute([
                $userId,
                $companyName,
                $ownerFullName,
                $ownerNationalCode,
                $registrationNo ?: null,
                $registrationDate ?: null,
                $economicCode ?: null,
                $provinceId,
                $cityId,
                $address ?: null,
                $postalCode ?: null
            ]);
        }

        if (isset($_FILES['company_national_card_image']) && $_FILES['company_national_card_image']['error'] === UPLOAD_ERR_OK) {
            $savedCompanyId = $companyId ?: (int)$pdo->lastInsertId();
            $fileKey = save_user_file($userId, 2, $_FILES['company_national_card_image'], $savedCompanyId);
            if ($fileKey !== null && $savedCompanyId > 0) {
                save_company_document($savedCompanyId, 1, $fileKey);
            }
        }

        return ['ok' => true];
    } catch (Throwable $e) {
        $msg = ((string)env('APP_DEBUG', '0') === '1') ? $e->getMessage() : 'خطا در ذخیره اطلاعات باربری';
        return ['ok' => false, 'message' => $msg];
    }
}

// تابع عمومی اصلاح‌شده برای ذخیره فایل کاربر (کاملاً هماهنگ با PHP 8.1+ Nullable)
function save_user_file(int $userId, int $fileType, array $file, ?int $relatedId = null): ?string
{
    $allowedTypes = [
        'image/jpeg' => ['kind' => 'image', 'extension' => 'jpg'],
        'image/png' => ['kind' => 'image', 'extension' => 'png'],
        'image/gif' => ['kind' => 'image', 'extension' => 'gif'],
        'image/webp' => ['kind' => 'image', 'extension' => 'webp'],
        'video/mp4' => ['kind' => 'video', 'extension' => 'mp4'],
        'video/quicktime' => ['kind' => 'video', 'extension' => 'mov'],
        'video/x-msvideo' => ['kind' => 'video', 'extension' => 'avi'],
        'video/webm' => ['kind' => 'video', 'extension' => 'webm'],
    ];

    $maxImageSize = 5 * 1024 * 1024; // 5MB
    $maxVideoSize = 50 * 1024 * 1024; // 50MB

    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return null;
    }
    $tmpName = (string)($file['tmp_name'] ?? '');
    if ($tmpName === '' || !is_uploaded_file($tmpName)) return null;
    $size = (int)($file['size'] ?? 0);
    if ($size <= 0) return null;

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = (string)$finfo->file($tmpName);
    $typeConfig = $allowedTypes[$mime] ?? null;
    if ($typeConfig === null) return null;
    $isImage = $typeConfig['kind'] === 'image';

    if (($isImage && $size > $maxImageSize) || (!$isImage && $size > $maxVideoSize)) {
        return null;
    }
    if ($isImage && @getimagesize($tmpName) === false) return null;

    $extension = $typeConfig['extension'];
    $filename = "user_{$userId}_" . bin2hex(random_bytes(12)) . ".{$extension}";

    $uploadDir = __DIR__ . '/../storage/uploads/';
    $relativePath = ($isImage ? 'images/' : 'videos/') . $filename;
    $uploadPath = $uploadDir . $relativePath;
    $dbFileKey = 'storage/uploads/' . $relativePath;

    if (!is_dir(dirname($uploadPath))) {
        @mkdir(dirname($uploadPath), 0775, true);
    }

    if (move_uploaded_file($tmpName, $uploadPath)) {
        @chmod($uploadPath, 0640);
        $pdo = db();

        $metadata = json_encode([
            'related_id' => $relatedId,
            'original_name' => basename((string)($file['name'] ?? '')),
            'uploaded_at' => date('Y-m-d H:i:s')
        ], JSON_UNESCAPED_UNICODE) ?: '{}';

        $stCheck = $pdo->prepare("SELECT id, file_key FROM user_files WHERE user_id = ? AND file_type = ? LIMIT 1");
        $stCheck->execute([$userId, $fileType]);
        $existing = $stCheck->fetch();

        if ($existing) {
            $oldKey = $existing['file_key'];
            if (!empty($oldKey)) {
                $oldPath = __DIR__ . '/../' . ltrim((string)$oldKey, '/');
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $stUpdate = $pdo->prepare("
                UPDATE user_files 
                SET file_key = ?, mime_type = ?, file_size = ?, metadata = ?, updated_at = NOW(3)
                WHERE id = ?
            ");
            $stUpdate->execute([
                $dbFileKey,
                $mime,
                $size,
                $metadata,
                $existing['id']
            ]);

            return $dbFileKey;
        } else {
            $stInsert = $pdo->prepare("
                INSERT INTO user_files 
                (user_id, file_type, file_key, mime_type, file_size, metadata, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, NOW(3), NOW(3))
            ");
            $stInsert->execute([
                $userId,
                $fileType,
                $dbFileKey,
                $mime,
                $size,
                $metadata
            ]);
            
            // باگ منطقی برطرف شد: مقدار ریترن در ایجاد سابقه جدید درست ست شد.
            return $dbFileKey; 
        }
    }
    return null;
}

function save_driver_document(int $driverId, int $docType, string $fileKey): void
{
    $pdo = db();
    $st = $pdo->prepare("SELECT id FROM driver_documents WHERE driver_id=? AND doc_type=? LIMIT 1");
    $st->execute([$driverId, $docType]);
    $id = $st->fetchColumn();

    if ($id) {
        $pdo->prepare("UPDATE driver_documents SET file_key=?, status=0, updated_at=NOW(3) WHERE id=?")->execute([$fileKey, $id]);
    } else {
        $pdo->prepare("INSERT INTO driver_documents (driver_id, doc_type, file_key, status, created_at, updated_at) VALUES (?, ?, ?, 0, NOW(3), NOW(3))")->execute([$driverId, $docType, $fileKey]);
    }
}

/**
 * Company document types:
 * 1 = national card of the company owner
 * 2 = business/activity licence
 */
function save_company_document(int $companyId, int $docType, string $fileKey): void
{
    $pdo = db();
    $st = $pdo->prepare("SELECT id FROM company_documents WHERE company_id=? AND doc_type=? ORDER BY id DESC LIMIT 1");
    $st->execute([$companyId, $docType]);
    $id = $st->fetchColumn();

    if ($id) {
        $pdo->prepare("
            UPDATE company_documents
            SET file_key=?, status=0, reviewed_by_user_id=NULL, reviewed_at=NULL,
                reject_reason=NULL, updated_at=NOW(3)
            WHERE id=?
        ")->execute([$fileKey, $id]);
    } else {
        $pdo->prepare("
            INSERT INTO company_documents
            (company_id, doc_type, file_key, status, created_at, updated_at)
            VALUES (?, ?, ?, 0, NOW(3), NOW(3))
        ")->execute([$companyId, $docType, $fileKey]);
    }
}
