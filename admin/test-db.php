<?php
try {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/auth/db.php');
    echo "Database connection successful!";
} catch (Exception $e) {
    echo "Database connection failed: " . $e->getMessage();
}
?>