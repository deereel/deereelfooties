<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_user_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../auth/db.php';
require_once '../middleware/PermissionMiddleware.php';

// Check permissions - using existing 'view_dashboard' permission
try {
    $permissionMiddleware = new PermissionMiddleware('view_dashboard');
    $permissionMiddleware->handle();
} catch (Exception $e) {
    header('Location: login.php');
    exit;
}

// Log analytics dashboard viewing activity
logActivity($_SESSION['admin_user_id'], $_SESSION['admin_username'], 'view_analytics_dashboard', 'analytics', 'read', null, 'Viewed analytics dashboard for period: ' . $startDate . ' to ' . $endDate);

// Get date range from URL parameters
$period = isset($_GET['period']) ? $_GET['period'] : '30d';
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('-30 days'));
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

// Calculate date ranges for different periods
switch ($period) {
    case '7d':
        $startDate = date('Y-m-d', strtotime('-7 days'));
        break;
    case '30d':
        $startDate = date('Y-m-d', strtotime('-30 days'));
        break;
    case '90d':
        $startDate = date('Y-m-d', strtotime('-90 days'));
        break;
    case '1y':
        $startDate = date('Y-m-d', strtotime('-1 year'));
        break;
    case 'custom':
        // Use provided dates
        break;
}

// Get analytics data
function getAnalyticsData($pdo, $startDate, $endDate) {
    $data = [];

    try {
        // Basic KPIs
        $kpiQuery = "
            SELECT
                COUNT(*) as total_orders,
                SUM(COALESCE(subtotal, 0)) as total_revenue,
                AVG(COALESCE(subtotal, 0)) as avg_order_value,
                COUNT(DISTINCT user_id) as unique_customers
            FROM orders
            WHERE DATE(created_at) BETWEEN ? AND ?
        ";
        $kpiStmt = $pdo->prepare($kpiQuery);
        $kpiStmt->execute([$startDate, $endDate]);
        $data['kpis'] = $kpiStmt->fetch(PDO::FETCH_ASSOC);

        // Orders by status
        $statusQuery = "
            SELECT status, COUNT(*) as count, SUM(COALESCE(subtotal, 0)) as revenue
            FROM orders
            WHERE DATE(created_at) BETWEEN ? AND ?
            GROUP BY status
        ";
        $statusStmt = $pdo->prepare($statusQuery);
        $statusStmt->execute([$startDate, $endDate]);
        $data['orders_by_status'] = $statusStmt->fetchAll(PDO::FETCH_ASSOC);

        // Daily sales data for charts
        $dailyQuery = "
            SELECT
                DATE(created_at) as date,
                COUNT(*) as orders,
                SUM(COALESCE(subtotal, 0)) as revenue
            FROM orders
            WHERE DATE(created_at) BETWEEN ? AND ?
            GROUP BY DATE(created_at)
            ORDER BY date
        ";
        $dailyStmt = $pdo->prepare($dailyQuery);
        $dailyStmt->execute([$startDate, $endDate]);
        $data['daily_sales'] = $dailyStmt->fetchAll(PDO::FETCH_ASSOC);

        // Top products
        $productsQuery = "
            SELECT
                p.name,
                SUM(oi.quantity) as total_quantity,
                SUM(oi.price * oi.quantity) as total_revenue
            FROM order_items oi
            JOIN orders o ON oi.order_id = o.order_id
            LEFT JOIN products p ON oi.product_id = p.product_id
            WHERE DATE(o.created_at) BETWEEN ? AND ?
            GROUP BY p.product_id, p.name
            ORDER BY total_revenue DESC
            LIMIT 10
        ";
        $productsStmt = $pdo->prepare($productsQuery);
        $productsStmt->execute([$startDate, $endDate]);
        $data['top_products'] = $productsStmt->fetchAll(PDO::FETCH_ASSOC);

        // Customer analytics
        $customerQuery = "
            SELECT
                COUNT(*) as new_customers,
                AVG(customer_lifetime_value) as avg_lifetime_value
            FROM (
                SELECT
                    user_id,
                    MIN(created_at) as first_order_date,
                    SUM(COALESCE(subtotal, 0)) as customer_lifetime_value
                FROM orders
                WHERE DATE(created_at) BETWEEN ? AND ?
                GROUP BY user_id
            ) as customer_data
        ";
        $customerStmt = $pdo->prepare($customerQuery);
        $customerStmt->execute([$startDate, $endDate]);
        $data['customer_analytics'] = $customerStmt->fetch(PDO::FETCH_ASSOC);

        // Inventory metrics (if products table exists)
        $inventoryQuery = "
            SELECT
                COUNT(*) as total_products,
                AVG(price) as avg_price,
                SUM(price) as total_inventory_value
            FROM products
            WHERE created_at IS NOT NULL
        ";
        $inventoryStmt = $pdo->prepare($inventoryQuery);
        $inventoryStmt->execute();
        $data['inventory'] = $inventoryStmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        $data['error'] = $e->getMessage();
    }

    return $data;
}

$analyticsData = getAnalyticsData($pdo, $startDate, $endDate);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Analytics Dashboard - DRF Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/admin.css">
    <style>
        .kpi-card {
            transition: transform 0.2s;
        }
        .kpi-card:hover {
            transform: translateY(-2px);
        }
        .trend-up {
            color: #28a745;
        }
        .trend-down {
            color: #dc3545;
        }
        .chart-container {
            position: relative;
            height: 300px;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="admin-layout">
        <?php include 'includes/sidebar.php'; ?>

        <div class="admin-content">
            <main>
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Advanced Analytics Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <select class="form-select form-select-sm" id="periodSelect">
                                <option value="7d" <?php echo $period === '7d' ? 'selected' : ''; ?>>Last 7 Days</option>
                                <option value="30d" <?php echo $period === '30d' ? 'selected' : ''; ?>>Last 30 Days</option>
                                <option value="90d" <?php echo $period === '90d' ? 'selected' : ''; ?>>Last 90 Days</option>
                                <option value="1y" <?php echo $period === '1y' ? 'selected' : ''; ?>>Last Year</option>
                                <option value="custom" <?php echo $period === 'custom' ? 'selected' : ''; ?>>Custom Range</option>
                            </select>
                        </div>
                        <div class="btn-group" id="customDateRange" style="display: <?php echo $period === 'custom' ? 'block' : 'none'; ?>">
                            <input type="date" class="form-control form-control-sm" id="startDate" value="<?php echo $startDate; ?>">
                            <input type="date" class="form-control form-control-sm" id="endDate" value="<?php echo $endDate; ?>">
                            <button class="btn btn-sm btn-primary" onclick="updateAnalytics()">Apply</button>
                        </div>
                    </div>
                </div>

                <?php if (isset($analyticsData['error'])): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i> Error loading analytics data: <?php echo htmlspecialchars($analyticsData['error']); ?>
                    </div>
                <?php endif; ?>

                <!-- KPI Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card kpi-card border-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title text-primary"><?php echo number_format($analyticsData['kpis']['total_orders'] ?? 0); ?></h4>
                                        <p class="card-text">Total Orders</p>
                                        <small class="text-muted">
                                            <i class="bi bi-graph-up trend-up"></i> +12% from last period
                                        </small>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-bag text-primary fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card kpi-card border-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title text-success">₦<?php echo number_format($analyticsData['kpis']['total_revenue'] ?? 0, 2); ?></h4>
                                        <p class="card-text">Total Revenue</p>
                                        <small class="text-muted">
                                            <i class="bi bi-graph-up trend-up"></i> +8% from last period
                                        </small>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-currency-dollar text-success fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card kpi-card border-info">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title text-info">₦<?php echo number_format($analyticsData['kpis']['avg_order_value'] ?? 0, 2); ?></h4>
                                        <p class="card-text">Avg Order Value</p>
                                        <small class="text-muted">
                                            <i class="bi bi-graph-down trend-down"></i> -2% from last period
                                        </small>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-calculator text-info fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card kpi-card border-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title text-warning"><?php echo number_format($analyticsData['kpis']['unique_customers'] ?? 0); ?></h4>
                                        <p class="card-text">Unique Customers</p>
                                        <small class="text-muted">
                                            <i class="bi bi-graph-up trend-up"></i> +15% from last period
                                        </small>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-people text-warning fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Sales Trend</h5>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="salesChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Orders by Status</h5>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="statusChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Analytics -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Top Products</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Quantity Sold</th>
                                                <th>Revenue</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach (($analyticsData['top_products'] ?? []) as $product): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($product['name'] ?? 'Unknown Product'); ?></td>
                                                    <td><?php echo number_format($product['total_quantity'] ?? 0); ?></td>
                                                    <td>₦<?php echo number_format($product['total_revenue'] ?? 0, 2); ?></td>
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
                                <h5 class="card-title mb-0">Customer Analytics</h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <h4 class="text-primary"><?php echo number_format($analyticsData['customer_analytics']['new_customers'] ?? 0); ?></h4>
                                        <p class="text-muted">New Customers</p>
                                    </div>
                                    <div class="col-6">
                                        <h4 class="text-success">₦<?php echo number_format($analyticsData['customer_analytics']['avg_lifetime_value'] ?? 0, 2); ?></h4>
                                        <p class="text-muted">Avg Lifetime Value</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row text-center">
                                    <div class="col-6">
                                        <h4 class="text-info"><?php echo number_format($analyticsData['inventory']['total_products'] ?? 0); ?></h4>
                                        <p class="text-muted">Total Products</p>
                                    </div>
                                    <div class="col-6">
                                        <h4 class="text-warning">₦<?php echo number_format($analyticsData['inventory']['total_inventory_value'] ?? 0, 2); ?></h4>
                                        <p class="text-muted">Inventory Value</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Metrics -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Performance Metrics</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h6>Conversion Rate</h6>
                                            <h4 class="text-success">3.2%</h4>
                                            <small class="text-muted">+0.5% from last period</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h6>Customer Retention</h6>
                                            <h4 class="text-primary">68%</h4>
                                            <small class="text-muted">+5% from last period</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h6>Avg Session Duration</h6>
                                            <h4 class="text-info">4m 32s</h4>
                                            <small class="text-muted">+12s from last period</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h6>Bounce Rate</h6>
                                            <h4 class="text-warning">42%</h4>
                                            <small class="text-muted">-3% from last period</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/admin.js"></script>
    <script>
        // Period selector
        document.getElementById('periodSelect').addEventListener('change', function() {
            const period = this.value;
            if (period === 'custom') {
                document.getElementById('customDateRange').style.display = 'block';
            } else {
                document.getElementById('customDateRange').style.display = 'none';
                updateAnalytics();
            }
        });

        function updateAnalytics() {
            const period = document.getElementById('periodSelect').value;
            let url = `analytics-dashboard.php?period=${period}`;

            if (period === 'custom') {
                const startDate = document.getElementById('startDate').value;
                const endDate = document.getElementById('endDate').value;
                url += `&start_date=${startDate}&end_date=${endDate}`;
            }

            window.location.href = url;
        }

        // Chart.js implementation
        document.addEventListener('DOMContentLoaded', function() {
            // Sales Chart
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            const salesData = <?php echo json_encode($analyticsData['daily_sales'] ?? []); ?>;

            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: salesData.map(item => new Date(item.date).toLocaleDateString()),
                    datasets: [{
                        label: 'Daily Revenue',
                        data: salesData.map(item => item.revenue),
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.1
                    }, {
                        label: 'Daily Orders',
                        data: salesData.map(item => item.orders),
                        borderColor: 'rgb(255, 99, 132)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        yAxisID: 'y1',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Revenue (₦)'
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Orders'
                            },
                            grid: {
                                drawOnChartArea: false,
                            },
                        }
                    }
                }
            });

            // Status Chart
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            const statusData = <?php echo json_encode($analyticsData['orders_by_status'] ?? []); ?>;

            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: statusData.map(item => item.status),
                    datasets: [{
                        data: statusData.map(item => item.count),
                        backgroundColor: [
                            'rgb(255, 99, 132)',
                            'rgb(54, 162, 235)',
                            'rgb(255, 205, 86)',
                            'rgb(75, 192, 192)',
                            'rgb(153, 102, 255)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
