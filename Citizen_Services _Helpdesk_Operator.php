<?php
session_start();

// Ensure the user is logged in and staff_id is available in the session
if (!isset($_SESSION['staff_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}

$staff_id = $_SESSION['staff_id'];
$message = '';

// Database connection
$conn = new mysqli("localhost", "root", "", "digital_e_gram_panchayat");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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

// Process form submissions for actions (Completed or Report)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['work_id'])) {
    $action = $_POST['action'];
    $work_id = intval($_POST['work_id']);

    if ($action === 'completed') {
        // Handle file upload for completed action
        if (isset($_FILES['work_document']) && $_FILES['work_document']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/work_documents/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $file_name = time() . '_' . basename($_FILES['work_document']['name']);
            $file_path = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['work_document']['tmp_name'], $file_path)) {
                // Update work status in the database
                $stmt = $conn->prepare("UPDATE assigned_works SET status = 'completed', documentation_path = ? WHERE work_id = ?");
                $stmt->bind_param("si", $file_path, $work_id);
                if ($stmt->execute()) {
                    $message = "Work marked as completed successfully.";
                } else {
                    $message = "Error updating the work status in the database.";
                }
                $stmt->close();
            } else {
                $message = "Error uploading documentation.";
            }
        } else {
            $message = "No valid documentation uploaded.";
        }
    } elseif ($action === 'report') {
        // Handle reporting with a reason
        $report_reason = htmlspecialchars($_POST['report_reason']);
        $stmt = $conn->prepare("UPDATE assigned_works SET status = 'reported', report_reason = ? WHERE work_id = ?");
        $stmt->bind_param("si", $report_reason, $work_id);
        if ($stmt->execute()) {
            $message = "Work reported successfully.";
        } else {
            $message = "Error updating the work status in the database.";
        }
        $stmt->close();
    }
}
// Fetch staff-specific notices from the staff_notice table
$sql = "SELECT notice_id, title, description, created_at FROM staff_notice ORDER BY created_at DESC";

$staff_notices = [];
if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $staff_notices[] = $row;  // Storing each notice in the array
    }
    $result->free();  // Free the result set
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DEGPS-Citizen Service Helpdesk Operator</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/staff-style.css">
</head>
<body>

<header class="bg-secondary text-white py-3">
    <div class="container d-flex justify-content-between align-items-center">
        <h1 class="mb-0">Staff-Citizen Service Helpdesk Operator</h1>
        <!-- Logout Button -->
        <div class="login">
            <a href="#" class="login-btn">
                <i class="fas fa-home"></i> Home
            </a>
        </div>
        
        <div class="login">
            <a href="/Digital E Gram Panchayat/auth/logout.php" class="login-btn">
                <i class="fas fa-sign-in-alt"></i> Logout
            </a>
        </div>
    </div>
</header>

<div class="container mt-4">
    <h3 class="mt-4">Assigned Works</h3>
    <?php if (!empty($assigned_works)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Work Description</th>
                    <th>Assigned By</th>
                    <th>Assigned On</th>
                    <th>Staff Name</th> <!-- New Column -->
                    <th>Actions</th> <!-- Actions Column -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($assigned_works as $index => $work): ?>
                    <tr>
                        <td><?= htmlspecialchars($work['work_description']); ?></td>
                        <td><?= htmlspecialchars($work['assigned_by']); ?></td>
                        <td><?= date("d M Y", strtotime($work['assigned_at'])); ?></td>
                        <td><?= htmlspecialchars($work['first_name']); ?></td>
                        <td>
                            <!-- Action Buttons -->
                            <button class="btn btn-success" data-toggle="modal" data-target="#completedModal<?= $index; ?>">Completed</button>
                            <button class="btn btn-danger" data-toggle="modal" data-target="#reportModal<?= $index; ?>">Report</button>
                        </td>
                    </tr>

                    <!-- Completed Modal -->
                    <div class="modal fade" id="completedModal<?= $index; ?>" tabindex="-1" aria-labelledby="completedModalLabel<?= $index; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="process_work_status.php" method="POST" enctype="multipart/form-data">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="completedModalLabel<?= $index; ?>">Mark Work as Completed</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="work_id" value="<?= $work['work_id']; ?>">
                                        <div class="form-group">
                                            <label for="work_document<?= $index; ?>">Upload Documentation</label>
                                            <input type="file" name="work_document" id="work_document<?= $index; ?>" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" name="action" value="completed" class="btn btn-success">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Report Modal -->
                    <div class="modal fade" id="reportModal<?= $index; ?>" tabindex="-1" aria-labelledby="reportModalLabel<?= $index; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="process_work_status.php" method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="reportModalLabel<?= $index; ?>">Report Work with a Reason</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="work_id" value="<?= $work['work_id']; ?>">
                                        <div class="form-group">
                                            <label for="report_reason<?= $index; ?>">Reason</label>
                                            <textarea name="report_reason" id="report_reason<?= $index; ?>" class="form-control" rows="4" required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" name="action" value="report" class="btn btn-danger">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No assigned works available at the moment.</p>
    <?php endif; ?>
</div>
<!-- Staff Public Notices Section -->
<div class="container mt-4">
    <h2 class="text-center">Staff Public Notices</h2>
    <?php if (!empty($staff_notices)): ?>
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Notice ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Created At</th>
                    <th>Actions</th> <!-- Add action column if necessary -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($staff_notices as $notice): ?>
                    <tr>
                        <td><?= htmlspecialchars($notice['notice_id']); ?></td>
                        <td><?= htmlspecialchars($notice['title']); ?></td>
                        <td>
                            <button 
                                class="btn btn-info btn-sm" 
                                onclick="toggleDescription('desc-<?= $notice['notice_id']; ?>')">
                                View
                            </button>
                            <div 
                                id="desc-<?= $notice['notice_id']; ?>" 
                                class="notice-description mt-2 p-2 text-muted" 
                                style="display: none; border: 1px solid #ddd; border-radius: 4px;">
                                <?= nl2br(htmlspecialchars($notice['description'])); ?>
                            </div>
                        </td>
                        <td><?= date("d M Y", strtotime($notice['created_at'])); ?></td>
                        <td>
                            <!-- You can add action buttons here (e.g., Mark as Read, etc.) -->
                            <button class="btn btn-success btn-sm">Mark as Read</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center text-muted">No staff public notices available at the moment.</p>
    <?php endif; ?>
</div>

<!-- JavaScript -->
<script>
    // Function to toggle the visibility of the description
    function toggleDescription(id) {
        const element = document.getElementById(id);
        if (element.style.display === "none" || element.style.display === "") {
            element.style.display = "block";
        } else {
            element.style.display = "none";
        }
    }
</script>
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
<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
