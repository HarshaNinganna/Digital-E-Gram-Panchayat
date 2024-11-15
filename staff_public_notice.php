<?php
session_start();
$message = '';
$notices = []; // Array to hold public notices

// Database connection
$conn = new mysqli("localhost", "root", "", "digital_e_gram_panchayat");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if staff is logged in
if (!isset($_SESSION['staff_id'])) {
    // Redirect to login page if staff is not logged in
    header("Location: officer_login.php");
    exit();
}

// Handle notice creation form submission for staff
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_notice'])) {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $staff_id = $_SESSION['staff_id'];  // Get the staff's ID from the session

    // Insert the new notice into the staff_notice table
    $stmt = $conn->prepare("INSERT INTO staff_notice (staff_id, title, description) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $staff_id, $title, $description);

    if ($stmt->execute()) {
        $message = "Notice created successfully!";
    } else {
        $message = "Error creating notice: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch staff notices from the staff_notice table
$sql = "SELECT notice_id, title, description, created_at FROM staff_notice ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notices[] = $row;
    }
} else {
    $message = "No notices found.";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Notices - Digital E Gram Panchayat</title>
    <link rel="stylesheet" href="../assets/css/user_style.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-left">
            <h1>Digital E Gram Panchayat</h1>
        </div>
        <div class="header-right">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </header>

    <!-- Message Section -->
    <?php if ($message): ?>
        <div class="alert alert-info text-center mt-4"><?= $message; ?></div>
    <?php endif; ?>

    <!-- Public Notice Creation Form (For Staff) -->
    <div class="container mt-4">
        <h3>Create a New Staff Notice</h3>
        <form action="staff_public_notice.php" method="POST">
            <div class="form-group">
                <label for="title">Notice Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Notice Description</label>
                <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" name="create_notice">Create Notice</button>
        </form>
    </div>

    <!-- Staff Notices Section -->
    <div class="container mt-4">
        <h2 class="text-center">Staff Notices</h2>
        <?php if (!empty($notices)): ?>
            <div class="row">
                <?php foreach ($notices as $notice): ?>
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($notice['title']); ?></h5>
                                <p class="card-text"><?= nl2br(htmlspecialchars($notice['description'])); ?></p>
                                <p class="text-muted"><?= date("d M Y", strtotime($notice['created_at'])); ?></p>
                                <a href="view_notice.php?notice_id=<?= $notice['notice_id']; ?>" class="btn btn-info">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-muted">No staff notices available at the moment.</p>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="text-center mt-4">
        <p>&copy; 2024 Digital E Gram Panchayat | All Rights Reserved</p>
    </footer>

    <!-- Bootstrap JS, jQuery, and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
