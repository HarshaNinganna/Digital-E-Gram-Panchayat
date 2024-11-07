<?php
// Start the session
session_start();

// Check if the user is logged in; if not, redirect to the login page

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
                        <a class="nav-link" href="view_reports.php">View Reports</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="settings.php">Settings</a>
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
                            <h5 class="card-title">Active Staff Members</h5>
                            <p class="card-text">120</p> <!-- Replace with dynamic value -->
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <h5 class="card-title">Reports Pending</h5>
                            <p class="card-text">15</p> <!-- Replace with dynamic value -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Section -->
            <h3>Recent Activity</h3>
            <ul class="list-group">
                <li class="list-group-item">Village record updated by Officer A</li>
                <li class="list-group-item">New report submitted by Staff B</li>
                <li class="list-group-item">Staff C assigned to Village D</li>
                <!-- Add more recent activity items dynamically -->
            </ul>
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
