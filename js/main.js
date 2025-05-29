import { AuthManager } from './modules/auth.js';
import { UIManager } from './modules/ui.js';
import CartManager from './modules/cart.js';
import { ProductManager } from './modules/product.js';

// Create global app object
window.app = {};

document.addEventListener('DOMContentLoaded', () => {
  // Add color scheme CSS if not already added
  if (!document.getElementById('color-scheme-css')) {
    const colorSchemeLink = document.createElement('link');
    colorSchemeLink.id = 'color-scheme-css';
    colorSchemeLink.rel = 'stylesheet';
    colorSchemeLink.href = '/css/colors.css';
    document.head.appendChild(colorSchemeLink);
  }
  
  if (!document.getElementById('tailwind-custom-css')) {
    const tailwindCustomLink = document.createElement('link');
    tailwindCustomLink.id = 'tailwind-custom-css';
    tailwindCustomLink.rel = 'stylesheet';
    tailwindCustomLink.href = '/css/tailwind-custom.css';
    document.head.appendChild(tailwindCustomLink);
  }
  
  // Initialize managers
  const ui = new UIManager();
  const auth = new AuthManager();
  const cart = new CartManager();
  const product = new ProductManager(cart);
  
  // Store managers in global app object for access from other scripts
  window.app.ui = ui;
  window.app.auth = auth;
  window.app.cart = cart;
  window.app.product = product;
  
  // Initialize UI and Auth first
  ui.init();
  auth.init();
  
  // Determine current page type
  const currentPath = window.location.pathname;
  
  // Initialize page-specific functionality
  if (currentPath.includes('/cart') || currentPath.includes('/checkout')) {
    cart.initCartPage();
  } else if (currentPath.includes('/product') || document.getElementById('product-details')) {
    product.initProductPage();
  } else if (currentPath.includes('/category') || document.querySelector('.product-grid')) {
    product.initCategoryPage();
  }
  
  // Initialize cart count display (needed on all pages)
  cart.updateCartCount();
  
  // Initialize mobile menu (needed on all pages)
  cart.initMobileMenu();
  
  // Update current year in footer
  const yearElement = document.getElementById('current-year');
  if (yearElement) {
    yearElement.textContent = new Date().getFullYear();
  }
});