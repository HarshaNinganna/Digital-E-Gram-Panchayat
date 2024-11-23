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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $work_id = $_POST['work_id'];
    $action = $_POST['action'];

    if ($action === 'completed') {
        // Handle the completed action with file upload
        if (isset($_FILES['work_document']) && $_FILES['work_document']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/work_documents/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $file_name = time() . '_' . $_FILES['work_document']['name'];
            $file_path = $upload_dir . $file_name;

            // Move the uploaded file
            if (move_uploaded_file($_FILES['work_document']['tmp_name'], $file_path)) {
                // Update work status to 'completed' and save file path in the database
                $stmt = $conn->prepare("UPDATE assigned_works SET status = 'completed', documentation_path = ? WHERE work_id = ?");
                $stmt->bind_param("si", $file_path, $work_id);
                if ($stmt->execute()) {
                    $message = "Work marked as completed successfully.";
                } else {
                    $message = "Error updating work status.";
                }
                $stmt->close();
            } else {
                $message = "Error uploading documentation.";
            }
        } else {
            $message = "No documentation uploaded.";
        }
    } elseif ($action === 'report') {
        // Handle the report action with a reason
        $report_reason = htmlspecialchars($_POST['report_reason']);

        // Update work status to 'reported' and save the reason in the database
        $stmt = $conn->prepare("UPDATE assigned_works SET status = 'reported', report_reason = ? WHERE work_id = ?");
        $stmt->bind_param("si", $report_reason, $work_id);
        if ($stmt->execute()) {
            $message = "Work reported successfully.";
        } else {
            $message = "Error reporting work.";
        }
        $stmt->close();
    }
}

$conn->close();

// Redirect back to the page where the work status was updated
header("Location: /Digital E Gram Panchayat/staff/staff_interface.php?message=" . urlencode($message));
exit;
?>
