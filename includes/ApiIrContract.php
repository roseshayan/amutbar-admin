<?php
declare(strict_types=1);

/** Contract checked against s.api.ir/json and api-ir/php-sdk on 2026-09-20. */
final class VerificationServiceException extends RuntimeException
{
    public function __construct(
        public string $errorCode,
        string $message,
        public int $httpStatus = 502,
        public bool $retryable = false,
        public ?int $providerCode = null,
        public ?string $requestId = null
    ) { parent::__construct($message); }

    public function publicPayload(): array
    {
        return ['code' => $this->errorCode, 'retryable' => $this->retryable,
            'request_id' => $this->requestId];
    }
}

final class ApiIrContract
{
    public const BASE_URL = 'https://s.api.ir';
    public const MAX_VIDEO_BYTES = 5 * 1024 * 1024;

    public static function prepare(string $endpoint, array $data): array
    {
        $endpoint = '/' . ltrim($endpoint, '/');
        if ($endpoint === '/api/sw1/Shahkar2') throw new VerificationServiceException('provider_service_obsolete', 'سرویس شاهکار ۲ دیگر در قرارداد فعلی موجود نیست؛ شاهکار لایت را انتخاب کنید.', 503);
        if ($endpoint === '/api/sw1/VideoMatch') throw new VerificationServiceException('provider_service_obsolete', 'این سرویس احراز هویت نیاز به به‌روزرسانی تنظیمات دارد.', 503);
        // Compatibility for saved panel services: PersonImage is absent in current OpenAPI.
        if ($endpoint === '/api/sw1/PersonImage') $endpoint = '/api/sw1/PersonData';
        $fields = match ($endpoint) {
            '/api/sw1/ShahkarLite' => ['nationalCode', 'mobile'],
            '/api/sw1/Shahkar', '/api/sw1/ShahkarPro' => ['nationalCode', 'mobile', 'isCompany'],
            '/api/sw1/PersonData', '/api/sw1/PersonInfo' => ['nationalCode', 'birthDate'],
            '/api/sw1/VideoVerifySpeechText' => [],
            '/api/sw1/VideoVerify' => ['nationalCode', 'birthDate', 'serialNumber', 'videoBase64', 'speechText', 'matchingThreshold', 'livenessThreshold', 'speechThreshold'],
            default => null,
        };
        if ($fields !== null) {
            $data = array_intersect_key($data, array_flip($fields));
            foreach ($fields as $field) {
                if (str_ends_with($field, 'Threshold') || $field === 'isCompany') continue;
                if (!isset($data[$field]) || !is_string($data[$field]) || trim($data[$field]) === '') {
                    throw new VerificationServiceException('verification_input_missing', 'اطلاعات لازم برای احراز هویت کامل نیست.', 422);
                }
            }
        }
        if (isset($data['nationalCode']) && !preg_match('/^\d{10,11}$/D', $data['nationalCode'])) {
            throw new VerificationServiceException('invalid_national_code', 'کد ملی معتبر وارد کنید.', 422);
        }
        if (isset($data['mobile']) && !preg_match('/^09\d{9}$/D', $data['mobile'])) {
            throw new VerificationServiceException('invalid_mobile', 'شماره موبایل معتبر وارد کنید.', 422);
        }
        if (isset($data['birthDate'])) {
            if (!preg_match('~^(1[34]\d{2})/(0?[1-9]|1[0-2])/(0?[1-9]|[12]\d|3[01])$~D', $data['birthDate'], $date)) {
                throw new VerificationServiceException('invalid_birth_date', 'تاریخ تولد معتبر وارد کنید.', 422);
            }
            $data['birthDate'] = $date[1] . '/' . (int)$date[2] . '/' . (int)$date[3];
        }
        if (in_array('isCompany', $fields ?? [], true)) $data['isCompany'] = (bool)($data['isCompany'] ?? false);
        if ($endpoint === '/api/sw1/VideoVerify') {
            if (mb_strlen($data['serialNumber']) < 5) throw new VerificationServiceException('national_serial_required', 'سریال پشت کارت ملی یا کد رهگیری رسید کارت را وارد کنید.', 422);
            if (mb_strlen($data['speechText']) < 10) throw new VerificationServiceException('invalid_video_challenge', 'متن ضبط معتبر نیست؛ راهنمای ضبط را دوباره دریافت کنید.', 422);
            $video = base64_decode($data['videoBase64'], true);
            if ($video === false || strlen($video) === 0 || strlen($video) > self::MAX_VIDEO_BYTES) throw new VerificationServiceException('video_too_large', 'ویدئو باید معتبر و حداکثر ۵ مگابایت باشد.', 422);
            foreach (['matchingThreshold' => 80, 'livenessThreshold' => 80, 'speechThreshold' => 50] as $key => $default) {
                $value = filter_var($data[$key] ?? $default, FILTER_VALIDATE_INT);
                if ($value === false || $value < 0 || $value > 100) throw new VerificationServiceException('provider_configuration_error', 'تنظیمات احراز هویت نیاز به بررسی پشتیبانی دارد.', 503);
                $data[$key] = $value;
            }
        }
        $timeout = match ($endpoint) {
            '/api/sw1/VideoVerify' => 120000,
            '/api/sw1/PersonData' => 60000,
            default => 30000,
        };
        return ['endpoint' => $endpoint, 'data' => $data, 'timeout_ms' => $timeout];
    }

    public static function decode(int $httpStatus, string $body, int $curlErrno = 0): array
    {
        if ($curlErrno !== 0) {
            if ($curlErrno === CURLE_OPERATION_TIMEDOUT) throw new VerificationServiceException('provider_timeout', 'پاسخ سرویس احراز هویت به‌موقع نرسید. نتیجه نامشخص است؛ پیش از تلاش دوباره وضعیت را بررسی کنید.', 504, true);
            if (in_array($curlErrno, [CURLE_SSL_CONNECT_ERROR, CURLE_SSL_CACERT], true)) throw new VerificationServiceException('provider_tls_error', 'ارتباط امن با سرویس احراز هویت برقرار نشد. با پشتیبانی تماس بگیرید.', 503);
            throw new VerificationServiceException('provider_unavailable', 'ارتباط با سرویس احراز هویت برقرار نشد. کمی بعد دوباره تلاش کنید.', 503, true);
        }
        $result = json_decode($body, true);
        $providerCode = is_array($result) && isset($result['code']) && is_numeric($result['code']) ? (int)$result['code'] : null;
        // Only 401 is documented as a provider business code. Do not invent meanings for others.
        if (in_array($httpStatus, [401, 403], true) || $providerCode === 401) throw new VerificationServiceException('provider_credentials_error', 'دسترسی سرویس احراز هویت نیاز به بررسی پشتیبانی دارد.', 503, false, $providerCode);
        if ($httpStatus === 402) throw new VerificationServiceException('provider_credit_error', 'سرویس احراز هویت فعلاً قابل استفاده نیست. با پشتیبانی تماس بگیرید.', 503, false, $providerCode);
        if ($httpStatus === 429) throw new VerificationServiceException('provider_rate_limited', 'تعداد درخواست‌ها زیاد است؛ کمی بعد دوباره تلاش کنید.', 503, true, $providerCode);
        if ($httpStatus >= 500) throw new VerificationServiceException('provider_unavailable', 'سرویس احراز هویت موقتاً پاسخ‌گو نیست.', 503, true, $providerCode);
        if ($httpStatus < 200 || $httpStatus >= 300) throw new VerificationServiceException('provider_request_rejected', 'سرویس احراز هویت درخواست را نپذیرفت. در صورت تکرار با پشتیبانی تماس بگیرید.', 502, false, $providerCode);
        if (!is_array($result) || !array_key_exists('success', $result) || !is_bool($result['success'])) throw new VerificationServiceException('provider_invalid_response', 'پاسخ سرویس احراز هویت قابل پردازش نبود.', 502, true);
        if ($result['success'] !== true) throw new VerificationServiceException('provider_rejected', 'استعلام انجام نشد. اطلاعات را بررسی کنید و در صورت ادامهٔ مشکل با پشتیبانی تماس بگیرید.', 502, false, $providerCode);
        return ['success' => true, 'code' => $providerCode ?? 0,
            'message' => is_string($result['message'] ?? null) ? $result['message'] : null,
            'data' => $result['data'] ?? null];
    }

    public static function validateResponse(string $endpoint, array $result, array $request = []): array
    {
        $data = $result['data'];
        $valid = match ($endpoint) {
            '/api/sw1/ShahkarLite', '/api/sw1/Shahkar', '/api/sw1/ShahkarPro' => is_bool($data),
            '/api/sw1/PersonData', '/api/sw1/PersonInfo' => is_array($data) && !array_is_list($data),
            '/api/sw1/VideoVerifySpeechText' => is_string($data) && mb_strlen(trim($data)) >= 10,
            '/api/sw1/VideoVerify' => is_array($data)
                && count(array_filter(['isPassed', 'isMatch', 'isLiveness', 'isSpeechMatched'], fn($key) => isset($data[$key]) && is_bool($data[$key]))) === 4,
            default => true,
        };
        if (!$valid) throw new VerificationServiceException('provider_invalid_response', 'پاسخ سرویس احراز هویت کامل نبود.', 502, true);
        if (in_array($endpoint, ['/api/sw1/PersonData', '/api/sw1/PersonInfo'], true)) {
            if (isset($data['nationalCode'], $request['nationalCode']) && $data['nationalCode'] !== $request['nationalCode']) {
                throw new VerificationServiceException('provider_invalid_response', 'اطلاعات پاسخ سرویس با درخواست مطابقت ندارد.', 502);
            }
            if (($data['alive'] ?? null) === false) throw new VerificationServiceException('identity_not_verified', 'اطلاعات هویتی تأیید نشد. با پشتیبانی تماس بگیرید.', 422);
        }
        return $result;
    }
}
