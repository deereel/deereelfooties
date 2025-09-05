<?php
require_once 'auth/db.php';

// Delete existing users
deleteData('admin_users', []);

// Create new users with proper password hashing
$users = [
    [
        'username' => 'oladayo',
        'email' => 'oladayo@drf.com',
        'password' => password_hash('admin333', PASSWORD_DEFAULT),
        'first_name' => 'Oladayo',
        'last_name' => 'Admin',
        'role_id' => 1,
        'is_active' => 1
    ],
    [
        'username' => 'temmy',
        'email' => 'temmy@drf.com',
        'password' => password_hash('admin222', PASSWORD_DEFAULT),
        'first_name' => 'Temmy',
        'last_name' => 'Admin',
        'role_id' => 2,
        'is_active' => 1
    ]
];

foreach ($users as $user) {
    $result = insertData('admin_users', $user);
    if (is_array($result) && isset($result['error'])) {
        echo "Error creating user {$user['username']}: " . $result['error'] . "\n";
    } else {
        echo "Successfully created user: {$user['username']}\n";
        echo "Password hash: {$user['password']}\n\n";
    }
}

echo "Admin users creation completed!\n";
echo "Login credentials:\n";
echo "Super Admin: oladayo / admin333\n";
echo "Admin: temmy / admin222\n";
?>
