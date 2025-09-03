<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_user_id'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../auth/db.php';

// Get customers with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

// Search functionality
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$whereClause = '';
$params = [];

if (!empty($search)) {
    $whereClause = " WHERE name LIKE ? OR email LIKE ? OR phone LIKE ?";
    $searchTerm = "%$search%";
    $params = [$searchTerm, $searchTerm, $searchTerm];
}

try {
    // Count total customers for pagination
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM users" . $whereClause);
    $countStmt->execute($params);
    $totalCustomers = $countStmt->fetchColumn();
    $totalPages = ceil($totalCustomers / $limit);

    // Get customers with pagination
    $customerStmt = $pdo->prepare("SELECT 
        u.*,
        COUNT(DISTINCT o.order_id) as total_orders,
        COALESCE(SUM(o.subtotal), SUM(o.total), 0) as total_spent,
        MAX(o.created_at) as last_order_date
        FROM users u 
        LEFT JOIN orders o ON u.user_id = o.user_id
        " . $whereClause . "
        GROUP BY u.user_id
        ORDER BY u.created_at DESC 
        LIMIT $limit OFFSET $offset");
    $customerStmt->execute($params);
    $customers = $customerStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Error retrieving customers: ' . $e->getMessage();
    $customers = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers - Admin Dashboard</title>
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
                    <h1 class="h2">Customers</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <span class="badge bg-primary fs-6"><?php echo $totalCustomers; ?> Total Customers</span>
                        </div>
                    </div>
                </div>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <!-- Search Bar -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-8">
                                <input type="text" name="search" class="form-control" placeholder="Search customers by name, email, or phone..." value="<?php echo htmlspecialchars($search); ?>">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-search me-1"></i> Search
                                </button>
                            </div>
                            <div class="col-md-2">
                                <a href="customers.php" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-x-circle me-1"></i> Clear
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Customers Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Customer ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Total Orders</th>
                                        <th>Total Spent</th>
                                        <th>Last Order</th>
                                        <th>Joined</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($customers) > 0): ?>
                                        <?php foreach ($customers as $customer): ?>
                                            <tr>
                                                <td>#<?php echo $customer['user_id']; ?></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($customer['name'] ?? 'N/A'); ?></strong>
                                                </td>
                                                <td><?php echo htmlspecialchars($customer['email'] ?? 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($customer['phone'] ?? 'N/A'); ?></td>
                                                <td>
                                                    <span class="badge bg-info"><?php echo $customer['total_orders']; ?></span>
                                                </td>
                                                <td>
                                                    <strong>₦<?php echo number_format($customer['total_spent'], 2); ?></strong>
                                                </td>
                                                <td>
                                                    <?php if ($customer['last_order_date']): ?>
                                                        <?php echo date('M d, Y', strtotime($customer['last_order_date'])); ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">No orders</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo date('M d, Y', strtotime($customer['created_at'])); ?></td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="customer-details.php?id=<?php echo $customer['user_id']; ?>" class="btn btn-outline-primary" title="View Details">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="orders.php?customer_id=<?php echo $customer['user_id']; ?>" class="btn btn-outline-secondary" title="View Orders">
                                                            <i class="bi bi-bag"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                <?php if (!empty($search)): ?>
                                                    No customers found matching "<?php echo htmlspecialchars($search); ?>"
                                                <?php else: ?>
                                                    No customers found
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <nav aria-label="Page navigation" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            
                            <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>" aria-label="Next">
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
</body>
</html>
