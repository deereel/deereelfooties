<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';

$orderId = $_GET['order_id'] ?? null;

if (!$orderId) {
    header('Location: /index.php');
    exit;
}

// Get order details
try {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order) {
        header('Location: /index.php');
        exit;
    }
} catch (PDOException $e) {
    header('Location: /index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Success | DeeReel Footies</title>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>
</head>

<body class="bg-background">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card text-center">
                    <div class="card-body p-5">
                        <div class="text-success mb-4">
                            <i class="fas fa-check-circle" style="font-size: 4rem;"></i>
                        </div>
                        <h2 class="text-success mb-3">Payment Successful!</h2>
                        <p class="lead mb-4">Thank you for your order. Your payment has been processed successfully.</p>
                        
                        <div class="bg-light p-4 rounded mb-4">
                            <h5>Order Details</h5>
                            <p><strong>Order ID:</strong> #<?php echo $order['order_id']; ?></p>
                            <p><strong>Amount:</strong> ₦<?php echo number_format($order['total'] ?? $order['subtotal'], 2); ?></p>
                            <p><strong>Status:</strong> <span class="badge bg-primary">Processing</span></p>
                        </div>
                        
                        <p class="text-muted mb-4">
                            You will receive an order confirmation email shortly. 
                            You can track your order status in your dashboard.
                        </p>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                            <a href="/dashboard.php" class="btn btn-primary">View Order</a>
                            <a href="/products.php" class="btn btn-outline-primary">Continue Shopping</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'); ?>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php'); ?>
</body>
</html>