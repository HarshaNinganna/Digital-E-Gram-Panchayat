<?php
// Start the session
session_start();

// Ensure the officer is logged in
if (!isset($_SESSION['officer_id'])) {
    header("Location: officer_login.php"); // Redirect to login if not logged in
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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<header class="bg-dark text-white py-3">
    <div class="container">
        <h1 class="text-center mb-0">Manage Services - Digital E Gram Panchayat</h1>
    </div>
</header>

<div class="container mt-4">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 bg-light sidebar py-4">
            <div class="sidebar-sticky">
                <h5>Menu</h5>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="Officer_dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="manage_services.php">Manage Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="update_application_status.php">Update Application Status</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-9 px-4">
            <h2>Manage Services</h2>

            <!-- Display message -->
            <?php if (!empty($message)): ?>
                <div class="alert alert-info"><?php echo $message; ?></div>
            <?php endif; ?>

            <!-- Display Existing Services -->
            <h3 class="mt-5">Existing Services</h3>
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
                <p class="text-muted">No services found. Please create a new service.</p>
            <?php endif; ?>
        </main>
    </div>
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

<footer class="bg-dark text-white py-3 mt-5">
    <div class="container text-center">
        <p>&copy; 2024 Digital E Gram Panchayat. All Rights Reserved.</p>
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
