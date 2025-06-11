<?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/auth/db.php'); ?>

<body data-page="cart">
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>

  <main class="container py-5">
    <h1 class="mb-4">Shopping Cart</h1>
    
    <div class="row">
      <!-- Cart Items (Left side - 2/3 width) -->
      <div class="col-lg-8 mb-4">
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
      
      <!-- Cart Summary (Right side - 1/3 width) -->
      <div class="col-lg-4">
        <div class="card">
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
                <span>Shipping</span>
                <strong id="shipping">₦0.00</strong>
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

            <!-- Checkout Button -->
            <button class="btn btn-primary w-100" id="checkout-btn">
              <i class="fas fa-credit-card me-2"></i> Proceed to Checkout
            </button>
          </div>
        </div>
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
  <script src="/js/cart.js"></script>
</body>
</html>