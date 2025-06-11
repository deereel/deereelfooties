<?php
// API endpoint to get a specific address
header('Content-Type: application/json');
require_once '../auth/db.php';

// Check if user is logged in
session_start();
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Get request parameters
$addressId = isset($_GET['address_id']) ? intval($_GET['address_id']) : 0;
$userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

// Validate parameters
if ($addressId <= 0 || $userId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid request parameters']);
    exit;
}

try {
    // Query to get address details
    $stmt = $pdo->prepare("SELECT * FROM user_addresses WHERE address_id = ? AND user_id = ?");
    $stmt->execute([$addressId, $userId]);
    $address = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$address) {
        echo json_encode(['success' => false, 'message' => 'Address not found or does not belong to user']);
        exit;
    }
    
    // Return success response with address
    echo json_encode(['success' => true, 'address' => $address]);
} catch (PDOException $e) {
    // Return error response
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}