const slideTemplates = {
    'Shoe Care Tips': [
        [
            {type: 'hook', title: 'Make Your Shoes Last Longer', subtitle: 'Quick, practical tips you can use today'},
            {type: 'tip', content: 'Rotate your shoes — avoid wearing the same pair two days in a row to extend sole life'},
            {type: 'tip', content: 'Spot-clean stains immediately with a gentle cleaner to avoid permanent marks'},
            {type: 'cta', title: 'Step Up Your Style'}
        ],
        [
            {type: 'hook', title: 'Protect Your Investment', subtitle: 'Essential shoe maintenance secrets'},
            {type: 'tip', content: 'Use cedar shoe trees after each wear to maintain shape and absorb moisture'},
            {type: 'tip', content: 'Apply waterproof spray before first wear and monthly thereafter'},
            {type: 'cta', title: 'Shop Quality Footwear'}
        ],
        [
            {type: 'hook', title: 'Shoe Care Mastery', subtitle: 'Professional tips for lasting footwear'},
            {type: 'tip', content: 'Clean leather shoes with saddle soap and condition monthly'},
            {type: 'tip', content: 'Store shoes in breathable bags, never plastic'},
            {type: 'cta', title: 'Discover Premium Shoes'}
        ]
    ],
    'Style Matching': [
        [
            {type: 'hook', title: 'Perfect Shoe Pairing', subtitle: 'Master the art of footwear coordination'},
            {type: 'tip', content: 'Match leather shoes with formal wear for classic elegance'},
            {type: 'tip', content: 'Choose sneakers for casual outfits to balance comfort and style'},
            {type: 'cta', title: 'Find Your Perfect Pair'}
        ],
        [
            {type: 'hook', title: 'Style Like a Pro', subtitle: 'Footwear coordination made simple'},
            {type: 'tip', content: 'Brown shoes work best with earth tones and warm colors'},
            {type: 'tip', content: 'Black shoes are versatile and pair with most formal attire'},
            {type: 'cta', title: 'Elevate Your Style'}
        ]
    ],
    'DIY Repairs': [
        [
            {type: 'hook', title: 'Fix Your Shoes at Home', subtitle: 'Simple repair hacks that actually work'},
            {type: 'tip', content: 'Remove scuffs from white soles using a small amount of toothpaste'},
            {type: 'tip', content: 'Fix loose soles temporarily with super glue until professional repair'},
            {type: 'cta', title: 'Need New Shoes?'}
        ],
        [
            {type: 'hook', title: 'Quick Shoe Fixes', subtitle: 'Emergency repairs you can do yourself'},
            {type: 'tip', content: 'Use clear nail polish to stop small tears from spreading'},
            {type: 'tip', content: 'Rub a walnut on leather scratches to naturally fill them'},
            {type: 'cta', title: 'Browse Our Collection'}
        ]
    ],
    'Fun Facts': [
        [
            {type: 'hook', title: 'Amazing Shoe Facts', subtitle: 'Fascinating footwear trivia you never knew'},
            {type: 'tip', content: 'The average person walks 7,500 steps daily — invest in quality footwear'},
            {type: 'tip', content: 'High heels were originally designed for Persian cavalry riders'},
            {type: 'cta', title: 'Step Into History'}
        ],
        [
            {type: 'hook', title: 'Shoe Trivia Time', subtitle: 'Mind-blowing facts about footwear'},
            {type: 'tip', content: 'Sneakers got their name because rubber soles made them quiet'},
            {type: 'tip', content: 'The most expensive shoes ever sold cost $17 million'},
            {type: 'cta', title: 'Discover Luxury Footwear'}
        ]
    ]
};

function createSlide(slideData, slideNum, totalSlides, format = 'instagram') {
    const canvas = document.createElement('canvas');
    
    // Set dimensions based on format
    if (format === 'instagram') {
        canvas.width = 1080;
        canvas.height = 1080;
    } else {
        canvas.width = 1080;
        canvas.height = 1920;
    }
    
    const ctx = canvas.getContext('2d');
    
    // Background with specified color
    ctx.fillStyle = '#1b2129';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    
    // Add subtle dust-like freckles
    ctx.fillStyle = 'rgba(255, 255, 255, 0.03)';
    for (let i = 0; i < 200; i++) {
        const x = Math.random() * canvas.width;
        const y = Math.random() * canvas.height;
        const size = Math.random() * 2 + 1;
        ctx.beginPath();
        ctx.arc(x, y, size, 0, Math.PI * 2);
        ctx.fill();
    }
    
    // Load logo at center top
    const logo = new Image();
    logo.crossOrigin = 'anonymous';
    logo.onload = function() {
        // Calculate proper aspect ratio to avoid compression
        const maxWidth = 800;
        const maxHeight = 300;
        
        let logoWidth = logo.naturalWidth;
        let logoHeight = logo.naturalHeight;
        
        // Scale down if too large while maintaining aspect ratio
        if (logoWidth > maxWidth) {
            logoHeight = (logoHeight * maxWidth) / logoWidth;
            logoWidth = maxWidth;
        }
        if (logoHeight > maxHeight) {
            logoWidth = (logoWidth * maxHeight) / logoHeight;
            logoHeight = maxHeight;
        }
        
        const logoX = (canvas.width - logoWidth) / 2;
        const logoY = 80;
        ctx.drawImage(logo, logoX, logoY, logoWidth, logoHeight);
    };
    logo.onerror = function() {
        // Fallback text if logo fails to load
        ctx.fillStyle = '#ffc107';
        ctx.font = 'bold 70px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('DeeReeL', canvas.width / 2, 160);
        
        ctx.font = 'bold 60px Arial';
        ctx.fillText('Footies', canvas.width / 2, 230);
    };
    logo.src = '/images/drf-logo.webp';
    
    const centerY = canvas.height / 2 + 100;
    
    // Reset text alignment to center
    ctx.textAlign = 'center';
    
    if (slideData.type === 'hook') {
        // Main title with shadow effect
        ctx.shadowColor = 'rgba(0, 0, 0, 0.5)';
        ctx.shadowBlur = 10;
        ctx.fillStyle = '#ffffff';
        ctx.font = 'bold 64px Arial';
        wrapTextCentered(ctx, slideData.title, canvas.width / 2, centerY - 50, canvas.width - 100, 80);
        
        // Reset shadow
        ctx.shadowBlur = 0;
        
        // Subtitle
        ctx.fillStyle = '#cccccc';
        ctx.font = '32px Arial';
        wrapTextCentered(ctx, slideData.subtitle, canvas.width / 2, centerY + 70, canvas.width - 100, 45);
        
    } else if (slideData.type === 'tip') {
        // TIP badge
        ctx.fillStyle = '#ffc107';
        ctx.fillRect(canvas.width / 2 - 60, centerY - 150, 120, 40);
        ctx.fillStyle = '#000000';
        ctx.font = 'bold 24px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('TIP', canvas.width / 2, centerY - 125);
        
        // Tip content
        ctx.fillStyle = '#ffffff';
        ctx.font = '38px Arial';
        ctx.textAlign = 'center';
        wrapTextCentered(ctx, slideData.content || slideData.title, canvas.width / 2, centerY - 20, canvas.width - 100, 55);
        
    } else if (slideData.type === 'cta') {
        // CTA title
        ctx.fillStyle = '#ffc107';
        ctx.font = 'bold 56px Arial';
        ctx.textAlign = 'center';
        wrapTextCentered(ctx, slideData.title, canvas.width / 2, centerY - 50, canvas.width - 100, 70);
        
        // Website box
        ctx.strokeStyle = '#ffc107';
        ctx.lineWidth = 3;
        ctx.strokeRect(canvas.width / 2 - 200, centerY + 30, 400, 80);
        
        ctx.fillStyle = '#ffffff';
        ctx.font = '28px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('deereelfooties.com', canvas.width / 2, centerY + 75);
        
        // Social handle
        ctx.fillStyle = '#cccccc';
        ctx.font = '24px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('Follow @deereelfooties', canvas.width / 2, centerY + 150);
    }
    
    // Bottom branding
    ctx.fillStyle = 'rgba(27, 33, 41, 0.9)';
    ctx.fillRect(0, canvas.height - 60, canvas.width, 60);
    
    ctx.fillStyle = '#888888';
    ctx.font = '20px Arial';
    ctx.textAlign = 'center';
    ctx.fillText('DeeReel Footies • Premium Footwear', canvas.width / 2, canvas.height - 25);
    
    // Slide number (bottom right)
    ctx.fillStyle = '#ffc107';
    ctx.font = 'bold 18px Arial';
    ctx.textAlign = 'right';
    ctx.fillText(`${slideNum}/${totalSlides}`, canvas.width - 30, canvas.height - 80);
    
    return canvas;
}

function wrapTextCentered(ctx, text, x, y, maxWidth, lineHeight) {
    const words = text.split(' ');
    const lines = [];
    let currentLine = '';
    
    for (let word of words) {
        const testLine = currentLine + word + ' ';
        const metrics = ctx.measureText(testLine);
        
        if (metrics.width > maxWidth && currentLine !== '') {
            lines.push(currentLine.trim());
            currentLine = word + ' ';
        } else {
            currentLine = testLine;
        }
    }
    lines.push(currentLine.trim());
    
    const startY = y - ((lines.length - 1) * lineHeight) / 2;
    
    lines.forEach((line, index) => {
        ctx.fillText(line, x, startY + (index * lineHeight));
    });
}

function wrapText(ctx, text, x, y, maxWidth, lineHeight) {
    const words = text.split(' ');
    let line = '';
    let currentY = y;
    
    for (let n = 0; n < words.length; n++) {
        const testLine = line + words[n] + ' ';
        const metrics = ctx.measureText(testLine);
        const testWidth = metrics.width;
        
        if (testWidth > maxWidth && n > 0) {
            ctx.fillText(line, x, currentY);
            line = words[n] + ' ';
            currentY += lineHeight;
        } else {
            line = testLine;
        }
    }
    ctx.fillText(line, x, currentY);
}

async function generateAndDownload() {
    const topicSelect = document.getElementById('topicSelect');
    const slidesSelect = document.getElementById('slidesSelect');
    
    const topic = topicSelect ? topicSelect.value : 'Shoe Care Tips';
    const numSlides = slidesSelect ? parseInt(slidesSelect.value) : 4;
    
    const previewDiv = document.getElementById('slidePreview');
    if (!previewDiv) {
        console.error('Preview div not found');
        return;
    }
    
    previewDiv.innerHTML = '<p>Fetching fresh content online...</p>';
    
    try {
        // Fetch fresh content from API
        const response = await fetch(`api/fetch-content.php?topic=${encodeURIComponent(topic)}`);
        const data = await response.json();
        
        if (!data.success) {
            throw new Error('Failed to fetch content');
        }
        
        const slides = data.slides.slice(0, numSlides);
        
        previewDiv.innerHTML = '<p>Generating professional slides...</p>';
        
        setTimeout(() => {
            previewDiv.innerHTML = '<h6>Instagram Format (1080×1080)</h6>';
            
            // Generate Instagram format slides
            slides.forEach((slide, index) => {
                const canvas = createSlide(slide, index + 1, slides.length, 'instagram');
                canvas.style.width = '200px';
                canvas.style.height = '200px';
                canvas.style.margin = '5px';
                canvas.style.border = '2px solid #ffc107';
                canvas.style.borderRadius = '8px';
                canvas.style.cursor = 'pointer';
                canvas.className = 'instagram-slide';
                canvas.onclick = () => showSlideModal(canvas, 'Instagram');
                previewDiv.appendChild(canvas);
            });
            
            // Add TikTok format preview
            const tiktokDiv = document.createElement('div');
            tiktokDiv.innerHTML = '<h6 class="mt-4">TikTok Format (1080×1920)</h6>';
            previewDiv.appendChild(tiktokDiv);
            
            slides.forEach((slide, index) => {
                const canvas = createSlide(slide, index + 1, slides.length, 'tiktok');
                canvas.style.width = '112px';
                canvas.style.height = '200px';
                canvas.style.margin = '5px';
                canvas.style.border = '2px solid #ffc107';
                canvas.style.borderRadius = '8px';
                canvas.style.cursor = 'pointer';
                canvas.className = 'tiktok-slide';
                canvas.onclick = () => showSlideModal(canvas, 'TikTok');
                previewDiv.appendChild(canvas);
            });
            
            const downloadBtn = document.getElementById('downloadBtn');
            if (downloadBtn) {
                downloadBtn.style.display = 'block';
            }
        }, 100);
        
    } catch (error) {
        console.error('Error fetching content:', error);
        previewDiv.innerHTML = '<p class="text-danger">Failed to fetch fresh content. Please try again.</p>';
    }
}

function showSlideModal(canvas, format) {
    // Create modal
    const modal = document.createElement('div');
    modal.style.cssText = `
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.8); z-index: 9999; display: flex;
        align-items: center; justify-content: center; cursor: pointer;
    `;
    
    // Create image
    const img = document.createElement('img');
    img.src = canvas.toDataURL();
    img.style.cssText = `
        max-width: 90%; max-height: 90%; border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.5);
    `;
    
    // Create close button
    const closeBtn = document.createElement('button');
    closeBtn.innerHTML = '×';
    closeBtn.style.cssText = `
        position: absolute; top: 20px; right: 30px; background: none;
        border: none; color: white; font-size: 40px; cursor: pointer;
    `;
    
    // Create download button
    const downloadBtn = document.createElement('button');
    downloadBtn.innerHTML = 'Download';
    downloadBtn.className = 'btn btn-primary';
    downloadBtn.style.cssText = `
        position: absolute; bottom: 30px; right: 30px;
        padding: 10px 20px; font-size: 16px;
    `;
    downloadBtn.onclick = (e) => {
        e.stopPropagation();
        const link = document.createElement('a');
        link.download = `deereel_${format.toLowerCase()}_slide.png`;
        link.href = canvas.toDataURL();
        link.click();
    };
    
    // Prevent modal from closing when clicking on buttons
    img.onclick = (e) => e.stopPropagation();
    downloadBtn.onclick = (e) => {
        e.stopPropagation();
        const link = document.createElement('a');
        link.download = `deereel_${format.toLowerCase()}_slide.png`;
        link.href = canvas.toDataURL();
        link.click();
    };
    
    modal.appendChild(img);
    modal.appendChild(closeBtn);
    modal.appendChild(downloadBtn);
    
    // Close modal on click
    modal.onclick = () => document.body.removeChild(modal);
    closeBtn.onclick = () => document.body.removeChild(modal);
    
    document.body.appendChild(modal);
}

function downloadSlides() {
    // Download Instagram slides
    const instagramSlides = document.querySelectorAll('#slidePreview .instagram-slide');
    instagramSlides.forEach((canvas, index) => {
        const link = document.createElement('a');
        link.download = `deereel_instagram_slide_${index + 1}.png`;
        link.href = canvas.toDataURL();
        link.click();
    });
    
    // Download TikTok slides
    setTimeout(() => {
        const tiktokSlides = document.querySelectorAll('#slidePreview .tiktok-slide');
        tiktokSlides.forEach((canvas, index) => {
            const link = document.createElement('a');
            link.download = `deereel_tiktok_slide_${index + 1}.png`;
            link.href = canvas.toDataURL();
            link.click();
        });
    }, 500);
}