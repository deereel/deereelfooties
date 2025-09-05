<?php
require_once 'auth/db.php';

echo "🔄 Assigning Activity Logs Permission\n";
echo "=====================================\n\n";

// Get the permission ID for view_activity_logs
$permission = fetchData('permissions', ['name' => 'view_activity_logs'], 'id, name');
if (empty($permission)) {
    echo "❌ Error: view_activity_logs permission not found in database\n";
    exit(1);
}

$permissionId = $permission[0]['id'];
echo "✅ Found permission: {$permission[0]['name']} (ID: $permissionId)\n\n";

// Get all roles
$roles = fetchData('roles', [], 'id, name');
if (empty($roles)) {
    echo "❌ Error: No roles found in database\n";
    exit(1);
}

// Assign permission to each role
foreach ($roles as $role) {
    // Check if role already has this permission
    $existing = fetchData('role_permissions', [
        'role_id' => $role['id'],
        'permission_id' => $permissionId
    ]);

    if (!empty($existing)) {
        echo "ℹ️  {$role['name']} already has view_activity_logs permission\n";
        continue;
    }

    // Assign permission
    $result = insertData('role_permissions', [
        'role_id' => $role['id'],
        'permission_id' => $permissionId
    ]);

    if (isset($result['error'])) {
        echo "❌ Error assigning to {$role['name']}: {$result['error']}\n";
    } else {
        echo "✅ Assigned view_activity_logs to {$role['name']}\n";
    }
}

echo "\n🎉 Permission assignment completed!\n";
echo "📋 Summary: view_activity_logs permission assigned to all roles\n";
?>
