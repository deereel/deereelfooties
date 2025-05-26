// Core application state and utilities
class DRFApp {
  constructor() {
    this.cartKey = 'DRFCart';
    this.customerKey = 'DRFCustomerInfo';
    this.userKey = 'DRFUser';
    this.init();
  }

  // Utility selectors
  $ = (sel, ctx = document) => ctx.querySelector(sel);
  $$ = (sel, ctx = document) => ctx.querySelectorAll(sel);

  // Cart management
  loadCart = () => JSON.parse(localStorage.getItem(this.cartKey)) || [];
  saveCart = (cart) => localStorage.setItem(this.cartKey, JSON.stringify(cart));

  updateCartCount(cart) {
    const cartCount = this.$('.fa-shopping-bag + span');
    if (cartCount) cartCount.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
  }

  getSelectedOptions() {
    return {
      color: this.$('#selected-color')?.value,
      size: this.$('#selected-size')?.value,
      width: this.$('#selected-width')?.value
    };
  }

  addToCart(item) {
    if (!item.color || !item.size || !item.width) {
      alert('Please select color, size, and width before adding to cart.');
      return;
    }

    const cart = this.loadCart();
    const index = cart.findIndex(ci =>
      ci.id === item.id && ci.color === item.color && ci.size === item.size && ci.width === item.width
    );

    index > -1 ? cart[index].quantity += item.quantity : cart.push(item);
    this.saveCart(cart);
    this.updateCartCount(cart);
    this.showAddToCartModal(item);
  }

  showAddToCartModal(item) {
    const modal = this.$('#added-to-cart-modal');
    if (modal) {
      this.$('#modal-product-image').src = item.image;
      this.$('#modal-product-image').alt = item.name;
      this.$('#modal-product-name').textContent = item.name;
      this.$('#modal-product-variant').textContent = `Size: ${item.size} | Width: ${item.width} | Color: ${item.color}`;
      this.$('#modal-product-price').textContent = `₦${item.price.toLocaleString()}`;
      modal.classList.remove('hidden');
      document.body.style.overflow = 'hidden';

      setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
      }, 5000);
    }
  }

  updateShippingProgress(subtotal) {
    const address = this.$('#shipping-address')?.value.toLowerCase() || '';
    const withinLagos = address.includes('lagos');
    const threshold = withinLagos ? 150000 : 250000;

    const progress = Math.min((subtotal / threshold) * 100, 100);
    const progressBar = this.$('#shipping-progress');
    const label = this.$('#shipping-progress-label');

    if (!progressBar || !label) return;

    progressBar.style.width = `${progress}%`;
    progressBar.classList.remove('bg-danger', 'bg-warning', 'bg-success');

    if (progress >= 100) {
      progressBar.classList.add('bg-success');
      label.textContent = '✅ You qualify for free shipping!';
    } else if (progress >= 50) {
      progressBar.classList.add('bg-warning');
      const left = threshold - subtotal;
      label.textContent = `Almost there! Spend ₦${left.toLocaleString()} more for free shipping${withinLagos ? ' in Lagos' : ''}.`;
    } else {
      progressBar.classList.add('bg-danger');
      const left = threshold - subtotal;
      label.textContent = `Spend ₦${left.toLocaleString()} more for free shipping${withinLagos ? ' in Lagos' : ''}.`;
    }
  }

  renderCartPage(cart) {
    const container = this.$('#cart-items');
    const summary = this.$('#cart-summary');
    if (!container || !summary) return;

    if (!cart.length) {
      container.innerHTML = `
        <div class="text-center py-12">
          <h2 class="text-2xl font-light mb-4">Your Cart is Empty</h2>
          <p class="mb-6">Looks like you haven't added any items to your cart yet.</p>
          <a href="/index.php" class="bg-black text-white px-6 py-2 inline-block hover:bg-gray-800 transition">CONTINUE SHOPPING</a>
        </div>`;
      summary.style.display = 'none';
      return;
    }

    container.innerHTML = cart.map((item, i) => `
      <div class="flex flex-col md:flex-row border-b py-6">
        <div class="md:w-1/4 mb-4 md:mb-0"><img src="${item.image}" alt="${item.name}" class="object-cover w-full h-full"></div>
        <div class="md:w-3/4 md:pl-6 flex flex-col">
          <div class="flex justify-between mb-2">
            <h3 class="text-lg font-medium">${item.name}</h3>
            <button class="text-gray-500 remove-item" data-index="${i}"><i class="fas fa-times"></i></button>
          </div>
          <p class="text-gray-500 mb-2">Size: ${item.size} | Width: ${item.width} | Color: ${item.color}</p>
          <p class="mb-4">₦${item.price.toLocaleString()}</p>
          <div class="flex items-center mt-auto">
            <div class="flex border border-gray-300">
              <button class="px-3 py-1 update-quantity" data-index="${i}" data-action="decrease">-</button>
              <span class="px-3 py-1">${item.quantity}</span>
              <button class="px-3 py-1 update-quantity" data-index="${i}" data-action="increase">+</button>
            </div>
            <p class="ml-auto font-medium">₦${(item.price * item.quantity).toLocaleString()}</p>
          </div>
        </div>
      </div>`).join('');

    const subtotal = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
    this.updateShippingProgress(subtotal);

    this.$('#subtotal').textContent = `₦${subtotal.toLocaleString()}`;
    this.$('#shipping').textContent = 'Determined by location';
    this.$('#total').textContent = `₦${subtotal.toLocaleString()}`;

    // Attach event listeners for cart interactions
    this.attachCartEventListeners(cart);
  }

  attachCartEventListeners(cart) {
    this.$$('.remove-item').forEach(btn =>
      btn.addEventListener('click', () => {
        const index = parseInt(btn.dataset.index);
        cart.splice(index, 1);
        this.saveCart(cart);
        this.updateCartCount(cart);
        this.renderCartPage(cart);
      })
    );

    this.$$('.update-quantity').forEach(btn =>
      btn.addEventListener('click', () => {
        const index = parseInt(btn.dataset.index);
        const action = btn.dataset.action;
        if (action === 'increase') cart[index].quantity += 1;
        else if (cart[index].quantity > 1) cart[index].quantity -= 1;
        this.saveCart(cart);
        this.updateCartCount(cart);
        this.renderCartPage(cart);
      })
    );
  }

  initMobileMenu() {
    const toggle = this.$('#mobileMenuToggle');
    const close = this.$('#closeMobileMenu');
    const overlay = this.$('.mobile-nav-overlay');
    
    if (!toggle || !close || !overlay) return;
    
    toggle.addEventListener('click', () => overlay.classList.replace('hidden', 'visible'));
    close.addEventListener('click', () => overlay.classList.replace('visible', 'hidden'));
    overlay.addEventListener('click', e => {
      if (e.target === overlay) overlay.classList.replace('visible', 'hidden');
    });
  }

  initAuthentication() {
    // Login form handler
    this.$('#modalLoginForm')?.addEventListener('submit', (event) => {
      event.preventDefault();
      const email = this.$('#modalLoginEmail').value.trim();
      const password = this.$('#modalLoginPassword').value.trim();

      fetch('/auth/login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          localStorage.setItem(this.userKey, JSON.stringify(data.user));
          alert('Login successful!');
          window.location.href = '/dashboard.php';
        } else {
          alert(data.error);
        }
      });
    });

    // Register form handler
    this.$('#modalRegisterForm')?.addEventListener('submit', (event) => {
      event.preventDefault();
      const name = this.$('#modalRegisterName').value.trim();
      const email = this.$('#modalRegisterEmail').value.trim();
      const password = this.$('#modalRegisterPassword').value;
      const confirm = this.$('#modalRegisterConfirmPassword').value;

      if (password !== confirm) {
        alert('Passwords do not match.');
        return;
      }

      fetch('/auth/signup.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}&confirm_password=${encodeURIComponent(confirm)}`
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert('🎉 Account created! You can now sign in.');
          this.$('#modalRegisterForm').classList.add('d-none');
          this.$('#modalLoginForm').classList.remove('d-none');
        } else {
          alert(data.error || 'Signup failed.');
        }
      });
    });

    // Logout handler
    this.$('#logoutBtn')?.addEventListener('click', (e) => {
      e.preventDefault();
      localStorage.removeItem(this.userKey);
      alert('Logged out!');
      location.reload();
    });
  }

  init() {
    // Initialize core functionality
    this.updateCartCount(this.loadCart());
    this.initMobileMenu();
    this.initAuthentication();

    // Initialize page-specific functionality
    if (location.pathname.includes('/cart.php')) {
      this.renderCartPage(this.loadCart());
    }

    // Display user welcome message
    const user = JSON.parse(localStorage.getItem(this.userKey));
    if (user && user.name) {
      const icon = this.$('#userIcon');
      if (icon) icon.setAttribute('title', `Welcome, ${user.name}`);
    }

    // Load existing customer info
    const existingCustomer = JSON.parse(localStorage.getItem(this.customerKey));
    if (existingCustomer) {
      const nameField = this.$('#client-name');
      const addressField = this.$('#shipping-address');
      if (nameField) nameField.value = existingCustomer.name || '';
      if (addressField) addressField.value = existingCustomer.address || '';
    }
  }
}

// Initialize app when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  const app = new DRFApp();
  window.drfApp = app;

  // Route-specific initialization
  const routes = {
    '/cart.php': () => app.renderCartPage(app.loadCart()),
    '/product.php': () => app.bindProductOptions?.(),
    '/checkout.php': () => app.initCheckout?.(),
    // add more routes as needed
  };

  const route = location.pathname;
  routes[route]?.();
});


// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
  module.exports = DRFApp;
}
