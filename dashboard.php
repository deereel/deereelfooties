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

// Get user data from session with fallback
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
} elseif (isset($_SESSION['user_id'])) {
    $user = [
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['username'] ?? 'User',
        'email' => $_SESSION['user_email'] ?? ''
    ];
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
                      <div class="card-header">
                          <h3 class="mb-0">Dashboard</h3>
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
                          <div id="orders-container">
                              <div class="text-center py-4">
                                  <div class="spinner-border text-primary" role="status"></div>
                                  <p class="mt-2">Loading your orders...</p>
                              </div>
                          </div>
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
                          <div id="wishlist-container">
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
                      <div class="card-header">
                          <h3 class="mb-0">My Data</h3>
                      </div>
                      <div class="card-body">
                          <form id="personal-form">
                              <div class="row">
                                  <div class="col-md-6 mb-3">
                                      <label for="fullName" class="form-label">Full Name</label>
                                      <input type="text" class="form-control" id="fullName" value="<?php echo htmlspecialchars($user['name']); ?>">
                                  </div>
                                  <div class="col-md-6 mb-3">
                                      <label for="email" class="form-label">Email</label>
                                      <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                                  </div>
                                  <div class="col-md-6 mb-3">
                                      <label for="phone" class="form-label">Phone</label>
                                      <input type="tel" class="form-control" id="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                                  </div>
                                  <div class="col-md-6 mb-3">
                                      <label for="gender" class="form-label">Gender</label>
                                      <select class="form-select" id="gender">
                                          <option value="">Select Gender</option>
                                          <option value="male" <?php echo (isset($user['gender']) && $user['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
                                          <option value="female" <?php echo (isset($user['gender']) && $user['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
                                          <option value="other" <?php echo (isset($user['gender']) && $user['gender'] == 'other') ? 'selected' : ''; ?>>Other</option>
                                      </select>
                                  </div>
                              </div>
                              <button type="submit" class="btn btn-primary">Save Changes</button>
                          </form>
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
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/added-to-cart-modal.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/search-modal.php'); ?>  
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php'); ?>
  
  <!-- Dashboard Scripts -->
  <script src="/components/dashboard-tabs.js"></script>
  <script src="/js/dashboard.js"></script>
  <script src="/js/dashboard-orders.js"></script>
  <script src="/js/dashboard-wishlist.js"></script>
  <script src="/components/dashboard-address.js"></script>
  <script src="/js/dashboard-designs.js"></script>
  
</body>
</html>