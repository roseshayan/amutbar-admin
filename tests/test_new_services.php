<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/ExternalApiHelper.php';
require_once __DIR__ . '/../includes/ApiIrContract.php';
require_once __DIR__ . '/../includes/sms.php';
require_once __DIR__ . '/../includes/otp.php';
require_once __DIR__ . '/../includes/verification_policy.php';
require_once __DIR__ . '/../includes/IdentityVerificationRunner.php';

$pdo = db();
$checks = 0;

function assertCheck(bool $condition, string $msg): void {
    global $checks;
    if (!$condition) {
        throw new RuntimeException("Assertion failed: $msg");
    }
    $checks++;
}

echo "=== Running New api.ir Services & Fallback Test Suite ===\n";

// 1. Test ApiIrContract::prepare with CallOTP and CallOTPalt
$callOtpReq = ApiIrContract::prepare('/api/sw1/CallOTP', ['code' => '12345', 'number' => '09121234567']);
assertCheck($callOtpReq['endpoint'] === '/api/sw1/CallOTP', 'CallOTP endpoint normalized');
assertCheck($callOtpReq['data']['code'] === '12345' && $callOtpReq['data']['number'] === '09121234567', 'CallOTP data prepared');
assertCheck($callOtpReq['timeout_ms'] === 20000, 'CallOTP timeout is 20s');

$callOtpAltReq = ApiIrContract::prepare('/api/sw1/CallOTPalt', ['code' => '54321', 'number' => '09351234567']);
assertCheck($callOtpAltReq['endpoint'] === '/api/sw1/CallOTPalt', 'CallOTPalt endpoint normalized');

// 2. Test ApiIrContract::prepare with DrivingLicense and DrivingLisense
$drivingReq = ApiIrContract::prepare('/api/sw1/DrivingLicense', ['nationalCode' => '0010007700', 'mobile' => '09121234567']);
assertCheck($drivingReq['endpoint'] === '/api/sw1/DrivingLicense', 'DrivingLicense endpoint prepared');
assertCheck($drivingReq['timeout_ms'] === 45000, 'DrivingLicense timeout is 45s');

$drivingOldReq = ApiIrContract::prepare('/api/sw1/DrivingLisense', ['nationalCode' => '0010007700', 'mobile' => '09121234567']);
assertCheck($drivingOldReq['endpoint'] === '/api/sw1/DrivingLisense', 'DrivingLisense endpoint prepared');

$drivingScoreReq = ApiIrContract::prepare('/api/sw1/DrivingScore', ['nationalCode' => '0010007700', 'mobile' => '09121234567', 'licenseNumber' => 'ABC12345']);
assertCheck($drivingScoreReq['endpoint'] === '/api/sw1/DrivingScore', 'DrivingScore endpoint prepared');
assertCheck($drivingScoreReq['data']['licenseNumber'] === 'ABC12345', 'DrivingScore licenseNumber passed');

// 3. Test ApiIrContract::validateResponse
$otpValid = ApiIrContract::validateResponse('/api/sw1/CallOTP', ['success' => true, 'data' => true]);
assertCheck($otpValid['data'] === true, 'CallOTP boolean response validated');

$licenseValid = ApiIrContract::validateResponse('/api/sw1/DrivingLicense', ['success' => true, 'data' => ['licenses' => []]]);
assertCheck(is_array($licenseValid['data']), 'DrivingLicense array response validated');

$personInfoValid = ApiIrContract::validateResponse('/api/sw1/PersonInfo', [
    'success' => true,
    'data' => ['nationalCode' => '0010007700', 'alive' => true, 'firstName' => 'علی']
], ['nationalCode' => '0010007700']);
assertCheck($personInfoValid['data']['alive'] === true, 'PersonInfo response validated');

// 4. Test ExternalApiHelper environment fallback
$helper = new ExternalApiHelper($pdo);
// In database, credential row 1 has env = 1. If we query env = 3 (production) or null, it should fallback to active credential row 1.
$credFallback = $helper->getActiveCredential(1, 3);
assertCheck($credFallback !== null, 'Active credential found via fallback for env 3');
assertCheck(!empty($credFallback['bearer_token']), 'Bearer token decrypted successfully');

// 5. Test verification_policy
$policyDriver = verification_policy(1);
assertCheck(array_key_exists('require_driving_license', $policyDriver), 'Driver policy has require_driving_license');
assertCheck(array_key_exists('require_shahkar', $policyDriver), 'Driver policy has require_shahkar');

// 6. Test IdentityVerificationRunner currentService with newly added services
$runner = new IdentityVerificationRunner($pdo);
$drivingSvc = IdentityVerificationRunner::currentService([
    'provider_slug' => 'api_ir',
    'code' => 'DrivingLicense',
    'title' => 'گواهینامه',
    'endpoint_path' => '/api/sw1/DrivingLicense'
]);
assertCheck($drivingSvc['available'] === true, 'DrivingLicense service is available');
$drivingConfig = json_decode((string)$drivingSvc['config_json'], true);
assertCheck($drivingConfig['required'] === ['nationalCode', 'mobile'], 'DrivingLicense required fields configured');

echo "PASS: All $checks new services and fallback checks passed successfully!\n";
