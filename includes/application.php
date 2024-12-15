<?php
include 'db.php';

function submitApplication($userId, $serviceId) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO applications (user_id, service_id, status) VALUES (?, ?, 'pending')");
    $stmt->bind_param("ii", $userId, $serviceId);
    return $stmt->execute();
}

function getUserApplications($userId) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM applications WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    return $stmt->get_result();
}

function getAllApplications() {
    global $conn;
    $stmt = $conn->prepare("SELECT applications.*, users.name AS user_name, services.name AS service_name
                            FROM applications
                            JOIN users ON applications.user_id = users.id
                            JOIN services ON applications.service_id = services.id");
    $stmt->execute();
    return $stmt->get_result();
}

function updateApplicationStatus($applicationId, $status, $updatedBy) {
    global $conn;
    $stmt = $conn->prepare("UPDATE applications SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $applicationId);
    $result = $stmt->execute();

    // Log the status change
    if ($result) {
        $stmtLog = $conn->prepare("INSERT INTO service_status (application_id, status, updated_by) VALUES (?, ?, ?)");
        $stmtLog->bind_param("isi", $applicationId, $status, $updatedBy);
        $stmtLog->execute();
    }

    return $result;
}
?>
