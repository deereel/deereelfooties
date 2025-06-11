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
        document.getElementById('selected-quantity').value = quantityInput.value;
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
  
  // Add to cart button
  const addToCartBtn = document.getElementById('add-to-cart-btn');
  if (addToCartBtn) {
    addToCartBtn.addEventListener('click', function() {
      const color = document.getElementById('selected-color').value;
      const size = document.getElementById('selected-size').value;
      const width = document.getElementById('selected-width').value;
      const quantity = document.getElementById('quantity').value;
      
      if (!color) {
        alert('Please select a color');
        return;
      }
      
      if (!size) {
        alert('Please select a size');
        return;
      }
      
      if (!width) {
        alert('Please select a width');
        return;
      }
      
      // Get product details
      const productId = document.querySelector('.add-to-wishlist').getAttribute('data-product-id');
      const productName = document.querySelector('h3.fw-bold').textContent;
      const productPrice = document.querySelector('p.text-2xl').textContent.replace('₦', '').replace(/,/g, '');
      const productImage = document.getElementById('mainImage').src;
      
      // Create cart item
      const cartItem = {
        product_id: productId,
        product_name: productName,
        price: parseFloat(productPrice),
        color: color,
        size: size,
        width: width,
        quantity: parseInt(quantity),
        image: productImage
      };
      
      // Add to cart
      addToCart(cartItem);
      
      // Show added to cart modal
      const addedToCartModal = document.getElementById('added-to-cart-modal');
      if (addedToCartModal) {
        document.getElementById('cart-product-details').textContent = 
          `Size: ${size} | Color: ${color} | Width: ${width}`;
        addedToCartModal.classList.remove('hidden');
      }
    });
  }
});

// Add to cart function
function addToCart(item) {
  // Get current cart
  let cart = JSON.parse(localStorage.getItem('DRFCart') || '[]');
  
  // Check if item already exists in cart
  const existingItemIndex = cart.findIndex(cartItem => 
    cartItem.product_id === item.product_id && 
    cartItem.color === item.color && 
    cartItem.size === item.size &&
    cartItem.width === item.width
  );
  
  if (existingItemIndex !== -1) {
    // Update quantity if item exists
    cart[existingItemIndex].quantity += item.quantity;
  } else {
    // Add new item
    cart.push(item);
  }
  
  // Save cart
  localStorage.setItem('DRFCart', JSON.stringify(cart));
  
  // Update cart count in navbar
  updateCartCount(cart);
  
  // Sync with database if user is logged in
  syncCartWithDatabase(cart);
}

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

// Update cart count in navbar
function updateCartCount(cart) {
  const cartCount = cart.reduce((total, item) => total + parseInt(item.quantity), 0);
  const cartBadge = document.querySelector('.fa-shopping-bag + span');
  
  if (cartBadge) {
    cartBadge.textContent = cartCount;
    cartBadge.style.display = cartCount > 0 ? 'inline-block' : 'none';
  }
}

// Sync cart with database
async function syncCartWithDatabase(cart) {
  const userData = localStorage.getItem('DRFUser');
  if (!userData) return; // Not logged in
  
  try {
    const user = JSON.parse(userData);
    const userId = user.user_id || user.id;
    
    await fetch('/api/sync_cart.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        user_id: userId,
        cart: cart
      })
    });
  } catch (error) {
    console.error('Error syncing cart with database:', error);
  }
}

// Sync wishlist with database
async function syncWishlistWithDatabase(wishlist) {
  const userData = localStorage.getItem('DRFUser');
  if (!userData) return; // Not logged in
  
  try {
    const user = JSON.parse(userData);
    const userId = user.user_id || user.id;
    
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