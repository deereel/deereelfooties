<?php
require_once 'auth/db.php';

// Insert support tickets permission
$permissions = [
    [
        'name' => 'manage_support_tickets',
        'description' => 'Can manage support tickets, view all tickets, assign tickets, and manage ticket settings'
    ],
    [
        'name' => 'view_support_tickets',
        'description' => 'Can view support tickets assigned to them'
    ],
    [
        'name' => 'create_support_tickets',
        'description' => 'Can create new support tickets'
    ]
];

foreach ($permissions as $permission) {
    $stmt = $pdo->prepare("INSERT IGNORE INTO permissions (name, description) VALUES (?, ?)");
    $stmt->execute([$permission['name'], $permission['description']]);
    echo "Permission '{$permission['name']}' created or already exists.\n";
}

// Get super_admin role
$stmt = $pdo->prepare("SELECT id FROM roles WHERE name = 'super_admin'");
$stmt->execute();
$superAdminRole = $stmt->fetch(PDO::FETCH_ASSOC);

if ($superAdminRole) {
    // Assign all support ticket permissions to super_admin
    foreach ($permissions as $permission) {
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO role_permissions (role_id, permission_id)
            SELECT ?, p.id FROM permissions p WHERE p.name = ?
        ");
        $stmt->execute([$superAdminRole['id'], $permission['name']]);
        echo "Assigned '{$permission['name']}' to super_admin role.\n";
    }
}

// Get admin role
$stmt = $pdo->prepare("SELECT id FROM roles WHERE name = 'admin'");
$stmt->execute();
$adminRole = $stmt->fetch(PDO::FETCH_ASSOC);

if ($adminRole) {
    // Assign view and create permissions to admin
    $adminPermissions = ['view_support_tickets', 'create_support_tickets'];
    foreach ($adminPermissions as $permissionName) {
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO role_permissions (role_id, permission_id)
            SELECT ?, p.id FROM permissions p WHERE p.name = ?
        ");
        $stmt->execute([$adminRole['id'], $permissionName]);
        echo "Assigned '{$permissionName}' to admin role.\n";
    }
}

echo "\nSupport ticket permissions setup completed!\n";
echo "Super Admin: Full access to manage support tickets\n";
echo "Admin: Can view and create support tickets\n";
echo "Regular users: No access to support ticket management\n";
?>
