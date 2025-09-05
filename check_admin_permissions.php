<?php
require_once 'auth/db.php';

$userId = 1; // Assuming admin user ID is 1
$query = 'SELECT p.name FROM admin_users au
          JOIN roles r ON au.role_id = r.id
          JOIN role_permissions rp ON r.id = rp.role_id
          JOIN permissions p ON rp.permission_id = p.id
          WHERE au.id = ?';

$stmt = $pdo->prepare($query);
$stmt->execute([$userId]);
$permissions = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo 'Current admin permissions:' . PHP_EOL;
foreach ($permissions as $perm) {
    echo '- ' . $perm . PHP_EOL;
}
?>
