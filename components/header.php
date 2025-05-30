<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- SEO Meta Tags -->
  <meta name="description" content="DeeReel Footies – Premium handcrafted shoes for men and women. Shop loafers, boots, slippers, and custom footwear.">
  <meta name="keywords" content="handcrafted shoes, Elegant shoes, DRF, DeeReel Footies, men's shoes, women's boots, women's shoes, men's boots, men's slippers, women's slippers, custom footwear, loafers, sandals, mules, derby, monk strap">
  <meta name="author" content="DeeReel Footies">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>DeeReel Footies | Handcrafted Luxury Shoes for Men and Women</title>
  <link href="/css/styles.css" rel="stylesheet">
  <link rel="stylesheet" href="/css/colors.css">
  <link rel="stylesheet" href="/css/tailwind-custom.css">
  <link rel="stylesheet" href="/css/dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://accounts.google.com/gsi/client" async defer></script>
  <script src="https://cdn.tailwindcss.com"></script>

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
      background-color: white;
      border: 1px solid var(--color-border);
      border-radius: 0.25rem;
      min-width: 10rem;
      padding: 0.5rem 0;
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

    /* Dropdown item styling */
    .dropdown-item {
      display: block;
      width: 100%;
      padding: 0.25rem 1.5rem;
      clear: both;
      font-weight: 400;
      color: var(--color-text-primary);
      text-align: inherit;
      white-space: nowrap;
      background-color: transparent;
      border: 0;
    }

    .dropdown-item:hover, .dropdown-item:focus {
      color: var(--color-primary);
      text-decoration: none;
      background-color: var(--color-secondary);
    }

    /* Mobile navigation */
    .mobile-nav-overlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(0, 0, 0, 0.8);
      z-index: 1000;
      display: none;
    }

    .mobile-nav-overlay.show {
      display: block;
    }

    .mobile-nav-content {
      position: fixed;
      top: 0;
      left: 0;
      width: 75%;
      max-width: 300px;
      height: 100%;
      background-color: white;
      overflow-y: auto;
      padding: 1.5rem;
      z-index: 1001;
    }

    /* Scroll to Top Custom Styling */
    #scrollToTop {
      background-color: #381819; /* Dark chocolate background */
      color: #fff; /* White icon color */
      align-items: center;
      justify-content: center;
      transition: background-color 0.3s ease, transform 0.3s ease;
    }

    #scrollToTop:hover {
      background-color: #5a2a2b; /* Lighter chocolate on hover */
      transform: scale(1.1);
    }

    html {
      scroll-behavior: smooth;
    }

    .selected {
      border: 2px solid black !important;
    }
    .thumb:hover {
      cursor: pointer;
      border: 2px solid black;
    }

    .color-option:hover {
      cursor: pointer;
      border: 2px solid black;
    }

    .size-option:hover {
      cursor: pointer;
      border: 2px solid black;
    }

    .width-option:hover {
      cursor: pointer;
      border: 2px solid black;
    }

    .quantity-btn {
      background-color: #381819; /* Dark chocolate background */
      color: #fff; /* White text color */
      border: none; /* Remove default border */
      padding: 10px 15px; /* Add some padding */
      cursor: pointer; /* Change cursor to pointer on hover */
    }

    .quantity-btn:hover {
      background-color: #5a2a2b; /* Lighter chocolate on hover */
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

    /* Fix for mobile navigation */
    .hidden {
      display: none !important;
    }

  </style>
  
  
    
</head>