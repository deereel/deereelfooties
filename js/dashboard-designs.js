// Dashboard Designs Management
class DashboardDesignsManager {
  constructor() {
    this.init();
  }

  init() {
    console.log('Initializing Dashboard Designs Manager');
    this.loadDesigns();
  }

  async loadDesigns() {
    const container = document.getElementById('designs-container');
    if (!container) return;

    try {
      // Show loading
      container.innerHTML = `
        <div class="text-center py-4">
          <div class="spinner-border text-primary" role="status"></div>
          <p class="mt-2">Loading your designs...</p>
        </div>
      `;

      // Get user data
      const userData = localStorage.getItem('DRFUser');
      if (!userData) {
        container.innerHTML = '<p class="text-center py-4">Please log in to view your designs.</p>';
        return;
      }

      const user = JSON.parse(userData);
      const userId = user.user_id || user.id;

      // Fetch designs
      const response = await fetch('/api/saved-designs.php');
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const designs = await response.json();
      console.log('Loaded designs:', designs);

      this.currentDesigns = designs;
      this.renderDesigns(designs);
    } catch (error) {
      console.error('Error loading designs:', error);
      container.innerHTML = '<p class="text-center py-4 text-danger">Failed to load designs. Please try again.</p>';
    }
  }

  renderDesigns(designs) {
    const container = document.getElementById('designs-container');
    
    if (!designs || designs.length === 0) {
      container.innerHTML = `
        <div class="text-center py-5">
          <i class="fas fa-palette fa-3x text-muted mb-3"></i>
          <h5>No saved designs found</h5>
          <p class="text-muted">Create your first custom design.</p>
          <a href="/customize.php" class="btn btn-primary mt-2">Start Designing</a>
        </div>
      `;
      return;
    }

    const designsHtml = `
      <div class="row">
        ${designs.map(design => this.renderDesignCard(design)).join('')}
      </div>
    `;

    container.innerHTML = designsHtml;
    
    // Add event listeners for add to cart buttons
    const self = this;
    setTimeout(() => {
      container.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          const designId = this.dataset.designId;
          console.log('Add to cart clicked for design:', designId);
          self.addToCart(designId);
        });
      });
    }, 100);
  }

  renderDesignCard(design) {
    const designDate = new Date(design.created_at).toLocaleDateString();
    const designData = JSON.parse(design.design_data);
    const imageUrl = this.getDesignImage(designData);
    
    return `
      <div class="col-md-4 col-sm-6 mb-4">
        <div class="card h-100">
          <div class="position-relative">
            <img src="${imageUrl}" class="card-img-top" alt="${design.design_name}" style="height: 200px; object-fit: cover;">
            <button class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" 
                    onclick="event.preventDefault(); dashboardDesignsManager.deleteDesign(${design.design_id})">
              <i class="fas fa-times"></i>
            </button>
          </div>
          <div class="card-body">
            <h5 class="card-title">${design.design_name}</h5>
            <p class="card-text text-muted small">Created on ${designDate}</p>
            <p class="card-text">${designData.category} ${designData.type} in ${designData.color} ${designData.material}</p>
            <div class="d-flex justify-content-between mt-3">
              <a href="/customize.php?design_id=${design.design_id}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-edit me-1"></i> Edit
              </a>
              <button class="btn btn-primary btn-sm add-to-cart-btn" data-design-id="${design.design_id}">
                <i class="fas fa-shopping-cart me-1"></i> Add to Cart
              </button>
            </div>
          </div>
        </div>
      </div>
    `;
  }

  getDesignImage(designData) {
    const imageMap = {
      'oxford-black': '/images/Oxford Cap Toe 600.webp',
      'oxford-brown': '/images/Oxford Cap Toe 600.webp',
      'oxford-tan': '/images/Oxford Cap Toe 600.webp',
      'oxford-burgundy': '/images/Oxford Cap Toe 600.webp',
      'oxford-navy': '/images/Oxford Cap Toe 600.webp',
      'derby-black': '/images/cram solid oxford.webp',
      'derby-brown': '/images/cram solid oxford.webp',
      'derby-tan': '/images/cram solid oxford.webp',
      'derby-burgundy': '/images/cram solid oxford.webp',
      'derby-navy': '/images/cram solid oxford.webp',
      'loafer-black': '/images/penny loafer 600.webp',
      'loafer-brown': '/images/penny loafer 600.webp',
      'loafer-tan': '/images/penny loafer 600.webp',
      'loafer-burgundy': '/images/penny loafer 600.webp',
      'loafer-navy': '/images/penny loafer 600.webp',
      'monk-black': '/images/Oxford Cap Toe 600.webp',
      'monk-brown': '/images/Oxford Cap Toe 600.webp',
      'monk-tan': '/images/Oxford Cap Toe 600.webp',
      'monk-burgundy': '/images/Oxford Cap Toe 600.webp',
      'monk-navy': '/images/Oxford Cap Toe 600.webp'
    };
    
    const key = `${designData.type || designData.style}-${designData.color}`;
    return imageMap[key] || '/images/Oxford Cap Toe 600.webp';
  }

  async deleteDesign(designId) {
    if (!confirm('Are you sure you want to delete this design?')) {
      return;
    }
    
    try {
      const response = await fetch('/api/delete_design.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          design_id: designId
        })
      });
      
      const data = await response.json();
      
      if (data.success) {
        alert('Design deleted successfully');
        this.loadDesigns();
      } else {
        alert(`Error: ${data.message}`);
      }
    } catch (error) {
      console.error('Error deleting design:', error);
      alert('Failed to delete design. Please try again.');
    }
  }
  
  addToCart(designId) {
    console.log('addToCart called with ID:', designId);
    console.log('Current designs:', this.currentDesigns);
    
    // Find the design data
    const designs = this.currentDesigns || [];
    const design = designs.find(d => d.design_id == designId);
    
    if (!design) {
      console.error('Design not found for ID:', designId);
      alert('Design not found');
      return;
    }
    
    console.log('Found design:', design);
    
    const designData = JSON.parse(design.design_data);
    
    // Calculate price
    const basePrices = { oxford: 85000, derby: 82000, loafer: 78000, monk: 88000 };
    const materialPrices = { calf: 0, suede: 10000, patent: 15000 };
    const price = (basePrices[designData.type] || basePrices[designData.style] || 85000) + (materialPrices[designData.material] || 0);
    
    // Create cart item
    const cartItem = {
      id: `custom_${designId}`,
      name: `Custom ${designData.type || designData.style}`,
      price: price,
      quantity: 1,
      image: this.getDesignImage(designData),
      custom_design: true,
      design_id: designId,
      specifications: `${designData.style} in ${designData.color} ${designData.material}, Size ${designData.size}`
    };
    
    // Add to cart using the cart handler's method
    if (window.cartHandler) {
      // Use the cart handler's addToCart method with proper structure
      window.cartHandler.addToCart({
        product_id: cartItem.id,
        product_name: cartItem.name,
        name: cartItem.name,
        price: cartItem.price,
        quantity: cartItem.quantity,
        image: cartItem.image,
        color: designData.color,
        size: designData.size,
        width: '',
        custom_design: true,
        design_id: designId
      }).then(() => {
        alert('Custom design added to cart!');
      }).catch(() => {
        alert('Error adding design to cart');
      });
    } else {
      alert('Cart system not available');
    }
  }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
  // Check if we're on the dashboard page
  if (document.body.getAttribute('data-page') === 'dashboard') {
    window.dashboardDesignsManager = new DashboardDesignsManager();
  }
});