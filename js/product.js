// Product page functionality
document.addEventListener('DOMContentLoaded', function() {
  console.log('Product page script loaded');
  
  // Quantity buttons
  const quantityBtns = document.querySelectorAll('.quantity-btn');
  const quantityInput = document.getElementById('quantity');
  
  if (quantityBtns.length > 0 && quantityInput) {
    quantityBtns.forEach(btn => {
      btn.addEventListener('click', function() {
        const action = this.getAttribute('data-action');
        let currentValue = parseInt(quantityInput.value);
        
        if (action === 'increase') {
          quantityInput.value = currentValue + 1;
        } else if (action === 'decrease' && currentValue > 1) {
          quantityInput.value = currentValue - 1;
        }
        
        // Update hidden input
        const selectedQuantityInput = document.getElementById('selected-quantity');
        if (selectedQuantityInput) {
          selectedQuantityInput.value = quantityInput.value;
        }
      });
    });
  }
  
  // Add to wishlist button
  const wishlistBtn = document.querySelector('.add-to-wishlist');
  if (wishlistBtn) {
    wishlistBtn.addEventListener('click', function() {
      const productId = this.getAttribute('data-product-id');
      addToWishlist(productId);
    });
  }
  
  // Customize button
  const customizeBtn = document.getElementById('customize-btn');
  if (customizeBtn) {
    customizeBtn.addEventListener('click', function() {
      window.location.href = '/customize.php';
    });
  }
  
  // Size selection
  const sizeOptions = document.querySelectorAll('.size-filter');
  if (sizeOptions.length > 0) {
    sizeOptions.forEach(option => {
      option.addEventListener('click', function() {
        // Remove selection from all options
        sizeOptions.forEach(opt => {
          opt.classList.remove('selected');
          opt.classList.remove('bg-black');
          opt.classList.remove('text-white');
        });
        
        // Add selection to clicked option
        this.classList.add('selected');
        this.classList.add('bg-black');
        this.classList.add('text-white');
        
        // Update hidden input
        document.getElementById('selected-size').value = this.getAttribute('data-size');
      });
    });
  }
  
  // Color selection
  const colorOptions = document.querySelectorAll('.color-filter');
  if (colorOptions.length > 0) {
    colorOptions.forEach(option => {
      option.addEventListener('click', function() {
        // Remove selection from all options
        colorOptions.forEach(opt => {
          opt.classList.remove('selected');
          opt.style.border = '1px solid #d1d5db'; // Reset border
        });
        
        // Add selection to clicked option
        this.classList.add('selected');
        this.style.border = '2px solid black'; // Thicker border for selected color
        
        // Update hidden input
        document.getElementById('selected-color').value = this.getAttribute('data-color');
      });
    });
  }
  
  // Width selection
  const widthOptions = document.querySelectorAll('.width-option');
  if (widthOptions.length > 0) {
    widthOptions.forEach(option => {
      option.addEventListener('click', function() {
        // Remove selection from all options
        widthOptions.forEach(opt => {
          opt.classList.remove('selected');
          opt.classList.remove('bg-black');
          opt.classList.remove('text-white');
        });
        
        // Add selection to clicked option
        this.classList.add('selected');
        this.classList.add('bg-black');
        this.classList.add('text-white');
        
        // Update hidden input
        document.getElementById('selected-width').value = this.getAttribute('data-width');
      });
    });
  }
  
  // Cart functionality has been removed
  const addToCartBtn = document.getElementById('add-to-cart-btn');
  if (addToCartBtn) {
    addToCartBtn.addEventListener('click', function() {
      alert('Cart functionality has been removed. Please contact us to place an order.');
    });
  }
});

// Cart functionality has been removed

// Add to wishlist function
function addToWishlist(productId) {
  // Get current wishlist
  let wishlist = JSON.parse(localStorage.getItem('DRFWishlist') || '[]');
  
  // Check if product already in wishlist
  if (!wishlist.includes(productId)) {
    wishlist.push(productId);
    localStorage.setItem('DRFWishlist', JSON.stringify(wishlist));
    alert('Product added to wishlist');
  } else {
    alert('Product already in wishlist');
  }
  
  // Sync with database if user is logged in
  syncWishlistWithDatabase(wishlist);
}

// Sync wishlist with database
async function syncWishlistWithDatabase(wishlist) {
  const userIdMeta = document.querySelector('meta[name="user-id"]');
  if (!userIdMeta) return; // Not logged in
  
  const userId = userIdMeta.getAttribute('content');
  
  try {
    await fetch('/api/wishlist.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        user_id: userId,
        action: 'sync',
        items: wishlist
      })
    });
  } catch (error) {
    console.error('Error syncing wishlist with database:', error);
  }
}