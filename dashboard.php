<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
// Start session and check authentication BEFORE any output
// session_start();

// Check if user is logged in BEFORE including any files that output content
if (!isset($_SESSION['user']) && !isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Now it's safe to include files that output content
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';
include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php');

// Get user data from database
$userId = null;
if (isset($_SESSION['user']['id'])) {
    $userId = $_SESSION['user']['id'];
} elseif (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
}

if ($userId) {
    try {
        $userStmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
        $userStmt->execute([$userId]);
        $user = $userStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            // Fallback to session data if database query fails
            $user = $_SESSION['user'] ?? [
                'id' => $_SESSION['user_id'] ?? null,
                'name' => $_SESSION['username'] ?? 'User',
                'email' => $_SESSION['user_email'] ?? ''
            ];
        } else {
            // Ensure consistent key naming
            $user['id'] = $user['user_id'];
        }
    } catch (PDOException $e) {
        // Fallback to session data on database error
        $user = $_SESSION['user'] ?? [
            'id' => $_SESSION['user_id'] ?? null,
            'name' => $_SESSION['username'] ?? 'User',
            'email' => $_SESSION['user_email'] ?? ''
        ];
    }
} else {
    header('Location: index.php');
    exit;
}
$page = 'dashboard';
?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard | DeeReel Footies</title>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>
</head>

<body data-page="dashboard">

  <div class="container py-5">
      <div class="row">
          <!-- User Info -->
          <div class="col-12 mb-4">
              <div class="card">
                  <div class="card-header">
                      <h4 class="mb-0">My Account</h4>
                  </div>
                  <div class="card-body">
                      <div class="d-flex align-items-center">
                          <div class="bg-accent rounded-circle text-white d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                              <i class="fas fa-user fa-2x"></i>
                          </div>
                          <div>
                              <h4 class="mb-0"><?php echo htmlspecialchars($user['name']); ?></h4>
                              <p class="text-muted mb-0"><?php echo htmlspecialchars($user['email']); ?></p>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          
          <!-- Dashboard Content -->
          <div class="col-md-3">
              <!-- Sidebar Navigation -->
              <div class="sidebar-fixed">
                  <div class="list-group mb-4 shadow" id="dashboard-tabs">
                      <a href="#" class="list-group-item list-group-item-action active" data-tab="dashboard">
                          <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                      </a>
                      <a href="#" class="list-group-item list-group-item-action" data-tab="orders">
                          <i class="fas fa-box me-2"></i> My Orders
                      </a>
                      <a href="#" class="list-group-item list-group-item-action" data-tab="wishlist">
                          <i class="fas fa-heart me-2"></i> Wishlist
                      </a>
                      <a href="#" class="list-group-item list-group-item-action" data-tab="address">
                          <i class="fas fa-address-book me-2"></i> Address Book
                      </a>
                      <a href="#" class="list-group-item list-group-item-action" data-tab="personal">
                          <i class="fas fa-user-edit me-2"></i> My Data
                      </a>
                      <a href="#" class="list-group-item list-group-item-action" data-tab="designs">
                          <i class="fas fa-palette me-2"></i> My Designs
                      </a>
                      
                      <div class="dropdown-divider my-2"></div>
                      
                      <a href="#" class="list-group-item list-group-item-action text-danger" data-tab="delete">
                          <i class="fas fa-user-times me-2"></i> Delete Account
                      </a>
                      <a href="#" class="list-group-item list-group-item-action text-danger" id="logout-btn">
                          <i class="fas fa-sign-out-alt me-2"></i> Logout
                      </a>
                  </div>
              </div>
          </div>
          
          <div class="col-md-9">
              <!-- Dashboard Tab -->
              <div id="dashboard-tab" class="tab-content active">
                  <div class="card">
                      <div class="card-header d-flex justify-content-between align-items-center">
                          <h3 class="mb-0">Dashboard</h3>
                          <a href="/customer/dashboard.php" class="btn btn-sm btn-outline-secondary">
                              <i class="fas fa-exchange-alt me-1"></i> Simple View
                          </a>
                      </div>
                      <div class="card-body">
                          <p>Welcome back, <strong><?php echo htmlspecialchars($user['name']); ?></strong>!</p>
                          
                          <div class="row mt-4">
                              <div class="col-md-6 mb-3">
                                  <div class="card bg-light">
                                      <div class="card-body text-center">
                                          <i class="fas fa-box-open fa-2x mb-3 text-primary"></i>
                                          <h5>Orders</h5>
                                          <p class="mb-0">View your order history</p>
                                          <button class="btn btn-outline-primary mt-3 tab-link" data-tab="orders">View Orders</button>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-md-6 mb-3">
                                  <div class="card bg-light">
                                      <div class="card-body text-center">
                                          <i class="fas fa-heart fa-2x mb-3 text-accent"></i>
                                          <h5>Wishlist</h5>
                                          <p class="mb-0">View your saved items</p>
                                          <button class="btn btn-outline-primary mt-3 tab-link" data-tab="wishlist">View Wishlist</button>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-md-6 mb-3">
                                  <div class="card bg-light">
                                      <div class="card-body text-center">
                                          <i class="fas fa-palette fa-2x mb-3 text-success"></i>
                                          <h5>My Designs</h5>
                                          <p class="mb-0">View your custom designs</p>
                                          <button class="btn btn-outline-primary mt-3 tab-link" data-tab="designs">View Designs</button>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-md-6 mb-3">
                                  <div class="card bg-light">
                                      <div class="card-body text-center">
                                          <i class="fas fa-user-edit fa-2x mb-3 text-info"></i>
                                          <h5>Account</h5>
                                          <p class="mb-0">Update your details</p>
                                          <button class="btn btn-outline-primary mt-3 tab-link" data-tab="personal">Edit Details</button>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              
              <!-- Orders Tab -->
              <div id="orders-tab" class="tab-content">
                  <div class="card">
                      <div class="card-header">
                          <h3 class="mb-0">My Orders</h3>
                      </div>
                      <div class="card-body">
                          <?php
                          try {
                              $ordersStmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
                              $ordersStmt->execute([$userId]);
                              $orders = $ordersStmt->fetchAll(PDO::FETCH_ASSOC);
                              
                              if (!empty($orders)):
                          ?>
                              <?php foreach ($orders as $order): ?>
                                  <?php
                                  // Get item count for this order
                                  $itemCountStmt = $pdo->prepare("SELECT COUNT(*) as item_count FROM order_items WHERE order_id = ?");
                                  $itemCountStmt->execute([$order['order_id']]);
                                  $itemCount = $itemCountStmt->fetchColumn();
                                  ?>
                                  <div class="card mb-3">
                                      <div class="card-body">
                                          <div class="row align-items-center">
                                              <div class="col-md-3">
                                                  <strong>Order #<?= $order['order_id'] ?></strong>
                                                  <br><small class="text-muted"><?= date('M d, Y', strtotime($order['created_at'])) ?></small>
                                              </div>
                                              <div class="col-md-2">
                                                  <span class="badge bg-<?= $order['status'] === 'Completed' ? 'success' : ($order['status'] === 'Processing' ? 'primary' : 'warning') ?>">
                                                      <?= $order['status'] ?>
                                                  </span>
                                              </div>
                                              <div class="col-md-2">
                                                  <small class="text-muted"><?= $itemCount ?> item<?= $itemCount != 1 ? 's' : '' ?></small>
                                              </div>
                                              <div class="col-md-3">
                                                  <strong>₦<?= number_format($order['total'] ?? $order['subtotal'] ?? 0, 2) ?></strong>
                                              </div>
                                              <div class="col-md-2 text-end">
                                                  <button class="btn btn-outline-primary btn-sm" onclick="showOrderDetails(<?= $order['order_id'] ?>)">View Details</button>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              <?php endforeach; ?>
                          <?php else: ?>
                              <div class="text-center py-5">
                                  <i class="fas fa-box text-muted" style="font-size: 4rem;"></i>
                                  <h4 class="mt-3">No Orders Found</h4>
                                  <p class="text-muted">You haven't placed any orders yet.</p>
                                  <a href="/products.php" class="btn btn-primary">Start Shopping</a>
                              </div>
                          <?php endif; ?>
                          <?php } catch (PDOException $e) { ?>
                              <div class="alert alert-danger">Error loading orders: <?= $e->getMessage() ?></div>
                          <?php } ?>
                      </div>
                  </div>
              </div>
              
              <!-- Wishlist Tab -->
              <div id="wishlist-tab" class="tab-content">
                  <div class="card">
                      <div class="card-header">
                          <h3 class="mb-0">My Wishlist</h3>
                      </div>
                      <div class="card-body">
                          <div id="wishlist-items">
                              <div class="text-center py-4">
                                  <div class="spinner-border text-primary" role="status"></div>
                                  <p class="mt-2">Loading your wishlist...</p>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              
              <!-- Address Book Tab -->
              <div id="address-tab" class="tab-content">
                  <div class="card">
                      <div class="card-header d-flex justify-content-between align-items-center">
                          <h3 class="mb-0">Address Book</h3>
                          <button class="btn btn-primary" id="add-address-btn">
                              <i class="fas fa-plus me-2"></i>Add New Address
                          </button>
                      </div>
                      <div class="card-body">
                          <div id="addresses-container">
                              <div class="text-center py-4">
                                  <div class="spinner-border text-primary" role="status"></div>
                                  <p class="mt-2">Loading your addresses...</p>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              
              <!-- Personal Data Tab -->
              <div id="personal-tab" class="tab-content">
                  <div class="card">
                      <div class="card-header d-flex justify-content-between align-items-center">
                          <h3 class="mb-0">My Profile</h3>
                          <a href="/account-settings.php" class="btn btn-primary">
                              <i class="fas fa-edit me-2"></i>Edit Profile
                          </a>
                      </div>
                      <div class="card-body">
                          <div class="row">
                              <div class="col-md-8">
                                  <div class="row mb-4">
                                      <div class="col-md-6 mb-3">
                                          <div class="border-bottom pb-2">
                                              <label class="form-label text-muted small">Full Name</label>
                                              <p class="mb-0 fw-medium"><?php echo htmlspecialchars($user['name']); ?></p>
                                          </div>
                                      </div>
                                      <div class="col-md-6 mb-3">
                                          <div class="border-bottom pb-2">
                                              <label class="form-label text-muted small">Email Address</label>
                                              <p class="mb-0 fw-medium"><?php echo htmlspecialchars($user['email']); ?></p>
                                          </div>
                                      </div>
                                      <div class="col-md-6 mb-3">
                                          <div class="border-bottom pb-2">
                                              <label class="form-label text-muted small">Phone Number</label>
                                              <p class="mb-0 fw-medium"><?php echo !empty($user['phone']) ? htmlspecialchars($user['phone']) : '<span class="text-muted">Not provided</span>'; ?></p>
                                          </div>
                                      </div>
                                      <div class="col-md-6 mb-3">
                                          <div class="border-bottom pb-2">
                                              <label class="form-label text-muted small">Account ID</label>
                                              <p class="mb-0 fw-medium">#<?php echo htmlspecialchars($user['id'] ?? $user['user_id'] ?? 'N/A'); ?></p>
                                          </div>
                                      </div>
                                  </div>
                                  
                                  <div class="row">
                                      <div class="col-12">
                                          <h5 class="mb-3">Account Information</h5>
                                          <div class="bg-light p-3 rounded">
                                              <div class="row">
                                                  <div class="col-md-6">
                                                      <small class="text-muted">Member Since</small>
                                                      <p class="mb-0"><?php 
                                                          if (isset($user['created_at'])) {
                                                              echo date('F d, Y', strtotime($user['created_at']));
                                                          } else {
                                                              echo 'N/A';
                                                          }
                                                      ?></p>
                                                  </div>
                                                  <div class="col-md-6">
                                                      <small class="text-muted">Account Status</small>
                                                      <p class="mb-0"><span class="badge bg-success">Active</span></p>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              
                              <div class="col-md-4">
                                  <div class="text-center">
                                      <div class="bg-primary rounded-circle text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                                          <i class="fas fa-user fa-2x"></i>
                                      </div>
                                      <h5><?php echo htmlspecialchars($user['name']); ?></h5>
                                      <p class="text-muted"><?php echo htmlspecialchars($user['email']); ?></p>
                                      
                                      <div class="mt-4">
                                          <a href="/account-settings.php" class="btn btn-outline-primary btn-sm w-100 mb-2">
                                              <i class="fas fa-edit me-2"></i>Edit Profile
                                          </a>
                                          <a href="/dashboard.php#address" class="btn btn-outline-secondary btn-sm w-100 tab-link" data-tab="address">
                                              <i class="fas fa-address-book me-2"></i>Manage Addresses
                                          </a>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              
              <!-- Designs Tab -->
              <div id="designs-tab" class="tab-content">
                  <div class="card">
                      <div class="card-header">
                          <h3 class="mb-0">My Designs</h3>
                      </div>
                      <div class="card-body">
                          <div id="designs-container">
                              <div class="text-center py-4">
                                  <div class="spinner-border text-primary" role="status"></div>
                                  <p class="mt-2">Loading your designs...</p>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              
              <!-- Delete Account Tab -->
              <div id="delete-tab" class="tab-content">
                  <div class="card">
                      <div class="card-header bg-danger">
                          <h3 class="mb-0 text-white">Delete Account</h3>
                      </div>
                      <div class="card-body">
                          <div class="alert alert-danger">
                              <h5><i class="fas fa-exclamation-triangle me-2"></i> Warning</h5>
                              <p>Deleting your account is permanent and cannot be undone. All your data, including order history, saved designs, and personal information will be permanently removed.</p>
                          </div>
                          <form id="delete-form">
                              <div class="mb-3">
                                  <label for="deleteConfirm" class="form-label">Type "DELETE" to confirm</label>
                                  <input type="text" class="form-control" id="deleteConfirm" required>
                              </div>
                              <div class="mb-3">
                                  <label for="password" class="form-label">Enter your password</label>
                                  <input type="password" class="form-control" id="password" required>
                              </div>
                              <button type="submit" class="btn btn-danger">Delete My Account</button>
                          </form>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'); ?>

  <!-- Scroll to Top Button -->
  <a href="#" class="btn btn-dark position-fixed bottom-0 end-0 m-4 shadow rounded-circle" style="z-index: 999; width: 45px; height: 45px; display: none;" id="scrollToTop">
    <i class="fas fa-chevron-up"></i>
  </a>
   
  <!-- Include scripts in the correct order -->
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/account-modal.php'); ?>  
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/address-modal.php'); ?>  
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/order-details-modal.php'); ?>  
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/search-modal.php'); ?>  
  
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/order-details-modal-customer.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php'); ?>
  
  <!-- Dashboard Scripts -->
  <script src="/components/dashboard-tabs.js"></script>
  <script src="/js/dashboard.js"></script>
  <script src="/js/dashboard-orders.js"></script>
  <script src="/js/dashboard-wishlist.js"></script>
  <script src="/components/dashboard-address.js"></script>
  <script src="/js/dashboard-designs.js"></script>
  
  <script>
  function showOrderDetails(orderId) {
      const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
      const content = document.getElementById('orderDetailsContent');
      
      content.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading order details...</p></div>';
      modal.show();
      
      fetch(`/api/get-order-details.php?order_id=${orderId}`)
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  const order = data.order;
                  const items = data.items;
                  const history = data.history;
                  
                  let itemsHtml = '';
                  if (items && items.length > 0) {
                      items.forEach(item => {
                          const imageUrl = item.image || item.product_image || '/images/placeholder.jpg';
                          const color = item.color || item.selected_color || 'N/A';
                          const size = item.size || item.selected_size || 'N/A';
                          
                          itemsHtml += `
                              <div class="row align-items-center mb-3 pb-3 border-bottom text-dark">
                                  <div class="col-md-2">
                                      <img src="${imageUrl}" alt="${item.product_name || 'Product'}" class="img-fluid rounded" style="max-height: 80px; object-fit: cover;">
                                  </div>
                                  <div class="col-md-6">
                                      <h6 class="mb-1 text-dark">${item.product_name || 'Product'}</h6>
                                      <div class="text-muted small">
                                          <div>Color: ${color}</div>
                                          <div>Size: ${size}</div>
                                          <div>Qty: ${item.quantity || 1}</div>
                                      </div>
                                  </div>
                                  <div class="col-md-2 text-center">
                                      <div class="fw-bold text-dark">₦${parseFloat(item.price || 0).toLocaleString()}</div>
                                      <small class="text-muted">each</small>
                                  </div>
                                  <div class="col-md-2 text-end">
                                      <div class="fw-bold text-dark">₦${(parseFloat(item.price || 0) * parseInt(item.quantity || 1)).toLocaleString()}</div>
                                      <small class="text-muted">total</small>
                                  </div>
                              </div>
                          `;
                      });
                  } else {
                      itemsHtml = '<p class="text-muted">No items found for this order.</p>';
                  }
                  
                  let historyHtml = '';
                  if (history && history.length > 0) {
                      history.forEach(status => {
                          historyHtml += `
                              <div class="d-flex mb-3">
                                  <div class="me-3"><i class="bi bi-circle-fill text-primary"></i></div>
                                  <div>
                                      <strong class="text-dark">${status.status}</strong><br>
                                      <small class="text-muted">${new Date(status.created_at).toLocaleDateString('en-US', {year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'})}</small>
                                  </div>
                              </div>
                          `;
                      });
                  } else {
                      // If no history, show current order status
                      historyHtml = `
                          <div class="d-flex mb-3">
                              <div class="me-3"><i class="bi bi-circle-fill text-primary"></i></div>
                              <div>
                                  <strong class="text-dark">${order.status}</strong><br>
                                  <small class="text-muted">${new Date(order.created_at).toLocaleDateString('en-US', {year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'})}</small>
                              </div>
                          </div>
                      `;
                  }
                  
                  let paymentStatus = 'Pending';
                  let paymentBadge = 'secondary';
                  if (order.payment_confirmed) {
                      paymentStatus = 'Confirmed';
                      paymentBadge = 'success';
                  }
                  
                  content.innerHTML = `
                      <div class="row mb-4 text-dark">
                          <div class="col-md-6">
                              <div class="card">
                                  <div class="card-header"><h5 class="text-dark">Order Information</h5></div>
                                  <div class="card-body">
                                      <table class="table table-borderless text-dark">
                                          <tr><th class="text-dark">Order ID:</th><td class="text-dark">#${order.order_id}</td></tr>
                                          <tr><th class="text-dark">Date:</th><td class="text-dark">${new Date(order.created_at).toLocaleDateString('en-US', {year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'})}</td></tr>
                                          <tr><th class="text-dark">Status:</th><td class="text-dark"><span class="badge bg-${order.status === 'Completed' ? 'success' : (order.status === 'Processing' ? 'primary' : (order.status === 'Cancelled' ? 'danger' : 'warning'))}">${order.status}</span></td></tr>
                                          <tr><th class="text-dark">Payment:</th><td class="text-dark"><span class="badge bg-${paymentBadge}">${paymentStatus}</span></td></tr>
                                          <tr><th class="text-dark">Total:</th><td class="text-dark">₦${parseFloat(order.total || order.subtotal || 0).toLocaleString()}</td></tr>
                                      </table>
                                  </div>
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="card">
                                  <div class="card-header"><h5 class="text-dark">Customer Information</h5></div>
                                  <div class="card-body">
                                      <table class="table table-borderless text-dark">
                                          <tr><th class="text-dark">Name:</th><td class="text-dark">${order.customer_name || order.name || 'N/A'}</td></tr>
                                          <tr><th class="text-dark">Email:</th><td class="text-dark">${order.customer_email || order.email || 'N/A'}</td></tr>
                                          <tr><th class="text-dark">Phone:</th><td class="text-dark">${data.user && data.user.phone ? data.user.phone : 'N/A'}</td></tr>
                                      </table>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="row mb-4">
                          <div class="col-md-12">
                              <div class="card">
                                  <div class="card-header"><h5 class="text-dark">Shipping Information</h5></div>
                                  <div class="card-body text-dark">
                                      <div class="row">
                                          <div class="col-md-6">
                                              <strong class="text-dark">Shipping Address:</strong><br>
                                              <span class="text-dark">
                                              ${data.shipping_address && data.shipping_address.name ? `${data.shipping_address.name}<br>` : ''}
                                              ${data.shipping_address && data.shipping_address.phone ? `${data.shipping_address.phone}<br>` : ''}
                                              ${data.shipping_address && data.shipping_address.street ? 
                                                  `${data.shipping_address.street}<br>
                                                   ${data.shipping_address.city || ''}, ${data.shipping_address.state || ''} ${data.shipping_address.zip || ''}<br>
                                                   ${data.shipping_address.country || ''}` : 
                                                  'No address provided'}
                                              </span>
                                          </div>
                                          <div class="col-md-6">
                                              <strong class="text-dark">Shipping Method:</strong><br>
                                              <span class="text-dark">${data.shipping_method || order.shipping_method || 'Standard Shipping'}</span><br><br>
                                              <strong class="text-dark">Tracking Number:</strong><br>
                                              <span class="text-dark">${data.tracking_number || order.tracking_number || 'Not available'}</span>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-8">
                              <div class="card mb-4">
                                  <div class="card-header"><h5 class="text-dark">Order Items</h5></div>
                                  <div class="card-body text-dark">${itemsHtml}</div>
                              </div>
                              <div class="card">
                                  <div class="card-header"><h5 class="text-dark">Order Status History</h5></div>
                                  <div class="card-body text-dark">${historyHtml}</div>
                              </div>
                          </div>
                          <div class="col-md-4">
                              <div class="card">
                                  <div class="card-header"><h5 class="text-dark">Order Summary</h5></div>
                                  <div class="card-body text-dark">
                                      <div class="d-flex justify-content-between mb-2">
                                          <span class="text-dark">Items:</span>
                                          <span class="text-dark">${items ? items.length : 0} item${items && items.length !== 1 ? 's' : ''}</span>
                                      </div>
                                      <div class="d-flex justify-content-between mb-2">
                                          <span class="text-dark">Subtotal:</span>
                                          <span class="text-dark">₦${parseFloat(order.subtotal || order.total || 0).toLocaleString()}</span>
                                      </div>
                                      <div class="d-flex justify-content-between mb-2">
                                          <span class="text-dark">Shipping:</span>
                                          <span class="text-dark">₦${parseFloat(order.shipping_cost || 0).toLocaleString()}</span>
                                      </div>
                                      <hr>
                                      <div class="d-flex justify-content-between">
                                          <strong class="text-dark">Total:</strong>
                                          <strong class="text-dark">₦${parseFloat(order.total || order.subtotal || 0).toLocaleString()}</strong>
                                      </div>
                                      <div class="mt-3">
                                        ${(order.status === 'Pending' || order.status === 'pending') && (!order.payment_confirmed || order.payment_confirmed == 0) ? `
                                          <button class="btn btn-sm btn-warning w-100 mb-2" onclick="showPaymentUpload('${order.order_id}')">Upload Payment Proof</button>
                                        ` : ''}
                                        <button class="btn btn-sm btn-success w-100" onclick="reorderItems('${order.order_id}')">Reorder Items</button>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  `;
              } else {
                  content.innerHTML = `<div class="alert alert-danger">${data.error || 'Failed to load order details'}</div>`;
              }
          })
          .catch(error => {
              content.innerHTML = '<div class="alert alert-danger">Error loading order details</div>';
          });
  }
  
  // Reorder function
  function reorderItems(orderId) {
    if (confirm('Add all items from this order to your cart?')) {
      fetch(`/api/reorder.php?order_id=${orderId}`, {
        method: 'POST'
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Items added to cart successfully!');
          window.location.href = '/checkout.php';
        } else {
          alert('Error: ' + (data.message || 'Failed to add items to cart'));
        }
      })
      .catch(error => {
        alert('Error adding items to cart. Please try again.');
      });
    }
  }
  
  // Payment proof upload function
  function showPaymentUpload(orderId) {
    const modal = document.getElementById('orderDetailsModal');
    const content = document.getElementById('orderDetailsContent');
    
    content.innerHTML = `
      <div class="text-dark">
        <h5 class="mb-3">Upload Payment Proof</h5>
        <form id="payment-proof-form" enctype="multipart/form-data">
          <input type="hidden" name="order_id" value="${orderId}">
          <div class="mb-3">
            <label for="proof_image" class="form-label">Payment Proof</label>
            <input type="file" class="form-control" name="proof_image" accept="image/*,application/pdf" required>
            <div class="form-text">Accepted formats: JPG, PNG, PDF</div>
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Upload Proof</button>
            <button type="button" class="btn btn-secondary" onclick="showOrderDetails('${orderId}')">Back</button>
          </div>
        </form>
      </div>
    `;
    
    // Handle form submission
    document.getElementById('payment-proof-form').addEventListener('submit', async function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      
      try {
        const response = await fetch('/api/payment_proof.php', {
          method: 'POST',
          body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
          alert('Payment proof uploaded successfully!');
          showOrderDetails(orderId);
        } else {
          alert('Error: ' + data.message);
        }
      } catch (error) {
        alert('Upload failed. Please try again.');
      }
    });
  }
  </script>
  
</body>
</html>