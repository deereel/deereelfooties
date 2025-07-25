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
    // Get user data
    $userStmt = $pdo->prepare("SELECT user_id, name, email, phone, gender FROM users WHERE user_id = ?");
    $userStmt->execute([$userId]);
    $user = $userStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit;
    }
    
    // Get user addresses
    $addressStmt = $pdo->prepare("SELECT * FROM user_addresses WHERE user_id = ? ORDER BY is_default DESC, created_at DESC");
    $addressStmt->execute([$userId]);
    $addresses = $addressStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return combined data
    echo json_encode([
        'success' => true, 
        'user' => $user,
        'addresses' => $addresses
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>