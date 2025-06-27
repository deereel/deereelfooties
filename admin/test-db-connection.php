<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';

try {
    $pdo = getDBConnection();
    echo "✅ Database connection successful!<br>";
    
    // Test if user_addresses table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'user_addresses'");
    if ($stmt->rowCount() > 0) {
        echo "✅ user_addresses table exists!<br>";
    } else {
        echo "❌ user_addresses table does not exist. You need to create it.<br>";
    }
    
    // Test if users table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "✅ users table exists!<br>";
    } else {
        echo "❌ users table does not exist.<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage();
}
?>