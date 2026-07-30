<?php
declare(strict_types=1);

header('Content-Type: application/json');

require_once '../includes/init.php';

// بررسی احراز هویت ادمین
require_admin();

$action = $_GET['action'] ?? ($_POST['action'] ?? '');

// تابع‌های کمکی
function encrypt_data($data): ?string
{
    if (empty($data)) return null;

    $key = get_encryption_key();
    $iv = random_bytes(12);
    $tag = '';
    $encrypted = openssl_encrypt(
        (string)$data,
        'aes-256-gcm',
        $key,
        OPENSSL_RAW_DATA,
        $iv,
        $tag
    );

    if ($encrypted === false) {
        return null;
    }

    return 'v2:' . base64_encode($iv . $tag . $encrypted);
}

function decrypt_data($encryptedData): ?string
{
    if (empty($encryptedData)) return null;

    $key = get_encryption_key();

    try {
        if (str_starts_with((string)$encryptedData, 'v2:')) {
            $decoded = base64_decode(substr((string)$encryptedData, 3), true);
            if ($decoded === false || strlen($decoded) < 29) return null;
            $iv = substr($decoded, 0, 12);
            $tag = substr($decoded, 12, 16);
            $ciphertext = substr($decoded, 28);
            $decrypted = openssl_decrypt(
                $ciphertext,
                'aes-256-gcm',
                $key,
                OPENSSL_RAW_DATA,
                $iv,
                $tag
            );
            return $decrypted !== false ? $decrypted : null;
        }

        // Backward compatibility for credentials encrypted by older releases.
        $decoded = base64_decode((string)$encryptedData, true);
        if ($decoded === false || !str_contains($decoded, '::')) return null;
        [$encrypted_data, $iv] = explode('::', $decoded, 2);
        $decrypted = openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);

        return $decrypted !== false ? $decrypted : null;
    } catch (Exception $e) {
        return null;
    }
}

try {
    $pdo = db();

    switch ($action) {
        case 'list_providers':
            $stmt = $pdo->query("
                SELECT * FROM external_api_providers 
                ORDER BY priority, created_at DESC
            ");
            $providers = $stmt->fetchAll();

            // اطمینان از اینکه خروجی آرایه است
            if ($providers === false) {
                $providers = [];
            }

            echo json_encode(['data' => $providers]);
            break;

        case 'list_credentials':
            $stmt = $pdo->query("
                SELECT c.*, p.name as provider_name 
                FROM external_api_credentials c
                LEFT JOIN external_api_providers p ON c.provider_id = p.id
                ORDER BY c.created_at DESC
            ");
            $credentials = $stmt->fetchAll();

            if ($credentials === false) {
                $credentials = [];
            }

            echo json_encode(['data' => $credentials]);
            break;

        case 'get_providers_select':
            $stmt = $pdo->query("SELECT id, name FROM external_api_providers WHERE status = 1 ORDER BY name");
            $providers = $stmt->fetchAll();

            if ($providers === false) {
                $providers = [];
            }

            echo json_encode(['status' => 'success', 'data' => $providers]);
            break;

        case 'get_provider':
            $id = intval($_GET['id'] ?? 0);
            $stmt = $pdo->prepare("SELECT * FROM external_api_providers WHERE id = ?");
            $stmt->execute([$id]);
            $provider = $stmt->fetch();

            if ($provider) {
                echo json_encode(['status' => 'success', 'data' => $provider]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'ارائه‌دهنده یافت نشد']);
            }
            break;

        case 'get_credential':
            $id = intval($_GET['id'] ?? 0);
            $stmt = $pdo->prepare("SELECT * FROM external_api_credentials WHERE id = ?");
            $stmt->execute([$id]);
            $credential = $stmt->fetch();

            if ($credential) {
                // رمزگشایی فیلدهای حساس برای نمایش در فرم
                $credential['api_key'] = !empty($credential['api_key_enc']) ?
                    decrypt_data($credential['api_key_enc']) : null;
                $credential['api_secret'] = !empty($credential['api_secret_enc']) ?
                    decrypt_data($credential['api_secret_enc']) : null;
                $credential['bearer_token'] = !empty($credential['bearer_token_enc']) ?
                    decrypt_data($credential['bearer_token_enc']) : null;

                echo json_encode(['status' => 'success', 'data' => $credential]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'اعتبارنامه یافت نشد']);
            }
            break;

        case 'add_provider':
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'slug' => trim($_POST['slug'] ?? ''),
                'base_url' => trim($_POST['base_url'] ?? ''),
                'docs_url' => trim($_POST['docs_url'] ?? null),
                'auth_type' => intval($_POST['auth_type'] ?? 1),
                'status' => intval($_POST['status'] ?? 1),
                'priority' => intval($_POST['priority'] ?? 100),
                'timeout_ms' => intval($_POST['timeout_ms'] ?? 8000),
                'retry_count' => intval($_POST['retry_count'] ?? 1),
                'rate_limit_rpm' => !empty($_POST['rate_limit_rpm']) ? intval($_POST['rate_limit_rpm']) : null
            ];

            // اعتبارسنجی
            if (empty($data['name']) || empty($data['slug']) || empty($data['base_url'])) {
                echo json_encode(['status' => 'error', 'message' => 'پر کردن فیلدهای الزامی ضروری است']);
                exit;
            }

            // بررسی تکراری نبودن slug
            $checkStmt = $pdo->prepare("SELECT id FROM external_api_providers WHERE slug = ?");
            $checkStmt->execute([$data['slug']]);
            if ($checkStmt->fetch()) {
                echo json_encode(['status' => 'error', 'message' => 'این شناسه (slug) قبلاً استفاده شده است']);
                exit;
            }

            $stmt = $pdo->prepare("
                INSERT INTO external_api_providers (name, slug, base_url, docs_url, auth_type, status, priority, timeout_ms, retry_count, rate_limit_rpm)
                VALUES (:name, :slug, :base_url, :docs_url, :auth_type, :status, :priority, :timeout_ms, :retry_count, :rate_limit_rpm)
            ");

            if ($stmt->execute($data)) {
                echo json_encode(['status' => 'success', 'message' => 'ارائه‌دهنده با موفقیت اضافه شد']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'خطا در افزودن ارائه‌دهنده']);
            }
            break;

        case 'update_provider':
            $id = intval($_POST['id'] ?? 0);
            $data = [
                'id' => $id,
                'name' => trim($_POST['name'] ?? ''),
                'slug' => trim($_POST['slug'] ?? ''),
                'base_url' => trim($_POST['base_url'] ?? ''),
                'docs_url' => trim($_POST['docs_url'] ?? null),
                'auth_type' => intval($_POST['auth_type'] ?? 1),
                'status' => intval($_POST['status'] ?? 1),
                'priority' => intval($_POST['priority'] ?? 100),
                'timeout_ms' => intval($_POST['timeout_ms'] ?? 8000),
                'retry_count' => intval($_POST['retry_count'] ?? 1),
                'rate_limit_rpm' => !empty($_POST['rate_limit_rpm']) ? intval($_POST['rate_limit_rpm']) : null
            ];

            // اعتبارسنجی
            if (empty($data['name']) || empty($data['slug']) || empty($data['base_url'])) {
                echo json_encode(['status' => 'error', 'message' => 'پر کردن فیلدهای الزامی ضروری است']);
                exit;
            }

            // بررسی تکراری نبودن slug (به جز رکورد فعلی)
            $checkStmt = $pdo->prepare("SELECT id FROM external_api_providers WHERE slug = ? AND id != ?");
            $checkStmt->execute([$data['slug'], $id]);
            if ($checkStmt->fetch()) {
                echo json_encode(['status' => 'error', 'message' => 'این شناسه (slug) قبلاً استفاده شده است']);
                exit;
            }

            $stmt = $pdo->prepare("
                UPDATE external_api_providers 
                SET name = :name, slug = :slug, base_url = :base_url, docs_url = :docs_url, 
                    auth_type = :auth_type, status = :status, priority = :priority, 
                    timeout_ms = :timeout_ms, retry_count = :retry_count, rate_limit_rpm = :rate_limit_rpm,
                    updated_at = NOW()
                WHERE id = :id
            ");

            if ($stmt->execute($data)) {
                echo json_encode(['status' => 'success', 'message' => 'ارائه‌دهنده با موفقیت به‌روزرسانی شد']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'خطا در به‌روزرسانی ارائه‌دهنده']);
            }
            break;

        case 'delete_provider':
            $id = intval($_POST['id'] ?? 0);

            // بررسی وجود اعتبارنامه‌های مرتبط
            $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM external_api_credentials WHERE provider_id = ?");
            $checkStmt->execute([$id]);
            $credentialCount = $checkStmt->fetchColumn();

            if ($credentialCount > 0) {
                echo json_encode(['status' => 'error', 'message' => 'ابتدا اعتبارنامه‌های مرتبط با این ارائه‌دهنده را حذف کنید']);
                exit;
            }

            $stmt = $pdo->prepare("DELETE FROM external_api_providers WHERE id = ?");
            if ($stmt->execute([$id])) {
                echo json_encode(['status' => 'success', 'message' => 'ارائه‌دهنده با موفقیت حذف شد']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'خطا در حذف ارائه‌دهنده']);
            }
            break;

        case 'add_credential':
            $data = [
                'provider_id' => intval($_POST['provider_id'] ?? 0),
                'env' => intval($_POST['env'] ?? 3),
                'key_id' => trim($_POST['key_id'] ?? null),
                'api_key_enc' => !empty($_POST['api_key']) ? encrypt_data($_POST['api_key']) : null,
                'api_secret_enc' => !empty($_POST['api_secret']) ? encrypt_data($_POST['api_secret']) : null,
                'bearer_token_enc' => !empty($_POST['bearer_token']) ? encrypt_data($_POST['bearer_token']) : null,
                'extra_json' => null,
                'status' => intval($_POST['status'] ?? 1),
                'created_by_user_id' => $_SESSION['admin_user_id']
            ];

            // اعتبارسنجی
            if (empty($data['provider_id'])) {
                echo json_encode(['status' => 'error', 'message' => 'انتخاب ارائه‌دهنده الزامی است']);
                exit;
            }

            // پردازش JSON اضافی
            if (!empty($_POST['extra_json'])) {
                $json = json_decode($_POST['extra_json'], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data['extra_json'] = json_encode($json);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'فرمت JSON تنظیمات اضافی نامعتبر است']);
                    exit;
                }
            }

            $stmt = $pdo->prepare("
                INSERT INTO external_api_credentials 
                (provider_id, env, key_id, api_key_enc, api_secret_enc, bearer_token_enc, extra_json, status, created_by_user_id)
                VALUES (:provider_id, :env, :key_id, :api_key_enc, :api_secret_enc, :bearer_token_enc, :extra_json, :status, :created_by_user_id)
            ");

            if ($stmt->execute($data)) {
                echo json_encode(['status' => 'success', 'message' => 'اعتبارنامه با موفقیت اضافه شد']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'خطا در افزودن اعتبارنامه']);
            }
            break;

        case 'update_credential':
            $id = intval($_POST['id'] ?? 0);

            if (!$id) {
                echo json_encode(['status' => 'error', 'message' => 'شناسه اعتبارنامه نامعتبر است']);
                exit;
            }

            // ابتدا اطلاعات فعلی را دریافت می‌کنیم
            $stmt = $pdo->prepare("SELECT * FROM external_api_credentials WHERE id = ?");
            $stmt->execute([$id]);
            $current = $stmt->fetch();

            if (!$current) {
                echo json_encode(['status' => 'error', 'message' => 'اعتبارنامه یافت نشد']);
                exit;
            }

            // آماده‌سازی داده‌ها
            $data = [
                'id' => $id,
                'provider_id' => intval($_POST['provider_id'] ?? $current['provider_id']),
                'env' => intval($_POST['env'] ?? $current['env']),
                'key_id' => trim($_POST['key_id'] ?? $current['key_id']),
                'status' => intval($_POST['status'] ?? $current['status'])
            ];

            // بررسی و رمزنگاری فیلدهای حساس
            $apiKey = trim($_POST['api_key'] ?? '');
            if ($apiKey !== '') {
                $data['api_key_enc'] = encrypt_data($apiKey);
            } elseif (isset($current['api_key_enc'])) {
                $data['api_key_enc'] = $current['api_key_enc'];
            }

            $apiSecret = trim($_POST['api_secret'] ?? '');
            if ($apiSecret !== '') {
                $data['api_secret_enc'] = encrypt_data($apiSecret);
            } elseif (isset($current['api_secret_enc'])) {
                $data['api_secret_enc'] = $current['api_secret_enc'];
            }

            $bearerToken = trim($_POST['bearer_token'] ?? '');
            if ($bearerToken !== '') {
                $data['bearer_token_enc'] = encrypt_data($bearerToken);
            } elseif (isset($current['bearer_token_enc'])) {
                $data['bearer_token_enc'] = $current['bearer_token_enc'];
            }

            // پردازش JSON اضافی
            if (!empty($_POST['extra_json'])) {
                $json = json_decode($_POST['extra_json'], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data['extra_json'] = json_encode($json);
                } else {
                    $data['extra_json'] = $current['extra_json'];
                }
            } else {
                $data['extra_json'] = $current['extra_json'];
            }

            // ساخت کوئری دینامیک
            $fields = [];
            $values = [];

            foreach ($data as $key => $value) {
                if ($key !== 'id') {
                    $fields[] = "{$key} = :{$key}";
                }
                $values[":{$key}"] = $value;
            }

            $fields[] = "updated_at = NOW()";

            $query = "UPDATE external_api_credentials SET " . implode(', ', $fields) . " WHERE id = :id";

            $stmt = $pdo->prepare($query);

            if ($stmt->execute($values)) {
                echo json_encode(['status' => 'success', 'message' => 'اعتبارنامه با موفقیت به‌روزرسانی شد']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'خطا در به‌روزرسانی اعتبارنامه']);
            }
            break;

        case 'delete_credential':
            $id = intval($_POST['id'] ?? 0);

            if (!$id) {
                echo json_encode(['status' => 'error', 'message' => 'شناسه اعتبارنامه نامعتبر است']);
                exit;
            }

            $stmt = $pdo->prepare("DELETE FROM external_api_credentials WHERE id = ?");

            if ($stmt->execute([$id])) {
                echo json_encode(['status' => 'success', 'message' => 'اعتبارنامه با موفقیت حذف شد']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'خطا در حذف اعتبارنامه']);
            }
            break;

        default:
            echo json_encode(['status' => 'error', 'message' => 'عملیات نامعتبر']);
    }
} catch (PDOException $e) {
    error_log("Database error in external-apis.php: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'خطای پایگاه داده: ' . $e->getMessage()]);
} catch (Exception $e) {
    error_log("Error in external-apis.php: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
