<?php
session_start();
// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login or another appropriate page
header("Location: index.php"); // Replace with your login page URL
exit;
?>
