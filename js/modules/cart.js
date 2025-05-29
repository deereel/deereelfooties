class CartManager {
  constructor() {
    this.cartKey = 'DRFCart';
    this.customerKey = 'DRFCustomerInfo';
  }

  // Cart data management
  loadCart() {
    // Check if user is logged in
    if (window.app && window.app.auth && window.app.auth.isLoggedIn()) {
      const user = window.app.auth.getCurrentUser();
      return this.loadUserCart(user);
    } else {
      // Load guest cart
      const cart = localStorage.getItem(this.cartKey);
      return cart ? JSON.parse(cart) : [];
    }
  }
  
  loadUserCart(user) {
    // Try to load user-specific cart
    const userCartKey = `DRFCart_${user.user_id || user.id || user.email}`;
    const cartData = localStorage.getItem(userCartKey);
    
    if (cartData) {
      return JSON.parse(cartData);
    } else {
      // Check if there's a guest cart to migrate
      const guestCart = localStorage.getItem(this.cartKey);
      if (guestCart) {
        const cart = JSON.parse(guestCart);
        if (cart && cart.length > 0) {
          // Migrate guest cart to user cart
          this.saveUserCart(user, cart);
          // Clear guest cart
          localStorage.removeItem(this.cartKey);
          return cart;
        }
      }
      return [];
    }
  }

  saveCart(cart) {
    // Check if user is logged in
    if (window.app && window.app.auth && window.app.auth.isLoggedIn()) {
      const user = window.app.auth.getCurrentUser();
      this.saveUserCart(user, cart);
    } else {
      // Save as guest cart
      localStorage.setItem(this.cartKey, JSON.stringify(cart));
    }
    this.updateCartCount();
  }
  
  saveUserCart(user, cart) {
    const userCartKey = `DRFCart_${user.user_id || user.id || user.email}`;
    localStorage.setItem(userCartKey, JSON.stringify(cart));
  }

  addToCart(product) {
    console.log('Adding to cart:', product);
    
    // Validate required fields
    if (!product.id || !product.name || !product.price) {
      console.error('Invalid product data:', product);
      return false;
    }
    
    // Validate options for product pages
    if (!product.color || !product.size || !product.width) {
      console.error('Missing product options:', product);
      alert('Please select color, size, and width before adding to cart.');
      return false;
    }

    const cart = this.loadCart();
    
    // Check if item already exists in cart
    const existingItemIndex = cart.findIndex(item => 
      item.id === product.id && 
      item.color === product.color && 
      item.size === product.size && 
      item.width === product.width
    );

    if (existingItemIndex > -1) {
      // Update quantity if item exists
      cart[existingItemIndex].quantity += product.quantity || 1;
    } else {
      // Add new item
      cart.push({
        ...product,
        quantity: product.quantity || 1
      });
    }

    this.saveCart(cart);
    this.showAddedToCartModal(product);
    
    return true;
  }

  updateCartCount() {
    const cart = this.loadCart();
    const count = cart.reduce((sum, item) => sum + item.quantity, 0);
    
    // Update all cart count elements
    const cartCountElements = document.querySelectorAll('.cart-count, .fa-shopping-bag + span');
    cartCountElements.forEach(element => {
      if (element) element.textContent = count;
    });
    
    return count;
  }

  showAddedToCartModal(product) {
    const modal = document.getElementById('added-to-cart-modal');
    if (!modal) {
      alert(`${product.name} added to cart!`);
      return;
    }

    console.log('Showing modal for product:', product);

    // Update modal content
    const modalImage = document.getElementById('modal-product-image');
    const modalName = document.getElementById('modal-product-name');
    const modalVariant = document.getElementById('modal-product-variant');
    const modalPrice = document.getElementById('modal-product-price');

    if (modalImage) {
      modalImage.src = product.image;
      modalImage.alt = product.name;
    }
    
    if (modalName) {
      modalName.textContent = product.name;
      console.log('Set modal name to:', product.name);
    }
    
    if (modalVariant) {
      modalVariant.textContent = `Size: ${product.size} | Width: ${product.width} | Color: ${product.color}`;
    }
    
    if (modalPrice) {
      modalPrice.textContent = `₦${product.price.toLocaleString()}`;
    }

    // Show modal
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // Auto-hide after 5 seconds
    setTimeout(() => {
      modal.classList.add('hidden');
      document.body.style.overflow = 'auto';
    }, 5000);
  }

  // Cart page functionality
  renderCartPage() {
    console.log('Rendering cart page');
    const cart = this.loadCart();
    const cartContainer = document.getElementById('cart-items');
    const cartSummary = document.getElementById('cart-summary');
    const emptyCartMessage = document.getElementById('empty-cart-message');
    
    if (!cartContainer) {
      return;
    }

    // Show/hide empty cart message
    if (emptyCartMessage) {
      emptyCartMessage.style.display = cart.length ? 'none' : 'block';
    }
    
    // Show/hide cart content
    if (cartContainer) {
      cartContainer.style.display = cart.length ? 'block' : 'none';
    }
    
    if (cartSummary) {
      cartSummary.style.display = cart.length ? 'block' : 'none';
    }

    if (!cart.length) {
      return;
    }

    // Render cart items
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

      cartHTML += `
        <div class="flex flex-col md:flex-row border-b py-4 gap-4">
          <div class="w-full md:w-24 h-24 flex-shrink-0">
            <img src="${item.image}" alt="${item.name}" class="w-full h-full object-cover">
          </div>
          <div class="flex-grow">
            <h3 class="font-medium text-primary">${item.name}</h3>
            <p class="text-muted text-sm">Size: ${item.size} | Width: ${item.width} | Color: ${item.color}</p>
            <div class="flex items-center mt-2">
              <button class="update-quantity border px-2" data-index="${index}" data-action="decrease">-</button>
              <span class="px-3">${item.quantity}</span>
              <button class="update-quantity border px-2" data-index="${index}" data-action="increase">+</button>
            </div>
          </div>
          <div class="flex flex-col items-end">
            <p class="font-medium">₦${itemTotal.toLocaleString()}</p>
            <button class="text-accent text-sm remove-item mt-2" data-index="${index}">Remove</button>
          </div>
        </div>
      `;
    });

    cartContainer.innerHTML = cartHTML;

    // Update summary
    const subtotalElement = document.getElementById('cart-subtotal');
    const accessoriesElement = document.getElementById('cart-accessories');
    const totalElement = document.getElementById('cart-total');
    
    if (subtotalElement) subtotalElement.textContent = `₦${subtotal.toLocaleString()}`;
    if (accessoriesElement) accessoriesElement.textContent = `₦${accessoriesTotal.toLocaleString()}`;

    // Get state selection
    const stateSelect = document.getElementById('state');
    const selectedState = stateSelect ? stateSelect.value : '';
    const isLagos = selectedState === 'Lagos';
    
    // Free shipping thresholds
    const lagosThreshold = 150000;
    const outsideThreshold = 250000;
    const threshold = isLagos ? lagosThreshold : outsideThreshold;
    
    // Calculate shipping cost - but don't display numeric value
    let shippingText = 'Depends on location';
    
    // Only show FREE if state is selected and threshold is met
    if (selectedState && subtotal >= threshold) {
      shippingText = 'FREE';
    }
    
    // Update shipping and total
    const shippingElement = document.getElementById('cart-shipping');
    if (shippingElement) shippingElement.textContent = shippingText;
    
    // Total is always subtotal + accessories (shipping is not included in total)
    const total = subtotal + accessoriesTotal;
    if (totalElement) {
      totalElement.textContent = `₦${total.toLocaleString()}`;
    }

    // Update shipping progress bar
    this.updateShippingProgress(subtotal, isLagos, selectedState);

    // Add event listeners
    this.addCartEventListeners();
    
    // Show continue shopping button
    const continueShoppingContainer = document.getElementById('continue-shopping-container');
    if (continueShoppingContainer) {
      continueShoppingContainer.style.display = 'block';
    }
  }

  updateShippingProgress(subtotal, isLagos = false, selectedState = '') {
    // Free shipping thresholds
    const lagosThreshold = 150000;
    const outsideThreshold = 250000;
    const threshold = isLagos ? lagosThreshold : outsideThreshold;
    
    // Calculate progress percentage
    const progress = Math.min(100, Math.round((subtotal / threshold) * 100));
    
    // Get or create progress elements
    let progressContainer = document.querySelector('.shipping-progress-container');
    
    if (!progressContainer) {
      progressContainer = document.createElement('div');
      progressContainer.className = 'shipping-progress-container mb-6 p-4 bg-gray-50 rounded-lg border';
      
      const summaryHeading = document.querySelector('#cart-summary h3');
      if (summaryHeading) {
        summaryHeading.parentNode.insertBefore(progressContainer, summaryHeading);
      }
    }

    if (!progressContainer) {
      return;
    }

    // Calculate remaining amount
    const remaining = threshold - subtotal;
    
    // Create message based on state selection and progress
    let message = '';
    if (progress >= 100) {
      message = '<span class="text-green-600 font-medium">✅ You qualify for free shipping!</span>';
    } else {
      const stateText = selectedState === 'Lagos' ? ' in Lagos' : '';
      message = `<span class="${progress >= 50 ? 'text-yellow-600' : 'text-red-600'}">
                   Spend ₦${remaining.toLocaleString()} more for free shipping${stateText}
                 </span>`;
    }
    
    // Update progress bar HTML
    progressContainer.innerHTML = `
      <h4 class="font-medium mb-3 text-primary">Free Shipping Progress</h4>
      <div class="w-full bg-gray-200 rounded-full h-2 mb-3">
        <div class="h-2 rounded-full transition-all duration-300 ${progress >= 100 ? 'bg-green-500' : progress >= 50 ? 'bg-yellow-500' : 'bg-red-500'}" 
             style="width: ${progress}%"></div>
      </div>
      <p class="text-sm">${message}</p>
      <p class="text-xs text-muted mt-2">Free shipping: ₦150,000+ within Lagos, ₦250,000+ outside Lagos</p>
    `;
  }

  addCartEventListeners() {
    // Remove item listeners
    document.querySelectorAll('.remove-item').forEach(btn => {
      btn.addEventListener('click', () => {
        const index = parseInt(btn.dataset.index);
        const cart = this.loadCart();
        cart.splice(index, 1);
        this.saveCart(cart);
        this.renderCartPage();
      });
    });

    // Update quantity listeners
    document.querySelectorAll('.update-quantity').forEach(btn => {
      btn.addEventListener('click', () => {
        const index = parseInt(btn.dataset.index);
        const action = btn.dataset.action;
        const cart = this.loadCart();
        
        if (action === 'increase') {
          cart[index].quantity += 1;
        } else if (action === 'decrease' && cart[index].quantity > 1) {
          cart[index].quantity -= 1;
        }
        
        this.saveCart(cart);
        this.renderCartPage();
      });
    });
    
    // State select change
    const stateSelect = document.getElementById('state');
    if (stateSelect) {
      stateSelect.addEventListener('change', () => {
        const cart = this.loadCart();
        if (cart.length > 0) {
          const subtotal = cart.reduce((sum, item) => {
            if (item.type !== 'accessory') {
              return sum + item.price * item.quantity;
            }
            return sum;
          }, 0);
          
          const isLagos = stateSelect.value === 'Lagos';
          this.updateShippingProgress(subtotal, isLagos, stateSelect.value);
        }
      });
    }
  }

  initShippingAddressListener() {
    const addressInput = document.getElementById('shipping-address');
    if (addressInput) {
      addressInput.addEventListener('input', () => {
        const cart = this.loadCart();
        if (cart.length > 0) {
          const subtotal = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
          this.renderCartPage();
        }
      });
    }
  }

  initCartModalHandlers() {
    const closeModal = document.getElementById('close-cart-modal');
    const continueBtn = document.getElementById('continue-shopping');

    if (closeModal) {
      closeModal.addEventListener('click', () => {
        document.getElementById('added-to-cart-modal')?.classList.add('hidden');
        document.body.style.overflow = 'auto';
      });
    }

    if (continueBtn) {
      continueBtn.addEventListener('click', () => {
        document.getElementById('added-to-cart-modal')?.classList.add('hidden');
        document.body.style.overflow = 'auto';
      });
    }
  }

  // Mobile menu functionality
  initMobileMenu() {
    const toggle = document.getElementById('mobileMenuToggle');
    const close = document.getElementById('closeMobileMenu');
    const overlay = document.querySelector('.mobile-nav-overlay');
    
    if (!toggle || !close || !overlay) return;
    
    toggle.addEventListener('click', () => {
      overlay.classList.remove('hidden');
      overlay.classList.add('visible');
    });
    
    close.addEventListener('click', () => {
      overlay.classList.remove('visible');
      overlay.classList.add('hidden');
    });
    
    overlay.addEventListener('click', (e) => {
      if (e.target === overlay) {
        overlay.classList.remove('visible');
        overlay.classList.add('hidden');
      }
    });
  }

  // Initialize cart page
  initCartPage() {
    this.renderCartPage();
    this.initCartModalHandlers();
    this.initShippingAddressListener();
    
    // Initialize continue shopping button
    const backButton = document.getElementById('back-button');
    if (backButton) {
      backButton.addEventListener('click', () => {
        // Go back to previous page if available, otherwise go to products page
        if (document.referrer && document.referrer.includes(window.location.hostname)) {
          window.location.href = document.referrer;
        } else {
          window.location.href = '/products.php';
        }
      });
    }
  }
}
  
export default CartManager;