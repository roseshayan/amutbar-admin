<?php

declare(strict_types=1);

/**
 * FilesIrClient
 * Integration client for Files.ir Cloud Storage API (BeDrive/REST API).
 * Used for automated offsite backup retention and disaster recovery.
 */
class FilesIrClient
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct(?string $apiKey = null, string $baseUrl = 'https://my.files.ir/api/v1')
    {
        $this->apiKey = $apiKey ?? (string)env('FILES_IR_API_KEY', '');
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    public function isConfigured(): bool
    {
        return !empty(trim($this->apiKey));
    }

    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = trim($apiKey);
    }

    /**
     * Test API connection and retrieve user storage statistics.
     */
    public function testConnection(): array
    {
        if (!$this->isConfigured()) {
            return [
                'ok' => false,
                'message' => 'کلید API سرویس Files.ir تنظیم نشده است.'
            ];
        }

        $res = $this->request('GET', '/user/space-usage');

        if ($res['status'] >= 200 && $res['status'] < 300) {
            $data = $res['data'] ?? [];
            return [
                'ok' => true,
                'message' => 'اتصال به فضای ابری Files.ir با موفقیت برقرار شد.',
                'data' => $data
            ];
        }

        // Try fallback check on file-entries
        $fallback = $this->request('GET', '/drive/file-entries?perPage=1');
        if ($fallback['status'] >= 200 && $fallback['status'] < 300) {
            return [
                'ok' => true,
                'message' => 'اتصال به فضای ابری Files.ir با موفقیت برقرار شد.',
                'data' => $fallback['data'] ?? []
            ];
        }

        $errMsg = $res['data']['message'] ?? $res['error'] ?? 'خطای ناشناخته در احراز هویت API';
        return [
            'ok' => false,
            'message' => 'خطا در ارتباط با Files.ir (کد ' . $res['status'] . '): ' . $errMsg
        ];
    }

    /**
     * Finds or creates a destination folder for backups.
     */
    public function ensureFolder(string $folderName = 'amutbar_backups'): ?int
    {
        // Check existing folders
        $listRes = $this->request('GET', '/drive/file-entries?type=folder&query=' . urlencode($folderName));
        if (!empty($listRes['data']['data'])) {
            foreach ($listRes['data']['data'] as $entry) {
                if (strcasecmp((string)($entry['name'] ?? ''), $folderName) === 0) {
                    return (int)$entry['id'];
                }
            }
        }

        // Create folder
        $createRes = $this->request('POST', '/folders', [
            'name' => $folderName,
            'parentId' => null
        ], true);

        if (!empty($createRes['data']['folder']['id'])) {
            return (int)$createRes['data']['folder']['id'];
        }
        if (!empty($createRes['data']['id'])) {
            return (int)$createRes['data']['id'];
        }

        return null;
    }

    /**
     * Upload backup archive to Files.ir.
     */
    public function uploadFile(string $filePath, ?int $parentId = null): array
    {
        if (!is_file($filePath)) {
            return [
                'ok' => false,
                'message' => 'فایل بک‌آپ محلی یافت نشد: ' . $filePath
            ];
        }

        $url = $this->baseUrl . '/uploads';
        $postData = [
            'file' => new \CURLFile($filePath, 'application/zip', basename($filePath))
        ];
        if ($parentId !== null && $parentId > 0) {
            $postData['parentId'] = (string)$parentId;
        }

        $headers = [
            'Authorization: Bearer ' . $this->apiKey,
            'Accept: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300); // 5 minutes timeout for large files

        $raw = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        unset($ch);

        if ($curlError) {
            return [
                'ok' => false,
                'message' => 'خطای cURL در ارسال فایل به Files.ir: ' . $curlError
            ];
        }

        $json = json_decode((string)$raw, true);

        if ($httpCode >= 200 && $httpCode < 300) {
            $entryId = $json['fileEntry']['id'] ?? $json['id'] ?? null;
            return [
                'ok' => true,
                'message' => 'فایل با موفقیت در فضای ابری Files.ir ذخیره شد.',
                'file_id' => $entryId,
                'response' => $json
            ];
        }

        $errMsg = $json['message'] ?? $raw ?? ('HTTP Code: ' . $httpCode);
        return [
            'ok' => false,
            'message' => 'خطای سرور ابری Files.ir: ' . $errMsg
        ];
    }

    /**
     * List backups stored in Files.ir matching a filename prefix.
     */
    public function listBackupFiles(string $query = 'amutbar_backup'): array
    {
        $res = $this->request('GET', '/drive/file-entries?query=' . urlencode($query) . '&perPage=50');
        if (!empty($res['data']['data']) && is_array($res['data']['data'])) {
            $entries = [];
            foreach ($res['data']['data'] as $item) {
                if (($item['type'] ?? '') !== 'folder') {
                    $entries[] = [
                        'id' => $item['id'] ?? null,
                        'name' => $item['name'] ?? '',
                        'file_name' => $item['file_name'] ?? '',
                        'file_size' => (int)($item['file_size'] ?? 0),
                        'created_at' => $item['created_at'] ?? '',
                        'url' => $item['url'] ?? null
                    ];
                }
            }
            return $entries;
        }

        return [];
    }

    /**
     * Enforces cloud retention: keeps the newest $keepLast backups and permanently deletes older ones.
     */
    public function pruneOldBackups(string $query = 'amutbar_backup', int $keepLast = 2): array
    {
        $files = $this->listBackupFiles($query);
        if (count($files) <= $keepLast) {
            return [
                'pruned_count' => 0,
                'remaining_count' => count($files),
                'deleted_ids' => []
            ];
        }

        // Sort files by creation date / id descending (newest first)
        usort($files, function ($a, $b) {
            return strcmp((string)($b['created_at'] ?? $b['id']), (string)($a['created_at'] ?? $a['id']));
        });

        // Entries to delete (beyond $keepLast)
        $toDelete = array_slice($files, $keepLast);
        $deleteIds = array_column($toDelete, 'id');

        if (!empty($deleteIds)) {
            $delRes = $this->request('POST', '/file-entries/delete', [
                'entryIds' => $deleteIds,
                'deleteForever' => 'true'
            ], true);

            return [
                'pruned_count' => count($deleteIds),
                'remaining_count' => $keepLast,
                'deleted_ids' => $deleteIds,
                'response' => $delRes['data'] ?? null
            ];
        }

        return [
            'pruned_count' => 0,
            'remaining_count' => count($files),
            'deleted_ids' => []
        ];
    }

    /**
     * Internal HTTP request helper.
     */
    private function request(string $method, string $endpoint, ?array $payload = null, bool $isJson = true): array
    {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');
        $headers = [
            'Authorization: Bearer ' . $this->apiKey,
            'Accept: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        if ($payload !== null) {
            if ($isJson) {
                $headers[] = 'Content-Type: application/json';
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_UNICODE));
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
            }
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $raw = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        unset($ch);

        $data = null;
        if ($raw) {
            $data = json_decode($raw, true) ?: $raw;
        }

        return [
            'status' => $httpCode,
            'data' => $data,
            'error' => $curlError
        ];
    }
}
