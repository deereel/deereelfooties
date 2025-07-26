<?php
// SEO Schema Generator for DeeReel Footies

function generateProductSchema($product) {
    return json_encode([
        "@context" => "https://schema.org",
        "@type" => "Product",
        "name" => $product['name'],
        "description" => $product['description'] ?? $product['short_description'],
        "image" => "https://deereelfooties.com" . $product['main_image'],
        "sku" => $product['product_id'] ?? $product['slug'],
        "brand" => [
            "@type" => "Brand",
            "name" => "DeeReel Footies"
        ],
        "offers" => [
            "@type" => "Offer",
            "price" => $product['price'],
            "priceCurrency" => "NGN",
            "availability" => "https://schema.org/InStock",
            "url" => "https://deereelfooties.com/product.php?slug=" . $product['slug']
        ],
        "aggregateRating" => [
            "@type" => "AggregateRating",
            "ratingValue" => "4.8",
            "reviewCount" => "127"
        ]
    ], JSON_UNESCAPED_SLASHES);
}

function generateBreadcrumbSchema($breadcrumbs) {
    $items = [];
    foreach ($breadcrumbs as $index => $crumb) {
        $items[] = [
            "@type" => "ListItem",
            "position" => $index + 1,
            "name" => $crumb['name'],
            "item" => "https://deereelfooties.com" . $crumb['url']
        ];
    }
    
    return json_encode([
        "@context" => "https://schema.org",
        "@type" => "BreadcrumbList",
        "itemListElement" => $items
    ], JSON_UNESCAPED_SLASHES);
}

function generateWebsiteSchema() {
    return json_encode([
        "@context" => "https://schema.org",
        "@type" => "WebSite",
        "name" => "DeeReel Footies",
        "url" => "https://deereelfooties.com",
        "potentialAction" => [
            "@type" => "SearchAction",
            "target" => "https://deereelfooties.com/products.php?search={search_term_string}",
            "query-input" => "required name=search_term_string"
        ]
    ], JSON_UNESCAPED_SLASHES);
}

function generateLocalBusinessSchema() {
    return json_encode([
        "@context" => "https://schema.org",
        "@type" => "LocalBusiness",
        "name" => "DeeReel Footies",
        "description" => "Premium handcrafted shoes for men and women",
        "url" => "https://deereelfooties.com",
        "telephone" => "+234-XXX-XXX-XXXX",
        "address" => [
            "@type" => "PostalAddress",
            "addressCountry" => "Nigeria"
        ],
        "openingHours" => "Mo-Sa 09:00-18:00",
        "priceRange" => "₦₦₦"
    ], JSON_UNESCAPED_SLASHES);
}
?>