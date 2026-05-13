<?php

declare(strict_types=1);

// -----------------------------
// Auth logging (optimized)
// -----------------------------
// Raw events are kept with a short retention window to avoid DB bloat.
// Aggregated daily stats are kept long-term for analytics.
//
// Event types:
//  1: login_success (admin/password)
//  2: logout
//  3: remember_login (admin cookie)
//  4: otp_login_success (app)
//  5: login_failed

function auth_log_retention_days(): int
{
    $d = (int)env('AUTH_LOG_RETENTION_DAYS', '60');
    return $d > 0 ? $d : 60;
}

function auth_log_ip_bin(): ?string
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
    if (!$ip) return null;
    $bin = @inet_pton($ip);
    return $bin !== false ? $bin : null;
}

function auth_log_ua_hash(): ?string
{
    $ua = trim((string)($_SERVER['HTTP_USER_AGENT'] ?? ''));
    if ($ua === '') return null;
    // 32 bytes binary (sha256)
    return hex2bin(hash('sha256', $ua));
}

function auth_log_event(int $userId, int $userType, int $eventType, array $payload = []): void
{
    // Best-effort logging: do not break auth flow if table is missing.
    try {
        $pdo = db();

        // Raw event
        $st = $pdo->prepare(
            "INSERT INTO auth_events (user_id, user_type, event_type, ip_address, user_agent_hash, payload_json, created_at)
             VALUES (?, ?, ?, ?, ?, ?, NOW(3))"
        );

        $st->execute([
            $userId,
            $userType,
            $eventType,
            auth_log_ip_bin(),
            auth_log_ua_hash(),
            !empty($payload) ? json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null,
        ]);

        // Daily aggregate
        $st2 = $pdo->prepare(
            "INSERT INTO auth_daily_stats (stat_date, user_type, event_type, cnt)
             VALUES (CURDATE(), ?, ?, 1)
             ON DUPLICATE KEY UPDATE cnt = cnt + 1"
        );
        $st2->execute([$userType, $eventType]);

        // Opportunistic cleanup (cheap)
        auth_log_cleanup(2000);
    } catch (Throwable $e) {
        // ignore
    }
}

function auth_log_cleanup(int $limit = 2000): void
{
    try {
        $pdo = db();
        $days = auth_log_retention_days();
        $limit = max(100, min(5000, $limit));
        // MySQL does not allow binding LIMIT in all configurations; cast to int.
        $pdo->exec(
            "DELETE FROM auth_events
             WHERE created_at < (NOW() - INTERVAL {$days} DAY)
             LIMIT {$limit}"
        );
    } catch (Throwable $e) {
        // ignore
    }
}

function auth_mark_last_login(int $userId): void
{
    try {
        $pdo = db();
        $pdo->prepare("UPDATE users SET last_login_at = NOW(3) WHERE id = ? LIMIT 1")
            ->execute([$userId]);
    } catch (Throwable $e) {
        // ignore
    }
}
