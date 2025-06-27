<?php
/**
 * Product page rendering functions
 */

/**
 * Render a product page based on slug
 * 
 * @param string $slug Product slug/identifier
 * @return void
 */
function renderProductPage($slug) {
    // Get product data from database or use sample data
    $product = getProductBySlug($slug);
    
    // Start output buffering to capture all HTML
    ob_start();
    
    // Include header
    include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php');
    
    // Output opening body tag
    echo '<body class="bg-background">';
    
    // Include navbar
    include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php');
    
    // Start main content
    echo '<main>';
    
    // Include product template with product data
    include($_SERVER['DOCUMENT_ROOT'] . '/components/product-template.php');
    
    // End main content
    echo '</main>';
    
    // Include footer
    include($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php');
    
    // Include account modal
    include($_SERVER['DOCUMENT_ROOT'] . '/components/account-modal.php');
    
    // Include scripts
    include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php');
    
    // Close body and html tags
    echo '</body></html>';
    
    // Output all captured HTML
    ob_end_flush();
}

/**
 * Get product data by slug
 * 
 * @param string $slug Product slug/identifier
 * @return array Product data
 */
function getProductBySlug($slug) {
    // Try to get product from database
    try {
        global $pdo;
        
        if (isset($pdo)) {
            // First try to get by slug
            $stmt = $pdo->prepare("SELECT * FROM products WHERE slug = ?");
            $stmt->execute([$slug]);
            $dbProduct = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // If not found by slug, try by product_id
            if (!$dbProduct) {
                $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
                $stmt->execute([$slug]);
                $dbProduct = $stmt->fetch(PDO::FETCH_ASSOC);
            }
            
            if ($dbProduct) {
                // Format product data from database
                $productId = $dbProduct['product_id'] ?? ($dbProduct['id'] ?? $slug);
                
                // Get product images
                $gallery = [];
                if (!empty($dbProduct['gallery'])) {
                    $gallery = explode(',', $dbProduct['gallery']);
                } else {
                    // Try to get images from product_images table
                    $imgStmt = $pdo->prepare("SELECT image_url FROM product_images WHERE product_id = ?");
                    $imgStmt->execute([$productId]);
                    while ($img = $imgStmt->fetch(PDO::FETCH_ASSOC)) {
                        if (!empty($img['image_url'])) {
                            $gallery[] = $img['image_url'];
                        }
                    }
                }
                
                // Get product colors
                $colors = [];
                if (!empty($dbProduct['colors'])) {
                    $colors = explode(',', $dbProduct['colors']);
                } else {
                    // Try to get colors from product_colors table
                    $colorStmt = $pdo->prepare("SELECT color FROM product_colors WHERE product_id = ?");
                    $colorStmt->execute([$productId]);
                    while ($color = $colorStmt->fetch(PDO::FETCH_ASSOC)) {
                        if (!empty($color['color'])) {
                            $colors[] = $color['color'];
                        }
                    }
                }
                
                // Get product sizes
                $sizes = [];
                if (!empty($dbProduct['sizes'])) {
                    $sizes = explode(',', $dbProduct['sizes']);
                } else {
                    // Try to get sizes from product_sizes table
                    $sizeStmt = $pdo->prepare("SELECT size FROM product_sizes WHERE product_id = ?");
                    $sizeStmt->execute([$productId]);
                    while ($size = $sizeStmt->fetch(PDO::FETCH_ASSOC)) {
                        if (!empty($size['size'])) {
                            $sizes[] = $size['size'];
                        }
                    }
                }
                
                // Get product widths
                $widths = [];
                if (!empty($dbProduct['widths'])) {
                    $widths = explode(',', $dbProduct['widths']);
                } else {
                    // Try to get widths from product_widths table
                    $widthStmt = $pdo->prepare("SELECT width FROM product_widths WHERE product_id = ?");
                    $widthStmt->execute([$productId]);
                    while ($width = $widthStmt->fetch(PDO::FETCH_ASSOC)) {
                        if (!empty($width['width'])) {
                            $widths[] = $width['width'];
                        }
                    }
                }
                
                return [
                    'id' => $productId,
                    'name' => $dbProduct['name'] ?? $dbProduct['product_name'] ?? 'Product',
                    'price' => $dbProduct['price'] ?? 0,
                    'description' => $dbProduct['description'] ?? '',
                    'image' => $dbProduct['image'] ?? $dbProduct['main_image'] ?? (!empty($gallery) ? $gallery[0] : '/images/product-placeholder.jpg'),
                    'gallery' => $gallery,
                    'colors' => $colors,
                    'sizes' => $sizes,
                    'widths' => $widths
                ];
            }
        }
    } catch (Exception $e) {
        // Log error
        error_log("Error fetching product: " . $e->getMessage());
    }
    
    // Return sample product data if database fetch fails
    return [
        'id' => $slug,
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
}
?>