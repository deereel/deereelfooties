<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>All Products | DeeReel Footies</title>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>
  </head>

  <body data-page="products">
    

    <!-- Main Content -->
    <main>
      <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-8">
          <h1 class="text-3xl font-light mb-2">PRODUCTS IN OUR WAREHOUSE</h1>
          <div class="flex items-center text-sm text-gray-500">
            <a href="/index.php">Home</a>
            <span class="mx-2">/</span>
            <span>Outlet</span>
          </div>
        </div>

        <div class="mb-12">
          <p>
            Discover exceptional quality at special prices. Our outlet features a selection of DeeReeL Footies shoes with minor
            cosmetic imperfections or from previous seasons, all offered at discounted prices. Each pair maintains our
            high standards of craftsmanship and durability.
          </p>
        </div>
      
        <div class="flex flex-col md:flex-row gap-8">
          <!-- Sidebar Filters -->
      <div class="md:w-1/4 space-y-6">
        <button id="clear-filters-btn" class="mb-4 px-4 py-2 border border-gray-400 rounded hover:bg-gray-100 transition">
          Clear Filters
        </button>

        <div id="active-filters-display" class="mb-4 text-sm text-gray-700">
          <!-- Active filters will be displayed here -->
        </div>

        <!-- FILTER BY TYPE -->
        <div>
          <h3 class="font-medium mb-3">FILTER BY TYPE</h3>
          <div class="flex space-x-2">
            <button class="type-filter px-3 py-1 border border-gray-300 rounded cursor-pointer active" data-type="all">All</button>
            <button class="type-filter px-3 py-1 border border-gray-300 rounded cursor-pointer" data-type="men">Men</button>
            <button class="type-filter px-3 py-1 border border-gray-300 rounded cursor-pointer" data-type="women">Women</button>
          </div>
        </div>

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

            <!-- FILTER BY COLOR (includes white + green) -->
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
              <h2 class="text-2xl font-light">All Products</h2>
              <select class="border p-2" id="sortSelect">
                <option value="">Sort by latest</option>
                <option value="low">Sort by price: low to high</option>
                <option value="high">Sort by price: high to low</option>
              </select>
            </div>
    
            <!-- Filter Tags Display -->
            <div id="active-filters" class="mb-4 flex flex-wrap gap-2 text-sm"></div>
    
            <!-- Product Grid -->
            <div class="swiper product-swiper">
              <div class="swiper-wrapper">
                <?php
                try {
                  $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
                  $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                  $chunks = array_chunk($products, 20);
                  foreach ($chunks as $chunk):
                ?>
                <div class="swiper-slide grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                  <?php foreach ($chunk as $product): ?>
                <div class="group product-card relative"
                    data-price="<?= $product['price'] ?>"
                    data-size="<?= $product['sizes'] ?>"
                    data-color="<?= $product['colors'] ?>"
                    data-type="<?= $product['type'] ?>"
                    data-gender="<?= $product['gender'] ?>"
                    data-created="<?= strtotime($product['created_at']) ?>"
                    data-name="<?= $product['name'] ?>"
                    data-description="<?= $product['short_description'] ?>">
                    <!-- Add wishlist button -->
                    <div class="absolute top-2 right-2 z-10">
                      <button class="add-to-wishlist-icon bg-white rounded-full p-2 shadow-sm hover:shadow-md transition" 
                              data-product-id="<?= $product['product_id'] ?? $product['slug'] ?>"
                              data-product-name="<?= $product['name'] ?>"
                              data-product-price="<?= $product['price'] ?>"
                              data-product-image="<?= $product['main_image'] ?>">
                        <i class="far fa-heart text-gray-600 hover:text-red-500"></i>
                      </button>
                    </div>
                    <a href="product.php?slug=<?= $product['slug'] ?>">
                      <div class="relative aspect-[3/4] overflow-hidden mb-4">
                        <img src="<?= $product['main_image'] ?>" alt="<?= $product['name'] ?>"
                            class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
                      </div>
                      <h3 class="text-lg"><?= $product['name'] ?></h3>
                      <p class="text-gray-500">₦<?= number_format($product['price']) ?></p>
                    </a>
                  </div>
                  <?php endforeach; ?>
                </div>
                <?php endforeach; ?>
              </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination mt-6 flex justify-center"></div>
            <!-- Add Navigation -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
          </div>
            <?php } catch (Exception $e) { echo "<div class='text-red-500'>Error loading products: " . htmlspecialchars($e->getMessage()) . "</div>"; } ?>
    
            <!-- Pagination -->
            <div class="pagination flex justify-center mt-12 hidden">
              <div class="flex space-x-1">
                <!-- JS will insert page buttons here -->
              </div>
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
    
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Initialize Swiper
      let swiper = new Swiper('.product-swiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
          type: 'fraction',
        },
      });

      // Type filtering
      const typeFilters = document.querySelectorAll('.type-filter');

      // Additional filters
      const priceFilters = document.querySelectorAll('input[type="checkbox"][id^="price"]');
      const sizeFilters = document.querySelectorAll('.size-filter');
      const colorFilters = document.querySelectorAll('.color-filter');

      function filterProducts() {
        const slides = document.querySelectorAll('.swiper-slide');
        // Get selected filters
        const selectedType = Array.from(typeFilters).find(f => f.classList.contains('active'))?.dataset.type || 'all';
        const selectedPrices = Array.from(priceFilters).filter(cb => cb.checked).map(cb => cb.nextElementSibling.textContent.trim());
        const selectedSizes = Array.from(sizeFilters).filter(el => el.classList.contains('active')).map(el => el.dataset.size.toLowerCase());
        const selectedColors = Array.from(colorFilters).filter(el => el.classList.contains('active')).map(el => el.dataset.color.toLowerCase());

        slides.forEach(slide => {
          let visibleCount = 0;
          const cards = slide.querySelectorAll('.product-card');
          cards.forEach(card => {
            let show = true;

            // Filter by gender (case-insensitive)
            if (selectedType !== 'all') {
              const cardGender = (card.dataset.gender || '').toLowerCase();
              const selectedTypeLower = selectedType.toLowerCase();

              // Show card only if gender matches selected type
              if (cardGender !== selectedTypeLower) {
                show = false;
              }
            }

            // Filter by price
            if (show && selectedPrices.length > 0) {
              const price = parseFloat(card.dataset.price);
              let priceMatch = false;
              selectedPrices.forEach(priceRange => {
                if (priceRange === '₦30k - ₦50K' && price >= 30000 && price <= 50000) priceMatch = true;
                else if (priceRange === '₦50K - ₦70K' && price > 50000 && price <= 70000) priceMatch = true;
                else if (priceRange === '₦70K - ₦90K' && price > 70000 && price <= 90000) priceMatch = true;
                else if (priceRange === '₦90k+' && price > 90000) priceMatch = true;
              });
              if (!priceMatch) show = false;
            }

            // Filter by size (case-insensitive)
            if (show && selectedSizes.length > 0) {
              const sizes = card.dataset.size.split(',').map(s => s.trim().toLowerCase());
              // Check if any selected size is included in product sizes
              if (!selectedSizes.some(s => sizes.includes(s))) {
                show = false;
              }
            }

            // Filter by color (case-insensitive)
            if (show && selectedColors.length > 0) {
              const colors = card.dataset.color.split(',').map(c => c.trim().toLowerCase());
              if (!selectedColors.some(c => colors.includes(c))) {
                show = false;
              }
            }

            if (show) {
              card.style.display = '';
              visibleCount++;
            } else {
              card.style.display = 'none';
            }
          });
          // Hide slide if no visible cards
          slide.style.display = visibleCount > 0 ? '' : 'none';
        });
        updateActiveFiltersDisplay();
        updateURLWithFilters();
      }

      // Event listeners for filters
      typeFilters.forEach(filter => {
        filter.addEventListener('click', function() {
          typeFilters.forEach(f => f.classList.remove('active'));
          this.classList.add('active');
          filterProducts();
        });
      });

      priceFilters.forEach(cb => {
        cb.addEventListener('change', filterProducts);
      });

      sizeFilters.forEach(el => {
        el.addEventListener('click', function() {
          this.classList.toggle('active');
          filterProducts();
        });
      });

      colorFilters.forEach(el => {
        el.addEventListener('click', function() {
          this.classList.toggle('active');
          filterProducts();
        });
      });

      function updateURLWithFilters() {
        const params = new URLSearchParams();

        // Add gender/type filter
        const activeType = Array.from(typeFilters).find(f => f.classList.contains('active'));
        if (activeType && activeType.dataset.type !== 'all') {
          params.set('gender', activeType.dataset.type);
        }

        // Add price filters
        const activePrices = Array.from(priceFilters).filter(cb => cb.checked).map(cb => cb.nextElementSibling.textContent.trim());
        if (activePrices.length > 0) {
          params.set('price', activePrices.join(','));
        }

        // Add size filters
        const activeSizes = Array.from(sizeFilters).filter(el => el.classList.contains('active')).map(el => el.dataset.size);
        if (activeSizes.length > 0) {
          params.set('size', activeSizes.join(','));
        }

        // Add color filters
        const activeColors = Array.from(colorFilters).filter(el => el.classList.contains('active')).map(el => el.dataset.color.toLowerCase());
        if (activeColors.length > 0) {
          params.set('color', activeColors.join(','));
        }

        const newUrl = window.location.pathname + '?' + params.toString();
        window.history.replaceState({}, '', newUrl);
      }

      function updateActiveFiltersDisplay() {
        const activeFilters = [];

        // Active type filter
        const activeType = Array.from(typeFilters).find(f => f.classList.contains('active'));
        if (activeType && activeType.dataset.type !== 'all') {
          activeFilters.push(activeType.textContent.trim());
        }

        // Active price filters
        priceFilters.forEach(cb => {
          if (cb.checked) {
            activeFilters.push(cb.nextElementSibling.textContent.trim());
          }
        });

        // Active size filters
        sizeFilters.forEach(el => {
          if (el.classList.contains('active')) {
            activeFilters.push(el.textContent.trim());
          }
        });

        // Active color filters
        colorFilters.forEach(el => {
          if (el.classList.contains('active')) {
            activeFilters.push(el.dataset.color);
          }
        });

        const activeFiltersDisplay = document.getElementById('active-filters-display');
        if (activeFilters.length > 0) {
          activeFiltersDisplay.textContent = 'Active Filters: ' + activeFilters.join(', ');
          activeFiltersDisplay.style.display = 'block';
        } else {
          activeFiltersDisplay.textContent = '';
          activeFiltersDisplay.style.display = 'none';
        }
      }

      // Clear Filters button
      const clearFiltersBtn = document.getElementById('clear-filters-btn');
      clearFiltersBtn.addEventListener('click', function() {
        // Clear type filters
        typeFilters.forEach(f => f.classList.remove('active'));
        // Set 'all' type filter active if exists
        const allTypeFilter = Array.from(typeFilters).find(f => f.dataset.type === 'all');
        if (allTypeFilter) allTypeFilter.classList.add('active');

        // Clear price filters
        priceFilters.forEach(cb => cb.checked = false);

        // Clear size filters
        sizeFilters.forEach(el => el.classList.remove('active'));

        // Clear color filters
        colorFilters.forEach(el => el.classList.remove('active'));

        // Apply filters after clearing
        filterProducts();
        updateActiveFiltersDisplay();
      });

      // Apply filters from URL query parameters on page load
      function applyFiltersFromURL() {
        const urlParams = new URLSearchParams(window.location.search);
        console.log('URL Params:', urlParams.toString());

        // Apply gender/type filter
        const gender = urlParams.get('gender');
        console.log('Gender param:', gender);
        if (gender) {
          typeFilters.forEach(f => f.classList.remove('active'));
          // Try to find exact match or fallback to 'men' or 'women'
          let genderFilter = Array.from(typeFilters).find(f => f.dataset.type.toLowerCase() === gender.toLowerCase());
          if (!genderFilter && (gender.toLowerCase() === 'men' || gender.toLowerCase() === 'women')) {
            genderFilter = Array.from(typeFilters).find(f => f.dataset.type.toLowerCase() === gender.toLowerCase());
          }
          if (genderFilter) {
            genderFilter.classList.add('active');
            console.log('Applied gender filter:', genderFilter.dataset.type);
          } else {
            console.log('No matching gender filter found');
          }
        }

        // Apply price filters
        const priceParam = urlParams.get('price');
        console.log('Price param:', priceParam);
        if (priceParam) {
          const prices = priceParam.split(',');
          priceFilters.forEach(cb => {
            const label = cb.nextElementSibling.textContent.trim();
            if (prices.includes(label)) {
              cb.checked = true;
              console.log('Checked price filter:', label);
            }
          });
        }

        // Apply size filters
        const sizeParam = urlParams.get('size');
        console.log('Size param:', sizeParam);
        if (sizeParam) {
          const sizes = sizeParam.split(',');
          sizeFilters.forEach(el => {
            if (sizes.includes(el.dataset.size)) {
              el.classList.add('active');
              console.log('Activated size filter:', el.dataset.size);
            }
          });
        }

        // Apply color filters
        const colorParam = urlParams.get('color');
        console.log('Color param:', colorParam);
        if (colorParam) {
          const colors = colorParam.split(',');
          colorFilters.forEach(el => {
            if (colors.includes(el.dataset.color.toLowerCase())) {
              el.classList.add('active');
              console.log('Activated color filter:', el.dataset.color);
            }
          });
        }

        // Apply filters after setting from URL
        filterProducts();
        updateActiveFiltersDisplay();
      }

      applyFiltersFromURL();

      // Sorting
      const sortSelect = document.getElementById('sortSelect');

      function sortProducts(sortValue) {
        // Get all product cards data
        const allCards = [];
        document.querySelectorAll('.product-card').forEach(card => {
          allCards.push({
            element: card,
            price: parseFloat(card.dataset.price),
          });
        });

        // Sort cards
        allCards.sort((a, b) => {
          if (sortValue === 'low') {
            return a.price - b.price;
          } else if (sortValue === 'high') {
            return b.price - a.price;
          }
          return 0;
        });

        // Remove all slides
        const swiperWrapper = document.querySelector('.product-swiper .swiper-wrapper');
        swiperWrapper.innerHTML = '';

        // Chunk sorted cards into groups of 20
        for (let i = 0; i < allCards.length; i += 20) {
          const chunk = allCards.slice(i, i + 20);
          const slide = document.createElement('div');
          slide.className = 'swiper-slide grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4';
          chunk.forEach(cardObj => {
            slide.appendChild(cardObj.element);
          });
          swiperWrapper.appendChild(slide);
        }

        // Destroy and reinitialize swiper
        if (swiper) swiper.destroy(true, true);
        swiper = new Swiper('.product-swiper', {
          slidesPerView: 1,
          spaceBetween: 20,
          navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
          },
          pagination: {
            el: '.swiper-pagination',
            clickable: true,
            type: 'fraction',
          },
        });
      }

      sortSelect.addEventListener('change', function() {
        sortProducts(this.value);
      });
    });
  </script>
</body>
</html>
