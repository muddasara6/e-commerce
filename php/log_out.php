<?php
    // Start session management
    session_start();
    // Clear cookies
    setcookie(session_name(), false, -1, '/');
    // Remove all session variables
    session_unset(); 
    // Destroy the session 
    session_destroy();
?>