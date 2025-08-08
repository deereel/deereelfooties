<?php
require_once 'auth/db.php';
require_once 'classes/InventoryManager.php';

try {
    // Set default stock quantities for existing products
    $stmt = $pdo->query("SELECT product_id, name FROM products WHERE stock_quantity IS NULL OR stock_quantity = 0");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $inventory = new InventoryManager($pdo);
    
    foreach ($products as $product) {
        // Set random stock between 10-50 for demo
        $randomStock = rand(10, 50);
        $inventory->updateStock($product['product_id'], $randomStock, 'adjustment', 'Initial inventory setup');
        echo "✅ Set {$product['name']} stock to {$randomStock}<br>";
    }
    
    // Generate SKUs for products without them
    $stmt = $pdo->query("SELECT product_id, name FROM products WHERE sku IS NULL OR sku = ''");
    $productsWithoutSku = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($productsWithoutSku as $product) {
        $sku = 'DRF-' . str_pad($product['product_id'], 4, '0', STR_PAD_LEFT);
        $stmt = $pdo->prepare("UPDATE products SET sku = ? WHERE product_id = ?");
        $stmt->execute([$sku, $product['product_id']]);
        echo "✅ Generated SKU {$sku} for {$product['name']}<br>";
    }
    
    echo "<br>🎉 Inventory system initialized successfully!<br>";
    echo "<a href='admin/inventory.php'>Go to Inventory Dashboard</a>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>