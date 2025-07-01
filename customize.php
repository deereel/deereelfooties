<?php 
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/auth/db.php'); 

// Check if we're editing an existing design
$designId = isset($_GET['design_id']) ? intval($_GET['design_id']) : 0;
$designData = null;

if ($designId > 0) {
  $stmt = $pdo->prepare("SELECT * FROM saved_designs WHERE design_id = ?");
  $stmt->execute([$designId]);
  $design = $stmt->fetch(PDO::FETCH_ASSOC);
  
  if ($design) {
    $designData = json_decode($design['design_data'], true);
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Customize Your Shoe | DeeReel Footies</title>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>
</head>

<body data-page="customize">

  <!-- Hero Section -->
  <section class="hero-section" style="background-image: url('/images/cram solid oxford.webp');">
    <div class="hero-content">
      <div class="container mx-auto px-4 text-center">
        <h1 class="text-5xl md:text-6xl font-light mb-6">Create Your Perfect Shoe</h1>
        <p class="text-xl md:text-2xl mb-4">Handcrafted to your exact specifications</p>
        <p class="text-lg mb-8 max-w-2xl mx-auto">Choose from premium materials, colors, and styles to design a one-of-a-kind pair that reflects your personal taste and fits perfectly.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <a href="#customizer" class="px-8 py-4 bg-white text-black hover:bg-gray-100 transition rounded font-medium">
            Start Customizing
          </a>
          <a href="#how-it-works" class="px-8 py-4 border-2 border-white text-white hover:bg-white hover:text-black transition rounded font-medium">
            How It Works
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- How It Works Section -->
  <section id="how-it-works" class="py-20 bg-white">
    <div class="container mx-auto px-4">
      <div class="text-center mb-16">
        <h2 class="text-4xl font-light mb-4">How Custom Works</h2>
        <p class="text-gray-600 max-w-2xl mx-auto">Our simple 4-step process ensures you get exactly what you envision</p>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <!-- Step 1 -->
        <div class="text-center">
          <div class="w-16 h-16 bg-black text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
            1
          </div>
          <h3 class="text-xl font-medium mb-3">Choose Your Style</h3>
          <p class="text-gray-600">Select from our range of classic styles including Oxford, Derby, Loafer, and more.</p>
        </div>

        <!-- Step 2 -->
        <div class="text-center">
          <div class="w-16 h-16 bg-black text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
            2
          </div>
          <h3 class="text-xl font-medium mb-3">Select Materials</h3>
          <p class="text-gray-600">Choose from premium leather options, colors, and finishes to match your vision.</p>
        </div>

        <!-- Step 3 -->
        <div class="text-center">
          <div class="w-16 h-16 bg-black text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
            3
          </div>
          <h3 class="text-xl font-medium mb-3">Perfect Fit</h3>
          <p class="text-gray-600">Provide your measurements or visit our store for professional fitting.</p>
        </div>

        <!-- Step 4 -->
        <div class="text-center">
          <div class="w-16 h-16 bg-black text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
            4
          </div>
          <h3 class="text-xl font-medium mb-3">Handcrafted</h3>
          <p class="text-gray-600">Our skilled artisans craft your shoes with attention to every detail.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Saved Designs Section -->
  <section class="py-10 bg-gray-50">
    <div class="container mx-auto px-4">
      <div class="text-center mb-8">
        <h2 class="text-3xl font-light mb-4">Your Saved Designs</h2>
        <p class="text-gray-600">Continue working on your previously saved designs</p>
      </div>
      
      <div id="saved-designs-container" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <!-- Designs will be loaded here via JavaScript -->
        <div class="text-center py-8">
          <div class="spinner-border text-primary" role="status"></div>
          <p class="mt-2">Loading your saved designs...</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Customizer Section -->
  <section id="customizer" class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
      <div class="text-center mb-16">
        <h2 class="text-4xl font-light mb-4">Design Your Shoe</h2>
        <p class="text-gray-600">Use our interactive customizer to create your perfect pair</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
        <!-- Preview Area -->
        <div class="order-2 lg:order-1">
          <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="aspect-square bg-gray-100 rounded-lg flex items-center justify-center mb-6">
              <img src="/images/Oxford Cap Toe 600.webp" 
                   alt="Shoe Preview" 
                   class="max-w-full max-h-full object-contain"
                   id="shoe-preview">
            </div>
            <div class="text-center">
              <h3 class="text-xl font-medium mb-2" id="preview-title">Oxford Cap Toe</h3>
              <p class="text-gray-600 mb-4" id="preview-description">Classic formal shoe with cap toe detail</p>
              <p class="text-2xl font-bold" id="preview-price">₦85,000</p>
              <p class="text-sm text-gray-500 mb-4">*Custom pricing includes premium materials and handcrafting</p>
              <button id="view-3d-btn" class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800 transition">
                <i class="fas fa-cube mr-2"></i> View in 3D
              </button>
            </div>
          </div>
        </div>

        <!-- Customization Options -->
        <div class="order-1 lg:order-2">
          <div class="space-y-8">
            <!-- Style Selection -->
            <div>
              <h3 class="text-xl font-medium mb-4">1. Choose Style</h3>
              <div class="grid grid-cols-2 gap-4">
                <button class="p-4 border-2 border-gray-300 rounded-lg hover:border-black transition text-left custom-option active" data-style="oxford">
                  <h4 class="font-medium">Oxford</h4>
                  <p class="text-sm text-gray-600">Classic formal</p>
                </button>
                <button class="p-4 border-2 border-gray-300 rounded-lg hover:border-black transition text-left custom-option" data-style="derby">
                  <h4 class="font-medium">Derby</h4>
                  <p class="text-sm text-gray-600">Open lacing</p>
                </button>
                <button class="p-4 border-2 border-gray-300 rounded-lg hover:border-black transition text-left custom-option" data-style="loafer">
                  <h4 class="font-medium">Loafer</h4>
                  <p class="text-sm text-gray-600">Slip-on style</p>
                </button>
                <button class="p-4 border-2 border-gray-300 rounded-lg hover:border-black transition text-left custom-option" data-style="monk">
                  <h4 class="font-medium">Monk Strap</h4>
                  <p class="text-sm text-gray-600">Buckle closure</p>
                </button>
              </div>
            </div>

            <!-- Color Selection -->
            <div>
              <h3 class="text-xl font-medium mb-4">2. Select Color</h3>
              <div class="flex flex-wrap gap-3">
                <button class="w-12 h-12 rounded-full border-4 border-black custom-color active" style="background-color: #000000;" data-color="black" title="Black"></button>
                <button class="w-12 h-12 rounded-full border-4 border-transparent hover:border-gray-400 custom-color" style="background-color: #8B4513;" data-color="brown" title="Brown"></button>
                <button class="w-12 h-12 rounded-full border-4 border-transparent hover:border-gray-400 custom-color" style="background-color: #D2B48C;" data-color="tan" title="Tan"></button>
                <button class="w-12 h-12 rounded-full border-4 border-transparent hover:border-gray-400 custom-color" style="background-color: #722F37;" data-color="burgundy" title="Burgundy"></button>
                <button class="w-12 h-12 rounded-full border-4 border-transparent hover:border-gray-400 custom-color" style="background-color: #1e3a8a;" data-color="navy" title="Navy"></button>
              </div>
            </div>

            <!-- Material Selection -->
            <div>
              <h3 class="text-xl font-medium mb-4">3. Choose Material</h3>
              <div class="space-y-3">
                <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                  <input type="radio" name="material" value="calf" class="mr-3" checked>
                  <div>
                    <h4 class="font-medium">Premium Calf Leather</h4>
                    <p class="text-sm text-gray-600">Smooth, durable finish</p>
                  </div>
                </label>
                <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                  <input type="radio" name="material" value="suede" class="mr-3">
                  <div>
                    <h4 class="font-medium">Suede Leather</h4>
                    <p class="text-sm text-gray-600">Soft, textured finish (+₦10,000)</p>
                  </div>
                </label>
                <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                  <input type="radio" name="material" value="patent" class="mr-3">
                  <div>
                    <h4 class="font-medium">Patent Leather</h4>
                    <p class="text-sm text-gray-600">High-gloss finish (+₦15,000)</p>
                  </div>
                </label>
              </div>
            </div>

            <!-- Size Selection -->
            <div>
              <h3 class="text-xl font-medium mb-4">4. Select Size</h3>
              <div class="grid grid-cols-4 gap-2" id="size-options">
                <?php for ($i = 37; $i <= 47; $i++): ?>
                <button class="border border-gray-300 py-2 hover:border-black transition size-option" data-size="<?= $i ?>"><?= $i ?></button>
                <?php endfor; ?>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-6 border-t">
              <button id="add-to-cart-btn" class="w-full bg-black text-white py-4 rounded-lg hover:bg-gray-800 transition font-medium mb-4">
                Add to Cart - <span id="final-price">₦85,000</span>
              </button>
              <button id="save-design-btn" class="w-full border-2 border-black text-black py-4 rounded-lg hover:bg-black hover:text-white transition font-medium">
                Save Design
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 3D Preview Modal -->
  <div id="preview-3d-modal" class="fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg w-full max-w-4xl max-h-[90vh] overflow-hidden">
      <div class="p-4 border-b flex justify-between items-center">
        <h3 class="text-xl font-medium">3D Preview</h3>
        <button id="close-3d-btn" class="text-gray-500 hover:text-black">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>
      <div class="p-4">
        <canvas id="shoe-3d-canvas" class="w-full" style="height: 500px;"></canvas>
      </div>
    </div>
  </div>

  <!-- Features Section -->
  <section class="py-20 bg-white">
    <div class="container mx-auto px-4">
      <div class="text-center mb-16">
        <h2 class="text-4xl font-light mb-4">Why Choose Custom?</h2>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="text-center">
          <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-ruler text-2xl text-black"></i>
          </div>
          <h3 class="text-xl font-medium mb-3">Perfect Fit</h3>
          <p class="text-gray-600">Made to your exact measurements for unparalleled comfort and fit.</p>
        </div>

        <div class="text-center">
          <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-palette text-2xl text-black"></i>
          </div>
          <h3 class="text-xl font-medium mb-3">Unique Design</h3>
          <p class="text-gray-600">Create a one-of-a-kind shoe that reflects your personal style.</p>
        </div>

        <div class="text-center">
          <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-hammer text-2xl text-black"></i>
          </div>
          <h3 class="text-xl font-medium mb-3">Expert Craftsmanship</h3>
          <p class="text-gray-600">Handcrafted by skilled artisans using traditional techniques.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Added to Cart Modal 
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/added-to-cart-modal.php'); ?>-->

  <script>
    // Pass PHP data to JavaScript
    var designData = <?php echo $designData ? json_encode($designData) : 'null'; ?>;
  </script>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'); ?>

  <!-- Scroll to Top Button -->
  <a href="#" class="btn btn-dark position-fixed bottom-0 end-0 m-4 shadow rounded-circle" style="z-index: 999; width: 45px; height: 45px; display: none;" id="scrollToTop">
    <i class="fas fa-chevron-up"></i>
  </a>


  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/account-modal.php'); ?>  
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/search-modal.php'); ?>  
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php'); ?>
  
  <!-- Three.js for 3D preview -->
  <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/build/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/controls/OrbitControls.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/GLTFLoader.js"></script>
  <script src="/js/shoe-viewer.js"></script>
  <script src="/js/customize.js"></script>
  <script src="/js/customize-add-to-cart.js"></script>
</body>
</html>