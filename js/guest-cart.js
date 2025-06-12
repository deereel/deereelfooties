// Guest cart functionality - For non-logged-in users only
document.addEventListener('DOMContentLoaded', function() {
  console.log('Guest cart script loaded');
  
  // Load cart items from localStorage
  loadCartItems();
  
  // Setup event listeners
  setupQuantityButtons();
  setupRemoveButtons();
  setupStateSelect();
  setupCheckoutButton();
  
  // Setup file input preview
  const paymentProof = document.getElementById('payment-proof');
  const proofPdf = document.getElementById('proof-pdf');
  
  if (paymentProof && proofPdf) {
    paymentProof.addEventListener('change', function() {
      if (this.files && this.files[0]) {
        const file = this.files[0];
        if (file.type === 'application/pdf') {
          const reader = new FileReader();
          reader.onload = function(e) {
            proofPdf.src = e.target.result;
            proofPdf.style.display = 'block';
          };
          reader.readAsDataURL(file);
        } else {
          proofPdf.style.display = 'none';
        }
      }
    });
  }
});

// Load cart items from localStorage
function loadCartItems() {
  const cartContainer = document.getElementById('cart-items');
  const emptyCartMessage = document.getElementById('empty-cart-message');
  const checkoutBtn = document.getElementById('checkout-btn');
  
  // Get cart from localStorage
  const cart = JSON.parse(localStorage.getItem('DRFCart') || '[]');
  
  if (cart.length === 0) {
    // Show empty cart message
    cartContainer.innerHTML = '';
    emptyCartMessage.style.display = 'block';
    checkoutBtn.disabled = true;
    return;
  }
  
  // Hide empty cart message
  emptyCartMessage.style.display = 'none';
  checkoutBtn.disabled = false;
  
  // Generate HTML for cart items
  let cartItemsHtml = '';
  
  cart.forEach((item, index) => {
    cartItemsHtml += `
      <div class="card mb-3">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col-md-2 col-4 mb-3 mb-md-0">
              <img src="${item.image || '/images/product-placeholder.jpg'}" class="img-fluid rounded" alt="${item.name || 'Product'}">
            </div>
            <div class="col-md-6 col-8 mb-3 mb-md-0">
              <h5 class="card-title">${item.name || 'Product'}</h5>
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
    `;
  });
  
  // Update cart container
  cartContainer.innerHTML = cartItemsHtml;
  
  // Calculate totals
  updateCartTotals();
}

// Setup quantity buttons
function setupQuantityButtons() {
  document.addEventListener('click', function(e) {
    if (e.target.classList.contains('quantity-btn')) {
      const index = parseInt(e.target.dataset.index);
      const action = e.target.dataset.action;
      updateQuantity(index, action);
    }
  });
}

// Setup remove buttons
function setupRemoveButtons() {
  document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-item') || e.target.closest('.remove-item')) {
      const button = e.target.classList.contains('remove-item') ? e.target : e.target.closest('.remove-item');
      const index = parseInt(button.dataset.index);
      removeItem(index);
    }
  });
}

// Update item quantity
function updateQuantity(index, action) {
  // Get cart from localStorage
  const cart = JSON.parse(localStorage.getItem('DRFCart') || '[]');
  
  // Update quantity based on action
  if (action === 'increase') {
    cart[index].quantity += 1;
  } else if (action === 'decrease' && cart[index].quantity > 1) {
    cart[index].quantity -= 1;
  }
  
  // Save updated cart
  localStorage.setItem('DRFCart', JSON.stringify(cart));
  
  // Reload cart items
  loadCartItems();
}

// Remove item from cart
function removeItem(index) {
  // Get cart from localStorage
  const cart = JSON.parse(localStorage.getItem('DRFCart') || '[]');
  
  // Remove item
  cart.splice(index, 1);
  
  // Save updated cart
  localStorage.setItem('DRFCart', JSON.stringify(cart));
  
  // Reload cart items
  loadCartItems();
}

// Setup state select
function setupStateSelect() {
  const stateSelect = document.getElementById('state-select');
  if (stateSelect) {
    stateSelect.addEventListener('change', updateCartTotals);
  }
}

// Update cart totals
function updateCartTotals() {
  // Get cart from localStorage
  const cart = JSON.parse(localStorage.getItem('DRFCart') || '[]');
  
  // Calculate subtotal
  const subtotal = cart.reduce((sum, item) => sum + (parseFloat(item.price) * item.quantity), 0);
  
  // Update subtotal display
  const subtotalElement = document.getElementById('subtotal');
  if (subtotalElement) {
    subtotalElement.textContent = `₦${subtotal.toLocaleString()}`;
  }
  
  // Get selected state
  const stateSelect = document.getElementById('state-select');
  const selectedState = stateSelect ? stateSelect.value : 'Lagos';
  
  // Calculate shipping cost
  const threshold = selectedState === 'Lagos' ? 150000 : 250000;
  const shippingCost = subtotal >= threshold ? 0 : (selectedState === 'Lagos' ? 2000 : 3500);
  
  // Update shipping cost display
  const shippingElement = document.getElementById('shipping');
  if (shippingElement) {
    shippingElement.textContent = shippingCost > 0 ? `₦${shippingCost.toLocaleString()}` : 'FREE';
  }
  
  // Update total
  const totalElement = document.getElementById('total');
  if (totalElement) {
    const total = subtotal + shippingCost;
    totalElement.textContent = `₦${total.toLocaleString()}`;
  }
  
  // Update shipping progress bar
  updateShippingProgress(subtotal, threshold);
}

// Update shipping progress bar
function updateShippingProgress(subtotal, threshold) {
  const progressBar = document.getElementById('shipping-progress');
  const progressLabel = document.getElementById('shipping-progress-label');
  
  if (!progressBar || !progressLabel) return;
  
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

// Setup checkout button
function setupCheckoutButton() {
  const checkoutBtn = document.getElementById('checkout-btn');
  if (checkoutBtn) {
    checkoutBtn.addEventListener('click', function() {
      // Show login modal for guest users
      const loginModal = document.getElementById('loginModal');
      if (loginModal) {
        const bsModal = new bootstrap.Modal(loginModal);
        bsModal.show();
      } else {
        alert('Please sign in to complete your purchase');
      }
    });
  }
}