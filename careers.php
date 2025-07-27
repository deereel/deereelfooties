<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Careers | DeeReel Footies</title>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>
</head>
<body class="bg-background" data-page="careers">

  <!-- Hero Section -->
  <section class="relative bg-gray-900 text-white py-20">
    <div class="absolute inset-0 bg-black opacity-60"></div>
    <div class="relative max-w-7xl mx-auto px-4 text-center">
      <h1 class="text-4xl md:text-6xl font-light mb-6">Join Our Team</h1>
      <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto">
        Be part of Nigeria's premier handcrafted footwear company. 
        Build your career while preserving traditional craftsmanship.
      </p>
      <a href="#open-positions" class="bg-white text-black px-8 py-3 hover:bg-gray-100 transition">
        View Open Positions
      </a>
    </div>
  </section>

  <!-- Main Content -->
  <main>
    <div class="max-w-7xl mx-auto px-4 py-16">
      
      <!-- Why Work With Us -->
      <section class="mb-20">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-light mb-4">Why Work at DeeReel Footies?</h2>
          <p class="text-lg text-gray-600 max-w-2xl mx-auto">
            Join a passionate team dedicated to preserving traditional craftsmanship while building a modern, sustainable business.
          </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
          <div class="text-center">
            <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-hammer text-2xl text-blue-600"></i>
            </div>
            <h3 class="text-xl font-medium mb-3">Master Your Craft</h3>
            <p class="text-gray-600">
              Learn from experienced artisans and develop your skills in traditional shoemaking techniques 
              passed down through generations.
            </p>
          </div>
          
          <div class="text-center">
            <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-users text-2xl text-green-600"></i>
            </div>
            <h3 class="text-xl font-medium mb-3">Collaborative Culture</h3>
            <p class="text-gray-600">
              Work in a supportive environment where creativity is encouraged and every team member's 
              contribution is valued and recognized.
            </p>
          </div>
          
          <div class="text-center">
            <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-chart-line text-2xl text-purple-600"></i>
            </div>
            <h3 class="text-xl font-medium mb-3">Growth Opportunities</h3>
            <p class="text-gray-600">
              Advance your career with training programs, skill development workshops, and opportunities 
              to take on leadership roles as we expand.
            </p>
          </div>
        </div>
      </section>

      <!-- Company Culture -->
      <section class="mb-20">
        <div class="bg-gray-50 rounded-lg p-8">
          <div class="grid md:grid-cols-2 gap-8 items-center">
            <div>
              <h2 class="text-3xl font-light mb-4">Our Culture & Values</h2>
              <p class="text-lg text-gray-600 mb-6">
                At DeeReel Footies, we believe in creating an environment where traditional craftsmanship 
                meets modern innovation, and where every team member can thrive.
              </p>
              
              <div class="space-y-4">
                <div class="flex items-start">
                  <i class="fas fa-check-circle text-green-600 mt-1 mr-3"></i>
                  <div>
                    <h4 class="font-medium">Quality First</h4>
                    <p class="text-gray-600">We never compromise on quality in our products or our work environment.</p>
                  </div>
                </div>
                
                <div class="flex items-start">
                  <i class="fas fa-check-circle text-green-600 mt-1 mr-3"></i>
                  <div>
                    <h4 class="font-medium">Continuous Learning</h4>
                    <p class="text-gray-600">We invest in our team's growth through training and skill development.</p>
                  </div>
                </div>
                
                <div class="flex items-start">
                  <i class="fas fa-check-circle text-green-600 mt-1 mr-3"></i>
                  <div>
                    <h4 class="font-medium">Work-Life Balance</h4>
                    <p class="text-gray-600">We respect personal time and promote a healthy work-life balance.</p>
                  </div>
                </div>
              </div>
            </div>
            
            <div>
              <div class="bg-gray-200 aspect-[4/3] rounded-lg flex items-center justify-center">
                <div class="text-center">
                  <i class="fas fa-building text-6xl text-gray-400 mb-4"></i>
                  <p class="text-gray-500">Our Lagos Workshop</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Benefits -->
      <section class="mb-20">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-light mb-4">Benefits & Perks</h2>
          <p class="text-lg text-gray-600">We take care of our team members with comprehensive benefits</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div class="bg-white rounded-lg shadow-sm border p-6 text-center">
            <i class="fas fa-heartbeat text-3xl text-red-500 mb-4"></i>
            <h3 class="font-medium mb-2">Health Insurance</h3>
            <p class="text-sm text-gray-600">Comprehensive health coverage for you and your family</p>
          </div>
          
          <div class="bg-white rounded-lg shadow-sm border p-6 text-center">
            <i class="fas fa-graduation-cap text-3xl text-blue-500 mb-4"></i>
            <h3 class="font-medium mb-2">Skills Training</h3>
            <p class="text-sm text-gray-600">Regular workshops and training programs to enhance your skills</p>
          </div>
          
          <div class="bg-white rounded-lg shadow-sm border p-6 text-center">
            <i class="fas fa-calendar-alt text-3xl text-green-500 mb-4"></i>
            <h3 class="font-medium mb-2">Flexible Hours</h3>
            <p class="text-sm text-gray-600">Flexible working hours to maintain work-life balance</p>
          </div>
          
          <div class="bg-white rounded-lg shadow-sm border p-6 text-center">
            <i class="fas fa-gift text-3xl text-purple-500 mb-4"></i>
            <h3 class="font-medium mb-2">Employee Discounts</h3>
            <p class="text-sm text-gray-600">Special discounts on all DeeReel Footies products</p>
          </div>
        </div>
      </section>

      <!-- Open Positions -->
      <section id="open-positions" class="mb-20">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-light mb-4">Current Openings</h2>
          <p class="text-lg text-gray-600">Join our growing team in Lagos, Nigeria</p>
        </div>

        <div class="space-y-6">
          <!-- Shoe Craftsman -->
          <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
              <div class="mb-4 md:mb-0">
                <h3 class="text-xl font-medium mb-2">Shoe Craftsman</h3>
                <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                  <span><i class="fas fa-map-marker-alt mr-1"></i>Lagos, Nigeria</span>
                  <span><i class="fas fa-clock mr-1"></i>Full-time</span>
                  <span><i class="fas fa-layer-group mr-1"></i>Production</span>
                </div>
                <p class="text-gray-600 mt-2">
                  Experienced craftsman to join our production team. Knowledge of traditional shoemaking 
                  techniques and leather working required.
                </p>
              </div>
              <div class="flex flex-col sm:flex-row gap-2">
                <button onclick="showJobDetails('craftsman')" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                  View Details
                </button>
                <a href="mailto:deereelfooties@gmail.com?subject=Application: Shoe Craftsman" 
                   class="border border-blue-600 text-blue-600 px-4 py-2 rounded text-center hover:bg-blue-600 hover:text-white transition">
                  Apply Now
                </a>
              </div>
            </div>
          </div>

          <!-- Sales Associate -->
          <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
              <div class="mb-4 md:mb-0">
                <h3 class="text-xl font-medium mb-2">Sales Associate</h3>
                <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                  <span><i class="fas fa-map-marker-alt mr-1"></i>Lagos, Nigeria</span>
                  <span><i class="fas fa-clock mr-1"></i>Full-time</span>
                  <span><i class="fas fa-handshake mr-1"></i>Sales</span>
                </div>
                <p class="text-gray-600 mt-2">
                  Customer-focused individual to help customers find the perfect footwear. 
                  Experience in retail sales and passion for quality products preferred.
                </p>
              </div>
              <div class="flex flex-col sm:flex-row gap-2">
                <button onclick="showJobDetails('sales')" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                  View Details
                </button>
                <a href="mailto:deereelfooties@gmail.com?subject=Application: Sales Associate" 
                   class="border border-blue-600 text-blue-600 px-4 py-2 rounded text-center hover:bg-blue-600 hover:text-white transition">
                  Apply Now
                </a>
              </div>
            </div>
          </div>

          <!-- Digital Marketing Specialist -->
          <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
              <div class="mb-4 md:mb-0">
                <h3 class="text-xl font-medium mb-2">Digital Marketing Specialist</h3>
                <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                  <span><i class="fas fa-map-marker-alt mr-1"></i>Lagos, Nigeria</span>
                  <span><i class="fas fa-clock mr-1"></i>Full-time</span>
                  <span><i class="fas fa-bullhorn mr-1"></i>Marketing</span>
                </div>
                <p class="text-gray-600 mt-2">
                  Creative marketer to manage our online presence and social media. Experience with 
                  Instagram, TikTok, and e-commerce marketing required.
                </p>
              </div>
              <div class="flex flex-col sm:flex-row gap-2">
                <button onclick="showJobDetails('marketing')" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                  View Details
                </button>
                <a href="mailto:deereelfooties@gmail.com?subject=Application: Digital Marketing Specialist" 
                   class="border border-blue-600 text-blue-600 px-4 py-2 rounded text-center hover:bg-blue-600 hover:text-white transition">
                  Apply Now
                </a>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Application Process -->
      <section class="mb-20">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-light mb-4">Application Process</h2>
          <p class="text-lg text-gray-600">Simple steps to join our team</p>
        </div>

        <div class="grid md:grid-cols-4 gap-8">
          <div class="text-center">
            <div class="bg-blue-600 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4 font-bold">1</div>
            <h3 class="font-medium mb-2">Apply</h3>
            <p class="text-sm text-gray-600">Send your CV and cover letter via email</p>
          </div>
          
          <div class="text-center">
            <div class="bg-blue-600 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4 font-bold">2</div>
            <h3 class="font-medium mb-2">Review</h3>
            <p class="text-sm text-gray-600">We review your application within 5 business days</p>
          </div>
          
          <div class="text-center">
            <div class="bg-blue-600 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4 font-bold">3</div>
            <h3 class="font-medium mb-2">Interview</h3>
            <p class="text-sm text-gray-600">Phone or in-person interview at our Lagos workshop</p>
          </div>
          
          <div class="text-center">
            <div class="bg-blue-600 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4 font-bold">4</div>
            <h3 class="font-medium mb-2">Welcome</h3>
            <p class="text-sm text-gray-600">Join our team and start your journey with us</p>
          </div>
        </div>
      </section>

      <!-- Contact Section -->
      <section class="text-center">
        <div class="bg-black text-white rounded-lg p-12">
          <h2 class="text-3xl font-light mb-4">Ready to Join Our Team?</h2>
          <p class="text-xl mb-8 opacity-90">
            Don't see a position that fits? We're always looking for talented individuals.
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="mailto:deereelfooties@gmail.com?subject=Career Inquiry" 
               class="bg-white text-black px-8 py-3 hover:bg-gray-100 transition">
              Send Your CV
            </a>
            <a href="https://wa.me/2347031864772?text=Hello! I'm interested in career opportunities at DeeReel Footies" 
               target="_blank" class="border border-white px-8 py-3 hover:bg-white hover:text-black transition">
              <i class="fab fa-whatsapp mr-2"></i>WhatsApp Us
            </a>
          </div>
          
          <div class="mt-8 pt-8 border-t border-gray-700">
            <p class="text-gray-300 mb-2">Visit our workshop:</p>
            <p class="text-white">2, Oluwa street, off Oke-Ayo street, Ishaga Lagos, Nigeria</p>
            <p class="text-gray-300 mt-2">Monday - Friday: 9:00 AM - 6:00 PM</p>
          </div>
        </div>
      </section>
    </div>
  </main>

  <!-- Job Details Modal -->
  <div id="jobModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
      <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
          <div class="flex justify-between items-center mb-4">
            <h3 id="modalTitle" class="text-2xl font-medium"></h3>
            <button onclick="closeJobModal()" class="text-gray-500 hover:text-gray-700">
              <i class="fas fa-times text-xl"></i>
            </button>
          </div>
          <div id="modalContent"></div>
        </div>
      </div>
    </div>
  </div>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/account-modal.php'); ?>  
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/search-modal.php'); ?>  
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php'); ?>

  <script>
    const jobDetails = {
      craftsman: {
        title: 'Shoe Craftsman',
        content: `
          <h4 class="font-medium mb-3">Job Description</h4>
          <p class="text-gray-600 mb-4">We are seeking an experienced shoe craftsman to join our production team in Lagos. You will be responsible for creating high-quality handcrafted footwear using traditional techniques.</p>
          
          <h4 class="font-medium mb-3">Requirements</h4>
          <ul class="list-disc list-inside text-gray-600 mb-4 space-y-1">
            <li>3+ years experience in shoe making or leather crafting</li>
            <li>Knowledge of Goodyear welt construction preferred</li>
            <li>Attention to detail and quality craftsmanship</li>
            <li>Ability to work with hand tools and machinery</li>
            <li>Team player with good communication skills</li>
          </ul>
          
          <h4 class="font-medium mb-3">Responsibilities</h4>
          <ul class="list-disc list-inside text-gray-600 mb-4 space-y-1">
            <li>Cut and prepare leather materials</li>
            <li>Assemble shoe components using traditional techniques</li>
            <li>Perform quality control checks</li>
            <li>Maintain tools and equipment</li>
            <li>Train junior craftsmen</li>
          </ul>
          
          <div class="mt-6 pt-4 border-t">
            <a href="mailto:deereelfooties@gmail.com?subject=Application: Shoe Craftsman" 
               class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 transition">
              Apply for this Position
            </a>
          </div>
        `
      },
      sales: {
        title: 'Sales Associate',
        content: `
          <h4 class="font-medium mb-3">Job Description</h4>
          <p class="text-gray-600 mb-4">Join our sales team to help customers discover the perfect handcrafted footwear. You'll provide exceptional customer service and product knowledge.</p>
          
          <h4 class="font-medium mb-3">Requirements</h4>
          <ul class="list-disc list-inside text-gray-600 mb-4 space-y-1">
            <li>1+ years retail sales experience</li>
            <li>Excellent communication and interpersonal skills</li>
            <li>Passion for quality footwear and craftsmanship</li>
            <li>Customer-focused mindset</li>
            <li>Basic computer skills</li>
          </ul>
          
          <h4 class="font-medium mb-3">Responsibilities</h4>
          <ul class="list-disc list-inside text-gray-600 mb-4 space-y-1">
            <li>Assist customers with product selection</li>
            <li>Provide product information and sizing guidance</li>
            <li>Process sales transactions</li>
            <li>Maintain store presentation</li>
            <li>Build customer relationships</li>
          </ul>
          
          <div class="mt-6 pt-4 border-t">
            <a href="mailto:deereelfooties@gmail.com?subject=Application: Sales Associate" 
               class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 transition">
              Apply for this Position
            </a>
          </div>
        `
      },
      marketing: {
        title: 'Digital Marketing Specialist',
        content: `
          <h4 class="font-medium mb-3">Job Description</h4>
          <p class="text-gray-600 mb-4">Lead our digital marketing efforts to grow our online presence and reach new customers. You'll manage social media, content creation, and online campaigns.</p>
          
          <h4 class="font-medium mb-3">Requirements</h4>
          <ul class="list-disc list-inside text-gray-600 mb-4 space-y-1">
            <li>2+ years digital marketing experience</li>
            <li>Proficiency in Instagram, TikTok, and Facebook</li>
            <li>Content creation and photography skills</li>
            <li>Understanding of e-commerce platforms</li>
            <li>Analytics and reporting experience</li>
          </ul>
          
          <h4 class="font-medium mb-3">Responsibilities</h4>
          <ul class="list-disc list-inside text-gray-600 mb-4 space-y-1">
            <li>Manage social media accounts and content</li>
            <li>Create engaging visual and video content</li>
            <li>Run digital advertising campaigns</li>
            <li>Analyze performance metrics</li>
            <li>Collaborate with influencers and partners</li>
          </ul>
          
          <div class="mt-6 pt-4 border-t">
            <a href="mailto:deereelfooties@gmail.com?subject=Application: Digital Marketing Specialist" 
               class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 transition">
              Apply for this Position
            </a>
          </div>
        `
      }
    };

    function showJobDetails(jobKey) {
      const job = jobDetails[jobKey];
      document.getElementById('modalTitle').textContent = job.title;
      document.getElementById('modalContent').innerHTML = job.content;
      document.getElementById('jobModal').classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function closeJobModal() {
      document.getElementById('jobModal').classList.add('hidden');
      document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    document.getElementById('jobModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeJobModal();
      }
    });

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
    });
  </script>
  
</body>
</html>