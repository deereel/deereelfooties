<?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>

<body>
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
      </div>
      
      <!-- Cart Summary (Right side - 1/3 width) -->
      <div class="lg:col-span-1">
        <div id="cart-summary" class="bg-gray-50 p-6 rounded-lg sticky top-4">
          <!-- Shipping progress will be inserted here -->
          
          <h3 class="text-lg font-medium mb-4">Order Summary</h3>
          
          <div class="space-y-3 mb-6">
            <div class="flex justify-between">
              <span>Subtotal</span>
              <span id="subtotal">₦0</span>
            </div>
            <div class="flex justify-between">
              <span>Shipping</span>
              <span id="shipping">Calculated at checkout</span>
            </div>
            <div class="border-t pt-3 flex justify-between font-medium text-lg">
              <span>Total</span>
              <span id="total">₦0</span>
            </div>
          </div>

          <!-- Customer Information Form -->
          <div class="space-y-4">
            <div>
              <label for="client-name" class="block text-sm font-medium mb-1">Full Name</label>
              <input type="text" id="client-name" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Enter your full name">
            </div>
            
            <div>
              <label for="shipping-address" class="block text-sm font-medium mb-1">Shipping Address</label>
              <textarea id="shipping-address" rows="3" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Enter your complete address"></textarea>
            </div>

            <!-- Bank Info -->
            <div class="mb-3">
              <h6 class="fw-bold">Bank Transfer Information</h6>
              <p class="mb-2">
                <strong>Bank Name:</strong> OPAY Digital Bank<br>
                <strong>Account Name:</strong> Oladayo Quadri<br>
                <strong>Account Number:</strong> 8134235110
              </p>
              <p class="mb-0">
                <strong>Bank Name:</strong> Stanbic IBTC Bank<br>
                <strong>Account Name:</strong> Oladayo Quadri<br>
                <strong>Account Number:</strong> 8134235110
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
            <button class="btn btn-success w-100" id="checkout-btn">
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


  
</body>
</html>