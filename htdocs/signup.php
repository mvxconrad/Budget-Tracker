<?php
    session_start();

$servername = "localhost";
$username = "admin";
$password = "Database123!";
$database = "budget_tracker";

$conn = new PDO ("mysql:host=$servername;dbname=$database", $username, $password);


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if username and password are provided
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm-password'];
        if(!str_contains($confirmPassword, $password))
        {
            header("location: 404.php");
    }
    // Argon2ID used for a more robust security
    $password_hash = password_hash($password, PASSWORD_ARGON2ID);
    $insert = $conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    $insert->bindParam(":username", $username);
    $insert->bindParam(":password", $password_hash);
    $insert->execute(); 
    header("Location: login.php");        
}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Tracker - Sign Up</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Budget Tracker</h1>
    </header>
    <main>
        <section class="signup-form">
            <h2>Sign Up</h2>
            <form id="signup-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST"> 
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm-password">Confirm Password:</label>
                    <input type="password" id="confirm-password" name="confirm-password" required>
                </div>
                <button type="submit">Sign Up</button>
            </form>
            <p>Already have an account? <a href="login.php">Login</a></p>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Budget Tracker</p>
    </footer>
</body>
</html>
```
