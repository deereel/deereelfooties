document.addEventListener('DOMContentLoaded', function() {
  // Get order ID from URL
  const urlParams = new URLSearchParams(window.location.search);
  const orderId = urlParams.get('order_id');
  
  if (!orderId) {
    window.location.href = '/index.php';
    return;
  }
  
  // Display order number
  document.getElementById('order-number').textContent = orderId;
  
  // Update WhatsApp link with order number
  const whatsappLink = document.getElementById('whatsapp-link');
  whatsappLink.href = whatsappLink.href + orderId;
  
  // Fetch order details
  fetch(`/api/orders.php?order_id=${orderId}`)
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        displayOrderDetails(data.data);
      } else {
        console.error('Error fetching order:', data.message);
      }
    })
    .catch(error => {
      console.error('Error:', error);
    });
  
  // Set up progress button
  const viewProgressBtn = document.getElementById('view-progress-btn');
  const progressContainer = document.getElementById('progress-container');
  
  viewProgressBtn.addEventListener('click', function() {
    progressContainer.classList.toggle('hidden');
    updateOrderProgress(orderId);
  });
});

function displayOrderDetails(order) {
  // Display shipping information
  document.getElementById('shipping-name').textContent = order.customer_name;
  document.getElementById('shipping-address').textContent = order.shipping_address;
  document.getElementById('shipping-state').textContent = order.state;
  
  // Display order items
  const orderDetailsContainer = document.getElementById('order-details');
  let orderItemsHTML = '';
  let subtotal = 0;
  let accessoriesTotal = 0;
  
  order.items.forEach(item => {
    const itemTotal = parseFloat(item.price) * item.quantity;
    
    // Check if item is an accessory
    if (item.product_id.includes('accessory')) {
      accessoriesTotal += itemTotal;
    } else {
      subtotal += itemTotal;
    }
    
    orderItemsHTML += `
      <div class="flex justify-between mb-2">
        <div>
          <span class="font-medium">${item.product_name}</span>
          <div class="text-sm text-gray-600">
            Size: ${item.size} | Width: ${item.width} | Color: ${item.color}
          </div>
          <div class="text-sm text-gray-600">Qty: ${item.quantity}</div>
        </div>
        <div class="text-right">
          <div>₦${parseFloat(item.price).toLocaleString()}</div>
          <div class="font-medium">₦${itemTotal.toLocaleString()}</div>
        </div>
      </div>
    `;
  });
  
  orderDetailsContainer.innerHTML = orderItemsHTML;
  
  // Update summary
  document.getElementById('summary-subtotal').textContent = `₦${subtotal.toLocaleString()}`;
  document.getElementById('summary-accessories').textContent = `₦${accessoriesTotal.toLocaleString()}`;
  
  // Set shipping based on state and subtotal
  const state = order.state;
  const isLagos = state === 'Lagos';
  const lagosThreshold = 150000;
  const otherThreshold = 250000;
  const threshold = isLagos ? lagosThreshold : otherThreshold;
  
  let shippingText = 'Depends on location';
  if (subtotal >= threshold) {
    shippingText = 'FREE';
  }
  
  document.getElementById('summary-shipping').textContent = shippingText;
  document.getElementById('summary-total').textContent = `₦${parseFloat(order.total).toLocaleString()}`;
  
  // Update payment status
  updatePaymentStatus(order.payment_status);
}

function updatePaymentStatus(status) {
  const paymentStatusElement = document.getElementById('payment-status');
  
  switch (status) {
    case 'confirmed':
      paymentStatusElement.innerHTML = `
        <span class="inline-block px-3 py-1 rounded-full bg-green-100 text-green-800">
          <i class="fas fa-check-circle mr-1"></i> Payment Confirmed
        </span>
      `;
      break;
    case 'uploaded':
      paymentStatusElement.innerHTML = `
        <span class="inline-block px-3 py-1 rounded-full bg-blue-100 text-blue-800">
          <i class="fas fa-file-upload mr-1"></i> Proof Uploaded, Awaiting Confirmation
        </span>
      `;
      break;
    case 'failed':
      paymentStatusElement.innerHTML = `
        <span class="inline-block px-3 py-1 rounded-full bg-red-100 text-red-800">
          <i class="fas fa-times-circle mr-1"></i> Payment Failed
        </span>
      `;
      break;
    default:
      paymentStatusElement.innerHTML = `
        <span class="inline-block px-3 py-1 rounded-full bg-yellow-100 text-yellow-800">
          <i class="fas fa-clock mr-1"></i> Awaiting Payment
        </span>
      `;
  }
}

function updateOrderProgress(orderId) {
  // Fetch order to get creation date
  fetch(`/api/orders.php?order_id=${orderId}`)
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        const order = data.data;
        const createdDate = new Date(order.created_at);
        const currentDate = new Date();
        
        // Calculate days since order creation
        const diffTime = Math.abs(currentDate - createdDate);
        const diffDays = Math.min(7, Math.floor(diffTime / (1000 * 60 * 60 * 24)));
        
        // Update progress bar
        const progressBar = document.getElementById('progress-bar');
        const progressDays = document.getElementById('progress-days');
        
        const progressPercentage = Math.min(100, (diffDays / 7) * 100);
        progressBar.style.width = `${progressPercentage}%`;
        progressDays.textContent = `Day ${diffDays} of 7`;
        
        // Change color based on progress
        if (progressPercentage >= 100) {
          progressBar.classList.remove('bg-blue-500');
          progressBar.classList.add('bg-green-500');
        }
      }
    })
    .catch(error => {
      console.error('Error:', error);
    });
}