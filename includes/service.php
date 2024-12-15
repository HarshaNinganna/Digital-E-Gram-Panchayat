<?php
// Include the database connection
include '../includes/db_connect.php'; // Adjust the relative path based on your file structure

// Check if database connection is established
if (!isset($db)) {
    die("Database connection not established. Please check db_connect.php.");
}

// Fetch services from the database
$query = "SELECT service_id, service_name, service_description, service_category FROM services";
$result = $db->query($query);

$services = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Services</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2 class="text-center">Available Services</h2>
    <?php if (!empty($services)): ?>
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Service ID</th>
                    <th>Service Name</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $service): ?>
                    <tr>
                        <td><?= htmlspecialchars($service['service_id']); ?></td>
                        <td><?= htmlspecialchars($service['service_name']); ?></td>
                        <td><?= htmlspecialchars($service['service_description']); ?></td>
                        <td><?= htmlspecialchars($service['service_category']); ?></td>
                        <td>
                            <a href="application.php?service_id=<?= htmlspecialchars($service['service_id']); ?>" 
                               class="btn btn-primary btn-sm">
                                Apply
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center text-muted">No services available at the moment.</p>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
