<?php
// api/create-order.php
require_once '../auth/db.php';
session_start();

// Set headers for JSON response
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Get user ID
$userId = $_SESSION['user_id'];

// Validate required fields
if (!isset($_POST['client_name']) || !isset($_POST['shipping_address']) || !isset($_POST['state'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Check if payment proof was uploaded
if (!isset($_FILES['payment_proof']) || $_FILES['payment_proof']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'Payment proof is required']);
    exit;
}

// Get form data
$clientName = $_POST['client_name'];
$clientPhone = $_POST['client_phone'] ?? '';
$shippingAddress = $_POST['shipping_address'];
$state = $_POST['state'];
$saveAddress = isset($_POST['save_address']) && $_POST['save_address'] === '1';

try {
    // Begin transaction
    $pdo->beginTransaction();
    
    // Get cart items
    $stmt = $pdo->prepare("SELECT * FROM cart_items WHERE user_id = ?");
    $stmt->execute([$userId]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($cartItems)) {
        echo json_encode(['success' => false, 'message' => 'Your cart is empty']);
        exit;
    }
    
    // Calculate total
    $total = 0;
    foreach ($cartItems as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    
    // Add shipping cost if applicable
    $shippingThreshold = $state === 'Lagos' ? 150000 : 250000;
    $shippingCost = $total >= $shippingThreshold ? 0 : ($state === 'Lagos' ? 2000 : 3500);
    $total += $shippingCost;
    
    // Save address if requested
    if ($saveAddress) {
        $stmt = $pdo->prepare("INSERT INTO user_addresses (user_id, name, full_name, phone, line1, city, state, country, is_default) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $userId,
            'Shipping Address',
            $clientName,
            $clientPhone,
            $shippingAddress,
            'City', // Default city
            $state,
            'Nigeria',
            0 // Not default
        ]);
    }
    
    // Create order
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, client_name, shipping_address, state, total, payment_status, order_status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $userId,
        $clientName,
        $shippingAddress,
        $state,
        $total,
        'uploaded', // Payment proof is being uploaded
        'processing'
    ]);
    
    $orderId = $pdo->lastInsertId();
    
    // Add order items
    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity, color, size, width) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($cartItems as $item) {
        $stmt->execute([
            $orderId,
            $item['product_id'],
            $item['product_name'],
            $item['price'],
            $item['quantity'],
            $item['color'] ?? '',
            $item['size'] ?? '',
            $item['width'] ?? ''
        ]);
    }
    
    // Handle payment proof upload
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/payment_proofs/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $fileExt = pathinfo($_FILES['payment_proof']['name'], PATHINFO_EXTENSION);
    $fileName = 'payment_' . $orderId . '_' . time() . '.' . $fileExt;
    $filePath = $uploadDir . $fileName;
    
    if (move_uploaded_file($_FILES['payment_proof']['tmp_name'], $filePath)) {
        // Save payment proof record
        $stmt = $pdo->prepare("INSERT INTO payment_proofs (order_id, user_id, file_path) VALUES (?, ?, ?)");
        $stmt->execute([
            $orderId,
            $userId,
            '/uploads/payment_proofs/' . $fileName
        ]);
    } else {
        throw new Exception('Failed to upload payment proof');
    }
    
    // Clear cart
    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
    $stmt->execute([$userId]);
    if ($stmt->rowCount() < 1) {
    throw new Exception('Cart clear failed or was already empty');
}

    
    // Commit transaction
    $pdo->commit();
    
    echo json_encode(['success' => true, 'message' => 'Order created successfully', 'order_id' => $orderId]);
} catch (Exception $e) {
    // Rollback transaction on error
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error creating order: ' . $e->getMessage()]);
}
?>