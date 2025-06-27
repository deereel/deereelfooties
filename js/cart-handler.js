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
    if (userData) {
      try {
        const user = JSON.parse(userData);
        this.isLoggedIn = true;
        this.userId = user.user_id || user.id;
        console.log('User logged in:', this.userId);
      } catch (e) {
        console.error('Error parsing user data:', e);
        this.isLoggedIn = false;
        this.userId = null;
      }
    } else {
      this.isLoggedIn = false;
      this.userId = null;
    }
  }

  async addToCart(item) {
    console.log('Adding to cart:', item);
    
    if (this.isLoggedIn) {
      await this.addToUserCart(item);
    } else {
      this.addToGuestCart(item);
    }
    
    this.updateCartCount();
  }

  addToGuestCart(item) {
    let cart = this.getGuestCart();
    
    // Check if item already exists
    const existingIndex = cart.findIndex(cartItem => 
      cartItem.product_id === item.product_id &&
      cartItem.color === item.color &&
      cartItem.size === item.size &&
      cartItem.width === item.width
    );

    if (existingIndex > -1) {
      cart[existingIndex].quantity += item.quantity;
    } else {
      cart.push({
        ...item,
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
    if (!this.isLoggedIn) return [];

    try {
      const response = await fetch(`/api/cart.php?action=get&user_id=${this.userId}`);
      const data = await response.json();
      
      if (data.success) {
        return data.items || [];
      }
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
    
    if (guestCart.length > 0) {
      console.log('Merging guest cart with user cart:', guestCart);
      await this.mergeGuestCartWithUserCart(guestCart);
      
      // Clear guest cart after successful merge
      localStorage.removeItem(this.cartKey);
    }

    // Load any previously saved cart
    await this.loadSavedCart();
    
    this.updateCartCount();
  }

  async mergeGuestCartWithUserCart(guestCart) {
    try {
      const response = await fetch('/api/cart.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          action: 'merge',
          user_id: this.userId,
          guest_cart: guestCart
        })
      });

      const data = await response.json();
      if (!data.success) {
        throw new Error(data.message || 'Failed to merge cart');
      }
      
      console.log('Cart merged successfully');
    } catch (error) {
      console.error('Error merging cart:', error);
    }
  }

  async loadSavedCart() {
    try {
      const response = await fetch(`/api/load-saved-cart.php?user_id=${this.userId}`);
      const data = await response.json();
      
      if (data.success && data.cart_data) {
        // Restore saved cart items to active cart
        const response2 = await fetch('/api/cart.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            action: 'restore',
            user_id: this.userId,
            cart_data: data.cart_data
          })
        });

        const result = await response2.json();
        if (result.success) {
          console.log('Saved cart loaded successfully');
        }
      }
    } catch (error) {
      console.error('Error loading saved cart:', error);
    }
  }

  async handleLogout() {
    console.log('Handling logout');
    
    if (this.isLoggedIn) {
      // Save current cart before logout
      await this.saveCartForLater();
      
      // Clear user cart from database
      await this.clearUserCart();
    }

    this.isLoggedIn = false;
    this.userId = null;
    this.updateCartCount();
  }

  async saveCartForLater() {
    try {
      const userCart = await this.getUserCart();
      if (userCart.length > 0) {
        const response = await fetch('/api/save-cart.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            user_id: this.userId,
            cart_data: userCart
          })
        });

        const data = await response.json();
        if (data.success) {
          console.log('Cart saved for later');
        }
      }
    } catch (error) {
      console.error('Error saving cart:', error);
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
