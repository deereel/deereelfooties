<?php
require_once 'auth/db.php';

echo "Restricting Tools & Setup permissions to Super Admin only...\n";

// First, get all permissions that should be super admin only
$superAdminOnlyPermissions = [
    'manage_backups',      // Backup Management
    'view_system_health',  // System Health
    'view_error_logs',     // Error Logging
    'view_login_monitoring', // Login Monitoring
    'view_activity_logs',  // Activity Logs
    'manage_security',     // IP Blocking, Security management
    'manage_settings',     // Settings (mentioned by user)
    'delete_products'      // Delete products (mentioned by user)
];

// Get permission IDs for super admin only permissions
$superAdminPermIds = [];
foreach ($superAdminOnlyPermissions as $permName) {
    $perm = fetchData('permissions', ['name' => $permName], 'id');
    if (!empty($perm)) {
        $superAdminPermIds[] = $perm[0]['id'];
    }
}

echo "Found " . count($superAdminPermIds) . " super admin only permissions\n";

// Get all roles except super_admin
$roles = fetchData('roles', [], 'id, name', 'id ASC');
$nonSuperAdminRoles = array_filter($roles, function($role) {
    return $role['name'] !== 'super_admin';
});

echo "Processing " . count($nonSuperAdminRoles) . " non-super-admin roles\n";

// Remove super admin only permissions from non-super-admin roles
foreach ($nonSuperAdminRoles as $role) {
    echo "Removing super admin permissions from role: {$role['name']}\n";

    if (!empty($superAdminPermIds)) {
        $placeholders = str_repeat('?,', count($superAdminPermIds) - 1) . '?';
        $stmt = $pdo->prepare("DELETE FROM role_permissions WHERE role_id = ? AND permission_id IN ($placeholders)");
        $params = array_merge([$role['id']], $superAdminPermIds);
        $stmt->execute($params);

        echo "Removed permissions from {$role['name']}\n";
    }
}

// Ensure super admin has all permissions
$superAdminRole = fetchData('roles', ['name' => 'super_admin'], 'id');
if (!empty($superAdminRole)) {
    $superAdminId = $superAdminRole[0]['id'];

    // Get all permissions
    $allPermissions = fetchData('permissions', [], 'id', 'id ASC');
    $allPermIds = array_column($allPermissions, 'id');

    // Add any missing permissions to super admin
    foreach ($allPermIds as $permId) {
        $existing = fetchData('role_permissions', ['role_id' => $superAdminId, 'permission_id' => $permId], 'id');
        if (empty($existing)) {
            $stmt = $pdo->prepare("INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)");
            $stmt->execute([$superAdminId, $permId]);
        }
    }

    echo "Ensured super admin has all permissions\n";
}

echo "\nPermission restriction completed!\n";

// Display summary
echo "\nFinal Role Permission Summary:\n";
foreach ($roles as $role) {
    $count = fetchData('role_permissions', ['role_id' => $role['id']], 'COUNT(*) as count')[0]['count'];
    echo "- {$role['name']}: {$count} permissions\n";
}

echo "\nSuper Admin Only Permissions:\n";
foreach ($superAdminOnlyPermissions as $perm) {
    echo "- $perm\n";
}

echo "\nDone!\n";
?>
