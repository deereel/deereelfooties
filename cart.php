<?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>

<body class="bg-background">
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>

  <main class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-light mb-8">Shopping Cart</h1>
    
    <!-- Cart Grid Layout: Items on left, Summary on right -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      
      <!-- Cart Items (Left side - 2/3 width) -->
      <div class="lg:col-span-2">
        <div id="cart-items">
          <!-- Cart items will be rendered here by JavaScript -->
        </div>
        <div id="empty-cart-message" class="text-center py-12">
          <h2 class="text-2xl font-light mb-4">Your Cart is Empty</h2>
          <p class="mb-6">Looks like you haven't added any items to your cart yet.</p>
          <a href="/products.php" class="bg-black text-white px-6 py-2 inline-block hover:bg-gray-800 transition">CONTINUE SHOPPING</a>
        </div>
        <div class="mt-6 text-center" id="continue-shopping-container" style="display: none;">
          <button id="back-button" class="btn-outline-secondary px-6 py-2">
            <i class="fas fa-arrow-left mr-2"></i> Continue Shopping
          </button>
        </div>
      </div>
      
      <!-- Cart Summary (Right side - 1/3 width) -->
      <div class="lg:col-span-1">
        <div id="cart-summary" class="bg-gray-50 p-6 rounded-lg sticky top-4">
          <!-- Shipping progress will be inserted here -->
          
          <h3 class="text-lg font-medium mb-4">Order Summary</h3>
          
          <div class="space-y-3 mb-6">
            <div class="flex justify-between">
              <span>Subtotal</span>
              <span id="cart-subtotal">₦0</span>
            </div>
            <div class="flex justify-between">
              <span>Accessories</span>
              <span id="cart-accessories">₦0</span>
            </div>
            <div class="flex justify-between">
              <span>Shipping</span>
              <span id="cart-shipping">Depends on location</span>
            </div>
            <div class="border-t pt-3 flex justify-between font-medium text-lg">
              <span>Total</span>
              <span id="cart-total">₦0</span>
            </div>
          </div>

          <!-- Customer Information Form -->
          <div class="space-y-4">
            <div>
              <label for="client-name" class="block text-sm font-medium mb-1">Full Name</label>
              <input type="text" id="client-name" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Enter your full name">
            </div>
            
            <!-- Shipping Address Container - will be populated by JS -->
            <div id="shipping-container">
              <!-- This will be replaced by JS based on login status -->
            </div>
            
            <div>
              <label for="state" class="block text-sm font-medium mb-1">State</label>
              <select id="state" class="w-full border border-gray-300 rounded px-3 py-2">
                <option value="">Select State</option>
                <option value="Lagos">Lagos</option>
                <option value="Abuja">Abuja</option>
                <option value="Rivers">Rivers</option>
                <option value="Kano">Kano</option>
                <option value="Oyo">Oyo</option>
                <option value="Kaduna">Kaduna</option>
                <option value="Enugu">Enugu</option>
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
                <option value="Gombe">Gombe</option>
                <option value="Imo">Imo</option>
                <option value="Jigawa">Jigawa</option>
                <option value="Katsina">Katsina</option>
                <option value="Kebbi">Kebbi</option>
                <option value="Kogi">Kogi</option>
                <option value="Kwara">Kwara</option>
                <option value="Nasarawa">Nasarawa</option>
                <option value="Niger">Niger</option>
                <option value="Ogun">Ogun</option>
                <option value="Ondo">Ondo</option>
                <option value="Osun">Osun</option>
                <option value="Plateau">Plateau</option>
                <option value="Sokoto">Sokoto</option>
                <option value="Taraba">Taraba</option>
                <option value="Yobe">Yobe</option>
                <option value="Zamfara">Zamfara</option>
              </select>
            </div>

            <!-- Bank Info -->
            <div class="mb-3">
              <h6 class="fw-bold">Bank Transfer Information</h6>
              <div class="text-muted small mb-2">
                <p>Make payment to the bank account details below and upload proof of payment.</p>
              </div> 
              <div class="text-muted small mb-2">
                <p>Payment is required to confirm your order.</p>
              </div>
              <div class="border-top my-4"></div>
              <p class="mb-2">
                <strong>Bank Name:</strong> OPAY Digital Bank<br>
                <strong>Account Name:</strong> Oladayo Quadri<br>
                <strong>Account Number:</strong> 8134235110
              </p>
              <p class="mb-0">
                <strong>Bank Name:</strong> Stanbic IBTC Bank<br>
                <strong>Account Name:</strong> Oladayo Quadri<br>
                <strong>Account Number:</strong> 0050379869
              </p>
            </div>

            <!-- Proof of Payment Upload -->
            <div class="mb-4">
              <label for="payment-proof" class="form-label">Upload Proof of Payment</label>
              <input class="form-control" type="file" id="payment-proof" accept="image/*,.pdf">
            </div>
            <!-- Proof Preview -->
            <div id="proof-preview" class="mb-3 text-center d-none">
              <p class="text-muted">Preview:</p>
              <!-- Image Preview -->
              <img id="proof-image" src="" alt="Payment Proof" class="img-fluid rounded shadow-sm mb-2" style="max-height: 200px; display: none;">

              <!-- PDF Preview -->
              <embed id="proof-pdf" type="application/pdf" width="100%" height="200px" style="display: none; border: 1px solid #ccc;" />
            </div>

            <!-- Checkout Button -->
            <button class="btn-primary w-100" id="checkout-btn">
              <i class="fas fa-credit-card me-2"></i> Proceed to Checkout
            </button>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/account-modal.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/search-modal.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/added-to-cart-modal.php'); ?>

  <!-- Scroll to Top Button -->
  <a href="#" class="btn-primary position-fixed bottom-0 end-0 m-4 shadow rounded-circle" style="z-index: 999; width: 45px; height: 45px; display: none;" id="scrollToTop">
    <i class="fas fa-chevron-up"></i>
  </a>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php'); ?>
  <script src="/js/cart-page.js"></script>
  
</body>
</html>