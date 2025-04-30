document.addEventListener('DOMContentLoaded', () => {
  const cartKey = 'DRFCart';

  // Utility Functions
  const $ = (selector, ctx = document) => ctx.querySelector(selector);
  const $$ = (selector, ctx = document) => ctx.querySelectorAll(selector);

  const loadCart = () => JSON.parse(localStorage.getItem(cartKey)) || [];
  const saveCart = (cart) => localStorage.setItem(cartKey, JSON.stringify(cart));

  const updateCartCount = (cart) => {
    const cartCount = $('.fa-shopping-bag + span');
    if (cartCount) {
      cartCount.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
    }
  };

  const setCurrentYear = () => {
    const yearEl = $('#current-year');
    if (yearEl) yearEl.textContent = new Date().getFullYear();
  };

  const initMobileMenu = () => {
    const menuToggle = $('.menu-toggle');
    const mobileMenu = $('.mobile-menu');

    if (!menuToggle || !mobileMenu) return;

    menuToggle.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
      const icon = $('i', menuToggle);
      icon.classList.toggle('fa-bars');
      icon.classList.toggle('fa-times');
    });
  };

  const initHeaderScrollEffect = () => {
    const header = $('header');
    if (!header) return;
    window.addEventListener('scroll', () => {
      header.classList.toggle('shadow-sm', window.scrollY > 10);
    });
  };

  const initFAQToggle = () => {
    $$('details').forEach(details => {
      details.addEventListener('toggle', () => {
        const icon = $('.transform', details.querySelector('summary'));
        if (icon) icon.style.transform = details.open ? 'rotate(180deg)' : 'rotate(0)';
      });
    });
  };

  const initNewsletterForm = () => {
    $$('form').forEach(form => {
      form.addEventListener('submit', (e) => {
        e.preventDefault();
        const email = $('input[type="email"]', form);
        if (email?.value) {
          alert('Thank you for subscribing to our newsletter!');
          email.value = '';
        }
      });
    });
  };

  const addToCart = (item) => {
    let cart = loadCart();
    const index = cart.findIndex(ci => ci.id === item.id && ci.color === item.color && ci.size === item.size);

    if (index > -1) cart[index].quantity += item.quantity;
    else cart.push(item);

    saveCart(cart);
    updateCartCount(cart);

    const modal = $('#added-to-cart-modal');
    if (modal) {
      modal.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }
  };

  const getSelectedOptions = () => {
    const color = [...$$('button[aria-label]')].find(btn => btn.classList.contains('ring-2'))?.getAttribute('aria-label') || 'Black';
    const size = [...$$('button:not([aria-label])')].find(btn => btn.classList.contains('border-black'))?.textContent || 'UK 8';
    return { color, size };
  };

  const initAddToCartButton = () => {
    const btn = $('#add-to-cart-btn');
    if (!btn) return;

    btn.addEventListener('click', () => {
      const productId = location.pathname.split('/').pop().replace('.html', '');
      const productName = $('h1')?.textContent || '';
      const productPrice = parseFloat($('p.text-2xl')?.textContent.replace('€', '') || 0);
      const productImage = $('#main-product-image')?.src || '';
      const quantity = parseInt($('#quantity')?.value) || 1;
      const { color, size } = getSelectedOptions();

      addToCart({ id: productId, name: productName, price: productPrice, image: productImage, color, size, quantity });
    });
  };

  const renderCartPage = (cart) => {
    const container = $('#cart-items');
    const summary = $('#cart-summary');
    if (!container || !summary) return;

    if (cart.length === 0) {
      container.innerHTML = `
        <div class="text-center py-12">
          <h2 class="text-2xl font-light mb-4">Your Cart is Empty</h2>
          <p class="mb-6">Looks like you haven't added any items to your cart yet.</p>
          <a href="men.html" class="bg-black text-white px-6 py-2 inline-block hover:bg-gray-800 transition">CONTINUE SHOPPING</a>
        </div>`;
      summary.style.display = 'none';
      return;
    }

    const itemsHTML = cart.map((item, i) => `
      <div class="flex flex-col md:flex-row border-b py-6">
        <div class="md:w-1/4 mb-4 md:mb-0">
          <img src="${item.image}" alt="${item.name}" class="object-cover w-full h-full">
        </div>
        <div class="md:w-3/4 md:pl-6 flex flex-col">
          <div class="flex justify-between mb-2">
            <h3 class="text-lg font-medium">${item.name}</h3>
            <button class="text-gray-500 remove-item" data-index="${i}"><i class="fas fa-times"></i></button>
          </div>
          <p class="text-gray-500 mb-2">Size: ${item.size} | Color: ${item.color}</p>
          <p class="mb-4">€${item.price.toFixed(2)}</p>
          <div class="flex items-center mt-auto">
            <div class="flex border border-gray-300">
              <button class="px-3 py-1 update-quantity" data-index="${i}" data-action="decrease">-</button>
              <span class="px-3 py-1">${item.quantity}</span>
              <button class="px-3 py-1 update-quantity" data-index="${i}" data-action="increase">+</button>
            </div>
            <p class="ml-auto font-medium">€${(item.price * item.quantity).toFixed(2)}</p>
          </div>
        </div>
      </div>`).join('');

    container.innerHTML = itemsHTML;

    const subtotal = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
    const shipping = subtotal >= 180 ? 0 : 15;
    const total = subtotal + shipping;

    $('#subtotal').textContent = `€${subtotal.toFixed(2)}`;
    $('#shipping').textContent = shipping === 0 ? 'FREE' : `€${shipping.toFixed(2)}`;
    $('#total').textContent = `€${total.toFixed(2)}`;

    $$('.remove-item').forEach(btn =>
      btn.addEventListener('click', () => removeCartItem(parseInt(btn.dataset.index)))
    );

    $$('.update-quantity').forEach(btn =>
      btn.addEventListener('click', () => updateQuantity(parseInt(btn.dataset.index), btn.dataset.action))
    );

    $('#checkout-btn')?.addEventListener('click', () => alert('Proceeding to checkout...'));
  };

  const removeCartItem = (index) => {
    let cart = loadCart();
    cart.splice(index, 1);
    saveCart(cart);
    updateCartCount(cart);
    renderCartPage(cart);
  };

  const updateQuantity = (index, action) => {
    let cart = loadCart();
    if (action === 'increase') cart[index].quantity += 1;
    else if (cart[index].quantity > 1) cart[index].quantity -= 1;
    saveCart(cart);
    updateCartCount(cart);
    renderCartPage(cart);
  };

  // Initialize All
  setCurrentYear();
  initMobileMenu();
  initHeaderScrollEffect();
  initFAQToggle();
  initNewsletterForm();
  initAddToCartButton();

  const cart = loadCart();
  updateCartCount(cart);
  if (location.pathname.includes('cart.html')) renderCartPage(cart);
});
