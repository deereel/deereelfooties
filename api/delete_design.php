<?php
// API endpoint to delete a saved design
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
$designId = isset($data['design_id']) ? intval($data['design_id']) : 0;

// Validate data
if ($userId <= 0 || $designId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid request data']);
    exit;
}

try {
    // Verify the design belongs to the user
    $stmt = $pdo->prepare("SELECT * FROM saved_designs WHERE design_id = ? AND user_id = ?");
    $stmt->execute([$designId, $userId]);
    $design = $stmt->fetch();
    
    if (!$design) {
        echo json_encode(['success' => false, 'message' => 'Design not found or does not belong to user']);
        exit;
    }
    
    // Delete the design
    $stmt = $pdo->prepare("DELETE FROM saved_designs WHERE design_id = ? AND user_id = ?");
    $stmt->execute([$designId, $userId]);
    
    // Return success response
    echo json_encode(['success' => true, 'message' => 'Design deleted successfully']);
} catch (PDOException $e) {
    // Return error response
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}