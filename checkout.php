<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Checkout | DeeReel Footies</title>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>
</head>

<body class="bg-background">

  <!-- Main Content -->
  <main>
    <div class="container my-5">
      <h2 class="mb-4">Checkout</h2>
      
      <div class="row">
        <div class="col-lg-8">
          <!-- Checkout Form -->
          <div class="card p-4 mb-4">
            <h4 class="mb-3">Shipping Information</h4>
            <form id="checkout-form">
              <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" required />
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" required />
              </div>
              <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="tel" class="form-control" id="phone" required />
              </div>
              <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" id="address" rows="2" required></textarea>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="city" class="form-label">City</label>
                  <input type="text" class="form-control" id="city" required />
                </div>
                <div class="col-md-6 mb-3">
                  <label for="postal-code" class="form-label">Postal Code</label>
                  <input type="text" class="form-control" id="postal-code" required />
                </div>
              </div>
              <div class="mb-3">
                <label for="country" class="form-label">Country</label>
                <select class="form-select" id="country" required>
                  <option value="">Select Country</option>
                  <option value="Nigeria">Nigeria</option>
                  <option value="Ghana">Ghana</option>
                  <option value="Kenya">Kenya</option>
                  <option value="South Africa">South Africa</option>
                  <option value="Other">Other</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="payment-method" class="form-label">Payment Method</label>
                <select class="form-select" id="payment-method" required>
                  <option value="">Select Payment Method</option>
                  <option value="bank-transfer">Bank Transfer</option>
                  <option value="paystack">Paystack</option>
                  <option value="flutterwave">Flutterwave</option>
                </select>
              </div>
              <button type="submit" class="btn btn-primary w-100">Place Order</button>
            </form>
          </div>
        </div>
        
        <div class="col-lg-4">
          <!-- Order Summary -->
          <div class="card p-4 mb-4">
            <h4>Order Summary</h4>
            <div id="checkout-items">
              <!-- Items will be loaded here -->
            </div>
            <hr>
            <p class="mb-1">Subtotal: <span class="fw-bold">€<span id="checkout-subtotal">0.00</span></span></p>
            <p class="mb-1">Shipping: <span class="fw-bold">€<span id="checkout-shipping">10.00</span></span></p>
            <hr>
            <h5>Total: <span class="fw-bold">€<span id="checkout-total">0.00</span></span></h5>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/account-modal.php'); ?>
  
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php'); ?>

  <!-- Checkout Page Script -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      if (typeof CartHandler === 'undefined') {
        console.error('CartHandler is not defined. Please ensure cart-handler.js is loaded.');
        return;
      }
      
      const cartHandler = new CartHandler();
      
      // Populate checkout summary
      function renderCheckoutSummary() {
        const cartItems = cartHandler.cartItems;
        const checkoutItems = document.getElementById('checkout-items');
        
        if (cartItems.length === 0) {
          window.location.href = '/cart.php';
          return;
        }
        
        // Clear container
        checkoutItems.innerHTML = '';
        
        // Create checkout items
        cartItems.forEach(item => {
          const itemEl = document.createElement('div');
          itemEl.className = 'mb-2';
          itemEl.innerHTML = `
            <div class="d-flex justify-content-between">
              <div>
                <span class="fw-bold">${item.product_name}</span>
                <small class="d-block text-muted">
                  ${item.color ? `${item.color}, ` : ''}
                  ${item.size ? `Size ${item.size}, ` : ''}
                  ${item.width ? `Width ${item.width}` : ''}
                </small>
              </div>
              <div>
                <span>${item.quantity} × €${item.price}</span>
              </div>
            </div>
          `;
          checkoutItems.appendChild(itemEl);
        });
        
        // Update totals
        const subtotal = cartHandler.getCartTotal();
        const shipping = 10.00; // Fixed shipping cost
        const total = subtotal + shipping;
        
        document.getElementById('checkout-subtotal').textContent = subtotal.toFixed(2);
        document.getElementById('checkout-total').textContent = total.toFixed(2);
      }
      
      renderCheckoutSummary();
      
      // Handle checkout form submission
      document.getElementById('checkout-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = {
          name: document.getElementById('name').value,
          email: document.getElementById('email').value,
          phone: document.getElementById('phone').value,
          address: document.getElementById('address').value,
          city: document.getElementById('city').value,
          postalCode: document.getElementById('postal-code').value,
          country: document.getElementById('country').value,
          paymentMethod: document.getElementById('payment-method').value,
          items: cartHandler.cartItems,
          subtotal: cartHandler.getCartTotal(),
          shipping: 10.00,
          total: cartHandler.getCartTotal() + 10.00
        };
        
        // Submit order
        fetch('/api/create-order.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Clear cart
            cartHandler.clearCart();
            
            // Redirect to order confirmation
            window.location.href = `/order-confirmation.php?order_id=${data.order_id}`;
          } else {
            alert('Error creating order: ' + (data.error || 'Unknown error'));
          }
        })
        .catch(error => {
          console.error('Error creating order:', error);
          alert('An error occurred while processing your order. Please try again.');
        });
      });
    });
  </script>
</body>
</html>