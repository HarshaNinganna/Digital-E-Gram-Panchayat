<?php
// Start the session
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['officer_id'])) {
    header("Location: /Digital%20E%20Gram%20Panchayat/auth/officer_login.php");
    exit();
}

// Initialize the officer ID from the session
$officer_id = $_SESSION['officer_id'];

// Initialize database connection
$conn = new mysqli("localhost", "root", "", "digital_e_gram_panchayat");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the notes submitted by users from `user_interface` table
$sql_notes = "SELECT * FROM user_interface ORDER BY created_at DESC";
$result_notes = $conn->query($sql_notes);

$notes = [];
if ($result_notes) {
    while ($row = $result_notes->fetch_assoc()) {
        $notes[] = $row;
    }
} else {
    $notes = [];
}

// Handle form submission for approving or deleting a note
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['note_id']) && isset($_POST['action'])) {
        $note_id = (int)$_POST['note_id'];
        $action = $_POST['action'];

        // Determine the SQL query based on the action
        $sql = "";
        if ($action === 'approve') {
            // Update the note's status to 'approved'
            $sql = "UPDATE user_interface SET status = 'approved' WHERE note_id = ?";
        } elseif ($action === 'delete') {
            // Delete the note
            $sql = "DELETE FROM user_interface WHERE note_id = ?";
        }

        if ($sql !== "") {
            // Prepare and execute the query
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("i", $note_id);
                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Note has been updated successfully.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Error updating the note: " . $stmt->error . "</div>";
                }
                $stmt->close();
            } else {
                echo "<div class='alert alert-danger'>Error preparing statement: " . $conn->error . "</div>";
            }
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
    <title>Manage Notes - Digital E Gram Panchayat</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<header class="bg-dark text-white py-3">
    <div class="container">
        <h1 class="text-center mb-0">Manage Notes - Digital E Gram Panchayat</h1>
    </div>
</header>

<div class="container mt-4">
    <h3>Notes</h3>
    <?php if (!empty($notes)): ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Created On</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notes as $note): ?>
                    <tr>
                        <td><?= htmlspecialchars($note['title']); ?></td>
                        <td><?= nl2br(htmlspecialchars($note['message'])); ?></td>
                        <td><?= date("d M Y", strtotime($note['created_at'])); ?></td>
                        <td><?= ucfirst(htmlspecialchars($note['status'])); ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="note_id" value="<?= $note['note_id']; ?>">
                                <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                                <button type="submit" name="action" value="delete" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No notes available at the moment.</div>
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
