<?php

declare(strict_types=1);

/**
 * --------------------------------------------------------------------------
 *  مسیرهای API مخصوص «اپلیکیشن اعلام بار / صاحبان بار» (user_type = 2)
 * --------------------------------------------------------------------------
 *  این فایل ماژولار است و از روتر اصلی، پس از guardهای نگهداری و فعال‌بودن
 *  endpoint، فراخوانی می‌شود. جابه‌جا کردن dispatch به قبل از guardها ممنوع است.
 * --------------------------------------------------------------------------
 */

if (!function_exists('company_profile_payload')) {
    /**
     * خروجی استاندارد پروفایل باربری (شبیه api_user_with_profile ولی برای شرکت)
     */
    function company_profile_payload(array $u): array
    {
        $pdo = db();
        $st = $pdo->prepare("SELECT id, company_name, owner_full_name, owner_national_code, registration_no, registration_date, economic_code, province_id, city_id, address, postal_code, verification_status, reject_reason, created_at, updated_at FROM companies WHERE user_id=? AND deleted_at IS NULL LIMIT 1");
        $st->execute([(int)$u['id']]);
        $company = $st->fetch() ?: null;

        // نوع شخص از روی وجود شماره ثبت استنتاج می‌شود (1=حقیقی، 2=حقوقی)
        if ($company !== null) {
            $hasReg = isset($company['registration_no']) && trim((string)$company['registration_no']) !== '';
            $company['entity_type'] = $hasReg ? 2 : 1;
        }

        // اطلاعات شهر و استان اختیاری است؛ این پرچم فقط برای نمایش یادآوری
        // در پروفایل استفاده می‌شود و نباید آنبوردینگ را متوقف کند.
        $needsProfile = true;
        if ($company !== null) {
            $needsProfile = ((int)($company['province_id'] ?? 0) <= 0) || ((int)($company['city_id'] ?? 0) <= 0);
        }

        return [
            'user'    => api_public_user_payload($u),
            'company' => $company,
            'onboarding' => [
                'identity_verified'   => ($company !== null) && ((int)($company['verification_status'] ?? 0) === 1),
                'verification_status' => $company['verification_status'] ?? null,
                'profile_completed'   => $company !== null,
                'needs_company_profile' => $needsProfile,
                'company_profile_optional' => true,
            ],
        ];
    }
}

if (!function_exists('company_public_load_code')) {
    function company_public_load_code(PDO $pdo): string
    {
        for ($attempt = 0; $attempt < 8; $attempt++) {
            $code = (string)random_int(100000000000, 999999999999);
            $st = $pdo->prepare("SELECT 1 FROM loads WHERE public_code=? LIMIT 1");
            $st->execute([$code]);
            if (!$st->fetchColumn()) return $code;
        }
        throw new RuntimeException('load_code_generation_failed');
    }
}

if (!function_exists('company_routes')) {

    function company_routes(string $method, string $path): void
    {
        // فقط مسیرهای مرتبط را بررسی می‌کنیم تا کارایی روتر رانندگان حفظ شود
        $isCompanyPath = str_starts_with($path, '/company/')
            || $path === '/meta/cargos/search';
        if (!$isCompanyPath) {
            return; // ادامه به روتر اصلی
        }

        // -------------------------------------------------------------
        // GET /meta/cargos/search?q=...   (جستجوی نوع کالا)
        // -------------------------------------------------------------
        if ($method === 'GET' && $path === '/meta/cargos/search') {
            $pdo = db();
            $q = trim((string)($_GET['q'] ?? ''));
            $st = $pdo->prepare("SELECT id, title FROM cargos_list WHERE title LIKE ? ORDER BY title ASC LIMIT 20");
            $st->execute(['%' . $q . '%']);
            $items = array_map(fn($r) => [
                'id'   => (int)$r['id'],
                'text' => (string)$r['title'],
            ], $st->fetchAll());
            api_ok(['items' => $items]);
        }

        // -------------------------------------------------------------
        // GET /company/me   (پروفایل باربری)
        // -------------------------------------------------------------
        if ($method === 'GET' && $path === '/company/me') {
            $u = api_require_auth();
            if ((int)$u['user_type'] !== 2) api_err('Forbidden', 403);

            $st = db()->prepare("SELECT * FROM users WHERE id=? LIMIT 1");
            $st->execute([(int)$u['id']]);
            $uFull = $st->fetch() ?: $u;
            $uFull['user_type'] = 2;
            $uFull['_active_user_type'] = 2;
            api_ok(company_profile_payload($uFull));
        }

        // -------------------------------------------------------------
        // POST /company/verify-identity   (احراز هویت صاحب بار - شاهکار)
        //  دقیقاً مثل رانندگان، فقط خروجی روی جدول companies ذخیره می‌شود.
        // -------------------------------------------------------------
        if ($method === 'POST' && $path === '/company/verify-identity') {
            $u = api_require_auth();
            if ((int)$u['user_type'] !== 2) api_err('Forbidden', 403);

            $in = api_input();
            $fullName     = api_str($in, 'full_name', 120);
            $nationalCode = api_str($in, 'national_code', 16);
            $birthDate    = api_str($in, 'birth_date', 32); // مثال: 1370/01/01
            $cardSerial   = api_str($in, 'card_serial', 64);

            if (!$fullName)     api_err('full_name الزامی است', 422);
            if (!$nationalCode) api_err('national_code الزامی است', 422);
            if (!$birthDate)    api_err('birth_date الزامی است', 422);
            $nationalError = validate_national_code($nationalCode);
            if ($nationalError !== null) api_err($nationalError, 422);
            if (!preg_match('~^1[34]\d{2}/(?:0[1-9]|1[0-2])/(?:0[1-9]|[12]\d|3[01])$~', $birthDate)) {
                api_err('فرمت تاریخ تولد نامعتبر است', 422);
            }

            $pdo = db();

            // فقط حساب‌های فعال باید کد ملی را رزرو کنند. رکورد بایگانی‌شده
            // بعد از اجرای migration کد ملی را برای ثبت‌نام جدید آزاد می‌کند.
            $st = $pdo->prepare("SELECT id FROM users WHERE code_meli=? AND deleted_at IS NULL AND id<>? LIMIT 1");
            $st->execute([$nationalCode, (int)$u['id']]);
            if ($st->fetchColumn()) {
                api_err(
                    'این کد ملی قبلاً برای یک حساب فعال ثبت شده است. اگر حساب متعلق به شماست، به پشتیبانی پیام بدهید.',
                    409,
                    ['code' => 'national_code_already_registered']
                );
            }

            require_once __DIR__ . '/../../includes/settings.php';
            $requireSerial = (settings_get('auth.require_national_serial') === '1');
            if ($requireSerial && !$cardSerial) {
                api_err('card_serial الزامی است', 422);
            }

            require_once __DIR__ . '/../../includes/ExternalApiHelper.php';
            $apiHelper = new ExternalApiHelper($pdo);
            $providerSlug = 'api_ir';

            // 1) شاهکار لایت: تطبیق موبایل و کد ملی
            try {
                $shahkarRes = $apiHelper->callExternalApi($providerSlug, '/api/sw1/ShahkarLite', 'POST', [
                    'mobile'       => (string)$u['phone'],
                    'nationalCode' => $nationalCode,
                ]);
            } catch (Throwable $e) {
                error_log('company.verify_identity shahkar failed: ' . $e->getMessage());
                api_err('در حال حاضر ارتباط با سرویس احراز هویت ممکن نیست', 502);
            }
            if (empty($shahkarRes['success']) || $shahkarRes['success'] !== true) {
                error_log('company.verify_identity shahkar rejected: ' . (string)($shahkarRes['message'] ?? 'provider rejected request'));
                api_err('استعلام اطلاعات هویتی انجام نشد. لطفاً اطلاعات را بررسی و دوباره تلاش کنید.', 400);
            }
            if (($shahkarRes['data'] ?? false) !== true) {
                api_err('کد ملی وارد شده متعلق به این شماره موبایل نیست.', 422);
            }

            // 2) استعلام عکس (فقط اگر سریال کارت ملی فعال باشد)
            $avatarKey = null;
            if ($requireSerial) {
                try {
                    $photoRes = $apiHelper->callExternalApi($providerSlug, '/api/sw1/PersonImage', 'POST', [
                        'birthDate'    => $birthDate,
                        'nationalCode' => $nationalCode,
                        'serialNumber' => $cardSerial,
                    ]);
                } catch (Throwable $e) {
                    error_log('company.verify_identity photo service failed: ' . $e->getMessage());
                    api_err('در حال حاضر ارتباط با سرویس تصویر هویتی ممکن نیست', 502);
                }
                if (empty($photoRes['success']) || $photoRes['success'] !== true) {
                    error_log('company.verify_identity photo rejected: ' . (string)($photoRes['message'] ?? 'provider rejected request'));
                    api_err('تصویر هویتی تأیید نشد. لطفاً تاریخ تولد و سریال کارت ملی را بررسی کنید.', 400);
                }
                $imageBase64 = $photoRes['data']['imageBase64'] ?? null;
                if (is_string($imageBase64) && $imageBase64 !== '') {
                    $bin = base64_decode($imageBase64, true);
                    if (
                        $bin !== false
                        && strlen($bin) > 0
                        && strlen($bin) <= 5 * 1024 * 1024
                        && @getimagesizefromstring($bin) !== false
                    ) {
                        $dir = __DIR__ . '/../../storage/uploads/avatars';
                        if (!is_dir($dir)) @mkdir($dir, 0775, true);
                        $filename = 'avatar_' . (int)$u['id'] . '_' . time() . '.jpg';
                        if (@file_put_contents($dir . '/' . $filename, $bin) !== false) {
                            @chmod($dir . '/' . $filename, 0640);
                            $avatarKey = 'storage/uploads/avatars/' . $filename;
                            $pdo->prepare("UPDATE users SET avatar_key=?, updated_at=NOW(3) WHERE id=? LIMIT 1")
                                ->execute([$avatarKey, (int)$u['id']]);
                        }
                    }
                }
            }

            // 3) ثبت اطلاعات هویتی روی users + ساخت/به‌روزرسانی ردیف companies
            $pdo->beginTransaction();
            try {
                $st = $pdo->prepare("SELECT id FROM companies WHERE user_id=? AND deleted_at IS NULL LIMIT 1");
                $st->execute([(int)$u['id']]);
                $companyId = (int)($st->fetchColumn() ?: 0);

                if ($companyId > 0) {
                    $pdo->prepare("UPDATE companies SET owner_full_name=?, owner_national_code=?, verification_status=1, verified_at=NOW(3), reject_reason=NULL, updated_at=NOW(3) WHERE id=? LIMIT 1")
                        ->execute([$fullName, $nationalCode, $companyId]);
                } else {
                    // ردیف اولیه؛ موقعیت بعداً و به‌صورت اختیاری از پروفایل تکمیل می‌شود.
                    $pdo->prepare("INSERT INTO companies (user_id, company_name, owner_full_name, owner_national_code, province_id, city_id, verification_status, verified_at, created_at, updated_at) VALUES (?, ?, ?, ?, NULL, NULL, 1, NOW(3), NOW(3), NOW(3))")
                        ->execute([(int)$u['id'], $fullName, $fullName, $nationalCode]);
                    $companyId = (int)$pdo->lastInsertId();
                }

                $pdo->prepare("UPDATE users SET full_name=?, code_meli=?, birth_date=?, national_card_serial=?, updated_at=NOW(3) WHERE id=? LIMIT 1")
                    ->execute([$fullName, $nationalCode, $birthDate, $cardSerial, (int)$u['id']]);

                $pdo->commit();
            } catch (Throwable $e) {
                $pdo->rollBack();
                error_log('company.verify_identity save failed: ' . $e->getMessage());

                $dbMessage = strtolower($e->getMessage());
                $isNationalCodeDuplicate = $e instanceof PDOException
                    && (string)$e->getCode() === '23000'
                    && (
                        str_contains($dbMessage, 'code_meli')
                        || str_contains($dbMessage, 'national_code')
                    );
                if ($isNationalCodeDuplicate) {
                    api_err(
                        'این کد ملی قبلاً برای یک حساب فعال ثبت شده است. اگر حساب متعلق به شماست، به پشتیبانی پیام بدهید.',
                        409,
                        ['code' => 'national_code_already_registered']
                    );
                }

                api_err('ذخیره اطلاعات انجام نشد. لطفاً دوباره تلاش کنید.', 500, [
                    'code' => 'identity_save_failed',
                ]);
            }

            try {
                $st = db()->prepare("SELECT * FROM users WHERE id=? LIMIT 1");
                $st->execute([(int)$u['id']]);
                $uFull = $st->fetch() ?: $u;
                $uFull['user_type'] = 2;
                $uFull['_active_user_type'] = 2;
                $me = company_profile_payload($uFull);
            } catch (Throwable $e) {
                // ذخیره قبلاً commit شده است؛ خطای ساخت پاسخ نباید باعث ارسال
                // دوباره فرم و ایجاد خطای تکراری شود.
                error_log('company.verify_identity response failed: ' . $e->getMessage());
                $me = [
                    'user' => [
                        'id' => (int)$u['id'],
                        'full_name' => $fullName,
                        'display_name' => $u['display_name'] ?? null,
                        'avatar_key' => $avatarKey ?? ($u['avatar_key'] ?? null),
                        'phone' => (string)$u['phone'],
                        'email' => $u['email'] ?? null,
                        'user_type' => 2,
                        'status' => (int)$u['status'],
                        'code_meli' => $nationalCode,
                        'birth_date' => $birthDate,
                    ],
                    'company' => [
                        'id' => $companyId,
                        'company_name' => $fullName,
                        'owner_full_name' => $fullName,
                        'owner_national_code' => $nationalCode,
                        'province_id' => null,
                        'city_id' => null,
                        'verification_status' => 1,
                        'entity_type' => 1,
                    ],
                    'onboarding' => [
                        'identity_verified' => true,
                        'verification_status' => 1,
                        'profile_completed' => true,
                        'needs_company_profile' => true,
                        'company_profile_optional' => true,
                    ],
                ];
            }

            api_ok([
                'message'    => 'احراز هویت با موفقیت انجام شد.',
                'avatar_key' => $avatarKey,
                'me'         => $me,
            ]);
        }

        // -------------------------------------------------------------
        // POST /company/profile   (تکمیل/ویرایش پروفایل باربری)
        //  entity_type: 1 = شخص حقیقی ، 2 = شخص حقوقی/شرکت
        // -------------------------------------------------------------
        if ($method === 'POST' && $path === '/company/profile') {
            $u = api_require_auth();
            if ((int)$u['user_type'] !== 2) api_err('Forbidden', 403);

            $in  = api_input();
            $pdo = db();

            $entityType  = api_int($in, 'entity_type') ?: 1; // پیش‌فرض حقیقی
            $companyName = api_str($in, 'company_name', 150);
            $provinceId  = api_int($in, 'province_id');
            $cityId      = api_int($in, 'city_id');
            $address     = api_str($in, 'address', 255);
            $postalCode  = api_str($in, 'postal_code', 20);

            // فیلدهای مخصوص شخص حقوقی
            $registrationNo   = api_str($in, 'registration_no', 64);
            $registrationDate = api_str($in, 'registration_date', 32);
            $economicCode     = api_str($in, 'economic_code', 64);

            if (!in_array($entityType, [1, 2], true)) api_err('entity_type نامعتبر است', 422);
            if (!$companyName) api_err('نام باربری/صاحب بار الزامی است', 422);
            if (($provinceId === null) !== ($cityId === null)) {
                api_err('استان و شهر باید با هم انتخاب شوند', 422);
            }
            if ($entityType === 2 && !$registrationNo) api_err('شماره ثبت برای شخص حقوقی الزامی است', 422);
            if ($postalCode !== null && !preg_match('/^\d{10}$/', $postalCode)) {
                api_err('کد پستی باید ۱۰ رقم باشد', 422);
            }

            $st = $pdo->prepare("SELECT id, verification_status, province_id, city_id FROM companies WHERE user_id=? AND deleted_at IS NULL LIMIT 1");
            $st->execute([(int)$u['id']]);
            $existingCompany = $st->fetch();
            if (!$existingCompany || (int)$existingCompany['verification_status'] !== 1) {
                api_err('ابتدا احراز هویت صاحب بار را تکمیل کنید', 403);
            }

            if ($provinceId !== null && $cityId !== null) {
                $st = $pdo->prepare("SELECT 1 FROM cities WHERE id=? AND province_id=? LIMIT 1");
                $st->execute([$cityId, $provinceId]);
                if (!$st->fetchColumn()) api_err('شهر انتخاب‌شده متعلق به استان انتخاب‌شده نیست', 422);
            }

            $pdo->beginTransaction();
            try {
                $companyId = (int)$existingCompany['id'];
                $pdo->prepare("UPDATE companies SET company_name=?, registration_no=?, registration_date=COALESCE(?, registration_date), economic_code=?, province_id=?, city_id=?, address=?, postal_code=?, updated_at=NOW(3) WHERE id=? LIMIT 1")
                    ->execute([$companyName, $registrationNo, $registrationDate, $economicCode, $provinceId, $cityId, $address, $postalCode, $companyId]);

                $pdo->commit();
            } catch (Throwable $e) {
                $pdo->rollBack();
                error_log('company.profile save failed: ' . $e->getMessage());
                api_err('ذخیره اطلاعات انجام نشد. لطفاً دوباره تلاش کنید.', 500);
            }

            $st = db()->prepare("SELECT * FROM users WHERE id=? LIMIT 1");
            $st->execute([(int)$u['id']]);
            $uFull = $st->fetch() ?: $u;
            $uFull['user_type'] = 2;
            $uFull['_active_user_type'] = 2;
            api_ok(['company' => company_profile_payload($uFull)]);
        }

        // -------------------------------------------------------------
        // POST /company/docs   (آپلود مدارک: کارت ملی صاحب بار / مجوز)
        // company_documents.doc_type: 1 = کارت ملی صاحب بار، 2 = مجوز فعالیت
        // -------------------------------------------------------------
        if ($method === 'POST' && $path === '/company/docs') {
            $u = api_require_auth();
            if ((int)$u['user_type'] !== 2) api_err('Forbidden', 403);

            $pdo = db();
            $st = $pdo->prepare("SELECT id FROM companies WHERE user_id=? AND deleted_at IS NULL LIMIT 1");
            $st->execute([(int)$u['id']]);
            $companyId = (int)($st->fetchColumn() ?: 0);
            if ($companyId <= 0) api_err('ابتدا پروفایل باربری را تکمیل کنید', 403);

            $hasAny = false;
            if (!empty($_FILES['national_card_image']) && is_array($_FILES['national_card_image'])) {
                $fileKey = save_user_file((int)$u['id'], 2, $_FILES['national_card_image'], $companyId);
                if ($fileKey !== null) {
                    save_company_document($companyId, 1, $fileKey);
                    $hasAny = true;
                }
            }
            if (!empty($_FILES['business_license_image']) && is_array($_FILES['business_license_image'])) {
                // 8 is reserved for a company business licence; 5 belongs to a driver's green card.
                $fileKey = save_user_file((int)$u['id'], 8, $_FILES['business_license_image'], $companyId);
                if ($fileKey !== null) {
                    save_company_document($companyId, 2, $fileKey);
                    $hasAny = true;
                }
            }

            if (!$hasAny) api_err('فایل معتبر ارسال نشده است', 422);
            api_ok(['uploaded' => true]);
        }

        // -------------------------------------------------------------
        // POST /company/loads   (اعلام بار جدید توسط باربری)
        //  company_id به‌صورت خودکار = شرکت همین کاربر
        // -------------------------------------------------------------
        if ($method === 'POST' && $path === '/company/loads') {
            $u = api_require_auth();
            if ((int)$u['user_type'] !== 2) api_err('Forbidden', 403);

            $pdo = db();
            $st = $pdo->prepare("SELECT id, verification_status, province_id, city_id FROM companies WHERE user_id=? AND deleted_at IS NULL LIMIT 1");
            $st->execute([(int)$u['id']]);
            $company = $st->fetch();
            if (!$company) api_err('ابتدا احراز هویت صاحب بار را تکمیل کنید', 403);
            $companyId = (int)$company['id'];
            if ((int)$company['verification_status'] !== 1) {
                api_err('حساب شما هنوز تایید نشده است. پس از تایید می‌توانید بار اعلام کنید.', 403);
            }

            $in = api_input();

            $originCityId = api_int($in, 'origin_city_id');
            $destCityId   = api_int($in, 'dest_city_id');
            if (!$originCityId || !$destCityId) api_err('شهر مبدا و مقصد الزامی است', 422);

            $stc = $pdo->prepare("SELECT province_id FROM cities WHERE id=? LIMIT 1");
            $stc->execute([$originCityId]);
            $originProvinceId = (int)($stc->fetchColumn() ?: 0);
            $stc->execute([$destCityId]);
            $destProvinceId = (int)($stc->fetchColumn() ?: 0);
            if (!$originProvinceId || !$destProvinceId) api_err('استان مبدا یا مقصد یافت نشد', 422);

            $loadType    = api_int($in, 'load_type') ?: 1;       // 1 دربستی / 2 روباری
            $priceType   = api_int($in, 'price_type') ?: 1;      // 1 سرویسی / 2 تنی
            if (!in_array($loadType, [1, 2], true)) api_err('نوع بار نامعتبر است', 422);
            if (!in_array($priceType, [1, 2], true)) api_err('نوع کرایه نامعتبر است', 422);
            $vehicleType = api_int($in, 'primary_vehicle_type_id');
            if (!$vehicleType || $vehicleType <= 0) api_err('نوع بارگیر الزامی است', 422);
            $cargoTypeId = api_int($in, 'cargo_type_id');
            if (!$cargoTypeId || $cargoTypeId <= 0) api_err('نوع کالا الزامی است', 422);
            $isTonnageFree = ((string)($in['is_tonnage_free'] ?? '0') === '1') ? 1 : 0;
            $weightKg    = $isTonnageFree ? null : api_decimal($in, 'weight');
            $proposed    = api_decimal($in, 'proposed_price');
            $phone       = api_str($in, 'phone_coordination', 24);
            $description = api_str($in, 'description', 1000);
            $hasInsurance = ((string)($in['has_insurance'] ?? '0') === '1') ? 1 : 0;
            $insuranceValue = api_decimal($in, 'insurance_value');
            $originAddress = api_str($in, 'origin_address', 200);
            $destAddress   = api_str($in, 'dest_address', 200);

            if (!$phone || !preg_match('/^09\d{9}$/', $phone)) api_err('شماره تلفن هماهنگی نامعتبر است', 422);
            if (!$isTonnageFree && ($weightKg === null || (float)$weightKg <= 0)) {
                api_err('وزن بار باید بیشتر از صفر باشد', 422);
            }
            if ($proposed === null || (float)$proposed <= 0) api_err('کرایه پیشنهادی الزامی است', 422);
            if ($hasInsurance && ($insuranceValue === null || (float)$insuranceValue <= 0)) {
                api_err('ارزش واقعی کالا برای بیمه الزامی است', 422);
            }

            $st = $pdo->prepare("SELECT 1 FROM vehicle_types WHERE id=? AND is_active=1 LIMIT 1");
            $st->execute([$vehicleType]);
            if (!$st->fetchColumn()) api_err('نوع بارگیر نامعتبر است', 422);
            $st = $pdo->prepare("SELECT 1 FROM cargos_list WHERE id=? LIMIT 1");
            $st->execute([$cargoTypeId]);
            if (!$st->fetchColumn()) api_err('نوع کالا نامعتبر است', 422);

            $publicCode = company_public_load_code($pdo);

            try {
                $sql = "INSERT INTO loads (
                            public_code, company_id, created_by_user_id, phone_coordination, load_status, load_type,
                            cargo_type_id, description, weight_kg, is_tonnage_free, origin_province_id, origin_city_id,
                            origin_address, dest_province_id, dest_city_id, dest_address, price_type, proposed_price,
                            primary_vehicle_type_id, published_at, created_at, updated_at, has_insurance, insurance_value
                        ) VALUES (
                            ?, ?, ?, ?, 1, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                            NOW(3), NOW(3), NOW(3), ?, ?
                        )";
                $st = $pdo->prepare($sql);
                $st->execute([
                    $publicCode, $companyId, (int)$u['id'], $phone, $loadType,
                    $cargoTypeId, $description, $weightKg, $isTonnageFree, $originProvinceId, $originCityId,
                    $originAddress, $destProvinceId, $destCityId, $destAddress, $priceType, $proposed,
                    $vehicleType, $hasInsurance, $insuranceValue,
                ]);
                $loadId = (int)$pdo->lastInsertId();
            } catch (Throwable $e) {
                error_log('company.load create failed: ' . $e->getMessage());
                api_err('ثبت بار انجام نشد. لطفاً دوباره تلاش کنید.', 500);
            }

            api_ok(['message' => 'بار با موفقیت اعلام شد', 'load_id' => $loadId, 'public_code' => $publicCode]);
        }

        // -------------------------------------------------------------
        // GET /company/loads   (لیست بارهای خودِ باربری)
        // -------------------------------------------------------------
        if ($method === 'GET' && $path === '/company/loads') {
            $u = api_require_auth();
            if ((int)$u['user_type'] !== 2) api_err('Forbidden', 403);

            $pdo = db();
            $st = $pdo->prepare("SELECT id FROM companies WHERE user_id=? AND deleted_at IS NULL LIMIT 1");
            $st->execute([(int)$u['id']]);
            $companyId = (int)($st->fetchColumn() ?: 0);
            if ($companyId <= 0) api_ok(['items' => []]);

            $st = $pdo->prepare("
                SELECT l.id, l.public_code, l.load_status, l.load_type, l.weight_kg, l.is_tonnage_free,
                       l.proposed_price, l.price_type, l.created_at, l.published_at,
                       c1.name AS origin_city, p1.name AS origin_province,
                       c2.name AS dest_city, p2.name AS dest_province,
                       vt.title AS vehicle_title, cl.title AS cargo_title
                FROM loads l
                LEFT JOIN cities c1 ON l.origin_city_id = c1.id
                LEFT JOIN provinces p1 ON c1.province_id = p1.id
                LEFT JOIN cities c2 ON l.dest_city_id = c2.id
                LEFT JOIN provinces p2 ON c2.province_id = p2.id
                LEFT JOIN vehicle_types vt ON l.primary_vehicle_type_id = vt.id
                LEFT JOIN cargos_list cl ON l.cargo_type_id = cl.id
                WHERE l.company_id = ? AND l.deleted_at IS NULL
                ORDER BY l.id DESC
                LIMIT 100
            ");
            $st->execute([$companyId]);

            $statusText = [0 => 'پیش‌نویس', 1 => 'فعال', 2 => 'تخصیص‌یافته', 3 => 'بسته‌شده', 4 => 'لغوشده'];

            $items = array_map(function ($r) use ($statusText) {
                $ls = (int)$r['load_status'];
                return [
                    'id'           => (int)$r['id'],
                    'public_code'  => $r['public_code'],
                    'status_id'    => $ls,
                    'status_text'  => $statusText[$ls] ?? 'نامشخص',
                    'is_active'    => $ls === 1,
                    'load_type_text' => (int)$r['load_type'] === 1 ? 'دربستی' : 'روباری',
                    'origin'       => $r['origin_city'] . ' (' . $r['origin_province'] . ')',
                    'destination'  => $r['dest_city'] . ' (' . $r['dest_province'] . ')',
                    'cargo_title'  => $r['cargo_title'] ?? 'کالا عمومی',
                    'vehicle_title' => $r['vehicle_title'] ?? 'نامشخص',
                    'weight_text'  => (bool)$r['is_tonnage_free']
                        ? 'تناژ آزاد'
                        : ($r['weight_kg'] . ((int)$r['load_type'] === 1 ? ' تن' : ' کیلوگرم')),
                    'price'        => $r['proposed_price'] !== null ? number_format((float)$r['proposed_price']) : null,
                    'created_at'   => $r['created_at'],
                ];
            }, $st->fetchAll());

            api_ok(['items' => $items]);
        }

        // -------------------------------------------------------------
        // POST /company/loads/{id}/close   (بستن/پایان یک بار)
        // -------------------------------------------------------------
        if ($method === 'POST' && preg_match('~^/company/loads/(\d+)/close$~', $path, $m)) {
            $u = api_require_auth();
            if ((int)$u['user_type'] !== 2) api_err('Forbidden', 403);
            $loadId = (int)$m[1];

            $pdo = db();
            $st = $pdo->prepare("SELECT id FROM companies WHERE user_id=? AND deleted_at IS NULL LIMIT 1");
            $st->execute([(int)$u['id']]);
            $companyId = (int)($st->fetchColumn() ?: 0);

            // فقط بار متعلق به همین باربری قابل بستن است
            $st = $pdo->prepare("UPDATE loads SET load_status=3, updated_at=NOW(3) WHERE id=? AND company_id=? AND load_status=1 AND deleted_at IS NULL LIMIT 1");
            $st->execute([$loadId, $companyId]);
            if ($st->rowCount() <= 0) api_err('فقط بار فعال و متعلق به شما قابل بستن است', 409);

            api_ok(['message' => 'بار بسته شد', 'load_id' => $loadId]);
        }

        // اگر مسیر /company/... بود ولی متد اشتباه بود
        api_err('Not found', 404, ['path' => $path]);
    }
}
