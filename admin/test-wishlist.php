<?php
// Test the wishlist API
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';

echo "<h2>Testing Wishlist API</h2>";

// Create wishlist_items table if it doesn't exist
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS wishlist_items (
        wishlist_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        product_id VARCHAR(255) NOT NULL,
        product_name VARCHAR(255) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        image VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "<p>Wishlist table created or already exists</p>";
} catch (PDOException $e) {
    echo "<p>Error creating wishlist table: " . $e->getMessage() . "</p>";
}

// Test adding an item to wishlist
$testUserId = 1; // Use a test user ID
$testProductId = 'test-product-1';
$testProductName = 'Test Product';
$testPrice = 85000;
$testImage = '/images/penny loafer 600.webp';

try {
    // Check if item already exists
    $stmt = $pdo->prepare("SELECT wishlist_id FROM wishlist_items WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$testUserId, $testProductId]);
    
    if ($stmt->fetch()) {
        echo "<p>Test item already in wishlist</p>";
    } else {
        // Add to wishlist
        $stmt = $pdo->prepare("INSERT INTO wishlist_items (user_id, product_id, product_name, price, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $testUserId,
            $testProductId,
            $testProductName,
            $testPrice,
            $testImage
        ]);
        
        echo "<p>Test item added to wishlist</p>";
    }
} catch (PDOException $e) {
    echo "<p>Error adding to wishlist: " . $e->getMessage() . "</p>";
}

// Test getting wishlist items
try {
    $stmt = $pdo->prepare("SELECT * FROM wishlist_items WHERE user_id = ?");
    $stmt->execute([$testUserId]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Wishlist Items for User ID $testUserId:</h3>";
    echo "<pre>";
    print_r($items);
    echo "</pre>";
} catch (PDOException $e) {
    echo "<p>Error getting wishlist items: " . $e->getMessage() . "</p>";
}
?>
