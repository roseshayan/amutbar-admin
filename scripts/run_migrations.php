<?php
declare(strict_types=1);

/**
 * Automated database migration runner for CI/CD and deployment.
 * Sequentially and safely applies new SQL files in database/migrations.
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    echo "This script can only be run via CLI.\n";
    exit(1);
}

require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/db.php';

echo "=== Checking and applying database migrations ===\n";

try {
    $pdo = db();

    // 1. Create migration_history table if it does not exist
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `migration_history` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `migration_name` VARCHAR(255) NOT NULL UNIQUE,
            `applied_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX `idx_migration_name` (`migration_name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // 2. Fetch list of already applied migrations
    $st = $pdo->query("SELECT `migration_name` FROM `migration_history`");
    $applied = $st->fetchAll(PDO::FETCH_COLUMN);
    $appliedMap = array_flip($applied);

    // 3. Scan SQL files in database/migrations
    $migrationsDir = __DIR__ . '/../database/migrations';
    if (!is_dir($migrationsDir)) {
        echo "Migrations directory not found: $migrationsDir\n";
        exit(0);
    }

    $files = glob($migrationsDir . '/*.sql');
    sort($files);

    $executedCount = 0;

    foreach ($files as $filePath) {
        $filename = basename($filePath);

        // Skip if already applied
        if (isset($appliedMap[$filename])) {
            continue;
        }

        echo "Applying migration: $filename ... ";
        $sql = file_get_contents($filePath);
        if (trim($sql) === '') {
            echo "SKIPPED (empty file)\n";
            continue;
        }

        try {
            $pdo->exec($sql);

            $ins = $pdo->prepare("INSERT INTO `migration_history` (`migration_name`) VALUES (?)");
            $ins->execute([$filename]);

            if ($pdo->inTransaction()) {
                $pdo->commit();
            }
            echo "OK\n";
            $executedCount++;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $msg = $e->getMessage();
            $isAlreadyApplied = str_contains($msg, 'already exists') 
                || str_contains($msg, 'Duplicate column') 
                || str_contains($msg, "Can't DROP")
                || str_contains($msg, "check that column/key exists");

            if ($isAlreadyApplied) {
                $ins = $pdo->prepare("INSERT IGNORE INTO `migration_history` (`migration_name`) VALUES (?)");
                $ins->execute([$filename]);
                echo "SKIPPED (already present in schema)\n";
                $executedCount++;
                continue;
            }

            echo "FAILED\n";
            echo "Error message: " . $e->getMessage() . "\n";
            exit(1);
        }
    }

    if ($executedCount === 0) {
        echo "Database is up to date. No new migrations found.\n";
    } else {
        echo "Successfully applied $executedCount new migration(s).\n";
    }

} catch (Throwable $e) {
    echo "Connection or execution error: " . $e->getMessage() . "\n";
    exit(1);
}

exit(0);
