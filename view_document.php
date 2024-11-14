<?php
session_start();

// Ensure the user is logged in and staff_id is available in the session
if (!isset($_SESSION['staff_id'])) {
    die("Staff not logged in or session expired.");
}

// Ensure request_id is provided via GET
if (!isset($_GET['request_id'])) {
    die("Request ID not provided.");
}

$request_id = intval($_GET['request_id']);

// Database connection
$conn = new mysqli("localhost", "root", "", "digital_e_gram_panchayat");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the file data from the request_documents table
$stmt = $conn->prepare("SELECT file_data, file_name, file_extension FROM request_documents WHERE request_id = ?");
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();
$file = $result->fetch_assoc();
$stmt->close();
$conn->close();

if ($file) {
    // Determine content type based on file extension
    $file_extension = strtolower($file['file_extension']);
    $mime_types = [
        'pdf' => 'application/pdf',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        // Add more mime types if necessary
    ];

    $content_type = isset($mime_types[$file_extension]) ? $mime_types[$file_extension] : 'application/octet-stream';

    // Set headers to serve the file
    header("Content-Type: " . $content_type);
    header("Content-Disposition: inline; filename=\"" . $file['file_name'] . "\"");
    header("Content-Length: " . strlen($file['file_data']));

    // Output the file data
    echo $file['file_data'];
} else {
    echo "File not found.";
}
?>
