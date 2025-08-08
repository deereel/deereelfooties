<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../auth/db.php';

// Get filter parameters
$period = isset($_GET['period']) ? $_GET['period'] : 'monthly';
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');

// Get sales data
try {
    $salesData = getSalesData($pdo, $period, $year, $month);
    $chartData = getChartData($pdo, $period, $year, $month);
} catch (PDOException $e) {
    $error = 'Error retrieving sales data: ' . $e->getMessage();
}

function getSalesData($pdo, $period, $year, $month) {
    $data = [
        'total_sales' => 0,
        'total_orders' => 0,
        'avg_order_value' => 0,
        'completed_orders' => 0,
        'conversion_rate' => 0,
        'growth_rate' => 0
    ];
    
    // Build date filter based on period
    $dateFilter = '';
    $params = [];
    
    switch ($period) {
        case 'weekly':
            $startDate = date('Y-m-d', strtotime('monday this week'));
            $endDate = date('Y-m-d', strtotime('sunday this week'));
            $dateFilter = "DATE(created_at) BETWEEN ? AND ?";
            $params = [$startDate, $endDate];
            break;
        case 'monthly':
            $dateFilter = "YEAR(created_at) = ? AND MONTH(created_at) = ?";
            $params = [$year, $month];
            break;
        case 'yearly':
            $dateFilter = "YEAR(created_at) = ?";
            $params = [$year];
            break;
    }
    
    // Get total sales and orders
    $stmt = $pdo->prepare("SELECT 
        COUNT(*) as total_orders,
        SUM(subtotal) as total_sales,
        AVG(subtotal) as avg_order_value,
        SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) as completed_orders
        FROM orders WHERE $dateFilter");
    $stmt->execute($params);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $data['total_orders'] = (int)$result['total_orders'];
        $data['total_sales'] = (float)$result['total_sales'];
        $data['avg_order_value'] = (float)$result['avg_order_value'];
        $data['completed_orders'] = (int)$result['completed_orders'];
        $data['conversion_rate'] = $data['total_orders'] > 0 ? ($data['completed_orders'] / $data['total_orders']) * 100 : 0;
    }
    
    // Calculate growth rate compared to previous period
    $prevParams = [];
    $prevDateFilter = '';
    
    switch ($period) {
        case 'weekly':
            $prevStart = date('Y-m-d', strtotime('monday last week'));
            $prevEnd = date('Y-m-d', strtotime('sunday last week'));
            $prevDateFilter = "DATE(created_at) BETWEEN ? AND ?";
            $prevParams = [$prevStart, $prevEnd];
            break;
        case 'monthly':
            $prevMonth = $month == 1 ? 12 : $month - 1;
            $prevYear = $month == 1 ? $year - 1 : $year;
            $prevDateFilter = "YEAR(created_at) = ? AND MONTH(created_at) = ?";
            $prevParams = [$prevYear, $prevMonth];
            break;
        case 'yearly':
            $prevDateFilter = "YEAR(created_at) = ?";
            $prevParams = [$year - 1];
            break;
    }
    
    if ($prevDateFilter) {
        $prevStmt = $pdo->prepare("SELECT SUM(subtotal) as prev_sales FROM orders WHERE $prevDateFilter");
        $prevStmt->execute($prevParams);
        $prevSales = (float)$prevStmt->fetchColumn();
        
        if ($prevSales > 0) {
            $data['growth_rate'] = (($data['total_sales'] - $prevSales) / $prevSales) * 100;
        }
    }
    
    return $data;
}

function getChartData($pdo, $period, $year, $month) {
    $chartData = [];
    
    switch ($period) {
        case 'weekly':
            // Get data for each day of the current week
            for ($i = 0; $i < 7; $i++) {
                $date = date('Y-m-d', strtotime('monday this week +' . $i . ' days'));
                $stmt = $pdo->prepare("SELECT COALESCE(SUM(subtotal), 0) as sales FROM orders WHERE DATE(created_at) = ?");
                $stmt->execute([$date]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $chartData[] = [
                    'label' => date('D', strtotime($date)),
                    'value' => (float)$result['sales']
                ];
            }
            break;
        case 'monthly':
            // Get data for each day of the month
            $daysInMonth = date('t', mktime(0, 0, 0, $month, 1, $year));
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
                $stmt = $pdo->prepare("SELECT COALESCE(SUM(subtotal), 0) as sales FROM orders WHERE DATE(created_at) = ?");
                $stmt->execute([$date]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $chartData[] = [
                    'label' => $day,
                    'value' => (float)$result['sales']
                ];
            }
            break;
        case 'yearly':
            // Get data for each month of the year
            for ($m = 1; $m <= 12; $m++) {
                $stmt = $pdo->prepare("SELECT COALESCE(SUM(subtotal), 0) as sales FROM orders WHERE YEAR(created_at) = ? AND MONTH(created_at) = ?");
                $stmt->execute([$year, $m]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $chartData[] = [
                    'label' => date('M', mktime(0, 0, 0, $m, 1)),
                    'value' => (float)$result['sales']
                ];
            }
            break;
    }
    
    return $chartData;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Sales Report</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <a href="?period=weekly" class="btn btn-sm btn-outline-secondary <?php echo $period === 'weekly' ? 'active' : ''; ?>">Weekly</a>
                            <a href="?period=monthly&year=<?php echo $year; ?>&month=<?php echo $month; ?>" class="btn btn-sm btn-outline-secondary <?php echo $period === 'monthly' ? 'active' : ''; ?>">Monthly</a>
                            <a href="?period=yearly&year=<?php echo $year; ?>" class="btn btn-sm btn-outline-secondary <?php echo $period === 'yearly' ? 'active' : ''; ?>">Yearly</a>
                        </div>
                    </div>
                </div>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <!-- Period Selector -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <input type="hidden" name="period" value="<?php echo $period; ?>">
                            
                            <?php if ($period === 'monthly' || $period === 'yearly'): ?>
                            <div class="col-md-3">
                                <label for="year" class="form-label">Year</label>
                                <select name="year" id="year" class="form-select">
                                    <?php for ($y = date('Y'); $y >= 2020; $y--): ?>
                                        <option value="<?php echo $y; ?>" <?php echo $y === $year ? 'selected' : ''; ?>><?php echo $y; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($period === 'monthly'): ?>
                            <div class="col-md-3">
                                <label for="month" class="form-label">Month</label>
                                <select name="month" id="month" class="form-select">
                                    <?php for ($m = 1; $m <= 12; $m++): ?>
                                        <option value="<?php echo $m; ?>" <?php echo $m === $month ? 'selected' : ''; ?>><?php echo date('F', mktime(0, 0, 0, $m, 1)); ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <?php endif; ?>
                            
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary d-block">Update Report</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Sales Overview Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title">₦<?php echo number_format($salesData['total_sales'], 2); ?></h4>
                                        <p class="card-text">Total Sales</p>
                                        <?php if ($salesData['growth_rate'] != 0): ?>
                                        <small class="<?php echo $salesData['growth_rate'] > 0 ? 'text-success' : 'text-danger'; ?>">
                                            <i class="bi bi-<?php echo $salesData['growth_rate'] > 0 ? 'arrow-up' : 'arrow-down'; ?>"></i>
                                            <?php echo abs(round($salesData['growth_rate'], 1)); ?>%
                                        </small>
                                        <?php endif; ?>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-currency-dollar fs-1"></i>
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
                                        <h4 class="card-title"><?php echo $salesData['total_orders']; ?></h4>
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
                        <div class="card text-white bg-info">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title">₦<?php echo number_format($salesData['avg_order_value'], 2); ?></h4>
                                        <p class="card-text">Avg Order Value</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-graph-up fs-1"></i>
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
                                        <h4 class="card-title"><?php echo round($salesData['conversion_rate'], 1); ?>%</h4>
                                        <p class="card-text">Conversion Rate</p>
                                        <small>Completed/Total Orders</small>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-check-circle fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sales Chart -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Sales Trend - <?php echo ucfirst($period); ?> View</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="salesChart" width="400" height="100"></canvas>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sales Chart
        const ctx = document.getElementById('salesChart').getContext('2d');
        const chartData = <?php echo json_encode($chartData); ?>;
        
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.map(item => item.label),
                datasets: [{
                    label: 'Sales (₦)',
                    data: chartData.map(item => item.value),
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₦' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Sales: ₦' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>