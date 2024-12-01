<?php
// Start the session
session_start();

// Ensure the user is logged in as staff
if (!isset($_SESSION['staff_id'])) {
    header("Location: /Digital%20E%20Gram%20Panchayat/auth/staff_login.php"); // Redirect to login page if not logged in
    exit();
}

// Initialize the staff ID from session
$staff_id = $_SESSION['staff_id'];

// Initialize database connection
$conn = new mysqli("localhost", "root", "", "digital_e_gram_panchayat");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for approving or dissolving a note
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['note_id']) && isset($_POST['action'])) {
    $note_id = (int)$_POST['note_id'];
    $action = $_POST['action'];

    // Determine the SQL query based on the action
    if ($action === 'approve') {
        // Update the note's status to 'approved'
        $sql = "UPDATE notes SET status = 'approved' WHERE note_id = ?";
    } elseif ($action === 'dissolve') {
        // Update the note's status to 'dissolved'
        $sql = "UPDATE notes SET status = 'dissolved' WHERE note_id = ?";
    }

    // Prepare and execute the query
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $note_id);
        if ($stmt->execute()) {
            echo "<p class='alert alert-success'>Note has been updated successfully.</p>";
        } else {
            echo "<p class='alert alert-danger'>Error updating the note: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p class='alert alert-danger'>Error preparing statement: " . $conn->error . "</p>";
    }
}

// Fetch the notes submitted by users (with status)
$sql_notes = "SELECT note_id, first_name, last_name, message, created_at, status FROM notes ORDER BY created_at DESC";
$result_notes = $conn->query($sql_notes);

if ($result_notes && $result_notes->num_rows > 0) {
    $notes = [];
    while ($row = $result_notes->fetch_assoc()) {
        $notes[] = $row;
    }
} else {
    $notes = [];
}

// Fetch the assigned works for the logged-in staff
$sql_assigned_works = "
    SELECT 
        assigned_works.work_id,
        assigned_works.work_description,
        assigned_works.assigned_by,
        assigned_works.assigned_at,
        staff.first_name
    FROM 
        assigned_works
    INNER JOIN 
        staff 
    ON 
        assigned_works.staff_id = staff.staff_id
    WHERE 
        assigned_works.staff_id = ?
    ORDER BY 
        assigned_works.assigned_at DESC";

$assigned_works = [];
if ($stmt = $conn->prepare($sql_assigned_works)) {
    $stmt->bind_param("s", $staff_id); // Use the staff_id to fetch assigned works
    $stmt->execute();
    $result_assigned_works = $stmt->get_result();

    while ($row = $result_assigned_works->fetch_assoc()) {
        $assigned_works[] = $row;
    }
    $stmt->close();
}

if (!isset($_SESSION['staff_id'])) {
    header("Location: /Digital%20E%20Gram%20Panchayat/auth/staff_login.php"); // Redirect to login page if not logged in
    exit();
}

// Initialize the staff ID from session
$staff_id = $_SESSION['staff_id'];

// Initialize database connection
$conn = new mysqli("localhost", "root", "", "digital_e_gram_panchayat");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle button click for dynamic login
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['staff_id']) && isset($_POST['secret_number'])) {
    $input_staff_id = $_POST['staff_id'];
    $input_secret_number = $_POST['secret_number'];

    // Verify staff credentials
    $sql_verify_staff = "SELECT * FROM staff WHERE staff_id = ? AND secret_number = ?";
    if ($stmt = $conn->prepare($sql_verify_staff)) {
        $stmt->bind_param("ss", $input_staff_id, $input_secret_number);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            // Login successful - set session and redirect
            $_SESSION['staff_id'] = $input_staff_id;
            header("Location: staff_dashboard.php"); // Redirect to staff dashboard
            exit();
        } else {
            $login_error = "Invalid staff ID or secret number.";
        }
        $stmt->close();
    } else {
        $login_error = "Error preparing login verification statement.";
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
    <title>Staff Dashboard - Digital E Gram Panchayat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/staff-style.css">
</head>
<body>

<header class="bg-dark text-white py-3">
    <div class="container">
        <h1 class="text-center mb-0">Staff Dashboard</h1>
    </div>
    <center>
    <div class="login">
            <a href="#home" class="login-btn">
                <i class="fas fa-home"></i> Home
            </a>
        </div>

        <div class="login">
            <a href="#note" class="login-btn">
                <i class="fas fa-note-sticky""></i> Manage Notes
            </a>
        </div>
        <div class="login">
            <a href="#assign_work" class="login-btn">
                <i class="fas fa-briefcase"></i> Assigned Works
            </a>
        </div>
        <div class="login">
            <a href="#quick" class="login-btn">
                <i class="fas fa-sign-out-alt"></i> Quick Login
            </a>
        </div>

        <div class="login">
        <a href="/Digital E Gram Panchayat/auth/logout.php" class="login-btn">
            <i class="fas fa-sign-in-alt"></i> Logout
        </a>
        </div>
</center>
</header>

<!-- Main Content -->
<div class="home" id="home">
<div class="container mt-4">
    <h2>Welcome, Staff!</h2>
    <p class="lead">Here you can manage user notes and notices.</p>

    <!-- Display User Notes -->
    <div class="note" id="note">
    <div class="container mt-4">
        <h3 class="mt-4">User Notes</h3>
        <?php if (!empty($notes)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Message</th>
                        <th>Submitted On</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($notes as $note): ?>
                        <tr>
                            <td><?= htmlspecialchars($note['first_name']); ?></td>
                            <td><?= htmlspecialchars($note['last_name']); ?></td>
                            <td><?= nl2br(htmlspecialchars($note['message'])); ?></td>
                            <td><?= date("d M Y", strtotime($note['created_at'])); ?></td>
                            <td><?= htmlspecialchars($note['status']); ?></td>
                            <td>
                            <form action="C:/xampp1/htdocs/Digital E Gram Panchayat/admin/manage_notes.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="note_id" value="<?= htmlspecialchars($note['note_id']); ?>">
                                    <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                                </form>
                                <form action="admin/manage_notes.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="note_id" value="<?= htmlspecialchars($note['note_id']); ?>">
                                    <button type="submit" name="action" value="dissolve" class="btn btn-danger btn-sm">Dissolve</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No user notes available at the moment.</p>
        <?php endif; ?>
    </div>

    <div class="container mt-4">
    <div class="aassign_work" id="assign_work">
        <h3 class="mt-4">Assigned Works</h3>
        <?php if (!empty($assigned_works)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Work Description</th>
                        <th>Assigned By</th>
                        <th>Assigned On</th>
                        <th>Staff Name</th> <!-- New Column -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($assigned_works as $work): ?>
                        <tr>
                            <td><?= htmlspecialchars($work['work_description']); ?></td>
                            <td><?= htmlspecialchars($work['assigned_by']); ?></td>
                            <td><?= date("d M Y", strtotime($work['assigned_at'])); ?></td>
                            <td><?= htmlspecialchars($work['first_name']); ?></td> <!-- Display Staff Name -->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No assigned works available at the moment.</p>
        <?php endif; ?>
    </div>

    <div class="container mt-4">
    <div class="quick" id="quick"></div>
        <h3>Staff Quick Login</h3>
        <div class="row">
        <?php 
        // Define unique names and corresponding pages for each button
        $buttons = [
            ["name" => "IT Support Staff", "page" => "it_coordinator.php"],
            ["name" => "Data Entry Operator", "page" => "data_entry_operator.php"],
            ["name" => "Citizens Services Helpdesk Operator", "page" => "Citizen_Services _Helpdesk_Operator.php"],
            ["name" => "Digital Service Operator", "page" => "digital_service_manager.php"],
            ["name" => "Financial Illustion Officer", "page" => "Financial_Inclusion_Officer.php"],
            ["name" => "Health & Sanitation Officer", "page" => "health_and_sanitation officer.php"],
            ["name" => "Social Welfare Officer", "page" => "social_welfare_officer.php"],
            ["name" => "Agriculture Staff Login", "page" => "agriculture_officer.php"],
            ["name" => "Project Coordinator", "page" => "project_coordinator.php"],
        ];

        foreach ($buttons as $index => $button): 
        ?>
            <div class="col-md-2 mb-3">
                <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#loginModal<?= $index + 1; ?>">
                    <?= htmlspecialchars($button['name']); ?>
                </button>
            </div>

            <!-- Modal for each button -->
            <div class="modal fade" id="loginModal<?= $index + 1; ?>" tabindex="-1" aria-labelledby="loginModalLabel<?= $index + 1; ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="staff_login_handler.php" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="loginModalLabel<?= $index + 1; ?>"><?= htmlspecialchars($button['name']); ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="staff_id<?= $index + 1; ?>">Staff ID</label>
                                    <input type="text" name="staff_id" id="staff_id<?= $index + 1; ?>" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="secret_number<?= $index + 1; ?>">Secret Number</label>
                                    <input type="password" name="secret_number" id="secret_number<?= $index + 1; ?>" class="form-control" required>
                                </div>
                                <!-- Hidden field to specify the redirection page -->
                                <input type="hidden" name="redirect_page" value="<?= htmlspecialchars($button['page']); ?>">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
</div>
        </div>
        <div>
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
        </div>
</footer>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
