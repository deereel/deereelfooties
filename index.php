<?php include('components/header.php'); ?>

<body>
  <?php include('components/navbar.php'); ?>


  <!-- Main Content -->
  <main>
    <!-- Hero Section -->
    <section class="relative w-full h-[600px]">
      <img src="/images/hero.jpg" alt="DeeReeL Footies Handcrafted Shoes" class="object-cover w-full h-full">
      <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
        <div class="text-center text-white max-w-3xl px-4">
          <h1 class="text-4xl md:text-5xl font-light mb-4">HANDCRAFTED SHOES FROM MALLORCA</h1>
          <p class="text-lg md:text-xl mb-8">
            Since 1866, creating exceptional footwear with traditional methods and the finest materials
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

    <!-- Featured Products -->
    <section class="py-16 px-4 max-w-7xl mx-auto">
      <h2 class="text-3xl font-light text-center mb-12">FEATURED COLLECTION</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        <!-- Add products here -->
        <div class="group product-card"
                 data-price="55000"
                 data-size="40,41,42,43,44,45,46"
                 data-color="tan"
                 data-type="oxford">
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
                 data-gender="men">
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
                 data-type="loafer">
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
                 data-gender="men">
              <a href="/products/men/mules/vintage-croc-600.php">
                <div class="relative aspect-[3/4] overflow-hidden mb-4">
                  <img src="/images/Vintage Croc 600.webp" alt="Vintage Croc 600"
                       class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
                </div>
                <h3 class="text-lg">Vintage Croc 600</h3>
                <p class="text-gray-500">₦35,000</p>
              </a>
            </div>

        <!-- Add more product here -->

        
      </div>
      <div class="text-center mt-12">
        <a href="/products.php" class="border border-black px-8 py-3 inline-block hover:bg-black hover:text-white transition">
          VIEW ALL
        </a>
      </div>
    </section>

    <!-- Story Section -->
    <section class="py-16 bg-neutral-100">
      <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-2 gap-12 items-center">
        <div>
          <h2 class="text-3xl font-light mb-6">OUR STORY</h2>
          <p class="mb-4">
            For over 150 years, DeeReeL Footies has been dedicated to the art of shoemaking in Lagos, Nigeria. Our team
            combines traditional craftsmanship with innovative techniques to create shoes of exceptional
            quality and durability.
          </p>
          <p class="mb-6">
            Every pair of DRF shoes is handcrafted by skilled artisans using the finest materials sourced from
            around the world.
          </p>
          <a href="/our-history.php" class="border border-black px-6 py-2 inline-block hover:bg-black hover:text-white transition">
            LEARN MORE
          </a>
        </div>
        <div class="relative h-[500px]">
          <img src="/images/shoemaker-workshop-making-shoes_171337-12290.avif" alt="DRF Workshop" class="object-cover w-full h-full">
        </div>
      </div>
    </section>

    <!-- Inspiration Section -->
    <section class="py-16 px-4">
      <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl font-light text-center mb-4">MORE INSPIRATION #DEEREEL FOOTIES</h2>
        <p class="text-center mb-12 max-w-2xl mx-auto">
          These customers have already purchased them, see how they look on them. When they are yours, tag
          <a href="www.instagram.com/deereelfooties">@deereelfooties</a> on your instagram posts and <a href="www.tiktok.com/deereel.footies">@deereel.footies</a> on 
          your tiktok posts, and we will share your look!
        </p>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div class="relative aspect-square overflow-hidden group">
            <img src="/images/instagram-1.jpg" alt="Instagram Post 1" class="object-cover w-full h-full">
            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
              <i class="fab fa-instagram text-white text-2xl"></i>
            </div>
          </div>
          <div class="relative aspect-square overflow-hidden group">
            <img src="/images/instagram-2.jpg" alt="Instagram Post 2" class="object-cover w-full h-full">
            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
              <i class="fab fa-instagram text-white text-2xl"></i>
            </div>
          </div>
          <div class="relative aspect-square overflow-hidden group">
            <img src="/images/instagram-3.jpg" alt="Instagram Post 3" class="object-cover w-full h-full">
            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
              <i class="fab fa-instagram text-white text-2xl"></i>
            </div>
          </div>
          <div class="relative aspect-square overflow-hidden group">
            <img src="/images/instagram-4.jpg" alt="Instagram Post 4" class="object-cover w-full h-full">
            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
              <i class="fab fa-instagram text-white text-2xl"></i>
            </div>
          </div>
          <div class="relative aspect-square overflow-hidden group">
            <img src="/images/instagram-5.jpg" alt="Instagram Post 5" class="object-cover w-full h-full">
            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
              <i class="fab fa-instagram text-white text-2xl"></i>
            </div>
          </div>
          <div class="relative aspect-square overflow-hidden group">
            <img src="/images/instagram-6.jpg" alt="Instagram Post 6" class="object-cover w-full h-full">
            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
              <i class="fab fa-instagram text-white text-2xl"></i>
            </div>
          </div>
          <div class="relative aspect-square overflow-hidden group">
            <img src="/images/instagram-7.jpg" alt="Instagram Post 7" class="object-cover w-full h-full">
            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
              <i class="fab fa-instagram text-white text-2xl"></i>
            </div>
          </div>
          <div class="relative aspect-square overflow-hidden group">
            <img src="/images/instagram-8.jpg" alt="Instagram Post 8" class="object-cover w-full h-full">
            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
              <i class="fab fa-instagram text-white text-2xl"></i>
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

  

</body>
</html>