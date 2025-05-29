export class ProductManager {
  constructor(cartManager) {
    this.cart = cartManager;
    this.selectedOptions = {
      color: '',
      size: '',
      width: '',
      quantity: 1
    };
  }

  initProductPage() {
    console.log('Initializing product page functionality');
    
    // Debug price elements
    this.debugPriceElements();
    
    // Debug: Check if elements exist
    console.log('Color options found:', document.querySelectorAll('.color-option').length);
    console.log('Size options found:', document.querySelectorAll('.size-option').length);
    console.log('Width options found:', document.querySelectorAll('.width-option').length);
    console.log('Add to cart button found:', !!document.getElementById('add-to-cart-btn'));
    
    this.initColorSelection();
    this.initSizeSelection();
    this.initWidthSelection();
    this.initQuantityControls();
    this.initAddToCartButton();
    this.initSizeGuideModal();
  }

  initColorSelection() {
    const colorOptions = document.querySelectorAll('.color-option');
    console.log('Setting up color selection for', colorOptions.length, 'options');
    
    colorOptions.forEach((btn, index) => {
      console.log(`Color option ${index}:`, btn.dataset.color);
      btn.addEventListener('click', () => {
        console.log('Color clicked:', btn.dataset.color);
        
        // Remove selection from all
        document.querySelectorAll('.color-option').forEach(b => {
          b.classList.remove('ring-2', 'selected', 'ring-offset-2');
        });
        
        // Add selection to clicked
        btn.classList.add('ring-2', 'selected', 'ring-offset-2');
        
        // Update selection
        this.selectedOptions.color = btn.dataset.color;
        
        // Update hidden input
        const hiddenInput = document.getElementById('selected-color');
        if (hiddenInput) hiddenInput.value = btn.dataset.color;
        
        console.log('Color selected:', btn.dataset.color);
      });
    });
  }

  initSizeSelection() {
    const sizeOptions = document.querySelectorAll('.size-option');
    console.log('Setting up size selection for', sizeOptions.length, 'options');
    
    sizeOptions.forEach((btn, index) => {
      console.log(`Size option ${index}:`, btn.dataset.size);
      btn.addEventListener('click', () => {
        console.log('Size clicked:', btn.dataset.size);
        
        // Remove selection from all
        document.querySelectorAll('.size-option').forEach(b => {
          b.classList.remove('selected', 'text-white', 'border-black');
          b.classList.add('border-gray-300');
        });
        
        // Add selection to clicked
        btn.classList.remove('border-gray-300');
        btn.classList.add('selected', 'text-white', 'border-black');
        
        // Update selection
        this.selectedOptions.size = btn.dataset.size;
        
        // Update hidden input
        const hiddenInput = document.getElementById('selected-size');
        if (hiddenInput) hiddenInput.value = btn.dataset.size;
        
        console.log('Size selected:', btn.dataset.size);
      });
    });
  }

  initWidthSelection() {
    const widthOptions = document.querySelectorAll('.width-option');
    console.log('Setting up width selection for', widthOptions.length, 'options');
    
    widthOptions.forEach((btn, index) => {
      console.log(`Width option ${index}:`, btn.dataset.width);
      btn.addEventListener('click', () => {
        console.log('Width clicked:', btn.dataset.width);
        
        // Remove selection from all
        document.querySelectorAll('.width-option').forEach(b => {
          b.classList.remove('selected', 'text-white', 'border-black');
          b.classList.add('border-gray-300');
        });
        
        // Add selection to clicked
        btn.classList.remove('border-gray-300');
        btn.classList.add('selected', 'text-white', 'border-black');
        
        // Update selection
        this.selectedOptions.width = btn.dataset.width;
        
        // Update hidden input
        const hiddenInput = document.getElementById('selected-width');
        if (hiddenInput) hiddenInput.value = btn.dataset.width;
        
        console.log('Width selected:', btn.dataset.width);
      });
    });
  }

  initQuantityControls() {
    const quantityBtns = document.querySelectorAll('[data-action="increase"], [data-action="decrease"], .quantity-btn');
    console.log('Setting up quantity controls for', quantityBtns.length, 'buttons');
    
    quantityBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        console.log('Quantity button clicked:', btn.textContent);
        const action = btn.dataset.action || (btn.textContent.trim() === '+' ? 'increase' : 'decrease');
        const input = document.getElementById('quantity') || btn.closest('.flex')?.querySelector('input[type="number"]');
        
        if (input) {
          let value = parseInt(input.value) || 1;
          if (action === 'increase') {
            input.value = value + 1;
          } else if (action === 'decrease' && value > 1) {
            input.value = value - 1;
          }
          
          this.selectedOptions.quantity = parseInt(input.value);
          
          const hiddenInput = document.getElementById('selected-quantity');
          if (hiddenInput) hiddenInput.value = input.value;
          
          console.log('Quantity updated to:', input.value);
        }
      });
    });
  }

  initAddToCartButton() {
    const addToCartBtn = document.getElementById('add-to-cart-btn');
    console.log('Add to cart button found:', !!addToCartBtn);
    
    if (!addToCartBtn) {
      console.warn('Add to cart button not found. Looking for alternative selectors...');
      const altBtn = document.querySelector('.add-to-cart, [data-action="add-to-cart"], button:contains("Add to Cart")');
      if (altBtn) {
        console.log('Found alternative add to cart button');
        this.setupAddToCartHandler(altBtn);
      }
      return;
    }

    this.setupAddToCartHandler(addToCartBtn);
  }

  setupAddToCartHandler(button) {
    button.addEventListener('click', (e) => {
      e.preventDefault();
      console.log('Add to cart clicked');
      console.log('Current selections:', this.selectedOptions);
      
      // Get current selections
      const color = document.getElementById('selected-color')?.value || this.selectedOptions.color;
      const size = document.getElementById('selected-size')?.value || this.selectedOptions.size;
      const width = document.getElementById('selected-width')?.value || this.selectedOptions.width;
      const quantity = parseInt(document.getElementById('quantity')?.value) || this.selectedOptions.quantity;
      
      console.log('Final selections:', { color, size, width, quantity });
      
      // Validate selections
      if (!color) { 
        alert('Please select a color'); 
        return; 
      }
      if (!size) { 
        alert('Please select a size'); 
        return; 
      }
      if (!width) { 
        alert('Please select a width'); 
        return; 
      }

      // Show loading state
      const originalText = button.innerHTML;
      button.innerHTML = 'Adding...';
      button.disabled = true;

      // Get product data from page
      const productData = this.getProductDataFromPage();
      productData.color = color;
      productData.size = size;
      productData.width = width;
      productData.quantity = quantity;

      console.log('Complete product data for cart:', productData);

      // Add to cart after delay
      setTimeout(() => {
        const success = this.cart.addToCart(productData);
        button.innerHTML = originalText;
        button.disabled = false;
        
        if (success) {
          console.log('Product added to cart successfully');
        } else {
          console.error('Failed to add product to cart');
        }
      }, 1000);
    });
  }

  getProductDataFromPage() {
    // Get product name directly from h3 element within product card
    let productName = '';
    
    // Try to get product name from h3 within the product details section
    const productTitle = document.querySelector('.product-details h3, .product-info h3');
    if (productTitle) {
      productName = productTitle.textContent.trim();
      console.log('Found product name from product details h3:', productName);
    }
    
    // If not found, try h3 within the product card
    if (!productName) {
      const h3Element = document.querySelector('h3');
      if (h3Element) {
        productName = h3Element.textContent.trim();
        console.log('Found product name from h3:', productName);
      }
    }
    
    // If still not found, try h1
    if (!productName) {
      const h1Element = document.querySelector('h1');
      if (h1Element) {
        productName = h1Element.textContent.trim();
        console.log('Found product name from h1:', productName);
      }
    }
    
    // If still not found, try product title class
    if (!productName) {
      const titleElement = document.querySelector('.product-title');
      if (titleElement) {
        productName = titleElement.textContent.trim();
        console.log('Found product name from .product-title:', productName);
      }
    }
    
    // If still not found, try page title without site name
    if (!productName) {
      const pageTitle = document.title;
      if (pageTitle && pageTitle.includes('|')) {
        productName = pageTitle.split('|')[0].trim();
        console.log('Found product name from page title:', productName);
      }
    }
    
    // Fallback
    if (!productName) {
      productName = 'Product';
      console.warn('Could not find product name, using fallback');
    }

    // Better price extraction logic
    let price = 0;
    
    // First try data attributes
    const priceFromData = document.querySelector('[data-price]');
    if (priceFromData) {
      price = parseInt(priceFromData.dataset.price) || 0;
    }
    
    // If no data attribute, try common price selectors
    if (!price) {
      const priceSelectors = [
        '.text-2xl',
        '.product-price', 
        '.price',
        '.text-xl',
        'p.text-2xl',
        '.text-accent'
      ];

      for (const selector of priceSelectors) {
        const priceElement = document.querySelector(selector);
        if (priceElement) {
          const priceText = priceElement.textContent;
          
          // Extract number from price text (handles ₦, commas, etc.)
          const priceMatch = priceText.match(/[\d,]+/);
          if (priceMatch) {
            price = parseInt(priceMatch[0].replace(/,/g, ''));
            break;
          }
        }
      }
    }

    // Try to get main product image
    const productImage = document.getElementById('mainImage')?.src || 
                        document.querySelector('.product-image img')?.src ||
                        document.querySelector('img')?.src || 
                        '';

    const pathParts = window.location.pathname.split('/');
    const productId = pathParts[pathParts.length - 1].replace('.php', '');

    return {
      id: productId,
      name: productName,
      price: price,
      image: productImage
    };
  }

  initSizeGuideModal() {
    const sizeGuideBtn = document.getElementById('size-guide-btn');
    const sizeGuideModal = document.getElementById('size-guide-modal');
    const closeSizeGuide = document.getElementById('close-size-guide');

    if (sizeGuideBtn && sizeGuideModal) {
      sizeGuideBtn.addEventListener('click', (e) => {
        e.preventDefault();
        sizeGuideModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
      });
    }

    if (closeSizeGuide && sizeGuideModal) {
      closeSizeGuide.addEventListener('click', () => {
        sizeGuideModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
      });
    }

    if (sizeGuideModal) {
      sizeGuideModal.addEventListener('click', (e) => {
        if (e.target === sizeGuideModal) {
          sizeGuideModal.classList.add('hidden');
          document.body.style.overflow = 'auto';
        }
      });
    }
  }

  initCategoryPage() {
    console.log('Initializing category page functionality');
  }

  // Debug price extraction
  debugPriceElements() {
    console.log('=== PRICE DEBUG INFO ===');
    
    // Check for common price selectors
    const selectors = ['.text-2xl', '.price', '.product-price', '.text-accent'];
    selectors.forEach(selector => {
      const elements = document.querySelectorAll(selector);
      if (elements.length > 0) {
        console.log(`Selector "${selector}" found ${elements.length} elements:`);
        elements.forEach((el, i) => {
          console.log(`  ${i}: "${el.textContent.trim()}"`);
        });
      }
    });
    
    console.log('=== END PRICE DEBUG ===');
  }
}