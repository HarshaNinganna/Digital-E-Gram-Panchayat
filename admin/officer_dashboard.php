<?php
// Start the session
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['officer_id'])) {
    header("Location: /Digital%20E%20Gram%20Panchayat/admin/officer_login.php"); // Redirect to the login page if not logged in
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
// Initialize database connection
$conn = new mysqli("localhost", "root", "", "digital_e_gram_panchayat");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch staff members for selection in dropdown
$sql_staff = "SELECT staff_id, first_name, last_name FROM staff";
$result_staff = $conn->query($sql_staff);
$staff_members = [];

if ($result_staff && $result_staff->num_rows > 0) {
    while ($row = $result_staff->fetch_assoc()) {
        $staff_members[] = $row;
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

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Officer Dashboard - Digital E Gram Panchayat</title>
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
        <a href="http://localhost/Digital%20E%20Gram%20Panchayat/admin/public_notice.php" class="login-btn">
                <i class="fas fa-pen-to-square"></i> Public Notice
            </a>
        </div>
        <div class="login">
        <a href="http://localhost/Digital%20E%20Gram%20Panchayat/admin/staff_public_notice.php" class="login-btn">
                <i class="fas fa-square-pen"></i> Staff Notice
            </a>
        </div>

        <div class="login">
        <a href="/Digital E Gram Panchayat/auth/logout.php" class="login-btn">
            <i class="fas fa-sign-in-alt"></i> Logout
        </a>
        </div>
</center>
</header>
<div class="home" id="home">
<div class="container mt-4">
    <main>
        <h2>Welcome, Officer!</h2>
        <p class="lead">Here you can manage various services and update application statuses.</p>

        <div class="container mt-4">
        <div class="notes" id="notes">   
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
                                    <form action="manage_notes.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="note_id" value="<?= htmlspecialchars($note['note_id']); ?>">
                                        <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                                    </form>
                                    <form action="manage_notes.php" method="POST" style="display:inline;">
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
    </main>
</div>
<div class="work" id="work">
<div class="container mt-5">
    <h2 class="text-center mb-4">Assign Work to Staff</h2>

    <form action="assign_work.php" method="POST">
        <div class="row mb-3">
            <!-- Staff Member Dropdown -->
            <div class="col-md-6">
                <label for="staff_id" class="form-label">Select Staff</label>
                <select id="staff_id" name="staff_id" class="form-control" required>
                    <option value="">Select Staff</option>
                    <?php foreach ($staff_members as $staff): ?>
                        <option value="<?= $staff['staff_id']; ?>">
                            <?= htmlspecialchars($staff['first_name'] . ' ' . $staff['last_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Work Description -->
            <div class="col-md-6">
                <label for="work_description" class="form-label">Work Description</label>
                <textarea id="work_description" name="work_description" class="form-control" rows="3" required></textarea>
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Assign Work</button>
        </div>
    </form>
</div>

<!-- Write Notice Section -->
<div class="notice" id="notice">
<div class="container mt-5">
    <h2 class="text-center mb-4">Write a Notice</h2>
    
    <div class="row justify-content-center">
        <!-- Staff Notice Button -->
        <div class="col-md-3 mb-3">
            <a href="staff_public_notice.php" class="btn btn-primary btn-block">
                Staff Notice
            </a>
        </div>

        <!-- Public Notice Button -->
        <div class="col-md-3 mb-3">
            <a href="public_notice.php" class="btn btn-success btn-block">
                Public Notice
            </a>
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
</body>
</html>
