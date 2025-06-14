<?php
require_once 'auth/db.php';
session_start();

// Check if user is logged in
$currentUser = isset($_SESSION['user']) ? $_SESSION['user'] : null;
// Fallback to old session format if needed
if (!$currentUser && isset($_SESSION['user_id'])) {
  $currentUser = [
    'id' => $_SESSION['user_id'],
    'name' => $_SESSION['username'] ?? 'User'
  ];
}

// If logged in, redirect to logged-in cart
if ($currentUser) {
  header('Location: /logged-in-cart.php');
  exit;
}

include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php');
?>

<body data-page="cart">
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>

  <main class="container py-5">
    <h1 class="mb-4">Your Cart</h1>
    
    <div class="row">
      <!-- Cart Items -->
      <div class="col-lg-8">
        <div id="empty-cart-message" class="text-center py-5" style="display: none;">
          <i class="fas fa-shopping-cart fa-3x mb-3 text-muted"></i>
          <h3>Your cart is empty</h3>
          <p class="text-muted mb-4">Looks like you haven't added any items to your cart yet.</p>
          <a href="/products.php" class="btn btn-primary">Continue Shopping</a>
        </div>
        
        <div id="cart-items">
          <!-- Cart items will be loaded here via JavaScript -->
          <div class="text-center py-5">
            <div class="spinner-border" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading cart items...</p>
          </div>
        </div>
      </div>
      
      <!-- Order Summary -->
      <div class="col-lg-4">
        <div class="card mb-4">
          <div class="card-header bg-white">
            <h5 class="mb-0">Order Summary</h5>
          </div>
          <div class="card-body">
            <ul class="list-group list-group-flush mb-3">
              <li class="list-group-item d-flex justify-content-between">
                <span>Subtotal</span>
                <strong id="subtotal">₦0.00</strong>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span>Accessories</span>
                <strong id="accessories">₦0.00</strong>
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
              <span class="badge bg-secondary">
                <i class="fas fa-user me-1"></i> Guest
              </span>
            </div>
          </div>
          <div class="card-body">
            <div class="alert alert-info">
              <i class="fas fa-info-circle me-2"></i>
              <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" class="alert-link">Sign in</a> to access your saved addresses and checkout faster.
            </div>
            
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
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Initialize cart functionality
      if (window.cartManager) {
        // Load cart items
        window.cartManager.getCart().then(data => {
          if (data.success && data.cart_items && data.cart_items.length > 0) {
            renderCartItems(data.cart_items);
            document.getElementById('checkout-btn').disabled = false;
          } else {
            document.getElementById('cart-items').style.display = 'none';
            document.getElementById('empty-cart-message').style.display = 'block';
          }
        });
      }
    });
    
    // Render cart items
    function renderCartItems(items) {
      const container = document.getElementById('cart-items');
      let html = '';
      let subtotal = 0;
      
      items.forEach(item => {
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;
        
        html += `
          <div class="card mb-3">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-md-2 col-4 mb-3 mb-md-0">
                  <img src="${item.image_url || item.image || '/images/product-placeholder.jpg'}" class="img-fluid rounded" alt="${item.name}">
                </div>
                <div class="col-md-6 col-8 mb-3 mb-md-0">
                  <h5 class="card-title">${item.name}</h5>
                  <p class="card-text text-muted mb-1">
                    <span class="me-2">Color: ${item.color}</span>
                    <span class="me-2">Size: ${item.size}</span>
                    <span>Width: ${item.width}</span>
                  </p>
                  <p class="card-text text-primary">₦${parseFloat(item.price).toLocaleString()}</p>
                </div>
                <div class="col-md-2 col-6">
                  <div class="input-group">
                    <button class="btn btn-outline-secondary quantity-btn" type="button" data-action="decrease" data-id="${item.id}">-</button>
                    <input type="text" class="form-control text-center quantity-input" value="${item.quantity}" readonly>
                    <button class="btn btn-outline-secondary quantity-btn" type="button" data-action="increase" data-id="${item.id}">+</button>
                  </div>
                </div>
                <div class="col-md-2 col-6 text-end">
                  <p class="fw-bold mb-2">₦${itemTotal.toLocaleString()}</p>
                  <button class="btn btn-sm btn-outline-danger remove-item" data-id="${item.id}">
                    <i class="fas fa-trash-alt me-1"></i> Remove
                  </button>
                </div>
              </div>
            </div>
          </div>
        `;
      });
      
      container.innerHTML = html;
      
      // Update subtotal and total
      document.getElementById('subtotal').textContent = `₦${subtotal.toLocaleString()}`;
      document.getElementById('total').textContent = `₦${subtotal.toLocaleString()}`;
      
      // Setup event listeners
      setupCartEventListeners();
    }
    
    // Setup cart event listeners
    function setupCartEventListeners() {
      // Quantity buttons
      document.querySelectorAll('.quantity-btn').forEach(btn => {
        btn.addEventListener('click', function() {
          const itemId = this.dataset.id;
          const action = this.dataset.action;
          const quantityInput = this.closest('.input-group').querySelector('.quantity-input');
          let quantity = parseInt(quantityInput.value);
          
          if (action === 'increase') {
            quantity += 1;
          } else if (action === 'decrease' && quantity > 1) {
            quantity -= 1;
          } else {
            return;
          }
          
          // Update cart
          window.cartManager.updateCartItem(itemId, quantity).then(result => {
            if (result.success) {
              // Reload cart items
              window.cartManager.getCart().then(data => {
                if (data.success && data.cart_items) {
                  renderCartItems(data.cart_items);
                }
              });
            }
          });
        });
      });
      
      // Remove buttons
      document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function() {
          const itemId = this.dataset.id;
          
          if (confirm('Are you sure you want to remove this item from your cart?')) {
            window.cartManager.removeFromCart(itemId).then(result => {
              if (result.success) {
                // Reload cart items
                window.cartManager.getCart().then(data => {
                  if (data.success && data.cart_items && data.cart_items.length > 0) {
                    renderCartItems(data.cart_items);
                  } else {
                    document.getElementById('cart-items').style.display = 'none';
                    document.getElementById('empty-cart-message').style.display = 'block';
                    document.getElementById('checkout-btn').disabled = true;
                  }
                });
              }
            });
          }
        });
      });
    }
  </script>
</body>
</html>