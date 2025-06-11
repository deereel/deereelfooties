<?php
// API endpoint to delete user account
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

// Validate password
if (!isset($data['password']) || empty($data['password'])) {
    echo json_encode(['success' => false, 'message' => 'Password is required']);
    exit;
}

// Get current user from session
$userId = $_SESSION['user']['user_id'];
$userEmail = $_SESSION['user']['email'];

try {
    // Verify password
    $stmt = $pdo->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user || !password_verify($data['password'], $user['password'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid password']);
        exit;
    }
    
    // Begin transaction
    $pdo->beginTransaction();
    
    // Delete user's data from all related tables
    $tables = [
        'wishlist_items' => 'user_id',
        'saved_designs' => 'user_id',
        'cart_items' => 'user_id',
        'user_addresses' => 'user_id',
        'payment_proofs' => 'user_id'
    ];
    
    foreach ($tables as $table => $field) {
        $stmt = $pdo->prepare("DELETE FROM $table WHERE $field = ?");
        $stmt->execute([$userId]);
    }
    
    // Finally delete the user
    $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->execute([$userId]);
    
    // Commit transaction
    $pdo->commit();
    
    // Destroy session
    session_destroy();
    
    // Return success response
    echo json_encode(['success' => true, 'message' => 'Account deleted successfully']);
} catch (PDOException $e) {
    // Rollback transaction on error
    $pdo->rollBack();
    
    // Return error response
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}