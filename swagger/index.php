<?php
declare(strict_types=1);

// تنظیمات امنیتی پایه و تشخیص آدرس فعلی
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$baseDir = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'] ?? '/swagger/index.php')), '/\\');
$apiBaseUrl = $baseDir . '/api/v1';
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مستندات تعاملی API آموت‌بار | Swagger UI</title>
    <link rel="icon" type="image/png" href="../assets/images/brand-logos/favicon.ico">
    <link rel="stylesheet" href="swagger-ui.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vazirmatn@33.0.3/Vazirmatn-font-face.css">
    <style>
        :root {
            --amut-primary: #1e3a8a;
            --amut-secondary: #0ea5e9;
            --amut-dark: #0f172a;
            --amut-light: #f8fafc;
        }
        body {
            margin: 0;
            padding: 0;
            background-color: var(--amut-light);
            font-family: 'Vazirmatn', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #334155;
            direction: ltr; /* Swagger UI renders best with LTR layout while maintaining Persian text */
        }
        .custom-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
            color: #fff;
            padding: 1.25rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            direction: rtl;
        }
        .header-brand {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .header-brand h1 {
            margin: 0;
            font-size: 1.35rem;
            font-weight: 700;
        }
        .header-brand p {
            margin: 0;
            font-size: 0.85rem;
            color: #94a3b8;
        }
        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .btn-header {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid rgba(255,255,255,0.2);
            color: #fff;
            background: rgba(255,255,255,0.1);
        }
        .btn-header:hover {
            background: rgba(255,255,255,0.25);
            transform: translateY(-1px);
        }
        .btn-header-primary {
            background: var(--amut-secondary);
            border-color: var(--amut-secondary);
        }
        .btn-header-primary:hover {
            background: #0284c7;
        }
        #swagger-ui {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1.5rem 1rem 4rem;
        }
        .swagger-ui .topbar { display: none; }
        .swagger-ui .info { margin: 1.5rem 0 2rem; }
        .swagger-ui .info .title {
            color: var(--amut-dark);
            font-size: 2rem;
            font-weight: 800;
            font-family: 'Vazirmatn', sans-serif;
        }
        .swagger-ui .btn.authorize {
            background-color: #10b981;
            border-color: #10b981;
            color: #fff;
            font-weight: 700;
            border-radius: 6px;
        }
        .swagger-ui .btn.authorize svg { fill: #fff; }
        .swagger-ui .opblock.opblock-post { border-color: #10b981; background: rgba(16,185,129,.05); }
        .swagger-ui .opblock.opblock-get { border-color: #0ea5e9; background: rgba(14,165,233,.05); }
        .swagger-ui .opblock-summary-description { font-family: 'Vazirmatn', sans-serif; }
    </style>
</head>
<body>

    <header class="custom-header">
        <div class="header-brand">
            <div>
                <h1>مستندات وب‌سرویس‌های آموت‌بار (Amutbar REST API)</h1>
                <p>محیط تعاملی تست و فراخوانی APIها برای توسعه‌دهندگان اندروید، iOS و وب</p>
            </div>
        </div>
        <div class="header-actions">
            <a href="openapi.json" target="_blank" class="btn-header" download="amutbar-openapi.json">
                <span>دریافت فایل OpenAPI JSON</span>
            </a>
            <a href="../dashboard.php" class="btn-header btn-header-primary">
                <span>بازگشت به پنل ادمین</span>
            </a>
        </div>
    </header>

    <div id="swagger-ui"></div>

    <script src="swagger-ui-bundle.min.js"></script>
    <script src="swagger-ui-standalone-preset.min.js"></script>
    <script>
        window.onload = function() {
            const currentApiUrl = window.location.pathname.replace(/\/swagger\/?.*$/, '') + '/api/v1';

            const ui = SwaggerUIBundle({
                url: "openapi.json",
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "BaseLayout",
                filter: true,
                displayRequestDuration: true,
                docExpansion: "list",
                defaultModelsExpandDepth: 1,
                defaultModelExpandDepth: 1,
                persistAuthorization: true,
                tryItOutEnabled: true,
                onComplete: function() {
                    console.log("Swagger UI loaded successfully for Amutbar API");
                }
            });

            window.ui = ui;
        };
    </script>
</body>
</html>
