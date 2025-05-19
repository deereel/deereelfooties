document.addEventListener('DOMContentLoaded', () => {
  const cartKey = 'DRFCart';
  const $ = (sel, ctx = document) => ctx.querySelector(sel);
  const $$ = (sel, ctx = document) => ctx.querySelectorAll(sel);
  const loadCart = () => JSON.parse(localStorage.getItem(cartKey)) || [];
  const saveCart = (cart) => localStorage.setItem(cartKey, JSON.stringify(cart));

  const updateCartCount = (cart) => {
    const cartCount = $('.fa-shopping-bag + span');
    if (cartCount) cartCount.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
  };

  const getSelectedOptions = () => ({
    color: $('#selected-color')?.value,
    size: $('#selected-size')?.value,
    width: $('#selected-width')?.value
  });

  const addToCart = (item) => {
    if (!item.color || !item.size || !item.width) {
      alert('Please select color, size, and width before adding to cart.');
      return;
    }

    const cart = loadCart();
    const index = cart.findIndex(ci =>
      ci.id === item.id && ci.color === item.color && ci.size === item.size && ci.width === item.width
    );

    index > -1 ? cart[index].quantity += item.quantity : cart.push(item);
    saveCart(cart);
    updateCartCount(cart);

    const modal = $('#added-to-cart-modal');
    if (modal) {
      $('#modal-product-image').src = item.image;
      $('#modal-product-image').alt = item.name;
      $('#modal-product-name').textContent = item.name;
      $('#modal-product-variant').textContent = `Size: ${item.size} | Width: ${item.width} | Color: ${item.color}`;
      $('#modal-product-price').textContent = `₦${item.price.toLocaleString()}`;
      modal.classList.remove('hidden');
      document.body.style.overflow = 'hidden';

      setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
      }, 5000);
    }
  };

  const handleSelection = (groupClass, hiddenInputId) => {
    const options = document.querySelectorAll(groupClass);
    options.forEach(option => {
      option.addEventListener('click', () => {
        options.forEach(o => o.classList.remove('selected', 'ring-4', 'ring-black', 'bg-dark', 'text-white'));
        option.classList.add('selected', 'ring-4', 'ring-black', 'bg-dark', 'text-white');
        const type = groupClass.includes('color') ? 'color' : groupClass.includes('size') ? 'size' : 'width';
        document.getElementById(hiddenInputId).value = option.dataset[type];
      });
    });
  };

  const initAddToCartButton = () => {
    const btn = $('#add-to-cart-btn');
    if (!btn) return;

    btn.addEventListener('click', () => {
      const productId = location.pathname.split('/').pop().replace('.html', '');
      const productName = $('h3.fw-bold')?.textContent.trim() || '';
      const rawPrice = $('p.text-2xl')?.textContent || '0';
      const productPrice = parseFloat(rawPrice.replace(/[₦€,]/g, '').trim()) || 0;
      const productImage = $('#mainImage')?.src || '';
      const quantity = parseInt($('#quantity')?.value) || 1;
      const { color, size, width } = getSelectedOptions();

      if (!color || !size || !width) {
        alert('Please select color, size, and width before adding to cart.');
        return;
      }

      addToCart({
        id: productId,
        name: productName,
        price: productPrice,
        image: productImage,
        color,
        size,
        width,
        quantity
      });
    });
  };

  const updateShippingProgress = (subtotal) => {
    const address = document.getElementById('shipping-address')?.value.toLowerCase() || '';
    const withinLagos = address.includes('lagos');
    const threshold = withinLagos ? 150000 : 250000;

    const progress = Math.min((subtotal / threshold) * 100, 100);
    const progressBar = document.getElementById('shipping-progress');
    const label = document.getElementById('shipping-progress-label');

    // Update width
    progressBar.style.width = `${progress}%`;

    // Reset classes
    progressBar.classList.remove('bg-danger', 'bg-warning', 'bg-success');

    // Set color based on progress
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
  };


  const renderCartPage = (cart) => {
    const container = $('#cart-items'), summary = $('#cart-summary');
    if (!container || !summary) return;

    if (!cart.length) {
      container.innerHTML = `
        <div class="text-center py-12">
          <h2 class="text-2xl font-light mb-4">Your Cart is Empty</h2>
          <p class="mb-6">Looks like you haven't added any items to your cart yet.</p>
          <a href="/index.html" class="bg-black text-white px-6 py-2 inline-block hover:bg-gray-800 transition">CONTINUE SHOPPING</a>
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

    // Update the free shipping progress bar
    updateShippingProgress(subtotal);

    // Display prices in the summary section
    $('#subtotal').textContent = `₦${subtotal.toLocaleString()}`;
    $('#shipping').textContent = 'Determined by location';
    $('#total').textContent = `₦${subtotal.toLocaleString()}`;

    // Display shipping info hint
    const hintContainer = document.getElementById('shipping-hint');
    if (hintContainer) {
      hintContainer.innerHTML = `
        <p class="text-sm text-gray-500 mt-2">
          Free shipping on orders above ₦150,000 within Lagos and ₦250,000 outside Lagos.
        </p>
      `;
    }

   

    $$('.remove-item').forEach(btn =>
      btn.addEventListener('click', () => {
        const index = parseInt(btn.dataset.index);
        cart.splice(index, 1);
        saveCart(cart);
        updateCartCount(cart);
        renderCartPage(cart);
      })
    );

    $$('.update-quantity').forEach(btn =>
      btn.addEventListener('click', () => {
        const index = parseInt(btn.dataset.index);
        const action = btn.dataset.action;
        if (action === 'increase') cart[index].quantity += 1;
        else if (cart[index].quantity > 1) cart[index].quantity -= 1;
        saveCart(cart);
        updateCartCount(cart);
        renderCartPage(cart);
      })
    );
  };

  const initMobileMenu = () => {
    const toggle = $('#mobileMenuToggle'), close = $('#closeMobileMenu'), overlay = $('.mobile-nav-overlay');
    if (!toggle || !close || !overlay) return;
    toggle.addEventListener('click', () => overlay.classList.replace('hidden', 'visible'));
    close.addEventListener('click', () => overlay.classList.replace('visible', 'hidden'));
    overlay.addEventListener('click', e => {
      if (e.target === overlay) overlay.classList.replace('visible', 'hidden');
    });
  };

  const initPage = () => {
    handleSelection('.color-option', 'selected-color');
    handleSelection('.size-option', 'selected-size');
    handleSelection('.width-option', 'selected-width');
    initAddToCartButton();
    updateCartCount(loadCart());
    if (location.pathname.includes('/cart.html')) renderCartPage(loadCart());
    initMobileMenu();

    $('#close-cart-modal')?.addEventListener('click', () => {
      $('#added-to-cart-modal').classList.add('hidden');
      document.body.style.overflow = 'auto';
    });

    $('#continue-shopping')?.addEventListener('click', () => {
      $('#added-to-cart-modal').classList.add('hidden');
      document.body.style.overflow = 'auto';
    });
  };

  // ** Add this part to handle modal toggle for user icon **
  const userIconButton = $('#userIcon');  // Assuming this is the user icon's button
  const userAccountModal = new bootstrap.Modal(document.getElementById('userAccountModal'));

  if (userIconButton) {
    userIconButton.addEventListener('click', () => {
      userAccountModal.show();
    });
  }

  // Switch between Sign In and Sign Up forms
  document.getElementById('switchToSignUp').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('loginForm').classList.add('d-none');
    document.getElementById('signUpForm').classList.remove('d-none');
  });


   // Modal Login form validation
  document.getElementById('modalLoginForm').addEventListener('submit', function(event) {
    const email = document.getElementById('modalLoginEmail').value.trim();
    const password = document.getElementById('modalLoginPassword').value.trim();
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailPattern.test(email)) {
      alert('Please enter a valid email address.');
      event.preventDefault();
      return;
    }
    if (password.length === 0) {
      alert('Please enter your password.');
      event.preventDefault();
      return;
    }
    // Add real login logic here later
  });

  // Modal Register form validation
  document.getElementById('modalRegisterForm').addEventListener('submit', function(event) {
    const name = document.getElementById('modalRegisterName').value.trim();
    const email = document.getElementById('modalRegisterEmail').value.trim();
    const password = document.getElementById('modalRegisterPassword').value;
    const confirmPassword = document.getElementById('modalRegisterConfirmPassword').value;
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (name.length === 0) {
      alert('Please enter your full name.');
      event.preventDefault();
      return;
    }
    if (!emailPattern.test(email)) {
      alert('Please enter a valid email address.');
      event.preventDefault();
      return;
    }
    if (password.length < 6) {
      alert('Password must be at least 6 characters long.');
      event.preventDefault();
      return;
    }
    if (password !== confirmPassword) {
      alert('Passwords do not match.');
      event.preventDefault();
      return;
    }
    // Add real registration logic here later
  });

  initPage();
});
