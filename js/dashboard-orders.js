document.addEventListener('DOMContentLoaded', function() {
  console.log('Dashboard orders script loaded');
  
  // Listen for the loadOrders custom event
  document.addEventListener('loadOrders', function() {
    console.log('loadOrders event received');
    loadOrders();
  });
  
  // Also listen for clicks on the orders tab
  const ordersLink = document.querySelector('a[data-section="orders"]');
  if (ordersLink) {
    ordersLink.addEventListener('click', function() {
      console.log('Orders tab clicked');
      setTimeout(loadOrders, 100); // Small delay to ensure the section is visible
    });
  }
  
  // Check if hash is #orders on page load
  if (window.location.hash === '#orders') {
    console.log('Orders hash detected on load');
    setTimeout(loadOrders, 500); // Longer delay on initial load
  }
  
  function loadOrders() {
    console.log('Loading orders');
    
    // Find the orders section
    const ordersSection = document.getElementById('orders-section');
    if (!ordersSection) {
      console.error('Orders section not found');
      return;
    }
    
    // Clear the orders section first
    ordersSection.innerHTML = '<h3 class="mb-4">My Orders</h3>';
    
    // Create a new container
    const container = document.createElement('div');
    container.id = 'orders-container';
    ordersSection.appendChild(container);
    
    // Load orders into the new container
    loadOrdersData(container);
  }
  
  function loadOrdersData(container) {
    // Show loading indicator
    container.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading your orders...</p></div>';
    
    // Check if user is logged in
    if (!window.app || !window.app.auth || !window.app.auth.isLoggedIn()) {
      console.error('User not logged in');
      container.innerHTML = '<div class="alert alert-warning">Please log in to view your orders.</div>';
      return;
    }
    
    const user = window.app.auth.getCurrentUser();
    console.log('Current user:', user);
    
    const userId = user.user_id || user.id;
    console.log('Using user ID:', userId);
    
    // Fetch orders
    fetch(`/api/orders.php?user_id=${userId}`)
      .then(response => {
        console.log('API response status:', response.status);
        return response.json();
      })
      .then(data => {
        console.log('Orders data:', data);
        
        if (data.success && data.data && data.data.length > 0) {
          // Render orders
          let ordersHTML = '';
          
          data.data.forEach(order => {
            const orderDate = new Date(order.created_at).toLocaleDateString();
            
            ordersHTML += `
              <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <div>
                    <h5 class="mb-0">Order #${order.order_id}</h5>
                    <small class="text-muted">Placed on ${orderDate}</small>
                  </div>
                  <div>
                    <span class="badge bg-primary">${order.order_status}</span>
                    <span class="badge bg-info ms-2">${order.payment_status}</span>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row mb-3">
                    <div class="col-md-6">
                      <h6>Shipping Address</h6>
                      <p class="mb-0">${order.shipping_address}</p>
                      <p class="mb-0">${order.state}</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                      <h6>Total</h6>
                      <p class="h4">₦${parseFloat(order.total).toLocaleString()}</p>
                    </div>
                  </div>
                  <a href="/checkout.php?order_id=${order.order_id}" class="btn-primary">View Order Details</a>
                </div>
              </div>
            `;
          });
          
          container.innerHTML = ordersHTML;
        } else {
          // No orders found
          container.innerHTML = `
            <div class="alert alert-info">
              <i class="fas fa-info-circle me-2"></i> You haven't placed any orders yet.
              <a href="/products.php" class="alert-link">Start shopping</a>
            </div>
          `;
        }
      })
      .catch(error => {
        console.error('Error fetching orders:', error);
        container.innerHTML = `
          <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i> Error loading orders. Please try again later.
          </div>
        `;
      });
  }
});
