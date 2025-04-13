<?php
/**
 * Logout script
 * Destroys session and redirects to home page
 */

// Include necessary files
require_once 'includes/auth.php';

// Logout user
logoutUser();

// Redirect to home page
header('Location: index.php');
exit;
?> 