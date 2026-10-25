<?php

declare(strict_types=1);

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/FilesIrClient.php';

/**
 * Backup Service for Amutbar Cargo & Driver Platform.
 * Manages database dumps, uploads archiving, local retention,
 * disaster recovery restore, database file import (.sql, .sql.gz, .zip),
 * and optional Cloud sync with Files.ir.
 */

function backup_get_dir(): string
{
    $dir = BASE_PATH . '/storage/backups';
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    // Prevent direct web access
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
 * یافتن خودکار مسیر فایل اجرایی mysqldump در سرورهای لینوکس و ویندوز (لاراگون / زمپ)
 */
function backup_find_mysqldump_cli(): ?string
{
    $cmd = (PHP_OS_FAMILY === 'Windows') ? 'where.exe mysqldump.exe' : 'which mysqldump';
    $out = [];
    $code = 1;
    @exec($cmd, $out, $code);
    if ($code === 0 && !empty($out[0]) && is_file(trim($out[0]))) {
        return trim($out[0]);
    }

    if (PHP_OS_FAMILY === 'Windows') {
        $laragonGlob = glob('C:/laragon/bin/mysql/*/bin/mysqldump.exe');
        if (!empty($laragonGlob) && is_file($laragonGlob[0])) {
            return str_replace('/', '\\', $laragonGlob[0]);
        }
        $mariadbGlob = glob('C:/laragon/bin/mariadb/*/bin/mysqldump.exe');
        if (!empty($mariadbGlob) && is_file($mariadbGlob[0])) {
            return str_replace('/', '\\', $mariadbGlob[0]);
        }
        $xampp = 'C:/xampp/mysql/bin/mysqldump.exe';
        if (is_file($xampp)) {
            return str_replace('/', '\\', $xampp);
        }
    } else {
        if (is_executable('/usr/bin/mariadb-dump')) {
            return '/usr/bin/mariadb-dump';
        }
        if (is_executable('/usr/bin/mysqldump')) {
            return '/usr/bin/mysqldump';
        }
    }

    return null;
}

/**
 * یافتن خودکار مسیر فایل اجرایی mysql / mariadb client برای ایمپورت فوق سریع
 */
function backup_find_mysql_cli(): ?string
{
    $cmd = (PHP_OS_FAMILY === 'Windows') ? 'where.exe mysql.exe' : 'which mysql';
    $out = [];
    $code = 1;
    @exec($cmd, $out, $code);
    if ($code === 0 && !empty($out[0]) && is_file(trim($out[0]))) {
        return trim($out[0]);
    }

    if (PHP_OS_FAMILY === 'Windows') {
        $laragonGlob = glob('C:/laragon/bin/mysql/*/bin/mysql.exe');
        if (!empty($laragonGlob) && is_file($laragonGlob[0])) {
            return str_replace('/', '\\', $laragonGlob[0]);
        }
        $mariadbGlob = glob('C:/laragon/bin/mariadb/*/bin/mysql.exe');
        if (!empty($mariadbGlob) && is_file($mariadbGlob[0])) {
            return str_replace('/', '\\', $mariadbGlob[0]);
        }
        $xampp = 'C:/xampp/mysql/bin/mysql.exe';
        if (is_file($xampp)) {
            return str_replace('/', '\\', $xampp);
        }
    } else {
        if (is_executable('/usr/bin/mariadb')) {
            return '/usr/bin/mariadb';
        }
        if (is_executable('/usr/bin/mysql')) {
            return '/usr/bin/mysql';
        }
    }

    return null;
}

/**
 * خروجی‌گیری کامل از پایگاه داده با mysqldump یا فال‌بک امن PDO
 */
function backup_dump_database(string $destSqlPath): array
{
    $dbHost = (string)env('DB_HOST', '127.0.0.1');
    $dbPort = (string)env('DB_PORT', '3306');
    $dbName = (string)env('DB_NAME', 'amutbar');
    $dbUser = (string)env('DB_USER', 'amutbar');
    $dbPass = (string)env('DB_PASS', '');

    $dumpBin = backup_find_mysqldump_cli();
    if ($dumpBin && function_exists('exec')) {
        $passArg = $dbPass !== '' ? (' -p' . escapeshellarg($dbPass)) : '';
        if (PHP_OS_FAMILY === 'Windows') {
            $cmd = sprintf(
                'cmd /c ""%s" -h%s -P%s -u%s%s --single-transaction --quick --routines --triggers %s > "%s"" 2>&1',
                $dumpBin,
                escapeshellarg($dbHost),
                escapeshellarg($dbPort),
                escapeshellarg($dbUser),
                $passArg,
                escapeshellarg($dbName),
                $destSqlPath
            );
        } else {
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
        }

        $output = [];
        $returnCode = 1;
        @exec($cmd, $output, $returnCode);

        if ($returnCode === 0 && is_file($destSqlPath) && filesize($destSqlPath) > 500) {
            return [
                'ok' => true,
                'method' => 'mysqldump_cli',
                'size' => filesize($destSqlPath)
            ];
        }
    }

    // Fallback: Pure PHP PDO Table Exporter
    try {
        $pdo = db();
        $handle = fopen($destSqlPath, 'w');
        if (!$handle) {
            throw new RuntimeException("امکان باز کردن فایل SQL جهت ذخیره‌سازی وجود ندارد.");
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
 * ساخت نسخه پشتیبان فشرده کامل (.zip) شامل دیتابیس و پوشه uploads
 */
function backup_create_full(array $options = []): array
{
    $backupDir = backup_get_dir();
    $timestamp = date('Y-m-d_His');
    $prefix = (string)($options['prefix'] ?? 'amutbar_backup_');
    $zipFilename = $prefix . $timestamp . '.zip';
    $zipPath = $backupDir . '/' . $zipFilename;
    $tempSqlPath = $backupDir . '/temp_db_' . $timestamp . '.sql';

    // 1. خروجی‌گیری از دیتابیس
    $dumpRes = backup_dump_database($tempSqlPath);
    if (empty($dumpRes['ok'])) {
        @unlink($tempSqlPath);
        return [
            'ok' => false,
            'message' => 'خطا در پشتیبان‌گیری از دیتابیس: ' . ($dumpRes['error'] ?? 'نامشخص')
        ];
    }

    // 2. ساخت آرشیو Zip
    $zip = new ZipArchive();
    if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        @unlink($tempSqlPath);
        return [
            'ok' => false,
            'message' => 'امکان ساخت فایل فشرده Zip وجود ندارد.'
        ];
    }

    // افزودن database.sql
    $zip->addFile($tempSqlPath, 'database.sql');

    // افزودن پوشه uploads
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

    // شناسنامه متادیتا
    $manifest = [
        'app' => 'Amutbar Platform',
        'version' => '1.0.0',
        'created_at' => date('Y-m-d H:i:s'),
        'timestamp' => $timestamp,
        'db_dump_method' => $dumpRes['method'],
        'db_size_bytes' => filesize($tempSqlPath),
        'uploads_files_count' => $uploadCount,
    ];
    $zip->addFromString('backup_manifest.json', json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $zip->close();

    @unlink($tempSqlPath);

    $finalSize = is_file($zipPath) ? filesize($zipPath) : 0;

    // چرخش نسخه‌های محلی
    $localKeepLast = (int)env('BACKUP_LOCAL_KEEP_LAST', 5);
    backup_prune_local($localKeepLast);

    // همگام‌سازی ابری اختیاری
    $cloudResult = null;
    $shouldCloudSync = !empty($options['force_cloud']) || ((string)env('BACKUP_CLOUD_ENABLED', '0') === '1');
    $cloudApiKey = (string)($options['files_ir_api_key'] ?? env('FILES_IR_API_KEY', ''));

    if ($shouldCloudSync && !empty($cloudApiKey)) {
        try {
            $client = new FilesIrClient($cloudApiKey);
            $cloudResult = $client->syncBackupFile($zipPath, (int)env('FILES_IR_KEEP_LAST', 2));
        } catch (\Throwable $e) {
            $cloudResult = [
                'synced' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    audit_log('backup.create', 'backup', 0, ['filename' => $zipFilename, 'size' => $finalSize]);

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
 * موتور قدرتمند اجرای فایل SQL روی دیتابیس (با پشتیبانی از CLI بومی و پارسر جریانی PDO)
 */
function backup_execute_sql_file(string $sqlFilePath): array
{
    if (!is_file($sqlFilePath) || !is_readable($sqlFilePath)) {
        return ['ok' => false, 'message' => 'فایل SQL برای اجرا یافت نشد یا قابل خواندن نیست.'];
    }

    $startTime = microtime(true);
    $dbHost = (string)env('DB_HOST', '127.0.0.1');
    $dbPort = (string)env('DB_PORT', '3306');
    $dbName = (string)env('DB_NAME', 'amutbar');
    $dbUser = (string)env('DB_USER', 'amutbar');
    $dbPass = (string)env('DB_PASS', '');

    // ۱. اولویت اول: اجرای مستقیم از طریق کلاینت بومی MySQL (بسیار سریع و استاندارد)
    $mysqlBin = backup_find_mysql_cli();
    if ($mysqlBin && function_exists('exec')) {
        $passArg = $dbPass !== '' ? (' -p' . escapeshellarg($dbPass)) : '';
        if (PHP_OS_FAMILY === 'Windows') {
            $cmd = sprintf(
                'cmd /c ""%s" -h%s -P%s -u%s%s --default-character-set=utf8mb4 %s < "%s"" 2>&1',
                $mysqlBin,
                escapeshellarg($dbHost),
                escapeshellarg($dbPort),
                escapeshellarg($dbUser),
                $passArg,
                escapeshellarg($dbName),
                $sqlFilePath
            );
        } else {
            $cmd = sprintf(
                '%s -h%s -P%s -u%s%s --default-character-set=utf8mb4 %s < %s 2>&1',
                escapeshellcmd($mysqlBin),
                escapeshellarg($dbHost),
                escapeshellarg($dbPort),
                escapeshellarg($dbUser),
                $passArg,
                escapeshellarg($dbName),
                escapeshellarg($sqlFilePath)
            );
        }

        $cliOut = [];
        $cliCode = 1;
        @exec($cmd, $cliOut, $cliCode);

        if ($cliCode === 0) {
            $elapsed = round(microtime(true) - $startTime, 2);
            return [
                'ok' => true,
                'method' => 'mysql_cli',
                'elapsed_sec' => $elapsed,
                'message' => "اجرا و بازیابی موفق از طریق CLI بومی در مدت {$elapsed} ثانیه",
            ];
        }
    }

    // ۲. روش پشتیبان مطمئن (Fallback): پارسر هوشمند جریانی PDO
    try {
        $pdo = db();
        $handle = fopen($sqlFilePath, 'r');
        if (!$handle) {
            throw new RuntimeException("امکان باز کردن فایل SQL جهت خواندن وجود ندارد.");
        }

        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0; SET SQL_MODE = ''; SET UNIQUE_CHECKS = 0;");

        $query = '';
        $delimiter = ';';
        $statementsCount = 0;
        $inMultilineComment = false;

        while (($line = fgets($handle)) !== false) {
            $trimmed = trim($line);

            // بررسی تغییر DELIMITER
            if (preg_match('/^DELIMITER\s+(.+)$/i', $trimmed, $m)) {
                $delimiter = trim($m[1]);
                continue;
            }

            // کامنت‌های چندخطی
            if ($inMultilineComment) {
                if (str_contains($line, '*/')) {
                    $inMultilineComment = false;
                    $line = substr($line, strpos($line, '*/') + 2);
                    $trimmed = trim($line);
                } else {
                    continue;
                }
            }

            if (str_starts_with($trimmed, '/*') && !str_contains($trimmed, '*/')) {
                $inMultilineComment = true;
                continue;
            }

            // کامنت‌های تک‌خطی
            if (str_starts_with($trimmed, '--') || str_starts_with($trimmed, '#')) {
                continue;
            }

            // ماکروهای مای‌اس‌کیو ال /*!40101 ... */
            if (preg_match('/^\/\*!\d+.*\*\/;?$/', $trimmed)) {
                try {
                    $pdo->exec($trimmed);
                    $statementsCount++;
                } catch (\Throwable $e) {}
                continue;
            }

            $query .= $line;

            if (str_ends_with(rtrim($query), $delimiter)) {
                $sqlToExec = trim(substr(rtrim($query), 0, -strlen($delimiter)));
                if ($sqlToExec !== '') {
                    $pdo->exec($sqlToExec);
                    $statementsCount++;
                }
                $query = '';
            }
        }

        fclose($handle);

        if (trim($query) !== '') {
            $pdo->exec(trim($query));
            $statementsCount++;
        }

        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

        $elapsed = round(microtime(true) - $startTime, 2);
        return [
            'ok' => true,
            'method' => 'pdo_stream',
            'statements_count' => $statementsCount,
            'elapsed_sec' => $elapsed,
            'message' => "اجرا و بازیابی موفق {$statementsCount} کوئری دیتابیس در مدت {$elapsed} ثانیه",
        ];
    } catch (\Throwable $e) {
        if (isset($pdo)) {
            try { $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;"); } catch (\Throwable $ignore) {}
        }
        return [
            'ok' => false,
            'message' => 'خطا در اجرای کوئری‌های فایل دیتابیس: ' . $e->getMessage(),
        ];
    }
}

/**
 * استخراج فایل‌های فشرده شده Gzip
 */
function backup_decompress_gzip(string $gzPath, string $destSqlPath): bool
{
    if (!function_exists('gzopen')) {
        return false;
    }
    $gz = gzopen($gzPath, 'rb');
    if (!$gz) return false;

    $dest = fopen($destSqlPath, 'wb');
    if (!$dest) {
        gzclose($gz);
        return false;
    }

    while (!gzeof($gz)) {
        fwrite($dest, gzread($gz, 65536));
    }

    gzclose($gz);
    fclose($dest);
    return is_file($destSqlPath) && filesize($destSqlPath) > 0;
}

/**
 * تضمین پیوستگی و پایداری نشست مدیر کل پس از جایگزینی دیتابیس
 */
function backup_ensure_admin_session(): void
{
    $pdo = db();
    $currId = (int)($_SESSION['admin_user_id'] ?? 0);
    if ($currId > 0) {
        $st = $pdo->prepare("SELECT id, user_type, status, admin_role FROM users WHERE id = ? LIMIT 1");
        $st->execute([$currId]);
        $row = $st->fetch(PDO::FETCH_ASSOC);

        if (!$row || (int)$row['user_type'] !== 3 || (int)$row['status'] !== 1) {
            $stSuper = $pdo->query("SELECT id FROM users WHERE user_type = 3 AND admin_role = 'super_admin' AND status = 1 ORDER BY id ASC LIMIT 1");
            $newId = (int)($stSuper->fetchColumn() ?: 0);
            if ($newId > 0) {
                $_SESSION['admin_user_id'] = $newId;
            }
        }
    }
}

/**
 * ایمپورت و بازیابی فایل بارگذاری شده (.sql, .sql.gz, .zip)
 */
function backup_import_uploaded_file(array $uploadedFile, bool $autoPreBackup = true): array
{
    require_super_admin();

    if (!isset($uploadedFile['error']) || $uploadedFile['error'] !== UPLOAD_ERR_OK) {
        $errorCode = $uploadedFile['error'] ?? UPLOAD_ERR_NO_FILE;
        $errMsg = match ($errorCode) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'حجم فایل ارسالی بیش از حداکثر مجاز آپلود سرور است.',
            UPLOAD_ERR_PARTIAL => 'فایل به صورت ناقص دریافت شد. لطفاً مجدداً تلاش کنید.',
            UPLOAD_ERR_NO_FILE => 'هیچ فایلی انتخاب نشده است.',
            default => 'خطا در بارگذاری فایل در سرور (کد خطا: ' . $errorCode . ')',
        };
        return ['ok' => false, 'message' => $errMsg];
    }

    $tmpPath = (string)($uploadedFile['tmp_name'] ?? '');
    $origName = (string)($uploadedFile['name'] ?? '');
    if (!is_uploaded_file($tmpPath)) {
        return ['ok' => false, 'message' => 'فایل آپلود شده معتبر نیست.'];
    }

    $lowerName = strtolower($origName);
    $isSql = str_ends_with($lowerName, '.sql');
    $isGz = str_ends_with($lowerName, '.sql.gz') || str_ends_with($lowerName, '.gz');
    $isZip = str_ends_with($lowerName, '.zip');

    if (!$isSql && !$isGz && !$isZip) {
        return [
            'ok' => false,
            'message' => 'فرمت فایل معتبر نیست. تنها فایل‌های .sql یا .sql.gz یا آرشیوهای .zip پشتیبانی می‌شوند.'
        ];
    }

    $backupDir = backup_get_dir();
    $timestamp = date('Y-m-d_His');

    // ۱. تهیه خودکار نسخه پشتیبان اضطراری جهت ایمنی ۱۰۰٪ قبل از بازیابی
    $preBackupFilename = null;
    if ($autoPreBackup) {
        $preRes = backup_create_full(['prefix' => 'amutbar_backup_prerestore_']);
        if (!empty($preRes['ok'])) {
            $preBackupFilename = $preRes['filename'] ?? null;
        }
    }

    $tempDir = $backupDir . '/temp_import_' . $timestamp . '_' . bin2hex(random_bytes(4));
    @mkdir($tempDir, 0755, true);

    $restoredUploadsCount = 0;
    $execRes = null;

    try {
        if ($isSql) {
            $destSql = $tempDir . '/import.sql';
            if (!move_uploaded_file($tmpPath, $destSql)) {
                throw new RuntimeException("خطا در انتقال فایل SQL آپلود شده به مسیر موقت.");
            }
            $execRes = backup_execute_sql_file($destSql);
        } elseif ($isGz) {
            $destGz = $tempDir . '/import.gz';
            $destSql = $tempDir . '/import.sql';
            if (!move_uploaded_file($tmpPath, $destGz)) {
                throw new RuntimeException("خطا در انتقال فایل فشرده Gzip.");
            }
            if (!backup_decompress_gzip($destGz, $destSql)) {
                throw new RuntimeException("خطا در استخراج محتوای فایل Gzip.");
            }
            $execRes = backup_execute_sql_file($destSql);
        } elseif ($isZip) {
            $destZip = $tempDir . '/import.zip';
            if (!move_uploaded_file($tmpPath, $destZip)) {
                throw new RuntimeException("خطا در انتقال آرشیو Zip.");
            }
            $zip = new ZipArchive();
            if ($zip->open($destZip) !== true) {
                throw new RuntimeException("امکان بازگشایی آرشیو Zip وجود ندارد.");
            }
            $zip->extractTo($tempDir);
            $zip->close();

            // یافتن فایل SQL درون فایل زیپ
            $sqlFile = null;
            if (is_file($tempDir . '/database.sql')) {
                $sqlFile = $tempDir . '/database.sql';
            } else {
                $sqls = glob($tempDir . '/*.sql');
                if (!empty($sqls)) {
                    $sqlFile = $sqls[0];
                }
            }

            if (!$sqlFile) {
                throw new RuntimeException("فایل پایگاه داده (.sql) درون فایل فشرده Zip یافت نشد.");
            }

            $execRes = backup_execute_sql_file($sqlFile);

            // بازیابی پوشه uploads در صورت وجود
            $extractedUploads = $tempDir . '/uploads';
            if (is_dir($extractedUploads)) {
                $targetUploads = BASE_PATH . '/storage/uploads';
                if (!is_dir($targetUploads)) {
                    @mkdir($targetUploads, 0777, true);
                }
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
                        $restoredUploadsCount++;
                    }
                }
            }
        }

        if (empty($execRes['ok'])) {
            throw new RuntimeException($execRes['message'] ?? 'خطای نامشخص در اجرای دستورات دیتابیس.');
        }

        backup_ensure_admin_session();

        audit_log('backup.import_restore', 'backup', 0, [
            'original_file' => $origName,
            'method' => $execRes['method'] ?? 'unknown',
            'pre_backup' => $preBackupFilename,
        ]);

        self_cleanup_dir($tempDir);

        $msg = "پایگاه داده با موفقیت کامل از فایل «{$origName}» ایمپورت و بازیابی شد.";
        if ($restoredUploadsCount > 0) {
            $msg .= " همچنین تعداد {$restoredUploadsCount} فایل چندرسانه‌ای نیز بازگردانی شدند.";
        }

        return [
            'ok' => true,
            'message' => $msg,
            'method' => $execRes['method'] ?? '',
            'pre_backup_file' => $preBackupFilename,
            'restored_files_count' => $restoredUploadsCount,
        ];

    } catch (\Throwable $e) {
        self_cleanup_dir($tempDir);
        return [
            'ok' => false,
            'message' => 'خطا در فرآیند ایمپورت و بازیابی: ' . $e->getMessage(),
            'pre_backup_file' => $preBackupFilename,
        ];
    }
}

/**
 * بازیابی نسخه پشتیبان موجود در سرور
 */
function backup_restore(string $filename, bool $autoPreBackup = true): array
{
    require_super_admin();

    $backupDir = backup_get_dir();
    $safeName = basename($filename);
    $zipPath = $backupDir . '/' . $safeName;

    if (!is_file($zipPath)) {
        return [
            'ok' => false,
            'message' => 'فایل بک‌آپ مورد نظر یافت نشد: ' . $safeName
        ];
    }

    // تهیه نسخه اضطراری قبل از بازگردانی
    $preBackupFilename = null;
    if ($autoPreBackup) {
        $preRes = backup_create_full(['prefix' => 'amutbar_backup_prerestore_']);
        if (!empty($preRes['ok'])) {
            $preBackupFilename = $preRes['filename'] ?? null;
        }
    }

    $zip = new ZipArchive();
    if ($zip->open($zipPath) !== true) {
        return [
            'ok' => false,
            'message' => 'امکان بازگشایی فایل فشرده وجود ندارد.'
        ];
    }

    $extractDir = $backupDir . '/temp_restore_' . time() . '_' . bin2hex(random_bytes(3));
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

    // ۱. اجرای دیتابیس با موتور پیشرفته
    $execRes = backup_execute_sql_file($sqlFile);
    if (empty($execRes['ok'])) {
        self_cleanup_dir($extractDir);
        return [
            'ok' => false,
            'message' => 'خطا در بازیابی پایگاه داده: ' . ($execRes['message'] ?? 'نامشخص'),
            'pre_backup_file' => $preBackupFilename,
        ];
    }

    // ۲. بازیابی پوشه آپلودها
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

    // تنظیم مجوزها روی لینوکس
    if (PHP_OS_FAMILY === 'Linux') {
        @exec('chmod -R 777 ' . escapeshellarg($targetUploads));
        @exec('chown -R www-data:www-data ' . escapeshellarg($targetUploads));
    }

    backup_ensure_admin_session();
    self_cleanup_dir($extractDir);

    audit_log('backup.restore', 'backup', 0, ['filename' => $safeName, 'pre_backup' => $preBackupFilename]);

    return [
        'ok' => true,
        'message' => 'پایگاه داده و مستندات با موفقیت کامل بازیابی شدند.',
        'restored_files_count' => $restoredFilesCount,
        'filename' => $safeName,
        'pre_backup_file' => $preBackupFilename,
    ];
}

/**
 * دریافت لیست تمام نسخه‌های محلی موجود روی سرور
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
        $isPreRestore = str_contains($filename, 'prerestore');

        $list[] = [
            'filename' => $filename,
            'is_pre_restore' => $isPreRestore,
            'size_bytes' => $size,
            'size_formatted' => backup_format_bytes($size),
            'created_at' => date('Y-m-d H:i:s', $modTime),
            'timestamp' => $modTime
        ];
    }

    usort($list, function ($a, $b) {
        return $b['timestamp'] <=> $a['timestamp'];
    });

    return $list;
}

/**
 * حذف نسخه‌های پشتیبان قدیمی و نگهداری N نسخه اخیر
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

function self_cleanup_dir(string $dir): void
{
    if (!is_dir($dir)) return;
    $items = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($items as $item) {
        if ($item->isDir()) {
            @rmdir($item->getRealPath());
        } else {
            @unlink($item->getRealPath());
        }
    }
    @rmdir($dir);
}
