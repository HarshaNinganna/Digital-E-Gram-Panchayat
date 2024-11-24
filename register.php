<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "digital_e_gram_panchayat";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$message = '';
$user_id = '';
$staff_id = '';
$officer_id = '';
$photo_path = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and fetch form data
    $first_name = htmlspecialchars($_POST['first_name']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $address = htmlspecialchars($_POST['address']);
    $qualification = htmlspecialchars($_POST['qualification']);
    $sub_qualification = isset($_POST['sub_qualification']) ? htmlspecialchars($_POST['sub_qualification']) : '';
    $pg_qualification = isset($_POST['pg_qualification']) ? htmlspecialchars($_POST['pg_qualification']) : '';
    $occupation = isset($_POST['occupation']) ? htmlspecialchars($_POST['occupation']) : '';
    $gov_id_proof = htmlspecialchars($_POST['gov_id_proof']);
    $pan_id = htmlspecialchars($_POST['pan_id']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $dob = htmlspecialchars($_POST['dob']);
    $gender = htmlspecialchars($_POST['gender']);
    $title = htmlspecialchars($_POST['title']);
    $role = htmlspecialchars($_POST['role']);
    $password = htmlspecialchars($_POST['password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);
    $officerdesignation = isset($_POST['officerdesignation']) ? htmlspecialchars($_POST['officerdesignation']) : '';
    $staffdesignation = isset($_POST['staffdesignation']) ? htmlspecialchars($_POST['staffdesignation']) : '';

    // Generate user ID based on first name and DOB year
    $dob_year = date("Y", strtotime($dob));
    $user_id = strtolower($first_name) . $dob_year;

    // Validate password confirmation
    if ($password !== $confirm_password) {
        $message = "Passwords do not match.";
    } else {
        // Validate and upload photo
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $photo = $_FILES['photo'];
            $allowed_types = ['image/jpeg', 'image/png'];
            $dimensions = getimagesize($photo['tmp_name']);
            if (in_array($photo['type'], $allowed_types) && $dimensions[0] == 200 && $dimensions[1] == 200) {
                $photo_ext = pathinfo($photo['name'], PATHINFO_EXTENSION);
                $photo_name = $user_id . "." . $photo_ext;
                $photo_path = "uploads/" . $photo_name;
                move_uploaded_file($photo['tmp_name'], $photo_path);
            } else {
                $message = "Invalid photo. Please upload a 200x200 passport-size photo in JPG or PNG format.";
            }
        }

        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert data based on role
        if ($role == 'user') {
            $stmt = $conn->prepare("INSERT INTO users (user_id, first_name, last_name, address, qualification, sub_qualification, gov_id_proof, pan_id, email, phone, dob, gender, title, password, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if (!$stmt) die("Prepare failed for User role: " . $conn->error);
            $stmt->bind_param("sssssssssssssss", $user_id, $first_name, $last_name, $address, $qualification, $sub_qualification, $gov_id_proof, $pan_id, $email, $phone, $dob, $gender, $title, $hashed_password, $photo_path);
            if ($stmt->execute()) {
                $message = "User registration successful. Welcome, " . $first_name . ". Your user ID is " . $user_id . ".";
                $_SESSION['user_id'] = $user_id;
            } else {
                $message = "Error: " . $stmt->error;
            }
        } elseif ($role == 'staff') {
            if (!empty($staffdesignation)) {
                $staff_id = strtoupper(substr($first_name, 0, 3)) . substr(md5(uniqid(mt_rand(), true)), 0, 3);

                // Generate a random secret number for staff login
                $secret_number = rand(1000, 9999);
        
                // Prepare the SQL statement for inserting the staff data
                $stmt = $conn->prepare("INSERT INTO staff (staff_id, first_name, last_name, address, qualification, occupation, gov_id_proof, pan_id, email, phone, dob, gender, title, password, photo, secret_number, designation) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
                if (!$stmt) die("Prepare failed for Staff role: " . $conn->error);
        
                // Bind the parameters (ensure all variables are properly assigned)
                $stmt->bind_param("sssssssssssssssss", $staff_id, $first_name, $last_name, $address, $qualification, $occupation, $gov_id_proof, $pan_id, $email, $phone, $dob, $gender, $title, $hashed_password, $photo_path, $secret_number, $staffdesignation);
        
                // Execute the statement
                if ($stmt->execute()) {
                    $message = "Staff registration successful. Generated Staff ID: $staff_id, Secret Number: $secret_number";
                    $_SESSION['staff_id'] = $staff_id;
                } else {
                    $message = "Error executing the query: " . $stmt->error;
                }
            } else {
                $message = "Please provide a valid Staff Designation.";
            }
        } elseif ($role == 'officer') {
            $officerdesignation = "Panchayat Development Officer (PDO)";
            $officer_id = strtoupper(substr($first_name, 0, 3)) . "DEGPO";
            $secret_number = rand(1000, 9999);
            $stmt = $conn->prepare("INSERT INTO officers (user_id, first_name, last_name, address, qualification, designation, gov_id_proof, pan_id, email, phone, dob, gender, title, password, photo, secret_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if (!$stmt) die("Prepare failed for Officer role: " . $conn->error);
            $stmt->bind_param("ssssssssssssssss", $officer_id, $first_name, $last_name, $address, $qualification, $officerdesignation, $gov_id_proof, $pan_id, $email, $phone, $dob, $gender, $title, $hashed_password, $photo_path, $secret_number);
            if ($stmt->execute()) {
                $message = "Officer registration successful. Generated Officer ID: $officer_id, Secret Number: $secret_number";
                $_SESSION['officer_id'] = $officer_id;
            } else {
                $message = "Error executing the query: " . $stmt->error;
            }
        } else {
            $message = "Invalid role provided.";
        }
    }
    
    // Close the statement and connection
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Digital E Gram Panchayat</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/auth.css">
    <script type="module">
  import { initializeApp } from "firebase/app";
  import { getAnalytics } from "firebase/analytics";

  const firebaseConfig = {
    apiKey: "AIzaSyBNr76sV3W9EYNfwJ-_A95vbpW503_46SE",
    authDomain: "digital-e-gram-panchayat-6702f.firebaseapp.com",
    projectId: "digital-e-gram-panchayat-6702f",
    storageBucket: "digital-e-gram-panchayat-6702f.firebasestorage.app",
    messagingSenderId: "760029374096",
    appId: "1:760029374096:web:e822242264a6b20c609a15",
    measurementId: "G-XDSQQKXV5H"
  };

  // Initialize Firebase
  const app = initializeApp(firebaseConfig);
  const analytics = getAnalytics(app);
</script>
    <script>
        // JavaScript to show sub-qualification and designation fields based on selection
        function checkQualification() {
            const qualification = document.getElementById("qualification").value;
            document.getElementById("qualification-sub-choice").style.display = qualification === "ug" ? "block" : "none";
            document.getElementById("pg-sub-choice").style.display = qualification === "pg" ? "block" : "none";
        }

        function displayDesignation() {
            const role = document.getElementById("role").value;
            document.getElementById("officer-designation-div").style.display = role === "officer" ? "block" : "none";
            document.getElementById("staff-designation-div").style.display = role === "staff" ? "block" : "none";
        }
    </script>
</head>
<body>

<header class="bg-dark text-white py-3">
    <div class="container">
        <h1 class="text-center mb-0">Digital E Gram Panchayat</h1>
        <p class="text-center">Empowering Rural Communities</p>
    </div>
</header>

<div class="container mt-5">
    <div class="register-container">
        <h2 class="text-center mb-4">Register - Digital E Gram Panchayat</h2>

        <!-- Success Message Display -->
        <?php if (isset($message) && $message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form action="register.php" method="post" enctype="multipart/form-data">
            <!-- User Information Fields -->
            <div class="form-row mb-3">
                <div class="col">
                    <label for="first_name">First Name:</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>
                <div class="col">
                    <label for="last_name">Last Name:</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                </div>
            </div>
            <div class="form-group mb-3">
                <label for="photo">Upload Photo (Passport size):</label>
                <input type="file" name="photo" accept="image/jpeg, image/png" required>
            </div>

            <!-- Address and Qualification -->
            <div class="form-group mb-3">
                <label for="address">Address:</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>

            <div class="form-group mb-3">
                <label for="qualification">Qualification:</label>
                <select id="qualification" name="qualification" class="form-control" required onchange="checkQualification()">
                    <option value="">Select Qualification</option>
                    <option value="doctor">Doctor</option>
                    <option value="be_btech_mtech">BE/B.Tech/M.Tech</option>
                    <option value="ug">UG (BSc, BCA, BCom, BBA, BA, Others)</option>
                    <option value="pg">PG (MSc, MCA, MCom, MA, Others)</option>
                    <option value="iti_diploma">ITI/Diploma</option>
                    <option value="pu_plus12">PU/+12</option>
                    <option value="sslc">SSLC</option>
                    <option value="below_sslc">Below SSLC</option>
                    <option value="illiterate">Illiterate</option>
                </select>
            </div>

            <!-- UG and PG Sub-qualification Fields -->
            <div id="qualification-sub-choice" class="form-group mb-3" style="display:none;">
                <label for="sub_qualification">Under Graduation:</label>
                <select id="sub_qualification" name="sub_qualification" class="form-control">
                    <option value="bsc">BSc</option>
                    <option value="bca">BCA</option>
                    <option value="bcom">BCom</option>
                    <option value="bba">BBA</option>
                    <option value="ba">BA</option>
                    <option value="others">Others</option>
                </select>
            </div>

            <div id="pg-sub-choice" class="form-group mb-3" style="display:none;">
                <label for="pg_qualification">Post Graduation:</label>
                <select id="pg_qualification" name="pg_qualification" class="form-control">
                    <option value="msc">MSc</option>
                    <option value="mca">MCA</option>
                    <option value="mcom">MCom</option>
                    <option value="ma">MA</option>
                    <option value="others">Others</option>
                </select>
            </div>

            <!-- Occupation -->
            <div class="form-group mb-3">
                <label for="Occupation">Occupation:</label>
                <select id="Occupation" name="Occupation" class="form-control" required>
                    <option value="">Select Occupation</option>
                    <option value="State Government Employee">State Government Employee</option>
                    <option value="Central Government Employee">Central Government Employee</option>
                    <option value="Ex-Service(Government/Defence)">Ex-Service(Government/Defence)</option>
                    <option value="Private Sector Employee">Private Sector Employee</option>
                    <option value="Panchayat Staff">Panchayat Staff</option>
                    <option value="Farmer">Farmer</option>
                    <option value="Self Employed">Self Employed</option>
                    <option value="Unemployed/Day Labour">Unemployed/Day Labour</option>
                    <option value="Student">Student</option>
                </select>
            </div>

            <!-- Government ID, PAN ID, Email, and Phone Number -->
            <div class="form-group mb-3">
                <label for="gov_id_proof">Government ID Proof (Aadhar/Driving Licence):</label>
                <input type="text" class="form-control" id="gov_id_proof" name="gov_id_proof" required>
            </div>
            <div class="form-group mb-3">
                <label for="pan_id">PAN ID (Mandatory):</label>
                <input type="text" class="form-control" id="pan_id" name="pan_id" required>
            </div>
            <div class="form-group mb-3">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group mb-3">
                <label for="phone">Phone Number:</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>

            <!-- DOB, Gender, Title, Role -->
            <div class="form-group mb-3">
                <label for="dob">Date of Birth:</label>
                <input type="date" class="form-control" id="dob" name="dob" required>
            </div>
            <div class="form-group mb-3">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" class="form-control" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="title">Title:</label>
                <select id="title" name="title" class="form-control" required>
                    <option value="mr">Mr</option>
                    <option value="ms">Ms</option>
                    <option value="mrs">Mrs</option>
                    <option value="master">Master</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="role">Role:</label>
                <select id="role" name="role" class="form-control" required onchange="displayDesignation()">
                    <option value="user">User</option>
                    <option value="officer">Officer</option>
                    <option value="staff">Staff</option>
                </select>
            </div>

            <!-- Officer and Staff Designations (Officer designation will be default) -->
            <div id="officer-designation-div" class="form-group mb-3" style="display:none;">
                <label for="officer_designation">Officer Designation:</label>
                <input type="text" id="officer_designation" name="officer_designation" class="form-control" value="Panchayat Development Officer (PDO)" readonly />
            </div>

            <div id="staff-designation-div" class="form-group mb-3" style="display:none;">
                <label for="staffdesignation">Staff Designation:</label>
                <select id="staffdesignation" name="staffdesignation" class="form-control">
                    <option value="IT Coordinator">IT Coordinator</option>
                    <option value="Data Entry Operator">Data Entry Operator</option>
                    <option value="Citizen Services Helpdesk Operator">Citizen Services Helpdesk Operator</option>
                    <option value="Digital Service Manager">Digital Service Manager</option>
                    <option value="Financial Inclusion Officer">Financial Inclusion Officer</option>
                    <option value="Health & Sanitation Officer">Health & Sanitation Officer</option>
                    <option value="Social Welfare Officer">Social Welfare Officer</option>
                    <option value="Agriculture Officer">Agriculture Officer</option>
                    <option value="Project Coordinator">Project Coordinator</option>
                    <!-- Add other staff designations as needed -->
                </select>
            </div>

            <!-- Password and Confirmation -->
            <div class="form-group mb-3">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group mb-3">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Register</button>
        </form>
    </div>
</div> <br>
<div> <center>
<p>Already have an account? <a href="Officer_login.php">Officer login</a>|
<a href="staff_login.php">Staff Login </a>|
<a href="user_login.php">User Login </a>|
</p>
        </center> </div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<footer class="bg-secondary text-white py-3 mt-5">
    <div class="container text-center">
        <p>&copy; 2024 Digital E Gram Panchayat. All Rights Reserved.</p>
    </div>
</footer>

<script>
// Function to handle role selection and display specific fields accordingly
function displayDesignation() {
    var role = document.getElementById("role").value;  // Get selected role

    // Hide both designation fields initially
    document.getElementById("officer-designation-div").style.display = "none";
    document.getElementById("staff-designation-div").style.display = "none";

    // Show the appropriate designation field based on role
    if (role == "officer") {
        // Automatically set the officer designation to PDO and hide the input field
        document.getElementById("officer-designation-div").style.display = "block";  // Show officer designation
    } else if (role == "staff") {
        document.getElementById("staff-designation-div").style.display = "block";  // Show staff designation
    } else if (role == "user") {
        // If role is 'user', no designation fields are shown
    }
}

// Function to handle qualification-specific sub-choices
function checkQualification() {
    var qualification = document.getElementById("qualification").value;

    // Show sub-choice for qualification if it's 'ug' or 'pg'
    if (qualification == "ug") {
        document.getElementById("qualification-sub-choice").style.display = "block";
        document.getElementById("pg-sub-choice").style.display = "none";  // Hide pg options if UG is selected
    } else {
        document.getElementById("qualification-sub-choice").style.display = "none";
    }

    if (qualification == "pg") {
        document.getElementById("pg-sub-choice").style.display = "block";
        document.getElementById("qualification-sub-choice").style.display = "none";  // Hide UG options if PG is selected
    } else {
        document.getElementById("pg-sub-choice").style.display = "none";
    }
}

// Function to handle occupation-specific logic if needed
function checkOccupation() {
    // Add any occupation-specific logic here
}
</script>


</body>
</html>
