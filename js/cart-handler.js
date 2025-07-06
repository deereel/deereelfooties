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
    // Small delay to ensure DOM is fully loaded
    setTimeout(() => {
      this.checkLoginStatus();
      this.updateCartCount();
    }, 100);
  }

  checkLoginStatus() {
    // Method 1: Check localStorage
    const userData = localStorage.getItem(this.userKey);
    console.log('Checking login status. User data:', userData);
    
    if (userData) {
      try {
        const user = JSON.parse(userData);
        this.isLoggedIn = true;
        this.userId = user.id || user.user_id;
        console.log('User logged in via localStorage:', this.userId);
        return;
      } catch (e) {
        console.error('Error parsing user data:', e);
      }
    }
    
    // Method 2: Check DOM meta tag
    const userIdMeta = document.querySelector('meta[name="user-id"]');
    if (userIdMeta && userIdMeta.content) {
      this.isLoggedIn = true;
      this.userId = userIdMeta.content;
      console.log('User logged in via meta tag:', this.userId);
      return;
    }
    
    // Method 3: Check body data attribute
    const bodyUserId = document.body.getAttribute('data-user-id');
    if (bodyUserId) {
      this.isLoggedIn = true;
      this.userId = bodyUserId;
      console.log('User logged in via body attribute:', this.userId);
      return;
    }
    
    // Method 4: Check sessionStorage
    const sessionUserId = sessionStorage.getItem('user_id');
    if (sessionUserId) {
      this.isLoggedIn = true;
      this.userId = sessionUserId;
      console.log('User logged in via sessionStorage:', this.userId);
      return;
    }
    
    // No login detected
    this.isLoggedIn = false;
    this.userId = null;
    console.log('No user login detected');
  }

  forceRefreshLoginStatus() {
    console.log('Force refreshing login status');
    this.checkLoginStatus();
    return this.isLoggedIn;
  }

  async addToCart(item) {
    console.log('Adding to cart:', item);
    
    // Force re-check login status before adding
    this.checkLoginStatus();
    console.log('Login status after check:', this.isLoggedIn, 'User ID:', this.userId);
    
    let success = false;
    if (this.isLoggedIn && this.userId) {
      console.log('Adding to user cart for user:', this.userId);
      success = await this.addToUserCart(item);
    } else {
      console.log('Adding to guest cart');
      this.addToGuestCart(item);
      success = true;
    }
    
    // Update cart count after adding
    await this.updateCartCount();
    console.log('Cart addition completed, success:', success);
    return success;
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
      console.log('Sending add to user cart request:', {
        action: 'add',
        user_id: this.userId,
        ...item
      });
      
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
      console.log('Add to user cart response:', data);
      
      if (!data.success) {
        throw new Error(data.message || 'Failed to add to cart');
      }
      
      console.log('Successfully added to user cart');
      return true;
    } catch (error) {
      console.error('Error adding to user cart:', error);
      // Fallback to guest cart
      console.log('Falling back to guest cart');
      this.addToGuestCart(item);
      return false;
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
    
    // Clear login status
    this.isLoggedIn = false;
    this.userId = null;
    
    // Update cart count immediately
    await this.updateCartCount();
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

// Initialize cart handler after DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', function() {
    window.cartHandler = new CartHandler();
  });
} else {
  // DOM is already ready
  window.cartHandler = new CartHandler();
}

// Listen for login events
document.addEventListener('userLoggedIn', function() {
  console.log('User logged in event received');
  
  // Re-initialize cart handler to pick up new login status
  if (window.cartHandler) {
    window.cartHandler.checkLoginStatus();
    
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
  } else {
    // Re-create cart handler if it doesn't exist
    window.cartHandler = new CartHandler();
  }
});

// Listen for logout events
document.addEventListener('userLoggedOut', function() {
  console.log('User logged out event received');
  if (window.cartHandler) {
    window.cartHandler.handleLogout();
  } else {
    // Re-create cart handler if it doesn't exist
    window.cartHandler = new CartHandler();
  }
});
