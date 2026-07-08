<?php

declare(strict_types=1);

// ------------------------------------------------------------
// Driver in-app activity logging
// ------------------------------------------------------------
// این لاگ برای رهگیری جزئی رفتار راننده داخل اپ است؛ با auth_events فرق دارد.
// auth_events فقط ورود/خروج را نگه می‌دارد، این جدول صفحه، اکشن، بار، تیکت و متادیتا را ذخیره می‌کند.

function driver_activity_retention_days(): int
{
    $d = (int)env('DRIVER_ACTIVITY_RETENTION_DAYS', '180');
    return $d > 0 ? $d : 180;
}

function driver_activity_ensure_schema(): void
{
    static $done = false;
    if ($done) return;

    $pdo = db();
    $pdo->exec("CREATE TABLE IF NOT EXISTS driver_activity_logs (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id BIGINT UNSIGNED NOT NULL,
        driver_id BIGINT UNSIGNED DEFAULT NULL,
        event_key VARCHAR(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
        event_title VARCHAR(160) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
        screen_key VARCHAR(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        screen_title VARCHAR(160) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        entity_type VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        entity_id BIGINT UNSIGNED DEFAULT NULL,
        load_id BIGINT UNSIGNED DEFAULT NULL,
        company_id BIGINT UNSIGNED DEFAULT NULL,
        ticket_id BIGINT UNSIGNED DEFAULT NULL,
        client_event_id VARCHAR(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        client_platform TINYINT UNSIGNED DEFAULT NULL COMMENT '1=android,2=ios,3=web',
        client_version_code INT UNSIGNED DEFAULT NULL,
        app_version_name VARCHAR(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        device_id VARCHAR(96) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        ip_address VARBINARY(16) DEFAULT NULL,
        user_agent VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        payload_json JSON DEFAULT NULL,
        occurred_at DATETIME(3) DEFAULT NULL,
        created_at DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
        PRIMARY KEY (id),
        KEY ix_driver_activity_user_time (user_id, created_at),
        KEY ix_driver_activity_driver_time (driver_id, created_at),
        KEY ix_driver_activity_event_time (event_key, created_at),
        KEY ix_driver_activity_load_time (load_id, created_at),
        KEY ix_driver_activity_ticket_time (ticket_id, created_at),
        KEY ix_driver_activity_entity (entity_type, entity_id, created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $done = true;
}

function driver_activity_ip_bin(): ?string
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
    if (!$ip) return null;
    $bin = @inet_pton($ip);
    return $bin !== false ? $bin : null;
}

function driver_activity_str(array $src, string $key, int $max = 160): ?string
{
    $v = trim((string)($src[$key] ?? ''));
    if ($v === '') return null;
    if (function_exists('mb_substr')) return mb_substr($v, 0, $max, 'UTF-8');
    return substr($v, 0, $max);
}

function driver_activity_int(array $src, string $key): ?int
{
    if (!isset($src[$key]) || $src[$key] === '') return null;
    $v = (int)$src[$key];
    return $v > 0 ? $v : null;
}

function driver_activity_platform(?string $p): ?int
{
    $p = strtolower(trim((string)$p));
    if ($p === '') return null;
    return match ($p) {
        'android' => 1,
        'ios' => 2,
        'web' => 3,
        default => null,
    };
}

function driver_activity_event_title(string $eventKey, ?string $fallback = null): string
{
    $labels = [
        'app_open' => 'باز شدن اپلیکیشن',
        'screen_view' => 'مشاهده صفحه',
        'tab_change' => 'تغییر تب پایین اپ',
        'dashboard_view' => 'مشاهده داشبورد',
        'nearby_loads_view' => 'مشاهده بارهای اطراف من',
        'search_page_view' => 'ورود به صفحه جستجوی بار',
        'search_filter_set' => 'انتخاب فیلتر جستجوی بار',
        'search_loads' => 'جستجوی بار',
        'load_card_open' => 'باز کردن کارت بار',
        'load_detail_view' => 'مشاهده جزئیات بار',
        'call_button_tap' => 'کلیک روی برقراری تماس',
        'map_route_open' => 'باز کردن مسیر روی نقشه',
        'profile_view' => 'مشاهده پروفایل',
        'identity_info_view' => 'مشاهده مشخصات هویتی',
        'vehicle_info_view' => 'مشاهده مشخصات خودرو',
        'vehicle_info_update' => 'ویرایش مشخصات خودرو',
        'support_view' => 'مشاهده پشتیبانی',
        'support_contact_tap' => 'کلیک روی راه ارتباطی پشتیبانی',
        'ticket_create' => 'ثبت تیکت جدید',
        'ticket_view' => 'مشاهده گفتگوی تیکت',
        'ticket_reply' => 'ارسال پاسخ تیکت',
        'logout' => 'خروج از حساب',
    ];
    $fallback = trim((string)$fallback);
    return $labels[$eventKey] ?? ($fallback !== '' ? $fallback : $eventKey);
}

function driver_activity_log(array $user, array $event, array $context = []): void
{
    try {
        driver_activity_ensure_schema();
        $pdo = db();

        $userId = (int)($user['id'] ?? 0);
        if ($userId <= 0) return;

        $eventKey = driver_activity_str($event, 'event_key', 80) ?? driver_activity_str($event, 'event', 80) ?? '';
        $eventKey = preg_replace('/[^a-zA-Z0-9_.:-]+/', '_', $eventKey);
        $eventKey = trim($eventKey, '_');
        if ($eventKey === '') return;

        $stDr = $pdo->prepare('SELECT id FROM drivers WHERE user_id=? AND deleted_at IS NULL LIMIT 1');
        $stDr->execute([$userId]);
        $driverId = (int)($stDr->fetchColumn() ?: 0);

        $eventTitle = driver_activity_event_title($eventKey, driver_activity_str($event, 'event_title', 160));
        $screenKey = driver_activity_str($event, 'screen_key', 80);
        $screenTitle = driver_activity_str($event, 'screen_title', 160);
        $entityType = driver_activity_str($event, 'entity_type', 50);
        $entityId = driver_activity_int($event, 'entity_id');
        $loadId = driver_activity_int($event, 'load_id') ?? (($entityType === 'load') ? $entityId : null);
        $companyId = driver_activity_int($event, 'company_id') ?? (($entityType === 'company') ? $entityId : null);
        $ticketId = driver_activity_int($event, 'ticket_id') ?? (($entityType === 'ticket') ? $entityId : null);

        $platform = driver_activity_platform(driver_activity_str($event, 'client_platform', 32))
            ?? driver_activity_platform(driver_activity_str($context, 'client_platform', 32));
        $versionCode = driver_activity_int($event, 'client_version_code')
            ?? driver_activity_int($event, 'client_version')
            ?? driver_activity_int($context, 'client_version_code')
            ?? driver_activity_int($context, 'client_version');
        $appVersionName = driver_activity_str($event, 'app_version_name', 32)
            ?? driver_activity_str($context, 'app_version_name', 32);
        $deviceId = driver_activity_str($event, 'device_id', 96)
            ?? driver_activity_str($context, 'device_id', 96);
        $clientEventId = driver_activity_str($event, 'client_event_id', 80);

        $occurredAt = null;
        $rawOccurred = driver_activity_str($event, 'occurred_at', 40);
        if ($rawOccurred) {
            $ts = strtotime($rawOccurred);
            if ($ts !== false) $occurredAt = date('Y-m-d H:i:s.v', $ts);
        }

        $payload = $event['payload'] ?? $event['metadata'] ?? null;
        if (!is_array($payload)) {
            $payload = [];
        }
        if (!empty($context)) {
            $payload['_client'] = array_filter([
                'platform' => $context['client_platform'] ?? null,
                'version_code' => $context['client_version_code'] ?? ($context['client_version'] ?? null),
                'version_name' => $context['app_version_name'] ?? null,
                'device_id' => $context['device_id'] ?? null,
            ], static fn($v) => $v !== null && $v !== '');
        }

        $payloadJson = !empty($payload)
            ? json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            : null;

        $st = $pdo->prepare("INSERT INTO driver_activity_logs
            (user_id, driver_id, event_key, event_title, screen_key, screen_title, entity_type, entity_id, load_id, company_id, ticket_id, client_event_id, client_platform, client_version_code, app_version_name, device_id, ip_address, user_agent, payload_json, occurred_at, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(3))");
        $st->execute([
            $userId,
            $driverId > 0 ? $driverId : null,
            $eventKey,
            $eventTitle,
            $screenKey,
            $screenTitle,
            $entityType,
            $entityId,
            $loadId,
            $companyId,
            $ticketId,
            $clientEventId,
            $platform,
            $versionCode,
            $appVersionName,
            $deviceId,
            driver_activity_ip_bin(),
            substr((string)($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255),
            $payloadJson,
            $occurredAt,
        ]);
    } catch (Throwable $e) {
        // لاگ فعالیت نباید تجربه کاربر یا API اصلی را خراب کند.
    }
}

function driver_activity_cleanup(int $limit = 2000): void
{
    try {
        driver_activity_ensure_schema();
        $days = driver_activity_retention_days();
        $limit = max(100, min(5000, $limit));
        db()->exec("DELETE FROM driver_activity_logs WHERE created_at < (NOW() - INTERVAL {$days} DAY) LIMIT {$limit}");
    } catch (Throwable $e) {
        // ignore
    }
}
