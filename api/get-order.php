<?php
// API endpoint to get a specific order details
header('Content-Type: application/json');
require_once '../auth/db.php';

// Check if user is logged in
session_start();
// Remove session check as user data is passed via parameters

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
    try {
        $stmt = $pdo->prepare("SELECT * FROM order_progress WHERE order_id = ? ORDER BY updated_at DESC");
        $stmt->execute([$orderId]);
        $order['progress'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $order['progress'] = [];
    }
    
    // Get payment proof if exists
    try {
        $stmt = $pdo->prepare("SELECT * FROM payment_proof WHERE order_id = ?");
        $stmt->execute([$orderId]);
        $order['payment_proof'] = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $order['payment_proof'] = null;
    }
    
    // Return success response with order details
    echo json_encode(['success' => true, 'order' => $order]);
} catch (PDOException $e) {
    // Return error response
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}