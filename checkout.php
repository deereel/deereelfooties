<?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>

<body class="bg-background">
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>


  <!-- Main Content -->
  <main>
    <div class="container my-5">
      <h2 class="mb-4">Your Cart</h2>
  
      <!-- Cart Item -->
      <div class="card mb-4">
        <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
          <div class="d-flex align-items-center">
            <img src="https://via.placeholder.com/100" class="rounded me-3" alt="Product Image" />
            <div>
              <h5 class="mb-1">Handcrafted Leather Shoes</h5>
              <p class="text-muted mb-0">Size: 42 | Color: Brown</p>
            </div>
          </div>
          <div class="d-flex align-items-center mt-3 mt-md-0">
            <button class="btn btn-outline-secondary btn-sm me-2 quantity-decrease"><i class="fas fa-minus"></i></button>
            <span class="mx-2 quantity">1</span>
            <button class="btn btn-outline-secondary btn-sm ms-2 quantity-increase"><i class="fas fa-plus"></i></button>
          </div>
          <div class="fw-bold ms-md-4 mt-3 mt-md-0 price">$<span class="item-total">129.99</span></div>
        </div>
      </div>
  
      <!-- Order Summary -->
      <div class="card p-4 mb-4">
        <h4>Order Summary</h4>
        <p class="mb-1">Subtotal: $<span id="subtotal">129.99</span></p>
        <p class="mb-1">Shipping: $<span id="shipping">10.00</span></p>
        <hr>
        <h5>Total: $<span id="total">139.99</span></h5>
      </div>
  
      <!-- Checkout Form -->
      <div class="card p-4">
        <h4 class="mb-3">Checkout</h4>
        <form>
          <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" required />
          </div>
          <div class="mb-3">
            <label for="address" class="form-label">Shipping Address</label>
            <textarea class="form-control" id="address" rows="2" required></textarea>
          </div>
          <div class="mb-3">
            <label for="card" class="form-label">Credit Card</label>
            <input type="text" class="form-control" id="card" placeholder="1234 5678 9012 3456" required />
          </div>
          <button type="submit" class="btn btn-primary w-100">Place Order</button>
        </form>
      </div>
    </div>


  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/account-modal.php'); ?>
  
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php'); ?>


  
</body>
</html>