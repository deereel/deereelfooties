@echo off
echo Installing Python dependencies...
pip install -r requirements.txt

echo Starting AI Content Service...
python ai_content_service.py

pause