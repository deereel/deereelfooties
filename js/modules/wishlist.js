// js/wishlist.js
document.addEventListener('DOMContentLoaded', function() {
  // Initialize wishlist buttons
  initWishlistButtons();
  
  function initWishlistButtons() {
    // Product page wishlist button
    document.querySelectorAll('.add-to-wishlist').forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        addToWishlist(this);
      });
    });
    
    // Product card wishlist icons
    document.querySelectorAll('.add-to-wishlist-icon').forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        addToWishlist(this);
      });
    });
  }
  
  function addToWishlist(button) {
    // Check if user is logged in
    if (!isUserLoggedIn()) {
      if (confirm('You need to be logged in to add items to your wishlist. Would you like to sign in now?')) {
        // Show login modal or redirect to login page
        $('#loginModal').modal('show');
      }
      return;
    }
    
    const userId = getUserId();
    if (!userId) {
      alert('Please log in to add items to your wishlist');
      return;
    }
    
    // Get product data
    const productId = button.dataset.productId;
    let productName, productPrice, productImage;
    
    // For product page button
    if (button.classList.contains('add-to-wishlist')) {
      productName = document.querySelector('h3.fw-bold').textContent;
      productPrice = parseFloat(document.querySelector('.text-2xl').textContent.replace(/[^\d.]/g, ''));
      productImage = document.getElementById('mainImage').src;
    } 
    // For product card icon
    else {
      productName = button.dataset.productName;
      productPrice = parseFloat(button.dataset.productPrice);
      productImage = button.dataset.productImage;
    }
    
    console.log('Adding to wishlist:', {
      user_id: userId,
      product_id: productId,
      product_name: productName,
      price: productPrice,
      image: productImage
    });
    
    // Add to wishlist
    fetch('/api/wishlist.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        user_id: userId,
        product_id: productId,
        product_name: productName,
        price: productPrice,
        image: productImage
      })
    })
    .then(response => response.json())
    .then(data => {
      console.log('Wishlist response:', data);
      if (data.success) {
        // Change icon to filled heart
        const icon = button.querySelector('i');
        if (icon) {
          icon.classList.remove('far');
          icon.classList.add('fas');
          icon.classList.add('text-red-500');
        }
        
        alert('Item added to your wishlist!');
      } else {
        alert('Error: ' + data.message);
      }
    })
    .catch(error => {
      console.error('Error adding to wishlist:', error);
      alert('An error occurred. Please try again.');
    });
  }
  
  // Helper functions
  function isUserLoggedIn() {
    return !!(
      window.app?.auth?.isLoggedIn?.() || 
      window.app?.auth?.getCurrentUser?.() ||
      document.querySelector('[data-user-id]') ||
      document.querySelector('.user-menu') ||
      document.querySelector('.logout-btn') ||
      localStorage.getItem('isLoggedIn') === 'true' ||
      sessionStorage.getItem('user_id')
    );
  }
  
  function getUserId() {
    if (window.app?.auth?.getCurrentUser?.()) {
      return window.app.auth.getCurrentUser().user_id;
    }
    
    const userIdElement = document.querySelector('[data-user-id]');
    if (userIdElement) {
      return userIdElement.dataset.userId;
    }
    
    return sessionStorage.getItem('user_id') || localStorage.getItem('user_id');
  }
});
