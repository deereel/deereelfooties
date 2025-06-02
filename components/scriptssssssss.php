<!-- External Libraries -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>

<!-- Main Application -->
<script type="module" src="/js/main.js"></script>

<!-- Core Functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  console.log('Scripts.php loaded - DOM ready');
  
  // Initialize AOS
  if (typeof AOS !== 'undefined') {
    AOS.init({ duration: 800, easing: 'ease-in-out', once: true });
  }

  // Set current year
  const year = document.getElementById('current-year');
  if (year) year.textContent = new Date().getFullYear();

  // Initialize all functionality
  initScrollToTop();
  initBootstrapComponents();
  initProductFunctionality();
  initCartFunctionality();
  initFormValidation();
});

// Scroll to Top
function initScrollToTop() {
  const scrollBtns = document.querySelectorAll('.scroll-to-top, #scrollBtn, #scrollToTop, [data-scroll-top]');
  
  window.addEventListener('scroll', () => {
    const shouldShow = window.scrollY > 300;
    scrollBtns.forEach(btn => {
      if (btn) btn.style.display = shouldShow ? "flex" : "none";
    });
  });
  
  scrollBtns.forEach(btn => {
    if (btn) {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: "smooth" });
      });
    }
  });
}

// Bootstrap Components
function initBootstrapComponents() {
  // Dropdowns
  document.querySelectorAll('.dropdown').forEach(dropdown => {
    dropdown.addEventListener('mouseenter', () => {
      const toggle = dropdown.querySelector('[data-bs-toggle="dropdown"]');
      if (toggle && typeof bootstrap !== 'undefined') {
        bootstrap.Dropdown.getOrCreateInstance(toggle).show();
      }
    });
    dropdown.addEventListener('mouseleave', () => {
      const toggle = dropdown.querySelector('[data-bs-toggle="dropdown"]');
      if (toggle && typeof bootstrap !== 'undefined') {
        bootstrap.Dropdown.getOrCreateInstance(toggle).hide();
      }
    });
  });

  // Tooltips and Popovers
  if (typeof bootstrap !== 'undefined') {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
    document.querySelectorAll('[data-bs-toggle="popover"]').forEach(el => new bootstrap.Popover(el));
  }
}

// Product Page Functionality
function initProductFunctionality() {
  console.log('Initializing product functionality');
  
  // Color Selection
  document.querySelectorAll('.color-option').forEach(btn => {
    btn.addEventListener('click', function() {
      // Remove selection from all
      document.querySelectorAll('.color-option').forEach(b => {
        b.classList.remove('ring-2', 'ring-black', 'ring-offset-2');
      });
      
      // Add selection to this one
      this.classList.add('ring-2', 'ring-black', 'ring-offset-2');
      
      // Update hidden input
      const hiddenInput = document.getElementById('selected-color');
      if (hiddenInput) hiddenInput.value = this.dataset.color;
      
      console.log('Color selected:', this.dataset.color);
    });
  });

  // Size Selection
  document.querySelectorAll('.size-option').forEach(btn => {
    btn.addEventListener('click', function() {
      // Remove selection from all
      document.querySelectorAll('.size-option').forEach(b => {
        b.classList.remove('bg-black', 'text-white', 'border-black');
        b.classList.add('border-gray-300');
      });
      
      // Add selection to this one
      this.classList.remove('border-gray-300');
      this.classList.add('bg-black', 'text-white', 'border-black');
      
      // Update hidden input
      const hiddenInput = document.getElementById('selected-size');
      if (hiddenInput) hiddenInput.value = this.dataset.size;
      
      console.log('Size selected:', this.dataset.size);
    });
  });

  // Width Selection
  document.querySelectorAll('.width-option').forEach(btn => {
    btn.addEventListener('click', function() {
      // Remove selection from all
      document.querySelectorAll('.width-option').forEach(b => {
        b.classList.remove('bg-black', 'text-white', 'border-black');
        b.classList.add('border-gray-300');
      });
      
      // Add selection to this one
      this.classList.remove('border-gray-300');
      this.classList.add('bg-black', 'text-white', 'border-black');
      
      // Update hidden input
      const hiddenInput = document.getElementById('selected-width');
      if (hiddenInput) hiddenInput.value = this.dataset.width;
      
      console.log('Width selected:', this.dataset.width);
    });
  });

  // Quantity Controls
  document.querySelectorAll('[data-action="increase"], [data-action="decrease"], .quantity-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const action = this.dataset.action || (this.textContent.trim() === '+' ? 'increase' : 'decrease');
      const input = document.getElementById('quantity') || this.closest('.flex')?.querySelector('input[type="number"]');
      
      if (input) {
        let value = parseInt(input.value) || 1;
        if (action === 'increase') {
          input.value = value + 1;
        } else if (action === 'decrease' && value > 1) {
          input.value = value - 1;
        }
        
        const hiddenInput = document.getElementById('selected-quantity');
        if (hiddenInput) hiddenInput.value = input.value;
        
        console.log('Quantity updated to:', input.value);
      }
    });
  });

  // Add to Cart Button
  const addToCartBtn = document.getElementById('add-to-cart-btn');
  if (addToCartBtn) {
    addToCartBtn.addEventListener('click', function(e) {
      e.preventDefault();
      console.log('Add to cart clicked');
      
      // Get selections
      const color = document.getElementById('selected-color')?.value;
      const size = document.getElementById('selected-size')?.value;
      const width = document.getElementById('selected-width')?.value;
      const quantity = parseInt(document.getElementById('quantity')?.value) || 1;
      
      // Validate
      if (!color) { alert('Please select a color'); return; }
      if (!size) { alert('Please select a size'); return; }
      if (!width) { alert('Please select a width'); return; }

      // Show loading
      const originalText = this.innerHTML;
      this.innerHTML = 'Adding...';
      this.disabled = true;

      // Get product data
      const productName = document.querySelector('h1')?.textContent || 'Product';
      const priceText = document.querySelector('.text-2xl')?.textContent || '₦0';
      const price = parseInt(priceText.replace(/[₦,]/g, '')) || 0;
      const productImage = document.getElementById('mainImage')?.src || '';
      const pathParts = window.location.pathname.split('/');
      const productId = pathParts[pathParts.length - 1].replace('.php', '');

      const productData = {
        id: productId,
        name: productName,
        price: price,
        image: productImage,
        color: color,
        size: size,
        width: width,
        quantity: quantity
      };

      // Add to cart
      setTimeout(() => {
        addToCart(productData);
        this.innerHTML = originalText;
        this.disabled = false;
        showAddToCartModal(productData);
      }, 1000);
    });
  }

  // Size Guide Modal
  const sizeGuideBtn = document.getElementById('size-guide-btn');
  const sizeGuideModal = document.getElementById('size-guide-modal');
  const closeSizeGuide = document.getElementById('close-size-guide');

  if (sizeGuideBtn && sizeGuideModal) {
    sizeGuideBtn.addEventListener('click', (e) => {
      e.preventDefault();
      sizeGuideModal.classList.remove('hidden');
    });
  }

  if (closeSizeGuide && sizeGuideModal) {
    closeSizeGuide.addEventListener('click', () => {
      sizeGuideModal.classList.add('hidden');
    });
  }

  if (sizeGuideModal) {
    sizeGuideModal.addEventListener('click', (e) => {
      if (e.target === sizeGuideModal) {
        sizeGuideModal.classList.add('hidden');
      }
    });
  }
}

// Cart Functions
function addToCart(item) {
  const cart = JSON.parse(localStorage.getItem('DRFCart')) || [];
  const existingIndex = cart.findIndex(cartItem => 
    cartItem.id === item.id && 
    cartItem.color === item.color && 
    cartItem.size === item.size && 
    cartItem.width === item.width
  );
  
  if (existingIndex > -1) {
    cart[existingIndex].quantity += item.quantity;
  } else {
    cart.push(item);
  }
  
  localStorage.setItem('DRFCart', JSON.stringify(cart));
  updateCartCount(cart);
  console.log('Item added to cart:', item);
}

function updateCartCount(cart) {
  const cartCount = document.querySelector('.fa-shopping-bag + span');
  if (cartCount) {
    cartCount.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
  }
}

function showAddToCartModal(item) {
  const modal = document.getElementById('added-to-cart-modal');
  if (modal) {
    const modalImage = document.getElementById('modal-product-image');
    const modalName = document.getElementById('modal-product-name');
    const modalVariant = document.getElementById('modal-product-variant');
    const modalPrice = document.getElementById('modal-product-price');
    
    if (modalImage) modalImage.src = item.image;
    if (modalName) modalName.textContent = item.name;
    if (modalVariant) modalVariant.textContent = `Size: ${item.size} | Width: ${item.width} | Color: ${item.color}`;
    if (modalPrice) modalPrice.textContent = `₦${item.price.toLocaleString()}`;
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Auto close after 5 seconds
    setTimeout(() => {
      modal.classList.add('hidden');
      document.body.style.overflow = 'auto';
    }, 5000);
  }
}

// Cart Page Functionality
function initCartFunctionality() {
  // Only run on cart page
  if (!window.location.pathname.includes('cart.php')) return;
  
  console.log('Initializing cart page');
  renderCartPage();
  
  // Modal close handlers
  const closeCartModal = document.getElementById('close-cart-modal');
  const continueShopping = document.getElementById('continue-shopping');
  const modal = document.getElementById('added-to-cart-modal');
  
  if (closeCartModal && modal) {
    closeCartModal.addEventListener('click', () => {
      modal.classList.add('hidden');
      document.body.style.overflow = 'auto';
    });
  }
  
  if (continueShopping && modal) {
    continueShopping.addEventListener('click', () => {
      modal.classList.add('hidden');
      document.body.style.overflow = 'auto';
    });
  }
  
  if (modal) {
    modal.addEventListener('click', (e) => {
      if (e.target === modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
      }
    });
  }
}

function renderCartPage() {
  const cart = JSON.parse(localStorage.getItem('DRFCart')) || [];
  const container = document.getElementById('cart-items');
  const summary = document.getElementById('cart-summary');
  
  if (!container) return;
  
  if (!cart.length) {
    container.innerHTML = `
      <div class="text-center py-12">
        <h2 class="text-2xl font-light mb-4">Your Cart is Empty</h2>
        <p class="mb-6">Looks like you haven't added any items to your cart yet.</p>
        <a href="/index.php" class="bg-black text-white px-6 py-2 inline-block hover:bg-gray-800 transition">CONTINUE SHOPPING</a>
      </div>`;
    if (summary) summary.style.display = 'none';
    return;
  }

  container.innerHTML = cart.map((item, i) => `
    <div class="flex flex-col md:flex-row border-b py-6 cart-item">
      <div class="md:w-1/4 mb-4 md:mb-0">
        <img src="${item.image}" alt="${item.name}" class="object-cover w-full h-full">
      </div>
      <div class="md:w-3/4 md:pl-6 flex flex-col">
        <div class="flex justify-between mb-2">
          <h3 class="text-lg font-medium">${item.name}</h3>
          <button class="text-gray-500 remove-item" data-index="${i}">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <p class="text-gray-500 mb-2">Size: ${item.size} | Width: ${item.width} | Color: ${item.color}</p>
        <p class="mb-4">₦${item.price.toLocaleString()}</p>
        <div class="flex items-center mt-auto">
          <div class="flex border border-gray-300">
            <button class="px-3 py-1 update-quantity" data-index="${i}" data-action="decrease">-</button>
            <span class="px-3 py-1 quantity-display">${item.quantity}</span>
                        <button class="px-3 py-1 update-quantity" data-index="${i}" data-action="increase">+</button>
                      </div>
                    </div>
                  </div>
                </div>
              `).join('');
            
              // Add event listeners for remove and quantity update buttons
              container.querySelectorAll('.remove-item').forEach(btn => {
                btn.addEventListener('click', function() {
                  const index = parseInt(this.dataset.index);
                  cart.splice(index, 1);
                  localStorage.setItem('DRFCart', JSON.stringify(cart));
                  renderCartPage();
                  updateCartCount(cart);
                });
              });
            
              container.querySelectorAll('.update-quantity').forEach(btn => {
                btn.addEventListener('click', function() {
                  const index = parseInt(this.dataset.index);
                  const action = this.dataset.action;
                  if (action === 'increase') {
                    cart[index].quantity += 1;
                  } else if (action === 'decrease' && cart[index].quantity > 1) {
                    cart[index].quantity -= 1;
                  }
                  localStorage.setItem('DRFCart', JSON.stringify(cart));
                  renderCartPage();
                  updateCartCount(cart);
                });
              });
            
              // Update summary
              if (summary) {
                summary.style.display = '';
                const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
                summary.querySelector('.cart-total').textContent = `₦${total.toLocaleString()}`;
              }
            }