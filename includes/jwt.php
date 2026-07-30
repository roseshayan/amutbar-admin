<?php

declare(strict_types=1);

// -----------------------------
// JWT Utilities (HS256)
// -----------------------------
// نکته امنیتی:
// - در محیط production حتما JWT_SECRET را یک رشته طولانی و تصادفی تنظیم کنید.
// - توکن access کوتاه‌عمر و refresh بلندعمر است (refresh در دیتابیس ردیابی می‌شود).

function jwt_secret(): string
{
    $sec = (string)env('JWT_SECRET', '');
    if (strlen($sec) >= 32) return $sec;

    $isProduction = strtolower((string)env('APP_ENV', 'local')) === 'production';
    if ($isProduction) {
        throw new RuntimeException('JWT_SECRET must be a random value of at least 32 characters');
    }

    // فقط لوکال/تست.
    $fallback = (string)env('APP_KEY', env('CSRF_SECRET', ''));
    if (strlen($fallback) >= 32) return $fallback;
    return 'dev-jwt-secret-change-me';
}

function jwt_issuer(): string
{
    $iss = trim((string)env('JWT_ISSUER', ''));
    return $iss !== '' ? $iss : 'amutbar-api';
}

function jwt_audience(): string
{
    $aud = trim((string)env('JWT_AUDIENCE', ''));
    return $aud !== '' ? $aud : 'amutapp';
}

function jwt_b64url_encode(string $data): string
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function jwt_b64url_decode(string $data): string
{
    $remainder = strlen($data) % 4;
    if ($remainder) $data .= str_repeat('=', 4 - $remainder);
    $data = strtr($data, '-_', '+/');
    $out = base64_decode($data, true);
    return $out === false ? '' : $out;
}

function jwt_sign(string $input, string $secret): string
{
    return hash_hmac('sha256', $input, $secret, true);
}

function jwt_encode(array $payload, int $ttlSec, string $type, array $extra = []): string
{
    $now = time();
    $jti = rtrim(strtr(base64_encode(random_bytes(16)), '+/', '-_'), '=');

    $header = ['alg' => 'HS256', 'typ' => 'JWT'];

    $base = [
        'iss' => jwt_issuer(),
        'aud' => jwt_audience(),
        'iat' => $now,
        'nbf' => $now,
        'exp' => $now + max(1, $ttlSec),
        'jti' => $jti,
        'typ' => $type,
    ];

    $payload = $base + $payload + $extra;

    $h = jwt_b64url_encode(json_encode($header, JSON_UNESCAPED_UNICODE));
    $p = jwt_b64url_encode(json_encode($payload, JSON_UNESCAPED_UNICODE));
    $sig = jwt_b64url_encode(jwt_sign($h . '.' . $p, jwt_secret()));
    return $h . '.' . $p . '.' . $sig;
}

/**
 * @return array{ok:bool,payload?:array,message?:string}
 */
function jwt_decode(string $jwt, int $leewaySec = 10): array
{
    $jwt = trim($jwt);
    $parts = explode('.', $jwt);
    if (count($parts) !== 3) return ['ok' => false, 'message' => 'invalid_token'];

    [$h64, $p64, $s64] = $parts;
    $hjson = jwt_b64url_decode($h64);
    $pjson = jwt_b64url_decode($p64);
    $sig = jwt_b64url_decode($s64);

    $header = json_decode($hjson, true);
    $payload = json_decode($pjson, true);
    if (!is_array($header) || !is_array($payload)) return ['ok' => false, 'message' => 'invalid_token'];

    if (($header['alg'] ?? '') !== 'HS256') return ['ok' => false, 'message' => 'invalid_alg'];

    $expected = jwt_sign($h64 . '.' . $p64, jwt_secret());
    if (!hash_equals($expected, $sig)) return ['ok' => false, 'message' => 'invalid_signature'];

    $now = time();
    $iss = (string)($payload['iss'] ?? '');
    $aud = (string)($payload['aud'] ?? '');
    if ($iss !== jwt_issuer()) return ['ok' => false, 'message' => 'invalid_issuer'];
    if ($aud !== jwt_audience()) return ['ok' => false, 'message' => 'invalid_audience'];

    $nbf = (int)($payload['nbf'] ?? 0);
    $exp = (int)($payload['exp'] ?? 0);
    if ($nbf > 0 && $now + $leewaySec < $nbf) return ['ok' => false, 'message' => 'token_not_yet_valid'];
    if ($exp > 0 && $now - $leewaySec >= $exp) return ['ok' => false, 'message' => 'token_expired'];

    return ['ok' => true, 'payload' => $payload];
}
