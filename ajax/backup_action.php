<?php

declare(strict_types=1);

set_time_limit(600); // 10 minutes for backup/restore operations
ini_set('memory_limit', '512M');

require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/backup_service.php';
require_once __DIR__ . '/../includes/FilesIrClient.php';

$action = (string)($_GET['action'] ?? $_POST['action'] ?? 'list');

// -------------------------------------------------------------
// ACTION: download
// -------------------------------------------------------------
if ($action === 'download') {
    $filename = basename((string)($_GET['filename'] ?? ''));
    $backupDir = backup_get_dir();
    $filePath = $backupDir . '/' . $filename;

    if (!empty($filename) && is_file($filePath) && str_ends_with($filename, '.zip')) {
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filePath));
        header('Cache-Control: no-cache, no-store, must-revalidate');
        readfile($filePath);
        exit(0);
    }

    http_response_code(404);
    echo "Backup file not found.";
    exit(1);
}

header('Content-Type: application/json; charset=utf-8');

try {
    // -------------------------------------------------------------
    // ACTION: list
    // -------------------------------------------------------------
    if ($action === 'list') {
        $localBackups = backup_list_local();

        $cloudConfigured = !empty(env('FILES_IR_API_KEY'));
        $cloudEnabled = (string)env('BACKUP_CLOUD_ENABLED', '0') === '1';

        $cloudBackups = [];
        if ($cloudConfigured) {
            try {
                $client = new FilesIrClient();
                $cloudBackups = $client->listBackupFiles('amutbar_backup');
            } catch (\Throwable $e) {
                // Silently handle cloud listing failure
            }
        }

        echo json_encode([
            'ok' => true,
            'local_backups' => $localBackups,
            'cloud_backups' => $cloudBackups,
            'cloud_enabled' => $cloudEnabled,
            'cloud_configured' => $cloudConfigured,
            'local_keep_last' => (int)env('BACKUP_LOCAL_KEEP_LAST', 5),
            'cloud_keep_last' => (int)env('FILES_IR_KEEP_LAST', 2)
        ], JSON_UNESCAPED_UNICODE);
        exit(0);
    }

    // -------------------------------------------------------------
    // ACTION: create
    // -------------------------------------------------------------
    if ($action === 'create') {
        $syncCloud = !empty($_POST['sync_cloud']);
        $apiKey = trim((string)($_POST['api_key'] ?? ''));

        $options = [];
        if ($syncCloud) {
            $options['force_cloud'] = true;
            if ($apiKey !== '') {
                $options['files_ir_api_key'] = $apiKey;
            }
        }

        $res = backup_create_full($options);

        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        exit(0);
    }

    // -------------------------------------------------------------
    // ACTION: restore
    // -------------------------------------------------------------
    if ($action === 'restore') {
        $filename = (string)($_POST['filename'] ?? '');
        if (empty($filename)) {
            echo json_encode(['ok' => false, 'message' => 'نام فایل برای بازیابی ارسال نشده است.'], JSON_UNESCAPED_UNICODE);
            exit(0);
        }

        $res = backup_restore($filename);
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        exit(0);
    }

    // -------------------------------------------------------------
    // ACTION: delete
    // -------------------------------------------------------------
    if ($action === 'delete') {
        $filename = basename((string)($_POST['filename'] ?? ''));
        $backupDir = backup_get_dir();
        $target = $backupDir . '/' . $filename;

        if (is_file($target)) {
            @unlink($target);
            echo json_encode(['ok' => true, 'message' => 'فایل پشتیبان با موفقیت حذف شد.'], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['ok' => false, 'message' => 'فایل پشتیبان مورد نظر یافت نشد.'], JSON_UNESCAPED_UNICODE);
        }
        exit(0);
    }

    // -------------------------------------------------------------
    // ACTION: test_cloud
    // -------------------------------------------------------------
    if ($action === 'test_cloud') {
        $apiKey = trim((string)($_POST['api_key'] ?? env('FILES_IR_API_KEY', '')));
        if (empty($apiKey)) {
            echo json_encode(['ok' => false, 'message' => 'لطفاً کلید API را وارد کنید.'], JSON_UNESCAPED_UNICODE);
            exit(0);
        }

        $client = new FilesIrClient($apiKey);
        $res = $client->testConnection();
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        exit(0);
    }

    // -------------------------------------------------------------
    // ACTION: save_settings
    // -------------------------------------------------------------
    if ($action === 'save_settings') {
        $cloudEnabled = !empty($_POST['cloud_enabled']) ? '1' : '0';
        $apiKey = trim((string)($_POST['api_key'] ?? ''));
        $localKeep = max(1, (int)($_POST['local_keep'] ?? 5));
        $cloudKeep = max(1, (int)($_POST['cloud_keep'] ?? 2));

        $envFile = BASE_PATH . '/.env';
        if (is_file($envFile)) {
            $envContent = file_get_contents($envFile);

            $replacements = [
                'BACKUP_CLOUD_ENABLED' => $cloudEnabled,
                'FILES_IR_API_KEY' => $apiKey,
                'BACKUP_LOCAL_KEEP_LAST' => (string)$localKeep,
                'FILES_IR_KEEP_LAST' => (string)$cloudKeep,
            ];

            foreach ($replacements as $k => $v) {
                if (preg_match("/^{$k}=.*/m", $envContent)) {
                    $envContent = preg_replace("/^{$k}=.*/m", "{$k}={$v}", $envContent);
                } else {
                    $envContent .= "\n{$k}={$v}";
                }
            }

            file_put_contents($envFile, $envContent);
        }

        echo json_encode([
            'ok' => true,
            'message' => 'تنظیمات بک‌آپ با موفقیت در سیستم ذخیره شد.'
        ], JSON_UNESCAPED_UNICODE);
        exit(0);
    }

    echo json_encode(['ok' => false, 'message' => 'عملیات نامعتبر است.'], JSON_UNESCAPED_UNICODE);
} catch (\Throwable $e) {
    echo json_encode(['ok' => false, 'message' => 'خطای سرور: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
