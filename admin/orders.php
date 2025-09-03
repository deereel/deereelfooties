<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_user_id'])) {
    header('Location: login.php');
    exit;
}

// Include database connection and middleware
require_once '../auth/db.php';
require_once '../middleware/PermissionMiddleware.php';

// Check if user has permission to manage orders
$permissionMiddleware = new PermissionMiddleware('manage_orders');
$permissionMiddleware->handle();

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
    <title>Orders - Admin Dashboard</title>
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
                    <h1 class="h2">
                        Orders
                        <?php if ($customerFilter > 0): ?>
                            <small class="text-muted">for Customer #<?php echo $customerFilter; ?></small>
                        <?php endif; ?>
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <a href="?<?php echo $customerFilter > 0 ? 'customer_id=' . $customerFilter : ''; ?>" class="btn btn-sm btn-outline-secondary <?php echo $statusFilter === '' ? 'active' : ''; ?>">All</a>
                            <a href="?status=Pending<?php echo $customerFilter > 0 ? '&customer_id=' . $customerFilter : ''; ?>" class="btn btn-sm btn-outline-secondary <?php echo $statusFilter === 'Pending' ? 'active' : ''; ?>">Pending</a>
                            <a href="?status=Processing<?php echo $customerFilter > 0 ? '&customer_id=' . $customerFilter : ''; ?>" class="btn btn-sm btn-outline-secondary <?php echo $statusFilter === 'Processing' ? 'active' : ''; ?>">Processing</a>
                            <a href="?status=Payment+Confirmed<?php echo $customerFilter > 0 ? '&customer_id=' . $customerFilter : ''; ?>" class="btn btn-sm btn-outline-secondary <?php echo $statusFilter === 'Payment Confirmed' ? 'active' : ''; ?>">Payment Confirmed</a>
                            <a href="?status=Completed<?php echo $customerFilter > 0 ? '&customer_id=' . $customerFilter : ''; ?>" class="btn btn-sm btn-outline-secondary <?php echo $statusFilter === 'Completed' ? 'active' : ''; ?>">Completed</a>
                        </div>
                        <?php if ($customerFilter > 0): ?>
                        <div class="btn-group">
                            <a href="?" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-x-circle me-1"></i> Clear Customer Filter
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($orders) > 0): ?>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td>#<?php echo $order['order_id']; ?></td>
                                        <td>
                                            <?php 
                                            echo htmlspecialchars($order['customer_name'] ?? 'Guest'); 
                                            if (!empty($order['user_id'])) {
                                                echo ' (ID: ' . $order['user_id'] . ')';
                                            }
                                            ?>
                                        </td>
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
                                            <?php
                                            // Check payment confirmation status first
                                            if (isset($order['payment_confirmed']) && $order['payment_confirmed']) {
                                                echo '<span class="badge bg-success">Confirmed</span>';
                                            } else {
                                                // Check if payment proof exists
                                                $proofStmt = $pdo->prepare("SELECT id FROM payment_proof WHERE order_id = ?");
                                                $proofStmt->execute([$order['order_id']]);
                                                $hasProof = $proofStmt->rowCount() > 0;
                                                
                                                if ($hasProof) {
                                                    echo '<span class="badge bg-warning">Uploaded</span>';
                                                } else {
                                                    echo '<span class="badge bg-secondary">Pending</span>';
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="order-details.php?id=<?php echo $order['order_id']; ?>" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-eye"></i> View
                                                </a>
                                                <?php if (strtolower($order['status']) === 'pending'): ?>
                                                <button class="btn btn-sm btn-success process-order-btn" data-order-id="<?php echo $order['order_id']; ?>">
                                                    <i class="bi bi-play-circle"></i> Process
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">No orders found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>&status=<?php echo $statusFilter; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&status=<?php echo $statusFilter; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            
                            <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>&status=<?php echo $statusFilter; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/admin.js"></script>
    <script>
    document.querySelectorAll('.process-order-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const orderId = this.dataset.orderId;
            if (!confirm('Process this order? This will update inventory and send confirmation.')) return;
            
            try {
                const response = await fetch('../api/process-order.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `order_id=${orderId}&action=process`
                });
                const data = await response.json();
                
                if (data.success) {
                    alert('Order processed successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                alert('Network error: ' + error.message);
            }
        });
    });
    </script>
</body>
</html>