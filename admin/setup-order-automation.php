<?php
require_once '../auth/db.php';

try {
    // Create order_status_history table
    $sql = "CREATE TABLE IF NOT EXISTS order_status_history (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        status VARCHAR(50) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);
    echo "✅ order_status_history table created<br>";

    // Create order_items table if not exists
    $sql = "CREATE TABLE IF NOT EXISTS order_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        product_id INT NOT NULL,
        quantity INT NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);
    echo "✅ order_items table created<br>";

    // Add status column to orders if not exists
    try {
        $pdo->exec("ALTER TABLE orders ADD COLUMN status VARCHAR(50) DEFAULT 'pending'");
        echo "✅ Added status column to orders table<br>";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "ℹ️ Status column already exists<br>";
        } else {
            throw $e;
        }
    }

    echo "<br>🎉 Order automation setup complete!";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>