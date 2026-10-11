<?php
declare(strict_types=1);

// Prevent timeouts and buffering
set_time_limit(300);
ini_set('memory_limit', '512M');

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');

require_once __DIR__ . '/../includes/init.php';

$action = (string)($_GET['action'] ?? $_POST['action'] ?? 'status');
$storageLogs = BASE_PATH . '/storage/logs';
$activeTestFile = $storageLogs . '/active_stress_test.json';
$historyFile = $storageLogs . '/stress_history.json';
$stopFlagFile = $storageLogs . '/stop_stress_test.flag';

if (!is_dir($storageLogs)) {
    @mkdir($storageLogs, 0775, true);
}

// -------------------------------------------------------------
// ACTION: status (Poll live test state)
// -------------------------------------------------------------
if ($action === 'status') {
    if (is_file($activeTestFile)) {
        $data = json_decode((string)file_get_contents($activeTestFile), true) ?: [];
        $status = (string)($data['status'] ?? 'idle');
        $isRunning = ($status === 'running' || $status === 'stopping');
        
        // Check if process might be dead but status was still running
        if ($isRunning && !empty($data['pid'])) {
            $pid = (int)$data['pid'];
            if (PHP_OS_FAMILY === 'Linux') {
                $check = @shell_exec("kill -0 $pid 2>&1");
                // If kill -0 has error and process doesn't exist
                if (!empty($check) && str_contains(strtolower($check), 'no such process')) {
                    $isRunning = false;
                    $data['status'] = 'completed';
                    @file_put_contents($activeTestFile, json_encode($data, JSON_UNESCAPED_SLASHES));
                }
            }
        }

        echo json_encode([
            'ok' => true,
            'is_running' => $isRunning,
            'data' => $data,
        ], JSON_UNESCAPED_SLASHES);
    } else {
        echo json_encode([
            'ok' => true,
            'is_running' => false,
            'data' => null,
        ]);
    }
    exit;
}

// -------------------------------------------------------------
// ACTION: start (Spawn asynchronous background test)
// -------------------------------------------------------------
if ($action === 'start') {
    // Check if test is already running
    if (is_file($activeTestFile)) {
        $curr = json_decode((string)file_get_contents($activeTestFile), true) ?: [];
        if (($curr['status'] ?? '') === 'running') {
            echo json_encode([
                'ok' => false,
                'message' => 'یک تست در حال حاضر در حال اجرا است. ابتدا آن را متوقف کنید یا صبر کنید به پایان برسد.',
                'data' => $curr,
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    $vus = max(1, min(1000, (int)($_POST['vus'] ?? 50)));
    $mode = (string)($_POST['mode'] ?? 'duration');
    $duration = max(5, min(3600, (int)($_POST['duration'] ?? 60))); // Up to 60 minutes
    $requests = max(50, min(500000, (int)($_POST['requests'] ?? 5000)));
    $scenario = (string)($_POST['scenario'] ?? 'mixed');
    $customUrl = trim((string)($_POST['custom_url'] ?? ''));
    $host = (string)($_SERVER['HTTP_HOST'] ?? 'amut.speed-ping.shop');

    $runId = 'run_' . bin2hex(random_bytes(6));
    $runnerScript = escapeshellarg(BASE_PATH . '/scripts/stress_runner.php');

    $args = sprintf(
        '--vus=%d --mode=%s --duration=%d --requests=%d --scenario=%s --run-id=%s --host=%s',
        $vus,
        escapeshellarg($mode),
        $duration,
        $requests,
        escapeshellarg($scenario),
        escapeshellarg($runId),
        escapeshellarg($host)
    );

    if ($customUrl !== '') {
        $args .= ' --custom-url=' . escapeshellarg($customUrl);
    }

    // Spawn Background Process
    $phpBin = PHP_BINARY ?: 'php';
    if (PHP_OS_FAMILY === 'Linux') {
        $cmd = "nohup $phpBin $runnerScript $args > /dev/null 2>&1 &";
        @shell_exec($cmd);
    } else {
        // Windows
        pclose(popen("start /B \"AmutbarStressRunner\" $phpBin $runnerScript $args", "r"));
    }

    // Give the background process a moment to initialize metadata
    usleep(250000); // 250ms

    $state = [];
    if (is_file($activeTestFile)) {
        $state = json_decode((string)file_get_contents($activeTestFile), true) ?: [];
    }

    echo json_encode([
        'ok' => true,
        'message' => 'تست فشار در پس‌زمینه با موفقیت استارت شد.',
        'run_id' => $runId,
        'state' => $state,
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

// -------------------------------------------------------------
// ACTION: stop (Emergency abort running test)
// -------------------------------------------------------------
if ($action === 'stop') {
    @file_put_contents($stopFlagFile, 'stop');

    if (is_file($activeTestFile)) {
        $curr = json_decode((string)file_get_contents($activeTestFile), true) ?: [];
        $pid = (int)($curr['pid'] ?? 0);
        if ($pid > 0 && PHP_OS_FAMILY === 'Linux') {
            @shell_exec("kill -TERM $pid 2>/dev/null");
        }
        $curr['status'] = 'stopping';
        @file_put_contents($activeTestFile, json_encode($curr, JSON_UNESCAPED_SLASHES));
    }

    echo json_encode([
        'ok' => true,
        'message' => 'سیگنال توقف تست ارسال شد. طی چند ثانیه متوقف می‌گردد.',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// -------------------------------------------------------------
// ACTION: history (Retrieve past runs)
// -------------------------------------------------------------
if ($action === 'history') {
    if (is_file($historyFile)) {
        $data = json_decode((string)file_get_contents($historyFile), true) ?: [];
        echo json_encode([
            'ok' => true,
            'history' => array_reverse($data),
        ], JSON_UNESCAPED_SLASHES);
    } else {
        echo json_encode(['ok' => true, 'history' => []]);
    }
    exit;
}

// -------------------------------------------------------------
// ACTION: clear_history
// -------------------------------------------------------------
if ($action === 'clear_history') {
    @unlink($historyFile);
    echo json_encode(['ok' => true, 'message' => 'History cleared']);
    exit;
}

// -------------------------------------------------------------
// ACTION: export_csv (Download history as CSV)
// -------------------------------------------------------------
if ($action === 'export_csv') {
    $data = [];
    if (is_file($historyFile)) {
        $data = json_decode((string)file_get_contents($historyFile), true) ?: [];
    }

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="amutbar_stress_history_' . date('Ymd_His') . '.csv"');

    $fp = fopen('php://output', 'w');
    // UTF-8 BOM for Excel
    fputs($fp, "\xEF\xBB\xBF");

    fputcsv($fp, [
        'Run_ID', 'Timestamp', 'Scenario', 'Mode', 'VUs', 'Duration_Sec',
        'Total_Requests', 'RPS', 'Success_Rate_Percent',
        'Latency_Avg_ms', 'Latency_p50_ms', 'Latency_p90_ms', 'Latency_p95_ms', 'Latency_p99_ms',
        'Grade', 'Est_Peak_Users_10k_Target', 'Est_Daily_Users', 'Verdict'
    ]);

    foreach (array_reverse($data) as $row) {
        fputcsv($fp, [
            $row['id'] ?? '',
            $row['timestamp'] ?? '',
            $row['scenario_name'] ?? ($row['scenario'] ?? ''),
            $row['mode'] ?? '',
            $row['concurrency_vus'] ?? '',
            $row['duration_seconds'] ?? '',
            $row['total_requests'] ?? '',
            $row['rps'] ?? '',
            $row['success_rate'] ?? '',
            $row['latency_avg_ms'] ?? '',
            $row['latency_p50_ms'] ?? '',
            $row['latency_p90_ms'] ?? '',
            $row['latency_p95_ms'] ?? '',
            $row['latency_p99_ms'] ?? '',
            $row['grade'] ?? '',
            $row['capacity_sizing']['estimated_peak_concurrent_users'] ?? '',
            $row['capacity_sizing']['estimated_daily_active_users'] ?? '',
            $row['capacity_sizing']['verdict'] ?? '',
        ]);
    }
    fclose($fp);
    exit;
}

echo json_encode(['ok' => false, 'message' => 'Unknown action: ' . $action], 400);
