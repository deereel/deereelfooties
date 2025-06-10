<?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
<<<<<<< HEAD
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/auth/db.php'); ?>
<body class="bg-background" data-page="shoemaking">
=======

<body data-page="shoemaking">
>>>>>>> parent of f36b17c (checkout page)
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>


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