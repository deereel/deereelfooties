// Cart switcher - Handles switching between guest cart and logged-in cart
document.addEventListener('DOMContentLoaded', function() {
  console.log('Cart switcher script loaded');
  
  // Check if user is logged in
  const isLoggedIn = document.getElementById('user-status-indicator') !== null;
  
  // If logged in, sync local cart with database
  if (isLoggedIn) {
    syncLocalCartWithDatabase();
  }
});

// Sync local cart with database
function syncLocalCartWithDatabase() {
  // Get cart from localStorage
  const localCart = JSON.parse(localStorage.getItem('DRFCart') || '[]');
  
  // If local cart is empty, no need to sync
  if (localCart.length === 0) {
    return;
  }
  
  // Format cart items for API
  const cartItems = localCart.map(item => ({
    id: item.id || '',
    name: item.name || '',
    product_name: item.name || '',
    price: parseFloat(item.price) || 0,
    color: item.color || '',
    size: item.size || '',
    material: item.material || '',
    width: item.width || '',
    quantity: parseInt(item.quantity) || 1,
    image: item.image || '',
    isCustom: !!item.isCustom
  }));
  
  // Get user ID from page
  const userIdElement = document.querySelector('meta[name="user-id"]');
  if (!userIdElement) {
    console.error('User ID not found in page metadata');
    return;
  }
  
  const userId = userIdElement.getAttribute('content');
  
  // Sync with database
  fetch('/api/sync_cart.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      user_id: userId,
      cart_items: cartItems
    })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      console.log('Cart synced successfully');
      // Clear local cart after successful sync
      localStorage.setItem('DRFCart', '[]');
      // Reload page to show database cart
      window.location.reload();
    } else {
      console.error('Error syncing cart:', data.message);
    }
  })
  .catch(error => {
    console.error('Error syncing cart:', error);
  });
}