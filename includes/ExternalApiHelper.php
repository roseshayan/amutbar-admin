<?php
require_once __DIR__ . "/ApiIrContract.php";
class ExternalApiHelper {
    private $pdo;
    
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * دریافت کلید رمزنگاری
     */
    private function getEncryptionKey(): string {
        return get_encryption_key();
    }
    
    /**
     * رمزگشایی داده
     */
    private function decryptData($encryptedData) {
        if (empty($encryptedData)) return null;
        
        $key = $this->getEncryptionKey();
        
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
            if ($decoded === false || strpos($decoded, '::') === false) return null;
            [$encrypted_data, $iv] = explode('::', $decoded, 2);
            $decrypted = openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);
            
            return $decrypted !== false ? $decrypted : null;
        } catch (Exception $e) {
            error_log("Decryption failed: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * دریافت اعتبارنامه فعال برای یک ارائه‌دهنده
     */
    public function getActiveCredential($providerId, $env = 3) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM external_api_credentials 
            WHERE provider_id = ? AND env = ? AND status = 1
            ORDER BY created_at DESC 
            LIMIT 1
        ");
        $stmt->execute([$providerId, $env]);
        $credential = $stmt->fetch();
        
        if (!$credential) {
            return null;
        }
        
        // رمزگشایی فیلدهای حساس
        return $this->decryptCredential($credential);
    }
    
    /**
     * رمزگشایی اعتبارنامه
     */
    private function decryptCredential($credential) {
        if (!$credential) return null;
        
        // لیست فیلدهای رمز شده
        $encryptedFields = [
            'api_key_enc' => 'api_key',
            'api_secret_enc' => 'api_secret',
            'bearer_token_enc' => 'bearer_token'
        ];
        
        foreach ($encryptedFields as $encryptedField => $decryptedField) {
            if (!empty($credential[$encryptedField])) {
                $credential[$decryptedField] = $this->decryptData($credential[$encryptedField]);
            } else {
                $credential[$decryptedField] = null;
            }
        }
        
        // پردازش JSON اضافی
        if (!empty($credential['extra_json'])) {
            $credential['extra'] = json_decode($credential['extra_json'], true);
        }
        
        return $credential;
    }
    
    /**
     * دریافت اطلاعات ارائه‌دهنده API
     */
    public function getApiProvider($providerId) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM external_api_providers 
            WHERE id = ? AND status = 1
        ");
        $stmt->execute([$providerId]);
        return $stmt->fetch();
    }
    
    /**
     * ارسال درخواست به API خارجی
     */
    public function callExternalApi($providerSlug, $endpoint, $method = 'GET', $data = [], $env = 3) {
        $requestId = bin2hex(random_bytes(16));
        $provider = null;
        $credential = null;
        $httpCode = 0;
        $started = microtime(true);
        $errorCode = null;
        $decoded = null;
        try {
            $st = $this->pdo->prepare("SELECT * FROM external_api_providers WHERE slug=? AND status=1");
            $st->execute([$providerSlug]);
            $provider = $st->fetch();
            if (!$provider) throw new VerificationServiceException('provider_configuration_error', 'سرویس احراز هویت فعال نیست. با پشتیبانی تماس بگیرید.', 503);
            $credential = $this->getActiveCredential($provider['id'], $env);
            if (!$credential) throw new VerificationServiceException('provider_credentials_error', 'دسترسی سرویس احراز هویت تنظیم نشده است.', 503);
            $method = strtoupper((string)$method);
            $timeout = max(1000, min(180000, (int)$provider['timeout_ms']));
            $headers = ['Content-Type: application/json', 'Accept: application/json'];
            if ($providerSlug === 'api_ir') {
                $request = ApiIrContract::prepare((string)$endpoint, $data);
                $endpoint = $request['endpoint'];
                $data = $request['data'];
                $timeout = max($timeout, $request['timeout_ms']);
                $url = ApiIrContract::BASE_URL . '/' . ltrim($endpoint, '/');
                $method = 'POST';
                $token = trim((string)(($credential['bearer_token'] ?? '') ?: ($credential['api_key'] ?? '')));
                if ($token === '' || preg_match('/[\r\n]/', $token)) throw new VerificationServiceException('provider_credentials_error', 'کلید سرویس احراز هویت نیاز به بررسی پشتیبانی دارد.', 503);
                $headers[] = 'Authorization: Bearer ' . $token;
            } else {
                $url = rtrim($provider['base_url'], '/') . '/' . ltrim($endpoint, '/');
                $this->addAuthHeader($headers, $provider['auth_type'], $credential);
            }
            $scheme = strtolower((string)parse_url($url, PHP_URL_SCHEME));
            if (!in_array($scheme, ['http', 'https'], true) || (env('APP_ENV', 'local') === 'production' && $scheme !== 'https')) {
                throw new VerificationServiceException('provider_configuration_error', 'آدرس سرویس نامعتبر است.', 503);
            }
            // No automatic retry: a timed-out POST may already have consumed credit.
            if (is_callable('set_time_limit')) @set_time_limit((int)ceil($timeout / 1000) + 30);
            $response = $this->sendRequest($url, $method, $data, $headers, $timeout);
            $httpCode = $response['status'];
            if ($providerSlug === 'api_ir') {
                $decoded = ApiIrContract::validateResponse($endpoint, ApiIrContract::decode($httpCode, $response['body'], $response['errno']), $data);
            } else {
                if ($response['errno'] !== 0 || $httpCode < 200 || $httpCode >= 300) throw new VerificationServiceException('provider_unavailable', 'ارتباط با سرویس خارجی برقرار نشد.', 502, true);
                $decoded = json_decode($response['body'], true);
                if (!is_array($decoded)) throw new VerificationServiceException('provider_invalid_response', 'پاسخ سرویس قابل پردازش نبود.', 502, true);
            }
            return $decoded;
        } catch (VerificationServiceException $e) {
            $e->requestId = $requestId;
            $errorCode = $e->errorCode;
            error_log("External API [$providerSlug] request=$requestId error=$errorCode http=$httpCode provider_code=" . ($e->providerCode ?? 'unknown'));
            throw $e;
        } catch (Throwable $e) {
            $errorCode = 'provider_internal_error';
            error_log("External API [$providerSlug] request=$requestId error=$errorCode type=" . get_class($e));
            throw new VerificationServiceException($errorCode, 'خطایی در پردازش استعلام رخ داد. با پشتیبانی تماس بگیرید.', 502, false, null, $requestId);
        } finally {
            if ($provider && $credential) {
                $summary = ['success' => $decoded['success'] ?? false, 'code' => $decoded['code'] ?? null, 'error_code' => $errorCode];
                $this->logApiRequest($provider['id'], $credential['id'], $endpoint, $method, $httpCode,
                    json_encode(['fields' => array_keys($data)]), json_encode($summary), $requestId,
                    (int)round((microtime(true) - $started) * 1000), $errorCode);
            }
        }
    }

    /** Transport seam allows fixture tests without any paid provider requests. */
    protected function sendRequest(string $url, string $method, array $data, array $headers, int $timeout): array {
        $ch = curl_init();
        $body = '';
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CONNECTTIMEOUT_MS => min(10000, $timeout),
            CURLOPT_TIMEOUT_MS => $timeout,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_WRITEFUNCTION => static function ($handle, string $chunk) use (&$body): int {
                if (strlen($body) + strlen($chunk) > 16 * 1024 * 1024) return 0;
                $body .= $chunk;
                return strlen($chunk);
            },
        ];
        if ($method === 'GET') {
            if ($data) $options[CURLOPT_URL] .= '?' . http_build_query($data);
        } else {
            $options[CURLOPT_CUSTOMREQUEST] = $method;
            $options[CURLOPT_POSTFIELDS] = json_encode($data ?: new stdClass(), JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        }
        try {
            curl_setopt_array($ch, $options);
            curl_exec($ch);
            return ['status' => (int)curl_getinfo($ch, CURLINFO_HTTP_CODE), 'errno' => curl_errno($ch), 'body' => $body];
        } finally { curl_close($ch); }
    }

    /**
     * افزودن هدر احراز هویت
     */
    private function addAuthHeader(&$headers, $authType, $credential) {
        switch ($authType) {
            case 1: // API Key
                if (!empty($credential['api_key'])) {
                    $headers[] = "X-API-Key: " . $credential['api_key'];
                }
                break;
                
            case 2: // Bearer Token
                if (!empty($credential['bearer_token'])) {
                    $headers[] = "Authorization: Bearer " . $credential['bearer_token'];
                }
                break;
                
            case 3: // Basic Auth
                if (!empty($credential['api_key']) && !empty($credential['api_secret'])) {
                    $auth = base64_encode($credential['api_key'] . ':' . $credential['api_secret']);
                    $headers[] = "Authorization: Basic " . $auth;
                }
                break;
                
            case 4: // OAuth 2.0
                if (!empty($credential['bearer_token'])) {
                    $headers[] = "Authorization: Bearer " . $credential['bearer_token'];
                }
                break;
        }
    }
    
    /**
     * ثبت لاگ درخواست API
     */
    private function logApiRequest($providerId, $credentialId, $endpoint, $method, 
                                 $httpCode, $requestData, $response, $requestId = null, $latency = null, $errorCode = null) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO external_api_request_logs 
                (provider_id, credential_id, operation, http_method, url_path, 
                 http_status, request_redacted_json, response_redacted_json, request_id, latency_ms, error_code)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $operation = $this->extractOperationFromEndpoint($endpoint);
            
            $stmt->execute([
                $providerId,
                $credentialId,
                $operation,
                $method,
                $endpoint,
                $httpCode,
                $this->redactSensitiveData($requestData),
                $this->redactSensitiveData($response), $requestId, $latency, $errorCode
            ]);
        } catch (Exception $e) {
            error_log("Failed to log API request: " . $e->getMessage());
        }
    }
    
    /**
     * استخراج نام عملیات از endpoint
     */
    private function extractOperationFromEndpoint($endpoint) {
        $parts = explode('/', $endpoint);
        $lastPart = end($parts);
        
        if (empty($lastPart)) {
            $lastPart = $parts[count($parts) - 2] ?? 'unknown';
        }
        
        return $lastPart;
    }
    
    /**
     * حذف اطلاعات حساس از داده‌ها برای لاگ
     */
    private function redactSensitiveData($data) {
        if (is_string($data)) {
            $decoded = json_decode($data, true);
            $data = is_array($decoded) ? $decoded : ['body' => mb_substr($data, 0, 2000)];
        }

        $sensitiveFields = [
            'password', 'token', 'secret', 'key', 'authorization',
            'national', 'mobile', 'phone', 'birth', 'email', 'address',
            'postal', 'serial', 'video', 'image', 'document', 'file',
        ];
        $walk = function ($value) use (&$walk, $sensitiveFields) {
            if (!is_array($value)) return $value;
            $result = [];
            foreach ($value as $key => $item) {
                $redact = false;
                if (is_string($key)) {
                    foreach ($sensitiveFields as $field) {
                        if (stripos($key, $field) !== false) {
                            $redact = true;
                            break;
                        }
                    }
                }
                $result[$key] = $redact ? '[REDACTED]' : $walk($item);
            }
            return $result;
        };

        $json = json_encode($walk($data), JSON_UNESCAPED_UNICODE);
        if ($json === false) return '{}';
        if (strlen($json) > 32768) {
            return json_encode([
                'truncated' => true,
                'original_size' => strlen($json),
                'sha256' => hash('sha256', $json),
            ], JSON_UNESCAPED_UNICODE) ?: '{}';
        }
        return $json;
    }
    
    /**
     * مثال: احراز هویت با API ایران
     */
    public function verifyWithApiIr($nationalCode, $phoneNumber, $isCompany = false) {
        try {
            $data = [
                'nationalCode' => $nationalCode,
                'mobile' => $phoneNumber,
                'isCompany' => $isCompany
            ];
            
            $response = $this->callExternalApi(
                'api_ir',        // slug ارائه‌دهنده
                '/api/sw1/Shahkar',
                'POST',
                $data
            );
            
            return [
                'success' => $response['success'] ?? false,
                'data' => $response['data'] ?? false,
                'message' => $response['message'] ?? null
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * مثال: دریافت اطلاعات هویتی از API ایران
     */
    public function getIdentityInfo($nationalCode, $birthDate) {
        try {
            $data = [
                'nationalCode' => $nationalCode,
                'birthDate' => $birthDate
            ];
            
            $response = $this->callExternalApi(
                'api_ir',
                '/api/sw1/PersonData',
                'POST',
                $data
            );
            
            return [
                'success' => $response['success'] ?? false,
                'data' => $response['data'] ?? null,
                'message' => $response['message'] ?? null
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
