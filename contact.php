<<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Contact Us | DeeReel Footies</title>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>
</head>
<body class="bg-background" data-page="contact">



  <!-- Main Content -->
  <main>
    <div class="max-w-7xl mx-auto px-4 py-8">
      <div class="mb-8">
        <h1 class="text-3xl font-light mb-2">CONTACT US</h1>
        <div class="flex items-center text-sm text-gray-500">
          <a href="/index.php">Home</a>
          <span class="mx-2">/</span>
          <span>Contact</span>
        </div>
      </div>

      <div class="grid md:grid-cols-2 gap-12">
        <!-- Contact Information -->
        <div>
          <h2 class="text-2xl font-light mb-6">GET IN TOUCH</h2>
          <p class="mb-8">
            We're here to help with any questions you may have about our products, services, or your order. Please feel free to reach out to us using any of the methods below.
          </p>
          
          <div class="space-y-6">
            <div>
              <h3 class="font-medium mb-2">CUSTOMER SERVICE</h3>
              <p class="mb-1">Email: <a href="mailto:deereelfooties@gmail.com" class="underline">deereelfooties@gmail.com</a></p>
              <p>Phone: +34 971 50 16 02</p>
              <p class="text-sm text-gray-500 mt-2">Monday to Friday: 9:00 AM - 6:00 PM (CET)</p>
            </div>
            
            <div>
              <h3 class="font-medium mb-2">HEADQUARTERS</h3>
              <address class="not-italic">
                DeeReeL Footies<br>
                Carrer del Quarter, 23<br>
                07300 Inca, Balearic Islands<br>
                Spain
              </address>
            </div>
            
            <div>
              <h3 class="font-medium mb-2">FOLLOW US</h3>
              <div class="flex space-x-4">
                <a href="#" class="hover:text-gray-600">
                  <i class="fab fa-instagram text-lg"></i>
                  <span class="sr-only">Instagram</span>
                </a>
                <a href="#" class="hover:text-gray-600">
                  <i class="fab fa-facebook text-lg"></i>
                  <span class="sr-only">Facebook</span>
                </a>
                <a href="#" class="hover:text-gray-600">
                  <i class="fab fa-twitter text-lg"></i>
                  <span class="sr-only">Twitter</span>
                </a>
                <a href="#" class="hover:text-gray-600">
                  <i class="fab fa-youtube text-lg"></i>
                  <span class="sr-only">YouTube</span>
                </a>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Contact Form -->
        <div>
          <h2 class="text-2xl font-light mb-6">SEND US A MESSAGE</h2>
          <form id="contact-form" class="space-y-6">
            <div>
              <label for="name" class="block mb-1">Name *</label>
              <input
                type="text"
                id="name"
                name="name"
                class="w-full px-4 py-2 border border-gray-300 focus:outline-none focus:border-black"
                required
              />
            </div>
            
            <div>
              <label for="email" class="block mb-1">Email *</label>
              <input
                type="email"
                id="email"
                name="email"
                class="w-full px-4 py-2 border border-gray-300 focus:outline-none focus:border-black"
                required
              />
            </div>
            
            <div>
              <label for="phone" class="block mb-1">Phone</label>
              <input
                type="tel"
                id="phone"
                name="phone"
                class="w-full px-4 py-2 border border-gray-300 focus:outline-none focus:border-black"
              />
            </div>
            
            <div>
              <label for="subject" class="block mb-1">Subject *</label>
              <select
                id="subject"
                name="subject"
                class="w-full px-4 py-2 border border-gray-300 focus:outline-none focus:border-black"
                required
              >
                <option value="">Please select</option>
                <option value="order">Order Inquiry</option>
                <option value="product">Product Information</option>
                <option value="returns">Returns & Exchanges</option>
                <option value="sizing">Sizing & Fit</option>
                <option value="other">Other</option>
              </select>
            </div>
            
            <div>
              <label for="message" class="block mb-1">Message *</label>
              <textarea
                id="message"
                name="message"
                rows="6"
                class="w-full px-4 py-2 border border-gray-300 focus:outline-none focus:border-black"
                required
              ></textarea>
            </div>
            
            <div>
              <div class="flex items-start">
                <input
                  type="checkbox"
                  id="privacy"
                  name="privacy"
                  class="mt-1 mr-2"
                  required
                />
                <label for="privacy" class="text-sm">
                  I have read and agree to the <a href="privacy.php" class="underline">Privacy Policy</a> *
                </label>
              </div>
            </div>
            
            <div>
              <button type="submit" class="bg-black text-white px-8 py-3 hover:bg-gray-800 transition">
                SEND MESSAGE
              </button>
            </div>
          </form>
          
          <div id="form-success" class="mt-6 p-4 bg-green-100 text-green-800 hidden">
            Thank you for your message. We will get back to you as soon as possible.
          </div>
          
          <div id="form-error" class="mt-6 p-4 bg-red-100 text-red-800 hidden">
            There was an error sending your message. Please try again later.
          </div>
        </div>
      </div>
      
      <!-- FAQ Section -->
      <section class="mt-16">
        <h2 class="text-2xl font-light mb-8">FREQUENTLY ASKED QUESTIONS</h2>
        
        <div class="space-y-4">
          <details class="group border p-4">
            <summary class="flex justify-between items-center cursor-pointer">
              <span class="font-medium">How can I track my order?</span>
              <span class="transform group-open:rotate-180 transition-transform">
                <i class="fas fa-chevron-down"></i>
              </span>
            </summary>
            <div class="pt-4 text-gray-600">
              <p>
                Once your order has been shipped, you will receive a confirmation email with a tracking number. You can use this number to track your package on the carrier's website. Alternatively, you can log in to your account on our website and view your order status under "Order History."
              </p>
            </div>
          </details>
          
          <details class="group border p-4">
            <summary class="flex justify-between items-center cursor-pointer">
              <span class="font-medium">What is your return policy?</span>
              <span class="transform group-open:rotate-180 transition-transform">
                <i class="fas fa-chevron-down"></i>
              </span>
            </summary>
            <div class="pt-4 text-gray-600">
              <p>
                We accept returns of unworn shoes in their original packaging within 30 days of delivery. To initiate a return, please contact our customer service team or visit the "Returns" section in your account. Please note that custom or MADE ON ORDER shoes cannot be returned unless there is a manufacturing defect.
              </p>
            </div>
          </details>
          
          <details class="group border p-4">
            <summary class="flex justify-between items-center cursor-pointer">
              <span class="font-medium">How do I determine my shoe size?</span>
              <span class="transform group-open:rotate-180 transition-transform">
                <i class="fas fa-chevron-down"></i>
              </span>
            </summary>
            <div class="pt-4 text-gray-600">
              <p>
                Please refer to our <a href="/size-guide.php" class="underline">Size Guide</a> for detailed information on how to measure your feet and find your perfect size. If you're still unsure, our customer service team will be happy to assist you.
              </p>
            </div>
          </details>
          
          <details class="group border p-4">
            <summary class="flex justify-between items-center cursor-pointer">
              <span class="font-medium">Do you offer shoe repairs or resoling services?</span>
              <span class="transform group-open:rotate-180 transition-transform">
                <i class="fas fa-chevron-down"></i>
              </span>
            </summary>
            <div class="pt-4 text-gray-600">
              <p>
                Yes, we offer repair and resoling services for DeeReeL Footies shoes. Please contact our customer service team for more information on how to send your shoes to us for repair. Alternatively, any skilled cobbler familiar with Goodyear welted shoes can also perform repairs.
              </p>
            </div>
          </details>
          
          <details class="group border p-4">
            <summary class="flex justify-between items-center cursor-pointer">
              <span class="font-medium">How should I care for my DeeReeL Footies shoes?</span>
              <span class="transform group-open:rotate-180 transition-transform">
                <i class="fas fa-chevron-down"></i>
              </span>
            </summary>
            <div class="pt-4 text-gray-600">
              <p>
                Proper care will extend the life of your DeeReeL Footies shoes significantly. We recommend using shoe trees between wears, allowing at least 24 hours of rest between wears, regular cleaning, and conditioning with quality shoe care products. For detailed care instructions, please visit our <a href="care-guide.php" class="underline">Shoe Care Guide</a>.
              </p>
            </div>
          </details>
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


  
</body>
</html>