// Payment Gateway Integration
class PaymentHandler {
    constructor() {
        this.publicKey = 'pk_test_YOUR_ACTUAL_PUBLIC_KEY'; // Replace with your actual public key
    }
    
    async initializePayment(orderId) {
        try {
            const response = await fetch('/api/initialize-payment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ order_id: orderId })
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.openPaymentModal(data.authorization_url, data.reference);
            } else {
                alert('Payment initialization failed: ' + data.message);
            }
        } catch (error) {
            alert('Error initializing payment: ' + error.message);
        }
    }
    
    openPaymentModal(authUrl, reference) {
        // Open Paystack popup
        window.location.href = authUrl;
    }
    
    // Alternative: Use Paystack inline popup
    payWithPaystack(email, amount, orderId) {
        const handler = PaystackPop.setup({
            key: this.publicKey,
            email: email,
            amount: amount * 100, // Convert to kobo
            ref: 'order_' + orderId + '_' + Math.floor((Math.random() * 1000000000) + 1),
            metadata: {
                order_id: orderId
            },
            callback: function(response) {
                // Payment successful
                window.location.href = '/api/payment-callback.php?reference=' + response.reference;
            },
            onClose: function() {
                alert('Payment cancelled');
            }
        });
        handler.openIframe();
    }
}

// Initialize payment handler
const paymentHandler = new PaymentHandler();

// Add event listeners for payment buttons
document.addEventListener('DOMContentLoaded', function() {
    const payButtons = document.querySelectorAll('.pay-now-btn');
    payButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            const email = this.dataset.email;
            const amount = this.dataset.amount;
            
            if (orderId && email && amount) {
                paymentHandler.payWithPaystack(email, amount, orderId);
            } else {
                paymentHandler.initializePayment(orderId);
            }
        });
    });
});