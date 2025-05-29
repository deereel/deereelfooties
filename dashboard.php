<?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>

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
              <p class="text-muted mb-4">Products you've saved to purchase later.</p>
              
              <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> Your wishlist is empty.
              </div>
              
              <!-- Sample Wishlist Items (hidden by default) -->
              <div class="row d-none">
                <div class="col-md-4 mb-4">
                  <div class="card h-100">
                    <img src="/images/penny loafer 600.webp" class="card-img-top" alt="Product">
                    <div class="card-body">
                      <h5 class="card-title">Penny Loafer 600</h5>
                      <p class="card-text text-accent">₦42,000</p>
                      <div class="d-flex justify-content-between">
                        <button class="btn-primary btn-sm">Add to Cart</button>
                        <button class="btn-outline-danger btn-sm"><i class="fas fa-trash"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
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
                    <label for="birthdate" class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" id="birthdate">
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
              
              <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> You have no saved designs.
                <a href="/customize.php" class="alert-link">Create a custom design</a>
              </div>
              
              <!-- Sample Design Cards (hidden by default) -->
              <div class="row d-none">
                <div class="col-md-4 mb-4">
                  <div class="card h-100">
                    <img src="/images/custom-design.jpg" class="card-img-top" alt="Custom Design">
                    <div class="card-body">
                      <h5 class="card-title">My Custom Oxford</h5>
                      <p class="text-muted small">Created on March 10, 2023</p>
                      <p class="card-text text-accent">₦65,000</p>
                      <div class="d-flex justify-content-between">
                        <button class="btn-primary btn-sm">Purchase</button>
                        <button class="btn-outline-secondary btn-sm">Edit</button>
                      </div>
                    </div>
                  </div>
                </div>
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

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php'); ?>
  <script src="/js/dashboard.js"></script>
  <script src="/js/orders.js"></script>
  <script src="/js/addresses.js"></script>
  <script src="/js/user_profile.js"></script>
  <script src="/js/dashboard-orders.js"></script>
  <script src="/js/dashboard-wishlist.js"></script>

</body>
</html>