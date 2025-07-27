<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Frequently Asked Questions | DeeReel Footies</title>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>
</head>
<body class="bg-background" data-page="faq">

  <!-- Main Content -->
  <main>
    <div class="max-w-4xl mx-auto px-4 py-8">
      <!-- Breadcrumb -->
      <div class="mb-8">
        <h1 class="text-3xl font-light mb-2">FREQUENTLY ASKED QUESTIONS</h1>
        <div class="flex items-center text-sm text-gray-500">
          <a href="/index.php">Home</a>
          <span class="mx-2">/</span>
          <span>FAQ</span>
        </div>
      </div>

      <!-- Introduction -->
      <div class="mb-12">
        <div class="bg-gray-50 p-6 rounded-lg">
          <p class="text-lg mb-4">
            Find answers to the most commonly asked questions about DeeReel Footies shoes, 
            ordering, sizing, care, and more. If you can't find what you're looking for, 
            please don't hesitate to <a href="/contact.php" class="underline">contact us</a>.
          </p>
        </div>
      </div>

      <!-- FAQ Categories -->
      <div class="mb-8">
        <h2 class="text-xl font-medium mb-4">Browse by Category</h2>
        <div class="flex flex-wrap gap-2">
          <button onclick="showCategory('all')" class="category-btn active px-4 py-2 bg-black text-white rounded">All</button>
          <button onclick="showCategory('ordering')" class="category-btn px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Ordering</button>
          <button onclick="showCategory('sizing')" class="category-btn px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Sizing & Fit</button>
          <button onclick="showCategory('care')" class="category-btn px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Care & Maintenance</button>
          <button onclick="showCategory('shipping')" class="category-btn px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Shipping & Returns</button>
          <button onclick="showCategory('products')" class="category-btn px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Products</button>
        </div>
      </div>

      <!-- FAQ Accordion -->
      <div class="space-y-4">
        
        <!-- Ordering Questions -->
        <details class="group border border-gray-200 rounded-lg p-4 faq-item" data-category="ordering">
          <summary class="flex justify-between items-center cursor-pointer">
            <span class="font-medium">How do I place an order?</span>
            <span class="transform group-open:rotate-180 transition-transform">
              <i class="fas fa-chevron-down"></i>
            </span>
          </summary>
          <div class="pt-4 text-gray-600">
            <p>You can place an order by browsing our collection, selecting your desired shoes, choosing your size, and adding them to your cart. Then proceed to checkout, fill in your shipping information, and complete payment. You can order as a guest or create an account for faster future purchases.</p>
          </div>
        </details>

        <details class="group border border-gray-200 rounded-lg p-4 faq-item" data-category="ordering">
          <summary class="flex justify-between items-center cursor-pointer">
            <span class="font-medium">Can I modify or cancel my order after placing it?</span>
            <span class="transform group-open:rotate-180 transition-transform">
              <i class="fas fa-chevron-down"></i>
            </span>
          </summary>
          <div class="pt-4 text-gray-600">
            <p>Orders can be modified or cancelled within 2 hours of placement. After this time, your order enters our production queue and cannot be changed. Please contact our customer service team immediately if you need to make changes.</p>
          </div>
        </details>

        <details class="group border border-gray-200 rounded-lg p-4 faq-item" data-category="ordering">
          <summary class="flex justify-between items-center cursor-pointer">
            <span class="font-medium">What payment methods do you accept?</span>
            <span class="transform group-open:rotate-180 transition-transform">
              <i class="fas fa-chevron-down"></i>
            </span>
          </summary>
          <div class="pt-4 text-gray-600">
            <p>We accept bank transfers, mobile money payments, and cash on delivery (for select locations). Payment instructions will be provided during checkout. For international orders, we also accept PayPal and major credit cards.</p>
          </div>
        </details>

        <!-- Sizing Questions -->
        <details class="group border border-gray-200 rounded-lg p-4 faq-item" data-category="sizing">
          <summary class="flex justify-between items-center cursor-pointer">
            <span class="font-medium">How do I determine my correct shoe size?</span>
            <span class="transform group-open:rotate-180 transition-transform">
              <i class="fas fa-chevron-down"></i>
            </span>
          </summary>
          <div class="pt-4 text-gray-600">
            <p>Please refer to our detailed <a href="/size-guide.php" class="underline">Size Guide</a> which includes foot measurement instructions and size conversion charts. We recommend measuring your feet in the evening when they're at their largest. If you're between sizes, we generally recommend sizing up for comfort.</p>
          </div>
        </details>

        <details class="group border border-gray-200 rounded-lg p-4 faq-item" data-category="sizing">
          <summary class="flex justify-between items-center cursor-pointer">
            <span class="font-medium">Do DeeReel Footies shoes run true to size?</span>
            <span class="transform group-open:rotate-180 transition-transform">
              <i class="fas fa-chevron-down"></i>
            </span>
          </summary>
          <div class="pt-4 text-gray-600">
            <p>Our shoes generally run true to size, but fit can vary slightly between different styles. Oxfords and Derbys tend to run true to size, while boots may run slightly larger. Loafers typically fit snugly initially but will stretch with wear. Check individual product pages for specific sizing notes.</p>
          </div>
        </details>

        <details class="group border border-gray-200 rounded-lg p-4 faq-item" data-category="sizing">
          <summary class="flex justify-between items-center cursor-pointer">
            <span class="font-medium">What if my shoes don't fit properly?</span>
            <span class="transform group-open:rotate-180 transition-transform">
              <i class="fas fa-chevron-down"></i>
            </span>
          </summary>
          <div class="pt-4 text-gray-600">
            <p>If your shoes don't fit properly and haven't been worn outside, you can return them within 30 days for an exchange or refund. We also offer professional stretching services for minor fit adjustments. Contact our customer service team for assistance.</p>
          </div>
        </details>

        <!-- Care Questions -->
        <details class="group border border-gray-200 rounded-lg p-4 faq-item" data-category="care">
          <summary class="flex justify-between items-center cursor-pointer">
            <span class="font-medium">How should I care for my leather shoes?</span>
            <span class="transform group-open:rotate-180 transition-transform">
              <i class="fas fa-chevron-down"></i>
            </span>
          </summary>
          <div class="pt-4 text-gray-600">
            <p>Use cedar shoe trees after each wear, allow 24 hours rest between wears, brush regularly with horsehair brush, and condition every 3-6 months. For detailed care instructions, visit our <a href="/care-guide.php" class="underline">Shoe Care Guide</a>.</p>
          </div>
        </details>

        <details class="group border border-gray-200 rounded-lg p-4 faq-item" data-category="care">
          <summary class="flex justify-between items-center cursor-pointer">
            <span class="font-medium">Can I wear my leather shoes in the rain?</span>
            <span class="transform group-open:rotate-180 transition-transform">
              <i class="fas fa-chevron-down"></i>
            </span>
          </summary>
          <div class="pt-4 text-gray-600">
            <p>While our leather shoes can handle light moisture, we don't recommend wearing them in heavy rain. If they do get wet, stuff with newspaper, let them dry naturally away from heat, and condition once completely dry. Consider applying waterproofing treatment for better protection.</p>
          </div>
        </details>

        <details class="group border border-gray-200 rounded-lg p-4 faq-item" data-category="care">
          <summary class="flex justify-between items-center cursor-pointer">
            <span class="font-medium">How often should I polish my shoes?</span>
            <span class="transform group-open:rotate-180 transition-transform">
              <i class="fas fa-chevron-down"></i>
            </span>
          </summary>
          <div class="pt-4 text-gray-600">
            <p>For regular wear, polish your shoes every 5-10 wears or weekly if worn daily. Use cream polish for nourishment and color restoration, and wax polish for high shine and protection. Always clean before polishing and use colors that match your leather.</p>
          </div>
        </details>

        <!-- Shipping Questions -->
        <details class="group border border-gray-200 rounded-lg p-4 faq-item" data-category="shipping">
          <summary class="flex justify-between items-center cursor-pointer">
            <span class="font-medium">How long does shipping take?</span>
            <span class="transform group-open:rotate-180 transition-transform">
              <i class="fas fa-chevron-down"></i>
            </span>
          </summary>
          <div class="pt-4 text-gray-600">
            <p><strong>For shoes currently available in warehouse:</strong></p>
            <ul class="mt-2 ml-4 list-disc">
              <li>Within Lagos: 1-2 business days</li>
              <li>Other Nigerian states: 3-5 business days</li>
              <li>International shipping: 7-14 business days</li>
            </ul>
            <p class="mt-4"><strong>For newly made shoes:</strong> Additional 7-10 working days production time before shipping.</p>
            <p class="mt-2"><strong>Made-to-order shoes:</strong> Additional 2-3 weeks production time before shipping.</p>
          </div>
        </details>

        <details class="group border border-gray-200 rounded-lg p-4 faq-item" data-category="shipping">
          <summary class="flex justify-between items-center cursor-pointer">
            <span class="font-medium">Do you offer free shipping?</span>
            <span class="transform group-open:rotate-180 transition-transform">
              <i class="fas fa-chevron-down"></i>
            </span>
          </summary>
          <div class="pt-4 text-gray-600">
            <p>Yes! We offer free shipping based on your location:</p>
            <ul class="mt-2 ml-4 list-disc">
              <li><strong>Lagos:</strong> Free shipping on orders over ₦150,000</li>
              <li><strong>Other Nigerian states:</strong> Free shipping on orders over ₦250,000</li>
              <li><strong>African countries:</strong> Free shipping on orders over ₦600,000</li>
              <li><strong>Other countries:</strong> Free shipping on orders over ₦800,000</li>
            </ul>
          </div>
        </details>

        <details class="group border border-gray-200 rounded-lg p-4 faq-item" data-category="shipping">
          <summary class="flex justify-between items-center cursor-pointer">
            <span class="font-medium">What is your return policy?</span>
            <span class="transform group-open:rotate-180 transition-transform">
              <i class="fas fa-chevron-down"></i>
            </span>
          </summary>
          <div class="pt-4 text-gray-600">
            <p>We accept returns of unworn shoes in original packaging within 30 days of delivery. Custom and Made-to-Order shoes cannot be returned unless there's a manufacturing defect. Return shipping costs are covered by the customer unless the return is due to our error.</p>
          </div>
        </details>

        <!-- Product Questions -->
        <details class="group border border-gray-200 rounded-lg p-4 faq-item" data-category="products">
          <summary class="flex justify-between items-center cursor-pointer">
            <span class="font-medium">What makes DeeReel Footies shoes special?</span>
            <span class="transform group-open:rotate-180 transition-transform">
              <i class="fas fa-chevron-down"></i>
            </span>
          </summary>
          <div class="pt-4 text-gray-600">
            <p>Our shoes are handcrafted using traditional techniques, premium materials, and Goodyear welt construction. Each pair is made with attention to detail, ensuring durability, comfort, and timeless style. We use full-grain leather and offer both ready-to-wear and custom options.</p>
          </div>
        </details>

        <details class="group border border-gray-200 rounded-lg p-4 faq-item" data-category="products">
          <summary class="flex justify-between items-center cursor-pointer">
            <span class="font-medium">What's the difference between your shoe categories?</span>
            <span class="transform group-open:rotate-180 transition-transform">
              <i class="fas fa-chevron-down"></i>
            </span>
          </summary>
          <div class="pt-4 text-gray-600">
            <p><strong>Oxfords:</strong> Formal closed-lacing shoes. <strong>Derbys:</strong> Open-lacing, more casual. <strong>Loafers:</strong> Slip-on style without laces. <strong>Boots:</strong> Ankle-high or higher coverage. <strong>Mules:</strong> Backless slip-on shoes. <strong>Sneakers:</strong> Casual athletic-inspired footwear.</p>
          </div>
        </details>

        <details class="group border border-gray-200 rounded-lg p-4 faq-item" data-category="products">
          <summary class="flex justify-between items-center cursor-pointer">
            <span class="font-medium">Do you offer custom or made-to-order shoes?</span>
            <span class="transform group-open:rotate-180 transition-transform">
              <i class="fas fa-chevron-down"></i>
            </span>
          </summary>
          <div class="pt-4 text-gray-600">
            <p>Yes! We offer Made-to-Order (MOO) services where you can customize colors, materials, and certain design elements. We also provide full bespoke services for completely custom shoes. Visit our <a href="/moo.php" class="underline">Made on Order</a> page or contact us for more information.</p>
          </div>
        </details>

        <details class="group border border-gray-200 rounded-lg p-4 faq-item" data-category="products">
          <summary class="flex justify-between items-center cursor-pointer">
            <span class="font-medium">Are your shoes suitable for wide or narrow feet?</span>
            <span class="transform group-open:rotate-180 transition-transform">
              <i class="fas fa-chevron-down"></i>
            </span>
          </summary>
          <div class="pt-4 text-gray-600">
            <p>Our standard shoes are made on medium-width lasts suitable for most feet. For wide or narrow feet, we offer stretching services and can accommodate special width requirements through our Made-to-Order service. Contact us to discuss your specific needs.</p>
          </div>
        </details>

        <!-- General Questions -->
        <details class="group border border-gray-200 rounded-lg p-4 faq-item" data-category="general">
          <summary class="flex justify-between items-center cursor-pointer">
            <span class="font-medium">Do you have a physical store I can visit?</span>
            <span class="transform group-open:rotate-180 transition-transform">
              <i class="fas fa-chevron-down"></i>
            </span>
          </summary>
          <div class="pt-4 text-gray-600">
            <p>Yes, our showroom is located at 2, Oluwa street, off Oke-Ayo street, Ishaga Lagos. You can visit to see our shoes in person, get fitted, and speak with our craftsmen. We recommend calling ahead to schedule an appointment for the best service.</p>
          </div>
        </details>

        <details class="group border border-gray-200 rounded-lg p-4 faq-item" data-category="general">
          <summary class="flex justify-between items-center cursor-pointer">
            <span class="font-medium">Do you offer shoe repair services?</span>
            <span class="transform group-open:rotate-180 transition-transform">
              <i class="fas fa-chevron-down"></i>
            </span>
          </summary>
          <div class="pt-4 text-gray-600">
            <p>Yes, we provide repair and restoration services for DeeReel Footies shoes including resoling, heel replacement, stitching repairs, and refinishing. We also service shoes from other quality brands. Contact us for a repair quote and timeline.</p>
          </div>
        </details>

        <details class="group border border-gray-200 rounded-lg p-4 faq-item" data-category="general">
          <summary class="flex justify-between items-center cursor-pointer">
            <span class="font-medium">How can I stay updated on new releases and promotions?</span>
            <span class="transform group-open:rotate-180 transition-transform">
              <i class="fas fa-chevron-down"></i>
            </span>
          </summary>
          <div class="pt-4 text-gray-600">
            <p>Follow us on social media (<a href="https://instagram.com/deereelfooties" class="underline">Instagram</a>, <a href="https://www.tiktok.com/@deereel.footies" class="underline">TikTok</a>), subscribe to our newsletter, or join our WhatsApp updates at <a href="https://wa.me/2347031864772" class="underline">07031864772</a>. We regularly share new arrivals, care tips, and exclusive offers.</p>
          </div>
        </details>

        <details class="group border border-gray-200 rounded-lg p-4 faq-item" data-category="general">
          <summary class="flex justify-between items-center cursor-pointer">
            <span class="font-medium">What if I have a question not covered here?</span>
            <span class="transform group-open:rotate-180 transition-transform">
              <i class="fas fa-chevron-down"></i>
            </span>
          </summary>
          <div class="pt-4 text-gray-600">
            <p>Please don't hesitate to <a href="/contact.php" class="underline">contact us</a>! You can reach us via email at deereelfooties@gmail.com, WhatsApp at 07031864772, or phone at +2348134235110. Our customer service team is here to help with any questions or concerns.</p>
          </div>
        </details>

      </div>

      <!-- Contact CTA -->
      <div class="mt-16 bg-gray-50 p-8 rounded-lg text-center">
        <h2 class="text-2xl font-light mb-4">Still Have Questions?</h2>
        <p class="text-gray-600 mb-6">
          Our customer service team is here to help. Get in touch with us through any of the channels below.
        </p>
        <div class="flex flex-wrap justify-center gap-4">
          <a href="/contact.php" class="bg-black text-white px-6 py-3 hover:bg-gray-800 transition">
            Contact Us
          </a>
          <a href="https://wa.me/2347031864772" target="_blank" class="bg-green-600 text-white px-6 py-3 hover:bg-green-700 transition">
            <i class="fab fa-whatsapp mr-2"></i>WhatsApp
          </a>
          <a href="mailto:deereelfooties@gmail.com" class="bg-blue-600 text-white px-6 py-3 hover:bg-blue-700 transition">
            <i class="fas fa-envelope mr-2"></i>Email
          </a>
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

  <script>
    // Category filtering functionality
    function showCategory(category) {
      const faqItems = document.querySelectorAll('.faq-item');
      const categoryBtns = document.querySelectorAll('.category-btn');
      
      // Update button styles
      categoryBtns.forEach(btn => {
        btn.classList.remove('active', 'bg-black', 'text-white');
        btn.classList.add('bg-gray-200', 'text-gray-700');
      });
      
      event.target.classList.remove('bg-gray-200', 'text-gray-700');
      event.target.classList.add('active', 'bg-black', 'text-white');
      
      // Show/hide FAQ items
      faqItems.forEach(item => {
        if (category === 'all' || item.dataset.category === category) {
          item.style.display = 'block';
        } else {
          item.style.display = 'none';
        }
      });
    }

    // Search functionality
    document.addEventListener('DOMContentLoaded', function() {
      // Add search box
      const searchHTML = `
        <div class="mb-6">
          <div class="relative">
            <input type="text" id="faq-search" placeholder="Search FAQs..." 
                   class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:border-black">
            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
          </div>
        </div>
      `;
      
      const categorySection = document.querySelector('.mb-8');
      categorySection.insertAdjacentHTML('afterend', searchHTML);
      
      // Search functionality
      const searchInput = document.getElementById('faq-search');
      searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const faqItems = document.querySelectorAll('.faq-item');
        
        faqItems.forEach(item => {
          const question = item.querySelector('summary span').textContent.toLowerCase();
          const answer = item.querySelector('.pt-4').textContent.toLowerCase();
          
          if (question.includes(searchTerm) || answer.includes(searchTerm)) {
            item.style.display = 'block';
          } else {
            item.style.display = 'none';
          }
        });
      });
    });
  </script>
  
</body>
</html>