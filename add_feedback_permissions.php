<?php
require_once 'auth/db.php';

try {
    // Check if permissions already exist
    $existingPermissions = $pdo->query("SELECT name FROM permissions WHERE name LIKE '%feedback%'")->fetchAll(PDO::FETCH_COLUMN);

    $permissionsToAdd = [
        'view_feedback',
        'manage_feedback',
        'create_feedback',
        'respond_feedback',
        'delete_feedback'
    ];

    $newPermissions = array_diff($permissionsToAdd, $existingPermissions);

    if (empty($newPermissions)) {
        echo "✅ All feedback permissions already exist.\n";
        exit(0);
    }

    // Insert new permissions
    $stmt = $pdo->prepare("INSERT INTO permissions (name, description, module) VALUES (?, ?, ?)");

    foreach ($newPermissions as $permission) {
        $description = match($permission) {
            'view_feedback' => 'View customer feedback',
            'manage_feedback' => 'Manage and assign feedback',
            'create_feedback' => 'Create feedback entries',
            'respond_feedback' => 'Respond to customer feedback',
            'delete_feedback' => 'Delete feedback entries',
            default => 'Feedback permission'
        };

        $stmt->execute([$permission, $description, 'feedback']);
        echo "✅ Added permission: $permission\n";
    }

    echo "\n🎉 Feedback permissions setup completed!\n";
    echo "📋 Added permissions: " . implode(', ', $newPermissions) . "\n";

} catch (Exception $e) {
    echo "❌ Error setting up feedback permissions: " . $e->getMessage() . "\n";
    exit(1);
}
?>
