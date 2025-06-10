<?php
require_once '../auth/db.php';
require_once '../auth/auth.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isLoggedIn()) {
    echo json_encode([]);
    exit;
}

$userId = $_SESSION['user_id'];

try {
    // Get user's saved designs
    $stmt = $pdo->prepare("SELECT * FROM saved_designs WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$userId]);
    $designs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($designs);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>