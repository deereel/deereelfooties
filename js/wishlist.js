// wishlist.js - Handles wishlist functionality
document.addEventListener('DOMContentLoaded', function() {
  initWishlistButtons();
  
  // Initialize wishlist buttons
  function initWishlistButtons() {
    console.log('Initializing wishlist buttons');
    
    // Product page wishlist button
    const wishlistBtn = document.getElementById('add-to-wishlist-btn');
    if (wishlistBtn) {
      console.log('Found product page wishlist button');
      wishlistBtn.addEventListener('click', function(e) {
        e.preventDefault();
        handleWishlistAction(this);
      });
    }
    
    // Product card wishlist icons - both classes to ensure all are covered
    const wishlistIcons = document.querySelectorAll('.wishlist-icon, .add-to-wishlist-icon');
    console.log('Found wishlist icons:', wishlistIcons.length);
    
    wishlistIcons.forEach(icon => {
      icon.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation(); // Prevent navigating to product page
        console.log('Wishlist icon clicked', this);
        handleWishlistAction(this);
      });
    });
    
    // Add event delegation for dynamically added wishlist icons
    document.addEventListener('click', function(e) {
      if (e.target && (e.target.closest('.wishlist-icon') || e.target.closest('.add-to-wishlist-icon'))) {
        e.preventDefault();
        e.stopPropagation();
        const icon = e.target.closest('.wishlist-icon') || e.target.closest('.add-to-wishlist-icon');
        console.log('Wishlist icon clicked via delegation', icon);
        handleWishlistAction(icon);
      }
    });
  }
  
  // Handle wishlist action (add/remove)
  function handleWishlistAction(element) {
    // Check if user is logged in
    if (!isUserLoggedIn()) {
      // Show login modal
      const loginModal = document.getElementById('loginModal');
      if (loginModal && typeof bootstrap !== 'undefined') {
        const modal = new bootstrap.Modal(loginModal);
        modal.show();
      } else {
        alert('Please log in to add items to your wishlist');
      }
      return;
    }
    
    // Get product data
    let productId = element.dataset.productId;
    let productName, productPrice, productImage;
    
    // For product page button
    if (element.id === 'add-to-wishlist-btn') {
      productName = document.querySelector('h3.fw-bold')?.textContent || '';
      const priceText = document.querySelector('.text-2xl')?.textContent || '';
      // Extract numbers from price text (₦450,000 -> 450000)
      productPrice = parseFloat(priceText.replace(/[^\d\.]/g, '')) || 0;
      productImage = document.getElementById('mainImage')?.src || '';
      console.log('Product page wishlist data:', { productName, productPrice, productImage });
    } 
    // For product card icon
    else {
      // Try to get data directly from the element's data attributes first
      productName = element.dataset.productName;
      productPrice = parseFloat(element.dataset.price || element.dataset.productPrice || '0');
      productImage = element.dataset.image || element.dataset.productImage;
      
      // If data is missing, try to get from parent card
      if (!productName || !productPrice || !productImage) {
        const card = element.closest('.product-card');
        if (!card) {
          console.error('Could not find parent product card');
          return;
        }
        
        productName = productName || card.dataset.name || card.querySelector('h3')?.textContent || '';
        
        if (!productPrice) {
          productPrice = parseFloat(card.dataset.price || '0');
          if (!productPrice) {
            const priceText = card.querySelector('p')?.textContent || '';
            productPrice = parseFloat(priceText.replace(/[^\d\.]/g, '')) || 0;
          }
        }
        
        productId = productId || card.dataset.productId;
        
        // If still no image, get from nearest img tag
        if (!productImage) {
          productImage = card.querySelector('img')?.src || '';
        }
      }
      
      console.log('Product card wishlist data:', { productId, productName, productPrice, productImage });
    }
    
    // Add to wishlist
    addToWishlist({
      product_id: productId,
      product_name: productName,
      price: productPrice,
      image: productImage
    }, element);
  }
  
  // Add item to wishlist
  async function addToWishlist(product, element) {
    const userId = getUserId();
    if (!userId) {
      alert('Please log in to add items to your wishlist');
      return;
    }
    
    console.log('Adding to wishlist:', product);
    
    try {
      const response = await fetch('/api/wishlist.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          user_id: userId,
          product_id: product.product_id,
          product_name: product.product_name,
          price: product.price,
          image: product.image
        })
      });
      
      const data = await response.json();
      console.log('Wishlist API response:', data);
      
      if (data.success) {
        // Update UI
        if (element) {
          // Change icon to filled heart
          const icon = element.querySelector('i') || element;
          if (icon.classList.contains('far')) {
            icon.classList.remove('far');
            icon.classList.add('fas');
            icon.classList.add('text-danger');
          }
        }
        
        // Show success message
        alert('Item added to your wishlist!');
        
        // Trigger event for wishlist update if dashboard is open
        const wishlistUpdateEvent = new CustomEvent('wishlistUpdated');
        document.dispatchEvent(wishlistUpdateEvent);
      } else {
        alert(data.message || 'Failed to add item to wishlist');
      }
    } catch (error) {
      console.error('Error adding to wishlist:', error);
      alert('An error occurred. Please try again.');
    }
  }
  
  // Check if user is logged in
  function isUserLoggedIn() {
    // Check multiple sources for login status
    return !!(
      localStorage.getItem('DRFUser') ||
      document.body.getAttribute('data-user-id') ||
      localStorage.getItem('isLoggedIn') === 'true' ||
      sessionStorage.getItem('user_id')
    );
  }
  
  // Get current user ID
  function getUserId() {
    // Try to get user ID from localStorage
    const userData = localStorage.getItem('DRFUser');
    if (userData) {
      try {
        const user = JSON.parse(userData);
        return user.id || user.user_id;
      } catch (e) {
        console.error('Error parsing user data:', e);
      }
    }
    
    // Try to get from body attribute
    const bodyUserId = document.body.getAttribute('data-user-id');
    if (bodyUserId) {
      return bodyUserId;
    }
    
    // Try sessionStorage
    return sessionStorage.getItem('user_id');
  }
});