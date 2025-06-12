// Cart page functionality
document.addEventListener('DOMContentLoaded', function() {
  console.log('Cart page script loaded');
  
  // Load cart items
  loadCartItems();
  
  // Setup checkout button
  const checkoutBtn = document.getElementById('checkout-btn');
  if (checkoutBtn) {
    checkoutBtn.addEventListener('click', handleCheckout);
  }
});

// Load cart items
function loadCartItems() {
  const cartContainer = document.getElementById('cart-items');
  const emptyCartMessage = document.getElementById('empty-cart-message');
  
  if (!cartContainer) return;
  
  // Check if user is logged in
  const isLoggedIn = document.body.hasAttribute('data-user-id');
  const userId = document.body.getAttribute('data-user-id');
  
  // Clear any existing content
  cartContainer.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Loading cart items...</p></div>';
  
  if (isLoggedIn && userId) {
    // For logged-in users, ONLY get cart from database
    // Clear any localStorage cart to prevent mixing
    localStorage.removeItem('DRFCart');
    
    fetch(`/api/get_cart.php?user_id=${userId}`)
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const cartItems = data.items || [];
          renderCartItems(cartItems);
        } else {
          console.error('Error loading cart:', data.message);
          cartContainer.innerHTML = '<div class="alert alert-danger">Failed to load cart items. Please try again.</div>';
        }
      })
      .catch(error => {
        console.error('Error loading cart:', error);
        cartContainer.innerHTML = '<div class="alert alert-danger">Failed to load cart items. Please try again.</div>';
      });
  } else {
    // For guests, ONLY get cart from localStorage
    const cartItems = JSON.parse(localStorage.getItem('DRFCart') || '[]');
    renderCartItems(cartItems);
  }
}

// Render cart items
function renderCartItems(cartItems) {
  const cartContainer = document.getElementById('cart-items');
  const emptyCartMessage = document.getElementById('empty-cart-message');
  const checkoutBtn = document.getElementById('checkout-btn');
  
  if (cartItems.length === 0) {
    // Show empty cart message
    cartContainer.style.display = 'none';
    if (emptyCartMessage) emptyCartMessage.style.display = 'block';
    if (checkoutBtn) checkoutBtn.disabled = true;
    return;
  }
  
  // Hide empty cart message
  if (emptyCartMessage) emptyCartMessage.style.display = 'none';
  if (checkoutBtn) checkoutBtn.disabled = false;
  
  // Generate HTML for cart items
  const cartItemsHtml = cartItems.map((item, index) => `
    <div class="card mb-3">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-md-2 col-4 mb-3 mb-md-0">
            <img src="${item.image || '/images/product-placeholder.jpg'}" class="img-fluid rounded" alt="${item.product_name || item.name || 'Product'}">
          </div>
          <div class="col-md-6 col-8 mb-3 mb-md-0">
            <h5 class="card-title">${item.product_name || item.name || 'Product'}</h5>
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
  
  // Update cart container
  cartContainer.innerHTML = cartItemsHtml;
  cartContainer.style.display = 'block';
  
  // Calculate totals
  const subtotal = cartItems.reduce((sum, item) => sum + (parseFloat(item.price) * item.quantity), 0);
  
  // Update summary
  document.getElementById('subtotal').textContent = `₦${subtotal.toLocaleString()}`;
  document.getElementById('total').textContent = `₦${subtotal.toLocaleString()}`;
  
  // Update shipping progress bar
  updateShippingProgress(subtotal);
  
  // Add event listeners
  document.querySelectorAll('.quantity-btn').forEach(btn => {
    btn.addEventListener('click', handleQuantityChange);
  });
  
  document.querySelectorAll('.remove-item').forEach(btn => {
    btn.addEventListener('click', handleRemoveItem);
  });
}

// Handle quantity change
function handleQuantityChange(event) {
  const index = parseInt(event.currentTarget.dataset.index);
  const action = event.currentTarget.dataset.action;
  
  // Check if user is logged in
  const isLoggedIn = document.body.hasAttribute('data-user-id');
  const userId = document.body.getAttribute('data-user-id');
  
  if (isLoggedIn && userId) {
    // For logged-in users, update in database
    fetch(`/api/get_cart.php?user_id=${userId}`)
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const cartItems = data.items || [];
          
          if (action === 'increase') {
            cartItems[index].quantity += 1;
          } else if (action === 'decrease' && cartItems[index].quantity > 1) {
            cartItems[index].quantity -= 1;
          }
          
          // Update database
          return fetch('/api/sync_cart.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              user_id: userId,
              cart_items: cartItems
            })
          });
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Reload cart items
          loadCartItems();
        }
      })
      .catch(error => {
        console.error('Error updating cart:', error);
      });
  } else {
    // For guests, update in localStorage
    const cartItems = JSON.parse(localStorage.getItem('DRFCart') || '[]');
    
    if (action === 'increase') {
      cartItems[index].quantity += 1;
    } else if (action === 'decrease' && cartItems[index].quantity > 1) {
      cartItems[index].quantity -= 1;
    }
    
    // Update localStorage
    localStorage.setItem('DRFCart', JSON.stringify(cartItems));
    
    // Reload cart items
    renderCartItems(cartItems);
  }
}

// Handle remove item
function handleRemoveItem(event) {
  const index = parseInt(event.currentTarget.dataset.index);
  
  // Check if user is logged in
  const isLoggedIn = document.body.hasAttribute('data-user-id');
  const userId = document.body.getAttribute('data-user-id');
  
  if (isLoggedIn && userId) {
    // For logged-in users, remove from database
    fetch(`/api/get_cart.php?user_id=${userId}`)
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const cartItems = data.items || [];
          
          // Remove item
          cartItems.splice(index, 1);
          
          // Update database
          return fetch('/api/sync_cart.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              user_id: userId,
              cart_items: cartItems
            })
          });
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Reload cart items
          loadCartItems();
        }
      })
      .catch(error => {
        console.error('Error removing item from cart:', error);
      });
  } else {
    // For guests, remove from localStorage
    const cartItems = JSON.parse(localStorage.getItem('DRFCart') || '[]');
    
    // Remove item
    cartItems.splice(index, 1);
    
    // Update localStorage
    localStorage.setItem('DRFCart', JSON.stringify(cartItems));
    
    // Reload cart items
    renderCartItems(cartItems);
  }
}

// Update shipping progress based on subtotal
function updateShippingProgress(subtotal) {
  const progressBar = document.getElementById('shipping-progress');
  const progressLabel = document.getElementById('shipping-progress-label');
  const shippingHint = document.getElementById('shipping-hint');
  const shippingElement = document.getElementById('shipping');
  
  if (!progressBar || !progressLabel) return;
  
  // Get selected state
  const stateSelect = document.getElementById('state-select');
  const selectedState = stateSelect ? stateSelect.value : 'Lagos';
  
  // Set threshold based on state
  const threshold = selectedState === 'Lagos' ? 150000 : 250000;
  
  // Update shipping hint text
  if (shippingHint) {
    shippingHint.textContent = `Free shipping on orders above ₦${threshold.toLocaleString()} ${selectedState === 'Lagos' ? 'within Lagos' : 'outside Lagos'}.`;
  }
  
  // Calculate progress percentage
  const progress = Math.min(100, (subtotal / threshold) * 100);
  
  // Update progress bar
  progressBar.style.width = `${progress}%`;
  
  // Remove all color classes
  progressBar.classList.remove('bg-danger', 'bg-warning', 'bg-info', 'bg-success');
  
  // Set shipping cost
  let shippingCost = 0;
  if (subtotal < threshold) {
    shippingCost = selectedState === 'Lagos' ? 2000 : 3500;
  }
  
  // Update shipping cost display
  if (shippingElement) {
    shippingElement.textContent = shippingCost > 0 ? `₦${shippingCost.toLocaleString()}` : 'FREE';
  }
  
  // Update total with shipping
  const totalElement = document.getElementById('total');
  if (totalElement) {
    const total = subtotal + shippingCost;
    totalElement.textContent = `₦${total.toLocaleString()}`;
  }
  
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

// Handle checkout
function handleCheckout() {
  // Implement checkout functionality
  alert('Checkout functionality will be implemented soon!');
}