<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

function callAIService($topic) {
    $url = 'http://127.0.0.1:5000/generate-content?topic=' . urlencode($topic);
    
    $context = stream_context_create([
        'http' => [
            'timeout' => 15,
            'method' => 'GET'
        ]
    ]);
    
    $response = @file_get_contents($url, false, $context);
    
    if ($response) {
        return json_decode($response, true);
    }
    
    return null;
}

$topic = $_GET['topic'] ?? 'shoe care';
$aiResponse = callAIService($topic);

if ($aiResponse && $aiResponse['success']) {
    echo json_encode($aiResponse);
} else {
    // Fallback if AI service is down
    $fallbackSlides = [
        ['type' => 'hook', 'title' => 'Professional Shoe Care', 'subtitle' => 'Expert tips for footwear'],
        ['type' => 'tip', 'content' => 'Clean leather shoes with specialized cleaner monthly'],
        ['type' => 'tip', 'content' => 'Use cedar shoe trees to maintain shape'],
        ['type' => 'tip', 'content' => 'Apply waterproof spray before first wear'],
        ['type' => 'cta', 'title' => 'Shop Quality Footwear']
    ];
    
    echo json_encode(['success' => true, 'slides' => $fallbackSlides, 'source' => 'fallback']);
}
?>