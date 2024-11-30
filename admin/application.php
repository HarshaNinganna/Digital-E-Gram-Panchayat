<?php
// Start session
session_start();

// Initialize database connection
$conn = new mysqli("localhost", "root", "", "digital_e_gram_panchayat");

// Check for database connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the service_id is passed as a query parameter
if (isset($_GET['service_id'])) {
    $service_id = (int)$_GET['service_id']; // Ensure service_id is treated as an integer

    // Fetch service details from the database
    $stmt = $conn->prepare("SELECT service_id, service_name FROM services WHERE service_id = ?");
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $stmt->store_result();

    // If no matching service found, show error
    if ($stmt->num_rows == 0) {
        die("Invalid service ID. Please try again.");
    } else {
        // Fetch the service details
        $stmt->bind_result($fetched_service_id, $service_name);
        $stmt->fetch();
        $stmt->close();
    }
} else {
    // Redirect or show an error if service_id is not provided
    die("No service ID provided.");
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Application</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Apply for Service</h2>
    <p class="text-muted text-center">Service Name: <?= htmlspecialchars($service_name); ?></p>

    <!-- Application Form -->
    <form action="submit_application.php" method="post">
        <input type="hidden" name="service_id" value="<?= $service_id; ?>">
        
        <div class="form-group">
            <label for="name">Your Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
        </div>
        
        <div class="form-group">
            <label for="email">Your Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
        </div>
        
        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter your phone number" required>
        </div>
        
        <div class="form-group">
            <label for="message">Message/Additional Information</label>
            <textarea class="form-control" id="message" name="message" rows="4" placeholder="Enter any additional information"></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary btn-block">Submit Application</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
