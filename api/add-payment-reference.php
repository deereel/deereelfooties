<?php
require_once '../auth/db.php';

try {
    // Add payment_reference column to orders table if it doesn't exist
    $stmt = $pdo->prepare("SHOW COLUMNS FROM orders LIKE 'payment_reference'");
    $stmt->execute();
    
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE orders ADD COLUMN payment_reference VARCHAR(255) NULL");
        echo "Payment reference column added successfully.\n";
    } else {
        echo "Payment reference column already exists.\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>