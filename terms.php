<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Terms & Conditions | DeeReel Footies</title>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>
</head>
<body class="bg-background" data-page="terms">

  <!-- Main Content -->
  <main>
    <div class="max-w-4xl mx-auto px-4 py-8">
      <!-- Breadcrumb -->
      <div class="mb-8">
        <h1 class="text-3xl font-light mb-2">Terms & Conditions</h1>
        <div class="flex items-center text-sm text-gray-500">
          <a href="/index.php">Home</a>
          <span class="mx-2">/</span>
          <span>Terms & Conditions</span>
        </div>
      </div>

      <!-- Last Updated -->
      <div class="mb-8 p-4 bg-gray-50 rounded-lg">
        <p class="text-sm text-gray-600">
          <strong>Last Updated:</strong> <?php echo date('F d, Y'); ?>
        </p>
        <p class="text-sm text-gray-600 mt-2">
          Please read these Terms and Conditions carefully before using our website or purchasing our products.
        </p>
      </div>

      <!-- Terms Content -->
      <div class="prose max-w-none">
        
        <!-- 1. Agreement -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">1. Agreement to Terms</h2>
          <p class="mb-4 text-gray-700">
            By accessing and using the DeeReel Footies website (www.deereelfooties.com) and purchasing our products, 
            you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to 
            abide by the above, please do not use this service.
          </p>
          <p class="mb-4 text-gray-700">
            These Terms and Conditions apply to all visitors, users, and others who access or use our website and services.
          </p>
        </section>

        <!-- 2. Company Information -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">2. Company Information</h2>
          <div class="bg-gray-50 p-4 rounded-lg">
            <p class="mb-2"><strong>Business Name:</strong> DeeReel Footies</p>
            <p class="mb-2"><strong>Address:</strong> 2, Oluwa street, off Oke-Ayo street, Ishaga Lagos, Nigeria</p>
            <p class="mb-2"><strong>Email:</strong> deereelfooties@gmail.com</p>
            <p class="mb-2"><strong>Phone:</strong> +234 813 423 5110</p>
            <p class="mb-2"><strong>WhatsApp:</strong> +234 703 186 4772</p>
          </div>
        </section>

        <!-- 3. Products and Services -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">3. Products and Services</h2>
          <p class="mb-4 text-gray-700">
            DeeReel Footies specializes in handcrafted leather footwear including shoes, boots, sneakers, mules, 
            and slippers for both men and women. We offer:
          </p>
          <ul class="list-disc list-inside mb-4 text-gray-700 space-y-2">
            <li>Ready-to-wear footwear from our standard collection</li>
            <li>Made-to-Order (MOO) customization services</li>
            <li>Bespoke shoe crafting services</li>
            <li>Shoe repair and restoration services</li>
            <li>Shoe care products and accessories</li>
          </ul>
        </section>

        <!-- 4. Ordering and Payment -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">4. Ordering and Payment</h2>
          
          <h3 class="text-xl font-medium mb-3">4.1 Order Placement</h3>
          <p class="mb-4 text-gray-700">
            Orders can be placed through our website, by phone, WhatsApp, or in-person at our Lagos workshop. 
            All orders are subject to acceptance and availability.
          </p>

          <h3 class="text-xl font-medium mb-3">4.2 Payment Methods</h3>
          <ul class="list-disc list-inside mb-4 text-gray-700 space-y-2">
            <li>Bank transfer (preferred method)</li>
            <li>Mobile money payments</li>
            <li>Cash on delivery (Lagos only, subject to conditions)</li>
            <li>International payments via PayPal or credit card</li>
          </ul>

          <h3 class="text-xl font-medium mb-3">4.3 Pricing</h3>
          <p class="mb-4 text-gray-700">
            All prices are displayed in Nigerian Naira (₦) and are inclusive of applicable taxes. 
            Prices are subject to change without notice. International customers may be subject 
            to additional customs duties and taxes.
          </p>
        </section>

        <!-- 5. Production and Delivery -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">5. Production and Delivery</h2>
          
          <h3 class="text-xl font-medium mb-3">5.1 Production Times</h3>
          <ul class="list-disc list-inside mb-4 text-gray-700 space-y-2">
            <li><strong>In-stock items:</strong> Ready for shipping within 1-2 business days</li>
            <li><strong>Newly made shoes:</strong> 7-10 working days production time</li>
            <li><strong>Made-to-Order:</strong> 2-3 weeks production time</li>
            <li><strong>Bespoke shoes:</strong> 4-6 weeks production time</li>
          </ul>

          <h3 class="text-xl font-medium mb-3">5.2 Shipping Times</h3>
          <ul class="list-disc list-inside mb-4 text-gray-700 space-y-2">
            <li><strong>Lagos:</strong> 1-2 business days</li>
            <li><strong>Other Nigerian states:</strong> 3-5 business days</li>
            <li><strong>International:</strong> 7-14 business days</li>
          </ul>

          <h3 class="text-xl font-medium mb-3">5.3 Free Shipping</h3>
          <ul class="list-disc list-inside mb-4 text-gray-700 space-y-2">
            <li>Lagos: Orders over ₦150,000</li>
            <li>Other Nigerian states: Orders over ₦250,000</li>
            <li>African countries: Orders over ₦600,000</li>
            <li>Other countries: Orders over ₦800,000</li>
          </ul>
        </section>

        <!-- 6. Returns and Exchanges -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">6. Returns and Exchanges</h2>
          
          <h3 class="text-xl font-medium mb-3">6.1 Return Policy</h3>
          <p class="mb-4 text-gray-700">
            We accept returns of unworn shoes in their original packaging within 30 days of delivery. 
            The customer is responsible for return shipping costs unless the return is due to our error.
          </p>

          <h3 class="text-xl font-medium mb-3">6.2 Non-Returnable Items</h3>
          <ul class="list-disc list-inside mb-4 text-gray-700 space-y-2">
            <li>Custom or Made-to-Order shoes (unless manufacturing defect)</li>
            <li>Bespoke shoes (unless manufacturing defect)</li>
            <li>Shoes that have been worn outside</li>
            <li>Shoes damaged by misuse or normal wear</li>
          </ul>

          <h3 class="text-xl font-medium mb-3">6.3 Refund Process</h3>
          <p class="mb-4 text-gray-700">
            Refunds will be processed within 7-14 business days after we receive and inspect the returned items. 
            Refunds will be issued to the original payment method.
          </p>
        </section>

        <!-- 7. Warranty -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">7. Warranty and Quality Guarantee</h2>
          <p class="mb-4 text-gray-700">
            We warrant our shoes against manufacturing defects for 6 months from the date of purchase. 
            This warranty covers defects in materials and workmanship under normal use conditions.
          </p>
          <p class="mb-4 text-gray-700">
            The warranty does not cover normal wear and tear, damage from misuse, or damage from 
            improper care and maintenance.
          </p>
        </section>

        <!-- 8. Intellectual Property -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">8. Intellectual Property</h2>
          <p class="mb-4 text-gray-700">
            All content on this website, including but not limited to text, graphics, logos, images, 
            and software, is the property of DeeReel Footies and is protected by copyright and 
            other intellectual property laws.
          </p>
          <p class="mb-4 text-gray-700">
            You may not reproduce, distribute, or create derivative works from our content without 
            express written permission.
          </p>
        </section>

        <!-- 9. Privacy -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">9. Privacy and Data Protection</h2>
          <p class="mb-4 text-gray-700">
            Your privacy is important to us. Please review our Privacy Policy to understand how we 
            collect, use, and protect your personal information.
          </p>
          <p class="mb-4 text-gray-700">
            By using our services, you consent to the collection and use of your information as 
            described in our Privacy Policy.
          </p>
        </section>

        <!-- 10. Limitation of Liability -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">10. Limitation of Liability</h2>
          <p class="mb-4 text-gray-700">
            DeeReel Footies shall not be liable for any indirect, incidental, special, consequential, 
            or punitive damages, including without limitation, loss of profits, data, use, goodwill, 
            or other intangible losses.
          </p>
          <p class="mb-4 text-gray-700">
            Our total liability for any claim arising out of or relating to these terms shall not 
            exceed the amount paid by you for the specific product or service.
          </p>
        </section>

        <!-- 11. Force Majeure -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">11. Force Majeure</h2>
          <p class="mb-4 text-gray-700">
            We shall not be liable for any failure or delay in performance due to circumstances 
            beyond our reasonable control, including but not limited to acts of God, natural disasters, 
            war, terrorism, strikes, or government regulations.
          </p>
        </section>

        <!-- 12. Governing Law -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">12. Governing Law</h2>
          <p class="mb-4 text-gray-700">
            These Terms and Conditions shall be governed by and construed in accordance with the 
            laws of the Federal Republic of Nigeria. Any disputes arising from these terms shall 
            be subject to the exclusive jurisdiction of Nigerian courts.
          </p>
        </section>

        <!-- 13. Changes to Terms -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">13. Changes to Terms</h2>
          <p class="mb-4 text-gray-700">
            We reserve the right to modify these Terms and Conditions at any time. Changes will be 
            effective immediately upon posting on our website. Your continued use of our services 
            after changes are posted constitutes acceptance of the modified terms.
          </p>
        </section>

        <!-- 14. Contact Information -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">14. Contact Information</h2>
          <p class="mb-4 text-gray-700">
            If you have any questions about these Terms and Conditions, please contact us:
          </p>
          <div class="bg-gray-50 p-4 rounded-lg">
            <p class="mb-2"><strong>Email:</strong> deereelfooties@gmail.com</p>
            <p class="mb-2"><strong>Phone:</strong> +234 813 423 5110</p>
            <p class="mb-2"><strong>WhatsApp:</strong> +234 703 186 4772</p>
            <p class="mb-2"><strong>Address:</strong> 2, Oluwa street, off Oke-Ayo street, Ishaga Lagos, Nigeria</p>
            <p class="mb-2"><strong>Business Hours:</strong> Monday - Friday, 9:00 AM - 6:00 PM (WAT)</p>
          </div>
        </section>

        <!-- Acceptance -->
        <section class="mb-8">
          <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
            <p class="text-blue-800">
              <strong>By using our website and services, you acknowledge that you have read, 
              understood, and agree to be bound by these Terms and Conditions.</strong>
            </p>
          </div>
        </section>

      </div>

      <!-- Navigation Links -->
      <div class="mt-12 pt-8 border-t">
        <div class="flex flex-wrap justify-center gap-4 text-sm">
          <a href="/privacy.php" class="text-blue-600 hover:underline">Privacy Policy</a>
          <a href="/faq.php" class="text-blue-600 hover:underline">FAQ</a>
          <a href="/contact.php" class="text-blue-600 hover:underline">Contact Us</a>
          <a href="/returns.php" class="text-blue-600 hover:underline">Returns & Exchanges</a>
          <a href="/shipping.php" class="text-blue-600 hover:underline">Shipping Information</a>
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
    // Add smooth scrolling for any anchor links
    document.addEventListener('DOMContentLoaded', function() {
      // Highlight sections on scroll
      const sections = document.querySelectorAll('section');
      const observerOptions = {
        threshold: 0.3,
        rootMargin: '0px 0px -50px 0px'
      };

      const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.style.backgroundColor = '#f8fafc';
            entry.target.style.padding = '1rem';
            entry.target.style.borderRadius = '0.5rem';
            entry.target.style.transition = 'all 0.3s ease';
            
            setTimeout(() => {
              entry.target.style.backgroundColor = 'transparent';
              entry.target.style.padding = '0';
            }, 2000);
          }
        });
      }, observerOptions);

      sections.forEach(section => {
        observer.observe(section);
      });
    });
  </script>
  
</body>
</html>