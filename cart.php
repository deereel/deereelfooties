<?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/auth/db.php'); ?>

<body data-page="cart">
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>

  <main class="container py-5">
    <h1 class="mb-4">Shopping Cart</h1>
    
    <div class="row">
      <!-- Cart Items (Left side) -->
      <div class="col-lg-7 mb-4">
        <div class="card">
          <div class="card-header bg-white">
            <h5 class="mb-0">Cart Items</h5>
          </div>
          <div class="card-body">
            <div id="cart-items" class="mb-3">
              <!-- Cart items will be rendered here by JavaScript -->
              <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-3">Loading your cart...</p>
              </div>
            </div>
            <div id="empty-cart-message" class="text-center py-5" style="display: none;">
              <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
              <h5>Your cart is empty</h5>
              <p class="text-muted">Add items to your cart to continue shopping.</p>
              <a href="/products.php" class="btn btn-primary mt-3">Continue Shopping</a>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Right Side (Summary, Bank Info, etc.) -->
      <div class="col-lg-5">
        <!-- Order Summary -->
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
            <div id="user-status-indicator"></div>
          </div>
          <div class="card-body">
            <!-- For logged-in users: Saved addresses dropdown -->
            <div id="saved-addresses-section" style="display: none;">
              <div class="mb-3">
                <label for="saved-addresses" class="form-label">Select a saved address</label>
                <select id="saved-addresses" class="form-select">
                  <option value="">Loading addresses...</option>
                </select>
              </div>
              <div class="mb-3">
                <button type="button" class="btn btn-outline-primary btn-sm" id="new-address-btn">
                  <i class="fas fa-plus me-1"></i> Use a new address
                </button>
              </div>
              <hr>
            </div>
            
            <!-- Form fields for both guest and logged-in users -->
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
              
              <!-- For logged-in users: Save address option -->
              <div class="form-check mb-3" id="save-address-option" style="display: none;">
                <input class="form-check-input" type="checkbox" id="save-address-checkbox">
                <label class="form-check-label" for="save-address-checkbox">
                  Save this address for future orders
                </label>
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
        <button class="btn btn-success w-100 mb-4" id="checkout-btn">
          <i class="fas fa-credit-card me-2"></i> Proceed to Checkout
        </button>
      </div>
    </div>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/account-modal.php'); ?>
  
  <!-- Scroll to Top Button -->
  <a href="#" class="btn btn-dark position-fixed bottom-0 end-0 m-4 shadow rounded-circle" style="z-index: 999; width: 45px; height: 45px; display: none;" id="scrollToTop">
    <i class="fas fa-chevron-up"></i>
  </a>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php'); ?>
  <script src="/js/cart-page.js"></script>
</body>
</html>