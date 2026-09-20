<?php
declare(strict_types=1);
require_once __DIR__ . '/settings.php';

function verification_policy(int $role): array
{
    if (!in_array($role, [1, 2], true)) throw new InvalidArgumentException('Invalid app role');
    $prefix = $role === 1 ? 'verification.driver.' : 'verification.cargo.';
    return [
        'require_shahkar' => (string)settings_get($prefix . 'require_shahkar', '1') === '1',
        'require_national_serial' => (string)settings_get($prefix . 'require_national_serial', settings_get('auth.require_national_serial', '0')) === '1',
        'require_video' => (string)settings_get($prefix . 'require_video', $role === 1 ? settings_get('onboarding.require_verification_video', '0') : '0') === '1',
        'video_guide_url' => (string)settings_get($prefix . 'video_guide_url', settings_get('verification.video_guide_url', '')),
        'video_guide_text' => (string)settings_get($prefix . 'video_guide_text', settings_get('verification.video_guide_text', '')),
    ];
}

function verification_policy_keys(): array
{
    $keys = [];
    foreach (['driver', 'cargo'] as $app) {
        foreach (['require_shahkar', 'require_national_serial', 'require_video', 'video_guide_url', 'video_guide_text'] as $key) {
            $keys[] = "verification.$app.$key";
        }
    }
    return $keys;
}

function verification_video_passed(array $result): bool
{
    $data = $result['data'] ?? null;
    return ($result['success'] ?? false) === true && is_array($data)
        && ($data['isMatch'] ?? false) === true
        && ($data['isLiveness'] ?? false) === true
        && ($data['isSpeechMatched'] ?? false) === true;
}

function verification_needs_video(int $userId, int $role): bool
{
    if (!verification_policy($role)['require_video']) return false;
    $st = db()->prepare("SELECT status, response_redacted_json FROM identity_verification_jobs
        WHERE subject_user_id=? AND subject_kind=? AND check_type='VideoVerify' ORDER BY id DESC LIMIT 1");
    $st->execute([$userId, $role]);
    $job = $st->fetch();
    $result = $job ? json_decode((string)$job['response_redacted_json'], true) : null;
    return !$job || (int)$job['status'] !== 2 || !is_array($result) || !verification_video_passed($result);
}

function verification_validate_settings(array $items): void
{
    foreach (['verification.video_max_seconds' => [1, 30], 'verification.video_max_mb' => [1, 12],
        'verification.api_ir.liveness_threshold' => [0, 100], 'verification.api_ir.matching_threshold' => [0, 100],
        'verification.api_ir.speech_threshold' => [0, 100]] as $key => [$min, $max]) {
        if (!array_key_exists($key, $items)) continue;
        $number = filter_var($items[$key], FILTER_VALIDATE_INT);
        if ($number === false || $number < $min || $number > $max) throw new InvalidArgumentException('زمان، حجم یا آستانهٔ تأیید خارج از محدوده است');
    }
    foreach (verification_policy_keys() as $key) {
        if (!array_key_exists($key, $items)) continue;
        $value = $items[$key];
        if (!is_string($value)) throw new InvalidArgumentException('مقدار تنظیمات نامعتبر است');
        if (str_contains($key, '.require_') && !in_array($value, ['0', '1'], true)) {
            throw new InvalidArgumentException('مقدار فعال/غیرفعال نامعتبر است');
        }
        if (str_ends_with($key, 'video_guide_url') && $value !== '') {
            $uploaded = preg_match('~^storage/uploads/system/guide_[a-f0-9]{32}\.mp4$~D', $value);
            $remote = filter_var($value, FILTER_VALIDATE_URL) && in_array(strtolower((string)parse_url($value, PHP_URL_SCHEME)), ['http', 'https'], true);
            if (strlen($value) > 2000 || (!$uploaded && !$remote)) throw new InvalidArgumentException('لینک ویدئو باید http یا https باشد');
        }
        if (str_ends_with($key, 'video_guide_text') && mb_strlen($value) > 4000) {
            throw new InvalidArgumentException('متن راهنما باید حداکثر ۴۰۰۰ کاراکتر باشد');
        }
    }
}
