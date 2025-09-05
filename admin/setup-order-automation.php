<?php
session_start();
require_once '../auth/db.php';
require_once '../middleware/PermissionMiddleware.php';

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
        <title>Access Denied - Order Automation Setup</title>
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
                            You do not have permission to access the Order Automation Setup page.<br>
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
    // Create order_status_history table
    $sql = "CREATE TABLE IF NOT EXISTS order_status_history (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        status VARCHAR(50) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);
    echo "✅ order_status_history table created<br>";

    // Create order_items table if not exists
    $sql = "CREATE TABLE IF NOT EXISTS order_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        product_id INT NOT NULL,
        quantity INT NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);
    echo "✅ order_items table created<br>";

    // Add status column to orders if not exists
    try {
        $pdo->exec("ALTER TABLE orders ADD COLUMN status VARCHAR(50) DEFAULT 'pending'");
        echo "✅ Added status column to orders table<br>";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "ℹ️ Status column already exists<br>";
        } else {
            throw $e;
        }
    }

    echo "<br>🎉 Order automation setup complete!";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>