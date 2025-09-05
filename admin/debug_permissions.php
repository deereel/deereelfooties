<?php
session_start();
require_once '../auth/db.php';

// Debug permission checking
echo "<h1>Permission Debug</h1>";
echo "<pre>";

// Check session
echo "Session Data:\n";
echo "admin_user_id: " . (isset($_SESSION['admin_user_id']) ? $_SESSION['admin_user_id'] : 'NOT SET') . "\n";
echo "user_id: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'NOT SET') . "\n\n";

// Check if user is logged in
if (!isset($_SESSION['admin_user_id'])) {
    echo "❌ User is not logged in as admin\n";
    exit;
}

$userId = $_SESSION['admin_user_id'];
echo "User ID: $userId\n\n";

// Test getUserRole function
echo "Testing getUserRole function:\n";
$userRole = getUserRole($userId);
if ($userRole) {
    echo "✅ Role found: " . print_r($userRole, true) . "\n";
    echo "Role Name: " . $userRole['name'] . "\n";
    echo "Is Super Admin: " . ($userRole['name'] === 'Super Admin' ? 'YES' : 'NO') . "\n";
} else {
    echo "❌ No role found for user\n";
}

// Test userHasPermission function
echo "\nTesting userHasPermission function:\n";
$permissionsToTest = ['view_products', 'add_products', 'edit_products', 'delete_products'];
foreach ($permissionsToTest as $permission) {
    $hasPermission = userHasPermission($userId, $permission);
    echo "$permission: " . ($hasPermission ? '✅ YES' : '❌ NO') . "\n";
}

// Check database tables
echo "\nDatabase Tables Check:\n";
try {
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_NUM);
    echo "Found " . count($tables) . " tables:\n";
    foreach ($tables as $table) {
        echo "- " . $table[0] . "\n";
    }
} catch (Exception $e) {
    echo "❌ Error checking tables: " . $e->getMessage() . "\n";
}

// Check admin_users table
echo "\nAdmin Users Check:\n";
try {
    $stmt = $pdo->prepare("SELECT au.*, r.name as role_name FROM admin_users au JOIN roles r ON au.role_id = r.id WHERE au.id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        echo "✅ User found: " . print_r($user, true) . "\n";
    } else {
        echo "❌ User not found in database\n";
    }
} catch (Exception $e) {
    echo "❌ Error checking user: " . $e->getMessage() . "\n";
}

echo "</pre>";
?>
