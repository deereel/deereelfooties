document.addEventListener('DOMContentLoaded', function() {
  // Check if user is logged in
  const checkAuth = setInterval(() => {
    if (window.app && window.app.auth) {
      clearInterval(checkAuth);
      initDashboard();
    }
  }, 100);
  
  // Timeout after 5 seconds
  setTimeout(() => clearInterval(checkAuth), 5000);
  
  function initDashboard() {
    // Redirect if not logged in
    if (!window.app.auth.isLoggedIn()) {
      console.log('User not logged in, redirecting to index');
      window.location.href = '/index.php';
      return;
    }
    
    console.log('User is logged in:', window.app.auth.getCurrentUser());
    
    // Get user data
    const user = window.app.auth.getCurrentUser();
    
    // Update username in dashboard
    const dashboardUsername = document.getElementById('dashboard-username');
    if (dashboardUsername && user) {
      dashboardUsername.textContent = user.name + "'s Account";
    }
    
    // Fill in user data form
    const fullNameInput = document.getElementById('fullName');
    const emailInput = document.getElementById('email');
    
    if (fullNameInput && user) {
      fullNameInput.value = user.name || '';
    }
    
    if (emailInput && user) {
      emailInput.value = user.email || '';
    }
    
    // Handle navigation
    const navLinks = document.querySelectorAll('.dashboard-nav .nav-link');
    const contentSections = document.querySelectorAll('.dashboard-content');
    
    navLinks.forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        
        const section = this.getAttribute('data-section');
        if (!section) return;
        
        // Update active link
        navLinks.forEach(l => l.classList.remove('active'));
        this.classList.add('active');
        
        // Show selected section
        contentSections.forEach(s => s.classList.remove('active'));
        document.getElementById(section + '-section').classList.add('active');
        
        // Load content based on section
        if (section === 'designs') {
          loadSavedDesigns();
        } else if (section === 'wishlist') {
          loadWishlist();
        }
        
        // Update URL hash
        window.location.hash = section;
      });
    });
    
    // Check for hash in URL
    const hash = window.location.hash.substring(1);
    if (hash) {
      const link = document.querySelector(`.dashboard-nav .nav-link[data-section="${hash}"]`);
      if (link) {
        link.click();
      }
    }
    
    // Handle dashboard logout
    const logoutBtn = document.getElementById('dashboard-logout');
    if (logoutBtn) {
      logoutBtn.addEventListener('click', function(e) {
        e.preventDefault();
        window.app.auth.logout();
      });
    }
    
    // Handle personal data form submission
    const personalDataForm = document.getElementById('personal-data-form');
    if (personalDataForm) {
      personalDataForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const name = document.getElementById('fullName').value;
        const phone = document.getElementById('phone').value;
        const birthdate = document.getElementById('birthdate').value;
        
        // Update user data
        user.name = name;
        user.phone = phone;
        user.birthdate = birthdate;
        
        // Save to localStorage
        window.app.auth.login(user);
        
        // Show success message
        alert('Personal information updated successfully!');
      });
    }
    
    // Handle delete account form
    const deleteAccountForm = document.getElementById('delete-account-form');
    if (deleteAccountForm) {
      deleteAccountForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const confirmation = document.getElementById('deleteConfirmation').value;
        
        if (confirmation !== 'DELETE') {
          alert('Please type DELETE to confirm account deletion');
          return;
        }
        
        if (confirm('Are you absolutely sure you want to delete your account? This action cannot be undone.')) {
          window.app.auth.logout();
          alert('Your account has been deleted.');
          window.location.href = '/index.php';
        }
      });
    }
    
    // Handle address form
    const saveAddressBtn = document.getElementById('saveAddressBtn');
    if (saveAddressBtn) {
      saveAddressBtn.addEventListener('click', function() {
        alert('Address saved successfully!');
        const modal = bootstrap.Modal.getInstance(document.getElementById('addressModal'));
        if (modal) {
          modal.hide();
        }
      });
    }
  }

  // Function to load wishlist items (updated)
  function loadWishlist() {
    const user = window.app.auth.getCurrentUser();
    console.log('Loading wishlist for user:', user);
    
    if (!user) {
      console.error('No user found');
      return;
    }
    
    // Try different user ID properties
    const userId = user.user_id || user.id || getUserId();
    console.log('Using user ID:', userId);
    
    if (!userId) {
      console.error('No user ID found');
      return;
    }
    
    const wishlistContainer = document.getElementById('wishlist-container');
    if (!wishlistContainer) {
      console.error('Wishlist container not found');
      return;
    }
    
    // Show loading indicator
    wishlistContainer.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading your wishlist...</p></div>';
    
    // Fetch wishlist from the server
    fetch(`/api/wishlist.php?user_id=${userId}`)
      .then(response => response.json())
      .then(data => {
        console.log('Wishlist data received:', data);
        
        if (data.success && data.items && data.items.length > 0) {
          // Render wishlist items
          let html = '<div class="row">';
          data.items.forEach(item => {
            html += `
              <div class="col-md-4 mb-4">
                <div class="card h-100">
                  <img src="${item.image}" class="card-img-top" alt="${item.product_name}" style="height: 250px; object-fit: cover;">
                  <div class="card-body d-flex flex-column">
                    <h5 class="card-title">${item.product_name}</h5>
                    <p class="card-text text-accent">₦${parseFloat(item.price).toLocaleString()}</p>
                    <div class="mt-auto">
                      <div class="d-flex justify-content-between gap-2">
                        <button class="btn btn-primary btn-sm add-wishlist-to-cart" 
                                data-product-id="${item.product_id}"
                                data-product-name="${item.product_name}"
                                data-product-price="${item.price}"
                                data-product-image="${item.image}">
                          Add to Cart
                        </button>
                        <button class="btn btn-outline-danger btn-sm delete-wishlist-btn" 
                                data-wishlist-id="${item.wishlist_id}">
                          Remove
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            `;
          });
          html += '</div>';
          
          wishlistContainer.innerHTML = html;
          
          // Add event listeners to the new buttons
          setupWishlistEventListeners();
        } else {
          // No wishlist items found
          wishlistContainer.innerHTML = `
            <div class="alert alert-info">
              <i class="fas fa-info-circle me-2"></i> Your wishlist is empty.
              <a href="/products.php" class="alert-link">Browse products</a>
            </div>
          `;
        }
      })
      .catch(error => {
        console.error('Error loading wishlist:', error);
        wishlistContainer.innerHTML = `
          <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i> Error loading wishlist. Please try again.
            <br><small>Error: ${error.message}</small>
          </div>
        `;
      });
  }

  // Complete the loadSavedDesigns function
  function loadSavedDesigns() {
    const user = window.app.auth.getCurrentUser();
    if (!user || !user.user_id) return;
    
    const designsContainer = document.getElementById('saved-designs-container');
    if (!designsContainer) return;
    
    // Show loading indicator
    designsContainer.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading your designs...</p></div>';
    
    // Fetch designs from the server
    fetch(`/api/get-designs.php?user_id=${user.user_id}`)
      .then(response => response.json())
      .then(data => {
        if (data.success && data.designs && data.designs.length > 0) {
          // Render designs
          let html = '';
          data.designs.forEach(design => {
            const designData = JSON.parse(design.design_data);
            const createdAt = new Date(design.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            
            html += `
              <div class="col-md-4 mb-4">
                <div class="card h-100 position-relative">
                  <!-- Add delete button -->
                  <button class="delete-design-btn position-absolute top-0 end-0 bg-white rounded-circle p-1 m-2 border shadow-sm" 
                          data-design-id="${design.design_id}">
                    <i class="fas fa-times text-danger"></i>
                  </button>
                  <img src="${designData.image}" class="card-img-top" alt="${designData.name}">
                  <div class="card-body">
                    <h5 class="card-title">${designData.name}</h5>
                    <p class="text-muted small">Created on ${createdAt}</p>
                    <p class="mb-2">Color: ${designData.color.charAt(0).toUpperCase() + designData.color.slice(1)}</p>
                    <p class="mb-2">Material: ${designData.material.charAt(0).toUpperCase() + designData.material.slice(1)}</p>
                    <p class="mb-2">Size: ${designData.size}</p>
                    <p class="card-text text-accent">₦${designData.price.toLocaleString()}</p>
                    <div class="d-flex justify-content-between">
                      <button class="btn-primary btn-sm add-design-to-cart" 
                              data-design='${JSON.stringify(designData).replace(/'/g, "\\'")}'>
                        Add to Cart
                      </button>
                      <a href="/customize.php?design_id=${design.design_id}" class="btn-outline-secondary btn-sm">
                        Edit
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            `;
          });
          
          designsContainer.innerHTML = html;
          
          // Add event listeners to the new buttons
          setupDesignEventListeners();
        } else {
          // No designs found
          designsContainer.innerHTML = `
            <div class="alert alert-info">
              <i class="fas fa-info-circle me-2"></i> You have no saved designs.
              <a href="/customize.php" class="alert-link">Create a custom design</a>
            </div>
          `;
        }
      })
      .catch(error => {
        console.error('Error loading designs:', error);
        designsContainer.innerHTML = `
          <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i> Error loading designs. Please try again.
          </div>
        `;
      });
  }

  // Function to setup design event listeners
  function setupDesignEventListeners() {
    // Delete design buttons
    document.querySelectorAll('.delete-design-btn').forEach(button => {
      button.addEventListener('click', function() {
        const designId = this.dataset.designId;
        
        if (confirm('Are you sure you want to delete this design?')) {
          const user = window.app.auth.getCurrentUser();
          const userId = user.user_id || user.id;
          
          // Send delete request
          fetch('/api/delete_design.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              user_id: userId,
              design_id: designId
            })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Remove the design card from the UI
              this.closest('.col-md-4').remove();
              
              // Show message if no designs left
              const designsContainer = document.getElementById('saved-designs-container');
              if (designsContainer && designsContainer.querySelectorAll('.col-md-4').length === 0) {
                designsContainer.innerHTML = `
                  <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> You have no saved designs.
                    <a href="/customize.php" class="alert-link">Create a custom design</a>
                  </div>
                `;
              }
            } else {
              alert('Error: ' + data.message);
            }
          })
          .catch(error => {
            console.error('Error deleting design:', error);
            alert('An error occurred. Please try again.');
          });
        }
      });
    });
    
    // Add design to cart buttons
    document.querySelectorAll('.add-design-to-cart').forEach(button => {
      button.addEventListener('click', function() {
        try {
          const designData = JSON.parse(this.dataset.design);
          
          const product = {
            id: 'custom-' + Date.now(),
            name: designData.name,
            price: designData.price,
            image: designData.image,
            color: designData.color,
            size: designData.size,
            material: designData.material,
            width: 'D',
            quantity: 1,
            isCustom: true
          };
          
          // Add to cart
          if (window.app && window.app.cart) {
            window.app.cart.addToCart(product);
          } else {
            // Fallback if cart module isn't available
            let cart = JSON.parse(localStorage.getItem('DRFCart') || '[]');
            cart.push(product);
            localStorage.setItem('DRFCart', JSON.stringify(cart));
          }
          
          alert('Design added to cart!');
        } catch (e) {
          console.error('Error adding design to cart:', e);
          alert('Error adding design to cart');
        }
      });
    });
  }

  // In your dashboard.js file
  function switchSection(sectionId) {
    // Hide all sections
    document.querySelectorAll('.dashboard-content').forEach(section => {
      section.classList.remove('active');
    });
    
    // Show selected section
    const targetSection = document.getElementById(sectionId + '-section');
    if (targetSection) {
      targetSection.classList.add('active');
      
      // If switching to orders section, load orders
      if (sectionId === 'orders') {
        // Trigger a custom event that dashboard-orders.js can listen for
        const event = new CustomEvent('loadOrders');
        document.dispatchEvent(event);
      }
    }
  }

  function setupWishlistEventListeners() {
    // Delete wishlist item buttons
    document.querySelectorAll('.delete-wishlist-btn').forEach(button => {
      button.addEventListener('click', function() {
        const wishlistId = this.dataset.wishlistId;
        
        if (confirm('Are you sure you want to remove this item from your wishlist?')) {
          const user = window.app.auth.getCurrentUser();
          const userId = user.user_id || user.id || getUserId();
          
          // Send delete request
          fetch('/api/wishlist.php', {
            method: 'DELETE',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              user_id: userId,
              wishlist_id: wishlistId
            })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Remove the wishlist item from the UI
              this.closest('.col-md-4').remove();
              
              // Show message if no items left
              const wishlistContainer = document.getElementById('wishlist-container');
              if (wishlistContainer && wishlistContainer.querySelectorAll('.col-md-4').length === 0) {
                wishlistContainer.innerHTML = `
                  <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Your wishlist is empty.
                    <a href="/products.php" class="alert-link">Browse products</a>
                  </div>
                `;
              }
            } else {
              alert('Error: ' + data.message);
            }
          })
          .catch(error => {
            console.error('Error removing wishlist item:', error);
            alert('An error occurred. Please try again.');
          });
        }
      });
    });
    
    // Add wishlist items to cart
    document.querySelectorAll('.add-wishlist-to-cart').forEach(button => {
      button.addEventListener('click', function() {
        const productId = this.dataset.productId;
        const productName = this.dataset.productName;
        const productPrice = parseFloat(this.dataset.productPrice);
        const productImage = this.dataset.productImage;
        
        // Add to cart
        if (window.app && window.app.cart) {
          window.app.cart.addToCart({
            id: productId,
            name: productName,
            price: productPrice,
            image: productImage,
            color: 'default',
            size: 'default',
            width: 'default',
            quantity: 1
          });
          alert('Item added to cart!');
        } else {
          alert('Could not add item to cart. Please try again later.');
        }
      });
    });
  }

  // Helper function to get user ID
  function getUserId() {
    if (window.app?.auth?.getCurrentUser?.()) {
      const user = window.app.auth.getCurrentUser();
      return user.user_id || user.id || user.email;
    }
    return null;
  }
});