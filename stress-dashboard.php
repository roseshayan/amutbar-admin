<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/init.php';
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>داشبورد زنده مانیتورینگ و استرس تست آموت‌بار</title>
    <link rel="icon" type="image/png" href="assets/images/brand-logos/favicon.ico">
    <link rel="stylesheet" href="assets/libs/bootstrap/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="assets/icon-fonts/remixicon/remixicon.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vazirmatn@33.0.3/Vazirmatn-font-face.css">
    <script src="assets/libs/chart.js/chart.umd.js"></script>
    <style>
        :root {
            --amut-primary: #1e3a8a;
            --amut-secondary: #0ea5e9;
            --amut-dark: #0f172a;
            --amut-card-bg: #ffffff;
            --amut-bg: #f8fafc;
        }
        body {
            background-color: var(--amut-bg);
            font-family: 'Vazirmatn', -apple-system, BlinkMacSystemFont, sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }
        .navbar-custom {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
            color: #fff;
            padding: 1rem 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .metric-card {
            background: #fff;
            border-radius: 1rem;
            padding: 1.25rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        .metric-card:hover {
            box-shadow: 0 6px 16px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }
        .metric-title {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        .metric-value {
            font-size: 1.85rem;
            font-weight: 800;
            color: #0f172a;
        }
        .metric-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.6rem;
            border-radius: 2rem;
            font-weight: 700;
        }
        .status-badge-live {
            background-color: #10b981;
            color: #fff;
            animation: pulse-green 2s infinite;
        }
        @keyframes pulse-green {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        .preset-btn {
            border-radius: 0.6rem;
            font-weight: 700;
            padding: 0.5rem 1rem;
            transition: all 0.2s ease;
        }
        .preset-btn.active {
            background-color: var(--amut-primary);
            color: #fff;
            border-color: var(--amut-primary);
        }
        .chart-container {
            position: relative;
            height: 220px;
            width: 100%;
        }
        .table-responsive {
            border-radius: 0.75rem;
            overflow: hidden;
        }
    </style>
</head>
<body>

    <!-- Header Navbar -->
    <header class="navbar-custom">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-3">
                <i class="ri-dashboard-3-line fs-2 text-info"></i>
                <div>
                    <h1 class="h5 m-0 fw-bold">داشبورد زنده مانیتورینگ سرور و استرس تست آموت‌بار</h1>
                    <small class="text-white-50">مانیتورینگ برخط منابع سرور (Hetzner 2 vCPU / 4 GB RAM) و تست ظرفیت ترافیک</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge status-badge-live px-3 py-2"><i class="ri-pulse-line me-1"></i> مانیتورینگ زنده فعال است</span>
                <a href="swagger/" target="_blank" class="btn btn-sm btn-outline-light"><i class="ri-article-line me-1"></i> Swagger API</a>
                <a href="dashboard.php" class="btn btn-sm btn-info text-white"><i class="ri-arrow-left-line me-1"></i> پنل اصلی</a>
            </div>
        </div>
    </header>

    <div class="container-fluid py-4 px-lg-4">

        <!-- Top Live Stats Cards -->
        <div class="row g-3 mb-4">
            <!-- CPU Metric -->
            <div class="col-xl-3 col-md-6">
                <div class="metric-card">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="metric-title">مصرف پردازنده (CPU)</span>
                        <i class="ri-cpu-line fs-4 text-primary"></i>
                    </div>
                    <div class="d-flex align-items-baseline gap-2">
                        <span class="metric-value" id="valCpu">0%</span>
                        <span class="metric-badge bg-light text-muted" id="badgeCpuCores">2 هسته</span>
                    </div>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar bg-primary" id="progCpu" role="progressbar" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- RAM Metric -->
            <div class="col-xl-3 col-md-6">
                <div class="metric-card">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="metric-title">حافظه رم (RAM)</span>
                        <i class="ri-database-2-line fs-4 text-info"></i>
                    </div>
                    <div class="d-flex align-items-baseline gap-2">
                        <span class="metric-value" id="valRam">0 MB</span>
                        <span class="metric-badge bg-info-subtle text-info" id="badgeRamPercent">0%</span>
                    </div>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar bg-info" id="progRam" role="progressbar" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- PHP-FPM Workers -->
            <div class="col-xl-3 col-md-6">
                <div class="metric-card">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="metric-title">ورکرهای فعال PHP-FPM</span>
                        <i class="ri-server-line fs-4 text-success"></i>
                    </div>
                    <div class="d-flex align-items-baseline gap-2">
                        <span class="metric-value" id="valPhpWorkers">3</span>
                        <span class="metric-badge bg-success-subtle text-success" id="badgePhpMax">سقف: 65</span>
                    </div>
                    <small class="text-muted d-block mt-2">استخر پردازش سریع FastCGI</small>
                </div>
            </div>

            <!-- MySQL Connections -->
            <div class="col-xl-3 col-md-6">
                <div class="metric-card">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="metric-title">کانکشن‌های دیتابیس MySQL</span>
                        <i class="ri-hard-drive-2-line fs-4 text-warning"></i>
                    </div>
                    <div class="d-flex align-items-baseline gap-2">
                        <span class="metric-value" id="valMysqlConn">1</span>
                        <span class="metric-badge bg-warning-subtle text-warning">بافر: 1.5GB</span>
                    </div>
                    <small class="text-muted d-block mt-2" id="txtMysqlQueries">کل کوئری‌ها: -</small>
                </div>
            </div>
        </div>

        <!-- Middle Section: Real-time Charts & Stress Controller -->
        <div class="row g-4 mb-4">
            <!-- Left: Real-time Charts -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
                        <h2 class="h6 m-0 fw-bold"><i class="ri-line-chart-line text-primary me-2"></i>نمودار لحظه‌ای مصرف منابع سرور</h2>
                        <span class="badge bg-light text-muted">به‌روزرسانی خودکار هر ۲ ثانیه</span>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="chartTelemetry"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Interactive Stress Test Control Panel -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h2 class="h6 m-0 fw-bold text-danger"><i class="ri-flashlight-line text-danger me-2"></i>کنترلر استرس تست آنی (Stress Test Runner)</h2>
                    </div>
                    <div class="card-body">
                        <form id="formStressTest">
                            <!-- Preset Buttons -->
                            <label class="form-label fw-bold small text-muted">انتخاب سناریوی آماده:</label>
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <button type="button" class="btn btn-sm btn-outline-secondary preset-btn active" data-vus="25" data-reqs="100">۲۵ کاربر (پایه)</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary preset-btn" data-vus="100" data-reqs="400">۱۰۰ کاربر (معمولی)</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary preset-btn" data-vus="250" data-reqs="1000">۲۵۰ کاربر (سنگین)</button>
                                <button type="button" class="btn btn-sm btn-outline-danger preset-btn" data-vus="500" data-reqs="2000">۵۰۰ کاربر (پیک)</button>
                            </div>

                            <!-- Target Endpoint -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">مسیر اندپوینت مورد تست:</label>
                                <select class="form-select form-select-sm" id="inpEndpoint">
                                    <option value="/health">/health (تست وب‌سرور و PHP)</option>
                                    <option value="/meta/app-config?target_app_id=1" selected>/meta/app-config (تنظیمات اپلیکیشن راننده)</option>
                                    <option value="/banners?target_app_id=1&placement=dashboard">/banners (بنرهای دیتابیس)</option>
                                    <option value="/meta/provinces">/meta/provinces (لیست استان‌ها)</option>
                                    <option value="/meta/cities/search?q=تهران">/meta/cities/search (جستجوی دیتابیس با LIKE)</option>
                                </select>
                            </div>

                            <!-- Concurrency Slider -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label fw-bold small text-muted">کاربران همزمان (VUs):</label>
                                    <span class="fw-bold text-primary" id="lblVus">25</span>
                                </div>
                                <input type="range" class="form-range" id="inpVus" min="5" max="500" step="5" value="25">
                            </div>

                            <!-- Total Requests Slider -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label fw-bold small text-muted">تعداد کل درخواست‌ها:</label>
                                    <span class="fw-bold text-primary" id="lblReqs">100</span>
                                </div>
                                <input type="range" class="form-range" id="inpReqs" min="20" max="2500" step="20" value="100">
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-danger w-100 py-2 fw-bold" id="btnStartTest">
                                <i class="ri-play-circle-line me-1 fs-5 align-middle"></i> شروع استرس تست زنده
                            </button>
                        </form>

                        <!-- Live Progress Spinner -->
                        <div class="text-center mt-3 d-none" id="spinnerTest">
                            <div class="spinner-border text-danger spinner-border-sm me-2" role="status"></div>
                            <span class="small fw-bold text-muted">تست در حال اجراست؛ لطفاً نمودار منابع را زیر نظر بگیرید...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Test Report Summary Card -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 d-none" id="cardReport">
            <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
                <h3 class="h6 m-0 fw-bold"><i class="ri-checkbox-circle-line text-success me-2"></i>نتیجه آخرین استرس تست اجرا شده</h3>
                <span class="badge" id="badgeGrade">EXCELLENT</span>
            </div>
            <div class="card-body">
                <div class="row g-3 text-center">
                    <div class="col-md-3 col-6">
                        <div class="p-3 bg-light rounded-3">
                            <span class="text-muted small d-block mb-1">توان پاسخگویی (RPS)</span>
                            <span class="h4 fw-bold text-primary" id="repRps">0</span> <small>req/sec</small>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-3 bg-light rounded-3">
                            <span class="text-muted small d-block mb-1">تاخیر p95 (زمان پاسخ)</span>
                            <span class="h4 fw-bold text-info" id="repP95">0</span> <small>ms</small>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-3 bg-light rounded-3">
                            <span class="text-muted small d-block mb-1">درصد موفقیت (Success Rate)</span>
                            <span class="h4 fw-bold text-success" id="repSuccess">100%</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-3 bg-light rounded-3">
                            <span class="text-muted small d-block mb-1">مدت زمان اجرا</span>
                            <span class="h4 fw-bold text-dark" id="repDuration">0</span> <small>sec</small>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info mt-3 mb-0 small" id="alertRecommendation">
                    تحلیل ظرفیت سرور در این بخش نمایش داده می‌شود.
                </div>
            </div>
        </div>

        <!-- Historical Reports Table -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h3 class="h6 m-0 fw-bold"><i class="ri-history-line text-secondary me-2"></i>تاریخچه و لاگ تست‌های پیشین</h3>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-secondary" id="btnExportCsv"><i class="ri-download-2-line me-1"></i> خروجی اکسل (CSV)</button>
                    <button class="btn btn-outline-danger" id="btnClearHistory"><i class="ri-delete-bin-line me-1"></i> پاکسازی تاریخچه</button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center">
                        <thead class="table-light">
                            <tr>
                                <th>زمان</th>
                                <th>اندپوینت</th>
                                <th>کاربر همزمان (VUs)</th>
                                <th>تعداد درخواست</th>
                                <th>RPS</th>
                                <th>تاخیر p95</th>
                                <th>نرخ موفقیت</th>
                                <th>ارزیابی عملکرد</th>
                            </tr>
                        </thead>
                        <tbody id="tblHistoryBody">
                            <tr><td colspan="8" class="text-muted py-4">در حال دریافت تاریخچه...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Setup Chart.js for real-time telemetry
        const ctx = document.getElementById('chartTelemetry').getContext('2d');
        const maxPoints = 25;
        const labels = Array(maxPoints).fill('');
        const dataCpu = Array(maxPoints).fill(0);
        const dataRam = Array(maxPoints).fill(0);

        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'مصرف CPU (%)',
                        data: dataCpu,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'مصرف RAM (%)',
                        data: dataRam,
                        borderColor: '#0284c7',
                        backgroundColor: 'rgba(2, 132, 199, 0.05)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: { callback: v => v + '%' }
                    },
                    x: { display: false }
                },
                plugins: {
                    legend: { position: 'top' }
                }
            }
        });

        // Polling Telemetry Function
        async function fetchTelemetry() {
            try {
                const res = await fetch('ajax/server_telemetry.php');
                const data = await res.json();
                if (data.ok) {
                    // Update UI Cards
                    document.getElementById('valCpu').innerText = data.cpu.percent + '%';
                    document.getElementById('progCpu').style.width = data.cpu.percent + '%';

                    document.getElementById('valRam').innerText = data.memory.used_mb + ' MB';
                    document.getElementById('badgeRamPercent').innerText = data.memory.percent + '%';
                    document.getElementById('progRam').style.width = data.memory.percent + '%';

                    document.getElementById('valPhpWorkers').innerText = data.php_fpm.workers;
                    document.getElementById('valMysqlConn').innerText = data.mysql.connected;
                    document.getElementById('txtMysqlQueries').innerText = 'کل کوئری‌ها: ' + data.mysql.queries;

                    // Update Chart
                    chart.data.datasets[0].data.shift();
                    chart.data.datasets[0].data.push(data.cpu.percent);
                    chart.data.datasets[1].data.shift();
                    chart.data.datasets[1].data.push(data.memory.percent);
                    chart.update();
                }
            } catch (e) {
                console.error("Telemetry fetch error:", e);
            }
        }

        // Start Telemetry Polling every 2s
        setInterval(fetchTelemetry, 2000);
        fetchTelemetry();

        // Preset buttons handler
        document.querySelectorAll('.preset-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.preset-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                const vus = btn.dataset.vus;
                const reqs = btn.dataset.reqs;
                document.getElementById('inpVus').value = vus;
                document.getElementById('lblVus').innerText = vus;
                document.getElementById('inpReqs').value = reqs;
                document.getElementById('lblReqs').innerText = reqs;
            });
        });

        // Sliders change handler
        document.getElementById('inpVus').addEventListener('input', e => {
            document.getElementById('lblVus').innerText = e.target.value;
        });
        document.getElementById('inpReqs').addEventListener('input', e => {
            document.getElementById('lblReqs').innerText = e.target.value;
        });

        // Load History Function
        async function loadHistory() {
            try {
                const res = await fetch('ajax/run_stress_test.php?action=history');
                const data = await res.json();
                const tbody = document.getElementById('tblHistoryBody');
                if (data.ok && data.history.length > 0) {
                    tbody.innerHTML = data.history.map(item => {
                        let badgeClass = 'bg-success';
                        if (item.grade === 'GOOD') badgeClass = 'bg-info';
                        if (item.grade === 'WARNING') badgeClass = 'bg-warning text-dark';
                        if (item.grade === 'CRITICAL') badgeClass = 'bg-danger';

                        return `
                            <tr>
                                <td><small>${item.timestamp}</small></td>
                                <td><code>${item.endpoint}</code></td>
                                <td class="fw-bold">${item.concurrency_vus}</td>
                                <td>${item.total_requests}</td>
                                <td class="fw-bold text-primary">${item.rps}</td>
                                <td class="fw-bold text-info">${item.latency_p95_ms} ms</td>
                                <td class="fw-bold text-success">${item.success_rate}%</td>
                                <td><span class="badge ${badgeClass}">${item.grade}</span></td>
                            </tr>
                        `;
                    }).join('');
                } else {
                    tbody.innerHTML = '<tr><td colspan="8" class="text-muted py-4">هنوز تستی ثبت نشده است. اولین تست را اجرا کنید!</td></tr>';
                }
            } catch (e) {
                console.error(e);
            }
        }
        loadHistory();

        // Clear History
        document.getElementById('btnClearHistory').addEventListener('click', async () => {
            if (confirm('آیا از پاکسازی تاریخچه تست‌ها اطمینان دارید؟')) {
                await fetch('ajax/run_stress_test.php?action=clear_history');
                loadHistory();
            }
        });

        // Export CSV
        document.getElementById('btnExportCsv').addEventListener('click', () => {
            window.open('ajax/run_stress_test.php?action=history', '_blank');
        });

        // Handle Stress Test Execution
        document.getElementById('formStressTest').addEventListener('submit', async e => {
            e.preventDefault();
            const btn = document.getElementById('btnStartTest');
            const spinner = document.getElementById('spinnerTest');
            btn.disabled = true;
            spinner.classList.remove('d-none');

            const formData = new FormData();
            formData.append('endpoint', document.getElementById('inpEndpoint').value);
            formData.append('concurrency', document.getElementById('inpVus').value);
            formData.append('total_requests', document.getElementById('inpReqs').value);

            try {
                const res = await fetch('ajax/run_stress_test.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();
                if (data.ok && data.report) {
                    const r = data.report;
                    // Render Latest Report Card
                    const card = document.getElementById('cardReport');
                    card.classList.remove('d-none');
                    document.getElementById('repRps').innerText = r.rps;
                    document.getElementById('repP95').innerText = r.latency_p95_ms;
                    document.getElementById('repSuccess').innerText = r.success_rate + '%';
                    document.getElementById('repDuration').innerText = r.duration_seconds;
                    document.getElementById('alertRecommendation').innerText = r.recommendation;

                    const badge = document.getElementById('badgeGrade');
                    badge.innerText = r.grade;
                    badge.className = 'badge ' + (r.grade === 'EXCELLENT' ? 'bg-success' : (r.grade === 'GOOD' ? 'bg-info' : 'bg-danger'));

                    loadHistory();
                }
            } catch (err) {
                alert('خطا در اجرای تست: ' + err.message);
            } finally {
                btn.disabled = false;
                spinner.classList.add('d-none');
            }
        });
    </script>
</body>
</html>
