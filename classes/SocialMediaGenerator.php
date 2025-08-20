<?php
class SocialMediaGenerator {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    public function generateWithPython() {
        $pythonScript = __DIR__ . '/../scripts/content_generator.py';
        $output = shell_exec("python \"$pythonScript\"");
        return json_decode($output, true);
    }
    
    public function fetchContentIdeas() {
        $topics = [
            "shoe care tips for leather boots",
            "how to match shoes with outfits",
            "DIY shoe repair hacks",
            "interesting facts about sneakers",
            "best shoe storage methods",
            "shoe cleaning mistakes to avoid",
            "trending footwear styles 2024",
            "how to make shoes last longer"
        ];
        
        return $topics[array_rand($topics)];
    }
    
    public function generatePostContent($topic) {
        $contentTemplates = [
            'tip' => [
                "💡 Pro Tip: {content}\n\n✨ Keep your footwear game strong with DeeReel Footies!\n\n👟 Shop premium footwear at deereelfooties.com",
                "🔥 Did you know? {content}\n\n💯 Level up your shoe game with DeeReel Footies!\n\n🛒 Visit us for quality footwear!"
            ],
            'care' => [
                "👠 Shoe Care 101: {content}\n\n✨ Your shoes deserve the best care!\n\n🏪 Find quality footwear at DeeReel Footies",
                "🧽 Keep them fresh: {content}\n\n💎 Premium shoes need premium care!\n\n🛍️ Shop DeeReel Footies today!"
            ],
            'style' => [
                "👗 Style Secret: {content}\n\n🌟 Make every step count with DeeReel Footies!\n\n💫 Discover your perfect pair now!",
                "✨ Fashion Tip: {content}\n\n👑 Step into style with DeeReel Footies!\n\n🛒 Browse our collection today!"
            ]
        ];
        
        $type = array_rand($contentTemplates);
        $template = $contentTemplates[$type][array_rand($contentTemplates[$type])];
        
        return str_replace('{content}', $this->getContentByTopic($topic), $template);
    }
    
    private function getContentByTopic($topic) {
        $contents = [
            "Clean leather shoes with a damp cloth, then apply conditioner to prevent cracking",
            "Match brown shoes with earth tones, black shoes with cool colors for perfect coordination",
            "Use clear nail polish on small scuffs to make them disappear instantly",
            "The average person walks 7,500 steps daily - invest in quality footwear!",
            "Store shoes with cedar shoe trees to maintain shape and absorb moisture",
            "Never put wet shoes near direct heat - it can crack and damage the material",
            "Chunky sneakers and platform shoes are dominating 2024 fashion trends",
            "Rotate your shoes daily to extend their lifespan by up to 50%"
        ];
        
        return $contents[array_rand($contents)];
    }
    
    public function createPost($slides = 3) {
        $topic = $this->fetchContentIdeas();
        $slideContents = $this->generateSlideContents($topic, $slides);
        
        $postId = uniqid('post_');
        $slidePaths = $this->createSlideImages($slideContents, $postId);
        
        $post = [
            'id' => $postId,
            'topic' => $topic,
            'content' => json_encode($slidePaths),
            'slides' => count($slidePaths),
            'created_at' => date('Y-m-d H:i:s'),
            'status' => 'draft'
        ];
        
        $this->savePost($post);
        return $post;
    }
    
    private function generateSlideContents($topic, $numSlides) {
        $slides = [
            ['type' => 'hook', 'text' => '👟 Your Shoes Deserve Better!', 'subtitle' => 'Pro Tips Inside'],
            ['type' => 'tip', 'text' => 'Clean with soft brush + mild soap', 'number' => '01'],
            ['type' => 'tip', 'text' => 'Air dry, never use direct heat', 'number' => '02'],
            ['type' => 'tip', 'text' => 'Use shoe trees to maintain shape', 'number' => '03'],
            ['type' => 'cta', 'text' => 'Shop Premium Footwear', 'subtitle' => 'deereelfooties.com']
        ];
        
        return array_slice($slides, 0, $numSlides);
    }
    
    private function createSlideImages($slideContents, $postId) {
        $slidePaths = [];
        $uploadDir = __DIR__ . '/../uploads/social_posts/' . date('Ymd_His') . '_' . $postId;
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        foreach ($slideContents as $index => $slide) {
            $imagePath = $uploadDir . '/slide_' . ($index + 1) . '.png';
            $this->generateSlideImage($slide, $imagePath, $index + 1, count($slideContents));
            $slidePaths[] = $imagePath;
        }
        
        return $slidePaths;
    }
    
    private function generateSlideImage($slide, $imagePath, $slideNum, $totalSlides) {
        $width = 1080;
        $height = 1920;
        $image = imagecreatetruecolor($width, $height);
        
        // Brand colors
        $bgColor = imagecolorallocate($image, 45, 45, 45);
        $textColor = imagecolorallocate($image, 255, 255, 255);
        $accentColor = imagecolorallocate($image, 255, 193, 7);
        $darkAccent = imagecolorallocate($image, 200, 150, 0);
        
        // Fill background
        imagefill($image, 0, 0, $bgColor);
        
        // Add gradient effect
        for ($i = 0; $i < 200; $i++) {
            $alpha = ($i / 200) * 30;
            $gradientColor = imagecolorallocatealpha($image, 255, 193, 7, 127 - $alpha);
            imageline($image, 0, $i, $width, $i, $gradientColor);
        }
        
        // Brand header
        imagestring($image, 5, 50, 80, 'DeeReel Footies', $accentColor);
        
        if ($slide['type'] === 'hook') {
            imagestring($image, 5, 50, 400, $slide['text'], $textColor);
            imagestring($image, 3, 50, 500, $slide['subtitle'], $accentColor);
        } elseif ($slide['type'] === 'tip') {
            imagestring($image, 4, 50, 300, $slide['number'], $accentColor);
            imagestring($image, 4, 50, 400, $slide['text'], $textColor);
        } elseif ($slide['type'] === 'cta') {
            imagestring($image, 5, 50, 600, $slide['text'], $accentColor);
            imagestring($image, 4, 50, 700, $slide['subtitle'], $textColor);
        }
        
        // Slide indicator
        imagestring($image, 3, $width - 100, $height - 100, "$slideNum/$totalSlides", $textColor);
        
        // Save image
        imagepng($image, $imagePath);
        imagedestroy($image);
    }
    

    
    public function getPosts($limit = 10) {
        $stmt = $this->db->prepare("SELECT * FROM social_posts ORDER BY created_at DESC LIMIT " . (int)$limit);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function schedulePost($postId, $platform, $scheduledTime) {
        $stmt = $this->db->prepare("UPDATE social_posts SET platform = ?, scheduled_time = ?, status = 'scheduled' WHERE post_id = ?");
        return $stmt->execute([$platform, $scheduledTime, $postId]);
    }
}
?>