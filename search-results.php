<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';

$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$products = [];
$totalResults = 0;

if (!empty($query)) {
  try {
    $searchTerm = "%$query%";
    $stmt = $pdo->prepare("SELECT * FROM products WHERE 
                          name LIKE ? OR 
                          category LIKE ? OR 
                          type LIKE ? OR 
                          colors LIKE ? OR 
                          description LIKE ? OR 
                          short_description LIKE ?
                          ORDER BY name ASC");
    $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $totalResults = count($products);
  } catch (PDOException $e) {
    error_log("Search error: " . $e->getMessage());
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Search Results<?= !empty($query) ? ' for "' . htmlspecialchars($query) . '"' : '' ?> | DeeReel Footies</title>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>
</head>
<body data-page="search-results">

  <main>
    <div class="max-w-7xl mx-auto px-4 py-8">
      <!-- Search Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-light mb-2">
          <?php if (!empty($query)): ?>
            Search Results for "<?= htmlspecialchars($query) ?>"
          <?php else: ?>
            Search Products
          <?php endif; ?>
        </h1>
        <div class="flex items-center text-sm text-gray-500">
          <a href="/index.php">Home</a>
          <span class="mx-2">/</span>
          <span>Search</span>
        </div>
        <?php if (!empty($query)): ?>
          <p class="text-gray-600 mt-2"><?= $totalResults ?> product<?= $totalResults !== 1 ? 's' : '' ?> found</p>
        <?php endif; ?>
      </div>

      <!-- Search Form -->
      <div class="mb-8">
        <form method="GET" class="max-w-md">
          <div class="flex">
            <input type="text" name="query" value="<?= htmlspecialchars($query) ?>" 
                   placeholder="Search for products..." 
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="px-6 py-2 bg-black text-white rounded-r-lg hover:bg-gray-800 transition">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </form>
      </div>

      <?php if (!empty($query)): ?>
        <?php if ($totalResults > 0): ?>
          <!-- Results Grid -->
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($products as $product): ?>
              <?php
              // Get second image from gallery
              $gallery = [];
              if (!empty($product['gallery'])) {
                $gallery = explode(',', $product['gallery']);
              } elseif (!empty($product['additional_images'])) {
                $gallery = explode(',', $product['additional_images']);
              }
              $secondImage = $product['main_image'];
              foreach($gallery as $img) {
                $img = trim($img);
                if ($img !== $product['main_image']) {
                  $secondImage = $img;
                  break;
                }
              }
              ?>
              <div class="group product-card" data-product-id="<?= $product['product_id'] ?? $product['id'] ?? $product['slug'] ?>">
                <a href="/product.php?slug=<?= $product['slug'] ?>" class="block">
                  <div class="relative overflow-hidden rounded-lg mb-4 shadow-sm group-hover:shadow-md transition">
                    <div class="aspect-[3/4] overflow-hidden">
                      <img src="<?= $product['main_image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>" 
                           class="product-main-image w-full h-full object-cover transform group-hover:scale-105 transition duration-500"
                           data-main="<?= $product['main_image'] ?>" data-hover="<?= $secondImage ?>">
                    </div>
                    <?php if (strtotime($product['created_at']) >= strtotime('-60 days')): ?>
                    <span class="absolute top-2 left-2 bg-primary text-white text-xs px-2 py-1">NEW</span>
                    <?php endif; ?>
                    <!-- Wishlist Icon -->
                    <button class="wishlist-icon absolute top-2 right-2 bg-white rounded-full p-2 shadow-md hover:shadow-lg transition-all"
                            data-product-id="<?= $product['product_id'] ?? $product['id'] ?? $product['slug'] ?>"
                            data-product-name="<?= htmlspecialchars($product['name']) ?>"
                            data-price="<?= $product['price'] ?>"
                            data-image="<?= $product['main_image'] ?>">
                      <i class="far fa-heart"></i>
                    </button>
                  </div>
                  <h3 class="font-medium text-lg"><?= htmlspecialchars($product['name']) ?></h3>
                  <p class="text-gray-500">₦<?= number_format($product['price']) ?></p>
                  <?php if (!empty($product['short_description'])): ?>
                    <p class="text-sm text-gray-400 mt-1"><?= htmlspecialchars(substr($product['short_description'], 0, 60)) ?>...</p>
                  <?php endif; ?>
                </a>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <!-- No Results -->
          <div class="text-center py-12">
            <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-medium mb-2">No products found</h3>
            <p class="text-gray-600 mb-6">Try searching with different keywords or browse our categories</p>
            <div class="flex flex-wrap gap-4 justify-center">
              <a href="/men.php" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-50 transition">Men's Shoes</a>
              <a href="/women.php" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-50 transition">Women's Shoes</a>
              <a href="/products.php" class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800 transition">All Products</a>
            </div>
          </div>
        <?php endif; ?>
      <?php else: ?>
        <!-- Popular Searches -->
        <div class="text-center py-12">
          <h3 class="text-xl font-medium mb-4">Popular Searches</h3>
          <div class="flex flex-wrap gap-3 justify-center">
            <a href="?query=oxford" class="px-4 py-2 bg-gray-100 rounded-full hover:bg-gray-200 transition">Oxford</a>
            <a href="?query=loafers" class="px-4 py-2 bg-gray-100 rounded-full hover:bg-gray-200 transition">Loafers</a>
            <a href="?query=boots" class="px-4 py-2 bg-gray-100 rounded-full hover:bg-gray-200 transition">Boots</a>
            <a href="?query=derby" class="px-4 py-2 bg-gray-100 rounded-full hover:bg-gray-200 transition">Derby</a>
            <a href="?query=sneakers" class="px-4 py-2 bg-gray-100 rounded-full hover:bg-gray-200 transition">Sneakers</a>
            <a href="?query=black" class="px-4 py-2 bg-gray-100 rounded-full hover:bg-gray-200 transition">Black</a>
            <a href="?query=brown" class="px-4 py-2 bg-gray-100 rounded-full hover:bg-gray-200 transition">Brown</a>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/account-modal.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/search-modal.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php'); ?>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Product image hover functionality
      const productCards = document.querySelectorAll('.product-card');
      productCards.forEach(card => {
        const img = card.querySelector('.product-main-image');
        if (!img) return;
        
        const mainSrc = img.dataset.main;
        const hoverSrc = img.dataset.hover;
        
        if (hoverSrc && hoverSrc !== mainSrc) {
          card.addEventListener('mouseenter', () => {
            img.src = hoverSrc;
          });
          
          card.addEventListener('mouseleave', () => {
            img.src = mainSrc;
          });
        }
      });
    });
  </script>
</body>
</html>