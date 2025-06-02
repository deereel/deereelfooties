// /js/product.js
import { addToCart } from './cart.js';
import { showAddToCartModal } from './modal.js';

export function bindProductOptions() {
  // Color Selection
  document.querySelectorAll('.color-option').forEach(btn => {
    btn.addEventListener('click', () => {
      document.getElementById('selected-color').value = btn.getAttribute('data-color');
      document.querySelectorAll('.color-option').forEach(b => b.classList.remove('ring-4', 'ring-black'));
      btn.classList.add('ring-4', 'ring-black');
    });
  });

  // Size Selection
  document.querySelectorAll('.size-option').forEach(btn => {
    btn.addEventListener('click', () => {
      document.getElementById('selected-size').value = btn.getAttribute('data-size');
      document.querySelectorAll('.size-option').forEach(b => b.classList.remove('bg-dark', 'text-white'));
      btn.classList.add('bg-dark', 'text-white');
    });
  });

  // Width Selection
  document.querySelectorAll('.width-option').forEach(btn => {
    btn.addEventListener('click', () => {
      document.getElementById('selected-width').value = btn.getAttribute('data-width');
      document.querySelectorAll('.width-option').forEach(b => b.classList.remove('bg-dark', 'text-white'));
      btn.classList.add('bg-dark', 'text-white');
    });
  });

  // Add to Cart Button
  const addToCartBtn = document.getElementById('add-to-cart-btn');
  if (addToCartBtn) {
    addToCartBtn.addEventListener('click', function (e) {
      e.preventDefault();

      const color = document.getElementById('selected-color')?.value;
      const size = document.getElementById('selected-size')?.value;
      const width = document.getElementById('selected-width')?.value;
      const quantity = parseInt(document.getElementById('quantity')?.value) || 1;

      if (!color || !size || !width) {
        alert('Please select color, size, and width.');
        return;
      }

      const product = {
        id: window.location.pathname.split('/').pop().replace('.php', ''),
        name: document.querySelector('h1')?.textContent || 'Product',
        price: parseInt((document.querySelector('.text-2xl')?.textContent || '0').replace(/[₦,]/g, '')) || 0,
        image: document.getElementById('mainImage')?.src || '',
        color,
        size,
        width,
        quantity
      };

      addToCart(product);
      showAddToCartModal(product);
    });
  }
}
