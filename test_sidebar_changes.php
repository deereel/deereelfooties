<?php
// Test script to verify sidebar changes
echo "Testing Admin Sidebar Changes\n";
echo "==============================\n\n";

// Read the sidebar file
$sidebarContent = file_get_contents('admin/includes/sidebar.php');

echo "Checking for removed sections:\n\n";

// Check if User Management section is removed
if (strpos($sidebarContent, 'User Management') === false) {
    echo "✅ User Management section successfully removed\n";
} else {
    echo "❌ User Management section still exists\n";
}

// Check if Product Management section is removed
if (strpos($sidebarContent, 'Product Management') === false) {
    echo "✅ Product Management section successfully removed\n";
} else {
    echo "❌ Product Management section still exists\n";
}

// Check if Customer Management section is removed
if (strpos($sidebarContent, 'Customer Management') === false) {
    echo "✅ Customer Management section successfully removed\n";
} else {
    echo "❌ Customer Management section still exists\n";
}

// Check if specific menu items are removed
$removedItems = [
    'Admin Users',
    'User Management',
    'Role Management',
    'Change Password',
    'Add Product',
    'Edit Product',
    'Delete Product',
    'Customer Details'
];

echo "\nChecking for removed menu items:\n";
foreach ($removedItems as $item) {
    if (strpos($sidebarContent, $item) === false) {
        echo "✅ '$item' successfully removed\n";
    } else {
        echo "❌ '$item' still exists\n";
    }
}

// Check if remaining sections are intact
$remainingSections = [
    'Dashboard',
    'Orders',
    'Products',
    'Inventory',
    'Customers',
    'Social Media',
    'Slide Generator',
    'Settings',
    'Reports',
    'Tools & Setup'
];

echo "\nChecking if remaining sections are intact:\n";
foreach ($remainingSections as $section) {
    if (strpos($sidebarContent, $section) !== false) {
        echo "✅ '$section' section is intact\n";
    } else {
        echo "❌ '$section' section is missing\n";
    }
}

echo "\nTest completed!\n";
?>
