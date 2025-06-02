// js/customize.js - Complete updated version

document.addEventListener('DOMContentLoaded', function() {
  console.log('Customize.js loaded');
  
  // Check if we're on the customize page
  const shoePreview = document.getElementById('shoe-preview');
  if (!shoePreview) {
    console.log('Not on customize page');
    return;
  }
  
  // Initial values
  let selectedStyle = 'oxford';
  let selectedColor = 'black';
  let selectedMaterial = 'calf';
  let selectedSize = '';
  let basePrice = 85000;
  let totalPrice = basePrice;
  
  // Image mappings
  const shoeImages = {
    oxford: {
      black: '/images/Oxford Cap Toe 600.webp',
      brown: '/images/cram solid oxford.webp',
      tan: '/images/penny loafer 600.webp',
      burgundy: '/images/Oxford Cap Toe 600.webp',
      navy: '/images/cram solid oxford.webp'
    },
    derby: {
      black: '/images/Oxford Cap Toe 600.webp',
      brown: '/images/cram solid oxford.webp',
      tan: '/images/penny loafer 600.webp',
      burgundy: '/images/Oxford Cap Toe 600.webp',
      navy: '/images/cram solid oxford.webp'
    },
    loafer: {
      black: '/images/penny loafer 600.webp',
      brown: '/images/penny loafer 600.webp',
      tan: '/images/penny loafer 600.webp',
      burgundy: '/images/penny loafer 600.webp',
      navy: '/images/penny loafer 600.webp'
    },
    monk: {
      black: '/images/Vintage Croc 600.webp',
      brown: '/images/Vintage Croc 600.webp',
      tan: '/images/Vintage Croc 600.webp',
      burgundy: '/images/Vintage Croc 600.webp',
      navy: '/images/Vintage Croc 600.webp'
    }
  };
  
  // Style names and descriptions
  const styleInfo = {
    oxford: {
      name: 'Oxford Cap Toe',
      description: 'Classic formal shoe with cap toe detail',
      price: 85000
    },
    derby: {
      name: 'Derby Plain Toe',
      description: 'Versatile shoe with open lacing system',
      price: 82000
    },
    loafer: {
      name: 'Penny Loafer',
      description: 'Elegant slip-on with signature strap detail',
      price: 78000
    },
    monk: {
      name: 'Monk Strap',
      description: 'Sophisticated shoe with buckle closure',
      price: 88000
    }
  };
  
  // Material price adjustments and display names
  const materialPrices = {
    calf: 0,
    suede: 10000,
    patent: 15000
  };

  const materialNames = {
    calf: 'Calf Leather',
    suede: 'Suede Leather',
    patent: 'Patent Leather'
  };
  
  // Elements
  const previewTitle = document.getElementById('preview-title');
  const previewDescription = document.getElementById('preview-description');
  const previewPrice = document.getElementById('preview-price');
  const styleOptions = document.querySelectorAll('.custom-option[data-style]');
  const colorOptions = document.querySelectorAll('.custom-color');
  const materialOptions = document.querySelectorAll('input[name="material"]');
  const sizeOptions = document.querySelectorAll('.size-option');
  const addToCartBtn = document.querySelector('.bg-black.text-white.py-4');
  const saveDesignBtn = document.querySelector('.border-2.border-black');
  
  console.log('Elements found:', {
    shoePreview: !!shoePreview,
    previewTitle: !!previewTitle,
    previewDescription: !!previewDescription,
    previewPrice: !!previewPrice,
    styleOptions: styleOptions.length,
    colorOptions: colorOptions.length,
    materialOptions: materialOptions.length,
    sizeOptions: sizeOptions.length,
    addToCartBtn: !!addToCartBtn,
    saveDesignBtn: !!saveDesignBtn
  });

  // Function to get current price from preview pane
  function getCurrentPrice() {
    if (previewPrice && previewPrice.textContent) {
      return previewPrice.textContent;
    }
    return '₦' + totalPrice.toLocaleString();
  }

  // Function to update button text with price
  function updateButtonPrice() {
    if (addToCartBtn) {
      const currentPrice = getCurrentPrice();
      
      // Update button text to include price
      const buttonContent = addToCartBtn.innerHTML;
      
      // Check if button already has price
      if (buttonContent.includes('₦')) {
        // Replace existing price
        addToCartBtn.innerHTML = buttonContent.replace(/₦[\d,]+/, currentPrice);
      } else {
        // Add price to button text
        if (buttonContent.includes('ADD TO CART')) {
          addToCartBtn.innerHTML = buttonContent.replace('ADD TO CART', `ADD TO CART - ${currentPrice}`);
        } else {
          addToCartBtn.innerHTML = `ADD TO CART - ${currentPrice}`;
        }
      }
      
      console.log('Button price updated:', currentPrice);
    }
  }
  
  // Update preview function
  function updatePreview() {
    console.log('Updating preview:', { selectedStyle, selectedColor, selectedMaterial });
    
    // Update image
    const imagePath = shoeImages[selectedStyle][selectedColor] || shoeImages.oxford.black;
    console.log('Setting image path:', imagePath);
    shoePreview.src = imagePath;
    
    // Update title and description
    const style = styleInfo[selectedStyle];
    if (previewTitle) previewTitle.textContent = style.name;
    if (previewDescription) previewDescription.textContent = style.description;
    
    // Calculate price
    basePrice = style.price;
    const materialAdditional = materialPrices[selectedMaterial] || 0;
    totalPrice = basePrice + materialAdditional;
    
    console.log('Price calculation:', { basePrice, materialAdditional, totalPrice });
    
    // Update price displays
    const formattedPrice = '₦' + totalPrice.toLocaleString();
    if (previewPrice) previewPrice.textContent = formattedPrice;
    
    // Update final price on button and button text
    updateFinalPrice();
    updateButtonPrice();
  }

  // Update final price function - moved inside DOMContentLoaded
  function updateFinalPrice() {
    const finalPriceElement = document.querySelector('.bg-black.text-white.py-4 .text-xl');
    const formattedPrice = '₦' + totalPrice.toLocaleString();
    
    console.log('Updating final price:', formattedPrice);
    
    if (finalPriceElement) {
      finalPriceElement.textContent = formattedPrice;
      console.log('Final price updated successfully');
    } else {
      console.warn('Final price element not found');
    }
  }
  
  // Style selection
  styleOptions.forEach(option => {
    option.addEventListener('click', function() {
      console.log('Style clicked:', this.dataset.style);
      styleOptions.forEach(opt => opt.classList.remove('active', 'border-black'));
      this.classList.add('active', 'border-black');
      selectedStyle = this.dataset.style;
      updatePreview();
    });
  });
  
  // Color selection
  colorOptions.forEach(option => {
    option.addEventListener('click', function() {
      console.log('Color clicked:', this.dataset.color);
      colorOptions.forEach(opt => opt.classList.remove('active', 'border-black'));
      this.classList.remove('border-transparent', 'hover:border-gray-400');
      this.classList.add('active', 'border-black');
      selectedColor = this.dataset.color;
      updatePreview();
    });
  });
  
  // Material selection
  materialOptions.forEach(option => {
    option.addEventListener('change', function() {
      console.log('Material changed:', this.value);
      selectedMaterial = this.value;
      updatePreview();
    });
  });
  
  // Size selection
  sizeOptions.forEach(option => {
    option.addEventListener('click', function() {
      console.log('Size clicked:', this.dataset.size);
      sizeOptions.forEach(opt => opt.classList.remove('active', 'border-black', 'bg-black', 'text-white'));
      this.classList.add('active', 'border-black', 'bg-black', 'text-white');
      selectedSize = this.dataset.size;
    });
  });
  
  // Helper function to check if user is logged in
  function isUserLoggedIn() {
    return !!(
      window.app?.auth?.isLoggedIn?.() || 
      window.app?.auth?.getCurrentUser?.() ||
      document.querySelector('[data-user-id]') ||
      document.querySelector('.user-menu') ||
      document.querySelector('.logout-btn') ||
      localStorage.getItem('isLoggedIn') === 'true' ||
      sessionStorage.getItem('user_id')
    );
  }

  // Helper function to get user ID
  function getUserId() {
    if (window.app?.auth?.getCurrentUser?.()) {
      return window.app.auth.getCurrentUser().user_id;
    }
    
    const userIdElement = document.querySelector('[data-user-id]');
    if (userIdElement) {
      return userIdElement.dataset.userId;
    }
    
    return sessionStorage.getItem('user_id') || localStorage.getItem('user_id');
  }

  // Fixed add to cart functionality
  if (addToCartBtn) {
    addToCartBtn.addEventListener('click', function() {
      if (!selectedSize) {
        alert('Please select a size before adding to cart');
        return;
      }
      
      const product = {
        id: 'custom-' + Date.now(),
        name: styleInfo[selectedStyle].name,
        price: totalPrice,
        image: shoeImages[selectedStyle][selectedColor],
        color: selectedColor,
        size: selectedSize,
        material: selectedMaterial,
        materialName: materialNames[selectedMaterial],
        leatherType: materialNames[selectedMaterial], // For backward compatibility
        width: 'D',
        quantity: 1,
        isCustom: true,
        style: selectedStyle,
        styleName: styleInfo[selectedStyle].name,
        // Additional product details for cart display
        productDetails: {
          style: styleInfo[selectedStyle].name,
          color: selectedColor,
          material: materialNames[selectedMaterial],
          size: selectedSize,
          width: 'D (Standard)'
        }
      };
      
      console.log('Adding product to cart:', product);
      
      // Use the CartManager if available
      if (window.app?.cart?.addToCart) {
        console.log('Using CartManager');
        window.app.cart.addToCart(product);
        alert(`${product.name} added to cart!`);
        return;
      }
      
      // Fallback to direct localStorage manipulation
      const isLoggedIn = isUserLoggedIn();
      console.log('User logged in status:', isLoggedIn);
      
      if (isLoggedIn) {
        const userId = getUserId();
        console.log('User ID:', userId);
        
        if (userId) {
          const userCartKey = `DRFCart_${userId}`;
          let cart = JSON.parse(localStorage.getItem(userCartKey) || '[]');
          cart.push(product);
          localStorage.setItem(userCartKey, JSON.stringify(cart));
          console.log('Added to user cart:', userCartKey);
        } else {
          console.warn('Could not determine user ID, falling back to guest cart');
          let cart = JSON.parse(localStorage.getItem('DRFCart') || '[]');
          cart.push(product);
          localStorage.setItem('DRFCart', JSON.stringify(cart));
        }
      } else {
        console.log('Adding to guest cart');
        let cart = JSON.parse(localStorage.getItem('DRFCart') || '[]');
        cart.push(product);
        localStorage.setItem('DRFCart', JSON.stringify(cart));
      }
      
      alert(`${product.name} added to cart!`);
      
      // Update cart count if function exists
      if (window.app?.cart?.updateCartCount) {
        window.app.cart.updateCartCount();
      }
    });
  }
  
  // Save design button
  if (saveDesignBtn) {
    saveDesignBtn.addEventListener('click', function() {
      if (!selectedSize) {
        alert('Please select a size before saving your design');
        return;
      }
      
      const design = {
        style: selectedStyle,
        color: selectedColor,
        material: selectedMaterial,
        materialName: materialNames[selectedMaterial],
        size: selectedSize,
        price: totalPrice,
        image: shoeImages[selectedStyle][selectedColor],
        name: styleInfo[selectedStyle].name
      };
      
      console.log('Saving design:', design);
      
      if (isUserLoggedIn()) {
        saveDesignToProfile(design);
      } else {
        if (confirm('You need to be logged in to save designs. Would you like to sign in now?')) {
          $('#loginModal').modal('show');
          sessionStorage.setItem('pendingDesign', JSON.stringify(design));
        }
      }
    });
  }
  
  // Function to save design to profile
  function saveDesignToProfile(design) {
    const userId = getUserId();
    
    fetch('/api/save-design.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        user_id: userId,
        design: design
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('Your design has been saved to your profile!');
      } else {
        alert('Error saving design: ' + data.message);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('An error occurred while saving your design');
    });
  }
  
  // Load existing design if available
  if (typeof designData !== 'undefined' && designData) {
    console.log('Loading existing design:', designData);
    
    if (designData.style) {
      selectedStyle = designData.style;
      const styleOption = document.querySelector(`.custom-option[data-style="${selectedStyle}"]`);
      if (styleOption) styleOption.click();
    }
    
    if (designData.color) {
      selectedColor = designData.color;
      const colorOption = document.querySelector(`.custom-color[data-color="${selectedColor}"]`);
      if (colorOption) colorOption.click();
    }
    
    if (designData.material) {
      selectedMaterial = designData.material;
      const materialOption = document.querySelector(`input[name="material"][value="${selectedMaterial}"]`);
      if (materialOption) materialOption.checked = true;
    }
    
    if (designData.size) {
      selectedSize = designData.size;
      const sizeOption = document.querySelector(`.size-option[data-size="${selectedSize}"]`);
      if (sizeOption) sizeOption.click();
    }
  }    
    
  // Initialize preview
  updatePreview();
  console.log('Customize.js initialization complete');
});