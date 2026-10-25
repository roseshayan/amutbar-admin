<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/init.php';
require_super_admin();
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سامانه پیشرفته استرس تست، بنچ‌مارک و مانیتورینگ آموت‌بار</title>
    <link rel="icon" type="image/png" href="assets/images/brand-logos/favicon.ico">
    <link rel="stylesheet" href="assets/libs/bootstrap/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="assets/icon-fonts/RemixIcons/fonts/remixicon.css">
    <link rel="stylesheet" href="assets/icon-fonts/icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vazirmatn@33.0.3/Vazirmatn-font-face.css">
    <script src="assets/libs/chart.js/chart.umd.js"></script>
    <style>
        :root {
            --amut-primary: #1e40af;
            --amut-primary-light: #3b82f6;
            --amut-secondary: #0ea5e9;
            --amut-dark: #0f172a;
            --amut-card-bg: #ffffff;
            --amut-bg: #f8fafc;
            --amut-border: #e2e8f0;
        }
        body {
            background-color: var(--amut-bg);
            font-family: 'Vazirmatn', -apple-system, BlinkMacSystemFont, sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }
        .header-bar {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
            color: #fff;
            padding: 1.25rem 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.12);
        }
        .card-custom {
            background: var(--amut-card-bg);
            border: 1px solid var(--amut-border);
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            margin-bottom: 1.5rem;
            transition: all 0.2s ease-in-out;
        }
        .card-custom:hover {
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
        }
        .metric-card {
            border-right: 4px solid var(--amut-primary);
        }
        .metric-value {
            font-size: 1.85rem;
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -0.5px;
        }
        .metric-label {
            font-size: 0.82rem;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        .hud-panel {
            background: linear-gradient(145deg, #0f172a 0%, #1e293b 100%);
            color: #f8fafc;
            border-radius: 14px;
            padding: 1.5rem;
            border: 1px solid #334155;
            box-shadow: 0 8px 30px rgba(15, 23, 42, 0.25);
        }
        .hud-digit {
            font-family: monospace, sans-serif;
            font-size: 2.2rem;
            font-weight: 800;
            color: #38bdf8;
            line-height: 1;
        }
        .preset-badge {
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid #cbd5e1;
            padding: 0.5rem 0.85rem;
            border-radius: 8px;
            font-size: 0.85rem;
            background: #fff;
        }
        .preset-badge:hover, .preset-badge.active {
            background: var(--amut-primary);
            color: #fff;
            border-color: var(--amut-primary);
        }
        .pulse-live {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #10b981;
            box-shadow: 0 0 0 rgba(16, 185, 129, 0.4);
            animation: pulse 2s infinite;
            display: inline-block;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
        .percentile-table td, .percentile-table th {
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
        }
        .capacity-box {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 10px;
            padding: 1.25rem;
        }
    </style>
</head>
<body>

    <!-- Header Navigation Bar -->
    <header class="header-bar d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <img src="assets/images/brand-logos/toggle-dark.png" alt="Amutbar Logo" style="height: 38px;">
            <div>
                <h4 class="mb-0 fw-bold">سامانه تست استقامت و مانیتورینگ آموت‌بار</h4>
                <small class="text-white-50">Enterprise Load Testing & Deep Performance Suite v2.0</small>
            </div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-success-subtle text-success border border-success px-3 py-2">
                <span class="pulse-live me-1"></span> سرور متصل است: <strong id="srvHostname">Hetzner CX23</strong>
            </span>
            <a href="swagger/" target="_blank" class="btn btn-outline-light btn-sm">
                <i class="ri-file-code-line align-middle"></i> مستندات Swagger
            </a>
            <a href="index.php" class="btn btn-primary btn-sm">
                <i class="ri-arrow-go-forward-line align-middle"></i> پنل مدیریت
            </a>
        </div>
    </header>

    <div class="container-fluid px-4 py-4">

        <!-- Top System Vitals Banner -->
        <div class="row g-3 mb-4">
            <!-- CPU & Load -->
            <div class="col-xl-3 col-md-6">
                <div class="card-custom p-3 metric-card h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="metric-label"><i class="ri-cpu-line me-1 text-primary"></i> مصرف CPU و بار پردازشی</span>
                        <span class="badge bg-primary-subtle text-primary" id="lblCpuCores">2 Cores</span>
                    </div>
                    <div class="d-flex align-items-baseline gap-2 mt-2">
                        <div class="metric-value text-primary" id="valCpu">0.0%</div>
                        <small class="text-muted" id="valLoadAvg">Load: 0.0, 0.0, 0.0</small>
                    </div>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar bg-primary" id="progCpu" style="width: 0%"></div>
                    </div>
                    <small class="text-muted d-block mt-2" id="txtCpuModel">AMD EPYC Processor</small>
                </div>
            </div>

            <!-- RAM & Buffers -->
            <div class="col-xl-3 col-md-6">
                <div class="card-custom p-3 metric-card h-100" style="border-right-color: #0ea5e9;">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="metric-label"><i class="ri-database-2-line me-1 text-info"></i> حافظه رم و بافرها</span>
                        <span class="badge bg-info-subtle text-info" id="badgeRamPercent">0%</span>
                    </div>
                    <div class="d-flex align-items-baseline gap-2 mt-2">
                        <div class="metric-value text-info" id="valRamUsed">0 MB</div>
                        <small class="text-muted" id="valRamTotal">از 4096 MB</small>
                    </div>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar bg-info" id="progRam" style="width: 0%"></div>
                    </div>
                    <small class="text-muted d-block mt-2" id="txtRamCached">آزاد: 0 MB | بافر/کش: 0 MB</small>
                </div>
            </div>

            <!-- PHP-FPM Workers -->
            <div class="col-xl-3 col-md-6">
                <div class="card-custom p-3 metric-card h-100" style="border-right-color: #f59e0b;">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="metric-label"><i class="ri-server-line me-1 text-warning"></i> استخر ورکر‌های PHP-FPM</span>
                        <span class="badge bg-warning-subtle text-warning" id="lblPhpMaxLimit">سقف: 65</span>
                    </div>
                    <div class="d-flex align-items-baseline gap-2 mt-2">
                        <div class="metric-value text-warning" id="valPhpWorkers">3</div>
                        <small class="text-muted" id="lblPhpCapacity">ظرفیت: 5%</small>
                    </div>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar bg-warning" id="progPhp" style="width: 5%"></div>
                    </div>
                    <small class="text-muted d-block mt-2">پایدار، بدون انباشت صف (No Queue)</small>
                </div>
            </div>

            <!-- MySQL Stats -->
            <div class="col-xl-3 col-md-6">
                <div class="card-custom p-3 metric-card h-100" style="border-right-color: #10b981;">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="metric-label"><i class="ri-hard-drive-2-line me-1 text-success"></i> دیتابیس MariaDB</span>
                        <span class="badge bg-success-subtle text-success" id="lblMysqlHitRate">Hit Rate: 99.8%</span>
                    </div>
                    <div class="d-flex align-items-baseline gap-2 mt-2">
                        <div class="metric-value text-success" id="valMysqlConn">1</div>
                        <small class="text-muted">کانکشن فعال</small>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <small class="text-muted" id="txtMysqlQueries">کل کوئری‌ها: 0</small>
                        <small class="text-muted" id="txtMysqlSlow">کوئری کند: 0</small>
                    </div>
                    <small class="text-muted d-block mt-1" id="txtUptime">آپ‌تایم سیستم: 1d 4h</small>
                </div>
            </div>
        </div>

        <!-- MAIN ROW: Control Deck & Live Charts -->
        <div class="row g-4 mb-4">
            
            <!-- Left Column: Test Configuration Deck -->
            <div class="col-xl-5 col-lg-6">
                <div class="card-custom p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <h5 class="fw-bold mb-0 text-dark">
                            <i class="ri-dashboard-2-line text-primary me-2"></i> تنظیمات پرتاب تست فشار
                        </h5>
                        <span class="badge bg-primary-subtle text-primary">Target: 10,000 Users</span>
                    </div>

                    <!-- Preset Quick Pickers -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">سناریوهای سریع و آماده (Quick Presets):</label>
                        <div class="d-flex flex-wrap gap-2">
                            <div class="preset-badge" data-mode="duration" data-dur="60" data-vus="50" data-scenario="mixed">
                                🟢 نرمال (50 VUs - 1m)
                            </div>
                            <div class="preset-badge" data-mode="duration" data-dur="180" data-vus="150" data-scenario="mixed">
                                🟡 ساعت پیک (150 VUs - 3m)
                            </div>
                            <div class="preset-badge active" data-mode="duration" data-dur="600" data-vus="250" data-scenario="mixed">
                                🔴 استقامت ۱۰ دقیقه‌ای (250 VUs - 10m)
                            </div>
                            <div class="preset-badge" data-mode="duration" data-dur="300" data-vus="500" data-scenario="mixed">
                                🔥 تست تخریب (500 VUs - 5m)
                            </div>
                        </div>
                    </div>

                    <form id="formStressTest">
                        <!-- Mode Selector Tabs -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">نوع اجرای تست:</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="testMode" id="modeDuration" value="duration" checked>
                                <label class="btn btn-outline-primary" for="modeDuration">
                                    <i class="ri-time-line me-1"></i> تست ممتد زمانی (Duration)
                                </label>

                                <input type="radio" class="btn-check" name="testMode" id="modeRequests" value="requests">
                                <label class="btn btn-outline-primary" for="modeRequests">
                                    <i class="ri-crosshair-2-line me-1"></i> تعداد مشخص درخواست (Requests)
                                </label>
                            </div>
                        </div>

                        <!-- Target Scenario / Endpoint -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">سناریو و اندپوینت هدف:</label>
                            <select class="form-select" id="selScenario">
                                <option value="ultimate_10k" selected>🔥 سخت‌ترین حالت ممکن ۱۰,۰۰۰ کاربر (Ultimate DB Storm: سرچ سنگین شهرها، ماشین‌ها و تنظیمات)</option>
                                <option value="mixed">🚀 سناریوی ترکیبی کاربران واقعی (Loads, Cities, Config, FAQs, Vehicles)</option>
                                <option value="public_only">🌐 ترافیک کاملاً عمومی دیتابیس (شهرها، تنظیمات، استان‌ها، ماشین‌ها - بدون توکن)</option>
                                <option value="driver_loads">🚚 فید بارهای رانندگان (/driver/loads - با توکن احراز هویت)</option>
                                <option value="city_search">🔍 جستجوی دیتابیسی شهرها (/meta/cities/search?q=تهران)</option>
                                <option value="app_config">⚙️ تنظیمات و متادیتا اپ (/meta/app-config?target_app_id=1)</option>
                                <option value="vehicle_types">🚛 انواع ناوگان و بارگیرها (/meta/vehicle-types)</option>
                                <option value="provinces">🗺️ لیست استان‌ها و تقسیمات کشوری (/meta/provinces)</option>
                                <option value="faqs">❓ پرسش‌های متداول (/faqs?target_app_id=1)</option>
                                <option value="banners">🖼️ بنرها و اسلایدرها (/banners?target_app_id=1)</option>
                                <option value="health">💚 تست خالص وب‌سرور (/health - بدون کوئری دیتابیس)</option>
                                <option value="custom">✏️ آدرس سفارشی دلخواه...</option>
                            </select>
                        </div>

                        <!-- Traffic Pacing Settings -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">الگوی ترافیک و رفتار پردازنده (Traffic Pacing):</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="trafficPacing" id="pacingRealistic" value="25" checked>
                                <label class="btn btn-outline-success" for="pacingRealistic">
                                    <i class="ri-user-smile-line me-1"></i> ترافیک واقعی ۱۰,۰۰۰ کاربر (CPU متوازن ~۵۰-۶۰٪)
                                </label>

                                <input type="radio" class="btn-check" name="trafficPacing" id="pacingThrottle" value="0">
                                <label class="btn btn-outline-danger" for="pacingThrottle">
                                    <i class="ri-flashlight-line me-1"></i> حداکثر فشار پیوسته (CPU در مرز ۱۰۰٪)
                                </label>
                            </div>
                        </div>

                        <!-- Custom URL Input (hidden by default) -->
                        <div class="mb-3 d-none" id="divCustomUrl">
                            <label class="form-label fw-bold small text-muted">آدرس سفارشی اندپوینت:</label>
                            <input type="text" class="form-control" id="inpCustomUrl" placeholder="/api/v1/...">
                        </div>

                        <!-- Concurrency (VUs) Slider -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label fw-bold small text-muted mb-0">کاربران همزمان (Concurrent VUs):</label>
                                <span class="badge bg-primary fs-6" id="lblVus">250</span>
                            </div>
                            <input type="range" class="form-range" id="inpVus" min="5" max="1000" step="5" value="250">
                            <div class="d-flex justify-content-between small text-muted">
                                <span>5 کاربر</span>
                                <span>250 کاربر</span>
                                <span>500 کاربر</span>
                                <span>1000 کاربر</span>
                            </div>
                        </div>

                        <!-- Duration Settings (when mode=duration) -->
                        <div class="mb-4" id="grpDuration">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label fw-bold small text-muted mb-0">مدت زمان تست ممتد (Duration):</label>
                                <span class="badge bg-dark fs-6" id="lblDurationText">10 دقیقه (600 ثانیه)</span>
                            </div>
                            <select class="form-select" id="selDuration">
                                <option value="30">۳۰ ثانیه (تست سریع)</option>
                                <option value="60">۱ دقیقه</option>
                                <option value="180">۳ دقیقه</option>
                                <option value="300">۵ دقیقه (تست استاندارد)</option>
                                <option value="600" selected>۱۰ دقیقه (تست کامل استقامت - هدف شما)</option>
                                <option value="900">۱۵ دقیقه (تست فشار عمیق)</option>
                            </select>
                        </div>

                        <!-- Request Count Settings (when mode=requests) -->
                        <div class="mb-4 d-none" id="grpRequests">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label fw-bold small text-muted mb-0">تعداد کل درخواست‌ها:</label>
                                <span class="badge bg-dark fs-6" id="lblReqs">10,000</span>
                            </div>
                            <input type="range" class="form-range" id="inpReqs" min="500" max="50000" step="500" value="10000">
                            <div class="d-flex justify-content-between small text-muted">
                                <span>500</span>
                                <span>10,000</span>
                                <span>25,000</span>
                                <span>50,000</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg flex-grow-1 fw-bold" id="btnStartTest">
                                <i class="ri-play-circle-line me-1 align-middle"></i> شروع تست فشار
                            </button>
                            <button type="button" class="btn btn-danger btn-lg d-none fw-bold" id="btnStopTest">
                                <i class="ri-stop-circle-line me-1 align-middle"></i> توقف اضطراری
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column: Live HUD & Real-time Charts -->
            <div class="col-xl-7 col-lg-6">
                <!-- LIVE HUD (Displays during active test) -->
                <div class="hud-panel mb-4 d-none" id="panelLiveHud">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="pulse-live"></span>
                            <h5 class="fw-bold mb-0 text-white">تست در حال اجرا در پس‌زمینه...</h5>
                        </div>
                        <span class="badge bg-danger fs-6 px-3 py-2" id="hudStatusBadge">در حال پردازش</span>
                    </div>

                    <div class="row g-3 text-center mb-3">
                        <div class="col-md-3 col-6">
                            <div class="p-2 border border-secondary rounded bg-dark bg-opacity-50">
                                <small class="text-white-50 d-block">زمان باقی‌مانده</small>
                                <div class="hud-digit" id="hudTimer">00:00</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="p-2 border border-secondary rounded bg-dark bg-opacity-50">
                                <small class="text-white-50 d-block">درخواست‌های ارسالی</small>
                                <div class="hud-digit text-warning" id="hudTotalReqs">0</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="p-2 border border-secondary rounded bg-dark bg-opacity-50">
                                <small class="text-white-50 d-block">توان لحظه‌ای (RPS)</small>
                                <div class="hud-digit text-success" id="hudCurrentRps">0.0</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="p-2 border border-secondary rounded bg-dark bg-opacity-50">
                                <small class="text-white-50 d-block">تاخیر p95 لحظه‌ای</small>
                                <div class="hud-digit text-info" id="hudLatency">0 ms</div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="progress bg-dark" style="height: 12px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" id="hudProgressBar" style="width: 0%"></div>
                    </div>
                    <div class="d-flex justify-content-between small text-white-50 mt-1">
                        <span id="hudScenarioName">سناریو: در حال آماده‌سازی</span>
                        <span id="hudProgressPercent">0%</span>
                    </div>
                </div>

                <!-- DUAL LIVE REAL-TIME CHARTS -->
                <div class="card-custom p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="ri-line-chart-line text-primary me-1"></i> توان پردازش (RPS) و زمان تاخیر (Latency ms) در طول زمان
                        </h6>
                        <small class="text-muted">آپدیت هر ۱ ثانیه</small>
                    </div>
                    <div style="height: 230px;">
                        <canvas id="chartThroughput"></canvas>
                    </div>
                </div>

                <div class="card-custom p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="ri-cpu-line text-danger me-1"></i> فشار لحظه‌ای به منابع سرور (CPU % و RAM %)
                        </h6>
                        <small class="text-muted">مانیتورینگ سخت‌افزار</small>
                    </div>
                    <div style="height: 200px;">
                        <canvas id="chartResources"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- COMPREHENSIVE FINAL REPORT CARD -->
        <div class="card-custom p-4 mb-4 d-none" id="cardReport">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 border-bottom pb-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1">
                        <i class="ri-award-line text-success me-2"></i> کارنامه نهایی و گزارش تحلیلی تست استقامت
                    </h4>
                    <span class="text-muted small" id="repTimestamp">ثبت شده در: -</span>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <span class="badge bg-success fs-5 px-3 py-2" id="repGrade">EXCELLENT</span>
                    <button class="btn btn-outline-secondary btn-sm" id="btnDownloadReportJson">
                        <i class="ri-download-2-line me-1"></i> دانلود JSON
                    </button>
                </div>
            </div>

            <!-- Report Metric Cards -->
            <div class="row g-3 text-center mb-4">
                <div class="col-md-2 col-4">
                    <div class="p-3 bg-light rounded">
                        <small class="text-muted d-block">نرخ پردازش (RPS)</small>
                        <h3 class="fw-bold text-primary mb-0" id="repRps">0.0</h3>
                    </div>
                </div>
                <div class="col-md-2 col-4">
                    <div class="p-3 bg-light rounded">
                        <small class="text-muted d-block">کل درخواست‌ها</small>
                        <h3 class="fw-bold text-dark mb-0" id="repTotalReqs">0</h3>
                    </div>
                </div>
                <div class="col-md-2 col-4">
                    <div class="p-3 bg-light rounded">
                        <small class="text-muted d-block">نرخ موفقیت</small>
                        <h3 class="fw-bold text-success mb-0" id="repSuccessRate">100%</h3>
                    </div>
                </div>
                <div class="col-md-2 col-4">
                    <div class="p-3 bg-light rounded">
                        <small class="text-muted d-block">میانگین تاخیر</small>
                        <h3 class="fw-bold text-info mb-0" id="repAvgLatency">0 ms</h3>
                    </div>
                </div>
                <div class="col-md-2 col-4">
                    <div class="p-3 bg-light rounded">
                        <small class="text-muted d-block">صدک p95</small>
                        <h3 class="fw-bold text-warning mb-0" id="repP95">0 ms</h3>
                    </div>
                </div>
                <div class="col-md-2 col-4">
                    <div class="p-3 bg-light rounded">
                        <small class="text-muted d-block">مدت زمان تست</small>
                        <h3 class="fw-bold text-dark mb-0" id="repDuration">0s</h3>
                    </div>
                </div>
            </div>

            <!-- Deep Breakdown: Percentiles & 10k Capacity Box -->
            <div class="row g-4 mb-3">
                <!-- Percentiles Table -->
                <div class="col-lg-6">
                    <h6 class="fw-bold text-dark mb-3"><i class="ri-bar-chart-grouped-line me-1 text-primary"></i> توزیع آماری صدک‌های زمان تاخیر (Latency Percentiles):</h6>
                    <table class="table table-sm table-bordered percentile-table text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>حداقل (Min)</th>
                                <th>میانه (p50)</th>
                                <th>صدک p75</th>
                                <th>صدک p90</th>
                                <th>صدک p95</th>
                                <th>صدک p99</th>
                                <th>حداکثر (Max)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="pMin">-</td>
                                <td class="fw-bold text-primary" id="p50">-</td>
                                <td id="p75">-</td>
                                <td id="p90">-</td>
                                <td class="fw-bold text-warning" id="p95">-</td>
                                <td class="fw-bold text-danger" id="p99">-</td>
                                <td id="pMax">-</td>
                            </tr>
                        </tbody>
                    </table>
                    <small class="text-muted">انحراف معیار (Jitter): <strong id="repStdDev">0 ms</strong> | کدهای وضعیت: <span id="repStatusCodes">200: 0</span></small>
                </div>

                <!-- 10k Capacity Forecast Box -->
                <div class="col-lg-6">
                    <div class="capacity-box h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold text-success mb-0">
                                    <i class="ri-user-star-line me-1"></i> ارزیابی ظرفیت برای هدف ۱۰,۰۰۰ کاربر:
                                </h6>
                                <span class="badge bg-success">Capacity Analysis</span>
                            </div>
                            <p class="mb-3 text-dark fw-medium" id="repCapacityVerdict">
                                در حال تحلیل...
                            </p>
                        </div>
                        <div class="row g-2 text-center pt-2 border-top border-success-subtle">
                            <div class="col-6">
                                <small class="text-muted d-block">کاربران آنلاین همزمان در پیک:</small>
                                <strong class="text-success fs-5" id="repEstPeakUsers">-</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">کاربران فعال روزانه (DAU):</small>
                                <strong class="text-primary fs-5" id="repEstDailyUsers">-</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recommendations -->
            <div class="alert alert-info mb-0 py-2 mt-3" id="repRecommendation">
                <strong>توصیه فنی:</strong> سرور آموت‌بار در این سناریو به خوبی پاسخ داده است.
            </div>
        </div>

        <!-- HISTORICAL RUNS TABLE -->
        <div class="card-custom p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="ri-history-line text-primary me-2"></i> تاریخچه تست‌های انجام شده
                </h5>
                <div class="d-flex gap-2">
                    <a href="ajax/run_stress_test.php?action=export_csv" class="btn btn-outline-success btn-sm">
                        <i class="ri-file-excel-2-line align-middle me-1"></i> دانلود خروجی CSV / اکسل
                    </a>
                    <button class="btn btn-outline-danger btn-sm" id="btnClearHistory">
                        <i class="ri-delete-bin-line align-middle me-1"></i> پاکسازی تاریخچه
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>زمان ثبت</th>
                            <th>سناریو / اندپوینت</th>
                            <th>کاربران همزمان</th>
                            <th>درخواست‌ها</th>
                            <th>RPS</th>
                            <th>تاخیر p95</th>
                            <th>موفقیت</th>
                            <th>کاربران همزمان پیک</th>
                            <th>رتبه</th>
                        </tr>
                    </thead>
                    <tbody id="tblHistoryBody">
                        <tr><td colspan="9" class="text-center py-4 text-muted">در حال بارگذاری تاریخچه...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- SCRIPT SECTION -->
    <script>
        let latestRunReport = null;
        let isTestRunning = false;
        let activeRunId = null;

        // Chart 1: Throughput (RPS & Latency)
        const ctxThroughput = document.getElementById('chartThroughput').getContext('2d');
        const chartThroughput = new Chart(ctxThroughput, {
            type: 'line',
            data: {
                labels: Array.from({length: 40}, (_, i) => ''),
                datasets: [
                    {
                        label: 'توان پردازش (RPS)',
                        data: Array(40).fill(0),
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        fill: true,
                        tension: 0.3,
                        yAxisID: 'yRps',
                    },
                    {
                        label: 'زمان تاخیر (Latency ms)',
                        data: Array(40).fill(0),
                        borderColor: '#0ea5e9',
                        borderDash: [4, 4],
                        tension: 0.3,
                        yAxisID: 'yLat',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: false,
                scales: {
                    yRps: {
                        type: 'linear',
                        position: 'left',
                        beginAtZero: true,
                        title: { display: true, text: 'RPS (Req/sec)' }
                    },
                    yLat: {
                        type: 'linear',
                        position: 'right',
                        beginAtZero: true,
                        grid: { drawOnChartArea: false },
                        title: { display: true, text: 'تاخیر (ms)' }
                    },
                    x: { display: false }
                }
            }
        });

        // Chart 2: Resources (CPU & RAM)
        const ctxResources = document.getElementById('chartResources').getContext('2d');
        const chartResources = new Chart(ctxResources, {
            type: 'line',
            data: {
                labels: Array.from({length: 40}, (_, i) => ''),
                datasets: [
                    {
                        label: 'مصرف پردازنده (CPU %)',
                        data: Array(40).fill(0),
                        borderColor: '#dc2626',
                        backgroundColor: 'rgba(220, 38, 38, 0.1)',
                        fill: true,
                        tension: 0.3,
                    },
                    {
                        label: 'مصرف حافظه (RAM %)',
                        data: Array(40).fill(0),
                        borderColor: '#0284c7',
                        tension: 0.3,
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
                }
            }
        });

        // Polling Telemetry Function (Every 2 seconds)
        async function fetchTelemetry() {
            try {
                const res = await fetch('ajax/server_telemetry.php');
                if (!res.ok) return;
                const data = await res.json();
                if (data.ok) {
                    // Update Vitals
                    document.getElementById('valCpu').innerText = data.cpu.percent + '%';
                    document.getElementById('progCpu').style.width = data.cpu.percent + '%';
                    document.getElementById('lblCpuCores').innerText = data.cpu.cores + ' Cores';
                    document.getElementById('valLoadAvg').innerText = 'Load: ' + data.cpu.load_avg.join(', ');
                    document.getElementById('txtCpuModel').innerText = data.cpu.model;

                    document.getElementById('valRamUsed').innerText = data.memory.used_mb + ' MB';
                    document.getElementById('valRamTotal').innerText = 'از ' + data.memory.total_mb + ' MB';
                    document.getElementById('badgeRamPercent').innerText = data.memory.percent + '%';
                    document.getElementById('progRam').style.width = data.memory.percent + '%';
                    document.getElementById('txtRamCached').innerText = `آزاد: ${data.memory.free_mb} MB | کش: ${data.memory.cached_mb} MB`;

                    document.getElementById('valPhpWorkers').innerText = data.php_fpm.workers;
                    document.getElementById('lblPhpMaxLimit').innerText = 'سقف: ' + data.php_fpm.max_limit;
                    document.getElementById('lblPhpCapacity').innerText = 'ظرفیت: ' + data.php_fpm.capacity_percent + '%';
                    document.getElementById('progPhp').style.width = data.php_fpm.capacity_percent + '%';

                    document.getElementById('valMysqlConn').innerText = data.mysql.connected;
                    document.getElementById('lblMysqlHitRate').innerText = 'Hit Rate: ' + data.mysql.buffer_hit_rate + '%';
                    document.getElementById('txtMysqlQueries').innerText = 'کل کوئری‌ها: ' + Number(data.mysql.queries).toLocaleString();
                    document.getElementById('txtMysqlSlow').innerText = 'کوئری کند: ' + data.mysql.slow_queries;
                    document.getElementById('txtUptime').innerText = 'آپ‌تایم سیستم: ' + data.server.uptime;
                    document.getElementById('srvHostname').innerText = data.server.hostname;

                    // Update Resources Chart
                    chartResources.data.datasets[0].data.shift();
                    chartResources.data.datasets[0].data.push(data.cpu.percent);
                    chartResources.data.datasets[1].data.shift();
                    chartResources.data.datasets[1].data.push(data.memory.percent);
                    chartResources.update();
                }
            } catch (e) {
                console.warn("Telemetry fetch error:", e);
            }
        }
        setInterval(fetchTelemetry, 2000);
        fetchTelemetry();

        // Mode switch handler (Duration vs Requests)
        document.querySelectorAll('input[name="testMode"]').forEach(radio => {
            radio.addEventListener('change', e => {
                if (e.target.value === 'duration') {
                    document.getElementById('grpDuration').classList.remove('d-none');
                    document.getElementById('grpRequests').classList.add('d-none');
                } else {
                    document.getElementById('grpDuration').classList.add('d-none');
                    document.getElementById('grpRequests').classList.remove('d-none');
                }
            });
        });

        // Scenario switch handler (Custom URL visibility)
        document.getElementById('selScenario').addEventListener('change', e => {
            const divCustom = document.getElementById('divCustomUrl');
            if (e.target.value === 'custom') {
                divCustom.classList.remove('d-none');
            } else {
                divCustom.classList.add('d-none');
            }
        });

        // Sliders & Selects binding
        document.getElementById('inpVus').addEventListener('input', e => {
            document.getElementById('lblVus').innerText = e.target.value;
        });
        document.getElementById('inpReqs').addEventListener('input', e => {
            document.getElementById('lblReqs').innerText = Number(e.target.value).toLocaleString();
        });
        document.getElementById('selDuration').addEventListener('change', e => {
            const sec = parseInt(e.target.value);
            const min = Math.round(sec / 60);
            document.getElementById('lblDurationText').innerText = min >= 1 ? `${min} دقیقه (${sec} ثانیه)` : `${sec} ثانیه`;
        });

        // Presets Click Handler
        document.querySelectorAll('.preset-badge').forEach(badge => {
            badge.addEventListener('click', () => {
                document.querySelectorAll('.preset-badge').forEach(b => b.classList.remove('active'));
                badge.classList.add('active');

                const vus = badge.dataset.vus;
                const dur = badge.dataset.dur;
                const scenario = badge.dataset.scenario;

                document.getElementById('inpVus').value = vus;
                document.getElementById('lblVus').innerText = vus;

                document.getElementById('modeDuration').checked = true;
                document.getElementById('grpDuration').classList.remove('d-none');
                document.getElementById('grpRequests').classList.add('d-none');

                document.getElementById('selDuration').value = dur;
                const min = Math.round(dur / 60);
                document.getElementById('lblDurationText').innerText = min >= 1 ? `${min} دقیقه (${dur} ثانیه)` : `${dur} ثانیه`;

                document.getElementById('selScenario').value = scenario;
                document.getElementById('divCustomUrl').classList.add('d-none');
            });
        });

        // Form Submit: Start Stress Test
        document.getElementById('formStressTest').addEventListener('submit', async e => {
            e.preventDefault();
            const btnStart = document.getElementById('btnStartTest');
            const btnStop = document.getElementById('btnStopTest');

            const mode = document.querySelector('input[name="testMode"]:checked').value;
            const scenario = document.getElementById('selScenario').value;
            const customUrl = document.getElementById('inpCustomUrl').value;
            const vus = document.getElementById('inpVus').value;
            const duration = document.getElementById('selDuration').value;
            const requests = document.getElementById('inpReqs').value;
            const pacing = document.querySelector('input[name="trafficPacing"]:checked')?.value || '25';

            const formData = new FormData();
            formData.append('action', 'start');
            formData.append('mode', mode);
            formData.append('scenario', scenario);
            formData.append('custom_url', customUrl);
            formData.append('vus', vus);
            formData.append('duration', duration);
            formData.append('requests', requests);
            formData.append('pacing', pacing);

            btnStart.disabled = true;
            btnStart.classList.add('d-none');
            btnStop.classList.remove('d-none');
            document.getElementById('panelLiveHud').classList.remove('d-none');

            // Reset HUD fields for new test
            document.getElementById('hudTimer').innerText = '00:00';
            document.getElementById('hudTotalReqs').innerText = '0';
            document.getElementById('hudCurrentRps').innerText = '0.0';
            document.getElementById('hudLatency').innerText = '0 ms';
            document.getElementById('hudProgressBar').style.width = '0%';
            document.getElementById('hudProgressPercent').innerText = '0%';
            document.getElementById('hudScenarioName').innerText = 'سناریو: ' + scenario;
            document.getElementById('hudStatusBadge').innerText = 'در حال راه‌اندازی...';

            try {
                const res = await fetch('ajax/run_stress_test.php', { method: 'POST', body: formData });
                const data = await res.json();
                if (!data.ok) {
                    alert(data.message || 'خطا در اجرای تست');
                    btnStart.disabled = false;
                    btnStart.classList.remove('d-none');
                    btnStop.classList.add('d-none');
                    document.getElementById('panelLiveHud').classList.add('d-none');
                } else {
                    activeRunId = data.run_id;
                    isTestRunning = true;
                }
            } catch (err) {
                console.error(err);
                alert('خطا در ارسال درخواست به سرور');
                btnStart.disabled = false;
                btnStart.classList.remove('d-none');
                btnStop.classList.add('d-none');
                document.getElementById('panelLiveHud').classList.add('d-none');
            }
        });

        // Emergency Stop Handler
        document.getElementById('btnStopTest').addEventListener('click', async () => {
            if (confirm('آیا از توقف اضطراری تست اطمینان دارید؟')) {
                const formData = new FormData();
                formData.append('action', 'stop');
                await fetch('ajax/run_stress_test.php', { method: 'POST', body: formData });
            }
        });

        // Polling Test Status (Every 1 second)
        async function pollTestStatus() {
            try {
                const res = await fetch('ajax/run_stress_test.php?action=status');
                if (!res.ok) return;
                const resData = await res.json();
                if (!resData.ok || !resData.data) return;

                const d = resData.data;

                // Ignore stale data from previous test run
                if (isTestRunning && activeRunId && d.run_id && d.run_id !== activeRunId) {
                    return;
                }

                const isRunning = (d.status === 'running' || d.status === 'stopping');

                if (isRunning) {
                    activeRunId = d.run_id;
                    isTestRunning = true;
                    document.getElementById('panelLiveHud').classList.remove('d-none');
                    document.getElementById('btnStartTest').classList.add('d-none');
                    document.getElementById('btnStopTest').classList.remove('d-none');

                    // Format remaining seconds
                    const remSec = d.remaining_sec || 0;
                    const remMin = Math.floor(remSec / 60);
                    const remS = remSec % 60;
                    document.getElementById('hudTimer').innerText = `${String(remMin).padStart(2, '0')}:${String(remS).padStart(2, '0')}`;
                    document.getElementById('hudTotalReqs').innerText = Number(d.total_requests).toLocaleString();
                    document.getElementById('hudCurrentRps').innerText = d.current_rps;
                    document.getElementById('hudLatency').innerText = d.latency_p95_ms + ' ms';

                    document.getElementById('hudProgressBar').style.width = d.progress_percent + '%';
                    document.getElementById('hudProgressPercent').innerText = d.progress_percent + '%';
                    document.getElementById('hudScenarioName').innerText = 'سناریو: ' + (d.scenario_name || d.scenario);
                    document.getElementById('hudStatusBadge').innerText = d.status === 'stopping' ? 'در حال توقف...' : 'در حال اجرا';

                    // Update Throughput Chart
                    chartThroughput.data.datasets[0].data.shift();
                    chartThroughput.data.datasets[0].data.push(d.current_rps);
                    chartThroughput.data.datasets[1].data.shift();
                    chartThroughput.data.datasets[1].data.push(d.latency_avg_ms);
                    chartThroughput.update();

                } else if (isTestRunning && d.status === 'completed' && (!activeRunId || d.run_id === activeRunId)) {
                    // Test finished legitimately!
                    isTestRunning = false;
                    activeRunId = null;
                    document.getElementById('btnStartTest').disabled = false;
                    document.getElementById('btnStartTest').classList.remove('d-none');
                    document.getElementById('btnStopTest').classList.add('d-none');
                    document.getElementById('panelLiveHud').classList.add('d-none');

                    // Render Final Report
                    renderReportCard(d);
                    loadHistory();
                }
            } catch (e) {
                console.warn(e);
            }
        }
        setInterval(pollTestStatus, 1000);

        // Render Report Card
        function renderReportCard(report) {
            latestRunReport = report;
            const card = document.getElementById('cardReport');
            card.classList.remove('d-none');

            document.getElementById('repTimestamp').innerText = `زمان ثبت: ${report.timestamp} | سناریو: ${report.scenario_name || report.scenario}`;
            document.getElementById('repRps').innerText = report.rps;
            document.getElementById('repTotalReqs').innerText = Number(report.total_requests).toLocaleString();
            document.getElementById('repSuccessRate').innerText = report.success_rate + '%';
            document.getElementById('repAvgLatency').innerText = report.latency_avg_ms + ' ms';
            document.getElementById('repP95').innerText = report.latency_p95_ms + ' ms';
            document.getElementById('repDuration').innerText = report.duration_seconds + 's';

            // Percentiles Table
            document.getElementById('pMin').innerText = report.latency_min_ms + ' ms';
            document.getElementById('p50').innerText = report.latency_p50_ms + ' ms';
            document.getElementById('p75').innerText = report.latency_p75_ms + ' ms';
            document.getElementById('p90').innerText = report.latency_p90_ms + ' ms';
            document.getElementById('p95').innerText = report.latency_p95_ms + ' ms';
            document.getElementById('p99').innerText = report.latency_p99_ms + ' ms';
            document.getElementById('pMax').innerText = report.latency_max_ms + ' ms';
            document.getElementById('repStdDev').innerText = (report.latency_std_dev || 0) + ' ms';

            // Status codes
            const codesStr = Object.entries(report.status_codes || {}).map(([k, v]) => `${k}: ${v}`).join(' | ');
            document.getElementById('repStatusCodes').innerText = codesStr;

            // Grade badge
            const repGrade = document.getElementById('repGrade');
            repGrade.innerText = report.grade;
            repGrade.className = 'badge fs-5 px-3 py-2 ' + (
                report.grade === 'EXCELLENT' ? 'bg-success' :
                report.grade === 'GOOD' ? 'bg-info' :
                report.grade === 'WARNING' ? 'bg-warning text-dark' : 'bg-danger'
            );

            // Capacity Sizing Box
            if (report.capacity_sizing) {
                document.getElementById('repCapacityVerdict').innerText = report.capacity_sizing.verdict;
                document.getElementById('repEstPeakUsers').innerText = Number(report.capacity_sizing.estimated_peak_concurrent_users).toLocaleString() + ' کاربر';
                document.getElementById('repEstDailyUsers').innerText = Number(report.capacity_sizing.estimated_daily_active_users).toLocaleString() + ' کاربر';
            }

            document.getElementById('repRecommendation').innerHTML = `<strong>توصیه فنی:</strong> ${report.recommendation}`;
            card.scrollIntoView({ behavior: 'smooth' });
        }

        // Download JSON report
        document.getElementById('btnDownloadReportJson').addEventListener('click', () => {
            if (!latestRunReport) return;
            const blob = new Blob([JSON.stringify(latestRunReport, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `amutbar_benchmark_${latestRunReport.id || 'report'}.json`;
            a.click();
        });

        // Load History Function
        async function loadHistory() {
            try {
                const res = await fetch('ajax/run_stress_test.php?action=history');
                if (!res.ok) return;
                const data = await res.json();
                const tbody = document.getElementById('tblHistoryBody');
                if (data.ok && data.history.length > 0) {
                    tbody.innerHTML = data.history.map(item => {
                        let badgeClass = 'bg-success';
                        if (item.grade === 'GOOD') badgeClass = 'bg-info';
                        if (item.grade === 'WARNING') badgeClass = 'bg-warning text-dark';
                        if (item.grade === 'CRITICAL') badgeClass = 'bg-danger';

                        const peakUsers = item.capacity_sizing ? Number(item.capacity_sizing.estimated_peak_concurrent_users).toLocaleString() : '-';

                        return `
                            <tr>
                                <td><small class="text-muted">${item.timestamp}</small></td>
                                <td><span class="fw-bold">${item.scenario_name || item.scenario || item.endpoint}</span></td>
                                <td class="fw-bold text-center">${item.concurrency_vus}</td>
                                <td>${Number(item.total_requests).toLocaleString()}</td>
                                <td class="fw-bold text-primary">${item.rps}</td>
                                <td class="fw-bold text-warning">${item.latency_p95_ms} ms</td>
                                <td class="fw-bold text-success">${item.success_rate}%</td>
                                <td class="fw-bold text-info">${peakUsers}</td>
                                <td><span class="badge ${badgeClass}">${item.grade}</span></td>
                            </tr>
                        `;
                    }).join('');
                } else {
                    tbody.innerHTML = '<tr><td colspan="9" class="text-muted text-center py-4">هنوز تستی ثبت نشده است. اولین تست را اجرا فرمایید!</td></tr>';
                }
            } catch (e) {
                console.error(e);
            }
        }
        loadHistory();

        // Clear History
        document.getElementById('btnClearHistory').addEventListener('click', async () => {
            if (confirm('آیا از پاکسازی تمام تاریخچه تست‌ها اطمینان دارید؟')) {
                await fetch('ajax/run_stress_test.php?action=clear_history');
                loadHistory();
            }
        });
    </script>
</body>
</html>
