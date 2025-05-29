<?php
require_once '../auth/db.php';

// Create orders table
$ordersTable = "
CREATE TABLE IF NOT EXISTS orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    client_name VARCHAR(100) NOT NULL,
    shipping_address TEXT NOT NULL,
    state VARCHAR(50) NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    payment_status ENUM('pending', 'uploaded', 'confirmed', 'failed') DEFAULT 'pending',
    order_status ENUM('processing', 'production', 'shipping', 'delivered', 'cancelled') DEFAULT 'processing',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
)";

try {
    $pdo->exec($ordersTable);
    echo "Orders table created successfully<br>";
} catch (PDOException $e) {
    echo "Error creating orders table: " . $e->getMessage() . "<br>";
}

// Create order items table
$orderItemsTable = "
CREATE TABLE IF NOT EXISTS order_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id VARCHAR(50) NOT NULL,
    product_name VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL,
    color VARCHAR(50) NOT NULL,
    size VARCHAR(20) NOT NULL,
    width VARCHAR(20) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE
)";

try {
    $pdo->exec($orderItemsTable);
    echo "Order items table created successfully<br>";
} catch (PDOException $e) {
    echo "Error creating order items table: " . $e->getMessage() . "<br>";
}

// Create payment proofs table
$paymentProofsTable = "
CREATE TABLE IF NOT EXISTS payment_proofs (
    proof_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    user_id INT NULL,
    file_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
)";

try {
    $pdo->exec($paymentProofsTable);
    echo "Payment proofs table created successfully<br>";
} catch (PDOException $e) {
    echo "Error creating payment proofs table: " . $e->getMessage() . "<br>";
}

echo "Setup completed";
?>