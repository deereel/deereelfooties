<?php
session_start();
require_once 'auth/db.php';

// Test sidebar permission checks for temmy
$username = 'temmy';
$password = 'admin222';

echo "Testing sidebar permissions for $username...\n";

// Get admin user by username
$user = getAdminUserByUsername($username);

if ($user) {
    echo "User found: {$user['username']}\n";

    // Verify password
    if (verifyAdminPassword($password, $user['password'])) {
        echo "Password verified successfully!\n";

        // Set session
        $_SESSION['admin_user_id'] = $user['id'];
        $_SESSION['admin_username'] = $user['username'];
        $_SESSION['admin_role'] = $user['role_name'];
        $_SESSION['admin_name'] = $user['first_name'] . ' ' . $user['last_name'];

        echo "Session set successfully!\n";

        // Test permission functions
        function currentUserHasPermission($permissionName) {
            if (!isset($_SESSION['admin_user_id'])) {
                return false;
            }
            return userHasPermission($_SESSION['admin_user_id'], $permissionName);
        }

        // Test each permission
        $permissions = [
            'manage_backups',
            'view_system_health',
            'view_error_logs'
        ];

        echo "\nPermission checks:\n";
        foreach ($permissions as $perm) {
            $hasPerm = currentUserHasPermission($perm);
            echo "- $perm: " . ($hasPerm ? 'YES' : 'NO') . "\n";
        }

        echo "\nSidebar buttons that should be visible:\n";
        if (currentUserHasPermission('manage_backups')) {
            echo "- Backup Management\n";
        }
        if (currentUserHasPermission('view_system_health')) {
            echo "- System Health\n";
        }
        if (currentUserHasPermission('view_error_logs')) {
            echo "- Error Logging\n";
        }

    } else {
        echo "Password verification failed!\n";
    }
} else {
    echo "User not found!\n";
}
?>
