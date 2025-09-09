<?php
require_once 'auth/db.php';

try {
    // Insert system permissions (in case they are missing)
    $pdo->exec("INSERT IGNORE INTO permissions (name, description, module) VALUES
        ('manage_backups', 'Create and manage database backups', 'system'),
        ('view_system_health', 'View system health and monitoring', 'system'),
        ('view_error_logs', 'View and analyze error logs', 'system')");

    // Get super_admin role ID
    $stmt = $pdo->query("SELECT id FROM roles WHERE name = 'super_admin'");
    $role = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($role) {
        // Assign permissions to super_admin role
        $pdo->exec("INSERT IGNORE INTO role_permissions (role_id, permission_id)
            SELECT {$role['id']}, p.id
            FROM permissions p
            WHERE p.name IN ('manage_backups', 'view_system_health', 'view_error_logs')");

        echo "✅ System permissions successfully assigned to super_admin role!\n";
    } else {
        echo "❌ Could not find super_admin role\n";
    }

    // Verify permissions were created
    $stmt = $pdo->query("SELECT * FROM permissions WHERE name IN ('manage_backups', 'view_system_health', 'view_error_logs')");
    $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "\nSystem permissions in database:\n";
    foreach ($permissions as $perm) {
        echo "- {$perm['name']}: {$perm['description']}\n";
    }

    // Verify role assignments
    $stmt = $pdo->query("SELECT p.name FROM permissions p
                        JOIN role_permissions rp ON p.id = rp.permission_id
                        JOIN roles r ON rp.role_id = r.id
                        WHERE r.name = 'super_admin' AND p.name IN ('manage_backups', 'view_system_health', 'view_error_logs')");
    $assigned = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "\nPermissions assigned to super_admin:\n";
    foreach ($assigned as $perm) {
        echo "- {$perm['name']}\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
