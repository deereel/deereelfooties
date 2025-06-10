document.addEventListener('DOMContentLoaded', function() {
  console.log('Dashboard script loaded');
  
  // Initialize dashboard functionality
  initDashboard();
  
  function initDashboard() {
    console.log('Initializing dashboard...');
    
    // Get user data
    const user = getUserData();
    console.log('User data:', user);
    
    if (!user) {
      console.error('No user data found');
      window.location.href = '/login.php';
      return;
    }
    
    // Update username in dashboard
    updateUsername(user);
    
    // Handle navigation
    setupNavigation();
    
    // Handle dashboard logout
    setupLogout();
    
    // Handle form submissions
    setupForms();
    
    // Check for hash in URL or show default section
    setTimeout(() => {
      checkUrlHash();
    }, 100);
    
    console.log('Dashboard initialization complete');
  }
  
  function getUserData() {
    const userData = localStorage.getItem('DRFUser');
    if (userData) {
      try {
        return JSON.parse(userData);
      } catch (e) {
        console.error('Error parsing user data:', e);
      }
    }
    return null;
  }
  
  function updateUsername(user) {
    const dashboardUsername = document.getElementById('dashboard-username');
    if (dashboardUsername && user && user.name) {
      dashboardUsername.textContent = user.name + "'s Account";
    }
  }
  
  function setupNavigation() {
    console.log('Setting up navigation...');
    
    // Get all navigation links
    const navLinks = document.querySelectorAll('[data-section]');
    console.log('Found navigation links:', navLinks.length);
    
    navLinks.forEach((link, index) => {
      console.log(`Nav link ${index}:`, link.getAttribute('data-section'));
      
      link.addEventListener('click', function(e) {
        e.preventDefault();
        
        const section = this.getAttribute('data-section');
        console.log('Clicked section:', section);
        
        if (!section) return;
        
        // Update active states
        updateActiveStates(section);
        
        // Show the selected section
        showSection(section);
        
        // Load section data
        loadSectionData(section);
        
        // Update URL
        window.location.hash = section;
      });
    });
  }
  
  function updateActiveStates(activeSection) {
    // Remove active from all nav links
    document.querySelectorAll('[data-section]').forEach(link => {
      link.classList.remove('active', 'list-group-item-primary');
    });
    
    // Add active to clicked link
    const activeLink = document.querySelector(`[data-section="${activeSection}"]`);
    if (activeLink) {
      activeLink.classList.add('active', 'list-group-item-primary');
    }
  }
  
  function showSection(sectionName) {
    console.log('Showing section:', sectionName);
    
    // Hide all sections
    const allSections = document.querySelectorAll('.dashboard-content');
    allSections.forEach(section => {
      section.style.display = 'none';
      section.classList.remove('active');
    });
    
    // Show target section
    const targetSection = document.getElementById(sectionName + '-section') || 
                         document.getElementById(sectionName);
    
    if (targetSection) {
      targetSection.style.display = 'block';
      targetSection.classList.add('active');
      console.log('Section shown:', targetSection.id);
    } else {
      console.error('Section not found:', sectionName);
      // Try to find any section that contains the name
      const fallbackSection = document.querySelector(`[id*="${sectionName}"]`);
      if (fallbackSection) {
        fallbackSection.style.display = 'block';
        fallbackSection.classList.add('active');
        console.log('Fallback section shown:', fallbackSection.id);
      }
    }
  }
  
  function loadSectionData(section) {
    console.log('Loading data for section:', section);
    
    switch(section) {
      case 'designs':
        loadSavedDesigns();
        break;
      case 'wishlist':
        loadWishlist();
        break;
      case 'orders':
        loadOrders();
        break;
      case 'personal':
        loadPersonalInfo();
        break;
      default:
        console.log('No data loader for section:', section);
    }
  }
  
  function checkUrlHash() {
    const hash = window.location.hash.substring(1);
    console.log('Checking URL hash:', hash);
    
    if (hash) {
      const link = document.querySelector(`[data-section="${hash}"]`);
      if (link) {
        console.log('Triggering click for hash:', hash);
        link.click();
        return;
      }
    }
    
    // Show default section
    const defaultLink = document.querySelector('[data-section="personal"]') ||
                       document.querySelector('[data-section]');
    if (defaultLink) {
      console.log('Showing default section');
      defaultLink.click();
    }
  }
  
  function loadPersonalInfo() {
    const user = getUserData();
    if (!user) return;
    
    // Populate form fields
    const fields = {
      'fullName': user.name,
      'email': user.email,
      'phone': user.phone,
      'gender': user.gender
    };
    
    Object.keys(fields).forEach(fieldId => {
      const field = document.getElementById(fieldId);
      if (field && fields[fieldId]) {
        field.value = fields[fieldId];
      }
    });
  }
  
  function loadSavedDesigns() {
    const user = getUserData();
    if (!user) return;
    
    const userId = user.user_id || user.id;
    const container = document.getElementById('saved-designs-container');
    
    if (!container) {
      console.error('Designs container not found');
      return;
    }
    
    // Show loading
    container.innerHTML = `
      <div class="text-center py-5">
        <div class="spinner-border text-primary" role="status"></div>
        <p class="mt-2">Loading your designs...</p>
      </div>
    `;
    
    // Fetch from API
    fetch(`/api/get-designs.php?user_id=${userId}`)
      .then(response => response.json())
      .then(data => {
        console.log('Designs response:', data);
        
        if (data.success && data.designs && data.designs.length > 0) {
          renderDesigns(data.designs, container);
        } else {
          container.innerHTML = `
            <div class="alert alert-info">
              <i class="fas fa-info-circle me-2"></i> You have no saved designs.
              <a href="/customize.php" class="alert-link">Create a custom design</a>
            </div>
          `;
        }
      })
      .catch(error => {
        console.error('Error loading designs:', error);
        container.innerHTML = `
          <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i> Error loading designs.
            <button class="btn btn-sm btn-outline-primary ms-2" onclick="loadSavedDesigns()">Retry</button>
          </div>
        `;
      });
  }
  
  function renderDesigns(designs, container) {
    let html = '<div class="row">';
    
    designs.forEach(design => {
      const designData = JSON.parse(design.design_data || '{}');
      const createdAt = new Date(design.created_at).toLocaleDateString();
      
      html += `
        <div class="col-md-4 mb-4">
          <div class="card h-100">
            <img src="${designData.image || '/images/default-shoe.jpg'}" 
                 class="card-img-top" alt="${designData.name || 'Design'}"
                 style="height: 200px; object-fit: cover;">
            <div class="card-body">
              <h5 class="card-title">${designData.name || 'Custom Design'}</h5>
              <p class="text-muted small">Created: ${createdAt}</p>
              <p class="mb-1"><strong>Color:</strong> ${designData.color || 'N/A'}</p>
              <p class="mb-1"><strong>Size:</strong> ${designData.size || 'N/A'}</p>
              <p class="text-primary">₦${(designData.price || 0).toLocaleString()}</p>
              <div class="d-flex gap-2">
                <button class="btn btn-primary btn-sm flex-fill">Add to Cart</button>
                <button class="btn btn-outline-danger btn-sm" onclick="deleteDesign(${design.id})">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      `;
    });
    
    html += '</div>';
    container.innerHTML = html;
  }
  
  function loadWishlist() {
    const user = getUserData();
    if (!user) return;
    
    const userId = user.user_id || user.id;
    const container = document.getElementById('wishlist-container');
    
    if (!container) {
      console.error('Wishlist container not found');
      return;
    }
    
    // Show loading
    container.innerHTML = `
      <div class="text-center py-5">
        <div class="spinner-border text-primary" role="status"></div>
        <p class="mt-2">Loading your wishlist...</p>
      </div>
    `;
    
    // Fetch from API
    fetch(`/api/wishlist.php?user_id=${userId}`)
      .then(response => response.json())
      .then(data => {
        console.log('Wishlist response:', data);
        
        if (data.success && data.items && data.items.length > 0) {
          renderWishlist(data.items, container);
        } else {
          container.innerHTML = `
            <div class="alert alert-info">
              <i class="fas fa-heart me-2"></i> Your wishlist is empty.
              <a href="/products.php" class="alert-link">Browse products</a>
            </div>
          `;
        }
      })
      .catch(error => {
        console.error('Error loading wishlist:', error);
        container.innerHTML = `
          <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i> Error loading wishlist.
            <button class="btn btn-sm btn-outline-primary ms-2" onclick="loadWishlist()">Retry</button>
          </div>
        `;
      });
  }
  
  function renderWishlist(items, container) {
    let html = '<div class="row">';
    
    items.forEach(item => {
      html += `
        <div class="col-md-4 mb-4">
          <div class="card h-100">
            <img src="${item.image || '/images/default-product.jpg'}" 
                 class="card-img-top" alt="${item.product_name}"
                 style="height: 200px; object-fit: cover;">
            <div class="card-body">
              <h5 class="card-title">${item.product_name}</h5>
              <p class="text-primary">₦${parseFloat(item.price).toLocaleString()}</p>
              <div class="d-flex gap-2">
                <button class="btn btn-primary btn-sm flex-fill">Add to Cart</button>
                <button class="btn btn-outline-danger btn-sm" onclick="removeFromWishlist(${item.id})">
                  Remove
                </button>
              </div>
            </div>
          </div>
        </div>
      `;
    });
    
    html += '</div>';
    container.innerHTML = html;
  }
  
  function loadOrders() {
    const user = getUserData();
    if (!user) return;
    
    const userId = user.user_id || user.id;
    const container = document.getElementById('orders-container');
    
    if (!container) {
      console.error('Orders container not found');
      return;
    }
    
    // Show loading
    container.innerHTML = `
      <div class="text-center py-5">
        <div class="spinner-border text-primary" role="status"></div>
        <p class="mt-2">Loading your orders...</p>
      </div>
    `;
    
    // Fetch from API
    fetch(`/api/orders.php?user_id=${userId}`)
      .then(response => response.json())
      .then(data => {
        console.log('Orders response:', data);
        
        if (data.success && data.orders && data.orders.length > 0) {
          renderOrders(data.orders, container);
        } else {
          container.innerHTML = `
            <div class="alert alert-info">
              <i class="fas fa-shopping-bag me-2"></i> You have no orders yet.
              <a href="/products.php" class="alert-link">Start shopping</a>
            </div>
          `;
        }
      })
      .catch(error => {
        console.error('Error loading orders:', error);
        container.innerHTML = `
          <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i> Error loading orders.
            <button class="btn btn-sm btn-outline-primary ms-2" onclick="loadOrders()">Retry</button>
          </div>
        `;
      });
  }
  
  function renderOrders(orders, container) {
    let html = '';
    
    orders.forEach(order => {
      const orderDate = new Date(order.created_at).toLocaleDateString();
      
      html += `
        <div class="card mb-3">
          <div class="card-header d-flex justify-content-between">
            <div>
              <h6 class="mb-0">Order #${order.id}</h6>
              <small class="text-muted">${orderDate}</small>
            </div>
            <span class="badge bg-primary">${order.status || 'Processing'}</span>
          </div>
          <div class="card-body">
            <p><strong>Total:</strong> ₦${parseFloat(order.total || 0).toLocaleString()}</p>
            <p><strong>Items:</strong> ${order.items?.length || 0}</p>
            <button class="btn btn-outline-primary btn-sm">View Details</button>
          </div>
        </div>
      `;
    });
    
    container.innerHTML = html;
  }
  
  function setupLogout() {
    const logoutBtn = document.getElementById('dashboard-logout');
    if (logoutBtn) {
      logoutBtn.addEventListener('click', function(e) {
        e.preventDefault();
        localStorage.removeItem('DRFUser');
        window.location.href = '/index.php';
      });
    }
  }
});