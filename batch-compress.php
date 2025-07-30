<?php
// Batch compress images using ImageMagick
$imageDir = "images/";
$outputDir = "images/compressed/";

// Create output directory if it doesn't exist
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

// Find all JPG files
$files = glob($imageDir . "*.{jpg,jpeg,JPG,JPEG}", GLOB_BRACE);

foreach($files as $file) {
    $filename = basename($file);
    $outputFile = $outputDir . "compressed_" . $filename;
    
    // ImageMagick command
    $command = "magick \"$file\" -quality 80 -resize 800x600> \"$outputFile\"";
    
    // Execute command
    exec($command, $output, $returnCode);
    
    if ($returnCode === 0) {
        $originalSize = filesize($file);
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