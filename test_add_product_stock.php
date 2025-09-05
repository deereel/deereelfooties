<?php
require_once 'auth/db.php';

// Test adding a product with stock quantity
echo "Testing Add Product with Stock Quantity\n";
echo "=======================================\n\n";

// Test data
$testProduct = [
    'name' => 'Test Product with Stock',
    'slug' => 'test-product-with-stock',
    'price' => 5000,
    'stock_quantity' => 25,
    'gender' => 'unisex',
    'category' => 'shoes',
    'type' => 'oxford',
    'main_image' => '/images/test-product.webp',
    'low_stock_threshold' => 5
];

try {
    // Insert test product
    $stmt = $pdo->prepare("INSERT INTO products
        (name, slug, price, stock_quantity, low_stock_threshold, gender, category, type, main_image)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->execute([
        $testProduct['name'],
        $testProduct['slug'],
        $testProduct['price'],
        $testProduct['stock_quantity'],
        $testProduct['low_stock_threshold'],
        $testProduct['gender'],
        $testProduct['category'],
        $testProduct['type'],
        $testProduct['main_image']
    ]);

    $productId = $pdo->lastInsertId();

    echo "✅ Product added successfully!\n";
    echo "   Product ID: $productId\n";
    echo "   Name: {$testProduct['name']}\n";
    echo "   Stock Quantity: {$testProduct['stock_quantity']}\n";
    echo "   Low Stock Threshold: {$testProduct['low_stock_threshold']}\n\n";

    // Verify the product was added with correct stock
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        echo "✅ Verification successful!\n";
        echo "   Stock in database: {$product['stock_quantity']}\n";
        echo "   Expected stock: {$testProduct['stock_quantity']}\n";

        if ($product['stock_quantity'] == $testProduct['stock_quantity']) {
            echo "   ✅ Stock quantity matches!\n";
        } else {
            echo "   ❌ Stock quantity mismatch!\n";
        }

        // Check if low stock alert was created
        $stmt = $pdo->prepare("SELECT * FROM low_stock_alerts WHERE product_id = ? AND status = 'active'");
        $stmt->execute([$productId]);
        $alert = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($alert) {
            echo "   ❌ Unexpected low stock alert created (stock is above threshold)\n";
        } else {
            echo "   ✅ No low stock alert created (correct behavior)\n";
        }
    } else {
        echo "❌ Product not found in database!\n";
    }

    // Clean up test product
    $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->execute([$productId]);
    echo "\n🧹 Test product cleaned up\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\nTest completed!\n";
?>
