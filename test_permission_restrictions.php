<?php
session_start();
require_once 'auth/db.php';

echo "🧪 Testing Permission Restrictions\n";
echo "=================================\n\n";

// Test data
$testUsers = [
    'super_admin' => ['username' => 'oladayo', 'expected_role' => 'super_admin'],
    'admin' => ['username' => 'temmy', 'expected_role' => 'admin']
];

// Restricted permissions that should only be accessible by super admin
$restrictedPermissions = [
    'manage_backups' => 'Backup Management',
    'view_system_health' => 'System Health',
    'view_error_logs' => 'Error Logging',
    'view_login_monitoring' => 'Login Monitoring',
    'view_activity_logs' => 'Activity Logs',
    'manage_security' => 'Security Management (IP Blocking)',
    'manage_settings' => 'Settings',
    'delete_products' => 'Delete Products'
];

$results = [];

foreach ($testUsers as $userType => $userData) {
    echo "👤 Testing {$userType} user: {$userData['username']}\n";
    echo "----------------------------------------\n";

    // Get user details
    $user = getAdminUserByUsername($userData['username']);
    if (!$user) {
        echo "❌ User not found: {$userData['username']}\n\n";
        continue;
    }

    // Check role
    $roleMatch = $user['role_name'] === $userData['expected_role'];
    echo "Role: {$user['role_name']} " . ($roleMatch ? "✅" : "❌ Expected: {$userData['expected_role']}\n");

    // Test each restricted permission
    $accessibleRestricted = [];
    $inaccessibleRestricted = [];

    foreach ($restrictedPermissions as $perm => $description) {
        $hasPermission = userHasPermission($user['id'], $perm);
        if ($hasPermission) {
            $accessibleRestricted[] = $description;
        } else {
            $inaccessibleRestricted[] = $description;
        }
    }

    // Results based on user type
    if ($userType === 'super_admin') {
        // Super admin should have ALL restricted permissions
        $allAccessible = count($accessibleRestricted) === count($restrictedPermissions);
        echo "Super Admin Access: " . ($allAccessible ? "✅ All restricted permissions accessible" : "❌ Missing some permissions") . "\n";
        if (!$allAccessible) {
            echo "  Missing: " . implode(', ', array_diff(array_values($restrictedPermissions), $accessibleRestricted)) . "\n";
        }
    } else {
        // Regular admin should have NONE of the restricted permissions
        $noneAccessible = count($accessibleRestricted) === 0;
        echo "Regular Admin Access: " . ($noneAccessible ? "✅ No restricted permissions accessible" : "❌ Has access to restricted permissions") . "\n";
        if (!$noneAccessible) {
            echo "  Should not have access to: " . implode(', ', $accessibleRestricted) . "\n";
        }
    }

    // Store results
    $results[$userType] = [
        'role_correct' => $roleMatch,
        'accessible_restricted' => $accessibleRestricted,
        'inaccessible_restricted' => $inaccessibleRestricted,
        'total_permissions' => count(fetchData('role_permissions', ['role_id' => $user['role_id']], 'id'))
    ];

    echo "Total permissions for role: {$results[$userType]['total_permissions']}\n\n";
}

// Summary
echo "📊 Test Summary\n";
echo "==============\n";

$allTestsPass = true;

foreach ($results as $userType => $result) {
    $status = ($userType === 'super_admin' ?
        (count($result['accessible_restricted']) === count($restrictedPermissions) && $result['role_correct']) :
        (count($result['accessible_restricted']) === 0 && $result['role_correct']));

    echo "{$userType}: " . ($status ? "✅ PASS" : "❌ FAIL") . "\n";
    if (!$status) $allTestsPass = false;
}

echo "\n" . ($allTestsPass ? "🎉 All tests passed!" : "⚠️  Some tests failed. Please review the results above.") . "\n";

echo "\n🔒 Permission Restriction Status:\n";
echo "- Super Admin: " . $results['super_admin']['total_permissions'] . " permissions\n";
echo "- Regular Admin: " . $results['admin']['total_permissions'] . " permissions\n";
echo "- Restricted permissions: " . count($restrictedPermissions) . "\n";
?>
