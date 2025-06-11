<?php
// API endpoint to delete an address
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

// Debug
error_log("Delete address request: " . print_r($data, true));
error_log("User ID: $userId, Address ID: $addressId");

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
    
    // Check if this is the default address
    $isDefault = $address['is_default'] == 1;
    
    // Delete the address
    $stmt = $pdo->prepare("DELETE FROM user_addresses WHERE address_id = ? AND user_id = ?");
    $stmt->execute([$addressId, $userId]);
    
    // If this was the default address, set another address as default if available
    if ($isDefault) {
        $stmt = $pdo->prepare("SELECT address_id FROM user_addresses WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$userId]);
        $newDefault = $stmt->fetch();
        
        if ($newDefault) {
            $stmt = $pdo->prepare("UPDATE user_addresses SET is_default = 1 WHERE address_id = ?");
            $stmt->execute([$newDefault['address_id']]);
        }
    }
    
    // Return success response
    echo json_encode(['success' => true, 'message' => 'Address deleted successfully']);
} catch (PDOException $e) {
    // Return error response
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}