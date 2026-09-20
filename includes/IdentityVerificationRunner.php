<?php
declare(strict_types=1);

require_once __DIR__ . '/ExternalApiHelper.php';
require_once __DIR__ . '/app_roles.php';
require_once __DIR__ . '/verification_policy.php';

/**
 * اجرای یکنواخت سرویس‌های احراز هویت (پنل و API اپلیکیشن)
 * - لیست سرویس‌های فعال بر اساس نقش کاربر
 * - اجرای سرویس و ثبت در identity_verification_jobs
 */
final class IdentityVerificationRunner
{
    private PDO $pdo;
    private ExternalApiHelper $api;

    public function __construct(PDO $pdo, ?ExternalApiHelper $api = null)
    {
        $this->pdo = $pdo;
        $this->api = $api ?? new ExternalApiHelper($pdo);
    }

    public function listActiveServicesForUserType(int $userType): array
    {
        // 1=driver, 2=company, 3=admin
        $subjectKind = ($userType === 1) ? 1 : (($userType === 2) ? 2 : 0);

        $st = $this->pdo->prepare("
            SELECT s.*, p.slug AS provider_slug, p.name AS provider_name
            FROM identity_verification_services s
            JOIN external_api_providers p ON p.id = s.provider_id
            WHERE s.is_active=1 AND (s.subject_kind=0 OR s.subject_kind=?)
            ORDER BY s.sort_order ASC, s.id ASC
        ");
        $st->execute([$subjectKind]);
        $rows = $st->fetchAll();
        return array_map([self::class, 'currentService'], $rows ?: []);
    }

    public static function currentService(array $svc): array
    {
        $svc['available'] = true;
        if (($svc['provider_slug'] ?? '') !== 'api_ir') return $svc;
        $path = '/' . ltrim((string)$svc['endpoint_path'], '/');
        if ($path === '/api/sw1/PersonImage') {
            $path = '/api/sw1/PersonData';
            $svc['code'] = 'PersonData';
            $svc['title'] = 'استعلام مشخصات و تصویر هویتی';
        }
        $svc['endpoint_path'] = $path;
        $svc['http_method'] = 'POST';
        $required = match ($path) {
            '/api/sw1/ShahkarLite', '/api/sw1/Shahkar', '/api/sw1/ShahkarPro' => ['nationalCode', 'mobile'],
            '/api/sw1/PersonData', '/api/sw1/PersonInfo' => ['nationalCode', 'birthDate'],
            '/api/sw1/VideoVerify' => ['nationalCode', 'birthDate', 'serialNumber', 'videoBase64', 'speechText'],
            default => null,
        };
        $cfg = json_decode((string)($svc['config_json'] ?? ''), true) ?: [];
        if (!is_array($cfg)) $cfg = [];
        if ($required !== null) $cfg['required'] = $required;
        $svc['config_json'] = json_encode($cfg, JSON_UNESCAPED_UNICODE);
        if (in_array($path, ['/api/sw1/Shahkar2', '/api/sw1/VideoMatch'], true)) {
            $svc['available'] = false;
            $svc['description'] = 'این سرویس در قرارداد فعلی موجود نیست؛ از شاهکار لایت یا VideoVerify استفاده کنید.';
        } elseif ($path === '/api/sw1/PersonData') {
            $svc['description'] = 'نیازمند کد ملی و تاریخ تولد ذخیره‌شده؛ بدون نیاز به سریال کارت.';
        } elseif ($path === '/api/sw1/VideoVerify') {
            $svc['description'] = 'اجرای مجدد آخرین ویدئوی همین اپ با متن اصلی ضبط؛ استعلام مجدد ممکن است هزینه داشته باشد.';
        }
        return $svc;
    }

    public function runServiceForUser(
        int $serviceId,
        int $subjectUserId,
        ?int $actorAdminId = null,
        array $payloadOverrides = [],
        ?int $appRole = null,
        ?string $videoFileKey = null
    ): array {
        $svc = $this->getServiceById($serviceId);
        if (!$svc || (int)$svc['is_active'] !== 1) {
            return $this->fail('سرویس یافت نشد یا غیرفعال است');
        }

        $svc = self::currentService($svc);
        if (!$svc['available']) return $this->fail($svc['description'], ['code' => 'provider_service_obsolete']);

        $user = $this->getUserCore($subjectUserId);
        if (!$user) return $this->fail('کاربر یافت نشد');
        if ($appRole !== null) {
            if (!in_array($appRole, [1, 2], true) || !api_user_has_app_role($user, $appRole)) return $this->fail('نقش نامعتبر است');
            $user['user_type'] = $appRole;
        }

        if ((int)$svc['subject_kind'] !== 0 && (int)$svc['subject_kind'] !== (int)$user['user_type']) {
            return $this->fail('این سرویس برای نقش انتخاب‌شده فعال نیست.', ['code' => 'invalid_app_role']);
        }
        if ($actorAdminId !== null && $svc['endpoint_path'] === '/api/sw1/VideoVerify') {
            $st = $this->pdo->prepare("SELECT file_key, metadata FROM user_files WHERE user_id=? AND file_type=6 AND JSON_EXTRACT(metadata, '$.app_role')=? ORDER BY id DESC LIMIT 1");
            $st->execute([$subjectUserId, (int)$user['user_type']]);
            $file = $st->fetch();
            $meta = $file ? json_decode((string)$file['metadata'], true) : null;
            if (!is_array($meta) || empty($meta['speech_text'])) return $this->fail('ویدئوی دارای متن معتبر برای این اپ موجود نیست؛ کاربر باید در نسخهٔ جدید اپ دوباره ضبط کند.', ['code' => 'video_recording_required']);
            $videoFileKey = $file['file_key'];
            $payloadOverrides['speechText'] = $meta['speech_text'];
        }

        // payload پایه از اطلاعات کاربر
        $payload = $this->buildBasePayloadFromUser($user);

        // سریال کارت ملی (اگر در پروژه ذخیره می‌کنی)
        if (!empty($user['national_card_serial'])) {
            $payload['serialNumber'] = (string)$user['national_card_serial'];
        }

        // Override های UI (پلاک، ویدیو و ...)
        foreach ($payloadOverrides as $k => $v) {
            $payload[$k] = $v;
        }

        $cfg = $svc['config_json'] ? json_decode((string)$svc['config_json'], true) : [];
        if (!is_array($cfg)) $cfg = [];

        // defaults
        if (!empty($cfg['defaults']) && is_array($cfg['defaults'])) {
            foreach ($cfg['defaults'] as $k => $v) {
                if (!array_key_exists($k, $payload)) $payload[$k] = $v;
            }
        }

        if ($svc['provider_slug'] === 'api_ir' && $svc['code'] === 'VideoVerify') {
            $payload['videoBase64'] = $this->loadVerificationVideoBase64((int)$user['id'], $videoFileKey) ?? '';
        }

        // required
        $required = $cfg['required'] ?? [];
        if (!is_array($required)) $required = [];
        if ($svc['provider_slug'] === 'api_ir' && in_array($svc['endpoint_path'], ['/api/sw1/PersonImage', '/api/sw1/PersonData', '/api/sw1/PersonInfo'], true)) {
            $required = ['nationalCode', 'birthDate'];
        }

        // پشتیبانی api.ir biometric: videoBase64 / videobase64
        // اگر سرویس این فیلد را required کرده باشد، از فایل ویدئوی احراز هویت کاربر base64 می‌سازیم.
        $videoRequiredKey = null;
        foreach ($required as $rk) {
            if (strtolower((string)$rk) === 'videobase64') {
                $videoRequiredKey = (string)$rk; // حفظ همان کیس/نام کلید سرویس
                break;
            }
        }
        if ($videoRequiredKey !== null && (!isset($payload[$videoRequiredKey]) || !$payload[$videoRequiredKey])) {
            $vb = $this->loadVerificationVideoBase64((int)$user['id'], $videoFileKey);
            if ($vb !== null) {
                $payload[$videoRequiredKey] = $vb;
            }
        }

        foreach ($required as $key) {
            if (!array_key_exists($key, $payload) || $payload[$key] === '' || $payload[$key] === null) {
                return $this->fail("فیلد اجباری ناقص است: {$key}");
            }
        }

        // ایجاد job
        $jobId = $this->createJob($user, $svc, $payload, $actorAdminId);

        $startedAt = microtime(true);
        try {
            $resp = $this->api->callExternalApi(
                (string)$svc['provider_slug'],
                (string)$svc['endpoint_path'],
                strtoupper((string)$svc['http_method']),
                $payload
            );

            $elapsedMs = (int)round((microtime(true) - $startedAt) * 1000);
            $normalized = $this->normalizeApiResponse($resp);

            $verified = null;
            if ($svc['provider_slug'] === 'api_ir') {
                if (in_array($svc['code'], ['ShahkarLite', 'Shahkar', 'ShahkarPro'], true)) $verified = $normalized['data'] === true;
                if ($svc['code'] === 'VideoVerify') $verified = verification_video_passed($normalized);
            }
            $this->finishJob($jobId, $normalized, $verified !== false);

            return [
                'ok' => true,
                'verified' => $verified,
                'job_id' => $jobId,
                'latency_ms' => $elapsedMs,
                'service' => [
                    'id' => (int)$svc['id'],
                    'code' => (string)$svc['code'],
                    'title' => (string)$svc['title'],
                ],
                'result' => $normalized,
            ];
        } catch (VerificationServiceException $e) {
            $this->finishJob($jobId, ['success' => false, 'code' => $e->errorCode, 'request_id' => $e->requestId], false);
            return $this->fail($e->getMessage(), ['job_id' => $jobId, 'status' => $e->httpStatus] + $e->publicPayload());
        } catch (Throwable $e) {
            $this->finishJob($jobId, ['success' => false, 'error' => $e->getMessage()], false);
            error_log('verification.runner unexpected type=' . get_class($e));
            return $this->fail('خطایی در پردازش احراز هویت رخ داد.', ['job_id' => $jobId, 'code' => 'verification_internal_error', 'status' => 500]);
        }
    }

    private function getServiceById(int $id): ?array
    {
        $st = $this->pdo->prepare("
            SELECT s.*, p.slug AS provider_slug
            FROM identity_verification_services s
            JOIN external_api_providers p ON p.id=s.provider_id
            WHERE s.id=?
            LIMIT 1
        ");
        $st->execute([$id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    private function getUserCore(int $id): ?array
    {
        $st = $this->pdo->prepare("
            SELECT id, user_type, full_name, phone, code_meli, birth_date, national_card_serial
            FROM users
            WHERE id=? AND deleted_at IS NULL
            LIMIT 1
        ");
        $st->execute([$id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    private function buildBasePayloadFromUser(array $user): array
    {
        $payload = [];

        if (!empty($user['code_meli'])) $payload['nationalCode'] = (string)$user['code_meli'];
        if (!empty($user['phone'])) $payload['mobile'] = (string)$user['phone'];
        if (!empty($user['birth_date'])) $payload['birthDate'] = (string)$user['birth_date'];

        return $payload;
    }

    private function loadVerificationVideoBase64(int $userId, ?string $fileKey = null): ?string
    {
        // file_type=6 => verification_video
        $st = $this->pdo->prepare("SELECT file_key, file_size FROM user_files WHERE user_id=? AND file_type=6 AND (? IS NULL OR file_key=?) ORDER BY id DESC LIMIT 1");
        $st->execute([$userId, $fileKey, $fileKey]);
        $row = $st->fetch();
        if (!$row || empty($row['file_key'])) return null;

        $size = (int)($row['file_size'] ?? 0);
        // سقف برای جلوگیری از فشار حافظه/CPU (base64 حدود 33% بزرگ‌تر می‌شود)
        if ($size <= 0 || $size > ApiIrContract::MAX_VIDEO_BYTES) {
            return null;
        }

        $key = ltrim(str_replace('\\', '/', (string)$row['file_key']), '/');
        if ($key === '' || str_contains($key, '..')) return null;
        $path = str_starts_with($key, 'storage/uploads/')
            ? BASE_PATH . '/' . $key
            : BASE_PATH . '/storage/uploads/' . $key;
        if (!is_file($path)) return null;

        $bin = file_get_contents($path);
        if ($bin === false) return null;
        return base64_encode($bin);
    }

    private function createJob(array $user, array $svc, array $payload, ?int $actorAdminId): int
    {
        $subjectKind = ((int)$user['user_type'] === 1) ? 1 : (((int)$user['user_type'] === 2) ? 2 : 0);

        $st = $this->pdo->prepare("
            INSERT INTO identity_verification_jobs
            (subject_user_id, subject_kind, check_type, provider_id, credential_id, status,
             request_id, request_redacted_json, started_at, created_by_user_id, created_at)
            VALUES
            (:subject_user_id, :subject_kind, :check_type, :provider_id, NULL, 1,
             NULL, :request_redacted_json, NOW(3), :created_by, NOW(3))
        ");

        $reqJson = json_encode($this->redact($payload), JSON_UNESCAPED_UNICODE);
        $st->execute([
            ':subject_user_id' => (int)$user['id'],
            ':subject_kind' => $subjectKind,
            ':check_type' => (string)$svc['code'],
            ':provider_id' => (int)$svc['provider_id'],
            ':request_redacted_json' => $reqJson,
            ':created_by' => $actorAdminId,
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    private function finishJob(int $jobId, array $normalized, bool $success): void
    {
        // قرارداد داخلی: 2=موفق، 4=ناموفق
        $status = $success ? 2 : 4;
        $respJson = json_encode($this->redact($normalized), JSON_UNESCAPED_UNICODE);

        $st = $this->pdo->prepare("
            UPDATE identity_verification_jobs
            SET status=:status,
                response_redacted_json=:resp,
                finished_at=NOW(3)
            WHERE id=:id
            LIMIT 1
        ");
        $st->execute([
            ':status' => $status,
            ':resp' => $respJson,
            ':id' => $jobId,
        ]);
    }

    private function normalizeApiResponse(array $resp): array
    {
        // api.ir معمولاً: success, code, data, error, message
        return [
            'success' => (bool)($resp['success'] ?? false),
            'code' => $resp['code'] ?? null,
            'data' => $resp['data'] ?? null,
            'error' => $resp['error'] ?? null,
            'message' => $resp['message'] ?? null,
        ];
    }

    private function redact($data)
    {
        if (!is_array($data)) return $data;
        foreach ($data as $k => $v) {
            $lk = strtolower((string)$k);
            $sensitive = [
                'token', 'secret', 'key', 'authorization', 'video',
                'national', 'mobile', 'phone', 'birth', 'serial',
                'image', 'photo', 'address', 'postal', 'email',
            ];
            $mustRedact = false;
            foreach ($sensitive as $field) {
                if (str_contains($lk, $field)) {
                    $mustRedact = true;
                    break;
                }
            }
            if ($mustRedact) {
                $data[$k] = '[REDACTED]';
            } elseif (is_array($v)) {
                $data[$k] = $this->redact($v);
            }
        }
        return $data;
    }

    private function fail(string $message, array $extra = []): array
    {
        return ['ok' => false, 'message' => $message] + $extra;
    }
}
