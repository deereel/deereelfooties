<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Shoe Care Guide | DeeReel Footies</title>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>
</head>
<body class="bg-background" data-page="care-guide">

  <!-- Main Content -->
  <main>
    <div class="max-w-7xl mx-auto px-4 py-8">
      <!-- Breadcrumb -->
      <div class="mb-8">
        <h1 class="text-3xl font-light mb-2">SHOE CARE GUIDE</h1>
        <div class="flex items-center text-sm text-gray-500">
          <a href="/index.php">Home</a>
          <span class="mx-2">/</span>
          <span>Care Guide</span>
        </div>
      </div>

      <!-- Introduction -->
      <div class="mb-12">
        <div class="bg-gray-50 p-8 rounded-lg">
          <h2 class="text-2xl font-light mb-4">Preserve Your Investment</h2>
          <p class="text-lg mb-4">
            Proper care is essential to maintain the beauty, comfort, and longevity of your DeeReel Footies shoes. 
            With the right techniques and products, your handcrafted footwear can last for decades while developing 
            a beautiful patina that tells the story of your journey.
          </p>
          <p class="text-gray-600">
            Follow our comprehensive care guide below, tailored specifically for each type of footwear in our collection.
          </p>
        </div>
      </div>

      <!-- Quick Navigation -->
      <div class="mb-12">
        <h3 class="text-xl font-medium mb-6">Quick Navigation</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
          <a href="#leather-shoes" class="p-4 border rounded-lg hover:bg-gray-50 text-center">
            <i class="fas fa-shoe-prints text-2xl mb-2 text-blue-600"></i>
            <p class="font-medium">Leather Shoes</p>
          </a>
          <a href="#boots" class="p-4 border rounded-lg hover:bg-gray-50 text-center">
            <i class="fas fa-hiking text-2xl mb-2 text-brown-600"></i>
            <p class="font-medium">Boots</p>
          </a>
          <a href="#sneakers" class="p-4 border rounded-lg hover:bg-gray-50 text-center">
            <i class="fas fa-running text-2xl mb-2 text-green-600"></i>
            <p class="font-medium">Sneakers</p>
          </a>
          <a href="#mules" class="p-4 border rounded-lg hover:bg-gray-50 text-center">
            <i class="fas fa-socks text-2xl mb-2 text-purple-600"></i>
            <p class="font-medium">Mules</p>
          </a>
          <a href="#slippers" class="p-4 border rounded-lg hover:bg-gray-50 text-center">
            <i class="fas fa-home text-2xl mb-2 text-orange-600"></i>
            <p class="font-medium">Slippers</p>
          </a>
        </div>
      </div>

      <!-- General Care Principles -->
      <section class="mb-16">
        <h2 class="text-2xl font-light mb-8">General Care Principles</h2>
        <div class="grid md:grid-cols-2 gap-8">
          <div class="space-y-6">
            <div class="flex items-start space-x-4">
              <div class="bg-blue-100 p-3 rounded-full">
                <i class="fas fa-clock text-blue-600"></i>
              </div>
              <div>
                <h3 class="font-medium mb-2">Rest Between Wears</h3>
                <p class="text-gray-600">Allow at least 24 hours between wears to let moisture evaporate and leather recover its shape.</p>
              </div>
            </div>
            
            <div class="flex items-start space-x-4">
              <div class="bg-green-100 p-3 rounded-full">
                <i class="fas fa-tree text-green-600"></i>
              </div>
              <div>
                <h3 class="font-medium mb-2">Use Shoe Trees</h3>
                <p class="text-gray-600">Cedar shoe trees maintain shape, absorb moisture, and prevent creasing when shoes are not worn.</p>
              </div>
            </div>
            
            <div class="flex items-start space-x-4">
              <div class="bg-yellow-100 p-3 rounded-full">
                <i class="fas fa-brush text-yellow-600"></i>
              </div>
              <div>
                <h3 class="font-medium mb-2">Regular Cleaning</h3>
                <p class="text-gray-600">Remove dirt and dust after each wear with a soft brush or cloth to prevent buildup.</p>
              </div>
            </div>
          </div>
          
          <div class="space-y-6">
            <div class="flex items-start space-x-4">
              <div class="bg-purple-100 p-3 rounded-full">
                <i class="fas fa-tint text-purple-600"></i>
              </div>
              <div>
                <h3 class="font-medium mb-2">Condition Regularly</h3>
                <p class="text-gray-600">Apply leather conditioner every 3-6 months to keep leather supple and prevent cracking.</p>
              </div>
            </div>
            
            <div class="flex items-start space-x-4">
              <div class="bg-red-100 p-3 rounded-full">
                <i class="fas fa-sun text-red-600"></i>
              </div>
              <div>
                <h3 class="font-medium mb-2">Avoid Extreme Conditions</h3>
                <p class="text-gray-600">Keep away from direct heat, excessive moisture, and prolonged sun exposure.</p>
              </div>
            </div>
            
            <div class="flex items-start space-x-4">
              <div class="bg-gray-100 p-3 rounded-full">
                <i class="fas fa-box text-gray-600"></i>
              </div>
              <div>
                <h3 class="font-medium mb-2">Proper Storage</h3>
                <p class="text-gray-600">Store in a cool, dry place with good ventilation. Use dust bags for long-term storage.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Leather Shoes Care -->
      <section id="leather-shoes" class="mb-16">
        <h2 class="text-2xl font-light mb-8">Leather Shoes Care</h2>
        <div class="bg-white border rounded-lg p-8">
          <div class="grid md:grid-cols-2 gap-8">
            <div>
              <h3 class="text-xl font-medium mb-4">Oxford, Derby & Loafers</h3>
              <div class="space-y-4">
                <div>
                  <h4 class="font-medium mb-2">Daily Care:</h4>
                  <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <li>Remove with shoe horn to preserve heel counter</li>
                    <li>Brush with horsehair brush to remove surface dirt</li>
                    <li>Insert cedar shoe trees immediately</li>
                    <li>Allow to air dry naturally</li>
                  </ul>
                </div>
                
                <div>
                  <h4 class="font-medium mb-2">Weekly Care:</h4>
                  <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <li>Deep clean with damp cloth</li>
                    <li>Apply cream polish matching leather color</li>
                    <li>Buff with soft cloth for shine</li>
                    <li>Clean and condition leather soles</li>
                  </ul>
                </div>
              </div>
            </div>
            
            <div>
              <h3 class="text-xl font-medium mb-4">Monk Straps</h3>
              <div class="space-y-4">
                <div>
                  <h4 class="font-medium mb-2">Special Attention:</h4>
                  <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <li>Clean buckles with soft brush</li>
                    <li>Polish around hardware carefully</li>
                    <li>Check strap tension regularly</li>
                    <li>Condition strap leather separately</li>
                  </ul>
                </div>
                
                <div>
                  <h4 class="font-medium mb-2">Monthly Maintenance:</h4>
                  <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <li>Deep condition entire shoe</li>
                    <li>Check buckle mechanism</li>
                    <li>Apply waterproofing treatment</li>
                    <li>Professional cleaning if needed</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Boots Care -->
      <section id="boots" class="mb-16">
        <h2 class="text-2xl font-light mb-8">Boots Care</h2>
        <div class="bg-white border rounded-lg p-8">
          <div class="grid md:grid-cols-2 gap-8">
            <div>
              <h3 class="text-xl font-medium mb-4">Chelsea & Ankle Boots</h3>
              <div class="space-y-4">
                <div>
                  <h4 class="font-medium mb-2">Elastic Care:</h4>
                  <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <li>Clean elastic panels with damp cloth</li>
                    <li>Avoid over-stretching when putting on</li>
                    <li>Apply leather conditioner around elastic</li>
                    <li>Check for wear signs regularly</li>
                  </ul>
                </div>
                
                <div>
                  <h4 class="font-medium mb-2">Leather Maintenance:</h4>
                  <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <li>Use boot trees to maintain shape</li>
                    <li>Condition more frequently due to flexing</li>
                    <li>Pay attention to ankle area stress points</li>
                    <li>Waterproof treatment every 2-3 months</li>
                  </ul>
                </div>
              </div>
            </div>
            
            <div>
              <h3 class="text-xl font-medium mb-4">Lace-up Boots</h3>
              <div class="space-y-4">
                <div>
                  <h4 class="font-medium mb-2">Lacing System:</h4>
                  <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <li>Loosen laces completely before removal</li>
                    <li>Clean eyelets with small brush</li>
                    <li>Replace laces when frayed</li>
                    <li>Use quality waxed cotton laces</li>
                  </ul>
                </div>
                
                <div>
                  <h4 class="font-medium mb-2">Extended Care:</h4>
                  <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <li>Condition tongue and collar padding</li>
                    <li>Check stitching around stress points</li>
                    <li>Apply mink oil for weather protection</li>
                    <li>Professional resoling when needed</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Sneakers Care -->
      <section id="sneakers" class="mb-16">
        <h2 class="text-2xl font-light mb-8">Sneakers Care</h2>
        <div class="bg-white border rounded-lg p-8">
          <div class="grid md:grid-cols-2 gap-8">
            <div>
              <h3 class="text-xl font-medium mb-4">Leather Sneakers</h3>
              <div class="space-y-4">
                <div>
                  <h4 class="font-medium mb-2">Cleaning Process:</h4>
                  <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <li>Remove laces and insoles</li>
                    <li>Clean with leather cleaner</li>
                    <li>Use soft brush for textured areas</li>
                    <li>Air dry away from heat</li>
                  </ul>
                </div>
                
                <div>
                  <h4 class="font-medium mb-2">Protection:</h4>
                  <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <li>Apply sneaker protector spray</li>
                    <li>Use cedar shoe trees</li>
                    <li>Rotate between multiple pairs</li>
                    <li>Store in breathable bags</li>
                  </ul>
                </div>
              </div>
            </div>
            
            <div>
              <h3 class="text-xl font-medium mb-4">Canvas/Fabric Sneakers</h3>
              <div class="space-y-4">
                <div>
                  <h4 class="font-medium mb-2">Washing Instructions:</h4>
                  <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <li>Hand wash with mild detergent</li>
                    <li>Use soft brush for stubborn stains</li>
                    <li>Rinse thoroughly with clean water</li>
                    <li>Stuff with paper to maintain shape while drying</li>
                  </ul>
                </div>
                
                <div>
                  <h4 class="font-medium mb-2">Maintenance:</h4>
                  <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <li>Treat stains immediately</li>
                    <li>Use fabric protector spray</li>
                    <li>Replace insoles regularly</li>
                    <li>Check sole wear patterns</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Mules Care -->
      <section id="mules" class="mb-16">
        <h2 class="text-2xl font-light mb-8">Mules Care</h2>
        <div class="bg-white border rounded-lg p-8">
          <div class="grid md:grid-cols-2 gap-8">
            <div>
              <h3 class="text-xl font-medium mb-4">Leather Mules</h3>
              <div class="space-y-4">
                <div>
                  <h4 class="font-medium mb-2">Daily Care:</h4>
                  <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <li>Wipe clean after each wear</li>
                    <li>Pay attention to heel area</li>
                    <li>Use half shoe trees if available</li>
                    <li>Allow to air dry completely</li>
                  </ul>
                </div>
                
                <div>
                  <h4 class="font-medium mb-2">Special Considerations:</h4>
                  <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <li>Condition heel counter regularly</li>
                    <li>Check for heel slippage wear</li>
                    <li>Apply heel grips if needed</li>
                    <li>Store upright to maintain shape</li>
                  </ul>
                </div>
              </div>
            </div>
            
            <div>
              <h3 class="text-xl font-medium mb-4">Suede Mules</h3>
              <div class="space-y-4">
                <div>
                  <h4 class="font-medium mb-2">Suede Care:</h4>
                  <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <li>Use suede brush to restore nap</li>
                    <li>Apply suede protector spray</li>
                    <li>Remove stains with suede eraser</li>
                    <li>Avoid water and moisture</li>
                  </ul>
                </div>
                
                <div>
                  <h4 class="font-medium mb-2">Restoration:</h4>
                  <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <li>Steam lightly to revive nap</li>
                    <li>Use suede renovator for color</li>
                    <li>Professional cleaning for deep stains</li>
                    <li>Store with suede protector</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Slippers Care -->
      <section id="slippers" class="mb-16">
        <h2 class="text-2xl font-light mb-8">Slippers & Sandals Care</h2>
        <div class="bg-white border rounded-lg p-8">
          <div class="grid md:grid-cols-2 gap-8">
            <div>
              <h3 class="text-xl font-medium mb-4">Leather Slippers</h3>
              <div class="space-y-4">
                <div>
                  <h4 class="font-medium mb-2">Indoor Care:</h4>
                  <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <li>Clean soles regularly</li>
                    <li>Condition leather monthly</li>
                    <li>Use cedar sachets for freshness</li>
                    <li>Rotate between pairs</li>
                  </ul>
                </div>
                
                <div>
                  <h4 class="font-medium mb-2">Comfort Maintenance:</h4>
                  <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <li>Replace insoles when worn</li>
                    <li>Check for loose stitching</li>
                    <li>Maintain sole flexibility</li>
                    <li>Store in dust bags</li>
                  </ul>
                </div>
              </div>
            </div>
            
            <div>
              <h3 class="text-xl font-medium mb-4">Sandals</h3>
              <div class="space-y-4">
                <div>
                  <h4 class="font-medium mb-2">Strap Care:</h4>
                  <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <li>Clean straps after each wear</li>
                    <li>Condition leather straps regularly</li>
                    <li>Check buckles and hardware</li>
                    <li>Adjust fit to prevent stretching</li>
                  </ul>
                </div>
                
                <div>
                  <h4 class="font-medium mb-2">Footbed Maintenance:</h4>
                  <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <li>Clean footbed with damp cloth</li>
                    <li>Use antibacterial spray</li>
                    <li>Allow to dry completely</li>
                    <li>Replace footbed when worn</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Recommended Products -->
      <section class="mb-16">
        <h2 class="text-2xl font-light mb-8">Recommended Care Products</h2>
        <div class="grid md:grid-cols-3 gap-6">
          <div class="bg-white border rounded-lg p-6">
            <h3 class="font-medium mb-4">Essential Kit</h3>
            <ul class="space-y-2 text-gray-600">
              <li>• Horsehair brush</li>
              <li>• Leather conditioner</li>
              <li>• Cream polish</li>
              <li>• Soft cloths</li>
              <li>• Cedar shoe trees</li>
            </ul>
          </div>
          
          <div class="bg-white border rounded-lg p-6">
            <h3 class="font-medium mb-4">Advanced Care</h3>
            <ul class="space-y-2 text-gray-600">
              <li>• Suede brush</li>
              <li>• Waterproofing spray</li>
              <li>• Mink oil</li>
              <li>• Leather cleaner</li>
              <li>• Edge dressing</li>
            </ul>
          </div>
          
          <div class="bg-white border rounded-lg p-6">
            <h3 class="font-medium mb-4">Professional Tools</h3>
            <ul class="space-y-2 text-gray-600">
              <li>• Shoe horn</li>
              <li>• Boot jack</li>
              <li>• Leather repair kit</li>
              <li>• Sole protector</li>
              <li>• Storage bags</li>
            </ul>
          </div>
        </div>
      </section>

      <!-- Professional Services -->
      <section class="mb-16">
        <div class="bg-gray-50 p-8 rounded-lg">
          <h2 class="text-2xl font-light mb-6">Professional Services</h2>
          <div class="grid md:grid-cols-2 gap-8">
            <div>
              <h3 class="font-medium mb-4">When to Seek Professional Help</h3>
              <ul class="space-y-2 text-gray-600">
                <li>• Deep stains or discoloration</li>
                <li>• Sole separation or damage</li>
                <li>• Heel replacement needed</li>
                <li>• Stitching repairs</li>
                <li>• Stretching or fitting issues</li>
              </ul>
            </div>
            
            <div>
              <h3 class="font-medium mb-4">DeeReel Footies Services</h3>
              <p class="text-gray-600 mb-4">
                We offer professional cleaning, repair, and restoration services for all DeeReel Footies products. 
                Contact our customer service team to learn more about our care services.
              </p>
              <a href="/contact.php" class="inline-block bg-black text-white px-6 py-2 hover:bg-gray-800 transition">
                Contact Us
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

  <script>
    // Smooth scrolling for navigation links
    document.addEventListener('DOMContentLoaded', function() {
      const navLinks = document.querySelectorAll('a[href^="#"]');
      
      navLinks.forEach(link => {
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
    });
  </script>
  
</body>
</html>