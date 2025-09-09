<?php
require_once 'auth/db.php';

try {
    // Insert returns permissions
    $pdo->exec("INSERT IGNORE INTO permissions (name, description, module) VALUES
        ('view_returns', 'View return requests', 'returns'),
        ('manage_returns', 'Create and manage return requests', 'returns'),
        ('process_refunds', 'Process refund requests', 'returns')");

    // Get super_admin role ID
    $stmt = $pdo->query("SELECT id FROM roles WHERE name = 'super_admin'");
    $role = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($role) {
        // Assign permissions to super_admin role
        $pdo->exec("INSERT IGNORE INTO role_permissions (role_id, permission_id)
            SELECT {$role['id']}, p.id
            FROM permissions p
            WHERE p.name IN ('view_returns', 'manage_returns', 'process_refunds')");

        echo "✅ Returns permissions successfully assigned to super_admin role!\n";
    } else {
        echo "❌ Could not find super_admin role\n";
    }

    // Verify permissions were created
    $stmt = $pdo->query("SELECT * FROM permissions WHERE name LIKE '%returns%'");
    $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "\nReturns permissions in database:\n";
    foreach ($permissions as $perm) {
        echo "- {$perm['name']}: {$perm['description']}\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
