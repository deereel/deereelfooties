<?php
// API endpoint to set an address as default
header('Content-Type: application/json');
require_once '../auth/db.php';

// Check if user is logged in
session_start();
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Get request data
$data = json_decode(file_get_contents('php://input'), true);
$userId = isset($data['user_id']) ? intval($data['user_id']) : 0;
$addressId = isset($data['address_id']) ? intval($data['address_id']) : 0;

// Validate data
if ($userId <= 0 || $addressId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid request data']);
    exit;
}

try {
    // Verify the address belongs to the user
    $stmt = $pdo->prepare("SELECT * FROM user_addresses WHERE address_id = ? AND user_id = ?");
    $stmt->execute([$addressId, $userId]);
    $address = $stmt->fetch();
    
    if (!$address) {
        echo json_encode(['success' => false, 'message' => 'Address not found or does not belong to user']);
        exit;
    }
    
    // Begin transaction
    $pdo->beginTransaction();
    
    // Set all addresses to non-default
    $stmt = $pdo->prepare("UPDATE user_addresses SET is_default = 0 WHERE user_id = ?");
    $stmt->execute([$userId]);
    
    // Set the selected address as default
    $stmt = $pdo->prepare("UPDATE user_addresses SET is_default = 1 WHERE address_id = ?");
    $stmt->execute([$addressId]);
    
    // Commit transaction
    $pdo->commit();
    
    // Return success response
    echo json_encode(['success' => true, 'message' => 'Default address updated successfully']);
} catch (PDOException $e) {
    // Rollback transaction on error
    $pdo->rollBack();
    
    // Return error response
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}