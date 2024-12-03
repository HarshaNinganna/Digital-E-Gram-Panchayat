<?php
// Database connection setup
$servername = "localhost";  // Change as needed
$username = "root";         // Change as needed
$password = "";             // Change as needed
$dbname = "digital_e_gram_panchayat"; // Database name

// Initialize variables
$message = '';
$user_id = '';
$password = '';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and fetch form data
    $user_id = htmlspecialchars($_POST['user_id']);
    $password = htmlspecialchars($_POST['password']);
    
    // Prepare SQL query to check for the user_id and password in the users table
    $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    
    if ($stmt) {
        $stmt->bind_param("s", $user_id);  // Bind the user_id parameter
        $stmt->execute();
        $stmt->store_result();

        // Check if user_id exists in the database
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashed_password);
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // Store session information and redirect
                session_start();
                $_SESSION['user_id'] = $user_id;  // Save user_id in session
                header("Location: ../user/user_interface.php");  // Redirect to user interface after login
                exit();
            } else {
                $message = "Invalid user ID or password.";
            }
        } else {
            $message = "Invalid user ID or password.";
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
    <title>User Login - Digital E Gram Panchayat</title>
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
        <h2 class="text-center mb-4">User Login - Digital E Gram Panchayat</h2>

        <?php if ($message): ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="user_login.php" method="post">
            <div class="form-group mb-3">
                <label for="user_id">User ID:</label>
                <input type="text" class="form-control" id="user_id" name="user_id" value="<?php echo $user_id; ?>" required>
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
