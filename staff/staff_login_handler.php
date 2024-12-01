<?php
session_start();

// Initialize database connection
$conn = new mysqli("localhost", "root", "", "digital_e_gram_panchayat");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['staff_id']) && isset($_POST['secret_number']) && isset($_POST['redirect_page'])) {
    $staff_id = $_POST['staff_id'];
    $secret_number = $_POST['secret_number'];
    $redirect_page = $_POST['redirect_page'];

    // Verify staff credentials
    $sql_verify_staff = "SELECT * FROM staff WHERE staff_id = ? AND secret_number = ?";
    if ($stmt = $conn->prepare($sql_verify_staff)) {
        $stmt->bind_param("ss", $staff_id, $secret_number);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            // Login successful - set session and redirect
            $_SESSION['staff_id'] = $staff_id;
            header("Location: $redirect_page");
            exit();
        } else {
            echo "<p class='alert alert-danger'>Invalid staff ID or secret number.</p>";
        }
        $stmt->close();
    } else {
        echo "<p class='alert alert-danger'>Error preparing statement: " . $conn->error . "</p>";
    }
}

// Close the database connection
$conn->close();
?>
