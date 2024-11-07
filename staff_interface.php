<?php
// Start the session
session_start();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Interface - Digital E Gram Panchayat</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/staff_interface.css">
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

        <!-- Main Content -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-9 px-4">
            <h2>Welcome, Staff Member!</h2>
            <p class="lead">Manage requests and oversee assigned villages through the Digital E Gram Panchayat system.</p>

            <!-- Overview Section -->
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <h5 class="card-title">Assigned Villages</h5>
                            <p class="card-text">15</p> <!-- Replace with dynamic value -->
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h5 class="card-title">Pending Requests</h5>
                            <p class="card-text">8</p> <!-- Replace with dynamic value -->
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <h5 class="card-title">Issues Reported</h5>
                            <p class="card-text">2</p> <!-- Replace with dynamic value -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Requests Section -->
            <h3>Recent Requests</h3>
            <ul class="list-group">
                <li class="list-group-item">Road repair request in Village B</li>
                <li class="list-group-item">Water supply improvement request in Village A</li>
                <li class="list-group-item">Electricity connection issue in Village C</li>
                <!-- Add more requests dynamically -->
            </ul>
        </main>
    </div>
</div>

<footer class="bg-secondary text-white py-3 mt-5">
    <div class="container text-center">
        <p>&copy; 2024 Digital E Gram Panchayat. All Rights Reserved.</p>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
