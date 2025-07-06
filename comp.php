addToCart(productId, productName, price, image) {
  // Make the entire function async to handle the server request properly
  async addToCart(productId, productName, price, image) {
    const quantity = 1; // Default quantity

    if (this.user) {
      console.log('Adding to user cart (server)...');
      // This was the issue: _addToUserCart is async, but we weren't waiting for it.
      this._addToUserCart(productId, quantity);
      // Now we wait for the server to confirm the item is added before proceeding.
      await this._addToUserCart(productId, quantity);
    } else {
      console.log('Adding to guest cart (local)...');
      this._addToGuestCart({ productId, productName, price, image, quantity });
    }

    // This was running before the server had finished, causing the UI to show the old count.
    this.updateCartUI();
    // Now this runs *after* the item has been successfully added,
    // ensuring the UI reflects the correct state immediately.
    await this.updateCartUI();
  }

  _addToGuestCart(item) {
  const cartHandler = new CartHandler();

  // Add event listeners to all "Add to Cart" buttons on the page
  document.querySelectorAll('.add-to-cart-btn').forEach(button => {
    button.addEventListener('click', (e) => {
    // Use an async function for the event listener to properly call the async addToCart
    button.addEventListener('click', async (e) => {
      e.preventDefault();
      const productId = button.dataset.productId;
      const productName = button.dataset.productName;
      const price = button.dataset.price;
      const image = button.dataset.image;
      cartHandler.addToCart(productId, productName, price, image);
      await cartHandler.addToCart(productId, productName, price, image);
      alert(`${productName} has been added to your cart!`); // Give the user immediate feedback
    });
  });
});