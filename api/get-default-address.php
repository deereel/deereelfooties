<?php
require_once '../auth/db.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Check if required parameters are provided
if (!isset($_GET['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing user_id parameter']);
    exit;
}

$userId = $_GET['user_id'];

try {
    // First try to get the default address
    $stmt = $pdo->prepare("SELECT * FROM user_addresses WHERE user_id = ? AND is_default = 1 LIMIT 1");
    $stmt->execute([$userId]);
    $address = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // If no default address, get the most recently created address
    if (!$address) {
        $stmt = $pdo->prepare("SELECT * FROM user_addresses WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$userId]);
        $address = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Debug log
    error_log("Default address query for user $userId: " . json_encode($address));
    
    if ($address) {
        echo json_encode(['success' => true, 'address' => $address]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No addresses found for this user']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>