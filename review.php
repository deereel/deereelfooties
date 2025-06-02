<?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
<<<<<<< HEAD
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/auth/db.php'); ?>
<body class="bg-background" data-page="review">
=======

<body data-page="review">
>>>>>>> parent of f36b17c (checkout page)
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>
  
  <div class="container py-5">
    <h2 class="mb-4">Review Your Order</h2>

    <div id="review-content" class="row g-5">
      <div class="col-md-8" id="review-cart">
        <h4 class="mb-3">Items in Your Cart</h4>
      </div>

      <div class="col-md-4">
        <div class="card p-4 shadow-sm">
          <h5 class="fw-bold mb-3">Customer Information</h5>
          <p><strong>Name:</strong> <span id="review-name"></span></p>
          <p><strong>Shipping Address:</strong><br><span id="review-address"></span></p>
          <div class="mb-3">
            <strong>Payment Proof:</strong><br>
            <img id="review-proof" src="" alt="Proof of Payment" class="img-fluid mt-2" style="max-height: 300px; display: none;" />
          </div>
          <button class="btn btn-primary w-100" onclick="sendEmail()">Submit Order</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    let cart = [];
    let info = {};

    function loadReviewData() {
      cart = JSON.parse(localStorage.getItem('DRFCart')) || [];
      info = JSON.parse(localStorage.getItem('DRFCheckoutInfo')) || {};

      document.getElementById('review-name').textContent = info.name || 'N/A';
      document.getElementById('review-address').textContent = info.address || 'N/A';

      if (info.proof) {
        const proofImg = document.getElementById('review-proof');
        proofImg.src = info.proof;
        proofImg.style.display = 'block';
      }

      const cartContainer = document.getElementById('review-cart');
      if (cart.length === 0) {
        cartContainer.innerHTML += `<p class="text-muted">Your cart is empty.</p>`;
        return;
      }

      cart.forEach(item => {
        const card = document.createElement('div');
        card.className = 'card mb-3';
        card.innerHTML = `
          <div class="row g-0">
            <div class="col-md-4">
              <img src="${item.image}" class="img-fluid rounded-start" alt="${item.name}">
            </div>
            <div class="col-md-8">
              <div class="card-body">
                <h5 class="card-title">${item.name}</h5>
                <p class="card-text">Size: ${item.size}, Width: ${item.width}, Color: ${item.color}</p>
                <p class="card-text"><strong>€${item.price.toFixed(2)}</strong> x ${item.quantity}</p>
                <p class="card-text">Total: €${(item.price * item.quantity).toFixed(2)}</p>
              </div>
            </div>
          </div>`;
        cartContainer.appendChild(card);
      });
    }

    function sendEmail() {
      if (!info.name || !info.address || cart.length === 0) {
        alert("Missing name, address, or cart is empty.");
        return;
      }

      const cartText = cart.map(item =>
        `• ${item.name} - ${item.color}, Size ${item.size}, Width ${item.width}, Qty: ${item.quantity}, Total: €${(item.price * item.quantity).toFixed(2)}`
      ).join('\n');

      const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);

      const templateParams = {
        name: info.name,
        address: info.address,
        cart_items: cartText,
        total: `€${total.toFixed(2)}`
      };

      emailjs.send("service_wur0urq", "template_4zy7k7n", templateParams)
        .then(function(response) {
          alert("Order sent successfully!");
        }, function(error) {
          alert("Failed to send order. Please try again.");
        });
    }

    loadReviewData();
  </script>
</body>
</html>
