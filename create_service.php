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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $service_name = $_POST['service_name'];
    $service_description = $_POST['service_description'];
    $service_category = $_POST['service_category'];

    // Validate the inputs
    if (empty($service_name) || empty($service_description) || empty($service_category)) {
        $message = "All fields are required.";
    } else {
        // Prepare SQL statement to insert service data
        $stmt = $conn->prepare("INSERT INTO services (service_name, service_description, service_category) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $service_name, $service_description, $service_category);

        if ($stmt->execute()) {
            $message = "Service created successfully.";
        } else {
            $message = "Error creating service: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Fetch all services
$services = [];
$result = $conn->query("SELECT * FROM services");
if ($result && $result->num_rows > 0) {
    $services = $result->fetch_all(MYSQLI_ASSOC);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Service - Digital E Gram Panchayat</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<header class="bg-dark text-white py-3">
    <div class="container">
        <h1 class="text-center mb-0">Create Service - Digital E Gram Panchayat</h1>
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
                        <a class="nav-link active" href="create_service.php">Create Service</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="update_service.php">Manage Service</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="public_notice.php">Public Notice</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="staff_notice.php">Staff Notice</a>
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
            <h2>Create New Service</h2>

            <!-- Display message -->
            <?php if ($message): ?>
                <div class="alert alert-info"><?php echo $message; ?></div>
            <?php endif; ?>

            <form action="create_service.php" method="POST">
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
                <button type="submit" class="btn btn-primary">Create Service</button>
            </form>

            <hr>
            <h3>Existing Services</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Service ID</th>
                        <th>Service Name</th>
                        <th>Service Description</th>
                        <th>Service Category</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($services)): ?>
                        <?php foreach ($services as $service): ?>
                            <tr>
                                <td><?php echo $service['service_id']; ?></td>
                                <td><?php echo $service['service_name']; ?></td>
                                <td><?php echo $service['service_description']; ?></td>
                                <td><?php echo $service['service_category']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">No services found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </main>
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
</body>
</html>
