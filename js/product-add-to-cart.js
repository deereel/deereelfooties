document.addEventListener('DOMContentLoaded', function() {
  const addToCartBtn = document.getElementById('add-to-cart-btn');
  if (!addToCartBtn) return;

  addToCartBtn.addEventListener('click', async function() {
    const color = document.getElementById('selected-color')?.value;
    const size = document.getElementById('selected-size')?.value;
    const width = document.getElementById('selected-width')?.value;
    const quantityInput = document.getElementById('quantity');
    const quantity = quantityInput ? parseInt(quantityInput.value) : 1;

    if (!color || !size || !width) {
      alert('Please select color, size, and width');
      return;
    }

    if (!quantity || quantity < 1) {
      alert('Please enter a valid quantity');
      return;
    }

    const productName = document.querySelector('h3.fw-bold')?.textContent || '';
    const productPriceText = document.querySelector('p.text-2xl')?.textContent || '';
    const productPrice = parseFloat(productPriceText.replace(/[^\d\.]/g, '')) || 0;
    const productImage = document.getElementById('mainImage')?.src || '';
    const productId = window.location.pathname;

    const cartItem = {
      product_id: productId,
      product_name: productName,
      price: productPrice,
      image: productImage,
      color,
      size,
      width,
      quantity
    };

    if (!window.cartHandler) {
      window.cartHandler = new CartHandler();
    }

    await window.cartHandler.addToCart(cartItem);
    if (window.cartHandler.showAddedToCartModal) {
      window.cartHandler.showAddedToCartModal(cartItem);
    } else {
      alert('Added to cart!');
    }
  });
});
