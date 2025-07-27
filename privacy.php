<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Privacy Policy | DeeReel Footies</title>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>
</head>
<body class="bg-background" data-page="privacy">

  <!-- Main Content -->
  <main>
    <div class="max-w-4xl mx-auto px-4 py-8">
      <!-- Breadcrumb -->
      <div class="mb-8">
        <h1 class="text-3xl font-light mb-2">Privacy Policy</h1>
        <div class="flex items-center text-sm text-gray-500">
          <a href="/index.php">Home</a>
          <span class="mx-2">/</span>
          <span>Privacy Policy</span>
        </div>
      </div>

      <!-- Last Updated -->
      <div class="mb-8 p-4 bg-gray-50 rounded-lg">
        <p class="text-sm text-gray-600">
          <strong>Last Updated:</strong> <?php echo date('F d, Y'); ?>
        </p>
        <p class="text-sm text-gray-600 mt-2">
          This Privacy Policy describes how DeeReel Footies collects, uses, and protects your personal information.
        </p>
      </div>

      <!-- Privacy Content -->
      <div class="prose max-w-none">
        
        <!-- 1. Introduction -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">1. Introduction</h2>
          <p class="mb-4 text-gray-700">
            At DeeReel Footies, we are committed to protecting your privacy and ensuring the security of your personal information. 
            This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website 
            or purchase our products.
          </p>
          <p class="mb-4 text-gray-700">
            By using our website and services, you consent to the data practices described in this policy.
          </p>
        </section>

        <!-- 2. Information We Collect -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">2. Information We Collect</h2>
          
          <h3 class="text-xl font-medium mb-3">2.1 Personal Information</h3>
          <p class="mb-4 text-gray-700">We may collect the following personal information:</p>
          <ul class="list-disc list-inside mb-4 text-gray-700 space-y-2">
            <li>Name and contact information (email, phone number, address)</li>
            <li>Account credentials (username, password)</li>
            <li>Payment information (processed securely through third-party providers)</li>
            <li>Shipping and billing addresses</li>
            <li>Order history and preferences</li>
            <li>Communication preferences</li>
          </ul>

          <h3 class="text-xl font-medium mb-3">2.2 Automatically Collected Information</h3>
          <ul class="list-disc list-inside mb-4 text-gray-700 space-y-2">
            <li>IP address and device information</li>
            <li>Browser type and version</li>
            <li>Pages visited and time spent on our website</li>
            <li>Referring website information</li>
            <li>Cookies and similar tracking technologies</li>
          </ul>
        </section>

        <!-- 3. How We Use Your Information -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">3. How We Use Your Information</h2>
          <p class="mb-4 text-gray-700">We use your information for the following purposes:</p>
          <ul class="list-disc list-inside mb-4 text-gray-700 space-y-2">
            <li>Processing and fulfilling your orders</li>
            <li>Providing customer service and support</li>
            <li>Communicating about your orders and account</li>
            <li>Improving our products and services</li>
            <li>Personalizing your shopping experience</li>
            <li>Sending marketing communications (with your consent)</li>
            <li>Preventing fraud and ensuring security</li>
            <li>Complying with legal obligations</li>
          </ul>
        </section>

        <!-- 4. Information Sharing -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">4. Information Sharing and Disclosure</h2>
          <p class="mb-4 text-gray-700">We do not sell, trade, or rent your personal information to third parties. We may share your information in the following circumstances:</p>
          
          <h3 class="text-xl font-medium mb-3">4.1 Service Providers</h3>
          <ul class="list-disc list-inside mb-4 text-gray-700 space-y-2">
            <li>Payment processors for secure transaction processing</li>
            <li>Shipping companies for order delivery</li>
            <li>Email service providers for communications</li>
            <li>Website hosting and maintenance providers</li>
          </ul>

          <h3 class="text-xl font-medium mb-3">4.2 Legal Requirements</h3>
          <p class="mb-4 text-gray-700">
            We may disclose your information if required by law, court order, or government regulation, 
            or to protect our rights, property, or safety.
          </p>
        </section>

        <!-- 5. Data Security -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">5. Data Security</h2>
          <p class="mb-4 text-gray-700">
            We implement appropriate technical and organizational measures to protect your personal information against 
            unauthorized access, alteration, disclosure, or destruction. These measures include:
          </p>
          <ul class="list-disc list-inside mb-4 text-gray-700 space-y-2">
            <li>SSL encryption for data transmission</li>
            <li>Secure servers and databases</li>
            <li>Regular security assessments</li>
            <li>Access controls and authentication</li>
            <li>Employee training on data protection</li>
          </ul>
        </section>

        <!-- 6. Cookies and Tracking -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">6. Cookies and Tracking Technologies</h2>
          <p class="mb-4 text-gray-700">
            We use cookies and similar technologies to enhance your browsing experience. For detailed information 
            about our cookie usage, please see our <a href="/cookies.php" class="text-blue-600 underline">Cookie Policy</a>.
          </p>
          <p class="mb-4 text-gray-700">
            You can control cookie settings through your browser preferences, but disabling cookies may affect 
            website functionality.
          </p>
        </section>

        <!-- 7. Your Rights -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">7. Your Rights and Choices</h2>
          <p class="mb-4 text-gray-700">You have the following rights regarding your personal information:</p>
          <ul class="list-disc list-inside mb-4 text-gray-700 space-y-2">
            <li><strong>Access:</strong> Request a copy of your personal information</li>
            <li><strong>Correction:</strong> Update or correct inaccurate information</li>
            <li><strong>Deletion:</strong> Request deletion of your personal information</li>
            <li><strong>Portability:</strong> Request transfer of your data</li>
            <li><strong>Opt-out:</strong> Unsubscribe from marketing communications</li>
            <li><strong>Restriction:</strong> Limit how we process your information</li>
          </ul>
          <p class="mb-4 text-gray-700">
            To exercise these rights, please contact us using the information provided below.
          </p>
        </section>

        <!-- 8. Data Retention -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">8. Data Retention</h2>
          <p class="mb-4 text-gray-700">
            We retain your personal information for as long as necessary to fulfill the purposes outlined in this policy, 
            comply with legal obligations, resolve disputes, and enforce our agreements. Specific retention periods include:
          </p>
          <ul class="list-disc list-inside mb-4 text-gray-700 space-y-2">
            <li>Account information: Until account deletion or 3 years of inactivity</li>
            <li>Order information: 7 years for tax and legal compliance</li>
            <li>Marketing communications: Until you unsubscribe</li>
            <li>Website analytics: 2 years</li>
          </ul>
        </section>

        <!-- 9. International Transfers -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">9. International Data Transfers</h2>
          <p class="mb-4 text-gray-700">
            Your information may be transferred to and processed in countries other than Nigeria. We ensure that 
            such transfers comply with applicable data protection laws and implement appropriate safeguards.
          </p>
        </section>

        <!-- 10. Children's Privacy -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">10. Children's Privacy</h2>
          <p class="mb-4 text-gray-700">
            Our services are not intended for children under 13 years of age. We do not knowingly collect personal 
            information from children under 13. If we become aware that we have collected such information, 
            we will take steps to delete it promptly.
          </p>
        </section>

        <!-- 11. Third-Party Links -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">11. Third-Party Links</h2>
          <p class="mb-4 text-gray-700">
            Our website may contain links to third-party websites. We are not responsible for the privacy practices 
            of these external sites. We encourage you to review their privacy policies before providing any information.
          </p>
        </section>

        <!-- 12. Changes to Privacy Policy -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">12. Changes to This Privacy Policy</h2>
          <p class="mb-4 text-gray-700">
            We may update this Privacy Policy from time to time. We will notify you of any material changes by 
            posting the new policy on our website and updating the "Last Updated" date. Your continued use of 
            our services after changes are posted constitutes acceptance of the updated policy.
          </p>
        </section>

        <!-- 13. Contact Information -->
        <section class="mb-8">
          <h2 class="text-2xl font-medium mb-4">13. Contact Us</h2>
          <p class="mb-4 text-gray-700">
            If you have questions about this Privacy Policy or wish to exercise your rights, please contact us:
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

      <!-- Navigation Links -->
      <div class="mt-12 pt-8 border-t">
        <div class="flex flex-wrap justify-center gap-4 text-sm">
          <a href="/terms.php" class="text-blue-600 hover:underline">Terms & Conditions</a>
          <a href="/cookies.php" class="text-blue-600 hover:underline">Cookie Policy</a>
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
  
</body>
</html>