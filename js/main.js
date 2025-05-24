// main.js
import { handleSelection, initMobileMenu, $ } from './ui.js';
import { loadCart, updateCartCount } from './cart.js';
import { saveCustomerInfo } from './customer.js';
import { initAddToCartButton } from './product.js';
import { renderCartPage } from './cart-page.js';

document.addEventListener('DOMContentLoaded', () => {
  const user = JSON.parse(localStorage.getItem('DRFUser'));
  if (user?.name) $('#userIcon')?.setAttribute('title', `Welcome, ${user.name}`);

  handleSelection('.color-option', 'selected-color');
  handleSelection('.size-option', 'selected-size');
  handleSelection('.width-option', 'selected-width');

  initAddToCartButton();
  updateCartCount(loadCart());

  if (location.pathname.includes('/cart.php')) renderCartPage(loadCart());
  initMobileMenu();

  $('#saveCustomerBtn')?.addEventListener('click', saveCustomerInfo);
});
