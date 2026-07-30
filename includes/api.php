<?php

declare(strict_types=1);

// -----------------------------
// API Auth (JWT Access + Refresh)
// -----------------------------
// - access_token: JWT کوتاه‌عمر (پیشنهادی: 15 دقیقه)
// - refresh_token: JWT بلندعمر (پیشنهادی: 30-90 روز) + ذخیره هش jti در دیتابیس
// - Rotation: هر بار refresh انجام شد، refresh قبلی revoke و توکن جدید صادر می‌شود
//
// جداول:
// - jwt_refresh_tokens
// فیلد کاربران:
// - users.jwt_token_version (برای logout-all / ابطال سراسری)

require_once __DIR__ . '/jwt.php';

function api_bearer_token(): ?string
{
    // بعضی سرورها Authorization را داخل HTTP_AUTHORIZATION نمی‌گذارند
    // و ممکن است در REDIRECT_HTTP_AUTHORIZATION یا getallheaders باشد.
    $h = (string)(
        $_SERVER['HTTP_AUTHORIZATION']
        ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
        ?? $_SERVER['Authorization']
        ?? ''
    );

    if ($h === '' && function_exists('getallheaders')) {
        $headers = getallheaders();
        if (is_array($headers)) {
            // کلید می‌تواند با حروف متفاوت باشد
            foreach ($headers as $k => $v) {
                if (strcasecmp((string)$k, 'Authorization') === 0) {
                    $h = (string)$v;
                    break;
                }
            }
        }
    }

    $h = trim($h);
    if ($h === '') return null;
    if (stripos($h, 'Bearer ') !== 0) return null;
    $t = trim(substr($h, 7));
    return $t !== '' ? $t : null;
}

function api_platform_from_string(?string $p): ?int
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

function api_refresh_jti_hash(string $jti): string
{
    $pepper = (string)env('JWT_REFRESH_PEPPER', env('APP_KEY', env('CSRF_SECRET', '')));
    return hash('sha256', $jti . '|' . $pepper);
}

function api_access_ttl_sec(): int
{
    $v = function_exists('api_jwt_access_ttl_sec') ? api_jwt_access_ttl_sec() : (int)env('JWT_ACCESS_TTL_SEC', 900);
    return ($v < 60 || $v > 86400) ? 900 : $v;
}

function api_refresh_ttl_days(?int $override = null): int
{
    $v = $override !== null ? (int)$override : (function_exists('api_jwt_refresh_ttl_days') ? api_jwt_refresh_ttl_days() : (int)env('JWT_REFRESH_TTL_DAYS', 30));
    return ($v < 7 || $v > 365) ? 30 : $v;
}

/**
 * صدور توکن‌ها (Access + Refresh)
 * ttl_days پارامتر قدیمی پروژه بوده؛ اینجا به عنوان TTL رفرش استفاده می‌شود.
 */
function api_issue_token(int $user_id, ?string $device_id = null, ?int $platform = null, ?int $ttl_days = 30): array
{
    $pdo = db();

    // خواندن token_version برای ابطال سراسری
    $st = $pdo->prepare("SELECT id, user_type, status, jwt_token_version FROM users WHERE id=? AND deleted_at IS NULL LIMIT 1");
    $st->execute([$user_id]);
    $u = $st->fetch();
    if (!$u) return ['ok' => false, 'message' => 'user_not_found'];
    if ((int)$u['status'] !== 1) return ['ok' => false, 'message' => 'user_inactive'];

    $tv = (int)($u['jwt_token_version'] ?? 1);
    if ($tv <= 0) $tv = 1;

    $accessTtl = api_access_ttl_sec();
    $refreshDays = api_refresh_ttl_days($ttl_days);
    $refreshTtl = $refreshDays * 86400;

    // access
    $access = jwt_encode([
        'sub' => (int)$user_id,
        'ut' => (int)$u['user_type'],
        'tv' => $tv,
        'did' => $device_id,
        'plt' => $platform,
    ], $accessTtl, 'access');

    // refresh
    $refresh = jwt_encode([
        'sub' => (int)$user_id,
        'ut' => (int)$u['user_type'],
        'tv' => $tv,
        'did' => $device_id,
        'plt' => $platform,
    ], $refreshTtl, 'refresh');

    // استخراج jti رفرش برای ذخیره در DB
    $dec = jwt_decode($refresh);
    if (!$dec['ok']) return ['ok' => false, 'message' => 'token_issue_failed'];
    $payload = $dec['payload'];
    $jti = (string)($payload['jti'] ?? '');
    $exp = (int)($payload['exp'] ?? 0);
    if ($jti === '' || $exp <= 0) return ['ok' => false, 'message' => 'token_issue_failed'];

    $jtiHash = api_refresh_jti_hash($jti);
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
    $ua = substr((string)($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255);

    $expiresAt = (new DateTimeImmutable('@' . $exp))->setTimezone(new DateTimeZone('Asia/Tehran'))->format('Y-m-d H:i:s');

    $ins = $pdo->prepare(
        "INSERT INTO jwt_refresh_tokens (user_id, jti_hash, device_id, platform, ip_address, user_agent, expires_at, created_at)
         VALUES (?, ?, ?, ?, ?, ?, ?, NOW(3))"
    );
    $ins->execute([(int)$user_id, $jtiHash, $device_id, $platform, $ip, $ua, $expiresAt]);

    return [
        'ok' => true,
        'token_type' => 'Bearer',
        'access_token' => $access,
        'refresh_token' => $refresh,
        'access_expires_in' => $accessTtl,
        'refresh_expires_in' => $refreshTtl,
    ];
}

function api_auth_user(): ?array
{
    $plain = api_bearer_token();
    if (!$plain) return null;

    $dec = jwt_decode($plain);
    if (!$dec['ok']) return null;
    $p = $dec['payload'];

    if (($p['typ'] ?? '') !== 'access') return null;
    $userId = (int)($p['sub'] ?? 0);
    if ($userId <= 0) return null;

    $tv = (int)($p['tv'] ?? 0);
    if ($tv <= 0) return null;

    $pdo = db();
    $st = $pdo->prepare(
        "SELECT id, full_name, phone, email, user_type, status, display_name, avatar_key, jwt_token_version
         FROM users WHERE id=? AND deleted_at IS NULL LIMIT 1"
    );
    $st->execute([$userId]);
    $u = $st->fetch();
    if (!$u) return null;
    if ((int)$u['status'] !== 1) return null;
    $dbTv = (int)($u['jwt_token_version'] ?? 1);
    if ($dbTv <= 0) $dbTv = 1;
    if ($dbTv !== $tv) return null;

    return [
        'id' => (int)$u['id'],
        'full_name' => (string)$u['full_name'],
        'display_name' => $u['display_name'] !== null ? (string)$u['display_name'] : null,
        'avatar_key' => $u['avatar_key'] !== null ? (string)$u['avatar_key'] : null,
        'phone' => (string)$u['phone'],
        'email' => $u['email'] !== null ? (string)$u['email'] : null,
        'user_type' => (int)$u['user_type'],
        'status' => (int)$u['status'],
    ];
}

function api_require_auth(): array
{
    $u = api_auth_user();
    if (!$u) json_out(['ok' => false, 'message' => 'Unauthorized'], 401);
    return $u;
}

function api_require_admin_user(): array
{
    $u = api_require_auth();
    if ((int)$u['user_type'] !== 3) json_out(['ok' => false, 'message' => 'Forbidden'], 403);
    return $u;
}

/**
 * Return only fields that are safe and useful to the authenticated mobile
 * user. Never serialize a raw `SELECT * FROM users` row into an API response.
 */
function api_public_user_payload(array $user): array
{
    $userId = (int)($user['id'] ?? 0);
    if ($userId <= 0) return [];

    $st = db()->prepare("
        SELECT id, full_name, phone, email, user_type, status, display_name,
               avatar_key, code_meli, birth_date
        FROM users
        WHERE id=? AND deleted_at IS NULL
        LIMIT 1
    ");
    $st->execute([$userId]);
    $row = $st->fetch();
    if (!$row) return [];

    return [
        'id' => (int)$row['id'],
        'full_name' => (string)$row['full_name'],
        'display_name' => $row['display_name'] !== null ? (string)$row['display_name'] : null,
        'avatar_key' => $row['avatar_key'] !== null ? (string)$row['avatar_key'] : null,
        'phone' => (string)$row['phone'],
        'email' => $row['email'] !== null ? (string)$row['email'] : null,
        'user_type' => (int)$row['user_type'],
        'status' => (int)$row['status'],
        'code_meli' => $row['code_meli'] !== null ? (string)$row['code_meli'] : null,
        'birth_date' => $row['birth_date'] !== null ? (string)$row['birth_date'] : null,
    ];
}

function api_ok(array $payload = []): void
{
    json_out(['ok' => true] + $payload);
}

function api_err(string $message, int $status = 400, array $payload = []): void
{
    json_out(['ok' => false, 'message' => $message] + $payload, $status);
}

// -----------------------------
// Refresh / Logout Helpers
// -----------------------------

function api_refresh_rotate(string $refreshToken, ?string $deviceId = null): array
{
    $dec = jwt_decode($refreshToken);
    if (!$dec['ok']) return ['ok' => false, 'status' => 401, 'message' => 'refresh_invalid'];
    $p = $dec['payload'];
    if (($p['typ'] ?? '') !== 'refresh') return ['ok' => false, 'status' => 401, 'message' => 'refresh_invalid'];

    $userId = (int)($p['sub'] ?? 0);
    $tv = (int)($p['tv'] ?? 0);
    $jti = (string)($p['jti'] ?? '');
    $did = (string)($p['did'] ?? '');
    $plt = isset($p['plt']) ? (int)$p['plt'] : null;

    if ($userId <= 0 || $tv <= 0 || $jti === '') return ['ok' => false, 'status' => 401, 'message' => 'refresh_invalid'];
    if ($deviceId !== null && $deviceId !== '' && $did !== '' && $deviceId !== $did) {
        return ['ok' => false, 'status' => 401, 'message' => 'device_mismatch'];
    }

    $pdo = db();

    // بررسی token_version کاربر
    $stU = $pdo->prepare("SELECT id, user_type, status, jwt_token_version FROM users WHERE id=? AND deleted_at IS NULL LIMIT 1");
    $stU->execute([$userId]);
    $u = $stU->fetch();
    if (!$u) return ['ok' => false, 'status' => 401, 'message' => 'refresh_invalid'];
    if ((int)$u['status'] !== 1) return ['ok' => false, 'status' => 403, 'message' => 'user_inactive'];
    $dbTv = (int)($u['jwt_token_version'] ?? 1);
    if ($dbTv <= 0) $dbTv = 1;
    if ($dbTv !== $tv) return ['ok' => false, 'status' => 401, 'message' => 'refresh_revoked'];

    $jtiHash = api_refresh_jti_hash($jti);

    $pdo->beginTransaction();
    try {
        $st = $pdo->prepare(
            "SELECT id FROM jwt_refresh_tokens
             WHERE jti_hash=? AND user_id=? AND revoked_at IS NULL AND expires_at > NOW(3)
             LIMIT 1"
        );
        $st->execute([$jtiHash, $userId]);
        $rtId = (int)($st->fetchColumn() ?: 0);
        if ($rtId <= 0) {
            $pdo->rollBack();
            return ['ok' => false, 'status' => 401, 'message' => 'refresh_revoked'];
        }

        // revoke old
        $pdo->prepare("UPDATE jwt_refresh_tokens SET revoked_at=NOW(3), rotated_at=NOW(3) WHERE id=? LIMIT 1")
            ->execute([$rtId]);

        // issue new pair (refresh TTL از env)
        $tok = api_issue_token($userId, $did !== '' ? $did : $deviceId, $plt, null);
        if (!$tok['ok']) {
            $pdo->rollBack();
            return ['ok' => false, 'status' => 500, 'message' => 'token_issue_failed'];
        }

        // parent tracking
        $newDec = jwt_decode((string)$tok['refresh_token']);
        if ($newDec['ok']) {
            $newJti = (string)($newDec['payload']['jti'] ?? '');
            if ($newJti !== '') {
                $newHash = api_refresh_jti_hash($newJti);
                $pdo->prepare("UPDATE jwt_refresh_tokens SET parent_id=? WHERE jti_hash=? AND user_id=? LIMIT 1")
                    ->execute([$rtId, $newHash, $userId]);
            }
        }

        $pdo->commit();
        return ['ok' => true] + $tok;
    } catch (Throwable $e) {
        $pdo->rollBack();
        $msg = ((string)env('APP_DEBUG', '0') === '1') ? $e->getMessage() : 'server_error';
        return ['ok' => false, 'status' => 500, 'message' => $msg];
    }
}

function api_revoke_refresh(string $refreshToken): bool
{
    $dec = jwt_decode($refreshToken);
    if (!$dec['ok']) return false;
    $p = $dec['payload'];
    if (($p['typ'] ?? '') !== 'refresh') return false;
    $jti = (string)($p['jti'] ?? '');
    $uid = (int)($p['sub'] ?? 0);
    if ($jti === '' || $uid <= 0) return false;
    $hash = api_refresh_jti_hash($jti);
    $pdo = db();
    $st = $pdo->prepare("UPDATE jwt_refresh_tokens SET revoked_at=NOW(3) WHERE jti_hash=? AND user_id=? AND revoked_at IS NULL");
    $st->execute([$hash, $uid]);
    return $st->rowCount() > 0;
}

function api_revoke_all_user_tokens(int $userId): void
{
    $pdo = db();
    // افزایش token_version: همه access/refresh های قبلی نامعتبر می‌شوند
    $pdo->prepare("UPDATE users SET jwt_token_version = IFNULL(jwt_token_version,1) + 1, updated_at=NOW(3) WHERE id=?")
        ->execute([$userId]);
    $pdo->prepare("UPDATE jwt_refresh_tokens SET revoked_at=NOW(3) WHERE user_id=? AND revoked_at IS NULL")
        ->execute([$userId]);
}
