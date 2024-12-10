<?php
// Start the session
session_start();

// Ensure the officer is logged in
if (!isset($_SESSION['officer_id'])) {
    header("Location: /Digital E Gram Panchayat/auth/officer_login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "digital_e_gram_panchayat");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_service'])) {
    // Get form data
    $service_id = $_POST['service_id'];
    $service_name = $_POST['service_name'];
    $service_description = $_POST['service_description'];
    $service_category = $_POST['service_category'];

    // Validate inputs
    if (empty($service_name) || empty($service_description) || empty($service_category)) {
        $message = "All fields are required.";
    } else {
        // Prepare SQL statement to update service data
        $stmt = $conn->prepare("UPDATE services SET service_name = ?, service_description = ?, service_category = ? WHERE service_id = ?");
        $stmt->bind_param("sssi", $service_name, $service_description, $service_category, $service_id);

        if ($stmt->execute()) {
            $message = "Service updated successfully.";
        } else {
            $message = "Error updating service: " . $stmt->error;
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
    <h2 class="text-center">Manage Services</h2>

    <!-- Display message -->
    <?php if (!empty($message)): ?>
        <div class="alert alert-info text-center"><?php echo $message; ?></div>
    <?php endif; ?>

    
    <?php if ($services_result && $services_result->num_rows > 0): ?>
        <table class="table table-striped">
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
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal" 
                                data-id="<?php echo $service['service_id']; ?>" 
                                data-name="<?php echo $service['service_name']; ?>" 
                                data-description="<?php echo $service['service_description']; ?>" 
                                data-category="<?php echo $service['service_category']; ?>">
                                Edit
                            </button>
                            <a href="delete_service.php?service_id=<?php echo $service['service_id']; ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Are you sure you want to delete this service?');">
                               Delete
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-muted text-center">No services found. Please create a new service.</p>
    <?php endif; ?>
</div>

<!-- Edit Service Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="manage_services.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Service</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="service_id" name="service_id">
                    <div class="form-group">
                        <label for="service_name">Service Name</label>
                        <input type="text" class="form-control" id="service_name" name="service_name" required>
                    </div>
                    <div class="form-group">
                        <label for="service_description">Service Description</label>
                        <textarea class="form-control" id="service_description" name="service_description" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="service_category">Service Category</label>
                        <input type="text" class="form-control" id="service_category" name="service_category" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="update_service" class="btn btn-primary">Save Changes</button>
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
    // Pass service data to the modal
    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var id = button.data('id');
        var name = button.data('name');
        var description = button.data('description');
        var category = button.data('category');

        var modal = $(this);
        modal.find('#service_id').val(id);
        modal.find('#service_name').val(name);
        modal.find('#service_description').val(description);
        modal.find('#service_category').val(category);
    });
</script>
</body>
</html>
