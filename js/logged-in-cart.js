// Logged-in user cart functionality
document.addEventListener('DOMContentLoaded', function() {
  console.log('Logged-in cart script loaded');
  
  // Setup event listeners
  setupQuantityButtons();
  setupRemoveButtons();
  setupAddressSelection();
  setupCheckoutButton();
  updateShippingProgress();
  setupPaymentProofPreview();
  
  // Setup state select change event
  const stateSelect = document.getElementById('state-select');
  if (stateSelect) {
    stateSelect.addEventListener('change', updateShippingProgress);
  }
});

// Setup quantity buttons
function setupQuantityButtons() {
  document.querySelectorAll('.quantity-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const itemId = this.dataset.id;
      const action = this.dataset.action;
      updateQuantity(itemId, action);
    });
  });
}

// Setup remove buttons
function setupRemoveButtons() {
  document.querySelectorAll('.remove-item').forEach(btn => {
    btn.addEventListener('click', function() {
      const itemId = this.dataset.id;
      removeItem(itemId);
    });
  });
}

// Update item quantity
function updateQuantity(itemId, action) {
  // Get current quantity
  const quantityInput = document.querySelector(`.quantity-btn[data-id="${itemId}"]`).closest('.input-group').querySelector('.quantity-input');
  let quantity = parseInt(quantityInput.value);
  
  // Update quantity based on action
  if (action === 'increase') {
    quantity += 1;
  } else if (action === 'decrease' && quantity > 1) {
    quantity -= 1;
  } else {
    return; // Don't proceed if trying to decrease below 1
  }
  
  // Update in database
  fetch('/api/update-cart-item.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      item_id: itemId,
      quantity: quantity
    })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // Reload page to reflect changes
      window.location.reload();
    } else {
      console.error('Error updating quantity:', data.message);
      alert('Error updating quantity. Please try again.');
    }
  })
  .catch(error => {
    console.error('Error updating quantity:', error);
    alert('Error updating quantity. Please try again.');
  });
}

// Remove item from cart
function removeItem(itemId) {
  if (!confirm('Are you sure you want to remove this item from your cart?')) {
    return;
  }
  
  fetch('/api/remove-cart-item.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      item_id: itemId
    })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // Reload page to reflect changes
      window.location.reload();
    } else {
      console.error('Error removing item:', data.message);
      alert('Error removing item. Please try again.');
    }
  })
  .catch(error => {
    console.error('Error removing item:', error);
    alert('Error removing item. Please try again.');
  });
}

// Setup address selection
function setupAddressSelection() {
  const savedAddressesSelect = document.getElementById('saved-addresses');
  const newAddressBtn = document.getElementById('new-address-btn');
  const saveAddressOption = document.getElementById('save-address-option');
  
  if (savedAddressesSelect) {
    savedAddressesSelect.addEventListener('change', function() {
      const addressId = this.value;
      if (addressId) {
        loadAddress(addressId);
        if (saveAddressOption) saveAddressOption.style.display = 'none';
      } else {
        clearAddressForm();
        if (saveAddressOption) saveAddressOption.style.display = 'block';
      }
    });
    
    // Load default address if selected
    if (savedAddressesSelect.value) {
      loadAddress(savedAddressesSelect.value);
      if (saveAddressOption) saveAddressOption.style.display = 'none';
    }
  }
  
  if (newAddressBtn) {
    newAddressBtn.addEventListener('click', function() {
      if (savedAddressesSelect) {
        savedAddressesSelect.value = '';
      }
      clearAddressForm();
      if (saveAddressOption) saveAddressOption.style.display = 'block';
    });
  }
}

// Load address details
function loadAddress(addressId) {
  fetch(`/api/get-address.php?address_id=${addressId}`)
    .then(response => response.json())
    .then(data => {
      if (data.success && data.address) {
        const address = data.address;
        
        // Fill form fields
        document.getElementById('client-name').value = address.full_name || '';
        document.getElementById('client-phone').value = address.phone || '';
        document.getElementById('shipping-address').value = address.line1 || '';
        document.getElementById('state-select').value = address.state || 'Lagos';
        
        // Update shipping progress based on selected state
        updateShippingProgress();
      }
    })
    .catch(error => {
      console.error('Error loading address:', error);
    });
}

// Clear address form
function clearAddressForm() {
  document.getElementById('client-name').value = '';
  document.getElementById('client-phone').value = '';
  document.getElementById('shipping-address').value = '';
  document.getElementById('state-select').value = 'Lagos';
}

// Update shipping progress
function updateShippingProgress() {
  const subtotalElement = document.getElementById('subtotal');
  if (!subtotalElement) return;
  
  // Get subtotal value
  const subtotalText = subtotalElement.textContent;
  const subtotal = parseFloat(subtotalText.replace(/[^\d.]/g, ''));
  
  // Get selected state
  const stateSelect = document.getElementById('state-select');
  const selectedState = stateSelect ? stateSelect.value : 'Lagos';
  
  // Set threshold based on state
  const threshold = selectedState === 'Lagos' ? 150000 : 250000;
  
  // Update shipping hint
  const shippingHint = document.getElementById('shipping-hint');
  if (shippingHint) {
    shippingHint.textContent = `Free shipping on orders above ₦${threshold.toLocaleString()} ${selectedState === 'Lagos' ? 'within Lagos' : 'outside Lagos'}.`;
  }
  
  // Calculate progress percentage
  const progress = Math.min(100, (subtotal / threshold) * 100);
  
  // Update progress bar
  const progressBar = document.getElementById('shipping-progress');
  if (progressBar) {
    progressBar.style.width = `${progress}%`;
    
    // Remove all color classes
    progressBar.classList.remove('bg-danger', 'bg-warning', 'bg-info', 'bg-success');
    
    // Set color based on progress level
    if (progress >= 100) {
      progressBar.classList.add('bg-success');
    } else if (progress >= 75) {
      progressBar.classList.add('bg-info');
    } else if (progress >= 40) {
      progressBar.classList.add('bg-warning');
    } else {
      progressBar.classList.add('bg-danger');
    }
  }
  
  // Update progress label
  const progressLabel = document.getElementById('shipping-progress-label');
  if (progressLabel) {
    if (progress >= 100) {
      progressLabel.textContent = 'You qualify for free shipping!';
    } else {
      const remaining = threshold - subtotal;
      progressLabel.textContent = `Add ₦${remaining.toLocaleString()} more to qualify for free shipping`;
    }
  }
  
  // Update shipping cost
  const shippingElement = document.getElementById('shipping');
  if (shippingElement) {
    if (progress >= 100) {
      shippingElement.textContent = 'FREE';
    } else {
      const shippingCost = selectedState === 'Lagos' ? 2000 : 3500;
      shippingElement.textContent = `₦${shippingCost.toLocaleString()}`;
      
      // Update total with shipping
      const totalElement = document.getElementById('total');
      if (totalElement) {
        const total = subtotal + shippingCost;
        totalElement.textContent = `₦${total.toLocaleString()}`;
      }
    }
  }
}

// Setup payment proof preview
function setupPaymentProofPreview() {
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
}

// Setup checkout button
function setupCheckoutButton() {
  const checkoutBtn = document.getElementById('checkout-btn');
  if (checkoutBtn) {
    checkoutBtn.addEventListener('click', function() {
      handleCheckout();
    });
  }
}

// Handle checkout
function handleCheckout() {
  // Validate form
  const clientName = document.getElementById('client-name').value;
  const clientPhone = document.getElementById('client-phone').value;
  const shippingAddress = document.getElementById('shipping-address').value;
  const stateSelect = document.getElementById('state-select').value;
  const paymentProof = document.getElementById('payment-proof').files[0];
  
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
  const saveAddressCheckbox = document.getElementById('save-address-checkbox');
  const shouldSaveAddress = saveAddressCheckbox && saveAddressCheckbox.checked;
  
  // Create form data for file upload
  const formData = new FormData();
  formData.append('client_name', clientName);
  formData.append('client_phone', clientPhone);
  formData.append('shipping_address', shippingAddress);
  formData.append('state', stateSelect);
  formData.append('payment_proof', paymentProof);
  formData.append('save_address', shouldSaveAddress ? '1' : '0');
  
  // Submit order
  fetch('/api/create-order.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // Redirect to order confirmation page
      window.location.href = `/order-confirmation.php?order_id=${data.order_id}`;
    } else {
      console.error('Error creating order:', data.message);
      alert('Error creating order: ' + data.message);
    }
  })
  .catch(error => {
    console.error('Error creating order:', error);
    alert('Error creating order. Please try again.');
  });
}