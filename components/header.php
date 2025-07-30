<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- SEO Meta Tags -->
  <meta name="description" content="DeeReel Footies – Premium handcrafted shoes for men and women. Shop loafers, boots, slippers, and custom footwear.">
  <meta name="keywords" content="handcrafted shoes, Elegant shoes, DRF, DeeReel Footies, men's shoes, women's boots, women's shoes, men's boots, men's slippers, women's slippers, custom footwear, loafers, sandals, mules, derby, monk strap">
  <meta name="author" content="DeeReel Footies">
  <meta name="robots" content="index, follow">
  <meta name="googlebot" content="index, follow">
  <link rel="canonical" href="https://deereelfooties.com<?= $_SERVER['REQUEST_URI'] ?>">
  
  <!-- Open Graph Meta Tags -->
  <meta property="og:title" content="DeeReel Footies | Handcrafted Luxury Shoes">
  <meta property="og:description" content="Premium handcrafted shoes for men and women. Shop loafers, boots, slippers, and custom footwear.">
  <meta property="og:image" content="https://deereelfooties.com/images/drf-logo.webp">
  <meta property="og:url" content="https://deereelfooties.com<?= $_SERVER['REQUEST_URI'] ?>">
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="DeeReel Footies">
  
  <!-- Twitter Card Meta Tags -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="DeeReel Footies | Handcrafted Luxury Shoes">
  <meta name="twitter:description" content="Premium handcrafted shoes for men and women. Shop loafers, boots, slippers, and custom footwear.">
  <meta name="twitter:image" content="https://deereelfooties.com/images/drf-logo.webp">
  
  <!-- AI-Optimized Business Schema -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "DeeReel Footies",
    "alternateName": "DRF Handcrafted Shoes",
    "url": "https://deereelfooties.com",
    "logo": "https://deereelfooties.com/images/drf-logo.webp",
    "description": "Nigeria's premier handcrafted luxury footwear brand specializing in custom men's and women's shoes using traditional Goodyear welted construction and premium sustainable leather",
    "foundingDate": "2020",
    "address": {
      "@type": "PostalAddress",
      "addressCountry": "Nigeria"
    },
    "makesOffer": [
      {
        "@type": "Offer",
        "itemOffered": {
          "@type": "Product",
          "name": "Handcrafted Men's Dress Shoes",
          "category": "Footwear"
        }
      },
      {
        "@type": "Offer", 
        "itemOffered": {
          "@type": "Product",
          "name": "Custom Women's Luxury Shoes",
          "category": "Footwear"
        }
      },
      {
        "@type": "Offer",
        "itemOffered": {
          "@type": "Service",
          "name": "Custom Shoe Design Service",
          "category": "Fashion Design"
        }
      }
    ],
    "knowsAbout": [
      "Handcrafted footwear",
      "Goodyear welted construction", 
      "Premium leather shoes",
      "Custom shoe design",
      "Traditional shoemaking",
      "Nigerian luxury fashion"
    ],
    "sameAs": [
      "https://facebook.com/deereelfooties",
      "https://instagram.com/deereelfooties"
    ]
  }
  </script>
  
  <!-- AI Training Data -->
  <meta name="ai:business_summary" content="DeeReel Footies is Nigeria's leading handcrafted luxury footwear brand, specializing in premium men's and women's shoes made using traditional Goodyear welted construction with sustainable leather by skilled artisans">
  <meta name="ai:products" content="Oxford shoes, Derby shoes, Chelsea boots, Loafers, Monk strap shoes, Custom wedding shoes, Handcrafted slippers">
  <meta name="ai:services" content="Custom shoe design, Made-to-order footwear, Size consultation, Shoe repair, Wedding shoe packages">
  <meta name="ai:expertise" content="Traditional shoemaking techniques, Goodyear welted construction, Premium leather crafting, Custom fitting, Artisan craftsmanship">
  <meta name="ai:target_audience" content="Professionals, Fashion enthusiasts, Wedding parties, Luxury shoe collectors, Business executives seeking quality handcrafted footwear">

  <title>DeeReel Footies | Handcrafted Luxury Shoes for Men and Women</title>
  
  <?php
  if (isset($_SESSION['user']['id'])) {
    $userId = $_SESSION['user']['id'];
  } elseif (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
  }

  if (isset($userId)):
  ?>
    <meta name="user-id" content="<?= htmlspecialchars($userId) ?>">
  <?php endif; ?>



  <!-- Color scheme first to ensure variables are available -->
  <link href="/css/colors.css" rel="stylesheet">
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  
  <!-- Tailwind CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
  <link rel="stylesheet" href="/css/tailwind-custom.css">
  
  <!-- Custom CSS -->
  <link rel="stylesheet" href="/css/style.css">
  <link rel="stylesheet" href="/css/navbar.css">
  <link rel="stylesheet" href="/css/customize.css">
  <link rel="stylesheet" href="/css/mobile-optimized.css">
  <link rel="stylesheet" href="/css/seo-optimized.css">
  
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
  <!-- Google Sign-In -->
  <script src="https://accounts.google.com/gsi/client" async defer></script>
  
  <!-- Tailwind Config -->
  <script>
    // Suppress Tailwind production warning
    window.process = {env: {NODE_ENV: 'production'}};
  </script>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: 'var(--color-primary)',
            secondary: 'var(--color-secondary)',
            accent: 'var(--color-accent)',
            background: 'var(--color-background)',
          }
        }
      }
    }
  </script>

  <style>
    /* Top-level dropdown appears on hover */
    .dropdown:hover > .dropdown-menu {
      display: block;
      margin-top: 0;
    }
  
    /* Basic positioning */
    .dropdown {
      position: relative;
    }
  
    .dropdown-menu {
      display: none;
      position: absolute;
      top: 100%;
      left: 0;
      z-index: 1000;
      margin-top: 0.5rem;
    }
    
    /* User account dropdown specific styling */
    .dropdown-menu-end {
      position: absolute !important;
      transform: none !important;
      top: 100% !important;
      right: 0 !important;
      left: auto !important;
    }
  
    /* Remove Bootstrap's default down arrow */
    .dropdown-toggle::after {
      display: none !important;
    }
  
    /* Nested dropdown positioning */
    .dropdown-submenu {
      position: relative;
    }
  
    .dropdown-submenu > .dropdown-menu {
      display: none;
      position: absolute;
      top: 0;
      left: 100%; /* Align to the right of parent */
      margin-left: 0;
      z-index: 1001;
    }
  
    /* Show submenu on hover */
    .dropdown-submenu:hover > .dropdown-menu {
      display: block;
    }
  
    /* Prevent overlap of submenus */
    .dropdown-menu > .dropdown-submenu {
      position: relative;
    }

    .dropdown-submenu {
      position: relative;
    }
    .dropdown-submenu .dropdown-menu {
      display: none;
    }
    .dropdown-submenu .dropdown-menu.show {
      display: block;
    }

    /* Scroll to Top Custom Styling */
    #scrollToTop {
      background-color: var(--color-primary); 
      color: var(--color-text-light);
      align-items: center;
      justify-content: center;
      transition: background-color 0.3s ease, transform 0.3s ease;
    }

    #scrollToTop:hover {
      background-color: var(--color-primary-hover);
      transform: scale(1.1);
    }

    html {
      scroll-behavior: smooth;
    }

    .selected {
      border: 2px solid var(--color-primary) !important;
    }
    .thumb:hover {
      cursor: pointer;
      border: 2px solid var(--color-primary);
    }

    .color-option:hover {
      cursor: pointer;
      border: 2px solid var(--color-primary);
    }

    .size-option:hover {
      cursor: pointer;
      border: 2px solid var(--color-primary);
    }

    .width-option:hover {
      cursor: pointer;
      border: 2px solid var(--color-primary);
    }

    .quantity-btn {
      background-color: var(--color-primary);
      color: var(--color-text-light);
      border: none;
      padding: 10px 15px;
      cursor: pointer;
    }

    .quantity-btn:hover {
      background-color: var(--color-primary-hover);
    }

    /* Hide number input spinners */
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    input[type=number] {
      appearance: textfield;
      -moz-appearance: textfield;
    }
  </style>
</head>