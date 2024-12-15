<?php
// Database configuration
$servername = "localhost";             // Server address (e.g., localhost for local development)
$username = "root";                    // MySQL username
$password = "";                        // MySQL password
$dbname = "digital_e_gram_panchayat";  // Your database name

// Create a new MySQLi connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection and handle errors
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
