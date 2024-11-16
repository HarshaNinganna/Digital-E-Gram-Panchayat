<?php
session_start();
$message = '';
$services = []; // Array to hold services created by the officer
$notices = []; // Array to hold public notices

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: http://localhost/Digital%20E%20Gram%20Panchayat/auth/user_login.php");
    exit();
}

// Fetch services created by officers
$conn = new mysqli("localhost", "root", "", "digital_e_gram_panchayat");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch services
$sql_services = "SELECT service_id, service_name, service_description, service_category FROM services";
$result_services = $conn->query($sql_services);

if ($result_services && $result_services->num_rows > 0) {
    while ($row = $result_services->fetch_assoc()) {
        $services[] = $row;
    }
}

// Fetch public notices
$sql_notices = "SELECT notice_id, title, description, created_at FROM public_notices ORDER BY created_at DESC";
$result_notices = $conn->query($sql_notices);

if ($result_notices && $result_notices->num_rows > 0) {
    while ($row = $result_notices->fetch_assoc()) {
        $notices[] = $row;
    }
}

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
        <!-- Logo Section -->
        <div class="logo">
            <h1>Digital E Gram Panchayat</h1>
        </div>
        <div class="login">
            <a href="#header-container" class="login-btn">
                <i class="fas fa-home"></i> Home
            </a>
        </div>
        <div class="login">
            <a href="#about_us" class="login-btn">
                <i class="fas fa-users"></i> About Us
            </a>
        </div>
        
        <!-- Menu Dropdown Button -->
        <div class="login">
            <a href="#" class="login-btn" onclick="toggleDropdown()">
                <i class="fas fa-bars"></i> Menu
            </a>
            <div class="dropdown-content">
                <a href="#">Home</a>
                <a href="#">Service</a>
                <a href="#">About</a>
                <a href="#">Contact</a>
            </div>
        </div>
        
<!-- Profile Button (triggers modal) -->
<div class="login">
    <a href="#" class="login-btn" onclick="fetchUserProfile()"> <i class="fas fa-user"></i> Profile</a>
</div>

<!-- Modal for displaying Profile -->
<div id="profileModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h3>User Profile</h3>
        <div id="profileDetails"></div>
    </div>
</div>

<!-- Styles for the Modal -->
<style>
    /* Modal styling */
    .modal {
        display: none;  /* Hidden by default */
        position: fixed;
        z-index: 1; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4); /* Background with slight opacity */
    }

    /* Modal content styling */
    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 600px;
    }

    /* Close button styling */
    .close-btn {
        color: #aaa;
        font-size: 28px;
        font-weight: bold;
        float: right;
        cursor: pointer;
    }

    /* Close button hover effect */
    .close-btn:hover,
    .close-btn:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
</style>

<!-- JavaScript to handle fetching and displaying profile data -->
<script>
    // Fetch profile data and display it in the modal
    function fetchUserProfile() {
        const userId = 'user_id_example'; // Set the user ID dynamically (e.g., from session or cookie)

        // Make the API request to fetch user profile
        fetch("fetch_profile.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
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
                    <p><strong>Address:</strong> ${profile.address}</p>
                    <p><strong>Qualification:</strong> ${profile.qualification}</p>
                    <p><strong>Sub Qualification:</strong> ${profile.sub_qualification}</p>
                    <p><strong>Date of Birth:</strong> ${profile.dob}</p>
                    <p><strong>Gender:</strong> ${profile.gender}</p>
                    <p><strong>Gov ID Proof:</strong> ${profile.gov_id_proof}</p>
                    <p><strong>PAN ID:</strong> ${profile.pan_id}</p>
                    <p><strong>Profile Photo:</strong> <img src="${profile.photo}" alt="Profile Photo" width="100"></p>
                `;
                // Display the modal
                document.getElementById("profileModal").style.display = "block";
            } else {
                document.getElementById("profileDetails").innerHTML = "<p>Failed to load profile details.</p>";
            }
        })
        .catch(err => {
            console.error("Error fetching profile:", err);
            document.getElementById("profileDetails").innerHTML = "<p>Error fetching profile details.</p>";
        });
    }

    // Close the modal
    function closeModal() {
        document.getElementById("profileModal").style.display = "none";
    }

    // Close the modal if clicked outside the content
    window.onclick = function(event) {
        const modal = document.getElementById("profileModal");
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

        <!-- Login Button -->
        <div class="login">
            <a href="#" class="login-btn">
                <i class="fas fa-sign-in-alt"></i> Login
            </a>
        </div>
    </div>
</header>
<script> function toggleDropdown() {
    const dropdown = document.querySelector('.dropdown-content');
    dropdown.classList.toggle('show');
}
</script>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="#infrastructure">Village Infrastructure</a></li>
                    <li class="nav-item"><a class="nav-link" href="#water_supply">Water Supply</a></li>
                    <li class="nav-item"><a class="nav-link" href="#sanitation">Sanitation & Waste Management</a></li>
                    <li class="nav-item"><a class="nav-link" href="#education">Primary Education</a></li>
                    <li class="nav-item"><a class="nav-link" href="#health">Health & Hygiene</a></li>
                    <li class="nav-item"><a class="nav-link" href="#employment">Employment Programs</a></li>
                    <li class="nav-item"><a class="nav-link" href="#welfare">Women & Child Welfare</a></li>
                    <li class="nav-item"><a class="nav-link" href="#resources">Management of Resources</a></li>
                </ul>
            </div>
        </div>
    </nav>
<!-- New Empty Section -->
<div class="container mt-4" id="infrastructure">
    <h2 class="text-center">Village Infrastructure</h2>
    <p class="text-center">Village infrastructure plays a vital role in the overall development of rural areas. It includes basic facilities such as roads, electricity, water supply, and sanitation. The development of infrastructure ensures better living standards for villagers and facilitates various services and activities.</p>

    <!-- Images Section -->
    <div class="row mt-4">
        <!-- Image 1: Village Roads Infrastructure -->
        <div class="col-md-6">
            <div class="card">
            <img src="../assets/village-road.jpg" class="card-img-top" alt="Village Roads">
                <div class="card-body">
                    <h5 class="card-title">Village Roads</h5>
                    <p class="card-text">Well-maintained roads are essential for ensuring easy access to remote areas, improving connectivity, and facilitating transportation of goods and services.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6" >
            <div class="card">
                <img src="../assets/electric-supply.jpg"class="card-img-top" alt="Electricity Supply">
                <div class="card-body">
                    <h5 class="card-title">Electricity Supply</h5>
                    <p class="card-text">Reliable electricity supply is crucial for various aspects of daily life. It powers homes, schools, businesses, and other essential services, fostering economic growth.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Infrastructure Details -->
    <p class="text-center mt-4">The infrastructure development also focuses on improving healthcare facilities, electricity, sanitation, and waste management systems. Through various government schemes and initiatives, we aim to ensure sustainable growth and better quality of life for the villagers.</p>
</div>

<!-- Water Supply Section -->
<div class="container mt-4" id="water_supply">
    <h2 class="text-center">Water Supply</h2>
    <p class="text-center">A reliable water supply infrastructure is fundamental to the development of rural areas. It ensures access to clean and safe water, improving living standards and supporting essential activities such as agriculture, sanitation, and daily household needs.</p>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <img src="../assets/water-supply.jpg" class="card-img-top" alt="Water Supply">
                <div class="card-body">
                <h5 class="card-title">Water Supply</h5>
                    <p class="card-text">Reliable and clean water supply is essential for health and well-being. We are working on enhancing water distribution systems in the village.</p>
                    
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <img src="../assets/electric-supply.jpg"class="card-img-top" alt="Electricity Supply">
                <div class="card-body">
                    <h5 class="card-title">Water Supply</h5>
                    <p class="card-text">Access to safe and clean water is vital for health and quality of life. We are committed to improving water distribution systems in the village.</p>
                </div>
            </div>
        </div>
    </div>

    <p class="card-text text-muted">Efforts are being made to install water pumps and improve storage facilities for better access to drinking water.</p>
</div>

<div class="container mt-4" id="sanitation">
<h2 class="text-center">Sanitation & Waste Management</h2>
    <p class="text-center">A reliable water supply infrastructure is fundamental to the development of rural areas. It ensures access to clean and safe water, improving living standards and supporting essential activities such as agriculture, sanitation, and daily household needs.</p>
    <div class="container mt-4">

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <img src="../assets/sanitation.jpg" class="card-img-top" alt="Sanitation & Waste Management">
                <div class="card-body">
                    <h5 class="card-title">Sanitation & Waste Management</h5>
                    <p class="card-text">Improving sanitation and waste management is key to maintaining a healthy environment. We are building better waste disposal and recycling systems.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <img src="../assets/electric-supply.jpg"class="card-img-top" alt="Electricity Supply">
                <div class="card-body">
                    <h5 class="card-title">Sanitation & Waste Management</h5>
                    <p class="card-text">Enhancing sanitation and waste management is crucial for fostering a healthy environment. We are developing efficient waste disposal and recycling systems.</p>
                </div>
            </div>
        </div>

<div class="container mt-4" id="education">
<h2 class="text-center">Primary Education</h2>
    <p class="text-center">A reliable water supply infrastructure is fundamental to the development of rural areas. It ensures access to clean and safe water, improving living standards and supporting essential activities such as agriculture, sanitation, and daily household needs.</p>
    <div class="container mt-4">

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <img src="../assets/primary-education.jpg" class="card-img-top" alt="Primary Education">
                <div class="card-body">
                <h5 class="card-title">Primary Education</h5>
                    <p class="card-text">Quality education is the foundation for a better future. We are working to provide children with access to schools, teachers, and learning materials.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <img src="../assets/electric-supply.jpg"class="card-img-top" alt="Electricity Supply">
                <div class="card-body">
                    <h5 class="card-title">Primary Education</h5>
                    <p class="card-text">Access to quality education is the cornerstone of a brighter future. We are striving to ensure children have the resources they need, including schools, teachers, and learning materials.</p>
                </div>
            </div>
        </div>

    <div class="container mt-4" id="health">
    <h2 class="text-center">Health & Hygiene</h2>
    <p class="text-center">A reliable water supply infrastructure is fundamental to the development of rural areas. It ensures access to clean and safe water, improving living standards and supporting essential activities such as agriculture, sanitation, and daily household needs.</p>
    <div class="container mt-4">   
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <img src="../assets/health.jpg" class="card-img-top" alt="Health & Hygiene">
                <div class="card-body">
                    <h5 class="card-title">Health & Hygiene</h5>
                    <p class="card-text">We aim to improve the health and hygiene of our village through better healthcare facilities and cleanliness awareness programs.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <img src="../assets/electric-supply.jpg"class="card-img-top" alt="Electricity Supply">
                <div class="card-body">
                    <h5 class="card-title">Health & Hygiene</h5>
                    <p class="card-text">Our goal is to enhance the health & hygiene of our village by providing improved facilities and promoting cleanliness awareness programs.</p>
                </div>
            </div>
        </div>

<!-- Employment Programs Section -->
<div class="container mt-4"id="employment">
    <h2 class="text-center">Employment Programs</h2>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <img src="../assets/employment-programs.jpg" class="card-img-top" alt="Employment Programs">
                <div class="card-body">
                <h5 class="card-title">Employment Programs</h5>
                    <p class="card-text">We provide various employment opportunities through skill development programs and local community projects.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <img src="../assets/electric-supply.jpg"class="card-img-top" alt="Electricity Supply">
                <div class="card-body">
                    <h5 class="card-title">Employment Programs</h5>
                    <p class="card-text">We create diverse employment opportunities by offering skill development programs and supporting local community initiatives</p>
                </div>
            </div>
        </div>

        <div class="container mt-4" id="welfare">
    <h2 class="text-center">Women & Child Welfare</h2>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <img src="../assets/women-child-welfare.jpg" class="card-img-top" alt="Women & Child Welfare">
                <div class="card-body">
                    <h5 class="card-title">Women & Child Welfare</h5>
                    <p class="card-text">Programs for women and children focus on health, safety, education, and overall empowerment to create a better future for the younger generation.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <img src="../assets/electric-supply.jpg"class="card-img-top" alt="Electricity Supply">
                <div class="card-body">
                    <h5 class="card-title">Women & Child Welfare</h5>
                    <p class="card-text">Our programs for women and children prioritize health, safety, education, and empowerment, aiming to build a brighter future for the next generation.</p>
                </div>
            </div>
        </div>

<!-- Management of Resources Section -->
<div class="container mt-4" id="resources">
    <h2 class="text-center">Management of Resources</h2>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <img src="../assets/resources-management.jpg" class="card-img-top" alt="Management of Resources">
                <div class="card-body">
                <h5 class="card-title">Management of Resources</h5>
                    <p class="card-text">Efficient management of natural and human resources is essential for sustainable growth. We focus on conserving resources and promoting eco-friendly practices.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <img src="../assets/electric-supply.jpg"class="card-img-top" alt="Electricity Supply">
                <div class="card-body">
                    <h5 class="card-title">Management of Resources</h5>
                    <p class="card-text">
                    Sustainable growth relies on the efficient management of natural and human resources. We prioritize resource conservation and advocate for eco-friendly practices.</p>
                </div>
            </div>
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
                            <td><?= nl2br(htmlspecialchars($notice['description'])); ?></td>
                            <td><?= date("d M Y", strtotime($notice['created_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center text-muted">No public notices available at the moment.</p>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="text-center mt-4">
        <p>&copy; 2024 Gram Panchayat Services | All Rights Reserved</p>
        <p>Contact Us: info@grampanchayatservices.com | Phone: +91 1234567890</p>
    </footer>

    <!-- Bootstrap JS, jQuery, and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
