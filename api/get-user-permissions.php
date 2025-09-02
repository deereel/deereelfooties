<?php
session_start();
require_once '../auth/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin_user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if (!isset($_GET['user_id'])) {
    echo json_encode(['error' => 'User ID is required']);
    exit;
}

$userId = (int)$_GET['user_id'];

try {
    // Get user's role
    $stmt = $pdo->prepare("SELECT role_id FROM admin_users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(['error' => 'User not found']);
        exit;
    }

    $roleId = $user['role_id'];

    if (!$roleId) {
        echo json_encode(['success' => true, 'permissions' => []]);
        exit;
    }

    // Get permissions for the role
    $stmt = $pdo->prepare("
        SELECT p.id, p.name, p.description
        FROM permissions p
        INNER JOIN role_permissions rp ON p.id = rp.permission_id
        WHERE rp.role_id = ?
        ORDER BY p.name ASC
    ");
    $stmt->execute([$roleId]);
    $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'permissions' => $permissions]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
