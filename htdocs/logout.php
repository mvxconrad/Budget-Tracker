<?php
session_start(); // Start the session

// Check if the user is logged in
if (empty($_SESSION["user"])) {
    header("Location: login.php"); // Redirect if session is empty
    exit(); // Ensure the script doesn't continue
}

// Unset all session variables and destroy the session
$_SESSION = array(); // Clear all session variables
session_destroy(); // Destroy the session

// Redirect to the login page
header("Location: login.php");
exit(); // Ensure redirection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logout Successful</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <script>
        // Redirect to "login.php" after 3 seconds
        setTimeout(function() {
            window.location.href = "login.php"; // Redirects to login page
        }, 3000); // 3-second delay
    </script>
</head>
<body>
    <div class="logout-message">
        <h1>You have successfully logged out.</h1>
        <p>Redirecting to the login page in 3 seconds...</p>
        <!-- Optional: Add a loading spinner or animation -->
        <div class="spinner"></div>
    </div>
</body>
</html>
