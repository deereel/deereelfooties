<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Shoemaking Craftsmanship | DeeReel Footies</title>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>
</head>
<body class="bg-background" data-page="shoemaking">

  <!-- Hero Section -->
  <section class="relative">
    <div class="swiper hero-swiper">
      <div class="swiper-wrapper">
        <!-- Slide 1 -->
        <div class="swiper-slide">
          <div class="slide-bg" style="background-image: url('/images/shoemaking.webp');"></div>
          <div class="slide-content">
            <div class="container mx-auto px-4">
              <div class="max-w-xl">
                <h1 class="text-5xl md:text-6xl font-light mb-4">Explore Our Workshop</h1>
                <p class="text-xl mb-8">Witness the artistry behind every handcrafted pair in our Lagos workshop.</p>
                <a href="#workshop-tour" class="btn-primary px-8 py-3">Take the Tour</a>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Slide 2 -->
        <div class="swiper-slide">
          <div class="slide-bg" style="background-image: url('/images/shoemaking1.webp');"></div>
          <div class="slide-content">
            <div class="container mx-auto px-4">
              <div class="max-w-xl ml-auto text-right">
                <h1 class="text-5xl md:text-6xl font-light mb-4">Commission Your Shoe</h1>
                <p class="text-xl mb-8">Create bespoke footwear tailored exclusively to your vision and measurements.</p>
                <div class="flex flex-wrap gap-4 justify-end">
                  <a href="/moo.php" class="btn-primary px-8 py-3">Start Your Order</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="swiper-pagination"></div>
    </div>
  </section>

  <!-- Main Content -->
  <main>
    <div class="max-w-7xl mx-auto px-4 py-16">
      
      <!-- Introduction -->
      <section class="mb-20">
        <div class="grid md:grid-cols-2 gap-12 items-center">
          <div>
            <h2 class="text-3xl font-light mb-6">Craftsmanship Rooted in Tradition</h2>
            <p class="text-lg mb-6 text-gray-600">
              At DeeReel Footies, every pair of shoes tells a story of meticulous craftsmanship. 
              Our workshop in Lagos, Nigeria, serves as the heart of our operation, where skilled 
              artisans blend time-honored techniques with contemporary innovation.
            </p>
            <p class="text-lg mb-6 text-gray-600">
              Each shoe is a testament to our commitment to quality, durability, and style. 
              From the selection of premium materials to the final finishing touches, 
              every step is executed with precision and passion.
            </p>
            <div class="grid grid-cols-2 gap-6 mt-8">
              <div class="text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">10+</div>
                <p class="text-sm text-gray-600">Years of Experience</p>
              </div>
              <div class="text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">50+</div>
                <p class="text-sm text-gray-600">Shoes Crafted Monthly</p>
              </div>
            </div>
          </div>
          <div class="relative">
            <div class="bg-gray-200 aspect-[4/3] rounded-lg flex items-center justify-center">
              <i class="fas fa-hammer text-6xl text-gray-400"></i>
            </div>
            <div class="absolute -bottom-6 -right-6 bg-blue-600 text-white p-4 rounded-lg">
              <i class="fas fa-map-marker-alt mr-2"></i>
              <span class="font-medium">Lagos, Nigeria</span>
            </div>
          </div>
        </div>
      </section>

      <!-- Workshop Tour -->
      <section id="workshop-tour" class="mb-20">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-light mb-4">Inside Our Lagos Workshop</h2>
          <p class="text-lg text-gray-600 max-w-2xl mx-auto">
            Take a journey through our workshop and discover the meticulous process 
            behind every pair of DeeReel Footies shoes.
          </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
          <div class="text-center">
            <div class="bg-gray-200 aspect-square rounded-lg mb-4 flex items-center justify-center">
              <i class="fas fa-cut text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-xl font-medium mb-2">Pattern Making & Cutting</h3>
            <p class="text-gray-600">
              Our master craftsmen create precise patterns and carefully cut premium leather 
              using traditional techniques passed down through generations.
            </p>
          </div>
          
          <div class="text-center">
            <div class="bg-gray-200 aspect-square rounded-lg mb-4 flex items-center justify-center">
              <i class="fas fa-hand-paper text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-xl font-medium mb-2">Hand Stitching</h3>
            <p class="text-gray-600">
              Every stitch is placed with care and precision. Our artisans use the finest threads 
              and traditional hand-stitching methods for superior durability.
            </p>
          </div>
          
          <div class="text-center">
            <div class="bg-gray-200 aspect-square rounded-lg mb-4 flex items-center justify-center">
              <i class="fas fa-shoe-prints text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-xl font-medium mb-2">Lasting & Finishing</h3>
            <p class="text-gray-600">
              The final shaping and finishing process ensures perfect fit and comfort, 
              with attention to every detail from sole to upper.
            </p>
          </div>
        </div>
      </section>

      <!-- The Process -->
      <section class="mb-20">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-light mb-4">Our Shoemaking Process</h2>
          <p class="text-lg text-gray-600">From concept to completion, every step is carefully executed</p>
        </div>

        <div class="space-y-12">
          <!-- Step 1 -->
          <div class="grid md:grid-cols-2 gap-8 items-center">
            <div class="order-2 md:order-1">
              <div class="flex items-center mb-4">
                <div class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold mr-4">1</div>
                <h3 class="text-2xl font-medium">Design & Pattern Creation</h3>
              </div>
              <p class="text-gray-600 mb-4">
                Every shoe begins with a vision. Our designers create detailed patterns and templates, 
                ensuring each design meets our standards for both aesthetics and functionality.
              </p>
              <ul class="text-gray-600 space-y-2">
                <li>• Custom pattern development</li>
                <li>• Material selection and testing</li>
                <li>• Prototype creation and refinement</li>
              </ul>
            </div>
            <div class="order-1 md:order-2">
              <div class="bg-gray-200 aspect-[4/3] rounded-lg flex items-center justify-center">
                <i class="fas fa-drafting-compass text-6xl text-gray-400"></i>
              </div>
            </div>
          </div>

          <!-- Step 2 -->
          <div class="grid md:grid-cols-2 gap-8 items-center">
            <div>
              <div class="bg-gray-200 aspect-[4/3] rounded-lg flex items-center justify-center">
                <i class="fas fa-layer-group text-6xl text-gray-400"></i>
              </div>
            </div>
            <div>
              <div class="flex items-center mb-4">
                <div class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold mr-4">2</div>
                <h3 class="text-2xl font-medium">Material Preparation</h3>
              </div>
              <p class="text-gray-600 mb-4">
                We source only the finest materials from trusted suppliers. Each piece of leather 
                is carefully inspected and prepared for the crafting process.
              </p>
              <ul class="text-gray-600 space-y-2">
                <li>• Premium leather selection</li>
                <li>• Quality control inspection</li>
                <li>• Conditioning and preparation</li>
              </ul>
            </div>
          </div>

          <!-- Step 3 -->
          <div class="grid md:grid-cols-2 gap-8 items-center">
            <div class="order-2 md:order-1">
              <div class="flex items-center mb-4">
                <div class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold mr-4">3</div>
                <h3 class="text-2xl font-medium">Cutting & Assembly</h3>
              </div>
              <p class="text-gray-600 mb-4">
                Using traditional cutting techniques, our craftsmen carefully cut each piece. 
                The assembly process requires skill and patience to ensure perfect alignment.
              </p>
              <ul class="text-gray-600 space-y-2">
                <li>• Precision cutting techniques</li>
                <li>• Hand-guided assembly</li>
                <li>• Quality checks at each stage</li>
              </ul>
            </div>
            <div class="order-1 md:order-2">
              <div class="bg-gray-200 aspect-[4/3] rounded-lg flex items-center justify-center">
                <i class="fas fa-cut text-6xl text-gray-400"></i>
              </div>
            </div>
          </div>

          <!-- Step 4 -->
          <div class="grid md:grid-cols-2 gap-8 items-center">
            <div>
              <div class="bg-gray-200 aspect-[4/3] rounded-lg flex items-center justify-center">
                <i class="fas fa-tools text-6xl text-gray-400"></i>
              </div>
            </div>
            <div>
              <div class="flex items-center mb-4">
                <div class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold mr-4">4</div>
                <h3 class="text-2xl font-medium">Lasting & Construction</h3>
              </div>
              <p class="text-gray-600 mb-4">
                The lasting process shapes the shoe over a wooden last, creating the final form. 
                This critical step determines the fit and comfort of the finished shoe.
              </p>
              <ul class="text-gray-600 space-y-2">
                <li>• Goodyear welt construction</li>
                <li>• Hand-lasted for perfect fit</li>
                <li>• Sole attachment and shaping</li>
              </ul>
            </div>
          </div>

          <!-- Step 5 -->
          <div class="grid md:grid-cols-2 gap-8 items-center">
            <div class="order-2 md:order-1">
              <div class="flex items-center mb-4">
                <div class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold mr-4">5</div>
                <h3 class="text-2xl font-medium">Finishing & Quality Control</h3>
              </div>
              <p class="text-gray-600 mb-4">
                The final stage involves meticulous finishing work, polishing, and comprehensive 
                quality control to ensure every shoe meets our exacting standards.
              </p>
              <ul class="text-gray-600 space-y-2">
                <li>• Hand polishing and buffing</li>
                <li>• Final quality inspection</li>
                <li>• Packaging and presentation</li>
              </ul>
            </div>
            <div class="order-1 md:order-2">
              <div class="bg-gray-200 aspect-[4/3] rounded-lg flex items-center justify-center">
                <i class="fas fa-gem text-6xl text-gray-400"></i>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Materials & Techniques -->
      <section class="mb-20">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-light mb-4">Premium Materials & Techniques</h2>
          <p class="text-lg text-gray-600">We use only the finest materials and time-tested techniques</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
          <div class="text-center">
            <div class="bg-amber-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-leaf text-2xl text-amber-600"></i>
            </div>
            <h3 class="font-medium mb-2">Full-Grain Leather</h3>
            <p class="text-sm text-gray-600">Premium leather that develops beautiful patina over time</p>
          </div>
          
          <div class="text-center">
            <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-circle text-2xl text-blue-600"></i>
            </div>
            <h3 class="font-medium mb-2">Goodyear Welt</h3>
            <p class="text-sm text-gray-600">Traditional construction method for durability and repairability</p>
          </div>
          
          <div class="text-center">
            <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-hand-holding-heart text-2xl text-green-600"></i>
            </div>
            <h3 class="font-medium mb-2">Hand Stitching</h3>
            <p class="text-sm text-gray-600">Every stitch placed by skilled artisans for superior quality</p>
          </div>
          
          <div class="text-center">
            <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-tree text-2xl text-purple-600"></i>
            </div>
            <h3 class="font-medium mb-2">Cedar Components</h3>
            <p class="text-sm text-gray-600">Natural cedar elements for moisture control and freshness</p>
          </div>
        </div>
      </section>

      <!-- Workshop Location -->
      <section class="mb-20">
        <div class="bg-gray-50 rounded-lg p-8">
          <div class="grid md:grid-cols-2 gap-8 items-center">
            <div>
              <h2 class="text-3xl font-light mb-4">Visit Our Lagos Workshop</h2>
              <p class="text-lg text-gray-600 mb-6">
                Experience the art of shoemaking firsthand. Our workshop in Lagos welcomes 
                visitors who want to see traditional craftsmanship in action.
              </p>
              
              <div class="space-y-4 mb-6">
                <div class="flex items-center">
                  <i class="fas fa-map-marker-alt text-blue-600 mr-3"></i>
                  <span>2, Oluwa street, off Oke-Ayo street, Ishaga Lagos, Nigeria</span>
                </div>
                <div class="flex items-center">
                  <i class="fas fa-clock text-blue-600 mr-3"></i>
                  <span>Monday - Friday: 9:00 AM - 6:00 PM</span>
                </div>
                <div class="flex items-center">
                  <i class="fas fa-phone text-blue-600 mr-3"></i>
                  <span>+234 813 423 5110</span>
                </div>
              </div>
              
              <div class="flex flex-col sm:flex-row gap-4">
                <a href="/contact.php" class="bg-black text-white px-6 py-3 text-center hover:bg-gray-800 transition">
                  Schedule a Visit
                </a>
                <a href="https://wa.me/2347031864772?text=Hello! I'd like to visit your workshop in Lagos" 
                   target="_blank" class="border border-black px-6 py-3 text-center hover:bg-black hover:text-white transition">
                  <i class="fab fa-whatsapp mr-2"></i>WhatsApp Us.
                </a>
              </div>
            </div>
            
            <div>
              <div class="bg-gray-200 aspect-[4/3] rounded-lg flex items-center justify-center">
                <div class="text-center">
                  <i class="fas fa-building text-6xl text-gray-400 mb-4"></i>
                  <p class="text-gray-500">Workshop Location</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Call to Action -->
      <section class="text-center">
        <div class="bg-gray-100 text-black rounded-lg p-12 relative overflow-hidden">
          <!-- Logo Pattern Background -->
          <div class="absolute inset-0 opacity-10">
            <?php for($row = 0; $row < 4; $row++): ?>
              <div class="flex justify-between items-center w-full" style="height: 25%;">
                <?php for($col = 0; $col < 15; $col++): ?>
                  <img src="/images/drf-logo.webp" alt="" class="w-12 h-12">
                <?php endfor; ?>
              </div>
            <?php endfor; ?>
          </div>
          
          <!-- Content -->
          <div class="relative z-10">
            <h2 class="text-3xl font-light mb-4">Ready to Experience Our Craftsmanship?</h2>
            <p class="text-xl mb-8 opacity-80">
              Discover the difference that traditional shoemaking techniques can make
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
              <a href="/products.php" class="bg-black text-white px-8 py-3 hover:bg-gray-800 transition">
                Browse Our Collection
              </a>
              <a href="/moo.php" class="border border-black px-8 py-3 hover:bg-black hover:text-white transition">
                Commission Custom Shoes
              </a>
            </div>
          </div>
        </div>
      </section>
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
  
  <!-- Swiper JS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
  <link rel="stylesheet" href="/css/slider.css" />
  <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const swiper = new Swiper('.hero-swiper', {
        loop: true,
        autoplay: {
          delay: 5000,
          disableOnInteraction: false,
        },
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
      });
    });
  </script>

  <script>
    // Smooth scrolling for anchor links
    document.addEventListener('DOMContentLoaded', function() {
      const anchorLinks = document.querySelectorAll('a[href^="#"]');
      
      anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          const targetId = this.getAttribute('href');
          const targetElement = document.querySelector(targetId);
          
          if (targetElement) {
            targetElement.scrollIntoView({
              behavior: 'smooth',
              block: 'start'
            });
          }
        });
      });

      // Add scroll animations
      const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
      };

      const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
          }
        });
      }, observerOptions);

      // Observe elements for animation
      document.querySelectorAll('section').forEach(section => {
        section.style.opacity = '0';
        section.style.transform = 'translateY(20px)';
        section.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(section);
      });
    });
  </script>
  
</body>
</html>