<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/middleware/PermissionMiddleware.php';

// Check if user is logged in
if (!isset($_SESSION['admin_user_id'])) {
    header('Location: login.php');
    exit;
}

// Check if user is Super Admin
$userId = $_SESSION['admin_user_id'];
$userRole = getUserRole($userId);
$isSuperAdmin = ($userRole && $userRole['name'] === 'super_admin');

if (!$isSuperAdmin) {
    // Show access denied message for non-super admin users
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Access Denied - Test DB Connection</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body { background-color: #f8f9fa; }
            .access-denied { max-width: 500px; margin: 100px auto; text-align: center; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="access-denied">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <i class="bi bi-shield-x text-danger" style="font-size: 4rem;"></i>
                        </div>
                        <h2 class="card-title text-danger mb-3">Access Denied</h2>
                        <p class="card-text text-muted mb-4">
                            You do not have permission to access the Test DB Connection page.<br>
                            Only Super Admin users can access this area.
                        </p>
                        <div class="d-grid gap-2">
                            <a href="index.php" class="btn btn-primary">
                                <i class="bi bi-house-door me-2"></i>Return to Dashboard
                            </a>
                            <a href="login.php" class="btn btn-outline-secondary">
                                <i class="bi bi-box-arrow-right me-2"></i>Login as Different User
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            // Auto redirect after 5 seconds
            setTimeout(function() {
                window.location.href = "index.php";
            }, 5000);
        </script>
    </body>
    </html>';
    exit;
}

try {
    global $pdo;
    echo "✅ Database connection successful!<br>";

    // List of tables to check based on database structure
    $tablesToCheck = [
        'admin_users',
        'roles',
        'permissions',
        'role_permissions',
        'admin_user_roles',
        'products',
        'orders',
        'order_items',
        'payment_proof',
        'order_progress',
        'order_status_history',
        'user_addresses',
        'users'
    ];

    foreach ($tablesToCheck as $tableName) {
        $stmt = $pdo->query("SHOW TABLES LIKE '{$tableName}'");
        if ($stmt->rowCount() > 0) {
            echo "✅ {$tableName} table exists!<br>";
        } else {
            echo "❌ {$tableName} table does not exist. You need to create it.<br>";
        }
    }

} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage();
}
?>