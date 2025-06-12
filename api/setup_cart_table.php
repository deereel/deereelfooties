<?php
require_once '../auth/db.php';

// Create cart_items table if it doesn't exist
$cartItemsTable = "
CREATE TABLE IF NOT EXISTS cart_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id VARCHAR(50) NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    color VARCHAR(50),
    size VARCHAR(20),
    material VARCHAR(50),
    width VARCHAR(20),
    quantity INT DEFAULT 1,
    image VARCHAR(255),
    is_custom TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

try {
    $pdo->exec($cartItemsTable);
    echo "Cart items table created successfully<br>";
} catch (PDOException $e) {
    echo "Error creating cart items table: " . $e->getMessage() . "<br>";
}

// Create event to clean up old cart items (older than 100 days)
$cleanupEvent = "
CREATE EVENT IF NOT EXISTS cleanup_old_cart_items
ON SCHEDULE EVERY 1 DAY
DO
    DELETE FROM cart_items 
    WHERE created_at < DATE_SUB(NOW(), INTERVAL 100 DAY)
    AND item_id NOT IN (SELECT cart_item_id FROM order_items)
";

try {
    // First, make sure event scheduler is enabled
    $pdo->exec("SET GLOBAL event_scheduler = ON");
    $pdo->exec($cleanupEvent);
    echo "Cleanup event created successfully<br>";
} catch (PDOException $e) {
    echo "Error creating cleanup event: " . $e->getMessage() . "<br>";
}

echo "Cart setup completed";
?>