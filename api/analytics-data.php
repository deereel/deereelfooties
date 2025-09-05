<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once '../auth/db.php';
require_once '../middleware/PermissionMiddleware.php';

// Check if user is logged in
if (!isset($_SESSION['admin_user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Check permissions
try {
    $permissionMiddleware = new PermissionMiddleware('view_analytics');
    $permissionMiddleware->handle();
} catch (Exception $e) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        handleGetAnalytics();
        break;
    case 'POST':
        handlePostAnalytics();
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

function handleGetAnalytics() {
    global $pdo;

    $type = isset($_GET['type']) ? $_GET['type'] : 'overview';
    $period = isset($_GET['period']) ? $_GET['period'] : '30d';
    $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('-30 days'));
    $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

    try {
        switch ($type) {
            case 'overview':
                echo json_encode(getOverviewAnalytics($pdo, $startDate, $endDate));
                break;
            case 'sales':
                echo json_encode(getSalesAnalytics($pdo, $startDate, $endDate));
                break;
            case 'customers':
                echo json_encode(getCustomerAnalytics($pdo, $startDate, $endDate));
                break;
            case 'products':
                echo json_encode(getProductAnalytics($pdo, $startDate, $endDate));
                break;
            case 'realtime':
                echo json_encode(getRealtimeAnalytics($pdo));
                break;
            default:
                echo json_encode(['error' => 'Invalid analytics type']);
                break;
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function handlePostAnalytics() {
    // Handle POST requests for analytics (e.g., custom reports, data export)
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON data']);
        return;
    }

    // Process analytics request
    echo json_encode(['status' => 'success', 'message' => 'Analytics request processed']);
}

function getOverviewAnalytics($pdo, $startDate, $endDate) {
    // Basic KPIs
    $kpiQuery = "
        SELECT
            COUNT(*) as total_orders,
            SUM(COALESCE(subtotal, total_amount, 0)) as total_revenue,
            AVG(COALESCE(subtotal, total_amount, 0)) as avg_order_value,
            COUNT(DISTINCT user_id) as unique_customers
        FROM orders
        WHERE DATE(created_at) BETWEEN ? AND ?
    ";
    $kpiStmt = $pdo->prepare($kpiQuery);
    $kpiStmt->execute([$startDate, $endDate]);
    $kpis = $kpiStmt->fetch(PDO::FETCH_ASSOC);

    // Previous period comparison
    $prevStartDate = date('Y-m-d', strtotime($startDate . ' -30 days'));
    $prevEndDate = date('Y-m-d', strtotime($endDate . ' -30 days'));

    $prevKpiStmt = $pdo->prepare($kpiQuery);
    $prevKpiStmt->execute([$prevStartDate, $prevEndDate]);
    $prevKpis = $prevKpiStmt->fetch(PDO::FETCH_ASSOC);

    // Calculate percentage changes
    $kpis['revenue_change'] = calculatePercentageChange($prevKpis['total_revenue'], $kpis['total_revenue']);
    $kpis['orders_change'] = calculatePercentageChange($prevKpis['total_orders'], $kpis['total_orders']);
    $kpis['customers_change'] = calculatePercentageChange($prevKpis['unique_customers'], $kpis['unique_customers']);
    $kpis['avg_order_change'] = calculatePercentageChange($prevKpis['avg_order_value'], $kpis['avg_order_value']);

    return [
        'kpis' => $kpis,
        'period' => ['start' => $startDate, 'end' => $endDate]
    ];
}

function getSalesAnalytics($pdo, $startDate, $endDate) {
    // Daily sales data
    $dailyQuery = "
        SELECT
            DATE(created_at) as date,
            COUNT(*) as orders,
            SUM(COALESCE(subtotal, total_amount, 0)) as revenue
        FROM orders
        WHERE DATE(created_at) BETWEEN ? AND ?
        GROUP BY DATE(created_at)
        ORDER BY date
    ";
    $dailyStmt = $pdo->prepare($dailyQuery);
    $dailyStmt->execute([$startDate, $endDate]);
    $dailySales = $dailyStmt->fetchAll(PDO::FETCH_ASSOC);

    // Sales by payment method (if available)
    $paymentQuery = "
        SELECT
            COALESCE(payment_method, 'Unknown') as payment_method,
            COUNT(*) as orders,
            SUM(COALESCE(subtotal, total_amount, 0)) as revenue
        FROM orders
        WHERE DATE(created_at) BETWEEN ? AND ?
        GROUP BY payment_method
        ORDER BY revenue DESC
    ";
    $paymentStmt = $pdo->prepare($paymentQuery);
    $paymentStmt->execute([$startDate, $endDate]);
    $salesByPayment = $paymentStmt->fetchAll(PDO::FETCH_ASSOC);

    return [
        'daily_sales' => $dailySales,
        'sales_by_payment' => $salesByPayment
    ];
}

function getCustomerAnalytics($pdo, $startDate, $endDate) {
    // Customer acquisition
    $acquisitionQuery = "
        SELECT
            DATE(created_at) as date,
            COUNT(*) as new_customers
        FROM users
        WHERE DATE(created_at) BETWEEN ? AND ?
        GROUP BY DATE(created_at)
        ORDER BY date
    ";
    $acquisitionStmt = $pdo->prepare($acquisitionQuery);
    $acquisitionStmt->execute([$startDate, $endDate]);
    $customerAcquisition = $acquisitionStmt->fetchAll(PDO::FETCH_ASSOC);

    // Customer lifetime value
    $ltvQuery = "
        SELECT
            u.user_id,
            u.name,
            u.email,
            COUNT(o.order_id) as total_orders,
            SUM(COALESCE(o.subtotal, o.total_amount, 0)) as lifetime_value,
            MAX(o.created_at) as last_order_date
        FROM users u
        LEFT JOIN orders o ON u.user_id = o.user_id
        WHERE DATE(u.created_at) BETWEEN ? AND ?
        GROUP BY u.user_id, u.name, u.email
        ORDER BY lifetime_value DESC
        LIMIT 20
    ";
    $ltvStmt = $pdo->prepare($ltvQuery);
    $ltvStmt->execute([$startDate, $endDate]);
    $topCustomers = $ltvStmt->fetchAll(PDO::FETCH_ASSOC);

    return [
        'customer_acquisition' => $customerAcquisition,
        'top_customers' => $topCustomers
    ];
}

function getProductAnalytics($pdo, $startDate, $endDate) {
    // Top products by revenue
    $productsQuery = "
        SELECT
            p.name,
            p.product_id,
            SUM(oi.quantity) as total_quantity,
            SUM(oi.price * oi.quantity) as total_revenue,
            COUNT(DISTINCT o.order_id) as orders_count
        FROM order_items oi
        JOIN orders o ON oi.order_id = o.order_id
        LEFT JOIN products p ON oi.product_id = p.product_id
        WHERE DATE(o.created_at) BETWEEN ? AND ?
        GROUP BY p.product_id, p.name
        ORDER BY total_revenue DESC
        LIMIT 20
    ";
    $productsStmt = $pdo->prepare($productsQuery);
    $productsStmt->execute([$startDate, $endDate]);
    $topProducts = $productsStmt->fetchAll(PDO::FETCH_ASSOC);

    // Product performance over time
    $performanceQuery = "
        SELECT
            DATE(o.created_at) as date,
            p.name,
            SUM(oi.quantity) as quantity_sold,
            SUM(oi.price * oi.quantity) as revenue
        FROM order_items oi
        JOIN orders o ON oi.order_id = o.order_id
        LEFT JOIN products p ON oi.product_id = p.product_id
        WHERE DATE(o.created_at) BETWEEN ? AND ?
        GROUP BY DATE(o.created_at), p.product_id, p.name
        ORDER BY date, revenue DESC
    ";
    $performanceStmt = $pdo->prepare($performanceQuery);
    $performanceStmt->execute([$startDate, $endDate]);
    $productPerformance = $performanceStmt->fetchAll(PDO::FETCH_ASSOC);

    return [
        'top_products' => $topProducts,
        'product_performance' => $productPerformance
    ];
}

function getRealtimeAnalytics($pdo) {
    // Today's metrics
    $today = date('Y-m-d');
    $todayQuery = "
        SELECT
            COUNT(*) as today_orders,
            SUM(COALESCE(subtotal, total_amount, 0)) as today_revenue,
            COUNT(DISTINCT user_id) as today_customers
        FROM orders
        WHERE DATE(created_at) = ?
    ";
    $todayStmt = $pdo->prepare($todayQuery);
    $todayStmt->execute([$today]);
    $todayStats = $todayStmt->fetch(PDO::FETCH_ASSOC);

    // Current active users (rough estimate based on recent activity)
    $activeUsersQuery = "
        SELECT COUNT(DISTINCT user_id) as active_users
        FROM orders
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
    ";
    $activeUsers = $pdo->query($activeUsersQuery)->fetchColumn();

    // Recent orders (last 10)
    $recentOrdersQuery = "
        SELECT
            order_id,
            customer_name,
            COALESCE(subtotal, total_amount, 0) as total,
            status,
            created_at
        FROM orders
        ORDER BY created_at DESC
        LIMIT 10
    ";
    $recentOrders = $pdo->query($recentOrdersQuery)->fetchAll(PDO::FETCH_ASSOC);

    return [
        'today_stats' => $todayStats,
        'active_users' => $activeUsers,
        'recent_orders' => $recentOrders,
        'timestamp' => date('Y-m-d H:i:s')
    ];
}

function calculatePercentageChange($oldValue, $newValue) {
    if ($oldValue == 0) {
        return $newValue > 0 ? 100 : 0;
    }
    return round((($newValue - $oldValue) / $oldValue) * 100, 2);
}
?>
