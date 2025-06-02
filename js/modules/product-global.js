// Non-module version of product.js for global use
class ProductManager {
  constructor(cartManager) {
    this.cart = cartManager;
  }
  
  initProductPage() {
    // Get product data from the page
    const productContainer = document.querySelector('.product-container');
    if (!productContainer) return;
    
    const productId = productContainer.dataset.productId;
    const productName = productContainer.dataset.productName;
    const productPrice = parseFloat(productContainer.dataset.productPrice);
    const productImage = document.querySelector('.product-image img')?.src;
    
    // Initialize color selector
    this.initColorSelector();
    
    // Initialize size selector
    this.initSizeSelector();
    
    // Initialize width selector
    this.initWidthSelector();
    
    // Initialize quantity selector
    this.initQuantitySelector();
    
    // Initialize add to cart button
    const addToCartBtn = document.getElementById('addToCartBtn');
    if (addToCartBtn) {
      addToCartBtn.addEventListener('click', () => {
        // Get selected options
        const selectedColor = document.querySelector('.color-option.selected')?.dataset.color || 'default';
        const selectedSize = document.querySelector('.size-option.selected')?.dataset.size || 'default';
        const selectedWidth = document.querySelector('.width-option.selected')?.dataset.width || 'default';
        const quantity = parseInt(document.getElementById('quantity')?.value || '1');
        
        // Validate selections
        if (selectedColor === 'default') {
          alert('Please select a color');
          return;
        }
        
        if (selectedSize === 'default') {
          alert('Please select a size');
          return;
        }
        
        if (selectedWidth === 'default') {
          alert('Please select a width');
          return;
        }
        
        // Add to cart
        this.cart.addToCart({
          id: productId,
          name: productName,
          price: productPrice,
          image: productImage,
          color: selectedColor,
          size: selectedSize,
          width: selectedWidth,
          quantity: quantity
        });
      });
    }
    
    // Initialize wishlist button
    const wishlistBtn = document.getElementById('addToWishlistBtn');
    if (wishlistBtn) {
      wishlistBtn.addEventListener('click', () => {
        this.addToWishlist(productId, productName, productPrice, productImage);
      });
    }
  }
  
  initColorSelector() {
    const colorOptions = document.querySelectorAll('.color-option');
    if (colorOptions.length === 0) return;
    
    colorOptions.forEach(option => {
      option.addEventListener('click', () => {
        // Remove selected class from all options
        colorOptions.forEach(opt => opt.classList.remove('selected'));
        
        // Add selected class to clicked option
        option.classList.add('selected');
        
        // Update color name display
        const colorName = document.getElementById('selectedColorName');
        if (colorName) {
          colorName.textContent = option.dataset.color;
        }
      });
    });
    
    // Select first option by default
    colorOptions[0].click();
  }
  
  initSizeSelector() {
    const sizeOptions = document.querySelectorAll('.size-option');
    if (sizeOptions.length === 0) return;
    
    sizeOptions.forEach(option => {
      option.addEventListener('click', () => {
        // Remove selected class from all options
        sizeOptions.forEach(opt => opt.classList.remove('selected'));
        
        // Add selected class to clicked option
        option.classList.add('selected');
        
        // Update size name display
        const sizeName = document.getElementById('selectedSizeName');
        if (sizeName) {
          sizeName.textContent = option.dataset.size;
        }
      });
    });
    
    // Select first option by default
    sizeOptions[0].click();
  }
  
  initWidthSelector() {
    const widthOptions = document.querySelectorAll('.width-option');
    if (widthOptions.length === 0) return;
    
    widthOptions.forEach(option => {
      option.addEventListener('click', () => {
        // Remove selected class from all options
        widthOptions.forEach(opt => opt.classList.remove('selected'));
        
        // Add selected class to clicked option
        option.classList.add('selected');
        
        // Update width name display
        const widthName = document.getElementById('selectedWidthName');
        if (widthName) {
          widthName.textContent = option.dataset.width;
        }
      });
    });
    
    // Select first option by default
    widthOptions[0].click();
  }
  
  initQuantitySelector() {
    const quantityInput = document.getElementById('quantity');
    const increaseBtn = document.getElementById('increaseQuantity');
    const decreaseBtn = document.getElementById('decreaseQuantity');
    
    if (!quantityInput || !increaseBtn || !decreaseBtn) return;
    
    increaseBtn.addEventListener('click', () => {
      const currentValue = parseInt(quantityInput.value);
      quantityInput.value = currentValue + 1;
    });
    
    decreaseBtn.addEventListener('click', () => {
      const currentValue = parseInt(quantityInput.value);
      if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
      }
    });
    
    quantityInput.addEventListener('change', () => {
      const currentValue = parseInt(quantityInput.value);
      if (isNaN(currentValue) || currentValue < 1) {
        quantityInput.value = 1;
      }
    });
  }
  
  initCategoryPage() {
    // Initialize product filters
    this.initProductFilters();
    
    // Initialize product sorting
    this.initProductSorting();
    
    // Initialize wishlist buttons
    this.initWishlistButtons();
  }
  
  initProductFilters() {
    const filterToggles = document.querySelectorAll('.filter-toggle');
    if (filterToggles.length === 0) return;
    
    filterToggles.forEach(toggle => {
      toggle.addEventListener('click', () => {
        const target = document.getElementById(toggle.dataset.target);
        if (target) {
          target.classList.toggle('hidden');
          toggle.querySelector('i').classList.toggle('fa-chevron-down');
          toggle.querySelector('i').classList.toggle('fa-chevron-up');
        }
      });
    });
    
    // Initialize price range filter
    const priceRange = document.getElementById('priceRange');
    const priceValue = document.getElementById('priceValue');
    
    if (priceRange && priceValue) {
      priceRange.addEventListener('input', () => {
        priceValue.textContent = `₦${parseInt(priceRange.value).toLocaleString()}`;
      });
    }
    
    // Initialize filter form
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
      filterForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        // Get filter values
        const color = document.getElementById('colorFilter')?.value;
        const size = document.getElementById('sizeFilter')?.value;
        const price = document.getElementById('priceRange')?.value;
        
        // Build query string
        const params = new URLSearchParams(window.location.search);
        
        if (color) params.set('color', color);
        else params.delete('color');
        
        if (size) params.set('size', size);
        else params.delete('size');
        
        if (price) params.set('price', price);
        else params.delete('price');
        
        // Redirect to filtered URL
        window.location.href = `${window.location.pathname}?${params.toString()}`;
      });
    }
    
    // Initialize clear filters button
    const clearFiltersBtn = document.getElementById('clearFilters');
    if (clearFiltersBtn) {
      clearFiltersBtn.addEventListener('click', () => {
        window.location.href = window.location.pathname;
      });
    }
  }
  
  initProductSorting() {
    const sortSelect = document.getElementById('sortSelect');
    if (!sortSelect) return;
    
    sortSelect.addEventListener('change', () => {
      const params = new URLSearchParams(window.location.search);
      params.set('sort', sortSelect.value);
      window.location.href = `${window.location.pathname}?${params.toString()}`;
    });
  }
  
  initWishlistButtons() {
    const wishlistBtns = document.querySelectorAll('.wishlist-btn');
    if (wishlistBtns.length === 0) return;
    
    wishlistBtns.forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        
        const productId = btn.dataset.productId;
        const productName = btn.dataset.productName;
        const productPrice = parseFloat(btn.dataset.productPrice);
        const productImage = btn.dataset.productImage;
        
        this.addToWishlist(productId, productName, productPrice, productImage);
      });
    });
  }
  
  addToWishlist(productId, productName, productPrice, productImage) {
    // Check if user is logged in
    if (!window.app.auth || !window.app.auth.isLoggedIn()) {
      alert('Please log in to add items to your wishlist');
      return;
    }
    
    const user = window.app.auth.getCurrentUser();
    const userId = user.user_id || user.id;
    
    if (!userId) {
      alert('Unable to identify user. Please log in again.');
      return;
    }
    
    // Send request to add to wishlist
    fetch('/api/wishlist.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        user_id: userId,
        product_id: productId,
        product_name: productName,
        price: productPrice,
        image: productImage
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('Item added to wishlist!');
      } else {
        alert('Error: ' + data.message);
      }
    })
    .catch(error => {
      console.error('Error adding to wishlist:', error);
      alert('An error occurred. Please try again.');
    });
  }
}