<?php
require_once 'auth/db.php';
require_once 'classes/InventoryManager.php';

try {
    $inventory = new InventoryManager($pdo);

    echo "🔧 Fixing Low Stock Alerts...\n\n";

    // Step 1: Resolve stale alerts (products that are no longer low stock)
    echo "1. Resolving stale alerts...\n";
    $stmt = $pdo->prepare("UPDATE low_stock_alerts SET status = 'resolved', resolved_at = NOW()
        WHERE status = 'active' AND product_id IN (
            SELECT p.product_id FROM products p WHERE p.stock_quantity > p.low_stock_threshold
        )");
    $resolvedCount = $stmt->execute() ? $stmt->rowCount() : 0;
    echo "   ✅ Resolved $resolvedCount stale alerts\n\n";

    // Step 2: Create alerts for products that are low stock but don't have active alerts
    echo "2. Creating missing alerts for low stock products...\n";

    $stmt = $pdo->query("SELECT product_id, stock_quantity, low_stock_threshold, name
        FROM products
        WHERE stock_quantity <= low_stock_threshold
        AND product_id NOT IN (
            SELECT product_id FROM low_stock_alerts WHERE status = 'active'
        )");

    $missingAlerts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $createdCount = 0;

    foreach($missingAlerts as $product) {
        $stmt = $pdo->prepare("INSERT INTO low_stock_alerts
            (product_id, current_stock, threshold) VALUES (?, ?, ?)");
        $stmt->execute([$product['product_id'], $product['stock_quantity'], $product['low_stock_threshold']]);
        $createdCount++;
        echo "   📢 Created alert for: {$product['name']} (Stock: {$product['stock_quantity']})\n";
    }

    echo "   ✅ Created $createdCount new alerts\n\n";

    // Step 3: Show current status
    echo "3. Current Low Stock Status:\n";

    $lowStockProducts = $inventory->getLowStockProducts();
    if (count($lowStockProducts) > 0) {
        echo "   🚨 Active low stock alerts:\n";
        foreach($lowStockProducts as $product) {
            echo "      - {$product['name']} (ID: {$product['product_id']}) - Stock: {$product['stock_quantity']}, Threshold: {$product['low_stock_threshold']}\n";
        }
    } else {
        echo "   ✅ No active low stock alerts\n";
    }

    echo "\n🎉 Low stock alerts have been fixed!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
