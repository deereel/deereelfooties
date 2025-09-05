<?php
session_start();
require_once 'auth/db.php';

// Test login for oladayo
$username = 'oladayo';
$password = 'admin333';

echo "Testing login for $username...\n";

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
        echo "Session data:\n";
        print_r($_SESSION);

        // Test permission
        $hasPermission = userHasPermission($user['id'], 'view_activity_logs');
        echo "\nHas view_activity_logs permission: " . ($hasPermission ? 'YES' : 'NO') . "\n";

        // Test activity logs function
        $activityLogs = getActivityLogs([], 5, 0);
        echo "\nActivity logs count: " . count($activityLogs) . "\n";

        if (count($activityLogs) > 0) {
            echo "Sample logs:\n";
            foreach($activityLogs as $log) {
                echo "- {$log['action']} by {$log['username']}\n";
            }
        }

    } else {
        echo "Password verification failed!\n";
    }
} else {
    echo "User not found!\n";
}
?>
