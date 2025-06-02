document.addEventListener('DOMContentLoaded', function() {
  console.log('Cart page loaded');
  
  // Initialize cart functionality
  initCart();
  
  function initCart() {
    console.log('Initializing cart');
    
    // Load cart items from localStorage
    const cart = loadCart();
    
    // Render cart items
    renderCart(cart);
    
    // Setup shipping address section based on login status
    setTimeout(() => {
      setupShippingAddressSection();
    }, 100);
    
    // Load saved form data
    loadFormData();
    
    // Add event listeners
    setupEventListeners(cart);

    // Setup payment proof preview
    setupPaymentProofPreview();
  }
  
  function loadCart() {
    // Check if user is logged in
    if (window.app && window.app.auth && window.app.auth.isLoggedIn()) {
      const user = window.app.auth.getCurrentUser();
      const userCartKey = `DRFCart_${user.user_id || user.id || user.email}`;
      const cartData = localStorage.getItem(userCartKey);
      console.log('Loading user cart:', userCartKey);
      return cartData ? JSON.parse(cartData) : [];
    } else {
      // Load guest cart
      const cartData = localStorage.getItem('DRFCart');
      console.log('Loading guest cart');
      return cartData ? JSON.parse(cartData) : [];
    }
  }
  
  function saveCart(cart) {
    // Check if user is logged in
    if (window.app && window.app.auth && window.app.auth.isLoggedIn()) {
      const user = window.app.auth.getCurrentUser();
      const userCartKey = `DRFCart_${user.user_id || user.id || user.email}`;
      localStorage.setItem(userCartKey, JSON.stringify(cart));
      console.log('Saving user cart:', userCartKey);
    } else {
      // Save as guest cart
      localStorage.setItem('DRFCart', JSON.stringify(cart));
      console.log('Saving guest cart');
    }
  }

  function clearCart() {
    if (window.app && window.app.auth && window.app.auth.isLoggedIn()) {
      const user = window.app.auth.getCurrentUser();
      const userCartKey = `DRFCart_${user.user_id || user.id || user.email}`;
      localStorage.removeItem(userCartKey);
    } else {
      localStorage.removeItem('DRFCart');
    }
  }
  
  // Setup shipping address section based on login status
  function setupShippingAddressSection() {
    const shippingContainer = document.getElementById('shipping-container');
    if (!shippingContainer) {
      console.error('Shipping container not found');
      return;
    }
    
    console.log('Setting up shipping container');
    
    // Check if user is logged in
    const isLoggedIn = window.app && window.app.auth && window.app.auth.isLoggedIn();
    console.log('User logged in:', isLoggedIn);
    
    if (isLoggedIn) {
      const user = window.app.auth.getCurrentUser();
      console.log('Current user:', user);
      
      // Clear existing content
      shippingContainer.innerHTML = '<p>Loading addresses...</p>';
      
      // Fetch user addresses
      const userId = user.user_id || user.id;
      console.log('Fetching addresses for user ID:', userId);
      
      fetch(`/api/addresses.php?user_id=${userId}`)
        .then(response => response.json())
        .then(data => {
          console.log('Addresses response:', data);
          
          // Create address dropdown with "Add New Address" option
          let addressesHTML = `
            <label for="address-select" class="block text-sm font-medium mb-1">Select Shipping Address</label>
            <select id="address-select" class="w-full border border-gray-300 rounded px-3 py-2">
              <option value="new">+ Add New Address</option>
          `;
          
          // Add saved addresses if available
          if (data.success && data.data && data.data.length > 0) {
            const addresses = data.data;
            console.log('User has addresses:', addresses.length);
            
            addresses.forEach(address => {
              addressesHTML += `
                <option value="${address.address_id}" ${address.is_default ? 'selected' : ''}>
                  ${address.address_name}: ${address.full_name}, ${address.street_address}, ${address.city}, ${address.state}
                </option>
              `;
            });
          }
          
          addressesHTML += `</select>`;
          
          // Add new address form (initially hidden)
          addressesHTML += `
            <div id="new-address-form" class="mt-4" style="display: none;">
              <h4 class="font-medium mb-3">Add New Address</h4>
              
              <div class="mb-3">
                <label for="new-address-name" class="block text-sm font-medium mb-1">Address Name</label>
                <input type="text" id="new-address-name" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Home, Work, etc.">
              </div>
              
              <div class="mb-3">
                <label for="new-full-name" class="block text-sm font-medium mb-1">Full Name</label>
                <input type="text" id="new-full-name" class="w-full border border-gray-300 rounded px-3 py-2" required>
              </div>
              
              <div class="mb-3">
                <label for="new-phone" class="block text-sm font-medium mb-1">Phone Number</label>
                <input type="tel" id="new-phone" class="w-full border border-gray-300 rounded px-3 py-2">
              </div>
              
              <div class="mb-3">
                <label for="new-street-address" class="block text-sm font-medium mb-1">Street Address</label>
                <textarea id="new-street-address" rows="2" class="w-full border border-gray-300 rounded px-3 py-2" required></textarea>
              </div>
              
              <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                  <label for="new-city" class="block text-sm font-medium mb-1">City</label>
                  <input type="text" id="new-city" class="w-full border border-gray-300 rounded px-3 py-2" required>
                </div>
                
                <div>
                  <label for="new-state" class="block text-sm font-medium mb-1">State</label>
                  <select id="new-state" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    <option value="">Select State</option>
                    <option value="Lagos">Lagos</option>
                    <option value="Abuja">Abuja</option>
                    <option value="Rivers">Rivers</option>
                    <option value="Kano">Kano</option>
                    <option value="Oyo">Oyo</option>
                    <option value="Kaduna">Kaduna</option>
                    <option value="Enugu">Enugu</option>
                    <option value="Abia">Abia</option>
                    <option value="Adamawa">Adamawa</option>
                    <option value="Akwa Ibom">Akwa Ibom</option>
                    <option value="Anambra">Anambra</option>
                    <option value="Bauchi">Bauchi</option>
                    <option value="Bayelsa">Bayelsa</option>
                    <option value="Benue">Benue</option>
                    <option value="Borno">Borno</option>
                    <option value="Cross River">Cross River</option>
                    <option value="Delta">Delta</option>
                    <option value="Ebonyi">Ebonyi</option>
                    <option value="Edo">Edo</option>
                    <option value="Ekiti">Ekiti</option>
                    <option value="Gombe">Gombe</option>
                    <option value="Imo">Imo</option>
                    <option value="Jigawa">Jigawa</option>
                    <option value="Katsina">Katsina</option>
                    <option value="Kebbi">Kebbi</option>
                    <option value="Kogi">Kogi</option>
                    <option value="Kwara">Kwara</option>
                    <option value="Nasarawa">Nasarawa</option>
                    <option value="Niger">Niger</option>
                    <option value="Ogun">Ogun</option>
                    <option value="Ondo">Ondo</option>
                    <option value="Osun">Osun</option>
                    <option value="Plateau">Plateau</option>
                    <option value="Sokoto">Sokoto</option>
                    <option value="Taraba">Taraba</option>
                    <option value="Yobe">Yobe</option>
                    <option value="Zamfara">Zamfara</option>
                  </select>
                </div>
              </div>
              
              <div class="mb-3">
                <label for="new-country" class="block text-sm font-medium mb-1">Country</label>
                <input type="text" id="new-country" class="w-full border border-gray-300 rounded px-3 py-2" value="Nigeria" required>
              </div>
              
              <div class="mb-3">
                <label class="flex items-center">
                  <input type="checkbox" id="save-to-profile" class="mr-2">
                  <span>Save to my address book</span>
                </label>
              </div>

              <button type="button" id="use-new-address" class="btn-primary px-4 py-2">Use This Address</button>

            </div>
          `;
          
          shippingContainer.innerHTML = addressesHTML;

          // Add event listener to the select
          const addressSelect = document.getElementById('address-select');
          const newAddressForm = document.getElementById('new-address-form');

          if (addressSelect) {
            addressSelect.addEventListener('change', function() {
              const selectedValue = this.value;
              
              if (selectedValue === 'new') {
                // Show new address form
                newAddressForm.style.display = 'block';
              } else {
                // Hide new address form
                newAddressForm.style.display = 'none';
                
                if (selectedValue) {
                  // Find selected address
                  const selectedAddress = data.success && data.data ? 
                    data.data.find(addr => addr.address_id == selectedValue) : null;
                  
                  if (selectedAddress) {
                    // Update state selection
                    const stateSelect = document.getElementById('state');
                    if (stateSelect) {
                      stateSelect.value = selectedAddress.state;
                      
                      // Trigger state change event to update shipping
                      const event = new Event('change');
                      stateSelect.dispatchEvent(event);
                    }
                  }
                }
              }
              
              // Save form data
              saveFormData();
            });
            
            // Trigger change event to set initial state
            const event = new Event('change');
            addressSelect.dispatchEvent(event);
          }
          
          // Add event listener to the "Use This Address" button
          const useNewAddressBtn = document.getElementById('use-new-address');
          if (useNewAddressBtn) {
            useNewAddressBtn.addEventListener('click', function() {
              // Validate form
              const fullName = document.getElementById('new-full-name').value;
              const streetAddress = document.getElementById('new-street-address').value;
              const city = document.getElementById('new-city').value;
              const state = document.getElementById('new-state').value;
              const country = document.getElementById('new-country').value;
              
              if (!fullName || !streetAddress || !city || !state || !country) {
                alert('Please fill in all required fields');
                return;
              }
              
              // Update main state select
              const mainStateSelect = document.getElementById('state');
              if (mainStateSelect) {
                mainStateSelect.value = state;
                
                // Trigger state change event to update shipping
                const event = new Event('change');
                mainStateSelect.dispatchEvent(event);
              }
              
              // Check if we should save to profile
              const saveToProfile = document.getElementById('save-to-profile').checked;
              if (saveToProfile) {
                // Save address to user profile
                const addressData = {
                  user_id: user.user_id || user.id,
                  address_name: document.getElementById('new-address-name').value || 'Home',
                  full_name: fullName,
                  phone: document.getElementById('new-phone').value,
                  street_address: streetAddress,
                  city: city,
                  state: state,
                  country: country,
                  is_default: false
                };
                
                fetch('/api/addresses.php', {
                  method: 'POST',
                  headers: {
                    'Content-Type': 'application/json'
                  },
                  body: JSON.stringify(addressData)
                })
                .then(response => response.json())
                .then(data => {
                  if (data.success) {
                    alert('Address saved to your profile');
                    // Reload shipping section to show the new address
                    setupShippingAddressSection();
                  } else {
                    alert('Error saving address: ' + data.message);
                  }
                })
                .catch(error => {
                  console.error('Error saving address:', error);
                  alert('Error saving address. Please try again.');
                });
              } else {
                // Just use the address for this order without saving
                alert('Using this address for your order');
              }
            });
          }
        })
        .catch(error => {
          console.error('Error fetching addresses:', error);
          shippingContainer.innerHTML = `
            <div class="alert alert-danger">
              <p>Error loading addresses. Please try again later.</p>
            </div>
          `;
        });
    } else {
      console.log('User is not logged in, showing regular address input');
      // Not logged in, show regular address input
      shippingContainer.innerHTML = `
        <label for="shipping-address" class="block text-sm font-medium mb-1">Shipping Address</label>
        <textarea id="shipping-address" rows="3" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Enter your complete address"></textarea>
      `;
      
      // Add event listener to the textarea
      const shippingAddress = document.getElementById('shipping-address');
      if (shippingAddress) {
        shippingAddress.addEventListener('input', saveFormData);
      }
    }
  }
  
  // Save form data to localStorage
  function saveFormData() {
    const clientName = document.getElementById('client-name')?.value || '';
    const shippingAddress = document.getElementById('shipping-address')?.value || '';
    const addressSelect = document.getElementById('address-select')?.value || '';
    const state = document.getElementById('state')?.value || '';
    
    const formData = {
      clientName,
      shippingAddress,
      addressSelect,
      state
    };
    
    localStorage.setItem('DRFCartFormData', JSON.stringify(formData));
  }
  
  // Load form data from localStorage
  function loadFormData() {
    const savedFormData = localStorage.getItem('DRFCartFormData');
    if (savedFormData) {
      const formData = JSON.parse(savedFormData);
      
      const clientNameInput = document.getElementById('client-name');
      const shippingAddressInput = document.getElementById('shipping-address');
      const addressSelect = document.getElementById('address-select');
      const stateSelect = document.getElementById('state');
      
      if (clientNameInput && formData.clientName) {
        clientNameInput.value = formData.clientName;
      }
      
      if (shippingAddressInput && formData.shippingAddress) {
        shippingAddressInput.value = formData.shippingAddress;
      }
      
      if (addressSelect && formData.addressSelect) {
        addressSelect.value = formData.addressSelect;
        
        // Trigger change event
        const event = new Event('change');
        addressSelect.dispatchEvent(event);
      }
      
      if (stateSelect && formData.state) {
        stateSelect.value = formData.state;
        
        // Trigger state change to update shipping calculation
        const event = new Event('change');
        stateSelect.dispatchEvent(event);
      }
    }
  }
  
  function renderCart(cart) {
    const cartItemsContainer = document.getElementById('cart-items');
    const emptyCartMessage = document.getElementById('empty-cart-message');
    const cartSummary = document.getElementById('cart-summary');
    const continueShoppingContainer = document.getElementById('continue-shopping-container');
    
    if (!cartItemsContainer || !emptyCartMessage || !cartSummary) return;
    
    // Show/hide elements based on cart content
    if (cart.length === 0) {
      cartItemsContainer.innerHTML = '';
      emptyCartMessage.style.display = 'block';
      cartSummary.style.display = 'none';
      if (continueShoppingContainer) continueShoppingContainer.style.display = 'none';
      return;
    }
    
    // Hide empty cart message, show summary and continue shopping button
    emptyCartMessage.style.display = 'none';
    cartSummary.style.display = 'block';
    if (continueShoppingContainer) continueShoppingContainer.style.display = 'block';
    
    // Build cart items HTML
    let cartHTML = '';
    let subtotal = 0;
    let accessoriesTotal = 0;
    
    cart.forEach((item, index) => {
      const itemTotal = item.price * item.quantity;
      
      // Check if item is an accessory
      if (item.type === 'accessory') {
        accessoriesTotal += itemTotal;
      } else {
        subtotal += itemTotal;
      }
      
      // Check if item is custom
      const isCustom = item.isCustom || false;
      const materialInfo = item.materialName || item.leatherType || '';
      
      cartHTML += `
        <div class="flex flex-col md:flex-row border-b py-4 gap-4">
          <div class="w-full md:w-24 h-24 flex-shrink-0">
            <img src="${item.image}" alt="${item.name}" class="w-full h-full object-cover">
          </div>
          <div class="flex-grow">
            <div class="flex items-start justify-between mb-2">
              <h3 class="font-medium text-primary">${item.name}</h3>
              ${isCustom ? '<span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium ml-2">CUSTOM</span>' : ''}
            </div>
            <div class="text-muted text-sm space-y-1">
              <p>Size: ${item.size} | Width: ${item.width} | Color: ${item.color}</p>
              ${materialInfo ? `<p><strong>Material:</strong> ${materialInfo}</p>` : ''}
              ${isCustom && item.styleName ? `<p><strong>Style:</strong> ${item.styleName}</p>` : ''}
            </div>
            <div class="flex items-center mt-2">
              <button class="update-quantity border px-2" data-index="${index}" data-action="decrease">-</button>
              <span class="px-3">${item.quantity}</span>
              <button class="update-quantity border px-2" data-index="${index}" data-action="increase">+</button>
            </div>
          </div>
          <div class="flex flex-col items-end">
            <p class="font-medium">₦${itemTotal.toLocaleString()}</p>
            ${item.quantity > 1 ? `<p class="text-xs text-gray-500">₦${item.price.toLocaleString()} each</p>` : ''}
            <button class="text-accent text-sm remove-item mt-2" data-index="${index}">Remove</button>
          </div>
        </div>
      `;
    });
    
    cartItemsContainer.innerHTML = cartHTML;
    
    // Update order summary
    updateOrderSummary(subtotal, accessoriesTotal);
  }
  
  function updateOrderSummary(subtotal, accessoriesTotal) {
    const subtotalElement = document.getElementById('cart-subtotal');
    const accessoriesElement = document.getElementById('cart-accessories');
    const shippingElement = document.getElementById('cart-shipping');
    const totalElement = document.getElementById('cart-total');
    
    if (!subtotalElement || !shippingElement || !totalElement) return;
    
    console.log('Updating order summary:', { subtotal, accessoriesTotal });
    
    // Update subtotal
    subtotalElement.textContent = `₦${subtotal.toLocaleString()}`;
    
    // Update accessories
    if (accessoriesElement) {
      accessoriesElement.textContent = `₦${accessoriesTotal.toLocaleString()}`;
    }
    
    // Get selected state
    const stateSelect = document.getElementById('state');
    const selectedState = stateSelect ? stateSelect.value : '';
    const isLagos = selectedState === 'Lagos';
    
    // Calculate shipping cost based on state and subtotal
    const lagosThreshold = 150000;
    const otherThreshold = 250000;
    const threshold = isLagos ? lagosThreshold : otherThreshold;
    
    let shippingText = 'Depends on location';
    
    // Only show FREE if state is selected and threshold is met
    if (selectedState && subtotal >= threshold) {
      shippingText = 'FREE';
    }
    
    // Update shipping and total
    shippingElement.textContent = shippingText;
    
    // Total is subtotal + accessories
    const total = subtotal + accessoriesTotal;
    totalElement.textContent = `₦${total.toLocaleString()}`;
    
    // Update shipping progress bar
    updateShippingProgress(subtotal, isLagos, selectedState);
  }
  
  function updateShippingProgress(subtotal, isLagos, selectedState) {
    // Set thresholds
    const lagosThreshold = 150000;
    const otherThreshold = 250000;
    const threshold = isLagos ? lagosThreshold : otherThreshold;
    
    // Calculate progress
    const progress = Math.min(100, Math.round((subtotal / threshold) * 100));
    const remaining = threshold - subtotal;
    
    // Get or create progress container
    let progressContainer = document.querySelector('.shipping-progress-container');
    
    if (!progressContainer) {
      progressContainer = document.createElement('div');
      progressContainer.className = 'shipping-progress-container mb-6 p-4 bg-gray-50 rounded-lg border';
      
      const summaryHeading = document.querySelector('#cart-summary h3');
      if (summaryHeading) {
        summaryHeading.parentNode.insertBefore(progressContainer, summaryHeading);
      }
    }
    
    // Create message based on state selection and progress
    let message = '';
    if (progress >= 100) {
      message = '<p class="text-sm text-green-600 font-medium">✅ You qualify for free shipping!</p>';
    } else {
      const stateText = selectedState === 'Lagos' ? ' in Lagos' : selectedState ? ` in ${selectedState}` : '';
      message = `<p class="text-sm ${progress >= 50 ? 'text-yellow-600' : 'text-red-600'}">
                   Spend ₦${remaining.toLocaleString()} more for free shipping${stateText}
                 </p>`;
    }
    
    // Update progress bar HTML
    progressContainer.innerHTML = `
      <h4 class="font-medium mb-3 text-primary">Free Shipping Progress</h4>
      <div class="w-full bg-gray-200 rounded-full h-2 mb-3">
        <div class="h-2 rounded-full transition-all duration-300 ${progress >= 100 ? 'bg-green-500' : progress >= 50 ? 'bg-yellow-500' : 'bg-red-500'}" 
             style="width: ${progress}%"></div>
      </div>
      ${message}
      <p class="text-xs text-muted mt-2">Free shipping on orders of ₦150,000+ within Lagos, ₦250,000+ outside Lagos</p>
    `;
  }
  
  function setupEventListeners(cart) {
    // Remove item listeners
    document.querySelectorAll('.remove-item').forEach(button => {
      button.addEventListener('click', function() {
        const index = parseInt(this.dataset.index);
        cart.splice(index, 1);
        saveCart(cart);
        renderCart(cart);
      });
    });
    
    // Update quantity listeners
    document.querySelectorAll('.update-quantity').forEach(button => {
      button.addEventListener('click', function() {
        const index = parseInt(this.dataset.index);
        const action = this.dataset.action;
        
        if (action === 'increase') {
          cart[index].quantity += 1;
        } else if (action === 'decrease' && cart[index].quantity > 1) {
          cart[index].quantity -= 1;
        }
        
        saveCart(cart);
        renderCart(cart);
      });
    });
    
    // State select change
    const stateSelect = document.getElementById('state');
    if (stateSelect) {
      stateSelect.addEventListener('change', function() {
        // Get current cart
        const currentCart = loadCart();
        
        // Calculate subtotal and accessories total
        let subtotal = 0;
        let accessoriesTotal = 0;
        
        currentCart.forEach(item => {
          const itemTotal = item.price * item.quantity;
          if (item.type === 'accessory') {
            accessoriesTotal += itemTotal;
          } else {
            subtotal += itemTotal;
          }
        });
        
        updateOrderSummary(subtotal, accessoriesTotal);
        saveFormData();
      });
    }
    
    // Form input change listeners
    const clientNameInput = document.getElementById('client-name');
    if (clientNameInput) {
      clientNameInput.addEventListener('input', saveFormData);
    }
    
    // Continue shopping button
    const backButton = document.getElementById('back-button');
    if (backButton) {
      backButton.addEventListener('click', function() {
        // Go back to previous page if available, otherwise go to products page
        if (document.referrer && document.referrer.includes(window.location.hostname)) {
          window.location.href = document.referrer;
        } else {
          window.location.href = '/products.php';
        }
      });
    }
    
    
    // Checkout button
    const checkoutBtn = document.getElementById('checkout-btn');
    if (checkoutBtn) {
      checkoutBtn.addEventListener('click', function() {
        processCheckout();
      });
    }     
  }

  // Process checkout function
    function processCheckout() {
      // Get cart items
      const cart = loadCart();
      
      if (cart.length === 0) {
        alert('Your cart is empty');
        return;
      }
      
      // Validate form fields
      const clientName = document.getElementById('client-name').value;
      const isLoggedIn = window.app && window.app.auth && window.app.auth.isLoggedIn();
      
      let shippingAddress = '';
      let state = '';
      let addressId = null;
      
      if (isLoggedIn) {
        const addressSelect = document.getElementById('address-select');
        
        if (addressSelect.value === 'new') {
          // Check if new address form is filled
          const fullName = document.getElementById('new-full-name')?.value;
          const streetAddress = document.getElementById('new-street-address')?.value;
          const city = document.getElementById('new-city')?.value;
          const newState = document.getElementById('new-state')?.value;
          
          if (!fullName || !streetAddress || !city || !newState) {
            alert('Please fill in all required fields in the new address form');
            return;
          }
          
          shippingAddress = `${streetAddress}, ${city}, ${newState}`;
          state = newState;
        } else if (!addressSelect.value) {
          alert('Please select a shipping address');
          return;
        } else {
          // Get address details from selected option
          addressId = addressSelect.value;
          const selectedOption = addressSelect.options[addressSelect.selectedIndex];
          shippingAddress = selectedOption.text.split(': ')[1];
          state = document.getElementById('state').value;
        }
      } else {
        shippingAddress = document.getElementById('shipping-address').value;
        state = document.getElementById('state').value;
        
        if (!shippingAddress) {
          alert('Please enter your shipping address');
          return;
        }
      }
      
      if (!clientName || !state) {
        alert('Please fill in all required fields');
        return;
      }
      
      // Check for payment proof
      const paymentProofInput = document.getElementById('payment-proof');
      if (!paymentProofInput || !paymentProofInput.files || !paymentProofInput.files[0]) {
        alert('Please upload proof of payment');
        return;
      }
      
      // Calculate totals
      let subtotal = 0;
      let accessoriesTotal = 0;
      
      cart.forEach(item => {
        const itemTotal = item.price * item.quantity;
        if (item.type === 'accessory') {
          accessoriesTotal += itemTotal;
        } else {
          subtotal += itemTotal;
        }
      });
      
      const total = subtotal + accessoriesTotal;
      
      // Show loading state
      const checkoutBtn = document.getElementById('checkout-btn');
      checkoutBtn.disabled = true;
      checkoutBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processing...';
      
      // Create order data
      const orderData = {
        user_id: isLoggedIn ? (window.app.auth.getCurrentUser().user_id || window.app.auth.getCurrentUser().id) : null,
        client_name: clientName,
        shipping_address: shippingAddress,
        state: state,
        total: total,
        items: cart.map(item => ({
          id: item.id,
          name: item.name,
          price: item.price,
          quantity: item.quantity,
          color: item.color || 'N/A',  // Provide default values for required fields
          size: item.size || 'N/A',
          width: item.width || 'N/A'
        }))
      };

      console.log('Sending order data:', orderData); // Add this for debugging

      
      // Create order
      fetch('/api/orders.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(orderData)
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const orderId = data.order_id;
          
          // Upload payment proof
          const formData = new FormData();
          formData.append('payment_proof', paymentProofInput.files[0]);
          formData.append('order_id', orderId);
          formData.append('user_id', isLoggedIn ? (window.app.auth.getCurrentUser().user_id || window.app.auth.getCurrentUser().id) : null);
          
          return fetch('/api/upload_payment.php', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(uploadData => {
            if (uploadData.success) {
              // Clear cart
              clearCart();
              
              // Redirect to checkout success page
              window.location.href = `/checkout.php?order_id=${orderId}`;
            } else {
              throw new Error(uploadData.message || 'Failed to upload payment proof');
            }
          });
        } else {
          throw new Error(data.message || 'Failed to create order');
        }
      })
      .catch(error => {
        console.error('Error during checkout:', error);
        alert('Error during checkout: ' + error.message);
        
        // Reset button
        checkoutBtn.disabled = false;
        checkoutBtn.innerHTML = '<i class="fas fa-credit-card me-2"></i> Proceed to Checkout';
      });
    }   
  
  // Add any additional functions here if needed

});