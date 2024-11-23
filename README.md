# **Digital E-Gram Panchayat 🌟**
Digital E-Gram Panchayat is a web-based application designed to simplify and streamline the management of services and records for rural governance. The system enables officers to manage citizen services effectively, ensuring transparency, accountability, and efficiency.

## **🌐 Features**
### **Service Management:**
Add, update, and delete citizen services.
Track services with detailed descriptions and categories.

### **Application Status Tracking:**
Update and manage the status of service applications in real-time.

### **Logging and Auditing:**
Maintain logs for critical actions like service deletions, along with reasons for such actions.

### **Secure Officer Login:**
Ensures that only authorized personnel have access to the system.

### **User-Friendly Interface:**
Intuitive design for easy navigation and efficient functionality.

### **🛠️ Technologies Used**
## **Frontend:**
HTML, CSS, Bootstrap
## **Backend:**
PHP, MySQL
## **Database:**
MySQL for secure data storage and management.

### **🚀 How to Get Started**
## **Prerequisites**
Ensure you have the following installed:

PHP 7.4 or above
MySQL 5.7 or above
A web server like Apache or Nginx
A browser to access the application
Installation

**Clone the repository:**

```bash
git clone https://github.com/HarshaNinganna/Digital-E-Gram-Panchayat.git
```
**Navigate to the project directory:**
```bash
cd Digital-E-Gram-Panchayat
```
**Import the database:**
```bash
Locate the digital_e_gram_panchayat.sql file in the repository.
```
**Import it into your MySQL server using a MySQL client or the command below:**
```bash
mysql -u [username] -p [database_name] < digital_e_gram_panchayat.sql
```
**Configure the database connection:**

Open the config.php file (or the relevant database connection file).
Update the credentials as needed:
```bash
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "digital_e_gram_panchayat";
```
**Start the server:** 

Use a local server environment like XAMPP, WAMP, or MAMP.
Place the project folder in the server’s root directory (e.g., htdocs for XAMPP).

**Access the application:**

**Open your browser and navigate to:**
```bash
http://localhost/Digital-E-Gram-Panchayat/
```
## **📂 Project Structure**
```bash
Digital-E-Gram-Panchayat/
├── admin/             # Main officer dashboard
├── assets/            # CSS, JS, and image assets
├── auth/              # Authentication files
├── includes/          # Database-related files
├── staff/             # Staff interface  
├── users/             # User interface  
└── README.md          # Project documentation
```
**📸 Screenshots**
**Dashboard**
(Add screenshots of your dashboard here)

**Manage Services**
(Add screenshots of the Manage Services page here)

**🤝 Contributing**
We welcome contributions to enhance this project. Follow these steps to contribute:

### **Fork the repository.**
**Create a new branch for your feature/bug fix:**
```bash
git checkout -b feature-name
```
**Commit your changes:**
```bash
git commit -m "Description of changes"
```
**Push to your branch:**
```bash
git push origin feature-name
```
**Open a pull request and describe your changes.**

**📜 License**
This project is licensed under the MIT License.

**✨ Acknowledgments**
Special thanks to the contributors and community for their valuable support.

**🧑‍💻 About the Author**
Developed by Harsha N.
Explore more projects on GitHub.
