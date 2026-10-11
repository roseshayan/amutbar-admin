<?php

declare(strict_types=1);

/**
 * CLI Backup Runner for Cron Jobs and Automated Scheduling.
 * Outputs status in pure English for terminal and logging consistency.
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    echo "This script must be executed via CLI.\n";
    exit(1);
}

require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/backup_service.php';

$shortopts = "h";
$longopts = [
    "force-cloud",
    "local-only",
    "help"
];

$options = getopt($shortopts, $longopts);

if (isset($options['h']) || isset($options['help'])) {
    echo "Amutbar Backup Runner\n";
    echo "Usage: php scripts/backup_runner.php [options]\n";
    echo "Options:\n";
    echo "  --force-cloud    Force upload to Files.ir cloud storage\n";
    echo "  --local-only     Skip cloud upload even if configured\n";
    echo "  --help           Show this help message\n";
    exit(0);
}

echo "=== Starting Amutbar Automated Backup ===\n";
echo "Timestamp: " . date('Y-m-d H:i:s') . "\n";

$backupOptions = [];
if (isset($options['force-cloud'])) {
    $backupOptions['force_cloud'] = true;
}
if (isset($options['local-only'])) {
    $backupOptions['force_cloud'] = false;
    putenv('BACKUP_CLOUD_ENABLED=0');
    $_ENV['BACKUP_CLOUD_ENABLED'] = '0';
}

$startTime = microtime(true);
$result = backup_create_full($backupOptions);
$duration = round(microtime(true) - $startTime, 2);

if (!empty($result['ok'])) {
    echo "Status:          SUCCESS\n";
    echo "Archive Name:    " . $result['filename'] . "\n";
    echo "Archive Size:    " . $result['size_formatted'] . "\n";
    echo "Uploads Count:   " . $result['uploads_count'] . " files\n";
    echo "Duration:        " . $duration . "s\n";

    if (!empty($result['cloud']['synced'])) {
        echo "Cloud Sync:      SUCCESS (Files.ir - Retention applied)\n";
    } else {
        echo "Cloud Sync:      SKIPPED/INACTIVE (" . ($result['cloud']['message'] ?? 'Not enabled') . ")\n";
    }
    echo "=========================================\n";
    exit(0);
} else {
    echo "Status:          FAILED\n";
    echo "Error:           " . ($result['message'] ?? 'Unknown error') . "\n";
    echo "=========================================\n";
    exit(1);
}
