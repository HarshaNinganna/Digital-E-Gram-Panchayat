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

// Fetch all applications with service details
$query = "SELECT applications.application_id, applications.applicant_name, applications.status, services.service_name
          FROM applications
          INNER JOIN services ON applications.service_id = services.service_id";

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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<header class="bg-dark text-white py-3">
    <div class="container">
        <h1 class="text-center mb-0">Update Application Status - Digital E Gram Panchayat</h1>
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
                        <a class="nav-link" href="create_service.php">Create Service</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_services.php">Manage Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="update_application_status.php">Update Application Status</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-9 px-4">
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
                        <th>Action</th>
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
                            <td></td>
                        </tr>
                    <?php endforeach; ?>
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
