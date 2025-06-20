<?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
<<<<<<< HEAD
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/auth/db.php'); ?>
<body class="bg-background" data-page="oxford-cap-toe-80201">
=======

<body data-page="oxford-cap-toe-80201">
>>>>>>> parent of f36b17c (checkout page)
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>

  <!-- Main Content -->
  <main>
    <div class="max-w-7xl mx-auto px-4 py-8">
      <!-- Breadcrumb -->
      <div class="mb-8">
        <div class="flex items-center text-sm text-gray-500">
          <a href="../..//index.php">Home</a>
          <span class="mx-2">/</span>
          <a href="/men.php">Men</a>
          <span class="mx-2">/</span>
          <span>Oxford Cap Toe 80201</span>
        </div>
      </div>

      <!-- Product Details -->
      <div class="grid md:grid-cols-2 gap-12">
        <!-- Product Images -->
        <div>
          <div class="relative aspect-square overflow-hidden mb-4">
            <img src="/images/oxford cap toe 80201.webp" alt="Oxford Cap Toe 80201" class="object-cover w-full h-full" id="main-product-image">
          </div>
          <div class="grid grid-cols-4 gap-2">
            <div class="relative aspect-square overflow-hidden cursor-pointer product-thumbnail">
              <img src="/images/oxford cap toe 80201.webp" alt="Oxford Cap Toe 80201 - View 1" class="object-cover w-full h-full">
            </div>
            <div class="relative aspect-square overflow-hidden cursor-pointer product-thumbnail">
              <img src="/images/oxford cap toe 80201-2.webp" alt="Oxford Cap Toe 80201 - View 2" class="object-cover w-full h-full">
            </div>
            <div class="relative aspect-square overflow-hidden cursor-pointer product-thumbnail">
              <img src="/images/oxford cap toe 80201-3.webp" alt="Oxford Cap Toe 80201 - View 3" class="object-cover w-full h-full">
            </div>
            <div class="relative aspect-square overflow-hidden cursor-pointer product-thumbnail">
              <img src="/images/oxford cap toe 80201-4.webp" alt="Oxford Cap Toe 80201 - View 4" class="object-cover w-full h-full">
            </div>
          </div>
        </div>

        <!-- Product Info -->
        <div>
          <h1 class="text-3xl font-light mb-2">Oxford Cap Toe 80201</h1>
          <p class="text-2xl mb-4">€450</p>
          
          <div class="mb-6">
            <div class="flex items-center mb-2">
              <div class="flex">
                <i class="fas fa-star text-yellow-500"></i>
                <i class="fas fa-star text-yellow-500"></i>
                <i class="fas fa-star text-yellow-500"></i>
                <i class="fas fa-star text-yellow-500"></i>
                <i class="fas fa-star-half-alt text-yellow-500"></i>
              </div>
              <span class="ml-2 text-sm text-gray-500">4.5 (24 reviews)</span>
            </div>
            <p class="text-sm text-gray-500">In stock - Ships within 1-2 business days</p>
          </div>

          <div class="mb-6">
            <p class="mb-4">
              The Oxford Cap Toe is a timeless classic, crafted with meticulous attention to detail. This elegant dress shoe features premium calfskin leather, Goodyear welt construction, and a leather sole.
            </p>
            <ul class="list-disc pl-5 mb-4 text-gray-600">
              <li>Premium calfskin upper</li>
              <li>Leather lining and insole</li>
              <li>Goodyear welted construction</li>
              <li>Oak-tanned leather sole</li>
              <li>Made in Mallorca, Spain</li>
            </ul>
          </div>

          <!-- Color Selection -->
          <div class="mb-6">
            <h3 class="font-medium mb-2">Color</h3>
            <div class="flex space-x-2">
              <button class="w-8 h-8 rounded-full bg-black ring-2 ring-black" aria-label="Black"></button>
              <button class="w-8 h-8 rounded-full" style="background-color: #5c3a21;" aria-label="Dark Brown"></button>
              <button class="w-8 h-8 rounded-full" style="background-color: #8b4513;" aria-label="Brown"></button>
              <button class="w-8 h-8 rounded-full" style="background-color: #1a2456;" aria-label="Navy"></button>
            </div>
          </div>

          <!-- Size Selection -->
          <div class="mb-6">
            <div class="flex justify-between items-center mb-2">
              <h3 class="font-medium">Size</h3>
              <button class="text-sm underline" id="size-guide-btn">Size Guide</button>
            </div>
            <div class="grid grid-cols-4 gap-2">
              <button class="border border-gray-300 py-2 hover:border-black">UK 6</button>
              <button class="border border-gray-300 py-2 hover:border-black">UK 7</button>
              <button class="border border-gray-300 py-2 hover:border-black">UK 8</button>
              <button class="border border-gray-300 py-2 hover:border-black">UK 9</button>
              <button class="border border-gray-300 py-2 hover:border-black">UK 10</button>
              <button class="border border-gray-300 py-2 hover:border-black">UK 11</button>
              <button class="border border-gray-300 py-2 hover:border-black">UK 12</button>
              <button class="border border-gray-300 py-2 hover:border-black">UK 13</button>
            </div>
          </div>

          <!-- Width Selection -->
          <div class="mb-8">
            <h3 class="font-medium mb-2">Width</h3>
            <div class="grid grid-cols-3 gap-2">
              <button class="border border-gray-300 py-2 hover:border-black">D (Standard)</button>
              <button class="border border-gray-300 py-2 hover:border-black">E (Wide)</button>
              <button class="border border-gray-300 py-2 hover:border-black">EE (Extra Wide)</button>
            </div>
          </div>

          <!-- Add to Cart -->
          <div class="mb-6">
            <div class="flex flex-col sm:flex-row gap-4">
              <div class="flex border border-gray-300">
                <button class="px-4 py-2 quantity-btn" data-action="decrease">-</button>
                <input type="number" value="1" min="1" class="w-12 text-center focus:outline-none" id="quantity">
                <button class="px-4 py-2 quantity-btn" data-action="increase">+</button>
              </div>
              <button class="bg-black text-white px-8 py-3 flex-grow hover:bg-gray-800 transition" id="add-to-cart-btn">
                ADD TO CART
              </button>
            </div>
          </div>

          <!-- Additional Options -->
          <div class="flex flex-col sm:flex-row gap-4 mb-8">
            <button class="border border-black px-4 py-2 flex-1 hover:bg-black hover:text-white transition">
              ADD TO WISHLIST
            </button>
            <button class="border border-black px-4 py-2 flex-1 hover:bg-black hover:text-white transition">
              CUSTOMIZE THIS SHOE
            </button>
          </div>

          <!-- Product Details Accordion -->
          <div class="border-t pt-6 space-y-4">
            <details class="group">
              <summary class="flex justify-between items-center cursor-pointer">
                <span class="font-medium">Description</span>
                <span class="transform group-open:rotate-180 transition-transform">
                  <i class="fas fa-chevron-down"></i>
                </span>
              </summary>
              <div class="pt-4 pb-2 text-gray-600">
                <p>
                  The Oxford Cap Toe 80201 is a quintessential dress shoe that embodies timeless elegance and superior craftsmanship. Featuring a sleek silhouette with a cap toe design, this Oxford is handcrafted in our workshop in Mallorca using traditional techniques that have been perfected over generations.
                </p>
                <p class="mt-2">
                  The premium calfskin leather develops a beautiful patina over time, making each pair uniquely yours. The Goodyear welt construction ensures durability and allows for resoling, making this an investment piece that will last for years with proper care.
                </p>
              </div>
            </details>

            <details class="group border-t pt-4">
              <summary class="flex justify-between items-center cursor-pointer">
                <span class="font-medium">Details & Care</span>
                <span class="transform group-open:rotate-180 transition-transform">
                  <i class="fas fa-chevron-down"></i>
                </span>
              </summary>
              <div class="pt-4 pb-2 text-gray-600">
                <ul class="list-disc pl-5">
                  <li>Last: Inca</li>
                  <li>Construction: Goodyear welted</li>
                  <li>Upper: Premium calfskin leather</li>
                  <li>Lining: Full leather</li>
                  <li>Sole: Oak-tanned leather</li>
                  <li>Heel: Stacked leather with rubber top piece</li>
                  <li>Wipe with a clean, dry cloth</li>
                  <li>Apply quality shoe cream or polish as needed</li>
                  <li>Use shoe trees between wears (not included)</li>
                  <li>Allow 24 hours between wears</li>
                </ul>
              </div>
            </details>

            <details class="group border-t pt-4">
              <summary class="flex justify-between items-center cursor-pointer">
                <span class="font-medium">Shipping & Returns</span>
                <span class="transform group-open:rotate-180 transition-transform">
                  <i class="fas fa-chevron-down"></i>
                </span>
              </summary>
              <div class="pt-4 pb-2 text-gray-600">
                <p>Free worldwide shipping on all orders over €180.</p>
                <p class="mt-2">Standard shipping: 3-5 business days (Europe), 5-7 business days (International)</p>
                <p class="mt-2">Express shipping: 1-2 business days (Europe), 2-3 business days (International)</p>
                <p class="mt-2">Returns accepted within 30 days of delivery for unworn shoes in original packaging.</p>
              </div>
            </details>
          </div>
        </div>
      </div>

      <!-- Related Products -->
      <section class="mt-16">
        <h2 class="text-2xl font-light mb-8">You May Also Like</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
          <div class="group">
            <a href="2.php">
              <div class="relative aspect-[3/4] overflow-hidden mb-4">
                <img src="/images/product-2.webp" alt="Penny Loafer 80647" class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
              </div>
              <h3 class="text-lg">Penny Loafer 80647</h3>
              <p class="text-gray-500">€425</p>
            </a>
          </div>
          <div class="group">
            <a href="3.php">
              <div class="relative aspect-[3/4] overflow-hidden mb-4">
                <img src="/images/product-3.webp" alt="Chelsea Boot 80216" class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
              </div>
              <h3 class="text-lg">Chelsea Boot 80216</h3>
              <p class="text-gray-500">€495</p>
            </a>
          </div>
          <div class="group">
            <a href="4.php">
              <div class="relative aspect-[3/4] overflow-hidden mb-4">
                <img src="/images/product-4.webp" alt="Wing Tip 80290" class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
              </div>
              <h3 class="text-lg">Wing Tip 80290</h3>
              <p class="text-gray-500">€460</p>
            </a>
          </div>
          <div class="group">
            <a href="5.php">
              <div class="relative aspect-[3/4] overflow-hidden mb-4">
                <img src="/images/product-5.webp" alt="Double Monk 80544" class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
              </div>
              <h3 class="text-lg">Double Monk 80544</h3>
              <p class="text-gray-500">€475</p>
            </a>
          </div>
        </div>
      </section>
    </div>

    <!-- Size Guide Modal -->
    <div id="size-guide-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
      <div class="bg-white p-8 max-w-3xl w-full max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-light">Size Guide</h2>
          <button id="close-size-guide" class="text-2xl">&times;</button>
        </div>
        <div class="mb-6">
          <h3 class="font-medium mb-2">Men's Size Conversion Chart</h3>
          <div class="overflow-x-auto">
            <table class="w-full border-collapse">
              <thead>
                <tr class="bg-gray-100">
                  <th class="border p-2 text-left">UK</th>
                  <th class="border p-2 text-left">US</th>
                  <th class="border p-2 text-left">EU</th>
                  <th class="border p-2 text-left">JP</th>
                  <th class="border p-2 text-left">Foot Length (cm)</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="border p-2">6</td>
                  <td class="border p-2">7</td>
                  <td class="border p-2">40</td>
                  <td class="border p-2">25</td>
                  <td class="border p-2">25.0</td>
                </tr>
                <tr>
                  <td class="border p-2">7</td>
                  <td class="border p-2">8</td>
                  <td class="border p-2">41</td>
                  <td class="border p-2">26</td>
                  <td class="border p-2">26.0</td>
                </tr>
                <tr>
                  <td class="border p-2">8</td>
                  <td class="border p-2">9</td>
                  <td class="border p-2">42</td>
                  <td class="border p-2">27</td>
                  <td class="border p-2">27.0</td>
                </tr>
                <tr>
                  <td class="border p-2">9</td>
                  <td class="border p-2">10</td>
                  <td class="border p-2">43</td>
                  <td class="border p-2">28</td>
                  <td class="border p-2">28.0</td>
                </tr>
                <tr>
                  <td class="border p-2">10</td>
                  <td class="border p-2">11</td>
                  <td class="border p-2">44</td>
                  <td class="border p-2">29</td>
                  <td class="border p-2">29.0</td>
                </tr>
                <tr>
                  <td class="border p-2">11</td>
                  <td class="border p-2">12</td>
                  <td class="border p-2">45</td>
                  <td class="border p-2">30</td>
                  <td class="border p-2">30.0</td>
                </tr>
                <tr>
                  <td class="border p-2">12</td>
                  <td class="border p-2">13</td>
                  <td class="border p-2">46</td>
                  <td class="border p-2">31</td>
                  <td class="border p-2">31.0</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div>
          <h3 class="font-medium mb-2">How to Measure Your Foot</h3>
          <ol class="list-decimal pl-5 text-gray-600">
            <li class="mb-2">Stand on a piece of paper with your heel against a wall.</li>
            <li class="mb-2">Mark the longest part of your foot on the paper.</li>
            <li class="mb-2">Measure the distance from the wall to the mark in centimeters.</li>
            <li class="mb-2">Use this measurement to find your size in the chart above.</li>
            <li class="mb-2">If you're between sizes, we recommend sizing up.</li>
          </ol>
          <p class="mt-4 text-gray-600">
            Note: Different lasts may fit differently. If you have any questions about sizing, please contact our customer service team.
          </p>
        </div>
      </div>
    </div>

    <!-- Added to Cart Modal -->
    <div id="added-to-cart-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
      <div class="bg-white p-8 max-w-md w-full">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-xl font-medium">Added to Cart</h2>
          <button id="close-cart-modal" class="text-2xl">&times;</button>
        </div>
        <div class="flex items-center mb-6">
          <div class="w-20 h-20 relative overflow-hidden mr-4">
            <img src="/images/oxford cap toe 80201.webp" alt="Oxford Cap Toe 80201" class="object-cover w-full h-full">
          </div>
          <div>
            <h3 class="font-medium">Oxford Cap Toe 80201</h3>
            <p class="text-gray-500">Size: UK 8 | Color: Black</p>
            <p>€450</p>
          </div>
        </div>
        <div class="flex flex-col sm:flex-row gap-4">
          <a href="../..//cart.php" class="bg-black text-white px-4 py-2 text-center flex-1 hover:bg-gray-800 transition">
            VIEW CART
          </a>
          <button id="continue-shopping" class="border border-black px-4 py-2 flex-1 hover:bg-black hover:text-white transition">
            CONTINUE SHOPPING
          </button>
        </div>
      </div>
    </div>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/account-modal.php'); ?>
  

  <!-- Scroll to Top Button -->
  <a href="#" class="btn btn-dark position-fixed bottom-0 end-0 m-4 shadow rounded-circle" style="z-index: 999; width: 45px; height: 45px; display: none;" id="scrollToTop">
    <i class="fas fa-chevron-up"></i>
  </a>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php'); ?>


  
  
</body>
</html>