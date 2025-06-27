<?php
require_once '../auth/db.php';

// Create cart_items table if it doesn't exist
$cartItemsTable = "
CREATE TABLE IF NOT EXISTS cart_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  product_id VARCHAR(50) NOT NULL,
  product_name VARCHAR(255) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  quantity INT NOT NULL DEFAULT 1,
  color VARCHAR(50),
  size VARCHAR(10),
  width VARCHAR(10),
  image VARCHAR(255),
  date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

try {
    $pdo->exec($cartItemsTable);
    echo "Cart items table created successfully<br>";
} catch (PDOException $e) {
    echo "Error creating cart items table: " . $e->getMessage() . "<br>";
}

echo "Cart table setup completed";
?>