<?php
require_once '../auth/db.php';

// Create orders table if it doesn't exist
$ordersTable = "
CREATE TABLE IF NOT EXISTS orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10, 2),
    status ENUM('Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled') DEFAULT 'Pending',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

try {
    $pdo->exec($ordersTable);
    echo "Orders table created successfully<br>";
} catch (PDOException $e) {
    echo "Error creating orders table: " . $e->getMessage() . "<br>";
}

// Create payment_proof table if it doesn't exist
$paymentProofTable = "
CREATE TABLE IF NOT EXISTS payment_proof (
    proof_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    proof_image VARCHAR(255),
    proof_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE
)";

try {
    $pdo->exec($paymentProofTable);
    echo "Payment proof table created successfully<br>";
} catch (PDOException $e) {
    echo "Error creating payment proof table: " . $e->getMessage() . "<br>";
}

// Create order_progress table if it doesn't exist
$orderProgressTable = "
CREATE TABLE IF NOT EXISTS order_progress (
    progress_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    status_update TEXT,
    update_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE
)";

try {
    $pdo->exec($orderProgressTable);
    echo "Order progress table created successfully<br>";
} catch (PDOException $e) {
    echo "Error creating order progress table: " . $e->getMessage() . "<br>";
}

// Create uploads directory for payment proofs
$uploadDir = "../uploads/payment_proofs/";
if (!file_exists($uploadDir)) {
    if (mkdir($uploadDir, 0777, true)) {
        echo "Uploads directory created successfully<br>";
    } else {
        echo "Error creating uploads directory<br>";
    }
}

echo "Setup completed";
?>