<?php
session_start();
include('../includes/db.php'); // Relative path to db.php

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Prepared statement to prevent SQL injection
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password with hashed password in database
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] == 'officer') {
                header("Location: ../admin/dashboard.php");
            } elseif ($user['role'] == 'staff') {
                header("Location: ../staff/dashboard.php");
            } elseif ($user['role'] == 'user') {
                header("Location: ../user/dashboard.php");
            }
            exit;
        } else {
            $message = "Invalid username or password.";
        }
    } else {
        $message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Digital E Gram Panchayat</title>
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>
    <div class="login-container">
        <h2>Login - Digital E Gram Panchayat</h2>
        <?php if ($message): ?>
            <div class="error-message"><?php echo $message; ?></div>
        <?php endif; ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="login-btn">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>
