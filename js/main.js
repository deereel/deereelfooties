document.addEventListener('DOMContentLoaded', function() {
    // Set current year in footer
    document.getElementById('current-year').textContent = new Date().getFullYear();
    
    // Mobile menu toggle
    const menuToggle = document.querySelector('.menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    
    if (menuToggle && mobileMenu) {
      menuToggle.addEventListener('click', function() {
        mobileMenu.classList.toggle('hidden');
        
        // Toggle icon between bars and X
        const icon = menuToggle.querySelector('i');
        if (icon.classList.contains('fa-bars')) {
          icon.classList.remove('fa-bars');
          icon.classList.add('fa-times');
        } else {
          icon.classList.remove('fa-times');
          icon.classList.add('fa-bars');
        }
      });
    }
    
    // Header scroll effect
    const header = document.querySelector('header');
    
    if (header) {
      window.addEventListener('scroll', function() {
        if (window.scrollY > 10) {
          header.classList.add('shadow-sm');
        } else {
          header.classList.remove('shadow-sm');
        }
      });
    }
    
    // FAQ accordions (if present on page)
    const detailsElements = document.querySelectorAll('details');
    
    detailsElements.forEach(details => {
      details.addEventListener('toggle', function() {
        const summary = this.querySelector('summary');
        const icon = summary.querySelector('.transform');
        
        if (this.open && icon) {
          icon.style.transform = 'rotate(180deg)';
        } else if (icon) {
          icon.style.transform = 'rotate(0)';
        }
      });
    });
    
    // Newsletter form submission
    const newsletterForms = document.querySelectorAll('form');
    
    newsletterForms.forEach(form => {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        const emailInput = this.querySelector('input[type="email"]');
        
        if (emailInput && emailInput.value) {
          // In a real implementation, you would send this to your server
          alert('Thank you for subscribing to our newsletter!');
          emailInput.value = '';
        }
      });
    });
  
    // Shopping Cart Functionality
    initShoppingCart();
  });
  
  // Shopping Cart Functions
  function initShoppingCart() {
    // Load cart from localStorage
    let cart = JSON.parse(localStorage.getItem('drfCart')) || [];
    
    // Update cart count in header
    updateCartCount(cart);
    
    // Add to cart buttons (on product pages)
    const addToCartBtn = document.getElementById('add-to-cart-btn');
    
    if (addToCartBtn) {
      addToCartBtn.addEventListener('click', function() {
        // Get product details
        const productId = window.location.pathname.split('/').pop().replace('.html', '');
        const productName = document.querySelector('h1').textContent;
        const productPrice = document.querySelector('p.text-2xl').textContent.replace('€', '');
        const productImage = document.getElementById('main-product-image').src;
        const quantity = parseInt(document.getElementById('quantity').value);
        
        // Get selected options
        let selectedColor = 'Black'; // Default
        const colorButtons = document.querySelectorAll('button[aria-label]');
        colorButtons.forEach(button => {
          if (button.classList.contains('ring-2')) {
            selectedColor = button.getAttribute('aria-label');
          }
        });
        
        let selectedSize = 'UK 8'; // Default
        const sizeButtons = document.querySelectorAll('button:not([aria-label])');
        sizeButtons.forEach(button => {
          if (button.classList.contains('border-black')) {
            selectedSize = button.textContent;
          }
        });
        
        // Create cart item
        const cartItem = {
          id: productId,
          name: productName,
          price: parseFloat(productPrice),
          image: productImage,
          color: selectedColor,
          size: selectedSize,
          quantity: quantity
        };
        
        // Add to cart
        addToCart(cartItem);
      });
    }
    
    // Render cart page if on cart.html
    if (window.location.pathname.includes('cart.html')) {
      renderCartPage(cart);
    }
  }
  
  function addToCart(item) {
    // Get current cart
    let cart = JSON.parse(localStorage.getItem('drfCart')) || [];
    
    // Check if item already exists in cart
    const existingItemIndex = cart.findIndex(cartItem => 
      cartItem.id === item.id && 
      cartItem.color === item.color && 
      cartItem.size === item.size
    );
    
    if (existingItemIndex > -1) {
      // Update quantity if item exists
      cart[existingItemIndex].quantity += item.quantity;
    } else {
      // Add new item if it doesn't exist
      cart.push(item);
    }
    
    // Save cart to localStorage
    localStorage.setItem('drfCart', JSON.stringify(cart));
    
    // Update cart count
    updateCartCount(cart);
    
    // Show added to cart modal if it exists
    const addedToCartModal = document.getElementById('added-to-cart-modal');
    if (addedToCartModal) {
      addedToCartModal.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }
  }
  
  function updateCartCount(cart) {
    const cartCount = document.querySelector('.fa-shopping-bag + span');
    if (cartCount) {
      const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
      cartCount.textContent = totalItems;
    }
  }
  
  function renderCartPage(cart) {
    const cartContainer = document.getElementById('cart-items');
    const cartSummary = document.getElementById('cart-summary');
    
    if (!cartContainer || !cartSummary) return;
    
    if (cart.length === 0) {
      // Show empty cart message
      cartContainer.innerHTML = `
        <div class="text-center py-12">
          <h2 class="text-2xl font-light mb-4">Your Cart is Empty</h2>
          <p class="mb-6">Looks like you haven't added any items to your cart yet.</p>
          <a href="men.html" class="bg-black text-white px-6 py-2 inline-block hover:bg-gray-800 transition">
            CONTINUE SHOPPING
          </a>
        </div>
      `;
      cartSummary.style.display = 'none';
      return;
    }
    
    // Render cart items
    let cartItemsHTML = '';
    
    cart.forEach((item, index) => {
      cartItemsHTML += `
        <div class="flex flex-col md:flex-row border-b py-6">
          <div class="md:w-1/4 mb-4 md:mb-0">
            <div class="relative aspect-square overflow-hidden">
              <img src="${item.image}" alt="${item.name}" class="object-cover w-full h-full">
            </div>
          </div>
          <div class="md:w-3/4 md:pl-6 flex flex-col">
            <div class="flex justify-between mb-2">
              <h3 class="text-lg font-medium">${item.name}</h3>
              <button class="text-gray-500 remove-item" data-index="${index}">
                <i class="fas fa-times"></i>
              </button>
            </div>
            <p class="text-gray-500 mb-2">Size: ${item.size} | Color: ${item.color}</p>
            <p class="mb-4">€${item.price.toFixed(2)}</p>
            <div class="flex items-center mt-auto">
              <div class="flex border border-gray-300">
                <button class="px-3 py-1 update-quantity" data-index="${index}" data-action="decrease">-</button>
                <span class="px-3 py-1">${item.quantity}</span>
                <button class="px-3 py-1 update-quantity" data-index="${index}" data-action="increase">+</button>
              </div>
              <p class="ml-auto font-medium">€${(item.price * item.quantity).toFixed(2)}</p>
            </div>
          </div>
        </div>
      `;
    });
    
    cartContainer.innerHTML = cartItemsHTML;
    
    // Calculate totals
    const subtotal = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
    const shipping = subtotal >= 180 ? 0 : 15;
    const total = subtotal + shipping;
    
    // Update summary
    document.getElementById('subtotal').textContent = `€${subtotal.toFixed(2)}`;
    document.getElementById('shipping').textContent = shipping === 0 ? 'FREE' : `€${shipping.toFixed(2)}`;
    document.getElementById('total').textContent = `€${total.toFixed(2)}`;
    
    // Add event listeners for cart actions
    document.querySelectorAll('.remove-item').forEach(button => {
      button.addEventListener('click', function() {
        const index = parseInt(this.dataset.index);
        removeCartItem(index  function() {
        const index = parseInt(this.dataset.index);
        removeCartItem(index);
      });
    });
    
    document.querySelectorAll('.update-quantity').forEach(button => {
      button.addEventListener('click', function() {
        const index = parseInt(this.dataset.index);
        const action = this.dataset.action;
        updateCartItemQuantity(index, action);
      });
    });
    
    // Checkout button
    document.getElementById('checkout-btn').addEventListener('click', function() {
      // In a real implementation, this would redirect to checkout
      alert('Proceeding to checkout...');
    });
  }
  
  function removeCartItem(index) {
    let cart = JSON.parse(localStorage.getItem('drfCart')) || [];
    
    // Remove item at index
    cart.splice(index, 1);
    
    // Save updated cart
    localStorage.setItem('drfCart', JSON.stringify(cart));
    
    // Update UI
    updateCartCount(cart);
    renderCartPage(cart);
  }
  
  function updateCartItemQuantity(index, action) {
    let cart = JSON.parse(localStorage.getItem('drfCart')) || [];
    
    if (action === 'increase') {
      cart[index].quantity += 1;
    } else if (action === 'decrease' && cart[index].quantity > 1) {
      cart[index].quantity -= 1;
    }
    
    // Save updated cart
    localStorage.setItem('drfCart', JSON.stringify(cart));
    
    // Update UI
    updateCartCount(cart);
    renderCartPage(cart);
  }
  
  ## 4. Cart Page HTML
  
  ```html type="code" project="DeeReeL Footies HTML" file="cart.html"
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - DeeReeL Footies</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  </head>
  <body>
    <!-- Header -->
    <header class="sticky top-0 z-50 w-full transition-all duration-200 bg-white">
      <!-- Top Bar -->
      
      <!-- Secondary Navigation -->
      <div class="hidden md:flex justify-end items-center gap-6 px-6 py-2 text-xs">
        <a href="contact.html" class="hover:underline">CONTACT</a>
        <a href="our-history.html" class="hover:underline">OUR HISTORY</a>
        <a href="blog.html" class="hover:underline">BLOG</a>
      </div>
  
      <!-- Main Navigation -->
      <div class="flex items-center justify-between px-4 py-4 border-b">
        <!-- Mobile Menu Button -->
        <button class="md:hidden menu-toggle">
          <i class="fas fa-bars"></i>
        </button>
  
        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center space-x-8">
          <a href="men.html" class="hover:underline">MEN</a>
          <a href="women.html" class="hover:underline">WOMEN</a>
          <a href="customize.html" class="hover:underline">CUSTOMIZE</a>
          <a href="mto.html" class="hover:underline">MTO</a>
          <a href="outlet-shoes.html" class="hover:underline">OUTLET SHOES</a>
        </nav>
  
        <!-- Logo -->
        <a href="index.html" class="absolute left-1/2 transform -translate-x-1/2">
          <div class="relative w-32 h-8">
            <span class="font-serif text-xl tracking-widest">DEEREEL FOOTIES</span>
          </div>
        </a>
  
        <!-- Right Navigation -->
        <div class="flex items-center space-x-4">
          <a href="search.html" class="hover:text-gray-600">
            <i class="fas fa-search"></i>
            <span class="sr-only">Search</span>
          </a>
          <a href="shoemaking.html" class="hidden md:block hover:underline">SHOEMAKING</a>
          <a href="cart.html" class="hover:text-gray-600">
            <div class="relative">
              <i class="fas fa-shopping-bag"></i>
              <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                0
              </span>
            </div>
            <span class="sr-only">Cart</span>
          </a>
        </div>
      </div>
  
      <!-- Mobile Menu -->
      <div class="mobile-menu md:hidden bg-white absolute w-full z-50 border-b hidden">
        <nav class="flex flex-col p-4 space-y-4">
          <a href="men.html" class="py-2 border-b">MEN</a>
          <a href="women.html" class="py-2 border-b">WOMEN</a>
          <a href="customize.html" class="py-2 border-b">CUSTOMIZE</a>
          <a href="mto.html" class="py-2 border-b">MTO</a>
          <a href="outlet-shoes.html" class="py-2 border-b">OUTLET SHOES</a>
          <a href="shoemaking.html" class="py-2 border-b">SHOEMAKING</a>
          <a href="contact.html" class="py-2 border-b">CONTACT</a>
          <a href="our-history.html" class="py-2 border-b">OUR HISTORY</a>
          <a href="blog.html" class="py-2">BLOG</a>
        </nav>
      </div>
    </header>
  
    <!-- Main Content -->
    <main>
      <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-8">
          <h1 class="text-3xl font-light mb-2">SHOPPING CART</h1>
          <div class="flex items-center text-sm text-gray-500">
            <a href="index.html">Home</a>
            <span class="mx-2">/</span>
            <span>Cart</span>
          </div>
        </div>
  
        <div class="grid md:grid-cols-3 gap-8">
          <!-- Cart Items -->
          <div class="md:col-span-2">
            <div id="cart-items">
              <!-- Cart items will be rendered here by JavaScript -->
            </div>
          </div>
  
          <!-- Cart Summary -->
          <div id="cart-summary" class="bg-neutral-50 p-6">
            <h2 class="text-xl font-medium mb-6">ORDER SUMMARY</h2>
            
            <div class="space-y-4 mb-6">
              <div class="flex justify-between">
                <span>Subtotal</span>
                <span id="subtotal">€0.00</span>
              </div>
              <div class="flex justify-between">
                <span>Shipping</span>
                <span id="shipping">€0.00</span>
              </div>
              <div class="border-t pt-4 flex justify-between font-medium">
                <span>Total</span>
                <span id="total">€0.00</span>
              </div>
            </div>
            
            <button id="checkout-btn" class="w-full bg-black text-white py-3 mb-4 hover:bg-gray-800 transition">
              PROCEED TO CHECKOUT
            </button>
            
            <div class="text-sm text-gray-500 mb-6">
              <p>Free shipping on orders over €180</p>
              <p>Estimated delivery: 3-5 business days</p>
            </div>
            
            <div class="border-t pt-4">
              <h3 class="font-medium mb-2">ACCEPTED PAYMENT METHODS</h3>
              <div class="flex flex-wrap gap-2">
                <div class="w-12 h-8 bg-gray-200 flex items-center justify-center">
                  <i class="fab fa-cc-visa text-gray-700"></i>
                </div>
                <div class="w-12 h-8 bg-gray-200 flex items-center justify-center">
                  <i class="fab fa-cc-mastercard text-gray-700"></i>
                </div>
                <div class="w-12 h-8 bg-gray-200 flex items-center justify-center">
                  <i class="fab fa-cc-amex text-gray-700"></i>
                </div>
                <div class="w-12 h-8 bg-gray-200 flex items-center justify-center">
                  <i class="fab fa-cc-paypal text-gray-700"></i>
                </div>
                <div class="w-12 h-8 bg-gray-200 flex items-center justify-center">
                  <i class="fab fa-apple-pay text-gray-700"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  
    <!-- Footer -->
  <footer class="bg-white border-t">
    <!-- Main Footer -->
    <div class="max-w-7xl mx-auto py-12 px-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
      <div>
        <h3 class="font-medium mb-4">ABOUT DEEREEL FOOTIES</h3>
        <ul class="space-y-2 text-sm">
          <li>
            <a href="our-history.html" class="hover:underline">Our History</a>
          </li>
          <li>
            <a href="shoemaking.html" class="hover:underline">Craftsmanship</a>
          </li>
          <li>
            <a href="contact.html" class="hover:underline">Contact Us</a>
          </li>
          <li>
            <a href="careers.html" class="hover:underline">Careers</a>
          </li>
        </ul>
      </div>

      <div>
        <h3 class="font-medium mb-4">CUSTOMER SERVICE</h3>
        <ul class="space-y-2 text-sm">
          <li>
            <a href="shipping.html" class="hover:underline">Shipping & Delivery</a>
          </li>
          <li>
            <a href="returns.html" class="hover:underline">Returns & Exchanges</a>
          </li>
          <li>
            <a href="size-guide.html" class="hover:underline">Size Guide</a>
          </li>
          <li>
            <a href="faq.html" class="hover:underline">FAQ</a>
          </li>
          <li>
            <a href="care-guide.html" class="hover:underline">Shoe Care Guide</a>
          </li>
        </ul>
      </div>

      <div>
        <h3 class="font-medium mb-4">SHOP</h3>
        <ul class="space-y-2 text-sm">
          <li>
            <a href="men.html" class="hover:underline">Men's Collection</a>
          </li>
          <li>
            <a href="women.html" class="hover:underline">Women's Collection</a>
          </li>
          <li>
            <a href="customize.html" class="hover:underline">Customize</a>
          </li>
          <li>
            <a href="mto.html" class="hover:underline">Made to Order</a>
          </li>
          <li>
            <a href="outlet-shoes.html" class="hover:underline">Outlet</a>
          </li>          
        </ul>
      </div>

      <div>
        <h3 class="font-medium mb-4">NEWSLETTER</h3>
        <p class="text-sm mb-4">Subscribe to receive updates, access to exclusive deals, and more.</p>
        <form class="mb-6">
          <div class="flex flex-col space-y-2">
            <input
              type="email"
              class="px-4 py-2 border border-gray-300 focus:outline-none"
              placeholder="Your email address"
              required
            />
            <button type="submit" class="bg-black text-white px-6 py-2 hover:bg-gray-800 transition">
              SUBSCRIBE
            </button>
          </div>
        </form>
        

        <h3 class="font-medium mb-4">FOLLOW US</h3>
        <div class="flex space-x-4">
          <a href="instagram.com/deereelfooties" class="hover:text-gray-600">
            <i class="fab fa-instagram text-lg"></i>
            <span class="sr-only">Instagram</span>
          </a>
          <a href="tiktok.com/deereel.footies" class="hover:text-gray-600">
            <i class="fab fa-tiktok text-lg"></i>
            <span class="sr-only">Tiktok</span>
          </a>
          <a href="https://wa.me/2347031864772?text=Hello%20DeeReeL%20Footies%2C%20I%20would%20like%20to%20place%20order%20for..." class="hover:text-gray-600">
            <i class="fab fa-whatsapp text-lg"></i>
            <span class="sr-only">Twitter</span>
          </a>
          
        </div>
      </div>
    </div>

    <!-- Bottom Footer -->
    <div class="border-t py-6 px-4">
      <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center text-sm">
        <div class="mb-2 mb-md-0">
          <p>&copy; <span id="current-year"></span> DeeReeL Footies. All rights reserved.</p>
        </div>
        <div class="flex flex-wrap justify-center gap-4">
          <a href="terms.html" class="hover:underline">Terms & Conditions</a>
          <a href="privacy.html" class="hover:underline">Privacy Policy</a>
          <a href="cookies.html" class="hover:underline">Cookie Policy</a>
          <a href="sitemap.html" class="hover:underline">Sitemap</a>
        </div>
      </div>
    </div>
    
  </footer>

  <!-- Scroll to Top Button -->
  <a href="#" class="btn btn-dark position-fixed bottom-0 end-0 m-4 shadow rounded-circle" style="z-index: 999; width: 45px; height: 45px; display: none;" id="scrollToTop">
    <i class="fas fa-chevron-up"></i>
  </a>


  <script>
    document.querySelectorAll('.dropdown-submenu > a').forEach(function (element) {
      element.addEventListener('click', function (e) {
        const submenu = this.nextElementSibling;
        if (submenu && submenu.classList.contains('dropdown-menu')) {
          e.preventDefault();
          submenu.classList.toggle('show');
          e.stopPropagation();
        }
      });
    });
  </script>
  
  <script src="js/main.js"></script>
  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
  <script>
    // Set the current year in the footer
    document.getElementById('current-year').textContent = new Date().getFullYear();
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Bootstrap JS (with Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-..." crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

  <script>
    // Enable dropdowns on hover
    document.querySelectorAll('.dropdown').forEach(function (dropdown) {
      dropdown.addEventListener('mouseenter', function () {
        let toggle = this.querySelector('[data-bs-toggle="dropdown"]');
        if (toggle) {
          let dropdownInstance = bootstrap.Dropdown.getOrCreateInstance(toggle);
          dropdownInstance.show();
        }
      });
      dropdown.addEventListener('mouseleave', function () {
        let toggle = this.querySelector('[data-bs-toggle="dropdown"]');
        if (toggle) {
          let dropdownInstance = bootstrap.Dropdown.getOrCreateInstance(toggle);
          dropdownInstance.hide();
        }
      });
    });
  
    // Also add hover support for nested submenus (if you're using them)
    document.querySelectorAll('.dropdown-submenu').forEach(function (submenu) {
      submenu.addEventListener('mouseenter', function () {
        let submenuList = this.querySelector('.dropdown-menu');
        if (submenuList) submenuList.classList.add('show');
      });
      submenu.addEventListener('mouseleave', function () {
        let submenuList = this.querySelector('.dropdown-menu');
        if (submenuList) submenuList.classList.remove('show');
      });
    });
  </script>
  <script>
    const scrollBtn = document.getElementById("scrollToTop");
  
    window.addEventListener("scroll", () => {
      if (window.scrollY > 300) {
        scrollBtn.style.display = "flex";
      } else {
        scrollBtn.style.display = "none";
      }
    });
  
    scrollBtn.addEventListener("click", (e) => {
      e.preventDefault();
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  </script>
  </body>
  </html>