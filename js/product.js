// product.js
import { loadCart, saveCart, updateCartCount } from './cart.js';
import { $ } from './ui.js';

export const getSelectedOptions = () => ({
  color: $('#selected-color')?.value,
  size: $('#selected-size')?.value,
  width: $('#selected-width')?.value
});

export const addToCart = (item) => {
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

export const initAddToCartButton = () => {
  const btn = $('#add-to-cart-btn');
  if (!btn) return;

  btn.addEventListener('click', () => {
    const productId = location.pathname.split('/').pop().replace('.php', '');
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

    addToCart({ id: productId, name: productName, price: productPrice, image: productImage, color, size, width, quantity });
  });
};
