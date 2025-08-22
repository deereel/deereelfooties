from flask import Flask, jsonify, request
from transformers import pipeline
import requests
import random
from bs4 import BeautifulSoup

app = Flask(__name__)

# Initialize Hugging Face models
summarizer = pipeline("summarization", model="facebook/bart-large-cnn")
generator = pipeline("text-generation", model="gpt2")

def scrape_content(url):
    try:
        response = requests.get(url, timeout=10, headers={'User-Agent': 'Mozilla/5.0'})
        soup = BeautifulSoup(response.content, 'html.parser')
        
        # Extract paragraphs
        paragraphs = soup.find_all('p')
        content = []
        
        for p in paragraphs:
            text = p.get_text().strip()
            if len(text) > 50 and any(word in text.lower() for word in ['shoe', 'footwear', 'leather', 'care']):
                content.append(text)
        
        return content[:5]
    except:
        return []

def generate_shoe_tips():
    sources = [
        'https://www.love2laundry.nl/blog/how-to-care-for-different-types-of-shoes/',
        'https://www.zappos.com/c/shoe-care-guide',
        'https://centralcoastfootclinics.com/shoe-anatomy'
    ]
    
    all_content = []
    for url in sources:
        content = scrape_content(url)
        all_content.extend(content)
    
    if not all_content:
        return [
            "Clean leather shoes with specialized cleaner monthly",
            "Use cedar shoe trees to maintain shape",
            "Apply waterproof spray before first wear"
        ]
    
    # Summarize content using AI
    tips = []
    for text in all_content[:3]:
        try:
            summary = summarizer(text, max_length=50, min_length=20, do_sample=False)
            tips.append(summary[0]['summary_text'])
        except:
            tips.append(text[:100] + "...")
    
    return tips

@app.route('/generate-content', methods=['GET'])
def generate_content():
    topic = request.args.get('topic', 'shoe care')
    
    tips = generate_shoe_tips()
    
    hooks = [
        "AI-Powered Shoe Care Secrets",
        "Smart Footwear Tips",
        "Expert AI Recommendations",
        "Professional Care Guide"
    ]
    
    ctas = [
        "Shop Smart Footwear",
        "Discover AI-Curated Collection",
        "Find Your Perfect Match",
        "Upgrade with Intelligence"
    ]
    
    slides = [
        {'type': 'hook', 'title': random.choice(hooks), 'subtitle': 'AI-generated insights'},
        {'type': 'tip', 'content': tips[0]},
        {'type': 'tip', 'content': tips[1]},
        {'type': 'tip', 'content': tips[2]},
        {'type': 'cta', 'title': random.choice(ctas)}
    ]
    
    return jsonify({'success': True, 'slides': slides, 'source': 'huggingface_ai'})

if __name__ == '__main__':
    app.run(host='127.0.0.1', port=5000, debug=True)