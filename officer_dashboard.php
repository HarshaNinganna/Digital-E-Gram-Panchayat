<?php
// Start the session
session_start();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Officer Dashboard - Digital E Gram Panchayat</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<header class="bg-dark text-white py-3">
    <div class="container">
        <h1 class="text-center mb-0">Officer Dashboard - Digital E Gram Panchayat</h1>
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
                        <a class="nav-link active" href="Officer_dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_villages.php">Manage Villages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_staff.php">Manage Staff</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="user_verification.php">User Verification</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="service_requests.php">Service Requests</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_reports.php">View Reports</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="notifications.php">Notifications</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-9 px-4">
            <h2>Welcome, Officer!</h2>
            <p class="lead">Here you can manage various aspects of the Digital E Gram Panchayat system.</p>

            <!-- Dashboard Content (e.g., statistics, recent activity) -->
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <h5 class="card-title">Total Villages</h5>
                            <p class="card-text">50</p> <!-- Replace with dynamic value -->
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h5 class="card-title">Verified Users</h5>
                            <p class="card-text">450</p> <!-- Replace with dynamic value -->
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <h5 class="card-title">Pending Service Requests</h5>
                            <p class="card-text">30</p> <!-- Replace with dynamic value -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Section -->
            <h3>Recent Activity</h3>
            <ul class="list-group mb-4">
                <li class="list-group-item">User A verified by Officer</li>
                <li class="list-group-item">New report submitted for Village B</li>
                <li class="list-group-item">Service request for subsidy processed</li>
                <!-- Add more recent activity items dynamically -->
            </ul>

            <!-- Verification and Service Request Sections -->
            <div class="row">
                <div class="col-md-6">
                    <h4>User Verification Requests</h4>
                    <ul class="list-group">
                        <li class="list-group-item">User C <button class="btn btn-sm btn-success ml-2">Approve</button> <button class="btn btn-sm btn-danger">Reject</button></li>
                        <li class="list-group-item">User D <button class="btn btn-sm btn-success ml-2">Approve</button> <button class="btn btn-sm btn-danger">Reject</button></li>
                        <!-- Populate verification requests dynamically -->
                    </ul>
                </div>
                <div class="col-md-6">
                    <h4>Pending Service Requests</h4>
                    <ul class="list-group">
                        <li class="list-group-item">Water connection request by User E</li>
                        <li class="list-group-item">Application for health camp by Village F</li>
                        <!-- Populate service requests dynamically -->
                    </ul>
                </div>
            </div>

            <!-- Notifications Section -->
            <h4 class="mt-5">Send Notifications</h4>
            <form action="send_notification.php" method="POST">
                <div class="form-group">
                    <label for="notificationText">Notification Text:</label>
                    <textarea class="form-control" id="notificationText" name="notificationText" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send Notification</button>
            </form>
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
