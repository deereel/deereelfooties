document.addEventListener('DOMContentLoaded', function() {
  // Elements
  const shoePreview = document.getElementById('shoe-preview');
  const previewTitle = document.getElementById('preview-title');
  const previewDescription = document.getElementById('preview-description');
  const previewPrice = document.getElementById('preview-price');
  const finalPrice = document.getElementById('final-price');
  const styleOptions = document.querySelectorAll('.custom-option');
  const colorOptions = document.querySelectorAll('.custom-color');
  const materialOptions = document.querySelectorAll('input[name="material"]');
  const sizeOptions = document.querySelectorAll('.size-option');
  const savedDesignsContainer = document.getElementById('saved-designs-container');
  const saveDesignBtn = document.getElementById('save-design-btn');
  const addToCartBtn = document.getElementById('add-to-cart-btn');
  const view3dBtn = document.getElementById('view-3d-btn');
  const preview3dModal = document.getElementById('preview-3d-modal');
  const close3dBtn = document.getElementById('close-3d-btn');
  
  // Base prices
  const basePrices = {
    oxford: 85000,
    derby: 82000,
    loafer: 78000,
    monk: 88000
  };
  
  // Material price adjustments
  const materialPrices = {
    calf: 0,
    suede: 10000,
    patent: 15000
  };
  
  // Current selections
  let currentStyle = 'oxford';
  let currentColor = 'black';
  let currentMaterial = 'calf';
  let currentSize = null;
  
  // Load saved design if available
  if (window.designData) {
    currentStyle = designData.style || currentStyle;
    currentColor = designData.color || currentColor;
    currentMaterial = designData.material || currentMaterial;
    currentSize = designData.size || currentSize;
    
    // Update UI to match saved design
    document.querySelector(`.custom-option[data-style="${currentStyle}"]`).classList.add('active');
    document.querySelector(`.custom-color[data-color="${currentColor}"]`).classList.add('active');
    document.querySelector(`input[name="material"][value="${currentMaterial}"]`).checked = true;
    
    if (currentSize) {
      document.querySelector(`.size-option[data-size="${currentSize}"]`).classList.add('selected');
    }
  }
  
  // Load saved designs
  function loadSavedDesigns() {
    if (!savedDesignsContainer) return;
    
    fetch('/api/saved-designs.php')
      .then(response => response.json())
      .then(designs => {
        if (designs.length === 0) {
          savedDesignsContainer.innerHTML = '<div class="col-span-full text-center py-8">You have no saved designs yet.</div>';
          return;
        }
        
        savedDesignsContainer.innerHTML = '';
        designs.forEach(design => {
          const designData = JSON.parse(design.design_data);
          const card = document.createElement('div');
          card.className = 'bg-white rounded-lg shadow-md overflow-hidden';
          
          card.innerHTML = `
            <div class="aspect-[3/4] bg-gray-100">
              <img src="/images/${designData.style}-${designData.color || 'black'}.jpg" 
                   alt="Saved Design" 
                   class="w-full h-full object-cover">
            </div>
            <div class="p-4">
              <h3 class="font-medium">${design.design_name || 'Custom Design'}</h3>
              <p class="text-sm text-gray-500">Created: ${new Date(design.created_at).toLocaleDateString()}</p>
              <a href="/customize.php?design_id=${design.design_id}" 
                 class="mt-3 inline-block px-4 py-2 bg-black text-white rounded hover:bg-gray-800 transition w-full text-center">
                Load Design
              </a>
            </div>
          `;
          
          savedDesignsContainer.appendChild(card);
        });
      })
      .catch(error => {
        console.error('Error loading saved designs:', error);
        savedDesignsContainer.innerHTML = '<div class="col-span-full text-center py-8">Error loading saved designs. Please try again later.</div>';
      });
  }
  
  // Update preview and price
  function updatePreview() {
    // Update image (in a real app, you'd have different images for each combination)
    shoePreview.src = `/images/${currentStyle}-${currentColor}.jpg`;
    
    // Update title and description
    const titles = {
      oxford: 'Oxford Cap Toe',
      derby: 'Derby Plain Toe',
      loafer: 'Penny Loafer',
      monk: 'Double Monk Strap'
    };
    
    const descriptions = {
      oxford: 'Classic formal shoe with cap toe detail',
      derby: 'Versatile shoe with open lacing system',
      loafer: 'Elegant slip-on style with penny strap',
      monk: 'Sophisticated shoe with double buckle closure'
    };
    
    previewTitle.textContent = titles[currentStyle];
    previewDescription.textContent = descriptions[currentStyle];
    
    // Calculate and update price
    const basePrice = basePrices[currentStyle];
    const materialAdjustment = materialPrices[currentMaterial];
    const totalPrice = basePrice + materialAdjustment;
    
    previewPrice.textContent = `₦${totalPrice.toLocaleString()}`;
    finalPrice.textContent = `₦${totalPrice.toLocaleString()}`;
    
    // Update 3D model if viewer is initialized
    if (window.shoeViewer) {
      window.shoeViewer.setStyle(currentStyle);
      window.shoeViewer.setColor(currentColor);
      window.shoeViewer.setMaterial(currentMaterial);
    }
  }
  
  // Style selection
  styleOptions.forEach(option => {
    option.addEventListener('click', function() {
      styleOptions.forEach(opt => opt.classList.remove('active'));
      this.classList.add('active');
      currentStyle = this.dataset.style;
      updatePreview();
    });
  });
  
  // Color selection
  colorOptions.forEach(option => {
    option.addEventListener('click', function() {
      colorOptions.forEach(opt => opt.classList.remove('active'));
      this.classList.add('active');
      currentColor = this.dataset.color;
      updatePreview();
    });
  });
  
  // Material selection
  materialOptions.forEach(option => {
    option.addEventListener('change', function() {
      currentMaterial = this.value;
      updatePreview();
    });
  });
  
  // Size selection
  sizeOptions.forEach(option => {
    option.addEventListener('click', function() {
      sizeOptions.forEach(opt => opt.classList.remove('selected'));
      this.classList.add('selected');
      currentSize = this.dataset.size;
    });
  });
  
  // Save design
  if (saveDesignBtn) {
    // Use a variable to track if a save operation is in progress
    let isSaving = false;
    
    saveDesignBtn.addEventListener('click', function() {
      // Prevent multiple clicks
      if (isSaving) return;
      
      if (!currentSize) {
        showNotification('Please select a size before saving your design.');
        return;
      }
      
      // Set flag to prevent duplicate prompts
      isSaving = true;
      
      // Create prompt dialog with safeguards
      const designName = prompt('Enter a name for your design:');
      
      // Reset flag if user cancels
      if (!designName) {
        isSaving = false;
        return;
      }
      
      const designData = {
        style: currentStyle,
        color: currentColor,
        material: currentMaterial,
        size: currentSize
      };
      
      fetch('/api/save-design.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          design_name: designName,
          design_data: designData
        })
      })
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        isSaving = false;
        if (data.success) {
          showNotification('Design saved successfully!');
          loadSavedDesigns();
        } else {
          showNotification('Error saving design: ' + (data.message || 'Please try again'));
        }
      })
      .catch(error => {
        isSaving = false;
        console.error('Error saving design:', error);
        showNotification('Error saving design. Please try again later.');
      });
    });
  }
  
  // Add to cart
  if (addToCartBtn) {
    // Use a variable to track if an add-to-cart operation is in progress
    let isAddingToCart = false;
    
    addToCartBtn.addEventListener('click', function() {
      // Prevent multiple clicks
      if (isAddingToCart) return;
      
      if (!currentSize) {
        showNotification('Please select a size before adding to cart.');
        return;
      }
      
      // Set flag to prevent duplicate operations
      isAddingToCart = true;
      
      const basePrice = basePrices[currentStyle];
      const materialAdjustment = materialPrices[currentMaterial];
      const totalPrice = basePrice + materialAdjustment;
      
      const productData = {
        id: `custom-${currentStyle}-${currentColor}-${currentMaterial}`,
        name: previewTitle.textContent,
        price: totalPrice,
        style: currentStyle,
        color: currentColor,
        material: currentMaterial,
        size: currentSize,
        width: 'D', // Default width
        image: shoePreview.src,
        quantity: 1,
        isCustom: true
      };
      
      // Store in local storage for guests, use API for logged-in users
      const userId = getUserId();
      
      if (!userId) {
        // Guest user - store in localStorage
        let cart = JSON.parse(localStorage.getItem('cart') || '[]');
        cart.push(productData);
        localStorage.setItem('cart', JSON.stringify(cart));
        isAddingToCart = false;
        showNotification('Added to cart successfully!');
        return;
      }
      
      // Logged-in user - use API
      fetch('/api/sync_cart.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          user_id: userId,
          cart_items: [productData]
        })
      })
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        isAddingToCart = false;
        if (data.success) {
          showNotification('Added to cart successfully!');
          // Update cart count if needed
        } else {
          showNotification('Error adding to cart: ' + (data.message || 'Please try again'));
        }
      })
      .catch(error => {
        isAddingToCart = false;
        console.error('Error adding to cart:', error);
        showNotification('Error adding to cart. Please try again later.');
      });
    });
  }
  
  // Helper function to get user ID - returns null if not logged in
  function getUserId() {
    // Check for user ID in various places
    let userId = null;
    
    if (window.app?.auth?.getCurrentUser?.()) {
      userId = window.app.auth.getCurrentUser().user_id;
    } else {
      const userIdElement = document.querySelector('[data-user-id]');
      if (userIdElement && userIdElement.dataset.userId) {
        userId = userIdElement.dataset.userId;
      } else {
        userId = sessionStorage.getItem('user_id') || localStorage.getItem('user_id');
      }
    }
    
    // Only return if it's a valid numeric ID
    return userId && !isNaN(parseInt(userId)) ? userId : null;
  }
  
  // 3D Preview
  if (view3dBtn) {
    view3dBtn.addEventListener('click', function() {
      if (preview3dModal) {
        preview3dModal.classList.remove('hidden');
        initializeShoeViewer();
      }
    });
  }
  
  if (close3dBtn) {
    close3dBtn.addEventListener('click', function() {
      if (preview3dModal) {
        preview3dModal.classList.add('hidden');
      }
    });
  }
  
  // Initialize 3D viewer
  function initializeShoeViewer() {
    if (!window.shoeViewer) {
      const canvas = document.getElementById('shoe-3d-canvas');
      if (canvas) {
        window.shoeViewer = new ShoeViewer(canvas);
        window.shoeViewer.setStyle(currentStyle);
        window.shoeViewer.setColor(currentColor);
        window.shoeViewer.setMaterial(currentMaterial);
        window.shoeViewer.render();
      }
    }
  }
  
  // Custom notification function to replace alerts
  function showNotification(message) {
    // Remove any existing notification
    const existingNotification = document.getElementById('custom-notification');
    if (existingNotification) {
      existingNotification.remove();
    }
    
    // Create notification element
    const notification = document.createElement('div');
    notification.id = 'custom-notification';
    notification.className = 'fixed bottom-4 right-4 bg-black text-white py-3 px-6 rounded-lg shadow-lg z-50 transition-opacity duration-300';
    notification.textContent = message;
    
    // Add to document
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
      notification.style.opacity = '0';
      setTimeout(() => {
        notification.remove();
      }, 300);
    }, 3000);
  }
  
  // Initialize preview
  updatePreview();
  
  // Load saved designs
  loadSavedDesigns();
  
  // Scroll to Top Button
  const scrollToTopBtn = document.getElementById('scrollToTop');
  
  window.addEventListener('scroll', function() {
    if (window.pageYOffset > 300) {
      scrollToTopBtn.style.display = 'flex';
    } else {
      scrollToTopBtn.style.display = 'none';
    }
  });
  
  scrollToTopBtn.addEventListener('click', function(e) {
    e.preventDefault();
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });
});