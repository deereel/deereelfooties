<?php
header('Content-Type: application/json');
session_start();

require_once '../auth/db.php';
require_once '../classes/InventoryManager.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$productId = $_POST['product_id'] ?? '';
$quantity = (int)($_POST['quantity'] ?? 0);
$type = $_POST['type'] ?? '';
$reason = $_POST['reason'] ?? '';

if (!$productId || !$quantity || !$type) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

try {
    $inventory = new InventoryManager($pdo);
    $inventory->updateStock($productId, $quantity, $type, $reason);
    
    echo json_encode(['success' => true, 'message' => 'Stock updated successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>