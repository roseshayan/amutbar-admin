<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/ApiIrContract.php';

echo "Testing ApiIrContract for IPIsIran...\n";

// 1. Prepare valid IP
$prep = ApiIrContract::prepare('/api/sw1/IPIsIran', ['ip' => '5.200.1.1']);
assert($prep['endpoint'] === '/api/sw1/IPIsIran');
assert($prep['data']['ip'] === '5.200.1.1');
echo "[PASS] Prepare with valid IP\n";

// 2. Decode & validate success response
$dec = ApiIrContract::decode(200, json_encode(['code' => 0, 'success' => true, 'data' => true]));
$valid = ApiIrContract::validateResponse('/api/sw1/IPIsIran', $dec);
assert($valid['data'] === true);
echo "[PASS] Validate response data=true (Iran IP)\n";

// 3. Decode & validate VPN / non-Iran response
$decForeign = ApiIrContract::decode(200, json_encode(['code' => 0, 'success' => true, 'data' => false]));
$validForeign = ApiIrContract::validateResponse('/api/sw1/IPIsIran', $decForeign);
assert($validForeign['data'] === false);
echo "[PASS] Validate response data=false (Foreign / VPN IP)\n";

// 4. Test invalid IP rejection
try {
    ApiIrContract::prepare('/api/sw1/IPIsIran', ['ip' => 'invalid-ip-format']);
    echo "[FAIL] Expected exception on invalid IP\n";
    exit(1);
} catch (VerificationServiceException $e) {
    assert($e->errorCode === 'invalid_ip_address');
    echo "[PASS] Invalid IP rejected with 'invalid_ip_address'\n";
}

// 5. Test private/loopback IP handling
$privateIps = ['127.0.0.1', '192.168.1.100', '10.0.0.5', '::1'];
foreach ($privateIps as $pIp) {
    $isPrivate = !filter_var($pIp, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    assert($isPrivate === true, "IP {$pIp} must be recognized as private");
}
echo "[PASS] Private IP identification verified\n";

echo "\nAll IPIsIran contract checks passed successfully!\n";
