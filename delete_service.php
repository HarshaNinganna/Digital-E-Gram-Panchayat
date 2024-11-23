<?php
// Start the session
session_start();

// Ensure the officer is logged in
if (!isset($_SESSION['officer_id'])) {
    header("Location: officer_login.php"); // Redirect to the login page if not logged in
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "digital_e_gram_panchayat");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

// Handle service deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_service'])) {
    $service_id = $_POST['service_id'];
    $reason = trim($_POST['delete_reason']);

    if (empty($reason)) {
        $message = "A reason is required to delete the service.";
    } else {
        // Log the deletion reason and remove the service
        $stmt = $conn->prepare("INSERT INTO service_deletion_logs (service_id, reason) VALUES (?, ?)");
        $stmt->bind_param("is", $service_id, $reason);

        if ($stmt->execute()) {
            // Delete the service from the `services` table
            $delete_stmt = $conn->prepare("DELETE FROM services WHERE service_id = ?");
            $delete_stmt->bind_param("i", $service_id);

            if ($delete_stmt->execute()) {
                $message = "Service deleted successfully.";
            } else {
                $message = "Error deleting service: " . $delete_stmt->error;
            }

            $delete_stmt->close();
        } else {
            $message = "Error logging deletion: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Fetch all services
$services_query = "SELECT service_id, service_name, service_description, service_category FROM services";
$services_result = $conn->query($services_query);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services - Digital E Gram Panchayat</title>
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
    <!-- Display message -->
    <?php if (!empty($message)): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>

    <!-- Display Existing Services -->
    <h3>Existing Services</h3>
    <?php if ($services_result && $services_result->num_rows > 0): ?>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>Service ID</th>
                    <th>Service Name</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($service = $services_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $service['service_id']; ?></td>
                        <td><?php echo $service['service_name']; ?></td>
                        <td><?php echo $service['service_description']; ?></td>
                        <td><?php echo $service['service_category']; ?></td>
                        <td>
                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal" 
                                data-id="<?php echo $service['service_id']; ?>">
                                Delete
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-muted">No services found. Please create a new service.</p>
    <?php endif; ?>
</div>

<!-- Delete Service Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="manage_services.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Service</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="service_id" name="service_id">
                    <div class="form-group">
                        <label for="delete_reason">Reason for Deletion</label>
                        <textarea class="form-control" id="delete_reason" name="delete_reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="delete_service" class="btn btn-danger">Confirm Delete</button>
                </div>
            </form>
        </div>
    </div>
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
<script>
    // Pass service ID to the delete modal
    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var id = button.data('id');

        var modal = $(this);
        modal.find('#service_id').val(id);
    });
</script>
</body>
</html>
