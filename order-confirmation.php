<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';

// Check if order ID is provided
if (!isset($_GET['order_id'])) {
  header('Location: /index.php');
  exit;
}

$orderId = $_GET['order_id'];

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
  // Handle error
  $order = null;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Order Confirmation | DeeReel Footies</title>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>
</head>

<body class="bg-background">

  <!-- Main Content -->
  <main>
    <div class="container my-5">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="card p-4 mb-4">
            <div class="text-center mb-4">
              <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
              <h2>Order Confirmed!</h2>
              <p class="lead">Thank you for your order. Your payment proof has been received.</p>
              <p>Order #<?php echo $orderId; ?></p>
            </div>
            
            <div class="alert alert-info">
              <p><i class="fas fa-info-circle me-2"></i> Your order is now being processed. We will update you on the status of your order.</p>
            </div>
            
            <div class="alert alert-success">
              <p><i class="fab fa-whatsapp me-2"></i> For faster response and confirmation, you can reach out and send proof of payment through WhatsApp:</p>
              <a href="https://wa.me/2347031864772?text=Hello%20DeeReeL%20Footies,%20%20Here's%20my%20proof%20of%20payment%20for%20confirmation..." 
                 class="btn btn-success btn-sm" target="_blank">
                <i class="fab fa-whatsapp me-2"></i>Send Payment Proof via WhatsApp
              </a>
            </div>
            
            <?php if ($order): ?>
            <div class="card mb-4">
              <div class="card-header bg-light">
                <h5 class="mb-0">Order Summary</h5>
              </div>
              <div class="card-body">
                <p><strong>Order Date:</strong> <?php echo date('F j, Y', strtotime($order['created_at'])); ?></p>
                <p><strong>Status:</strong> <span class="badge bg-info"><?php echo $order['status']; ?></span></p>
                <p><strong>Total:</strong> ₦<?php echo number_format($order['total']); ?></p>
              </div>
            </div>
            <?php endif; ?>
            
            <div class="text-center mt-4">
              <a href="/index.php" class="btn btn-primary me-2">
                <i class="fas fa-home me-2"></i>Return to Home
              </a>
              <?php if (isset($_SESSION['user']) || isset($_SESSION['user_id'])): ?>
              <a href="/dashboard.php#orders" class="btn btn-outline-primary">
                <i class="fas fa-box me-2"></i>View My Orders
              </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/account-modal.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php'); ?>
</body>
</html>