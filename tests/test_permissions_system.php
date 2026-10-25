<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';

echo "==========================================\n";
echo "Testing Granular Permission & RBAC System\n";
echo "==========================================\n";

$pdo = db();

// 1. Check Super Admin has all permissions
$superAdminId = 1;
$_SESSION['admin_user_id'] = $superAdminId;

$allKeys = permissions_all_keys();
echo "[TEST 1] Total catalog permissions: " . count($allKeys) . "\n";
assert(count($allKeys) === 33, "Expected 33 catalog permissions");

assert(is_super_admin() === true, "Super admin role check failed");
assert(has_permission('dashboard.view') === true, "Super admin should have dashboard.view");
assert(has_permission('drivers.delete') === true, "Super admin should have drivers.delete");
echo "[PASS] Super Admin possesses all permissions unconditionally.\n";

// 2. Test staff user isolation
// Find or create a test staff user
$stStaff = $pdo->prepare("SELECT id FROM users WHERE user_type = 3 AND admin_role = 'staff' AND deleted_at IS NULL LIMIT 1");
$stStaff->execute();
$staffId = (int)($stStaff->fetchColumn() ?: 0);

if (!$staffId) {
    $pdo->prepare("INSERT INTO users (full_name, phone, user_type, admin_role, status, password_hash, created_at, updated_at) VALUES ('کارمند تست', '09120000001', 3, 'staff', 1, 'hash', NOW(3), NOW(3))")->execute();
    $staffId = (int)$pdo->lastInsertId();
}

echo "[TEST 2] Testing staff permissions for user ID {$staffId}...\n";
// Super admin saves staff permissions
$_SESSION['admin_user_id'] = $superAdminId;
$testPerms = ['loads.view', 'drivers.view'];
save_staff_permissions($staffId, $testPerms);

// Now switch to staff session
$_SESSION['admin_user_id'] = $staffId;
assert(is_staff() === true, "Staff role check failed");
assert(is_super_admin() === false, "Staff should NOT be super admin");

$loadedPerms = admin_permissions($staffId);
echo "[TEST 2.1] Loaded staff permissions count: " . count($loadedPerms) . "\n";
assert(in_array('loads.view', $loadedPerms, true), "Staff should have loads.view");
assert(in_array('drivers.view', $loadedPerms, true), "Staff should have drivers.view");
assert(!in_array('drivers.delete', $loadedPerms, true), "Staff should NOT have drivers.delete");
assert(!in_array('cargos_users.delete', $loadedPerms, true), "Staff should NOT have cargos_users.delete");
echo "[PASS] Staff permissions correctly isolated and enforced.\n";

// 3. Test Security Barrier: Staff cannot view, edit or delete super admin
echo "[TEST 3] Testing security boundary: staff touching super admin...\n";

// 3.1 Can manage user
assert(can_manage_user($superAdminId, $staffId) === false, "Staff should NOT be able to manage super admin!");

// 3.2 users_get should return null for super admin when called by staff
$superAdminView = users_get($superAdminId);
assert($superAdminView === null, "Staff users_get(superAdmin) must return null!");

// 3.3 users_soft_delete should reject staff attempting to delete super admin
$caught = false;
try {
    users_soft_delete($superAdminId);
} catch (RuntimeException $e) {
    $caught = true;
}
assert($caught === true, "Deleting super admin as staff must throw RuntimeException!");

// 3.4 users_datatable must NOT contain super admin when called by staff
$dt = users_datatable(['draw' => 1, 'start' => 0, 'length' => 100]);
foreach ($dt['data'] as $u) {
    assert((int)$u['user_type'] !== 3, "users_datatable for staff must NOT contain user_type=3! Found ID: " . $u['id']);
}
echo "[PASS] Super Admin is 100% shielded from staff viewing, editing, or deleting.\n";

// 4. Test Super Admin Protection from self-deletion
$_SESSION['admin_user_id'] = $superAdminId;
$selfCaught = false;
try {
    users_soft_delete($superAdminId);
} catch (RuntimeException $e) {
    $selfCaught = true;
}
assert($selfCaught === true, "Super admin deleting themselves or primary super admin must throw exception!");
echo "[PASS] Super Admin self-deletion and primary super admin protection verified.\n";

// Restore test staff permissions to default
save_staff_permissions($staffId, get_default_staff_permissions());

echo "\n==========================================\n";
echo "ALL PERMISSION AND SECURITY TESTS PASSED!\n";
echo "==========================================\n";
