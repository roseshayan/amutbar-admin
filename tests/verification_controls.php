<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

$afterSharedAccountChecks = function (): void {
    $start = $GLOBALS['checks'];
    $pdo = db();
    $id = (int)$pdo->query("SELECT id FROM users WHERE phone='09120000001'")->fetchColumn();
    check(verification_policy(1)['require_shahkar'] && verification_policy(2)['require_shahkar'], 'Existing Shahkar default retained');
    settings_set('onboarding.require_verification_video', '1');
    check(verification_policy(1)['require_video'] && !verification_policy(2)['require_video'], 'Legacy driver video flag does not enable cargo video');
    settings_set('verification.cargo.require_video', '1');
    settings_set('verification.driver.require_video', '0');
    settings_set('verification.driver.require_shahkar', '0');
    settings_set('verification.cargo.require_national_serial', '1');
    check(!verification_policy(1)['require_shahkar'] && verification_policy(2)['require_shahkar'], 'Shahkar controls are independent');
    check(!verification_policy(1)['require_national_serial'] && verification_policy(2)['require_national_serial'], 'Photo controls are independent');
    check(!verification_needs_video($id, 1) && verification_needs_video($id, 2), 'Only enabled role requires video');
    $pdo->exec("INSERT INTO external_api_providers (name, slug, base_url) VALUES ('Test', 'test', 'https://example.invalid')");
    $provider = (int)$pdo->lastInsertId();
    $passed = ['success' => true, 'data' => ['isPassed' => true, 'isMatch' => true, 'isLiveness' => true, 'isSpeechMatched' => true]];
    $insert = $pdo->prepare("INSERT INTO identity_verification_jobs (subject_user_id, subject_kind, check_type, provider_id, status, response_redacted_json) VALUES (?, ?, 'VideoVerify', ?, ?, ?)");
    $insert->execute([$id, 1, $provider, 2, json_encode($passed)]);
    check(verification_needs_video($id, 2), 'A shared account driver result cannot verify cargo');
    $insert->execute([$id, 2, $provider, 2, json_encode($passed)]);
    check(!verification_needs_video($id, 2), 'All successful checks complete cargo verification');
    foreach (['isMatch', 'isLiveness', 'isSpeechMatched'] as $flag) {
        $failed = $passed;
        $failed['data'][$flag] = false;
        $insert->execute([$id, 2, $provider, 2, json_encode($failed)]);
        check(verification_needs_video($id, 2), "Rejected $flag is not upload success");
    }
    $insert->execute([$id, 2, $provider, 4, json_encode($passed)]);
    check(verification_needs_video($id, 2), 'Failed job status is not verified');
    check(!verification_video_passed(['success' => true, 'data' => ['isMatch' => true]]), 'Partial biometric results are rejected');
    $badBoolean = $passed;
    $badBoolean['data']['isMatch'] = 'false';
    check(!verification_video_passed($badBoolean), 'String false cannot pass verification');
    $cargo = userRow($id); $cargo['user_type'] = 2;
    check(company_profile_payload($cargo)['onboarding']['needs_verification_video'], 'Company endpoint exposes pending video');
    check(api_user_with_profile($cargo)['onboarding']['needs_verification_video'], 'Shared me endpoint exposes pending cargo video');
    settings_set('verification.cargo.require_video', '0');
    check(!company_profile_payload($cargo)['onboarding']['needs_verification_video'], 'Disabling immediately releases pending users');
    settings_set('verification.driver.video_guide_url', 'https://example.com/driver.mp4');
    settings_set('verification.cargo.video_guide_url', 'storage/uploads/system/guide_' . str_repeat('a', 32) . '.mp4');
    check(verification_policy(1)['video_guide_url'] !== verification_policy(2)['video_guide_url'], 'Per-app uploaded or remote guides');
    foreach (['javascript:alert(1)', 'file:///secret', '../private.mp4'] as $url) {
        try { verification_validate_settings(['verification.driver.video_guide_url' => $url]); $rejected = false; }
        catch (InvalidArgumentException $e) { $rejected = true; }
        check($rejected, 'Invalid guide URL rejected');
    }
    verification_validate_settings(['verification.driver.video_guide_url' => 'https://example.com/guide.mp4']);
    verification_validate_settings(['verification.cargo.video_guide_url' => verification_policy(2)['video_guide_url']]);
    verification_validate_settings(['verification.cargo.video_guide_url' => '']);
    check(true, 'Remote, uploaded, and removed guides accepted');
    try { verification_validate_settings(['verification.video_max_seconds' => '300']); $rejected = false; }
    catch (InvalidArgumentException $e) { $rejected = true; }
    check($rejected, 'Recording duration validated');
    $count = $GLOBALS['checks'] - $start;
    echo "PASS: $count verification-control integration checks\n";
};
require __DIR__ . '/shared_app_accounts.php';
