<div class="group product-card"
     data-price="450"
     data-size="41,42"
     data-color="black"
     data-type="oxford"
     data-product-id="<?= $product['product_id'] ?? $product['id'] ?? '' ?>">
  <div class="relative">
    <a href="/men/product/1.php">
      <div class="relative aspect-[3/4] overflow-hidden mb-4">
        <img src="/images/oxford-cap-toe-80201.webp" alt="Oxford Cap Toe 600"
             class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
      </div>
      <h3 class="text-lg">Oxford Cap Toe 600</h3>
      <p class="text-gray-500">€450</p>
    </a>
    <!-- Wishlist Icon -->
    <button class="wishlist-icon absolute top-2 right-2 bg-white rounded-full p-2 shadow-md hover:shadow-lg transition-all"
            data-product-id="<?= $product['product_id'] ?? $product['id'] ?? '' ?>"
            data-product-name="<?= htmlspecialchars($product['name'] ?? $product['product_name'] ?? 'Product') ?>"
            data-price="<?= $product['price'] ?? 0 ?>">
      <i class="far fa-heart"></i>
    </button>
  </div>
</div>
