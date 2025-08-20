import requests
import json
import os
from PIL import Image, ImageDraw, ImageFont
from datetime import datetime
import mysql.connector
import random

class SocialContentGenerator:
    def __init__(self):
        self.db_config = {
            'host': 'localhost',
            'user': 'root',
            'password': '',
            'database': 'drf_database'
        }
        self.brand_colors = {
            'bg': (45, 45, 45),
            'text': (255, 255, 255),
            'accent': (255, 193, 7)
        }
        
    def fetch_trending_topics(self):
        topics = [
            "How to clean white sneakers without damaging them",
            "Best shoe storage methods to prevent creasing",
            "Matching shoes with different outfit styles",
            "DIY shoe repair for common problems",
            "Seasonal footwear care tips"
        ]
        return random.choice(topics)
    
    def generate_slide_content(self, topic):
        content_templates = {
            'hook': ["Your Shoes Deserve Better! 👟", "Shoe Game Strong 💪", "Step Up Your Style ✨"],
            'tips': {
                'cleaning': ["Use soft brush + mild soap", "Air dry, never direct heat", "Remove laces before cleaning"],
                'storage': ["Use shoe trees to maintain shape", "Store in breathable bags", "Keep away from direct sunlight"],
                'style': ["Match leather with formal wear", "Sneakers for casual outfits", "Consider color coordination"]
            },
            'cta': "Shop Premium Footwear at DeeReel Footies! 🛒"
        }
        
        hook = random.choice(content_templates['hook'])
        tip_category = random.choice(list(content_templates['tips'].keys()))
        tips = content_templates['tips'][tip_category]
        
        slides = [
            {'type': 'hook', 'text': hook},
            {'type': 'tip', 'text': f"Tip 1: {tips[0]}"},
            {'type': 'tip', 'text': f"Tip 2: {tips[1]}"},
            {'type': 'tip', 'text': f"Tip 3: {tips[2]}"},
            {'type': 'cta', 'text': content_templates['cta']}
        ]
        
        return slides
    
    def create_slide_image(self, slide_data, slide_num, total_slides):
        width, height = 1080, 1080
        img = Image.new('RGB', (width, height), self.brand_colors['bg'])
        draw = ImageDraw.Draw(img)
        
        try:
            title_font = ImageFont.truetype("arial.ttf", 60)
            text_font = ImageFont.truetype("arial.ttf", 40)
        except:
            title_font = ImageFont.load_default()
            text_font = ImageFont.load_default()
        
        draw.text((50, 50), "DeeReel Footies", fill=self.brand_colors['accent'], font=title_font)
        
        if slide_data['type'] == 'hook':
            draw.text((50, 300), slide_data['text'], fill=self.brand_colors['text'], font=title_font)
        elif slide_data['type'] == 'tip':
            draw.text((50, 250), slide_data['text'], fill=self.brand_colors['text'], font=text_font)
        elif slide_data['type'] == 'cta':
            draw.text((50, 400), slide_data['text'], fill=self.brand_colors['accent'], font=text_font)
            draw.text((50, 500), "deereelfooties.com", fill=self.brand_colors['text'], font=text_font)
        
        draw.text((width-100, height-50), f"{slide_num}/{total_slides}", fill=self.brand_colors['text'], font=text_font)
        
        return img
    
    def generate_post(self):
        topic = self.fetch_trending_topics()
        slides_content = self.generate_slide_content(topic)
        
        date_str = datetime.now().strftime("%Y%m%d_%H%M%S")
        post_dir = f"../uploads/social_posts/{date_str}"
        os.makedirs(post_dir, exist_ok=True)
        
        slide_paths = []
        for i, slide in enumerate(slides_content, 1):
            img = self.create_slide_image(slide, i, len(slides_content))
            slide_path = f"{post_dir}/slide_{i}.png"
            img.save(slide_path)
            slide_paths.append(slide_path)
        
        self.save_to_database(topic, slide_paths, date_str)
        
        return {
            'topic': topic,
            'slides': slide_paths,
            'post_id': date_str
        }
    
    def save_to_database(self, topic, slide_paths, post_id):
        try:
            conn = mysql.connector.connect(**self.db_config)
            cursor = conn.cursor()
            
            cursor.execute("""
                INSERT INTO social_posts (post_id, topic, content, slides, file_path, created_at, status)
                VALUES (%s, %s, %s, %s, %s, %s, %s)
            """, (post_id, topic, json.dumps(slide_paths), len(slide_paths), slide_paths[0], datetime.now(), 'pending'))
            
            conn.commit()
            cursor.close()
            conn.close()
        except Exception as e:
            print(f"Database error: {e}")

if __name__ == "__main__":
    generator = SocialContentGenerator()
    result = generator.generate_post()
    print(json.dumps(result))