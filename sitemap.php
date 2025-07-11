<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Sitemap | DeeReel Footies</title>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>
</head>

<body class="bg-background" data-page="sitemap">


  <!-- Main Content -->
  <main class="py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="card sitemap-card shadow-sm border-0">
            <div class="card-body">
              <h2 class="card-title text-center mb-4"><i class="bi bi-diagram-3-fill me-2"></i>Website Sections</h2>
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><a href="/index.php#home"><i class="bi bi-house-door-fill"></i> Home</a></li>
                <li class="list-group-item"><a href="/index.php#about"><i class="bi bi-person-fill"></i> About</a></li>
                <li class="list-group-item"><a href="/index.php#projects"><i class="bi bi-layers-fill"></i> Projects</a></li>
                <li class="list-group-item"><a href="/index.php#skills"><i class="bi bi-tools"></i> Skills</a></li>
                <li class="list-group-item"><a href="/index.php#contact"><i class="bi bi-envelope-fill"></i> Contact</a></li>
                <!-- Cart functionality has been removed -->
                <li class="list-group-item"><a href="checkout.php"><i class="bi bi-credit-card-fill"></i> Checkout</a></li>
                <li class="list-group-item"><a href="product.php"><i class="bi bi-box-seam"></i> Product Page</a></li>
                <li class="list-group-item"><a href="sitemap.php"><i class="bi bi-map"></i> Sitemap</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-3 mt-5 shadow-sm">
    <div class="container">
      <p class="mb-0">&copy; 2025 DeeReeL Footies. All rights reserved.</p>
    </div>
  </footer>

  <!-- Bootstrap Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
