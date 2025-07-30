<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../auth/db.php';

// Get date range from URL parameters
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01'); // First day of current month
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d'); // Today
$reportType = isset($_GET['type']) ? $_GET['type'] : 'summary';

try {
    // Get summary statistics
    $totalOrdersStmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE DATE(created_at) BETWEEN ? AND ?");
    $totalOrdersStmt->execute([$startDate, $endDate]);
    $totalOrders = $totalOrdersStmt->fetchColumn();

    $totalRevenueStmt = $pdo->prepare("SELECT SUM(COALESCE(subtotal, total, 0)) FROM orders WHERE status = 'Completed' AND DATE(created_at) BETWEEN ? AND ?");
    $totalRevenueStmt->execute([$startDate, $endDate]);
    $totalRevenue = $totalRevenueStmt->fetchColumn() ?: 0;

    $pendingOrdersStmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE status = 'Pending' AND DATE(created_at) BETWEEN ? AND ?");
    $pendingOrdersStmt->execute([$startDate, $endDate]);
    $pendingOrders = $pendingOrdersStmt->fetchColumn();

    $completedOrdersStmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE status = 'Completed' AND DATE(created_at) BETWEEN ? AND ?");
    $completedOrdersStmt->execute([$startDate, $endDate]);
    $completedOrders = $completedOrdersStmt->fetchColumn();

    // Get detailed orders for the period
    $ordersStmt = $pdo->prepare("SELECT * FROM orders WHERE DATE(created_at) BETWEEN ? AND ? ORDER BY created_at DESC");
    $ordersStmt->execute([$startDate, $endDate]);
    $orders = $ordersStmt->fetchAll(PDO::FETCH_ASSOC);

    // Get top customers
    $topCustomersStmt = $pdo->prepare("SELECT customer_name, COUNT(*) as order_count, SUM(COALESCE(subtotal, total, 0)) as total_spent FROM orders WHERE DATE(created_at) BETWEEN ? AND ? GROUP BY customer_name ORDER BY total_spent DESC LIMIT 10");
    $topCustomersStmt->execute([$startDate, $endDate]);
    $topCustomers = $topCustomersStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = 'Error generating report: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Report - DeeReel Footies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            .print-break { page-break-before: always; }
            body { font-size: 12px; }
            .container-fluid { padding: 0; }
            .card { border: 1px solid #dee2e6; box-shadow: none; }
            .table { font-size: 11px; }
        }
        
        .report-header {
            border-bottom: 3px solid #0d6efd;
            margin-bottom: 30px;
            padding-bottom: 20px;
        }
        
        .stat-card {
            border-left: 4px solid #0d6efd;
            background: #f8f9fa;
        }
        
        .company-logo {
            max-height: 60px;
        }
    </style>
</head>
<body>
    <!-- Navigation (hidden in print) -->
    <div class="no-print">
        <?php include 'includes/header.php'; ?>
        
        <div class="container-fluid">
            <div class="row">
                <?php include 'includes/sidebar.php'; ?>
                
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="h2">Business Reports</h1>
                        <div class="btn-group">
                            <button onclick="window.print()" class="btn btn-primary">
                                <i class="bi bi-printer me-2"></i>Print Report
                            </button>
                            <button onclick="generatePDF()" class="btn btn-success">
                                <i class="bi bi-file-pdf me-2"></i>Save as PDF
                            </button>
                        </div>
                    </div>
                    
                    <!-- Date Range Filter -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <form method="GET" class="row g-3">
                                <div class="col-md-4">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $startDate; ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $endDate; ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary d-block">Generate Report</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <!-- Report Content (printable) -->
    <div class="container-fluid">
        <!-- Report Header -->
        <div class="report-header text-center">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <img src="../images/drf-logo.webp" alt="DeeReel Footies" class="company-logo">
                </div>
                <div class="col-md-6">
                    <h1 class="mb-0">DeeReel Footies</h1>
                    <h3 class="text-muted">Business Report</h3>
                    <p class="mb-0">Period: <?php echo date('F d, Y', strtotime($startDate)); ?> - <?php echo date('F d, Y', strtotime($endDate)); ?></p>
                </div>
                <div class="col-md-3 text-end">
                    <p class="mb-0"><strong>Generated:</strong> <?php echo date('F d, Y g:i A'); ?></p>
                    <p class="mb-0"><strong>By:</strong> Admin</p>
                </div>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card stat-card">
                    <div class="card-body text-center">
                        <h2 class="text-primary"><?php echo $totalOrders; ?></h2>
                        <p class="mb-0">Total Orders</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card">
                    <div class="card-body text-center">
                        <h2 class="text-success">₦<?php echo number_format($totalRevenue, 2); ?></h2>
                        <p class="mb-0">Total Revenue</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card">
                    <div class="card-body text-center">
                        <h2 class="text-warning"><?php echo $pendingOrders; ?></h2>
                        <p class="mb-0">Pending Orders</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card">
                    <div class="card-body text-center">
                        <h2 class="text-info"><?php echo $completedOrders; ?></h2>
                        <p class="mb-0">Completed Orders</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Customers -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Top Customers by Revenue</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Orders</th>
                                        <th>Total Spent</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($topCustomers as $customer): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($customer['customer_name'] ?: 'Guest'); ?></td>
                                        <td><?php echo $customer['order_count']; ?></td>
                                        <td>₦<?php echo number_format($customer['total_spent'], 2); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Order Status Breakdown</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $statusStmt = $pdo->prepare("SELECT status, COUNT(*) as count FROM orders WHERE DATE(created_at) BETWEEN ? AND ? GROUP BY status");
                        $statusStmt->execute([$startDate, $endDate]);
                        $statusBreakdown = $statusStmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Count</th>
                                        <th>Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($statusBreakdown as $status): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($status['status']); ?></td>
                                        <td><?php echo $status['count']; ?></td>
                                        <td><?php echo $totalOrders > 0 ? round(($status['count'] / $totalOrders) * 100, 1) : 0; ?>%</td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Orders List -->
        <div class="print-break">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Detailed Orders List</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>#<?php echo $order['order_id']; ?></td>
                                    <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                    <td><?php echo htmlspecialchars($order['customer_name'] ?: 'Guest'); ?></td>
                                    <td>₦<?php echo number_format($order['subtotal'] ?? $order['total'] ?? 0, 2); ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo $order['status'] === 'Completed' ? 'success' : 
                                                ($order['status'] === 'Processing' ? 'primary' : 
                                                ($order['status'] === 'Cancelled' ? 'danger' : 'warning')); 
                                        ?>"><?php echo $order['status']; ?></span>
                                    </td>
                                    <td>
                                        <?php if ($order['payment_confirmed']): ?>
                                            <span class="badge bg-success">Confirmed</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Footer -->
        <div class="text-center mt-4 pt-4 border-top">
            <p class="text-muted mb-0">This report was generated automatically by DeeReel Footies Admin System</p>
            <p class="text-muted mb-0">© <?php echo date('Y'); ?> DeeReel Footies. All rights reserved.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function generatePDF() {
            // Use browser's print dialog with PDF option
            window.print();
        }
        
        // Auto-focus on print-friendly layout
        document.addEventListener('DOMContentLoaded', function() {
            // Add any additional print optimizations here
        });
    </script>
</body>
</html>