<?php
// Development email service - just logs emails instead of sending
function sendEmail($to, $subject, $body) {
    $logEntry = date('Y-m-d H:i:s') . " - Email to: $to, Subject: $subject\n";
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/email-log.txt', $logEntry, FILE_APPEND);
    
    echo "✅ Email logged (development mode): $subject to $to<br>";
    return true;
}
?>