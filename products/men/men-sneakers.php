<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Men's Sneakers | DeeReel Footies</title>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>
</head>
<body data-page="men-sneakers">

  <!-- Main Content -->
  <main>   
    <div class="max-w-7xl mx-auto px-4 py-8">
      <!-- Breadcrumb -->
      <div class="mb-8">
        <h1 class="text-3xl font-light mb-2">MEN'S SNEAKERS COLLECTION</h1>
        <div class="flex items-center text-sm text-gray-500">
          <a href="/index.php">Home</a>
          <span class="mx-2">/</span>
          <a href="/men.php">Men</a>
          <span class="mx-2">/</span>
          <span>Sneakers</span>
        </div>
      </div>
   
      <!-- Mobile Inner Filter for Sneaker Types -->
    <div class="block md:hidden sticky top-16 z-40 bg-white border-b px-4 py-2 overflow-x-auto whitespace-nowrap space-x-3 flex shadow-sm clean-scroll">
      <button onclick="filterByType('running')" data-type="running" class="type-filter inline-block px-4 py-2 border rounded text-sm font-medium hover:bg-gray-100">
        Running
      </button>
      <button onclick="filterByType('casual')" data-type="casual" class="type-filter inline-block px-4 py-2 border rounded text-sm font-medium hover:bg-gray-100">
        Casual
      </button>
      <button onclick="filterByType('basketball')" data-type="basketball" class="type-filter inline-block px-4 py-2 border rounded text-sm font-medium hover:bg-gray-100">
        Basketball
      </button>
      <button onclick="filterByType('lifestyle')" data-type="lifestyle" class="type-filter inline-block px-4 py-2 border rounded text-sm font-medium hover:bg-gray-100">
        Lifestyle
      </button>
    </div>

      <div class="flex flex-col md:flex-row gap-8">
      <!-- Sidebar Filters -->
      <div class="md:w-1/4 space-y-6">
        <h3 class="font-medium mb-3">FILTER BY PRICE</h3>
        <div class="space-y-1">
          <div><input id="price1" type="checkbox" class="mr-2"> <label for="price1">₦30k - ₦50K</label></div>
          <div><input id="price2" type="checkbox" class="mr-2"> <label for="price2">₦50K - ₦70K</label></div>
          <div><input id="price3" type="checkbox" class="mr-2"> <label for="price3">₦70K - ₦90K</label></div>
          <div><input id="price4" type="checkbox" class="mr-2"> <label for="price4">₦90k+</label></div>
        </div>

        <!-- FILTER BY SIZE -->
        <div class="mb-6">
          <h3 class="font-medium mb-3">FILTER BY SIZE</h3>
          <div class="flex flex-wrap gap-2">
            <div class="size-filter w-10 h-10 flex items-center justify-center border border-gray-300 rounded-full 
                        text-sm font-medium cursor-pointer transition"
                data-size="39">39</div>
            <div class="size-filter w-10 h-10 flex items-center justify-center border border-gray-300 rounded-full 
                        text-sm font-medium cursor-pointer transition"
                data-size="40">40</div>
            <div class="size-filter w-10 h-10 flex items-center justify-center border border-gray-300 rounded-full 
                        text-sm font-medium cursor-pointer transition"
                data-size="41">41</div>
            <div class="size-filter w-10 h-10 flex items-center justify-center border border-gray-300 rounded-full 
                        text-sm font-medium cursor-pointer transition"
                data-size="42">42</div>
            <div class="size-filter w-10 h-10 flex items-center justify-center border border-gray-300 rounded-full 
                        text-sm font-medium cursor-pointer transition"
                data-size="43">43</div>
            <div class="size-filter w-10 h-10 flex items-center justify-center border border-gray-300 rounded-full 
                        text-sm font-medium cursor-pointer transition"
                data-size="44">44</div>
            <div class="size-filter w-10 h-10 flex items-center justify-center border border-gray-300 rounded-full 
                        text-sm font-medium cursor-pointer transition"
                data-size="45">45</div>
            <div class="size-filter w-10 h-10 flex items-center justify-center border border-gray-300 rounded-full 
                        text-sm font-medium cursor-pointer transition"
                data-size="46">46</div>
            <div class="size-filter w-10 h-10 flex items-center justify-center border border-gray-300 rounded-full 
                        text-sm font-medium cursor-pointer transition"
                data-size="47">47</div>
          </div>
        </div>

        <!-- FILTER BY COLOR -->
        <div class="mb-6">
          <h3 class="font-medium mb-3">FILTER BY COLOR</h3>
          <div class="flex flex-wrap gap-2">
            <div class="w-6 h-6 rounded-full cursor-pointer border border-gray-300 color-filter" style="background-color: black;" data-color="black"></div>
            <div class="w-6 h-6 rounded-full cursor-pointer border border-gray-300 color-filter" style="background-color: white;" data-color="white"></div>
            <div class="w-6 h-6 rounded-full cursor-pointer border border-gray-300 color-filter" style="background-color: #dc2626;" data-color="red"></div>
            <div class="w-6 h-6 rounded-full cursor-pointer border border-gray-300 color-filter" style="background-color: #2563eb;" data-color="blue"></div>
            <div class="w-6 h-6 rounded-full cursor-pointer border border-gray-300 color-filter" style="background-color: #16a34a;" data-color="green"></div>
            <div class="w-6 h-6 rounded-full cursor-pointer border border-gray-300 color-filter" style="background-color: #6b7280;" data-color="gray"></div>
          </div>
        </div>
      </div>

      <!-- Main Product Area -->
      <div class="md:w-3/4">
        <div class="flex justify-between items-center mb-6">
          <div>Showing results</div>
          <select class="border p-2" id="sortSelect">
            <option value="">Sort by latest</option>
            <option value="low">Sort by price: low to high</option>
            <option value="high">Sort by price: high to low</option>
          </select>
        </div>

      <!-- Filter Tags Display -->
      <div id="active-filters" class="mb-4 flex flex-wrap gap-2 text-sm"></div>
      
      <!-- Product Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="product-grid">
        <?php
              // Get products from database            
              $gender = 'men';
              $category = 'sneakers';
              $singularCategory = rtrim($category, 's'); // Remove trailing 's' to get singular form

              $stmt = $pdo->prepare("SELECT * FROM products WHERE gender IN (?, 'unisex') AND 
                                    (category = ? OR category = ? OR 
                                    type LIKE ? OR type LIKE ?) 
                                    ORDER BY created_at DESC");

              $stmt->execute([
                  $gender, 
                  $category, 
                  $singularCategory,
                  "%$category%",  // Will match both singular and plural in type field
                  "%$singularCategory%"  // Will match both singular and plural in type field
              ]);
              $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
              

            
              if (count($products) > 0) {
                foreach ($products as $product):
                  $colors = explode(',', $product['colors'] ?? '');
                  $sizes = explode(',', $product['sizes'] ?? '');
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
          <div class="group product-card"
               data-product-id="<?= $product['product_id'] ?? $product['id'] ?? $product['slug'] ?>"
               data-price="<?= $product['price'] ?>"
               data-size="<?= $product['sizes'] ?>"
               data-color="<?= $product['colors'] ?>"
               data-type="<?= $product['type'] ?>"
               data-name="<?= $product['name'] ?>"
               data-gender="<?= $product['gender'] ?>">
            <div class="relative">
              <a href="/product.php?slug=<?= $product['slug'] ?>">
                <div class="relative aspect-[3/4] overflow-hidden mb-4">
                  <img src="<?= $product['main_image'] ?>" alt="<?= $product['name'] ?>"
                       class="product-main-image object-cover w-full h-full group-hover:scale-105 transition duration-500"
                       data-main="<?= $product['main_image'] ?>" data-hover="<?= $secondImage ?>">
                </div>
                <h3 class="text-lg"><?= $product['name'] ?></h3>
                <p class="text-gray-500">₦<?= number_format($product['price']) ?></p>
              </a>
              <!-- Wishlist Icon -->
              <button class="wishlist-icon absolute top-2 right-2 bg-white rounded-full p-2 shadow-md hover:shadow-lg transition-all"
                      data-product-id="<?= $product['product_id'] ?? $product['id'] ?? $product['slug'] ?>"
                      data-product-name="<?= htmlspecialchars($product['name']) ?>"
                      data-price="<?= $product['price'] ?>"
                      data-image="<?= $product['main_image'] ?>">
                <i class="far fa-heart"></i>
              </button>
            </div>
          </div>
        <?php 
              endforeach;
            } else {
              echo '<div class="col-span-3 text-center py-8">No sneakers found in this category yet.</div>';
            }
            ?>
          </div>

        <!-- Pagination -->
        <div class="pagination flex justify-center mt-12">
          <div class="flex space-x-1">
            <!-- JS will insert page buttons here -->
          </div>
        </div>
      </div>
    </div>
  </main>

 <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'); ?>

  <!-- Scroll to Top Button -->
  <a href="#" class="btn btn-dark position-fixed bottom-0 end-0 m-4 shadow rounded-circle" style="z-index: 999; width: 45px; height: 45px; display: none;" id="scrollToTop">
    <i class="fas fa-chevron-up"></i>
  </a>


  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/account-modal.php'); ?>  
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/search-modal.php'); ?>  
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php'); ?>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const productCards = document.querySelectorAll('.product-card');
      productCards.forEach(card => {
        const img = card.querySelector('.product-main-image');
        if (!img) return;
        const mainSrc = img.dataset.main;
        const hoverSrc = img.dataset.hover;
        if (hoverSrc && hoverSrc !== mainSrc) {
          card.addEventListener('mouseenter', () => { img.src = hoverSrc; });
          card.addEventListener('mouseleave', () => { img.src = mainSrc; });
        }
      });
    });
  </script>
</body>
</html>