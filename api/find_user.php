<?php
require_once '../auth/db.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Get email parameter
$email = isset($_GET['email']) ? $_GET['email'] : null;

if (!$email) {
    echo json_encode(['success' => false, 'message' => 'Email parameter is required']);
    exit;
}

try {
    // Find user by email
    $stmt = $pdo->prepare("SELECT user_id, name, email, phone, gender, created_at FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo json_encode(['success' => true, 'data' => $user]);
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>