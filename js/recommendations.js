// Smart product recommendations
class RecommendationEngine {
    constructor() {
        this.viewedProducts = JSON.parse(localStorage.getItem('viewedProducts') || '[]');
        this.init();
    }
    
    init() {
        this.trackProductView();
        this.showRecommendations();
    }
    
    trackProductView() {
        // Track when user views a product
        const productId = this.getProductIdFromUrl();
        if (productId && !this.viewedProducts.includes(productId)) {
            this.viewedProducts.push(productId);
            if (this.viewedProducts.length > 10) {
                this.viewedProducts.shift(); // Keep only last 10
            }
            localStorage.setItem('viewedProducts', JSON.stringify(this.viewedProducts));
        }
    }
    
    async showRecommendations() {
        const container = document.getElementById('recommendations-container');
        if (!container || this.viewedProducts.length === 0) return;
        
        try {
            const response = await fetch('/api/get-recommendations.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ viewed_products: this.viewedProducts })
            });
            
            const data = await response.json();
            if (data.success && data.products.length > 0) {
                this.renderRecommendations(data.products);
            }
        } catch (error) {
            console.error('Error loading recommendations:', error);
        }
    }
    
    renderRecommendations(products) {
        const container = document.getElementById('recommendations-container');
        const html = `
            <div class="row">
                <div class="col-12 mb-3">
                    <h4>Recommended for You</h4>
                    <p class="text-muted">Based on your browsing history</p>
                </div>
                ${products.slice(0, 4).map(product => `
                    <div class="col-md-3 mb-3">
                        <div class="card h-100">
                            <img src="${product.image}" class="card-img-top" alt="${product.name}">
                            <div class="card-body">
                                <h6 class="card-title">${product.name}</h6>
                                <p class="text-primary fw-bold">₦${parseFloat(product.price).toLocaleString()}</p>
                                <a href="/product.php?id=${product.id}" class="btn btn-outline-primary btn-sm">View Product</a>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
        container.innerHTML = html;
    }
    
    getProductIdFromUrl() {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('id');
    }
}

// Initialize recommendations
document.addEventListener('DOMContentLoaded', () => {
    new RecommendationEngine();
});