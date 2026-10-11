<?php

declare(strict_types=1);

/**
 * CLI Restore Runner for Disaster Recovery.
 * Outputs status in pure English for terminal and logging consistency.
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    echo "This script must be executed via CLI.\n";
    exit(1);
}

require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/backup_service.php';

$longopts = [
    "file:",
    "list",
    "help"
];

$options = getopt("", $longopts);

if (isset($options['help'])) {
    echo "Amutbar Disaster Recovery Restore Runner\n";
    echo "Usage:\n";
    echo "  php scripts/restore_runner.php --list\n";
    echo "  php scripts/restore_runner.php --file=amutbar_backup_YYYY-MM-DD_HHmmss.zip\n";
    exit(0);
}

if (isset($options['list'])) {
    echo "=== Available Local Backups ===\n";
    $backups = backup_list_local();
    if (empty($backups)) {
        echo "No backup archives found in storage/backups/\n";
    } else {
        foreach ($backups as $b) {
            echo " - " . $b['filename'] . " (" . $b['size_formatted'] . ", " . $b['created_at'] . ")\n";
        }
    }
    exit(0);
}

$file = $options['file'] ?? null;
if (!$file) {
    echo "Error: Please specify backup filename with --file=<filename> or use --list\n";
    exit(1);
}

echo "=== Starting Amutbar Disaster Recovery Restore ===\n";
echo "Target Archive: " . $file . "\n";
echo "Restoring database and storage/uploads...\n";

$startTime = microtime(true);
$result = backup_restore((string)$file);
$duration = round(microtime(true) - $startTime, 2);

if (!empty($result['ok'])) {
    echo "Status:          SUCCESS\n";
    echo "Message:         " . $result['message'] . "\n";
    echo "Restored Files:  " . $result['restored_files_count'] . " files\n";
    echo "Duration:        " . $duration . "s\n";
    echo "=========================================\n";
    exit(0);
} else {
    echo "Status:          FAILED\n";
    echo "Error:           " . ($result['message'] ?? 'Unknown error') . "\n";
    echo "=========================================\n";
    exit(1);
}
