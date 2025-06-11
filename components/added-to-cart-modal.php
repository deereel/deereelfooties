<!-- Added to Cart Modal -->
<div id="added-to-cart-modal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden">
  <div class="bg-white rounded-lg max-w-md w-full mx-4 overflow-hidden">
    <div class="p-4 bg-green-50 flex justify-between items-center">
      <h3 class="text-lg font-medium text-green-800">
        <i class="fas fa-check-circle mr-2"></i> Added to Cart
      </h3>
      <button id="close-cart-modal" class="text-gray-500 hover:text-gray-700">
        <i class="fas fa-times"></i>
      </button>
    </div>
    
    <div class="p-4">
      <div class="flex items-center gap-4">
        <div class="w-20 h-20 flex-shrink-0">
          <img id="modal-product-image" src="" alt="Product" class="w-full h-full object-cover">
        </div>
        <div>
          <h4 id="modal-product-name" class="font-medium"></h4>
          <p id="modal-product-variant" class="text-sm text-gray-500"></p>
          <p id="modal-product-price" class="text-accent font-medium mt-1"></p>
        </div>
      </div>
      
      <div class="mt-4 flex gap-3">
        <a href="/cart.php" class="btn-primary flex-1 text-center py-2">
          View Cart
        </a>
        <button id="continue-shopping" class="btn-outline-secondary flex-1 py-2">
          Continue Shopping
        </button>
      </div>
    </div>
  </div>
</div>