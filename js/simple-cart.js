// Simple cart implementation that works directly with the database for logged-in users
document.addEventListener('DOMContentLoaded', function() {
  // Initialize cart
  initCart();
  
  // Add event listeners for add to cart buttons
  document.querySelectorAll('#add-to-cart-btn').forEach(button => {
    button.addEventListener('click', addToCart);
  });
  
  // Listen for login events to handle cart migration
  document.addEventListener('userLoggedIn', function() {
    // Clear localStorage cart when user logs in to prevent mixing
    localStorage.removeItem('DRFCart');
  });
});

// Initialize cart
function initCart() {
  // Update cart count in the UI
  updateCartCount();
}

// Add to cart function
function addToCart() {
  // Get product details
  const productId = document.querySelector('body').dataset.productId || 
                   window.location.pathname.split('/').pop().replace('.php', '');
  const productName = document.querySelector('h3.fw-bold').textContent;
  const price = parseFloat(document.querySelector('.text-2xl').textContent.replace(/[^\d.]/g, ''));
  const mainImage = document.getElementById('mainImage').src;
  
  // Get selected options
  const color = document.getElementById('selected-color').value;
  const size = document.getElementById('selected-size').value;
  const width = document.getElementById('selected-width').value;
  const quantity = parseInt(document.getElementById('quantity').value) || 1;
  
  // Validate selections
  if (!color || !size || !width) {
    alert('Please select color, size and width');
    return;
  }
  
  // Create cart item
  const item = {
    id: productId,
    name: productName,
    product_name: productName,
    price: price,
    color: color,
    size: size,
    width: width,
    quantity: quantity,
    image: mainImage,
    isCustom: false
  };
  
  // Check if user is logged in
  const isLoggedIn = document.body.hasAttribute('data-user-id');
  const userId = document.body.getAttribute('data-user-id');
  
  if (isLoggedIn && userId) {
    // For logged-in users, save directly to database
    saveCartToDatabase(userId, item);
  } else {
    // For guests, save to localStorage
    saveCartToLocalStorage(item);
  }
  
  // Show success message
  showAddedToCartModal(item);
}

// Save cart to database for logged-in users
function saveCartToDatabase(userId, item) {
  // First get existing cart items
  fetch(`/api/get_cart.php?user_id=${userId}`)
    .then(response => response.json())
    .then(data => {
      let cartItems = data.success ? data.items : [];
      
      // Check if item already exists
      const existingItemIndex = cartItems.findIndex(i => 
        i.id === item.id && 
        i.color === item.color && 
        i.size === item.size && 
        i.width === item.width
      );
      
      if (existingItemIndex !== -1) {
        // Update quantity if item exists
        cartItems[existingItemIndex].quantity += item.quantity;
      } else {
        // Add new item
        cartItems.push(item);
      }
      
      // Save to database
      return fetch('/api/sync_cart.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          user_id: userId,
          cart_items: cartItems
        })
      });
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        console.log('Cart saved to database');
        updateCartCount();
      } else {
        console.error('Error saving cart to database:', data.message);
      }
    })
    .catch(error => {
      console.error('Error saving cart to database:', error);
    });
}

// Save cart to localStorage for guests
function saveCartToLocalStorage(item) {
  // Get existing cart
  let cart = JSON.parse(localStorage.getItem('DRFCart') || '[]');
  
  // Check if item already exists
  const existingItemIndex = cart.findIndex(i => 
    i.id === item.id && 
    i.color === item.color && 
    i.size === item.size && 
    i.width === item.width
  );
  
  if (existingItemIndex !== -1) {
    // Update quantity if item exists
    cart[existingItemIndex].quantity += item.quantity;
  } else {
    // Add new item
    cart.push(item);
  }
  
  // Save to localStorage
  localStorage.setItem('DRFCart', JSON.stringify(cart));
  
  // Update cart count
  updateCartCount();
}

// Update cart count in the UI
function updateCartCount() {
  // Check if user is logged in
  const isLoggedIn = document.body.hasAttribute('data-user-id');
  const userId = document.body.getAttribute('data-user-id');
  
  if (isLoggedIn && userId) {
    // For logged-in users, get count from database
    fetch(`/api/get_cart.php?user_id=${userId}`)
      .then(response => response.json())
      .then(data => {
        const cartItems = data.success ? data.items : [];
        const count = cartItems.reduce((sum, item) => sum + item.quantity, 0);
        updateCartBadge(count);
      })
      .catch(error => {
        console.error('Error getting cart count:', error);
      });
  } else {
    // For guests, get count from localStorage
    const cart = JSON.parse(localStorage.getItem('DRFCart') || '[]');
    const count = cart.reduce((sum, item) => sum + item.quantity, 0);
    updateCartBadge(count);
  }
}

// Update cart badge in the UI
function updateCartBadge(count) {
  const badge = document.querySelector('.cart-count');
  if (badge) {
    badge.textContent = count;
    badge.style.display = count > 0 ? 'inline' : 'none';
  }
}

// Show added to cart modal
function showAddedToCartModal(item) {
  const modal = document.getElementById('added-to-cart-modal');
  if (!modal) return;
  
  // Update modal content
  document.getElementById('cart-product-name').textContent = item.name;
  document.getElementById('cart-product-details').textContent = 
    `Size: ${item.size} | Color: ${item.color} | Width: ${item.width}`;
  document.getElementById('cart-product-price').textContent = `₦${item.price.toLocaleString()}`;
  document.getElementById('cart-product-image').src = item.image;
  
  // Show modal
  modal.classList.remove('hidden');
  
  // Add event listeners
  document.getElementById('close-cart-modal').addEventListener('click', function() {
    modal.classList.add('hidden');
  });
  
  document.getElementById('continue-shopping').addEventListener('click', function() {
    modal.classList.add('hidden');
  });
}