<?php
session_start();

// Database connection setup
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "digital_e_gram_panchayat";

// Initialize variables
$message = '';
$staff_id = '';
$secret_number = '';
$role = '';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission based on role-specific login
if (isset($_POST['login_role'])) {
    $role = $_POST['login_role'];
    $staff_id = htmlspecialchars($_POST['staff_id']);
    $secret_number = htmlspecialchars($_POST['secret_number']);

    // Prepare SQL query
    $stmt = $conn->prepare("SELECT designation FROM staff WHERE staff_id = ? AND secret_number = ?");

    if ($stmt) {
        $stmt->bind_param("ss", $staff_id, $secret_number);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($db_designation);
            $stmt->fetch();

            // Debugging: Output the fetched designation and selected role
            echo "Database Role: " . $db_designation . "<br>";
            echo "Role Selected: " . $role . "<br>";

            // Check if fetched designation matches the selected role
            if (strcasecmp(trim($db_designation), trim($role)) === 0) {
                // Store session info and redirect
                $_SESSION['staff_id'] = $staff_id;
                $_SESSION['designation'] = $db_designation;

                // Redirect based on role (can be dynamic depending on the role)
                header("Location: " . strtolower(str_replace(' ', '_', $db_designation)) . ".php");
                exit();
            } else {
                $message = "Invalid credentials for the selected role: " . htmlspecialchars($role) . ".";
            }
        } else {
            $message = "Invalid Staff ID or Secret Number.";
        }

        $stmt->close();
    } else {
        $message = "Error preparing the SQL statement.";
    }
} else {
    $message = "No login role specified.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Interface - Digital E Gram Panchayat</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<header class="bg-secondary text-white py-3">
    <div class="container">
        <h1 class="text-center mb-0">Staff Interface - Digital E Gram Panchayat</h1>
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
                        <a class="nav-link active" href="staff_interface.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_requests.php">Manage Requests</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_assigned_villages.php">View Assigned Villages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="report_issue.php">Report an Issue</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="staff_profile.php">My Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Login Section -->
        <div class="col-md-9">
            <h2>Select Your Role to Log In</h2>
            <div class="row">
                <?php 
                // Define roles
                $designation = [
                    "IT Coordinator", "Village Manager", "Data Entry Operator",
                    "Citizen Services Helpdesk Operator", "Digital Service Manager", 
                    "Financial Inclusion Officer", "Health and Sanitation Officer", 
                    "Social Welfare Officer", "Agriculture Officer", "Project Coordinator"
                ];

                // Create a button for each role with modal for login
                foreach ($designation as $role): 
                    $modal_id = strtolower(str_replace(' ', '_', $role)) . "_modal";
                ?>
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#<?= $modal_id; ?>">
                            <?= $role; ?>
                        </button>
                    </div>

                    <!-- Modal for each role -->
                    <div class="modal fade" id="<?= $modal_id; ?>" tabindex="-1" role="dialog" aria-labelledby="<?= $modal_id; ?>Label" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="<?= $modal_id; ?>Label"><?= $role; ?> Login</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="staff_interface.php" method="post">
                                        <input type="hidden" name="login_role" value="<?= $role; ?>">
                                        <div class="form-group">
                                            <label for="staff_id">Staff ID:</label>
                                            <input type="text" class="form-control" name="staff_id" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="secret_number">Secret Number:</label>
                                            <input type="password" class="form-control" name="secret_number" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Login</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Display error message if any -->
            <?php if ($message): ?>
                <div class="alert alert-danger mt-4"><?php echo $message; ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<footer class="bg-secondary text-white py-3 mt-5">
    <div class="container text-center">
        <p>&copy; 2024 Digital E Gram Panchayat. All Rights Reserved.</p>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
