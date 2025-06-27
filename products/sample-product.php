<?php
require_once '../auth/db.php';
require_once '../components/product-functions.php';

// Sample product data
$product = [
    'id' => 'sample-product',
    'name' => 'Oxford Cap Toe 600',
    'price' => 450,
    'description' => '<p>Handcrafted leather shoes made with the finest materials. These Oxford Cap Toe shoes feature a sleek design perfect for formal occasions.</p>
                     <p>Features:</p>
                     <ul>
                       <li>Full grain leather upper</li>
                       <li>Leather sole</li>
                       <li>Hand-stitched details</li>
                       <li>Cushioned insole for comfort</li>
                     </ul>',
    'image' => '/images/oxford-cap-toe-600.webp',
    'gallery' => [
        '/images/oxford-cap-toe-600-1.webp',
        '/images/oxford-cap-toe-600-2.webp',
        '/images/oxford-cap-toe-600-3.webp'
    ],
    'colors' => ['Black', 'Brown', 'Tan'],
    'sizes' => ['40', '41', '42', '43', '44', '45'],
    'widths' => ['D', 'E', 'EE']
];

// Include header
include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php');
?>

<body class="bg-background">
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>

  <main>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/product-template.php'); ?>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/account-modal.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php'); ?>
</body>
</html>