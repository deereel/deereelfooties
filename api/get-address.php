<?php
require_once '../auth/db.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Check if required parameters are provided
if (!isset($_GET['address_id']) || !isset($_GET['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit;
}

$addressId = $_GET['address_id'];
$userId = $_GET['user_id'];

try {
    // Get address details
    $stmt = $pdo->prepare("SELECT * FROM user_addresses WHERE address_id = ? AND user_id = ?");
    $stmt->execute([$addressId, $userId]);
    $address = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($address) {
        echo json_encode(['success' => true, 'address' => $address]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Address not found']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>