// dashboard-wishlist.js - Handles wishlist functionality in the dashboard
document.addEventListener('DOMContentLoaded', function() {
  console.log('Dashboard wishlist script loaded');
  
  // Check if we're on the dashboard page
  if (document.body.getAttribute('data-page') === 'dashboard') {
    // Initialize wishlist tab
    const wishlistTab = document.querySelector('[data-tab="wishlist"]');
    console.log('Found wishlist tab:', wishlistTab);
    
    // Load wishlist items when tab is clicked
    if (wishlistTab) {
      wishlistTab.addEventListener('click', function() {
        console.log('Wishlist tab clicked');
        setTimeout(loadWishlistItems, 100);
      });
    }
    
    // Add event listener to all tab links
    document.querySelectorAll('.tab-link').forEach(link => {
      if (link.dataset.tab === 'wishlist') {
        link.addEventListener('click', function() {
          console.log('Wishlist tab link clicked');
          setTimeout(loadWishlistItems, 100);
        });
      }
    });
    
    // Load wishlist items on page load if hash is #wishlist
    if (window.location.hash === '#wishlist') {
      console.log('Loading wishlist from hash');
      setTimeout(loadWishlistItems, 300);
    }
    
    // Try to load wishlist items after a delay
    setTimeout(function() {
      const wishlistTabContent = document.getElementById('wishlist-tab');
      if (wishlistTabContent && !wishlistTabContent.classList.contains('d-none')) {
        console.log('Wishlist tab is visible, loading items');
        loadWishlistItems();
      }
    }, 500);
  }
  
  // Load wishlist items
  async function loadWishlistItems() {
    const container = document.getElementById('wishlist-items');
    if (!container) return;
    
    // Show loading
    container.innerHTML = `
      <div class="text-center py-4">
        <div class="spinner-border text-primary" role="status"></div>
        <p class="mt-2">Loading your wishlist...</p>
      </div>
    `;
    
    try {
      // Get user ID
      const userData = localStorage.getItem('DRFUser');
      if (!userData) {
        container.innerHTML = '<p class="text-center py-4">Please log in to view your wishlist.</p>';
        return;
      }
      
      const user = JSON.parse(userData);
      const userId = user.id || user.user_id;
      
      // Fetch wishlist items
      const response = await fetch(`/api/wishlist.php?user_id=${userId}`);
      const data = await response.json();
      
      if (data.success) {
        renderWishlistItems(data.items || []);
      } else {
        container.innerHTML = `<p class="text-center py-4 text-danger">Error: ${data.message}</p>`;
      }
    } catch (error) {
      console.error('Error loading wishlist:', error);
      container.innerHTML = '<p class="text-center py-4 text-danger">Failed to load wishlist. Please try again.</p>';
    }
  }
  
  // Render wishlist items
  function renderWishlistItems(items) {
    const container = document.getElementById('wishlist-items');
    
    if (!items || items.length === 0) {
      container.innerHTML = `
        <div class="text-center py-5">
          <i class="far fa-heart fa-3x text-muted mb-3"></i>
          <h5>Your wishlist is empty</h5>
          <p class="text-muted">Items you add to your wishlist will appear here.</p>
          <a href="/products.php" class="btn btn-primary mt-3">Browse Products</a>
        </div>
      `;
      return;
    }
    
    const itemsHtml = items.map(item => {
      return `
        <div class="card mb-3">
          <div class="row g-0">
            <div class="col-md-3">
              <img src="${item.image}" class="img-fluid rounded-start" alt="${item.product_name}" style="max-height: 150px; object-fit: cover;">
            </div>
            <div class="col-md-9">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <h5 class="card-title">${item.product_name}</h5>
                  <button class="btn btn-sm btn-outline-danger remove-wishlist-item" data-wishlist-id="${item.wishlist_id}">
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
                <p class="card-text">₦${parseFloat(item.price).toLocaleString()}</p>
                <div class="d-flex gap-2 mt-3">
                  <a href="/product.php?id=${item.product_id}" class="btn btn-sm btn-outline-primary">View Details</a>
                  <button class="btn btn-sm btn-primary add-to-cart-from-wishlist" 
                          data-product-id="${item.product_id}"
                          data-product-name="${item.product_name}"
                          data-price="${item.price}"
                          data-image="${item.image}">
                    Add to Cart
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      `;
    }).join('');
    
    container.innerHTML = itemsHtml;
    
    // Add event listeners for remove buttons
    document.querySelectorAll('.remove-wishlist-item').forEach(button => {
      button.addEventListener('click', function() {
        removeWishlistItem(this.dataset.wishlistId);
      });
    });
    
    // Add event listeners for add to cart buttons
    document.querySelectorAll('.add-to-cart-from-wishlist').forEach(button => {
      button.addEventListener('click', function() {
        addToCartFromWishlist(this);
      });
    });
  }
  
  // Remove item from wishlist
  async function removeWishlistItem(wishlistId) {
    if (!confirm('Are you sure you want to remove this item from your wishlist?')) {
      return;
    }
    
    try {
      const userData = localStorage.getItem('DRFUser');
      if (!userData) {
        alert('Please log in to manage your wishlist.');
        return;
      }
      
      const user = JSON.parse(userData);
      const userId = user.id || user.user_id;
      
      const response = await fetch('/api/wishlist.php', {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          user_id: userId,
          wishlist_id: wishlistId
        })
      });
      
      const data = await response.json();
      
      if (data.success) {
        // Reload wishlist items
        loadWishlistItems();
      } else {
        alert(data.message || 'Failed to remove item from wishlist');
      }
    } catch (error) {
      console.error('Error removing wishlist item:', error);
      alert('An error occurred. Please try again.');
    }
  }
  
  // Add to cart from wishlist
  function addToCartFromWishlist(button) {
    if (!window.cartHandler) {
      alert('Cart functionality is not available');
      return;
    }
    
    const product = {
      product_id: button.dataset.productId,
      product_name: button.dataset.productName,
      price: parseFloat(button.dataset.price),
      image: button.dataset.image,
      quantity: 1
    };
    
    window.cartHandler.addToCart(product);
    alert('Product added to cart!');
  }
});