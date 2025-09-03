<?php
require_once '../auth/db.php';
session_start();

// Set headers for JSON response
header('Content-Type: application/json');

// Check if admin is logged in
if (!isset($_SESSION['admin_user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Handle different HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'GET') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get pagination parameters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$offset = ($page - 1) * $limit;

// Get filter parameters
$status = isset($_GET['status']) ? $_GET['status'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Build query
$query = "SELECT * FROM orders";
$countQuery = "SELECT COUNT(*) FROM orders";
$params = [];
$whereAdded = false;

// Add status filter
if (!empty($status)) {
    $query .= " WHERE status = ?";
    $countQuery .= " WHERE status = ?";
    $params[] = $status;
    $whereAdded = true;
}

// Add search filter
if (!empty($search)) {
    if ($whereAdded) {
        $query .= " AND (customer_name LIKE ? OR customer_email LIKE ? OR order_id LIKE ?)";
        $countQuery .= " AND (customer_name LIKE ? OR customer_email LIKE ? OR order_id LIKE ?)";
    } else {
        $query .= " WHERE (customer_name LIKE ? OR customer_email LIKE ? OR order_id LIKE ?)";
        $countQuery .= " WHERE (customer_name LIKE ? OR customer_email LIKE ? OR order_id LIKE ?)";
    }
    $searchParam = "%$search%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
}

// Add sorting
$query .= " ORDER BY created_at DESC";

// Add pagination
$query .= " LIMIT $limit OFFSET $offset";

try {
    // Get total count for pagination
    $countStmt = $pdo->prepare($countQuery);
    $countStmt->execute(array_slice($params, 0, -2)); // Remove limit and offset params
    $totalOrders = $countStmt->fetchColumn();
    $totalPages = ceil($totalOrders / $limit);
    
    // Get orders
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get payment proof status for each order
    foreach ($orders as &$order) {
        $proofStmt = $pdo->prepare("SELECT id FROM payment_proof WHERE order_id = ?");
        $proofStmt->execute([$order['order_id']]);
        $order['has_payment_proof'] = $proofStmt->rowCount() > 0;
    }
    
    echo json_encode([
        'success' => true, 
        'orders' => $orders,
        'pagination' => [
            'total' => $totalOrders,
            'per_page' => $limit,
            'current_page' => $page,
            'last_page' => $totalPages
        ]
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>