<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$user = $_SESSION['user'];
$page = 'dashboard';
?>

<!DOCTYPE html>
<html lang="en">
<?php include('components/header.php'); ?>
<body>
    <?php include('components/navbar.php'); ?>

    <div class="container py-5">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-secondary rounded-circle text-white d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <h5 class="mb-0" id="dashboard-username"><?php echo htmlspecialchars($user['name']); ?></h5>
                                <small class="text-muted"><?php echo htmlspecialchars($user['email']); ?></small>
                            </div>
                        </div>
                        
                        <div class="dashboard-nav">
                            <div class="list-group">
                                <a href="#dashboard" class="list-group-item list-group-item-action" data-section="dashboard">
                                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                                </a>
                                <a href="#orders" class="list-group-item list-group-item-action" data-section="orders">
                                    <i class="fas fa-box me-2"></i> My Orders
                                </a>
                                <a href="#wishlist" class="list-group-item list-group-item-action" data-section="wishlist">
                                    <i class="fas fa-heart me-2"></i> Wishlist
                                </a>
                                <a href="#addresses" class="list-group-item list-group-item-action" data-section="addresses">
                                    <i class="fas fa-address-book me-2"></i> Address Book
                                </a>
                                <a href="#personal" class="list-group-item list-group-item-action" data-section="personal">
                                    <i class="fas fa-user-edit me-2"></i> My Data
                                </a>
                                <a href="#designs" class="list-group-item list-group-item-action" data-section="designs">
                                    <i class="fas fa-palette me-2"></i> My Designs
                                </a>
                                
                                <div class="dropdown-divider my-3"></div>
                                
                                <a href="#delete-account" class="list-group-item list-group-item-action text-danger" data-section="delete-account">
                                    <i class="fas fa-user-times me-2"></i> Delete Account
                                </a>
                                <a href="#" id="dashboard-logout" class="list-group-item list-group-item-action text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9">
                <!-- Dashboard Section -->
                <div id="dashboard-section" class="dashboard-content active">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h3 class="card-title">Dashboard</h3>
                            <p>Hello <strong><?php echo htmlspecialchars($user['name']); ?></strong>, welcome to your account dashboard.</p>
                            <p>From here you can view your recent orders, manage your shipping and billing addresses, and edit your account details.</p>
                            
                            <div class="row mt-4">
                                <div class="col-md-6 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <i class="fas fa-box-open fa-2x mb-3"></i>
                                            <h5>Orders</h5>
                                            <p class="mb-0">View your order history</p>
                                            <a href="#orders" class="btn btn-outline-primary mt-3" data-section="orders">View Orders</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <i class="fas fa-heart fa-2x mb-3"></i>
                                            <h5>Wishlist</h5>
                                            <p class="mb-0">View your saved items</p>
                                            <a href="#wishlist" class="btn btn-outline-primary mt-3" data-section="wishlist">View Wishlist</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <i class="fas fa-palette fa-2x mb-3"></i>
                                            <h5>My Designs</h5>
                                            <p class="mb-0">View your custom designs</p>
                                            <a href="#designs" class="btn btn-outline-primary mt-3" data-section="designs">View Designs</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <i class="fas fa-user-edit fa-2x mb-3"></i>
                                            <h5>Account</h5>
                                            <p class="mb-0">Update your details</p>
                                            <a href="#personal" class="btn btn-outline-primary mt-3" data-section="personal">Edit Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Orders Section -->
                <div id="orders-section" class="dashboard-content">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h3 class="card-title">My Orders</h3>
                            <div id="orders-container">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i> You have no orders yet.
                                    <a href="/products.php" class="alert-link">Start shopping</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Wishlist Section -->
                <div id="wishlist-section" class="dashboard-content">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h3 class="card-title">My Wishlist</h3>
                            <div id="wishlist-container">
                                <?php
                                require_once('auth/db.php');
                                
                                // Get user ID from session
                                $userId = $user['user_id'] ?? 0;
                                
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
                                                echo '<div class="row">';
                                                foreach ($items as $item):
                                                    $createdAt = date('F j, Y', strtotime($item['created_at']));
                                                ?>
                                                <div class="col-md-4 mb-4">
                                                    <div class="card h-100 position-relative">
                                                        <button class="position-absolute top-0 end-0 btn btn-sm btn-light rounded-circle m-2" 
                                                                data-wishlist-id="<?= $item['wishlist_id'] ?>">
                                                            <i class="fas fa-times text-danger"></i>
                                                        </button>
                                                        
                                                        <img src="<?= $item['image'] ?>" class="card-img-top" alt="<?= $item['product_name'] ?>">
                                                        <div class="card-body">
                                                            <h5 class="card-title"><?= $item['product_name'] ?></h5>
                                                            <p class="text-muted small">Added on <?= $createdAt ?></p>
                                                            <p class="card-text">₦<?= number_format($item['price']) ?></p>
                                                            <div class="d-flex justify-content-between">
                                                                <button class="btn btn-primary btn-sm add-wishlist-to-cart" 
                                                                        data-product-id="<?= $item['product_id'] ?>"
                                                                        data-product-name="<?= $item['product_name'] ?>"
                                                                        data-product-price="<?= $item['price'] ?>"
                                                                        data-product-image="<?= $item['image'] ?>">
                                                                    Add to Cart
                                                                </button>
                                                                <a href="/product.php?slug=<?= $item['product_id'] ?>" class="btn btn-outline-secondary btn-sm">
                                                                    View Details
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                endforeach;
                                                echo '</div>';
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
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Addresses Section -->
                <div id="addresses-section" class="dashboard-content">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h3 class="card-title">My Addresses</h3>
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">Shipping Address</h5>
                                            <button class="btn btn-sm btn-outline-secondary">Edit</button>
                                        </div>
                                        <div class="card-body">
                                            <address>
                                                <?php echo htmlspecialchars($user['name']); ?><br>
                                                123 Main Street<br>
                                                Lekki Phase 1<br>
                                                Lagos, Nigeria<br>
                                                Phone: +234 801 234 5678
                                            </address>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">Billing Address</h5>
                                            <button class="btn btn-sm btn-outline-secondary">Edit</button>
                                        </div>
                                        <div class="card-body">
                                            <address>
                                                <?php echo htmlspecialchars($user['name']); ?><br>
                                                123 Main Street<br>
                                                Lekki Phase 1<br>
                                                Lagos, Nigeria<br>
                                                Phone: +234 801 234 5678
                                            </address>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Personal Data Section -->
                <div id="personal-section" class="dashboard-content">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h3 class="card-title">Account Details</h3>
                            <form id="personal-data-form" class="needs-validation" novalidate>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="fullName" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="fullName" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email address</label>
                                        <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" id="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="gender" class="form-label">Gender</label>
                                        <select class="form-select" id="gender">
                                            <option value="">Select Gender</option>
                                            <option value="male" <?php echo (isset($user['gender']) && $user['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
                                            <option value="female" <?php echo (isset($user['gender']) && $user['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
                                            <option value="other" <?php echo (isset($user['gender']) && $user['gender'] == 'other') ? 'selected' : ''; ?>>Other</option>
                                            <option value="prefer_not_to_say" <?php echo (isset($user['gender']) && $user['gender'] == 'prefer_not_to_say') ? 'selected' : ''; ?>>Prefer not to say</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <hr class="my-4">
                                <h5>Password Change</h5>
                                
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
                                
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Designs Section -->
                <div id="designs-section" class="dashboard-content">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h3 class="card-title">My Designs</h3>
                            <div id="saved-designs-container">
                                <?php
                                // Get user ID from session
                                $userId = $user['user_id'] ?? 0;
                                
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
                                                echo '<div class="row">';
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
                                                        <button class="position-absolute top-0 end-0 btn btn-sm btn-light rounded-circle m-2 delete-design-btn" 
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
                                                            <p class="card-text">₦<?= number_format($designPrice) ?></p>
                                                            <div class="d-flex justify-content-between">
                                                                <button class="btn btn-primary btn-sm add-design-to-cart" 
                                                                        data-design='<?= htmlspecialchars(json_encode($designData), ENT_QUOTES, 'UTF-8') ?>'>
                                                                    Add to Cart
                                                                </button>
                                                                <a href="/customize.php?design_id=<?= $design['design_id'] ?>" class="btn btn-outline-secondary btn-sm">
                                                                    Edit
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                }
                                                echo '</div>';
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
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Delete Account Section -->
                <div id="delete-account-section" class="dashboard-content">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h3 class="card-title">Delete Account</h3>
                            <div class="alert alert-danger">
                                <h5><i class="fas fa-exclamation-triangle me-2"></i> Warning</h5>
                                <p>Deleting your account is permanent and cannot be undone. All your data, including order history, saved designs, and personal information will be permanently removed.</p>
                            </div>
                            
                            <form id="delete-account-form" class="needs-validation" novalidate>
                                <div class="mb-3">
                                    <label for="deleteConfirmation" class="form-label">To confirm, type "DELETE" below:</label>
                                    <input type="text" class="form-control" id="deleteConfirmation" required>
                                    <div class="invalid-feedback">
                                        Please type DELETE to confirm.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="deletePassword" class="form-label">Enter your password:</label>
                                    <input type="password" class="form-control" id="deletePassword" required>
                                    <div class="invalid-feedback">
                                        Please enter your password.
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-danger">Permanently Delete Account</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('components/footer.php'); ?>
    <?php include('components/scripts.php'); ?>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
      console.log('Dashboard script loaded directly');
      
      // Get user data
      const userData = localStorage.getItem('DRFUser');
      let user = null;
      if (userData) {
        try {
          user = JSON.parse(userData);
        } catch (e) {
          console.error('Error parsing user data:', e);
        }
      }
      
      // Setup tab navigation
      const navLinks = document.querySelectorAll('.dashboard-nav .list-group-item[data-section]');
      
      navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          
          const section = this.getAttribute('data-section');
          console.log('Clicked section:', section);
          
          // Update active link
          navLinks.forEach(l => l.classList.remove('active'));
          this.classList.add('active');
          
          // Hide all sections
          document.querySelectorAll('.dashboard-content').forEach(s => {
            s.style.display = 'none';
          });
          
          // Show selected section
          const targetSection = document.getElementById(section + '-section');
          if (targetSection) {
            targetSection.style.display = 'block';
            console.log('Showing section:', section + '-section');
            
            // Load data for the section if needed
            if (user && (section === 'orders' || section === 'wishlist' || section === 'designs')) {
              const userId = user.user_id || user.id;
              
              if (section === 'orders') {
                loadOrders(userId);
              } else if (section === 'wishlist') {
                loadWishlist(userId);
              } else if (section === 'designs') {
                loadDesigns(userId);
              }
            }
          }
          
          // Update URL hash
          window.location.hash = section;
        });
      });
      
      // Handle logout button
      const logoutBtn = document.getElementById('dashboard-logout');
      if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
          e.preventDefault();
          localStorage.removeItem('DRFUser');
          window.location.href = '/index.php';
        });
      }
      
      // Check URL hash on page load
      const hash = window.location.hash.substring(1);
      if (hash) {
        const link = document.querySelector(`.dashboard-nav .list-group-item[data-section="${hash}"]`);
        if (link) {
          link.click();
        }
      } else {
        // Show default dashboard section
        const defaultLink = document.querySelector('.dashboard-nav .list-group-item[data-section="dashboard"]');
        if (defaultLink) {
          defaultLink.click();
        }
      }
      
      // API data loading functions
      function loadOrders(userId) {
        const container = document.getElementById('orders-container');
        if (!container) return;
        
        container.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading orders...</p></div>';
        
        fetch(`/api/orders.php?user_id=${userId}`)
          .then(response => response.json())
          .then(data => {
            if (data.success && data.orders && data.orders.length > 0) {
              let html = '';
              data.orders.forEach(order => {
                html += `
                  <div class="card mb-3">
                    <div class="card-header">
                      <h5>Order #${order.order_id}</h5>
                    </div>
                    <div class="card-body">
                      <p>Total: ₦${parseFloat(order.total).toLocaleString()}</p>
                      <button class="btn btn-sm btn-primary">View Details</button>
                    </div>
                  </div>
                `;
              });
              container.innerHTML = html;
            } else {
              container.innerHTML = `
                <div class="alert alert-info">
                  <i class="fas fa-info-circle me-2"></i> You have no orders yet.
                </div>
              `;
            }
          })
          .catch(error => {
            console.error('Error loading orders:', error);
            container.innerHTML = `
              <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i> Error loading orders.
              </div>
            `;
          });
      }
      
      function loadWishlist(userId) {
        const container = document.getElementById('wishlist-container');
        if (!container) return;
        
        container.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading wishlist...</p></div>';
        
        fetch(`/api/wishlist.php?user_id=${userId}`)
          .then(response => response.json())
          .then(data => {
            if (data.success && data.items && data.items.length > 0) {
              let html = '<div class="row">';
              data.items.forEach(item => {
                html += `
                  <div class="col-md-4 mb-4">
                    <div class="card">
                      <img src="${item.image}" class="card-img-top" alt="${item.product_name}">
                      <div class="card-body">
                        <h5>${item.product_name}</h5>
                        <p>₦${parseFloat(item.price).toLocaleString()}</p>
                      </div>
                    </div>
                  </div>
                `;
              });
              html += '</div>';
              container.innerHTML = html;
            } else {
              container.innerHTML = `
                <div class="alert alert-info">
                  <i class="fas fa-info-circle me-2"></i> Your wishlist is empty.
                </div>
              `;
            }
          })
          .catch(error => {
            console.error('Error loading wishlist:', error);
            container.innerHTML = `
              <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i> Error loading wishlist.
              </div>
            `;
          });
      }
      
      function loadDesigns(userId) {
        const container = document.getElementById('saved-designs-container');
        if (!container) return;
        
        container.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading designs...</p></div>';
        
        fetch(`/api/get-designs.php?user_id=${userId}`)
          .then(response => response.json())
          .then(data => {
            if (data.success && data.designs && data.designs.length > 0) {
              let html = '<div class="row">';
              data.designs.forEach(design => {
                const designData = JSON.parse(design.design_data);
                html += `
                  <div class="col-md-4 mb-4">
                    <div class="card">
                      <img src="${designData.image || '/images/default.jpg'}" class="card-img-top" alt="${designData.name || 'Design'}">
                      <div class="card-body">
                        <h5>${designData.name || 'Custom Design'}</h5>
                        <p>₦${(designData.price || 0).toLocaleString()}</p>
                      </div>
                    </div>
                  </div>
                `;
              });
              html += '</div>';
              container.innerHTML = html;
            } else {
              container.innerHTML = `
                <div class="alert alert-info">
                  <i class="fas fa-info-circle me-2"></i> You have no saved designs.
                </div>
              `;
            }
          })
          .catch(error => {
            console.error('Error loading designs:', error);
            container.innerHTML = `
              <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i> Error loading designs.
              </div>
            `;
          });
      }
    });
    </script>
    
    <style>
    .dashboard-content {
        display: none;
    }
    </style>
</body>
</html>