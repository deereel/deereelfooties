@echo off
echo Image Compression Tool
echo =====================
echo.
echo This script will help you compress images to under 100KB
echo.
echo INSTRUCTIONS:
echo 1. Go to https://tinypng.com in your browser
echo 2. Drag and drop your images (up to 20 at once)
echo 3. Wait for compression to complete
echo 4. Download the compressed images
echo.
echo ALTERNATIVE:
echo 1. Go to https://squoosh.app
echo 2. Upload one image at a time
echo 3. Adjust quality until file size is under 100KB
echo 4. Download the compressed image
echo.
echo RECOMMENDED SETTINGS:
echo - Format: WebP or JPEG
echo - Quality: 75-85%%
echo - Max dimensions: 800x600px
echo.
pause
start https://tinypng.com
echo.
echo TinyPNG opened in your browser
echo Drag your images from the 'images' folder to the website
echo.
pause