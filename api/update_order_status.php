<?php
require_once '../auth/db.php';
session_start();

// Set headers for JSON response
header('Content-Type: application/json');

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Handle different HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get data from request
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['order_id']) || !isset($data['status']) || !isset($data['status_note'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit;
}

$orderId = $data['order_id'];
$status = $data['status'];
$statusNote = $data['status_note'];

// Validate status
$validStatuses = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Completed', 'Cancelled'];
if (!in_array($status, $validStatuses)) {
    echo json_encode(['success' => false, 'message' => 'Invalid status']);
    exit;
}

try {
    // Check if order exists
    $checkStmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ?");
    $checkStmt->execute([$orderId]);
    
    if (!$checkStmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        exit;
    }
    
    // Update order status
    $updateStmt = $pdo->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    $updateStmt->execute([$status, $orderId]);
    
    // Add progress update
    $progressStmt = $pdo->prepare("INSERT INTO order_progress (order_id, status_update) VALUES (?, ?)");
    $progressStmt->execute([$orderId, $statusNote]);
    
    echo json_encode(['success' => true, 'message' => 'Order status updated successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>