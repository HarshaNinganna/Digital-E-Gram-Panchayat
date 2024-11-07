<?php
// Initialize variables
$message = '';
$user_id = '';

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

    // Generate user ID based on first name, DOB, and current year
    $dob_year = date("Y", strtotime($dob));  // Extract year from DOB
    $user_id = strtolower($first_name) . $dob_year;  // Concatenate first name and year

    // Example message for demonstration
    $message = "User ID generated: " . $user_id;

    // You can proceed with further processing like inserting data into the database, etc.
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

        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
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

            <!-- Qualification Dropdown -->
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

            <!-- Occupation -->
            <div class="form-group mb-3" id="occupation-div" style="display:none;">
                <label for="occupation">Occupation:</label>
                <input type="text" class="form-control" id="occupation" name="occupation" placeholder="Enter your occupation">
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
                    <option value="Loan Recovery Officer">Loan Recovery Officer</option>
                </select>
            </div>

            <!-- Password and Confirm Password -->
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
</div>
<div class="text-center mt-3">
    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>
<footer class="bg-dark text-white py-3 mt-5">
    <div class="container text-center">
        <p>&copy; 2024 Digital E Gram Panchayat. All Rights Reserved.</p>
    </div>
</footer>

<script>
function displayDesignation() {
    var role = document.getElementById("role").value;
    if (role === "officer") {
        document.getElementById("designation-div").style.display = "block";
    } else {
        document.getElementById("designation-div").style.display = "none";
    }
}

function checkQualification() {
    var qualification = document.getElementById("qualification").value;
    var subChoice = document.getElementById("qualification-sub-choice");
    var pgChoice = document.getElementById("pg-sub-choice");
    var occupationDiv = document.getElementById("occupation-div");
    
    if (qualification === "ug") {
        subChoice.style.display = "block";
        pgChoice.style.display = "none";
    } else if (qualification === "pg") {
        pgChoice.style.display = "block";
        subChoice.style.display = "none";
    } else {
        subChoice.style.display = "none";
        pgChoice.style.display = "none";
    }

    // Display occupation field for ITI/Diploma, and others
    if (qualification === "iti_diploma" || qualification === "others") {
        occupationDiv.style.display = "block";
    } else {
        occupationDiv.style.display = "none";
    }
}
</script>

</body>
</html>
