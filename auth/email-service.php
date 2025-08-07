<?php
// Download PHPMailer manually from: https://github.com/PHPMailer/PHPMailer/releases
// Extract to /vendor/phpmailer/ folder

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/phpmailer/src/PHPMailer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/phpmailer/src/SMTP.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

function sendEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);
    
    try {
        // Gmail SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@gmail.com'; // Your Gmail
        $mail->Password = 'your-app-password';    // Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        // Email settings
        $mail->setFrom('your-email@gmail.com', 'DeeReel Footies');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email error: " . $mail->ErrorInfo);
        return false;
    }
}
?>