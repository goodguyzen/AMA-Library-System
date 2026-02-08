<?php
// Start a session to access session variables
session_start();

// Destroy the current session, effectively logging the user out
session_destroy();

// Redirect the user to the login page or home page
header('location:/capstoneproject/index.html');
exit(); // Ensure no further code is executed after the redirection
?>
