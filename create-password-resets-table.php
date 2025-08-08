<?php
require_once 'auth/db.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS password_resets (
        user_id INT PRIMARY KEY,
        token VARCHAR(64) UNIQUE,
        expires_at DATETIME,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_token (token),
        INDEX idx_expires (expires_at)
    )";
    
    $pdo->exec($sql);
    echo "✅ password_resets table created successfully!<br>";
    
    // Verify table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'password_resets'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Table verified - password_resets exists in database<br>";
        
        // Show table structure
        $stmt = $pdo->query("DESCRIBE password_resets");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<h3>Table Structure:</h3>";
        foreach ($columns as $column) {
            echo "- {$column['Field']}: {$column['Type']}<br>";
        }
    } else {
        echo "❌ Table creation failed";
    }
    
} catch (Exception $e) {
    echo "❌ Error creating table: " . $e->getMessage();
}
?>