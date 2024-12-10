<?php 
// Start the session
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['officer_id'])) {
    die("Officer not logged in or session expired.");
}

// Database connection
$conn = new mysqli("localhost", "root", "", "digital_e_gram_panchayat");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form has been submitted to update the application status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['application_id']) && isset($_POST['status'])) {
    $application_id = $_POST['application_id'];
    $status = $_POST['status'];

    // Validate the inputs
    if (empty($application_id) || empty($status)) {
        $message = "Application ID and status are required.";
    } else {
        // Prepare SQL statement to update application status
        $stmt = $conn->prepare("UPDATE applications SET status = ? WHERE application_id = ?");
        $stmt->bind_param("si", $status, $application_id);

        if ($stmt->execute()) {
            $message = "Application status updated successfully.";
        } else {
            $message = "Error updating status: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Fetch all applications with service details and applicant name
$query = "SELECT applications.application_id, CONCAT(users.first_name, ' ', users.last_name) AS applicant_name, applications.status, services.service_name
          FROM applications
          INNER JOIN services ON applications.service_id = services.service_id
          INNER JOIN users ON applications.user_id = users.user_id";  // Join users table to get applicant name

$result = $conn->query($query);

// Check if query executed successfully
if ($result) {
    // Fetch and display data
    $applications = [];
    while ($row = $result->fetch_assoc()) {
        $applications[] = $row;
    }
} else {
    $message = "Error executing query: " . $conn->error;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Application Status - Digital E Gram Panchayat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/staff-style.css">
</head>
<body>

<header class="bg-dark text-white py-3">
    <div class="container">
        <h1 class="text-center mb-0">Panchayath Officer Dashboard</h1>
    </div>
    <center>
    <div class="login">
            <a href="#nome" class="login-btn">
                <i class="fas fa-home"></i> Home
            </a>
        </div>

        <div class="login">
            <a href="http://localhost/Digital%20E%20Gram%20Panchayat/admin/create_service.php" class="login-btn">
                <i class="fas fa-gear"></i> Create Service
            </a>
        </div>

        <div class="login">
        <a href="http://localhost/Digital%20E%20Gram%20Panchayat/admin/update_service.php" class="login-btn">
                <i class="fas fa-people-roof"></i> Manage Service
            </a>
        </div>
        <div class="login">
        <a href="http://localhost/Digital%20E%20Gram%20Panchayat/admin/update_application_status.php" class="login-btn">
                <i class="fas fa-envelope-open-text"></i> Update Application Status
            </a>
        </div>
        <div class="login">
        <a href="/Digital E Gram Panchayat/auth/logout.php" class="login-btn">
            <i class="fas fa-sign-in-alt"></i> Logout
        </a>
        </div>
</center>
</header>

<div class="container mt-4">
    <!-- Main Content -->
    <main role="main">
        <h2>Update Application Status</h2>

        <!-- Display message -->
        <?php if (isset($message)): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Table of Applications -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Application ID</th>
                    <th>Applicant Name</th>
                    <th>Service Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applications as $application): ?>
                    <tr>
                        <td><?php echo $application['application_id']; ?></td>
                        <td><?php echo $application['applicant_name']; ?></td>
                        <td><?php echo $application['service_name']; ?></td>
                        <td>
                            <form action="update_application_status.php" method="POST">
                                <select name="status" class="form-control" required>
                                    <option value="Pending" <?php echo ($application['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                    <option value="Approved" <?php echo ($application['status'] == 'Approved') ? 'selected' : ''; ?>>Approved</option>
                                    <option value="Rejected" <?php echo ($application['status'] == 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
                                </select>
                                <input type="hidden" name="application_id" value="<?php echo $application['application_id']; ?>">
                                <button type="submit" class="btn btn-primary btn-sm mt-2">Update Status</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</div>

<footer class="footer bg-dark text-white py-5 mt-4">
    <div class="container">
        <!-- Footer Content -->
        <div class="row">
            <!-- Left Section: About Us or Contact Information -->
            <div class="col-md-4">
                <h4>About Gram Panchayat</h4>
                <p>We are committed to delivering digital governance and services for rural development. Join us in creating a digital future.</p>
            </div>

            <!-- Middle Section: Contact Information -->
            <div class="col-md-4">
                <h4>Contact Us</h4>
                <p>Email: <a href="mailto:info@grampanchayatservices.com" class="text-white">info@grampanchayatservices.com</a></p>
                <p>Phone: <a href="tel:+911234567890" class="text-white">+91 123 456 7890</a></p>
            </div>

            <!-- Right Section: Social Media Links -->
            <div class="col-md-4">
                <h4>Follow Us</h4>
                <div class="social-icons">
                    <a href="#" class="text-white"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom text-center mt-4">
            <p>&copy; 2024 Gram Panchayat Services | All Rights Reserved</p>
        </div>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
