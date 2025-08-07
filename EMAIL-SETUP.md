# Email Service Setup Guide

## Option 1: PHPMailer + Gmail (Recommended)

### Step 1: Download PHPMailer
1. Go to: https://github.com/PHPMailer/PHPMailer/releases
2. Download latest release
3. Extract to `/vendor/phpmailer/` folder in your project

### Step 2: Gmail Setup
1. Enable 2-Factor Authentication on your Gmail
2. Generate App Password:
   - Google Account → Security → 2-Step Verification → App passwords
   - Select "Mail" and generate password
3. Update `auth/email-service.php`:
   ```php
   $mail->Username = 'your-email@gmail.com';
   $mail->Password = 'your-16-digit-app-password';
   ```

### Step 3: Test Email
```php
require_once 'auth/email-service.php';
$result = sendEmail('test@example.com', 'Test', 'Hello World');
echo $result ? 'Success' : 'Failed';
```

## Option 2: SendGrid (Professional)

### Step 1: Get SendGrid API Key
1. Sign up at https://sendgrid.com
2. Create API Key in Settings → API Keys

### Step 2: Create SendGrid Service
```php
// auth/sendgrid-service.php
function sendEmailSendGrid($to, $subject, $body) {
    $data = [
        'personalizations' => [[
            'to' => [['email' => $to]]
        ]],
        'from' => ['email' => 'noreply@yoursite.com'],
        'subject' => $subject,
        'content' => [['type' => 'text/html', 'value' => $body]]
    ];
    
    $ch = curl_init('https://api.sendgrid.com/v3/mail/send');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer YOUR_SENDGRID_API_KEY',
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return $httpCode === 202;
}
```

## Quick Start (Gmail)
1. Download PHPMailer to `/vendor/phpmailer/`
2. Update email/password in `auth/email-service.php`
3. Test with a password reset request

## Security Notes
- Never commit email passwords to git
- Use environment variables for production:
  ```php
  $mail->Password = $_ENV['GMAIL_APP_PASSWORD'];
  ```