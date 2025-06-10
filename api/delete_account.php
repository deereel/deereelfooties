<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/auth/db.php');
header('Content-Type: application/json');

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get JSON data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['user_id']) || !isset($data['password'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

try {
    // First verify password
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$data['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user || !password_verify($data['password'], $user['password'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid password']);
        exit;
    }
    
    // Delete user data from related tables
    $pdo->beginTransaction();
    
    // Delete wishlist items
    $stmt = $pdo->prepare("DELETE FROM wishlist_items WHERE user_id = ?");
    $stmt->execute([$data['user_id']]);
    
    // Delete saved designs
    $stmt = $pdo->prepare("DELETE FROM saved_designs WHERE user_id = ?");
    $stmt->execute([$data['user_id']]);
    
    // Delete user account
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $result = $stmt->execute([$data['user_id']]);
    
    if ($result) {
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Account deleted successfully']);
    } else {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Failed to delete account']);
    }
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>