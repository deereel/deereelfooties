<<<<<<< HEAD
// js/main.js
document.addEventListener('DOMContentLoaded', function() {
  console.log('Main.js loaded');
  
  // Initialize app object if it doesn't exist
  window.app = window.app || {};
  
  // Initialize modules
  initModules();
  
  // Initialize UI components
  initUI();
  
  // Initialize page-specific functionality
  initPageSpecific();
  
  function initModules() {
    // Initialize cart module
    if (typeof CartManager !== 'undefined') {
      window.app.cart = new CartManager();
      window.app.cart.updateCartCount();
    }
    
    // Initialize auth module
    if (typeof AuthManager !== 'undefined') {
      window.app.auth = new AuthManager();
    }
    
    // Initialize UI module
    if (typeof UIManager !== 'undefined') {
      window.app.ui = new UIManager();
    }
=======
// Import modules
import { AuthManager } from './modules/auth.js';
import { CartManager } from './modules/cart.js';

document.addEventListener('DOMContentLoaded', () => {
  console.log('Main.js loaded');

  // Initialize modules
  const authManager = new AuthManager();
  const cartManager = new CartManager();

  // Initialize auth (handles all modal switching and forms)
  authManager.init();

  // Initialize cart (handles cart count and functionality)
  cartManager.updateCartCount();
  
  // Page-specific initialization
  initPageSpecific(cartManager);
});

function initPageSpecific(cartManager) {
  const currentPath = location.pathname;

  // Cart page
  if (currentPath.includes('/cart.php')) {
    cartManager.initCartPage();
    cartManager.populateCustomerForm();
    cartManager.initShippingAddressListener();
    initCheckoutButton(cartManager);
  }

  // Product pages
  if (currentPath.includes('/products/') && currentPath.includes('.php')) {
    initProductPage(cartManager);
  }

  // Category/listing pages
  if (isProductListingPage()) {
    initProductFiltering();
>>>>>>> parent of f36b17c (checkout page)
  }

  // Global functionality
  initMobileMenu();
  initScrollToTop();
}

function initProductPage(cartManager) {
  console.log('Initializing product page');
  
<<<<<<< HEAD
  function initUI() {
    // Initialize mobile menu
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileNavOverlay = document.querySelector('.mobile-nav-overlay');
    const closeMobileMenu = document.getElementById('closeMobileMenu');
    
    if (mobileMenuToggle && mobileNavOverlay && closeMobileMenu) {
      mobileMenuToggle.addEventListener('click', function() {
        mobileNavOverlay.classList.remove('hidden');
      });
      
      closeMobileMenu.addEventListener('click', function() {
        mobileNavOverlay.classList.add('hidden');
      });
      
      mobileNavOverlay.addEventListener('click', function(e) {
        if (e.target === mobileNavOverlay) {
          mobileNavOverlay.classList.add('hidden');
        }
      });
    }
    
    // Initialize scroll to top button
    const scrollToTopBtn = document.getElementById('scrollToTop');
    if (scrollToTopBtn) {
      window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
          scrollToTopBtn.style.display = 'flex';
        } else {
          scrollToTopBtn.style.display = 'none';
        }
      });
      
      scrollToTopBtn.addEventListener('click', function(e) {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    }
    
    // Initialize modals
    initModals();
  }
  
  function initModals() {
    // Account modal
    const accountModal = document.getElementById('accountModal');
    const accountBtn = document.getElementById('accountBtn');
    const closeAccountModal = document.getElementById('closeAccountModal');
    
    if (accountModal && accountBtn && closeAccountModal) {
      accountBtn.addEventListener('click', function() {
        accountModal.classList.remove('hidden');
      });
      
      closeAccountModal.addEventListener('click', function() {
        accountModal.classList.add('hidden');
      });
      
      accountModal.addEventListener('click', function(e) {
        if (e.target === accountModal) {
          accountModal.classList.add('hidden');
        }
      });
    }
    
    // Search modal
    const searchModal = document.getElementById('searchModal');
    const searchBtn = document.getElementById('searchBtn');
    const closeSearchModal = document.getElementById('closeSearchModal');
    
    if (searchModal && searchBtn && closeSearchModal) {
      searchBtn.addEventListener('click', function() {
        searchModal.classList.remove('hidden');
        setTimeout(() => {
          document.getElementById('searchInput')?.focus();
        }, 100);
      });
      
      closeSearchModal.addEventListener('click', function() {
        searchModal.classList.add('hidden');
      });
      
      searchModal.addEventListener('click', function(e) {
        if (e.target === searchModal) {
          searchModal.classList.add('hidden');
        }
      });
    }
    
    // Cart modal
    const cartModal = document.getElementById('cartModal');
    const cartBtn = document.getElementById('cartBtn');
    const closeCartModal = document.getElementById('closeCartModal');
    
    if (cartModal && cartBtn && closeCartModal) {
      cartBtn.addEventListener('click', function() {
        cartModal.classList.remove('hidden');
      });
      
      closeCartModal.addEventListener('click', function() {
        cartModal.classList.add('hidden');
      });
      
      cartModal.addEventListener('click', function(e) {
        if (e.target === cartModal) {
          cartModal.classList.add('hidden');
        }
      });
    }
  }
  
  function initPageSpecific() {
    const page = document.body.dataset.page;
    
    if (!page) return;
    
    // Home page
    if (page === 'home') {
      initHomePage();
    }
    
    // Product page
    if (page.includes('product-')) {
      initProductPage();
    }
    
    // Category pages
    if (page.includes('men-') || page.includes('women-')) {
      initCategoryPage();
    }
    
    // Cart page
    if (page === 'cart') {
      initCartPage();
    }
    
    // Dashboard page
    if (page === 'dashboard') {
      initDashboardPage();
    }
    
    // Customize page
    if (page === 'customize') {
      initCustomizePage();
    }
  }
  
  function initHomePage() {
    // Initialize hero slider if exists
    if (typeof Swiper !== 'undefined') {
      const heroSwiper = new Swiper('.hero-swiper', {
        loop: true,
        autoplay: {
          delay: 5000,
        },
        pagination: {
          el: '.swiper-pagination',
          clickable: true
        }
      });
    }
  }
  
  function initProductPage() {
    if (window.app.cart) {
      window.app.cart.initCartModalHandlers();
    }
    
    if (typeof ProductManager !== 'undefined') {
      const productManager = new ProductManager(window.app.cart);
      productManager.initProductPage();
    }
  }
  
  function initCategoryPage() {
    if (typeof ProductManager !== 'undefined') {
      const productManager = new ProductManager(window.app.cart);
      productManager.initCategoryPage();
    }
  }
  
  function initCartPage() {
    if (window.app.cart) {
      window.app.cart.initCartPage();
    }
  }
  
  function initDashboardPage() {
    // Initialize dashboard tabs
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
        
        // Update URL hash
        window.location.hash = section;
      });
    });
    
    // Check for hash in URL
    if (window.location.hash) {
      const hash = window.location.hash.substring(1);
      const link = document.querySelector(`.dashboard-nav .nav-link[data-section="${hash}"]`);
      if (link) {
        link.click();
      }
    }
  }
  
  function initCustomizePage() {
    // Customize page is handled by customize.js
  }
});
=======
  // Product option selection
  initProductOptions();
  
  // Quantity handlers
  initQuantityHandlers();
  
  // Size guide modal
  initSizeGuideModal();
  
  // Add to cart functionality
  initAddToCartButton(cartManager);
  
  // Cart modal handlers
  cartManager.initCartModalHandlers();
}

function initProductOptions() {
  // Color selection
  document.querySelectorAll('.color-option').forEach(btn => {
    btn.addEventListener('click', function() {
      document.querySelectorAll('.color-option').forEach(b => {
        b.classList.remove('ring-2', 'ring-black', 'ring-offset-2');
      });
      this.classList.add('ring-2', 'ring-black', 'ring-offset-2');
      
      const hiddenInput = document.getElementById('selected-color');
      if (hiddenInput) hiddenInput.value = this.dataset.color;
    });
  });

  // Size selection
  document.querySelectorAll('.size-option').forEach(btn => {
    btn.addEventListener('click', function() {
      document.querySelectorAll('.size-option').forEach(b => {
        b.classList.remove('bg-black', 'text-white', 'border-black');
        b.classList.add('border-gray-300');
      });
      this.classList.remove('border-gray-300');
      this.classList.add('bg-black', 'text-white', 'border-black');
      
      const hiddenInput = document.getElementById('selected-size');
      if (hiddenInput) hiddenInput.value = this.dataset.size;
    });
  });

  // Width selection
  document.querySelectorAll('.width-option').forEach(btn => {
    btn.addEventListener('click', function() {
      document.querySelectorAll('.width-option').forEach(b => {
        b.classList.remove('bg-black', 'text-white', 'border-black');
        b.classList.add('border-gray-300');
      });
      this.classList.remove('border-gray-300');
      this.classList.add('bg-black', 'text-white', 'border-black');
      
      const hiddenInput = document.getElementById('selected-width');
      if (hiddenInput) hiddenInput.value = this.dataset.width;
    });
  });
}

function initQuantityHandlers() {
  document.querySelectorAll('.quantity-btn, [data-action="increase"], [data-action="decrease"]').forEach(btn => {
    btn.addEventListener('click', () => {
      const action = btn.dataset.action || (btn.textContent.trim() === '+' ? 'increase' : 'decrease');
      const input = btn.closest('.flex')?.querySelector('#quantity') || document.getElementById('quantity');
      
      if (input) {
        let value = parseInt(input.value) || 1;
        if (action === 'increase') {
          input.value = value + 1;
        } else if (action === 'decrease' && value > 1) {
          input.value = value - 1;
        }
        
        // Update hidden input if exists
        const hiddenInput = document.getElementById('selected-quantity');
        if (hiddenInput) hiddenInput.value = input.value;
      }
    });
  });

  // Handle direct input changes
  const quantityInput = document.getElementById('quantity');
  if (quantityInput) {
    quantityInput.addEventListener('input', () => {
      if (parseInt(quantityInput.value) < 1) quantityInput.value = 1;
      const hiddenInput = document.getElementById('selected-quantity');
      if (hiddenInput) hiddenInput.value = quantityInput.value;
    });
  }
}

function initSizeGuideModal() {
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

  // Close modal when clicking outside
  if (sizeGuideModal) {
    sizeGuideModal.addEventListener('click', (e) => {
      if (e.target === sizeGuideModal) {
        sizeGuideModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
      }
    });
  }
}

function getProductData() {
  // Get product information from the page
  const productId = location.pathname.split('/').pop().replace('.php', '');
  const productName = document.querySelector('h1, h2, h3.fw-bold')?.textContent.trim() || '';
  const priceElement = document.querySelector('.text-2xl, .price, [class*="price"]');
  const rawPrice = priceElement?.textContent || '0';
  const productPrice = parseFloat(rawPrice.replace(/[₦€$,]/g, '').trim()) || 0;
  const productImage = document.querySelector('#mainImage, .product-image img, img')?.src || '';
  const quantity = parseInt(document.getElementById('quantity')?.value) || 1;
  
  // Get selected options
  const color = document.getElementById('selected-color')?.value || '';
  const size = document.getElementById('selected-size')?.value || '';
  const width = document.getElementById('selected-width')?.value || '';

  console.log('Product data:', {
    id: productId,
    name: productName,
    price: productPrice,
    image: productImage,
    color,
    size,
    width,
    quantity
  });

  return {
    id: productId,
    name: productName,
    price: productPrice,
    image: productImage,
    color,
    size,
    width,
    quantity
  };
}

function initMobileMenu() {
  const toggle = document.getElementById('mobileMenuToggle');
  const close = document.getElementById('closeMobileMenu');
  const overlay = document.querySelector('.mobile-nav-overlay');
  
  if (!toggle || !close || !overlay) return;
  
  toggle.addEventListener('click', () => {
    overlay.classList.remove('hidden');
    overlay.classList.add('visible');
  });
  
  close.addEventListener('click', () => {
    overlay.classList.remove('visible');
    overlay.classList.add('hidden');
  });
  
  overlay.addEventListener('click', (e) => {
    if (e.target === overlay) {
      overlay.classList.remove('visible');
      overlay.classList.add('hidden');
    }
  });
}

function initPageSpecific(cartManager) {
  // Initialize checkout functionality
  const checkoutBtn = document.getElementById('checkout-btn');
  if (checkoutBtn) {
    checkoutBtn.addEventListener('click', () => {
      const cart = cartManager.loadCart();
      if (!cart.length) {
        alert('Your cart is empty');
        return;
      }

      if (cartManager.saveCustomerInfo()) {
        alert('Order information saved! You can now proceed with payment.');
      }
    });
  }

  // Load existing customer info on cart page
  if (location.pathname.includes('/cart.php')) {
    cartManager.populateCustomerForm();
    cartManager.initShippingAddressListener();
  }

  // Initialize product filtering on category pages
  if (isProductListingPage()) {
    initProductFiltering();
  }

  // Initialize scroll to top button
  initScrollToTop();
}

function isProductListingPage() {
  const listingPages = ['/men.php', '/women.php', '/products.php'];
  return listingPages.some(page => location.pathname.includes(page)) || 
         location.pathname.includes('/products/') && !location.pathname.includes('.php');
}

function initProductFiltering() {
  // Product grid sorting
  const productGrid = document.getElementById('product-grid');
  const sortSelect = document.getElementById('sortSelect');

  if (productGrid && sortSelect) {
    sortSelect.addEventListener('change', function () {
      const selectedValue = this.value;
      const products = Array.from(productGrid.children);

      if (selectedValue === 'low') {
        products.sort((a, b) => parseFloat(a.dataset.price) - parseFloat(b.dataset.price));
      } else if (selectedValue === 'high') {
        products.sort((a, b) => parseFloat(b.dataset.price) - parseFloat(a.dataset.price));
      } else {
        products.sort((a, b) => new Date(b.dataset.date) - new Date(a.dataset.date));
      }

      productGrid.innerHTML = '';
      products.forEach(product => productGrid.appendChild(product));
    });
  }

  // Type filter highlight based on URL params
  const urlParams = new URLSearchParams(window.location.search);
  const type = (urlParams.get('type') || '').toLowerCase().replace(/s$/, '').trim();

  document.querySelectorAll('.type-filter').forEach(btn => {
    const btnType = btn.dataset.type.toLowerCase().replace(/s$/, '').trim();
    if (btnType === type) {
      btn.classList.add('bg-black', 'text-white', 'border-black');
    }
  });

  // Category filter highlight
  const currentUrl = window.location.pathname.toLowerCase();
  document.querySelectorAll('.cat-filter').forEach(link => {
    const match = link.dataset.cat;
    if (match && currentUrl.includes(match)) {
      link.classList.add('bg-black', 'text-white', 'border-black');
    }
  });
}

function initScrollToTop() {
  const scrollBtns = document.querySelectorAll('.scroll-to-top, #scrollBtn, #scrollToTop, [data-scroll-top]');
  
  const handleScrollVisibility = () => {
    const shouldShow = window.scrollY > 300;
    scrollBtns.forEach(btn => {
      if (btn) {
        btn.style.display = shouldShow ? "flex" : "none";
      }
    });
  };

  window.addEventListener("scroll", handleScrollVisibility);
  
  scrollBtns.forEach(btn => {
    if (btn) {
      btn.addEventListener("click", (e) => {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: "smooth" });
      });
    }
  });
}

// Global functions that might be called from HTML
window.filterByType = function(type) {
  // Clear existing type filters
  document.querySelectorAll('.type-filter').forEach(btn => {
    btn.classList.remove('bg-black', 'text-white', 'border-black');
    if (btn.dataset.type === type) {
      btn.classList.add('bg-black', 'text-white', 'border-black');
    }
  });
  
  // Apply filter logic here if needed
  console.log('Filtering by type:', type);
};

// Export for potential use in other modules
export { initProductPage, initMobileMenu, getProductData };
>>>>>>> parent of f36b17c (checkout page)
