// /js/cart.js
import { getCart, setCart } from './storage.js';
import { showAddToCartModal } from './modal.js';

export function addToCart(item) {
  const cart = getCart();
  const index = cart.findIndex(p =>
    p.id === item.id &&
    p.color === item.color &&
    p.size === item.size &&
    p.width === item.width
  );

  if (index > -1) {
    cart[index].quantity += item.quantity;
  } else {
    cart.push(item);
  }

  setCart(cart);
  updateCartCount(cart);
  showAddToCartModal(item);
}

export function updateCartCount(cart) {
  const badge = document.querySelector('.fa-shopping-bag + span');
  if (badge) {
    badge.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
  }
}

export function renderCartPage() {
  const cart = getCart();
  const container = document.getElementById('cart-items');
  const summary = document.getElementById('cart-summary');
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

  summary.style.display = 'block';

  const subtotal = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
  document.getElementById('subtotal').textContent = `₦${subtotal.toLocaleString()}`;
  document.getElementById('shipping').textContent = 'Determined by location';
  document.getElementById('total').textContent = `₦${subtotal.toLocaleString()}`;

  attachCartListeners(cart);
}

function attachCartListeners(cart) {
  document.querySelectorAll('.remove-item').forEach(btn =>
    btn.addEventListener('click', () => {
      const index = parseInt(btn.dataset.index);
      cart.splice(index, 1);
      setCart(cart);
      renderCartPage();
    })
  );

  document.querySelectorAll('.update-quantity').forEach(btn =>
    btn.addEventListener('click', () => {
      const index = parseInt(btn.dataset.index);
      const action = btn.dataset.action;
      if (action === 'increase') cart[index].quantity += 1;
      else if (cart[index].quantity > 1) cart[index].quantity -= 1;
      setCart(cart);
      renderCartPage();
    })
  );
}
