<?php include('components/header.php'); ?>

<body>
  <?php include('components/navbar.php'); ?>


  <!-- Main Content -->
  <main>
    <!-- Cart Section -->
     <!-- Hero Section -->
    <section class="relative w-full h-[600px]">
      <img src="/images/hero.jpg" alt="DeeReeL Footies Handcrafted Shoes" class="object-cover w-full h-full">
      <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
        <div class="text-center text-white max-w-3xl px-4">
          <h1 class="text-4xl md:text-5xl font-bold mb-4 text-center">Your Cart</h1>
          <p class="text-lg md:text-xl mb-8">
            You can review your order before proceeding to checkout
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/men.php" class="bg-white text-black px-8 py-3 font-medium hover:bg-gray-100 transition">
              SHOP MEN
            </a>
            <a href="/women.php" class="bg-white text-black px-8 py-3 font-medium hover:bg-gray-100 transition">
              SHOP WOMEN
            </a>
          </div>
        </div>
      </div>
    </section>
    <section class="pt-5 mt-5">
      <div class="container py-5">
        <h2 class="text-2xl font-bold mb-4 text-center">Your Cart</h2>

        <div class="row">
          <!-- Cart Items -->
          <div class="col-md-8" id="cart-items">
            <!-- Dynamic cart content will be rendered here -->
          </div>


          <!-- Cart Summary -->
          <div class="col-md-4" id="cart-summary">
            <div class="card p-4 shadow-sm">
              <h4 class="text-xl font-bold mb-3">Order Summary</h4>

              <ul class="list-group list-group-flush mb-3">
                <li class="list-group-item d-flex justify-content-between">
                  <span>Subtotal</span>
                  <strong id="subtotal">₦0.00</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                  <span>Shipping</span>
                  <strong id="shipping">₦0.00</strong>
                </li>
                <!-- Shipping Hint & Progress -->
                <li class="list-group-item">
                  <small class="text-muted d-block mb-2">
                    Shipping fee will be calculated based on your delivery address.
                  </small>
                  <small class="text-muted" id="shipping-hint">
                    Free shipping on orders above ₦120,000 within Lagos and ₦250,000 outside Lagos.
                  </small>
                  <!-- Free Shipping Progress Bar -->
                  <div class="progress mt-3 rounded-pill" style="height: 14px; background-color: #e9ecef;">
                    <div
                      class="progress-bar rounded-pill"
                      id="shipping-progress"
                      style="width: 0%; transition: width 0.6s ease;"
                    ></div>
                  </div>
                  <small id="shipping-progress-label" class="d-block mt-2 fw-medium text-sm text-muted">
                    <!-- Label gets updated dynamically -->
                  </small>

                </li>
                <li class="list-group-item d-flex justify-content-between">
                  <span>Total</span>
                  <strong id="total">₦0.00</strong>
                </li>
              </ul>

              <!-- Spacing -->
              <div class="mb-3"></div>

              <!-- Client Name -->
              <div class="mb-3">
                <label for="client-name" class="form-label">Your Name</label>
                <input type="text" id="client-name" class="form-control" placeholder="Enter your full name" required>
              </div>

              <!-- Shipping Address -->
              <div class="mb-3">
                <label for="shipping-address" class="form-label">Shipping Address</label>
                <textarea id="shipping-address" class="form-control" rows="3" placeholder="Enter full shipping address" required></textarea>
              </div>

              <!-- Bank Info -->
              <div class="mb-3">
                <h6 class="fw-bold">Bank Transfer Information</h6>
                <p class="mb-2">
                  <strong>Bank Name:</strong> OPAY Digital Bank<br>
                  <strong>Account Name:</strong> Oladayo Quadri<br>
                  <strong>Account Number:</strong> 8134235110
                </p>
                <p class="mb-0">
                  <strong>Bank Name:</strong> Stanbic IBTC Bank<br>
                  <strong>Account Name:</strong> Oladayo Quadri<br>
                  <strong>Account Number:</strong> 8134235110
                </p>
              </div>

              <!-- Proof of Payment Upload -->
              <div class="mb-4">
                <label for="payment-proof" class="form-label">Upload Proof of Payment</label>
                <input class="form-control" type="file" id="payment-proof" accept="image/*,.pdf">
              </div>
              <!-- Proof Preview -->
              <div id="proof-preview" class="mb-3 text-center d-none">
                <p class="text-muted">Preview:</p>
                <!-- Image Preview -->
                <img id="proof-image" src="" alt="Payment Proof" class="img-fluid rounded shadow-sm mb-2" style="max-height: 200px; display: none;">

                <!-- PDF Preview -->
                <embed id="proof-pdf" type="application/pdf" width="100%" height="200px" style="display: none; border: 1px solid #ccc;" />
              </div>



              <!-- Checkout Button -->
              <button class="btn btn-success w-100" id="checkout-btn">
                <i class="fas fa-credit-card me-2"></i> Proceed to Checkout
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>







  </main>

  <?php include('components/footer.php'); ?>
  <?php include('components/account-modal.php'); ?>
  

  <!-- Scroll to Top Button -->
  <a href="#" class="btn btn-dark position-fixed bottom-0 end-0 m-4 shadow rounded-circle" style="z-index: 999; width: 45px; height: 45px; display: none;" id="scrollToTop">
    <i class="fas fa-chevron-up"></i>
  </a>

  <?php include('components/scripts.php'); ?>


  <script>
    document.querySelectorAll('.dropdown-submenu > a').forEach(function (element) {
      element.addEventListener('click', function (e) {
        const submenu = this.nextElementSibling;
        if (submenu && submenu.classList.contains('dropdown-menu')) {
          e.preventDefault();
          submenu.classList.toggle('show');
          e.stopPropagation();
        }
      });
    });
  </script>
  
  
  
  <script>
    // Enable dropdowns on hover
    document.querySelectorAll('.dropdown').forEach(function (dropdown) {
      dropdown.addEventListener('mouseenter', function () {
        let toggle = this.querySelector('[data-bs-toggle="dropdown"]');
        if (toggle) {
          let dropdownInstance = bootstrap.Dropdown.getOrCreateInstance(toggle);
          dropdownInstance.show();
        }
      });
      dropdown.addEventListener('mouseleave', function () {
        let toggle = this.querySelector('[data-bs-toggle="dropdown"]');
        if (toggle) {
          let dropdownInstance = bootstrap.Dropdown.getOrCreateInstance(toggle);
          dropdownInstance.hide();
        }
      });
    });
  
    // Also add hover support for nested submenus (if you're using them)
    document.querySelectorAll('.dropdown-submenu').forEach(function (submenu) {
      submenu.addEventListener('mouseenter', function () {
        let submenuList = this.querySelector('.dropdown-menu');
        if (submenuList) submenuList.classList.add('show');
      });
      submenu.addEventListener('mouseleave', function () {
        let submenuList = this.querySelector('.dropdown-menu');
        if (submenuList) submenuList.classList.remove('show');
      });
    });
  </script>
  <script>
    const scrollBtn = document.getElementById("scrollToTop");
  
    window.addEventListener("scroll", () => {
      if (window.scrollY > 300) {
        scrollBtn.style.display = "flex";
      } else {
        scrollBtn.style.display = "none";
      }
    });
  
    scrollBtn.addEventListener("click", (e) => {
      e.preventDefault();
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  </script>

  <script>
    document.querySelectorAll('.dropdown-submenu > a').forEach(link => {
      link.addEventListener('click', function (e) {
        const submenu = this.nextElementSibling;

        // If the user clicked the main link directly (not just hovering)
        if (!submenu || !submenu.classList.contains('dropdown-menu')) {
          return; // not a submenu
        }

        // Prevent the submenu from hijacking the click
        const isSubmenuOpen = submenu.classList.contains('show');
        if (!isSubmenuOpen) {
          // Allow navigation
          window.location.href = this.getAttribute('href');
        }

        e.preventDefault();
      });
    });
  </script>

  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
  <script>
    // Set the current year in the footer
    document.getElementById('current-year').textContent = new Date().getFullYear();
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Bootstrap JS (with Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-..." crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
  <!-- Include this inside body on all pages -->
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/account-modal.php'); ?>

  <script type="module" src="js/main.js"></script>



</body>
</html>