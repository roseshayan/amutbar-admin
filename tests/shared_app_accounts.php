<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

// Integration test: creates and drops ONLY its own randomly named database.
// TEST_MYSQL_DSN must omit dbname; defaults to local MySQL. No SMS/provider calls.
$server = new PDO(getenv('TEST_MYSQL_DSN') ?: 'mysql:host=127.0.0.1;port=3306;charset=utf8mb4',
    getenv('TEST_MYSQL_USER') ?: 'root', getenv('TEST_MYSQL_PASSWORD') ?: '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
$testDatabase = 'amutbar_test_shared_' . bin2hex(random_bytes(6));
$server->exec("CREATE DATABASE `$testDatabase` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$server->exec("USE `$testDatabase`");
function db(): PDO { return $GLOBALS['server']; }
function env(string $key, $default = null) { return $default; }
require_once __DIR__ . '/../includes/api.php';
require_once __DIR__ . '/../api/v1/_company_routes.php';
$checks = 0;
function check(bool $condition, string $message): void {
    if (!$condition) throw new RuntimeException($message);
    $GLOBALS['checks']++;
}
function authenticated(string $token): ?array {
    $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer ' . $token;
    return api_auth_user();
}
function userRow(int $id): array {
    $st = db()->prepare('SELECT * FROM users WHERE id=?');
    $st->execute([$id]);
    return $st->fetch();
}
function duplicateRejected(callable $operation): bool {
    try { $operation(); } catch (PDOException $e) { return $e->getCode() === '23000'; }
    return false;
}
try {
    $server->exec(file_get_contents(__DIR__ . '/../database/schema.sql'));
    $migration = file_get_contents(__DIR__ . '/../database/migrations/2026_09_20_000006_enable_shared_app_accounts.sql');
    $server->exec($migration);
    $server->exec($migration);

    // Both registration orders must resolve to the same identity and preserve profiles.
    foreach ([[1, 2, '09120000001', '1111111111'], [2, 1, '09120000002', '2222222222']] as [$first, $second, $phone, $national]) {
        $a = api_login_mobile_account($phone, $first);
        check($a['ok'], 'First app enrolment');
        $id = $a['user_id'];
        $server->prepare('UPDATE users SET code_meli=? WHERE id=?')->execute([$national, $id]);
        $initial = api_issue_token($id, 'same-device', 1, 30, $first);
        check($initial['ok'], 'Original app session issued');
        $b = api_login_mobile_account($phone, $second);
        check($b['ok'] && $b['user_id'] === $id, 'Second app reuses the user');
        check((int)userRow($id)['user_type'] === $first, 'Original account type is unchanged');
        check(userRow($id)['code_meli'] === $national, 'Shared national code is unchanged');
        check((int)$server->query("SELECT COUNT(*) FROM user_app_roles WHERE user_id=$id")->fetchColumn() === 2, 'Two memberships');

        $driverToken = api_issue_token($id, 'same-device', 1, 30, 1);
        $cargoToken = api_issue_token($id, 'same-device', 1, 30, 2);
        $driver = authenticated($driverToken['access_token']);
        $cargo = authenticated($cargoToken['access_token']);
        check($driver['user_type'] === 1 && $cargo['user_type'] === 2, 'Signed app context controls access');
        check(authenticated($initial['access_token'])['user_type'] === $first, 'Other app login preserves the old session');
        check(api_public_user_payload($cargo)['user_type'] === 2, 'Public payload preserves selected role');
        check(!api_issue_token($id, null, null, 30, 3)['ok'], 'Mobile account cannot receive admin token');
        $forged = jwt_encode(['sub' => $id, 'ut' => 3, 'tv' => 1], 900, 'access');
        check(authenticated($forged) === null, 'Even a signed admin claim requires admin membership');

        $server->prepare("INSERT INTO companies (user_id, company_name, owner_full_name, owner_national_code, verification_status) VALUES (?, 'Cargo', 'Person', ?, 1)")->execute([$id, $national]);
        $driverProfile = api_user_with_profile($driver);
        $cargoProfile = api_user_with_profile($cargo);
        check($driverProfile['driver'] === null && !$driverProfile['onboarding']['identity_verified'], 'Cargo verification does not skip driver onboarding');
        check($cargoProfile['company'] !== null && $cargoProfile['driver'] === null && $cargoProfile['onboarding']['identity_verified'], 'Cargo receives only its own profile');
        check(company_profile_payload($cargo)['user']['user_type'] === 2, 'Company payload uses cargo role');
        $server->prepare("INSERT INTO drivers (user_id, full_name, national_code, verification_status) VALUES (?, 'Person', ?, 1)")->execute([$id, $national]);
        $profile = api_user_with_profile($driver);
        check($profile['driver'] !== null && $profile['company'] === null && $profile['onboarding']['needs_vehicle_info'], 'Independent driver profile and vehicle onboarding');
        check(api_user_with_profile($cargo)['company']['owner_national_code'] === $national, 'Both profiles accept the shared national code');

        foreach ([1 => $driverToken, 2 => $cargoToken] as $role => $token) {
            $rotated = api_refresh_rotate($token['refresh_token'], 'same-device');
            check($rotated['ok'] && authenticated($rotated['access_token'])['user_type'] === $role, 'Refresh preserves app role');
            check(!api_refresh_rotate($token['refresh_token'], 'same-device')['ok'], 'Rotated token cannot be replayed');
        }
        $freshDriver = api_issue_token($id, null, 1, 30, 1);
        $freshCargo = api_issue_token($id, null, 1, 30, 2);
        api_revoke_refresh($freshCargo['refresh_token']);
        check(api_refresh_rotate($freshDriver['refresh_token'])['ok'], 'Cargo logout does not revoke driver refresh');

        $server->exec("UPDATE user_app_roles SET status=2 WHERE user_id=$id AND app_role=2");
        $server->exec($migration);
        check(!api_login_mobile_account($phone, 2)['ok'], 'Login and migration preserve disabled membership');
        check(authenticated($cargoToken['access_token']) === null, 'Disabled membership rejects existing access');
        check(!api_refresh_rotate($freshCargo['refresh_token'])['ok'], 'Disabled role cannot refresh');
        check(authenticated($driverToken['access_token']) !== null, 'Disabled cargo does not disable driver');
        $server->exec("UPDATE users SET status=4 WHERE id=$id");
        check(!api_login_mobile_account($phone, 1)['ok'] && authenticated($driverToken['access_token']) === null, 'Global suspension remains enforced');
        $server->exec("UPDATE users SET status=1 WHERE id=$id");
    }

    check((int)$server->query('SELECT COUNT(*) FROM users')->fetchColumn() === 2, 'No duplicate identity rows');
    check(duplicateRejected(fn() => $server->exec("INSERT INTO users (full_name, phone, user_type) VALUES ('Duplicate', '09120000001', 2)")), 'Phone remains globally unique');
    check(duplicateRejected(fn() => $server->exec("UPDATE users SET code_meli='1111111111' WHERE phone='09120000002'")), 'National code remains globally unique');
    $server->exec("INSERT INTO users (full_name, phone, user_type, status) VALUES ('Admin', '09120000003', 3, 1)");
    check(!api_login_mobile_account('09120000003', 1)['ok'], 'OTP cannot enrol administrator in mobile app');
    check(!api_login_mobile_account('09120000004', 3)['ok'], 'OTP cannot create administrator');

    // Existing accounts/tokens work without explicit membership rows.
    $server->exec("INSERT INTO users (full_name, phone, user_type, status) VALUES ('Legacy', '09120000005', 1, 1)");
    $legacyId = (int)$server->lastInsertId();
    check(api_issue_token($legacyId)['ok'], 'Existing original-role session compatibility');
    check(!api_issue_token($legacyId, null, null, 30, 2)['ok'], 'Unenrolled second app cannot receive a token');
    $server->exec("UPDATE users SET deleted_at=NOW(3) WHERE id=$legacyId");
    $replacement = api_login_mobile_account('09120000005', 2);
    check($replacement['ok'] && $replacement['user_id'] !== $legacyId, 'Archived accounts are not revived');
    echo "PASS: $checks shared-account integration checks\n";
    if (isset($afterSharedAccountChecks)) $afterSharedAccountChecks();
} finally {
    // Name is generated here, never supplied by the environment/user.
    $server->exec("DROP DATABASE `$testDatabase`");
}
