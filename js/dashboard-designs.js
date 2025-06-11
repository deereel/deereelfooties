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
      const response = await fetch(`/api/get-designs.php?user_id=${userId}`);
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const data = await response.json();
      console.log('Loaded designs response:', data);

      if (data.success) {
        this.renderDesigns(data.designs || []);
      } else {
        container.innerHTML = `<p class="text-center py-4 text-danger">Error: ${data.message}</p>`;
      }
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
  }

  renderDesignCard(design) {
    const designDate = new Date(design.created_at).toLocaleDateString();
    const imageUrl = design.image_url || '/images/design-placeholder.jpg';
    
    return `
      <div class="col-md-4 col-sm-6 mb-4">
        <div class="card h-100">
          <div class="position-relative">
            <img src="${imageUrl}" class="card-img-top" alt="${design.name}" style="height: 200px; object-fit: cover;">
            <button class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" 
                    onclick="event.preventDefault(); dashboardDesignsManager.deleteDesign(${design.id})">
              <i class="fas fa-times"></i>
            </button>
          </div>
          <div class="card-body">
            <h5 class="card-title">${design.name}</h5>
            <p class="card-text text-muted small">Created on ${designDate}</p>
            <p class="card-text">${design.description || 'No description'}</p>
            <div class="d-flex justify-content-between mt-3">
              <a href="/customize.php?design_id=${design.id}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-edit me-1"></i> Edit
              </a>
              <a href="/product.php?design_id=${design.id}" class="btn btn-primary btn-sm">
                <i class="fas fa-shopping-cart me-1"></i> Order
              </a>
            </div>
          </div>
        </div>
      </div>
    `;
  }

  async deleteDesign(designId) {
    if (!confirm('Are you sure you want to delete this design?')) {
      return;
    }
    
    try {
      // Get user data
      const userData = localStorage.getItem('DRFUser');
      if (!userData) {
        alert('Please log in to manage your designs');
        return;
      }

      const user = JSON.parse(userData);
      const userId = user.user_id || user.id;
      
      // Delete design
      const response = await fetch('/api/delete_design.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          user_id: userId,
          design_id: designId
        })
      });
      
      const data = await response.json();
      
      if (data.success) {
        // Show success message
        alert('Design deleted successfully');
        
        // Reload designs
        this.loadDesigns();
      } else {
        alert(`Error: ${data.message}`);
      }
    } catch (error) {
      console.error('Error deleting design:', error);
      alert('Failed to delete design. Please try again.');
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