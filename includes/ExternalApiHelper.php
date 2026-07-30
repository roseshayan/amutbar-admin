<?php
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
        try {
            // دریافت اطلاعات ارائه‌دهنده
            $providerStmt = $this->pdo->prepare("
                SELECT * FROM external_api_providers 
                WHERE slug = ? AND status = 1
            ");
            $providerStmt->execute([$providerSlug]);
            $provider = $providerStmt->fetch();
            
            if (!$provider) {
                throw new Exception("ارائه‌دهنده API یافت نشد: $providerSlug");
            }
            
            // دریافت اعتبارنامه فعال
            $credential = $this->getActiveCredential($provider['id'], $env);
            if (!$credential) {
                throw new Exception("اعتبارنامه فعال برای $providerSlug یافت نشد");
            }
            
            // رمزگشایی اعتبارنامه
            $credential = $this->decryptCredential($credential);
            
            // ساخت URL کامل
            $url = rtrim($provider['base_url'], '/') . '/' . ltrim($endpoint, '/');
            $scheme = strtolower((string)(parse_url($url, PHP_URL_SCHEME) ?: ''));
            if (!in_array($scheme, ['http', 'https'], true)) {
                throw new Exception('پروتکل آدرس ارائه‌دهنده مجاز نیست');
            }
            if (
                strtolower((string)env('APP_ENV', 'local')) === 'production'
                && $scheme !== 'https'
            ) {
                throw new Exception('در محیط عملیاتی آدرس ارائه‌دهنده باید HTTPS باشد');
            }
            
            // آماده‌سازی هدرها
            $headers = [
                'Content-Type: application/json',
                'Accept: application/json'
            ];
            
            // افزودن هدر احراز هویت
            $this->addAuthHeader($headers, $provider['auth_type'], $credential);
            
            // تنظیمات cURL
            $ch = curl_init();
            
            if ($method === 'POST') {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            } elseif ($method === 'PUT') {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            } elseif ($method === 'DELETE') {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            } else {
                if (!empty($data)) {
                    $url .= '?' . http_build_query($data);
                }
            }
            
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, $provider['timeout_ms']);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTP | CURLPROTO_HTTPS);
            
            // اجرای درخواست
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            // ثبت لاگ درخواست
            $this->logApiRequest($provider['id'], $credential['id'], $endpoint, $method, 
                               $httpCode, json_encode($data), $response);
            
            if ($error) {
                throw new Exception("خطای cURL: $error");
            }
            
            $decodedResponse = json_decode($response, true);
            
            // بررسی وضعیت HTTP
            if ($httpCode < 200 || $httpCode >= 300) {
                $errorMsg = isset($decodedResponse['message']) ? 
                           $decodedResponse['message'] : 
                           "خطای HTTP: $httpCode";
                throw new Exception($errorMsg);
            }
            
            return $decodedResponse;
            
        } catch (Exception $e) {
            // ثبت خطا
            error_log("External API Error [$providerSlug]: " . $e->getMessage());
            throw $e;
        }
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
                                 $httpCode, $requestData, $response) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO external_api_request_logs 
                (provider_id, credential_id, operation, http_method, url_path, 
                 http_status, request_redacted_json, response_redacted_json)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
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
                $this->redactSensitiveData($response)
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
