<?php
session_start();

// Database connection setup
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "digital_e_gram_panchayat";

$message = '';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $staffID = htmlspecialchars($_POST['staffID']);
    $password = htmlspecialchars($_POST['password']);

    // Prepare SQL query to check staff ID and fetch hashed password
    $stmt = $conn->prepare("SELECT password FROM staff WHERE staff_id = ?");
    
    if ($stmt) {
        $stmt->bind_param("s", $staffID);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                // Password is correct; regenerate session ID and store user ID in session
                session_regenerate_id(true);
                $_SESSION['staff_id'] = $staffID;
                header("Location: ../staff/staff_interface.php");
                exit();
            } else {
                $message = "Invalid Staff ID or password.";
            }
        } else {
            $message = "Invalid Staff ID or password.";
        }
        $stmt->close();
    } else {
        $message = "Error preparing the SQL statement.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login - Digital E Gram Panchayat</title>
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
        <h2 class="text-center mb-4">Staff Login</h2>

        <?php if ($message): ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="staff_login.php" method="post">
            <div class="form-group mb-3">
                <label for="staffID">Staff ID:</label>
                <input type="text" class="form-control" id="staffID" name="staffID" required>
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
