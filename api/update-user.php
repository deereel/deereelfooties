<?php
session_start();
require_once '../auth/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin_user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['error' => 'Invalid request data']);
    exit;
}

$userId = (int)$data['user_id'];
$username = trim($data['username']);
$email = trim($data['email']);
$roleId = (int)$data['role_id'];
$firstName = isset($data['first_name']) ? trim($data['first_name']) : '';
$lastName = isset($data['last_name']) ? trim($data['last_name']) : '';
$reason = isset($data['reason']) ? trim($data['reason']) : 'User profile updated via admin panel';

if (!$userId || !$username || !$email || !$roleId) {
    echo json_encode(['error' => 'All fields are required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['error' => 'Invalid email format']);
    exit;
}

try {
    // Check if user exists
    $stmt = $pdo->prepare("SELECT id FROM admin_users WHERE id = ?");
    $stmt->execute([$userId]);
    if (!$stmt->fetch()) {
        echo json_encode(['error' => 'User not found']);
        exit;
    }

    // Check if role exists
    $stmt = $pdo->prepare("SELECT id FROM roles WHERE id = ?");
    $stmt->execute([$roleId]);
    if (!$stmt->fetch()) {
        echo json_encode(['error' => 'Role not found']);
        exit;
    }

    // Check if username or email is already taken by another user
    $stmt = $pdo->prepare("SELECT id FROM admin_users WHERE (username = ? OR email = ?) AND id != ?");
    $stmt->execute([$username, $email, $userId]);
    if ($stmt->fetch()) {
        echo json_encode(['error' => 'Username or email already exists']);
        exit;
    }

    // Update user
    $stmt = $pdo->prepare("UPDATE admin_users SET username = ?, email = ?, first_name = ?, last_name = ?, role_id = ? WHERE id = ?");
    $stmt->execute([$username, $email, $firstName, $lastName, $roleId, $userId]);

    // Log the change (you might want to create a logs table)
    // For now, just return success

    echo json_encode(['success' => true, 'message' => 'User updated successfully']);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
