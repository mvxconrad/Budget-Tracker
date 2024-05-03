# Budget Tracker

Welcome to my Budget Tracker project! This application helps users manage their expenses by providing tools to add, categorize, and visualize their expenditures through a web-based interface.

## Prerequisites

Before you begin, you need to have XAMPP installed on your computer. XAMPP is a free and open-source cross-platform web server solution stack package developed by Apache Friends, consisting mainly of the Apache HTTP Server, MariaDB database, and interpreters for scripts written in the PHP and other programming languages.

### Installing XAMPP

1. **Download XAMPP:**
   - Visit the Apache Friends website at [https://www.apachefriends.org/download.html].
   - Select the version compatible with your operating system (Windows, Linux, or macOS).
   - Download the installer.

2. **Install XAMPP:**
   - Launch the downloaded installer.
   - Follow the on-screen instructions. Default settings should work for most users.

### Setting Up the Project

#### Step 1: Clone the Repository

Clone the repository into the `htdocs` directory of your XAMPP installation. This directory is typically found at `C:\xampp\htdocs` on Windows or `/opt/lampp/htdocs` on Linux.

```bash
cd /path/to/xampp/htdocs
git clone https://github.com/mvxconrad/Budget-Tracker/htdocs.git
```
Make sure you remove the sql file from /xampp/htdocs and move it to another location.

#### Step 2: Database Setup

1. **Start XAMPP:**
   - Open the XAMPP Control Panel.
   - Start the Apache and MySQL modules.

2. **Create Database:**
   - Open a web browser and go to `http://localhost/phpmyadmin`.
   - Click on the "Databases" tab to create a new database.
   - Name your database `budget_tracker` (or any other name you prefer) and select `utf8_general_ci` for collation.

3. **Import Database:**
   - With the database selected, navigate to the "Import" tab in phpMyAdmin.
   - Click on "Choose File" and select the SQL file provided in your project repository (`budget_tracker.sql`).
   - Press the "Go" button at the bottom of the page to import your database structures along with any initial data.

### Configuring the Application

Copy or rename the `config.example.php` file to `config.php` and update it with the correct database connection settings:

```php
<?php
// config.php
define('DB_HOST', 'localhost');
define('DB_USER', 'admin'); 
define('DB_PASS', 'Database123!'); 
define('DB_NAME', 'budget_tracker'); 
?>
```

### Running the Application

Once all configurations are done, you can access your application by opening a browser and navigating to:

```
http://localhost/budget-tracker/
```

Replace `budget-tracker` with the directory name of your project if different.

## Support

For support, please open an issue on the GitHub repository page.
