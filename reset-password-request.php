<?php
session_start();
require_once 'auth/security.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password | DeeReel Footies</title>
    <?php include 'components/header.php'; ?>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Reset Password</h4>
                    </div>
                    <div class="card-body">
                        <p>Enter your email address and we'll send you a link to reset your password.</p>
                        <form id="resetForm">
                            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Send Reset Link</button>
                            <a href="/" class="btn btn-secondary ms-2">Back to Login</a>
                        </form>
                        <div id="message" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    document.getElementById('resetForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        try {
            const response = await fetch('/auth/password-reset.php', {
                method: 'POST',
                body: formData
            });
            
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const responseText = await response.text();
            console.log('Raw response:', responseText);
            
            let data;
            try {
                data = JSON.parse(responseText);
            } catch (parseError) {
                throw new Error('Invalid JSON response: ' + responseText.substring(0, 200));
            }
            
            const messageDiv = document.getElementById('message');
            if (data.success) {
                messageDiv.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
            } else {
                messageDiv.innerHTML = '<div class="alert alert-danger">' + data.message + '</div>';
            }
        } catch (error) {
            console.error('Fetch error:', error);
            document.getElementById('message').innerHTML = '<div class="alert alert-danger">Error: ' + error.message + '</div>';
        }
    });
    </script>
    
    <!-- EmailJS -->
    <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
    <script src="/js/emailjs-service.js"></script>
</body>
</html>