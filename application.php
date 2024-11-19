<?php
// application.php

// Start a session (if needed for form handling or future user tracking)
session_start();

// Check if the service_id is passed as a query parameter
if (isset($_GET['service_id'])) {
    $service_id = htmlspecialchars($_GET['service_id']); // Secure the input
} else {
    // Redirect or show an error if service_id is not provided
    header("Location: service.php"); // Assuming 'services.php' lists all services
    exit;
}

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
    <p class="text-muted text-center">Service ID: <?= $service_id; ?></p>
    
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
