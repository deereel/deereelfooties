<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';
?>

<!DOCTYPE html>
<html>
<head>
  <title>Made on Order | DeeReel Footies</title>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>
</head>

<body class="bg-background" data-page="moo">
  


  <!-- Main Content -->
  <main>
    <!-- Hero Section -->
    <section class="relative w-full h-[400px]">
      <img src="/images/moo.webp" alt="DRF MADE ON ORDER" class="object-cover w-full h-full">
      <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
        <div class="text-center text-white max-w-3xl px-4">
          <h1 class="text-4xl md:text-5xl font-light mb-4">MADE ON ORDER</h1>
          <p class="text-lg md:text-xl">Bespoke shoemaking for the discerning customer</p>
        </div>
      </div>
    </section>

    <!-- MOO Introduction -->
    <section class="py-16 px-4">
      <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-3xl font-light mb-6">THE ULTIMATE SHOEMAKING EXPERIENCE</h2>
        <p class="mb-8">
          Our Made on Order service offers a unique experience for those who desire shoes crafted specifically to
          their requirements. With our master shoemakers' guidance, create footwear that perfectly balances
          aesthetics, comfort, and individuality.
        </p>
        <div class="grid md:grid-cols-3 gap-8">
          <div>
            <div class="w-16 h-16 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-2xl">1</span>
            </div>
            <h3 class="text-xl mb-2">CONSULTATION</h3>
            <p class="text-gray-600">Meet with our expert craftsmen to discuss your vision</p>
          </div>
          <div>
            <div class="w-16 h-16 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-2xl">2</span>
            </div>
            <h3 class="text-xl mb-2">DESIGN</h3>
            <p class="text-gray-600">Select exclusive materials and personalized details</p>
          </div>
          <div>
            <div class="w-16 h-16 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-2xl">3</span>
            </div>
            <h3 class="text-xl mb-2">CRAFTING</h3>
            <p class="text-gray-600">Your shoes are meticulously handcrafted to your specifications</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Process Section -->
    <section class="py-16 bg-neutral-100">
      <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-light text-center mb-12">THE MoO PROCESS</h2>
        <div class="grid md:grid-cols-2 gap-12 items-start">
          <div class="flex justify-center">
            <img src="/images/moo-process.webp" alt="MoO Process" class="max-w-full h-auto rounded-lg shadow-lg">
          </div>
          <div>
            <div class="space-y-8">
              <div>
                <h3 class="text-xl font-medium mb-2">INITIAL CONSULTATION</h3>
                <p class="text-gray-600">
                  Begin with a detailed discussion about your preferences, style, and specific requirements. Our
                  experts will guide you through available options.
                </p>
              </div>
              <div>
                <h3 class="text-xl font-medium mb-2">MATERIAL SELECTION</h3>
                <p class="text-gray-600">
                  Choose from our extensive collection of premium leathers, exclusive to our MoO service. Each
                  material is carefully selected for quality and character.
                </p>
              </div>
              <div>
                <h3 class="text-xl font-medium mb-2">FITTING AND MEASUREMENTS</h3>
                <p class="text-gray-600">
                  Precise measurements ensure your shoes will fit perfectly. We account for the unique characteristics
                  of your feet.
                </p>
              </div>
              <div>
                <h3 class="text-xl font-medium mb-2">HANDCRAFTING</h3>
                <p class="text-gray-600">
                  Our master artisans in Mallorca bring your vision to life using traditional techniques passed down
                  through generations.
                </p>
              </div>
            </div>
            <div class="mt-8">
              <button id="book-consultation-btn" class="border border-black px-6 py-2 inline-block hover:bg-black hover:text-white transition">
                BOOK A CONSULTATION
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Materials -->
    <section class="py-16 px-4">
      <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl font-light text-center mb-12">EXCLUSIVE MATERIALS</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
          <div class="text-center">
            <div class="relative aspect-square overflow-hidden mb-4">
              <img src="/images/material-1.webp" alt="Shell Cordovan" class="object-cover w-full h-full">
            </div>
            <h3 class="text-lg">SHELL CORDOVAN</h3>
          </div>
          <div class="text-center">
            <div class="relative aspect-square overflow-hidden mb-4">
              <img src="/images/material-2.webp" alt="Museum Calf" class="object-cover w-full h-full">
            </div>
            <h3 class="text-lg">MUSEUM CALF</h3>
          </div>
          <div class="text-center">
            <div class="relative aspect-square overflow-hidden mb-4">
              <img src="/images/material-3.webp" alt="Box Calf" class="object-cover w-full h-full">
            </div>
            <h3 class="text-lg">BOX CALF</h3>
          </div>
          <div class="text-center">
            <div class="relative aspect-square overflow-hidden mb-4">
              <img src="/images/material-4.webp" alt="Suede" class="object-cover w-full h-full">
            </div>
            <h3 class="text-lg">SUEDE</h3>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="py-16 bg-neutral-900 text-white text-center">
      <div class="max-w-3xl mx-auto px-4">
        <h2 class="text-3xl font-light mb-6">CREATE YOUR MASTERPIECE</h2>
        <p class="mb-8">
          Experience the luxury of truly bespoke footwear. Delivery time for MADE ON ORDER shoes is approximately 8-10
          weeks.
        </p>
        <button id="schedule-appointment-btn" class="bg-white text-black px-8 py-3 inline-block hover:bg-gray-200 transition">
          SCHEDULE APPOINTMENT
        </button>
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

  <!-- Consultation Modal -->
  <div id="consultation-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg max-w-md w-full mx-4 p-6">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-medium">Book Consultation</h3>
        <button id="close-modal" class="text-gray-500 hover:text-black">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <form id="consultation-form">
        <div class="mb-4">
          <label class="block text-sm font-medium mb-2">Full Name</label>
          <input type="text" id="client-name" class="w-full border border-gray-300 rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
          <label class="block text-sm font-medium mb-2">Email</label>
          <input type="email" id="client-email" class="w-full border border-gray-300 rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
          <label class="block text-sm font-medium mb-2">Phone</label>
          <input type="tel" id="client-phone" class="w-full border border-gray-300 rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
          <label class="block text-sm font-medium mb-2">Preferred Date</label>
          <input type="date" id="preferred-date" class="w-full border border-gray-300 rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
          <label class="block text-sm font-medium mb-2">Message</label>
          <textarea id="client-message" rows="3" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Tell us about your vision..."></textarea>
        </div>
        <div class="flex gap-3">
          <button type="submit" class="flex-1 bg-black text-white py-2 rounded hover:bg-gray-800 transition">
            Submit Request
          </button>
          <button type="button" id="cancel-modal" class="flex-1 border border-gray-300 py-2 rounded hover:bg-gray-50 transition">
            Cancel
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Cache buster - v2.0
    document.addEventListener('DOMContentLoaded', function() {
      const modal = document.getElementById('consultation-modal');
      const bookBtn = document.getElementById('book-consultation-btn');
      const scheduleBtn = document.getElementById('schedule-appointment-btn');
      const closeBtn = document.getElementById('close-modal');
      const cancelBtn = document.getElementById('cancel-modal');
      const form = document.getElementById('consultation-form');
      
      // Open modal
      [bookBtn, scheduleBtn].forEach(btn => {
        if (btn) {
          btn.addEventListener('click', () => {
            modal.classList.remove('hidden');
          });
        }
      });
      
      // Close modal
      [closeBtn, cancelBtn].forEach(btn => {
        if (btn) {
          btn.addEventListener('click', () => {
            modal.classList.add('hidden');
          });
        }
      });
      
      // Close on outside click
      modal.addEventListener('click', (e) => {
        if (e.target === modal) {
          modal.classList.add('hidden');
        }
      });
      
      // Handle form submission
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = {
          name: document.getElementById('client-name').value,
          email: document.getElementById('client-email').value,
          phone: document.getElementById('client-phone').value,
          date: document.getElementById('preferred-date').value,
          message: document.getElementById('client-message').value
        };
        
        // Create WhatsApp message
        const message = `Hello DeeReel Footies! 👋

I would like to book a Made on Order consultation:

👤 Name: ${formData.name}
📧 Email: ${formData.email}
📱 Phone: ${formData.phone}
📅 Preferred Date: ${formData.date}
💬 Message: ${formData.message}

Looking forward to creating my custom shoes!`;
        
        // Open WhatsApp
        window.open(`https://wa.me/2348123456789?text=${encodeURIComponent(message)}`, '_blank');
        console.log('WhatsApp opened with message:', message);
        
        // Close modal and show success
        modal.classList.add('hidden');
        alert('Thank you! Your consultation request has been sent via WhatsApp. We will contact you within 24 hours.');
        form.reset();
      });
      
      // Set minimum date to today
      const today = new Date().toISOString().split('T')[0];
      document.getElementById('preferred-date').setAttribute('min', today);
    });
  </script>
  
</body>
</html>