class CartHandler {
  constructor() {
    this.cartKey = 'DRFCart';
    this.userKey = 'DRFUser';
    this.isLoggedIn = false;
    this.userId = null;
    this.init();
  }

  init() {
    console.log('Initializing CartHandler');
    this.checkLoginStatus();
    this.updateCartCount();
  }

  checkLoginStatus() {
    const userData = localStorage.getItem(this.userKey);
    console.log('Checking login status. User data:', userData);
    if (userData) {
      try {
        const user = JSON.parse(userData);
        this.isLoggedIn = true;
        this.userId = user.id || user.user_id;
        console.log('User logged in:', this.userId, 'Full user data:', user);
      } catch (e) {
        console.error('Error parsing user data:', e);
        this.isLoggedIn = false;
        this.userId = null;
      }
    } else {
      this.isLoggedIn = false;
      this.userId = null;
      console.log('No user data found - user not logged in');
    }
  }

  async addToCart(item) {
    console.log('Adding to cart:', item);
    
    // Re-check login status before adding
    this.checkLoginStatus();
    
    if (this.isLoggedIn) {
      await this.addToUserCart(item);
    } else {
      this.addToGuestCart(item);
    }
    
    this.updateCartCount();
  }

  addToGuestCart(item) {
    let cart = this.getGuestCart();
    
    // Normalize item data to ensure consistent field names
    const normalizedItem = {
      product_id: item.product_id || item.id,
      product_name: item.product_name || item.name,
      price: item.price,
      image: item.image,
      color: item.color,
      size: item.size,
      width: item.width || '',
      quantity: item.quantity
    };
    
    // Check if item already exists
    const existingIndex = cart.findIndex(cartItem => 
      cartItem.product_id === normalizedItem.product_id &&
      cartItem.color === normalizedItem.color &&
      cartItem.size === normalizedItem.size &&
      cartItem.width === normalizedItem.width
    );

    if (existingIndex > -1) {
      cart[existingIndex].quantity += normalizedItem.quantity;
    } else {
      cart.push({
        ...normalizedItem,
        cart_item_id: Date.now() + Math.random(), // Temporary ID for guest cart
        added_at: new Date().toISOString()
      });
    }

    localStorage.setItem(this.cartKey, JSON.stringify(cart));
    console.log('Added to guest cart:', cart);
  }

  async addToUserCart(item) {
    try {
      const response = await fetch('/api/cart.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          action: 'add',
          user_id: this.userId,
          ...item
        })
      });

      const data = await response.json();
      if (!data.success) {
        throw new Error(data.message || 'Failed to add to cart');
      }
      
      console.log('Added to user cart:', data);
    } catch (error) {
      console.error('Error adding to user cart:', error);
      // Fallback to guest cart
      this.addToGuestCart(item);
    }
  }

  getGuestCart() {
    try {
      const cart = localStorage.getItem(this.cartKey);
      return cart ? JSON.parse(cart) : [];
    } catch (e) {
      console.error('Error parsing guest cart:', e);
      return [];
    }
  }

  async getUserCart() {
    if (!this.isLoggedIn) {
      console.log('User not logged in, returning empty cart');
      return [];
    }

    try {
      console.log('Fetching user cart for user ID:', this.userId);
      const response = await fetch(`/api/cart.php?action=get&user_id=${this.userId}`);
      const data = await response.json();
      
      console.log('User cart response:', data);
      
      if (data.success) {
        console.log('User cart items:', data.items);
        return data.items || [];
      }
      console.log('Failed to fetch user cart:', data.message);
      return [];
    } catch (error) {
      console.error('Error fetching user cart:', error);
      return [];
    }
  }

  async getCart() {
    if (this.isLoggedIn) {
      return await this.getUserCart();
    } else {
      return this.getGuestCart();
    }
  }

  async updateCartCount() {
    const cart = await this.getCart();
    const totalItems = cart.reduce((sum, item) => sum + (item.quantity || 0), 0);
    
    // Update all cart count elements
    document.querySelectorAll('.cart-count, #cart-count, [data-cart-count]').forEach(el => {
      el.textContent = totalItems;
      el.style.display = totalItems > 0 ? 'inline' : 'none';
    });
  }

  async handleLogin(userId) {
    console.log('Handling login for user:', userId);
    this.userId = userId;
    this.isLoggedIn = true;

    // Get guest cart before clearing
    const guestCart = this.getGuestCart();
    console.log('Guest cart before login:', guestCart);
    
    if (guestCart.length > 0) {
      console.log('Merging guest cart with user cart:', guestCart);
      await this.mergeGuestCartWithUserCart(guestCart);
      console.log('Guest cart merge completed');
      
      // Clear guest cart after successful merge
      localStorage.removeItem(this.cartKey);
      console.log('Guest cart cleared from localStorage');
    } else {
      console.log('No guest cart items to merge');
    }
    
    this.updateCartCount();
    
    // Trigger cart refresh on cart page
    if (window.location.pathname.includes('/cart.php')) {
      if (typeof loadCartItems === 'function') {
        setTimeout(() => loadCartItems(), 100);
      }
    }
  }

  async mergeGuestCartWithUserCart(guestCart) {
    try {
      // Normalize guest cart data to match database schema
      const normalizedCart = guestCart.map(item => ({
        product_id: item.product_id || item.id,
        product_name: item.product_name || item.name,
        price: item.price,
        image: item.image,
        color: item.color,
        size: item.size,
        width: item.width || '',
        quantity: item.quantity
      }));
      
      console.log('Sending merge request with normalized data:', {
        action: 'merge',
        user_id: this.userId,
        guest_cart: normalizedCart
      });
      
      const response = await fetch('/api/cart.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          action: 'merge',
          user_id: this.userId,
          guest_cart: normalizedCart
        })
      });

      const data = await response.json();
      console.log('Merge response:', data);
      
      if (!data.success) {
        throw new Error(data.message || 'Failed to merge cart');
      }
      
      console.log('Cart merged successfully');
    } catch (error) {
      console.error('Error merging cart:', error);
    }
  }



  async handleLogout() {
    console.log('Handling logout');
    
    // Simply update login status - keep cart items in database
    this.isLoggedIn = false;
    this.userId = null;
    this.updateCartCount();
    
    // Trigger cart refresh on cart page
    if (window.location.pathname.includes('/cart.php')) {
      if (typeof loadCartItems === 'function') {
        await loadCartItems();
      }
    }
  }



  async clearUserCart() {
    try {
      const response = await fetch('/api/cart.php', {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          action: 'clear',
          user_id: this.userId
        })
      });

      const data = await response.json();
      if (data.success) {
        console.log('User cart cleared');
      }
    } catch (error) {
      console.error('Error clearing user cart:', error);
    }
  }

  async clearCartAfterOrder() {
    if (this.isLoggedIn) {
      await this.clearUserCart();
    } else {
      localStorage.removeItem(this.cartKey);
    }
    this.updateCartCount();
  }

  showAddedToCartModal(item) {
    // Show success message or modal
    const message = `${item.product_name} added to cart!`;
    
    // Try to show in a modal if available
    const modal = document.getElementById('added-to-cart-modal');
    if (modal) {
      const productName = modal.querySelector('.modal-product-name');
      const productImage = modal.querySelector('.modal-product-image');
      
      if (productName) productName.textContent = item.product_name;
      if (productImage) productImage.src = item.image;
      
      // Show modal (assuming Bootstrap)
      if (typeof bootstrap !== 'undefined') {
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
      }
    } else {
      // Fallback to alert
      alert(message);
    }
  }
}

// Initialize cart handler globally
window.cartHandler = new CartHandler();

// Listen for login events
document.addEventListener('userLoggedIn', function() {
  console.log('User logged in event received');
  if (window.cartHandler) {
    const userData = localStorage.getItem('DRFUser');
    if (userData) {
      try {
        const user = JSON.parse(userData);
        const userId = user.id || user.user_id;
        console.log('Handling login for user:', userId);
        window.cartHandler.handleLogin(userId);
      } catch (e) {
        console.error('Error parsing user data on login:', e);
      }
    }
  }
});

// Listen for logout events
document.addEventListener('userLoggedOut', function() {
  console.log('User logged out event received');
  if (window.cartHandler) {
    window.cartHandler.handleLogout();
  }
});
