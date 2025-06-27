// Non-module version of CartManager for browsers without module support

class CartManager {
  constructor() {
    this.cartKey = 'DRFCart';
    this.customerKey = 'DRFCustomerInfo';
  }

  isUserLoggedIn() {
    return !!(
      window.app && window.app.auth && (window.app.auth.isLoggedIn && window.app.auth.isLoggedIn()) || 
      (window.app.auth.getCurrentUser && window.app.auth.getCurrentUser())
    );
  }

  getUserId() {
    if (window.app && window.app.auth && window.app.auth.getCurrentUser) {
      const user = window.app.auth.getCurrentUser();
      return user.user_id || user.id || user.email;
    }
    return null;
  }

  getCurrentUser() {
    if (window.app && window.app.auth && window.app.auth.getCurrentUser) {
      return window.app.auth.getCurrentUser();
    }
    return null;
  }

  loadCart() {
    if (this.isUserLoggedIn()) {
      const user = this.getCurrentUser();
      return this.loadUserCart(user);
    } else {
      const cart = localStorage.getItem(this.cartKey);
      return cart ? JSON.parse(cart) : [];
    }
  }

  loadUserCart(user) {
    const userCartKey = `DRFCart_${user.user_id || user.id || user.email}`;
    const cartData = localStorage.getItem(userCartKey);
    if (cartData) {
      return JSON.parse(cartData);
    } else {
      return [];
    }
  }

  saveCart(cart) {
    if (this.isUserLoggedIn()) {
      const user = this.getCurrentUser();
      this.saveUserCart(user, cart);
    } else {
      localStorage.setItem(this.cartKey, JSON.stringify(cart));
    }
    this.updateCartCount();
    this.syncCartWithDatabase();
  }

  saveUserCart(user, cart) {
    const userCartKey = `DRFCart_${user.user_id || user.id || user.email}`;
    localStorage.setItem(userCartKey, JSON.stringify(cart));
  }

  updateCartCount() {
    const cart = this.loadCart();
    const cartCount = document.querySelector('.fa-shopping-bag + span');
    if (cartCount) {
      cartCount.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
    }
  }

  addToCart(item) {
    if (!item.color || !item.size || !item.width) {
      alert('Please select color, size, and width before adding to cart.');
      return false;
    }
    const cart = this.loadCart();
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
    this.saveCart(cart);
    this.showAddToCartModal(item);
    return true;
  }

  removeFromCart(index) {
    const cart = this.loadCart();
    cart.splice(index, 1);
    this.saveCart(cart);
    this.renderCartPage();
  }

  updateQuantity(index, action) {
    const cart = this.loadCart();
    if (cart[index]) {
      if (action === 'increase') {
        cart[index].quantity += 1;
      } else if (action === 'decrease' && cart[index].quantity > 1) {
        cart[index].quantity -= 1;
      }
      this.saveCart(cart);
      this.renderCartPage();
    }
  }

  showAddToCartModal(item) {
    const modal = document.getElementById('added-to-cart-modal');
    if (!modal) return;
    const modalImage = modal.querySelector('img') || document.getElementById('modal-product-image');
    const modalName = modal.querySelector('h3') || document.getElementById('modal-product-name');
    const modalVariant = modal.querySelector('.text-gray-500') || document.getElementById('modal-product-variant');
    const modalPrice = modal.querySelector('p:last-child') || document.getElementById('modal-product-price');
    if (modalImage) {
      modalImage.src = item.image;
      modalImage.alt = item.name;
    }
    if (modalName) modalName.textContent = item.name;
    if (modalVariant) modalVariant.textContent = `Size: ${item.size} | Width: ${item.width} | Color: ${item.color}`;
    if (modalPrice) modalPrice.textContent = `₦${item.price.toLocaleString()}`;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    setTimeout(() => {
      modal.classList.add('hidden');
      document.body.style.overflow = 'auto';
    }, 5000);
  }

  renderCartPage() {
    const cart = this.loadCart();
    const container = document.getElementById('cart-items');
    const summary = document.getElementById('cart-summary');
    if (!container) return;
    if (!cart.length) {
      container.innerHTML = `<div class="col-span-full text-center py-12">
        <h2 class="text-2xl font-light mb-4">Your Cart is Empty</h2>
        <p class="mb-6">Looks like you haven't added any items to your cart yet.</p>
        <a href="/index.php" class="bg-black text-white px-6 py-2 inline-block hover:bg-gray-800 transition">CONTINUE SHOPPING</a>
      </div>`;
      if (summary) summary.style.display = 'none';
      return;
    }
    if (summary) summary.style.display = 'block';
    container.innerHTML = cart.map((item, i) => `
      <div class="flex flex-col md:flex-row border-b py-6 cart-item" data-index="${i}">
        <div class="md:w-1/4 mb-4 md:mb-0">
          <img src="${item.image}" alt="${item.name}" class="object-cover w-full h-full rounded">
        </div>
        <div class="md:w-3/4 md:pl-6 flex flex-col">
          <div class="flex justify-between mb-2">
            <h3 class="text-lg font-medium">${item.name}</h3>
            <button class="text-gray-500 hover:text-red-500 remove-item" data-index="${i}">
              <i class="fas fa-times"></i>
            </button>
          </div>
          <p class="text-gray-500 mb-2">Size: ${item.size} | Width: ${item.width} | Color: ${item.color}</p>
          <p class="text-lg font-medium mb-4">₦${item.price.toLocaleString()}</p>
          <div class="flex items-center justify-between mt-auto">
            <div class="flex items-center border border-gray-300 rounded">
              <button class="px-3 py-2 hover:bg-gray-100 update-quantity" data-index="${i}" data-action="decrease">-</button>
              <span class="px-4 py-2 border-l border-r border-gray-300 quantity-display">${item.quantity}</span>
              <button class="px-3 py-2 hover:bg-gray-100 update-quantity" data-index="${i}" data-action="increase">+</button>
            </div>
            <p class="text-lg font-bold item-total">₦${(item.price * item.quantity).toLocaleString()}</p>
          </div>
        </div>
      </div>
    `).join('');
    const subtotal = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
    this.updateCartTotals(subtotal);
    this.updateShippingProgress(subtotal);
    this.addCartEventListeners();
  }

  updateCartTotals(subtotal) {
    const subtotalElement = document.getElementById('subtotal');
    const totalElement = document.getElementById('total');
    const shippingElement = document.getElementById('shipping');
    if (subtotalElement) subtotalElement.textContent = `₦${subtotal.toLocaleString()}`;
    if (totalElement) totalElement.textContent = `₦${subtotal.toLocaleString()}`;
    if (shippingElement) shippingElement.textContent = 'Calculated at checkout';
  }

  updateShippingProgress(subtotal) {
    const addressInput = document.getElementById('shipping-address');
    const address = addressInput ? addressInput.value.toLowerCase() : '';
    const withinLagos = address.includes('lagos');
    const threshold = withinLagos ? 150000 : 250000;
    const progress = Math.min((subtotal / threshold) * 100, 100);
    let progressBar = document.getElementById('shipping-progress');
    let progressLabel = document.getElementById('shipping-progress-label');
    if (!progressBar || !progressLabel) {
      this.createShippingProgressElements();
      progressBar = document.getElementById('shipping-progress');
      progressLabel = document.getElementById('shipping-progress-label');
    }
    if (!progressBar || !progressLabel) return;
    progressBar.style.width = `${progress}%`;
    progressBar.className = 'h-2 rounded-full transition-all duration-300';
    const left = threshold - subtotal;
    if (progress >= 100) {
      progressBar.classList.add('bg-green-500');
      progressLabel.innerHTML = '<span class="text-green-600 font-medium">✅ You qualify for free shipping!</span>';
    } else if (progress >= 50) {
      progressBar.classList.add('bg-yellow-500');
      progressLabel.innerHTML = `<span class="text-yellow-600">Almost there! Spend ₦${left.toLocaleString()} more for free shipping${withinLagos ? ' in Lagos' : ''}.</span>`;
    } else {
      progressBar.classList.add('bg-red-500');
      progressLabel.innerHTML = `<span class="text-red-600">Spend ₦${left.toLocaleString()} more for free shipping${withinLagos ? ' in Lagos' : ''}.</span>`;
    }
  }

  createShippingProgressElements() {
    const summary = document.getElementById('cart-summary');
    if (!summary) return;
    const progressContainer = document.createElement('div');
    progressContainer.className = 'mb-6 p-4 bg-gray-50 rounded-lg border';
    progressContainer.innerHTML = `
      <h4 class="font-medium mb-3">Free Shipping Progress</h4>
      <div class="w-full bg-gray-200 rounded-full h-2 mb-3">
        <div id="shipping-progress" class="h-2 rounded-full bg-red-500 transition-all duration-300" style="width: 0%"></div>
      </div>
      <p id="shipping-progress-label" class="text-sm text-red-600">Add items to see shipping progress</p>
      <p class="text-xs text-gray-500 mt-2">Free shipping: ₦150,000+ within Lagos, ₦250,000+ outside Lagos</p>
    `;
    summary.insertBefore(progressContainer, summary.firstChild);
  }

  addCartEventListeners() {
    document.querySelectorAll('.remove-item').forEach(btn => {
      btn.addEventListener('click', () => {
        const index = parseInt(btn.dataset.index);
        const cart = this.loadCart();
        cart.splice(index, 1);
        this.saveCart(cart);
        this.renderCartPage();
      });
    });
    document.querySelectorAll('.update-quantity').forEach(btn => {
      btn.addEventListener('click', () => {
        const index = parseInt(btn.dataset.index);
        const action = btn.dataset.action;
        const cart = this.loadCart();
        if (action === 'increase') {
          cart[index].quantity += 1;
        } else if (action === 'decrease' && cart[index].quantity > 1) {
          cart[index].quantity -= 1;
        }
        this.saveCart(cart);
        this.renderCartPage();
      });
    });
  }
}
