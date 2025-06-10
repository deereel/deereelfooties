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

if (!$data || !isset($data['user_id']) || !isset($data['name'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

try {
    // Update user in database
    $stmt = $pdo->prepare("UPDATE users SET name = ?, phone = ?, gender = ? WHERE id = ?");
    $result = $stmt->execute([
        $data['name'],
        $data['phone'] ?? null,
        $data['gender'] ?? null,
        $data['user_id']
    ]);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'User information updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update user information']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>