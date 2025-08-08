<?php
session_start();
unset($_SESSION['pending_email']);
echo "✅ Pending email cleared";
?>