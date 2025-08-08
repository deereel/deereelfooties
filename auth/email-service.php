<?php
function sendEmail($to, $subject, $body) {
    // Try PHPMailer first if available
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/phpmailer/PHPMailer-master/src/PHPMailer.php')) {
        return sendEmailPHPMailer($to, $subject, $body);
    }
    
    // Fallback to basic mail function
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: DeeReel Footies <noreply@deereelfooties.com>' . "\r\n";
    
    return mail($to, $subject, $body, $headers);
}

function sendEmailPHPMailer($to, $subject, $body) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/phpmailer/PHPMailer-master/src/PHPMailer.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/phpmailer/PHPMailer-master/src/SMTP.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/phpmailer/PHPMailer-master/src/Exception.php';
    
    try {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'deereelfooties@gmail.com';
        $mail->Password = 'kzwm txpz ivpr ictk';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        // XAMPP SSL fix
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        
        $mail->setFrom('deereelfooties@gmail.com', 'DeeReel Footies');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "PHPMailer error: " . $e->getMessage();
        error_log("PHPMailer error: " . $e->getMessage());
        return false;
    }
}


