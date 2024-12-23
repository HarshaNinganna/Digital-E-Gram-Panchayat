<?php
// Initialize variables
$message = '';
$email = '';
$password = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and fetch form data
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    
    // Example message for demonstration (validate user credentials in the database)
    if ($email == 'user@example.com' && $password == 'password123') {
        // Redirect to dashboard or homepage after successful login
        header("Location: dashboard.php");
        exit();
    } else {
        $message = "Invalid email or password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Digital E Gram Panchayat</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/auth.css">
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
        <h2 class="text-center mb-4">Login - Digital E Gram Panchayat</h2>

        <?php if ($message): ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="user_login.php" method="post">
            <div class="form-group mb-3">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" required>
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
