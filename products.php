<?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>

<body class="bg-background" data-page="products">
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>

  <!-- Main Content -->
  <main class="max-w-7xl mx-auto px-4 py-8">
    <?php
    // Get search query if present
    $searchQuery = isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '';
    
    if (!empty($searchQuery)) {
      echo '<h1 class="text-3xl font-light mb-6">Search Results for "' . $searchQuery . '"</h1>';
    } else {
      echo '<h1 class="text-3xl font-light mb-6">All Products</h1>';
    }
    ?>
    
    <!-- Filter and Sort Controls -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
      <div class="flex flex-wrap gap-2 mb-4 md:mb-0">
        <button class="type-filter px-4 py-2 border rounded text-sm font-medium hover:bg-gray-100" data-type="all">
          All
        </button>
        <button class="type-filter px-4 py-2 border rounded text-sm font-medium hover:bg-gray-100" data-type="oxford">
          Oxford
        </button>
        <button class="type-filter px-4 py-2 border rounded text-sm font-medium hover:bg-gray-100" data-type="loafer">
          Loafers
        </button>
        <button class="type-filter px-4 py-2 border rounded text-sm font-medium hover:bg-gray-100" data-type="boot">
          Boots
        </button>
        <button class="type-filter px-4 py-2 border rounded text-sm font-medium hover:bg-gray-100" data-type="mule">
          Mules
        </button>
      </div>
      
      <div>
        <select id="sortSelect" class="border p-2 rounded">
          <option value="">Sort by latest</option>
          <option value="low">Sort by price: low to high</option>
          <option value="high">Sort by price: high to low</option>
        </select>
      </div>
    </div>
    
    <!-- Active Filters Display -->
    <div id="active-filters" class="mb-4 flex flex-wrap gap-2 text-sm">
      <?php if (!empty($searchQuery)): ?>
      <div class="inline-flex items-center bg-gray-100 px-3 py-1 rounded">
        <span>Search: <?php echo $searchQuery; ?></span>
        <a href="<?php echo strtok($_SERVER["REQUEST_URI"], '?'); ?>" class="ml-2 text-gray-500 hover:text-black">×</a>
      </div>
      <?php endif; ?>
    </div>
    
    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="product-grid">
      <!-- Products will be filtered by search query if present -->
      
      <!-- Sample Product -->
      <div class="group product-card"
           data-price="55000"
           data-size="40,41,42,43,44,45,46"
           data-color="tan"
           data-type="oxford"
           data-name="Oxford Cap Toe 600"
           data-description="Classic oxford cap toe design with premium leather">
        <a href="/products/men/shoes/oxford-cap-toe-600.php">
          <div class="relative aspect-[3/4] overflow-hidden mb-4">
            <img src="/images/Oxford Cap Toe 600.webp" alt="Oxford Cap Toe 600"
                 class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
          </div>
          <h3 class="text-lg">Oxford Cap Toe 600</h3>
          <p class="text-gray-500">₦55,000</p>
        </a>
      </div>

      <div class="group product-card"
           data-price="55000"
           data-size="39,40,41,42,43,44,45,46,47"
           data-color="tan,brown,black"
           data-type="oxford"
           data-gender="men"
           data-name="Cram Solid Oxford"
           data-description="Elegant solid oxford design for formal occasions">
        <a href="/products/men/shoes/cram-solid-oxford.php">
          <div class="relative aspect-[3/4] overflow-hidden mb-4">
            <img src="/images/cram solid oxford.webp" alt="Cram Solid oxford"
                 class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
          </div>
          <h3 class="text-lg">Cram Solid Oxford</h3>
          <p class="text-gray-500">₦55,000</p>
        </a>
      </div>

      <div class="group product-card"
           data-price="42000"
           data-size="40,41,42,43,44,45,46"
           data-color="brown"
           data-type="loafer"
           data-gender="men"
           data-name="Penny Loafer 600"
           data-description="Classic penny loafer with premium leather construction">
        <a href="/products/men/shoes/penny-loafer-600.php">
          <div class="relative aspect-[3/4] overflow-hidden mb-4">
            <img src="/images/penny loafer 600.webp" alt="Penny Loafer 600"
                 class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
          </div>
          <h3 class="text-lg">Penny Loafer 600</h3>
          <p class="text-gray-500">₦42,000</p>
        </a>
      </div>

      <div class="group product-card"
           data-price="35000"
           data-size="39,40,41,42,43,44,45,46,47"
           data-color="tan,green,black,white"
           data-type="mule"
           data-gender="men"
           data-name="Vintage Croc 600"
           data-description="Stylish mule with crocodile pattern leather">
        <a href="/products/men/mules/vintage-croc-600.php">
          <div class="relative aspect-[3/4] overflow-hidden mb-4">
            <img src="/images/Vintage Croc 600.webp" alt="Vintage Croc 600"
                 class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
          </div>
          <h3 class="text-lg">Vintage Croc 600</h3>
          <p class="text-gray-500">₦35,000</p>
        </a>
      </div>
      
      <!-- More products would be added here -->
    </div>
    
    <!-- No Results Message (hidden by default) -->
    <div id="no-results" class="text-center py-12 hidden">
      <h2 class="text-2xl font-medium text-primary mb-2">No products found</h2>
      <p class="text-muted mb-6">Try adjusting your search or filter criteria</p>
      <a href="/products.php" class="btn-primary px-6 py-2 rounded">View All Products</a>
    </div>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/account-modal.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/search-modal.php'); ?>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php'); ?>
  <script src="/js/search.js"></script>
  
  <script>
    // Handle search filtering
    document.addEventListener('DOMContentLoaded', function() {
      const searchQuery = '<?php echo $searchQuery; ?>'.toLowerCase();
      
      if (searchQuery) {
        const products = document.querySelectorAll('.product-card');
        let visibleCount = 0;
        
        products.forEach(product => {
          const productName = (product.dataset.name || '').toLowerCase();
          const productDesc = (product.dataset.description || '').toLowerCase();
          const productType = (product.dataset.type || '').toLowerCase();
          const productColor = (product.dataset.color || '').toLowerCase();
          
          // Check if product matches search query
          if (productName.includes(searchQuery) || 
              productDesc.includes(searchQuery) || 
              productType.includes(searchQuery) || 
              productColor.includes(searchQuery)) {
            product.style.display = '';
            visibleCount++;
          } else {
            product.style.display = 'none';
          }
        });
        
        // Show no results message if needed
        const noResults = document.getElementById('no-results');
        if (visibleCount === 0 && noResults) {
          noResults.classList.remove('hidden');
        }
      }
    });
  </script>
</body>
</html>