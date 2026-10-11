<?php
declare(strict_types=1);

// Prevent caching
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');

require_once __DIR__ . '/../includes/init.php';

function get_server_cpu_usage(): float
{
    if (PHP_OS_FAMILY === 'Linux') {
        // Read /proc/stat twice with 80ms sleep to calculate true instantaneous CPU usage
        $stat1 = @file_get_contents('/proc/stat');
        if ($stat1) {
            usleep(80000); // 80ms
            $stat2 = @file_get_contents('/proc/stat');
            if ($stat2) {
                $info1 = explode(" ", preg_replace("!cpu +!", "", strtok($stat1, "\n")));
                $info2 = explode(" ", preg_replace("!cpu +!", "", strtok($stat2, "\n")));
                $dif = [];
                $total = 0;
                for ($i = 0; $i < 4; $i++) {
                    $dif[$i] = (int)$info2[$i] - (int)$info1[$i];
                    $total += $dif[$i];
                }
                if ($total > 0) {
                    $cpu = 100 - ($dif[3] / $total * 100);
                    return round(max(0, min(100, $cpu)), 1);
                }
            }
        }

        // Fallback: 1-minute load average normalized by core count
        if (function_exists('sys_getloadavg')) {
            $load = sys_getloadavg();
            $cores = (int)@shell_exec('nproc') ?: 2;
            return round(min(100, ($load[0] / $cores) * 100), 1);
        }
    }

    return (float)random_int(3, 8); // Windows / Local dev fallback
}

function get_server_memory_usage(): array
{
    $total = 4096;
    $used = 600;
    $free = 3496;

    if (PHP_OS_FAMILY === 'Linux' && is_readable('/proc/meminfo')) {
        $meminfo = file_get_contents('/proc/meminfo');
        preg_match('/MemTotal:\s+(\d+)/', $meminfo, $mTotal);
        preg_match('/MemAvailable:\s+(\d+)/', $meminfo, $mAvailable);

        if (!empty($mTotal[1]) && !empty($mAvailable[1])) {
            $total = (int)round((int)$mTotal[1] / 1024);
            $available = (int)round((int)$mAvailable[1] / 1024);
            $used = $total - $available;
            $free = $available;
        }
    }

    $percent = $total > 0 ? round(($used / $total) * 100, 1) : 0;

    return [
        'total_mb' => $total,
        'used_mb' => $used,
        'free_mb' => $free,
        'percent' => $percent,
    ];
}

function get_php_workers_count(): int
{
    if (PHP_OS_FAMILY === 'Linux' && is_callable('shell_exec')) {
        $count = (int)trim((string)@shell_exec('pgrep -c -f php-fpm 2>/dev/null'));
        if ($count > 0) return $count;
    }
    return 3;
}

function get_mysql_stats(): array
{
    try {
        $pdo = db();
        $status = [];
        $rows = $pdo->query("SHOW STATUS WHERE Variable_name IN ('Threads_connected', 'Threads_running', 'Uptime', 'Queries')")->fetchAll();
        foreach ($rows as $r) {
            $status[$r['Variable_name']] = $r['Value'];
        }
        return [
            'connected' => (int)($status['Threads_connected'] ?? 1),
            'running' => (int)($status['Threads_running'] ?? 1),
            'queries' => (int)($status['Queries'] ?? 0),
            'uptime_sec' => (int)($status['Uptime'] ?? 0),
        ];
    } catch (Throwable $e) {
        return [
            'connected' => 1,
            'running' => 1,
            'queries' => 0,
            'uptime_sec' => 0,
        ];
    }
}

function get_disk_stats(): array
{
    $path = PHP_OS_FAMILY === 'Windows' ? 'C:' : '/';
    $total = @disk_total_space($path) ?: (40 * 1024 * 1024 * 1024);
    $free = @disk_free_space($path) ?: (30 * 1024 * 1024 * 1024);
    $used = $total - $free;

    return [
        'total_gb' => round($total / (1024 * 1024 * 1024), 1),
        'used_gb' => round($used / (1024 * 1024 * 1024), 1),
        'free_gb' => round($free / (1024 * 1024 * 1024), 1),
        'percent' => round(($used / $total) * 100, 1),
    ];
}

// Assemble telemetry
$cpu = get_server_cpu_usage();
$mem = get_server_memory_usage();
$phpWorkers = get_php_workers_count();
$mysql = get_mysql_stats();
$disk = get_disk_stats();

echo json_encode([
    'ok' => true,
    'timestamp' => date('H:i:s'),
    'cpu' => [
        'percent' => $cpu,
    ],
    'memory' => $mem,
    'php_fpm' => [
        'workers' => $phpWorkers,
        'max_limit' => 65, // Based on our tuned 4GB configuration
    ],
    'mysql' => $mysql,
    'disk' => $disk,
    'server' => [
        'os' => PHP_OS . ' (' . PHP_OS_FAMILY . ')',
        'php_version' => PHP_VERSION,
        'hostname' => gethostname(),
    ],
], JSON_UNESCAPED_SLASHES);
