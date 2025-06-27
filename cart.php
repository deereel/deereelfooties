<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';

// Check if user is logged in
$currentUser = isset($_SESSION['user']) ? $_SESSION['user'] : null;
// Fallback to old session format if needed
if (!$currentUser && isset($_SESSION['user_id'])) {
$currentUser = [
  'id' => $_SESSION['user_id'],
  'name' => $_SESSION['username'] ?? 'User'
];
}

?>


<!DOCTYPE html>
<html>
<head>
<title>Your Cart | DeeReel Footies</title>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>
</head>

<body data-page="cart">

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
              <span>Shipping</span>
              <strong id="shipping">Depends on location</strong>
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
              <i class="fas fa-user me-1"></i> <?php echo $currentUser ? 'Logged In' : 'Guest'; ?>
            </span>
          </div>
        </div>
        <div class="card-body">
          <?php if (!$currentUser): ?>
          <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" class="alert-link">Sign in</a> to access your saved addresses and checkout faster.
          </div>
          <?php endif; ?>
            
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
                <option value="Lagos" selected>Lagos</option>
                <option value="Abuja">Abuja</option>
                <!-- Other states -->
              </select>
            </div>
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
<?php include($_SERVER['DOCUMENT_ROOT'] . '/components/account-modal.php'); ?>  
<?php include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php'); ?>
  
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Initialize cart handler if not already done
  if (!window.cartHandler) {
      window.cartHandler = new CartHandler();
  }
    
  // Load and render cart
  loadCartItems();
    
  async function loadCartItems() {
      const cartContainer = document.getElementById('cart-items-container');
      if (!cartContainer) return;
        
      try {
          // Show loading
          cartContainer.innerHTML = '<div class="text-center py-4"><div class="spinner-border"></div><p>Loading cart...</p></div>';
            
          const cartItems = await window.cartHandler.getCart();
          renderCartItems(cartItems);
            
      } catch (error) {
          console.error('Error loading cart:', error);
          cartContainer.innerHTML = '<div class="alert alert-danger">Error loading cart items</div>';
      }
  }
    
  function renderCartItems(items) {
      const cartContainer = document.getElementById('cart-items-container');
      const cartSummary = document.getElementById('cart-summary');
        
      if (!items || items.length === 0) {
          cartContainer.innerHTML = `
              <div class="text-center py-5">
                  <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                  <h5>Your cart is empty</h5>
                  <p class="text-muted">Add some items to get started!</p>
                  <a href="/products.php" class="btn btn-primary">Shop Now</a>
              </div>
          `;
          if (cartSummary) cartSummary.style.display = 'none';
          return;
      }
        
      let html = '';
      let total = 0;
      let totalItems = 0;
        
      items.forEach(item => {
          const itemTotal = item.price * item.quantity;
          total += itemTotal;
          totalItems += parseInt(item.quantity);
            
          html += `
              <div class="cart-item border-bottom py-3" data-item-id="${item.cart_item_id || item.product_id}" data-color="${item.color}" data-size="${item.size}" data-width="${item.width}">
                  <div class="row align-items-center">
                      <div class="col-md-2">
                          <img src="${item.image}" alt="${item.product_name}" class="img-fluid rounded" style="max-height: 80px; object-fit: cover;">
                      </div>
                      <div class="col-md-4">
                          <h6 class="mb-1">${item.product_name}</h6>
                          <small class="text-muted">
                              Color: ${item.color} | Size: ${item.size} | Width: ${item.width}
                          </small>
                      </div>
                      <div class="col-md-2">
                          <span class="fw-bold">$${parseFloat(item.price).toFixed(2)}</span>
                      </div>
                      <div class="col-md-2">
                          <div class="input-group input-group-sm">
                              <button class="btn btn-outline-secondary quantity-btn" type="button" data-action="decrease">-</button>
                              <input type="number" class="form-control text-center quantity-input" value="${item.quantity}" min="1" max="10">
                              <button class="btn btn-outline-secondary quantity-btn" type="button" data-action="increase">+</button>
                          </div>
                      </div>
                      <div class="col-md-1">
                          <span class="fw-bold item-total">$${itemTotal.toFixed(2)}</span>
                      </div>
                      <div class="col-md-1">
                          <button class="btn btn-sm btn-outline-danger remove-item" title="Remove item">
                              <i class="fas fa-trash"></i>
                          </button>
                      </div>
                  </div>
              </div>
          `;
      });
        
      cartContainer.innerHTML = html;
        
      // Update cart summary
      if (cartSummary) {
          cartSummary.style.display = 'block';
          cartSummary.innerHTML = `
              <div class="card">
                  <div class="card-body">
                      <h5 class="card-title">Order Summary</h5>
                      <div class="d-flex justify-content-between mb-2">
                          <span>Items (${totalItems}):</span>
                          <span>$${total.toFixed(2)}</span>
                      </div>
                      <div class="d-flex justify-content-between mb-2">
                          <span>Shipping:</span>
                          <span>Free</span>
                      </div>
                      <hr>
                      <div class="d-flex justify-content-between mb-3">
                          <strong>Total:</strong>
                          <strong>$${total.toFixed(2)}</strong>
                      </div>
                      <button class="btn btn-primary w-100" id="checkout-btn">
                          Proceed to Checkout
                      </button>
                  </div>
              </div>
          `;
      }
        
      // Add event listeners
      addCartEventListeners();
  }
    
  function addCartEventListeners() {
      // Quantity change buttons
      document.querySelectorAll('.quantity-btn').forEach(btn => {
          btn.addEventListener('click', async function() {
              const action = this.dataset.action;
              const cartItem = this.closest('.cart-item');
              const quantityInput = cartItem.querySelector('.quantity-input');
              const currentQuantity = parseInt(quantityInput.value);
                
              let newQuantity = currentQuantity;
              if (action === 'increase') {
                  newQuantity = Math.min(currentQuantity + 1, 10);
              } else if (action === 'decrease') {
                  newQuantity = Math.max(currentQuantity - 1, 1);
              }
                
              if (newQuantity !== currentQuantity) {
                  quantityInput.value = newQuantity;
                  await updateCartItemQuantity(cartItem, newQuantity);
              }
          });
      });
        
      // Quantity input direct change
      document.querySelectorAll('.quantity-input').forEach(input => {
          input.addEventListener('change', async function() {
              const cartItem = this.closest('.cart-item');
              const newQuantity = Math.max(1, Math.min(10, parseInt(this.value) || 1));
              this.value = newQuantity;
              await updateCartItemQuantity(cartItem, newQuantity);
          });
      });
        
      // Remove item buttons
      document.querySelectorAll('.remove-item').forEach(btn => {
          btn.addEventListener('click', async function() {
              const cartItem = this.closest('.cart-item');
              await removeCartItem(cartItem);
          });
      });
        
      // Checkout button
      const checkoutBtn = document.getElementById('checkout-btn');
      if (checkoutBtn) {
          checkoutBtn.addEventListener('click', function() {
              // Check if user is logged in
              const userData = localStorage.getItem('DRFUser');
              if (!userData) {
                  alert('Please log in to proceed with checkout');
                  // Show login modal or redirect to login
                  const loginModal = document.getElementById('loginModal');
                  if (loginModal && typeof bootstrap !== 'undefined') {
                      new bootstrap.Modal(loginModal).show();
                  }
                  return;
              }
                
              // Proceed to checkout
              window.location.href = '/checkout.php';
          });
      }
  }
    
  async function updateCartItemQuantity(cartItem, newQuantity) {
      const itemId = cartItem.dataset.itemId;
      const isLoggedIn = window.cartHandler.isLoggedIn;
        
      try {
          if (isLoggedIn) {
              // Update in database
              const response = await fetch('/api/cart.php', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json'
                  },
                  body: JSON.stringify({
                      action: 'update',
                      cart_item_id: itemId,
                      quantity: newQuantity,
                      user_id: window.cartHandler.userId
                  })
              });
                
              const data = await response.json();
              if (!data.success) {
                  throw new Error(data.message);
              }
          } else {
              // Update in localStorage
              let guestCart = window.cartHandler.getGuestCart();
              const color = cartItem.dataset.color;
              const size = cartItem.dataset.size;
              const width = cartItem.dataset.width;
                
              const itemIndex = guestCart.findIndex(item => 
                  item.product_id === itemId && 
                  item.color === color && 
                  item.size === size && 
                  item.width === width
              );
                
              if (itemIndex > -1) {
                  guestCart[itemIndex].quantity = newQuantity;
                  localStorage.setItem('DRFCart', JSON.stringify(guestCart));
              }
          }
            
          // Update item total display
          const priceElement = cartItem.querySelector('.fw-bold');
          const price = parseFloat(priceElement.textContent.replace('$', ''));
          const itemTotalElement = cartItem.querySelector('.item-total');
          itemTotalElement.textContent = `$${(price * newQuantity).toFixed(2)}`;
            
          // Update cart count and summary
          window.cartHandler.updateCartCount();
          loadCartItems(); // Reload to update summary
            
      } catch (error) {
          console.error('Error updating quantity:', error);
          alert('Error updating item quantity');
      }
  }
    
  async function removeCartItem(cartItem) {
      if (!confirm('Are you sure you want to remove this item from your cart?')) {
          return;
      }
        
      const itemId = cartItem.dataset.itemId;
      const isLoggedIn = window.cartHandler.isLoggedIn;
        
      try {
          if (isLoggedIn) {
              // Remove from database
              const response = await fetch('/api/cart.php', {
                  method: 'DELETE',
                  headers: {
                      'Content-Type': 'application/json'
                  },
                  body: JSON.stringify({
                      action: 'remove',
                      cart_item_id: itemId,
                      user_id: window.cartHandler.userId
                  })
              });
                
              const data = await response.json();
              if (!data.success) {
                  throw new Error(data.message);
              }
          } else {
              // Remove from localStorage
              let guestCart = window.cartHandler.getGuestCart();
              const color = cartItem.dataset.color;
              const size = cartItem.dataset.size;
              const width = cartItem.dataset.width;
                
              guestCart = guestCart.filter(item => 
                  !(item.product_id === itemId && 
                    item.color === color && 
                    item.size === size && 
                    item.width === width)
              );
                
              localStorage.setItem('DRFCart', JSON.stringify(guestCart));
          }
            
          // Remove item from display
          cartItem.remove();
            
          // Update cart count and reload items
          window.cartHandler.updateCartCount();
          loadCartItems();
            
      } catch (error) {
          console.error('Error removing item:', error);
          alert('Error removing item from cart');
      }
  }
});
</script>
</body>
</html>
