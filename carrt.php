<?php include('components/header.php'); ?>

<body>
  <main>
    <div class="max-w-4xl mx-auto px-4 py-12">
      <h1 class="text-3xl font-light mb-8">Your Cart</h1>
      <div id="cart-items"></div>
      <div id="cart-summary" class="mt-8 border-t pt-6">
        <div class="flex justify-between mb-2">
          <span>Subtotal</span>
          <span id="subtotal">₦0</span>
        </div>
        <div class="flex justify-between mb-2">
          <span>Shipping</span>
          <span id="shipping">₦0</span>
        </div>
        <div class="flex justify-between font-medium text-lg">
          <span>Total</span>
          <span id="total">₦0</span>
        </div>
        <a href="/checkout.php" class="btn btn-dark w-full mt-6">Proceed to Checkout</a>
      </div>
    </div>
  </main>

  <script>
    function renderCart() {
      const cart = JSON.parse(localStorage.getItem('cart')) || [];
      const cartContainer = document.getElementById('cart-items');
      cartContainer.innerHTML = '';
      let subtotal = 0;

      if (cart.length === 0) {
        cartContainer.innerHTML = '<p>Your cart is empty.</p>';
        document.getElementById('cart-subtotal').textContent = '0';
        return;
      }

      cart.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;

        const itemElement = document.createElement('div');
        itemElement.className = 'cart-item row align-items-center';

        itemElement.innerHTML = `
          <div class="col-md-6">
            <h5 class="mb-1">${item.name}</h5>
            <p class="mb-1"><strong>Color:</strong> ${item.color}</p>
            <p class="mb-1"><strong>Size:</strong> ${item.size}</p>
            <p class="mb-1"><strong>Width:</strong> ${item.width}</p>
          </div>
          <div class="col-md-3 d-flex align-items-center">
            <button class="btn btn-outline-secondary quantity-btn" onclick="updateQuantity(${index}, -1)">-</button>
            <span class="mx-2">${item.quantity}</span>
            <button class="btn btn-outline-secondary quantity-btn" onclick="updateQuantity(${index}, 1)">+</button>
          </div>
          <div class="col-md-2">
            €${itemTotal.toFixed(2)}
          </div>
          <div class="col-md-1 text-end">
            <button class="btn btn-sm btn-danger" onclick="removeItem(${index})">&times;</button>
          </div>
        `;

        cartContainer.appendChild(itemElement);
      });

      document.getElementById('cart-subtotal').textContent = subtotal.toFixed(2);
    }

    function updateQuantity(index, delta) {
      let cart = JSON.parse(localStorage.getItem('cart')) || [];
      cart[index].quantity += delta;
      if (cart[index].quantity < 1) cart[index].quantity = 1;
      localStorage.setItem('cart', JSON.stringify(cart));
      renderCart();
    }

    function removeItem(index) {
      let cart = JSON.parse(localStorage.getItem('cart')) || [];
      cart.splice(index, 1);
      localStorage.setItem('cart', JSON.stringify(cart));
      renderCart();
    }

    // Initialize cart on page load
    renderCart();
  </script>
</body>
</html>
<!-- This code creates a simple shopping cart page using HTML, Bootstrap, and JavaScript. It allows users to view their cart items, update quantities, remove items, and see the subtotal. The cart data is stored in local storage. -->