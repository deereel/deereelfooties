<?php
// Simple image compression without external tools
$imageDir = "images/";
$outputDir = "images/compressed/";

// Create output directory
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

$files = glob($imageDir . "*.{jpg,jpeg,png,JPG,JPEG,PNG}", GLOB_BRACE);

foreach($files as $file) {
    $filename = basename($file);
    $outputFile = $outputDir . "compressed_" . $filename;
    
    // Get image info
    $imageInfo = getimagesize($file);
    $originalSize = filesize($file);
    
    if ($imageInfo === false) {
        echo "✗ Invalid image: $filename\n";
        continue;
    }
    
    // Create image resource based on type
    switch($imageInfo[2]) {
        case IMAGETYPE_JPEG:
            $image = imagecreatefromjpeg($file);
            break;
        case IMAGETYPE_PNG:
            $image = imagecreatefrompng($file);
            break;
        default:
            echo "✗ Unsupported format: $filename\n";
            continue 2;
    }
    
    if ($image === false) {
        echo "✗ Failed to load: $filename\n";
        continue;
    }
    
    // Resize if too large
    $width = $imageInfo[0];
    $height = $imageInfo[1];
    $maxWidth = 800;
    $maxHeight = 600;
    
    if ($width > $maxWidth || $height > $maxHeight) {
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = (int)($width * $ratio);
        $newHeight = (int)($height * $ratio);
        
        $resized = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency for PNG
        if ($imageInfo[2] == IMAGETYPE_PNG) {
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
        }
        
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($image);
        $image = $resized;
    }
    
    // Save compressed image
    $success = false;
    if ($imageInfo[2] == IMAGETYPE_JPEG) {
        $success = imagejpeg($image, $outputFile, 80); // 80% quality
    } elseif ($imageInfo[2] == IMAGETYPE_PNG) {
        $success = imagepng($image, $outputFile, 6); // Compression level 6
    }
    
    imagedestroy($image);
    
    if ($success && file_exists($outputFile)) {
        $compressedSize = filesize($outputFile);
        $savings = round((($originalSize - $compressedSize) / $originalSize) * 100, 1);
        
        echo "✓ Compressed: $filename\n";
        echo "  Original: " . formatBytes($originalSize) . "\n";
        echo "  Compressed: " . formatBytes($compressedSize) . "\n";
        echo "  Savings: {$savings}%\n\n";
    } else {
        echo "✗ Failed to compress: $filename\n";
    }
}

function formatBytes($size) {
    if ($size >= 1024 * 1024) {
        return round($size / (1024 * 1024), 1) . ' MB';
    } elseif ($size >= 1024) {
        return round($size / 1024, 1) . ' KB';
    }
    return $size . ' bytes';
}

echo "Compression complete!\n";
?>
