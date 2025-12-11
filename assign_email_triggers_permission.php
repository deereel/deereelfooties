<?php
require_once 'auth/db.php';

try {
    // Ensure email triggers permission exists
    $pdo->exec("INSERT IGNORE INTO permissions (name, description, module) VALUES
        ('manage_email_triggers', 'Create and manage email triggers and automation', 'automation')");

    // Get super_admin role ID
    $stmt = $pdo->query("SELECT id FROM roles WHERE name = 'super_admin'");
    $role = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($role) {
        // Assign permission to super_admin role
        $pdo->exec("INSERT IGNORE INTO role_permissions (role_id, permission_id)
            SELECT {$role['id']}, p.id
            FROM permissions p
            WHERE p.name = 'manage_email_triggers'");

        echo "✅ Email triggers permission successfully assigned to super_admin role!\n";
    } else {
        echo "❌ Could not find super_admin role\n";
    }

    // Also assign to admin role if exists
    $stmt = $pdo->query("SELECT id FROM roles WHERE name = 'admin'");
    $adminRole = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($adminRole) {
        $pdo->exec("INSERT IGNORE INTO role_permissions (role_id, permission_id)
            SELECT {$adminRole['id']}, p.id
            FROM permissions p
            WHERE p.name = 'manage_email_triggers'");

        echo "✅ Email triggers permission successfully assigned to admin role!\n";
    }

    // Verify permission was created
    $stmt = $pdo->query("SELECT * FROM permissions WHERE name = 'manage_email_triggers'");
    $permission = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($permission) {
        echo "\nEmail triggers permission in database:\n";
        echo "- {$permission['name']}: {$permission['description']}\n";
    } else {
        echo "\n❌ Email triggers permission not found in database\n";
    }

    // Verify role assignments
    echo "\nRole assignments:\n";
    $stmt = $pdo->query("
        SELECT r.name as role_name, p.name as permission_name
        FROM roles r
        JOIN role_permissions rp ON r.id = rp.role_id
        JOIN permissions p ON rp.permission_id = p.id
        WHERE p.name = 'manage_email_triggers'
        ORDER BY r.name
    ");
    $assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($assignments as $assignment) {
        echo "- {$assignment['role_name']}: {$assignment['permission_name']}\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
