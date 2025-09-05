<?php
require_once 'auth/db.php';

try {
    // Get all products with their stock levels and thresholds
    $stmt = $pdo->query("SELECT product_id, name, stock_quantity, low_stock_threshold
                        FROM products
                        ORDER BY stock_quantity ASC");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Product Stock Levels:\n";
    echo str_repeat("-", 80) . "\n";
    printf("%-5s %-30s %-10s %-15s %-10s\n", "ID", "Name", "Stock", "Threshold", "Status");
    echo str_repeat("-", 80) . "\n";

    $lowStockCount = 0;
    foreach($products as $product) {
        $status = ($product['stock_quantity'] <= $product['low_stock_threshold']) ? "LOW STOCK" : "OK";
        if ($product['stock_quantity'] <= $product['low_stock_threshold']) {
            $lowStockCount++;
        }

        printf("%-5s %-30s %-10s %-15s %-10s\n",
            $product['product_id'],
            substr($product['name'], 0, 30),
            $product['stock_quantity'],
            $product['low_stock_threshold'],
            $status
        );
    }

    echo str_repeat("-", 80) . "\n";
    echo "Total products: " . count($products) . "\n";
    echo "Low stock products: $lowStockCount\n\n";

    // Check current active alerts
    $stmt = $pdo->query("SELECT a.*, p.name, p.stock_quantity, p.low_stock_threshold
                        FROM low_stock_alerts a
                        JOIN products p ON a.product_id = p.product_id
                        WHERE a.status = 'active'
                        ORDER BY a.created_at DESC");
    $activeAlerts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Active Low Stock Alerts:\n";
    echo str_repeat("-", 100) . "\n";
    printf("%-5s %-30s %-8s %-10s %-12s %-15s\n", "ID", "Product", "Stock", "Threshold", "Alert Date", "Still Low?");
    echo str_repeat("-", 100) . "\n";

    foreach($activeAlerts as $alert) {
        $stillLow = ($alert['stock_quantity'] <= $alert['low_stock_threshold']) ? "YES" : "NO";
        printf("%-5s %-30s %-8s %-10s %-12s %-15s\n",
            $alert['product_id'],
            substr($alert['name'], 0, 30),
            $alert['stock_quantity'],
            $alert['low_stock_threshold'],
            date('Y-m-d', strtotime($alert['created_at'])),
            $stillLow
        );
    }

    echo str_repeat("-", 100) . "\n";
    echo "Active alerts: " . count($activeAlerts) . "\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
