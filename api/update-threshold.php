<?php
header('Content-Type: application/json');
session_start();

require_once '../auth/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$productId = $input['product_id'] ?? '';
$threshold = (int)($input['threshold'] ?? 0);

if (!$productId || $threshold < 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
    exit;
}

try {
    // Update low stock threshold
    $stmt = $pdo->prepare("UPDATE products SET low_stock_threshold = ? WHERE product_id = ?");
    $stmt->execute([$threshold, $productId]);
    
    // Check if this resolves any low stock alerts
    $stmt = $pdo->prepare("SELECT stock_quantity FROM products WHERE product_id = ?");
    $stmt->execute([$productId]);
    $currentStock = $stmt->fetchColumn();
    
    if ($currentStock > $threshold) {
        // Resolve low stock alert if stock is now above threshold
        $stmt = $pdo->prepare("UPDATE low_stock_alerts SET status = 'resolved', resolved_at = NOW() 
            WHERE product_id = ? AND status = 'active'");
        $stmt->execute([$productId]);
    }
    
    echo json_encode(['success' => true, 'message' => 'Threshold updated successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>