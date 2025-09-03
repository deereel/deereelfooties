<?php
session_start();
require_once '../auth/db.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Check if admin is logged in
if (!isset($_SESSION['admin_user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Get parameters
$period = $_GET['period'] ?? 'monthly';
$year = (int)($_GET['year'] ?? date('Y'));
$month = (int)($_GET['month'] ?? date('n'));

try {
    $salesData = getSalesData($pdo, $period, $year, $month);
    $chartData = getChartData($pdo, $period, $year, $month);
    
    echo json_encode([
        'success' => true,
        'salesData' => $salesData,
        'chartData' => $chartData
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

function getSalesData($pdo, $period, $year, $month) {
    $data = [
        'total_sales' => 0,
        'total_orders' => 0,
        'avg_order_value' => 0,
        'completed_orders' => 0
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
        SUM(COALESCE(subtotal, total, 0)) as total_sales,
        AVG(COALESCE(subtotal, total, 0)) as avg_order_value,
        SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) as completed_orders
        FROM orders WHERE $dateFilter");
    $stmt->execute($params);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $data['total_orders'] = (int)$result['total_orders'];
        $data['total_sales'] = (float)$result['total_sales'];
        $data['avg_order_value'] = (float)$result['avg_order_value'];
        $data['completed_orders'] = (int)$result['completed_orders'];
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
                $stmt = $pdo->prepare("SELECT COALESCE(SUM(COALESCE(subtotal, total, 0)), 0) as sales FROM orders WHERE DATE(created_at) = ?");
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
                $stmt = $pdo->prepare("SELECT COALESCE(SUM(COALESCE(subtotal, total, 0)), 0) as sales FROM orders WHERE DATE(created_at) = ?");
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
                $stmt = $pdo->prepare("SELECT COALESCE(SUM(COALESCE(subtotal, total, 0)), 0) as sales FROM orders WHERE YEAR(created_at) = ? AND MONTH(created_at) = ?");
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