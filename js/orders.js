document.addEventListener('DOMContentLoaded', function() {
  // Check if user is logged in
  const checkAuth = setInterval(() => {
    if (window.app && window.app.auth) {
      clearInterval(checkAuth);
      initOrdersModule();
    }
  }, 100);
  
  // Timeout after 5 seconds
  setTimeout(() => clearInterval(checkAuth), 5000);
  
  function initOrdersModule() {
    // Only initialize if user is logged in
    if (!window.app.auth.isLoggedIn()) {
      return;
    }
    
    const user = window.app.auth.getCurrentUser();
    
    // Load user orders
    loadUserOrders(user.id);
    
    // Handle payment proof upload
    const paymentProofForm = document.getElementById('payment-proof-form');
    if (paymentProofForm) {
      paymentProofForm.addEventListener('submit', function(e) {
        e.preventDefault();
        uploadPaymentProof(this);
      });
    }
  }
  
  // Load user orders from API
  function loadUserOrders(userId) {
    const ordersSection = document.getElementById('orders-section');
    if (!ordersSection) return;
    
    // Show loading state
    ordersSection.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading your orders...</p></div>';
    
    // Fetch orders from API
    fetch(`/api/orders.php?user_id=${userId}`)
      .then(response => response.json())
      .then(data => {
        if (data.success && data.data.length > 0) {
          // Render orders
          let ordersHTML = `
            <h3 class="mb-4">My Orders</h3>
            <p class="text-muted mb-4">View and manage your orders. From here you can track shipments and request returns.</p>
          `;
          
          data.data.forEach(order => {
            ordersHTML += `
              <div class="card mb-3">
                <div class="card-header bg-light d-flex justify-content-between">
                  <span>Order #${order.order_id}</span>
                  <span>${new Date(order.order_date).toLocaleDateString()}</span>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-7">
                      <h5>Order Total: ₦${parseFloat(order.total_amount).toLocaleString()}</h5>
                      <p class="text-muted">Status: <span class="badge ${getStatusBadgeClass(order.status)}">${order.status}</span></p>
                    </div>
                    <div class="col-md-5 text-end">
                      <a href="#" class="btn-primary btn-sm view-order-details" data-order-id="${order.order_id}">View Details</a>
                      ${order.status === 'Pending' ? 
                        `<a href="#" class="btn-outline-secondary btn-sm ms-2 upload-payment" data-order-id="${order.order_id}" data-bs-toggle="modal" data-bs-target="#paymentProofModal">Upload Payment</a>` : ''}
                    </div>
                  </div>
                </div>
              </div>
            `;
          });
          
          ordersSection.innerHTML = ordersHTML;
          
          // Add event listeners to view details buttons
          document.querySelectorAll('.view-order-details').forEach(btn => {
            btn.addEventListener('click', function(e) {
              e.preventDefault();
              const orderId = this.getAttribute('data-order-id');
              viewOrderDetails(orderId);
            });
          });
          
          // Add event listeners to upload payment buttons
          document.querySelectorAll('.upload-payment').forEach(btn => {
            btn.addEventListener('click', function(e) {
              e.preventDefault();
              const orderId = this.getAttribute('data-order-id');
              document.getElementById('order-id-input').value = orderId;
            });
          });
        } else {
          // No orders found
          ordersSection.innerHTML = `
            <h3 class="mb-4">My Orders</h3>
            <p class="text-muted mb-4">View and manage your orders. From here you can track shipments and request returns.</p>
            <div class="alert alert-info">
              <i class="fas fa-info-circle me-2"></i> You have no orders yet.
            </div>
          `;
        }
      })
      .catch(error => {
        console.error('Error fetching orders:', error);
        ordersSection.innerHTML = `
          <h3 class="mb-4">My Orders</h3>
          <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i> Error loading orders. Please try again later.
          </div>
        `;
      });
  }
  
  // View order details
  function viewOrderDetails(orderId) {
    // Fetch order details and progress
    Promise.all([
      fetch(`/api/orders.php?order_id=${orderId}`).then(res => res.json()),
      fetch(`/api/order_progress.php?order_id=${orderId}`).then(res => res.json())
    ])
    .then(([orderData, progressData]) => {
      if (orderData.success && progressData.success) {
        const order = orderData.data;
        const progress = progressData.data;
        
        // Create modal for order details
        const modalHTML = `
          <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Order #${order.order_id} Details</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row mb-4">
                    <div class="col-md-6">
                      <p><strong>Order Date:</strong> ${new Date(order.order_date).toLocaleDateString()}</p>
                      <p><strong>Total Amount:</strong> ₦${parseFloat(order.total_amount).toLocaleString()}</p>
                    </div>
                    <div class="col-md-6">
                      <p><strong>Status:</strong> <span class="badge ${getStatusBadgeClass(order.status)}">${order.status}</span></p>
                    </div>
                  </div>
                  
                  <h6 class="mb-3">Order Progress</h6>
                  <div class="timeline">
                    ${progress.map(item => `
                      <div class="timeline-item">
                        <div class="timeline-date">${new Date(item.update_date).toLocaleString()}</div>
                        <div class="timeline-content">
                          <p>${item.status_update}</p>
                        </div>
                      </div>
                    `).join('')}
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
        `;
        
        // Add modal to body
        const modalContainer = document.createElement('div');
        modalContainer.innerHTML = modalHTML;
        document.body.appendChild(modalContainer);
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
        modal.show();
        
        // Remove modal from DOM when hidden
        document.getElementById('orderDetailsModal').addEventListener('hidden.bs.modal', function() {
          document.body.removeChild(modalContainer);
        });
      } else {
        alert('Error loading order details');
      }
    })
    .catch(error => {
      console.error('Error fetching order details:', error);
      alert('Error loading order details');
    });
  }
  
  // Upload payment proof
  function uploadPaymentProof(form) {
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Uploading...';
    
    fetch('/api/payment_proof.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Hide modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('paymentProofModal'));
        modal.hide();
        
        // Show success message
        alert('Payment proof uploaded successfully');
        
        // Reload orders
        const user = window.app.auth.getCurrentUser();
        loadUserOrders(user.id);
      } else {
        alert('Error: ' + data.message);
      }
    })
    .catch(error => {
      console.error('Error uploading payment proof:', error);
      alert('Error uploading payment proof');
    })
    .finally(() => {
      // Reset button
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalBtnText;
    });
  }
  
  // Helper function to get badge class based on status
  function getStatusBadgeClass(status) {
    switch (status) {
      case 'Pending':
        return 'bg-warning text-dark';
      case 'Processing':
        return 'bg-info text-dark';
      case 'Shipped':
        return 'bg-primary';
      case 'Delivered':
        return 'bg-success';
      case 'Cancelled':
        return 'bg-danger';
      default:
        return 'bg-secondary';
    }
  }
});