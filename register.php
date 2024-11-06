<?php
// Include database connection
include('../includes/db.php');

// Initialize message variable
$message = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and get form inputs
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $qualification = mysqli_real_escape_string($conn, $_POST['qualification']);
    $sub_qualification = isset($_POST['sub_qualification']) ? mysqli_real_escape_string($conn, $_POST['sub_qualification']) : '';
    $pg_qualification = isset($_POST['pg_qualification']) ? mysqli_real_escape_string($conn, $_POST['pg_qualification']) : '';
    $occupation = isset($_POST['occupation']) ? mysqli_real_escape_string($conn, $_POST['occupation']) : '';
    $gov_id_proof = mysqli_real_escape_string($conn, $_POST['gov_id_proof']);
    $pan_id = mysqli_real_escape_string($conn, $_POST['pan_id']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $designation = isset($_POST['designation']) ? mysqli_real_escape_string($conn, $_POST['designation']) : '';
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Validate password
    if ($password !== $confirm_password) {
        $message = "Passwords do not match!";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert into database
        $sql = "INSERT INTO users (first_name, last_name, address, qualification, sub_qualification, pg_qualification, occupation, gov_id_proof, pan_id, email, phone, dob, gender, title, role, designation, password)
                VALUES ('$first_name', '$last_name', '$address', '$qualification', '$sub_qualification', '$pg_qualification', '$occupation', '$gov_id_proof', '$pan_id', '$email', '$phone', '$dob', '$gender', '$title', '$role', '$designation', '$hashed_password')";

        if (mysqli_query($conn, $sql)) {
            // Redirect or show success message
            $message = "Registration successful! <a href='login.php'>Login here</a>";
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Digital E Gram Panchayat</title>
    <!-- Add Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>

<!-- Header Section -->
<header class="bg-dark text-white py-3">
    <div class="container">
        <h1 class="text-center mb-0">Digital E Gram Panchayat</h1>
        <p class="text-center">Empowering Rural Communities</p>
    </div>
</header>

<!-- Main Content Section -->
<div class="container mt-5">
    <div class="register-container">
        <h2 class="text-center mb-4">Register - Digital E Gram Panchayat</h2>

        <?php if ($message): ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="register.php" method="post">
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
                <label for="address">Address:</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>

            <!-- Qualification Dropdown with Choices -->
            <div class="form-group mb-3">
                <label for="qualification">Qualification:</label>
                <select id="qualification" name="qualification" class="form-control" required onchange="checkQualification()">
                    <option value="">Select Qualification</option>
                    <option value="doctor">Doctor</option>
                    <option value="be_btech_mtech">BE/B.Tech/M.Tech</option>
                    <option value="ug">UG (Bsc, BCA, BCom, BBA, BA, Others)</option>
                    <option value="pg">PG (MSc, MCA, MCom, MA, Others)</option>
                    <option value="iti_diploma">ITI/Diploma</option>
                    <option value="pu_plus12">PU/+12</option>
                    <option value="sslc">SSLC</option>
                    <option value="below_sslc">Below SSLC</option>
                    <option value="illiterate">Illiterate</option>
                </select>
            </div>

            <!-- Sub-choices for UG and PG Qualifications -->
            <div id="qualification-sub-choice" class="form-group mb-3" style="display:none;">
                <label for="sub_qualification">Sub-Qualification:</label>
                <select id="sub_qualification" name="sub_qualification" class="form-control">
                    <!-- UG Sub-Choices -->
                    <option value="bsc">BSc</option>
                    <option value="bca">BCA</option>
                    <option value="bcom">BCom</option>
                    <option value="bba">BBA</option>
                    <option value="ba">BA</option>
                    <option value="others">Others</option>
                </select>
            </div>

            <div id="pg-sub-choice" class="form-group mb-3" style="display:none;">
                <label for="pg_qualification">PG Sub-Qualification:</label>
                <select id="pg_qualification" name="pg_qualification" class="form-control">
                    <option value="msc">MSc</option>
                    <option value="mca">MCA</option>
                    <option value="mcom">MCom</option>
                    <option value="ma">MA</option>
                    <option value="others">Others</option>
                </select>
            </div>

            <div class="form-group mb-3" id="occupation-div" style="display:none;">
                <label for="occupation">Occupation:</label>
                <input type="text" class="form-control" id="occupation" name="occupation">
            </div>
            
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

            <div id="designation-div" class="form-group mb-3" style="display:none;">
                <label for="designation">Designation:</label>
                <select id="designation" name="designation" class="form-control">
                    <option value="Bank Manager">Bank Manager</option>
                    <option value="Relationship Manager">Relationship Manager</option>
                    <option value="Credit Analyst">Credit Analyst</option>
                    <option value="Financial Analyst">Financial Analyst</option>
                    <option value="Account Manager">Account Manager</option>
                    <option value="Loan Officer">Loan Officer</option>
                    <option value="Teller/Cashier">Teller/Cashier</option>
                    <option value="Customer Service Representative">Customer Service Representative</option>
                    <option value="Operations Officer">Operations Officer</option>
                    <option value="Accountant">Accountant</option>
                </select>
            </div>

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

        <p class="text-center mt-3">Already have an account? <a href="login.php">Login here</a></p>
    </div>
</div>

<!-- Footer Section -->
<footer class="bg-dark text-white py-4">
    <div class="container text-center">
        <p>&copy; 2024 Digital E Gram Panchayat. All rights reserved.</p>
    </div>
</footer>

<!-- Add Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function checkQualification() {
        const qualification = document.getElementById('qualification').value;
        const subChoice = document.getElementById('qualification-sub-choice');
        const pgChoice = document.getElementById('pg-sub-choice');
        if (qualification === 'ug') {
            subChoice.style.display = 'block';
            pgChoice.style.display = 'none';
        } else if (qualification === 'pg') {
            pgChoice.style.display = 'block';
            subChoice.style.display = 'none';
        } else {
            subChoice.style.display = 'none';
            pgChoice.style.display = 'none';
        }
    }

    function displayDesignation() {
        const role = document.getElementById('role').value;
        const designationDiv = document.getElementById('designation-div');
        if (role === 'officer' || role === 'staff') {
            designationDiv.style.display = 'block';
        } else {
            designationDiv.style.display = 'none';
        }
    }
</script>
</body>
</html>
