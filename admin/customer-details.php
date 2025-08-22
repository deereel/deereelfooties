<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../auth/db.php';

// Get customer ID from URL
$customerId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($customerId <= 0) {
    header('Location: customers.php');
    exit;
}

try {
    // Get customer details
    $customerStmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
    $customerStmt->execute([$customerId]);
    $customer = $customerStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$customer) {
        header('Location: customers.php');
        exit;
    }
    
    // Get customer orders
    $ordersStmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 10");
    $ordersStmt->execute([$customerId]);
    $orders = $ordersStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get customer addresses
    $addressesStmt = $pdo->prepare("SELECT * FROM user_addresses WHERE user_id = ? ORDER BY is_default DESC, created_at DESC");
    $addressesStmt->execute([$customerId]);
    $addresses = $addressesStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get customer statistics
    $statsStmt = $pdo->prepare("SELECT 
        COUNT(*) as total_orders,
        COALESCE(SUM(subtotal), SUM(total), 0) as total_spent,
        AVG(COALESCE(subtotal, total, 0)) as avg_order_value,
        MAX(created_at) as last_order_date
        FROM orders WHERE user_id = ?");
    $statsStmt->execute([$customerId]);
    $stats = $statsStmt->fetch(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $error = 'Error retrieving customer details: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Details - Admin Dashboard</title>
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
                    <h1 class="h2">Customer Details</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="customers.php" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Customers
                        </a>
                    </div>
                </div>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <div class="row mb-4">
                    <!-- Customer Information -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Customer Information</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th>Customer ID:</th>
                                        <td>#<?php echo $customer['user_id']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Name:</th>
                                        <td><?php echo htmlspecialchars($customer['name'] ?? 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td><?php echo htmlspecialchars($customer['email'] ?? 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Phone:</th>
                                        <td><?php echo htmlspecialchars($customer['phone'] ?? 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Joined:</th>
                                        <td><?php echo date('F d, Y H:i', strtotime($customer['created_at'])); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Customer Statistics -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Order Statistics</h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-6 mb-3">
                                        <h4 class="text-primary"><?php echo $stats['total_orders']; ?></h4>
                                        <small class="text-muted">Total Orders</small>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <h4 class="text-success">₦<?php echo number_format($stats['total_spent'], 2); ?></h4>
                                        <small class="text-muted">Total Spent</small>
                                    </div>
                                    <div class="col-6">
                                        <h4 class="text-info">₦<?php echo number_format($stats['avg_order_value'], 2); ?></h4>
                                        <small class="text-muted">Avg Order Value</small>
                                    </div>
                                    <div class="col-6">
                                        <h4 class="text-warning">
                                            <?php if ($stats['last_order_date']): ?>
                                                <?php echo date('M d, Y', strtotime($stats['last_order_date'])); ?>
                                            <?php else: ?>
                                                Never
                                            <?php endif; ?>
                                        </h4>
                                        <small class="text-muted">Last Order</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Customer Addresses -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Saved Addresses</h5>
                    </div>
                    <div class="card-body">
                        <?php if (count($addresses) > 0): ?>
                            <div class="row">
                                <?php foreach ($addresses as $address): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="card <?php echo $address['is_default'] ? 'border-primary' : ''; ?>">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="card-title"><?php echo htmlspecialchars($address['address_name'] ?? 'Address'); ?></h6>
                                                    <?php if ($address['is_default']): ?>
                                                        <span class="badge bg-primary">Default</span>
                                                    <?php endif; ?>
                                                </div>
                                                <p class="card-text">
                                                    <strong><?php echo htmlspecialchars($address['full_name'] ?? ''); ?></strong><br>
                                                    <?php echo htmlspecialchars($address['phone'] ?? ''); ?><br>
                                                    <?php echo htmlspecialchars($address['street_address'] ?? $address['address'] ?? ''); ?><br>
                                                    <?php echo htmlspecialchars($address['city'] ?? ''); ?>, <?php echo htmlspecialchars($address['state'] ?? ''); ?><br>
                                                    <?php echo htmlspecialchars($address['country'] ?? ''); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No saved addresses found.</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Recent Orders -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Recent Orders</h5>
                        <a href="index.php?customer_id=<?php echo $customerId; ?>" class="btn btn-sm btn-outline-primary">View All Orders</a>
                    </div>
                    <div class="card-body">
                        <?php if (count($orders) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Date</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orders as $order): ?>
                                            <tr>
                                                <td>#<?php echo $order['order_id']; ?></td>
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
                                                    <a href="order-details.php?id=<?php echo $order['order_id']; ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No orders found for this customer.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>