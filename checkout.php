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

// Redirect to login page if not logged in (server-side check)
if (!$currentUser) {
  // Save the current URL as the redirect target after login
  $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
  
  // Redirect to login page
  header('Location: /login.php?message=' . urlencode('Please log in to proceed with checkout'));
  exit;
}

// Debug information
$debug = [
  'session_user' => isset($_SESSION['user']),
  'session_user_id' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null,
  'current_user' => $currentUser
];
// Log debug info
file_put_contents('checkout_debug.log', date('Y-m-d H:i:s') . ' - ' . json_encode($debug) . "\n", FILE_APPEND);
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
          <!-- Order Summary -->
          <div class="card p-4 mb-4">
            <h4 class="mb-3">Order Summary</h4>
            <div id="checkout-items">
              <!-- Items will be loaded here -->
              <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2">Loading your items...</p>
              </div>
            </div>
          </div>
          
          <!-- Customer Information -->
          <div class="card p-4 mb-4">
            <h4 class="mb-3">Customer Information</h4>
            <div id="customer-info">
              <!-- Customer info will be loaded here -->
              <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2">Loading customer information...</p>
              </div>
            </div>
          </div>
          
          <!-- Payment Options -->
          <div class="card p-4 mb-4">
            <h4 class="mb-3">Payment Options</h4>
            <div class="row">
              <div class="col-md-6 mb-3">
                <div class="card border-primary">
                  <div class="card-body text-center">
                    <h5 class="card-title text-primary">Pay Online</h5>
                    <p class="card-text">Pay securely with your card or bank account</p>
                    <button id="pay-online-btn" class="btn btn-primary w-100" disabled>
                      <i class="fas fa-credit-card me-2"></i>Pay Now
                    </button>
                  </div>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="card">
                  <div class="card-body text-center">
                    <h5 class="card-title">Bank Transfer</h5>
                    <p class="card-text">Transfer to our bank account and upload proof</p>
                    <button id="bank-transfer-btn" class="btn btn-outline-primary w-100">
                      <i class="fas fa-university me-2"></i>Bank Transfer
                    </button>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Bank Transfer Details (hidden by default) -->
            <div id="bank-transfer-details" class="mt-4" style="display: none;">
              <div class="alert alert-info">
                <p><strong>Please make payment to any of the following bank accounts:</strong></p>
                <div class="row mt-3">
                  <div class="col-md-6 mb-3">
                    <div class="card h-100">
                      <div class="card-body">
                        <h5 class="card-title">Opay Digital Bank</h5>
                        <p class="card-text mb-1"><strong>Account Number:</strong> 8134235110</p>
                        <p class="card-text mb-0"><strong>Account Name:</strong> Oladayo Quadri</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 mb-3">
                    <div class="card h-100">
                      <div class="card-body">
                        <h5 class="card-title">Stanbic IBTC Bank</h5>
                        <p class="card-text mb-1"><strong>Account Number:</strong> 0050379869</p>
                        <p class="card-text mb-0"><strong>Account Name:</strong> Oladayo Quadri</p>
                      </div>
                    </div>
                  </div>
                </div>
                <p class="mt-3 mb-0">After making payment, please upload your payment proof below.</p>
              </div>
            </div>
          </div>
          
          <!-- Payment Proof Upload -->
          <div class="card p-4 mb-4">
            <h4 class="mb-3">Upload Payment Proof</h4>
            <form id="payment-proof-form" enctype="multipart/form-data">
              <input type="hidden" id="order-id-input" name="order_id">
              <input type="hidden" id="user-id-input" name="user_id" value="<?php echo $currentUser ? $currentUser['id'] : ''; ?>">
              <input type="hidden" id="customer-name-input" name="customer_name" value="<?php echo $currentUser ? $currentUser['name'] : ''; ?>">
              
              <div class="mb-3">
                <label for="proof_image" class="form-label">Payment Proof</label>
                <input type="file" class="form-control" id="proof_image" name="proof_image" accept="image/jpeg,image/png,application/pdf" required>
                <div class="form-text">Accepted formats: JPG, PNG, PDF</div>
              </div>
              
              <div class="mb-3">
                <div id="image-preview" class="text-center d-none">
                  <p class="text-muted">Preview:</p>
                  <img id="preview-image" src="#" alt="Preview" class="img-fluid mb-2 rounded" style="max-height: 200px;">
                </div>
              </div>
              
              <button type="submit" class="btn btn-primary">Upload Payment Proof</button>
            </form>
          </div>
        </div>
        
        <div class="col-lg-4">
          <!-- Order Summary Sidebar -->
          <div class="card p-4 mb-4 sticky-top" style="top: 20px; z-index: 100;">
            <h4>Order Total</h4>
            <hr>
            <p class="mb-1">Subtotal: <span class="fw-bold">₦<span id="checkout-subtotal">0.00</span></span></p>
            <p class="mb-1">Shipping: <span id="checkout-shipping-text">Calculating...</span></p>
            <hr>
            <h5>Total: <span class="fw-bold">₦<span id="checkout-total">0.00</span></span></h5>
            
            <!-- Free Shipping Status -->
            <div class="mt-3" id="shipping-status">
              <div class="mb-2">
                <small id="shipping-progress-text" class="text-muted">
                  Loading shipping information...
                </small>
              </div>
              <div class="progress mb-2" style="height: 8px;">
                <div id="shipping-progress-bar" class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
            
            <div class="mt-4">
              <button id="place-order-btn" class="btn btn-success w-100">
                <i class="fas fa-check-circle me-2"></i> Place Order
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/account-modal.php'); ?>
  
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php'); ?>
  <script src="https://js.paystack.co/v1/inline.js"></script>
  <script src="/js/payment.js"></script>

  <!-- Checkout Page Script -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      if (typeof CartHandler === 'undefined') {
        console.error('CartHandler is not defined. Please ensure cart-handler.js is loaded.');
        return;
      }
      
      const cartHandler = new CartHandler();
      let orderId = null;
      
      // Populate checkout summary
      async function renderCheckoutSummary() {
        try {
          // Force refresh login status
          cartHandler.forceRefreshLoginStatus();
          console.log('Login status before getting cart:', cartHandler.isLoggedIn, 'User ID:', cartHandler.userId);
          
          // For guest users, get cart from localStorage directly
          let cartItems;
          if (!cartHandler.isLoggedIn) {
            cartItems = JSON.parse(localStorage.getItem('DRFCart') || '[]');
            console.log('Guest cart items from localStorage:', cartItems);
          } else {
            // For logged-in users, use the cart handler
            try {
              cartItems = await cartHandler.getCart();
              console.log('Logged-in user cart items:', cartItems);
            } catch (error) {
              console.error('Error getting logged-in user cart, falling back to localStorage:', error);
              cartItems = JSON.parse(localStorage.getItem('DRFCart') || '[]');
            }
          }
          
          const checkoutItems = document.getElementById('checkout-items');
          
          if (!cartItems || cartItems.length === 0) {
            console.log('No cart items found, redirecting to cart page');
            window.location.href = '/cart.php';
            return;
          }
          
          // Clear container
          let html = '';
          let subtotal = 0;
          
          // Create checkout items
          cartItems.forEach(item => {
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
            
            const itemTotal = normalizedItem.price * normalizedItem.quantity;
            subtotal += itemTotal;
            
            html += `
              <div class="d-flex mb-3 border-bottom pb-3">
                <div class="flex-shrink-0" style="width: 60px;">
                  <img src="${normalizedItem.image}" alt="${normalizedItem.name}" class="img-fluid rounded">
                </div>
                <div class="flex-grow-1 ms-3">
                  <h6 class="mb-1">${normalizedItem.name}</h6>
                  <p class="text-muted small mb-1">
                    ${normalizedItem.color ? `Color: ${normalizedItem.color}` : ''}
                    ${normalizedItem.size ? `Size: ${normalizedItem.size}` : ''}
                    ${normalizedItem.width ? `Width: ${normalizedItem.width}` : ''}
                  </p>
                  <div class="d-flex justify-content-between">
                    <span>${normalizedItem.quantity} × ₦${normalizedItem.price.toLocaleString()}</span>
                    <span class="fw-bold">₦${itemTotal.toLocaleString()}</span>
                  </div>
                </div>
              </div>
            `;
          });
          
          checkoutItems.innerHTML = html;
          
          // Update totals
          await updateOrderSummary(subtotal);
          
          return cartItems; // Return cart items for further processing
        } catch (error) {
          console.error('Error loading cart items:', error);
          // If there's an error, show error message instead of redirecting
          const checkoutItems = document.getElementById('checkout-items');
          if (checkoutItems) {
            checkoutItems.innerHTML = `
              <div class="alert alert-danger">
                <p>Error loading cart items. <a href="/cart.php">Return to cart</a></p>
              </div>
            `;
          }
          throw error; // Re-throw to be handled by caller
        }
        
        // Clear container
        let html = '';
        
        // The rendering code has been moved to the renderCheckoutSummary function
      }
      
      // Update order summary and shipping status
      async function updateOrderSummary(subtotal) {
        const shippingText = document.getElementById('checkout-shipping-text');
        const subtotalElement = document.getElementById('checkout-subtotal');
        const totalElement = document.getElementById('checkout-total');
        
        // Format subtotal
        subtotalElement.textContent = subtotal.toLocaleString();
        
        // Get customer country and state from shipping address
        const customerInfo = await getCustomerInfo();
        const country = customerInfo.shippingAddress ? customerInfo.shippingAddress.country : customerInfo.country;
        const state = customerInfo.shippingAddress ? customerInfo.shippingAddress.state : customerInfo.state;
        
        // Calculate shipping
        const { shipping, isFreeShipping } = calculateShipping(subtotal, country, state);
        
        // Update shipping text - match cart page logic
        if (isFreeShipping) {
          shippingText.textContent = 'Free';
          shippingText.className = 'text-success fw-bold';
        } else {
          shippingText.textContent = 'Depends on location';
          shippingText.className = 'fw-bold';
        }
        
        // Update total
        const total = subtotal + (isFreeShipping ? 0 : shipping);
        totalElement.textContent = total.toLocaleString();
        
        // Update shipping progress
        updateShippingProgress(subtotal, country, state);
      }
      
      // Calculate shipping based on subtotal and location
      function calculateShipping(subtotal, country, state) {
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
        
        let threshold = 150000; // Default for Lagos
        let shipping = 5000; // Default shipping cost
        
        if (country === 'Nigeria') {
          if (state === 'Lagos') {
            threshold = 150000;
            shipping = 5000;
          } else {
            threshold = 250000;
            shipping = 10000;
          }
        } else if (AFRICAN_COUNTRIES.includes(country)) {
          threshold = 600000;
          shipping = 30000;
        } else {
          threshold = 800000;
          shipping = 50000;
        }
        
        const isFreeShipping = subtotal >= threshold;
        
        return { shipping, isFreeShipping, threshold };
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
      
      // Update shipping progress bar
      function updateShippingProgress(subtotal, country, state) {
        const progressBar = document.getElementById('shipping-progress-bar');
        const progressText = document.getElementById('shipping-progress-text');
        
        if (!progressBar || !progressText) return;
        
        const { threshold, isFreeShipping } = calculateShipping(subtotal, country, state);
        const percentage = Math.min((subtotal / threshold) * 100, 100);
        const remaining = Math.max(threshold - subtotal, 0);
        
        // Update progress bar
        progressBar.style.width = percentage + '%';
        progressBar.setAttribute('aria-valuenow', percentage);
        
        // Update progress bar color
        progressBar.className = 'progress-bar ' + getShippingProgressColor(percentage);
        
        // Update text
        if (remaining > 0) {
          let locationText = country;
          if (country === 'Nigeria' && state) {
            locationText = state === 'Lagos' ? 'Lagos' : 'other Nigerian states';
          } else if (country && ['Algeria', 'Angola', 'Benin', 'Botswana', 'Burkina Faso', 'Burundi', 'Cameroon', 'Cape Verde', 'Central African Republic', 'Chad', 'Comoros', 'Congo', 'Democratic Republic of the Congo', 'Djibouti', 'Egypt', 'Equatorial Guinea', 'Eritrea', 'Eswatini', 'Ethiopia', 'Gabon', 'Gambia', 'Ghana', 'Guinea', 'Guinea-Bissau', 'Ivory Coast', 'Kenya', 'Lesotho', 'Liberia', 'Libya', 'Madagascar', 'Malawi', 'Mali', 'Mauritania', 'Mauritius', 'Morocco', 'Mozambique', 'Namibia', 'Niger', 'Nigeria', 'Rwanda', 'Sao Tome and Principe', 'Senegal', 'Seychelles', 'Sierra Leone', 'Somalia', 'South Africa', 'South Sudan', 'Sudan', 'Tanzania', 'Togo', 'Tunisia', 'Uganda', 'Zambia', 'Zimbabwe'].includes(country)) {
            locationText = 'other African countries';
          } else {
            locationText = 'international delivery';
          }
          
          progressText.innerHTML = `Add ₦${remaining.toLocaleString()} more for free shipping to ${locationText}`;
          progressText.className = 'text-muted';
        } else {
          progressText.innerHTML = '🎉 You qualify for free shipping!';
          progressText.className = 'text-success fw-bold';
        }
      }
      
      // Get customer information
      async function getCustomerInfo() {
        console.log('Getting customer information');
        // Default values
        let info = {
          name: '',
          email: '',
          phone: '',
          address: '',
          city: '',
          state: 'Lagos',
          country: 'Nigeria',
          addressLabel: ''
        };
        
        // Try to get from localStorage (saved during cart page)
        const savedInfo = localStorage.getItem('DRFCustomerInfo');
        console.log('Saved customer info from localStorage:', savedInfo);
        if (savedInfo) {
          try {
            const parsed = JSON.parse(savedInfo);
            info = { ...info, ...parsed };
            console.log('Parsed customer info:', info);
          } catch (e) {
            console.error('Error parsing customer info:', e);
          }
        }
        
        // For logged-in users, fetch the latest user data from the database
        if (cartHandler.isLoggedIn && cartHandler.userId) {
          console.log('User is logged in with ID:', cartHandler.userId);
          try {
            // Try the new combined API endpoint first
            const combinedResponse = await fetch(`/api/get-user-with-addresses.php?user_id=${cartHandler.userId}`);
            const combinedData = await combinedResponse.json();
            console.log('Combined user and address data:', combinedData);
            
            if (combinedData.success) {
              // Update user info
              if (combinedData.user) {
                const user = combinedData.user;
                info.name = user.name || info.name;
                info.email = user.email || info.email;
                info.userPhone = user.phone || info.userPhone; // Store user's phone separately
                console.log('Updated info with user data from combined API:', info);
              }
              
              // Update address info
              if (combinedData.addresses && combinedData.addresses.length > 0) {
                // Get selected address or default address
                const selectedAddressId = localStorage.getItem('DRFSelectedAddressId');
                let addressToUse = null;
                
                if (selectedAddressId) {
                  // Try to find the selected address
                  addressToUse = combinedData.addresses.find(addr => addr.address_id == selectedAddressId);
                }
                
                // If no selected address found, use default or first address
                if (!addressToUse) {
                  addressToUse = combinedData.addresses.find(addr => addr.is_default == 1) || combinedData.addresses[0];
                }
                
                if (addressToUse) {
                  console.log('Raw address data to use:', addressToUse);
                  
                  // Store shipping address separately
                  info.shippingAddress = {
                    full_name: addressToUse.full_name || addressToUse.name || '',
                    phone: addressToUse.phone || '',
                    address: addressToUse.street_address || addressToUse.line1 || addressToUse.address || '',
                    city: addressToUse.city || '',
                    state: addressToUse.state || '',
                    country: addressToUse.country || ''
                  };
                  
                  // Keep original address fields for backward compatibility
                  info.address = info.shippingAddress.address;
                  info.city = info.shippingAddress.city;
                  info.state = info.shippingAddress.state;
                  info.country = info.shippingAddress.country;
                  
                  // If we still don't have a phone number and user is logged in, try to get it from user data
                  if (!info.phone && cartHandler.isLoggedIn) {
                    try {
                      const userResponse = await fetch(`/api/user.php?user_id=${cartHandler.userId}`);
                      const userData = await userResponse.json();
                      if (userData.success && userData.data && userData.data.phone) {
                        info.phone = userData.data.phone;
                        console.log('Got phone from user data:', info.phone);
                      }
                    } catch (e) {
                      console.error('Error getting user phone:', e);
                    }
                  }
                  
                  // Store the address label if available
                  info.addressLabel = addressToUse.address_name || addressToUse.name || 'Shipping Address';
                  
                  // Save the selected address ID to localStorage
                  localStorage.setItem('DRFSelectedAddressId', addressToUse.address_id);
                  
                  console.log('Updated info with address from combined API:', info);
                  
                  // Verify that all required address fields are populated
                  const hasAllAddressFields = info.address && info.city && info.state && info.country;
                  if (!hasAllAddressFields) {
                    console.warn('Some address fields are still missing after update:', info);
                  }
                }
              } else {
                console.log('No addresses found in combined API response');
              }
            } else {
              // If combined API fails, fall back to separate API calls
              console.log('Combined API failed, falling back to separate calls');
              await fallbackToSeparateApiCalls(info);
            }
          } catch (e) {
            console.error('Error fetching combined user data:', e);
            
            // Fall back to separate API calls
            await fallbackToSeparateApiCalls(info);
          }
        } else {
          // For guest users, try to get from localStorage
          const userData = localStorage.getItem('DRFUser');
          console.log('User data from localStorage:', userData);
          if (userData) {
            try {
              const user = JSON.parse(userData);
              info.name = user.name || info.name;
              info.email = user.email || info.email;
              info.phone = user.phone || info.phone;
              console.log('Updated info with user data from localStorage:', info);
            } catch (e) {
              console.error('Error parsing user data from localStorage:', e);
            }
          }
        }
        
        console.log('Final customer info:', info);
        return info;
      }
      
      // Fallback to separate API calls for user data and address
      async function fallbackToSeparateApiCalls(info) {
        try {
          // Fetch user data from the database
          const response = await fetch(`/api/user.php?user_id=${cartHandler.userId}`);
          const userData = await response.json();
          console.log('User data from API:', userData);
          
          if (userData.success && userData.data) {
            const user = userData.data;
            info.name = user.name || info.name;
            info.email = user.email || info.email;
            info.phone = user.phone || info.phone;
            console.log('Updated info with user data from API:', info);
          }
          
          // For logged-in users, try to get selected address
          const selectedAddressId = localStorage.getItem('DRFSelectedAddressId');
          console.log('Selected address ID:', selectedAddressId);
          if (selectedAddressId) {
            console.log('Attempting to load selected address:', selectedAddressId);
            // Load the address details from the database
            const addressLoaded = await loadSelectedAddress(selectedAddressId, info);
            console.log('Selected address loaded:', addressLoaded);
            
            if (!addressLoaded) {
              console.log('Selected address not found, trying default address');
              // If selected address failed to load, try default address
              await loadDefaultAddress(info);
            }
          } else {
            console.log('No selected address ID, trying to load default address');
            // If no address is selected, try to get the default address
            await loadDefaultAddress(info);
          }
        } catch (e) {
          console.error('Error in fallback API calls:', e);
          
          // Fallback to localStorage if API fails
          const userData = localStorage.getItem('DRFUser');
          console.log('Fallback: User data from localStorage:', userData);
          if (userData) {
            try {
              const user = JSON.parse(userData);
              info.name = user.name || info.name;
              info.email = user.email || info.email;
              info.phone = user.phone || info.phone;
              console.log('Updated info with user data from localStorage:', info);
            } catch (e) {
              console.error('Error parsing user data from localStorage:', e);
            }
          }
        }
      }
      
      // Load selected address for logged-in users
      async function loadSelectedAddress(addressId, info) {
        try {
          console.log(`Loading address ID ${addressId} for user ${cartHandler.userId}`);
          const response = await fetch(`/api/get-address.php?address_id=${addressId}&user_id=${cartHandler.userId}`);
          const data = await response.json();
          console.log('Address API response:', data);
          
          if (data.success && data.address) {
            const address = data.address;
            console.log('Raw address data:', address);
            
            // Update customer info with address details - handle all possible field names
            info.address = address.street_address || address.line1 || address.address || info.address;
            info.city = address.city || info.city;
            info.state = address.state || info.state;
            info.country = address.country || info.country;
            
            // Make sure phone is populated - try all possible field names
            info.phone = address.phone || address.full_name || info.phone;
            
            // If we still don't have a phone number and user is logged in, try to get it from user data
            if (!info.phone && cartHandler.isLoggedIn) {
              try {
                const userResponse = await fetch(`/api/user.php?user_id=${cartHandler.userId}`);
                const userData = await userResponse.json();
                if (userData.success && userData.data && userData.data.phone) {
                  info.phone = userData.data.phone;
                  console.log('Got phone from user data:', info.phone);
                }
              } catch (e) {
                console.error('Error getting user phone:', e);
              }
            }
            
            // Store the address label if available
            info.addressLabel = address.address_name || address.name || address.label || 'Selected Address';
            
            console.log('Updated customer info with address:', info);
            
            // Update the customer info display
            renderCustomerInfo();
            return true;
          } else {
            console.error('Failed to load address or address not found');
            
            // Try direct API call to user_addresses table as fallback
            try {
              console.log('Trying direct query to user_addresses table');
              const directResponse = await fetch(`/api/user_addresses.php?address_id=${addressId}`);
              const directData = await directResponse.json();
              console.log('Direct address API response:', directData);
              
              if (directData.success && directData.data) {
                const address = directData.data;
                
                // Update customer info with address details
                info.address = address.street_address || address.line1 || address.address || info.address;
                info.city = address.city || info.city;
                info.state = address.state || info.state;
                info.country = address.country || info.country;
                
                // Make sure phone is populated - try all possible field names
                info.phone = address.phone || address.full_name || info.phone;
                
                // If we still don't have a phone number and user is logged in, try to get it from user data
                if (!info.phone && cartHandler.isLoggedIn) {
                  try {
                    const userResponse = await fetch(`/api/user.php?user_id=${cartHandler.userId}`);
                    const userData = await userResponse.json();
                    if (userData.success && userData.data && userData.data.phone) {
                      info.phone = userData.data.phone;
                      console.log('Got phone from user data:', info.phone);
                    }
                  } catch (e) {
                    console.error('Error getting user phone:', e);
                  }
                }
                
                // Store the address label if available
                info.addressLabel = address.address_name || address.name || address.label || 'Selected Address';
                
                console.log('Updated customer info with direct address query:', info);
                
                // Update the customer info display
                renderCustomerInfo();
                return true;
              }
            } catch (fallbackError) {
              console.error('Error with fallback address query:', fallbackError);
            }
            
            return false;
          }
        } catch (error) {
          console.error('Error loading selected address:', error);
          return false;
        }
      }
      
      // Load default address for logged-in users
      async function loadDefaultAddress(info) {
        try {
          console.log(`Loading default address for user ${cartHandler.userId}`);
          
          // Try multiple API endpoints to find an address
          const apiEndpoints = [
            `/api/get-default-address.php?user_id=${cartHandler.userId}`,
            `/api/addresses.php?user_id=${cartHandler.userId}`,
            `/api/user_addresses.php?user_id=${cartHandler.userId}`
          ];
          
          let addressFound = false;
          
          for (const endpoint of apiEndpoints) {
            if (addressFound) break;
            
            try {
              console.log(`Trying endpoint: ${endpoint}`);
              const response = await fetch(endpoint);
              const data = await response.json();
              console.log(`Response from ${endpoint}:`, data);
              
              // Handle different response formats
              if (data.success) {
                let addressToUse = null;
                
                // Different APIs return addresses in different formats
                if (data.address) {
                  // get-default-address.php format
                  addressToUse = data.address;
                } else if (data.addresses && data.addresses.length > 0) {
                  // addresses.php format
                  addressToUse = data.addresses[0];
                } else if (data.data) {
                  // user_addresses.php format (array or single object)
                  addressToUse = Array.isArray(data.data) ? data.data[0] : data.data;
                }
                
                if (addressToUse) {
                  console.log('Found address to use:', addressToUse);
                  
                  console.log('Raw address data to use:', addressToUse);
                  
                  // Update customer info with address details - handle all possible field names
                  info.address = addressToUse.street_address || addressToUse.line1 || addressToUse.address || info.address;
                  info.city = addressToUse.city || info.city;
                  info.state = addressToUse.state || info.state;
                  info.country = addressToUse.country || info.country;
                  
                  // Make sure phone is populated - try all possible field names
                  info.phone = addressToUse.phone || addressToUse.full_name || info.phone;
                  
                  // If we still don't have a phone number and user is logged in, try to get it from user data
                  if (!info.phone && cartHandler.isLoggedIn) {
                    try {
                      const userResponse = await fetch(`/api/user.php?user_id=${cartHandler.userId}`);
                      const userData = await userResponse.json();
                      if (userData.success && userData.data && userData.data.phone) {
                        info.phone = userData.data.phone;
                        console.log('Got phone from user data:', info.phone);
                      }
                    } catch (e) {
                      console.error('Error getting user phone:', e);
                    }
                  }
                  
                  // Store the address label if available
                  info.addressLabel = addressToUse.address_name || addressToUse.name || 'Shipping Address';
                  
                  // Save the selected address ID to localStorage if available
                  if (addressToUse.address_id) {
                    localStorage.setItem('DRFSelectedAddressId', addressToUse.address_id);
                  }
                  
                  console.log('Updated customer info with address:', info);
                  
                  // Verify that all required address fields are populated
                  const hasAllAddressFields = info.address && info.city && info.state && info.country;
                  if (!hasAllAddressFields) {
                    console.warn('Some address fields are still missing after update:', info);
                  } else {
                    addressFound = true;
                    break;
                  }
                }
              }
            } catch (endpointError) {
              console.error(`Error with endpoint ${endpoint}:`, endpointError);
              // Continue to next endpoint
            }
          }
          
          // If we found an address, update the UI
          if (addressFound) {
            renderCustomerInfo();
            return true;
          }
          
          console.error('No address found after trying all endpoints');
          return false;
        } catch (error) {
          console.error('Error in loadDefaultAddress:', error);
          return false;
        }
      }
      
      // Render customer information
      async function renderCustomerInfo() {
        const customerInfo = await getCustomerInfo();
        const customerInfoContainer = document.getElementById('customer-info');
        
        console.log('Rendering customer info:', customerInfo);
        
        // Check if essential information is missing
        const isMissingName = !customerInfo.name;
        const isMissingAddress = !customerInfo.address || !customerInfo.city || !customerInfo.state || !customerInfo.country;
        const isMissingPhone = !customerInfo.phone;
        const isMissingInfo = isMissingName || isMissingAddress || isMissingPhone;
        
        console.log('Missing info check:', { isMissingName, isMissingAddress, isMissingPhone, isMissingInfo });
        
        let html = '';
        
        if (isMissingInfo) {
          let missingItems = [];
          if (isMissingName) missingItems.push('name');
          if (isMissingPhone) missingItems.push('phone number');
          if (isMissingAddress) missingItems.push('complete shipping address');
          
          const missingText = missingItems.join(', ');
          
          html += `
            <div class="alert alert-warning mb-3">
              <p><i class="fas fa-exclamation-triangle me-2"></i> The following information is missing: <strong>${missingText}</strong></p>
              <p>Please <a href="/cart.php" class="alert-link">return to the cart page</a> to provide your shipping information.</p>
            </div>
          `;
        }
        
        // If we have a user ID but no phone, try to get it directly from the session
        if (cartHandler.isLoggedIn && cartHandler.userId && !customerInfo.phone) {
          try {
            const sessionResponse = await fetch('/api/get_session_data.php');
            const sessionData = await sessionResponse.json();
            if (sessionData.success && sessionData.user && sessionData.user.phone) {
              customerInfo.phone = sessionData.user.phone;
              console.log('Got phone from session:', customerInfo.phone);
            }
          } catch (e) {
            console.error('Error getting session data:', e);
          }
        }
        
        html += `
          <div class="row">
            <div class="col-md-6 mb-3">
              <p class="mb-1"><strong>Name:</strong></p>
              <p>${customerInfo.name || 'Not provided'}</p>
            </div>
            <div class="col-md-6 mb-3">
              <p class="mb-1"><strong>Email:</strong></p>
              <p>${customerInfo.email || 'Not provided'}</p>
            </div>
            <div class="col-md-6 mb-3">
              <p class="mb-1"><strong>Phone:</strong></p>
              <p>${customerInfo.userPhone || customerInfo.phone || 'Not provided'}</p>
              ${!customerInfo.userPhone && !customerInfo.phone ? '<p class="text-danger small">Please provide your phone number on the cart page</p>' : ''}
            </div>
            <div class="col-md-6 mb-3">
              <p class="mb-1"><strong>User ID:</strong></p>
              <p>${cartHandler.userId || 'Guest Checkout'}</p>
            </div>
          </div>
        `;
        
        // Add shipping details section with a heading
        html += `
          <div class="row">
            <div class="col-12">
              <h5 class="mb-2">Shipping Address</h5>
            </div>
            <div class="col-md-12 mb-3">
              <div class="card bg-light">
                <div class="card-body">
                  ${customerInfo.addressLabel ? `<p class="mb-1 text-primary"><em>${customerInfo.addressLabel}</em></p>` : ''}
                  ${customerInfo.shippingAddress && customerInfo.shippingAddress.full_name ? `<p class="mb-1"><strong>${customerInfo.shippingAddress.full_name}</strong></p>` : (customerInfo.name ? `<p class="mb-1"><strong>${customerInfo.name}</strong></p>` : '')}
                  ${customerInfo.shippingAddress && customerInfo.shippingAddress.phone ? `<p class="mb-1">${customerInfo.shippingAddress.phone}</p>` : ''}
                  ${customerInfo.address ? `<p class="mb-1">${customerInfo.address}</p>` : '<p class="mb-1 text-danger">Address not provided</p>'}
                  <p class="mb-0">
                    ${customerInfo.city || 'City not provided'}${customerInfo.state ? ', ' + customerInfo.state : ''}${customerInfo.country ? ', ' + customerInfo.country : ''}
                  </p>
                </div>
              </div>
            </div>
          </div>
        `;
        
        // For logged-in users, show address selection info
        if (cartHandler.isLoggedIn) {
          html += `
            <div class="row mt-2">
              <div class="col-12">
                <small class="text-muted">
                  <i class="fas fa-info-circle me-1"></i> 
                  ${localStorage.getItem('DRFSelectedAddressId') ? 'Using selected shipping address.' : 'Using default shipping address.'} 
                  <a href="/cart.php" class="text-decoration-underline">Change address</a>
                </small>
              </div>
            </div>
          `;
        }
        
        customerInfoContainer.innerHTML = html;
        
        // Update customer name in the payment form
        const customerNameInput = document.getElementById('customer-name-input');
        if (customerNameInput && customerInfo.name) {
          customerNameInput.value = customerInfo.name;
        }
        
        // Disable place order button if information is missing
        const placeOrderBtn = document.getElementById('place-order-btn');
        if (placeOrderBtn) {
          placeOrderBtn.disabled = isMissingInfo;
        }
      }
      
      // Initialize image preview functionality
      function initImagePreview() {
        const fileInput = document.getElementById('proof_image');
        const imagePreview = document.getElementById('image-preview');
        const previewImage = document.getElementById('preview-image');
        
        if (fileInput) {
          fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
              const file = this.files[0];
              
              // Only show preview for images, not PDFs
              if (file.type.match('image.*')) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                  previewImage.src = e.target.result;
                  imagePreview.classList.remove('d-none');
                }
                
                reader.readAsDataURL(file);
              } else {
                // Hide preview for non-image files
                imagePreview.classList.add('d-none');
              }
            }
          });
        }
      }
      
      // Handle place order button
      async function handlePlaceOrder() {
        const customerInfo = await getCustomerInfo();
        console.log('Customer info for order:', customerInfo);
        
        // Force refresh login status
        cartHandler.forceRefreshLoginStatus();
        console.log('Login status before placing order:', cartHandler.isLoggedIn, 'User ID:', cartHandler.userId);
        
        // Get cart items
        let cartItems;
        let subtotal = 0;
        
        try {
          // For guest users, get cart from localStorage directly
          if (!cartHandler.isLoggedIn) {
            cartItems = JSON.parse(localStorage.getItem('DRFCart') || '[]');
            console.log('Guest cart items for order:', cartItems);
            // Calculate subtotal manually for guest users
            subtotal = cartItems.reduce((sum, item) => {
              const price = parseFloat(item.price) || 0;
              const quantity = parseInt(item.quantity) || 1;
              return sum + (price * quantity);
            }, 0);
          } else {
            // For logged-in users, try cart handler first, fallback to localStorage
            try {
              cartItems = await cartHandler.getCart();
              console.log('Logged-in user cart items for order:', cartItems);
            } catch (error) {
              console.error('Error getting logged-in user cart, falling back to localStorage:', error);
              cartItems = JSON.parse(localStorage.getItem('DRFCart') || '[]');
            }
            
            // Calculate subtotal manually for consistency
            subtotal = cartItems.reduce((sum, item) => {
              const price = parseFloat(item.price) || 0;
              const quantity = parseInt(item.quantity) || 1;
              return sum + (price * quantity);
            }, 0);
          }
          
          console.log('Cart items for order:', cartItems);
          console.log('Calculated subtotal:', subtotal);
          
          if (!cartItems || cartItems.length === 0) {
            alert('Your cart is empty. Please add items to your cart before checking out.');
            window.location.href = '/cart.php';
            return;
          }
        } catch (error) {
          console.error('Error getting cart items:', error);
          alert('Error loading your cart. Please try again.');
          return;
        }
        
        const { shipping, isFreeShipping } = calculateShipping(subtotal, customerInfo.country, customerInfo.state);
        const total = subtotal + (isFreeShipping ? 0 : shipping);
        
        // Validate customer info
        console.log('Validating customer info:', customerInfo);
        if (!customerInfo.name) {
          alert('Please provide your name before placing your order.');
          return;
        }
        
        if (!customerInfo.address || !customerInfo.city || !customerInfo.state || !customerInfo.country) {
          alert('Please provide complete shipping address before placing your order.');
          return;
        }
        
        if (!customerInfo.phone) {
          alert('Please provide your phone number before placing your order.');
          return;
        }
        
        // Create order data
        const orderData = {
          customer_name: customerInfo.name,
          email: customerInfo.email || '',
          phone: customerInfo.phone || '',
          address: customerInfo.address,
          city: customerInfo.city,
          state: customerInfo.state,
          country: customerInfo.country,
          items: cartItems,
          subtotal: subtotal,
          shipping: isFreeShipping ? 0 : shipping,
          total: total,
          payment_method: 'bank_transfer',
          user_id: cartHandler.isLoggedIn ? cartHandler.userId : null
        };
        
        console.log('Order data:', orderData);
        
        try {
          // Submit order
          const response = await fetch('/api/create-order.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify(orderData)
          });
          
          const data = await response.json();
          
          if (data.success) {
            // Store order ID for payment proof upload
            orderId = data.order_id;
            document.getElementById('order-id-input').value = orderId;
            
            // Show success message
            alert('Order placed successfully! Please upload your payment proof to complete your order.');
            
            // Disable place order button and enable online payment
            document.getElementById('place-order-btn').disabled = true;
            document.getElementById('pay-online-btn').disabled = false;
            
            // Scroll to payment proof section
            document.querySelector('.card:nth-child(4)').scrollIntoView({ behavior: 'smooth' });
            
            // Clear cart based on user type
            if (cartHandler.isLoggedIn) {
              // For logged-in users
              try {
                await fetch('/api/cart.php', {
                  method: 'DELETE',
                  headers: {
                    'Content-Type': 'application/json'
                  },
                  body: JSON.stringify({
                    action: 'clear',
                    user_id: cartHandler.userId
                  })
                });
              } catch (error) {
                console.error('Error clearing user cart:', error);
              }
            } else {
              // For guest users
              localStorage.removeItem('DRFCart');
            }
            
            // Update cart count
            if (typeof cartHandler.updateCartCount === 'function') {
              cartHandler.updateCartCount();
            }
          } else {
            alert('Error creating order: ' + (data.error || 'Unknown error'));
          }
        } catch (error) {
          console.error('Error creating order:', error);
          alert('An error occurred while processing your order. Please try again.');
        }
      }
      
      // Handle payment proof form submission
      async function handlePaymentProofSubmit(e) {
        e.preventDefault();
        
        // Check if order ID exists
        if (!orderId) {
          alert('Please place your order first before uploading payment proof.');
          return;
        }
        
        const formData = new FormData(document.getElementById('payment-proof-form'));
        
        try {
          const response = await fetch('/api/payment_proof.php', {
            method: 'POST',
            body: formData
          });
          
          const data = await response.json();
          
          if (data.success) {
            alert('Payment proof uploaded successfully! Your order is now being processed.');
            
            // Redirect to order confirmation page
            window.location.href = `/order-confirmation.php?order_id=${orderId}`;
          } else {
            alert('Error uploading payment proof: ' + data.message);
          }
        } catch (error) {
          console.error('Error uploading payment proof:', error);
          alert('An error occurred while uploading your payment proof. Please try again.');
        }
      }
      
      // Initialize page
      async function initPage() {
        try {
          // Force refresh login status first
          cartHandler.forceRefreshLoginStatus();
          console.log('Login status on page load:', cartHandler.isLoggedIn, 'User ID:', cartHandler.userId);
          
          // Require login for checkout
          if (!cartHandler.isLoggedIn) {
            // Show login required message
            document.querySelector('.container.my-5').innerHTML = `
              <div class="alert alert-warning">
                <h4 class="alert-heading">Login Required</h4>
                <p>You must be logged in to proceed with checkout.</p>
                <hr>
                <p class="mb-0">
                  <button id="checkout-login-btn" class="btn btn-primary me-2">Login</button>
                  <a href="/cart.php" class="btn btn-outline-secondary">Return to Cart</a>
                </p>
              </div>
            `;
            
            // Add event listener for login button
            document.getElementById('checkout-login-btn').addEventListener('click', function() {
              // Show login modal if available
              const loginModal = document.getElementById('loginModal');
              if (loginModal && typeof bootstrap !== 'undefined') {
                const modal = new bootstrap.Modal(loginModal);
                modal.show();
              } else {
                // Redirect to login page if modal not available
                window.location.href = '/login.php?redirect=' + encodeURIComponent(window.location.href);
              }
            });
            
            return; // Stop initialization
          }
          
          // Debug: Check if we can get addresses directly
          if (cartHandler.isLoggedIn && cartHandler.userId) {
            try {
              console.log('Attempting direct address fetch for debugging');
              const addressResponse = await fetch(`/api/addresses.php?user_id=${cartHandler.userId}`);
              const addressData = await addressResponse.json();
              console.log('DEBUG - Direct address fetch result:', addressData);
            } catch (e) {
              console.error('DEBUG - Error fetching addresses directly:', e);
            }
          }
          
          // Load cart items and customer info in parallel
          await Promise.all([
            renderCheckoutSummary(),
            renderCustomerInfo()
          ]);
          
          initImagePreview();
        } catch (error) {
          console.error('Error initializing checkout page:', error);
        }
      }
      
      // Start initialization
      initPage();
      
      // Add event listeners
      document.getElementById('place-order-btn').addEventListener('click', handlePlaceOrder);
      document.getElementById('payment-proof-form').addEventListener('submit', handlePaymentProofSubmit);
      
      // Payment option handlers
      document.getElementById('pay-online-btn').addEventListener('click', function() {
        if (orderId) {
          const customerInfo = getCustomerInfo();
          const total = parseFloat(document.getElementById('checkout-total').textContent.replace(/,/g, ''));
          paymentHandler.payWithPaystack(customerInfo.email, total, orderId);
        } else {
          alert('Please place your order first.');
        }
      });
      
      document.getElementById('bank-transfer-btn').addEventListener('click', function() {
        const details = document.getElementById('bank-transfer-details');
        details.style.display = details.style.display === 'none' ? 'block' : 'none';
      });
    });
  </script>
</body>
</html>