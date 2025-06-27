<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Women's Slippers | DeeReel Footies</title>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>
</head>

<body data-page="women-slippers">

  <!-- Main Content -->
  <main>   
    <div class="max-w-7xl mx-auto px-4 py-8">
      <!-- Breadcrumb -->
      <div class="mb-8">
        <h1 class="text-3xl font-light mb-2">WOMEN'S SLIPPERS COLLECTION</h1>
        <div class="flex items-center text-sm text-gray-500">
          <a href="/index.php">Home</a>
          <span class="mx-2">/</span>
          <a href="/women.php">Women</a>
          <span class="mx-2">/</span>
          <span>Slippers</span>
        </div>
      </div>
   
      <!-- Mobile-only Inner Filter for Slipper Types -->
    <div class="block md:hidden sticky top-16 z-40 bg-white border-b px-4 py-2 overflow-x-auto whitespace-nowrap space-x-3 flex shadow-sm clean-scroll">
      <button onclick="filterByType('lounge')" data-type="lounge" class="type-filter inline-block px-4 py-2 border rounded text-sm font-medium hover:bg-gray-100">
        Lounge Slippers
      </button>
      <button onclick="filterByType('house')" data-type="house" class="type-filter inline-block px-4 py-2 border rounded text-sm font-medium hover:bg-gray-100">
        House Slippers
      </button>
      <button onclick="filterByType('slide')" data-type="slide" class="type-filter inline-block px-4 py-2 border rounded text-sm font-medium hover:bg-gray-100">
        Slides
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
                data-size="35">35</div>
            <div class="size-filter w-10 h-10 flex items-center justify-center border border-gray-300 rounded-full 
                        text-sm font-medium cursor-pointer transition"
                data-size="36">36</div>
            <div class="size-filter w-10 h-10 flex items-center justify-center border border-gray-300 rounded-full 
                        text-sm font-medium cursor-pointer transition"
                data-size="37">37</div>
            <div class="size-filter w-10 h-10 flex items-center justify-center border border-gray-300 rounded-full 
                        text-sm font-medium cursor-pointer transition"
                data-size="38">38</div>
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
          </div>
        </div>

        <!-- FILTER BY COLOR -->
        <div class="mb-6">
          <h3 class="font-medium mb-3">FILTER BY COLOR</h3>
          <div class="flex flex-wrap gap-2">
            <div class="w-6 h-6 rounded-full cursor-pointer border border-gray-300 color-filter" style="background-color: black;" data-color="black"></div>
            <div class="w-6 h-6 rounded-full cursor-pointer border border-gray-300 color-filter" style="background-color: #92400e;" data-color="brown"></div>
            <div class="w-6 h-6 rounded-full cursor-pointer border border-gray-300 color-filter" style="background-color: #d97706;" data-color="tan"></div>
            <div class="w-6 h-6 rounded-full cursor-pointer border border-gray-300 color-filter" style="background-color: #991b1b;" data-color="burgundy"></div>
            <div class="w-6 h-6 rounded-full cursor-pointer border border-gray-300 color-filter" style="background-color: #1e3a8a;" data-color="navy"></div>
            <div class="w-6 h-6 rounded-full cursor-pointer border border-gray-300 color-filter" style="background-color: white;" data-color="white"></div>
            <div class="w-6 h-6 rounded-full cursor-pointer border border-gray-300 color-filter" style="background-color: green;" data-color="green"></div>
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
              $gender = 'women';
              $category = 'slippers';
              $singularCategory = rtrim($category, 's'); // Remove trailing 's' to get singular form

              $stmt = $pdo->prepare("SELECT * FROM products WHERE gender = ? AND 
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
            ?>
          <div class="group product-card"
               data-price="<?= $product['price'] ?>"
               data-size="<?= $product['sizes'] ?>"
               data-color="<?= $product['colors'] ?>"
               data-type="<?= $product['type'] ?>"
               data-gender="<?= $product['gender'] ?>">
            <a href="/product.php?slug=<?= $product['slug'] ?>">
              <div class="relative aspect-[3/4] overflow-hidden mb-4">
                <img src="<?= $product['main_image'] ?>" alt="<?= $product['name'] ?>"
                     class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
              </div>
              <h3 class="text-lg"><?= $product['name'] ?></h3>
              <p class="text-gray-500">₦<?= number_format($product['price']) ?></p>
            </a>
          </div>
        <?php 
              endforeach;
            } else {
              echo '<div class="col-span-3 text-center py-8">No products found in this category yet.</div>';
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
</body>
</html>