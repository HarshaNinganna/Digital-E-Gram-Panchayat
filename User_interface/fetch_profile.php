<?php
session_start();
header('Content-Type: application/json');

// Check if the user ID is provided in the request
$data = json_decode(file_get_contents('php://input'), true);
$user_id = isset($data['user_id']) ? $data['user_id'] : null;

if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'User ID is required.']);
    exit;
}

// Connect to the database
$conn = new mysqli("localhost", "root", "", "digital_e_gram_panchayat");

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

// Fetch user details from the database
$sql_user = "SELECT user_id, first_name, last_name, email, phone FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql_user);
if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Database query preparation failed.']);
    exit;
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_details = $result->fetch_assoc();
    echo json_encode(['success' => true, 'profile' => $user_details]);
} else {
    echo json_encode(['success' => false, 'message' => 'User not found.']);
}

// Close the connection
$stmt->close();
$conn->close();
?>
