# Digital E-Gram Panchayat 🌟
Digital E-Gram Panchayat is a comprehensive web-based application designed to digitize and simplify rural governance. By integrating user-friendly interfaces and efficient backend systems, this project provides a streamlined solution for managing citizen services, improving transparency, and ensuring seamless communication between citizens and officials.

# 🌐 Features
**For Users:**
**Service Requests:**

Submit requests for government services (e.g., certificates, approvals).
Track the status of submitted applications.
**Profile Management:**

Create and update user profiles.
**Notifications:**

Receive updates on the status of services via the dashboard.
# **For Staff:**
**Service Handling:**

Review, approve, or reject service requests submitted by users.
Add comments or requirements for incomplete applications.
**Status Management:**

Update real-time status for pending or completed services.
**For Officers:**
**Dashboard Insights:**

View detailed analytics on service requests, approvals, and rejections.
**Audit Trail Logs:**

Track service updates, deletions, and other critical actions with reasons.
**Staff Supervision:**

Monitor and manage staff performance.
**Service Management:**

Add new services, edit existing ones, and categorize them appropriately.
# **💻 Technologies Used**
# **Frontend:**
**HTML:** For structuring content.
**CSS & Bootstrap:** For styling and responsive design.
**JavaScript:** For dynamic user interactions.
# **Backend:**
**PHP:** Server-side scripting and API handling.
# **Database:**
**MySQL:** Relational database for secure data storage.
# **Hosting:**
**Firebase Hosting:** Reliable and fast deployment for public access.

# 📂 Project Structure
```bash
Digital-E-Gram-Panchayat/
├── admin/             # Officer Dashboard
├── assets/            # Static assets (CSS, JavaScript, images)
├── auth/              # Authentication logic
├── includes/          # Database configuration and reusable backend code
├── sql/               # SQL scripts for database setup
├── staff/             # Staff-specific functionalities
├── users/             # User-specific functionalities
├── firebase.json      # Firebase hosting configuration
└── README.md          # Project documentation
```
# 🚀 How to Get Started
**System Requirements**
PHP 7.4 or higher
MySQL 5.7 or higher
Apache/Nginx web server
Browser (latest version recommended)
# Installation
**Clone the repository:**
Clone the project to your local environment using the command:

```bash
git clone https://github.com/HarshaNinganna/Digital-E-Gram-Panchayat.git
```
**Navigate to the project directory:**

```bash
cd Digital-E-Gram-Panchayat
```
**Set up the database:**
Locate the digital_e_gram_panchayat.sql file in the repository.
**Import it into your MySQL server:**
```bash
mysql -u root -p digital_e_gram_panchayat < digital_e_gram_panchayat.sql
```
**Configure database connection:**
Open config.php (or the relevant configuration file) and set the correct database credentials:

**php**
```bash
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "digital_e_gram_panchayat";
```
**Host the application locally:**
Use XAMPP, WAMP, or any PHP-supported server.
Place the project folder in the server’s root directory (e.g., htdocs for XAMPP).

**Access the application:**
Open your browser and go to:

```bash
http://localhost/Digital-E-Gram-Panchayat/
```
**Deployment (Firebase Hosting)**
**Install Firebase CLI:**
Install the Firebase CLI globally:

```bash
npm install -g firebase-tools
```
Login to Firebase:
Authenticate Firebase in your terminal:

```bash
firebase login
```
Deploy the application:

Deploy your project to Firebase Hosting:
```bash
firebase deploy --only hosting
```

```bash
firebase deploy --only hosting
```
# **🎨 Screenshots**
**Dashboard (Officer View)**
(Include a screenshot of the dashboard)

**Service Request (User View)**
(Include a screenshot of the service request form)

**Service Management (Staff View)**
(Include a screenshot of the service management page)

# **🤝 Contributing**
We welcome contributions to improve the Digital E-Gram Panchayat. To contribute:

**Fork the repository.**
Create a feature branch:
```bash
git checkout -b feature-name
```
**Commit your changes:**
```bash
git commit -m "Add a brief description of your changes"
```
**Push to your branch:**
```bash
git push origin feature-name
```
**Open a pull request.**
# **📜 License**
This project is licensed under the MIT License. See the LICENSE file for more details.

# **✨ Acknowledgments**
Gratitude to mentors and contributors for their guidance.
Appreciation to the open-source community for providing valuable tools and libraries.

# **🧑‍💻 About the Author**
Harsha N
A passionate developer with expertise in building dynamic web applications.
Explore more projects on GitHub.

Feel free to customize this further, adding images/screenshots, installation nuances, or FAQs! 🚀
