<?php
require_once 'auth/db.php';

// Test permissions for both users
$users = [
    ['username' => 'oladayo', 'expected_role' => 'super_admin'],
    ['username' => 'temmy', 'expected_role' => 'admin']
];

echo "Testing Admin Permissions:\n";
echo "=========================\n\n";

foreach ($users as $testUser) {
    $username = $testUser['username'];
    $expectedRole = $testUser['expected_role'];

    echo "Testing user: {$username} (Expected role: {$expectedRole})\n";
    echo "--------------------------------------------------\n";

    // Get user
    $user = getAdminUserByUsername($username);

    if ($user) {
        echo "✅ User found: {$user['username']} (Role: {$user['role_name']})\n";

        // Test key permissions
        $permissionsToTest = [
            'view_dashboard' => 'View Dashboard',
            'view_orders' => 'View Orders',
            'manage_orders' => 'Manage Orders',
            'view_users' => 'View Users',
            'manage_users' => 'Manage Users'
        ];

        foreach ($permissionsToTest as $permission => $description) {
            $hasPermission = userHasPermission($user['id'], $permission);
            $status = $hasPermission ? '✅' : '❌';
            echo "  {$status} {$description} ({$permission})\n";
        }

        echo "\n";
    } else {
        echo "❌ User not found\n\n";
    }
}

echo "Permission testing completed!\n";
?>
