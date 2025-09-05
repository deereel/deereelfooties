<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_user_id'])) {
    header('Location: login.php');
    exit;
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Include database connection and middleware
require_once '../auth/db.php';
require_once '../middleware/PermissionMiddleware.php';

// Check if user has permission to view dashboard
try {
    $permissionMiddleware = new PermissionMiddleware('view_dashboard');
    $permissionMiddleware->handle();
} catch (Exception $e) {
    header('Location: login.php');
    exit;
}

// Get orders with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Filter by status and customer if provided
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';
$customerFilter = isset($_GET['customer_id']) ? (int)$_GET['customer_id'] : 0;
$whereClause = '';
$params = [];
$conditions = [];

if (!empty($statusFilter)) {
    if ($statusFilter === 'Payment Confirmed') {
        $conditions[] = "payment_confirmed = 1";
    } else {
        $conditions[] = "status = ?";
        $params[] = $statusFilter;
    }
}

if ($customerFilter > 0) {
    $conditions[] = "user_id = ?";
    $params[] = $customerFilter;
}

if (!empty($conditions)) {
    $whereClause = " WHERE " . implode(" AND ", $conditions);
}

// Count total orders for pagination
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM orders" . $whereClause);
$countStmt->execute($params);
$totalOrders = $countStmt->fetchColumn();
$totalPages = ceil($totalOrders / $limit);

// Get orders with pagination
$orderStmt = $pdo->prepare("SELECT * FROM orders" . $whereClause . " ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
$orderStmt->execute($params);
$orders = $orderStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - DRF</title>
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
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <span class="badge bg-primary fs-6">Welcome back, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>!</span>
                            <a href="?logout=1" class="btn btn-sm btn-outline-danger ms-2">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Dashboard Stats -->
                <div class="row mb-4">
                    <?php
                    // Get dashboard statistics
                    try {
                        // Total orders
                        $totalOrdersStmt = $pdo->query("SELECT COUNT(*) FROM orders");
                        $totalOrdersCount = $totalOrdersStmt->fetchColumn();
                        
                        // Total revenue
                        $revenueStmt = $pdo->query("SELECT SUM(COALESCE(subtotal, total, 0)) FROM orders WHERE status = 'Completed'");
                        $totalRevenue = $revenueStmt->fetchColumn() ?: 0;
                        
                        // Pending orders
                        $pendingStmt = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'Pending'");
                        $pendingOrders = $pendingStmt->fetchColumn();
                        
                        // Total customers
                        $customersStmt = $pdo->query("SELECT COUNT(*) FROM users");
                        $totalCustomers = $customersStmt->fetchColumn();
                    } catch (PDOException $e) {
                        $totalOrdersCount = 0;
                        $totalRevenue = 0;
                        $pendingOrders = 0;
                        $totalCustomers = 0;
                    }
                    ?>
                    <div class="col-md-3">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title"><?php echo $totalOrdersCount; ?></h4>
                                        <p class="card-text">Total Orders</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-bag fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title">₦<?php echo number_format($totalRevenue, 2); ?></h4>
                                        <p class="card-text">Total Revenue</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-currency-dollar fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title"><?php echo $pendingOrders; ?></h4>
                                        <p class="card-text">Pending Orders</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-clock fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-info">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title"><?php echo $totalCustomers; ?></h4>
                                        <p class="card-text">Total Customers</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-people fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Orders -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Recent Orders</h5>
                        <a href="#" onclick="showAllOrders()" class="btn btn-sm btn-outline-primary">View All Orders</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Get recent orders (limit to 10)
                                    $recentOrdersStmt = $pdo->prepare("SELECT * FROM orders ORDER BY created_at DESC LIMIT 10");
                                    $recentOrdersStmt->execute();
                                    $recentOrders = $recentOrdersStmt->fetchAll(PDO::FETCH_ASSOC);
                                    
                                    if (count($recentOrders) > 0):
                                        foreach ($recentOrders as $order):
                                    ?>
                                        <tr>
                                            <td>#<?php echo $order['order_id']; ?></td>
                                            <td><?php echo htmlspecialchars($order['customer_name'] ?? 'Guest'); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                            <td>₦<?php echo number_format($order['subtotal'] ?? $order['total'] ?? 0, 2); ?></td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo $order['status'] === 'Completed' ? 'success' : 
                                                        ($order['status'] === 'Processing' ? 'primary' : 
                                                        ($order['status'] === 'Cancelled' ? 'danger' : 'warning')); 
                                                ?>">
                                                    <?php echo $order['status']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="order-details.php?id=<?php echo $order['order_id']; ?>" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    <?php 
                                        endforeach;
                                    else:
                                    ?>
                                        <tr>
                                            <td colspan="6" class="text-center">No orders found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/admin.js"></script>
    <script>
        function showAllOrders() {
            // Update sidebar to highlight Orders
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
                if (link.textContent.trim().includes('Orders')) {
                    link.classList.add('active');
                }
            });
            
            // Load orders page content
            window.location.href = 'orders.php';
        }
    </script>
</body>
</html>