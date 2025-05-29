<?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>

<body class="bg-background" data-page="women-boots">
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>


  <!-- Main Content -->
  <main>
    <div class="max-w-7xl mx-auto px-4 py-8">
      <!-- Breadcrumb -->
      <div class="mb-8">
        <h1 class="text-3xl font-light mb-2">WOMEN'S MULES COLLECTION</h1>
        <div class="flex items-center text-sm text-gray-500">
          <a href="/index.php">Home</a>
          <span class="mx-2">/</span>
          <a href="/women.php">Women</a>
          <span class="mx-2">/</span>
          <span>Mules</span>
        </div>
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
            <!-- Example Product -->
            <div class="group product-card"
                 data-price="35000"
                 data-size="40,41,42,43,44,45,46"
                 data-color="tan"
                 data-type="oxford"
                 data-gender="men">
              <a href="/products/men/shoes/oxford-cap-toe-600.php">
                <div class="relative aspect-[3/4] overflow-hidden mb-4">
                  <img src="/images/Oxford Cap Toe 600.webp" alt="Oxford Cap Toe 600"
                       class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
                </div>
                <h3 class="text-lg">Oxford Cap Toe 600</h3>
                <p class="text-gray-500">₦35,000</p>
              </a>
            </div>

            <div class="group product-card"
                 data-price="42000"
                 data-size="40,41,42,43,44,45,46"
                 data-color="brown"
                 data-type="loafer"
                 data-gender="men">
              <a href="/products/men/shoes/penny-loafer-600.php">
                <div class="relative aspect-[3/4] overflow-hidden mb-4">
                  <img src="/images/penny loafer 600.webp" alt="Penny Loafer 600"
                       class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
                </div>
                <h3 class="text-lg">Penny Loafer 600</h3>
                <p class="text-gray-500">₦42,000</p>
              </a>
            </div>
  
            <!-- Add more products as needed -->
          </div>
  
          <!-- Pagination -->
          <div class="pagination flex justify-center mt-12">
            <div class="flex space-x-1">
              <!-- JS will insert page buttons here -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/account-modal.php'); ?>
  

  <!-- Scroll to Top Button -->
  <a href="#" class="btn-primary btn-dark position-fixed bottom-0 end-0 m-4 shadow rounded-circle" style="z-index: 999; width: 45px; height: 45px; display: none;" id="scrollToTop">
    <i class="fas fa-chevron-up"></i>
  </a>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php'); ?>


  


  


</body>
</html>