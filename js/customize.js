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
  
  // Base prices by type
  const basePrices = {
    oxford: 85000,
    derby: 82000,
    loafers: 78000,
    monk: 88000,
    chelsea: 95000,
    wingtip: 98000,
    captoe: 92000,
    jodhpur: 96000,
    zipper: 94000,
    balmoral: 97000,
    classic: 75000,
    modern: 78000,
    sandals: 65000,
    casual: 70000,
    athletic: 72000
  };
  
  // Material price adjustments
  const materialPrices = {
    calf: 0,
    suede: 10000,
    patent: 15000
  };
  
  // Current selections
  let currentCategory = 'shoes';
  let currentType = 'oxford';
  let currentColor = 'black';
  let currentMaterial = 'calf';
  let currentSize = null;
  
  // Product types by category
  const productTypes = {
    shoes: [
      { type: 'oxford', name: 'Oxford', description: 'Classic formal' },
      { type: 'derby', name: 'Derby', description: 'Open lacing' },
      { type: 'loafers', name: 'Loafers', description: 'Slip-on style' },
      { type: 'monk', name: 'Monk Strap', description: 'Buckle closure' }
    ],
    boots: [
      { type: 'chelsea', name: 'Chelsea', description: 'Elastic sides' },
      { type: 'wingtip', name: 'Wingtip', description: 'Decorative toe' },
      { type: 'captoe', name: 'Cap Toe', description: 'Toe cap detail' },
      { type: 'jodhpur', name: 'Jodhpur', description: 'Ankle strap' },
      { type: 'zipper', name: 'Zipper', description: 'Side zip closure' },
      { type: 'balmoral', name: 'Balmoral', description: 'Closed lacing' }
    ],
    slippers: [
      { type: 'classic', name: 'Classic', description: 'Traditional comfort' },
      { type: 'sandals', name: 'Sandals', description: 'Open toe style' }
    ],
    mules: [
      { type: 'classic', name: 'Classic', description: 'Traditional style' },
      { type: 'modern', name: 'Modern', description: 'Contemporary cut' }
    ],
    sneakers: [
      { type: 'casual', name: 'Casual', description: 'Everyday wear' },
      { type: 'athletic', name: 'Athletic', description: 'Sport style' }
    ]
  };
  
  // Load saved design if available
  if (window.designData) {
    currentCategory = designData.category || currentCategory;
    currentType = designData.type || currentType;
    currentColor = designData.color || currentColor;
    currentMaterial = designData.material || currentMaterial;
    currentSize = designData.size || currentSize;
  }
  
  // Load saved designs
  function loadSavedDesigns() {
    if (!savedDesignsContainer) return;
    
    // Check if user is logged in
    const userData = localStorage.getItem('DRFUser');
    if (!userData) {
      savedDesignsContainer.innerHTML = '<div class="col-span-full text-center py-8"><p>Please <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" class="text-black underline">log in</a> to view saved designs.</p></div>';
      return;
    }
    
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
          
          const imageMap = {
            'oxford-black': '/images/Oxford Cap Toe 600.webp',
            'derby-black': '/images/cram solid oxford.webp',
            'loafer-black': '/images/penny loafer 600.webp'
          };
          const imageKey = `${designData.style}-${designData.color}`;
          const imageUrl = imageMap[imageKey] || '/images/Oxford Cap Toe 600.webp';
          
          card.innerHTML = `
            <div class="aspect-[3/4] bg-gray-100">
              <img src="${imageUrl}" 
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
  
  // Populate type options based on category
  function populateTypeOptions() {
    const typeContainer = document.getElementById('type-options');
    const types = productTypes[currentCategory] || [];
    
    typeContainer.innerHTML = '';
    types.forEach((typeData, index) => {
      const button = document.createElement('button');
      button.className = `p-3 border-2 border-gray-300 rounded-lg hover:border-black transition text-center type-option ${index === 0 ? 'active' : ''}`;
      button.dataset.type = typeData.type;
      button.innerHTML = `
        <h4 class="font-medium text-sm">${typeData.name}</h4>
        <p class="text-xs text-gray-600">${typeData.description}</p>
      `;
      typeContainer.appendChild(button);
    });
    
    // Set default type
    if (types.length > 0) {
      currentType = types[0].type;
    }
    
    // Add event listeners
    addTypeEventListeners();
  }
  
  // Update preview and price
  function updatePreview() {
    // Fetch product from database based on category and type
    fetchProductData(currentCategory, currentType).then(product => {
      if (product) {
        shoePreview.src = product.main_image;
        previewTitle.textContent = product.name;
        previewDescription.textContent = product.short_description;
        
        // Calculate price
        const basePrice = parseFloat(product.price) || basePrices[currentType] || 85000;
        const materialAdjustment = materialPrices[currentMaterial];
        const totalPrice = basePrice + materialAdjustment;
        
        previewPrice.textContent = `₦${totalPrice.toLocaleString()}`;
        finalPrice.textContent = `₦${totalPrice.toLocaleString()}`;
      } else {
        // Fallback to default images
        const imageMap = {
          'oxford-black': '/images/Oxford Cap Toe 600.webp',
          'derby-black': '/images/cram solid oxford.webp',
          'loafer-black': '/images/penny loafer 600.webp'
        };
        
        const imageKey = `${currentType}-${currentColor}`;
        shoePreview.src = imageMap[imageKey] || '/images/Oxford Cap Toe 600.webp';
        
        const basePrice = basePrices[currentType] || 85000;
        const materialAdjustment = materialPrices[currentMaterial];
        const totalPrice = basePrice + materialAdjustment;
        
        previewPrice.textContent = `₦${totalPrice.toLocaleString()}`;
        finalPrice.textContent = `₦${totalPrice.toLocaleString()}`;
      }
    });
  }
  
  // Fetch product data from database
  async function fetchProductData(category, type) {
    try {
      const response = await fetch(`/api/get-product.php?category=${category}&type=${type}`);
      const data = await response.json();
      return data.success ? data.product : null;
    } catch (error) {
      console.error('Error fetching product:', error);
      return null;
    }
  }
  
  // Category selection
  const categoryOptions = document.querySelectorAll('.category-option');
  categoryOptions.forEach(option => {
    option.addEventListener('click', function() {
      categoryOptions.forEach(opt => opt.classList.remove('active', 'border-black'));
      this.classList.add('active', 'border-black');
      currentCategory = this.dataset.category;
      populateTypeOptions();
      updatePreview();
    });
  });
  
  // Type selection event listeners
  function addTypeEventListeners() {
    const typeOptions = document.querySelectorAll('.type-option');
    typeOptions.forEach(option => {
      option.addEventListener('click', function() {
        typeOptions.forEach(opt => opt.classList.remove('active', 'border-black'));
        this.classList.add('active', 'border-black');
        currentType = this.dataset.type;
        updatePreview();
      });
    });
  }
  
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
        category: currentCategory,
        type: currentType,
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
  
  // Add to Cart functionality
  if (addToCartBtn) {
    addToCartBtn.addEventListener('click', function() {
      if (!currentSize) {
        showNotification('Please select a size before adding to cart.');
        return;
      }
      
      const cartItem = {
        product_id: `custom_${Date.now()}`,
        product_name: `Custom ${currentType}`,
        name: `Custom ${currentType}`,
        price: calculatePrice(),
        quantity: 1,
        image: shoePreview.src,
        color: currentColor,
        size: currentSize,
        width: '',
        custom_design: true,
        specifications: `${currentType} in ${currentColor} ${currentMaterial}, Size ${currentSize}`
      };
      
      if (window.cartHandler) {
        window.cartHandler.addToCart(cartItem).then(() => {
          showNotification('Custom design added to cart!');
        }).catch(() => {
          showNotification('Error adding design to cart');
        });
      } else {
        showNotification('Cart system not available');
      }
    });
  }
  
  // Share Design functionality
  const shareDesignBtn = document.getElementById('share-design-btn');
  if (shareDesignBtn) {
    shareDesignBtn.addEventListener('click', function() {
      if (navigator.share) {
        navigator.share({
          title: 'My Custom Shoe Design',
          text: `Check out my custom ${currentStyle} design in ${currentColor}!`,
          url: window.location.href
        });
      } else {
        // Fallback: copy to clipboard
        const shareText = `Check out my custom ${currentStyle} design in ${currentColor}! ${window.location.href}`;
        navigator.clipboard.writeText(shareText).then(() => {
          showNotification('Design link copied to clipboard!');
        }).catch(() => {
          showNotification('Unable to share. Please copy the URL manually.');
        });
      }
    });
  }
  
  // Helper function to calculate price
  function calculatePrice() {
    const basePrice = basePrices[currentType] || 85000;
    const materialAdjustment = materialPrices[currentMaterial];
    return basePrice + materialAdjustment;
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
  
  // Additional close button for 3D modal
  const close3dOverlay = document.getElementById('close-3d-overlay');
  if (close3dOverlay) {
    close3dOverlay.addEventListener('click', function() {
      if (preview3dModal) {
        preview3dModal.classList.add('hidden');
      }
    });
  }
  
  // Close modal when clicking outside
  if (preview3dModal) {
    preview3dModal.addEventListener('click', function(e) {
      if (e.target === preview3dModal) {
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
  
  // Initialize
  populateTypeOptions();
  updatePreview();
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