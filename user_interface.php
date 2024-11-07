<?php
// Start the session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gram Panchayat Services</title>
    <link rel="stylesheet" href="../assets/css/user_style.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-left">
            <h1>Digital E Gram Panchayat</h1>
            <a href="#home">Home</a>
            <a href="#menu">Menu</a>
        </div>
        <div class="header-right">
            <a href="#login" class="login-btn">Login</a>
        </div>
    </header>

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

    <!-- Main content -->
    <main class="container mt-4">
        <section id="infrastructure">
            <h2>Village Infrastructure</h2>
            <p>Our focus is on maintaining and developing essential village infrastructure, including roads, bridges, and community centers, to ensure safe and convenient accessibility for everyone in the community.</p>
        </section>

        <section id="water_supply">
            <h2>Water Supply</h2>
            <p>Efforts are dedicated to providing clean and reliable water supply through efficient distribution networks, regular maintenance, and water quality checks to promote health and hygiene.</p>
        </section>

        <section id="sanitation">
            <h2>Sanitation & Waste Management</h2>
            <p>We strive to maintain village cleanliness and sanitation through organized waste disposal, recycling initiatives, and awareness programs that ensure a healthy environment for all residents.</p>
        </section>

        <section id="education">
            <h2>Primary Education</h2>
            <p>Providing access to quality primary education with well-equipped facilities, skilled teachers, and supportive learning programs to build a strong educational foundation for village children.</p>
        </section>

        <section id="health">
            <h2>Health & Public Hygiene</h2>
            <p>Health camps, disease prevention drives, and public health education sessions are regularly conducted to promote community health, sanitation, and overall well-being.</p>
        </section>

        <section id="employment">
            <h2>Rural Employment Programs</h2>
            <p>Supporting rural employment through programs like MGNREGA, providing villagers with work opportunities, skill-building, and avenues to improve their economic status.</p>
        </section>

        <section id="welfare">
            <h2>Women & Child Welfare</h2>
            <p>Empowering women through skill development and health initiatives, and supporting child health and education to ensure a brighter future for our young generation.</p>
        </section>

        <section id="resources">
            <h2>Management of Common Resources</h2>
            <p>Conservation and sustainable management of community resources, including water bodies, agricultural lands, and forests, to benefit the present and future generations.</p>
        </section>

        <!-- Call to Action -->
        <div class="text-center my-4">
            <button class="btn-primary-custom">Learn About Our Impact</button>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Gram Panchayat Services | All Rights Reserved</p>
        <p>Contact Us: info@grampanchayatservices.com | Phone: +91 1234567890</p>
    </footer>

    <!-- Bootstrap JS, jQuery, and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
