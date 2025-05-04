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

  const getSelectedOptions = () => {
    const color = $('#selected-color')?.value || 'Black';
    const size = $('#selected-size')?.value || 'UK 8';
    const width = $('#selected-width')?.value || 'Standard';
    return { color, size, width };
  };

  const addToCart = (item) => {
    let cart = loadCart();
    const index = cart.findIndex(ci =>
      ci.id === item.id &&
      ci.color === item.color &&
      ci.size === item.size &&
      ci.width === item.width
    );

    if (index > -1) {
      cart[index].quantity += item.quantity;
    } else {
      cart.push(item);
    }

    saveCart(cart);
    updateCartCount(cart);

    const modal = $('#added-to-cart-modal');
    if (modal) {
      modal.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }
  };

  const initAddToCartButton = () => {
    const btn = $('#add-to-cart-btn');
    if (!btn) return;

    btn.addEventListener('click', () => {
      const productId = location.pathname.split('/').pop().replace('.html', '');
      const productName = $('h3.fw-bold')?.textContent || '';
      const productPrice = parseFloat($('p.text-2xl')?.textContent.replace('€', '') || 0);
      const productImage = $('#mainImage')?.src || '';
      const quantity = parseInt($('#quantity')?.value) || 1;
      const { color, size, width } = getSelectedOptions();

      addToCart({ id: productId, name: productName, price: productPrice, image: productImage, color, size, width, quantity });
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
          <a href="index.html" class="bg-black text-white px-6 py-2 inline-block hover:bg-gray-800 transition">CONTINUE SHOPPING</a>
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
          <p class="text-gray-500 mb-2">Size: ${item.size} | Width: ${item.width} | Color: ${item.color}</p>
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

    const checkoutBtn = $('#checkout-btn');
      if (checkoutBtn) {
        checkoutBtn.addEventListener('click', (e) => {
          const name = $('#client-name')?.value.trim();
          const address = $('#shipping-address')?.value.trim();
          const fileInput = $('#payment-proof');
          const file = fileInput?.files[0];
        
          if (!name) {
            alert('Please enter your name before proceeding.');
            return;
          }
        
          if (!address) {
            alert('Please enter your shipping address.');
            return;
          }
        
          if (!file) {
            alert('Please upload proof of payment.');
            return;
          }
        
          const reader = new FileReader();
          reader.onload = function () {
            const base64Data = reader.result;
        
            const checkoutInfo = {
              name: name,
              address: address,
              proof: base64Data
            };
        
            localStorage.setItem('DRFCheckoutInfo', JSON.stringify(checkoutInfo));
        
            alert(`Thank you, ${name}! Your shipping and payment information have been saved.`);
          };
        
          reader.readAsDataURL(file);
        });
        
      }

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

  const handleSelection = (groupClass, hiddenInputId) => {
    const options = document.querySelectorAll(groupClass);
    options.forEach(option => {
      option.addEventListener('click', function () {
        // Remove visual selection
        options.forEach(o => o.classList.remove('selected', 'ring-4', 'ring-black', 'bg-dark', 'text-white'));
  
        // Add visual highlight
        this.classList.add('selected', 'ring-4', 'ring-black', 'bg-dark', 'text-white');
  
        // Store selected value
        const dataAttr = groupClass.includes('color') ? 'color'
                        : groupClass.includes('size') ? 'size'
                        : 'width';
  
        document.getElementById(hiddenInputId).value = this.dataset[dataAttr];
      });
    });
  };
  

  const initPage = () => {
    handleSelection('.color-option', 'selected-color');
    handleSelection('.size-option', 'selected-size');
    handleSelection('.width-option', 'selected-width');
    initAddToCartButton();

    const cart = loadCart();
    updateCartCount(cart);
    if (location.pathname.includes('cart.html')) renderCartPage(cart);
  };

  initPage();
});
