<?php
require_once 'auth/db.php';

// Set all products to have a threshold of 5
$newThreshold = 3;

try {
    $stmt = $pdo->prepare("UPDATE products SET low_stock_threshold = ?");
    $stmt->execute([$newThreshold]);
    
    echo "✅ Updated all product thresholds to {$newThreshold}";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>