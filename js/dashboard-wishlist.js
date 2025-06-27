// Dashboard Wishlist Management
class DashboardWishlistManager {
  constructor() {
    this.init();
  }

  init() {
    console.log('Initializing Dashboard Wishlist Manager');
    this.loadWishlist();
  }

  async loadWishlist() {
    const container = document.getElementById('wishlist-container');
    if (!container) return;

    try {
      // Show loading
      container.innerHTML = `
        <div class="text-center py-4">
          <div class="spinner-border text-primary" role="status"></div>
          <p class="mt-2">Loading your wishlist...</p>
        </div>
      `;

      // Get user data
      const userData = localStorage.getItem('DRFUser');
      if (!userData) {
        container.innerHTML = '<p class="text-center py-4">Please log in to view your wishlist.</p>';
        return;
      }

      const user = JSON.parse(userData);
      const userId = user.user_id || user.id;

      // Fetch wishlist
      const response = await fetch(`/api/wishlist.php?user_id=${userId}&action=get`);
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const data = await response.json();
      console.log('Loaded wishlist response:', data);

      if (data.success) {
        this.renderWishlist(data.items || []);
      } else {
        container.innerHTML = `<p class="text-center py-4 text-danger">Error: ${data.message}</p>`;
      }
    } catch (error) {
      console.error('Error loading wishlist:', error);
      container.innerHTML = '<p class="text-center py-4 text-danger">Failed to load wishlist. Please try again.</p>';
    }
  }

  renderWishlist(items) {
    const container = document.getElementById('wishlist-container');
    
    if (!items || items.length === 0) {
      container.innerHTML = `
        <div class="text-center py-5">
          <i class="fas fa-heart fa-3x text-muted mb-3"></i>
          <h5>Your wishlist is empty</h5>
          <p class="text-muted">Save items you love to your wishlist.</p>
          <a href="/products.php" class="btn btn-primary mt-2">Start Shopping</a>
        </div>
      `;
      return;
    }

    const itemsHtml = `
      <div class="row">
        ${items.map(item => this.renderWishlistItem(item)).join('')}
      </div>
    `;

    container.innerHTML = itemsHtml;
  }

  renderWishlistItem(item) {
    const productUrl = item.url || `/product.php?id=${item.product_id}`;
    const imageUrl = item.image || '/images/product-placeholder.jpg';
    const price = parseFloat(item.price).toFixed(2);
    
    return `
      <div class="col-md-4 col-sm-6 mb-4">
        <div class="card h-100">
          <div class="position-relative">
            <img src="${imageUrl}" class="card-img-top" alt="${item.name}" style="height: 200px; object-fit: cover;">
            <button class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" 
                    onclick="event.preventDefault(); dashboardWishlistManager.removeFromWishlist(${item.product_id})">
              <i class="fas fa-times"></i>
            </button>
          </div>
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">${item.name}</h5>
            <p class="card-text text-primary mb-3">$${price}</p>
            <div class="mt-auto">
              <a href="${productUrl}" class="btn btn-outline-primary btn-sm">View Details</a>
            </div>
          </div>
        </div>
      </div>
    `;
  }

  async removeFromWishlist(productId) {
    try {
      // Get user data
      const userData = localStorage.getItem('DRFUser');
      if (!userData) {
        alert('Please log in to manage your wishlist');
        return;
      }

      const user = JSON.parse(userData);
      const userId = user.user_id || user.id;

      // Remove from wishlist
      const response = await fetch('/api/wishlist.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          action: 'remove',
          user_id: userId,
          product_id: productId
        })
      });
      
      const data = await response.json();
      
      if (data.success) {
        // Update local storage wishlist if it exists
        const wishlist = JSON.parse(localStorage.getItem('DRFWishlist') || '[]');
        const updatedWishlist = wishlist.filter(id => id !== productId);
        localStorage.setItem('DRFWishlist', JSON.stringify(updatedWishlist));
        
        // Reload wishlist
        this.loadWishlist();
      } else {
        alert(`Error: ${data.message}`);
      }
    } catch (error) {
      console.error('Error removing from wishlist:', error);
      alert('Failed to remove item from wishlist. Please try again.');
    }
  }

  // Cart functionality has been removed
  async addToCart(productId) {
    alert('Cart functionality has been removed from the system.');
  }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
  // Check if we're on the dashboard page
  if (document.body.getAttribute('data-page') === 'dashboard') {
    window.dashboardWishlistManager = new DashboardWishlistManager();
  }
});