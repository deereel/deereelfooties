<?php
session_start();
require_once '../auth/db.php';

echo "<h1>Role Check</h1>";
echo "<pre>";

// Check all roles in the database
try {
    $stmt = $pdo->query("SELECT * FROM roles");
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "All roles in database:\n";
    foreach ($roles as $role) {
        echo "ID: {$role['id']}, Name: '{$role['name']}'\n";
    }
    echo "\n";

    // Check admin users and their roles
    echo "Admin users and their roles:\n";
    $stmt = $pdo->prepare("SELECT au.id, au.username, r.name as role_name FROM admin_users au JOIN roles r ON au.role_id = r.id");
    $stmt->execute();
    $adminUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($adminUsers as $user) {
        echo "User ID: {$user['id']}, Username: {$user['username']}, Role: '{$user['role_name']}'\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "</pre>";
?>
