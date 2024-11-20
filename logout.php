<?php
session_start(); // Start the session if it's not already started

// Clear the session data
$_SESSION = [];

// If you want to delete the session cookie (optional)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000, 
        $params['path'], 
        $params['domain'], 
        $params['secure'], 
        $params['httponly']
    );
}

// Destroy the session
session_destroy(); 
?>
