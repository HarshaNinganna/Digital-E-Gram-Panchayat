<?php
// Ensure the user is logged in as an officer
session_start();
if (!isset($_SESSION['officer_id'])) {
    header("Location: officer_login.php"); // Redirect to login page if not logged in
    exit();
}

// Initialize the officer ID from session
$officer_id = $_SESSION['officer_id'];

// Initialize database connection
$conn = new mysqli("localhost", "root", "", "digital_e_gram_panchayat");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['staff_id']) && isset($_POST['work_description'])) {
    $staff_id = $_POST['staff_id']; // Get the staff ID
    $work_description = $conn->real_escape_string($_POST['work_description']); // Sanitize work description

    // Check if the staff_id exists in the staff table
    $check_staff_sql = "SELECT COUNT(*) FROM staff WHERE staff_id = ?";
    if ($stmt = $conn->prepare($check_staff_sql)) {
        $stmt->bind_param("s", $staff_id);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        if ($count == 0) {
            echo "<p class='alert alert-danger'>Error: Invalid staff ID. The staff ID does not exist.</p>";
            exit();
        }
        $stmt->close();
    } else {
        echo "<p class='alert alert-danger'>Error checking staff ID: " . $conn->error . "</p>";
        exit();
    }

    // Insert the assigned work into the database
    $sql = "INSERT INTO assigned_works (staff_id, work_description, assigned_by, assigned_at) 
            VALUES (?, ?, ?, NOW())"; // Insert work assignment

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sss", $staff_id, $work_description, $officer_id); // Bind parameters for the query

        if ($stmt->execute()) {
            echo "<p class='alert alert-success'>Work has been assigned successfully!</p>";
        } else {
            echo "<p class='alert alert-danger'>Error assigning work: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p class='alert alert-danger'>Error preparing statement: " . $conn->error . "</p>";
    }
}

// Close the database connection
$conn->close();
?>
