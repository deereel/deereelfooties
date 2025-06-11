<?php
// API endpoint to get a specific order details
header('Content-Type: application/json');
require_once '../auth/db.php';

// Check if user is logged in
session_start();
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Get request parameters
$orderId = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

// Validate parameters
if ($orderId <= 0 || $userId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid request parameters']);
    exit;
}

try {
    // Query to get order details
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ? AND user_id = ?");
    $stmt->execute([$orderId, $userId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Order not found or does not belong to user']);
        exit;
    }
    
    // Get order items
    $stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
    $stmt->execute([$orderId]);
    $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get order progress
    $stmt = $pdo->prepare("SELECT * FROM order_progress WHERE order_id = ? ORDER BY update_date DESC");
    $stmt->execute([$orderId]);
    $order['progress'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get payment proof if exists
    $stmt = $pdo->prepare("SELECT * FROM payment_proofs WHERE order_id = ?");
    $stmt->execute([$orderId]);
    $order['payment_proof'] = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Return success response with order details
    echo json_encode(['success' => true, 'order' => $order]);
} catch (PDOException $e) {
    // Return error response
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}