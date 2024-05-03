<?php
$servername = "localhost"; // Database server
$username = "admin"; // Database username
$password = "Database123!"; // Database password
$database = "budget_tracker"; // Database name

try {
    // Initialize the database connection
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error handling
} catch (PDOException $e) {
    // Handle connection failure
    die("Database connection failed: " . $e->getMessage());
}
