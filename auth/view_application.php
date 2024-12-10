<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "digital_e_gram_panchayat";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch user, staff, and officer details
function fetchAllDetails($conn) {
    // Query to fetch all records for users, staff, and officers
    $query = "
        SELECT 'user' AS role, user_id AS id, first_name, last_name, address, email, phone, photo FROM users
        UNION 
        SELECT 'staff' AS role, staff_id AS id, first_name, last_name, address, email, phone, photo FROM staff
        UNION 
        SELECT 'officer' AS role, officer_id AS id, first_name, last_name, address, email, phone, photo FROM officers
    ";

    // Execute the query directly without prepare since it's a simple query
    $result = $conn->query($query);

    // Check if the query execution failed
    if ($result === false) {
        die("Error executing query: " . $conn->error);
    }

    return $result;
}

// Start the session
session_start();

// Display messages passed from other pages
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';

// Fetch all details from users, staff, and officers
$details = fetchAllDetails($conn);

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Application - Digital E Gram Panchayat</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>
<header class="bg-secondary text-white py-3">
    <div class="container">
        <h1 class="text-center mb-0">View Application - Digital E Gram Panchayat</h1>
    </div>
</header>

<div class="container my-4">
    <?php if ($message): ?>
        <p class="alert alert-info"><?php echo $message; ?></p>
    <?php endif; ?>

    <?php if ($details && $details->num_rows > 0): ?>
        <h3>All Applications</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Address</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Photo</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $details->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo ucfirst(htmlspecialchars($row['role'])); ?></td>
                        <td>
                            <?php if (!empty($row['photo'])): ?>
                                <img src="<?php echo htmlspecialchars($row['photo']); ?>" alt="Photo" width="100" height="100">
                            <?php else: ?>
                                No photo uploaded.
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="alert alert-warning">No applications found.</p>
    <?php endif; ?>

    <p><a href="register.php" class="btn btn-primary">Back to Registration</a></p>
</div>

<footer class="bg-secondary text-white py-3 mt-5">
    <div class="container text-center">
        <p>&copy; 2024 Digital E Gram Panchayat. All Rights Reserved.</p>
    </div>
</footer>

</body>
</html>
