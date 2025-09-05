<?php
require_once 'auth/db.php';

try {
    // Get super admin role ID
    $superAdminRole = $pdo->query("SELECT id FROM roles WHERE name = 'super_admin'")->fetch(PDO::FETCH_ASSOC);

    if (!$superAdminRole) {
        echo "❌ Super admin role not found. Please run role setup first.\n";
        exit(1);
    }

    $superAdminRoleId = $superAdminRole['id'];

    // Get feedback permissions
    $feedbackPermissions = $pdo->query("SELECT id, name FROM permissions WHERE name LIKE '%feedback%'")->fetchAll(PDO::FETCH_ASSOC);

    if (empty($feedbackPermissions)) {
        echo "❌ No feedback permissions found. Please run add_feedback_permissions.php first.\n";
        exit(1);
    }

    // Check existing role permissions
    $existingRolePermissions = $pdo->query("SELECT permission_id FROM role_permissions WHERE role_id = $superAdminRoleId")->fetchAll(PDO::FETCH_COLUMN);

    $newPermissions = [];
    foreach ($feedbackPermissions as $permission) {
        if (!in_array($permission['id'], $existingRolePermissions)) {
            $newPermissions[] = $permission;
        }
    }

    if (empty($newPermissions)) {
        echo "✅ Super admin already has all feedback permissions.\n";
        exit(0);
    }

    // Assign new permissions to super admin
    $stmt = $pdo->prepare("INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)");

    foreach ($newPermissions as $permission) {
        $stmt->execute([$superAdminRoleId, $permission['id']]);
        echo "✅ Assigned permission: {$permission['name']}\n";
    }

    echo "\n🎉 Feedback permissions assigned to super admin successfully!\n";
    echo "📋 Assigned permissions: " . implode(', ', array_column($newPermissions, 'name')) . "\n";

} catch (Exception $e) {
    echo "❌ Error assigning feedback permissions: " . $e->getMessage() . "\n";
    exit(1);
}
?>
