<?php

declare(strict_types=1);

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/FilesIrClient.php';

/**
 * Backup Service for Amutbar Cargo & Driver Platform.
 * Manages database dumps, uploads archiving, local retention,
 * disaster recovery restore, and optional Cloud sync with Files.ir.
 */

function backup_get_dir(): string
{
    $dir = BASE_PATH . '/storage/backups';
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    // Prevent web access
    $htaccess = $dir . '/.htaccess';
    if (!is_file($htaccess)) {
        @file_put_contents($htaccess, "Deny from all\n");
    }
    return $dir;
}

function backup_format_bytes(int $bytes): string
{
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    }
    if ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    }
    if ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    }
    return $bytes . ' B';
}

/**
 * Dumps MariaDB database to SQL file using mysqldump or PDO fallback.
 */
function backup_dump_database(string $destSqlPath): array
{
    $dbHost = (string)env('DB_HOST', '127.0.0.1');
    $dbPort = (string)env('DB_PORT', '3306');
    $dbName = (string)env('DB_NAME', 'amutbar');
    $dbUser = (string)env('DB_USER', 'amutbar');
    $dbPass = (string)env('DB_PASS', '');

    // Try native mysqldump / mariadb-dump first
    $dumpBin = 'mysqldump';
    if (PHP_OS_FAMILY === 'Linux') {
        if (is_executable('/usr/bin/mariadb-dump')) {
            $dumpBin = '/usr/bin/mariadb-dump';
        } elseif (is_executable('/usr/bin/mysqldump')) {
            $dumpBin = '/usr/bin/mysqldump';
        }
    }

    $passArg = $dbPass !== '' ? (' -p' . escapeshellarg($dbPass)) : '';
    $cmd = sprintf(
        '%s -h%s -P%s -u%s%s --single-transaction --quick --routines --triggers %s > %s 2>&1',
        escapeshellcmd($dumpBin),
        escapeshellarg($dbHost),
        escapeshellarg($dbPort),
        escapeshellarg($dbUser),
        $passArg,
        escapeshellarg($dbName),
        escapeshellarg($destSqlPath)
    );

    @exec($cmd, $output, $returnCode);

    if ($returnCode === 0 && is_file($destSqlPath) && filesize($destSqlPath) > 500) {
        return [
            'ok' => true,
            'method' => 'mysqldump',
            'size' => filesize($destSqlPath)
        ];
    }

    // Fallback: Pure PHP PDO Table Exporter
    try {
        $pdo = db();
        $handle = fopen($destSqlPath, 'w');
        if (!$handle) {
            throw new RuntimeException("Cannot open destination SQL file for writing.");
        }

        fwrite($handle, "-- Amutbar PDO Database Backup\n");
        fwrite($handle, "-- Generated: " . date('Y-m-d H:i:s') . "\n");
        fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\nSET NAMES utf8mb4;\n\n");

        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        foreach ($tables as $table) {
            $createTable = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch(PDO::FETCH_ASSOC);
            $createSql = $createTable['Create Table'] ?? '';
            fwrite($handle, "DROP TABLE IF EXISTS `{$table}`;\n" . $createSql . ";\n\n");

            // Dump data in chunks of 500
            $st = $pdo->query("SELECT * FROM `{$table}`");
            $rows = [];
            while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
                $escaped = array_map(function ($val) use ($pdo) {
                    return $val === null ? 'NULL' : $pdo->quote((string)$val);
                }, $row);
                $rows[] = '(' . implode(', ', $escaped) . ')';

                if (count($rows) >= 500) {
                    fwrite($handle, "INSERT INTO `{$table}` VALUES \n" . implode(",\n", $rows) . ";\n");
                    $rows = [];
                }
            }
            if (!empty($rows)) {
                fwrite($handle, "INSERT INTO `{$table}` VALUES \n" . implode(",\n", $rows) . ";\n");
            }
            fwrite($handle, "\n");
        }

        fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
        fclose($handle);

        return [
            'ok' => true,
            'method' => 'pdo_fallback',
            'size' => filesize($destSqlPath)
        ];
    } catch (\Throwable $e) {
        return [
            'ok' => false,
            'error' => $e->getMessage()
        ];
    }
}

/**
 * Creates a complete backup archive (.zip) containing DB and storage/uploads.
 */
function backup_create_full(array $options = []): array
{
    $backupDir = backup_get_dir();
    $timestamp = date('Y-m-d_His');
    $zipFilename = 'amutbar_backup_' . $timestamp . '.zip';
    $zipPath = $backupDir . '/' . $zipFilename;
    $tempSqlPath = $backupDir . '/temp_db_' . $timestamp . '.sql';

    // 1. Dump Database
    $dumpRes = backup_dump_database($tempSqlPath);
    if (empty($dumpRes['ok'])) {
        @unlink($tempSqlPath);
        return [
            'ok' => false,
            'message' => 'خطا در پشتیبان‌گیری از دیتابیس: ' . ($dumpRes['error'] ?? 'نامشخص')
        ];
    }

    // 2. Build Zip Archive
    $zip = new ZipArchive();
    if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        @unlink($tempSqlPath);
        return [
            'ok' => false,
            'message' => 'امکان ساخت فایل فشرده Zip وجود ندارد.'
        ];
    }

    // Add database.sql
    $zip->addFile($tempSqlPath, 'database.sql');

    // Add storage/uploads
    $uploadsDir = BASE_PATH . '/storage/uploads';
    $uploadCount = 0;
    if (is_dir($uploadsDir)) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($uploadsDir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = 'uploads/' . substr($filePath, strlen($uploadsDir) + 1);
                $relativePath = str_replace('\\', '/', $relativePath);
                $zip->addFile($filePath, $relativePath);
                $uploadCount++;
            }
        }
    }

    // Manifest metadata
    $manifest = [
        'app' => 'Amutbar Platform',
        'version' => '1.0.0',
        'created_at' => date('Y-m-d H:i:s'),
        'timestamp' => $timestamp,
        'db_dump_method' => $dumpRes['method'],
        'db_size_bytes' => filesize($tempSqlPath),
        'uploads_files_count' => $uploadCount,
        'php_version' => PHP_VERSION,
        'os' => PHP_OS
    ];
    $zip->addFromString('manifest.json', json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $zip->close();

    // Clean temp SQL file
    @unlink($tempSqlPath);

    $finalSize = is_file($zipPath) ? filesize($zipPath) : 0;

    // 3. Prune old local backups (keep last N)
    $localKeepLast = (int)env('BACKUP_LOCAL_KEEP_LAST', 5);
    backup_prune_local($localKeepLast);

    // 4. Optional Cloud Sync with Files.ir
    $cloudResult = [
        'synced' => false,
        'message' => 'ارسال ابری غیرفعال است.'
    ];

    $cloudEnabled = (string)env('BACKUP_CLOUD_ENABLED', '0') === '1' || !empty($options['force_cloud']);
    $apiKey = (string)env('FILES_IR_API_KEY', $options['files_ir_api_key'] ?? '');

    if ($cloudEnabled && !empty($apiKey)) {
        try {
            $client = new FilesIrClient($apiKey);
            $folderName = (string)env('FILES_IR_FOLDER_NAME', 'amutbar_backups');
            $folderId = $client->ensureFolder($folderName);

            $uploadRes = $client->uploadFile($zipPath, $folderId);
            if (!empty($uploadRes['ok'])) {
                // Keep only latest 2 backups on Cloud as per requirement
                $cloudKeepLast = (int)env('FILES_IR_KEEP_LAST', 2);
                $pruneRes = $client->pruneOldBackups('amutbar_backup', $cloudKeepLast);

                $cloudResult = [
                    'synced' => true,
                    'file_id' => $uploadRes['file_id'] ?? null,
                    'message' => 'فایل با موفقیت در Files.ir بارگذاری شد و قانون نگهداری (۲ نسخه اخیر) اعمال گردید.',
                    'pruned_count' => $pruneRes['pruned_count'] ?? 0
                ];
            } else {
                $cloudResult = [
                    'synced' => false,
                    'message' => 'خطا در ارسال به Files.ir: ' . ($uploadRes['message'] ?? 'نامشخص')
                ];
            }
        } catch (\Throwable $ce) {
            $cloudResult = [
                'synced' => false,
                'message' => 'خطای اتصال به Files.ir: ' . $ce->getMessage()
            ];
        }
    }

    return [
        'ok' => true,
        'filename' => $zipFilename,
        'filepath' => $zipPath,
        'size_bytes' => $finalSize,
        'size_formatted' => backup_format_bytes($finalSize),
        'created_at' => date('Y-m-d H:i:s'),
        'uploads_count' => $uploadCount,
        'manifest' => $manifest,
        'cloud' => $cloudResult
    ];
}

/**
 * Restores a full backup (.zip archive).
 */
function backup_restore(string $filename): array
{
    $backupDir = backup_get_dir();
    $safeName = basename($filename);
    $zipPath = $backupDir . '/' . $safeName;

    if (!is_file($zipPath)) {
        return [
            'ok' => false,
            'message' => 'فایل بک‌آپ مورد نظر یافت نشد: ' . $safeName
        ];
    }

    $zip = new ZipArchive();
    if ($zip->open($zipPath) !== true) {
        return [
            'ok' => false,
            'message' => 'امکان بازگشایی فایل فشرده وجود ندارد.'
        ];
    }

    $extractDir = $backupDir . '/temp_restore_' . time();
    @mkdir($extractDir, 0755, true);
    $zip->extractTo($extractDir);
    $zip->close();

    $sqlFile = $extractDir . '/database.sql';
    if (!is_file($sqlFile)) {
        self_cleanup_dir($extractDir);
        return [
            'ok' => false,
            'message' => 'فایل پایگاه داده (database.sql) در این بک‌آپ یافت نشد.'
        ];
    }

    // 1. Restore Database
    $dbHost = (string)env('DB_HOST', '127.0.0.1');
    $dbPort = (string)env('DB_PORT', '3306');
    $dbName = (string)env('DB_NAME', 'amutbar');
    $dbUser = (string)env('DB_USER', 'amutbar');
    $dbPass = (string)env('DB_PASS', '');

    $restoredViaCli = false;
    if (PHP_OS_FAMILY === 'Linux' && is_executable('/usr/bin/mariadb')) {
        $passArg = $dbPass !== '' ? (' -p' . escapeshellarg($dbPass)) : '';
        $cmd = sprintf(
            '/usr/bin/mariadb -h%s -P%s -u%s%s %s < %s 2>&1',
            escapeshellarg($dbHost),
            escapeshellarg($dbPort),
            escapeshellarg($dbUser),
            $passArg,
            escapeshellarg($dbName),
            escapeshellarg($sqlFile)
        );
        @exec($cmd, $cliOut, $cliCode);
        if ($cliCode === 0) {
            $restoredViaCli = true;
        }
    }

    if (!$restoredViaCli) {
        // Fallback PDO multi-query or command execution
        try {
            $pdo = db();
            $sqlContent = file_get_contents($sqlFile);
            $pdo->exec($sqlContent);
        } catch (\Throwable $pe) {
            self_cleanup_dir($extractDir);
            return [
                'ok' => false,
                'message' => 'خطا در بازیابی پایگاه داده: ' . $pe->getMessage()
            ];
        }
    }

    // 2. Restore Uploads
    $extractedUploads = $extractDir . '/uploads';
    $targetUploads = BASE_PATH . '/storage/uploads';
    if (!is_dir($targetUploads)) {
        @mkdir($targetUploads, 0777, true);
    }

    $restoredFilesCount = 0;
    if (is_dir($extractedUploads)) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($extractedUploads, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($iterator as $item) {
            $subPath = substr($item->getPathname(), strlen($extractedUploads) + 1);
            $targetPath = $targetUploads . DIRECTORY_SEPARATOR . $subPath;
            if ($item->isDir()) {
                if (!is_dir($targetPath)) {
                    @mkdir($targetPath, 0777, true);
                }
            } else {
                @copy($item->getPathname(), $targetPath);
                $restoredFilesCount++;
            }
        }
    }

    // Fix permissions if Linux
    if (PHP_OS_FAMILY === 'Linux') {
        @exec('chmod -R 777 ' . escapeshellarg($targetUploads));
        @exec('chown -R www-data:www-data ' . escapeshellarg($targetUploads));
    }

    // Clean temp extraction
    self_cleanup_dir($extractDir);

    return [
        'ok' => true,
        'message' => 'پایگاه داده و مستندات با موفقیت کامل بازیابی شدند.',
        'restored_files_count' => $restoredFilesCount,
        'filename' => $safeName
    ];
}

/**
 * Lists all existing local backups.
 */
function backup_list_local(): array
{
    $backupDir = backup_get_dir();
    $files = glob($backupDir . '/amutbar_backup_*.zip');
    if (!$files) {
        return [];
    }

    $list = [];
    foreach ($files as $file) {
        $filename = basename($file);
        $size = filesize($file);
        $modTime = filemtime($file);

        $list[] = [
            'filename' => $filename,
            'size_bytes' => $size,
            'size_formatted' => backup_format_bytes($size),
            'created_at' => date('Y-m-d H:i:s', $modTime),
            'timestamp' => $modTime
        ];
    }

    // Sort newest first
    usort($list, function ($a, $b) {
        return $b['timestamp'] <=> $a['timestamp'];
    });

    return $list;
}

/**
 * Prunes local backups, keeping only the newest $keepLast backups.
 */
function backup_prune_local(int $keepLast = 5): int
{
    $list = backup_list_local();
    if (count($list) <= $keepLast) {
        return 0;
    }

    $backupDir = backup_get_dir();
    $toDelete = array_slice($list, $keepLast);
    $deletedCount = 0;

    foreach ($toDelete as $item) {
        $filePath = $backupDir . '/' . $item['filename'];
        if (is_file($filePath)) {
            @unlink($filePath);
            $deletedCount++;
        }
    }

    return $deletedCount;
}

/**
 * Helper to recursively remove temporary extraction folders.
 */
function self_cleanup_dir(string $dir): void
{
    if (!is_dir($dir)) return;
    $files = array_diff(scandir($dir) ?: [], ['.', '..']);
    foreach ($files as $file) {
        $path = "$dir/$file";
        is_dir($path) ? self_cleanup_dir($path) : @unlink($path);
    }
    @rmdir($dir);
}
