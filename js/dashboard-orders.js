// Dashboard Orders Management
class DashboardOrdersManager {
  constructor() {
    this.init();
  }

  init() {
    console.log('Initializing Dashboard Orders Manager');
    this.loadOrders();
  }

  async loadOrders() {
    const container = document.getElementById('orders-container');
    if (!container) return;

    try {
      // Show loading
      container.innerHTML = `
        <div class="text-center py-4">
          <div class="spinner-border text-primary" role="status"></div>
          <p class="mt-2">Loading your orders...</p>
        </div>
      `;

      // Get user data
      const userData = localStorage.getItem('DRFUser');
      if (!userData) {
        container.innerHTML = '<p class="text-center py-4">Please log in to view orders.</p>';
        return;
      }

      const user = JSON.parse(userData);
      const userId = user.user_id || user.id;

      // Fetch orders
      const response = await fetch(`/api/get-orders.php?user_id=${userId}`);
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const data = await response.json();
      console.log('Loaded orders response:', data);

      if (data.success) {
        this.renderOrders(data.orders || []);
      } else {
        container.innerHTML = `<p class="text-center py-4 text-danger">Error: ${data.message}</p>`;
      }
    } catch (error) {
      console.error('Error loading orders:', error);
      container.innerHTML = '<p class="text-center py-4 text-danger">Failed to load orders. Please try again.</p>';
    }
  }

  renderOrders(orders) {
    const container = document.getElementById('orders-container');
    
    if (!orders || orders.length === 0) {
      container.innerHTML = `
        <div class="text-center py-5">
          <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
          <h5>No orders found</h5>
          <p class="text-muted">You haven't placed any orders yet.</p>
          <a href="/products.php" class="btn btn-primary mt-2">Start Shopping</a>
        </div>
      `;
      return;
    }

    const ordersHtml = orders.map(order => {
      const orderDate = new Date(order.created_at).toLocaleDateString();
      const orderStatus = this.getStatusBadge(order.status);
      
      return `
        <div class="card mb-3">
          <div class="card-header bg-light">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <span class="fw-bold">Order #${order.order_id}</span>
                <span class="text-muted ms-3">${orderDate}</span>
              </div>
              <div>
                ${orderStatus}
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-8">
                <h6>Items</h6>
                ${this.renderOrderItems(order.items || [])}
              </div>
              <div class="col-md-4">
                <h6>Order Summary</h6>
                <div class="d-flex justify-content-between mb-2">
                  <span>Subtotal:</span>
                  <span>₦${parseFloat(order.subtotal).toFixed(2)}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                  <span>Shipping:</span>
                  <span>₦${parseFloat(order.shipping).toFixed(2)}</span>
                </div>
                ${order.discount ? `
                <div class="d-flex justify-content-between mb-2 text-success">
                  <span>Discount:</span>
                  <span>-₦${parseFloat(order.discount).toFixed(2)}</span>
                </div>` : ''}
                <div class="d-flex justify-content-between fw-bold mt-2">
                  <span>Total:</span>
                  <span>₦${parseFloat(order.total).toFixed(2)}</span>
                </div>
              </div>
            </div>
            <div class="mt-3">
              <a href="#" class="btn btn-sm btn-outline-primary" onclick="event.preventDefault(); dashboardOrdersManager.viewOrderDetails('${order.order_id}')">
                View Details
              </a>
              ${order.status === 'pending' ? `
              <a href="#" class="btn btn-sm btn-outline-danger ms-2" onclick="event.preventDefault(); dashboardOrdersManager.cancelOrder('${order.order_id}')">
                Cancel Order
              </a>` : ''}
            </div>
          </div>
        </div>
      `;
    }).join('');

    container.innerHTML = ordersHtml;
  }

  renderOrderItems(items) {
    if (!items || items.length === 0) {
      return '<p class="text-muted">No items found</p>';
    }

    return items.map(item => `
      <div class="d-flex mb-2">
        <div class="me-3" style="width: 60px; height: 60px;">
          <img src="${item.image || '/images/product-placeholder.jpg'}" class="img-fluid rounded" alt="${item.product_name || 'Product'}">
        </div>
        <div>
          <p class="mb-0 fw-bold">${item.product_name || 'Product'}</p>
          <p class="mb-0 text-muted small">
            ${item.color ? `Color: ${item.color}` : ''}
            ${item.size ? `Size: ${item.size}` : ''}
            ${item.width ? `Width: ${item.width}` : ''}
          </p>
          <p class="mb-0">
            ₦${parseFloat(item.price).toFixed(2)} × ${item.quantity}
          </p>
        </div>
      </div>
    `).join('');
  }

  getStatusBadge(status) {
    const statusMap = {
      'pending': '<span class="badge bg-warning">Pending</span>',
      'processing': '<span class="badge bg-info">Processing</span>',
      'shipped': '<span class="badge bg-primary">Shipped</span>',
      'delivered': '<span class="badge bg-success">Delivered</span>',
      'cancelled': '<span class="badge bg-danger">Cancelled</span>'
    };
    
    return statusMap[status] || `<span class="badge bg-secondary">${status}</span>`;
  }

  async viewOrderDetails(orderId) {
    try {
      // Show modal with loading state
      const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
      const content = document.getElementById('orderDetailsContent');
      
      content.innerHTML = `
        <div class="text-center py-4">
          <div class="spinner-border text-primary" role="status"></div>
          <p class="mt-2">Loading order details...</p>
        </div>
      `;
      
      modal.show();

      // Get user data
      const userData = localStorage.getItem('DRFUser');
      if (!userData) {
        content.innerHTML = '<p class="text-center py-4 text-danger">Please log in to view order details.</p>';
        return;
      }

      const user = JSON.parse(userData);
      const userId = user.user_id || user.id;

      // Fetch order details
      const response = await fetch(`/api/get-order.php?order_id=${orderId}&user_id=${userId}`);
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const data = await response.json();
      
      if (data.success) {
        this.renderOrderDetailsModal(data.order);
      } else {
        content.innerHTML = `<p class="text-center py-4 text-danger">Error: ${data.message}</p>`;
      }
    } catch (error) {
      console.error('Error loading order details:', error);
      document.getElementById('orderDetailsContent').innerHTML = '<p class="text-center py-4 text-danger">Failed to load order details. Please try again.</p>';
    }
  }

  renderOrderDetailsModal(order) {
    const content = document.getElementById('orderDetailsContent');
    const orderDate = new Date(order.created_at).toLocaleDateString();
    const statusBadge = this.getStatusBadge(order.status);
    
    content.innerHTML = `
      <div class="row mb-4">
        <div class="col-md-6">
          <h6>Order Information</h6>
          <p><strong>Order ID:</strong> #${order.order_id}</p>
          <p><strong>Date:</strong> ${orderDate}</p>
          <p><strong>Status:</strong> ${statusBadge}</p>
        </div>
        <div class="col-md-6">
          <h6>Shipping Address</h6>
          <p>${this.formatShippingAddress(order)}</p>
        </div>
      </div>
      
      <div class="mb-4">
        <h6>Order Items</h6>
        <div class="table-responsive">
          <table class="table table-sm">
            <thead>
              <tr>
                <th>Product</th>
                <th>Details</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              ${(order.items || []).map(item => `
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      <img src="${item.image || '/images/product-placeholder.jpg'}" class="me-2" style="width: 40px; height: 40px; object-fit: cover;" alt="${item.product_name}">
                      <span>${item.product_name}</span>
                    </div>
                  </td>
                  <td>
                    <small>
                      ${item.color ? `Color: ${item.color}<br>` : ''}
                      ${item.size ? `Size: ${item.size}<br>` : ''}
                      ${item.width ? `Width: ${item.width}` : ''}
                    </small>
                  </td>
                  <td>₦${parseFloat(item.price).toFixed(2)}</td>
                  <td>${item.quantity}</td>
                  <td>₦${(parseFloat(item.price) * parseInt(item.quantity)).toFixed(2)}</td>
                </tr>
              `).join('')}
            </tbody>
          </table>
        </div>
      </div>
      
      <div class="row">
        <div class="col-md-6">
          <h6>Payment Information</h6>
          <p><strong>Payment Method:</strong> ${order.payment_method || 'Bank Transfer'}</p>
          <p><strong>Payment Status:</strong> ${this.getPaymentStatusBadge(order.payment_confirmed, order.payment_proof)}</p>
        </div>
        <div class="col-md-6">
          <h6>Order Summary</h6>
          <div class="d-flex justify-content-between mb-2">
            <span>Subtotal:</span>
            <span>₦${parseFloat(order.subtotal).toFixed(2)}</span>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span>Shipping:</span>
            <span>₦${parseFloat(order.shipping).toFixed(2)}</span>
          </div>
          ${order.discount ? `
          <div class="d-flex justify-content-between mb-2 text-success">
            <span>Discount:</span>
            <span>-₦${parseFloat(order.discount).toFixed(2)}</span>
          </div>` : ''}
          <hr>
          <div class="d-flex justify-content-between fw-bold">
            <span>Total:</span>
            <span>₦${parseFloat(order.total).toFixed(2)}</span>
          </div>
        </div>
      </div>
    `;
  }

  formatShippingAddress(order) {
    const parts = [];
    if (order.address) parts.push(order.address);
    if (order.city) parts.push(order.city);
    if (order.state) parts.push(order.state);
    if (order.country) parts.push(order.country);
    return parts.length > 0 ? parts.join(', ') : 'Not provided';
  }

  getPaymentStatusBadge(paymentConfirmed, paymentProof) {
    if (paymentConfirmed == 1) {
      return '<span class="badge bg-success">Confirmed</span>';
    } else if (paymentProof) {
      return '<span class="badge bg-warning">Uploaded</span>';
    } else {
      return '<span class="badge bg-secondary">Pending</span>';
    }
  }

  async cancelOrder(orderId) {
    if (!confirm('Are you sure you want to cancel this order?')) {
      return;
    }

    try {
      // Get user data
      const userData = localStorage.getItem('DRFUser');
      if (!userData) {
        alert('Please log in to cancel an order');
        return;
      }

      const user = JSON.parse(userData);
      const userId = user.user_id || user.id;

      // Cancel order
      const response = await fetch('/api/orders.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          action: 'cancel',
          order_id: orderId,
          user_id: userId
        })
      });
      
      const data = await response.json();
      
      if (data.success) {
        alert('Order cancelled successfully');
        this.loadOrders(); // Reload orders
      } else {
        alert(`Error: ${data.message}`);
      }
    } catch (error) {
      console.error('Error cancelling order:', error);
      alert('Failed to cancel order. Please try again.');
    }
  }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
  // Check if we're on the dashboard page
  if (document.body.getAttribute('data-page') === 'dashboard') {
    window.dashboardOrdersManager = new DashboardOrdersManager();
  }
});