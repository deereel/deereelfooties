<?php
// api/get-address.php
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

// Get address ID from query parameter
$addressId = isset($_GET['address_id']) ? intval($_GET['address_id']) : 0;

if (!$addressId) {
    echo json_encode(['success' => false, 'message' => 'Missing address_id parameter']);
    exit;
}

try {
    // Verify the address belongs to the user
    $stmt = $pdo->prepare("SELECT * FROM user_addresses WHERE address_id = ? AND user_id = ?");
    $stmt->execute([$addressId, $userId]);
    $address = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$address) {
        echo json_encode(['success' => false, 'message' => 'Address not found or does not belong to user']);
        exit;
    }
    
    echo json_encode(['success' => true, 'address' => $address]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>