<?php
session_start();
require_once '../auth/db.php';

// Test session
echo "<h2>Session Test</h2>";
echo "<pre>";
echo "Session ID: " . session_id() . "\n";
echo "Admin User ID: " . ($_SESSION['admin_user_id'] ?? 'Not set') . "\n";
echo "Admin Username: " . ($_SESSION['admin_username'] ?? 'Not set') . "\n";
echo "Admin Role: " . ($_SESSION['admin_role'] ?? 'Not set') . "\n";
echo "</pre>";

// Test database connection
echo "<h2>Database Test</h2>";
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM admin_users");
    $count = $stmt->fetchColumn();
    echo "Database connection: OK (Found $count admin users)<br>";
} catch (Exception $e) {
    echo "Database connection: FAILED - " . $e->getMessage() . "<br>";
}

// Test permission function
echo "<h2>Permission Test</h2>";
if (isset($_SESSION['admin_user_id'])) {
    echo "Testing permissions for user ID: " . $_SESSION['admin_user_id'] . "<br>";
    echo "view_order_automation: " . (userHasPermission($_SESSION['admin_user_id'], 'view_order_automation') ? 'YES' : 'NO') . "<br>";
    echo "view_feedback: " . (userHasPermission($_SESSION['admin_user_id'], 'view_feedback') ? 'YES' : 'NO') . "<br>";
    echo "view_returns: " . (userHasPermission($_SESSION['admin_user_id'], 'view_returns') ? 'YES' : 'NO') . "<br>";
    echo "manage_security: " . (userHasPermission($_SESSION['admin_user_id'], 'manage_security') ? 'YES' : 'NO') . "<br>";
} else {
    echo "No admin user session found<br>";
}

echo "<h2>currentUserHasPermission Test</h2>";
echo "view_order_automation: " . (currentUserHasPermission('view_order_automation') ? 'YES' : 'NO') . "<br>";
echo "view_feedback: " . (currentUserHasPermission('view_feedback') ? 'YES' : 'NO') . "<br>";
echo "view_returns: " . (currentUserHasPermission('view_returns') ? 'YES' : 'NO') . "<br>";
echo "manage_security: " . (currentUserHasPermission('manage_security') ? 'YES' : 'NO') . "<br>";

echo "<h2>Sidebar Test</h2>";
echo "<p>If you can see this page, the sidebar should be working. Check the sidebar on the left.</p>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Test - DRF Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="admin-layout">
        <?php include 'includes/sidebar.php'; ?>

        <div class="admin-content">
            <main>
                <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1><i class="bi bi-tools me-2"></i>Sidebar Test Page</h1>
                </div>

                <div class="alert alert-info">
                    <h5>Sidebar Test Results</h5>
                    <p>This page tests the sidebar functionality. If the sidebar is not visible or some links are missing, check the debug information above.</p>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Expected Sidebar Links</h5>
                            </div>
                            <div class="card-body">
                                <ul>
                                    <li>Dashboard</li>
                                    <li>Orders</li>
                                    <li>Products</li>
                                    <li>Inventory</li>
                                    <li>Customers</li>
                                    <li>Social Media</li>
                                    <li>Slide Generator</li>
                                    <li>Settings (if permission)</li>
                                    <li>Analytics Dashboard</li>
                                    <li>Business Reports</li>
                                    <li>Sales Report</li>
                                    <li>Order Automation (if permission)</li>
                                    <li>Customer Feedback (if permission)</li>
                                    <li>Returns & Refunds (if permission)</li>
                                    <li>Other tools (if permissions)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Troubleshooting</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>If sidebar is not visible:</strong></p>
                                <ul>
                                    <li>Check if you're logged in as admin</li>
                                    <li>Verify database connection</li>
                                    <li>Check PHP error logs</li>
                                </ul>

                                <p><strong>If some links are missing:</strong></p>
                                <ul>
                                    <li>Check user permissions</li>
                                    <li>Verify permission assignments</li>
                                    <li>Check if required tables exist</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/admin.js"></script>
</body>
</html>
