<?php
// Include database connection
include '../includes/db_connect.php'; // Adjust the path to your db_connect.php file

// Check if service_id is passed via GET
if (isset($_GET['service_id']) && !empty($_GET['service_id'])) {
    $service_id = htmlspecialchars($_GET['service_id']); // Sanitize the input

    // Assuming there's a logged-in user, get their user_id (update with your session/user logic)
    session_start();
    if (!isset($_SESSION['user_id'])) {
        die("You must be logged in to apply for a service.");
    }
    $user_id = $_SESSION['user_id'];

    // Check if the user has already applied for this service
    $stmt = $db->prepare("SELECT * FROM applications WHERE user_id = ? AND service_id = ?");
    $stmt->bind_param("ii", $user_id, $service_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "You have already applied for this service.";
    } else {
        // Insert the application into the database
        $stmt = $db->prepare("INSERT INTO applications (user_id, service_id, application_date) VALUES (?, ?, NOW())");
        $stmt->bind_param("ii", $user_id, $service_id);

        if ($stmt->execute()) {
            echo "Application submitted successfully!";
        } else {
            echo "Failed to submit the application. Please try again.";
        }
    }

    $stmt->close();
} else {
    echo "Invalid service ID.";
}

// Close the database connection
$db->close();
?>
