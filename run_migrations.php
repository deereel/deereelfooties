<?php
// Migration runner script
// This script executes all the migration files in order

require_once 'auth/db.php';

echo "<h1>Running Database Migrations</h1>";
echo "<pre>";

$migrationFiles = [
    'migrations/create_roles_table.sql',
    'migrations/create_permissions_table.sql',
    'migrations/create_role_permissions_table.sql',
    'migrations/create_admin_users_table.sql'
];

foreach ($migrationFiles as $file) {
    echo "\n=== Executing: $file ===\n";

    if (!file_exists($file)) {
        echo "❌ Migration file not found: $file\n";
        continue;
    }

    $sql = file_get_contents($file);

    try {
        $pdo->exec($sql);
        echo "✅ Migration executed successfully: $file\n";
    } catch (PDOException $e) {
        echo "❌ Error executing migration $file: " . $e->getMessage() . "\n";
    }
}

echo "\n=== Migration process completed ===\n";
echo "</pre>";
?>
