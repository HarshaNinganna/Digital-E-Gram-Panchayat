<?php
session_start();

// Initialize variables
$message = '';
$services = []; // Array to hold services created by the officer
$notices = []; // Array to hold public notices
$user_details = []; // Array to hold user details

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page or show a custom message
    header("Location: /Digital E Gram Panchayat/auth/user_login.php");
    exit;
}

// User ID is properly set in session
$user_id = $_SESSION['user_id']; 

// Initialize database connection
$conn = new mysqli("localhost", "root", "", "digital_e_gram_panchayat");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch services created by officers
$sql_services = "SELECT service_id, service_name, service_description, service_category FROM services";
$result_services = $conn->query($sql_services);

if ($result_services && $result_services->num_rows > 0) {
    while ($row = $result_services->fetch_assoc()) {
        $services[] = $row;
    }
} else {
    error_log("Error fetching services: " . $conn->error);  // Log error if there are no services or any error occurs
}

// Fetch public notices
$sql_notices = "SELECT notice_id, title, description, created_at FROM public_notices ORDER BY created_at DESC";
$result_notices = $conn->query($sql_notices);

if ($result_notices && $result_notices->num_rows > 0) {
    while ($row = $result_notices->fetch_assoc()) {
        $notices[] = $row;
    }
} else {
    error_log("Error fetching notices: " . $conn->error);  // Log error if no notices are found
}

// Fetch user details
$sql_user = "SELECT user_id, first_name, last_name, email, phone FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql_user);

if ($stmt === false) {
    error_log('MySQL prepare error: ' . $conn->error);  // Log error without displaying
    die('Error preparing user details query.');
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result_user = $stmt->get_result();

if ($result_user && $result_user->num_rows > 0) {
    $user_details = $result_user->fetch_assoc();
} else {
    $message = "User details not found.";
    error_log($message);  // Log if no user found
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gram Panchayat Services</title>
    <link rel="stylesheet" href="../assets/css/user_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body>
<header>
    <div class="header-container">
        <div class="logo">
            <div style="display: flex; align-items: center;">
                <img src="assets/logo.jpg" alt="Logo" style="height: 50px; margin-right: 10px;">
                <h1>Digital E Gram Panchayat</h1>
            </div>
        </div>

        <div class="login">
            <a href="#" class="login-btn">
                <i class="fas fa-home"></i> Home
            </a>
        </div>

        <div class="login">
            <a href="#about_us" class="login-btn">
                <i class="fas fa-users"></i> About Us
            </a>
        </div>

        <div class="login dropdown">
            <a href="#" class="login-btn">
                <i class="fas fa-bars"></i> Menu
            </a>
            <div class="dropdown-content">
                <a href="#">Home</a>
                <a href="#">Service</a>
                <a href="#">About</a>
                <a href="#">Contact</a>
            </div>
        </div>
        <style>
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
</style>
        <div class="login">
            <a href="#" class="login-btn" onclick="fetchUserProfile()">
                <i class="fas fa-user"></i> Profile
            </a>
        </div>

        <div class="login">
        <a href="/Digital E Gram Panchayat/auth/logout.php" class="login-btn">
    <i class="fas fa-sign-in-alt"></i> Logout
</a>

        </div>

        <div id="profileModal" class="modal">
            <div class="modal-content">
                <span class="close-btn" onclick="closeModal()">&times;</span>
                <h3>User Profile</h3>
                <div id="profileDetails">Loading...</div>
            </div>
        </div>
    </div>
</header>

<script>
    function toggleDropdown() {
        const dropdown = document.querySelector('.dropdown-content');
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    }

    function fetchUserProfile() {
        const userId = '<?= $user_id ?>';
        document.getElementById("profileModal").style.display = "block";

        fetch("fetch_profile.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ user_id: userId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const profile = data.profile;
                document.getElementById("profileDetails").innerHTML = `
                    <p><strong>Name:</strong> ${profile.first_name} ${profile.last_name}</p>
                    <p><strong>Email:</strong> ${profile.email}</p>
                    <p><strong>Phone:</strong> ${profile.phone}</p>
                `;
            } else {
                document.getElementById("profileDetails").innerHTML = `<p>${data.message}</p>`;
            }
        })
        .catch(err => {
            console.error("Error fetching profile:", err);
            document.getElementById("profileDetails").innerHTML = "<p>Error fetching profile details.</p>";
        });
    }

    function closeModal() {
        document.getElementById("profileModal").style.display = "none";
    }
</script>
<!-- Services Section -->
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
                        <a href="/Digital E Gram Panchayat/admin/service.php?service_id=<?= htmlspecialchars($service['service_id']); ?>" class="btn btn-primary btn-sm">
                            Apply </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center text-muted">No services available at the moment.</p>
    <?php endif; ?>
</div>
<div class="about_us" id="about_us">
    <h3>About Us</h3>
    <p>
        Welcome to Digital E Gram Panchayath, an initiative aimed at bringing technology to the heart of rural governance. 
        We are dedicated to revolutionizing the way panchayats and local governments engage with citizens by digitizing processes 
        to make services more transparent, accessible, and efficient. Our goal is to empower rural communities with tools and resources 
        that foster development and improve quality of life.
    </p>
    <h4>Our Vision</h4>
    <p>
        Our vision is to create a digitally connected rural India where every gram panchayat has the tools to provide efficient 
        governance, transparent services, and easy access to government schemes. We envision an inclusive digital ecosystem 
        that empowers local communities and promotes sustainable development.
    </p>
    <h4>Our Mission</h4>
    <p>
        Digital E Gram Panchayath strives to integrate digital technologies into the panchayat system to make governance more 
        efficient and citizen-friendly. Through this platform, we aim to:
        <ul>
            <li>Enable easy access to government services and resources for rural communities.</li>
            <li>Streamline communication between panchayats, citizens, and government bodies.</li>
            <li>Promote transparency and accountability in local governance.</li>
            <li>Encourage citizen participation in decision-making and development planning.</li>
            <li>Enhance the delivery of essential services, such as health, education, and welfare.</li>
        </ul>
    </p>
    <h4>Our Values</h4>
    <ul>
        <li><strong>Inclusivity:</strong> We believe that every rural citizen deserves access to digital resources and opportunities.</li>
        <li><strong>Transparency:</strong> Our platform promotes transparency in all processes, ensuring accountability and trust.</li>
        <li><strong>Innovation:</strong> We leverage cutting-edge technology to solve real-world governance challenges in rural India.</li>
        <li><strong>Community Empowerment:</strong> We focus on providing tools that empower local communities to take charge of their own development.</li>
        <li><strong>Sustainability:</strong> We are committed to building a sustainable digital ecosystem that benefits future generations.</li>
    </ul>
    <h4>Our Team</h4>
    <p>
        Our team is a diverse group of professionals dedicated to transforming rural India through technology. 
        From engineers to local governance experts, we work collaboratively to build a system that serves the needs of the community 
        while driving positive change. We are committed to continuously improving our platform and ensuring that it aligns with the 
        real-world needs of rural areas.
    </p>
    <h4>Contact Us</h4>
    <p>
        If you have any questions or would like to learn more about Digital E Gram Panchayath, please feel free to reach out to us. 
        We are always open to collaborations, partnerships, and suggestions to improve our services. 
        <a href="contact.html">Contact Us</a> today.
    </p>
</div>
<!-- Public Notices Section -->
<div class="container mt-4">
    <h2 class="text-center">Public Notices</h2>
    <?php if (!empty($notices)): ?>
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Notice ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notices as $notice): ?>
                    <tr>
                        <td><?= htmlspecialchars($notice['notice_id']); ?></td>
                        <td><?= htmlspecialchars($notice['title']); ?></td>
                        <td>
                            <button 
                                class="btn btn-info btn-sm" 
                                onclick="openNoticeModal('<?= htmlspecialchars(addslashes($notice['title'])); ?>', '<?= nl2br(htmlspecialchars(addslashes($notice['description']))); ?>')">
                                View
                            </button>
                        </td>
                        <td><?= date("d M Y", strtotime($notice['created_at'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center text-muted">No public notices available at the moment.</p>
    <?php endif; ?>
</div>

<!-- Modal for Viewing Notice Description -->
<div id="noticeModal" class="modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Notice Details</h5>
                <button type="button" class="btn-close" onclick="closeNoticeModal()">&times;</button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body" id="modalDescription">
                <!-- Content will be loaded dynamically -->
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeNoticeModal()">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    // Function to open the modal and display the notice details
    function openNoticeModal(title, description) {
        // Update modal title and description
        document.getElementById("modalTitle").innerText = title;
        document.getElementById("modalDescription").innerHTML = description;

        // Show the modal
        const modal = document.getElementById("noticeModal");
        modal.style.display = "block";
    }

    // Function to close the modal
    function closeNoticeModal() {
        const modal = document.getElementById("noticeModal");
        modal.style.display = "none";
    }

    // Close modal if clicking outside of it
    window.onclick = function(event) {
        const modal = document.getElementById("noticeModal");
        if (event.target === modal) {
            closeNoticeModal();
        }
    }
</script>

<!-- CSS for Modal -->
<style>
    .modal {
        display: none; /* Hidden by default */
        position: fixed;
        z-index: 1050;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-dialog {
        position: relative;
        margin: auto;
        top: 20%;
        max-width: 600px;
        width: 90%;
    }

    .modal-content {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .btn-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
    }
</style>
<footer class="text-center mt-4">
    <p>&copy; 2024 Gram Panchayat Services | All Rights Reserved</p>
    <p>Contact Us: info@grampanchayatservices.com | Phone: +91 123 456 7890</p>
</footer>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
