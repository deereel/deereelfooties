// dashboard-wishlist.js
document.addEventListener('DOMContentLoaded', function() {
  // Check if user is logged in
  const checkAuth = setInterval(() => {
    if (window.app && window.app.auth) {
      clearInterval(checkAuth);
      initWishlist();
    }
  }, 100);
  
  // Timeout after 5 seconds
  setTimeout(() => clearInterval(checkAuth), 5000);
  
  function initWishlist() {
    // Redirect if not logged in
    if (!window.app.auth.isLoggedIn()) {
      return;
    }
    
    // Check if we're on the wishlist section
    const wishlistSection = document.getElementById('wishlist-section');
    if (!wishlistSection) return;
    
    // Add event listener for wishlist tab
    const wishlistLink = document.querySelector('.nav-link[data-section="wishlist"]');
    if (wishlistLink) {
      wishlistLink.addEventListener('click', function() {
        loadWishlist();
      });
    }
    
    // Check if we're already on the wishlist section
    if (window.location.hash === '#wishlist') {
      loadWishlist();
    }
  }
  
  // Function to load wishlist items
  function loadWishlist() {
    const user = window.app.auth.getCurrentUser();
    console.log('Loading wishlist for user:', user);
    
    if (!user) {
      console.error('No user found');
      return;
    }
    
    // Try different user ID properties
    const userId = user.user_id || user.id || getUserId();
    console.log('Using user ID:', userId);
    
    if (!userId) {
      console.error('No user ID found');
      return;
    }
    
    const wishlistContainer = document.getElementById('wishlist-container');
    if (!wishlistContainer) {
      console.error('Wishlist container not found');
      return;
    }
    
    // Show loading indicator
    wishlistContainer.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading your wishlist...</p></div>';
    
    // Fetch wishlist from the server
    fetch(`/api/wishlist.php?user_id=${userId}`)
      .then(response => response.json())
      .then(data => {
        console.log('Wishlist data received:', data);
        
        if (data.success && data.items && data.items.length > 0) {
          // Render wishlist items
          let html = '<div class="row">';
          data.items.forEach(item => {
            html += `
              <div class="col-md-4 mb-4">
                <div class="card h-100">
                  <img src="${item.image}" class="card-img-top" alt="${item.product_name}" style="height: 250px; object-fit: cover;">
                  <div class="card-body d-flex flex-column">
                    <h5 class="card-title">${item.product_name}</h5>
                    <p class="card-text text-accent">₦${parseFloat(item.price).toLocaleString()}</p>
                    <div class="mt-auto">
                      <div class="d-flex justify-content-between gap-2">
                        <button class="btn btn-primary btn-sm add-wishlist-to-cart" 
                                data-product-id="${item.product_id}"
                                data-product-name="${item.product_name}"
                                data-product-price="${item.price}"
                                data-product-image="${item.image}">
                          Add to Cart
                        </button>
                        <button class="btn btn-outline-danger btn-sm delete-wishlist-btn" 
                                data-wishlist-id="${item.wishlist_id}">
                          Remove
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            `;
          });
          html += '</div>';
          
          wishlistContainer.innerHTML = html;
          
          // Add event listeners to the new buttons
          setupWishlistEventListeners();
        } else {
          // No wishlist items found
          wishlistContainer.innerHTML = `
            <div class="alert alert-info">
              <i class="fas fa-info-circle me-2"></i> Your wishlist is empty.
              <a href="/products.php" class="alert-link">Browse products</a>
            </div>
          `;
        }
      })
      .catch(error => {
        console.error('Error loading wishlist:', error);
        wishlistContainer.innerHTML = `
          <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i> Error loading wishlist. Please try again.
            <br><small>Error: ${error.message}</small>
          </div>
        `;
      });
  }
  
  // Function to set up wishlist event listeners
  function setupWishlistEventListeners() {
    // Delete wishlist item buttons
    document.querySelectorAll('.delete-wishlist-btn').forEach(button => {
      button.addEventListener('click', function() {
        const wishlistId = this.dataset.wishlistId;
        
        if (confirm('Are you sure you want to remove this item from your wishlist?')) {
          const user = window.app.auth.getCurrentUser();
          const userId = user.user_id || user.id || getUserId();
          
          fetch('/api/wishlist.php', {
            method: 'DELETE',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              user_id: userId,
              wishlist_id: wishlistId
            })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              this.closest('.col-md-4').remove();
              
              const wishlistContainer = document.getElementById('wishlist-container');
              if (wishlistContainer && wishlistContainer.querySelectorAll('.col-md-4').length === 0) {
                wishlistContainer.innerHTML = `
                  <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Your wishlist is empty.
                    <a href="/products.php" class="alert-link">Browse products</a>
                  </div>
                `;
              }
            } else {
              alert('Error: ' + data.message);
            }
          })
          .catch(error => {
            console.error('Error removing wishlist item:', error);
            alert('An error occurred. Please try again.');
          });
        }
      });
    });
    
    // Add wishlist items to cart
    document.querySelectorAll('.add-wishlist-to-cart').forEach(button => {
      button.addEventListener('click', function() {
        const productId = this.dataset.productId;
        const productName = this.dataset.productName;
        const productPrice = parseFloat(this.dataset.productPrice);
        const productImage = this.dataset.productImage;
        
        // Add to cart
        if (window.app && window.app.cart) {
          window.app.cart.addToCart({
            id: productId,
            name: productName,
            price: productPrice,
            image: productImage,
            color: 'default',
            size: 'default',
            width: 'default',
            quantity: 1
          });
          alert('Item added to cart!');
        } else {
          alert('Could not add item to cart. Please try again later.');
        }
      });
    });
  }
  
  // Helper function to get user ID
  function getUserId() {
    if (window.app?.auth?.getCurrentUser?.()) {
      const user = window.app.auth.getCurrentUser();
      return user.user_id || user.id || user.email;
    }
    return null;
  }
});