<?php
// Database configuration
$servername = "localhost";             // Server address (use "localhost" for local development)
$username = "root";                    // MySQL username
$password = "";                        // MySQL password
$dbname = "digital_e_gram_panchayat";  // Your database name

// Enable error reporting for debugging (only for development; disable in production)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Create a new MySQLi connection
    $db = new mysqli($servername, $username, $password, $dbname);
    
    // Set character set to UTF-8
    $db->set_charset("utf8");
    
    // Connection successful
    // You can echo a message here for testing, but remove it for production
} catch (mysqli_sql_exception $e) {
    // Handle connection errors
    die("Database connection failed: " . $e->getMessage());
}
?>
