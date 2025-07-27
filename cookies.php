<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Cookie Policy | DeeReel Footies</title>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>
</head>
<body class="bg-background" data-page="cookies">

  <!-- Main Content -->
  <main>
    <div class="max-w-4xl mx-auto px-4 py-8">
      <!-- Breadcrumb -->
      <div class="mb-8">
        <h1 class="text-3xl font-light mb-2">Cookie Policy</h1>
        <div class="flex items-center text-sm text-gray-500">
          <a href="/index.php">Home</a>
          <span class="mx-2">/</span>
          <span>Cookie Policy</span>
        </div>
      </div>

      <!-- Last Updated -->
      <div class="mb-8 p-4 bg-gray-50 rounded-lg">
        <p class="text-sm text-gray-600">
          <strong>Last Updated:</strong> <?php echo date('F d, Y'); ?>
        </p>
        <p class="text-sm text-gray-600 mt-2">
          This Cookie Policy explains how DeeReel Footies uses cookies and similar technologies on our website.
        </p>
      </div>

      <!-- Cookie Content -->
      <div class="prose max-w-none">
        
        <!-- 1. What Are Cookies -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">1. What Are Cookies?</h2>
          <p class="mb-4 text-gray-700">
            Cookies are small text files that are stored on your device (computer, tablet, or mobile) when you visit a website. 
            They help websites remember information about your visit, such as your preferred language, login status, and other settings.
          </p>
          <p class="mb-4 text-gray-700">
            Cookies make your browsing experience more efficient and personalized by remembering your preferences and 
            providing relevant content.
          </p>
        </section>

        <!-- 2. How We Use Cookies -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">2. How We Use Cookies</h2>
          <p class="mb-4 text-gray-700">
            DeeReel Footies uses cookies to enhance your website experience and provide better services. 
            We use cookies for the following purposes:
          </p>
          <ul class="list-disc list-inside mb-4 text-gray-700 space-y-2">
            <li>Maintaining your login session and account preferences</li>
            <li>Remembering items in your shopping cart</li>
            <li>Personalizing content and product recommendations</li>
            <li>Analyzing website traffic and user behavior</li>
            <li>Improving website functionality and performance</li>
            <li>Providing relevant advertising and marketing content</li>
          </ul>
        </section>

        <!-- 3. Types of Cookies -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">3. Types of Cookies We Use</h2>
          
          <h3 class="text-xl font-medium mb-3">3.1 Essential Cookies</h3>
          <p class="mb-4 text-gray-700">
            These cookies are necessary for the website to function properly. They enable basic functions like 
            page navigation, access to secure areas, and shopping cart functionality.
          </p>
          <div class="bg-blue-50 p-4 rounded-lg mb-4">
            <p class="text-sm text-blue-800">
              <strong>Examples:</strong> Session cookies, authentication cookies, shopping cart cookies
            </p>
          </div>

          <h3 class="text-xl font-medium mb-3">3.2 Performance Cookies</h3>
          <p class="mb-4 text-gray-700">
            These cookies collect information about how visitors use our website, such as which pages are visited most often. 
            This data helps us improve website performance and user experience.
          </p>
          <div class="bg-green-50 p-4 rounded-lg mb-4">
            <p class="text-sm text-green-800">
              <strong>Examples:</strong> Google Analytics cookies, page load time tracking
            </p>
          </div>

          <h3 class="text-xl font-medium mb-3">3.3 Functionality Cookies</h3>
          <p class="mb-4 text-gray-700">
            These cookies remember choices you make to improve your experience, such as language preferences, 
            region selection, and personalized content.
          </p>
          <div class="bg-purple-50 p-4 rounded-lg mb-4">
            <p class="text-sm text-purple-800">
              <strong>Examples:</strong> Language preference cookies, user interface customization
            </p>
          </div>

          <h3 class="text-xl font-medium mb-3">3.4 Marketing Cookies</h3>
          <p class="mb-4 text-gray-700">
            These cookies track your browsing habits to deliver advertisements that are relevant to you and your interests. 
            They also help measure the effectiveness of advertising campaigns.
          </p>
          <div class="bg-orange-50 p-4 rounded-lg mb-4">
            <p class="text-sm text-orange-800">
              <strong>Examples:</strong> Social media cookies, advertising network cookies, retargeting cookies
            </p>
          </div>
        </section>

        <!-- 4. Third-Party Cookies -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">4. Third-Party Cookies</h2>
          <p class="mb-4 text-gray-700">
            We may allow third-party service providers to place cookies on your device to help us analyze website usage 
            and provide better services. These third parties include:
          </p>
          
          <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div class="border rounded-lg p-4">
              <h4 class="font-medium mb-2">Google Analytics</h4>
              <p class="text-sm text-gray-600 mb-2">Helps us understand website traffic and user behavior</p>
              <a href="https://policies.google.com/privacy" target="_blank" class="text-blue-600 text-xs hover:underline">
                Google Privacy Policy
              </a>
            </div>
            
            <div class="border rounded-lg p-4">
              <h4 class="font-medium mb-2">Social Media Platforms</h4>
              <p class="text-sm text-gray-600 mb-2">Enable social sharing and login functionality</p>
              <p class="text-xs text-gray-500">Facebook, Instagram, WhatsApp</p>
            </div>
            
            <div class="border rounded-lg p-4">
              <h4 class="font-medium mb-2">Payment Processors</h4>
              <p class="text-sm text-gray-600 mb-2">Secure payment processing and fraud prevention</p>
              <p class="text-xs text-gray-500">PayPal, Stripe, Local payment gateways</p>
            </div>
            
            <div class="border rounded-lg p-4">
              <h4 class="font-medium mb-2">Customer Support</h4>
              <p class="text-sm text-gray-600 mb-2">Live chat and customer service functionality</p>
              <p class="text-xs text-gray-500">WhatsApp Business, Email services</p>
            </div>
          </div>
        </section>

        <!-- 5. Managing Cookies -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">5. Managing Your Cookie Preferences</h2>
          
          <h3 class="text-xl font-medium mb-3">5.1 Browser Settings</h3>
          <p class="mb-4 text-gray-700">
            You can control and manage cookies through your browser settings. Most browsers allow you to:
          </p>
          <ul class="list-disc list-inside mb-4 text-gray-700 space-y-2">
            <li>View cookies stored on your device</li>
            <li>Delete existing cookies</li>
            <li>Block cookies from specific websites</li>
            <li>Block all cookies</li>
            <li>Set preferences for cookie acceptance</li>
          </ul>

          <h3 class="text-xl font-medium mb-3">5.2 Browser-Specific Instructions</h3>
          <div class="grid md:grid-cols-2 gap-4 mb-6">
            <div class="border rounded-lg p-4">
              <h4 class="font-medium mb-2">Google Chrome</h4>
              <p class="text-sm text-gray-600">Settings → Privacy and Security → Cookies and other site data</p>
            </div>
            <div class="border rounded-lg p-4">
              <h4 class="font-medium mb-2">Mozilla Firefox</h4>
              <p class="text-sm text-gray-600">Options → Privacy & Security → Cookies and Site Data</p>
            </div>
            <div class="border rounded-lg p-4">
              <h4 class="font-medium mb-2">Safari</h4>
              <p class="text-sm text-gray-600">Preferences → Privacy → Manage Website Data</p>
            </div>
            <div class="border rounded-lg p-4">
              <h4 class="font-medium mb-2">Microsoft Edge</h4>
              <p class="text-sm text-gray-600">Settings → Cookies and site permissions → Cookies and site data</p>
            </div>
          </div>

          <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <p class="text-yellow-800">
              <strong>Important:</strong> Disabling cookies may affect website functionality and your user experience. 
              Some features may not work properly without cookies enabled.
            </p>
          </div>
        </section>

        <!-- 6. Cookie Consent -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">6. Cookie Consent</h2>
          <p class="mb-4 text-gray-700">
            When you first visit our website, you may see a cookie consent banner. This allows you to:
          </p>
          <ul class="list-disc list-inside mb-4 text-gray-700 space-y-2">
            <li>Accept all cookies</li>
            <li>Reject non-essential cookies</li>
            <li>Customize your cookie preferences</li>
            <li>Learn more about our cookie usage</li>
          </ul>
          <p class="mb-4 text-gray-700">
            You can change your cookie preferences at any time by clearing your browser cookies and revisiting our website, 
            or by contacting us directly.
          </p>
        </section>

        <!-- 7. Mobile Devices -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">7. Mobile Devices and Apps</h2>
          <p class="mb-4 text-gray-700">
            When you access our website through mobile devices, we may use similar technologies to cookies, 
            such as mobile identifiers and local storage. You can manage these through your device settings:
          </p>
          <ul class="list-disc list-inside mb-4 text-gray-700 space-y-2">
            <li><strong>iOS:</strong> Settings → Privacy → Advertising → Limit Ad Tracking</li>
            <li><strong>Android:</strong> Settings → Google → Ads → Opt out of Ads Personalization</li>
          </ul>
        </section>

        <!-- 8. Data Retention -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">8. Cookie Data Retention</h2>
          <p class="mb-4 text-gray-700">
            Different types of cookies have different lifespans:
          </p>
          <ul class="list-disc list-inside mb-4 text-gray-700 space-y-2">
            <li><strong>Session cookies:</strong> Deleted when you close your browser</li>
            <li><strong>Persistent cookies:</strong> Remain until expiration date or manual deletion</li>
            <li><strong>Essential cookies:</strong> Typically expire after 1 year</li>
            <li><strong>Analytics cookies:</strong> Usually expire after 2 years</li>
            <li><strong>Marketing cookies:</strong> Vary from 30 days to 2 years</li>
          </ul>
        </section>

        <!-- 9. Updates to Cookie Policy -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">9. Updates to This Cookie Policy</h2>
          <p class="mb-4 text-gray-700">
            We may update this Cookie Policy from time to time to reflect changes in our practices or for legal reasons. 
            We will notify you of any significant changes by posting the updated policy on our website and updating 
            the "Last Updated" date.
          </p>
        </section>

        <!-- 10. Contact Information -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">10. Contact Us</h2>
          <p class="mb-4 text-gray-700">
            If you have questions about our use of cookies or this Cookie Policy, please contact us:
          </p>
          <div class="bg-gray-50 p-4 rounded-lg">
            <p class="mb-2"><strong>Email:</strong> deereelfooties@gmail.com</p>
            <p class="mb-2"><strong>Phone:</strong> +234 813 423 5110</p>
            <p class="mb-2"><strong>WhatsApp:</strong> +234 703 186 4772</p>
            <p class="mb-2"><strong>Address:</strong> 2, Oluwa street, off Oke-Ayo street, Ishaga Lagos, Nigeria</p>
            <p class="mb-2"><strong>Business Hours:</strong> Monday - Friday, 9:00 AM - 6:00 PM (WAT)</p>
          </div>
        </section>

      </div>

      <!-- Cookie Preferences Button -->
      <div class="mt-8 p-6 bg-blue-50 rounded-lg text-center">
        <h3 class="text-lg font-medium mb-4">Manage Your Cookie Preferences</h3>
        <p class="text-gray-600 mb-4">
          You can update your cookie preferences at any time by clearing your browser cookies and refreshing this page.
        </p>
        <button onclick="clearCookiesAndRefresh()" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
          Reset Cookie Preferences
        </button>
      </div>

      <!-- Navigation Links -->
      <div class="mt-12 pt-8 border-t">
        <div class="flex flex-wrap justify-center gap-4 text-sm">
          <a href="/privacy.php" class="text-blue-600 hover:underline">Privacy Policy</a>
          <a href="/terms.php" class="text-blue-600 hover:underline">Terms & Conditions</a>
          <a href="/faq.php" class="text-blue-600 hover:underline">FAQ</a>
          <a href="/contact.php" class="text-blue-600 hover:underline">Contact Us</a>
        </div>
      </div>
    </div>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/account-modal.php'); ?>  
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/search-modal.php'); ?>  
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php'); ?>

  <script>
    function clearCookiesAndRefresh() {
      if (confirm('This will clear all cookies and refresh the page. Continue?')) {
        // Clear all cookies for this domain
        document.cookie.split(";").forEach(function(c) { 
          document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/"); 
        });
        
        // Clear localStorage and sessionStorage
        localStorage.clear();
        sessionStorage.clear();
        
        // Refresh the page
        window.location.reload();
      }
    }

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
            entry.target.style.backgroundColor = '#f1f5f9';
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