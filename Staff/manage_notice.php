<?php
// Start the session
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['officer_id'])) {
    header("Location: /Digital%20E%20Gram%20Panchayat/auth/officer_login.php"); // Redirect to the login page if not logged in
    exit();
}

// Initialize the officer ID from session
$officer_id = $_SESSION['officer_id'];

// Initialize database connection
$conn = new mysqli("localhost", "root", "", "digital_e_gram_panchayat");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the notices submitted by users
$sql_notices = "SELECT * FROM public_notices ORDER BY created_at DESC"; // Fetch all notices, order by date
$result_notices = $conn->query($sql_notices);

if ($result_notices && $result_notices->num_rows > 0) {
    $notices = [];
    while ($row = $result_notices->fetch_assoc()) {
        $notices[] = $row;
    }
} else {
    $notices = [];
}

// Handle form submission for approving or dissolving a notice
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['notice_id']) && isset($_POST['action'])) {
        $notice_id = (int)$_POST['notice_id'];
        $action = $_POST['action'];

        // Determine the SQL query based on the action
        if ($action === 'approve') {
            // Update the notice's status to 'approved'
            $sql = "UPDATE public_notices SET status = 'approved' WHERE notice_id = ?";
        } elseif ($action === 'dissolve') {
            // Update the notice's status to 'dissolved'
            $sql = "UPDATE public_notices SET status = 'dissolved' WHERE notice_id = ?";
        }

        // Prepare and execute the query
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $notice_id);
            if ($stmt->execute()) {
                echo "<p class='alert alert-success'>Notice has been updated successfully.</p>";
            } else {
                echo "<p class='alert alert-danger'>Error updating the notice: " . $stmt->error . "</p>";
            }
            $stmt->close();
        } else {
            echo "<p class='alert alert-danger'>Error preparing statement: " . $conn->error . "</p>";
        }
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Notices - Digital E Gram Panchayat</title>
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
    <h3 class="mt-4">Notices</h3>
    <?php if (!empty($notices)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Created On</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notices as $notice): ?>
                    <tr>
                        <td><?= htmlspecialchars($notice['title']); ?></td>
                        <td><?= nl2br(htmlspecialchars($notice['description'])); ?></td>
                        <td><?= date("d M Y", strtotime($notice['created_at'])); ?></td>
                        <td><?= ucfirst($notice['status']); ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="notice_id" value="<?= $notice['notice_id']; ?>">
                                <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                                <button type="submit" name="action" value="dissolve" class="btn btn-danger btn-sm">Dissolve</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No notices available at the moment.</p>
    <?php endif; ?>
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
