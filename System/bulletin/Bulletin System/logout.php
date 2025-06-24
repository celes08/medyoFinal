<?php
session_start();

// Unset all session variables
$_SESSION = array();
// Unset accepted_terms specifically (redundant but explicit)
unset($_SESSION['accepted_terms']);
// Destroy the session
session_destroy();

// Redirect to login page
header("Location: index.php");
exit();
?> 