<?php
// Start the session
session_start();

// Connect to the database
$conn = new mysqli("localhost", "root", "", "digital_e_gram_panchayat");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and validate input
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $address = $conn->real_escape_string($_POST['address']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    $email = $conn->real_escape_string($_POST['email']);
    $occupation = $conn->real_escape_string($_POST['occupation']);
    $title = $conn->real_escape_string($_POST['title']);  // Adding the title field
    $message = $conn->real_escape_string($_POST['message']);

    // Insert data into the `user_interface` table
    $sql = "INSERT INTO user_interface (first_name, last_name, address, phone_number, email, occupation, title, message)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssssss", $first_name, $last_name, $address, $phone_number, $email, $occupation, $title, $message);

        if ($stmt->execute()) {
            // Redirect to a success page or show a success message
            echo "<p class='alert alert-success'>Note submitted successfully!</p>";
        } else {
            echo "<p class='alert alert-danger'>Error submitting the note: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p class='alert alert-danger'>Error preparing statement: " . $conn->error . "</p>";
    }
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Successful</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container text-center mt-5">
        <h1>Thank You!</h1>
        <p>Your note has been submitted successfully.</p>
        <a href="user_interface.php" class="btn btn-primary">Go Back to Home</a>
    </div>
</body>
</html>
