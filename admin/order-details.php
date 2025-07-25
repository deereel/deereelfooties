<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../auth/db.php';

// Get order ID from URL
$orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($orderId <= 0) {
    header('Location: index.php');
    exit;
}

// Process form submissions
$message = '';
$messageType = '';

// Handle payment confirmation
if (isset($_POST['confirm_payment'])) {
    try {
        // Update order status
        $updateStmt = $pdo->prepare("UPDATE orders SET status = 'Processing', payment_confirmed = 1 WHERE order_id = ?");
        $updateStmt->execute([$orderId]);
        
        // Add progress update
        $progressStmt = $pdo->prepare("INSERT INTO order_progress (order_id, status_update) VALUES (?, ?)");
        $progressStmt->execute([$orderId, 'Payment confirmed by admin']);
        
        $message = 'Payment confirmed successfully';
        $messageType = 'success';
    } catch (PDOException $e) {
        $message = 'Error confirming payment: ' . $e->getMessage();
        $messageType = 'danger';
    }
}

// Handle status update
if (isset($_POST['update_status'])) {
    $newStatus = $_POST['status'];
    $statusNote = $_POST['status_note'];
    
    try {
        // Update order status
        $updateStmt = $pdo->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
        $updateStmt->execute([$newStatus, $orderId]);
        
        // Add progress update
        $progressStmt = $pdo->prepare("INSERT INTO order_progress (order_id, status_update) VALUES (?, ?)");
        $progressStmt->execute([$orderId, $statusNote]);
        
        $message = 'Order status updated successfully';
        $messageType = 'success';
    } catch (PDOException $e) {
        $message = 'Error updating status: ' . $e->getMessage();
        $messageType = 'danger';
    }
}

// Get order details
try {
    $orderStmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ?");
    $orderStmt->execute([$orderId]);
    $order = $orderStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order) {
        header('Location: index.php');
        exit;
    }
    
    // Initialize variables
    $user = null;
    $shippingAddress = null;
    $orderItems = [];
    $paymentProof = null;
    $progressUpdates = [];
    
    // Get user information if user_id exists
    if (!empty($order['user_id'])) {
        try {
            $userStmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
            $userStmt->execute([$order['user_id']]);
            $user = $userStmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Try alternative column name
            try {
                $userStmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
                $userStmt->execute([$order['user_id']]);
                $user = $userStmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e2) {
                // User table might not exist or have different structure
            }
        }
    }
    
    // Get shipping address information
    if (!empty($order['address_id'])) {
        try {
            $addressStmt = $pdo->prepare("SELECT * FROM user_addresses WHERE id = ?");
            $addressStmt->execute([$order['address_id']]);
            $shippingAddress = $addressStmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Address table might not exist or have different structure
        }
    }
    
    // Get order items
    try {
        $itemsStmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $itemsStmt->execute([$orderId]);
        $orderItems = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $orderItems = [];
    }
    
    // Get payment proof
    try {
        $proofStmt = $pdo->prepare("SELECT * FROM payment_proof WHERE order_id = ?");
        $proofStmt->execute([$orderId]);
        $paymentProof = $proofStmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $paymentProof = null;
    }
    
    // Get order progress updates
    try {
        $progressStmt = $pdo->prepare("SELECT * FROM order_progress WHERE order_id = ? ORDER BY updated_at DESC");
        $progressStmt->execute([$orderId]);
        $progressUpdates = $progressStmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $progressUpdates = [];
    }
    
} catch (PDOException $e) {
    $message = 'Error retrieving order details: ' . $e->getMessage();
    $messageType = 'danger';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #<?php echo $orderId; ?> - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Order #<?php echo $orderId; ?></h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="index.php" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Orders
                        </a>
                    </div>
                </div>
                
                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                        <?php echo $message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Order Information</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th>Order ID:</th>
                                        <td>#<?php echo $order['order_id']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Date:</th>
                                        <td><?php echo date('F d, Y H:i', strtotime($order['created_at'])); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo $order['status'] === 'Completed' ? 'success' : 
                                                    ($order['status'] === 'Processing' ? 'primary' : 
                                                    ($order['status'] === 'Cancelled' ? 'danger' : 'warning')); 
                                            ?>">
                                                <?php echo $order['status']; ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Payment:</th>
                                        <td>
                                            <?php if (isset($order['payment_confirmed']) && $order['payment_confirmed']): ?>
                                                <span class="badge bg-success">Confirmed</span>
                                            <?php elseif ($paymentProof): ?>
                                                <span class="badge bg-warning">Proof Uploaded (Unconfirmed)</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Total:</th>
                                        <td>₦<?php echo number_format($order['subtotal'] ?? $order['total'] ?? 0, 2); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Customer Information</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th>Name:</th>
                                        <td><?php 
                                            if ($user && !empty($user['name'])) {
                                                echo htmlspecialchars($user['name']);
                                            } else {
                                                echo htmlspecialchars($order['customer_name'] ?? 'N/A');
                                            }
                                        ?></td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td><?php 
                                            if ($user && !empty($user['email'])) {
                                                echo htmlspecialchars($user['email']);
                                            } else {
                                                echo htmlspecialchars($order['customer_email'] ?? 'N/A');
                                            }
                                        ?></td>
                                    </tr>
                                    <tr>
                                        <th>Phone:</th>
                                        <td><?php 
                                            if ($user && !empty($user['phone'])) {
                                                echo htmlspecialchars($user['phone']);
                                            } else {
                                                echo htmlspecialchars($order['customer_phone'] ?? 'N/A');
                                            }
                                        ?></td>
                                    </tr>
                                    <tr>
                                        <th>User ID:</th>
                                        <td><?php echo $order['user_id'] ? $order['user_id'] : 'Guest Checkout'; ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Shipping Address</h5>
                            </div>
                            <div class="card-body">
                                <address>
                                    <?php 
                                    if ($shippingAddress) {
                                        // Display address name if available
                                        if (!empty($shippingAddress['address_name'])) {
                                            echo '<em>' . htmlspecialchars($shippingAddress['address_name']) . '</em><br>';
                                        }
                                        
                                        // Display full name
                                        if (!empty($shippingAddress['full_name'])) {
                                            echo '<strong>' . htmlspecialchars($shippingAddress['full_name']) . '</strong><br>';
                                        }
                                        
                                        // Display phone
                                        if (!empty($shippingAddress['phone'])) {
                                            echo htmlspecialchars($shippingAddress['phone']) . '<br>';
                                        }
                                        
                                        // Display address
                                        if (!empty($shippingAddress['address'])) {
                                            echo nl2br(htmlspecialchars($shippingAddress['address'])) . '<br>';
                                        }
                                        
                                        // Display city, state, country
                                        $locationParts = [];
                                        if (!empty($shippingAddress['city'])) $locationParts[] = htmlspecialchars($shippingAddress['city']);
                                        if (!empty($shippingAddress['state'])) $locationParts[] = htmlspecialchars($shippingAddress['state']);
                                        if (!empty($shippingAddress['country'])) $locationParts[] = htmlspecialchars($shippingAddress['country']);
                                        
                                        if (!empty($locationParts)) {
                                            echo implode(', ', $locationParts);
                                        }
                                    } else {
                                        // Fallback to order table data if no shipping address found
                                        if (!empty($order['customer_name'])) {
                                            echo '<strong>' . htmlspecialchars($order['customer_name']) . '</strong><br>';
                                        }
                                        
                                        if (!empty($order['phone'])) {
                                            echo htmlspecialchars($order['phone']) . '<br>';
                                        }
                                        
                                        if (!empty($order['address'])) {
                                            echo nl2br(htmlspecialchars($order['address'])) . '<br>';
                                        }
                                        
                                        $locationParts = [];
                                        if (!empty($order['city'])) $locationParts[] = htmlspecialchars($order['city']);
                                        if (!empty($order['state'])) $locationParts[] = htmlspecialchars($order['state']);
                                        if (!empty($order['country'])) $locationParts[] = htmlspecialchars($order['country']);
                                        
                                        if (!empty($locationParts)) {
                                            echo implode(', ', $locationParts);
                                        } else {
                                            echo 'No shipping address provided';
                                        }
                                    }
                                    ?>
                                </address>

                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Payment Proof</h5>
                                <?php if ($paymentProof && !isset($order['payment_confirmed'])): ?>
                                    <form method="post" class="d-inline">
                                        <button type="submit" name="confirm_payment" class="btn btn-sm btn-success">
                                            Confirm Payment
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <?php if ($paymentProof): ?>
                                    <div class="text-center">
                                        <img src="../<?php echo htmlspecialchars($paymentProof['proof_image']); ?>" 
                                             class="img-fluid payment-proof-img" alt="Payment Proof" style="max-height: 300px; object-fit: contain;">
                                        <p class="mt-2">
                                            <small>Uploaded: <?php echo date('F d, Y H:i', strtotime($paymentProof['uploaded_at'])); ?></small>
                                        </p>
                                        
                                        <?php if (!isset($order['payment_confirmed']) || !$order['payment_confirmed']): ?>
                                        <div class="mt-3 p-3 bg-light rounded">
                                            <h6 class="text-warning"><i class="bi bi-exclamation-triangle me-2"></i>Payment Confirmation Required</h6>
                                            <p class="mb-3 small text-muted">Review the payment proof above and confirm if the payment is valid.</p>
                                            <form method="post" class="d-inline">
                                                <button type="submit" name="confirm_payment" class="btn btn-success me-2" onclick="return confirm('Are you sure you want to confirm this payment?')">
                                                    <i class="bi bi-check-circle me-1"></i> Confirm Payment
                                                </button>
                                            </form>
                                        </div>
                                        <?php else: ?>
                                        <div class="mt-3 p-3 bg-success bg-opacity-10 rounded">
                                            <h6 class="text-success"><i class="bi bi-check-circle me-2"></i>Payment Confirmed</h6>
                                            <p class="mb-0 small text-muted">This payment has been verified and confirmed by admin.</p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center p-4">
                                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-2">No payment proof uploaded yet</p>
                                        <small class="text-muted">Customer will upload payment proof after placing the order</small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Order Items</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($orderItems) > 0): ?>
                                        <?php foreach ($orderItems as $item): ?>
                                            <tr>
                                                <td>
                                                    <?php echo htmlspecialchars($item['product_name']); ?>
                                                    <?php if (!empty($item['product_options'])): ?>
                                                        <br>
                                                        <small class="text-muted">
                                                            <?php echo htmlspecialchars($item['product_options']); ?>
                                                        </small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>₦<?php echo number_format($item['price'], 2); ?></td>
                                                <td><?php echo $item['quantity']; ?></td>
                                                <td class="text-end">₦<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center">No items found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Subtotal:</th>
                                        <td class="text-end">₦<?php echo number_format($order['subtotal'] ?? $order['total'] ?? 0, 2); ?></td>
                                    </tr>
                                    <?php if (isset($order['shipping_fee']) && $order['shipping_fee'] > 0): ?>
                                        <tr>
                                            <th colspan="3" class="text-end">Shipping:</th>
                                            <td class="text-end">₦<?php echo number_format($order['shipping_fee'], 2); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <th colspan="3" class="text-end">Total:</th>
                                        <td class="text-end fw-bold">₦<?php 
                                            $total = $order['subtotal'] ?? $order['total'] ?? 0;
                                            if (isset($order['shipping_fee'])) {
                                                $total += $order['shipping_fee'];
                                            }
                                            echo number_format($total, 2); 
                                        ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Order Progress</h5>
                            </div>
                            <div class="card-body">
                                <ul class="timeline">
                                    <?php if (count($progressUpdates) > 0): ?>
                                        <?php foreach ($progressUpdates as $update): ?>
                                            <li class="timeline-item">
                                                <span class="timeline-date">
                                                    <?php echo date('M d, H:i', strtotime($update['updated_at'])); ?>
                                                </span>
                                                <p class="timeline-content">
                                                    <?php echo htmlspecialchars($update['status_update']); ?>
                                                </p>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li class="timeline-item">
                                            <p class="timeline-content text-muted">No progress updates yet</p>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Update Order Status</h5>
                            </div>
                            <div class="card-body">
                                <form method="post">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="Pending" <?php echo $order['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="Processing" <?php echo $order['status'] === 'Processing' ? 'selected' : ''; ?>>Processing</option>
                                            <option value="Shipped" <?php echo $order['status'] === 'Shipped' ? 'selected' : ''; ?>>Shipped</option>
                                            <option value="Delivered" <?php echo $order['status'] === 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                                            <option value="Completed" <?php echo $order['status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                            <option value="Cancelled" <?php echo $order['status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="status_note" class="form-label">Status Note</label>
                                        <textarea class="form-control" id="status_note" name="status_note" rows="3" required></textarea>
                                        <div class="form-text">This note will be visible to the customer</div>
                                    </div>
                                    <div class="d-grid">
                                        <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/admin.js"></script>
</body>
</html>