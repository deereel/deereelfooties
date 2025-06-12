<?php
// Start session at the beginning
session_start();

// Check if user is logged in
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

// If logged in, redirect to logged-in cart
if ($user) {
  header('Location: /logged-in-cart.php');
  exit;
}

// For guest users, cart is handled client-side with localStorage
$cartItems = [];
$subtotal = 0;
$pageTitle = "Your Cart";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $pageTitle ?> - DRF Footwear</title>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
  <style>
    .quantity-input {
      max-width: 50px;
    }
    .progress-bar {
      transition: width 0.6s ease;
    }
  </style>
</head>
<body>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>
  
  <main class="container py-5">
    <h1 class="mb-4"><?= $pageTitle ?></h1>
    
    <div class="row">
      <!-- Cart Items -->
      <div class="col-lg-8">
        <div id="cart-items">
          <!-- Cart items will be loaded dynamically via JavaScript -->
        </div>
        
        <div class="alert alert-info" id="empty-cart-message">
          <i class="fas fa-shopping-cart me-2"></i> Your cart is empty.
          <a href="/products.php" class="alert-link">Continue shopping</a>
        </div>
        
        <div class="d-flex justify-content-between mt-4">
          <a href="/products.php" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i> Continue Shopping
          </a>
        </div>
      </div>
      
      <!-- Order Summary -->
      <div class="col-lg-4">
        <div class="card mb-4">
          <div class="card-header bg-white">
            <h5 class="mb-0">Order Summary</h5>
          </div>
          <div class="card-body">
            <ul class="list-group list-group-flush">
              <li class="list-group-item d-flex justify-content-between">
                <span>Subtotal</span>
                <strong id="subtotal">₦0.00</strong>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span>Shipping</span>
                <strong id="shipping">Depends on location</strong>
              </li>
              <!-- Shipping Hint & Progress -->
              <li class="list-group-item">
                <small class="text-muted d-block mb-2">
                  Shipping fee will be calculated based on your delivery address.
                </small>
                <small class="text-muted" id="shipping-hint">
                  Free shipping on orders above ₦150,000 within Lagos and ₦250,000 outside Lagos.
                </small>
                <!-- Free Shipping Progress Bar -->
                <div class="progress mt-3 rounded-pill" style="height: 14px; background-color: #e9ecef;">
                  <div
                    class="progress-bar rounded-pill"
                    id="shipping-progress"
                    style="width: 0%; transition: width 0.6s ease;"
                  ></div>
                </div>
                <small id="shipping-progress-label" class="d-block mt-2 fw-medium text-sm text-muted">
                  <!-- Label gets updated dynamically -->
                </small>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span>Total</span>
                <strong id="total">₦0.00</strong>
              </li>
            </ul>
          </div>
        </div>

        <!-- Customer Information -->
        <div class="card mb-4">
          <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Customer Information</h5>
            <div id="user-status-indicator">
              <span class="badge bg-warning">
                <i class="fas fa-user me-1"></i> Guest
              </span>
            </div>
          </div>
          <div class="card-body">
            <!-- Form fields -->
            <div id="address-form-fields">
              <div class="mb-3">
                <label for="client-name" class="form-label">Full Name</label>
                <input type="text" id="client-name" class="form-control" placeholder="Enter your full name">
              </div>
              
              <div class="mb-3">
                <label for="client-phone" class="form-label">Phone Number</label>
                <input type="tel" id="client-phone" class="form-control" placeholder="Enter your phone number">
              </div>
              
              <div class="mb-3">
                <label for="shipping-address" class="form-label">Shipping Address</label>
                <textarea id="shipping-address" rows="3" class="form-control" placeholder="Enter your complete address"></textarea>
              </div>
              
              <div class="mb-3">
                <label for="state-select" class="form-label">State</label>
                <select id="state-select" class="form-select">
                  <option value="">Select State</option>
                  <option value="Abia">Abia</option>
                  <option value="Adamawa">Adamawa</option>
                  <option value="Akwa Ibom">Akwa Ibom</option>
                  <option value="Anambra">Anambra</option>
                  <option value="Bauchi">Bauchi</option>
                  <option value="Bayelsa">Bayelsa</option>
                  <option value="Benue">Benue</option>
                  <option value="Borno">Borno</option>
                  <option value="Cross River">Cross River</option>
                  <option value="Delta">Delta</option>
                  <option value="Ebonyi">Ebonyi</option>
                  <option value="Edo">Edo</option>
                  <option value="Ekiti">Ekiti</option>
                  <option value="Enugu">Enugu</option>
                  <option value="FCT">Federal Capital Territory</option>
                  <option value="Gombe">Gombe</option>
                  <option value="Imo">Imo</option>
                  <option value="Jigawa">Jigawa</option>
                  <option value="Kaduna">Kaduna</option>
                  <option value="Kano">Kano</option>
                  <option value="Katsina">Katsina</option>
                  <option value="Kebbi">Kebbi</option>
                  <option value="Kogi">Kogi</option>
                  <option value="Kwara">Kwara</option>
                  <option value="Lagos" selected>Lagos</option>
                  <option value="Nasarawa">Nasarawa</option>
                  <option value="Niger">Niger</option>
                  <option value="Ogun">Ogun</option>
                  <option value="Ondo">Ondo</option>
                  <option value="Osun">Osun</option>
                  <option value="Oyo">Oyo</option>
                  <option value="Plateau">Plateau</option>
                  <option value="Rivers">Rivers</option>
                  <option value="Sokoto">Sokoto</option>
                  <option value="Taraba">Taraba</option>
                  <option value="Yobe">Yobe</option>
                  <option value="Zamfara">Zamfara</option>
                </select>
              </div>
              
              <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> 
                <a href="/auth/login.php" class="alert-link">Sign in</a> or 
                <a href="/auth/signup.php" class="alert-link">create an account</a> 
                to save your address and track your orders.
              </div>
            </div>
          </div>
        </div>

        <!-- Bank Information -->
        <div class="card mb-4">
          <div class="card-header bg-white">
            <h5 class="mb-0">Bank Transfer Information</h5>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <p class="mb-2">
                <strong>Bank Name:</strong> OPAY Digital Bank<br>
                <strong>Account Name:</strong> Oladayo Quadri<br>
                <strong>Account Number:</strong> 8134235110
              </p>
              <p class="mb-2">
                <strong>Bank Name:</strong> Stanbic IBTC Bank<br>
                <strong>Account Name:</strong> Oladayo Quadri<br>
                <strong>Account Number:</strong> 0050379869
              </p>
              <p class="mb-0">
                <strong>Bank Name:</strong> Polaris Bank<br>
                <strong>Account Name:</strong> Uthman Oladayo Quadri<br>
                <strong>Account Number:</strong> 3125133788
              </p>
            </div>
            
            <div class="mb-3">
              <label for="payment-proof" class="form-label">Upload Payment Proof</label>
              <input type="file" id="payment-proof" class="form-control" accept="image/*,application/pdf">
              <embed id="proof-pdf" type="application/pdf" width="100%" height="200px" style="display: none; border: 1px solid #ccc; margin-top: 10px;" />
            </div>
          </div>
        </div>

        <!-- Checkout Button -->
        <button class="btn btn-success w-100 mb-4" id="checkout-btn" disabled>
          <i class="fas fa-credit-card me-2"></i> Proceed to Checkout
        </button>
      </div>
    </div>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'); ?>
  
  <!-- Scroll to Top Button -->
  <a href="#" class="btn btn-dark position-fixed bottom-0 end-0 m-4 shadow rounded-circle" style="z-index: 999; width: 45px; height: 45px; display: none;" id="scrollToTop">
    <i class="fas fa-chevron-up"></i>
  </a>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php'); ?>
  <script src="/js/guest-cart.js"></script>
</body>
</html>