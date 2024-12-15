<?php
include 'db.php';

function registerUser($name, $email, $password, $role = 'user') {
    global $conn;

    try {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Adjusted SQL query to match column names in users table
        if ($stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)")) {
            $stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);
            $executionResult = $stmt->execute();
            $stmt->close();
            return $executionResult;
        } else {
            throw new Exception("Failed to prepare SQL statement: " . $conn->error);
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}

function loginUser($email, $password) {
    global $conn;

    try {
        // Check if connection is successful
        if ($conn === false) {
            throw new Exception("Database connection failed.");
        }

        // Adjusted SQL query to match column names in users table
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");

        // Check if the prepare() method fails
        if ($stmt === false) {
            throw new Exception("Failed to prepare SQL statement: " . $conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];

                $stmt->close();
                return true;
            }
        }

        $stmt->close();
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }

    return false;
}

function logout() {
    if (session_status() == PHP_SESSION_ACTIVE) {
        session_unset();
        session_destroy();
    }
}
?>
