<?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>

<body class="bg-background">
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>

  <main class="max-w-4xl mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
      <div id="checkout-success" class="text-center py-8">
        <div class="mb-6">
          <i class="fas fa-check-circle text-green-500 text-6xl"></i>
        </div>
        <h1 class="text-3xl font-light mb-4">Thank You for Your Order!</h1>
        <p class="text-lg mb-2">Your order has been received.</p>
        <p class="mb-6">Order #<span id="order-number" class="font-medium"></span></p>
        
        <div class="bg-gray-50 p-6 rounded-lg mb-6 text-left">
          <h2 class="text-xl font-medium mb-4">Order Summary</h2>
          
          <div id="order-details" class="mb-4">
            <!-- Order details will be inserted here -->
          </div>
          
          <div class="border-t pt-4 mt-4">
            <div class="flex justify-between mb-2">
              <span>Subtotal</span>
              <span id="summary-subtotal">₦0</span>
            </div>
            <div class="flex justify-between mb-2">
              <span>Accessories</span>
              <span id="summary-accessories">₦0</span>
            </div>
            <div class="flex justify-between mb-2">
              <span>Shipping</span>
              <span id="summary-shipping">Depends on location</span>
            </div>
            <div class="flex justify-between font-medium">
              <span>Total</span>
              <span id="summary-total">₦0</span>
            </div>
          </div>
        </div>
        
        <div class="bg-gray-50 p-6 rounded-lg mb-6 text-left">
          <h2 class="text-xl font-medium mb-4">Shipping Information</h2>
          <p id="shipping-name" class="mb-1"></p>
          <p id="shipping-address" class="mb-1"></p>
          <p id="shipping-state" class="mb-1"></p>
        </div>
        
        <div class="bg-gray-50 p-6 rounded-lg mb-6 text-left">
          <h2 class="text-xl font-medium mb-4">Payment Status</h2>
          <p class="mb-4">Production will begin once your payment has been confirmed.</p>
          
          <div id="payment-status" class="mb-4">
            <span class="inline-block px-3 py-1 rounded-full bg-yellow-100 text-yellow-800">
              <i class="fas fa-clock mr-1"></i> Awaiting Confirmation
            </span>
          </div>
          
          <a href="https://wa.me/2347031864772?text=Hello%20DeeReeL%20Footies%2C%20Kindly%20confirm%20my%20payment%20for%20Order%20%23" id="whatsapp-link" class="btn-success inline-flex items-center px-4 py-2 rounded" target="_blank">
            <i class="fab fa-whatsapp mr-2 text-xl"></i> Contact Us on WhatsApp for Payment Confirmation
          </a>
        </div>
        
        <div class="bg-gray-50 p-6 rounded-lg mb-6 text-left">
          <h2 class="text-xl font-medium mb-4">Order Progress</h2>
          <button id="view-progress-btn" class="btn-primary px-4 py-2 mb-4">View Order Progress</button>
          
          <div id="progress-container" class="hidden">
            <div class="relative pt-1">
              <div class="flex mb-2 items-center justify-between">
                <div>
                  <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-blue-600 bg-blue-200">
                    In Progress
                  </span>
                </div>
                <div class="text-right">
                  <span class="text-xs font-semibold inline-block text-blue-600" id="progress-days">
                    Day 0 of 7
                  </span>
                </div>
              </div>
              <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-200">
                <div id="progress-bar" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500" style="width: 0%"></div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="mt-8">
          <a href="/index.php" class="btn-outline-primary px-6 py-2 mr-4">
            <i class="fas fa-home mr-2"></i> Return to Home
          </a>
          <a href="/dashboard.php#orders" class="btn-primary px-6 py-2">
            <i class="fas fa-list mr-2"></i> View My Orders
          </a>
        </div>
      </div>
    </div>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/account-modal.php'); ?>
  
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php'); ?>
  <script src="/js/checkout.js"></script>
</body>
</html>