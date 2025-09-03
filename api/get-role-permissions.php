<?php
session_start();
require_once '../auth/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin_user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if (!isset($_GET['role_id'])) {
    echo json_encode(['error' => 'Role ID is required']);
    exit;
}

$roleId = (int)$_GET['role_id'];

try {
    // Get all permissions with assignment status
    $stmt = $pdo->prepare("
        SELECT p.*, CASE WHEN rp.role_id IS NOT NULL THEN 1 ELSE 0 END as assigned
        FROM permissions p
        LEFT JOIN role_permissions rp ON p.id = rp.permission_id AND rp.role_id = ?
        ORDER BY p.module ASC, p.name ASC
    ");
    $stmt->execute([$roleId]);
    $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'permissions' => $permissions]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
