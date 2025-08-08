<?php
require_once '../auth/email-service-js.php';

header('Content-Type: application/json');

$email = getPendingEmail();
echo json_encode($email);
?>