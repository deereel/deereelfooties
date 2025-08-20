// Create urgency and scarcity indicators
class UrgencyIndicators {
    constructor() {
        this.init();
    }
    
    init() {
        this.addStockCounters();
        this.addRecentPurchases();
        this.addViewingIndicators();
    }
    
    addStockCounters() {
        const productCards = document.querySelectorAll('.product-card');
        productCards.forEach(card => {
            const stock = Math.floor(Math.random() * 15) + 3; // Random stock 3-17
            const urgencyHTML = `
                <div class="stock-indicator mt-2">
                    ${stock <= 5 ? `<span class="badge bg-danger">Only ${stock} left!</span>` : 
                      stock <= 10 ? `<span class="badge bg-warning">${stock} in stock</span>` : 
                      `<span class="badge bg-success">In Stock</span>`}
                </div>
            `;
            card.insertAdjacentHTML('beforeend', urgencyHTML);
        });
    }
    
    addRecentPurchases() {
        const names = ['John D.', 'Sarah M.', 'Mike O.', 'Lisa K.', 'David R.', 'Emma S.'];
        const locations = ['Lagos', 'Abuja', 'Port Harcourt', 'Kano', 'Ibadan', 'Enugu'];
        
        setInterval(() => {
            const name = names[Math.floor(Math.random() * names.length)];
            const location = locations[Math.floor(Math.random() * locations.length)];
            const timeAgo = Math.floor(Math.random() * 30) + 1;
            
            this.showPurchaseNotification(name, location, timeAgo);
        }, 15000); // Every 15 seconds
    }
    
    showPurchaseNotification(name, location, timeAgo) {
        const notificationHTML = `
            <div id="purchase-notification" class="position-fixed bg-white shadow rounded p-3" 
                 style="bottom: 100px; left: 20px; z-index: 999; max-width: 300px; border-left: 4px solid #28a745;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-shopping-bag text-success me-2"></i>
                    <div>
                        <small class="fw-bold">${name} from ${location}</small>
                        <div class="small text-muted">Just purchased a pair of shoes</div>
                        <div class="small text-success">${timeAgo} minutes ago</div>
                    </div>
                </div>
            </div>
        `;
        
        // Remove existing notification
        const existing = document.getElementById('purchase-notification');
        if (existing) existing.remove();
        
        document.body.insertAdjacentHTML('beforeend', notificationHTML);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            const notification = document.getElementById('purchase-notification');
            if (notification) notification.remove();
        }, 5000);
    }
    
    addViewingIndicators() {
        const productPage = document.querySelector('.product-details');
        if (productPage) {
            const viewers = Math.floor(Math.random() * 12) + 3;
            const viewerHTML = `
                <div class="alert alert-info d-flex align-items-center">
                    <i class="fas fa-eye me-2"></i>
                    <span>${viewers} people are viewing this product right now</span>
                </div>
            `;
            productPage.insertAdjacentHTML('afterbegin', viewerHTML);
        }
    }
}

// Initialize urgency indicators
document.addEventListener('DOMContentLoaded', () => {
    new UrgencyIndicators();
});