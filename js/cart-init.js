// Initialize cart functionality
document.addEventListener('DOMContentLoaded', function() {
  console.log('Cart initialization script loaded');
  
  // Check if user is logged in
  const isLoggedIn = document.querySelector('meta[name="user-id"]') !== null;
  
  // Update cart count in navbar
  updateCartCount(isLoggedIn);
  
  // Update wishlist count in navbar
  updateWishlistCount(isLoggedIn);
});

// Update cart count in navbar
function updateCartCount(isLoggedIn) {
  const cartCountElements = document.querySelectorAll('.cart-count');
  
  if (isLoggedIn) {
    // For logged-in users, get count from database
    const userId = document.querySelector('meta[name="user-id"]').getAttribute('content');
    
    fetch(`/api/get_cart.php?user_id=${userId}`)
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const count = data.items.length;
          cartCountElements.forEach(element => {
            element.textContent = count;
            element.style.display = count > 0 ? 'block' : 'none';
          });
        }
      })
      .catch(error => {
        console.error('Error fetching cart count:', error);
      });
  } else {
    // For guest users, get count from localStorage
    const cart = JSON.parse(localStorage.getItem('DRFCart') || '[]');
    const count = cart.length;
    
    cartCountElements.forEach(element => {
      element.textContent = count;
      element.style.display = count > 0 ? 'block' : 'none';
    });
  }
}

// Update wishlist count in navbar
function updateWishlistCount(isLoggedIn) {
  const wishlistCountElements = document.querySelectorAll('.wishlist-count');
  
  if (isLoggedIn) {
    // For logged-in users, get count from database
    const userId = document.querySelector('meta[name="user-id"]').getAttribute('content');
    
    fetch(`/api/wishlist.php?action=get&user_id=${userId}`)
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const count = data.items.length;
          wishlistCountElements.forEach(element => {
            element.textContent = count;
            element.style.display = count > 0 ? 'block' : 'none';
          });
        }
      })
      .catch(error => {
        console.error('Error fetching wishlist count:', error);
      });
  } else {
    // For guest users, get count from localStorage
    const wishlist = JSON.parse(localStorage.getItem('DRFWishlist') || '[]');
    const count = wishlist.length;
    
    wishlistCountElements.forEach(element => {
      element.textContent = count;
      element.style.display = count > 0 ? 'block' : 'none';
    });
  }
}