<!-- dashboard.php -->
<?php
session_start();
if (!isset($_SESSION['user'])) {
  header('Location: index.php');
  exit();
}
$user = $_SESSION['user'];
?>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
<<<<<<< HEAD
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/auth/db.php'); ?>
<body class="bg-background" data-page="dashboard">
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>

  <!-- Dashboard Content -->
  <div class="container-fluid py-5">
    <div class="row">
      <!-- Left Sidebar -->
      <div class="col-12 col-md-3 mb-4">
        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <h5 class="mb-4 text-primary" id="dashboard-username">My Account</h5>
            <nav class="nav flex-column dashboard-nav">
              <a class="nav-link active" href="#dashboard" data-section="dashboard">Dashboard</a>
              <a class="nav-link" href="#orders" data-section="orders">My Orders</a>
              <a class="nav-link" href="#wishlist" data-section="wishlist">Wishlist</a>
              <a class="nav-link" href="#addresses" data-section="addresses">Address Book</a>
              <a class="nav-link" href="#personal" data-section="personal">My Data</a>
              <a class="nav-link" href="#designs" data-section="designs">My Designs</a>
              
              <div class="border-top my-4"></div>
              
              <a class="nav-link text-danger" href="#delete-account" data-section="delete-account">Delete Account</a>
              <a class="nav-link text-danger" href="#" id="dashboard-logout">Logout</a>
            </nav>
          </div>
        </div>
      </div>
      
      <!-- Right Content Area -->
      <div class="col-12 col-md-9">
        <div class="card border-0 shadow-sm">
          <div class="card-body p-4">
            <!-- Dashboard Section -->
            <section id="dashboard-section" class="dashboard-content active">
              <h3 class="mb-4">Dashboard</h3>
              <div class="row">
                <div class="col-md-6 mb-4">
                  <div class="card h-100 border-0 bg-light">
                    <div class="card-body">
                      <h5 class="card-title">Recent Orders</h5>
                      <p class="card-text text-muted">You have no recent orders.</p>
                      <a href="#orders" class="btn-primary" data-section="orders">View All Orders</a>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mb-4">
                  <div class="card h-100 border-0 bg-light">
                    <div class="card-body">
                      <h5 class="card-title">Saved Designs</h5>
                      <p class="card-text text-muted">You have no saved designs.</p>
                      <a href="#designs" class="btn-primary" data-section="designs">View Saved Designs</a>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mb-4">
                  <div class="card h-100 border-0 bg-light">
                    <div class="card-body">
                      <h5 class="card-title">Wishlist</h5>
                      <p class="card-text text-muted">You have no items in your wishlist.</p>
                      <a href="#wishlist" class="btn-primary" data-section="wishlist">View Wishlist</a>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mb-4">
                  <div class="card h-100 border-0 bg-light">
                    <div class="card-body">
                      <h5 class="card-title">Account Details</h5>
                      <p class="card-text text-muted">Update your personal information.</p>
                      <a href="#personal" class="btn-primary" data-section="personal">Edit Details</a>
                    </div>
                  </div>
                </div>
              </div>
            </section>
            
            <!-- Orders Section -->
            <section id="orders-section" class="dashboard-content">
              <h3 class="mb-4">My Orders</h3>
              <div id="orders-container">
                <!-- Orders will be loaded here -->
              </div>
            </section>
            
            <!-- Wishlist Section -->
            <section id="wishlist-section" class="dashboard-content">
              <h3 class="mb-4">My Wishlist</h3>
              <p class="text-muted mb-4">Items you've saved for later.</p>
              
              <div id="wishlist-container" class="row">
                <?php
                // Get user ID from session
                $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
                $userId = $user ? $user['user_id'] : 0;
                
                if ($userId) {
                  try {
                    // Check if table exists
                    $tableExists = false;
                    $tables = $pdo->query("SHOW TABLES LIKE 'wishlist_items'")->fetchAll();
                    if (count($tables) > 0) {
                      $tableExists = true;
                    }
                    
                    if ($tableExists) {
                      $stmt = $pdo->prepare("SELECT * FROM wishlist_items WHERE user_id = ? ORDER BY created_at DESC");
                      $stmt->execute([$userId]);
                      $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                      
                      if (count($items) > 0) {
                        foreach ($items as $item):
                          $createdAt = date('F j, Y', strtotime($item['created_at']));
                ?>
                  <div class="col-md-4 mb-4">
                    <div class="card h-100 position-relative">
                      <!-- Add delete button -->
                      <button class="delete-wishlist-btn position-absolute top-0 end-0 bg-white rounded-circle p-1 m-2 border shadow-sm" 
                              data-wishlist-id="<?= $item['wishlist_id'] ?>">
                        <i class="fas fa-times text-danger"></i>
                      </button>
                      
                      <img src="<?= $item['image'] ?>" class="card-img-top" alt="<?= $item['product_name'] ?>">
                      <div class="card-body">
                        <h5 class="card-title"><?= $item['product_name'] ?></h5>
                        <p class="text-muted small">Added on <?= $createdAt ?></p>
                        <p class="card-text text-accent">₦<?= number_format($item['price']) ?></p>
                        <div class="d-flex justify-content-between">
                          <button class="btn-primary btn-sm add-wishlist-to-cart" 
                                  data-product-id="<?= $item['product_id'] ?>"
                                  data-product-name="<?= $item['product_name'] ?>"
                                  data-product-price="<?= $item['price'] ?>"
                                  data-product-image="<?= $item['image'] ?>">
                            Add to Cart
                          </button>
                          <a href="/product.php?slug=<?= $item['product_id'] ?>" class="btn-outline-secondary btn-sm">
                            View Details
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php
                        endforeach;
                      } else {
                ?>
                  <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Your wishlist is empty.
                    <a href="/products.php" class="alert-link">Browse products</a>
                  </div>
                <?php
                      }
                    } else {
                ?>
                  <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Your wishlist is empty.
                    <a href="/products.php" class="alert-link">Browse products</a>
                  </div>
                <?php
                    }
                  } catch (PDOException $e) {
                    echo '<div class="alert alert-danger">Error loading wishlist: ' . $e->getMessage() . '</div>';
                  }
                } else {
                ?>
                  <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Please log in to view your wishlist.
                  </div>
                <?php
                }
                ?>
              </div>
            </section>
            
            <!-- Address Book Section -->
            <section id="addresses-section" class="dashboard-content">
              <!-- Content will be loaded dynamically by addresses.js -->
            </section>
            
            <!-- Personal Data Section -->
            <section id="personal-section" class="dashboard-content">
              <h3 class="mb-4">My Data</h3>
              <p class="text-muted mb-4">Manage your personal information.</p>
              
              <form id="personal-data-form">
                <div class="row mb-3">
                  <div class="col-md-6 mb-3">
                    <label for="fullName" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="fullName" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" readonly>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phone">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-control" id="gender">
                      <option value="">Select Gender</option>
                      <option value="male">Male</option>
                      <option value="female">Female</option>
                      <option value="other">Other</option>
                      <option value="prefer_not_to_say">Prefer not to say</option>
                    </select>
                  </div>
                </div>
                
                <h5 class="mt-4 mb-3">Change Password</h5>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="currentPassword" class="form-label">Current Password</label>
                    <input type="password" class="form-control" id="currentPassword">
                  </div>
                  <div class="col-md-6"></div>
                  <div class="col-md-6 mb-3">
                    <label for="newPassword" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="newPassword">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="confirmPassword" class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control" id="confirmPassword">
                  </div>
                </div>
                
                <button type="submit" class="btn-primary mt-3">Save Changes</button>
              </form>
            </section>
            
            <!-- Designs Section -->            
            <section id="designs-section" class="dashboard-content">
              <h3 class="mb-4">My Designs</h3>
              <p class="text-muted mb-4">Your saved custom shoe designs.</p>
              
              <div id="saved-designs-container" class="row">
                <?php
                // Get user ID from session
                $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
                $userId = $user ? $user['user_id'] : 0;
                
                if ($userId) {
                  try {
                    // Check if table exists
                    $tableExists = false;
                    $tables = $pdo->query("SHOW TABLES LIKE 'saved_designs'")->fetchAll();
                    if (count($tables) > 0) {
                      $tableExists = true;
                    }
                    
                    if ($tableExists) {
                      $stmt = $pdo->prepare("SELECT * FROM saved_designs WHERE user_id = ? ORDER BY created_at DESC");
                      $stmt->execute([$userId]);
                      $designs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                      
                      if (count($designs) > 0) {
                        foreach ($designs as $design) {
                          $designData = json_decode($design['design_data'], true);
                          $designName = $designData['name'] ?? 'Custom Design';
                          $designColor = $designData['color'] ?? 'black';
                          $designMaterial = $designData['material'] ?? 'calf';
                          $designSize = $designData['size'] ?? '';
                          $designPrice = $designData['price'] ?? 0;
                          $designImage = $designData['image'] ?? '/images/penny loafer 600.webp';
                          $createdAt = date('F j, Y', strtotime($design['created_at']));
                ?>
                  <div class="col-md-4 mb-4">
                    <div class="card h-100 position-relative">
                      <!-- Add delete button -->
                      <button class="delete-design-btn position-absolute top-0 end-0 bg-white rounded-circle p-1 m-2 border shadow-sm" 
                              data-design-id="<?= $design['design_id'] ?>">
                        <i class="fas fa-times text-danger"></i>
                      </button>
                      
                      <img src="<?= $designImage ?>" class="card-img-top" alt="<?= $designName ?>">
                      <div class="card-body">
                        <h5 class="card-title"><?= $designName ?></h5>
                        <p class="text-muted small">Created on <?= $createdAt ?></p>
                        <p class="mb-2">Color: <?= ucfirst($designColor) ?></p>
                        <p class="mb-2">Material: <?= ucfirst($designMaterial) ?></p>
                        <p class="mb-2">Size: <?= $designSize ?></p>
                        <p class="card-text text-accent">₦<?= number_format($designPrice) ?></p>
                        <div class="d-flex justify-content-between">
                          <button class="btn-primary btn-sm add-design-to-cart" 
                                  data-design='<?= htmlspecialchars(json_encode($designData), ENT_QUOTES, 'UTF-8') ?>'>
                            Add to Cart
                          </button>
                          <a href="/customize.php?design_id=<?= $design['design_id'] ?>" class="btn-outline-secondary btn-sm">
                            Edit
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php
                        }
                      } else {
                ?>
                  <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> You have no saved designs.
                    <a href="/customize.php" class="alert-link">Create a custom design</a>
                  </div>
                <?php
                      }
                    } else {
                ?>
                  <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> You have no saved designs.
                    <a href="/customize.php" class="alert-link">Create a custom design</a>
                  </div>
                <?php
                    }
                  } catch (PDOException $e) {
                    echo '<div class="alert alert-danger">Error loading designs: ' . $e->getMessage() . '</div>';
                  }
                } else {
                ?>
                  <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Please log in to view your saved designs.
                  </div>
                <?php
                }
                ?>
              </div>
            </section>

            
            <!-- Delete Account Section -->
            <section id="delete-account-section" class="dashboard-content">
              <h3 class="mb-4">Delete Account</h3>
              <div class="alert alert-warning">
                <h5><i class="fas fa-exclamation-triangle me-2"></i> Warning</h5>
                <p>Deleting your account is permanent and cannot be undone. All your data, including order history, saved designs, and personal information will be permanently removed.</p>
              </div>
              
              <form id="delete-account-form">
                <div class="mb-3">
                  <label for="deleteConfirmation" class="form-label">To confirm, type "DELETE" below:</label>
                  <input type="text" class="form-control" id="deleteConfirmation" required>
                </div>
                <div class="mb-3">
                  <label for="deletePassword" class="form-label">Enter your password:</label>
                  <input type="password" class="form-control" id="deletePassword" required>
                </div>
                <button type="submit" class="btn btn-danger">Permanently Delete Account</button>
              </form>
            </section>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Include Address Modal -->
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/address_modal.php'); ?>
  
  <!-- Include Payment Proof Modal -->
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/payment_proof_modal.php'); ?>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/search-modal.php'); ?>

  <!-- Scroll to Top Button -->
  <a href="#" class="btn btn-dark position-fixed bottom-0 end-0 m-4 shadow rounded-circle" style="z-index: 999; width: 45px; height: 45px; display: none;" id="scrollToTop">
    <i class="fas fa-chevron-up"></i>
  </a>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php'); ?>
  <script src="/js/dashboard.js"></script>
  <script src="/js/orders.js"></script>
  <script src="/js/addresses.js"></script>
  <script src="/js/user_profile.js"></script>
  <script src="/js/dashboard-orders.js"></script>
  <script src="/js/dashboard-wishlist.js"></script>

=======

<body class="bg-light">
  <div class="container py-5">
    <h2 class="mb-4">Welcome, <?= htmlspecialchars($user['name']) ?>!</h2>
    <p>Your email: <?= htmlspecialchars($user['email']) ?></p>

    <a href="/auth/logout.php" class="btn btn-danger mt-3">Logout</a>
  </div>
>>>>>>> parent of f36b17c (checkout page)
</body>
</html>
