<?php
require_once 'auth/db.php';
require_once 'classes/InventoryManager.php';

try {
    echo "Testing manual stock reduction (should use 'out' type)...\n";
    
    // Create a test product if it doesn't exist
    $stmt = $pdo->prepare("INSERT IGNORE INTO products (name, price, stock_quantity) VALUES ('Test Product Manual', 1000, 20)");
    $stmt->execute();
    $productId = $pdo->lastInsertId();
    
    // If product already exists, get its ID
    if ($productId == 0) {
        $stmt = $pdo->query("SELECT product_id FROM products WHERE name = 'Test Product Manual'");
        $productId = $stmt->fetchColumn();
    }
    
    echo "Testing manual stock reduction for product #$productId\n";
    
    // Use InventoryManager to manually reduce stock (should use 'out' type)
    $inventory = new InventoryManager($pdo);
    $inventory->updateStock($productId, 3, 'out', 'Manual stock adjustment');
    
    echo "Manual stock reduction completed!\n";
    
    // Check the inventory transaction
    $stmt = $pdo->query("SELECT * FROM inventory_transactions WHERE product_id = $productId ORDER BY created_at DESC LIMIT 1");
    $transaction = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($transaction) {
        echo "Transaction Type: " . $transaction['transaction_type'] . "\n";
        echo "Quantity: " . $transaction['quantity'] . "\n";
        echo "Reason: " . $transaction['reason'] . "\n";
        
        if ($transaction['transaction_type'] === 'out') {
            echo "✅ SUCCESS: Manual reduction correctly uses 'out' type!\n";
        } else {
            echo "❌ FAILED: Manual reduction should use 'out' type but got '" . $transaction['transaction_type'] . "'\n";
        }
    } else {
        echo "❌ No inventory transaction found\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
