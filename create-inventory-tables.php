<?php
require_once 'auth/db.php';

try {
    // Add inventory columns to products table
    $pdo->exec("ALTER TABLE products 
        ADD COLUMN IF NOT EXISTS stock_quantity INT DEFAULT 0,
        ADD COLUMN IF NOT EXISTS low_stock_threshold INT DEFAULT 5,
        ADD COLUMN IF NOT EXISTS track_inventory TINYINT(1) DEFAULT 1,
        ADD COLUMN IF NOT EXISTS sku VARCHAR(50) UNIQUE");
    
    // Create inventory_transactions table
    $pdo->exec("CREATE TABLE IF NOT EXISTS inventory_transactions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT,
        transaction_type ENUM('in', 'out', 'adjustment') NOT NULL,
        quantity INT NOT NULL,
        previous_stock INT NOT NULL,
        new_stock INT NOT NULL,
        reason VARCHAR(255),
        reference_id VARCHAR(50),
        created_by INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
    )");
    
    // Create low_stock_alerts table
    $pdo->exec("CREATE TABLE IF NOT EXISTS low_stock_alerts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT,
        current_stock INT,
        threshold INT,
        status ENUM('active', 'resolved') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        resolved_at TIMESTAMP NULL,
        FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
    )");
    
    echo "✅ Inventory tables created successfully!<br>";
    echo "✅ Added stock_quantity, low_stock_threshold, track_inventory, sku to products<br>";
    echo "✅ Created inventory_transactions table<br>";
    echo "✅ Created low_stock_alerts table<br>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>