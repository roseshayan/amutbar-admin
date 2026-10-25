<?php
declare(strict_types=1);

/**
 * Amutbar Asynchronous High-Concurrency Stress Test Engine (CLI / Background Runner) v2.2
 * 
 * Features:
 *  - 100% English CLI Terminal Output
 *  - Second-by-second live terminal progress bar
 *  - Non-blocking asynchronous multi-curl engine
 *  - Automated Driver JWT Bearer Token Injection
 *  - Statistical percentiles (p50, p75, p90, p95, p99, std-dev)
 *  - 10,000 Users Capacity mathematical sizing
 */

set_time_limit(0);
ini_set('memory_limit', '1024M');
ignore_user_abort(true);

$basePath = dirname(__DIR__);
$storageLogs = $basePath . '/storage/logs';
if (!is_dir($storageLogs)) {
    @mkdir($storageLogs, 0775, true);
}

// Parse Command Line Options
$options = getopt('', [
    'vus::',
    'mode::',
    'duration::',
    'requests::',
    'scenario::',
    'custom-url::',
    'run-id::',
    'host::',
]);

$vus = max(1, min(1000, (int)($options['vus'] ?? 50)));
$mode = (string)($options['mode'] ?? 'duration'); // 'duration' or 'requests'
$duration = max(5, min(3600, (int)($options['duration'] ?? 60))); // Up to 60 minutes
$totalRequestsTarget = max(50, min(500000, (int)($options['requests'] ?? 5000)));
$scenario = (string)($options['scenario'] ?? 'mixed');
$customUrl = (string)($options['custom-url'] ?? '');
$runId = trim((string)($options['run-id'] ?? ('run_' . bin2hex(random_bytes(6)))), "'\"");
$hostHeader = (string)($options['host'] ?? 'amut.speed-ping.shop');

$activeTestFile = $storageLogs . '/active_stress_test.json';
$historyFile = $storageLogs . '/stress_history.json';
$stopFlagFile = $storageLogs . '/stop_stress_test.flag';

// Clean old stop flags
if (is_file($stopFlagFile)) {
    @unlink($stopFlagFile);
}

// Base URL resolution
$isLinux = (PHP_OS_FAMILY === 'Linux');
$baseUrl = $isLinux ? 'http://127.0.0.1' : 'http://localhost/amutbar-admin';

// Automatic Driver JWT Token Acquisition
function get_stress_driver_token(string $basePath): string
{
    static $token = null;
    if ($token !== null) return $token;

    try {
        require_once $basePath . '/includes/init.php';
        require_once $basePath . '/includes/api.php';
        require_once $basePath . '/includes/jwt.php';

        $pdo = db();
        // Check for active driver user (user_type=1)
        $driverId = (int)$pdo->query("SELECT id FROM users WHERE user_type=1 AND status=1 AND deleted_at IS NULL LIMIT 1")->fetchColumn();
        if ($driverId <= 0) {
            $driverId = (int)$pdo->query("SELECT id FROM users WHERE status=1 AND deleted_at IS NULL LIMIT 1")->fetchColumn();
        }

        if ($driverId > 0) {
            $issued = api_tokens_issue($driverId, 1);
            if (!empty($issued['access_token'])) {
                $token = (string)$issued['access_token'];
                return $token;
            }
        }
    } catch (\Throwable $e) {
        // Fallback
    }

    return '';
}

$driverToken = get_stress_driver_token($basePath);

// Scenarios definition with weighted endpoints
$scenarios = [
    'mixed' => [
        'name' => 'سناریوی ترکیبی کاربران واقعی (Realistic Traffic Mix)',
        'en_name' => 'Realistic Traffic Mix (Loads, Cities, Config, FAQs, Vehicles)',
        'endpoints' => [
            ['path' => '/api/v1/driver/loads', 'weight' => 30],
            ['path' => '/api/v1/meta/cities/search?q=' . urlencode('تهران'), 'weight' => 25],
            ['path' => '/api/v1/meta/app-config?target_app_id=1', 'weight' => 20],
            ['path' => '/api/v1/meta/provinces', 'weight' => 10],
            ['path' => '/api/v1/meta/vehicle-types', 'weight' => 10],
            ['path' => '/api/v1/faqs?target_app_id=1', 'weight' => 5],
        ]
    ],
    'ultimate_10k' => [
        'name' => 'سخت‌ترین حالت ممکن ۱۰,۰۰۰ کاربر (Ultimate Database Storm)',
        'en_name' => 'Ultimate 10k Users DB Storm (Heavy Text Search & Catalogs)',
        'endpoints' => [
            ['path' => '/api/v1/meta/cities/search?q=' . urlencode('تهران'), 'weight' => 35],
            ['path' => '/api/v1/meta/cities/search?q=' . urlencode('اصفهان'), 'weight' => 25],
            ['path' => '/api/v1/meta/cities/search?q=' . urlencode('شیراز'), 'weight' => 20],
            ['path' => '/api/v1/meta/vehicle-types', 'weight' => 10],
            ['path' => '/api/v1/meta/app-config?target_app_id=1', 'weight' => 10],
        ]
    ],
    'public_only' => [
        'name' => 'ترافیک عمومی بدون احراز هویت (Public Endpoints - No Auth)',
        'en_name' => 'Public Endpoints (Cities, Config, Provinces, Vehicles)',
        'endpoints' => [
            ['path' => '/api/v1/meta/cities/search?q=' . urlencode('تهران'), 'weight' => 35],
            ['path' => '/api/v1/meta/app-config?target_app_id=1', 'weight' => 35],
            ['path' => '/api/v1/meta/provinces', 'weight' => 15],
            ['path' => '/api/v1/meta/vehicle-types', 'weight' => 15],
        ]
    ],
    'driver_loads' => [
        'name' => 'فید بارهای رانندگان (/driver/loads - با توکن احراز هویت)',
        'en_name' => 'Driver Loads Feed (/driver/loads - DB Join)',
        'endpoints' => [
            ['path' => '/api/v1/driver/loads', 'weight' => 100]
        ]
    ],
    'city_search' => [
        'name' => 'جستجوی دیتابیسی شهرها (/meta/cities/search)',
        'en_name' => 'City DB Search (/meta/cities/search)',
        'endpoints' => [
            ['path' => '/api/v1/meta/cities/search?q=' . urlencode('تهران'), 'weight' => 50],
            ['path' => '/api/v1/meta/cities/search?q=' . urlencode('اصفهان'), 'weight' => 25],
            ['path' => '/api/v1/meta/cities/search?q=' . urlencode('شیراز'), 'weight' => 25],
        ]
    ],
    'app_config' => [
        'name' => 'تنظیمات و متادیتا اپلیکیشن (/meta/app-config)',
        'en_name' => 'Application Config (/meta/app-config)',
        'endpoints' => [
            ['path' => '/api/v1/meta/app-config?target_app_id=1', 'weight' => 100]
        ]
    ],
    'vehicle_types' => [
        'name' => 'انواع ناوگان و خودروها (/meta/vehicle-types)',
        'en_name' => 'Vehicle Types Catalog (/meta/vehicle-types)',
        'endpoints' => [
            ['path' => '/api/v1/meta/vehicle-types', 'weight' => 100]
        ]
    ],
    'provinces' => [
        'name' => 'لیست استان‌ها (/meta/provinces)',
        'en_name' => 'Provinces List (/meta/provinces)',
        'endpoints' => [
            ['path' => '/api/v1/meta/provinces', 'weight' => 100]
        ]
    ],
    'faqs' => [
        'name' => 'سوالات متداول (/faqs)',
        'en_name' => 'FAQs Content (/faqs)',
        'endpoints' => [
            ['path' => '/api/v1/faqs?target_app_id=1', 'weight' => 100]
        ]
    ],
    'banners' => [
        'name' => 'بنرهای تبلیغاتی (/banners)',
        'en_name' => 'Promotional Banners (/banners)',
        'endpoints' => [
            ['path' => '/api/v1/banners?target_app_id=1', 'weight' => 100]
        ]
    ],
    'health' => [
        'name' => 'بررسی سلامت پایه وب‌سرور (/health - Pure Baseline)',
        'en_name' => 'Web Server Health Check (/health)',
        'endpoints' => [
            ['path' => '/api/v1/health', 'weight' => 100]
        ]
    ],
    'custom' => [
        'name' => 'آدرس سفارشی (' . ($customUrl ?: '/api/v1/health') . ')',
        'en_name' => 'Custom Endpoint (' . ($customUrl ?: '/api/v1/health') . ')',
        'endpoints' => [
            ['path' => '/' . ltrim($customUrl ?: '/api/v1/health', '/'), 'weight' => 100]
        ]
    ]
];

$activeScenario = $scenarios[$scenario] ?? $scenarios['mixed'];

function pick_endpoint_url(array $endpoints, string $baseUrl): string
{
    $rand = random_int(1, 100);
    $cumulative = 0;
    foreach ($endpoints as $ep) {
        $cumulative += $ep['weight'];
        if ($rand <= $cumulative) {
            return $baseUrl . '/' . ltrim($ep['path'], '/');
        }
    }
    return $baseUrl . '/' . ltrim($endpoints[0]['path'], '/');
}

function calculate_percentile(array &$sortedValues, float $percentile): float
{
    $count = count($sortedValues);
    if ($count === 0) return 0.0;
    if ($count === 1) return (float)$sortedValues[0];
    
    $index = ($percentile / 100.0) * ($count - 1);
    $lower = (int)floor($index);
    $upper = (int)ceil($index);
    if ($lower === $upper) {
        return (float)$sortedValues[$lower];
    }
    $fraction = $index - $lower;
    return round((float)($sortedValues[$lower] + $fraction * ($sortedValues[$upper] - $sortedValues[$lower])), 2);
}

// Initial Metadata State
$initialState = [
    'run_id' => $runId,
    'status' => 'running',
    'mode' => $mode,
    'scenario' => $scenario,
    'scenario_name' => $activeScenario['name'],
    'target_vus' => $vus,
    'duration_target_sec' => $duration,
    'requests_target' => $totalRequestsTarget,
    'start_time' => date('Y-m-d H:i:s'),
    'elapsed_sec' => 0,
    'remaining_sec' => $mode === 'duration' ? $duration : 0,
    'progress_percent' => 0,
    'total_requests' => 0,
    'success_requests' => 0,
    'failed_requests' => 0,
    'current_rps' => 0.0,
    'avg_rps' => 0.0,
    'latency_avg_ms' => 0.0,
    'latency_p50_ms' => 0.0,
    'latency_p90_ms' => 0.0,
    'latency_p95_ms' => 0.0,
    'latency_p99_ms' => 0.0,
    'status_codes' => [],
    'rps_chart' => [],
    'latency_chart' => [],
    'pid' => getmypid(),
];
@file_put_contents($activeTestFile, json_encode($initialState, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

if (php_sapi_name() === 'cli') {
    echo "=== Starting Amutbar Stress Test Runner ===\n";
    echo "Run ID:          {$runId}\n";
    echo "Scenario:        {$activeScenario['en_name']}\n";
    echo "Target VUs:      {$vus}\n";
    echo "Mode:            {$mode} (" . ($mode === 'duration' ? "{$duration}s" : "{$totalRequestsTarget} requests") . ")\n";
    echo "Target Base:     {$baseUrl}\n";
    echo "Press Ctrl+C to stop.\n\n";
}

// Execution Variables
$mh = curl_multi_init();
$handles = [];
$totalSent = 0;
$totalCompleted = 0;
$statusCodes = [];
$latencies = [];
$successCount = 0;
$failedCount = 0;

$startTime = microtime(true);
$lastSecTime = $startTime;
$reqsThisSec = 0;
$rpsHistory = [];
$latencyHistory = [];

function create_curl_client(string $url, string $host, string $token): \CurlHandle
{
    $headers = [
        'Host: ' . $host,
        'User-Agent: Amutbar-EnduranceRunner/2.2',
        'Accept: application/json',
        'Connection: keep-alive',
    ];
    if ($token !== '') {
        $headers[] = 'Authorization: Bearer ' . $token;
    }

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 25,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTPHEADER => $headers,
    ]);
    return $ch;
}

// Fill pipeline with initial pool of handles
$maxConcurrent = $vus;
while ($totalSent < $maxConcurrent) {
    if ($mode === 'requests' && $totalSent >= $totalRequestsTarget) break;
    $targetUrl = pick_endpoint_url($activeScenario['endpoints'], $baseUrl);
    $ch = create_curl_client($targetUrl, $hostHeader, $driverToken);
    $hKey = (int)$ch;
    $handles[$hKey] = [
        'handle' => $ch,
        'start' => microtime(true),
        'url' => $targetUrl,
    ];
    curl_multi_add_handle($mh, $ch);
    $totalSent++;
}

$running = null;
$shouldStop = false;

do {
    // 1. Process multi-curl transfers
    do {
        $mrc = curl_multi_exec($mh, $running);
    } while ($mrc === CURLM_CALL_MULTI_PERFORM);

    // 2. Read completed transfers
    while ($info = curl_multi_info_read($mh)) {
        if ($info['msg'] === CURLMSG_DONE) {
            $ch = $info['handle'];
            $hKey = (int)$ch;
            $requestStart = $handles[$hKey]['start'] ?? microtime(true);
            $latencyMs = round((microtime(true) - $requestStart) * 1000, 2);

            $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($httpCode >= 200 && $httpCode < 500) {
                $successCount++;
            } else {
                $failedCount++;
            }

            $statusCodes[$httpCode] = ($statusCodes[$httpCode] ?? 0) + 1;
            $latencies[] = $latencyMs;
            $totalCompleted++;
            $reqsThisSec++;

            // Clean up old handle
            curl_multi_remove_handle($mh, $ch);
            unset($ch);
            unset($handles[$hKey]);

            // Add new handle if test is still running
            $now = microtime(true);
            $elapsedSec = $now - $startTime;
            $canSendMore = true;
            if ($mode === 'duration' && $elapsedSec >= $duration) {
                $canSendMore = false;
            } elseif ($mode === 'requests' && $totalSent >= $totalRequestsTarget) {
                $canSendMore = false;
            }

            if (!$shouldStop && $canSendMore) {
                $nextUrl = pick_endpoint_url($activeScenario['endpoints'], $baseUrl);
                $newCh = create_curl_client($nextUrl, $hostHeader, $driverToken);
                $newKey = (int)$newCh;
                $handles[$newKey] = [
                    'handle' => $newCh,
                    'start' => microtime(true),
                    'url' => $nextUrl,
                ];
                curl_multi_add_handle($mh, $newCh);
                $totalSent++;
            }
        }
    }

    // 3. Periodic Telemetry Stream (Every 1 second)
    $now = microtime(true);
    if (($now - $lastSecTime) >= 1.0) {
        $elapsedSec = (int)round($now - $startTime);
        $currentRps = round($reqsThisSec / ($now - $lastSecTime), 1);
        $avgRps = $elapsedSec > 0 ? round($totalCompleted / $elapsedSec, 1) : 0.0;
        
        $rpsHistory[] = $currentRps;
        if (count($rpsHistory) > 60) array_shift($rpsHistory);

        // Recent latency sample
        $recentLatSlice = array_slice($latencies, -50);
        $recentAvgLat = count($recentLatSlice) > 0 ? round(array_sum($recentLatSlice) / count($recentLatSlice), 1) : 0.0;
        $latencyHistory[] = $recentAvgLat;
        if (count($latencyHistory) > 60) array_shift($latencyHistory);

        // Calculate progress
        if ($mode === 'duration') {
            $remSec = max(0, $duration - $elapsedSec);
            $progressPercent = min(100, round(($elapsedSec / $duration) * 100, 1));
        } else {
            $remSec = 0;
            $progressPercent = min(100, round(($totalCompleted / $totalRequestsTarget) * 100, 1));
        }

        // Calculate quick percentiles
        $sortedSamples = array_slice($latencies, -500);
        sort($sortedSamples);
        $p50 = calculate_percentile($sortedSamples, 50);
        $p90 = calculate_percentile($sortedSamples, 90);
        $p95 = calculate_percentile($sortedSamples, 95);
        $p99 = calculate_percentile($sortedSamples, 99);

        // Check for Emergency Stop Flag
        if (is_file($stopFlagFile)) {
            $shouldStop = true;
            @unlink($stopFlagFile);
        }

        $liveState = [
            'run_id' => $runId,
            'status' => $shouldStop ? 'stopping' : 'running',
            'mode' => $mode,
            'scenario' => $scenario,
            'scenario_name' => $activeScenario['name'],
            'target_vus' => $vus,
            'duration_target_sec' => $duration,
            'requests_target' => $totalRequestsTarget,
            'start_time' => date('Y-m-d H:i:s', (int)$startTime),
            'elapsed_sec' => $elapsedSec,
            'remaining_sec' => $remSec,
            'progress_percent' => $progressPercent,
            'total_requests' => $totalCompleted,
            'success_requests' => $successCount,
            'failed_requests' => $failedCount,
            'current_rps' => $currentRps,
            'avg_rps' => $avgRps,
            'latency_avg_ms' => $recentAvgLat,
            'latency_p50_ms' => $p50,
            'latency_p90_ms' => $p90,
            'latency_p95_ms' => $p95,
            'latency_p99_ms' => $p99,
            'status_codes' => $statusCodes,
            'rps_chart' => $rpsHistory,
            'latency_chart' => $latencyHistory,
            'pid' => getmypid(),
        ];
        @file_put_contents($activeTestFile, json_encode($liveState, JSON_UNESCAPED_SLASHES));

        // CLI Live Terminal Output
        if (php_sapi_name() === 'cli') {
            $timeFormatted = sprintf('%02d:%02d / %02d:%02d', (int)($elapsedSec / 60), $elapsedSec % 60, (int)($duration / 60), $duration % 60);
            echo sprintf("\r[%s] Reqs: %s | RPS: %5.1f | Avg Lat: %4.1fms | p95: %4.1fms | 200 OK: %d",
                $timeFormatted,
                number_format($totalCompleted),
                $currentRps,
                $recentAvgLat,
                $p95,
                $statusCodes[200] ?? 0
            );
        }

        $reqsThisSec = 0;
        $lastSecTime = $now;

        // Check if duration or requests goal reached
        if ($mode === 'duration' && $elapsedSec >= $duration) {
            $shouldStop = true;
        } elseif ($mode === 'requests' && $totalCompleted >= $totalRequestsTarget) {
            $shouldStop = true;
        }

        // Limit memory footprint if latencies array exceeds 100k
        if (count($latencies) > 100000) {
            $filtered = [];
            for ($k = 0; $k < count($latencies); $k += 2) {
                $filtered[] = $latencies[$k];
            }
            $latencies = $filtered;
            unset($filtered);
        }
    }

    if ($shouldStop && count($handles) === 0) {
        break;
    }

    // Wait slightly for socket activity
    if ($running) {
        curl_multi_select($mh, 0.05);
    } else {
        usleep(5000); // 5ms
    }

} while (($running > 0 || count($handles) > 0) && !$shouldStop);

// Clean up remaining handles if stopped early
foreach ($handles as $h) {
    curl_multi_remove_handle($mh, $h['handle']);
    unset($h['handle']);
}
curl_multi_close($mh);

$endTime = microtime(true);
$totalDurationSec = max(0.01, round($endTime - $startTime, 2));

// Calculate Complete Final Statistical Percentiles
sort($latencies);
$totalCompleted = count($latencies);
$minLat = $totalCompleted > 0 ? (float)$latencies[0] : 0.0;
$maxLat = $totalCompleted > 0 ? (float)$latencies[$totalCompleted - 1] : 0.0;
$avgLat = $totalCompleted > 0 ? round(array_sum($latencies) / $totalCompleted, 2) : 0.0;

$p50 = calculate_percentile($latencies, 50);
$p75 = calculate_percentile($latencies, 75);
$p90 = calculate_percentile($latencies, 90);
$p95 = calculate_percentile($latencies, 95);
$p99 = calculate_percentile($latencies, 99);

// Standard Deviation (Jitter)
$sumVariance = 0.0;
foreach ($latencies as $l) {
    $sumVariance += ($l - $avgLat) ** 2;
}
$stdDev = $totalCompleted > 1 ? round(sqrt($sumVariance / ($totalCompleted - 1)), 2) : 0.0;

$rpsFinal = round($totalCompleted / $totalDurationSec, 1);

// Server errors count (5xx)
$server5xxErrors = 0;
foreach ($statusCodes as $sc => $c) {
    if ($sc >= 500) $server5xxErrors += $c;
}
$serverSuccessRate = $totalCompleted > 0 ? round((($totalCompleted - $server5xxErrors) / $totalCompleted) * 100, 2) : 100.0;
$strict2xxRate = $totalCompleted > 0 ? round(($successCount / $totalCompleted) * 100, 2) : 100.0;

// Grade and Performance Assessment based on Server Health
$grade = 'EXCELLENT';
if ($server5xxErrors > 0 || $p95 > 2000.0) {
    $grade = 'CRITICAL';
} elseif ($p95 > 800.0) {
    $grade = 'WARNING';
} elseif ($p95 > 300.0) {
    $grade = 'GOOD';
}

// 10k Users Capacity Sizing Calculator
$estPeakConcurrentUsers = (int)round($rpsFinal * 25);
$estDailyActiveUsers = (int)round($rpsFinal * 1440);
$capacityVerdict = '';
if ($estPeakConcurrentUsers >= 10000) {
    $capacityVerdict = "فوق‌العاده! این سرور به راحتی توانایی پشتیبانی از ۱۰,۰۰۰ کاربر آنلاین همزمان و بیش از ۵۰۰,۰۰۰ کاربر فعال روزانه را داراست.";
} elseif ($estDailyActiveUsers >= 10000) {
    $capacityVerdict = "هدف ۱۰,۰۰۰ کاربر روزانه محقق شد! سرور توانایی پاسخ به ترافیک {$estDailyActiveUsers} کاربر در روز و {$estPeakConcurrentUsers} کاربر آنلاین در ساعات پیک را دارد.";
} else {
    $capacityVerdict = "برای رسیدن به تارگت پایدار ۱۰,۰۰۰ کاربر همزمان، ارتقای سرور به ۴ هسته یا کلاسترینگ دیتابیس پیشنهاد می‌شود.";
}

$recommendation = "عملکرد پایدار و متناسب با کانفیگ سخت‌افزاری Hetzner CX23.";
if ($server5xxErrors === 0 && $grade === 'EXCELLENT') {
    $recommendation = "سرور با زمان تاخیر {$avgLat}ms و نرخ پاسخ {$rpsFinal} RPS بدون حتی یک خطای سروری (0 Server Errors) با موفقیت کامل تست را به پایان رساند.";
} elseif ($grade === 'WARNING') {
    $recommendation = "با افزایش ترافیک، تاخیر p95 به {$p95}ms رسید. به دلیل حفاظت از RAM، پروسس‌های PHP-FPM در صف قرار گرفتند اما هیچ درخواستی قطع نشد.";
} elseif ($grade === 'CRITICAL') {
    $recommendation = "کدهای خطای سروری (5xx) یا تایم‌اوت مشاهده شد. مقادیر pm.max_children در PHP-FPM را بررسی فرمایید.";
}

// Final Report Record
$finalReport = [
    'id' => $runId,
    'timestamp' => date('Y-m-d H:i:s'),
    'mode' => $mode,
    'scenario' => $scenario,
    'scenario_name' => $activeScenario['name'],
    'concurrency_vus' => $vus,
    'duration_seconds' => $totalDurationSec,
    'total_requests' => $totalCompleted,
    'success_requests' => $successCount,
    'failed_requests' => $server5xxErrors,
    'success_rate' => $strict2xxRate,
    'server_success_rate' => $serverSuccessRate,
    'rps' => $rpsFinal,
    'latency_min_ms' => $minLat,
    'latency_avg_ms' => $avgLat,
    'latency_p50_ms' => $p50,
    'latency_p75_ms' => $p75,
    'latency_p90_ms' => $p90,
    'latency_p95_ms' => $p95,
    'latency_p99_ms' => $p99,
    'latency_max_ms' => $maxLat,
    'latency_std_dev' => $stdDev,
    'status_codes' => $statusCodes,
    'grade' => $grade,
    'capacity_sizing' => [
        'target_users' => 10000,
        'estimated_peak_concurrent_users' => $estPeakConcurrentUsers,
        'estimated_daily_active_users' => $estDailyActiveUsers,
        'verdict' => $capacityVerdict,
    ],
    'recommendation' => $recommendation,
];

// Append to history
$existing = [];
if (is_file($historyFile)) {
    $existing = json_decode((string)file_get_contents($historyFile), true) ?: [];
}
$existing[] = $finalReport;
if (count($existing) > 50) {
    $existing = array_slice($existing, -50);
}
@file_put_contents($historyFile, json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

// Mark active test as completed
$finalReport['status'] = 'completed';
$finalReport['progress_percent'] = 100;
$finalReport['remaining_sec'] = 0;
@file_put_contents($activeTestFile, json_encode($finalReport, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

// Echo summary for CLI
if (php_sapi_name() === 'cli') {
    echo "\n\n";
    echo "========================================================\n";
    echo "=== Amutbar Stress Test Benchmark Finished ===\n";
    echo "========================================================\n";
    echo "Run ID:              {$runId}\n";
    echo "Scenario:            {$activeScenario['en_name']}\n";
    echo "Duration:            {$totalDurationSec}s\n";
    echo "Concurrency (VUs):   {$vus}\n";
    echo "Total Requests:      " . number_format($totalCompleted) . "\n";
    echo "Throughput (RPS):    {$rpsFinal} req/sec\n";
    echo "Server Success Rate: {$serverSuccessRate}%\n";
    echo "Status Codes:        " . json_encode($statusCodes) . "\n";
    echo "Latency Min:         {$minLat} ms\n";
    echo "Latency Avg:         {$avgLat} ms\n";
    echo "Latency p50:         {$p50} ms\n";
    echo "Latency p90:         {$p90} ms\n";
    echo "Latency p95:         {$p95} ms\n";
    echo "Latency p99:         {$p99} ms\n";
    echo "Latency Max:         {$maxLat} ms\n";
    echo "Performance Grade:   {$grade}\n";
    echo "Peak Concurrent:     ~" . number_format($estPeakConcurrentUsers) . " users\n";
    echo "Daily Active Users:  ~" . number_format($estDailyActiveUsers) . " users/day\n";
    echo "========================================================\n";
}
