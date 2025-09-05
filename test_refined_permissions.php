<?php
require_once 'auth/db.php';

// Test refined permissions for both users
$users = [
    ['username' => 'oladayo', 'expected_role' => 'super_admin'],
    ['username' => 'temmy', 'expected_role' => 'admin']
];

echo "Testing Refined Admin Permissions:\n";
echo "===================================\n\n";

foreach ($users as $testUser) {
    $username = $testUser['username'];
    $expectedRole = $testUser['expected_role'];

    echo "Testing user: {$username} (Role: {$expectedRole})\n";
    echo "---------------------------------------------\n";

    // Get user
    $user = getAdminUserByUsername($username);

    if ($user) {
        echo "✅ User found: {$user['username']} (Role: {$user['role_name']})\n\n";

        // Test settings permissions
        echo "Settings Permissions:\n";
        $settingsPermissions = [
            'view_settings' => 'View Settings',
            'manage_settings' => 'Manage Settings'
        ];

        foreach ($settingsPermissions as $permission => $description) {
            $hasPermission = userHasPermission($user['id'], $permission);
            $status = $hasPermission ? '✅' : '❌';
            echo "  {$status} {$description} ({$permission})\n";
        }

        echo "\nProduct Permissions:\n";
        $productPermissions = [
            'view_products' => 'View Products',
            'add_products' => 'Add Products',
            'edit_products' => 'Edit Products',
            'delete_products' => 'Delete Products',
            'manage_products' => 'Manage Products (Legacy)'
        ];

        foreach ($productPermissions as $permission => $description) {
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
