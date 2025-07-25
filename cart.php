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
      <!-- Free Shipping Progress -->
      <div class="card mb-4">
        <div class="card-header bg-white">
          <h5 class="mb-0"><i class="fas fa-truck me-2"></i>Free Shipping</h5>
        </div>
        <div class="card-body">
          <div id="shipping-progress-container">
            <div class="mb-2">
              <small id="shipping-progress-text" class="text-muted">
                Add ₦150,000 more for free shipping to Lagos
              </small>
            </div>
            <div class="progress mb-2" style="height: 8px;">
              <div id="shipping-progress-bar" class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="small text-muted">
              <div id="shipping-info-text">
                📍 Free shipping: ₦150k+ (Lagos) • ₦250k+ (Other Nigerian states) • ₦600k+ (African countries) • ₦800k+ (Other countries)
              </div>
            </div>
          </div>
        </div>
      </div>
      
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
            
          <!-- Saved Addresses for Logged-in Users -->
          <?php if ($currentUser): ?>
          <div id="saved-addresses" class="mb-4">
            <h6 class="mb-3">Saved Addresses</h6>
            <div id="addresses-list">
              <!-- Addresses will be loaded via JavaScript -->
              <div class="text-center py-3">
                <div class="spinner-border spinner-border-sm" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
                <span class="ms-2">Loading saved addresses...</span>
              </div>
            </div>
            
            <div class="mt-3">
              <button type="button" class="btn btn-outline-primary btn-sm" id="add-new-address-btn">
                <i class="fas fa-plus me-1"></i> Add New Address
              </button>
            </div>
          </div>
          <?php endif; ?>
          
          <!-- Address Form (for guests or new address for users) -->
          <div id="address-form-fields" <?php echo $currentUser ? 'style="display: none;"' : ''; ?>>
            <h6 class="mb-3"><?php echo $currentUser ? 'New Address' : 'Shipping Information'; ?></h6>
            
            <?php if ($currentUser): ?>
            <div class="mb-3">
              <label for="address-name" class="form-label">Address Name</label>
              <input type="text" id="address-name" class="form-control" placeholder="e.g., Home, Work, Office, Parents House, etc." value="Home">
            </div>
            <?php endif; ?>
            
            <div class="mb-3">
              <label for="client-name" class="form-label">Full Name <span class="text-danger">*</span></label>
              <input type="text" id="client-name" class="form-control" placeholder="Enter your full name" required>
            </div>
              
            <div class="mb-3">
              <label for="client-phone" class="form-label">Phone Number</label>
              <input type="tel" id="client-phone" class="form-control" placeholder="Enter your phone number">
            </div>
              
            <div class="mb-3">
              <label for="shipping-address" class="form-label">Street Address <span class="text-danger">*</span></label>
              <textarea id="shipping-address" rows="3" class="form-control" placeholder="Enter your street address" required></textarea>
            </div>
            
            <div class="mb-3">
              <label for="city-input" class="form-label">City <span class="text-danger">*</span></label>
              <input type="text" id="city-input" class="form-control" placeholder="Enter your city" required>
            </div>
            
            <div class="mb-3">
              <label for="country-select" class="form-label">Country <span class="text-danger">*</span></label>
              <select id="country-select" class="form-select" required>
                <option value="">Select Country</option>
                <option value="Nigeria" selected>Nigeria</option>
                <option value="Ghana">Ghana</option>
                <option value="Kenya">Kenya</option>
                <option value="South Africa">South Africa</option>
                <option value="United States">United States</option>
                <option value="United Kingdom">United Kingdom</option>
                <option value="Canada">Canada</option>
                <option value="Germany">Germany</option>
                <option value="France">France</option>
                <option value="Australia">Australia</option>
              </select>
            </div>
              
            <div class="mb-3">
              <label for="state-select" class="form-label">State/Province <span class="text-danger">*</span></label>
              <select id="state-select" class="form-select" required>
                <option value="">Select State</option>
                <!-- States will be populated based on country selection -->
              </select>
            </div>
            
            <?php if ($currentUser): ?>
            <div class="mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="save-address" checked>
                <label class="form-check-label" for="save-address">
                  Set as default address
                </label>
              </div>
            </div>
            
            <div class="d-flex gap-2">
              <button type="button" class="btn btn-primary" id="save-address-btn">
                <i class="fas fa-save me-1"></i> Save Address
              </button>
              <button type="button" class="btn btn-secondary" id="cancel-new-address-btn">
                Cancel
              </button>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Checkout Button -->
      <?php if ($currentUser): ?>
      <button class="btn btn-success w-100 mb-4" id="checkout-btn" disabled>
        <i class="fas fa-credit-card me-2"></i> Proceed to Checkout
      </button>
      <?php else: ?>
      <div class="alert alert-warning mb-3">
        <p><i class="fas fa-exclamation-triangle me-2"></i> You must be logged in to checkout.</p>
      </div>
      <button class="btn btn-primary w-100 mb-2" id="login-to-checkout-btn">
        <i class="fas fa-sign-in-alt me-2"></i> Login to Checkout
      </button>
      <a href="/signup.php" class="btn btn-outline-secondary w-100 mb-4">
        <i class="fas fa-user-plus me-2"></i> Create Account
      </a>
      <?php endif; ?>
    </div>
  </div>
</main>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/components/account-modal.php'); ?>  
<?php include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php'); ?>

<script src="/js/cart-handler.js"></script>
<script src="/js/countries-states.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Initialize cart handler if not already done
  if (!window.cartHandler) {
      window.cartHandler = new CartHandler();
  }
    
  // Load and render cart
  loadCartItems();
  
  // Load addresses for logged-in users
  if (window.cartHandler && window.cartHandler.isLoggedIn) {
    loadUserAddresses();
  }
  
  // Initialize address form handlers
  initializeAddressHandlers();
  
  // Initialize country/state dropdowns
  initializeCountryStateDropdowns();
  
  // Initialize shipping progress
  updateShippingProgress();
    
  async function loadCartItems() {
      const cartContainer = document.getElementById('cart-items');
      if (!cartContainer) return;
        
      try {
          // Show loading
          cartContainer.innerHTML = '<div class="text-center py-4"><div class="spinner-border"></div><p>Loading cart...</p></div>';
            
          const cartItems = await window.cartHandler.getCart();
          console.log('Loaded cart items:', cartItems);
          console.log('User logged in:', window.cartHandler.isLoggedIn);
          console.log('Raw localStorage cart:', localStorage.getItem('DRFCart'));
          renderCartItems(cartItems);
            
      } catch (error) {
          console.error('Error loading cart:', error);
          cartContainer.innerHTML = '<div class="alert alert-danger">Error loading cart items</div>';
      }
  }
  
  // Make loadCartItems globally available
  window.loadCartItems = loadCartItems;
    
  function renderCartItems(items) {
      const cartContainer = document.getElementById('cart-items');
      const emptyCartMessage = document.getElementById('empty-cart-message');
      
      console.log('Rendering cart items:', items);
        
      if (!items || items.length === 0) {
          cartContainer.innerHTML = '';
          emptyCartMessage.style.display = 'block';
          document.getElementById('subtotal').textContent = '₦0.00';
          document.getElementById('accessories').textContent = '₦0.00';
          document.getElementById('total').textContent = '₦0.00';
           document.getElementById('checkout-btn').disabled = true;
          return;
      }
      
      emptyCartMessage.style.display = 'none';
      document.getElementById('checkout-btn').disabled = false;
        
      let html = '';
      let total = 0;
      let totalItems = 0;
        
      items.forEach(item => {
          console.log('Processing item:', item);
          
          // Normalize item data for both guest and user carts
          const normalizedItem = {
          id: item.product_id || item.id || item.cart_item_id || 'unknown',
          name: item.product_name || item.name || 'Unknown Product',
          price: parseFloat(item.price) || 0,
          image: item.image || '/images/placeholder.jpg',
          color: item.color || '',
          size: item.size || '',
          width: item.width || '',
          quantity: parseInt(item.quantity) || 1
          };
          
          // Validate that we have essential data
          if (!normalizedItem.name || normalizedItem.name === 'Unknown Product') {
            console.warn('Item missing name:', item);
            // Try alternative field names
            normalizedItem.name = item.title || item.productName || 'Product';
          }
          if (!normalizedItem.price) {
            console.warn('Item missing price:', item);
            normalizedItem.price = parseFloat(item.cost || item.amount || 0);
          }
          
          console.log('Normalized item:', normalizedItem);
          
          const itemTotal = normalizedItem.price * normalizedItem.quantity;
          total += itemTotal;
          totalItems += normalizedItem.quantity;
            
          html += `
              <div class="cart-item border-bottom py-3" data-item-id="${item.cart_item_id || normalizedItem.id}" data-product-id="${normalizedItem.id}" data-color="${normalizedItem.color}" data-size="${normalizedItem.size}" data-width="${normalizedItem.width}">
                  <div class="row align-items-center">
                      <div class="col-md-2">
                          <img src="${normalizedItem.image}" alt="${normalizedItem.name}" class="img-fluid rounded" style="max-height: 80px; object-fit: cover;">
                      </div>
                      <div class="col-md-4">
                          <h6 class="mb-1">${normalizedItem.name}</h6>
                          <small class="text-muted">
                          Color: ${normalizedItem.color} | Size: ${normalizedItem.size}${normalizedItem.width ? ` | Width: ${normalizedItem.width}` : ''}
                          </small>
                      </div>
                      <div class="col-md-2">
                      <span class="fw-bold">₦${normalizedItem.price.toFixed(2)}</span>
                      </div>
                      <div class="col-md-2">
                      <div class="input-group input-group-sm">
                      <button class="btn btn-outline-secondary quantity-btn" type="button" data-action="decrease">-</button>
                      <input type="number" class="form-control text-center quantity-input" value="${normalizedItem.quantity}" min="1" max="10">
                      <button class="btn btn-outline-secondary quantity-btn" type="button" data-action="increase">+</button>
                      </div>
                      </div>
                      <div class="col-md-1">
                      <span class="fw-bold item-total">₦${itemTotal.toFixed(2)}</span>
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
      
      // Add continue shopping button at the end
      if (items && items.length > 0) {
          html += `
              <div class="text-center mt-4 mb-3">
                  <a href="/products.php" class="btn btn-outline-primary">
                      <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                  </a>
              </div>
          `;
      }
        
      cartContainer.innerHTML = html;
        
      // Update cart summary
      const accessories = 0; // Will be dynamic later
      const finalTotal = total + accessories;
      
      document.getElementById('subtotal').textContent = `₦${total.toFixed(2)}`;
      document.getElementById('accessories').textContent = `₦${accessories.toFixed(2)}`;
       document.getElementById('total').textContent = `₦${finalTotal.toFixed(2)}`;
       
       // Update shipping progress after cart total changes
       updateShippingProgress();
        
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
              // Save customer info to localStorage before proceeding to checkout
              saveCustomerInfo();
              
              // Proceed to checkout
              window.location.href = '/checkout.php';
          });
      }
      
      // Login to checkout button
      const loginToCheckoutBtn = document.getElementById('login-to-checkout-btn');
      if (loginToCheckoutBtn) {
          loginToCheckoutBtn.addEventListener('click', function() {
              // Save the current URL as the redirect target after login
              sessionStorage.setItem('redirect_after_login', '/checkout.php');
              
              // Show login modal if available
              const loginModal = document.getElementById('loginModal');
              if (loginModal && typeof bootstrap !== 'undefined') {
                  const modal = new bootstrap.Modal(loginModal);
                  modal.show();
              } else {
                  // Redirect to login page if modal not available
                  window.location.href = '/login.php?redirect=' + encodeURIComponent('/checkout.php');
              }
          });
      }
  }
    
  async function updateCartItemQuantity(cartItem, newQuantity) {
      const itemId = cartItem.dataset.itemId;
      const productId = cartItem.dataset.productId;
      const isLoggedIn = window.cartHandler.isLoggedIn;
      
      console.log('Updating cart item:', {itemId, productId, newQuantity, isLoggedIn});
        
      try {
          if (isLoggedIn) {
              // Update in database using cart_item_id
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
          // Update in localStorage using product details
          let guestCart = window.cartHandler.getGuestCart();
          const color = cartItem.dataset.color;
          const size = cartItem.dataset.size;
          const width = cartItem.dataset.width;
          
          console.log('Guest cart update:', {guestCart, productId, color, size, width});
            
          const itemIndex = guestCart.findIndex(item => 
          (item.product_id === productId || item.id === productId) && 
          item.color === color && 
          item.size === size && 
          (item.width === width || (item.width === '' && width === ''))
          );
          
          console.log('Found item index:', itemIndex);
            
              if (itemIndex > -1) {
                   guestCart[itemIndex].quantity = newQuantity;
                   localStorage.setItem('DRFCart', JSON.stringify(guestCart));
                   console.log('Updated guest cart:', guestCart);
               } else {
                   console.error('Item not found in guest cart for update');
               }
           }
            
          // Update item total display
          const priceElement = cartItem.querySelector('.fw-bold');
          const price = parseFloat(priceElement.textContent.replace('₦', ''));
          const itemTotalElement = cartItem.querySelector('.item-total');
          itemTotalElement.textContent = `₦${(price * newQuantity).toFixed(2)}`;
            
          // Update cart count and summary
          window.cartHandler.updateCartCount();
          updateShippingProgress(); // Update shipping progress
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
      const productId = cartItem.dataset.productId;
      const isLoggedIn = window.cartHandler.isLoggedIn;
        
      try {
          if (isLoggedIn) {
              // Remove from database using cart_item_id
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
              // Remove from localStorage using product details
              let guestCart = window.cartHandler.getGuestCart();
              const color = cartItem.dataset.color;
              const size = cartItem.dataset.size;
              const width = cartItem.dataset.width;
                
              console.log('Guest cart remove:', {guestCart, productId, color, size, width});
              
              guestCart = guestCart.filter(item => 
              !((item.product_id === productId || item.id === productId) && 
              item.color === color && 
              item.size === size && 
              (item.width === width || (item.width === '' && width === '')))
              );
               
               console.log('Guest cart after remove:', guestCart);
                
              localStorage.setItem('DRFCart', JSON.stringify(guestCart));
          }
            
          // Remove item from display
          cartItem.remove();
            
          // Update cart count and reload items
          window.cartHandler.updateCartCount();
          updateShippingProgress(); // Update shipping progress
          loadCartItems();
            
      } catch (error) {
          console.error('Error removing item:', error);
          alert('Error removing item from cart');
      }
  }
  
  // Shipping Progress Functions
  const AFRICAN_COUNTRIES = [
    'Algeria', 'Angola', 'Benin', 'Botswana', 'Burkina Faso', 'Burundi', 'Cameroon', 
    'Cape Verde', 'Central African Republic', 'Chad', 'Comoros', 'Congo', 'Democratic Republic of the Congo',
    'Djibouti', 'Egypt', 'Equatorial Guinea', 'Eritrea', 'Eswatini', 'Ethiopia', 'Gabon', 
    'Gambia', 'Ghana', 'Guinea', 'Guinea-Bissau', 'Ivory Coast', 'Kenya', 'Lesotho', 
    'Liberia', 'Libya', 'Madagascar', 'Malawi', 'Mali', 'Mauritania', 'Mauritius', 
    'Morocco', 'Mozambique', 'Namibia', 'Niger', 'Nigeria', 'Rwanda', 'Sao Tome and Principe',
    'Senegal', 'Seychelles', 'Sierra Leone', 'Somalia', 'South Africa', 'South Sudan', 
    'Sudan', 'Tanzania', 'Togo', 'Tunisia', 'Uganda', 'Zambia', 'Zimbabwe'
  ];

  function getShippingThreshold(country, state) {
    if (country === 'Nigeria' && state === 'Lagos') {
      return 150000; // ₦150k for Lagos
    } else if (country === 'Nigeria') {
      return 250000; // ₦250k for other Nigerian states
    } else if (AFRICAN_COUNTRIES.includes(country)) {
      return 600000; // ₦600k for African countries
    } else {
      return 800000; // ₦800k for non-African countries
    }
  }

  function getShippingProgressColor(percentage) {
    if (percentage >= 100) {
      return 'bg-success'; // Green when complete
    } else if (percentage >= 75) {
      return 'bg-info'; // Blue when close
    } else if (percentage >= 50) {
      return 'bg-warning'; // Yellow when halfway
    } else if (percentage >= 25) {
      return 'bg-primary'; // Blue when started
    } else {
      return 'bg-secondary'; // Gray when just starting
    }
  }

  function updateShippingProgress() {
    const progressBar = document.getElementById('shipping-progress-bar');
    const progressText = document.getElementById('shipping-progress-text');
    const infoText = document.getElementById('shipping-info-text');
    
    if (!progressBar || !progressText) return;

    // Get current cart total
    const subtotalElement = document.getElementById('subtotal');
    let cartTotal = 0;
    if (subtotalElement) {
      cartTotal = parseFloat(subtotalElement.textContent.replace('₦', '').replace(',', '')) || 0;
    }

    // Get selected country and state
    const countrySelect = document.getElementById('country-select');
    const stateSelect = document.getElementById('state-select');
    
    let selectedCountry = 'Nigeria'; // Default
    let selectedState = 'Lagos'; // Default
    
    // For guests, always use Nigeria/Lagos as default unless form is filled
    if (countrySelect && countrySelect.value) {
      selectedCountry = countrySelect.value;
    }
    if (stateSelect && stateSelect.value) {
      selectedState = stateSelect.value;
    }
    
    // For guests without address form filled, show Lagos shipping
    const isGuest = !window.cartHandler || !window.cartHandler.isLoggedIn;
    if (isGuest && (!countrySelect || !countrySelect.value)) {
      selectedCountry = 'Nigeria';
      selectedState = 'Lagos';
    }

    const threshold = getShippingThreshold(selectedCountry, selectedState);
    const percentage = Math.min((cartTotal / threshold) * 100, 100);
    const remaining = Math.max(threshold - cartTotal, 0);

    // Update progress bar
    progressBar.style.width = percentage + '%';
    progressBar.setAttribute('aria-valuenow', percentage);
    
    // Update progress bar color
    progressBar.className = 'progress-bar ' + getShippingProgressColor(percentage);

    // Update text and shipping in order summary
    const shippingElement = document.getElementById('shipping');
    if (remaining > 0) {
    let locationText = selectedCountry;
    if (selectedCountry === 'Nigeria' && selectedState) {
      locationText = selectedState === 'Lagos' ? 'Lagos' : 'other Nigerian states';
    } else if (AFRICAN_COUNTRIES.includes(selectedCountry)) {
      locationText = 'other African countries';
    } else {
      locationText = 'international delivery';
    }
    
      progressText.innerHTML = `Add ₦${remaining.toLocaleString()} more for free shipping to ${locationText}`;
    if (shippingElement) {
      shippingElement.textContent = 'Depends on location';
      }
     } else {
       progressText.innerHTML = `🎉 You qualify for free shipping!`;
       progressText.className = 'text-success fw-bold';
       if (shippingElement) {
         shippingElement.textContent = 'Free';
         shippingElement.className = 'text-success fw-bold';
       }
     }

    // Update info text with current thresholds
    if (selectedCountry === 'Nigeria') {
      infoText.innerHTML = `📍 Current location: ${selectedState || 'Nigeria'} • Free shipping: ₦${threshold.toLocaleString()}+`;
    } else {
      infoText.innerHTML = `📍 Current location: ${selectedCountry} • Free shipping: ₦${threshold.toLocaleString()}+`;
    }
  }
  
  // Address Management Functions
  async function loadUserAddresses() {
    const addressesList = document.getElementById('addresses-list');
    if (!addressesList) return;
    
    try {
      const response = await fetch(`/api/addresses.php?user_id=${window.cartHandler.userId}`);
      const data = await response.json();
      
      if (data.success && data.addresses.length > 0) {
        renderAddresses(data.addresses);
      } else {
        addressesList.innerHTML = '<p class="text-muted">No saved addresses found.</p>';
      }
    } catch (error) {
      console.error('Error loading addresses:', error);
      addressesList.innerHTML = '<p class="text-danger">Error loading addresses.</p>';
    }
  }
  
  function renderAddresses(addresses) {
    const addressesList = document.getElementById('addresses-list');
    let html = '';
    
    addresses.forEach(address => {
      html += `
        <div class="address-item border rounded p-3 mb-2 ${address.is_default ? 'border-primary' : ''}" data-address-id="${address.address_id}">
          <div class="d-flex justify-content-between align-items-start">
            <div class="flex-grow-1">
              <div class="form-check">
                <input class="form-check-input address-select" type="radio" name="selected-address" value="${address.address_id}" ${address.is_default ? 'checked' : ''}>
                <label class="form-check-label">
                  <strong>${address.address_name || 'Home'}</strong> - ${address.full_name}
                  ${address.is_default ? '<span class="badge bg-primary ms-2">Default</span>' : ''}
                </label>
              </div>
              <div class="mt-2 ms-4">
                <p class="mb-1">${address.street_address}</p>
                <p class="mb-1">${address.city}, ${address.state}</p>
                <p class="mb-1"><strong>Country:</strong> ${address.country}</p>
                <p class="mb-0"><strong>Phone:</strong> ${address.phone}</p>
              </div>
            </div>
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-ellipsis-v"></i>
              </button>
              <ul class="dropdown-menu">
                ${!address.is_default ? '<li><a class="dropdown-item set-default-address" href="#" data-address-id="' + address.address_id + '">Set as Default</a></li>' : ''}
                <li><a class="dropdown-item delete-address text-danger" href="#" data-address-id="${address.address_id}">Delete</a></li>
              </ul>
            </div>
          </div>
        </div>
      `;
    });
    
    addressesList.innerHTML = html;
    
    // Add event listeners
    document.querySelectorAll('.set-default-address').forEach(btn => {
      btn.addEventListener('click', setDefaultAddress);
    });
    
    document.querySelectorAll('.delete-address').forEach(btn => {
      btn.addEventListener('click', deleteAddress);
    });
    
    document.querySelectorAll('.address-select').forEach(radio => {
      radio.addEventListener('change', function() {
        if (this.checked) {
          populateCheckoutForm(addresses.find(addr => addr.address_id == this.value));
        }
      });
    });
    
    // Auto-populate checkout form with default address
    const defaultAddress = addresses.find(addr => addr.is_default);
    if (defaultAddress) {
      populateCheckoutForm(defaultAddress);
    }
  }
  
  function populateCheckoutForm(address) {
    const addressNameInput = document.getElementById('address-name');
    if (addressNameInput) {
      addressNameInput.value = address.address_name || 'Home';
    }
    
    document.getElementById('client-name').value = address.full_name;
    document.getElementById('client-phone').value = address.phone;
    document.getElementById('shipping-address').value = address.street_address;
    document.getElementById('city-input').value = address.city;
    document.getElementById('country-select').value = address.country;
    
    // Populate states based on country, then set the state
    populateStates(address.country, 'state-select', address.state);
    
    // Update shipping progress after address is populated
    setTimeout(updateShippingProgress, 100);
  }
  
  function initializeAddressHandlers() {
    const addNewAddressBtn = document.getElementById('add-new-address-btn');
    const saveAddressBtn = document.getElementById('save-address-btn');
    const cancelNewAddressBtn = document.getElementById('cancel-new-address-btn');
    const addressForm = document.getElementById('address-form-fields');
    
    if (addNewAddressBtn) {
      addNewAddressBtn.addEventListener('click', function() {
        addressForm.style.display = 'block';
        // Clear form
        const addressNameInput = document.getElementById('address-name');
        if (addressNameInput) addressNameInput.value = 'Home';
        
        document.getElementById('client-name').value = '';
        document.getElementById('client-phone').value = '';
        document.getElementById('shipping-address').value = '';
        document.getElementById('city-input').value = '';
        document.getElementById('country-select').value = 'Nigeria';
        
        // Populate states for default country
        populateStates('Nigeria');
      });
    }
    
    if (cancelNewAddressBtn) {
      cancelNewAddressBtn.addEventListener('click', function() {
        addressForm.style.display = 'none';
      });
    }
    
    if (saveAddressBtn) {
      saveAddressBtn.addEventListener('click', saveNewAddress);
    }
  }
  
  async function saveNewAddress() {
    const addressNameInput = document.getElementById('address-name');
    const addressName = addressNameInput ? addressNameInput.value : 'Home';
    const fullName = document.getElementById('client-name').value.trim();
    const phone = document.getElementById('client-phone').value.trim();
    const streetAddress = document.getElementById('shipping-address').value.trim();
    const city = document.getElementById('city-input').value.trim();
    const country = document.getElementById('country-select').value;
    const state = document.getElementById('state-select').value;
    const isDefault = document.getElementById('save-address').checked;
    
    if (!fullName || !streetAddress || !city || !country || !state) {
      alert('Please fill in all required fields (marked with *)');
      return;
    }
    
    try {
      const response = await fetch('/api/addresses.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          action: 'add',
          user_id: window.cartHandler.userId,
          address_name: addressName,
          full_name: fullName,
          phone: phone,
          street_address: streetAddress,
          city: city,
          state: state,
          country: country,
          is_default: isDefault
        })
      });
      
      const data = await response.json();
      
      if (data.success) {
        alert('Address saved successfully!');
        document.getElementById('address-form-fields').style.display = 'none';
        loadUserAddresses(); // Reload addresses
      } else {
        alert('Error saving address: ' + data.message);
      }
    } catch (error) {
      console.error('Error saving address:', error);
      alert('Error saving address. Please try again.');
    }
  }
  
  async function setDefaultAddress(e) {
    e.preventDefault();
    const addressId = e.target.dataset.addressId;
    
    try {
      const response = await fetch('/api/addresses.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          action: 'set_default',
          user_id: window.cartHandler.userId,
          address_id: addressId
        })
      });
      
      const data = await response.json();
      
      if (data.success) {
        loadUserAddresses(); // Reload addresses
      } else {
        alert('Error setting default address: ' + data.message);
      }
    } catch (error) {
      console.error('Error setting default address:', error);
      alert('Error updating address. Please try again.');
    }
  }
  
  async function deleteAddress(e) {
    e.preventDefault();
    const addressId = e.target.dataset.addressId;
    
    if (!confirm('Are you sure you want to delete this address?')) {
      return;
    }
    
    try {
      const response = await fetch('/api/addresses.php', {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          address_id: addressId,
          user_id: window.cartHandler.userId
        })
      });
      
      const data = await response.json();
      
      if (data.success) {
        loadUserAddresses(); // Reload addresses
      } else {
        alert('Error deleting address: ' + data.message);
      }
    } catch (error) {
      console.error('Error deleting address:', error);
      alert('Error deleting address. Please try again.');
    }
  }
  
  // Debug function to check guest cart
  window.debugGuestCart = function() {
    const cart = localStorage.getItem('DRFCart');
    console.log('Raw guest cart:', cart);
    if (cart) {
      console.log('Parsed guest cart:', JSON.parse(cart));
    }
    console.log('Cart handler status:', {
      isLoggedIn: window.cartHandler.isLoggedIn,
      userId: window.cartHandler.userId
    });
  };
  
  // Add event listener to force shipping progress update when address form changes
  document.addEventListener('change', function(e) {
    if (e.target.id === 'country-select' || e.target.id === 'state-select') {
      updateShippingProgress();
    }
  });
  
  // Save customer information to localStorage
  function saveCustomerInfo() {
    // Only save if the form is visible (for guests or if user is adding a new address)
    const addressForm = document.getElementById('address-form-fields');
    if (addressForm && addressForm.style.display !== 'none') {
      const customerInfo = {
        name: document.getElementById('client-name').value.trim(),
        phone: document.getElementById('client-phone').value.trim(),
        address: document.getElementById('shipping-address').value.trim(),
        city: document.getElementById('city-input').value.trim(),
        state: document.getElementById('state-select').value,
        country: document.getElementById('country-select').value
      };
      
      // Only save if at least name and address are provided
      if (customerInfo.name && customerInfo.address) {
        localStorage.setItem('DRFCustomerInfo', JSON.stringify(customerInfo));
      }
    } else if (window.cartHandler && window.cartHandler.isLoggedIn) {
      // For logged-in users with selected address
      const selectedAddress = document.querySelector('.address-select:checked');
      if (selectedAddress) {
        const addressId = selectedAddress.value;
        localStorage.setItem('DRFSelectedAddressId', addressId);
      }
    }
  }
});
</script>
</body>
</html>
