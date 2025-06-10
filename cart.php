<?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/auth/db.php'); ?>
<body class="bg-background data-page="cart">


<body>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>

  <main class="max-w-7xl mx-auto px-4 py-8">


        <div id="cart-items">
          <!-- Cart items will be rendered here by JavaScript -->
        </div>
      </div>
      
      <!-- Cart Summary (Right side - 1/3 width) -->


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


              <p class="mb-0">
                <strong>Bank Name:</strong> Stanbic IBTC Bank<br>
                <strong>Account Name:</strong> Oladayo Quadri<br>
                <strong>Account Number:</strong> 8134235110
              </p>
            </div>



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