// Cart page functionality
document.addEventListener('DOMContentLoaded', function() {
  console.log('Cart page script loaded');
  loadCart();
  setupCustomerSection();
  
  // Setup checkout button
  const checkoutBtn = document.getElementById('checkout-btn');
  if (checkoutBtn) {
    checkoutBtn.addEventListener('click', handleCheckout);
  }
  
  // Setup state select change event
  const stateSelect = document.getElementById('state-select');
  if (stateSelect) {
    stateSelect.addEventListener('change', function() {
      const cart = JSON.parse(localStorage.getItem('DRFCart') || '[]');
      updateCartSummary(cart);
    });
  }
  
  // Setup new address button
  const newAddressBtn = document.getElementById('new-address-btn');
  if (newAddressBtn) {
    newAddressBtn.addEventListener('click', function() {
      document.getElementById('saved-addresses').value = '';
      clearAddressForm();
      document.getElementById('save-address-option').style.display = 'block';
    });
  }
  
  // Setup saved addresses dropdown
  const savedAddressesSelect = document.getElementById('saved-addresses');
  if (savedAddressesSelect) {
    savedAddressesSelect.addEventListener('change', function() {
      const selectedAddressId = this.value;
      if (selectedAddressId) {
        loadSelectedAddress(selectedAddressId);
      } else {
        clearAddressForm();
      }
    });
  }
});

// Setup customer information section based on login status
function setupCustomerSection() {
  const userData = localStorage.getItem('DRFUser');
  const userStatusIndicator = document.getElementById('user-status-indicator');
  const savedAddressesSection = document.getElementById('saved-addresses-section');
  const saveAddressOption = document.getElementById('save-address-option');
  
  if (userData) {
    // User is logged in
    const user = JSON.parse(userData);
    
    // Show user status
    if (userStatusIndicator) {
      userStatusIndicator.innerHTML = `
        <span class="badge bg-success">
          <i class="fas fa-user me-1"></i> Logged in
        </span>
      `;
    }
    
    // Show saved addresses section
    if (savedAddressesSection) {
      savedAddressesSection.style.display = 'block';
      loadSavedAddresses();
    }
    
    // Show save address option
    if (saveAddressOption) {
      saveAddressOption.style.display = 'block';
    }
    
    // Pre-fill name if available
    const clientNameInput = document.getElementById('client-name');
    if (clientNameInput && user.name) {
      clientNameInput.value = user.name;
    }
  } else {
    // User is not logged in
    if (userStatusIndicator) {
      userStatusIndicator.innerHTML = `
        <span class="badge bg-secondary">
          <i class="fas fa-user me-1"></i> Guest
        </span>
      `;
    }
    
    // Hide saved addresses section
    if (savedAddressesSection) {
      savedAddressesSection.style.display = 'none';
    }
    
    // Hide save address option
    if (saveAddressOption) {
      saveAddressOption.style.display = 'none';
    }
  }
}

// Load saved addresses for logged-in user
async function loadSavedAddresses() {
  const userData = localStorage.getItem('DRFUser');
  if (!userData) return;
  
  const user = JSON.parse(userData);
  const userId = user.user_id || user.id;
  const savedAddressesSelect = document.getElementById('saved-addresses');
  
  if (!savedAddressesSelect) return;
  
  try {
    const response = await fetch(`/api/get-addresses.php?user_id=${userId}`);
    
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    const data = await response.json();
    
    if (data.success && data.addresses && data.addresses.length > 0) {
      // Clear loading option
      savedAddressesSelect.innerHTML = '<option value="">Select a saved address</option>';
      
      // Add addresses to dropdown
      data.addresses.forEach(address => {
        const displayName = address.name || address.address_type || 'Address';
        const option = document.createElement('option');
        option.value = address.address_id;
        option.textContent = `${displayName} - ${address.city}, ${address.state}`;
        if (parseInt(address.is_default) === 1) {
          option.selected = true;
          loadSelectedAddress(address.address_id);
        }
        savedAddressesSelect.appendChild(option);
      });
    } else {
      savedAddressesSelect.innerHTML = '<option value="">No saved addresses</option>';
    }
  } catch (error) {
    console.error('Error loading saved addresses:', error);
    savedAddressesSelect.innerHTML = '<option value="">Error loading addresses</option>';
  }
}

// Load selected address details
async function loadSelectedAddress(addressId) {
  const userData = localStorage.getItem('DRFUser');
  if (!userData) return;
  
  const user = JSON.parse(userData);
  const userId = user.user_id || user.id;
  
  try {
    const response = await fetch(`/api/get-address.php?address_id=${addressId}&user_id=${userId}`);
    
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    const data = await response.json();
    
    if (data.success && data.address) {
      const address = data.address;
      
      // Fill form fields
      document.getElementById('client-name').value = address.full_name || '';
      document.getElementById('client-phone').value = address.phone || '';
      document.getElementById('shipping-address').value = address.line1 || '';
      document.getElementById('state-select').value = address.state || 'Lagos';
      
      // Hide save address option since it's already saved
      const saveAddressOption = document.getElementById('save-address-option');
      if (saveAddressOption) {
        saveAddressOption.style.display = 'none';
      }
      
      // Update shipping progress based on selected state
      const cart = JSON.parse(localStorage.getItem('DRFCart') || '[]');
      updateCartSummary(cart);
    }
  } catch (error) {
    console.error('Error loading address details:', error);
    alert('Failed to load address details. Please try again.');
  }
}

// Clear address form
function clearAddressForm() {
  document.getElementById('client-name').value = '';
  document.getElementById('client-phone').value = '';
  document.getElementById('shipping-address').value = '';
  document.getElementById('state-select').value = 'Lagos';
}

// Load cart items from localStorage or database
async function loadCart() {
  const cartContainer = document.getElementById('cart-items');
  const emptyCartMessage = document.getElementById('empty-cart-message');
  const checkoutBtn = document.getElementById('checkout-btn');
  
  if (!cartContainer) return;
  
  try {
    // Get cart data - first try from localStorage, then from database if user is logged in
    let cart = [];
    const userData = localStorage.getItem('DRFUser');
    
    if (userData) {
      // User is logged in, get cart from database
      const user = JSON.parse(userData);
      const userId = user.user_id || user.id;
      
      try {
        const response = await fetch(`/api/get_cart.php?user_id=${userId}`);
        if (response.ok) {
          const data = await response.json();
          if (data.success) {
            cart = data.items || [];
          }
        }
      } catch (error) {
        console.error('Error fetching cart from database:', error);
        // Fall back to localStorage
        cart = JSON.parse(localStorage.getItem('DRFCart') || '[]');
      }
    } else {
      // User is not logged in, get cart from localStorage
      cart = JSON.parse(localStorage.getItem('DRFCart') || '[]');
    }
    
    // Render cart items
    if (cart.length === 0) {
      cartContainer.style.display = 'none';
      emptyCartMessage.style.display = 'block';
      checkoutBtn.disabled = true;
    } else {
      renderCartItems(cart);
      updateCartSummary(cart);
      cartContainer.style.display = 'block';
      emptyCartMessage.style.display = 'none';
      checkoutBtn.disabled = false;
    }
  } catch (error) {
    console.error('Error loading cart:', error);
    cartContainer.innerHTML = '<div class="alert alert-danger">Failed to load cart items. Please try again.</div>';
  }
}

// Render cart items
function renderCartItems(cart) {
  const cartContainer = document.getElementById('cart-items');
  
  const cartItemsHtml = cart.map((item, index) => `
    <div class="card mb-3">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-md-2 col-4 mb-3 mb-md-0">
            <img src="${item.image || '/images/product-placeholder.jpg'}" class="img-fluid rounded" alt="${item.product_name}">
          </div>
          <div class="col-md-6 col-8 mb-3 mb-md-0">
            <h5 class="card-title">${item.product_name}</h5>
            <p class="card-text text-muted mb-1">
              ${item.color ? `<span class="me-2">Color: ${item.color}</span>` : ''}
              ${item.size ? `<span class="me-2">Size: ${item.size}</span>` : ''}
              ${item.width ? `<span>Width: ${item.width}</span>` : ''}
            </p>
            <p class="card-text text-primary">₦${parseFloat(item.price).toLocaleString()}</p>
          </div>
          <div class="col-md-2 col-6">
            <div class="input-group">
              <button class="btn btn-outline-secondary quantity-btn" type="button" data-action="decrease" data-index="${index}">-</button>
              <input type="text" class="form-control text-center quantity-input" value="${item.quantity}" readonly>
              <button class="btn btn-outline-secondary quantity-btn" type="button" data-action="increase" data-index="${index}">+</button>
            </div>
          </div>
          <div class="col-md-2 col-6 text-end">
            <p class="fw-bold mb-2">₦${(parseFloat(item.price) * item.quantity).toLocaleString()}</p>
            <button class="btn btn-sm btn-outline-danger remove-item" data-index="${index}">
              <i class="fas fa-trash-alt me-1"></i> Remove
            </button>
          </div>
        </div>
      </div>
    </div>
  `).join('');
  
  cartContainer.innerHTML = cartItemsHtml;
  
  // Add event listeners
  document.querySelectorAll('.quantity-btn').forEach(btn => {
    btn.addEventListener('click', handleQuantityChange);
  });
  
  document.querySelectorAll('.remove-item').forEach(btn => {
    btn.addEventListener('click', handleRemoveItem);
  });
}

// Update cart summary
function updateCartSummary(cart) {
  const subtotal = cart.reduce((sum, item) => sum + (parseFloat(item.price) * item.quantity), 0);
  const accessories = 0; // This will be updated later when accessories are added
  
  const total = subtotal + accessories; // Shipping not included in total calculation
  
  // Update summary elements
  document.getElementById('subtotal').textContent = `₦${subtotal.toLocaleString()}`;
  document.getElementById('accessories').textContent = accessories > 0 ? `₦${accessories.toLocaleString()}` : '₦0.00';
  document.getElementById('total').textContent = `₦${total.toLocaleString()}`;
  
  // Get selected state
  const stateSelect = document.getElementById('state-select');
  const selectedState = stateSelect ? stateSelect.value : 'Lagos'; // Default to Lagos if not selected
  
  // Update shipping progress bar based on selected state
  updateShippingProgress(subtotal, selectedState);
}

// Update shipping progress based on state
function updateShippingProgress(subtotal, state) {
  const progressBar = document.getElementById('shipping-progress');
  const progressLabel = document.getElementById('shipping-progress-label');
  const shippingHint = document.getElementById('shipping-hint');
  
  if (!progressBar || !progressLabel || !shippingHint) return;
  
  // Set threshold based on state
  const threshold = state === 'Lagos' ? 150000 : 250000;
  
  // Update shipping hint text
  shippingHint.textContent = `Free shipping on orders above ₦150,000 within Lagos and ₦250,000 outside Lagos.`;
  
  // Calculate progress percentage
  const progress = Math.min(100, (subtotal / threshold) * 100);
  
  // Update progress bar
  progressBar.style.width = `${progress}%`;
  
  // Remove all color classes
  progressBar.classList.remove('bg-danger', 'bg-warning', 'bg-info', 'bg-success');
  
  // Set color based on progress level
  if (progress >= 100) {
    // Full - green
    progressBar.classList.add('bg-success');
    progressLabel.textContent = 'You qualify for free shipping!';
  } else if (progress >= 75) {
    // Almost there - blue
    progressBar.classList.add('bg-info');
    const remaining = threshold - subtotal;
    progressLabel.textContent = `Almost there! Add ₦${remaining.toLocaleString()} more for free shipping`;
  } else if (progress >= 40) {
    // Average - yellow
    progressBar.classList.add('bg-warning');
    const remaining = threshold - subtotal;
    progressLabel.textContent = `Add ₦${remaining.toLocaleString()} more to qualify for free shipping`;
  } else {
    // Low - red
    progressBar.classList.add('bg-danger');
    const remaining = threshold - subtotal;
    progressLabel.textContent = `Add ₦${remaining.toLocaleString()} more to qualify for free shipping`;
  }
}

// Handle quantity change
function handleQuantityChange(event) {
  const index = parseInt(event.currentTarget.dataset.index);
  const action = event.currentTarget.dataset.action;
  
  // Get cart
  let cart = JSON.parse(localStorage.getItem('DRFCart') || '[]');
  
  if (action === 'increase') {
    cart[index].quantity += 1;
  } else if (action === 'decrease' && cart[index].quantity > 1) {
    cart[index].quantity -= 1;
  }
  
  // Save updated cart
  localStorage.setItem('DRFCart', JSON.stringify(cart));
  
  // Update UI
  renderCartItems(cart);
  updateCartSummary(cart);
  
  // Update cart in database if user is logged in
  syncCartWithDatabase(cart);
}

// Handle remove item
function handleRemoveItem(event) {
  const index = parseInt(event.currentTarget.dataset.index);
  
  // Get cart
  let cart = JSON.parse(localStorage.getItem('DRFCart') || '[]');
  
  // Remove item
  cart.splice(index, 1);
  
  // Save updated cart
  localStorage.setItem('DRFCart', JSON.stringify(cart));
  
  // Update UI
  if (cart.length === 0) {
    const cartContainer = document.getElementById('cart-items');
    const emptyCartMessage = document.getElementById('empty-cart-message');
    const checkoutBtn = document.getElementById('checkout-btn');
    
    cartContainer.style.display = 'none';
    emptyCartMessage.style.display = 'block';
    checkoutBtn.disabled = true;
  } else {
    renderCartItems(cart);
    updateCartSummary(cart);
  }
  
  // Update cart in database if user is logged in
  syncCartWithDatabase(cart);
}

// Handle checkout
function handleCheckout() {
  const clientName = document.getElementById('client-name').value;
  const clientPhone = document.getElementById('client-phone').value;
  const shippingAddress = document.getElementById('shipping-address').value;
  const stateSelect = document.getElementById('state-select').value;
  const paymentProof = document.getElementById('payment-proof').files[0];
  
  // Validate form
  if (!clientName) {
    alert('Please enter your full name');
    document.getElementById('client-name').focus();
    return;
  }
  
  if (!clientPhone) {
    alert('Please enter your phone number');
    document.getElementById('client-phone').focus();
    return;
  }
  
  if (!shippingAddress) {
    alert('Please enter your shipping address');
    document.getElementById('shipping-address').focus();
    return;
  }
  
  if (!stateSelect) {
    alert('Please select your state');
    document.getElementById('state-select').focus();
    return;
  }
  
  if (!paymentProof) {
    alert('Please upload your payment proof');
    document.getElementById('payment-proof').focus();
    return;
  }
  
  // Check if user wants to save address
  const userData = localStorage.getItem('DRFUser');
  const saveAddressCheckbox = document.getElementById('save-address-checkbox');
  
  if (userData && saveAddressCheckbox && saveAddressCheckbox.checked) {
    saveNewAddress();
  }
  
  // Process checkout
  // This will be implemented later
  alert('Checkout functionality will be implemented soon!');
}

// Save new address
async function saveNewAddress() {
  const userData = localStorage.getItem('DRFUser');
  if (!userData) return;
  
  const user = JSON.parse(userData);
  const userId = user.user_id || user.id;
  
  const addressData = {
    user_id: userId,
    name: 'Shipping Address',
    address_type: 'shipping',
    full_name: document.getElementById('client-name').value,
    phone: document.getElementById('client-phone').value,
    line1: document.getElementById('shipping-address').value,
    city: 'City', // Default city
    state: document.getElementById('state-select').value,
    country: 'Nigeria',
    is_default: 0
  };
  
  try {
    const response = await fetch('/api/save-address.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(addressData)
    });
    
    const data = await response.json();
    
    if (data.success) {
      console.log('Address saved successfully');
      // Reload saved addresses
      loadSavedAddresses();
    } else {
      console.error('Error saving address:', data.message);
    }
  } catch (error) {
    console.error('Error saving address:', error);
  }
}

// Sync cart with database
async function syncCartWithDatabase(cart) {
  const userData = localStorage.getItem('DRFUser');
  if (!userData) return;
  
  try {
    const user = JSON.parse(userData);
    const userId = user.user_id || user.id;
    
    await fetch('/api/sync_cart.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        user_id: userId,
        cart: cart
      })
    });
  } catch (error) {
    console.error('Error syncing cart with database:', error);
  }
}