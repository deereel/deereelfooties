export class ProductManager {
  constructor() {
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
          b.classList.remove('ring-2', 'ring-black', 'ring-offset-2');
        });
        
        // Add selection to clicked
        btn.classList.add('ring-2', 'ring-black', 'ring-offset-2');
        
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
          b.classList.remove('bg-black', 'text-white', 'border-black');
          b.classList.add('border-gray-300');
        });
        
        // Add selection to clicked
        btn.classList.remove('border-gray-300');
        btn.classList.add('bg-black', 'text-white', 'border-black');
        
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
          b.classList.remove('bg-black', 'text-white', 'border-black');
          b.classList.add('border-gray-300');
        });
        
        // Add selection to clicked
        btn.classList.remove('border-gray-300');
        btn.classList.add('bg-black', 'text-white', 'border-black');
        
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
      
      // Get current selections
      const color = document.getElementById('selected-color')?.value || this.selectedOptions.color;
      const size = document.getElementById('selected-size')?.value || this.selectedOptions.size;
      const width = document.getElementById('selected-width')?.value || this.selectedOptions.width;
      const quantity = parseInt(document.getElementById('quantity')?.value) || this.selectedOptions.quantity;
      
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

      // Add to cart
      setTimeout(() => {
        const cartHandler = window.cartHandler || new CartHandler();
        const success = cartHandler.addToCart(productData);
        button.innerHTML = originalText;
        button.disabled = false;
        
        if (success) {
          alert('Product added to cart successfully');
        }
      }, 500);
    });
  }

  getProductDataFromPage() {
    // Try multiple selectors for product name
    const productName = document.querySelector('h1')?.textContent || 
                       document.querySelector('.product-title')?.textContent || 
                       document.querySelector('h3.fw-bold')?.textContent || 
                       'Product';

    // Better price extraction logic
    let price = 0;
    
    // First try data attributes
    const priceFromData = document.querySelector('[data-price]');
    if (priceFromData) {
      price = parseInt(priceFromData.dataset.price) || 0;
      console.log('Price from data attribute:', price);
    }
    
    // If no data attribute, try common price selectors
    if (!price) {
      const priceSelectors = [
        '.text-2xl',
        '.product-price', 
        '.price',
        '.text-xl',
        'p.text-2xl',
        '.fw-bold'
      ];

      for (const selector of priceSelectors) {
        const priceElement = document.querySelector(selector);
        if (priceElement) {
          const priceText = priceElement.textContent;
          console.log('Checking price text:', priceText, 'from selector:', selector);
          
          // Extract number from price text (handles ₦, commas, etc.)
          const priceMatch = priceText.match(/[\d,]+/);
          if (priceMatch) {
            price = parseInt(priceMatch[0].replace(/,/g, ''));
            console.log('Price extracted:', price, 'from:', priceText);
            break;
          }
        }
      }
    }

    // If still no price found, try finding any element containing ₦
    if (!price) {
      const allElements = document.querySelectorAll('*');
      for (const element of allElements) {
        if (element.textContent && element.textContent.includes('₦')) {
          const priceText = element.textContent;
          const priceMatch = priceText.match(/₦[\d,]+/);
          if (priceMatch) {
            price = parseInt(priceMatch[0].replace(/[₦,]/g, ''));
            console.log('Price found in element:', price, 'from:', priceText);
            break;
          }
        }
      }
    }

    // Try to get main product image
    const productImage = document.getElementById('mainImage')?.src || 
                        document.querySelector('.product-image img')?.src ||
                        document.querySelector('img[alt*="product"], img[alt*="Product"]')?.src ||
                        document.querySelector('img')?.src || 
                        '';

    const pathParts = window.location.pathname.split('/');
    const productId = pathParts[pathParts.length - 1].replace('.php', '');

    const productData = {
      id: productId,
      name: productName.trim(),
      price: price,
      image: productImage
    };

    console.log('Final product data extracted:', productData);
    
    // Warn if price is 0
    if (price === 0) {
      console.warn('Price extraction failed - price is 0. Check your HTML structure.');
      console.log('Available elements with text content:');
      document.querySelectorAll('*').forEach(el => {
        if (el.textContent && el.textContent.includes('₦')) {
          console.log('Element with ₦:', el.tagName, el.className, el.textContent.trim());
        }
      });
    }
    
    return productData;
  }

  initSizeGuideModal() {
    const sizeGuideBtn = document.getElementById('size-guide-btn');
    const sizeGuideModal = document.getElementById('size-guide-modal');
    const closeSizeGuide = document.getElementById('close-size-guide');

    console.log('Size guide elements:', {
      btn: !!sizeGuideBtn,
      modal: !!sizeGuideModal,
      close: !!closeSizeGuide
    });

    if (sizeGuideBtn && sizeGuideModal) {
      sizeGuideBtn.addEventListener('click', (e) => {
        e.preventDefault();
        console.log('Size guide opened');
        sizeGuideModal.classList.remove('hidden');
      });
    }

    if (closeSizeGuide && sizeGuideModal) {
      closeSizeGuide.addEventListener('click', () => {
        console.log('Size guide closed');
        sizeGuideModal.classList.add('hidden');
      });
    }

    if (sizeGuideModal) {
      sizeGuideModal.addEventListener('click', (e) => {
        if (e.target === sizeGuideModal) {
          console.log('Size guide closed by clicking outside');
          sizeGuideModal.classList.add('hidden');
        }
      });
    }
  }

  initCategoryPage() {
    console.log('Initializing category page functionality');
  }

  // Add this method to help debug price extraction
  debugPriceElements() {
    console.log('=== PRICE DEBUG INFO ===');
    
    // Check for common price selectors
    const selectors = ['.text-2xl', '.price', '.product-price', 'h3 + p', '.fw-bold'];
    selectors.forEach(selector => {
      const elements = document.querySelectorAll(selector);
      if (elements.length > 0) {
        console.log(`Selector "${selector}" found ${elements.length} elements:`);
        elements.forEach((el, i) => {
          console.log(`  ${i}: "${el.textContent.trim()}"`);
        });
      }
    });
    
    // Check for any element containing ₦
    console.log('Elements containing ₦:');
    document.querySelectorAll('*').forEach(el => {
      if (el.textContent && el.textContent.includes('₦') && el.children.length === 0) {
        console.log(`  ${el.tagName}.${el.className}: "${el.textContent.trim()}"`);
      }
    });
    
    console.log('=== END PRICE DEBUG ===');
  }
}