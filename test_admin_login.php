<?php
require_once 'auth/db.php';

// Test login credentials
$testCredentials = [
    ['username' => 'oladayo', 'password' => 'admin333'],
    ['username' => 'temmy', 'password' => 'admin222'],
    ['username' => 'oladayo', 'password' => 'wrongpassword'],
    ['username' => 'nonexistent', 'password' => 'admin333']
];

echo "Testing Admin Login Credentials:\n";
echo "================================\n\n";

foreach ($testCredentials as $test) {
    $username = $test['username'];
    $password = $test['password'];

    echo "Testing: {$username} / {$password}\n";

    // Get admin user by username
    $user = getAdminUserByUsername($username);

    if ($user) {
        echo "  User found: {$user['username']} (Role: {$user['role_name']})\n";

        // Verify password
        if (verifyAdminPassword($password, $user['password'])) {
            echo "  ✅ Password verification: SUCCESS\n";
            echo "  ✅ Login would be successful\n";
        } else {
            echo "  ❌ Password verification: FAILED\n";
            echo "  ❌ Login would fail\n";
        }
    } else {
        echo "  ❌ User not found\n";
        echo "  ❌ Login would fail\n";
    }

    echo "\n";
}

echo "Test completed!\n";
?>
