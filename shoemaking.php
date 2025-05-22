<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- SEO Meta Tags -->
  <meta name="description" content="DeeReel Footies – Premium handcrafted shoes for men and women. Shop loafers, boots, slippers, and custom footwear.">
  <meta name="keywords" content="handcrafted shoes, Elegant shoes, DRF, DeeReel Footies, men's shoes, women's boots, women's shoes, men's boots, men's slippers, women's slippers, custom footwear, loafers, sandals, mules, derby, monk strap">
  <meta name="author" content="DeeReel Footies">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Shoemaking - DeeReel Footies | Handcrafted Luxury Shoes for Men and Women</title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="/js/product-grid.js" defer></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <style>
    /* Top-level dropdown appears on hover */
    .dropdown:hover > .dropdown-menu {
      display: block;
      margin-top: 0;
    }
  
    /* Basic positioning */
    .dropdown {
      position: relative;
    }
  
    .dropdown-menu {
      display: none;
      position: absolute;
      top: 100%;
      left: 0;
      z-index: 1000;
      margin-top: 0.5rem;
    }
  
    /* Remove Bootstrap's default down arrow */
    .dropdown-toggle::after {
      display: none !important;
    }
  
    /* Nested dropdown positioning */
    .dropdown-submenu {
      position: relative;
    }
  
    .dropdown-submenu > .dropdown-menu {
      display: none;
      position: absolute;
      top: 0;
      left: 100%; /* Align to the right of parent */
      margin-left: 0;
      z-index: 1001;
    }
  
    /* Show submenu on hover */
    .dropdown-submenu:hover > .dropdown-menu {
      display: block;
    }
  
    /* Prevent overlap of submenus */
    .dropdown-menu > .dropdown-submenu {
      position: relative;
    }

    .dropdown-submenu {
      position: relative;
    }
    .dropdown-submenu .dropdown-menu {
      display: none;
    }
    .dropdown-submenu .dropdown-menu.show {
      display: block;
    }

    /* Scroll to Top Custom Styling */
    #scrollToTop {
      background-color: #381819; /* Dark chocolate background */
      color: #fff; /* White icon color */
      align-items: center;
      justify-content: center;
      transition: background-color 0.3s ease, transform 0.3s ease;
    }

    #scrollToTop:hover {
      background-color: #5a2a2b; /* Lighter chocolate on hover */
      transform: scale(1.1);
    }

    html {
      scroll-behavior: smooth;
    }

  </style>
  
  
    
</head>
<body>
  <!-- Sticky Header -->
  <header class="sticky-top bg-white border-bottom shadow-sm">
    <!-- First Row: Logo and Search Icon -->
    <div class="d-flex justify-content-between align-items-center px-4 py-1 mb-1">
      <a href="/index.php" class="navbar-brand d-flex align-items-center">
        <img src="/images/drf-logo.png" alt="DeeReeL Footies Logo" style="height: 50px;">
        <span class="nav-brand-title">DeeReel Footies</span>
      </a>
      <button class="btn btn-outline-secondary d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <button class="btn d-none d-lg-inline">
        <i class="fas fa-search"></i>
      </button>
    </div>

    <!-- Mobile Navigation -->
      <div class="mobile-nav d-lg-none">
        <button class="btn btn-outline-secondary" id="mobileMenuToggle" aria-label="Toggle navigation">
          ☰ Menu
        </button>

        <div class="mobile-nav-overlay hidden fixed inset-0 bg-black/80 z-50">
          <div class="mobile-nav-content bg-white h-full w-3/4 max-w-sm shadow-lg p-6 overflow-y-auto">
            <button class="close-btn text-black text-2xl mb-4" id="closeMobileMenu" aria-label="Close navigation">
              &times;
            </button>
            <ul class="space-y-4">
              <li><a href="/men.php" class="text-lg font-medium">MEN</a></li>
              <li><a href="/women.php" class="text-lg font-medium">WOMEN</a></li>
              <li><a href="/products.php" class="text-lg font-medium">PRODUCTS</a></li>
              <li><a href="/size-guide.php" class="text-lg font-medium">SIZE GUIDE</a></li>
              <li><a href="/contact.php" class="text-lg font-medium">CONTACT</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>


    <!-- Second Row: Main Navigation -->
    <nav class="d-none d-md-flex w-100 align-items-center">
    <!--<nav class="hidden md:flex items-center space-x-8 mb-2"> -->
      <!-- Left-side navigation -->
      <div class="d-flex align-items-center gap-4">
            <!-- MEN Dropdown -->    
            <div class="dropdown position-relative">
                <a href="/men.php" class="text-dark text-decoration-none" role="button">MEN</a>
                <ul class="dropdown-menu">
                  <!-- Shoes -->
                  <li class="dropdown-submenu">
                      <a href="/products/men/men-shoes.php?gender=men&type=all" class="dropdown-item">Shoes</a>
                      <ul class="dropdown-menu">
                          <li><a class="dropdown-item" href="/products/men/men-shoes.php?gender=men&type=loafers">Loafers</a></li>
                          <li><a class="dropdown-item" href="/products/men/men-shoes.php?gender=men&type=oxford">Oxford</a></li>
                          <li><a class="dropdown-item" href="/products/men/men-shoes.php?gender=men&type=derby">Derby</a></li>
                          <li><a class="dropdown-item" href="/products/men/men-shoes.php?gender=men&type=monk">Monk Straps</a></li>
                      </ul>
                  </li>
                  
                  <!-- Boots -->
                  <li class="dropdown-submenu">
                      <a href="/products/men/men-boots.php?gender=men&type=all" class="dropdown-item">Boots</a>
                      <ul class="dropdown-menu">
                          <li><a class="dropdown-item" href="/products/men/men-boots.php?gender=men&type=zipper">Zipper boots</a></li>
                          <li><a class="dropdown-item" href="/products/men/men-boots.php?gender=men&type=wingtip">Wingtip boots</a></li>
                          <li><a class="dropdown-item" href="/products/men/men-boots.php?gender=men&type=chelsea">Chelsea boots</a></li>
                          <li><a class="dropdown-item" href="/products/men/men-boots.php?gender=men&type=captoe">Captoe boots</a></li>
                          <li><a class="dropdown-item" href="/products/men/men-boots.php?gender=men&type=jodhpur">Jodhpur boots</a></li>
                          <li><a class="dropdown-item" href="/products/men/men-boots.php?gender=men&type=balmoral">Balmoral boots</a></li>
                      </ul>  
                  </li>
              
                  <li><a class="dropdown-item" href="/products/men/men-slippers.php">Slippers/Sandals</a></li>
                  <li><a class="dropdown-item" href="/products/men/men-mules.php">Mules</a></li>
                </ul>
            </div>
            
            

            <!-- WOMEN Dropdown -->    
            <div class="dropdown position-relative">

                <a href="/women.php" class="text-dark text-decoration-none" role="button">WOMEN</a>
                <ul class="dropdown-menu">
                  <!-- Shoes -->
                  <li class="dropdown-submenu">
                      <a href="/products/women/women-shoes.php?gender=women&type=all" class="dropdown-item">Shoes</a>
                      <ul class="dropdown-menu">
                          <li><a class="dropdown-item" href="/products/women/women-shoes.php?gender=women&type=loafers">Loafers</a></li>
                          <li><a class="dropdown-item" href="/products/women/women-shoes.php?gender=women&type=oxford">Oxford</a></li>
                          <li><a class="dropdown-item" href="/products/women/women-shoes.php?gender=women&type=derby">Derby</a></li>
                          <li><a class="dropdown-item" href="/products/women/women-shoes.php?gender=women&type=monk">Monk Straps</a></li>
                      </ul>
                  </li>
                  
                  <!-- Boots -->
                  <li class="dropdown-submenu">
                      <a href="/products/women/women-boots.php?gender=women&type=all" class="dropdown-item">Boots</a>
                      <ul class="dropdown-menu">
                          <li><a class="dropdown-item" href="/products/women/women-boots.php?gender=women&type=zipper">Zipper boots</a></li>
                          <li><a class="dropdown-item" href="/products/women/women-boots.php?gender=women&type=wingtip">Wingtip boots</a></li>
                          <li><a class="dropdown-item" href="/products/women/women-boots.php?gender=women&type=chelsea">Chelsea boots</a></li>
                          <li><a class="dropdown-item" href="/products/women/women-boots.php?gender=women&type=captoe">Captoe boots</a></li>
                          <li><a class="dropdown-item" href="/products/women/women-boots.php?gender=women&type=jodhpur">Jodhpur boots</a></li>
                          <li><a class="dropdown-item" href="/products/women/women-boots.php?gender=women&type=balmoral">Balmoral boots</a></li>
                      </ul>
                      </li>
              
                  <li><a class="dropdown-item" href="/products/women/women-slippers.php">Slippers/Sandals</a></li>
                  <li><a class="dropdown-item" href="/products/women/women-mules.php">Mules</a></li>
                </ul>
            </div>
            
            

            <a href="/customize.php" class="nav-link">CUSTOMIZE</a>
            <a href="/moo.php" class="nav-link">Made on Order</a>
            <a href="/products.php" class="nav-link">PRODUCTS</a>
            <a href="/size-guide.php" class="nav-link">SIZE GUIDE</a>
      </div>

      <!-- Right-side Shoemaking and Cart -->
      <ul class="navbar-nav flex-row gap-3 ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="/shoemaking.php">Shoemaking</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/cart.php">
            <i class="fas fa-shopping-cart"></i> Cart
          </a>
        </li>
        <!-- User Account Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="userAccountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user"></i> Account
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userAccountDropdown">
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Sign In / Sign Up</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#profileSection">Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a href="#" id="logoutBtn" class="dropdown-item">Logout</a></li>            
          </ul>
        </li>
      </ul>
    </nav>
    
  </header>

  <!-- Main Content -->
  <main>
    <!-- Hero Section -->
    <section class="relative w-full h-[500px]">
      <img src="/images/shoemaking-hero.jpg" alt="DeeReeL Footies Shoemaking" class="object-cover w-full h-full">
      <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
        <div class="text-center text-white max-w-3xl px-4">
          <h1 class="text-4xl md:text-5xl font-light mb-4">THE ART OF SHOEMAKING</h1>
          <p class="text-lg md:text-xl">Craftsmanship passed down through generations</p>
        </div>
      </div>
    </section>

    <!-- Introduction -->
    <section class="py-16 px-4">
      <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-3xl font-light mb-6">A LEGACY OF EXCELLENCE</h2>
        <p class="mb-8">
          Since 1866, DeeReeL Footies has been dedicated to the art of traditional shoemaking. Our commitment to quality
          and craftsmanship has been passed down through generations, preserving techniques that have stood the
          test of time while embracing innovation where it enhances our craft.
        </p>
        <p>
          Every pair of DeeReeL Footies shoes represents over 150 years of expertise, with each step of the process
          executed by skilled artisans in our workshop in Mallorca, Spain. From selecting the finest leathers
          to the final polish, we maintain an unwavering dedication to excellence.
        </p>
      </div>
    </section>

    <!-- Craftsmanship Process -->
    <section class="py-16 bg-neutral-100">
      <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-light text-center mb-12">THE SHOEMAKING PROCESS</h2>
        
        <!-- Step 1: Design -->
        <div class="grid md:grid-cols-2 gap-12 items-center mb-20">
          <div class="order-2 md:order-1">
            <span class="inline-block bg-black text-white px-3 py-1 mb-4">STEP 1</span>
            <h3 class="text-2xl font-light mb-4">DESIGN & LAST SELECTION</h3>
            <p class="mb-4">
              Every DeeReeL Footies shoe begins with a design concept and the selection of an appropriate last. The last is a
              three-dimensional form that determines the shape and fit of the shoe. Our collection of lasts has been
              developed and refined over decades to provide both aesthetic appeal and comfort.
            </p>
            <p>
              Our designers work closely with our master craftsmen to ensure that each design not only looks beautiful
              but can be executed to our exacting standards. This collaborative process ensures that innovation is
              balanced with practicality and tradition.
            </p>
          </div>
          <div class="relative h-[400px] order-1 md:order-2">
            <img src="/images/shoemaking-design.jpg" alt="Design & Last Selection" class="object-cover w-full h-full">
          </div>
        </div>
        
        <!-- Step 2: Pattern Making -->
        <div class="grid md:grid-cols-2 gap-12 items-center mb-20">
          <div class="relative h-[400px]">
            <img src="/images/shoemaking-pattern.jpg" alt="Pattern Making" class="object-cover w-full h-full">
          </div>
          <div>
            <span class="inline-block bg-black text-white px-3 py-1 mb-4">STEP 2</span>
            <h3 class="text-2xl font-light mb-4">PATTERN MAKING & CUTTING</h3>
            <p class="mb-4">
              Once the design is finalized, our pattern makers create precise templates for each component of the shoe.
              These patterns are then used to cut the leather pieces that will form the upper of the shoe.
            </p>
            <p>
              The cutting process requires exceptional skill and attention to detail. Our artisans carefully select
              sections of the hide that have the optimal characteristics for each part of the shoe, ensuring both
              beauty and durability. This meticulous selection process minimizes waste while maximizing quality.
            </p>
          </div>
        </div>
        
        <!-- Step 3: Stitching -->
        <div class="grid md:grid-cols-2 gap-12 items-center mb-20">
          <div class="order-2 md:order-1">
            <span class="inline-block bg-black text-white px-3 py-1 mb-4">STEP 3</span>
            <h3 class="text-2xl font-light mb-4">STITCHING & ASSEMBLY</h3>
            <p class="mb-4">
              The cut leather pieces are then carefully stitched together to form the upper of the shoe. This process
              requires precision and expertise, as the stitching must be both strong and aesthetically pleasing.
            </p>
            <p>
              Our artisans use a combination of machine and hand stitching, depending on the requirements of each
              section. Decorative elements such as broguing or medallions are meticulously executed during this phase,
              adding character and distinction to each pair.
            </p>
          </div>
          <div class="relative h-[400px] order-1 md:order-2">
            <img src="/images/shoemaking-stitching.jpg" alt="Stitching & Assembly" class="object-cover w-full h-full">
          </div>
        </div>
        
        <!-- Step 4: Lasting -->
        <div class="grid md:grid-cols-2 gap-12 items-center mb-20">
          <div class="relative h-[400px]">
            <img src="/images/shoemaking-lasting.jpg" alt="Lasting" class="object-cover w-full h-full">
          </div>
          <div>
            <span class="inline-block bg-black text-white px-3 py-1 mb-4">STEP 4</span>
            <h3 class="text-2xl font-light mb-4">LASTING</h3>
            <p class="mb-4">
              Lasting is the process of shaping the upper around the last to give the shoe its final form. This
              critical step requires both strength and finesse, as the leather must be stretched and secured without
              damaging its integrity.
            </p>
            <p>
              Our craftsmen use traditional wooden lasts and specialized tools to achieve the perfect shape. The upper
              is pulled taut over the last and temporarily secured with tacks. This process is what gives DeeReeL Footies shoes
              their distinctive silhouette and ensures a comfortable fit.
            </p>
          </div>
        </div>
        
        <!-- Step 5: Goodyear Welting -->
        <div class="grid md:grid-cols-2 gap-12 items-center mb-20">
          <div class="order-2 md:order-1">
            <span class="inline-block bg-black text-white px-3 py-1 mb-4">STEP 5</span>
            <h3 class="text-2xl font-light mb-4">GOODYEAR WELTING</h3>
            <p class="mb-4">
              DeeReeL Footies is renowned for our Goodyear welted construction, a technique that enhances both the durability
              and repairability of our shoes. This method involves stitching a strip of leather (the welt) to the upper
              and insole, then stitching the outsole to the welt.
            </p>
            <p>
              This double-stitching process creates a shoe that can be resoled multiple times, extending its lifespan
              significantly. It also provides superior water resistance and structural integrity. While more
              time-consuming and labor-intensive than other construction methods, Goodyear welting represents our
              commitment to creating shoes that last a lifetime.
            </p>
          </div>
          <div class="relative h-[400px] order-1 md:order-2">
            <img src="/images/shoemaking-welting.jpg" alt="Goodyear Welting" class="object-cover w-full h-full">
          </div>
        </div>
        
        <!-- Step 6: Finishing -->
        <div class="grid md:grid-cols-2 gap-12 items-center">
          <div class="relative h-[400px]">
            <img src="/images/shoemaking-finishing.jpg" alt="Finishing" class="object-cover w-full h-full">
          </div>
          <div>
            <span class="inline-block bg-black text-white px-3 py-1 mb-4">STEP 6</span>
            <h3 class="text-2xl font-light mb-4">FINISHING</h3>
            <p class="mb-4">
              The final stage in our shoemaking process is finishing, where each pair receives the attention to detail
              that sets DeeReeL Footies apart. The edges of the soles are trimmed, shaped, and polished to perfection.
            </p>
            <p class="mb-4">
              The uppers are meticulously cleaned and conditioned, then polished to bring out the natural beauty of the
              leather. Any decorative elements are refined, and the shoes undergo a thorough quality inspection to
              ensure they meet our exacting standards.
            </p>
            <p>
              Only after passing this rigorous inspection are the shoes ready to be boxed and shipped to our customers
              around the world, carrying with them the pride and tradition of DeeReeL Footies craftsmanship.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Materials -->
    <section class="py-16 px-4">
      <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl font-light text-center mb-12">EXCEPTIONAL MATERIALS</h2>
        
        <div class="grid md:grid-cols-2 gap-12 mb-16">
          <div>
            <h3 class="text-2xl font-light mb-6">THE FINEST LEATHERS</h3>
            <p class="mb-4">
              At DeeReeL Footies, we believe that exceptional shoes begin with exceptional materials. We source our leathers
              from the world's most prestigious tanneries, selecting only those that meet our stringent quality
              standards.
            </p>
            <p class="mb-4">
              From buttery-soft calfskin to rich shell cordovan, each type of leather is chosen for its specific
              characteristics and beauty. We work closely with our suppliers to ensure sustainable and ethical
              practices, respecting both tradition and the environment.
            </p>
            <p>
              Our leather selection includes:
            </p>
            <ul class="list-disc pl-5 mt-2 space-y-1">
              <li>Box Calf: Smooth, fine-grained leather with excellent durability</li>
              <li>Museum Calf: Distinguished by its subtle mottled appearance</li>
              <li>Shell Cordovan: Renowned for its durability and distinctive patina</li>
              <li>Suede: Soft, velvety leather with a luxurious texture</li>
              <li>Grain Leather: Naturally textured leather with enhanced water resistance</li>
            </ul>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div class="relative aspect-square overflow-hidden">
              <img src="/images/leather-1.jpg" alt="Box Calf Leather" class="object-cover w-full h-full">
            </div>
            <div class="relative aspect-square overflow-hidden">
              <img src="/images/leather-2.jpg" alt="Museum Calf Leather" class="object-cover w-full h-full">
            </div>
            <div class="relative aspect-square overflow-hidden">
              <img src="/images/leather-3.jpg" alt="Shell Cordovan Leather" class="object-cover w-full h-full">
            </div>
            <div class="relative aspect-square overflow-hidden">
              <img src="/images/leather-4.jpg" alt="Suede Leather" class="object-cover w-full h-full">
            </div>
          </div>
        </div>
        
        <div class="grid md:grid-cols-2 gap-12">
          <div class="order-2 md:order-1">
            <h3 class="text-2xl font-light mb-6">COMPONENTS & DETAILS</h3>
            <p class="mb-4">
              Beyond the leather uppers, every component of a DeeReeL Footies shoe is selected with the same attention to
              quality and performance. Our oak-tanned leather soles provide the perfect balance of durability and
              flexibility, while our cork fillings mold to the wearer's foot for personalized comfort.
            </p>
            <p class="mb-4">
              We use only the finest threads for our stitching, ensuring both strength and aesthetic appeal. Our laces,
              linings, and even our hidden components like toe puffs and heel counters are all chosen to contribute to
              the overall excellence of the final product.
            </p>
            <p>
              It's this holistic approach to quality—where every element, visible or not, is given equal
              importance—that defines the DeeReeL Footies difference and ensures that our shoes provide exceptional comfort,
              durability, and style.
            </p>
          </div>
          <div class="relative h-[400px] order-1 md:order-2">
            <img src="/images/shoemaking-components.jpg" alt="Shoe Components" class="object-cover w-full h-full">
          </div>
        </div>
      </div>
    </section>

    <!-- Workshop -->
    <section class="py-16 bg-neutral-900 text-white">
      <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-light text-center mb-12">OUR WORKSHOP IN MALLORCA</h2>
        
        <div class="grid md:grid-cols-2 gap-12 items-center">
          <div>
            <p class="mb-4">
              Nestled in the heart of Inca, Mallorca, our workshop is where tradition meets innovation. For over a
              century, this island has been home to our craft, with skills and knowledge passed down through
              generations of artisans.
            </p>
            <p class="mb-4">
              Today, our workshop combines time-honored techniques with modern efficiency, creating an environment
              where craftsmanship can flourish. Our team of skilled artisans—many of whom have been with us for
              decades—bring passion and expertise to every pair of shoes they create.
            </p>
            <p>
              We take pride in maintaining this workshop tradition in an age of mass production, believing that the
              human touch and attention to detail are irreplaceable elements in creating truly exceptional footwear.
            </p>
          </div>
          <div class="relative h-[400px]">
            <img src="/images/workshop.jpg" alt="DeeReeL Footies Workshop in Lagos Nigeria" class="object-cover w-full h-full">
          </div>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="py-16 px-4 text-center">
      <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl font-light mb-6">EXPERIENCE DEEREEL FOOTIES CRAFTSMANSHIP</h2>
        <p class="mb-8">
          Discover the difference that over 150 years of shoemaking expertise makes. Browse our collections to find
          your perfect pair of DeeReeL Footies shoes, handcrafted with pride and passion.
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
          <a href="/men.php" class="bg-black text-white px-8 py-3 hover:bg-gray-800 transition">
            SHOP MEN'S COLLECTION
          </a>
          <a href="/women.php" class="bg-black text-white px-8 py-3 hover:bg-gray-800 transition">
            SHOP WOMEN'S COLLECTION
          </a>
        </div>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer class="bg-white border-t">
    <!-- Main Footer -->
    <div class="max-w-7xl mx-auto py-12 px-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
      <div>
        <h3 class="font-medium mb-4">ABOUT DEEREEL FOOTIES</h3>
        <ul class="space-y-2 text-sm">
          <li>
            <a href="/our-history.php" class="hover:underline">Our History</a>
          </li>
          <li>
            <a href="/shoemaking.php" class="hover:underline">Craftsmanship</a>
          </li>
          <li>
            <a href="/contact.php" class="hover:underline">Contact Us</a>
          </li>
          <li>
            <a href="/careers.php" class="hover:underline">Careers</a>
          </li>
        </ul>
      </div>

      <div>
        <h3 class="font-medium mb-4">CUSTOMER SERVICE</h3>
        <ul class="space-y-2 text-sm">
          <li>
            <a href="/shipping.php" class="hover:underline">Shipping & Delivery</a>
          </li>
          <li>
            <a href="/returns.php" class="hover:underline">Returns & Exchanges</a>
          </li>
          <li>
            <a href="/size-guide.php" class="hover:underline">Size Guide</a>
          </li>
          <li>
            <a href="/faq.php" class="hover:underline">FAQ</a>
          </li>
          <li>
            <a href="/care-guide.php" class="hover:underline">Shoe Care Guide</a>
          </li>
        </ul>
      </div>

      <div>
        <h3 class="font-medium mb-4">SHOP</h3>
        <ul class="space-y-2 text-sm">
          <li>
            <a href="/men.php" class="hover:underline">Men's Collection</a>
          </li>
          <li>
            <a href="/women.php" class="hover:underline">Women's Collection</a>
          </li>
          <li>
            <a href="/customize.php" class="hover:underline">Customize</a>
          </li>
          <li>
            <a href="/moo.php" class="hover:underline">MADE ON ORDER</a>
          </li>
          <li>
            <a href="/products.php" class="hover:underline">Outlet</a>
          </li>          
        </ul>
      </div>

      <div>
        <h3 class="font-medium mb-4">NEWSLETTER</h3>
        <p class="text-sm mb-4">Subscribe to receive updates, access to exclusive deals, and more.</p>
        <form class="mb-6">
          <div class="flex flex-col space-y-2">
            <input
              type="email"
              class="px-4 py-2 border border-gray-300 focus:outline-none"
              placeholder="Your email address"
              required
            />
            <button type="submit" class="bg-black text-white px-6 py-2 hover:bg-gray-800 transition">
              SUBSCRIBE
            </button>
          </div>
        </form>
        

        <h3 class="font-medium mb-4">FOLLOW US</h3>
        <div class="flex space-x-4">
          <a href="https://www.instagram.com/deereelfooties" class="hover:text-gray-600">
            <i class="fab fa-instagram text-lg"></i>
            <span class="sr-only">Instagram</span>
          </a>
          <a href="https://www.tiktok.com/@deereel.footies" class="hover:text-gray-600">
            <i class="fab fa-tiktok text-lg"></i>
            <span class="sr-only">Tiktok</span>
          </a>
          <a href="https://wa.me/2347031864772?text=Hello%20DeeReeL%20Footies%2C%20I%20would%20like%20to%20place%20order%20for..." class="hover:text-gray-600">
            <i class="fab fa-whatsapp text-lg"></i>
            <span class="sr-only">Twitter</span>
          </a>
          
        </div>
      </div>
    </div>

    <!-- Bottom Footer -->
    <div class="border-t py-6 px-4">
      <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center text-sm">
        <div class="mb-2 mb-md-0">
          <p>&copy; <span id="current-year"></span> DeeReeL Footies. All rights reserved.</p>
        </div>
        <div class="flex flex-wrap justify-center gap-4">
          <a href="/terms.php" class="hover:underline">Terms & Conditions</a>
          <a href="/privacy.php" class="hover:underline">Privacy Policy</a>
          <a href="/cookies.php" class="hover:underline">Cookie Policy</a>
          <a href="/sitemap.php" class="hover:underline">Sitemap</a>
        </div>
      </div>
    </div>
    
  </footer>

  <!-- Scroll to Top Button -->
  <a href="#" class="btn btn-dark position-fixed bottom-0 end-0 m-4 shadow rounded-circle" style="z-index: 999; width: 45px; height: 45px; display: none;" id="scrollToTop">
    <i class="fas fa-chevron-up"></i>
  </a>


  <script>
    document.querySelectorAll('.dropdown-submenu > a').forEach(function (element) {
      element.addEventListener('click', function (e) {
        const submenu = this.nextElementSibling;
        if (submenu && submenu.classList.contains('dropdown-menu')) {
          e.preventDefault();
          submenu.classList.toggle('show');
          e.stopPropagation();
        }
      });
    });
  </script>
  
  <script src="/js/main.js"></script>

  <!-- Include this inside body on all pages -->
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/account-modal.php'); ?>

  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
  <script>
    // Set the current year in the footer
    document.getElementById('current-year').textContent = new Date().getFullYear();
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Bootstrap JS (with Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-..." crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

  <script>
    // Enable dropdowns on hover
    document.querySelectorAll('.dropdown').forEach(function (dropdown) {
      dropdown.addEventListener('mouseenter', function () {
        let toggle = this.querySelector('[data-bs-toggle="dropdown"]');
        if (toggle) {
          let dropdownInstance = bootstrap.Dropdown.getOrCreateInstance(toggle);
          dropdownInstance.show();
        }
      });
      dropdown.addEventListener('mouseleave', function () {
        let toggle = this.querySelector('[data-bs-toggle="dropdown"]');
        if (toggle) {
          let dropdownInstance = bootstrap.Dropdown.getOrCreateInstance(toggle);
          dropdownInstance.hide();
        }
      });
    });
  
    // Also add hover support for nested submenus (if you're using them)
    document.querySelectorAll('.dropdown-submenu').forEach(function (submenu) {
      submenu.addEventListener('mouseenter', function () {
        let submenuList = this.querySelector('.dropdown-menu');
        if (submenuList) submenuList.classList.add('show');
      });
      submenu.addEventListener('mouseleave', function () {
        let submenuList = this.querySelector('.dropdown-menu');
        if (submenuList) submenuList.classList.remove('show');
      });
    });
  </script>
  <script>
    const scrollBtn = document.getElementById("scrollToTop");
  
    window.addEventListener("scroll", () => {
      if (window.scrollY > 300) {
        scrollBtn.style.display = "flex";
      } else {
        scrollBtn.style.display = "none";
      }
    });
  
    scrollBtn.addEventListener("click", (e) => {
      e.preventDefault();
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  </script>

  <script>
    document.querySelectorAll('.dropdown-submenu > a').forEach(link => {
      link.addEventListener('click', function (e) {
        const submenu = this.nextElementSibling;

        // If the user clicked the main link directly (not just hovering)
        if (!submenu || !submenu.classList.contains('dropdown-menu')) {
          return; // not a submenu
        }

        // Prevent the submenu from hijacking the click
        const isSubmenuOpen = submenu.classList.contains('show');
        if (!isSubmenuOpen) {
          // Allow navigation
          window.location.href = this.getAttribute('href');
        }

        e.preventDefault();
      });
    });
  </script>
</body>
</html>