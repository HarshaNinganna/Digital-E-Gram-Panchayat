<?php
// Database connection setup
$servername = "localhost"; // Change as needed
$username = "root";        // Change as needed
$password = "";            // Change as needed
$dbname = "digital_e_gram_panchayat"; // Database name

// Initialize variables
$message = '';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and fetch form data
    $officerID = htmlspecialchars($_POST['officerID']);
    $password = htmlspecialchars($_POST['password']);
    
    // Prepare and execute SQL query to check for the officer's ID
    $stmt = $conn->prepare("SELECT officer_id, password FROM officers WHERE officer_id = ?");
    if ($stmt) {
        $stmt->bind_param("s", $officerID); // Bind the officerID parameter
        $stmt->execute();
        $stmt->store_result();

        // Check if a user exists
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($retrievedOfficerID, $hashed_password);
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // Store session information and redirect
                session_start();
                $_SESSION['officer_id'] = $retrievedOfficerID; // Save officer ID in session
                header("Location: ../admin/officer_dashboard.php"); // Redirect to officer_dashboard.php
                exit();
            } else {
                $message = "Invalid OfficerID or password.";
            }
        } else {
            $message = "Invalid OfficerID or password.";
        }

        // Close statement
        $stmt->close();
    } else {
        $message = "Error preparing the SQL statement.";
    }
}

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Officer Login - Digital E Gram Panchayat</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/user_style.css">
</head>
<body>

<header class="bg-dark text-white py-3">
    <div class="container">
        <h1 class="text-center mb-0">Digital E Gram Panchayat</h1>
        <p class="text-center">Empowering Rural Communities</p>
    </div>
</header>

<div class="container mt-5">
    <div class="login-container">
        <h2 class="text-center mb-4">Officer Login - Digital E Gram Panchayat</h2>

        <?php if ($message): ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="officer_login.php" method="post">
            <div class="form-group mb-3">
                <label for="officerID">Officer ID:</label>
                <input type="text" class="form-control" id="officerID" name="officerID" required>
            </div>

            <div class="form-group mb-3">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>

        <div class="text-center mt-3">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</div>

<footer class="bg-dark text-white py-3 mt-5">
    <div class="container text-center">
        <p>&copy; 2024 Digital E Gram Panchayat. All Rights Reserved.</p>
    </div>
</footer>

</body>
</html>
