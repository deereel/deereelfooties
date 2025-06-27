class OrderCompletionHandler {
    constructor() {
        this.init();
    }
    
    init() {
        // Listen for successful order completion
        document.addEventListener('orderCompleted', this.handleOrderCompletion.bind(this));
    }
    
    async handleOrderCompletion(event) {
        console.log('Order completed, clearing cart...');
        
        try {
            if (window.cartHandler) {
                await window.cartHandler.clearCartAfterOrder();
                console.log('Cart cleared after successful order');
            }
            
            // Redirect to success page or show success message
            this.showOrderSuccessMessage(event.detail);
            
        } catch (error) {
            console.error('Error clearing cart after order:', error);
        }
    }
    
    showOrderSuccessMessage(orderDetails) {
        // Show success modal or redirect
        const successModal = document.getElementById('orderSuccessModal');
        if (successModal) {
            // Update modal content with order details
            const orderNumber = successModal.querySelector('.order-number');
            if (orderNumber && orderDetails.order_id) {
                orderNumber.textContent = orderDetails.order_id;
            }
            
            // Show modal
            if (typeof bootstrap !== 'undefined') {
                new bootstrap.Modal(successModal).show();
            }
        } else {
            // Fallback: redirect to success page
            window.location.href = `/order-success.php?order_id=${orderDetails.order_id}`;
        }
    }
}

// Initialize order completion handler
document.addEventListener('DOMContentLoaded', function() {
    new OrderCompletionHandler();
});

// Function to call when order is successfully placed
function completeOrder(orderDetails) {
    // Dispatch custom event
    const event = new CustomEvent('orderCompleted', {
        detail: orderDetails
    });
    document.dispatchEvent(event);
}