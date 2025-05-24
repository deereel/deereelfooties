// cart.js
const cartKey = 'DRFCart';

export const loadCart = () => JSON.parse(localStorage.getItem(cartKey)) || [];
export const saveCart = (cart) => localStorage.setItem(cartKey, JSON.stringify(cart));

export const updateCartCount = (cart) => {
  const cartCount = document.querySelector('.fa-shopping-bag + span');
  if (cartCount) cartCount.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
};
