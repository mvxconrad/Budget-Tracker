<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "admin";
$password = "Database123!";
$database = "budget_tracker";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Budget Tracker</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1 class="welcome-header">Welcome to the Budget Tracker</h1>
        <nav>
            <ul>
                <li class="welcome-nav-item"><a href="expense.php" class="welcome-btn">Add Expenses</a></li>
                <li class="welcome-nav-item"><a href="dashboard.php" class="welcome-btn">View Dashboard</a></li>
                <li class="welcome-nav-item"><a href="logout.php" class="welcome-btn">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="welcome-info">
            <h2>How to Use Your Budget Tracker</h2>
            <p><strong>Add Expenses:</strong> Use the Add Expenses button to enter your daily expenses. This will help you manage and categorize your spending effectively.</p>
            <p><strong>View Dashboard:</strong> Use the View Dashboard button to see summaries and trends of your expenses.</p>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Budget Tracker</p>
    </footer>
</body>
</html>
