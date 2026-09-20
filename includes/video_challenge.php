<?php
declare(strict_types=1);
require_once __DIR__ . '/ExternalApiHelper.php';

function verification_create_challenge(array $user, string $serial = '', ?ExternalApiHelper $client = null): array
{
    $pdo = db();
    $st = $pdo->prepare('SELECT national_card_serial FROM users WHERE id=?');
    $st->execute([(int)$user['id']]);
    $stored = trim((string)$st->fetchColumn());
    if ($serial === '') $serial = $stored;
    if (mb_strlen($serial) < 5 || mb_strlen($serial) > 64) {
        throw new VerificationServiceException('national_serial_required', 'سریال پشت کارت ملی یا کد رهگیری رسید کارت را وارد کنید.', 422);
    }
    // Serialize challenge creation to avoid simultaneous paid speech requests.
    $lock = 'video_challenge_' . (int)$user['id'] . '_' . (int)$user['user_type'];
    $st = $pdo->prepare('SELECT GET_LOCK(?, 0)'); $st->execute([$lock]);
    if ((int)$st->fetchColumn() !== 1) throw new VerificationServiceException('verification_in_progress', 'درخواست قبلی در حال پردازش است.', 409);
    try {
        $st = $pdo->prepare('SELECT created_at FROM verification_video_challenges WHERE user_id=? AND app_role=? AND created_at > DATE_SUB(NOW(3), INTERVAL 10 SECOND) LIMIT 1');
        $st->execute([(int)$user['id'], (int)$user['user_type']]);
        if ($st->fetch()) throw new VerificationServiceException('verification_rate_limited', 'برای دریافت متن جدید چند ثانیه صبر کنید.', 429, true);
        $result = ($client ?? new ExternalApiHelper($pdo))->callExternalApi('api_ir', '/api/sw1/VideoVerifySpeechText', 'POST', []);
        $phrase = trim((string)$result['data']);
        if (mb_strlen($phrase) < 10 || mb_strlen($phrase) > 2000) throw new VerificationServiceException('provider_invalid_response', 'متن دریافت‌شده برای ضبط معتبر نیست.', 502, true);
        $token = bin2hex(random_bytes(32));
        $pdo->beginTransaction();
        $pdo->prepare('UPDATE verification_video_challenges SET used_at=NOW(3) WHERE user_id=? AND app_role=? AND used_at IS NULL')
            ->execute([(int)$user['id'], (int)$user['user_type']]);
        $pdo->prepare('INSERT INTO verification_video_challenges (token_hash, user_id, app_role, speech_text, expires_at) VALUES (?, ?, ?, ?, DATE_ADD(NOW(3), INTERVAL 10 MINUTE))')
            ->execute([hash('sha256', $token), (int)$user['id'], (int)$user['user_type'], $phrase]);
        if ($stored !== $serial) $pdo->prepare('UPDATE users SET national_card_serial=? WHERE id=?')->execute([$serial, (int)$user['id']]);
        $pdo->commit();
        return ['challenge_token' => $token, 'speech_text' => $phrase, 'expires_in' => 600];
    } finally {
        if ($pdo->inTransaction()) $pdo->rollBack();
        $pdo->prepare('SELECT RELEASE_LOCK(?)')->execute([$lock]);
    }
}

function verification_consume_challenge(array $user, string $token): string
{
    if (!preg_match('/^[a-f0-9]{64}$/D', $token)) throw new VerificationServiceException('video_challenge_required', 'ابتدا متن جدید ضبط را دریافت کنید.', 422);
    $args = [hash('sha256', $token), (int)$user['id'], (int)$user['user_type']];
    $st = db()->prepare('UPDATE verification_video_challenges SET used_at=NOW(3) WHERE token_hash=? AND user_id=? AND app_role=? AND used_at IS NULL AND expires_at > NOW(3)');
    $st->execute($args);
    if ($st->rowCount() !== 1) throw new VerificationServiceException('video_challenge_expired', 'متن ضبط منقضی یا استفاده شده است؛ متن جدید بگیرید و دوباره ضبط کنید.', 409);
    $st = db()->prepare('SELECT speech_text FROM verification_video_challenges WHERE token_hash=? AND user_id=? AND app_role=?');
    $st->execute($args);
    return (string)$st->fetchColumn();
}
