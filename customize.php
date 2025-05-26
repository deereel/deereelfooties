<?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>

<body data-page="customize">
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>


  <!-- Main Content -->
  <main>
    <!-- Hero Section -->
    <section class="relative w-full h-[500px]">
      <img src="/images/customize-hero.jpg" alt="DRF MADE ON ORDER" class="object-cover w-full h-full">
      <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
        <div class="text-center text-white max-w-3xl px-4">
          <h1 class="text-4xl md:text-5xl font-light mb-4">CUSTOMIZE YOUR SHOES</h1>
          <p class="text-lg md:text-xl">Create your own unique pair of DRF shoes</p>
        </div>
      </div>
    </section>

    <!-- Customization Introduction -->
    <section class="py-16 px-4">
      <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-3xl font-light mb-6">THE ULTIMATE PERSONALIZATION</h2>
        <p class="mb-8 text-lg">
          Our customization service allows you to create a truly personalized pair of shoes. Choose from a wide range
          of models, leathers, colors, and details to design footwear that reflects your personal style.
        </p>
        <div class="grid md:grid-cols-4 gap-8 mt-12">
          <div class="flex flex-col items-center">
            <div class="w-16 h-16 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-2xl">1</span>
            </div>
            <h3 class="text-xl mb-2">SELECT MODEL</h3>
            <p class="text-gray-600">Choose from our extensive range of classic styles</p>
          </div>
          <div class="flex flex-col items-center">
            <div class="w-16 h-16 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-2xl">2</span>
            </div>
            <h3 class="text-xl mb-2">CHOOSE LEATHER</h3>
            <p class="text-gray-600">Select from premium leathers in various colors and finishes</p>
          </div>
          <div class="flex flex-col items-center">
            <div class="w-16 h-16 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-2xl">3</span>
            </div>
            <h3 class="text-xl mb-2">ADD DETAILS</h3>
            <p class="text-gray-600">Personalize with soles, lasts, and optional monograms</p>
          </div>
          <div class="flex flex-col items-center">
            <div class="w-16 h-16 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-2xl">4</span>
            </div>
            <h3 class="text-xl mb-2">PRODUCTION</h3>
            <p class="text-gray-600">Your shoes are handcrafted in our workshop in Mallorca</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Interactive Customizer Preview -->
    <section class="py-16 bg-neutral-100">
      <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-light text-center mb-12">INTERACTIVE CUSTOMIZER</h2>
        <div class="bg-white p-8 shadow-md">
          <div class="grid md:grid-cols-2 gap-12">
            <div class="relative aspect-square overflow-hidden bg-gray-50 flex items-center justify-center">
              <img src="/images/shoe-customizer.jpg" alt="Shoe Preview" class="object-contain">
              <div class="absolute inset-0 flex items-center justify-center">
                <div class="bg-black/70 text-white py-3 px-6 rounded">
                  <p>Interactive 3D preview coming soon</p>
                </div>
              </div>
            </div>
            <div>
              <div class="space-y-8">
                <div>
                  <h3 class="text-xl font-medium mb-4 flex items-center">
                    <i class="fas fa-shoe-prints mr-2"></i> Select Your Model
                  </h3>
                  <div class="grid grid-cols-2 gap-4">
                    <button class="border border-gray-300 p-3 text-center hover:border-black transition">
                      Oxford
                    </button>
                    <button class="border border-gray-300 p-3 text-center hover:border-black transition">
                      Derby
                    </button>
                    <button class="border border-gray-300 p-3 text-center hover:border-black transition">
                      Loafer
                    </button>
                    <button class="border border-gray-300 p-3 text-center hover:border-black transition">
                      Monk
                    </button>
                  </div>
                </div>

                <div>
                  <h3 class="text-xl font-medium mb-4 flex items-center">
                    <i class="fas fa-palette mr-2"></i> Choose Leather & Color
                  </h3>
                  <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                      <button class="border border-gray-300 p-3 text-center hover:border-black transition">
                        Calfskin
                      </button>
                      <button class="border border-gray-300 p-3 text-center hover:border-black transition">
                        Suede
                      </button>
                      <button class="border border-gray-300 p-3 text-center hover:border-black transition">
                        Shell Cordovan
                      </button>
                      <button class="border border-gray-300 p-3 text-center hover:border-black transition">
                        Museum Calf
                      </button>
                    </div>
                    <div class="flex flex-wrap gap-3">
                      <button class="w-8 h-8 rounded-full border border-gray-300 hover:ring-2 hover:ring-black transition" style="background-color: black;" aria-label="black color"></button>
                      <button class="w-8 h-8 rounded-full border border-gray-300 hover:ring-2 hover:ring-black transition" style="background-color: brown;" aria-label="brown color"></button>
                      <button class="w-8 h-8 rounded-full border border-gray-300 hover:ring-2 hover:ring-black transition" style="background-color: burgundy;" aria-label="burgundy color"></button>
                      <button class="w-8 h-8 rounded-full border border-gray-300 hover:ring-2 hover:ring-black transition" style="background-color: navy;" aria-label="navy color"></button>
                      <button class="w-8 h-8 rounded-full border border-gray-300 hover:ring-2 hover:ring-black transition" style="background-color: tan;" aria-label="tan color"></button>
                      <button class="w-8 h-8 rounded-full border border-gray-300 hover:ring-2 hover:ring-black transition" style="background-color: olive;" aria-label="olive color"></button>
                    </div>
                  </div>
                </div>

                <div>
                  <h3 class="text-xl font-medium mb-4 flex items-center">
                    <i class="fas fa-ruler mr-2"></i> Sole Options
                  </h3>
                  <div class="grid grid-cols-2 gap-4">
                    <button class="border border-gray-300 p-3 text-center hover:border-black transition">
                      Leather
                    </button>
                    <button class="border border-gray-300 p-3 text-center hover:border-black transition">
                      Rubber
                    </button>
                    <button class="border border-gray-300 p-3 text-center hover:border-black transition">
                      Dainite
                    </button>
                    <button class="border border-gray-300 p-3 text-center hover:border-black transition">
                      Vibram
                    </button>
                  </div>
                </div>

                <div>
                  <h3 class="text-xl font-medium mb-4 flex items-center">
                    <i class="fas fa-sparkles mr-2"></i> Personal Details
                  </h3>
                  <div class="space-y-4">
                    <div>
                      <label for="initials" class="block mb-2">
                        Initials (optional)
                      </label>
                      <input
                        type="text"
                        id="initials"
                        maxlength="3"
                        class="w-full p-3 border border-gray-300 focus:outline-none focus:border-black"
                        placeholder="Up to 3 characters"
                      />
                    </div>
                  </div>
                </div>
              </div>

              <div class="mt-8 pt-8 border-t">
                <div class="flex justify-between items-center mb-4">
                  <span class="text-lg">Estimated Price:</span>
                  <span class="text-xl font-medium">€495</span>
                </div>
                <div class="flex flex-col sm:flex-row gap-4">
                  <button class="bg-black text-white px-8 py-3 flex-1 hover:bg-gray-800 transition">
                    SAVE DESIGN
                  </button>
                  <button class="bg-black text-white px-8 py-3 flex-1 hover:bg-gray-800 transition">
                    PROCEED TO ORDER
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Popular Designs -->
    <section class="py-16 px-4">
      <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl font-light text-center mb-12">POPULAR DESIGNS</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="group">
            <div class="relative aspect-[3/4] overflow-hidden mb-4">
              <img src="/images/custom-1.jpg" alt="Custom Design 1" class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
            </div>
            <h3 class="text-xl text-center mb-2">Custom Design #1</h3>
            <p class="text-center text-gray-600 mb-4">Oxford in Museum Calf with Leather Sole</p>
            <div class="text-center">
              <button class="border border-black px-6 py-2 hover:bg-black hover:text-white transition">
                USE AS TEMPLATE
              </button>
            </div>
          </div>
          <div class="group">
            <div class="relative aspect-[3/4] overflow-hidden mb-4">
              <img src="/images/custom-2.jpg" alt="Custom Design 2" class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
            </div>
            <h3 class="text-xl text-center mb-2">Custom Design #2</h3>
            <p class="text-center text-gray-600 mb-4">Oxford in Museum Calf with Leather Sole</p>
            <div class="text-center">
              <button class="border border-black px-6 py-2 hover:bg-black hover:text-white transition">
                USE AS TEMPLATE
              </button>
            </div>
          </div>
          <div class="group">
            <div class="relative aspect-[3/4] overflow-hidden mb-4">
              <img src="/images/custom-3.jpg" alt="Custom Design 3" class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
            </div>
            <h3 class="text-xl text-center mb-2">Custom Design #3</h3>
            <p class="text-center text-gray-600 mb-4">Oxford in Museum Calf with Leather Sole</p>
            <div class="text-center">
              <button class="border border-black px-6 py-2 hover:bg-black hover:text-white transition">
                USE AS TEMPLATE
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Testimonials -->
    <section class="py-16 bg-neutral-100 px-4">
      <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-light text-center mb-12">CUSTOMER TESTIMONIALS</h2>
        <div class="grid md:grid-cols-2 gap-8">
          <div class="bg-white p-6 shadow-sm">
            <div class="flex items-center mb-4">
              <div class="w-12 h-12 bg-neutral-200 rounded-full mr-4"></div>
              <div>
                <h4 class="font-medium">Robert K.</h4>
                <div class="flex">
                  <i class="fas fa-star text-yellow-500"></i>
                  <i class="fas fa-star text-yellow-500"></i>
                  <i class="fas fa-star text-yellow-500"></i>
                  <i class="fas fa-star text-yellow-500"></i>
                  <i class="fas fa-star text-yellow-500"></i>
                </div>
              </div>
            </div>
            <p class="text-gray-600">
              "The customization process was intuitive and enjoyable. My custom oxfords arrived in perfect condition
              and exactly as I designed them. The quality is exceptional."
            </p>
          </div>
          <div class="bg-white p-6 shadow-sm">
            <div class="flex items-center mb-4">
              <div class="w-12 h-12 bg-neutral-200 rounded-full mr-4"></div>
              <div>
                <h4 class="font-medium">James T.</h4>
                <div class="flex">
                  <i class="fas fa-star text-yellow-500"></i>
                  <i class="fas fa-star text-yellow-500"></i>
                  <i class="fas fa-star text-yellow-500"></i>
                  <i class="fas fa-star text-yellow-500"></i>
                  <i class="fas fa-star text-yellow-500"></i>
                </div>
              </div>
            </div>
            <p class="text-gray-600">
              "I've ordered three pairs of customized DRF shoes, and each time the attention to detail and
              craftsmanship has been outstanding. Worth every penny."
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- FAQ -->
    <section class="py-16 px-4">
      <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-light text-center mb-12">FREQUENTLY ASKED QUESTIONS</h2>
        <div class="space-y-6">
          <details class="border p-4 group">
            <summary class="font-medium cursor-pointer list-none flex justify-between items-center">
              How long does it take to receive my customized shoes?
              <span class="transform group-open:rotate-180 transition-transform">
                <i class="fas fa-chevron-down"></i>
              </span>
            </summary>
            <div class="mt-4 text-gray-600">
              <p>
                Customized shoes typically take 6-8 weeks to produce and deliver. This timeframe allows our artisans
                to carefully craft your shoes to your exact specifications. You'll receive updates on your order
                status throughout the process.
              </p>
            </div>
          </details>

          <details class="border p-4 group">
            <summary class="font-medium cursor-pointer list-none flex justify-between items-center">
              Can I return or exchange my customized shoes?
              <span class="transform group-open:rotate-180 transition-transform">
                <i class="fas fa-chevron-down"></i>
              </span>
            </summary>
            <div class="mt-4 text-gray-600">
              <p>
                Since customized shoes are made specifically for you, they cannot be returned or exchanged unless
                there is a manufacturing defect. We recommend carefully reviewing your design before finalizing your
                order.
              </p>
            </div>
          </details>

          <details class="border p-4 group">
            <summary class="font-medium cursor-pointer list-none flex justify-between items-center">
              How do I determine my correct size for customized shoes?
              <span class="transform group-open:rotate-180 transition-transform">
                <i class="fas fa-chevron-down"></i>
              </span>
            </summary>
            <div class="mt-4 text-gray-600">
              <p>
                We recommend visiting our store for a professional fitting if possible. Alternatively, you can
                use our detailed size guide or contact our customer service team for assistance. Different lasts may
                fit differently, so it's important to get the right size for your specific model.
              </p>
            </div>
          </details>

          <details class="border p-4 group">
            <summary class="font-medium cursor-pointer list-none flex justify-between items-center">
              Can I customize every aspect of the shoe?
              <span class="transform group-open:rotate-180 transition-transform">
                <i class="fas fa-chevron-down"></i>
              </span>
            </summary>
            <div class="mt-4 text-gray-600">
              <p>
                Our customization service allows you to select the model, leather type, color, sole, and add personal
                details like initials. While we offer extensive options, some technical aspects of construction are
                standardized to ensure the quality and durability of our shoes.
              </p>
            </div>
          </details>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="py-16 bg-neutral-900 text-white text-center">
      <div class="max-w-3xl mx-auto px-4">
        <h2 class="text-3xl font-light mb-6">READY TO CREATE YOUR UNIQUE PAIR?</h2>
        <p class="mb-8">
          Experience the luxury of shoes made especially for you. Delivery in approximately 6-8 weeks.
        </p>
        <a href="#" class="bg-white text-black px-8 py-3 inline-block hover:bg-gray-200 transition">
          BEGIN CUSTOMIZING
        </a>
      </div>
    </section>
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