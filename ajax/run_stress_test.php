<?php
declare(strict_types=1);

// Prevent timeouts during stress tests
set_time_limit(180);
ini_set('memory_limit', '512M');

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');

require_once __DIR__ . '/../includes/init.php';

// Method must be POST or GET with action=history
$action = $_GET['action'] ?? $_POST['action'] ?? 'run';
$historyFile = BASE_PATH . '/storage/logs/stress_history.json';

if ($action === 'history') {
    if (is_file($historyFile)) {
        $data = json_decode((string)file_get_contents($historyFile), true) ?: [];
        echo json_encode(['ok' => true, 'history' => array_reverse($data)]);
    } else {
        echo json_encode(['ok' => true, 'history' => []]);
    }
    exit;
}

if ($action === 'clear_history') {
    @unlink($historyFile);
    echo json_encode(['ok' => true, 'message' => 'History cleared']);
    exit;
}

// Read parameters
$endpoint = trim((string)($_POST['endpoint'] ?? '/health'));
$concurrency = max(5, min(500, (int)($_POST['concurrency'] ?? 50)));
$totalRequests = max(10, min(3000, (int)($_POST['total_requests'] ?? 200)));

// Determine target base URL
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$targetUrl = $protocol . '://' . $host . '/amutbar-admin/api/v1' . '/' . ltrim($endpoint, '/');

// On server with amut.speed-ping.shop, use local port or current host
if (str_contains($host, 'speed-ping') || PHP_OS_FAMILY === 'Linux') {
    // If running directly on the Linux VPS
    $targetUrl = 'http://127.0.0.1/api/v1/' . ltrim($endpoint, '/');
}

$mh = curl_multi_init();
$active = null;
$requestsLeft = $totalRequests;
$activeHandles = [];
$latencies = [];
$statusCodes = [];

$startTime = microtime(true);

function create_test_curl_handle(string $url, string $host): \CurlHandle
{
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTPHEADER => [
            'Host: ' . $host,
            'User-Agent: Amutbar-WebStressDashboard/1.0',
            'Accept: application/json',
        ],
    ]);
    return $ch;
}

// Start initial batch of requests
$initialBatch = min($concurrency, $requestsLeft);
for ($i = 0; $i < $initialBatch; $i++) {
    $ch = create_test_curl_handle($targetUrl, $host);
    curl_multi_add_handle($mh, $ch);
    $activeHandles[(int)$ch] = ['ch' => $ch, 'start' => microtime(true)];
    $requestsLeft--;
}

do {
    $status = curl_multi_exec($mh, $active);
    if ($active) {
        curl_multi_select($mh, 0.05);
    }

    // Process finished handles
    while ($info = curl_multi_info_read($mh)) {
        $ch = $info['handle'];
        $id = (int)$ch;
        $reqStart = $activeHandles[$id]['start'] ?? microtime(true);
        $durationMs = round((microtime(true) - $reqStart) * 1000, 2);
        $latencies[] = $durationMs;

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $statusCodes[$httpCode] = ($statusCodes[$httpCode] ?? 0) + 1;

        curl_multi_remove_handle($mh, $ch);
        curl_close($ch);
        unset($activeHandles[$id]);

        // Launch next request if quota remains
        if ($requestsLeft > 0) {
            $newCh = create_test_curl_handle($targetUrl, $host);
            curl_multi_add_handle($mh, $newCh);
            $activeHandles[(int)$newCh] = ['ch' => $newCh, 'start' => microtime(true)];
            $requestsLeft--;
        }
    }
} while ($active > 0 || !empty($activeHandles));

curl_multi_close($mh);

$totalTimeSec = round(microtime(true) - $startTime, 3);
$totalExecuted = count($latencies);

// Calculate metrics
sort($latencies);
$minLatency = $latencies[0] ?? 0;
$maxLatency = end($latencies) ?: 0;
$avgLatency = $totalExecuted > 0 ? round(array_sum($latencies) / $totalExecuted, 2) : 0;
$medianIdx = (int)floor($totalExecuted * 0.5);
$p95Idx = (int)floor($totalExecuted * 0.95);
$medianLatency = $latencies[$medianIdx] ?? 0;
$p95Latency = $latencies[$p95Idx] ?? 0;

$rps = $totalTimeSec > 0 ? round($totalExecuted / $totalTimeSec, 1) : 0;

$successCount = $statusCodes[200] ?? 0;
$successRate = $totalExecuted > 0 ? round(($successCount / $totalExecuted) * 100, 1) : 0;

// Evaluation Grade
$grade = 'EXCELLENT';
$recommendation = 'Server handled this load effortlessly! Excellent performance.';
if ($p95Latency > 500 || $successRate < 99) {
    $grade = 'GOOD';
    $recommendation = 'Good performance. Latency is acceptable under this concurrency.';
}
if ($p95Latency > 1000 || $successRate < 95) {
    $grade = 'WARNING';
    $recommendation = 'High load reached. Consider scaling up PHP-FPM workers or enabling caching.';
}
if ($p95Latency > 2000 || $successRate < 90) {
    $grade = 'CRITICAL';
    $recommendation = 'Bottleneck reached. Server is saturating under this concurrent volume.';
}

$report = [
    'id' => bin2hex(random_bytes(6)),
    'timestamp' => date('Y-m-d H:i:s'),
    'endpoint' => $endpoint,
    'concurrency_vus' => $concurrency,
    'total_requests' => $totalExecuted,
    'duration_seconds' => $totalTimeSec,
    'rps' => $rps,
    'success_rate' => $successRate,
    'latency_min_ms' => $minLatency,
    'latency_avg_ms' => $avgLatency,
    'latency_p95_ms' => $p95Latency,
    'latency_max_ms' => $maxLatency,
    'status_codes' => $statusCodes,
    'grade' => $grade,
    'recommendation' => $recommendation,
];

// Persist in history log
if (!is_dir(dirname($historyFile))) {
    @mkdir(dirname($historyFile), 0775, true);
}
$existingHistory = [];
if (is_file($historyFile)) {
    $existingHistory = json_decode((string)file_get_contents($historyFile), true) ?: [];
}
$existingHistory[] = $report;
// Keep last 50 tests
if (count($existingHistory) > 50) {
    $existingHistory = array_slice($existingHistory, -50);
}
@file_put_contents($historyFile, json_encode($existingHistory, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

echo json_encode([
    'ok' => true,
    'report' => $report,
], JSON_UNESCAPED_SLASHES);
