<?php
declare(strict_types=1);

require_once __DIR__ . '/ExternalApiHelper.php';

/**
 * اجرای یکنواخت سرویس‌های احراز هویت (پنل و API اپلیکیشن)
 * - لیست سرویس‌های فعال بر اساس نقش کاربر
 * - اجرای سرویس و ثبت در identity_verification_jobs
 */
final class IdentityVerificationRunner
{
    private PDO $pdo;
    private ExternalApiHelper $api;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->api = new ExternalApiHelper($pdo);
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
        return $rows ?: [];
    }

    public function runServiceForUser(
        int $serviceId,
        int $subjectUserId,
        ?int $actorAdminId = null,
        array $payloadOverrides = []
    ): array {
        $svc = $this->getServiceById($serviceId);
        if (!$svc || (int)$svc['is_active'] !== 1) {
            return $this->fail('سرویس یافت نشد یا غیرفعال است');
        }

        $user = $this->getUserCore($subjectUserId);
        if (!$user) return $this->fail('کاربر یافت نشد');

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

        // required
        $required = $cfg['required'] ?? [];
        if (!is_array($required)) $required = [];

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
            $vb = $this->loadVerificationVideoBase64((int)$user['id']);
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

            $this->finishJob($jobId, $normalized, true);

            return [
                'ok' => true,
                'job_id' => $jobId,
                'latency_ms' => $elapsedMs,
                'service' => [
                    'id' => (int)$svc['id'],
                    'code' => (string)$svc['code'],
                    'title' => (string)$svc['title'],
                ],
                'result' => $normalized,
            ];
        } catch (Throwable $e) {
            $this->finishJob($jobId, ['success' => false, 'error' => $e->getMessage()], false);
            return $this->fail($e->getMessage(), ['job_id' => $jobId]);
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

    private function loadVerificationVideoBase64(int $userId): ?string
    {
        // file_type=6 => verification_video
        $st = $this->pdo->prepare("SELECT file_key, file_size FROM user_files WHERE user_id=? AND file_type=6 ORDER BY id DESC LIMIT 1");
        $st->execute([$userId]);
        $row = $st->fetch();
        if (!$row || empty($row['file_key'])) return null;

        $size = (int)($row['file_size'] ?? 0);
        // سقف برای جلوگیری از فشار حافظه/CPU (base64 حدود 33% بزرگ‌تر می‌شود)
        if ($size <= 0 || $size > 12 * 1024 * 1024) {
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
