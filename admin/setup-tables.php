<?php
// Script to set up necessary tables for the admin interface
require_once '../auth/db.php';

try {
    // Create order_progress table if it doesn't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS order_progress (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        status_update VARCHAR(255) NOT NULL,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "Order progress table created or already exists.<br>";
    
    // Check if orders table exists, if not create it
    try {
        $pdo->query("SELECT 1 FROM orders LIMIT 1");
        echo "Orders table already exists.<br>";
        
        // Check if payment_confirmed column exists in orders table
        try {
            $pdo->query("SELECT payment_confirmed FROM orders LIMIT 1");
            echo "Payment confirmed column already exists in orders table.<br>";
        } catch (PDOException $e) {
            // Add payment_confirmed column to orders table
            $pdo->exec("ALTER TABLE orders ADD COLUMN payment_confirmed TINYINT(1) DEFAULT 0");
            echo "Added payment_confirmed column to orders table.<br>";
        }
    } catch (PDOException $e) {
        // Create orders table
        $pdo->exec("CREATE TABLE orders (
            order_id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NULL,
            customer_name VARCHAR(255) NOT NULL,
            customer_email VARCHAR(255) NOT NULL,
            customer_phone VARCHAR(50) NULL,
            shipping_address TEXT NULL,
            total_amount DECIMAL(10,2) NOT NULL,
            shipping_fee DECIMAL(10,2) DEFAULT 0,
            status VARCHAR(50) DEFAULT 'Pending',
            payment_confirmed TINYINT(1) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        echo "Orders table created.<br>";
    }
    
    // Check if order_items table exists, if not create it
    try {
        $pdo->query("SELECT 1 FROM order_items LIMIT 1");
        echo "Order items table already exists.<br>";
    } catch (PDOException $e) {
        // Create order_items table
        $pdo->exec("CREATE TABLE order_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            product_id INT NULL,
            product_name VARCHAR(255) NOT NULL,
            product_options TEXT NULL,
            price DECIMAL(10,2) NOT NULL,
            quantity INT NOT NULL DEFAULT 1,
            FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE
        )");
        echo "Order items table created.<br>";
    }
    
    // Check if payment_proof table exists, if not create it
    try {
        $pdo->query("SELECT 1 FROM payment_proof LIMIT 1");
        echo "Payment proof table already exists.<br>";
    } catch (PDOException $e) {
        // Create payment_proof table
        $pdo->exec("CREATE TABLE payment_proof (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            proof_image VARCHAR(255) NOT NULL,
            user_id INT NULL,
            customer_name VARCHAR(255) NULL,
            uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE
        )");
        echo "Payment proof table created.<br>";
    }
    
    echo "<br>All tables have been set up successfully!";
    
} catch (PDOException $e) {
    echo "Error setting up tables: " . $e->getMessage();
}
?>