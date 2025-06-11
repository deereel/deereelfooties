<?php
// API endpoint to update user data
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

// Validate data
if (!isset($data['name']) || empty($data['name'])) {
    echo json_encode(['success' => false, 'message' => 'Name is required']);
    exit;
}

// Get current user ID from session
$userId = $_SESSION['user']['user_id'];

try {
    // Update user data
    $stmt = $pdo->prepare("UPDATE users SET name = ?, phone = ?, gender = ? WHERE user_id = ?");
    $stmt->execute([
        $data['name'],
        $data['phone'] ?? null,
        $data['gender'] ?? null,
        $userId
    ]);
    
    // Update session data
    $_SESSION['user']['name'] = $data['name'];
    $_SESSION['user']['phone'] = $data['phone'] ?? null;
    $_SESSION['user']['gender'] = $data['gender'] ?? null;
    
    // Return success response
    echo json_encode(['success' => true, 'message' => 'User data updated successfully']);
} catch (PDOException $e) {
    // Return error response
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}